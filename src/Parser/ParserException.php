<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Lexer\Lexer;

class ParserException extends \Exception
{

	/** @var string */
	private $currentTokenValue;

	/** @var int */
	private $currentTokenType;

	/** @var int */
	private $currentOffset;

	/** @var int */
	private $expectedTokenType;

	/** @var string */
	private $expectedTokenValue;

	public function __construct(
		string $currentTokenValue,
		int $currentTokenType,
		int $currentOffset,
		?int $expectedTokenType,
		?string $expectedTokenValue = null
	)
	{
		$this->currentTokenValue = $currentTokenValue;
		$this->currentTokenType = $currentTokenType;
		$this->currentOffset = $currentOffset;
		$this->expectedTokenType = $expectedTokenType;
		$this->expectedTokenValue = $expectedTokenValue;

		$json = json_encode($currentTokenValue, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		assert($json !== false);

		if ($expectedTokenType !== null) {
			parent::__construct(sprintf(
				'Unexpected token %s, expected %s at offset %d',
				$json,
				Lexer::TOKEN_LABELS[$expectedTokenType],
				$currentOffset
			));
		} elseif ($expectedTokenValue !== null) {
			parent::__construct(sprintf(
				'Unexpected token value %s, expected value %s at offset %d',
				$json,
				$expectedTokenValue,
				$currentOffset
			));
		} else {
			throw new \LogicException();
		}
	}


	public function getCurrentTokenValue(): string
	{
		return $this->currentTokenValue;
	}


	public function getCurrentTokenType(): int
	{
		return $this->currentTokenType;
	}


	public function getCurrentOffset(): int
	{
		return $this->currentOffset;
	}


	public function getExpectedTokenType(): int
	{
		return $this->expectedTokenType;
	}

	public function getExpectedTokenValue(): string
	{
		return $this->expectedTokenValue;
	}

}
