<?php declare(strict_types = 1);

namespace PhpStan\TypeParser\Ast;


class UnionNode implements Node
{
	/** @var Node[] */
	public $types;


	public function __construct(array $types)
	{
		$this->types = $types;
	}


	public function __toString(): string
	{
		return '(' . implode(' | ', $this->types) . ')';
	}
}
