<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use Iterator;
use PHPStan\PhpDocParser\Lexer\Lexer;
use Symfony\Component\Process\Process;

class FuzzyTest extends \PHPUnit\Framework\TestCase
{

	/** @var Lexer */
	private $lexer;

	/** @var TypeParser */
	private $typeParser;

	/** @var ConstExprParser */
	private $constExprParser;

	protected function setUp(): void
	{
		parent::setUp();
		$this->lexer = new Lexer();
		$this->typeParser = new TypeParser(new ConstExprParser());
		$this->constExprParser = new ConstExprParser();
	}

	/**
	 * @dataProvider provideTypeParserData
	 * @param string $input
	 */
	public function testTypeParser(string $input): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$this->typeParser->parse($tokens);

		$this->assertSame(
			Lexer::TOKEN_END,
			$tokens->currentTokenType(),
			sprintf('Failed to parse input %s', $input)
		);
	}

	public function provideTypeParserData(): Iterator
	{
		return $this->provideFuzzyInputsData('Type');
	}

	/**
	 * @dataProvider provideConstExprParserData
	 * @param string $input
	 */
	public function testConstExprParser(string $input): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$this->constExprParser->parse($tokens);

		$this->assertSame(
			Lexer::TOKEN_END,
			$tokens->currentTokenType(),
			sprintf('Failed to parse input %s', $input)
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
			foreach (glob(sprintf('%s/*.tst', $inputsDirectory)) as $file) {
				unlink($file);
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

		foreach (glob(sprintf('%s/*.tst', $inputsDirectory)) as $file) {
			$input = file_get_contents($file);
			yield [$input];
		}
	}

}
