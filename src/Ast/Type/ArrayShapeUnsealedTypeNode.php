<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function sprintf;

class ArrayShapeUnsealedTypeNode implements Node
{

	use NodeAttributes;

	public TypeNode $valueType;

	public ?TypeNode $keyType = null;

	public function __construct(TypeNode $valueType, ?TypeNode $keyType)
	{
		$this->valueType = $valueType;
		$this->keyType = $keyType;
	}

	public function __toString(): string
	{
		if ($this->keyType !== null) {
			return sprintf('<%s, %s>', $this->keyType, $this->valueType);
		}
		return sprintf('<%s>', $this->valueType);
	}

}
