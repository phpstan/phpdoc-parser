<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function array_map;
use function implode;

/**
 * Represents a property access expression in conditional types.
 */
class PropertyAccessNode implements Node
{

	use NodeAttributes;

	public const HOLDER_SELF = 'self';
	public const HOLDER_PARENT = 'parent';
	public const HOLDER_STATIC = 'static';

	public bool $isStatic;

	/**
	 * For static access: 'self', 'parent', or 'static'
	 * For instance access: null (holder is implicitly $this)
	 *
	 * @var self::HOLDER_*|null
	 */
	public ?string $holder;

	/** @var list<PropertyAccessPathItem> */
	public array $path;

	/**
	 * @param self::HOLDER_*|null $holder
	 * @param list<PropertyAccessPathItem> $path
	 */
	public function __construct(bool $isStatic, ?string $holder, array $path)
	{
		$this->isStatic = $isStatic;
		$this->holder = $holder;
		$this->path = $path;
	}

	public function __toString(): string
	{
		if ($this->isStatic) {
			return $this->holder . '::$' . $this->path[0]->name;
		}

		$pathString = implode('->', array_map(
			static fn (PropertyAccessPathItem $item): string => $item->name,
			$this->path,
		));

		return '$this->' . $pathString;
	}

	/**
	 * @param array<string, mixed> $properties
	 */
	public static function __set_state(array $properties): self
	{
		$instance = new self(
			$properties['isStatic'],
			$properties['holder'],
			$properties['path'],
		);
		if (isset($properties['attributes'])) {
			foreach ($properties['attributes'] as $key => $value) {
				$instance->setAttribute($key, $value);
			}
		}
		return $instance;
	}

}
