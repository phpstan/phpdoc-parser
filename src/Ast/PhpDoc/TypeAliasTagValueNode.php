<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\NodeAttributes;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use function count;
use function implode;
use function trim;

class TypeAliasTagValueNode implements PhpDocTagValueNode
{

	use NodeAttributes;

	/** @var string */
	public $alias;

	/** @var TypeNode */
	public $type;

	/** @var array<string, TypeNode|null> */
	public $typeArguments;

	/**
	 * @param array<string, TypeNode|null> $typeArguments
	 */
	public function __construct(string $alias, TypeNode $type, array $typeArguments = [])
	{
		$this->alias = $alias;
		$this->type = $type;
		$this->typeArguments = $typeArguments;
	}


	public function __toString(): string
	{
		$args = '';
		if (count($this->typeArguments) > 0) {
			$printedArgs = [];
			foreach ($this->typeArguments as $name => $bound) {
				$printedArgs[] = $name . ($bound === null ? '' : ' of ' . $bound);
			}
			$args = '<' . implode(', ', $printedArgs) . '>';
		}
		return trim("{$this->alias}{$args} {$this->type}");
	}

}
