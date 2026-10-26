<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Lexer;

use PHPStan\PhpDocParser\Parser\Phplrt\TokenStream;
use PHPStan\PhpDocParser\ParserConfig;

/**
 * Implementation based on Nette Tokenizer (New BSD License; https://github.com/nette/tokenizer)
 *
 * The pattern is written out of "doc/grammars/lexemes.pp3" by "tools/phplrt/build.php".
 */
class Lexer
{

	public const TOKEN_REFERENCE = 0;
	public const TOKEN_UNION = 1;
	public const TOKEN_INTERSECTION = 2;
	public const TOKEN_NULLABLE = 3;
	public const TOKEN_OPEN_PARENTHESES = 4;
	public const TOKEN_CLOSE_PARENTHESES = 5;
	public const TOKEN_OPEN_ANGLE_BRACKET = 6;
	public const TOKEN_CLOSE_ANGLE_BRACKET = 7;
	public const TOKEN_OPEN_SQUARE_BRACKET = 8;
	public const TOKEN_CLOSE_SQUARE_BRACKET = 9;
	public const TOKEN_COMMA = 10;
	public const TOKEN_VARIADIC = 11;
	public const TOKEN_DOUBLE_COLON = 12;
	public const TOKEN_DOUBLE_ARROW = 13;
	public const TOKEN_EQUAL = 14;
	public const TOKEN_OPEN_PHPDOC = 15;
	public const TOKEN_CLOSE_PHPDOC = 16;
	public const TOKEN_PHPDOC_TAG = 17;
	public const TOKEN_DOCTRINE_TAG = 18;
	public const TOKEN_FLOAT = 19;
	public const TOKEN_INTEGER = 20;
	public const TOKEN_SINGLE_QUOTED_STRING = 21;
	public const TOKEN_DOUBLE_QUOTED_STRING = 22;
	public const TOKEN_DOCTRINE_ANNOTATION_STRING = 23;
	public const TOKEN_IDENTIFIER = 24;
	public const TOKEN_THIS_VARIABLE = 25;
	public const TOKEN_VARIABLE = 26;
	public const TOKEN_HORIZONTAL_WS = 27;
	public const TOKEN_PHPDOC_EOL = 28;
	public const TOKEN_OTHER = 29;
	public const TOKEN_END = 30;
	public const TOKEN_COLON = 31;
	public const TOKEN_WILDCARD = 32;
	public const TOKEN_OPEN_CURLY_BRACKET = 33;
	public const TOKEN_CLOSE_CURLY_BRACKET = 34;
	public const TOKEN_NEGATED = 35;
	public const TOKEN_ARROW = 36;

	public const TOKEN_COMMENT = 37;

	public const TOKEN_LABELS = [
		self::TOKEN_REFERENCE => '\'&\'',
		self::TOKEN_UNION => '\'|\'',
		self::TOKEN_INTERSECTION => '\'&\'',
		self::TOKEN_NULLABLE => '\'?\'',
		self::TOKEN_NEGATED => '\'!\'',
		self::TOKEN_OPEN_PARENTHESES => '\'(\'',
		self::TOKEN_CLOSE_PARENTHESES => '\')\'',
		self::TOKEN_OPEN_ANGLE_BRACKET => '\'<\'',
		self::TOKEN_CLOSE_ANGLE_BRACKET => '\'>\'',
		self::TOKEN_OPEN_SQUARE_BRACKET => '\'[\'',
		self::TOKEN_CLOSE_SQUARE_BRACKET => '\']\'',
		self::TOKEN_OPEN_CURLY_BRACKET => '\'{\'',
		self::TOKEN_CLOSE_CURLY_BRACKET => '\'}\'',
		self::TOKEN_COMMA => '\',\'',
		self::TOKEN_COMMENT => '\'//\'',
		self::TOKEN_COLON => '\':\'',
		self::TOKEN_VARIADIC => '\'...\'',
		self::TOKEN_DOUBLE_COLON => '\'::\'',
		self::TOKEN_DOUBLE_ARROW => '\'=>\'',
		self::TOKEN_ARROW => '\'->\'',
		self::TOKEN_EQUAL => '\'=\'',
		self::TOKEN_OPEN_PHPDOC => '\'/**\'',
		self::TOKEN_CLOSE_PHPDOC => '\'*/\'',
		self::TOKEN_PHPDOC_TAG => 'TOKEN_PHPDOC_TAG',
		self::TOKEN_DOCTRINE_TAG => 'TOKEN_DOCTRINE_TAG',
		self::TOKEN_PHPDOC_EOL => 'TOKEN_PHPDOC_EOL',
		self::TOKEN_FLOAT => 'TOKEN_FLOAT',
		self::TOKEN_INTEGER => 'TOKEN_INTEGER',
		self::TOKEN_SINGLE_QUOTED_STRING => 'TOKEN_SINGLE_QUOTED_STRING',
		self::TOKEN_DOUBLE_QUOTED_STRING => 'TOKEN_DOUBLE_QUOTED_STRING',
		self::TOKEN_DOCTRINE_ANNOTATION_STRING => 'TOKEN_DOCTRINE_ANNOTATION_STRING',
		self::TOKEN_IDENTIFIER => 'type',
		self::TOKEN_THIS_VARIABLE => '\'$this\'',
		self::TOKEN_VARIABLE => 'variable',
		self::TOKEN_HORIZONTAL_WS => 'TOKEN_HORIZONTAL_WS',
		self::TOKEN_OTHER => 'TOKEN_OTHER',
		self::TOKEN_END => 'TOKEN_END',
		self::TOKEN_WILDCARD => '*',
	];

	public const VALUE_OFFSET = 0;
	public const TYPE_OFFSET = 1;
	public const LINE_OFFSET = 2;

	private ParserConfig $config; // @phpstan-ignore property.onlyWritten

	public function __construct(ParserConfig $config)
	{
		$this->config = $config;
	}

	/**
	 * The tokens of the pattern written out of "doc/grammars/lexemes.pp3",
	 * ending with TOKEN_END.
	 *
	 * The last token carries what the parsers read along: keep the tokens
	 * together when handing them over to a {@see TokenIterator}.
	 *
	 * @return list<array{0: string, 1: int, 2: int, 3?: array<int, int>, 4?: list<int>}>
	 */
	public function tokenize(string $s): array
	{
		return TokenStream::tokenize($s);
	}

}
