<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;

class ConstExprParser
{

	private ParserConfig $config;

	private AstBuilder $builder;

	public function __construct(
		ParserConfig $config
	)
	{
		$this->config = $config;
		$this->builder = new AstBuilder($config);
	}

	/**
	 * @internal
	 */
	public function toDoctrine(): self
	{
		return new self($this->config);
	}

	/**
	 * @throws ParserException
	 */
	public function parse(TokenIterator $tokens): Ast\ConstExpr\ConstExprNode
	{
		/** @var Ast\ConstExpr\ConstExprNode */
		return $this->builder->read($tokens, 'ConstantExpr');
	}

	/**
	 * This method is supposed to be called with TokenIterator after reading TOKEN_DOUBLE_QUOTED_STRING and shifting
	 * to the next token.
	 */
	public function parseDoctrineString(string $text, TokenIterator $tokens): Ast\ConstExpr\DoctrineConstExprStringNode
	{
		// Because of how Lexer works, a valid Doctrine string
		// can consist of a sequence of TOKEN_DOUBLE_QUOTED_STRING and TOKEN_DOCTRINE_ANNOTATION_STRING
		while ($tokens->isCurrentTokenType(Lexer::TOKEN_DOUBLE_QUOTED_STRING, Lexer::TOKEN_DOCTRINE_ANNOTATION_STRING)) {
			$text .= $tokens->currentTokenValue();
			$tokens->next();
		}

		return new Ast\ConstExpr\DoctrineConstExprStringNode(Ast\ConstExpr\DoctrineConstExprStringNode::unescape($text));
	}

}
