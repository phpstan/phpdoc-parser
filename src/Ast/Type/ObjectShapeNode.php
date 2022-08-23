<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function implode;

class ObjectShapeNode implements TypeNode
{

	use NodeAttributes;

	/** @var ObjectShapeItemNode[] */
	public $items;

	public function __construct(array $items)
	{
		$this->items = $items;
	}


	public function __toString(): string
	{
		return 'object{' . implode(', ', $this->items) . '}';
	}

}
