<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use function implode;
use function trim;

class TypeAliasTagValueNode implements PhpDocTagValueNode
{

	use NodeAttributes;

	public string $alias;

	public TypeNode $type;

	/** @var TemplateTagValueNode[] */
	public array $templateTypes;

	/**
	 * @param TemplateTagValueNode[] $templateTypes
	 */
	public function __construct(string $alias, TypeNode $type, array $templateTypes = [])
	{
		$this->alias = $alias;
		$this->type = $type;
		$this->templateTypes = $templateTypes;
	}

	public function __toString(): string
	{
		$templateTypes = $this->templateTypes !== []
			? '<' . implode(', ', $this->templateTypes) . '>'
			: '';
		return trim("{$this->alias}{$templateTypes} {$this->type}");
	}

	/**
	 * @param array<string, mixed> $properties
	 */
	public static function __set_state(array $properties): self
	{
		$instance = new self($properties['alias'], $properties['type'], $properties['templateTypes'] ?? []);
		if (isset($properties['attributes'])) {
			foreach ($properties['attributes'] as $key => $value) {
				$instance->setAttribute($key, $value);
			}
		}
		return $instance;
	}

}
