<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\QuoteAwareConstExprStringNode;
use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function sprintf;

class ObjectShapeItemNode implements TypeNode
{

	use NodeAttributes;

	/** @var QuoteAwareConstExprStringNode|ConstExprStringNode|IdentifierTypeNode */
	public $keyName;

	/** @var bool */
	public $optional;

	/** @var TypeNode */
	public $valueType;

	/**
	 * @param QuoteAwareConstExprStringNode|ConstExprStringNode|IdentifierTypeNode $keyName
	 */
	public function __construct($keyName, bool $optional, TypeNode $valueType)
	{
		$this->keyName = $keyName;
		$this->optional = $optional;
		$this->valueType = $valueType;
	}


	public function __toString(): string
	{
		if ($this->keyName !== null) {
			return sprintf(
				'%s%s: %s',
				(string) $this->keyName,
				$this->optional ? '?' : '',
				(string) $this->valueType
			);
		}

		return (string) $this->valueType;
	}

}
