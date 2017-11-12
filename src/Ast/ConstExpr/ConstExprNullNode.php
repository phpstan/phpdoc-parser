<?php declare(strict_types = 1);

namespace PhpStan\TypeParser\Ast\ConstExpr;


class ConstExprNullNode implements ConstExprNode
{
	public function __toString(): string
	{
		return 'null';
	}
}
