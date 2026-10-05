<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeAttributes;

/**
 * Represents a single property in a property access path.
 *
 * Examples:
 * - In `$this->config->database`, there are two items: 'config' and 'database'
 * - In `self::$cache`, there is one item: 'cache'
 */
class PropertyAccessPathItem implements Node
{

	use NodeAttributes;

	public string $name;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function __toString(): string
	{
		return $this->name;
	}

	/**
	 * @param array<string, mixed> $properties
	 */
	public static function __set_state(array $properties): self
	{
		$instance = new self($properties['name']);
		if (isset($properties['attributes'])) {
			foreach ($properties['attributes'] as $key => $value) {
				$instance->setAttribute($key, $value);
			}
		}
		return $instance;
	}

}
