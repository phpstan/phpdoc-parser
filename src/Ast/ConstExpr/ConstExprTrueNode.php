<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ConstExpr;

use PHPStan\PhpDocParser\Ast\BaseNode;

class ConstExprTrueNode extends BaseNode implements ConstExprNode
{

	public function __toString(): string
	{
		return 'true';
	}

}
