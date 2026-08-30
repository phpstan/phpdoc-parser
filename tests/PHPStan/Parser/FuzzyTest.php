<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use Iterator;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use PHPStan\PhpDocParser\Printer\Printer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use function file_get_contents;
use function glob;
use function is_dir;
use function is_file;
use function sprintf;
use const PHP_BINARY;
use const PHP_VERSION_ID;

/**
 * Reads what the grammars in "doc/grammars" say is a well-formed input.
 *
 * A grammar says what a PHPDoc may be written as, so "tools/phplrt/fuzz.php"
 * walks it the other way round and writes PHPDocs down instead of reading them.
 * Every one of them then has to be read in full and has to survive being
 * printed and read again, which is a great deal more of the language than a
 * hand-written corpus ever covers.
 *
 * The tool writing the corpus needs PHP 8.4 and a toolchain of its own, so
 * where either is missing the test is skipped rather than failed.
 */
class FuzzyTest extends TestCase
{

	/**
	 * How many inputs are written from each grammar.
	 *
	 * Enough of them that a run reaches the corners of the language rather than
	 * only what a short walk over the rules comes out with: at a thousand the
	 * rules reading a "@method" signature are only reached every other run.
	 */
	private const INPUTS = 2000;

	/**
	 * The oldest PHP the tool writing the corpus runs on, which is the one the
	 * grammar compiler asks for.
	 */
	private const REQUIRED_PHP_VERSION = 80400;

	private Lexer $lexer;

	private Printer $printer;

	private TypeParser $typeParser;

	private ConstExprParser $constExprParser;

	private PhpDocParser $phpDocParser;

	protected function setUp(): void
	{
		parent::setUp();
		$config = new ParserConfig([]);
		$this->lexer = new Lexer($config);
		$this->printer = new Printer();
		$this->constExprParser = new ConstExprParser($config);
		$this->typeParser = new TypeParser($config, $this->constExprParser);
		$this->phpDocParser = new PhpDocParser($config, $this->typeParser, $this->constExprParser);
	}

	/**
	 * @dataProvider provideTypeParserData
	 */
	public function testTypeParser(?string $input): void
	{
		$this->assertReadInFull($input, fn (TokenIterator $tokens): Node => $this->typeParser->parse($tokens));
	}

	public function provideTypeParserData(): Iterator
	{
		return $this->provideFuzzyInputsData('type.pp3', 'Type');
	}

	/**
	 * @dataProvider provideConstExprParserData
	 */
	public function testConstExprParser(?string $input): void
	{
		$this->assertReadInFull($input, fn (TokenIterator $tokens): Node => $this->constExprParser->parse($tokens));
	}

	public function provideConstExprParserData(): Iterator
	{
		return $this->provideFuzzyInputsData('constant-expr.pp3', 'ConstantExpr');
	}

	/**
	 * @dataProvider providePhpDocParserData
	 */
	public function testPhpDocParser(?string $input): void
	{
		// A description written across several lines is printed as it was read
		// rather than as the lines of a PHPDoc, so a whole PHPDoc is not asked
		// to survive being printed and read again
		$this->assertReadInFull(
			$input,
			fn (TokenIterator $tokens): Node => $this->phpDocParser->parse($tokens),
			false,
		);
	}

	public function providePhpDocParserData(): Iterator
	{
		return $this->provideFuzzyInputsData('phpdoc.pp3', 'PhpDoc');
	}

	/**
	 * Asks the parser to read the whole input, and to read what it prints back
	 * as the very same thing.
	 *
	 * @param callable(TokenIterator): Node $parse
	 * @param bool $roundTrips whether what is printed has to be read back as
	 *        the very same thing
	 */
	private function assertReadInFull(?string $input, callable $parse, bool $roundTrips = true): void
	{
		if ($input === null) {
			self::markTestSkipped('The corpus needs PHP 8.4 and the toolchain of "make grammars-install"');
		}

		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$node = $parse($tokens);

		$this->assertSame(
			Lexer::TOKEN_END,
			$tokens->currentTokenType(),
			sprintf('Failed to parse input %s', $input),
		);

		if (!$roundTrips) {
			return;
		}

		$printed = $this->printer->print($node);
		$printedTokens = new TokenIterator($this->lexer->tokenize($printed));
		$reparsed = $parse($printedTokens);

		$this->assertSame(
			Lexer::TOKEN_END,
			$printedTokens->currentTokenType(),
			sprintf('Failed to parse printed input %s', $printed),
		);

		$this->assertSame(
			(string) $node,
			(string) $reparsed,
			sprintf('Printing and reading back changed the input %s', $input),
		);
	}

	/**
	 * Writes down a corpus of inputs the given grammar says are well-formed.
	 *
	 * @return Iterator<int, array{string|null}>
	 */
	private function provideFuzzyInputsData(string $grammar, string $startSymbol): Iterator
	{
		$root = __DIR__ . '/../../..';
		$inputsDirectory = sprintf('%s/temp/fuzzy/%s', $root, $startSymbol);

		if (
			PHP_VERSION_ID < self::REQUIRED_PHP_VERSION
			|| !is_file(sprintf('%s/tools/phplrt/vendor/autoload.php', $root))
		) {
			yield [null];

			return;
		}

		$process = new Process([
			PHP_BINARY,
			sprintf('%s/tools/phplrt/fuzz.php', $root),
			sprintf('%s/doc/grammars/%s', $root, $grammar),
			$inputsDirectory,
			(string) self::INPUTS,
		]);

		$process->mustRun();

		if (!is_dir($inputsDirectory)) {
			yield [null];

			return;
		}

		$glob = glob(sprintf('%s/*.tst', $inputsDirectory));

		if ($glob === false || $glob === []) {
			yield [null];

			return;
		}

		foreach ($glob as $file) {
			$input = file_get_contents($file);

			if ($input === false) {
				continue;
			}

			yield [$input];
		}
	}

}
