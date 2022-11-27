<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use function implode;

class MethodTagValueNode implements PhpDocTagValueNode
{

	use NodeAttributes;

	/** @var bool */
	public $isStatic;

	/** @var TypeNode|null */
	public $returnType;

	/** @var string */
	public $methodName;

	/** @var MethodTagValueParameterNode[] */
	public $parameters;

	/** @var string (may be empty) */
	public $description;

	/** @var bool */
	public $isByReference;

	public function __construct(bool $isStatic, ?TypeNode $returnType, string $methodName, array $parameters, string $description, bool $isByReference = false)
	{
		$this->isStatic = $isStatic;
		$this->returnType = $returnType;
		$this->methodName = $methodName;
		$this->parameters = $parameters;
		$this->description = $description;
		$this->isByReference = $isByReference;
	}


	public function __toString(): string
	{
		$static = $this->isStatic ? 'static ' : '';
		$returnType = $this->returnType !== null ? "{$this->returnType} " : '';
		$parameters = implode(', ', $this->parameters);
		$description = $this->description !== '' ? " {$this->description}" : '';
		$reference = $this->isByReference === true ? '&' : '';
		return "{$static}{$returnType}{$reference}{$this->methodName}({$parameters}){$description}";
	}

}
