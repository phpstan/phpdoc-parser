<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use Iterator;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use function file_get_contents;
use function glob;
use function is_dir;
use function mkdir;
use function sprintf;
use function unlink;

/**
 * @requires OS ^(?!win)
 */
class FuzzyTest extends TestCase
{

	private Lexer $lexer;

	private TypeParser $typeParser;

	private ConstExprParser $constExprParser;

	protected function setUp(): void
	{
		parent::setUp();
		$config = new ParserConfig([]);
		$this->lexer = new Lexer($config);
		$this->typeParser = new TypeParser($config, new ConstExprParser($config));
		$this->constExprParser = new ConstExprParser($config);
	}

	/**
	 * @dataProvider provideTypeParserData
	 */
	public function testTypeParser(string $input): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$this->typeParser->parse($tokens);

		$this->assertSame(
			Lexer::TOKEN_END,
			$tokens->currentTokenType(),
			sprintf('Failed to parse input %s', $input),
		);
	}

	public function provideTypeParserData(): Iterator
	{
		return $this->provideFuzzyInputsData('Type');
	}

	/**
	 * @dataProvider provideConstExprParserData
	 */
	public function testConstExprParser(string $input): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$this->constExprParser->parse($tokens);

		$this->assertSame(
			Lexer::TOKEN_END,
			$tokens->currentTokenType(),
			sprintf('Failed to parse input %s', $input),
		);
	}

	public function provideConstExprParserData(): Iterator
	{
		return $this->provideFuzzyInputsData('ConstantExpr');
	}

	private function provideFuzzyInputsData(string $startSymbol): Iterator
	{
		$inputsDirectory = sprintf('%s/fuzzy/%s', __DIR__ . '/../../../temp', $startSymbol);

		if (is_dir($inputsDirectory)) {
			$glob = glob(sprintf('%s/*.tst', $inputsDirectory));

			if ($glob !== false) {
				foreach ($glob as $file) {
					unlink($file);
				}
			}

		} else {
			mkdir($inputsDirectory, 0777, true);
		}

		$process = new Process([
			__DIR__ . '/../../../tools/abnfgen/abnfgen',
			'-lx',
			'-n',
			'1000',
			'-d',
			$inputsDirectory,
			'-s',
			$startSymbol,
			__DIR__ . '/../../../doc/grammars/type.abnf',
		]);

		$process->mustRun();

		$glob = glob(sprintf('%s/*.tst', $inputsDirectory));

		if ($glob === false) {
			return;
		}

		foreach ($glob as $file) {
			$input = file_get_contents($file);
			yield [$input];
		}
	}

}
