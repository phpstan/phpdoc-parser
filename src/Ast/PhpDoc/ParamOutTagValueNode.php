<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use function trim;

class ParamOutTagValueNode implements PhpDocTagValueNode
{

	use NodeAttributes;

	/** @var TypeNode */
	public $type;

	/** @var string */
	public $parameterName;

	public function __construct(TypeNode $type, string $parameterName)
	{
		$this->type = $type;
		$this->parameterName = $parameterName;
	}

	public function __toString(): string
	{
		return trim("{$this->type} {$this->parameterName}");
	}

}
