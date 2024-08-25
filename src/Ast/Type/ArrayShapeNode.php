<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use InvalidArgumentException;
use PHPStan\PhpDocParser\Ast\NodeAttributes;
use function implode;
use function strlen;
use function substr;

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

	/** @var GenericTypeNode|null */
	public $extraItemType;

	/**
	 * @param ArrayShapeItemNode[] $items
	 * @param self::KIND_* $kind
	 */
	public function __construct(
		array $items,
		bool $sealed = true,
		string $kind = self::KIND_ARRAY,
		?GenericTypeNode $extraItemType = null
	)
	{
		$this->items = $items;
		$this->sealed = $sealed;
		$this->kind = $kind;
		$this->extraItemType = $extraItemType;
		if ($sealed && $extraItemType !== null) {
			throw new InvalidArgumentException('An extra item type may only be set for an unsealed array shape');
		}
	}


	public function __toString(): string
	{
		$items = $this->items;

		if (! $this->sealed) {
			$item = '...';
			if ($this->extraItemType !== null) {
				$item .= substr((string) $this->extraItemType, strlen((string) $this->extraItemType->type));
			}
			$items[] = $item;
		}

		return $this->kind . '{' . implode(', ', $items) . '}';
	}

}
