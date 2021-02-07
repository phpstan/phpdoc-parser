<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\BaseNode;

class ThisTypeNode extends BaseNode implements TypeNode
{

	public function __toString(): string
	{
		return '$this';
	}

}
