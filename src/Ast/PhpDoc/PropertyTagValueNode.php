<?php declare(strict_types = 1);

namespace PhpStan\TypeParser\Ast\PhpDoc;

use PhpStan\TypeParser\Ast\Type\TypeNode;


class PropertyTagValueNode implements PhpDocTagValueNode
{
	/** @var TypeNode */
	public $type;

	/** @var string */
	public $propertyName;

	/** @var string (may be empty) */
	public $description;


	public function __construct(TypeNode $type, string $parameterName, string $description)
	{
		$this->type = $type;
		$this->propertyName = $parameterName;
		$this->description = $description;
	}


	public function __toString(): string
	{
		return trim("{$this->type} {$this->propertyName} {$this->description}");
	}
}
