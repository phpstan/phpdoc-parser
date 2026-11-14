<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Benchmarks;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use Tally\Attribute\Before;
use Tally\Attribute\Warmup;

/**
 * Parsing the PHPDocs of a config/reference.php that Symfony 7.4 generates, as PHPStan does:
 * one large array shape with many generics and constant types. 1427af4 made it about twice as
 * quick by computing the token offsets once, 3fcdcf0 twice as quick again by not walking to
 * the end of the PHPDoc for every generic.
 */
final class SymfonyConfigReferenceBench
{

	private Lexer $lexer;

	private PhpDocParser $phpDocParser;

	/** @var list<string> */
	private array $phpDocs;

	#[Before]
	public function prepare(): void
	{
		$config = new ParserConfig(['lines' => true]);
		$constExprParser = new ConstExprParser($config);
		$this->lexer = new Lexer($config);
		$this->phpDocParser = new PhpDocParser($config, new TypeParser($config, $constExprParser), $constExprParser);
		$this->phpDocs = require __DIR__ . '/data/symfony-config-reference.php';
	}

	#[Warmup(1)]
	public function benchParse(): void
	{
		foreach ($this->phpDocs as $phpDoc) {
			$this->phpDocParser->parse(new TokenIterator($this->lexer->tokenize($phpDoc)));
		}
	}

}
