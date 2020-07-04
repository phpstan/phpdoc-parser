<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\Type\TypeNode;

class FriendTagValueNode implements PhpDocTagValueNode
{

	/** @var TypeNode */
	public $type;

	/** @var string|null */
	public $method;

	public function __construct(TypeNode $type, ?string $method)
	{
		$this->type = $type;
		$this->method = $method;
	}

	public function __toString(): string
	{
		return $this->method === null ? "{$this->type}" : "{$this->type}::{$this->method}";
	}

}
