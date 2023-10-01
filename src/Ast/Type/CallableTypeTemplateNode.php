<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeAttributes;

class CallableTypeTemplateNode implements Node
{

	use NodeAttributes;

	/** @var IdentifierTypeNode */
	public $identifier;

	/** @var TypeNode|null */
	public $bound;

	public function __construct(IdentifierTypeNode $identifier, ?TypeNode $bound)
	{
		$this->identifier = $identifier;
		$this->bound = $bound;
	}

	public function __toString(): string
	{
		$res = (string) $this->identifier;
		if ($this->bound !== null) {
			$res .= ' of ' . $this->bound;
		}

		return $res;
	}

}
