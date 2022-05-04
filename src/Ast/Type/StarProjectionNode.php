<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;

final class StarProjectionNode implements TypeNode
{

	use NodeAttributes;

	public function __toString(): string
	{
		return '*';
	}

}
