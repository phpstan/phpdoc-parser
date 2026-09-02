<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Lexer;

use PHPStan\PhpDocParser\ParserConfig;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use function array_keys;
use function count;
use function preg_match_all;
use const PHP_VERSION_ID;

class LexerTest extends TestCase
{

	/**
	 * tokenize() reads only the whole match and the MARK out of
	 * preg_match_all(), but preg_match_all() collects every capturing group of
	 * the pattern for every token it finds. One capturing group added to one
	 * token pattern therefore costs one extra array entry per token of every
	 * PHPDoc that is lexed, whether anything reads it or not.
	 *
	 * Keep every group in the token patterns non-capturing.
	 */
	public function testTokenPatternsHaveNoCapturingGroups(): void
	{
		$lexer = new Lexer(new ParserConfig([]));
		$lexer->tokenize('/** @param float $a */');

		$property = new ReflectionProperty(Lexer::class, 'regexp');
		if (PHP_VERSION_ID < 80100) {
			$property->setAccessible(true);
		}
		$regexp = $property->getValue($lexer);
		self::assertIsString($regexp);

		// exercises the patterns that are most likely to grow a group
		$subject = '/** 0b1_0 0o1_0 0xa_b 1_0 1_0.0_1e+1_0 Foo|Bar $a ... */';
		preg_match_all($regexp, $subject, $matches);

		self::assertSame([0, 'MARK'], array_keys($matches));
	}

	/**
	 * tokenize() used to return an array, so count() on its result has to keep
	 * saying how many tokens there are.
	 */
	public function testTokenListIsCountable(): void
	{
		$lexer = new Lexer(new ParserConfig([]));
		$tokens = $lexer->tokenize('/** @param int $a */');

		self::assertCount($tokens->count, $tokens);
		self::assertCount(count($tokens->values), $tokens);
	}

}
