<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function sprintf;

class ConditionalTypeNode implements TypeNode
{

	use NodeAttributes;

	/** @var TypeNode */
	public $subjectType;

	/** @var TypeNode */
	public $targetType;

	/** @var TypeNode */
	public $trueType;

	/** @var TypeNode */
	public $falseType;

	/** @var bool */
	public $negated;

	public function __construct(TypeNode $subjectType, TypeNode $targetType, TypeNode $trueType, TypeNode $falseType, bool $negated)
	{
		$this->subjectType = $subjectType;
		$this->targetType = $targetType;
		$this->trueType = $trueType;
		$this->falseType = $falseType;
		$this->negated = $negated;
	}

	public function __toString(): string
	{
		return sprintf(
			'%s %s %s ? %s : %s',
			$this->subjectType,
			$this->negated ? 'is not' : 'is',
			$this->targetType,
			$this->trueType,
			$this->falseType
		);
	}

}
