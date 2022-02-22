<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ConstExpr;

use PHPStan\PhpDocParser\Ast\NodeAttributes;

class ConstExprStringNode implements ConstExprNode
{

	use NodeAttributes;

	/** @var string */
	public $value;

	public function __construct(string $value)
	{
		$len = strlen($value);
		if ($len >= 2 && (
			($value[0] === '"' && $value[$len-1] === '"')
			|| ($value[0] === "'" && $value[$len-1] === "'")
		)) {
			$value = substr($value, 1, -1);
		}
		// Don't go crazy with escaping
		if (strpos($value, '"') !== false) {
			$value = "'".$value."'";
		} else {
			$value = '"'.$value.'"';
		}
		$this->value = $value;
	}


	public function __toString(): string
	{
		return $this->value;
	}

}
