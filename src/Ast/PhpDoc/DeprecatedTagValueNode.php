<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

class DeprecatedTagValueNode implements PhpDocTagValueNode
{

	/** @var string|null (may be empty) */
	public $description;

	public function __construct(?string $description = null)
	{
		$this->description = $description;
	}


	public function __toString(): string
	{
		return $this->description ? trim($this->description) : '';
	}

}
