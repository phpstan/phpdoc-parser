<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use PHPUnit\Framework\TestCase;
use const PHP_EOL;

class TokenIteratorTest extends TestCase
{

	/**
	 * @return iterable<array{string, ?string}>
	 */
	public function dataGetDetectedNewline(): iterable
	{
		yield [
			'/** @param Foo $a */',
			null,
		];

		yield [
			'/**' . "\n" .
			' * @param Foo $a' . "\n" .
			' */',
			"\n",
		];

		yield [
			'/**' . "\r\n" .
			' * @param Foo $a' . "\r\n" .
			' */',
			"\r\n",
		];

		yield [
			'/**' . PHP_EOL .
			' * @param Foo $a' . PHP_EOL .
			' */',
			PHP_EOL,
		];
	}

	/**
	 * @dataProvider dataGetDetectedNewline
	 */
	public function testGetDetectedNewline(string $phpDoc, ?string $expectedNewline): void
	{
		$config = new ParserConfig([]);
		$lexer = new Lexer($config);
		$tokens = new TokenIterator($lexer->tokenize($phpDoc));
		$constExprParser = new ConstExprParser($config);
		$typeParser = new TypeParser($config, $constExprParser);
		$phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);
		$phpDocParser->parse($tokens);
		$this->assertSame($expectedNewline, $tokens->getDetectedNewline());
	}

}
