<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Lexer;

use PHPStan\PhpDocParser\Parser\Generated\GeneratedParser;
use PHPStan\PhpDocParser\Parser\Phplrt\TokenStream;
use PHPStan\PhpDocParser\ParserConfig;
use PHPUnit\Framework\TestCase;
use function array_keys;
use function array_map;
use function count;
use function intdiv;
use function preg_match_all;

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
		$regexp = GeneratedParser::LEXER_PATTERN;

		// exercises the patterns that are most likely to grow a group
		$subject = '/** 0b1_0 0o1_0 0xa_b 1_0 1_0.0_1e+1_0 Foo|Bar $a ... */';
		preg_match_all($regexp, $subject, $matches);

		self::assertSame([0, 'MARK'], array_keys($matches));
	}

	/**
	 * @return iterable<array{string}>
	 */
	public function dataStream(): iterable
	{
		yield ['/** @param ARRAY|Array{a: int}|array {b} $is IS is-a \\is not\\Of */'];
		yield ['/** @Param @phpstan-param:foo @param-out @param- @psalm-param_x @ORM\\Entity  @Foo(1) */'];
		yield ["/**\n * @var Foo[0] Foo [0] Foo(1) Foo (1) Foo{1} Foo {1}\n *  @return Foo::BAR* Foo::BAR*|Baz\n */"];
		yield ['/** @return Foo<br>see below</br> < br > x </br> Foo<Bar> <a>b</A> */'];
		yield ['/** TRUE False nULL true-ish 1_0.0_1e+1_0 0x1F -1 "a""b" \'c\' // comment */'];
		yield [''];
		yield ['@var int'];
	}

	/**
	 * The stream the lexer reads along with the tokens is the very one the
	 * parsers would otherwise read out of the tokens themselves.
	 *
	 * @dataProvider dataStream
	 */
	public function testStream(string $input): void
	{
		$tokens = (new Lexer(new ParserConfig([])))->tokenize($input);

		foreach ([0, intdiv(count($tokens), 2), count($tokens) - 1] as $start) {
			$ids = [];
			$indexes = [];
			TokenStream::create(array_map(static fn (array $token): array => [$token[0], $token[1], $token[2]], $tokens), $start, $ids, $indexes);

			self::assertSame([$ids, $indexes], TokenStream::fromTokens($tokens, $start));
		}
	}

}
