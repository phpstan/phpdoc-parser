<?php

/**
 * Writes down inputs a grammar of "doc/grammars" says are well-formed.
 *
 * A grammar says what a type may be written as, so it can be walked the other
 * way round and asked for types instead of being asked about one. The walk
 * gives a list of tokens rather than a text, and the text is written of them
 * afterwards.
 *
 * @internal this is a development tool, not part of the library
 */

declare(strict_types=1);

namespace PHPStan\PhpDocParser\Tools\Fuzzer;

use Phplrt\Parser\Grammar\Alternation;
use Phplrt\Parser\Grammar\Concatenation;
use Phplrt\Parser\Grammar\Lexeme;
use Phplrt\Parser\Grammar\Optional;
use Phplrt\Parser\Grammar\Predicate;
use Phplrt\Parser\Grammar\Repetition;
use Phplrt\Parser\Grammar\RuleInterface;

final class Generator
{
    /**
     * How a token is written down.
     *
     * A token that stands for more than one text is written as any of them, so
     * that the inputs differ in what they are written of and not only in how
     * they are put together.
     *
     * @var array<non-empty-string, list<string>>
     */
    private const LITERALS = [
        'T_KEYWORD_CONTRAVARIANT' => ['contravariant'],
        'T_KEYWORD_COVARIANT' => ['covariant'],
        'T_KEYWORD_NON_EMPTY_ARRAY' => ['non-empty-array'],
        'T_KEYWORD_NON_EMPTY_LIST' => ['non-empty-list'],
        'T_KEYWORD_SUPER' => ['super'],
        'T_KEYWORD_STATIC' => ['static'],
        'T_KEYWORD_FROM' => ['from'],
        'T_KEYWORD_OBJECT' => ['object'],
        'T_KEYWORD_ARRAY' => ['array'],
        'T_KEYWORD_LIST' => ['list'],
        'T_KEYWORD_NOT' => ['not'],
        'T_KEYWORD_IS' => ['is'],
        'T_KEYWORD_OF' => ['of'],
        'T_KEYWORD_AS' => ['as'],
        'T_KEYWORD_FALSE' => ['false', 'FALSE'],
        'T_KEYWORD_TRUE' => ['true', 'True'],
        'T_KEYWORD_NULL' => ['null', 'NULL'],
        'T_KEYWORD_ARRAY_ANY_CASE' => ['ARRAY', 'Array'],

        'T_FLOAT' => ['1.0', '0.5', '-1.5e3', '1_000.5', '.5'],
        'T_INTEGER' => ['0', '1', '123', '-5', '0x1F', '0b101', '0o17', '1_000'],
        'T_SINGLE_QUOTED_STRING' => ["'foo'", "'a b'", "''"],
        'T_DOUBLE_QUOTED_STRING' => ['"foo"', '"a b"', '""'],

        'T_IDENTIFIER' => ['Foo', 'int', 'string', 'positive-int', 'Bar\\Baz', '\\Foo', 'a'],
        'T_THIS_VARIABLE' => ['$this'],
        'T_VARIABLE' => ['$foo', '$value'],

        'T_REFERENCE' => ['&'],
        'T_UNION' => ['|'],
        'T_INTERSECTION' => ['&'],
        'T_NULLABLE' => ['?'],
        'T_NEGATED' => ['!'],

        'T_OPEN_PARENTHESES' => ['('],
        'T_CLOSE_PARENTHESES' => [')'],
        'T_OPEN_ANGLE_BRACKET' => ['<'],
        'T_CLOSE_ANGLE_BRACKET' => ['>'],
        'T_OPEN_SQUARE_BRACKET' => ['['],
        'T_CLOSE_SQUARE_BRACKET' => [']'],
        'T_OPEN_CURLY_BRACKET' => ['{'],
        'T_CLOSE_CURLY_BRACKET' => ['}'],

        'T_COMMA' => [','],
        'T_COMMENT' => ['// a comment'],
        'T_VARIADIC' => ['...'],
        'T_DOUBLE_COLON' => ['::'],
        'T_DOUBLE_ARROW' => ['=>'],
        'T_ARROW' => ['->'],
        'T_EQUAL' => ['='],
        'T_COLON' => [':'],
        'T_WILDCARD' => ['*'],

        'T_PHPDOC_EOL' => ["\n * "],
        'T_OTHER' => ['#other'],

        'T_OPEN_PHPDOC' => ['/** '],
        'T_CLOSE_PHPDOC' => ['*/'],
        'T_PHPDOC_TAG' => ['@author'],
        'T_PHPDOC_TAG_WS' => ['@author'],
        // A tag only reads as a Doctrine one where a "\\" or a "_" follows the
        // "@" straight away: anything else is read as an ordinary tag first,
        // and "@Foo_Bar" comes back as "@Foo" followed by the name "_Bar"
        'T_DOCTRINE_TAG' => ['@\\Foo'],
        'T_DOCTRINE_TAG_WS' => ['@\\Foo'],

        'T_OPEN_PARENTHESES_WS' => ['('],
        'T_OPEN_CURLY_BRACKET_WS' => ['{'],
        'T_OPEN_SQUARE_BRACKET_WS' => ['['],
        'T_WILDCARD_WS' => ['*'],
        'T_OPEN_ANGLE_BRACKET_HTML' => ['<'],

        'T_TAG_PARAM' => ['@param', '@phpstan-param'],
        'T_TAG_PARAM_IMMEDIATELY_INVOKED_CALLABLE' => ['@param-immediately-invoked-callable'],
        'T_TAG_PARAM_LATER_INVOKED_CALLABLE' => ['@param-later-invoked-callable'],
        'T_TAG_PARAM_CLOSURE_THIS' => ['@param-closure-this'],
        'T_TAG_PURE_UNLESS_CALLABLE_IS_IMPURE' => ['@pure-unless-callable-is-impure'],
        'T_TAG_PURE_UNLESS_PARAMETER_PASSED' => ['@pure-unless-parameter-passed'],
        'T_TAG_VAR' => ['@var'],
        'T_TAG_RETURN' => ['@return'],
        'T_TAG_THROWS' => ['@throws'],
        'T_TAG_MIXIN' => ['@mixin'],
        'T_TAG_REQUIRE_EXTENDS' => ['@phpstan-require-extends'],
        'T_TAG_REQUIRE_IMPLEMENTS' => ['@phpstan-require-implements'],
        'T_TAG_SEALED' => ['@phpstan-sealed'],
        'T_TAG_DEPRECATED' => ['@deprecated'],
        'T_TAG_PROPERTY' => ['@property', '@property-read'],
        'T_TAG_METHOD' => ['@method'],
        'T_TAG_TEMPLATE' => ['@template'],
        'T_TAG_EXTENDS' => ['@extends'],
        'T_TAG_IMPLEMENTS' => ['@implements'],
        'T_TAG_USE' => ['@use'],
        'T_TAG_TYPE_ALIAS' => ['@phpstan-type'],
        'T_TAG_TYPE_ALIAS_IMPORT' => ['@phpstan-import-type'],
        'T_TAG_ASSERT' => ['@phpstan-assert'],
        'T_TAG_SELF_OUT' => ['@phpstan-self-out'],
        'T_TAG_PARAM_OUT' => ['@param-out'],
    ];

    /**
     * The tokens whose meaning the whitespace around them decides: what such a
     * token is called is exactly what has to be written around it.
     *
     * @var array<non-empty-string, string>
     */
    private const SPACING_BEFORE = [
        'T_PHPDOC_TAG' => '',
        'T_DOCTRINE_TAG' => '',
        'T_OPEN_CURLY_BRACKET' => '',
        'T_OPEN_SQUARE_BRACKET' => '',
        'T_OPEN_CURLY_BRACKET_WS' => ' ',
        'T_OPEN_SQUARE_BRACKET_WS' => ' ',
        'T_PHPDOC_EOL' => '',
    ];

    /**
     * @var array<non-empty-string, string>
     */
    private const SPACING_AFTER = [
        'T_WILDCARD' => '',
        'T_WILDCARD_WS' => ' ',
        'T_PHPDOC_EOL' => '',
    ];

    /**
     * How many tokens an input may be written of before the walk starts looking
     * for the shortest way out of the rule it is in.
     */
    private const SIZE_LIMIT = 24;

    /**
     * The fewest tokens each rule can be written of, which is what the walk
     * takes the shortest way out of a rule by.
     *
     * @var array<int, int>
     */
    private array $sizes;

    /**
     * @param list<RuleInterface> $grammar
     * @param array<int, non-empty-string> $names
     */
    public function __construct(
        private array $grammar,
        private array $names,
        private int $initial,
    ) {
        $this->sizes = $this->calculateSizes();
    }

    /**
     * @return list<int>|null the tokens of an input, or "null" for a walk that
     *         has written nothing
     */
    public function generate(): ?array
    {
        $tokens = [];
        $this->walk($this->initial, $tokens);

        return $tokens === [] ? null : $tokens;
    }

    /**
     * @param list<int> $tokens
     */
    private function walk(int $rule, array &$tokens): void
    {
        $definition = $this->grammar[$rule];

        if ($definition instanceof Lexeme) {
            $tokens[] = $definition->tokenId;

            return;
        }

        if ($definition instanceof Predicate) {
            // A predicate reads nothing at all, and what it looks ahead at is
            // written by whatever follows it
            return;
        }

        if ($definition instanceof Concatenation) {
            foreach ($definition->ruleIds as $inner) {
                $this->walk($inner, $tokens);
            }

            return;
        }

        if ($definition instanceof Alternation) {
            $this->walk($this->choose($definition->ruleIds, $tokens), $tokens);

            return;
        }

        if ($definition instanceof Optional) {
            if ($this->isLongEnough($tokens) || \mt_rand(0, 2) === 0) {
                return;
            }

            $this->walk($definition->ruleId, $tokens);

            return;
        }

        \assert($definition instanceof Repetition);

        $max = $definition->max === \INF ? $definition->min + 2 : (int) $definition->max;
        $times = $this->isLongEnough($tokens)
            ? $definition->min
            : \mt_rand($definition->min, \max($definition->min, \min($max, $definition->min + 2)));

        for ($i = 0; $i < $times; $i++) {
            $this->walk($definition->ruleId, $tokens);
        }
    }

    /**
     * @param list<int> $alternatives
     * @param list<int> $tokens
     */
    private function choose(array $alternatives, array $tokens): int
    {
        if (!$this->isLongEnough($tokens)) {
            return $alternatives[\mt_rand(0, \count($alternatives) - 1)];
        }

        // An input that has grown long enough goes on with the alternative that
        // finishes it soonest, so that a grammar written of itself still stops
        $shortest = $alternatives[0];

        foreach ($alternatives as $alternative) {
            if ($this->sizes[$alternative] >= $this->sizes[$shortest]) {
                continue;
            }

            $shortest = $alternative;
        }

        return $shortest;
    }

    /**
     * @param list<int> $tokens
     */
    private function isLongEnough(array $tokens): bool
    {
        return \count($tokens) >= self::SIZE_LIMIT;
    }

    /**
     * Writes the given tokens down as the text they are read from.
     *
     * @param list<int> $tokens
     */
    public function render(array $tokens): string
    {
        $text = '';

        foreach ($tokens as $index => $token) {
            $name = $this->names[$token] ?? null;
            $literals = $name === null ? null : (self::LITERALS[$name] ?? null);

            if ($literals === null) {
                // The end of the input is written by the text simply stopping
                continue;
            }

            if ($index > 0) {
                $after = self::SPACING_AFTER[$this->names[$tokens[$index - 1]] ?? ''] ?? null;

                $text .= $after ?? (self::SPACING_BEFORE[$name] ?? ' ');
            }

            $text .= $literals[\mt_rand(0, \count($literals) - 1)];
        }

        return $text;
    }

    /**
     * The tokens of an input, leaving out the ones nothing is written for.
     *
     * @param list<int> $tokens
     * @return list<int>
     */
    public function written(array $tokens): array
    {
        $result = [];

        foreach ($tokens as $token) {
            if (!isset(self::LITERALS[$this->names[$token] ?? ''])) {
                continue;
            }

            $result[] = $token;
        }

        return $result;
    }

    /**
     * @return array<int, int>
     */
    private function calculateSizes(): array
    {
        $sizes = [];

        foreach ($this->grammar as $rule => $definition) {
            $sizes[$rule] = $definition instanceof Lexeme ? 1 : \PHP_INT_MAX;
        }

        // The fewest tokens a rule is written of depends on the rules it is
        // written of, so the answer is looked for until it stops changing
        do {
            $changed = false;

            foreach ($this->grammar as $rule => $definition) {
                $size = $this->calculateSize($definition, $sizes);

                if ($size >= $sizes[$rule]) {
                    continue;
                }

                $sizes[$rule] = $size;
                $changed = true;
            }
        } while ($changed);

        return $sizes;
    }

    /**
     * @param array<int, int> $sizes
     */
    private function calculateSize(RuleInterface $definition, array $sizes): int
    {
        if ($definition instanceof Lexeme) {
            return 1;
        }

        if ($definition instanceof Predicate || $definition instanceof Optional) {
            return 0;
        }

        if ($definition instanceof Repetition) {
            return $definition->min === 0 ? 0 : $definition->min * $sizes[$definition->ruleId];
        }

        if ($definition instanceof Alternation) {
            $size = \PHP_INT_MAX;

            foreach ($definition->ruleIds as $inner) {
                $size = \min($size, $sizes[$inner]);
            }

            return $size;
        }

        \assert($definition instanceof Concatenation);

        $size = 0;

        foreach ($definition->ruleIds as $inner) {
            if ($sizes[$inner] === \PHP_INT_MAX) {
                return \PHP_INT_MAX;
            }

            $size += $sizes[$inner];
        }

        return $size;
    }
}
