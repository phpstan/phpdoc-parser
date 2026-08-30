<?php

/**
 * Writes down a corpus of inputs the given grammar says are well-formed.
 *
 * This is what "tests/PHPStan/Parser/FuzzyTest.php" reads. A grammar says what
 * a PHPDoc may be written as, so it is walked the other way round and asked for
 * PHPDocs instead of being asked about one; every input it writes is then one
 * the parser has to read in full, which is a great deal more of the language
 * than a hand-written corpus ever covers.
 *
 * An input is written of the very rules the grammar is read into, so the two
 * can never drift apart: a rule that is changed changes what is generated the
 * moment the grammar is read again.
 *
 * Two things are thrown away before an input is written down, and neither of
 * them is the parser being wrong:
 *
 *  - one whose text does not read back as the very tokens it was written of,
 *    which is this tool having written two tokens the lexer reads as one;
 *
 *  - one the grammar does not recognize. A walk over the rules says what the
 *    language is written of, while a parser reads the alternatives of a rule in
 *    the order they are written and stops at the first one that fits, so a walk
 *    may well write down something perfectly well-formed that is nevertheless
 *    not what the very same text is read as.
 *
 * Usage:
 *   php tools/phplrt/fuzz.php <grammar.pp3> <directory> [count] [seed]
 *
 * @internal this is a development tool, not part of the library
 */

declare(strict_types=1);

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use PHPStan\PhpDocParser\Tools\Fuzzer\Generator;
use PHPStan\PhpDocParser\Tools\Fuzzer\TokenSource;
use PHPStan\PhpDocParser\Tools\Fuzzer\TokenStream;
use PHPStan\PhpDocParser\Tools\Fuzzer\TokenStreamLexer;
use Phplrt\Compiler\Compiler;
use Phplrt\Contracts\Lexer\Channel;
use Phplrt\Parser\Analysis\Mode;
use Phplrt\Parser\Analysis\Result\PartialResult;
use Phplrt\Parser\Analysis\Result\SuccessfulResult;
use Phplrt\Source\FileSource;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Fuzzer/TokenStream.php';
require __DIR__ . '/Fuzzer/Generator.php';

/**
 * How many times an input is written before the tool gives up on a grammar it
 * cannot write anything readable from.
 */
const ATTEMPTS_PER_INPUT = 40;

$grammar = $argv[1] ?? null;
$directory = $argv[2] ?? null;
$count = (int) ($argv[3] ?? 1000);
$seed = isset($argv[4]) ? (int) $argv[4] : null;

if ($grammar === null || $directory === null) {
    \fwrite(\STDERR, "Usage: php tools/phplrt/fuzz.php <grammar.pp3> <directory> [count] [seed]\n");

    exit(1);
}

\mt_srand($seed ?? \random_int(0, \PHP_INT_MAX));

$compiled = new Compiler()
    ->load(FileSource::createFromPathname($grammar))
    ->build();

/** @var array<non-empty-string, int> $ids */
$ids = \array_flip($compiled->lexer->names);

$stream = new TokenStream($ids);
$parser = $compiled->parser->toParser(new TokenStreamLexer());
$lexer = new Lexer(new ParserConfig([]));

$generator = new Generator(
    $compiled->parser->grammar,
    $compiled->lexer->names,
    $compiled->parser->initial,
);

if (!\is_dir($directory) && !\mkdir($directory, 0777, true)) {
    \fwrite(\STDERR, \sprintf("Cannot create %s\n", $directory));

    exit(1);
}

foreach ((array) \glob($directory . '/*.tst') as $stale) {
    \unlink((string) $stale);
}

$written = 0;
$attempts = 0;

while ($written < $count && $attempts < $count * ATTEMPTS_PER_INPUT) {
    $attempts++;

    $tokens = $generator->generate();

    if ($tokens === null) {
        continue;
    }

    $text = $generator->render($tokens);
    $read = $stream->create($lexer->tokenize($text));

    // The text has to read back as the very tokens it was written of
    $actual = [];

    foreach ($read as $token) {
        if ($token->channel === Channel::EndOfInput) {
            continue;
        }

        $actual[] = $token->id;
    }

    if ($generator->written($tokens) !== $actual) {
        continue;
    }

    // And the grammar has to recognize it, all of it
    $result = $parser->analyze(new TokenSource($read), Mode::SyntaxCheck);

    if (!$result instanceof SuccessfulResult || $result instanceof PartialResult) {
        continue;
    }

    \file_put_contents(\sprintf('%s/%04d.tst', $directory, $written), $text);
    $written++;
}

if ($written === 0) {
    \fwrite(\STDERR, \sprintf("Nothing could be written from %s\n", $grammar));

    exit(1);
}

echo $written, "\n";
