<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use function trim;

class AssertTagValueNode implements PhpDocTagValueNode
{

	use NodeAttributes;

	public TypeNode $type;

	public string $parameter;

	public bool $isNegated;

	public bool $isEquality;

	/** @var string (may be empty) */
	public string $description;

	public function __construct(TypeNode $type, string $parameter, bool $isNegated, string $description, bool $isEquality)
	{
		$this->type = $type;
		$this->parameter = $parameter;
		$this->isNegated = $isNegated;
		$this->isEquality = $isEquality;
		$this->description = $description;
	}


	public function __toString(): string
	{
		$isNegated = $this->isNegated ? '!' : '';
		$isEquality = $this->isEquality ? '=' : '';
		return trim("{$isNegated}{$isEquality}{$this->type} {$this->parameter} {$this->description}");
	}

}
