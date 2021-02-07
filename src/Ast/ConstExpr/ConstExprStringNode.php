<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ConstExpr;

use PHPStan\PhpDocParser\Ast\BaseNode;

class ConstExprStringNode extends BaseNode implements ConstExprNode
{

	/** @var string */
	public $value;

	public function __construct(string $value)
	{
		$this->value = $value;
	}


	public function __toString(): string
	{
		return $this->value;
	}

}
