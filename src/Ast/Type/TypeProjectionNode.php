<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;

class TypeProjectionNode implements TypeNode
{

	use NodeAttributes;

	/** @var TypeNode */
	public $type;

	/** @var 'covariant'|'contravariant' */
	public $variance;

	/**
	 * @param 'covariant'|'contravariant' $variance
	 */
	public function __construct(TypeNode $type, string $variance)
	{
		$this->type = $type;
		$this->variance = $variance;
	}


	public function __toString(): string
	{
		return $this->variance . ' ' . $this->type;
	}

}
