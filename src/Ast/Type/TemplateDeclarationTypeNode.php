<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;

/**
 * A template declared in a generic type argument, like the "T of Foo" in
 * class-string-map<T of Foo, T> or the "K of key-of<Config>" in
 * array<K of key-of<Config>, Config[K]>.
 *
 * The template is scoped to the generic type it is declared in. The parser reads
 * it in the arguments of every generic type; which types give it a meaning is up
 * to the tool resolving the types.
 */
class TemplateDeclarationTypeNode implements TypeNode
{

	use NodeAttributes;

	public string $name;

	public TypeNode $bound;

	public function __construct(string $name, TypeNode $bound)
	{
		$this->name = $name;
		$this->bound = $bound;
	}

	public function __toString(): string
	{
		return "{$this->name} of {$this->bound}";
	}

	/**
	 * @param array<string, mixed> $properties
	 */
	public static function __set_state(array $properties): self
	{
		$instance = new self($properties['name'], $properties['bound']);
		if (isset($properties['attributes'])) {
			foreach ($properties['attributes'] as $key => $value) {
				$instance->setAttribute($key, $value);
			}
		}
		return $instance;
	}

}
