<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast;

use function trim;

class Comment implements Node
{

	use NodeAttributes;

	public string $text;

	public function __construct(string $text)
	{
		$this->text = $text;
	}

	public function getReformattedText(): string
	{
		return trim($this->text);
	}

	public function __toString(): string
	{
		return $this->getReformattedText();
	}

}
