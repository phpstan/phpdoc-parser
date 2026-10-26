<?php

declare(strict_types=1);

use Phplrt\Parser\Builder\ParserBuilderResult;
use Phplrt\Parser\Grammar\Alternation;
use Phplrt\Parser\Grammar\Concatenation;
use Phplrt\Parser\Grammar\Lexeme;
use Phplrt\Parser\Grammar\Optional;
use Phplrt\Parser\Grammar\Predicate;
use Phplrt\Parser\Grammar\Repetition;
use Phplrt\Parser\Grammar\RuleInterface;

/**
 * Writes a compiled grammar down as a recursive descent parser of its own.
 *
 * The parser written reads exactly what the runtime of phplrt reads with the
 * same tables, and fails at the same token expecting the same ones: every
 * rule is a method doing what "RecursiveDescentTracer::match()" does for that
 * very rule, with the predictions it would look up written into the code.
 *
 * Where the runtime writes a trace down and builds the values out of it once
 * the whole input has been read, the parser written builds them as it reads,
 * the way "TraceReducer" would: the values a rule leaves behind are pushed onto
 * a stack, and a rule that is kept replaces them with its own value once it
 * has been read. A rule that is given up drops whatever it has pushed, along
 * with the comments its nodes have taken.
 *
 * Every rule is written twice: a method reading it and building its value
 * ("b" followed by the identifier of the rule), and one only reading it ("s"),
 * which is what predicates, the rules whose reducer reads nothing of what they
 * contain and the explanations of errors use. Only the methods reachable from
 * the entrypoints are written.
 *
 * @internal this is a development tool, not part of the library
 */
final class ParserGenerator
{

    /** how deep rules are written out into each other */
    private const INLINE_DEPTH = 6;

    /** how long the code of a rule written out into another one may be */
    private const INLINE_SIZE = 800;

    private const UNEXPECTED = "if (\$this->p > \$this->f) {\n\t\$this->f = \$this->p;\n\t\$this->er = [];\n}\n";

    /** @var array<int, RuleInterface> */
    private array $grammar;

    /** @var array<int, array<int, true>|null> */
    private array $start;

    /** @var array<int, array<int, list<int>>> */
    private array $choice;

    /** @var array<int, list<int>> */
    private array $sequence;

    /** @var array<int, bool> */
    private array $kept;

    /** @var array<int, array{string, bool}> the reducer of each rule, and whether it reads the children */
    private array $reducers;

    /** @var list<int> every token id, along with the end of the input */
    private array $tokenIds;

    /** @var array<int, true> the tokens no reducer reads, which are never pushed */
    private array $silent;

    /** @var array<string, string> the sets of tokens written as constants, by their name */
    private array $sets = [];

    /** @var array<string, true> the methods asked for */
    private array $requested = [];

    /** @var list<string> the methods still to write */
    private array $queue = [];

    private int $labels = 0;

    /** @var array<string, true> the rules being written out into each other */
    private array $stack = [];

    /**
     * @param array<int, array<int, list<int>>> $choicePrediction
     * @param array<int, array{string, bool}> $reducers
     * @param list<int> $tokenIds
     * @param list<int> $silent the tokens no reducer reads
     */
    public function __construct(ParserBuilderResult $parser, array $choicePrediction, array $reducers, array $tokenIds, array $silent)
    {
        $this->silent = array_fill_keys($silent, true);
        $this->grammar = $parser->grammar;
        $this->start = $parser->startPrediction;
        $this->choice = $choicePrediction;
        $this->sequence = $parser->sequencePrediction;
        $this->kept = $parser->kept;
        $this->reducers = $reducers;
        $this->tokenIds = $tokenIds;

        foreach ($this->grammar as $id => $rule) {
            if ($rule instanceof Lexeme && !$rule->keep) {
                throw new LogicException(sprintf('Rule %d: a token not kept is not supported', $id));
            }

            if ($rule instanceof Predicate && $this->kept[$id]) {
                throw new LogicException(sprintf('Rule %d: a kept predicate is not supported', $id));
            }
        }
    }

    /**
     * @param array<string, int> $entrypoints
     * @return array{string, string} the methods and the constants of the class
     */
    public function generate(array $entrypoints): array
    {
        $dispatch = [];
        foreach ($entrypoints as $name => $id) {
            $dispatch[] = sprintf(
                "\t\t\tcase %d: // %s\n\t\t\t\treturn \$build ? \$this->%s() : \$this->%s();",
                $id,
                $name,
                $this->request($id, true),
                $this->request($id, false),
            );
        }

        $methods = [];
        while ($this->queue !== []) {
            $name = array_shift($this->queue);
            $methods[$name] = $this->method((int) substr($name, 1), $name[0] === 'b');
        }

        ksort($methods, SORT_NATURAL);

        $code = "\t/**\n\t * Reads the given rule from the given position.\n\t *\n\t * @phpstan-impure\n\t */\n"
            . "\tprotected function parseRule(int \$rule, int \$position, bool \$build): bool\n\t{\n"
            . "\t\t\$this->p = \$position;\n\t\t\$this->f = -1;\n\t\t\$this->er = [];\n\n"
            . "\t\tswitch (\$rule) {\n" . implode("\n", $dispatch) . "\n\t\t\tdefault:\n"
            . "\t\t\t\tthrow new \\LogicException('Not an entrypoint: ' . \$rule);\n\t\t}\n\t}\n\n"
            . implode("\n", $methods);

        $constants = '';
        ksort($this->sets, SORT_NATURAL);
        foreach ($this->sets as $name => $value) {
            $constants .= sprintf("\tprivate const %s = %s;\n", $name, $value);
        }

        $expected = [];
        foreach ($this->grammar as $id => $rule) {
            $tokens = $this->expected($id);
            if ($tokens === []) {
                continue;
            }

            sort($tokens);
            $expected[] = $id . '=>[' . implode(',', $tokens) . ']';
        }

        $constants .= sprintf("\n\t/** the tokens each rule is expected to begin with */\n\tprivate const EXPECTED = [%s];\n", implode(',', $expected));

        return [$code, $constants];
    }

    /**
     * The tokens a failure of the given rule says have been expected: the ones
     * it may begin with. An alternation the compiler predicts nothing of,
     * because it may be a predicate as well, expects what its alternatives do,
     * a predicate expecting a rule being one expecting what the rule does.
     *
     * @param array<int, true> $visited
     * @return list<int>
     */
    private function expected(int $id, array $visited = []): array
    {
        $rule = $this->grammar[$id];
        if ($rule instanceof Lexeme) {
            return [$rule->tokenId];
        }

        if (isset($this->start[$id])) {
            return array_keys($this->start[$id]);
        }

        $visited[$id] = true;
        $inner = [];
        if ($rule instanceof Alternation) {
            $inner = $rule->ruleIds;
        } elseif ($rule instanceof Predicate && $rule->isExpected) {
            $inner = [$rule->ruleId];
        }

        $tokens = [];
        foreach ($inner as $alternative) {
            if (isset($visited[$alternative])) {
                continue;
            }

            foreach ($this->expected($alternative, $visited) as $token) {
                $tokens[$token] = true;
            }
        }

        return array_keys($tokens);
    }

    private function request(int $id, bool $build): string
    {
        $name = ($build ? 'b' : 's') . $id;
        if (!isset($this->requested[$name])) {
            $this->requested[$name] = true;
            $this->queue[] = $name;
        }

        return $name;
    }

    private function unique(): int
    {
        return ++$this->labels;
    }

    // -------------------------------------------------------------------------

    private function method(int $id, bool $build): string
    {
        $this->stack = [($build ? 'b' : 's') . $id => true];

        return sprintf("\tprivate function %s%d(): bool\n\t{\n", $build ? 'b' : 's', $id)
            . self::indent($this->body($id, $build, 'return false;'), 2)
            . "\n\t\treturn true;\n\t}\n";
    }

    /**
     * The code reading the given rule: it goes on past its end in case the
     * rule has been read, and runs the given code once it has given back
     * whatever it has read in case it has not.
     */
    private function body(int $id, bool $build, string $fail): string
    {
        $rule = $this->grammar[$id];
        $reducer = $this->reducers[$id] ?? null;
        $kept = $build && $reducer !== null;
        $u = $this->unique();
        // The variables of a rule are named after how deep it is written out,
        // which is all it takes for them not to be overwritten by the rules
        // inside it: a function initializes every variable it has on every
        // call, so there are as few of them as possible
        $d = count($this->stack);

        // A rule whose reducer reads nothing of what it contains is only read
        if ($kept && !$reducer[1]) {
            return "\$o{$d} = \$this->p;\n"
                . $this->match($id, false, $fail)
                . sprintf("\$this->v[\$this->n++] = \$this->%s(\$this->context(\$o%d));\n", $reducer[0], $d);
        }

        $code = '';
        $start = $this->start[$id] ?? null;
        if ($start !== null) {
            $code .= sprintf(
                "if (!%s) {\n\tif (\$this->p > \$this->f) {\n\t\t\$this->rec(%d);\n\t}\n\n%s}\n\n",
                $this->in(array_keys($start)),
                $id,
                self::indent($fail, 1),
            );
        }

        // Every rule pushes the tokens it reads while building, but for a
        // predicate, which reads nothing
        $pushes = $build && !$rule instanceof Predicate;
        $code .= "\$o{$d} = \$this->p;\n";
        if ($pushes) {
            $code .= "\$m{$d} = \$this->n;\n\$cs{$d} = \$this->comments;\n";
        }

        $failure = "goto f{$u};";
        if ($rule instanceof Concatenation) {
            $code .= $this->concatenation($id, $rule, $build, $failure);
        } elseif ($rule instanceof Alternation) {
            $code .= $this->alternation($id, $rule, $build, $failure);
        } elseif ($rule instanceof Repetition) {
            $code .= $this->repetition($rule, $build, $failure);
        } elseif ($rule instanceof Optional) {
            $code .= $this->optional($rule->ruleId, $build);
        } elseif ($rule instanceof Predicate) {
            $code .= $this->predicate($rule, $failure);
        } else {
            throw new LogicException(sprintf('Rule %d: unsupported %s', $id, get_class($rule)));
        }

        if (strpos($code, $failure) !== false) {
            $code .= "goto k{$u};\nf{$u}:\n\$this->p = \$o{$d};\n"
                . ($pushes ? "\$this->n = \$m{$d};\n\$this->comments = \$cs{$d};\n" : '')
                . $fail . "\nk{$u}:\n";
        }

        if ($kept) {
            $code .= sprintf(
                "\$this->v[\$m%1\$d] = \$this->%2\$s(\$this->context(\$o%1\$d), \$this->n === \$m%1\$d + 1 && !\\is_array(\$this->v[\$m%1\$d]) ? [\$this->v[\$m%1\$d]] : \$this->merge(\$m%1\$d));\n\$this->n = \$m%1\$d + 1;\n",
                $d,
                $reducer[0],
            );
        }

        return $code;
    }

    /**
     * The code reading the given rule where another one expects it: written
     * out right there in case it is small enough, called otherwise.
     */
    private function match(int $inner, bool $build, string $fail): string
    {
        $definition = $this->grammar[$inner];
        $tokens = $this->tokens($inner);
        if ($tokens !== null) {
            return sprintf(
                "if (!%s) {\n\tif (\$this->p %s \$this->f) {\n\t\t\$this->rec(%d);\n\t}\n\n%s}\n\n%s",
                $this->in($tokens[0]),
                $tokens[1],
                $inner,
                self::indent($fail, 1),
                $this->read($inner, $build),
            );
        }

        if ($definition instanceof Predicate) {
            return $this->predicate($definition, $fail);
        }

        $key = ($build ? 'b' : 's') . $inner;
        if (!isset($this->stack[$key]) && count($this->stack) < self::INLINE_DEPTH) {
            $this->stack[$key] = true;
            $labels = $this->labels;
            $sets = $this->sets;
            $requested = $this->requested;
            $queue = $this->queue;
            $code = $this->body($inner, $build, $fail);
            unset($this->stack[$key]);

            if (strlen($code) <= self::INLINE_SIZE) {
                return "// {$key}\n" . $code;
            }

            // Too much to write out: the methods asked for along the way are
            // not asked for after all
            $this->labels = $labels;
            $this->sets = $sets;
            $this->requested = $requested;
            $this->queue = $queue;
        }

        return sprintf("if (!\$this->%s()) {\n%s}\n", $this->request($inner, $build), self::indent($fail, 1));
    }

    private function concatenation(int $id, Concatenation $rule, bool $build, string $fail): string
    {
        $code = '';
        foreach ($this->sequence[$id] ?? $rule->ruleIds as $inner) {
            if ($inner >= 0) {
                $code .= $this->match($inner, $build, $fail);
                continue;
            }

            // An element that may be left out
            $inner = -$inner - 1;
            $element = $this->grammar[$inner];
            if ($element instanceof Predicate) {
                $tokens = $this->start[$element->ruleId] ?? null;
                $predicate = $this->predicate($element, $fail, true);
                $code .= $tokens === null
                    ? $predicate
                    : sprintf("if (%s) {\n%s}\n", $this->in(array_keys($tokens)), self::indent($predicate, 1));
            } elseif ($this->tokens($inner) !== null) {
                $code .= $this->optional($inner, $build);
            } else {
                $u = $this->unique();
                $tokens = $this->start[$inner] ?? null;
                $read = $this->match($inner, $build, "goto n{$u};") . "n{$u}:\n";
                $code .= $tokens === null
                    ? $read
                    : sprintf(
                        "if (%s) {\n%s} elseif (\$this->p > \$this->f) {\n\t\$this->rec(%d);\n}\n",
                        $this->in(array_keys($tokens)),
                        self::indent($read, 1),
                        $inner,
                    );
            }
        }

        return $code;
    }

    private function alternation(int $id, Alternation $rule, bool $build, string $fail): string
    {
        $ok = 'a' . $this->unique();

        // The alternatives worth trying at each token
        $lists = [];
        $groups = [];
        foreach ($this->tokenIds as $token) {
            $list = $this->choice[$id][$token] ?? $rule->ruleIds;
            $key = implode(',', $list);
            $lists[$key] = $list;
            $groups[$key][] = $token;
        }

        uasort($groups, static fn (array $a, array $b): int => count($b) <=> count($a));

        $code = '';
        if (count($groups) === 1) {
            $code .= $this->attempts($lists[array_key_first($groups)], $build, $ok);
        } else {
            $code .= "switch (\$this->ids[\$this->p]) {\n";
            $first = true;
            foreach ($groups as $key => $tokens) {
                if ($first) {
                    $code .= "\tdefault:\n";
                    $first = false;
                } else {
                    foreach ($tokens as $token) {
                        $code .= sprintf("\tcase %d:\n", $token);
                    }
                }

                $code .= self::indent($this->attempts($lists[$key], $build, $ok), 2) . "\t\tbreak;\n";
            }

            $code .= "}\n";
        }

        return $code . sprintf("\nif (\$this->p > \$this->f) {\n\t\$this->rec(%d);\n}\n\n%s\n%s:\n", $id, $fail, $ok);
    }

    /**
     * @param list<int> $alternatives
     */
    private function attempts(array $alternatives, bool $build, string $ok): string
    {
        $code = '';
        foreach ($alternatives as $inner) {
            if ($inner < 0) {
                // A token predicted by the very token the reading is at
                return $code . $this->push(-$inner - 1, $build) . "goto {$ok};\n";
            }

            $tokens = $this->tokens($inner);
            if ($tokens !== null) {
                $code .= sprintf(
                    "if (%s) {\n%s\tgoto %s;\n}\n\nif (\$this->p %s \$this->f) {\n\t\$this->rec(%d);\n}\n",
                    $this->in($tokens[0]),
                    self::indent($this->read($inner, $build), 1),
                    $ok,
                    $tokens[1],
                    $inner,
                );
                continue;
            }

            $next = 'n' . $this->unique();
            $read = $this->match($inner, $build, "goto {$next};") . "goto {$ok};\n";
            $code .= $read . "{$next}:\n";
        }

        return $code;
    }

    private function repetition(Repetition $rule, bool $build, string $fail): string
    {
        $inner = $rule->ruleId;
        $min = $rule->min;
        $max = $rule->max;
        $counted = $min > 0 || $max !== INF;
        $u = $this->unique();
        $d = count($this->stack);
        $end = "goto e{$u};";

        $code = $counted ? "\$k{$d} = 0;\n" : '';
        $code .= $max === INF ? "while (true) {\n" : sprintf("while (\$k%d < %d) {\n", $d, $max);
        $code .= "\t\$b{$d} = \$this->p;\n";

        $body = '';
        $tokens = $this->tokens($inner);
        if ($tokens !== null) {
            $body .= sprintf(
                "if (!%s) {\n\tif (\$b%d %s \$this->f) {\n\t\t\$this->rec(%d);\n\t}\n\n\t%s\n}\n\n%s",
                $this->in($tokens[0]),
                $d,
                $tokens[1],
                $inner,
                $end,
                $this->read($inner, $build),
            );
        } else {
            $start = $this->start[$inner] ?? null;
            if ($start !== null) {
                $body .= sprintf(
                    "if (!%s) {\n\tif (\$b%d > \$this->f) {\n\t\t\$this->rec(%d);\n\t}\n\n\t%s\n}\n\n",
                    $this->in(array_keys($start)),
                    $d,
                    $inner,
                    $end,
                );
            }

            $body .= $this->match($inner, $build, $end);
            // A body reading nothing would be read forever
            $body .= "if (\$this->p === \$b{$d}) {\n" . ($counted ? sprintf("\t\$k%d = %d;\n", $d, $min) : '') . "\t{$end}\n}\n";
        }

        $code .= self::indent($body, 1);
        $code .= $counted ? "\n\t\$k{$d}++;\n}\n" : "}\n";
        $code .= "e{$u}:\n";

        if ($min > 0) {
            $code .= sprintf("\nif (\$k%d < %d) {\n\t%s\n}\n", $d, $min, $fail);
        }

        return $code;
    }

    private function optional(int $inner, bool $build): string
    {
        $tokens = $this->tokens($inner);
        if ($tokens !== null) {
            return sprintf(
                "if (%s) {\n%s} elseif (\$this->p %s \$this->f) {\n\t\$this->rec(%d);\n}\n",
                $this->in($tokens[0]),
                self::indent($this->read($inner, $build), 1),
                $tokens[1],
                $inner,
            );
        }

        $u = $this->unique();

        return $this->match($inner, $build, "goto n{$u};") . "n{$u}:\n";
    }

    /**
     * @param bool $predicted whether the caller has already seen the rule
     *        inside may begin at the token the reading is at
     */
    private function predicate(Predicate $rule, string $fail, bool $predicted = false): string
    {
        $inner = $rule->ruleId;
        $tokens = $this->start[$inner] ?? null;

        $set = $this->tokens($inner);
        if ($set !== null) {
            if ($rule->isExpected) {
                return sprintf(
                    "if (!%s) {\n\tif (\$this->p > \$this->f) {\n\t\t\$this->rec(%d);\n\t}\n\n%s}\n",
                    $this->in($set[0]),
                    $inner,
                    self::indent($fail, 1),
                );
            }

            return sprintf("if (%s) {\n%s%s}\n", $this->in($set[0]), self::indent(self::UNEXPECTED, 1), self::indent($fail, 1));
        }

        $u = $this->unique();
        $check = sprintf(
            "\$sf = \$this->f;\n\$se = \$this->er;\n\$sp = \$this->p;\n\$matched = \$this->%2\$s();\n\$this->f = \$sf;\n\$this->er = \$se;\n\$this->p = \$sp;\nif (%3\$s\$matched) {\n%4\$s%5\$s}\n",
            0,
            $this->request($inner, false),
            $rule->isExpected ? '!' : '',
            self::indent(self::UNEXPECTED, 1),
            self::indent($fail, 1),
        );

        if ($tokens === null || $predicted) {
            return $check;
        }

        if ($rule->isExpected) {
            return sprintf(
                "if (!%s) {\n\tif (\$this->p > \$this->f) {\n\t\t\$this->rec(%d);\n\t}\n\n%s}\n\n%s",
                $this->in(array_keys($tokens)),
                $inner,
                self::indent($fail, 1),
                $check,
            );
        }

        return sprintf("if (%s) {\n%s}\n", $this->in(array_keys($tokens)), self::indent($check, 1));
    }

    /**
     * Reads the token the reading is at, which the given rule reads.
     */
    private function read(int $id, bool $build): string
    {
        if ($this->grammar[$id] instanceof Lexeme) {
            return $this->push($id, $build);
        }

        if (!$build || array_diff_key(array_flip($this->tokens($id)[0]), $this->silent) === []) {
            return "\$this->p++;\n";
        }

        return "\$this->v[\$this->n++] = \$this->token(\$this->p++);\n";
    }

    /**
     * The tokens a rule reads one of, in case that is all it does: a lexeme,
     * or an alternation of lexemes building nothing of its own.
     *
     * @return array{list<int>, string}|null the tokens, and the comparison
     *         with the furthest failure a failure to read one is recorded by
     */
    private function tokens(int $id): ?array
    {
        $rule = $this->grammar[$id];
        if ($rule instanceof Lexeme) {
            return [[$rule->tokenId], '>='];
        }

        if (!$rule instanceof Alternation || isset($this->reducers[$id])) {
            return null;
        }

        $tokens = [];
        foreach ($rule->ruleIds as $alternative) {
            $definition = $this->grammar[$alternative];
            if (!$definition instanceof Lexeme || isset($this->reducers[$alternative])) {
                return null;
            }

            $tokens[$definition->tokenId] = true;
        }

        // The alternation is entered at the very tokens it reads
        $start = $this->start[$id] ?? [];
        ksort($start);
        ksort($tokens);
        if ($start !== $tokens) {
            return null;
        }

        return [array_keys($tokens), '>'];
    }

    /**
     * Reads the token the reading is at as the given lexeme.
     */
    private function push(int $lexeme, bool $build): string
    {
        $definition = $this->grammar[$lexeme];
        if (!$build || (isset($this->silent[$definition->tokenId]) && !isset($this->reducers[$lexeme]))) {
            return "\$this->p++;\n";
        }

        $reducer = $this->reducers[$lexeme] ?? null;
        if ($reducer !== null) {
            return sprintf(
                "\$this->v[\$this->n++] = \$this->%s(\$this->context(\$this->p, \$this->p + 1)%s);\n\$this->p++;\n",
                $reducer[0],
                $reducer[1] ? ', [$this->token($this->p)]' : '',
            );
        }

        return "\$this->v[\$this->n++] = \$this->token(\$this->p++);\n";
    }

    /**
     * @param list<int> $tokens
     */
    private function in(array $tokens): string
    {
        sort($tokens);
        if (count($tokens) === 1) {
            return sprintf('($this->ids[$this->p] === %d)', $tokens[0]);
        }

        $name = 'T' . substr(md5(implode(',', $tokens)), 0, 8);
        $this->sets[$name] = '[' . implode(',', array_map(static fn (int $t): string => $t . '=>true', $tokens)) . ']';

        return sprintf('isset(self::%s[$this->ids[$this->p]])', $name);
    }

    private static function indent(string $code, int $levels): string
    {
        $prefix = str_repeat("\t", $levels);

        return preg_replace('/^(?=.)/m', $prefix, $code);
    }

}
