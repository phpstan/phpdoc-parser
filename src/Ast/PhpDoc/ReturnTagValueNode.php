<?php declare(strict_types = 1);

namespace PhpStan\TypeParser\Ast\PhpDoc;

use PhpStan\TypeParser\Ast\Type\TypeNode;


class ReturnTagValueNode implements PhpDocTagValueNode
{
	/** @var TypeNode */
	public $type;

	/** @var string (may be empty) */
	public $description;


	public function __construct(TypeNode $type, string $description)
	{
		$this->type = $type;
		$this->description = $description;
	}


	public function __toString(): string
	{
		return trim("{$this->type} {$this->description}");
	}
}
