<?php declare(strict_types = 1);

namespace PhpStan\TypeParser\Ast;


class GenericNode implements Node
{
	/** @var SimpleNode */
	public $type;

	/** @var Node[] */
	public $genericTypes;


	public function __construct(SimpleNode $type, array $genericTypes)
	{
		$this->type = $type;
		$this->genericTypes = $genericTypes;
	}


	public function __toString(): string
	{
		return $this->type . '<' . implode(', ', $this->genericTypes) . '>';
	}
}
