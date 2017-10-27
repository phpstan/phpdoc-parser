<?php declare(strict_types = 1);

namespace PhpStan\TypeParser\Ast;


class GenericNode implements Node
{
	/** @var IdentifierNode */
	public $type;

	/** @var Node[] */
	public $genericTypes;


	public function __construct(IdentifierNode $type, array $genericTypes)
	{
		$this->type = $type;
		$this->genericTypes = $genericTypes;
	}


	public function __toString(): string
	{
		return $this->type . '<' . implode(', ', $this->genericTypes) . '>';
	}
}
