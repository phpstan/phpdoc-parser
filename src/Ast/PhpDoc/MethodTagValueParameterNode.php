<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;

class MethodTagValueParameterNode implements Node
{

	/** @var null|TypeNode */
	public $type;

	/** @var bool */
	public $isReference;

	/** @var bool */
	public $isVariadic;

	/** @var string */
	public $parameterName;

	/** @var null|ConstExprNode */
	public $defaultValue;

	public function __construct(TypeNode $type = null, bool $isReference, bool $isVariadic, string $parameterName, ConstExprNode $defaultValue = null)
	{
		$this->type = $type;
		$this->isReference = $isReference;
		$this->isVariadic = $isVariadic;
		$this->parameterName = $parameterName;
		$this->defaultValue = $defaultValue;
	}


	public function __toString(): string
	{
		$type = $this->type ? "{$this->type} " : '';
		$isReference = $this->isReference ? '&' : '';
		$isVariadic = $this->isVariadic ? '...' : '';
		$default = $this->defaultValue ? " = {$this->defaultValue}" : '';
		return "{$type}{$isReference}{$isVariadic}{$this->parameterName}{$default}";
	}

}
