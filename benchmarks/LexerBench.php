<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Benchmarks;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use Tally\Attribute\Before;
use Tally\Attribute\Warmup;

/**
 * Lexing typical PHPDocs, which 148ceff made about 40 % quicker by extracting the matches
 * with PREG_PATTERN_ORDER after e59b0c8 made the numeric-separator groups non-capturing.
 */
final class LexerBench
{

	private Lexer $lexer;

	/** @var list<string> */
	private array $phpDocs;

	#[Before]
	public function prepare(): void
	{
		$this->lexer = new Lexer(new ParserConfig(['lines' => true]));
		$this->phpDocs = require __DIR__ . '/data/vendor-docblocks.php';
	}

	#[Warmup(1)]
	public function benchTokenizeTypicalPhpDocs(): void
	{
		foreach ($this->phpDocs as $phpDoc) {
			$this->lexer->tokenize($phpDoc);
		}
	}

}
