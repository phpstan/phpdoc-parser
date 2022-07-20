<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function implode;

class ObjectShapeNode implements TypeNode
{

	use NodeAttributes;

	/** @var IdentifierTypeNode $identitier */
	public $identifier;

	/** @var ShapeItemNode[] */
	public $items;

	public function __construct(IdentifierTypeNode $identifier, array $items)
	{
		$this->identifier = $identifier;
		$this->items = $items;
	}


	public function __toString(): string
	{
		return "{$this->identifier}{" . implode(', ', $this->items) . '}';
	}

}
