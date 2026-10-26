<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use function strlen;

class PhpDocParser
{

	private ParserConfig $config;

	private AstBuilder $builder;

	// The parsers it has always been given are not needed anymore, but it is
	// constructed the way it always has been
	// @phpstan-ignore constructor.unusedParameter, constructor.unusedParameter
	public function __construct(
		ParserConfig $config,
		TypeParser $typeParser,
		ConstExprParser $constantExprParser
	)
	{
		$this->config = $config;
		$this->builder = new AstBuilder($config);
	}

	public function parse(TokenIterator $tokens): Ast\PhpDoc\PhpDocNode
	{
		$startIndex = $tokens->currentTokenIndex();
		$all = $tokens->getTokens();
		foreach ($all as $i => $token) {
			if ($i < $startIndex || $token[Lexer::TYPE_OFFSET] !== Lexer::TOKEN_PHPDOC_EOL) {
				continue;
			}

			$tokens->detectNewlineOf($token[Lexer::VALUE_OFFSET]);
			break;
		}

		try {
			/** @var Ast\PhpDoc\PhpDocNode */
			return $this->builder->read($tokens, 'PhpDoc');
		} catch (ParserException $e) {
			return $this->createInvalidPhpDoc($tokens, $e, $startIndex);
		}
	}

	/**
	 * A PHPDoc that cannot be read up to its end is read as a single tag that
	 * cannot be read: the one written last before the error, if any.
	 */
	private function createInvalidPhpDoc(TokenIterator $tokens, ParserException $e, int $startIndex): Ast\PhpDoc\PhpDocNode
	{
		$all = $tokens->getTokens();
		$errorIndex = $startIndex;
		$offset = 0;
		foreach ($all as $i => $token) {
			if ($offset >= $e->getCurrentOffset()) {
				$errorIndex = $i;
				break;
			}

			$offset += strlen($token[Lexer::VALUE_OFFSET]);
		}

		$name = '';
		for ($i = $errorIndex - 1; $i > $startIndex; $i--) {
			$type = $all[$i][Lexer::TYPE_OFFSET];
			if ($type === Lexer::TOKEN_PHPDOC_TAG || $type === Lexer::TOKEN_DOCTRINE_TAG) {
				$name = $all[$i][Lexer::VALUE_OFFSET];
				break;
			}
		}

		$tokens->forwardToTheEnd();

		$value = new Ast\PhpDoc\InvalidTagValueNode($e->getMessage(), $e);
		$tag = new Ast\PhpDoc\PhpDocTagNode($name, $value);
		$node = new Ast\PhpDoc\PhpDocNode([$tag]);

		$endIndex = $tokens->endIndexOfLastRelevantToken();
		$endLine = $tokens->currentTokenLine();
		foreach ([$value, $tag, $node] as $item) {
			$isPhpDoc = $item === $node;
			if ($this->config->useLinesAttributes) {
				$item->setAttribute(Ast\Attribute::START_LINE, $isPhpDoc ? $all[$startIndex][Lexer::LINE_OFFSET] : $all[$errorIndex][Lexer::LINE_OFFSET]);
				$item->setAttribute(Ast\Attribute::END_LINE, $endLine);
			}

			if (!$this->config->useIndexAttributes) {
				continue;
			}

			$item->setAttribute(Ast\Attribute::START_INDEX, $isPhpDoc ? $startIndex : $errorIndex);
			$item->setAttribute(Ast\Attribute::END_INDEX, $endIndex);
		}

		return $node;
	}

	/**
	 * Reads the tag at the position the iterator is at, along with its value.
	 */
	public function parseTag(TokenIterator $tokens): Ast\PhpDoc\PhpDocTagNode
	{
		/** @var Ast\PhpDoc\PhpDocTagNode */
		return $this->builder->read(
			$tokens,
			$tokens->isCurrentTokenType(Lexer::TOKEN_DOCTRINE_TAG) || !isset(Phplrt\TokenStream::TAGS[$tokens->currentTokenValue()])
				? 'UnreadTagChild'
				: 'PhpDocChild',
		);
	}

	/**
	 * Reads the value of the given tag at the position the iterator is at.
	 */
	public function parseTagValue(TokenIterator $tokens, string $tag): Ast\PhpDoc\PhpDocTagValueNode
	{
		/** @var Ast\PhpDoc\PhpDocTagNode $node */
		$node = $this->builder->read(
			$tokens,
			isset(Phplrt\TokenStream::TAGS[$tag]) ? 'PhpDocChild' : 'UnreadTagChild',
			$tag,
		);

		return $node->value;
	}

}
