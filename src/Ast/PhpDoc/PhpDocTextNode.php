<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\NodeAttributes;

class PhpDocTextNode implements PhpDocChildNode
{

	use NodeAttributes;

	public string $text;

	/** @var list<PhpDocInlineTagNode> */
	public array $inlineTags;

	/**
	 * @param list<PhpDocInlineTagNode> $inlineTags
	 */
	public function __construct(string $text, array $inlineTags = [])
	{
		$this->text = $text;
		$this->inlineTags = $inlineTags;
	}

	public function __toString(): string
	{
		return $this->text;
	}

	/**
	 * @param array<string, mixed> $properties
	 */
	public static function __set_state(array $properties): self
	{
		$instance = new self($properties['text'], $properties['inlineTags'] ?? []);
		if (isset($properties['attributes'])) {
			foreach ($properties['attributes'] as $key => $value) {
				$instance->setAttribute($key, $value);
			}
		}
		return $instance;
	}

}
