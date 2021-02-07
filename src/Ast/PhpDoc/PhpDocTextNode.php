<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\BaseNode;

class PhpDocTextNode extends BaseNode implements PhpDocChildNode
{

	/** @var string */
	public $text;

	public function __construct(string $text)
	{
		$this->text = $text;
	}


	public function __toString(): string
	{
		return $this->text;
	}

}
