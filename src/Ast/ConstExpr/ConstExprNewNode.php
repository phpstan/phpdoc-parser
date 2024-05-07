<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ConstExpr;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function implode;

class ConstExprNewNode implements ConstExprNode
{

	use NodeAttributes;

	/** @var string */
	public $class;

	/** @var ConstExprNode[] */
	public $arguments;

	/**
	 * @param ConstExprNode[] $arguments
	 */
	public function __construct(string $class, array $arguments)
	{
		$this->class = $class;
		$this->arguments = $arguments;
	}


	public function __toString(): string
	{
		return 'new ' . $this->class . '(' . implode(', ', $this->arguments) . ')';
	}

}
