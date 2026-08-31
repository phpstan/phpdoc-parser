<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use LogicException;
use PHPStan\PhpDocParser\Ast\Comment;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Lexer\TokenList;
use function array_pop;
use function assert;
use function in_array;
use function strlen;
use function substr;

class TokenIterator
{

	private TokenList $tokens;

	/** @var list<string> */
	private array $values;

	/** @var list<int> */
	private array $types;

	/** @var list<int> */
	private array $lines;

	private int $count;

	private int $index;

	/** @var list<Comment> */
	private array $comments = [];

	/** @var list<array{int, list<Comment>}> */
	private array $savePoints = [];

	/** @var list<int> */
	private array $skippedTokenTypes = [Lexer::TOKEN_HORIZONTAL_WS];

	private ?string $newline = null;

	public function __construct(TokenList $tokens, int $index = 0)
	{
		$this->tokens = $tokens;
		$this->values = $tokens->values;
		$this->types = $tokens->types;
		$this->lines = $tokens->lines;
		$this->count = $tokens->count;
		$this->index = $index;

		$this->skipIrrelevantTokens();
	}

	public function getTokens(): TokenList
	{
		return $this->tokens;
	}

	public function getContentBetween(int $startPos, int $endPos): string
	{
		if ($startPos < 0 || $endPos > $this->count) {
			throw new LogicException();
		}

		$content = '';
		for ($i = $startPos; $i < $endPos; $i++) {
			$content .= $this->values[$i];
		}

		return $content;
	}

	public function getTokenCount(): int
	{
		return $this->count;
	}

	public function currentTokenValue(): string
	{
		return $this->values[$this->index];
	}

	public function currentTokenType(): int
	{
		return $this->types[$this->index];
	}

	public function currentTokenOffset(): int
	{
		$offset = 0;
		for ($i = 0; $i < $this->index; $i++) {
			$offset += strlen($this->values[$i]);
		}

		return $offset;
	}

	public function currentTokenLine(): int
	{
		return $this->lines[$this->index];
	}

	public function currentTokenIndex(): int
	{
		return $this->index;
	}

	public function endIndexOfLastRelevantToken(): int
	{
		$endIndex = $this->currentTokenIndex();
		$endIndex--;
		while (in_array($this->types[$endIndex], $this->skippedTokenTypes, true)) {
			if ($endIndex - 1 < 0) {
				break;
			}
			$endIndex--;
		}

		return $endIndex;
	}

	public function isCurrentTokenValue(string $tokenValue): bool
	{
		return $this->values[$this->index] === $tokenValue;
	}

	public function isCurrentTokenType(int ...$tokenType): bool
	{
		return in_array($this->types[$this->index], $tokenType, true);
	}

	public function isPrecededByHorizontalWhitespace(): bool
	{
		return ($this->types[$this->index - 1] ?? -1) === Lexer::TOKEN_HORIZONTAL_WS;
	}

	/**
	 * @throws ParserException
	 */
	public function consumeTokenType(int $tokenType): void
	{
		if ($this->types[$this->index] !== $tokenType) {
			$this->throwError($tokenType);
		}

		if ($tokenType === Lexer::TOKEN_PHPDOC_EOL) {
			if ($this->newline === null) {
				$this->detectNewline();
			}
		}

		$this->next();
	}

	/**
	 * @throws ParserException
	 */
	public function consumeTokenValue(int $tokenType, string $tokenValue): void
	{
		if ($this->types[$this->index] !== $tokenType || $this->values[$this->index] !== $tokenValue) {
			$this->throwError($tokenType, $tokenValue);
		}

		$this->next();
	}

	/** @phpstan-impure */
	public function tryConsumeTokenValue(string $tokenValue): bool
	{
		if ($this->values[$this->index] !== $tokenValue) {
			return false;
		}

		$this->next();

		return true;
	}

	/**
	 * @return list<Comment>
	 */
	public function flushComments(): array
	{
		$res = $this->comments;
		$this->comments = [];
		return $res;
	}

	/** @phpstan-impure */
	public function tryConsumeTokenType(int $tokenType): bool
	{
		if ($this->types[$this->index] !== $tokenType) {
			return false;
		}

		if ($tokenType === Lexer::TOKEN_PHPDOC_EOL) {
			if ($this->newline === null) {
				$this->detectNewline();
			}
		}

		$this->next();

		return true;
	}

	/**
	 * @deprecated Use skipNewLineTokensAndConsumeComments instead (when parsing a type)
	 */
	public function skipNewLineTokens(): void
	{
		if (!$this->isCurrentTokenType(Lexer::TOKEN_PHPDOC_EOL)) {
			return;
		}

		do {
			$foundNewLine = $this->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL);
		} while ($foundNewLine === true);
	}

	public function skipNewLineTokensAndConsumeComments(): void
	{
		if ($this->currentTokenType() === Lexer::TOKEN_COMMENT) {
			$this->comments[] = new Comment($this->currentTokenValue(), $this->currentTokenLine(), $this->currentTokenIndex());
			$this->next();
		}

		if (!$this->isCurrentTokenType(Lexer::TOKEN_PHPDOC_EOL)) {
			return;
		}

		do {
			$foundNewLine = $this->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL);
			if ($this->currentTokenType() !== Lexer::TOKEN_COMMENT) {
				continue;
			}

			$this->comments[] = new Comment($this->currentTokenValue(), $this->currentTokenLine(), $this->currentTokenIndex());
			$this->next();
		} while ($foundNewLine === true);
	}

	private function detectNewline(): void
	{
		$value = $this->currentTokenValue();
		if (substr($value, 0, 2) === "\r\n") {
			$this->newline = "\r\n";
		} elseif (substr($value, 0, 1) === "\n") {
			$this->newline = "\n";
		}
	}

	public function getSkippedHorizontalWhiteSpaceIfAny(): string
	{
		if ($this->index > 0 && $this->types[$this->index - 1] === Lexer::TOKEN_HORIZONTAL_WS) {
			return $this->values[$this->index - 1];
		}

		return '';
	}

	/** @phpstan-impure */
	public function joinUntil(int ...$tokenType): string
	{
		$s = '';
		while (!in_array($this->types[$this->index], $tokenType, true)) {
			$s .= $this->values[$this->index++];
		}
		return $s;
	}

	public function next(): void
	{
		$this->index++;
		$this->skipIrrelevantTokens();
	}

	private function skipIrrelevantTokens(): void
	{
		if ($this->index >= $this->count) {
			return;
		}

		while (in_array($this->types[$this->index], $this->skippedTokenTypes, true)) {
			if ($this->index + 1 >= $this->count) {
				break;
			}
			$this->index++;
		}
	}

	public function addEndOfLineToSkippedTokens(): void
	{
		$this->skippedTokenTypes = [Lexer::TOKEN_HORIZONTAL_WS, Lexer::TOKEN_PHPDOC_EOL];
	}

	public function removeEndOfLineFromSkippedTokens(): void
	{
		$this->skippedTokenTypes = [Lexer::TOKEN_HORIZONTAL_WS];
	}

	/** @phpstan-impure */
	public function forwardToTheEnd(): void
	{
		$this->index = $this->count - 1;
	}

	public function pushSavePoint(): void
	{
		$this->savePoints[] = [$this->index, $this->comments];
	}

	public function dropSavePoint(): void
	{
		array_pop($this->savePoints);
	}

	public function rollback(): void
	{
		$savepoint = array_pop($this->savePoints);
		assert($savepoint !== null);
		[$this->index, $this->comments] = $savepoint;
	}

	/**
	 * @throws ParserException
	 */
	private function throwError(int $expectedTokenType, ?string $expectedTokenValue = null): void
	{
		throw new ParserException(
			$this->currentTokenValue(),
			$this->currentTokenType(),
			$this->currentTokenOffset(),
			$expectedTokenType,
			$expectedTokenValue,
			$this->currentTokenLine(),
		);
	}

	/**
	 * Check whether the position is directly preceded by a certain token type.
	 *
	 * During this check TOKEN_HORIZONTAL_WS and TOKEN_PHPDOC_EOL are skipped
	 */
	public function hasTokenImmediatelyBefore(int $pos, int $expectedTokenType): bool
	{
		$types = $this->types;
		$pos--;
		for (; $pos >= 0; $pos--) {
			$type = $types[$pos];
			if ($type === $expectedTokenType) {
				return true;
			}
			if (!in_array($type, [
				Lexer::TOKEN_HORIZONTAL_WS,
				Lexer::TOKEN_PHPDOC_EOL,
			], true)) {
				break;
			}
		}
		return false;
	}

	/**
	 * Check whether the position is directly followed by a certain token type.
	 *
	 * During this check TOKEN_HORIZONTAL_WS and TOKEN_PHPDOC_EOL are skipped
	 */
	public function hasTokenImmediatelyAfter(int $pos, int $expectedTokenType): bool
	{
		$types = $this->types;
		$pos++;
		for ($c = $this->count; $pos < $c; $pos++) {
			$type = $types[$pos];
			if ($type === $expectedTokenType) {
				return true;
			}
			if (!in_array($type, [
				Lexer::TOKEN_HORIZONTAL_WS,
				Lexer::TOKEN_PHPDOC_EOL,
			], true)) {
				break;
			}
		}

		return false;
	}

	public function getDetectedNewline(): ?string
	{
		return $this->newline;
	}

	/**
	 * Whether the given position is immediately surrounded by parenthesis.
	 */
	public function hasParentheses(int $startPos, int $endPos): bool
	{
		return $this->hasTokenImmediatelyBefore($startPos, Lexer::TOKEN_OPEN_PARENTHESES)
			&& $this->hasTokenImmediatelyAfter($endPos, Lexer::TOKEN_CLOSE_PARENTHESES);
	}

}
