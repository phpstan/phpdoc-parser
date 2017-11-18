<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\Type\TypeNode;


class ParamTagValueNode implements PhpDocTagValueNode
{
	/** @var TypeNode */
	public $type;

	/** @var string (may be empty) */
	public $parameterName;

	/** @var string (may be empty) */
	public $description;


	public function __construct(TypeNode $type, string $parameterName, string $description)
	{
		$this->type = $type;
		$this->parameterName = $parameterName;
		$this->description = $description;
	}


	public function __toString(): string
	{
		return trim("{$this->type} {$this->parameterName} {$this->description}");
	}
}
