<?php declare(strict_types = 1);

namespace PhpStan\TypeParser\Ast\PhpDoc;

use PhpStan\TypeParser\Ast\Node;


class PhpDocNode implements Node
{
	/** @var PhpDocChildNode[] */
	public $children;


	/**
	 * @param PhpDocChildNode[] $children
	 */
	public function __construct(array $children)
	{
		$this->children = $children;
	}


	/**
	 * @return PhpDocTagNode[]
	 */
	public function getTags(): array
	{
		return array_filter($this->children, function (PhpDocChildNode $child): bool {
			return $child instanceof PhpDocTagNode;
		});
	}


	/**
	 * @return PhpDocTagNode[]
	 */
	public function getTagsByName(string $tagName): array
	{
		return array_filter($this->getTags(), function (PhpDocTagNode $tag) use ($tagName): bool {
			return $tag->name === $tagName;
		});
	}


	public function __toString(): string
	{
		return '/**' . implode('', $this->children) . "*/";
	}
}
