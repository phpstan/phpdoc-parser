<?php declare(strict_types = 1);

namespace PhpStan\TypeParser;

use Iterator;


class TokenIterator implements Iterator
{
	/** @var array */
	private $tokens;

	/** @var int */
	private $index;


	public function __construct(array $tokens, int $index = 0)
	{
		$this->tokens = $tokens;
		$this->index = $index;
	}


	public function current(): array
	{
		return $this->tokens[$this->index];
	}


	public function currentTokenValue(): string
	{
		return $this->tokens[$this->index][Lexer::VALUE_OFFSET];
	}


	public function currentTokenOffset(): int
	{
		$offset = 0;
		for ($i = 0; $i < $this->index; $i++) {
			$offset += strlen($this->tokens[$i][Lexer::VALUE_OFFSET]);
		}

		return $offset;
	}


	public function currentTokenType(): int
	{
		return $this->tokens[$this->index][Lexer::TYPE_OFFSET];
	}


	public function isCurrentTokenType(int $tokenType): bool
	{
		return $this->tokens[$this->index][Lexer::TYPE_OFFSET] === $tokenType;
	}


	public function next(): void
	{
		$this->index++;
	}


	public function key(): int
	{
		return $this->index;
	}


	public function valid(): bool
	{
		return isset($this->tokens[$this->index]);
	}


	public function rewind(): void
	{
		$this->index = 0;
	}
}
