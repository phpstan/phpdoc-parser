<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function array_column;
use function array_filter;
use function array_map;
use function implode;

class PhpDocNode implements Node
{

	use NodeAttributes;

	/** @var PhpDocChildNode[] */
	public array $children;

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
		return array_filter($this->children, static fn (PhpDocChildNode $child): bool => $child instanceof PhpDocTagNode);
	}


	/**
	 * @return PhpDocTagNode[]
	 */
	public function getTagsByName(string $tagName): array
	{
		return array_filter($this->getTags(), static fn (PhpDocTagNode $tag): bool => $tag->name === $tagName);
	}


	/**
	 * @return VarTagValueNode[]
	 */
	public function getVarTagValues(string $tagName = '@var'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof VarTagValueNode,
		);
	}


	/**
	 * @return ParamTagValueNode[]
	 */
	public function getParamTagValues(string $tagName = '@param'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof ParamTagValueNode,
		);
	}


	/**
	 * @return TypelessParamTagValueNode[]
	 */
	public function getTypelessParamTagValues(string $tagName = '@param'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof TypelessParamTagValueNode,
		);
	}


	/**
	 * @return ParamImmediatelyInvokedCallableTagValueNode[]
	 */
	public function getParamImmediatelyInvokedCallableTagValues(string $tagName = '@param-immediately-invoked-callable'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof ParamImmediatelyInvokedCallableTagValueNode,
		);
	}


	/**
	 * @return ParamLaterInvokedCallableTagValueNode[]
	 */
	public function getParamLaterInvokedCallableTagValues(string $tagName = '@param-later-invoked-callable'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof ParamLaterInvokedCallableTagValueNode,
		);
	}


	/**
	 * @return ParamClosureThisTagValueNode[]
	 */
	public function getParamClosureThisTagValues(string $tagName = '@param-closure-this'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof ParamClosureThisTagValueNode,
		);
	}

	/**
	 * @return PureUnlessCallableIsImpureTagValueNode[]
	 */
	public function getPureUnlessCallableIsImpureTagValues(string $tagName = '@pure-unless-callable-is-impure'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof PureUnlessCallableIsImpureTagValueNode,
		);
	}

	/**
	 * @return TemplateTagValueNode[]
	 */
	public function getTemplateTagValues(string $tagName = '@template'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof TemplateTagValueNode,
		);
	}


	/**
	 * @return ExtendsTagValueNode[]
	 */
	public function getExtendsTagValues(string $tagName = '@extends'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof ExtendsTagValueNode,
		);
	}


	/**
	 * @return ImplementsTagValueNode[]
	 */
	public function getImplementsTagValues(string $tagName = '@implements'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof ImplementsTagValueNode,
		);
	}


	/**
	 * @return UsesTagValueNode[]
	 */
	public function getUsesTagValues(string $tagName = '@use'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof UsesTagValueNode,
		);
	}


	/**
	 * @return ReturnTagValueNode[]
	 */
	public function getReturnTagValues(string $tagName = '@return'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof ReturnTagValueNode,
		);
	}


	/**
	 * @return ThrowsTagValueNode[]
	 */
	public function getThrowsTagValues(string $tagName = '@throws'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof ThrowsTagValueNode,
		);
	}


	/**
	 * @return MixinTagValueNode[]
	 */
	public function getMixinTagValues(string $tagName = '@mixin'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof MixinTagValueNode,
		);
	}

	/**
	 * @return RequireExtendsTagValueNode[]
	 */
	public function getRequireExtendsTagValues(string $tagName = '@phpstan-require-extends'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof RequireExtendsTagValueNode,
		);
	}

	/**
	 * @return RequireImplementsTagValueNode[]
	 */
	public function getRequireImplementsTagValues(string $tagName = '@phpstan-require-implements'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof RequireImplementsTagValueNode,
		);
	}

	/**
	 * @return DeprecatedTagValueNode[]
	 */
	public function getDeprecatedTagValues(): array
	{
		return array_filter(
			array_column($this->getTagsByName('@deprecated'), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof DeprecatedTagValueNode,
		);
	}


	/**
	 * @return PropertyTagValueNode[]
	 */
	public function getPropertyTagValues(string $tagName = '@property'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof PropertyTagValueNode,
		);
	}


	/**
	 * @return PropertyTagValueNode[]
	 */
	public function getPropertyReadTagValues(string $tagName = '@property-read'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof PropertyTagValueNode,
		);
	}


	/**
	 * @return PropertyTagValueNode[]
	 */
	public function getPropertyWriteTagValues(string $tagName = '@property-write'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof PropertyTagValueNode,
		);
	}


	/**
	 * @return MethodTagValueNode[]
	 */
	public function getMethodTagValues(string $tagName = '@method'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof MethodTagValueNode,
		);
	}


	/**
	 * @return TypeAliasTagValueNode[]
	 */
	public function getTypeAliasTagValues(string $tagName = '@phpstan-type'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof TypeAliasTagValueNode,
		);
	}


	/**
	 * @return TypeAliasImportTagValueNode[]
	 */
	public function getTypeAliasImportTagValues(string $tagName = '@phpstan-import-type'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof TypeAliasImportTagValueNode,
		);
	}


	/**
	 * @return AssertTagValueNode[]
	 */
	public function getAssertTagValues(string $tagName = '@phpstan-assert'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof AssertTagValueNode,
		);
	}


	/**
	 * @return AssertTagPropertyValueNode[]
	 */
	public function getAssertPropertyTagValues(string $tagName = '@phpstan-assert'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof AssertTagPropertyValueNode,
		);
	}


	/**
	 * @return AssertTagMethodValueNode[]
	 */
	public function getAssertMethodTagValues(string $tagName = '@phpstan-assert'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof AssertTagMethodValueNode,
		);
	}


	/**
	 * @return SelfOutTagValueNode[]
	 */
	public function getSelfOutTypeTagValues(string $tagName = '@phpstan-this-out'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof SelfOutTagValueNode,
		);
	}


	/**
	 * @return ParamOutTagValueNode[]
	 */
	public function getParamOutTypeTagValues(string $tagName = '@param-out'): array
	{
		return array_filter(
			array_column($this->getTagsByName($tagName), 'value'),
			static fn (PhpDocTagValueNode $value): bool => $value instanceof ParamOutTagValueNode,
		);
	}


	public function __toString(): string
	{
		$children = array_map(
			static function (PhpDocChildNode $child): string {
				$s = (string) $child;
				return $s === '' ? '' : ' ' . $s;
			},
			$this->children,
		);
		return "/**\n *" . implode("\n *", $children) . "\n */";
	}

}
