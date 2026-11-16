<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use function sprintf;

/**
 * A class-string-map whose key template is declared with a bound, like
 * class-string-map<T of Foo, T>.
 *
 * Without a bound, class-string-map<T, V> is read as a GenericTypeNode.
 */
class ClassStringMapTypeNode implements TypeNode
{

	use NodeAttributes;

	public IdentifierTypeNode $type;

	public TemplateTagValueNode $template;

	public TypeNode $valueType;

	public function __construct(IdentifierTypeNode $type, TemplateTagValueNode $template, TypeNode $valueType)
	{
		$this->type = $type;
		$this->template = $template;
		$this->valueType = $valueType;
	}

	public function __toString(): string
	{
		return sprintf('%s<%s, %s>', $this->type, $this->template, $this->valueType);
	}

	/**
	 * @param array<string, mixed> $properties
	 */
	public static function __set_state(array $properties): self
	{
		$instance = new self($properties['type'], $properties['template'], $properties['valueType']);
		if (isset($properties['attributes'])) {
			foreach ($properties['attributes'] as $key => $value) {
				$instance->setAttribute($key, $value);
			}
		}
		return $instance;
	}

}
