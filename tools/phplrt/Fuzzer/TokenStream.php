<?php

/**
 * The tokens of PHPStan\PhpDocParser\Lexer\Lexer, dressed as the stream a
 * grammar of "doc/grammars" is written in terms of.
 *
 * A grammar of the PP3 format has no semantic predicates, so the three
 * questions the hand-written parser asks about a token rather than about the
 * grammar are answered here instead, by giving such a token a number of its
 * own:
 *
 *  - a word that parser compares by value, like "is" or "array";
 *  - a bracket or an asterisk whose neighbouring whitespace decides what it
 *    means, and a tag a space is written before;
 *  - a "<" opening what that parser recognizes as an HTML tag.
 *
 * @internal this is a development tool, not part of the library
 */

declare(strict_types=1);

namespace PHPStan\PhpDocParser\Tools\Fuzzer;

use PHPStan\PhpDocParser\Lexer\Lexer;
use Phplrt\Contracts\Lexer\Channel;
use Phplrt\Contracts\Lexer\ChannelInterface;
use Phplrt\Contracts\Lexer\LexerInterface;
use Phplrt\Contracts\Lexer\TokenInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Lexer\Token\EndOfInputToken;

final class Token implements TokenInterface
{
    public ?string $name = null;

    public ChannelInterface $channel;

    public function __construct(
        public int $id,
        public string $value,
        public int $offset,
        public int $size,
        bool $isEndOfInput = false,
    ) {
        $this->channel = $isEndOfInput ? Channel::EndOfInput : Channel::Default;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

/**
 * A source that is a stream of tokens rather than a text.
 */
final class TokenSource implements ReadableInterface
{
    public string $content {
        get => '';
    }

    /**
     * @param list<Token> $tokens
     */
    public function __construct(public array $tokens) {}

    public function read(int $offset, int $bytes): string
    {
        return '';
    }
}

final class TokenStreamLexer implements LexerInterface
{
    public function lex(ReadableInterface $source, int $offset = 0): iterable
    {
        \assert($source instanceof TokenSource);

        return $source->tokens;
    }
}

final class TokenStream
{
    /**
     * The words the hand-written parser compares a T_IDENTIFIER against by
     * value, written exactly as it compares them.
     *
     * @var array<string, non-empty-string>
     */
    private const KEYWORDS = [
        'is' => 'T_KEYWORD_IS',
        'not' => 'T_KEYWORD_NOT',
        'of' => 'T_KEYWORD_OF',
        'as' => 'T_KEYWORD_AS',
        'super' => 'T_KEYWORD_SUPER',
        'static' => 'T_KEYWORD_STATIC',
        'from' => 'T_KEYWORD_FROM',
        'covariant' => 'T_KEYWORD_COVARIANT',
        'contravariant' => 'T_KEYWORD_CONTRAVARIANT',
        'array' => 'T_KEYWORD_ARRAY',
        'list' => 'T_KEYWORD_LIST',
        'non-empty-array' => 'T_KEYWORD_NON_EMPTY_ARRAY',
        'non-empty-list' => 'T_KEYWORD_NON_EMPTY_LIST',
        'object' => 'T_KEYWORD_OBJECT',
        'true' => 'T_KEYWORD_TRUE',
        'false' => 'T_KEYWORD_FALSE',
        'null' => 'T_KEYWORD_NULL',
    ];

    /**
     * The words the constant expression parser compares without regard to case.
     *
     * @var array<string, non-empty-string>
     */
    private const KEYWORDS_ANY_CASE = [
        'true' => 'T_KEYWORD_TRUE',
        'false' => 'T_KEYWORD_FALSE',
        'null' => 'T_KEYWORD_NULL',
        'array' => 'T_KEYWORD_ARRAY_ANY_CASE',
    ];

    private const KEYWORDS_ANY_CASE_LENGTH = 5;

    /**
     * The tags whose value PhpDocParser::parseTagValue() reads by a rule of its
     * own, told apart by the whole name written.
     *
     * @var array<string, non-empty-string>
     */
    private const TAGS = [
        '@param' => 'T_TAG_PARAM',
        '@phpstan-param' => 'T_TAG_PARAM',
        '@psalm-param' => 'T_TAG_PARAM',
        '@phan-param' => 'T_TAG_PARAM',
        '@param-immediately-invoked-callable' => 'T_TAG_PARAM_IMMEDIATELY_INVOKED_CALLABLE',
        '@phpstan-param-immediately-invoked-callable' => 'T_TAG_PARAM_IMMEDIATELY_INVOKED_CALLABLE',
        '@param-later-invoked-callable' => 'T_TAG_PARAM_LATER_INVOKED_CALLABLE',
        '@phpstan-param-later-invoked-callable' => 'T_TAG_PARAM_LATER_INVOKED_CALLABLE',
        '@param-closure-this' => 'T_TAG_PARAM_CLOSURE_THIS',
        '@phpstan-param-closure-this' => 'T_TAG_PARAM_CLOSURE_THIS',
        '@pure-unless-callable-is-impure' => 'T_TAG_PURE_UNLESS_CALLABLE_IS_IMPURE',
        '@phpstan-pure-unless-callable-is-impure' => 'T_TAG_PURE_UNLESS_CALLABLE_IS_IMPURE',
        '@pure-unless-parameter-passed' => 'T_TAG_PURE_UNLESS_PARAMETER_PASSED',
        '@phpstan-pure-unless-parameter-passed' => 'T_TAG_PURE_UNLESS_PARAMETER_PASSED',
        '@var' => 'T_TAG_VAR',
        '@phpstan-var' => 'T_TAG_VAR',
        '@psalm-var' => 'T_TAG_VAR',
        '@phan-var' => 'T_TAG_VAR',
        '@return' => 'T_TAG_RETURN',
        '@phpstan-return' => 'T_TAG_RETURN',
        '@psalm-return' => 'T_TAG_RETURN',
        '@phan-return' => 'T_TAG_RETURN',
        '@phan-real-return' => 'T_TAG_RETURN',
        '@throws' => 'T_TAG_THROWS',
        '@phpstan-throws' => 'T_TAG_THROWS',
        '@mixin' => 'T_TAG_MIXIN',
        '@phan-mixin' => 'T_TAG_MIXIN',
        '@psalm-require-extends' => 'T_TAG_REQUIRE_EXTENDS',
        '@phpstan-require-extends' => 'T_TAG_REQUIRE_EXTENDS',
        '@psalm-require-implements' => 'T_TAG_REQUIRE_IMPLEMENTS',
        '@phpstan-require-implements' => 'T_TAG_REQUIRE_IMPLEMENTS',
        '@psalm-inheritors' => 'T_TAG_SEALED',
        '@phpstan-sealed' => 'T_TAG_SEALED',
        '@deprecated' => 'T_TAG_DEPRECATED',
        '@property' => 'T_TAG_PROPERTY',
        '@property-read' => 'T_TAG_PROPERTY',
        '@property-write' => 'T_TAG_PROPERTY',
        '@phpstan-property' => 'T_TAG_PROPERTY',
        '@phpstan-property-read' => 'T_TAG_PROPERTY',
        '@phpstan-property-write' => 'T_TAG_PROPERTY',
        '@psalm-property' => 'T_TAG_PROPERTY',
        '@psalm-property-read' => 'T_TAG_PROPERTY',
        '@psalm-property-write' => 'T_TAG_PROPERTY',
        '@phan-property' => 'T_TAG_PROPERTY',
        '@phan-property-read' => 'T_TAG_PROPERTY',
        '@phan-property-write' => 'T_TAG_PROPERTY',
        '@method' => 'T_TAG_METHOD',
        '@phpstan-method' => 'T_TAG_METHOD',
        '@psalm-method' => 'T_TAG_METHOD',
        '@phan-method' => 'T_TAG_METHOD',
        '@template' => 'T_TAG_TEMPLATE',
        '@phpstan-template' => 'T_TAG_TEMPLATE',
        '@psalm-template' => 'T_TAG_TEMPLATE',
        '@phan-template' => 'T_TAG_TEMPLATE',
        '@template-covariant' => 'T_TAG_TEMPLATE',
        '@phpstan-template-covariant' => 'T_TAG_TEMPLATE',
        '@psalm-template-covariant' => 'T_TAG_TEMPLATE',
        '@template-contravariant' => 'T_TAG_TEMPLATE',
        '@phpstan-template-contravariant' => 'T_TAG_TEMPLATE',
        '@psalm-template-contravariant' => 'T_TAG_TEMPLATE',
        '@extends' => 'T_TAG_EXTENDS',
        '@phpstan-extends' => 'T_TAG_EXTENDS',
        '@phan-extends' => 'T_TAG_EXTENDS',
        '@phan-inherits' => 'T_TAG_EXTENDS',
        '@template-extends' => 'T_TAG_EXTENDS',
        '@implements' => 'T_TAG_IMPLEMENTS',
        '@phpstan-implements' => 'T_TAG_IMPLEMENTS',
        '@template-implements' => 'T_TAG_IMPLEMENTS',
        '@use' => 'T_TAG_USE',
        '@phpstan-use' => 'T_TAG_USE',
        '@template-use' => 'T_TAG_USE',
        '@phpstan-type' => 'T_TAG_TYPE_ALIAS',
        '@psalm-type' => 'T_TAG_TYPE_ALIAS',
        '@phan-type' => 'T_TAG_TYPE_ALIAS',
        '@phpstan-import-type' => 'T_TAG_TYPE_ALIAS_IMPORT',
        '@psalm-import-type' => 'T_TAG_TYPE_ALIAS_IMPORT',
        '@phpstan-assert' => 'T_TAG_ASSERT',
        '@phpstan-assert-if-true' => 'T_TAG_ASSERT',
        '@phpstan-assert-if-false' => 'T_TAG_ASSERT',
        '@psalm-assert' => 'T_TAG_ASSERT',
        '@psalm-assert-if-true' => 'T_TAG_ASSERT',
        '@psalm-assert-if-false' => 'T_TAG_ASSERT',
        '@phan-assert' => 'T_TAG_ASSERT',
        '@phan-assert-if-true' => 'T_TAG_ASSERT',
        '@phan-assert-if-false' => 'T_TAG_ASSERT',
        '@phpstan-this-out' => 'T_TAG_SELF_OUT',
        '@phpstan-self-out' => 'T_TAG_SELF_OUT',
        '@psalm-this-out' => 'T_TAG_SELF_OUT',
        '@psalm-self-out' => 'T_TAG_SELF_OUT',
        '@param-out' => 'T_TAG_PARAM_OUT',
        '@phpstan-param-out' => 'T_TAG_PARAM_OUT',
        '@psalm-param-out' => 'T_TAG_PARAM_OUT',
    ];

    /**
     * The number each token of the lexer is named by, for the ones the grammar
     * does not tell apart any further.
     *
     * @var array<int, int>
     */
    private array $types;

    /**
     * @param array<non-empty-string, int> $ids the number the grammar names
     *        each of its tokens by
     */
    public function __construct(private array $ids)
    {
        $this->types = [
            Lexer::TOKEN_REFERENCE => $this->id('T_REFERENCE'),
            Lexer::TOKEN_UNION => $this->id('T_UNION'),
            Lexer::TOKEN_INTERSECTION => $this->id('T_INTERSECTION'),
            Lexer::TOKEN_NULLABLE => $this->id('T_NULLABLE'),
            Lexer::TOKEN_NEGATED => $this->id('T_NEGATED'),
            Lexer::TOKEN_CLOSE_PARENTHESES => $this->id('T_CLOSE_PARENTHESES'),
            Lexer::TOKEN_CLOSE_ANGLE_BRACKET => $this->id('T_CLOSE_ANGLE_BRACKET'),
            Lexer::TOKEN_CLOSE_SQUARE_BRACKET => $this->id('T_CLOSE_SQUARE_BRACKET'),
            Lexer::TOKEN_CLOSE_CURLY_BRACKET => $this->id('T_CLOSE_CURLY_BRACKET'),
            Lexer::TOKEN_COMMA => $this->id('T_COMMA'),
            Lexer::TOKEN_COMMENT => $this->id('T_COMMENT'),
            Lexer::TOKEN_VARIADIC => $this->id('T_VARIADIC'),
            Lexer::TOKEN_DOUBLE_COLON => $this->id('T_DOUBLE_COLON'),
            Lexer::TOKEN_DOUBLE_ARROW => $this->id('T_DOUBLE_ARROW'),
            Lexer::TOKEN_ARROW => $this->id('T_ARROW'),
            Lexer::TOKEN_EQUAL => $this->id('T_EQUAL'),
            Lexer::TOKEN_COLON => $this->id('T_COLON'),
            Lexer::TOKEN_FLOAT => $this->id('T_FLOAT'),
            Lexer::TOKEN_INTEGER => $this->id('T_INTEGER'),
            Lexer::TOKEN_SINGLE_QUOTED_STRING => $this->id('T_SINGLE_QUOTED_STRING'),
            Lexer::TOKEN_DOUBLE_QUOTED_STRING => $this->id('T_DOUBLE_QUOTED_STRING'),
            Lexer::TOKEN_DOCTRINE_ANNOTATION_STRING => $this->id('T_DOCTRINE_ANNOTATION_STRING'),
            Lexer::TOKEN_THIS_VARIABLE => $this->id('T_THIS_VARIABLE'),
            Lexer::TOKEN_VARIABLE => $this->id('T_VARIABLE'),
            Lexer::TOKEN_OPEN_PHPDOC => $this->id('T_OPEN_PHPDOC'),
            Lexer::TOKEN_CLOSE_PHPDOC => $this->id('T_CLOSE_PHPDOC'),
            Lexer::TOKEN_PHPDOC_EOL => $this->id('T_PHPDOC_EOL'),
            Lexer::TOKEN_OTHER => $this->id('T_OTHER'),
            Lexer::TOKEN_END => EndOfInputToken::TOKEN_ID,
        ];
    }

    /**
     * @param non-empty-string $name
     */
    private function id(string $name): int
    {
        return $this->ids[$name] ?? throw new \RuntimeException(\sprintf('The grammar declares no %s', $name));
    }

    /**
     * @param list<array{string, int, int}> $tokens
     * @return list<Token>
     */
    public function create(array $tokens): array
    {
        $result = [];
        $offset = 0;

        foreach ($tokens as $index => $token) {
            $type = $token[Lexer::TYPE_OFFSET];
            $value = $token[Lexer::VALUE_OFFSET];

            if ($type === Lexer::TOKEN_HORIZONTAL_WS) {
                $offset += \strlen($value);

                continue;
            }

            $result[] = new Token(
                $this->identify($tokens, $index, $type, $value),
                $value,
                $offset,
                \strlen($value),
                $type === Lexer::TOKEN_END,
            );

            $offset += \strlen($value);
        }

        return $result;
    }

    /**
     * @param list<array{string, int, int}> $tokens
     */
    private function identify(array $tokens, int $index, int $type, string $value): int
    {
        switch ($type) {
            case Lexer::TOKEN_IDENTIFIER:
                $keyword = self::KEYWORDS[$value] ?? null;

                if ($keyword !== null) {
                    return $this->id($keyword);
                }

                if (\strlen($value) <= self::KEYWORDS_ANY_CASE_LENGTH) {
                    $keyword = self::KEYWORDS_ANY_CASE[\strtolower($value)] ?? null;
                }

                return $this->id($keyword ?? 'T_IDENTIFIER');

            case Lexer::TOKEN_PHPDOC_TAG:
                $tag = self::TAGS[$value] ?? null;

                if ($tag !== null) {
                    return $this->id($tag);
                }

                return $this->id(self::isPrecededByWhitespace($tokens, $index) ? 'T_PHPDOC_TAG_WS' : 'T_PHPDOC_TAG');

            case Lexer::TOKEN_DOCTRINE_TAG:
                return $this->id(self::isPrecededByWhitespace($tokens, $index) ? 'T_DOCTRINE_TAG_WS' : 'T_DOCTRINE_TAG');

            case Lexer::TOKEN_OPEN_CURLY_BRACKET:
                return $this->id(self::isPrecededByWhitespace($tokens, $index) ? 'T_OPEN_CURLY_BRACKET_WS' : 'T_OPEN_CURLY_BRACKET');

            case Lexer::TOKEN_OPEN_PARENTHESES:
                return $this->id(self::isPrecededByWhitespace($tokens, $index) ? 'T_OPEN_PARENTHESES_WS' : 'T_OPEN_PARENTHESES');

            case Lexer::TOKEN_OPEN_SQUARE_BRACKET:
                return $this->id(self::isPrecededByWhitespace($tokens, $index) ? 'T_OPEN_SQUARE_BRACKET_WS' : 'T_OPEN_SQUARE_BRACKET');

            case Lexer::TOKEN_WILDCARD:
                return $this->id(self::isFollowedByWhitespace($tokens, $index) ? 'T_WILDCARD_WS' : 'T_WILDCARD');

            case Lexer::TOKEN_OPEN_ANGLE_BRACKET:
                return $this->id(self::isHtml($tokens, $index) ? 'T_OPEN_ANGLE_BRACKET_HTML' : 'T_OPEN_ANGLE_BRACKET');

            default:
                return $this->types[$type];
        }
    }

    /**
     * @param list<array{string, int, int}> $tokens
     */
    private static function isPrecededByWhitespace(array $tokens, int $index): bool
    {
        return ($tokens[$index - 1][Lexer::TYPE_OFFSET] ?? -1) === Lexer::TOKEN_HORIZONTAL_WS;
    }

    /**
     * @param list<array{string, int, int}> $tokens
     */
    private static function isFollowedByWhitespace(array $tokens, int $index): bool
    {
        return ($tokens[$index + 1][Lexer::TYPE_OFFSET] ?? -1) === Lexer::TOKEN_HORIZONTAL_WS;
    }

    /**
     * Whether the "<" at the given position opens what
     * PHPStan\PhpDocParser\Parser\TypeParser::isHtml() recognizes as an HTML
     * tag, read the very same way it reads it.
     *
     * @param list<array{string, int, int}> $tokens
     */
    private static function isHtml(array $tokens, int $index): bool
    {
        $count = \count($tokens);

        $index = self::skipWhitespace($tokens, $index + 1, $count);
        if ($index >= $count || $tokens[$index][Lexer::TYPE_OFFSET] !== Lexer::TOKEN_IDENTIFIER) {
            return false;
        }

        $name = $tokens[$index][Lexer::VALUE_OFFSET];

        $index = self::skipWhitespace($tokens, $index + 1, $count);
        if ($index >= $count || $tokens[$index][Lexer::TYPE_OFFSET] !== Lexer::TOKEN_CLOSE_ANGLE_BRACKET) {
            return false;
        }

        $index = self::skipWhitespace($tokens, $index + 1, $count);

        $endTag = '</' . $name . '>';
        $length = \strlen($endTag);

        while ($index < $count && $tokens[$index][Lexer::TYPE_OFFSET] !== Lexer::TOKEN_END) {
            if ($tokens[$index][Lexer::TYPE_OFFSET] === Lexer::TOKEN_OPEN_ANGLE_BRACKET) {
                $index = self::skipWhitespace($tokens, $index + 1, $count);

                if ($index < $count && \strpos($tokens[$index][Lexer::VALUE_OFFSET], '/' . $name . '>') !== false) {
                    return true;
                }
            }

            if ($index < $count) {
                $value = $tokens[$index][Lexer::VALUE_OFFSET];

                if (\strlen($value) >= $length && \substr_compare($value, $endTag, -$length) === 0) {
                    return true;
                }
            }

            $index = self::skipWhitespace($tokens, $index + 1, $count);
        }

        return false;
    }

    /**
     * @param list<array{string, int, int}> $tokens
     */
    private static function skipWhitespace(array $tokens, int $index, int $count): int
    {
        while ($index < $count && $tokens[$index][Lexer::TYPE_OFFSET] === Lexer::TOKEN_HORIZONTAL_WS) {
            $index++;
        }

        return $index;
    }
}
