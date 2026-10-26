<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast;
use PHPStan\PhpDocParser\ParserConfig;

class TypeParser
{

	private AstBuilder $builder;

	// The parsers it has always been given are not needed anymore, but it is
	// constructed the way it always has been
	// @phpstan-ignore constructor.unusedParameter
	public function __construct(
		ParserConfig $config,
		ConstExprParser $constExprParser
	)
	{
		$this->builder = new AstBuilder($config);
	}

	/**
	 * Reads a type at the position the iterator is at and leaves the iterator
	 * right after it.
	 *
	 * @phpstan-impure
	 * @throws ParserException
	 */
	public function parse(TokenIterator $tokens): Ast\Type\TypeNode
	{
		/** @var Ast\Type\TypeNode */
		return $this->builder->read($tokens, 'Type');
	}

}
