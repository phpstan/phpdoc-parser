<?php declare(strict_types = 1);

namespace PhpStan\TypeParser\Ast;


class SimpleNode implements Node
{
	/** @var string */
	public $name;


	public function __construct(string $name)
	{
		$this->name = $name;
	}


	public function __toString(): string
	{
		return $this->name;
	}
}
