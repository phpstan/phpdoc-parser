<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeAttributes;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;

class MethodTagValueGenericNode implements Node
{

	use NodeAttributes;

	/** @var string */
	public $name;

	/** @var TypeNode|null */
	public $bound;

	public function __construct(string $name, ?TypeNode $bound)
	{
		$this->name = $name;
		$this->bound = $bound;
	}


	public function __toString(): string
	{
		$bound = $this->bound !== null ? " of {$this->bound}" : '';
		return trim("{$this->name}{$bound}");
	}

}
