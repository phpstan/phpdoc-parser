<?php

declare(strict_types=1);

use Phplrt\Source\StringSource;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/vendor/autoload.php';

// PHPLRT
$compile = new Phplrt\Compiler\Compiler()
    ->load(new \SplFileObject(__DIR__ . '/../../doc/grammars/type.pp3'))
    ->generate()
        ->save(__DIR__ . '/parser.php');

$phplrt = require __DIR__ . '/parser.php';

// PHPStan
$config = new ParserConfig([]);
$phpstanLexer = new Lexer($config);
$phpstanParser = new TypeParser($config, new ConstExprParser($config));


// -------------

const ITS = 10;

$sample = $sampleType = 'array<string, array<int, iterable<int, Example\Class>>>';
for ($i = 0; $i < 1000; ++$i) {
    $sample .= '|' . $sampleType;
}

// phplrt4
$before = microtime(true);

for ($i = 0; $i < ITS; ++$i) {
    $phplrt->parse(new StringSource($sample));
}


echo 'phplrt4: ' . number_format((microtime(true) - $before) * 1000, 3) . "ms\n";

// phpstan 2.3
$before = \microtime(true);

for ($i = 0; $i < ITS; ++$i) {
    $phpstanParser->parse(new TokenIterator($phpstanLexer->tokenize($sample)));
}

echo 'phpstan: ' . number_format((microtime(true) - $before) * 1000, 3) . "ms\n";
