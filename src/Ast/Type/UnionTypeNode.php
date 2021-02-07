<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\BaseNode;

class UnionTypeNode extends BaseNode implements TypeNode
{

	/** @var TypeNode[] */
	public $types;

	public function __construct(array $types)
	{
		$this->types = $types;
	}


	public function __toString(): string
	{
		return '(' . implode(' | ', $this->types) . ')';
	}

}
