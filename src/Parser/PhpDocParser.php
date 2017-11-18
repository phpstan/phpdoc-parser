<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast;
use PHPStan\PhpDocParser\Lexer\Lexer;

class PhpDocParser
{

	/** @var TypeParser */
	private $typeParser;

	/** @var ConstExprParser */
	private $constantExprParser;

	public function __construct(TypeParser $typeParser, ConstExprParser $constantExprParser)
	{
		$this->typeParser = $typeParser;
		$this->constantExprParser = $constantExprParser;
	}


	public function parse(TokenIterator $tokens): Ast\PhpDoc\PhpDocNode
	{
		$textNode = '';
		$children = [];
		$tokens->consumeTokenType(Lexer::TOKEN_OPEN_PHPDOC);

		if ($tokens->tryConsumeHorizontalWhiteSpace()) {
			$textNode .= $tokens->prevTokenValue();
		}

		while (!$tokens->tryConsumeTokenType(Lexer::TOKEN_CLOSE_PHPDOC)) {
			if ($tokens->isCurrentTokenType(Lexer::TOKEN_PHPDOC_TAG)) {
				if ($textNode !== '') {
					$children[] = new Ast\PhpDoc\PhpDocTextNode($textNode);
					$textNode = '';
				}
				$children[] = $this->parseTag($tokens);

			} else {
				$textNode .= $tokens->currentTokenValue();
				$tokens->next();
			}

			if ($tokens->tryConsumeHorizontalWhiteSpace()) {
				$textNode .= $tokens->prevTokenValue();
			}
		}

		if ($textNode !== '') {
			$children[] = new Ast\PhpDoc\PhpDocTextNode($textNode);
		}

		return new Ast\PhpDoc\PhpDocNode($children);
	}


	public function parseTag(TokenIterator $tokens): Ast\PhpDoc\PhpDocTagNode
	{
		$tag = $tokens->currentTokenValue();
		$tokens->next();
		$value = $this->parseTagValue($tokens, $tag);

		return new Ast\PhpDoc\PhpDocTagNode($tag, $value);
	}


	private function parseTagValue(TokenIterator $tokens, string $tag): Ast\PhpDoc\PhpDocTagValueNode
	{
		try {
			$tokens->pushSavePoint();

			switch ($tag) {
				case '@param':
					$tagValue = $this->parseParamTagValue($tokens);
					break;

				case '@var':
					$tagValue = $this->parseVarTagValue($tokens);
					break;

				case '@return':
				case '@returns':
					$tagValue = $this->parseReturnTagValue($tokens);
					break;

				case '@property':
				case '@property-read':
				case '@property-write':
					$tagValue = $this->parsePropertyTagValue($tokens);
					break;

				case '@method':
					$tagValue = $this->parseMethodTagValue($tokens);
					break;

				default:
					$tagValue = new Ast\PhpDoc\GenericTagValueNode($this->parseOptionalDescription($tokens));
					break;
			}

			$tokens->dropSavePoint();

		} catch (ParserException $e) {
			$tokens->rollback();
			$tagValue = new Ast\PhpDoc\InvalidTagValueNode($this->parseOptionalDescription($tokens), $e);
		}

		return $tagValue;
	}


	private function parseParamTagValue(TokenIterator $tokens): Ast\PhpDoc\ParamTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$parameterName = $this->parseOptionalVariableName($tokens);
		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\ParamTagValueNode($type, $parameterName, $description);
	}


	private function parseVarTagValue(TokenIterator $tokens): Ast\PhpDoc\VarTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$parameterName = $this->parseOptionalVariableName($tokens);
		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\VarTagValueNode($type, $parameterName, $description);
	}


	private function parseReturnTagValue(TokenIterator $tokens): Ast\PhpDoc\ReturnTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\ReturnTagValueNode($type, $description);
	}


	private function parsePropertyTagValue(TokenIterator $tokens): Ast\PhpDoc\PropertyTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$parameterName = $this->parseRequiredVariableName($tokens);
		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\PropertyTagValueNode($type, $parameterName, $description);
	}


	private function parseMethodTagValue(TokenIterator $tokens): Ast\PhpDoc\MethodTagValueNode
	{
		$isStatic = $tokens->tryConsumeTokenValue('static');
		$returnTypeOrMethodName = $this->typeParser->parse($tokens);

		if ($tokens->isCurrentTokenType(Lexer::TOKEN_IDENTIFIER)) {
			$returnType = $returnTypeOrMethodName;
			$methodName = $tokens->currentTokenValue();
			$tokens->next();

		} elseif ($returnTypeOrMethodName instanceof Ast\Type\IdentifierTypeNode) {
			$returnType = null;
			$methodName = $returnTypeOrMethodName->name;

		} else {
			$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER); // will throw exception
			exit;
		}

		$parameters = [];
		if ($tokens->tryConsumeTokenType(Lexer::TOKEN_OPEN_PARENTHESES)) {
			if (!$tokens->tryConsumeTokenType(Lexer::TOKEN_CLOSE_PARENTHESES)) {
				$parameters[] = $this->parseMethodTagValueParameter($tokens);
				while ($tokens->tryConsumeTokenType(Lexer::TOKEN_COMMA)) {
					$parameters[] = $this->parseMethodTagValueParameter($tokens);
				}
				$tokens->consumeTokenType(Lexer::TOKEN_CLOSE_PARENTHESES);
			}
		}

		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\MethodTagValueNode($isStatic, $returnType, $methodName, $parameters, $description);
	}


	private function parseMethodTagValueParameter(TokenIterator $tokens): Ast\PhpDoc\MethodTagValueParameterNode
	{
		switch ($tokens->currentTokenType()) {
			case Lexer::TOKEN_IDENTIFIER:
			case Lexer::TOKEN_OPEN_PARENTHESES:
			case Lexer::TOKEN_NULLABLE:
				$parameterType = $this->typeParser->parse($tokens);
				break;

			default:
				$parameterType = null;
		}

		$isReference = $tokens->tryConsumeTokenType(Lexer::TOKEN_REFERENCE);
		$isVariadic = $tokens->tryConsumeTokenType(Lexer::TOKEN_VARIADIC);

		$parameterName = $tokens->currentTokenValue();
		$tokens->consumeTokenType(Lexer::TOKEN_VARIABLE);

		if ($tokens->tryConsumeTokenType(Lexer::TOKEN_EQUAL)) {
			$defaultValue = $this->constantExprParser->parse($tokens);

		} else {
			$defaultValue = null;
		}

		return new Ast\PhpDoc\MethodTagValueParameterNode($parameterType, $isReference, $isVariadic, $parameterName, $defaultValue);
	}


	private function parseOptionalVariableName(TokenIterator $tokens): string
	{
		if ($tokens->tryConsumeHorizontalWhiteSpace() && $tokens->isCurrentTokenType(Lexer::TOKEN_VARIABLE)) {
			$parameterName = $tokens->currentTokenValue();
			$tokens->next();

		} else {
			$parameterName = '';
		}

		return $parameterName;
	}

	private function parseRequiredVariableName(TokenIterator $tokens): string
	{
		$tokens->tryConsumeHorizontalWhiteSpace();
		$parameterName = $tokens->currentTokenValue();
		$tokens->consumeTokenType(Lexer::TOKEN_VARIABLE);

		return $parameterName;
	}


	private function parseOptionalDescription(TokenIterator $tokens): string
	{
		// description MUST separated from any previous node by horizontal whitespace
		if ($tokens->tryConsumeHorizontalWhiteSpace()) {
			return $tokens->joinUntil(Lexer::TOKEN_EOL, Lexer::TOKEN_PHPDOC_TAG, Lexer::TOKEN_CLOSE_PHPDOC);
		}

		return '';
	}

}
