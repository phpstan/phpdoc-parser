<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\BaseNode;

class CallableTypeNode extends BaseNode implements TypeNode
{

	/** @var IdentifierTypeNode */
	public $identifier;

	/** @var CallableTypeParameterNode[] */
	public $parameters;

	/** @var TypeNode */
	public $returnType;

	public function __construct(IdentifierTypeNode $identifier, array $parameters, TypeNode $returnType)
	{
		$this->identifier = $identifier;
		$this->parameters = $parameters;
		$this->returnType = $returnType;
	}


	public function __toString(): string
	{
		$parameters = implode(', ', $this->parameters);
		return "{$this->identifier}({$parameters}): {$this->returnType}";
	}

}
