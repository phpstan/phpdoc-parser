<?php

/**
 * Writes the parser of "doc/grammars/phpdoc.pp3": a class of its own that runs
 * on PHP 7.4, "src/Parser/Generated/GeneratedParser.php". The class holds the
 * pattern of "src/Lexer/Lexer.php" as well, written out of the tokens the
 * grammar declares.
 *
 * The compiler asks for a recent PHP, so this runs here, as a development
 * tool, and only its output is a part of the library. The grammar is compiled
 * by phplrt, and "ParserGenerator.php" writes every rule of what it compiles
 * down as a method reading the rule the way the runtime of phplrt would.
 *
 * The rules a node of the AST is built out of are the ones
 * "src/Parser/AstBuilder.php" has a reducer for. They are marked as such
 * before the grammar is compiled, so that the compiler never merges them into
 * a rule around them; everything else is optimized the way the compiler sees
 * fit.
 *
 * Usage:
 *   php tools/phplrt/build.php [<output.php>]
 *
 * @internal this is a development tool, not part of the library
 */

declare(strict_types=1);

use Phplrt\Compiler\Compiler;
use Phplrt\Lexer\Builder\LexerBuilderResult;
use Phplrt\Parser\Builder\Compiler\ParserBuildingContext;
use Phplrt\Parser\Builder\Compiler\ParserCompilerPassInterface;
use Phplrt\Parser\Builder\Definition\AlternationRuleDefinition;
use Phplrt\Parser\Builder\Definition\RuleDefinition;
use Phplrt\Parser\Builder\ParserBuilder;
use Phplrt\Parser\Grammar\Alternation;
use Phplrt\Parser\Grammar\Lexeme;
use Phplrt\Source\FileSource;

// The upstream runtime the compiler is written against has to win over the
// downgraded one the library itself depends on
require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/ParserGenerator.php';
require __DIR__ . '/../../src/Lexer/Lexer.php';

$output = $argv[1] ?? __DIR__ . '/../../src/Parser/Generated/GeneratedParser.php';

/**
 * The rules the parsers are started at.
 */
const ENTRYPOINTS = ['PhpDoc', 'Type', 'ConstantExpr', 'KnownTag', 'UnreadTagChild', 'PhpDocChild'];

/**
 * The reducers of "src/Parser/AstBuilder.php", by the name of the rule they
 * reduce, along with whether they read what the rule contains. The source is
 * read rather than the class, which extends the very class written here.
 *
 * @var array<string, array{string, bool}> $reducers
 */
$reducers = [];
preg_match_all('/function (reduce(\w+))\(([^)]*)\)/', file_get_contents(__DIR__ . '/../../src/Parser/AstBuilder.php'), $matches, PREG_SET_ORDER);
foreach ($matches as [, $method, $rule, $parameters]) {
    $reducers[$rule] = [$method, substr_count($parameters, '$') > 1];
}

/** @var array<string, true> $entrypoints */
$entrypoints = array_fill_keys(array_keys($reducers), true) + array_fill_keys(ENTRYPOINTS, true);

$compiler = (new Compiler())
    ->load(FileSource::createFromPathname(__DIR__ . '/../../doc/grammars/phpdoc.pp3'));

/**
 * The grammars describe a PHPDoc written correctly, while the parser reads a
 * broken one as well: a tag whose value cannot be read keeps the rest of its
 * line as its value, and a type alias whose type cannot be read keeps its name.
 * Both are written here as alternatives tried once everything else has failed,
 * so that the grammars stay the description of the language they are.
 *
 *   InvalidTag          : TagToken() Text() ;
 *   InvalidTypeAliasTag : <T_TAG_TYPE_ALIAS> Identifier() <T_EQUAL>? Text() ;
 */
$builder = $compiler->parser;
$invalidTag = $builder->addConcatenation([
    $builder->addRuleReference('TagToken'),
    $builder->addRuleReference('Text'),
], 'InvalidTag');
$invalidTypeAliasTag = $builder->addConcatenation([
    $builder->addTokenReference('T_TAG_TYPE_ALIAS'),
    $builder->addRuleReference('Identifier'),
    $builder->addOptional($builder->addTokenReference('T_EQUAL')),
    $builder->addRuleReference('Text'),
], 'InvalidTypeAliasTag');

foreach ($builder->rules as $rule) {
    if ($rule->name === 'PhpDocChild') {
        // KnownTag | InvalidTag | !TagToken() TextChild()
        $alternatives = $rule->rules;
        array_splice($alternatives, 1, 0, [$invalidTag]);
        $rule->setRules($alternatives);
    } elseif ($rule->name === 'KnownTag') {
        $alternatives = [];
        foreach ($rule->rules as $alternative) {
            $alternatives[] = $alternative;
            if ((string) $alternative->rules[0] === '<name is "T_TAG_TYPE_ALIAS">') {
                $alternatives[] = $invalidTypeAliasTag;
            }
        }
        $rule->setRules($alternatives);
    }
}

$found = [];

foreach ($compiler->parser->rules as $rule) {
    if ($rule->name === null || !isset($entrypoints[$rule->name])) {
        continue;
    }

    $found[$rule->name] = true;

    if (in_array($rule->name, ENTRYPOINTS, true)) {
        $rule->setEntrypoint();
    }

    if (isset($reducers[$rule->name])) {
        // The reducer is only a mark that keeps the rule in the trace: the
        // parser written calls the one of the AST builder
        $rule->setReducer(static fn () => null);
    }
}

$missing = array_diff_key($entrypoints, $found);
if ($missing !== []) {
    fwrite(STDERR, sprintf("The grammar has no rule named %s\n", implode(', ', array_keys($missing))));
    exit(1);
}

/**
 * Joins an alternation with the alternations nested into it.
 *
 * Upstream leaves them nested, so that an alternation the token at hand cannot
 * start is skipped along with all of its alternatives by a single lookup. Here
 * every alternation is entered through its choice prediction, which already
 * names the very alternatives worth trying, so a nested one is only a call per
 * token more: the free-form text of a PHPDoc is read by three alternations
 * nested into each other, one call each per word.
 */
final class NestedAlternationParserCompilerPass implements ParserCompilerPassInterface
{

    public function process(ParserBuildingContext $context, LexerBuilderResult $lexer): void
    {
        foreach ($context->rules as $rule) {
            if (!$rule instanceof AlternationRuleDefinition) {
                continue;
            }

            $rule->setRules($this->expand($rule, $context));
        }

        $context->rules = $context->collectReachableRules();
    }

    /**
     * @return list<RuleDefinition>
     */
    private function expand(AlternationRuleDefinition $rule, ParserBuildingContext $context): array
    {
        $result = [];

        foreach ($rule->rules as $child) {
            if (
                $child instanceof AlternationRuleDefinition
                && $child->reducer === null
                && $child->message === null
                && !$context->isEntrypoint($child)
            ) {
                foreach ($this->expand($child, $context) as $inner) {
                    $result[] = $inner;
                }

                continue;
            }

            $result[] = $child;
        }

        return $result;
    }

}

$compiler->parser->addCompilerPass(new NestedAlternationParserCompilerPass(), ParserBuilder::PASS_PRIORITY_OPTIMIZE - 1);

$result = $compiler->build();
$parser = $result->parser;

/**
 * Writes the choice prediction down for every token there is.
 *
 * Upstream only writes a row for a token some alternative is known to begin
 * with, and an alternation is read by trying every one of its alternatives at
 * a token it has no row for. An alternative that may begin with anything at
 * all, like a line of text, is therefore what every other alternative is tried
 * along with, on every token nothing else begins with: a line of a description
 * is tried as each of the tags first. A row for each of those tokens says that
 * only such alternatives are worth trying.
 *
 * @return array<int, array<int, list<int>>>
 */
function completeChoicePrediction(Phplrt\Parser\Builder\ParserBuilderResult $parser, array $tokenIds): array
{
    $result = $parser->choicePrediction;

    foreach ($parser->grammar as $id => $rule) {
        if (!$rule instanceof Alternation) {
            continue;
        }

        $firsts = [];
        foreach ($rule->ruleIds as $alternative) {
            $definition = $parser->grammar[$alternative];
            $firsts[$alternative] = $definition instanceof Lexeme
                ? [$definition->tokenId => true]
                : $parser->startPrediction[$alternative] ?? null;
        }

        foreach ($tokenIds as $token) {
            if (isset($result[$id][$token])) {
                continue;
            }

            $candidates = [];
            foreach ($rule->ruleIds as $alternative) {
                $first = $firsts[$alternative];
                if ($first !== null && !isset($first[$token])) {
                    continue;
                }

                $definition = $parser->grammar[$alternative];
                $candidates[] = $definition instanceof Lexeme && $definition->tokenId === $token
                    ? -$alternative - 1
                    : $alternative;
            }

            if ($candidates === $rule->ruleIds) {
                continue;
            }

            $result[$id][$token] = $candidates;
        }

        if (isset($result[$id])) {
            ksort($result[$id]);
        }
    }

    ksort($result);

    return $result;
}

$tokenIds = array_keys($result->lexer->names);
// The end of the input, which the runtime reports on its own
$tokenIds[] = -1;

$choicePrediction = completeChoicePrediction($parser, $tokenIds);

/**
 * @param mixed $value
 */
function export($value): string
{
    if (is_array($value)) {
        if ($value === []) {
            return '[]';
        }

        $isList = array_is_list($value);
        $items = [];
        foreach ($value as $key => $item) {
            $items[] = ($isList ? '' : var_export($key, true) . ' => ') . export($item);
        }

        return '[' . implode(', ', $items) . ']';
    }

    return var_export($value, true);
}

$rules = [];
foreach ($parser->constants as $name => $id) {
    if (in_array($name, ENTRYPOINTS, true)) {
        $rules[$name] = $id;
    }
}

$reducerIds = [];
$abstracts = [];
foreach ($reducers as $name => $reducer) {
    $id = $parser->constants[$name];
    $reducerIds[$id] = $reducer;
    $abstracts[] = $reducer[1]
        ? sprintf("\t/**\n\t * @param list<mixed> \$children\n\t * @return mixed\n\t */\n\tabstract protected function %s(Context \$context, \$children);\n", $reducer[0])
        : sprintf("\t/**\n\t * @return mixed\n\t */\n\tabstract protected function %s(Context \$context);\n", $reducer[0]);
}

// The tokens no reducer reads: they are only what the other tokens are
// written in between
$silent = [];
foreach (['T_PHPDOC_EOL', 'T_OPEN_PHPDOC', 'T_CLOSE_PHPDOC', 'T_COMMENT'] as $name) {
    $silent[] = array_flip($result->lexer->names)[$name];
}

/**
 * Whether the given token is one the lexer does not read on its own: a token
 * it reads as another one, and only "TokenStream" tells apart, by its value or
 * by its surroundings. See "doc/grammars/lexemes.pp3".
 */
function isClassified(string $name): bool
{
    return strncmp($name, 'T_KEYWORD_', 10) === 0
        || strncmp($name, 'T_TAG_', 6) === 0
        || ($name !== 'T_HORIZONTAL_WS' && substr($name, -3) === '_WS')
        || $name === 'T_OPEN_ANGLE_BRACKET_HTML';
}

/**
 * The pattern of "Lexer": every token it reads on its own, in the order the
 * grammar declares them, marked by the identifier the grammar gives it. Along
 * with it, the type of "Lexer" each of those tokens is.
 */
if (($result->lexer->names[0] ?? null) !== 'T_HORIZONTAL_WS') {
    // "TokenStream::tokenize()" tells the whitespace by this very identifier
    fwrite(STDERR, "T_HORIZONTAL_WS has to be the first token of doc/grammars/lexemes.pp3\n");
    exit(1);
}

$lexerPatterns = [];
$lexerTypes = [];
foreach ($result->lexer->tokens as $id => $definition) {
    $name = $result->lexer->names[$id] ?? null;
    if ($name === null || isClassified($name)) {
        continue;
    }

    $type = 'PHPStan\\PhpDocParser\\Lexer\\Lexer::TOKEN_' . substr($name, 2);
    if (!defined($type)) {
        fwrite(STDERR, sprintf("The lexer has no token %s for %s\n", $type, $name));
        exit(1);
    }

    $lexerPatterns[] = '(?:' . $definition->regex . ')(*MARK:' . $id . ')';
    $lexerTypes[$id] = constant($type);
}
$lexerPattern = '~' . implode('|', $lexerPatterns) . '~Asi';

[$methods, $constants] = (new ParserGenerator($parser, $choicePrediction, $reducerIds, $tokenIds, $silent))->generate($rules);

$template = file_get_contents(__DIR__ . '/GeneratedParser.template.php');
$generated = strtr($template, [
    '/*RULES*/[]' => export($rules),
    '/*TOKENS*/[]' => export(array_flip($result->lexer->names)),
    '/*EXPECTATIONS*/[]' => export($parser->expectations),
    "/*LEXER_PATTERN*/''" => export($lexerPattern),
    '/*LEXER_TYPES*/[]' => export($lexerTypes),
    "\t/*CONSTANTS*/\n" => $constants,
    "\t/*REDUCERS*/\n" => implode("\n", $abstracts),
    "\t/*METHODS*/\n" => $methods,
]);

if (!is_dir(dirname($output))) {
    mkdir(dirname($output), 0777, true);
}

file_put_contents($output, $generated);

fwrite(STDERR, sprintf("%d rules written to %s\n", count($parser->grammar), $output));
