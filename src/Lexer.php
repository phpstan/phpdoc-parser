<?php declare(strict_types = 1);

namespace PhpStan\TypeParser;

/**
 * Implementation based on Nette Tokenizer (New BSD License; https://github.com/nette/tokenizer)
 */
class Lexer
{
	const TOKEN_IDENTIFIER = 0;
	const TOKEN_UNION = 1;
	const TOKEN_INTERSECTION = 2;
	const TOKEN_COMPLEMENT = 3;
	const TOKEN_OPEN_PARENTHESES = 4;
	const TOKEN_CLOSE_PARENTHESES = 5;
	const TOKEN_OPEN_ANGLE_BRACKET = 6;
	const TOKEN_CLOSE_ANGLE_BRACKET = 7;
	const TOKEN_OPEN_SQUARE_BRACKET = 8;
	const TOKEN_CLOSE_SQUARE_BRACKET = 9;
	const TOKEN_COMMA = 10;
	const TOKEN_VARIABLE = 11;
	const TOKEN_WS = 12;
	const TOKEN_OTHER = 13;

	const VALUE_OFFSET = 0;
	const TYPE_OFFSET = 1;


	/** @var string|null */
	private $regexp;

	/** @var array|NULL */
	private $types;


	public function tokenize(string $s): array
	{
		if ($this->regexp === NULL) {
			$this->initialize();
		}

		preg_match_all($this->regexp, $s, $tokens, PREG_SET_ORDER);
		$count = count($this->types);

		foreach ($tokens as &$match) {
			for ($i = 1; $i <= $count; $i++) {
				if ($match[$i] != NULL) {
					$match = [$match[0], $this->types[$i - 1]];
					break;
				}
			}
		}

		return $tokens;
	}


	private function initialize(): void
	{
		$patterns = [
			self::TOKEN_IDENTIFIER => '(?:[\\\\]?+[a-z_\\x7F-\\xFF][0-9a-z_\\x7F-\\xFF]*+)++',
			self::TOKEN_UNION => '\\|',
			self::TOKEN_INTERSECTION => '&',
			self::TOKEN_COMPLEMENT => '\\~',
			self::TOKEN_OPEN_PARENTHESES => '\\(',
			self::TOKEN_CLOSE_PARENTHESES => '\\)',
			self::TOKEN_OPEN_ANGLE_BRACKET => '<',
			self::TOKEN_CLOSE_ANGLE_BRACKET => '>',
			self::TOKEN_OPEN_SQUARE_BRACKET => '\\[',
			self::TOKEN_CLOSE_SQUARE_BRACKET => '\\]',
			self::TOKEN_COMMA => ',',
			self::TOKEN_VARIABLE => '\\$[a-z_\\x7F-\\xFF][0-9a-z_\\x7F-\\xFF]*+',
			self::TOKEN_WS => '\\s++',
			self::TOKEN_OTHER => '.++',
		];

		$this->regexp = '~(' . implode(')|(', $patterns) . ')~Ai';
		$this->types = array_keys($patterns);
	}
}
