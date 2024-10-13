<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;

class SubtractionTypeNode implements TypeNode
{

	use NodeAttributes;

	/** @var TypeNode */
	public $type;

	/** @var TypeNode */
	public $subtractedType;

	public function __construct(TypeNode $type, TypeNode $subtractedType)
	{
		$this->type = $type;
		$this->subtractedType = $subtractedType;
	}


	public function __toString(): string
	{
		return $this->type . '~' . $this->subtractedType;
	}

}
