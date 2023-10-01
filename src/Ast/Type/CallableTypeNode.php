<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function implode;

class CallableTypeNode implements TypeNode
{

	use NodeAttributes;

	/** @var IdentifierTypeNode */
	public $identifier;

	/** @var CallableTypeTemplateNode[] */
	public $templates;

	/** @var CallableTypeParameterNode[] */
	public $parameters;

	/** @var TypeNode */
	public $returnType;

	/**
	 * @param CallableTypeParameterNode[] $parameters
	 * @param CallableTypeTemplateNode[]  $templates
	 */
	public function __construct(IdentifierTypeNode $identifier, array $parameters, TypeNode $returnType, array $templates = [])
	{
		$this->identifier = $identifier;
		$this->parameters = $parameters;
		$this->returnType = $returnType;
		$this->templates = $templates;
	}


	public function __toString(): string
	{
		$returnType = $this->returnType;
		if ($returnType instanceof self) {
			$returnType = "({$returnType})";
		}
		$template = $this->templates !== []
			? '<' . implode(', ', $this->templates) . '>'
			: '';
		$parameters = implode(', ', $this->parameters);
		return "{$this->identifier}{$template}({$parameters}): {$returnType}";
	}

}
