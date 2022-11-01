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

	/** @var MethodTagValueGenericNode[] */
	public $generics;

	/** @var MethodTagValueParameterNode[] */
	public $parameters;

	/** @var string (may be empty) */
	public $description;

	public function __construct(bool $isStatic, ?TypeNode $returnType, string $methodName, array $generics, array $parameters, string $description)
	{
		$this->isStatic = $isStatic;
		$this->returnType = $returnType;
		$this->methodName = $methodName;
		$this->generics = $generics;
		$this->parameters = $parameters;
		$this->description = $description;
	}


	public function __toString(): string
	{
		$static = $this->isStatic ? 'static ' : '';
		$returnType = $this->returnType !== null ? "{$this->returnType} " : '';
		$generics = count($this->generics) > 0 ? '<' . implode(', ', $this->generics) . '>' : '';
		$parameters = implode(', ', $this->parameters);
		$description = $this->description !== '' ? " {$this->description}" : '';
		return "{$static}{$returnType}{$this->methodName}{$generics}({$parameters}){$description}";
	}

}
