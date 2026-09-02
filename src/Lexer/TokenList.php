<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Lexer;

use Countable;
use function count;

/**
 * Tokens in a struct-of-arrays layout: three parallel packed arrays instead of
 * one array (or object) per token.
 */
final class TokenList implements Countable
{

	/** @var list<string> */
	public array $values;

	/** @var list<int> */
	public array $types;

	/** @var list<int> */
	public array $lines;

	/** @var int<0, max> */
	public int $count;

	/**
	 * @param list<string> $values
	 * @param list<int> $types
	 * @param list<int> $lines
	 */
	public function __construct(array $values, array $types, array $lines)
	{
		$this->values = $values;
		$this->types = $types;
		$this->lines = $lines;
		$this->count = count($values);
	}

	/**
	 * Only so that code written against the array this replaces keeps working.
	 * Read the $count property directly in hot paths -- reading a property is
	 * cheaper than the call this makes.
	 */
	public function count(): int
	{
		return $this->count;
	}

}
