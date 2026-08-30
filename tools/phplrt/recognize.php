<?php

/**
 * Says which of the given inputs the grammar recognizes.
 *
 * This is what "tests/PHPStan/Parser/GrammarSyncTest.php" reads. Where the
 * fuzzer asks the parser to read what a grammar writes, this asks a grammar
 * about what the parser already reads: the inputs of every other test in this
 * project. A feature added to the parser and written down as a test is one the
 * grammars have to describe as well, and this is what says so.
 *
 * Reading is all that is asked of the grammar here. Nothing is built out of
 * what it reads, so there is no second implementation of the AST to keep in
 * step -- only the language itself.
 *
 * Usage:
 *   php tools/phplrt/recognize.php <grammar.pp3> <inputs.json> <verdicts.json>
 *
 * @internal this is a development tool, not part of the library
 */

declare(strict_types=1);

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use PHPStan\PhpDocParser\Tools\Fuzzer\TokenSource;
use PHPStan\PhpDocParser\Tools\Fuzzer\TokenStream;
use PHPStan\PhpDocParser\Tools\Fuzzer\TokenStreamLexer;
use Phplrt\Compiler\Compiler;
use Phplrt\Parser\Analysis\Mode;
use Phplrt\Parser\Analysis\Result\PartialResult;
use Phplrt\Parser\Analysis\Result\SuccessfulResult;
use Phplrt\Source\FileSource;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Fuzzer/TokenStream.php';

$grammar = $argv[1] ?? null;
$inputsFile = $argv[2] ?? null;
$verdictsFile = $argv[3] ?? null;

if ($grammar === null || $inputsFile === null || $verdictsFile === null) {
    \fwrite(\STDERR, "Usage: php tools/phplrt/recognize.php <grammar.pp3> <inputs.json> <verdicts.json>\n");

    exit(1);
}

// Not every input of this project's own corpus is valid UTF-8, so they travel
// encoded rather than as the text they are
/** @var list<string> $inputs */
$inputs = \array_map(
    static fn (string $encoded): string => (string) \base64_decode($encoded, true),
    \json_decode((string) \file_get_contents($inputsFile), true, 512, \JSON_THROW_ON_ERROR),
);

$compiled = new Compiler()
    ->load(FileSource::createFromPathname($grammar))
    ->build();

$stream = new TokenStream(\array_flip($compiled->lexer->names));
$parser = $compiled->parser->toParser(new TokenStreamLexer());
$lexer = new Lexer(new ParserConfig([]));

$verdicts = [];

foreach ($inputs as $input) {
    try {
        $read = $stream->create($lexer->tokenize($input));
        $result = $parser->analyze(new TokenSource($read), Mode::SyntaxCheck);
        $verdicts[] = $result instanceof SuccessfulResult && !$result instanceof PartialResult;
    } catch (Throwable) {
        // A grammar that cannot even be handed the tokens has not recognized
        // anything, which is what the test is told
        $verdicts[] = false;
    }
}

\file_put_contents($verdictsFile, \json_encode($verdicts, \JSON_THROW_ON_ERROR));
