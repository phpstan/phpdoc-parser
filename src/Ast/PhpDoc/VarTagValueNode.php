<?php declare(strict_types = 1);

namespace PhpStan\TypeParser\Ast\PhpDoc;

use PhpStan\TypeParser\Ast\Type\TypeNode;


class VarTagValueNode implements PhpDocTagValueNode
{
	/** @var TypeNode */
	public $type;

	/** @var string (may be empty) */
	public $variableName;

	/** @var string (may be empty) */
	public $description;


	public function __construct(TypeNode $type, string $parameterName, string $description)
	{
		$this->type = $type;
		$this->variableName = $parameterName;
		$this->description = $description;
	}


	public function __toString(): string
	{
		return trim("$this->type " . trim("{$this->variableName} {$this->description}"));
	}
}
