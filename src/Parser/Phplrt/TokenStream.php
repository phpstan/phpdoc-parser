<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser\Phplrt;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\Generated\GeneratedParser;
use function array_slice;
use function count;
use function preg_match_all;
use function strlen;
use function strpos;
use function strtolower;
use function strtoupper;
use function substr_compare;

/**
 * Turns the tokens of {@see Lexer} into the stream the grammar of
 * "doc/grammars" is written in terms of.
 *
 * A grammar of the PP3 format has no semantic predicates, so the three
 * questions the parser asks about a token rather than about the grammar are
 * answered here instead, by giving such a token a number of its own:
 *
 *  - a word compared by value, like "is" or "array";
 *  - a bracket or an asterisk whose neighbouring whitespace decides what it
 *    means, and a tag a space is written before;
 *  - a "<" opening what looks like an HTML tag.
 *
 * @internal
 */
final class TokenStream
{

	/**
	 * The words compared by value, written exactly as they are compared.
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
	 * The words a constant expression compares without regard to case.
	 */
	private const KEYWORDS_ANY_CASE = [
		'true' => 'T_KEYWORD_TRUE',
		'false' => 'T_KEYWORD_FALSE',
		'null' => 'T_KEYWORD_NULL',
		'array' => 'T_KEYWORD_ARRAY_ANY_CASE',
	];

	/**
	 * The tags whose value a rule of its own reads, told apart by the whole
	 * name written.
	 */
	public const TAGS = [
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
	 * The tokens of the lexer the grammar reads under a name of their own.
	 */
	private const TYPES = [
		Lexer::TOKEN_REFERENCE => 'T_REFERENCE',
		Lexer::TOKEN_UNION => 'T_UNION',
		Lexer::TOKEN_INTERSECTION => 'T_INTERSECTION',
		Lexer::TOKEN_NULLABLE => 'T_NULLABLE',
		Lexer::TOKEN_NEGATED => 'T_NEGATED',
		Lexer::TOKEN_CLOSE_PARENTHESES => 'T_CLOSE_PARENTHESES',
		Lexer::TOKEN_CLOSE_ANGLE_BRACKET => 'T_CLOSE_ANGLE_BRACKET',
		Lexer::TOKEN_CLOSE_SQUARE_BRACKET => 'T_CLOSE_SQUARE_BRACKET',
		Lexer::TOKEN_CLOSE_CURLY_BRACKET => 'T_CLOSE_CURLY_BRACKET',
		Lexer::TOKEN_COMMA => 'T_COMMA',
		Lexer::TOKEN_COMMENT => 'T_COMMENT',
		Lexer::TOKEN_VARIADIC => 'T_VARIADIC',
		Lexer::TOKEN_DOUBLE_COLON => 'T_DOUBLE_COLON',
		Lexer::TOKEN_DOUBLE_ARROW => 'T_DOUBLE_ARROW',
		Lexer::TOKEN_ARROW => 'T_ARROW',
		Lexer::TOKEN_EQUAL => 'T_EQUAL',
		Lexer::TOKEN_COLON => 'T_COLON',
		Lexer::TOKEN_FLOAT => 'T_FLOAT',
		Lexer::TOKEN_INTEGER => 'T_INTEGER',
		Lexer::TOKEN_SINGLE_QUOTED_STRING => 'T_SINGLE_QUOTED_STRING',
		Lexer::TOKEN_DOUBLE_QUOTED_STRING => 'T_DOUBLE_QUOTED_STRING',
		Lexer::TOKEN_DOCTRINE_ANNOTATION_STRING => 'T_DOCTRINE_ANNOTATION_STRING',
		Lexer::TOKEN_THIS_VARIABLE => 'T_THIS_VARIABLE',
		Lexer::TOKEN_VARIABLE => 'T_VARIABLE',
		Lexer::TOKEN_OPEN_PHPDOC => 'T_OPEN_PHPDOC',
		Lexer::TOKEN_CLOSE_PHPDOC => 'T_CLOSE_PHPDOC',
		Lexer::TOKEN_PHPDOC_EOL => 'T_PHPDOC_EOL',
		Lexer::TOKEN_OTHER => 'T_OTHER',
	];

	/** where the end of the input returned by {@see tokenize()} holds the identifiers of the stream */
	public const STREAM_IDS = 3;

	/** where it holds the index of the token each of them stands for */
	public const STREAM_INDEXES = 4;

	/** @var array<int, int> */
	private static ?array $types = null;

	/** @var array<int, int> the token each token preceded by whitespace is read as */
	private static array $whitespaceVariants;

	private static int $eol;

	/** @var array<string, int> */
	private static array $keywords;

	/** @var array<string, int> */

	/** @var array<string, int> */
	private static array $tags;

	private static int $identifier;

	private static int $phpDocTag;

	private static int $phpDocTagWs;

	private static int $doctrineTag;

	private static int $doctrineTagWs;

	private static int $openCurly;

	private static int $openCurlyWs;

	private static int $openParentheses;

	private static int $openParenthesesWs;

	private static int $openSquare;

	private static int $openSquareWs;

	private static int $wildcard;

	private static int $wildcardWs;

	private static int $openAngle;

	private static int $openAngleHtml;

	/**
	 * Appends the given tokens of the lexer to the stream the parser reads, one
	 * per token it reads: the whitespace is left out, while the index of the
	 * token of the lexer each of them stands for is kept along. The stream
	 * ends with -1, the end of the input.
	 *
	 * @param list<array{string, int, int}> $tokens
	 * @param int $start the index of the first token to read
	 * @param array<int, int> $ids the identifier of each token of the stream
	 * @param array<int, int> $indexes the index of each token of the stream
	 * @param-out array<int, int> $ids
	 * @param-out array<int, int> $indexes
	 */
	public static function create(array $tokens, int $start, array &$ids, array &$indexes): void
	{
		if (self::$types === null) {
			self::initialize();
		}

		$types = self::$types;
		$keywords = self::$keywords;
		$identifier = self::$identifier;

		$count = count($tokens);

		for ($index = $start; $index < $count; $index++) {
			$token = $tokens[$index];
			$type = $token[1];

			// the most common tokens first
			if ($type === Lexer::TOKEN_HORIZONTAL_WS) {
				continue;
			}

			if ($type === Lexer::TOKEN_IDENTIFIER) {
				$ids[] = $keywords[$token[0]] ?? $identifier;
				$indexes[] = $index;
				continue;
			}

			if (isset($types[$type])) {
				$ids[] = $types[$type];
				$indexes[] = $index;
				continue;
			}

			$value = $token[0];

			switch ($type) {
				case Lexer::TOKEN_PHPDOC_TAG:
					$id = self::$tags[$value] ?? (self::isPrecededByWhitespace($tokens, $index) ? self::$phpDocTagWs : self::$phpDocTag);
					break;

				case Lexer::TOKEN_DOCTRINE_TAG:
					$id = self::isPrecededByWhitespace($tokens, $index) ? self::$doctrineTagWs : self::$doctrineTag;
					break;

				case Lexer::TOKEN_OPEN_CURLY_BRACKET:
					$id = self::isPrecededByWhitespace($tokens, $index) ? self::$openCurlyWs : self::$openCurly;
					break;

				case Lexer::TOKEN_OPEN_PARENTHESES:
					$id = self::isPrecededByWhitespace($tokens, $index) ? self::$openParenthesesWs : self::$openParentheses;
					break;

				case Lexer::TOKEN_OPEN_SQUARE_BRACKET:
					$id = self::isPrecededByWhitespace($tokens, $index) ? self::$openSquareWs : self::$openSquare;
					break;

				case Lexer::TOKEN_WILDCARD:
					$id = ($tokens[$index + 1][1] ?? -1) === Lexer::TOKEN_HORIZONTAL_WS ? self::$wildcardWs : self::$wildcard;
					break;

				case Lexer::TOKEN_OPEN_ANGLE_BRACKET:
					$id = self::isHtml($tokens, $index, $count) ? self::$openAngleHtml : self::$openAngle;
					break;

				default:
					// Lexer::TOKEN_END
					$ids[] = -1;
					$indexes[] = $index;
					continue 2;
			}

			$ids[] = $id;
			$indexes[] = $index;
		}
	}

	/**
	 * Reads the given PHPDoc into the tokens of {@see Lexer}, and the stream of
	 * {@see create()} along with them, in one pass.
	 *
	 * The stream is handed over in the last token, the end of the input, as
	 * its {@see STREAM_IDS} and {@see STREAM_INDEXES}: the tokens are all
	 * {@see Lexer::tokenize()} returns, and the parser is given nothing else.
	 * See {@see fromTokens()}.
	 *
	 * @return list<array{0: string, 1: int, 2: int, 3?: array<int, int>, 4?: list<int>}>
	 */
	public static function tokenize(string $s): array
	{
		if (self::$types === null) {
			self::initialize();
		}

		// PREG_PATTERN_ORDER, not PREG_SET_ORDER: it collects the whole PHPDoc
		// into two arrays instead of allocating one array per token. This only
		// pays off while the token patterns have no capturing groups, because
		// every group would get an array of its own, one entry per token.
		preg_match_all(GeneratedParser::LEXER_PATTERN, $s, $matches);

		$values = $matches[0];
		// no MARK at all when nothing has been matched
		$marks = $matches['MARK'] ?? [];

		$lexerTypes = GeneratedParser::LEXER_TYPES;
		$keywords = self::$keywords;
		$tags = self::$tags;
		$identifier = self::$identifier;
		$phpDocTag = self::$phpDocTag;
		$whitespaceVariants = self::$whitespaceVariants;
		$wildcard = self::$wildcard;
		$openAngle = self::$openAngle;
		$eol = self::$eol;

		$tokens = [];
		$ids = [];
		$indexes = [];
		$angles = [];
		$line = 1;
		$afterWhitespace = false;

		foreach ($values as $index => $value) {
			/** @var key-of<GeneratedParser::LEXER_TYPES> $id every mark of the pattern is a token it reads */
			$id = (int) $marks[$index];

			if ($id === 0) {
				// T_HORIZONTAL_WS, the first token of the grammar, which the
				// stream leaves out
				$tokens[] = [$value, Lexer::TOKEN_HORIZONTAL_WS, $line];
				$afterWhitespace = true;
				continue;
			}

			$tokens[] = [$value, $lexerTypes[$id], $line];

			if ($id === $identifier) {
				$id = $keywords[$value] ?? $identifier;
			} elseif (isset($whitespaceVariants[$id])) {
				if ($id === $phpDocTag && isset($tags[$value])) {
					$id = $tags[$value];
				} elseif ($afterWhitespace) {
					$id = $whitespaceVariants[$id];
				}
			} elseif ($id === $wildcard) {
				if (($marks[$index + 1] ?? null) === '0') {
					$id = self::$wildcardWs;
				}
			} elseif ($id === $openAngle) {
				$angles[] = count($ids);
			} elseif ($id === $eol) {
				$line++;
			}

			$ids[] = $id;
			$indexes[] = $index;
			$afterWhitespace = false;
		}

		$count = count($tokens);
		$closable = [];
		foreach ($angles as $position) {
			// A tag is only closed by an end tag written somewhere in the
			// PHPDoc, which is looked for once per name rather than once per
			// "<": every "list<string>" would otherwise be one walk to the end
			$name = $tokens[$indexes[$position + 1] ?? $count][0] ?? '';
			$closable[$name] ??= $name !== '' && strpos($s, '/' . $name . '>') !== false;
			if (!$closable[$name] || !self::isHtml($tokens, $indexes[$position], $count)) {
				continue;
			}

			$ids[$position] = self::$openAngleHtml;
		}

		$ids[] = -1;
		$indexes[] = $count;
		$tokens[] = ['', Lexer::TOKEN_END, $line, $ids, $indexes];

		return $tokens;
	}

	/**
	 * The stream of the given tokens from the given index on, in case the
	 * tokens are the ones {@see tokenize()} has returned, or null in case they
	 * have to be read by {@see create()}.
	 *
	 * Whatever has been done to the tokens since, other than reading them,
	 * leaves the stream describing other tokens. Of all that may be done, the
	 * stream only tells apart the tokens being cut off or added to.
	 *
	 * @param list<array{0: string, 1: int, 2: int, 3?: array<int, int>, 4?: list<int>}> $tokens
	 * @return array{array<int, int>, list<int>}|null
	 */
	public static function fromTokens(array $tokens, int $start): ?array
	{
		$last = count($tokens) - 1;
		$end = $tokens[$last] ?? null;
		if (!isset($end[self::STREAM_INDEXES])) {
			return null;
		}

		$ids = $end[self::STREAM_IDS] ?? [];
		$indexes = $end[self::STREAM_INDEXES];
		$length = count($indexes);
		if ($indexes[$length - 1] !== $last) {
			return null;
		}

		if ($start <= $indexes[0]) {
			return [$ids, $indexes];
		}

		// the first token of the stream at the given index or after it
		$low = 0;
		$high = $length - 1;
		while ($low < $high) {
			$middle = $low + $high >> 1;
			if ($indexes[$middle] < $start) {
				$low = $middle + 1;
			} else {
				$high = $middle;
			}
		}

		return [array_slice($ids, $low), array_slice($indexes, $low)];
	}

	/**
	 * The identifier of the token a tag written nowhere in the tokens is read as.
	 */
	public static function tagId(string $value): int
	{
		if (self::$types === null) {
			self::initialize();
		}

		return self::$tags[$value] ?? self::$phpDocTag;
	}

	private static function initialize(): void
	{
		$ids = GeneratedParser::TOKENS;

		$types = [];
		foreach (self::TYPES as $type => $name) {
			$types[$type] = $ids[$name];
		}

		// A keyword written in any case is looked up along with the others,
		// every way of writing it in, since only a few of them are
		$keywords = [];
		foreach (self::KEYWORDS_ANY_CASE as $value => $name) {
			foreach (self::caseVariants($value) as $variant) {
				$keywords[$variant] = $ids[$name];
			}
		}

		foreach (self::KEYWORDS as $value => $name) {
			$keywords[$value] = $ids[$name];
		}
		self::$keywords = $keywords;

		$tags = [];
		foreach (self::TAGS as $value => $name) {
			$tags[$value] = $ids[$name];
		}
		self::$tags = $tags;

		self::$identifier = $ids['T_IDENTIFIER'];
		self::$phpDocTag = $ids['T_PHPDOC_TAG'];
		self::$phpDocTagWs = $ids['T_PHPDOC_TAG_WS'];
		self::$doctrineTag = $ids['T_DOCTRINE_TAG'];
		self::$doctrineTagWs = $ids['T_DOCTRINE_TAG_WS'];
		self::$openCurly = $ids['T_OPEN_CURLY_BRACKET'];
		self::$openCurlyWs = $ids['T_OPEN_CURLY_BRACKET_WS'];
		self::$openParentheses = $ids['T_OPEN_PARENTHESES'];
		self::$openParenthesesWs = $ids['T_OPEN_PARENTHESES_WS'];
		self::$openSquare = $ids['T_OPEN_SQUARE_BRACKET'];
		self::$openSquareWs = $ids['T_OPEN_SQUARE_BRACKET_WS'];
		self::$wildcard = $ids['T_WILDCARD'];
		self::$wildcardWs = $ids['T_WILDCARD_WS'];
		self::$openAngle = $ids['T_OPEN_ANGLE_BRACKET'];
		self::$openAngleHtml = $ids['T_OPEN_ANGLE_BRACKET_HTML'];
		self::$eol = $ids['T_PHPDOC_EOL'];
		self::$whitespaceVariants = [
			self::$phpDocTag => self::$phpDocTagWs,
			self::$doctrineTag => self::$doctrineTagWs,
			self::$openCurly => self::$openCurlyWs,
			self::$openParentheses => self::$openParenthesesWs,
			self::$openSquare => self::$openSquareWs,
		];

		self::$types = $types;
	}

	/**
	 * @return list<string>
	 */
	private static function caseVariants(string $value): array
	{
		$variants = [''];
		for ($i = 0, $length = strlen($value); $i < $length; $i++) {
			$next = [];
			foreach ($variants as $variant) {
				$next[] = $variant . strtolower($value[$i]);
				$next[] = $variant . strtoupper($value[$i]);
			}
			$variants = $next;
		}

		return $variants;
	}

	/**
	 * @param list<array{string, int, int}> $tokens
	 */
	private static function isPrecededByWhitespace(array $tokens, int $index): bool
	{
		return ($tokens[$index - 1][1] ?? -1) === Lexer::TOKEN_HORIZONTAL_WS;
	}

	/**
	 * Whether the "<" at the given position opens what looks like an HTML tag:
	 * a "<name>" with a matching "</name>" somewhere after it.
	 *
	 * @param list<array{string, int, int}> $tokens
	 */
	private static function isHtml(array $tokens, int $index, int $count): bool
	{
		$index = self::skipWhitespace($tokens, $index + 1, $count);
		if ($index >= $count || $tokens[$index][1] !== Lexer::TOKEN_IDENTIFIER) {
			return false;
		}

		$name = $tokens[$index][0];

		$index = self::skipWhitespace($tokens, $index + 1, $count);
		if ($index >= $count || $tokens[$index][1] !== Lexer::TOKEN_CLOSE_ANGLE_BRACKET) {
			return false;
		}

		$index = self::skipWhitespace($tokens, $index + 1, $count);

		$endTag = '</' . $name . '>';
		$length = strlen($endTag);

		while ($index < $count && $tokens[$index][1] !== Lexer::TOKEN_END) {
			if ($tokens[$index][1] === Lexer::TOKEN_OPEN_ANGLE_BRACKET) {
				$index = self::skipWhitespace($tokens, $index + 1, $count);

				if ($index < $count && strpos($tokens[$index][0], '/' . $name . '>') !== false) {
					return true;
				}
			}

			if ($index < $count) {
				$value = $tokens[$index][0];

				if (strlen($value) >= $length && substr_compare($value, $endTag, -$length) === 0) {
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
		while ($index < $count && $tokens[$index][1] === Lexer::TOKEN_HORIZONTAL_WS) {
			$index++;
		}

		return $index;
	}

}
