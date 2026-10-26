<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser\Generated;

use PHPStan\PhpDocParser\Ast\Comment;
use PHPStan\PhpDocParser\Parser\Phplrt\Token;
use function array_keys;
use function ksort;

/**
 * The parser of "doc/grammars/phpdoc.pp3".
 *
 * DO NOT EDIT: this file is written by "tools/phplrt/build.php" (make grammars-build).
 *
 * Every rule of the grammar is a method reading it the way the runtime of
 * phplrt would, and building its value along: a method named "b" followed by
 * the identifier of a rule reads the rule and pushes its value, one named "s"
 * only reads it. The values are built by the reducers of the class extending
 * this one, which are given the index of the first and of the last token of
 * the lexer the rule has read.
 *
 * @internal
 */
abstract class GeneratedParser
{

	/** @var array<string, int> the identifier of each rule a reading may start at */
	public const RULES = /*RULES*/[];

	/** @var array<string, int> the identifier of each token */
	public const TOKENS = /*TOKENS*/[];

	/** @var array<int, string> the name each token is expected by */
	public const EXPECTATIONS = /*EXPECTATIONS*/[];

	/** the pattern of the lexer, marking each token by its identifier */
	public const LEXER_PATTERN = /*LEXER_PATTERN*/'';

	/** @var array<int, int> the type of the lexer each token it reads is, by the identifier */
	public const LEXER_TYPES = /*LEXER_TYPES*/[];

	/*CONSTANTS*/

	/** @var array<int, int> the identifier of the token at each position, the last one being -1 */
	protected array $ids = [];

	/** @var array<int, int> the index of the token of the lexer at each position */
	protected array $indexes = [];

	/** the position the reading is at */
	protected int $p = 0;

	/** @var array<int, mixed> the values built, as a stack */
	protected array $v = [];

	/** the size of the stack of values */
	protected int $n = 0;

	/** @var array<int, Comment> the comments read and not given to any node yet, by their index */
	protected array $comments = [];

	/** the furthest position a failure has been recorded at */
	private int $f = -1;

	/** @var list<int> the rules recorded as failed at the furthest position */
	private array $er = [];

	abstract protected function token(int $position): Token;


	/*REDUCERS*/

	/**
	 * Where the last reading has failed, along with the tokens expected there.
	 *
	 * @return array{int, list<int>}
	 */
	protected function failure(): array
	{
		if ($this->f === -1 || $this->f < $this->p) {
			return [$this->p, []];
		}

		$expected = [];
		foreach ($this->er as $rule) {
			foreach (self::EXPECTED[$rule] ?? [] as $token) {
				$expected[$token] = true;
			}
		}

		ksort($expected);

		return [$this->f, array_keys($expected)];
	}

	/**
	 * Saves the state of the reading, so that a reading may be started in the
	 * middle of another one.
	 *
	 * @return array{int, int, list<int>}
	 */
	protected function saveReading(): array
	{
		return [$this->p, $this->f, $this->er];
	}

	/**
	 * @param array{int, int, list<int>} $state
	 */
	protected function restoreReading(array $state): void
	{
		[$this->p, $this->f, $this->er] = $state;
	}

	private function rec(int $rule): void
	{
		$position = $this->p;
		if ($position > $this->f) {
			$this->f = $position;
			$this->er = [$rule];
			return;
		}

		if ($position !== $this->f || isset($this->er[3])) {
			return;
		}

		$this->er[] = $rule;
	}

	/*METHODS*/
}
