<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser\Phplrt;

/**
 * A part of a node that is only finished by the rule around it: the arguments
 * of a generic type are only a type once the name before them is known.
 *
 * @internal
 */
final class Fragment
{

	public const GENERIC = 0;
	public const CALLABLE = 1;
	public const SHAPE = 2;
	public const OFFSET = 3;
	public const ARRAY = 4;
	public const OBJECT_SHAPE = 5;
	public const GENERIC_ARGUMENT = 6;

	public int $kind;

	/** @var mixed */
	public $a;

	/** @var mixed */
	public $b;

	/** @var mixed */
	public $c;

	/** the index of the last token the fragment has been read from */
	public int $end;

	/**
	 * @param mixed $a
	 * @param mixed $b
	 * @param mixed $c
	 */
	public function __construct(int $kind, int $end, $a = null, $b = null, $c = null)
	{
		$this->kind = $kind;
		$this->end = $end;
		$this->a = $a;
		$this->b = $b;
		$this->c = $c;
	}

}
