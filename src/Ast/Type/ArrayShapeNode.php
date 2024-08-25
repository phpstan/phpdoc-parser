<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function implode;

class ArrayShapeNode implements TypeNode
{

	public const KIND_ARRAY = 'array';
	public const KIND_LIST = 'list';

	use NodeAttributes;

	/** @var ArrayShapeItemNode[] */
	public $items;

	/** @var bool */
	public $sealed;

	/** @var self::KIND_* */
	public $kind;

	/** @var TypeNode|null */
	public $extraKeyType;

	/** @var TypeNode|null */
	public $extraValueType;

	/**
	 * @param ArrayShapeItemNode[] $items
	 * @param self::KIND_* $kind
	 */
	public function __construct(
		array $items,
		bool $sealed = true,
		string $kind = self::KIND_ARRAY,
		?TypeNode $extraKeyType = null,
		?TypeNode $extraValueType = null
	)
	{
		$this->items = $items;
		$this->sealed = $sealed;
		$this->kind = $kind;
		$this->extraKeyType = $extraKeyType;
		$this->extraValueType = $extraValueType;
	}


	public function __toString(): string
	{
		$items = $this->items;

		if (! $this->sealed) {
			$item = '...';
			if ($this->extraValueType !== null) {
				$extraTypes = [];
				if ($this->extraKeyType !== null) {
					$extraTypes[] = (string) $this->extraKeyType;
				}
				$extraTypes[] = (string) $this->extraValueType;
				$item .= '<' . implode(', ', $extraTypes) . '>';
			}
			$items[] = $item;
		}

		return $this->kind . '{' . implode(', ', $items) . '}';
	}

}
