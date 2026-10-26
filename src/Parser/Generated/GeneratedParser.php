<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser\Generated;

use PHPStan\PhpDocParser\Ast\Comment;
use PHPStan\PhpDocParser\Parser\Phplrt\Token;
use function array_keys;
use function is_array;
use function ksort;

/**
 * The parser of "doc/grammars/phpdoc.pp3".
 *
 * DO NOT EDIT: this file is written by "tools/phplrt/build.php" (make grammars-build).
 *
 * Every rule of the grammar is a method reading it the way the runtime of
 * phplrt would, and building its value along: a method named "b" followed by
 * the identifier of a rule reads the rule and pushes its value, one named "s"
 * only reads it. The values are built by the reducers of the class extending
 * this one, which are given the index of the first and of the last token of
 * the lexer the rule has read.
 *
 * @internal
 */
abstract class GeneratedParser
{

	/** @var array<string, int> the identifier of each rule a reading may start at */
	public const RULES = ['PhpDoc' => 0, 'UnreadTagChild' => 10, 'PhpDocChild' => 189, 'KnownTag' => 190, 'Type' => 199, 'ConstantExpr' => 340];

	/** @var array<string, int> the identifier of each token */
	public const TOKENS = ['T_HORIZONTAL_WS' => 0, 'T_KEYWORD_CONTRAVARIANT' => 1, 'T_KEYWORD_COVARIANT' => 2, 'T_KEYWORD_NON_EMPTY_ARRAY' => 3, 'T_KEYWORD_NON_EMPTY_LIST' => 4, 'T_KEYWORD_SUPER' => 5, 'T_KEYWORD_OBJECT' => 6, 'T_KEYWORD_ARRAY' => 7, 'T_KEYWORD_LIST' => 8, 'T_KEYWORD_STATIC' => 9, 'T_KEYWORD_FROM' => 10, 'T_KEYWORD_NOT' => 11, 'T_KEYWORD_IS' => 12, 'T_KEYWORD_OF' => 13, 'T_KEYWORD_AS' => 14, 'T_KEYWORD_FALSE' => 15, 'T_KEYWORD_TRUE' => 16, 'T_KEYWORD_NULL' => 17, 'T_KEYWORD_ARRAY_ANY_CASE' => 18, 'T_FLOAT' => 19, 'T_INTEGER' => 20, 'T_SINGLE_QUOTED_STRING' => 21, 'T_DOUBLE_QUOTED_STRING' => 22, 'T_DOCTRINE_ANNOTATION_STRING' => 23, 'T_IDENTIFIER' => 24, 'T_THIS_VARIABLE' => 25, 'T_VARIABLE' => 26, 'T_REFERENCE' => 27, 'T_UNION' => 28, 'T_INTERSECTION' => 29, 'T_NULLABLE' => 30, 'T_NEGATED' => 31, 'T_OPEN_PARENTHESES' => 32, 'T_CLOSE_PARENTHESES' => 33, 'T_OPEN_ANGLE_BRACKET' => 34, 'T_CLOSE_ANGLE_BRACKET' => 35, 'T_OPEN_SQUARE_BRACKET' => 36, 'T_CLOSE_SQUARE_BRACKET' => 37, 'T_OPEN_CURLY_BRACKET' => 38, 'T_CLOSE_CURLY_BRACKET' => 39, 'T_COMMA' => 40, 'T_COMMENT' => 41, 'T_VARIADIC' => 42, 'T_DOUBLE_COLON' => 43, 'T_DOUBLE_ARROW' => 44, 'T_ARROW' => 45, 'T_EQUAL' => 46, 'T_COLON' => 47, 'T_OPEN_PHPDOC' => 48, 'T_CLOSE_PHPDOC' => 49, 'T_WILDCARD' => 50, 'T_TAG_PARAM_IMMEDIATELY_INVOKED_CALLABLE' => 51, 'T_TAG_PURE_UNLESS_CALLABLE_IS_IMPURE' => 52, 'T_TAG_PURE_UNLESS_PARAMETER_PASSED' => 53, 'T_TAG_PARAM_LATER_INVOKED_CALLABLE' => 54, 'T_TAG_PARAM_CLOSURE_THIS' => 55, 'T_TAG_REQUIRE_IMPLEMENTS' => 56, 'T_TAG_REQUIRE_EXTENDS' => 57, 'T_TAG_TYPE_ALIAS_IMPORT' => 58, 'T_TAG_ASSERT' => 59, 'T_TAG_SELF_OUT' => 60, 'T_TAG_SEALED' => 61, 'T_TAG_TEMPLATE' => 62, 'T_TAG_PROPERTY' => 63, 'T_TAG_IMPLEMENTS' => 64, 'T_TAG_DEPRECATED' => 65, 'T_TAG_TYPE_ALIAS' => 66, 'T_TAG_PARAM_OUT' => 67, 'T_TAG_EXTENDS' => 68, 'T_TAG_METHOD' => 69, 'T_TAG_RETURN' => 70, 'T_TAG_THROWS' => 71, 'T_TAG_PARAM' => 72, 'T_TAG_MIXIN' => 73, 'T_TAG_USE' => 74, 'T_TAG_VAR' => 75, 'T_PHPDOC_TAG_WS' => 76, 'T_PHPDOC_TAG' => 77, 'T_DOCTRINE_TAG_WS' => 78, 'T_DOCTRINE_TAG' => 79, 'T_PHPDOC_EOL' => 80, 'T_OTHER' => 81, 'T_OPEN_PARENTHESES_WS' => 82, 'T_OPEN_CURLY_BRACKET_WS' => 83, 'T_OPEN_SQUARE_BRACKET_WS' => 84, 'T_WILDCARD_WS' => 85, 'T_OPEN_ANGLE_BRACKET_HTML' => 86];

	/** @var array<int, string> the name each token is expected by */
	public const EXPECTATIONS = ['T_HORIZONTAL_WS', 'T_KEYWORD_CONTRAVARIANT', 'T_KEYWORD_COVARIANT', 'T_KEYWORD_NON_EMPTY_ARRAY', 'T_KEYWORD_NON_EMPTY_LIST', 'T_KEYWORD_SUPER', 'T_KEYWORD_OBJECT', 'T_KEYWORD_ARRAY', 'T_KEYWORD_LIST', 'T_KEYWORD_STATIC', 'T_KEYWORD_FROM', 'T_KEYWORD_NOT', 'T_KEYWORD_IS', 'T_KEYWORD_OF', 'T_KEYWORD_AS', 'T_KEYWORD_FALSE', 'T_KEYWORD_TRUE', 'T_KEYWORD_NULL', 'T_KEYWORD_ARRAY_ANY_CASE', 'T_FLOAT', 'T_INTEGER', 'T_SINGLE_QUOTED_STRING', 'T_DOUBLE_QUOTED_STRING', 'T_DOCTRINE_ANNOTATION_STRING', 'T_IDENTIFIER', 'T_THIS_VARIABLE', 'T_VARIABLE', 'T_REFERENCE', 'T_UNION', 'T_INTERSECTION', 'T_NULLABLE', 'T_NEGATED', 'T_OPEN_PARENTHESES', 'T_CLOSE_PARENTHESES', 'T_OPEN_ANGLE_BRACKET', 'T_CLOSE_ANGLE_BRACKET', 'T_OPEN_SQUARE_BRACKET', 'T_CLOSE_SQUARE_BRACKET', 'T_OPEN_CURLY_BRACKET', 'T_CLOSE_CURLY_BRACKET', 'T_COMMA', 'T_COMMENT', 'T_VARIADIC', 'T_DOUBLE_COLON', 'T_DOUBLE_ARROW', 'T_ARROW', 'T_EQUAL', 'T_COLON', 'T_OPEN_PHPDOC', 'T_CLOSE_PHPDOC', 'T_WILDCARD', 'T_TAG_PARAM_IMMEDIATELY_INVOKED_CALLABLE', 'T_TAG_PURE_UNLESS_CALLABLE_IS_IMPURE', 'T_TAG_PURE_UNLESS_PARAMETER_PASSED', 'T_TAG_PARAM_LATER_INVOKED_CALLABLE', 'T_TAG_PARAM_CLOSURE_THIS', 'T_TAG_REQUIRE_IMPLEMENTS', 'T_TAG_REQUIRE_EXTENDS', 'T_TAG_TYPE_ALIAS_IMPORT', 'T_TAG_ASSERT', 'T_TAG_SELF_OUT', 'T_TAG_SEALED', 'T_TAG_TEMPLATE', 'T_TAG_PROPERTY', 'T_TAG_IMPLEMENTS', 'T_TAG_DEPRECATED', 'T_TAG_TYPE_ALIAS', 'T_TAG_PARAM_OUT', 'T_TAG_EXTENDS', 'T_TAG_METHOD', 'T_TAG_RETURN', 'T_TAG_THROWS', 'T_TAG_PARAM', 'T_TAG_MIXIN', 'T_TAG_USE', 'T_TAG_VAR', 'T_PHPDOC_TAG_WS', 'T_PHPDOC_TAG', 'T_DOCTRINE_TAG_WS', 'T_DOCTRINE_TAG', 'T_PHPDOC_EOL', 'T_OTHER', 'T_OPEN_PARENTHESES_WS', 'T_OPEN_CURLY_BRACKET_WS', 'T_OPEN_SQUARE_BRACKET_WS', 'T_WILDCARD_WS', 'T_OPEN_ANGLE_BRACKET_HTML', '/[^\\s]++/'];

	/** the pattern of the lexer, marking each token by its identifier */
	public const LEXER_PATTERN = '~(?:[\\x09\\x20]++)(*MARK:0)|(?:[+\\-]?(?:(?:[0-9]++(?:_[0-9]++)*\\.[0-9]*+(?:_[0-9]++)*(?:e[+\\-]?[0-9]++(?:_[0-9]++)*)?)|(?:[0-9]*+(?:_[0-9]++)*\\.[0-9]++(?:_[0-9]++)*(?:e[+\\-]?[0-9]++(?:_[0-9]++)*)?)|(?:[0-9]++(?:_[0-9]++)*e[+\\-]?[0-9]++(?:_[0-9]++)*)))(*MARK:19)|(?:[+\\-]?(?:(?:0b[0-1]++(?:_[0-1]++)*)|(?:0o[0-7]++(?:_[0-7]++)*)|(?:0x[0-9a-f]++(?:_[0-9a-f]++)*)|(?:[0-9]++(?:_[0-9]++)*)))(*MARK:20)|(?:\'(?:\\\\[^\\r\\n]|[^\'\\r\\n\\\\])*+\')(*MARK:21)|(?:"(?:\\\\[^\\r\\n]|[^"\\r\\n\\\\])*+")(*MARK:22)|(?:"(?:""|[^"])*+")(*MARK:23)|(?:(?:[\\\\]?+[a-z_\\x80-\\xFF][0-9a-z_\\x80-\\xFF-]*+)++)(*MARK:24)|(?:\\$this(?![0-9a-z_\\x80-\\xFF]))(*MARK:25)|(?:\\$[a-z_\\x80-\\xFF][0-9a-z_\\x80-\\xFF]*+)(*MARK:26)|(?:&(?=\\s*+(?:[,=)]|\\.\\.\\.|(?:\\$(?!this(?![0-9a-z_\\x80-\\xFF]))))))(*MARK:27)|(?:\\|)(*MARK:28)|(?:&)(*MARK:29)|(?:\\?)(*MARK:30)|(?:!)(*MARK:31)|(?:\\()(*MARK:32)|(?:\\))(*MARK:33)|(?:<)(*MARK:34)|(?:>)(*MARK:35)|(?:\\[)(*MARK:36)|(?:\\])(*MARK:37)|(?:\\{)(*MARK:38)|(?:\\})(*MARK:39)|(?:,)(*MARK:40)|(?://[^\\r\\n]*(?=\\n|\\r|\\*/))(*MARK:41)|(?:\\.\\.\\.)(*MARK:42)|(?:::)(*MARK:43)|(?:=>)(*MARK:44)|(?:->)(*MARK:45)|(?:=)(*MARK:46)|(?::)(*MARK:47)|(?:/\\*\\*(?=\\s)\\x20?+)(*MARK:48)|(?:\\*/)(*MARK:49)|(?:\\*)(*MARK:50)|(?:@(?:[a-z][a-z0-9-\\\\]+:)?[a-z][a-z0-9-\\\\]*+)(*MARK:77)|(?:@[a-z_\\\\][a-z0-9_:\\\\]*[a-z_][a-z0-9_]*)(*MARK:79)|(?:\\r?+\\n[\\x09\\x20]*+(?:\\*(?!/)\\x20?+)?)(*MARK:80)|(?:(?:(?!\\*/)[^\\s])++)(*MARK:81)~Asi';

	/** @var array<int, int> the type of the lexer each token it reads is, by the identifier */
	public const LEXER_TYPES = [0 => 27, 19 => 19, 20 => 20, 21 => 21, 22 => 22, 23 => 23, 24 => 24, 25 => 25, 26 => 26, 27 => 0, 28 => 1, 29 => 2, 30 => 3, 31 => 35, 32 => 4, 33 => 5, 34 => 6, 35 => 7, 36 => 8, 37 => 9, 38 => 33, 39 => 34, 40 => 10, 41 => 37, 42 => 11, 43 => 12, 44 => 13, 45 => 36, 46 => 14, 47 => 31, 48 => 15, 49 => 16, 50 => 32, 77 => 17, 79 => 18, 80 => 28, 81 => 29];

	private const T00b73ddb = [7=>true,15=>true,16=>true,17=>true,18=>true];
	private const T02ca7bff = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,24=>true,32=>true,34=>true,82=>true,86=>true];
	private const T05cf281c = [1=>true,2=>true];
	private const T0979f8dc = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,23=>true,24=>true,25=>true,26=>true,27=>true,28=>true,29=>true,30=>true,31=>true,32=>true,33=>true,34=>true,35=>true,36=>true,37=>true,38=>true,39=>true,40=>true,41=>true,42=>true,43=>true,44=>true,45=>true,46=>true,47=>true,48=>true,50=>true,81=>true,82=>true,83=>true,84=>true,85=>true,86=>true];
	private const T1ad6c327 = [7=>true,18=>true];
	private const T1e2ed78c = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,22=>true,23=>true,24=>true,38=>true,51=>true,52=>true,53=>true,54=>true,55=>true,56=>true,57=>true,58=>true,59=>true,60=>true,61=>true,62=>true,63=>true,64=>true,65=>true,66=>true,67=>true,68=>true,69=>true,70=>true,71=>true,72=>true,73=>true,74=>true,75=>true,76=>true,77=>true,78=>true,79=>true,83=>true];
	private const T1e81050f = [7=>true,18=>true,36=>true,84=>true];
	private const T3bf4df75 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,24=>true,25=>true,26=>true,27=>true,30=>true,32=>true,42=>true,82=>true];
	private const T4d756b3a = [50=>true,85=>true];
	private const T7dfbf2bf = [22=>true,23=>true];
	private const T24d0f25b = [21=>true,22=>true];
	private const T29c7f960 = [25=>true,26=>true];
	private const T34c34eee = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,24=>true,30=>true,32=>true,82=>true];
	private const T46ee5f48 = [78=>true,79=>true];
	private const T50fd39f8 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,24=>true,25=>true,30=>true,32=>true,42=>true,82=>true];
	private const T53b29bff = [49=>true,80=>true];
	private const T61cd0e44 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,23=>true,24=>true,25=>true,26=>true,27=>true,28=>true,29=>true,30=>true,31=>true,32=>true,33=>true,34=>true,35=>true,36=>true,37=>true,38=>true,39=>true,40=>true,41=>true,42=>true,43=>true,44=>true,45=>true,46=>true,47=>true,48=>true,50=>true,51=>true,52=>true,53=>true,54=>true,55=>true,56=>true,57=>true,58=>true,59=>true,60=>true,61=>true,62=>true,63=>true,64=>true,65=>true,66=>true,67=>true,68=>true,69=>true,70=>true,71=>true,72=>true,73=>true,74=>true,75=>true,76=>true,77=>true,78=>true,79=>true,81=>true,82=>true,83=>true,84=>true,85=>true,86=>true];
	private const T69bf8cc4 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,24=>true,36=>true,84=>true];
	private const T83e30518 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,24=>true];
	private const T195b3f33 = [49=>true,51=>true,52=>true,53=>true,54=>true,55=>true,56=>true,57=>true,58=>true,59=>true,60=>true,61=>true,62=>true,63=>true,64=>true,65=>true,66=>true,67=>true,68=>true,69=>true,70=>true,71=>true,72=>true,73=>true,74=>true,75=>true,76=>true,77=>true,78=>true,79=>true];
	private const T553dc07b = [26=>true,27=>true,42=>true];
	private const T648acf56 = [28=>true,29=>true,32=>true,34=>true,36=>true,41=>true,43=>true,80=>true,82=>true,84=>true];
	private const T885cd349 = [36=>true,84=>true];
	private const T977f027a = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,24=>true,25=>true,32=>true,82=>true];
	private const T1490fe7f = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,23=>true,24=>true,38=>true,51=>true,52=>true,53=>true,54=>true,55=>true,56=>true,57=>true,58=>true,59=>true,60=>true,61=>true,62=>true,63=>true,64=>true,65=>true,66=>true,67=>true,68=>true,69=>true,70=>true,71=>true,72=>true,73=>true,74=>true,75=>true,76=>true,77=>true,78=>true,79=>true,83=>true];
	private const T4020d016 = [3=>true,4=>true,6=>true,7=>true,8=>true];
	private const T4546fd6c = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,24=>true];
	private const T5448ab4c = [41=>true,80=>true];
	private const T7680fe2e = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,24=>true,25=>true,30=>true,32=>true,82=>true];
	private const T7726a0df = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,20=>true,21=>true,22=>true,23=>true,24=>true];
	private const T8458d590 = [28=>true,29=>true,41=>true,80=>true];
	private const T34048a70 = [38=>true,83=>true];
	private const T942497bd = [76=>true,77=>true,78=>true,79=>true];
	private const T1921405e = [1=>true,2=>true,5=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,24=>true];
	private const T9373805c = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,24=>true,26=>true,27=>true,30=>true,32=>true,42=>true,82=>true];
	private const T96867045 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,24=>true];
	private const Ta8d79561 = [3=>true,4=>true,8=>true];
	private const Ta7068161 = [46=>true,47=>true];
	private const Tb3a20a39 = [76=>true,78=>true];
	private const Tb3cd2a63 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,24=>true,25=>true,30=>true,32=>true,50=>true,82=>true,85=>true];
	private const Tb46fb4f7 = [34=>true,86=>true];
	private const Tc8c984b6 = [28=>true,29=>true];
	private const Tc8cfa1df = [32=>true,82=>true];
	private const Tcd64b9c0 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,24=>true,25=>true,26=>true,30=>true,32=>true,82=>true];
	private const Tce49e0fc = [51=>true,52=>true,53=>true,54=>true,55=>true,56=>true,57=>true,58=>true,59=>true,60=>true,61=>true,62=>true,63=>true,64=>true,65=>true,66=>true,67=>true,68=>true,69=>true,70=>true,71=>true,72=>true,73=>true,74=>true,75=>true];
	private const Tce797685 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,20=>true,21=>true,22=>true,24=>true];
	private const Td08a6883 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,24=>true];
	private const Td08f0236 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,24=>true,50=>true,85=>true];
	private const Td8017923 = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,21=>true,22=>true,24=>true];
	private const Tf4b693bf = [51=>true,52=>true,53=>true,54=>true,55=>true,56=>true,57=>true,58=>true,59=>true,60=>true,61=>true,62=>true,63=>true,64=>true,65=>true,66=>true,67=>true,68=>true,69=>true,70=>true,71=>true,72=>true,73=>true,74=>true,75=>true,76=>true,77=>true,78=>true,79=>true];
	private const Tf6aec6a4 = [76=>true,77=>true];
	private const Tf86de613 = [32=>true,34=>true,82=>true,86=>true];
	private const Tfc0ac6dd = [3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,24=>true,25=>true,30=>true,32=>true,82=>true];

	/** the tokens each rule is expected to begin with */
	private const EXPECTED = [0=>[48],1=>[48],3=>[80],7=>[49],8=>[76,77,78,79],9=>[76,77,78,79],10=>[76,77,78,79],11=>[76,77],12=>[76,77],13=>[77],14=>[76],16=>[32,82],17=>[32],18=>[82],21=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,50,81,82,83,84,85,86],22=>[24],23=>[12],24=>[11],25=>[9],26=>[10],27=>[13],28=>[14],29=>[5],30=>[2],31=>[1],32=>[7],33=>[18],34=>[8],35=>[3],36=>[4],37=>[6],38=>[16],39=>[15],40=>[17],41=>[19],42=>[20],43=>[21],44=>[22],45=>[23],46=>[25],47=>[26],48=>[27],49=>[28],50=>[29],51=>[30],52=>[31],53=>[33],54=>[34],55=>[86],56=>[35],57=>[36],58=>[84],59=>[37],60=>[38],61=>[83],62=>[39],63=>[40],64=>[41],65=>[42],66=>[43],67=>[44],68=>[45],69=>[46],70=>[47],71=>[50],72=>[85],73=>[81],74=>[76,78],76=>[49,80],77=>[49,80],78=>[80],79=>[80],81=>[49,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79],82=>[79],83=>[78],84=>[72],85=>[51],86=>[54],87=>[55],88=>[52],89=>[53],90=>[75],91=>[70],92=>[71],93=>[73],94=>[57],95=>[56],96=>[61],97=>[65],98=>[63],99=>[69],100=>[62],101=>[68],102=>[64],103=>[74],104=>[66],105=>[58],106=>[59],107=>[60],108=>[67],109=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,50,81,82,83,84,85,86],110=>[80],111=>[80],113=>[80],114=>[80],115=>[80],116=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,81,82,83,84,85,86],118=>[51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79],119=>[51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79],121=>[76,78],123=>[51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75],124=>[51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75],128=>[80],129=>[80],130=>[80],131=>[80],132=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,81,82,83,84,85,86],133=>[76,78],134=>[76,77,78,79],135=>[78,79],136=>[78,79],137=>[32,82],138=>[32,82],141=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,22,23,24,38,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,83],142=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,22,23,24,38,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,83],143=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24],144=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24],145=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,22,23,24,38,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,83],146=>[51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79],148=>[38,83],149=>[38,83],151=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,38,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,83],152=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,38,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,83],153=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,23,24],154=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,23,24],155=>[22,23],156=>[22],158=>[22,23],159=>[22,23],160=>[1,2,3,4,5,6,8,9,10,11,12,13,14,24],162=>[7,15,16,17,18],163=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24,50,85],164=>[50],166=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24],168=>[50,85],169=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24],171=>[46,47],172=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24],173=>[19],174=>[20],176=>[40],177=>[40],178=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,38,39,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,83],179=>[39],180=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,38,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,83],182=>[40],183=>[40],184=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,22,23,24,33,38,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,83],185=>[33],186=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,22,23,24,38,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,83],187=>[76,77],189=>[51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79],190=>[51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75],191=>[72],192=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,26,27,30,32,42,82],193=>[26,27,42],194=>[26,27,42],195=>[26,27,42],198=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],199=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],200=>[30],201=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,32,82],202=>[32,82],204=>[41,80],205=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,26,30,32,82],206=>[26],208=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,32,82],209=>[12],210=>[12],213=>[28,29],214=>[28],215=>[28],216=>[28],218=>[29],219=>[29],220=>[29],223=>[36,84],224=>[36,84],225=>[36],227=>[36,84],228=>[36,84],229=>[25],230=>[7],231=>[38],233=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,42,82],234=>[42],235=>[34,86],236=>[34,86],237=>[34,86],238=>[34,86],240=>[40],243=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],244=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],245=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,24],246=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,24],247=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24],249=>[43],252=>[40],254=>[41],255=>[3,4,8],256=>[3,4,8],257=>[38],259=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,42,82],260=>[42],261=>[34,86],262=>[34,86],263=>[34,86],264=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],266=>[40],267=>[6],268=>[38],270=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,21,22,24],271=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,21,22,24],272=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,21,22,24],274=>[40],275=>[40],276=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,21,22,24,39],277=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,21,22,24],278=>[3,4,6,7,8],279=>[3,4,6,7,8],281=>[32,34,36,82,84],282=>[34],283=>[34],284=>[34],285=>[34],286=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24],287=>[13,14],288=>[13],289=>[14],293=>[5],294=>[5],296=>[46],297=>[46],300=>[40],301=>[40],302=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24,35],303=>[35],304=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24],306=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],307=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],311=>[40],312=>[40],313=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,33,82],314=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],315=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],316=>[32,82],317=>[3,4,6,7,8],318=>[34,36,84,86],319=>[34,86],320=>[34,86],321=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,50,82,85],322=>[1,2],323=>[1,2],324=>[3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],327=>[40],328=>[40],329=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,35,50,82,85],330=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,50,82,85],331=>[1,2,5,9,10,11,12,13,14,15,16,17,18,24],332=>[1,2,5,9,10,11,12,13,14,15,16,17,18,24],333=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24],335=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24],337=>[7,18,36,84],338=>[7,18],339=>[7,18],340=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,36,84],341=>[19],342=>[20],343=>[21,22],344=>[16],345=>[15],346=>[17],347=>[7,18],349=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,36,84],350=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,36,84],351=>[44],352=>[44],355=>[40],356=>[40],357=>[1,2,3,4,5,6,8,9,10,11,12,13,14,15,16,17,24],360=>[43],361=>[36,84],362=>[34],363=>[34],364=>[32,82],366=>[1,2,5,9,10,11,12,13,14,15,16,17,18,24],367=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,32,82],368=>[28,29,41,80],369=>[28,29,41,80],370=>[28,29],371=>[28],372=>[28],373=>[28],376=>[28],377=>[29],378=>[29],379=>[29],382=>[29],384=>[75],385=>[25,26],386=>[25,26],387=>[25,26],390=>[70],391=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],392=>[71],393=>[73],394=>[57],395=>[56],396=>[61],397=>[60],398=>[63],399=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],400=>[67],401=>[55],402=>[51],403=>[26],404=>[54],405=>[52],406=>[53],407=>[65],408=>[69],409=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],410=>[9],412=>[28,29,32,34,36,41,43,80,82,84],413=>[28,29,41,80],414=>[28,29],415=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24,32,34,82,86],416=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24],417=>[32,34,82,86],418=>[34,86],419=>[34,86],421=>[40],422=>[40],424=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24,26,27,30,32,42,82],425=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24,26,27,30,32,42,82],426=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24,30,32,82],427=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24,30,32,82],428=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24,30,32,82],429=>[46],430=>[46],431=>[26,27,42],433=>[40],434=>[40],435=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,30,32,82],436=>[62],437=>[68],438=>[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,24],439=>[64],440=>[74],441=>[66],442=>[66],443=>[58],444=>[14],445=>[14],446=>[59],448=>[25,26],449=>[45],450=>[45],451=>[32,82],452=>[32,82],454=>[51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79],458=>[49,80],459=>[49]];

	/** @var array<int, int> the identifier of the token at each position, the last one being -1 */
	protected array $ids = [];

	/** @var array<int, int> the index of the token of the lexer at each position */
	protected array $indexes = [];

	/** the position the reading is at */
	protected int $p = 0;

	/** @var array<int, mixed> the values built, as a stack */
	protected array $v = [];

	/** the size of the stack of values */
	protected int $n = 0;

	/** @var array<int, Comment> the comments read and not given to any node yet, by their index */
	protected array $comments = [];

	/** the furthest position a failure has been recorded at */
	private int $f = -1;

	/** @var list<int> the rules recorded as failed at the furthest position */
	private array $er = [];

	abstract protected function token(int $position): Token;


	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceType(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceSubType(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceNullable(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceAtomic(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceCallableReturnType(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceArrayOrOffsetAccessItem(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceGeneric(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceGenericInReturnType(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceGenericArgument(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceCallable(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceCallableWithTemplates(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceCallableTemplateArgument(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceCallableParameter(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceArrayShape(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceListShape(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceArrayShapeUnsealedType(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceListShapeUnsealedType(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceArrayShapeItem(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceArrayShapeKey(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceObjectShape(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceObjectShapeItem(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceObjectShapeKey(int $begin, int $end, $children);

	/**
	 * @return mixed
	 */
	abstract protected function reduceDiscardedComment(int $begin, int $end);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantFloat(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantInteger(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantString(int $begin, int $end, $children);

	/**
	 * @return mixed
	 */
	abstract protected function reduceConstantTrue(int $begin, int $end);

	/**
	 * @return mixed
	 */
	abstract protected function reduceConstantFalse(int $begin, int $end);

	/**
	 * @return mixed
	 */
	abstract protected function reduceConstantNull(int $begin, int $end);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantArrayLiteral(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantArrayShorthand(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantArrayItem(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantFetch(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reducePhpDoc(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceTextChild(int $begin, int $end, $children);

	/**
	 * @return mixed
	 */
	abstract protected function reduceText(int $begin, int $end);

	/**
	 * @return mixed
	 */
	abstract protected function reduceDoctrineText(int $begin, int $end);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceKnownTag(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceMethodParameter(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceGenericTag(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineTag(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceInvalidTag(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceInvalidTypeAliasTag(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineArgument(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineNestedAnnotation(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineArray(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineArrayItem(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineArrayKey(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineIdentifier(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineConstantFetch(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineFloat(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineInteger(int $begin, int $end, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineString(int $begin, int $end, $children);

	/**
	 * Where the last reading has failed, along with the tokens expected there.
	 *
	 * @return array{int, list<int>}
	 */
	protected function failure(): array
	{
		if ($this->f === -1 || $this->f < $this->p) {
			return [$this->p, []];
		}

		$expected = [];
		foreach ($this->er as $rule) {
			foreach (self::EXPECTED[$rule] ?? [] as $token) {
				$expected[$token] = true;
			}
		}

		ksort($expected);

		return [$this->f, array_keys($expected)];
	}

	/**
	 * Saves the state of the reading, so that a reading may be started in the
	 * middle of another one.
	 *
	 * @return array{int, int, list<int>}
	 */
	protected function saveReading(): array
	{
		return [$this->p, $this->f, $this->er];
	}

	/**
	 * @param array{int, int, list<int>} $state
	 */
	protected function restoreReading(array $state): void
	{
		[$this->p, $this->f, $this->er] = $state;
	}

	private function rec(int $rule): void
	{
		$position = $this->p;
		if ($position > $this->f) {
			$this->f = $position;
			$this->er = [$rule];
			return;
		}

		if ($position !== $this->f || isset($this->er[3])) {
			return;
		}

		$this->er[] = $rule;
	}

	/**
	 * The values pushed since the given size, the lists among them flattened.
	 *
	 * @return list<mixed>
	 */
	private function merge(int $from): array
	{
		$result = [];
		for ($i = $from, $n = $this->n; $i < $n; $i++) {
			$value = $this->v[$i];
			if (!is_array($value)) {
				$result[] = $value;
				continue;
			}

			foreach ($value as $nested) {
				$result[] = $nested;
			}
		}

		return $result;
	}

	/**
	 * Reads the given rule from the given position.
	 *
	 * @phpstan-impure
	 */
	protected function parseRule(int $rule, int $position, bool $build): bool
	{
		$this->p = $position;
		$this->f = -1;
		$this->er = [];

		switch ($rule) {
			case 0: // PhpDoc
				return $build ? $this->b0() : $this->s0();
			case 10: // UnreadTagChild
				return $build ? $this->b10() : $this->s10();
			case 189: // PhpDocChild
				return $build ? $this->b189() : $this->s189();
			case 190: // KnownTag
				return $build ? $this->b190() : $this->s190();
			case 199: // Type
				return $build ? $this->b199() : $this->s199();
			case 340: // ConstantExpr
				return $build ? $this->b340() : $this->s340();
			default:
				throw new \LogicException('Not an entrypoint: ' . $rule);
		}
	}

	private function b0(): bool
	{
		if (!($this->ids[$this->p] === 48)) {
			if ($this->p > $this->f) {
				$this->rec(0);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 48)) {
			if ($this->p >= $this->f) {
				$this->rec(1);
			}

			goto f1;}

		$this->p++;
		if (($this->ids[$this->p] === 80)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(3);
		}
		if (!$this->b5()) {
			goto n2;}
		n2:
		if (!($this->ids[$this->p] === 49)) {
			if ($this->p >= $this->f) {
				$this->rec(7);
			}

			goto f1;}

		$this->p++;
		goto k1;
		f1:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1:
		$this->v[$m1] = $this->reducePhpDoc(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b5(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (($this->ids[$this->p] === 49)) {
			if (($this->ids[$this->p] === 49)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f186;}
		}
		if (!$this->b8()) {
			goto f186;}
		// b460
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			// b461
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			if (($this->ids[$this->p] === 49)) {
				if (($this->ids[$this->p] === 49)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto f189;}
			}
			if (!$this->b8()) {
				goto f189;}
			goto k189;
			f189:
			$this->p = $o3;
			$this->n = $m3;
			$this->comments = $cs3;
			goto e188;
			k189:
			if ($this->p === $b2) {
				goto e188;
			}
		}
		e188:
		goto k186;
		f186:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k186:

		return true;
	}

	private function b8(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				// b188
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b189()) {
					goto f437;}
				// b458
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 49:
						if (!($this->ids[$this->p] === 49)) {
							if ($this->p > $this->f) {
								$this->rec(7);
							}

							goto n440;}
						goto a439;
						n440:
						break;
					case 80:
						$this->p++;
						goto a439;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f438;
				a439:
				goto k438;
				f438:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f437;
				k438:
				goto k437;
				f437:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n436;
				k437:
				goto a435;
				n436:
				break;
			case 76:
			case 77:
			case 78:
			case 79:
				if (!$this->b9()) {
					goto n441;}
				goto a435;
				n441:
				// b188
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b189()) {
					goto f443;}
				// b458
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 49:
						if (!($this->ids[$this->p] === 49)) {
							if ($this->p > $this->f) {
								$this->rec(7);
							}

							goto n446;}
						goto a445;
						n446:
						break;
					case 80:
						$this->p++;
						goto a445;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f444;
				a445:
				goto k444;
				f444:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f443;
				k444:
				goto k443;
				f443:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n442;
				k443:
				goto a435;
				n442:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(8);
		}

		goto f434;
		a435:
		goto k434;
		f434:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k434:

		return true;
	}

	private function b9(): bool
	{
		if (!isset(self::T942497bd[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(9);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		// b10
		if (!isset(self::T942497bd[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(10);
			}

			goto f715;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 76:
			case 77:
				if (!$this->b11()) {
					goto n718;}
				goto a717;
				n718:
				if (!$this->b134()) {
					goto n719;}
				goto a717;
				n719:
				break;
			case 78:
			case 79:
				if (!$this->b134()) {
					goto n720;}
				goto a717;
				n720:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(10);
		}

		goto f716;
		a717:
		goto k716;
		f716:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f715;
		k716:
		if (($this->ids[$this->p] === 80)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(3);
		}
		goto k715;
		f715:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k715:

		return true;
	}

	private function b10(): bool
	{
		if (!isset(self::T942497bd[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(10);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 76:
			case 77:
				if (!$this->b11()) {
					goto n11;}
				goto a10;
				n11:
				if (!$this->b134()) {
					goto n12;}
				goto a10;
				n12:
				break;
			case 78:
			case 79:
				if (!$this->b134()) {
					goto n13;}
				goto a10;
				n13:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(10);
		}

		goto f9;
		a10:
		goto k9;
		f9:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k9:

		return true;
	}

	private function b11(): bool
	{
		if (!isset(self::Tf6aec6a4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(11);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tf6aec6a4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(12);
			}

			goto f204;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f204;}
		}
		// b19
		$o2 = $this->p;
		// s19
		$o3 = $this->p;
		// s20
		$o4 = $this->p;
		while (true) {
			$b4 = $this->p;
			if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
				if ($b4 > $this->f) {
					$this->rec(21);
				}

				goto e208;
			}

			$this->p++;
		}
		e208:
		if (!$this->s74()) {
			goto f206;}
		goto k206;
		f206:
		$this->p = $o3;
		goto f204;
		k206:
		$this->v[$this->n++] = $this->reduceDoctrineText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k204;
		f204:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k204:
		$this->v[$m1] = $this->reduceGenericTag(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b134(): bool
	{
		if (!isset(self::T942497bd[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(134);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 76:
			case 77:
				if (!$this->b187()) {
					goto n211;}
				goto a210;
				n211:
				break;
			case 78:
			case 79:
				if (!$this->b135()) {
					goto n212;}
				goto a210;
				n212:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(134);
		}

		goto f209;
		a210:
		goto k209;
		f209:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k209:
		$this->v[$m1] = $this->reduceDoctrineTag(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b135(): bool
	{
		if (!isset(self::T46ee5f48[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(135);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T46ee5f48[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(136);
			}

			goto f452;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b137
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n455;}
				goto a454;
				n455:
				break;
			case 32:
			case 82:
				if (!$this->b138()) {
					goto n456;}
				goto a454;
				n456:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n457;}
				goto a454;
				n457:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(137);
		}

		goto f453;
		a454:
		goto k453;
		f453:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f452;
		k453:
		// b19
		$o2 = $this->p;
		// s19
		$o3 = $this->p;
		// s20
		$o4 = $this->p;
		while (true) {
			$b4 = $this->p;
			if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
				if ($b4 > $this->f) {
					$this->rec(21);
				}

				goto e461;
			}

			$this->p++;
		}
		e461:
		if (!$this->s74()) {
			goto f459;}
		goto k459;
		f459:
		$this->p = $o3;
		goto f452;
		k459:
		$this->v[$this->n++] = $this->reduceDoctrineText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k452;
		f452:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k452:

		return true;
	}

	private function b138(): bool
	{
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(138);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f721;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b139
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e723;
			}

			$this->p++;
		}
		e723:
		if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			// b141
			if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(141);
				}

				goto n724;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b142()) {
				goto f725;}
			// b139
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e727;
				}

				$this->p++;
			}
			e727:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b182()) {
					goto n728;}
				n728:
			} elseif ($this->p > $this->f) {
				$this->rec(182);
			}
			goto k725;
			f725:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n724;
			k725:
			n724:
		} elseif ($this->p > $this->f) {
			$this->rec(141);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f721;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k721;
		f721:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k721:

		return true;
	}

	private function b142(): bool
	{
		if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(142);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 51:
			case 52:
			case 53:
			case 54:
			case 55:
			case 56:
			case 57:
			case 58:
			case 59:
			case 60:
			case 61:
			case 62:
			case 63:
			case 64:
			case 65:
			case 66:
			case 67:
			case 68:
			case 69:
			case 70:
			case 71:
			case 72:
			case 73:
			case 74:
			case 75:
			case 76:
			case 77:
			case 78:
			case 79:
				if (!$this->b146()) {
					goto n974;}
				goto a973;
				n974:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 24:
				if (!$this->b143()) {
					goto n975;}
				goto a973;
				n975:
				if (!$this->b172()) {
					goto n976;}
				goto a973;
				n976:
				if (!$this->b160()) {
					goto n977;}
				goto a973;
				n977:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				if (!$this->b143()) {
					goto n978;}
				goto a973;
				n978:
				if (!$this->b172()) {
					goto n979;}
				goto a973;
				n979:
				break;
			case 22:
			case 23:
				if (!$this->b155()) {
					goto n980;}
				goto a973;
				n980:
				break;
			case 38:
			case 83:
				if (!$this->b148()) {
					goto n981;}
				goto a973;
				n981:
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceDoctrineFloat($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a973;
				break;
			case 20:
				$this->v[$this->n++] = $this->reduceDoctrineInteger($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a973;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(142);
		}

		goto f972;
		a973:
		goto k972;
		f972:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k972:
		$this->v[$m1] = $this->reduceDoctrineArgument(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b143(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(143);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1275;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b139
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1277;
			}

			$this->p++;
		}
		e1277:
		if (!($this->ids[$this->p] === 46)) {
			if ($this->p >= $this->f) {
				$this->rec(69);
			}

			goto f1275;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b139
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1279;
			}

			$this->p++;
		}
		e1279:
		if (!$this->b145()) {
			goto f1275;}
		goto k1275;
		f1275:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1275:

		return true;
	}

	private function b145(): bool
	{
		if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(145);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 51:
			case 52:
			case 53:
			case 54:
			case 55:
			case 56:
			case 57:
			case 58:
			case 59:
			case 60:
			case 61:
			case 62:
			case 63:
			case 64:
			case 65:
			case 66:
			case 67:
			case 68:
			case 69:
			case 70:
			case 71:
			case 72:
			case 73:
			case 74:
			case 75:
			case 76:
			case 77:
			case 78:
			case 79:
				if (!$this->b146()) {
					goto n1558;}
				goto a1557;
				n1558:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 24:
				if (!$this->b172()) {
					goto n1559;}
				goto a1557;
				n1559:
				if (!$this->b160()) {
					goto n1560;}
				goto a1557;
				n1560:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				if (!$this->b172()) {
					goto n1561;}
				goto a1557;
				n1561:
				break;
			case 22:
			case 23:
				if (!$this->b155()) {
					goto n1562;}
				goto a1557;
				n1562:
				break;
			case 38:
			case 83:
				if (!$this->b148()) {
					goto n1563;}
				goto a1557;
				n1563:
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceDoctrineFloat($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a1557;
				break;
			case 20:
				$this->v[$this->n++] = $this->reduceDoctrineInteger($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a1557;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(145);
		}

		goto f1556;
		a1557:
		goto k1556;
		f1556:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1556:

		return true;
	}

	private function b146(): bool
	{
		if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(146);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(119);
			}

			goto f1273;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if (!$this->b138()) {
				goto n1274;}
			n1274:
		} elseif ($this->p > $this->f) {
			$this->rec(138);
		}
		goto k1273;
		f1273:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1273:
		$this->v[$m1] = $this->reduceDoctrineNestedAnnotation(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b148(): bool
	{
		if (!isset(self::T34048a70[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(148);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T34048a70[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(149);
			}

			goto f1285;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b139
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1287;
			}

			$this->p++;
		}
		e1287:
		if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
			// b151
			if (!isset(self::T1490fe7f[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(151);
				}

				goto n1288;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b152()) {
				goto f1289;}
			// b139
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e1291;
				}

				$this->p++;
			}
			e1291:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b176()) {
					goto n1292;}
				n1292:
			} elseif ($this->p > $this->f) {
				$this->rec(176);
			}
			goto k1289;
			f1289:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1288;
			k1289:
			n1288:
		} elseif ($this->p > $this->f) {
			$this->rec(151);
		}
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f1285;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1285;
		f1285:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1285:
		$this->v[$m1] = $this->reduceDoctrineArray(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b152(): bool
	{
		if (!isset(self::T1490fe7f[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(152);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 51:
			case 52:
			case 53:
			case 54:
			case 55:
			case 56:
			case 57:
			case 58:
			case 59:
			case 60:
			case 61:
			case 62:
			case 63:
			case 64:
			case 65:
			case 66:
			case 67:
			case 68:
			case 69:
			case 70:
			case 71:
			case 72:
			case 73:
			case 74:
			case 75:
			case 76:
			case 77:
			case 78:
			case 79:
				if (!$this->b146()) {
					goto n1570;}
				goto a1569;
				n1570:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 24:
				if (!$this->b153()) {
					goto n1571;}
				goto a1569;
				n1571:
				if (!$this->b172()) {
					goto n1572;}
				goto a1569;
				n1572:
				if (!$this->b160()) {
					goto n1573;}
				goto a1569;
				n1573:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				if (!$this->b153()) {
					goto n1574;}
				goto a1569;
				n1574:
				if (!$this->b172()) {
					goto n1575;}
				goto a1569;
				n1575:
				break;
			case 22:
			case 23:
				if (!$this->b153()) {
					goto n1576;}
				goto a1569;
				n1576:
				if (!$this->b155()) {
					goto n1577;}
				goto a1569;
				n1577:
				break;
			case 38:
			case 83:
				if (!$this->b148()) {
					goto n1578;}
				goto a1569;
				n1578:
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceDoctrineFloat($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a1569;
				break;
			case 20:
				if (!$this->b153()) {
					goto n1579;}
				goto a1569;
				n1579:
				$this->v[$this->n++] = $this->reduceDoctrineInteger($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a1569;
				break;
			case 21:
				if (!$this->b153()) {
					goto n1580;}
				goto a1569;
				n1580:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(152);
		}

		goto f1568;
		a1569:
		goto k1568;
		f1568:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1568:
		$this->v[$m1] = $this->reduceDoctrineArrayItem(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b153(): bool
	{
		if (!isset(self::T7726a0df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(153);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!$this->b154()) {
			goto f1706;}
		// b139
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1708;
			}

			$this->p++;
		}
		e1708:
		if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(171);
			}

			goto f1706;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b139
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1710;
			}

			$this->p++;
		}
		e1710:
		if (!$this->b145()) {
			goto f1706;}
		goto k1706;
		f1706:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1706:

		return true;
	}

	private function b154(): bool
	{
		if (!isset(self::T7726a0df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(154);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 24:
				if (!$this->b160()) {
					goto n1751;}
				goto a1750;
				n1751:
				// b169
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(169);
					}

					goto n1752;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1753;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1753;}
				}
				goto k1753;
				f1753:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1752;
				k1753:
				goto a1750;
				n1752:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				// b169
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(169);
					}

					goto n1754;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1755;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1755;}
				}
				goto k1755;
				f1755:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1754;
				k1755:
				goto a1750;
				n1754:
				break;
			case 22:
			case 23:
				if (!$this->b155()) {
					goto n1756;}
				goto a1750;
				n1756:
				break;
			case 20:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1750;
				break;
			case 21:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1750;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(154);
		}

		goto f1749;
		a1750:
		goto k1749;
		f1749:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1749:
		$this->v[$m1] = $this->reduceDoctrineArrayKey(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b155(): bool
	{
		if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(155);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 22:
				if (!$this->b156()) {
					goto n1284;}
				goto a1283;
				n1284:
				break;
			case 23:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1283;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(155);
		}

		goto f1282;
		a1283:
		goto k1282;
		f1282:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1282:
		$this->v[$m1] = $this->reduceDoctrineString(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b156(): bool
	{
		if (!($this->ids[$this->p] === 22)) {
			if ($this->p > $this->f) {
				$this->rec(156);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 22)) {
			if ($this->p >= $this->f) {
				$this->rec(44);
			}

			goto f1564;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
			// b158
			if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(158);
				}

				goto n1565;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(159);
					}

					goto e1567;
				}

				$this->v[$this->n++] = $this->token($this->p++);

				$k2++;
			}
			e1567:

			if ($k2 < 1) {
				goto f1566;
			}
			goto k1566;
			f1566:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1565;
			k1566:
			n1565:
		} elseif ($this->p > $this->f) {
			$this->rec(158);
		}
		goto k1564;
		f1564:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1564:

		return true;
	}

	private function b160(): bool
	{
		if (!isset(self::T96867045[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(160);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
			if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f1281;}
		}
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1281;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 43)) {
			if ($this->p >= $this->f) {
				$this->rec(66);
			}

			goto f1281;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b163()) {
			goto f1281;}
		goto k1281;
		f1281:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1281:
		$this->v[$m1] = $this->reduceDoctrineConstantFetch(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b163(): bool
	{
		if (!isset(self::Td08f0236[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(163);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				// b166
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(166);
					}

					goto n693;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f694;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
					if (!$this->b168()) {
						goto n695;}
					n695:
				} elseif ($this->p > $this->f) {
					$this->rec(168);
				}
				goto k694;
				f694:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n693;
				k694:
				goto a692;
				n693:
				break;
			case 50:
				// b164
				if (!($this->ids[$this->p] === 50)) {
					if ($this->p > $this->f) {
						$this->rec(164);
					}

					goto n696;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 50)) {
					if ($this->p >= $this->f) {
						$this->rec(71);
					}

					goto f697;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					if (!$this->b166()) {
						goto n698;}
					n698:
				} elseif ($this->p > $this->f) {
					$this->rec(166);
				}
				goto k697;
				f697:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n696;
				k697:
				goto a692;
				n696:
				break;
			case 85:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a692;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(163);
		}

		goto f691;
		a692:
		goto k691;
		f691:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k691:

		return true;
	}

	private function b164(): bool
	{
		if (!($this->ids[$this->p] === 50)) {
			if ($this->p > $this->f) {
				$this->rec(164);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 50)) {
			if ($this->p >= $this->f) {
				$this->rec(71);
			}

			goto f1269;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T83e30518[$this->ids[$this->p]])) {
			if (!$this->b166()) {
				goto n1270;}
			n1270:
		} elseif ($this->p > $this->f) {
			$this->rec(166);
		}
		goto k1269;
		f1269:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1269:

		return true;
	}

	private function b166(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(166);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f947;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
			if (!$this->b168()) {
				goto n948;}
			n948:
		} elseif ($this->p > $this->f) {
			$this->rec(168);
		}
		goto k947;
		f947:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k947:

		return true;
	}

	private function b168(): bool
	{
		if (!isset(self::T4d756b3a[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(168);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 50:
				if (!$this->b164()) {
					goto n946;}
				goto a945;
				n946:
				break;
			case 85:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a945;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(168);
		}

		goto f944;
		a945:
		goto k944;
		f944:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k944:

		return true;
	}

	private function b172(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(172);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1280;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f1280;}
		}
		goto k1280;
		f1280:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1280:
		$this->v[$m1] = $this->reduceDoctrineIdentifier(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b176(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(176);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(177);
				}

				goto e1582;
			}

			// b177
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(177);
				}

				goto e1582;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1583;}

			$this->v[$this->n++] = $this->token($this->p++);
			// b139
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e1585;
				}

				$this->p++;
			}
			e1585:
			if (!$this->b178()) {
				goto f1583;}
			goto k1583;
			f1583:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1582;
			k1583:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1582;
			}

			$k1++;
		}
		e1582:

		if ($k1 < 1) {
			goto f1581;
		}
		goto k1581;
		f1581:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1581:

		return true;
	}

	private function b178(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				// b180
				if (!isset(self::T1490fe7f[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(180);
					}

					goto n1713;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b152()) {
					goto f1714;}
				// b139
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1716;
					}

					$this->p++;
				}
				e1716:
				goto k1714;
				f1714:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1713;
				k1714:
				goto a1712;
				n1713:
				break;
			case 0:
			case 25:
			case 26:
			case 27:
			case 28:
			case 29:
			case 30:
			case 31:
			case 32:
			case 33:
			case 34:
			case 35:
			case 36:
			case 37:
			case 40:
			case 41:
			case 42:
			case 43:
			case 44:
			case 45:
			case 46:
			case 47:
			case 48:
			case 49:
			case 50:
			case 80:
			case 81:
			case 82:
			case 84:
			case 85:
			case 86:
			case -1:
				break;
			case 39:
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p > $this->f) {
						$this->rec(62);
					}

					goto n1717;}
				goto a1712;
				n1717:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(178);
		}

		goto f1711;
		a1712:
		goto k1711;
		f1711:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1711:

		return true;
	}

	private function b182(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(182);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(183);
				}

				goto e983;
			}

			// b183
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(183);
				}

				goto e983;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f984;}

			$this->v[$this->n++] = $this->token($this->p++);
			// b139
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e986;
				}

				$this->p++;
			}
			e986:
			if (!$this->b184()) {
				goto f984;}
			goto k984;
			f984:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e983;
			k984:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e983;
			}

			$k1++;
		}
		e983:

		if ($k1 < 1) {
			goto f982;
		}
		goto k982;
		f982:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k982:

		return true;
	}

	private function b184(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				// b186
				if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(186);
					}

					goto n1295;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b142()) {
					goto f1296;}
				// b139
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1298;
					}

					$this->p++;
				}
				e1298:
				goto k1296;
				f1296:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1295;
				k1296:
				goto a1294;
				n1295:
				break;
			case 0:
			case 21:
			case 25:
			case 26:
			case 27:
			case 28:
			case 29:
			case 30:
			case 31:
			case 32:
			case 34:
			case 35:
			case 36:
			case 37:
			case 39:
			case 40:
			case 41:
			case 42:
			case 43:
			case 44:
			case 45:
			case 46:
			case 47:
			case 48:
			case 49:
			case 50:
			case 80:
			case 81:
			case 82:
			case 84:
			case 85:
			case 86:
			case -1:
				break;
			case 33:
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p > $this->f) {
						$this->rec(53);
					}

					goto n1299;}
				goto a1294;
				n1299:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(184);
		}

		goto f1293;
		a1294:
		goto k1293;
		f1293:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1293:

		return true;
	}

	private function b187(): bool
	{
		if (!isset(self::Tf6aec6a4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(187);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tf6aec6a4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(12);
			}

			goto f447;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b138()) {
			goto f447;}
		// b19
		$o2 = $this->p;
		// s19
		$o3 = $this->p;
		// s20
		$o4 = $this->p;
		while (true) {
			$b4 = $this->p;
			if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
				if ($b4 > $this->f) {
					$this->rec(21);
				}

				goto e451;
			}

			$this->p++;
		}
		e451:
		if (!$this->s74()) {
			goto f449;}
		goto k449;
		f449:
		$this->p = $o3;
		goto f447;
		k449:
		$this->v[$this->n++] = $this->reduceDoctrineText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k447;
		f447:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k447:

		return true;
	}

	private function b189(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (!$this->b455()) {
					goto n33;}
				goto a32;
				n33:
				break;
			case 51:
			case 52:
			case 53:
			case 54:
			case 55:
			case 56:
			case 57:
			case 58:
			case 59:
			case 60:
			case 61:
			case 62:
			case 63:
			case 64:
			case 65:
			case 66:
			case 67:
			case 68:
			case 69:
			case 70:
			case 71:
			case 72:
			case 73:
			case 74:
			case 75:
				if (!$this->b190()) {
					goto n34;}
				goto a32;
				n34:
				if (!$this->b454()) {
					goto n35;}
				goto a32;
				n35:
				if (!$this->b455()) {
					goto n36;}
				goto a32;
				n36:
				break;
			case 76:
			case 77:
			case 78:
			case 79:
				if (!$this->b454()) {
					goto n37;}
				goto a32;
				n37:
				if (!$this->b455()) {
					goto n38;}
				goto a32;
				n38:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(189);
		}

		goto f31;
		a32:
		goto k31;
		f31:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k31:

		return true;
	}

	private function b190(): bool
	{
		if (!isset(self::Tce49e0fc[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(190);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 51:
				if (!$this->b402()) {
					goto n55;}
				goto a54;
				n55:
				break;
			case 52:
				if (!$this->b405()) {
					goto n56;}
				goto a54;
				n56:
				break;
			case 53:
				if (!$this->b406()) {
					goto n57;}
				goto a54;
				n57:
				break;
			case 54:
				if (!$this->b404()) {
					goto n58;}
				goto a54;
				n58:
				break;
			case 55:
				if (!$this->b401()) {
					goto n59;}
				goto a54;
				n59:
				break;
			case 56:
				// b395
				if (!($this->ids[$this->p] === 56)) {
					if ($this->p > $this->f) {
						$this->rec(395);
					}

					goto n60;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 56)) {
					if ($this->p >= $this->f) {
						$this->rec(95);
					}

					goto f61;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f61;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f62;}
				if (!$this->b388()) {
					goto f62;}
				goto k62;
				f62:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f61;
				k62:
				goto k61;
				f61:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n60;
				k61:
				goto a54;
				n60:
				break;
			case 57:
				// b394
				if (!($this->ids[$this->p] === 57)) {
					if ($this->p > $this->f) {
						$this->rec(394);
					}

					goto n63;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 57)) {
					if ($this->p >= $this->f) {
						$this->rec(94);
					}

					goto f64;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f64;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f65;}
				if (!$this->b388()) {
					goto f65;}
				goto k65;
				f65:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f64;
				k65:
				goto k64;
				f64:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n63;
				k64:
				goto a54;
				n63:
				break;
			case 58:
				if (!$this->b443()) {
					goto n66;}
				goto a54;
				n66:
				break;
			case 59:
				if (!$this->b446()) {
					goto n67;}
				goto a54;
				n67:
				break;
			case 60:
				// b397
				if (!($this->ids[$this->p] === 60)) {
					if ($this->p > $this->f) {
						$this->rec(397);
					}

					goto n68;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 60)) {
					if ($this->p >= $this->f) {
						$this->rec(107);
					}

					goto f69;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f69;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f70;}
				if (!$this->b388()) {
					goto f70;}
				goto k70;
				f70:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f69;
				k70:
				goto k69;
				f69:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n68;
				k69:
				goto a54;
				n68:
				break;
			case 61:
				// b396
				if (!($this->ids[$this->p] === 61)) {
					if ($this->p > $this->f) {
						$this->rec(396);
					}

					goto n71;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 61)) {
					if ($this->p >= $this->f) {
						$this->rec(96);
					}

					goto f72;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f72;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f73;}
				if (!$this->b388()) {
					goto f73;}
				goto k73;
				f73:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f72;
				k73:
				goto k72;
				f72:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n71;
				k72:
				goto a54;
				n71:
				break;
			case 62:
				if (!$this->b436()) {
					goto n74;}
				goto a54;
				n74:
				break;
			case 63:
				if (!$this->b398()) {
					goto n75;}
				goto a54;
				n75:
				break;
			case 64:
				if (!$this->b439()) {
					goto n76;}
				goto a54;
				n76:
				break;
			case 65:
				// b407
				if (!($this->ids[$this->p] === 65)) {
					if ($this->p > $this->f) {
						$this->rec(407);
					}

					goto n77;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 65)) {
					if ($this->p >= $this->f) {
						$this->rec(97);
					}

					goto f78;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f78;}
				$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
				goto k78;
				f78:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n77;
				k78:
				goto a54;
				n77:
				break;
			case 66:
				if (!$this->b441()) {
					goto n80;}
				goto a54;
				n80:
				if (!$this->b442()) {
					goto n81;}
				goto a54;
				n81:
				break;
			case 67:
				if (!$this->b400()) {
					goto n82;}
				goto a54;
				n82:
				break;
			case 68:
				if (!$this->b437()) {
					goto n83;}
				goto a54;
				n83:
				break;
			case 69:
				// b408
				if (!($this->ids[$this->p] === 69)) {
					if ($this->p > $this->f) {
						$this->rec(408);
					}

					goto n84;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 69)) {
					if ($this->p >= $this->f) {
						$this->rec(99);
					}

					goto f85;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b409()) {
					goto f85;}
				goto k85;
				f85:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n84;
				k85:
				goto a54;
				n84:
				break;
			case 70:
				// b390
				if (!($this->ids[$this->p] === 70)) {
					if ($this->p > $this->f) {
						$this->rec(390);
					}

					goto n86;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 70)) {
					if ($this->p >= $this->f) {
						$this->rec(91);
					}

					goto f87;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f87;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f88;}
				if (!$this->b388()) {
					goto f88;}
				goto k88;
				f88:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f87;
				k88:
				goto k87;
				f87:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n86;
				k87:
				goto a54;
				n86:
				break;
			case 71:
				// b392
				if (!($this->ids[$this->p] === 71)) {
					if ($this->p > $this->f) {
						$this->rec(392);
					}

					goto n89;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 71)) {
					if ($this->p >= $this->f) {
						$this->rec(92);
					}

					goto f90;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f90;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f91;}
				if (!$this->b388()) {
					goto f91;}
				goto k91;
				f91:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f90;
				k91:
				goto k90;
				f90:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n89;
				k90:
				goto a54;
				n89:
				break;
			case 72:
				if (!$this->b191()) {
					goto n92;}
				goto a54;
				n92:
				break;
			case 73:
				// b393
				if (!($this->ids[$this->p] === 73)) {
					if ($this->p > $this->f) {
						$this->rec(393);
					}

					goto n93;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 73)) {
					if ($this->p >= $this->f) {
						$this->rec(93);
					}

					goto f94;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f94;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f95;}
				if (!$this->b388()) {
					goto f95;}
				goto k95;
				f95:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f94;
				k95:
				goto k94;
				f94:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n93;
				k94:
				goto a54;
				n93:
				break;
			case 74:
				if (!$this->b440()) {
					goto n96;}
				goto a54;
				n96:
				break;
			case 75:
				if (!$this->b384()) {
					goto n97;}
				goto a54;
				n97:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(190);
		}

		goto f53;
		a54:
		goto k53;
		f53:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k53:
		$this->v[$m1] = $this->reduceKnownTag(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b191(): bool
	{
		if (!($this->ids[$this->p] === 72)) {
			if ($this->p > $this->f) {
				$this->rec(191);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 72)) {
			if ($this->p >= $this->f) {
				$this->rec(84);
			}

			goto f295;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b192()) {
			goto f295;}
		goto k295;
		f295:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k295:

		return true;
	}

	private function b192(): bool
	{
		if (!isset(self::T3bf4df75[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(192);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->b198()) {
					goto n544;}
				goto a543;
				n544:
				break;
			case 26:
			case 27:
			case 42:
				if (!$this->b193()) {
					goto n545;}
				goto a543;
				n545:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(192);
		}

		goto f542;
		a543:
		goto k542;
		f542:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k542:

		return true;
	}

	private function b193(): bool
	{
		if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(193);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(195);
			}

			goto f795;}
		if (($this->ids[$this->p] === 27)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(48);
		}
		if (($this->ids[$this->p] === 42)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(65);
		}
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f795;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f795;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k795;
		f795:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k795:

		return true;
	}

	private function b198(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(198);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!$this->b199()) {
			goto f793;}
		if (($this->ids[$this->p] === 27)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(48);
		}
		if (($this->ids[$this->p] === 42)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(65);
		}
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f793;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f793;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k793;
		f793:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k793:

		return true;
	}

	private function b199(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(199);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 32:
			case 82:
				// b367
				if (!isset(self::T977f027a[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(367);
					}

					goto n160;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b201()) {
					goto f161;}
				if (!$this->b368()) {
					goto f161;}
				goto k161;
				f161:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n160;
				k161:
				goto a159;
				n160:
				break;
			case 30:
				// b200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n162;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f163;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b201()) {
					goto f163;}
				goto k163;
				f163:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n162;
				k163:
				$this->v[$m2] = $this->reduceNullable(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a159;
				n162:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(199);
		}

		goto f158;
		a159:
		goto k158;
		f158:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k158:
		$this->v[$m1] = $this->reduceType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b201(): bool
	{
		if (!isset(self::T977f027a[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(201);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 5:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				// b366
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(366);
					}

					goto n342;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(332);
					}

					goto f343;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f343;}
				}
				if (!$this->b281()) {
					goto f343;}
				goto k343;
				f343:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n342;
				k343:
				goto a341;
				n342:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n344;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f345;}
				}
				if (!$this->b335()) {
					goto f345;}
				goto k345;
				f345:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n344;
				k345:
				goto a341;
				n344:
				break;
			case 19:
			case 20:
			case 21:
			case 22:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n347;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f348;}
				}
				if (!$this->b335()) {
					goto f348;}
				goto k348;
				f348:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n347;
				k348:
				goto a341;
				n347:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->b255()) {
					goto n350;}
				goto a341;
				n350:
				// b278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n351;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f352;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f352;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f352;}
				}
				if (!$this->b281()) {
					goto f352;}
				goto k352;
				f352:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n351;
				k352:
				goto a341;
				n351:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n353;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f354;}
				}
				if (!$this->b335()) {
					goto f354;}
				goto k354;
				f354:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n353;
				k354:
				goto a341;
				n353:
				break;
			case 32:
			case 82:
				if (!$this->b202()) {
					goto n356;}
				goto a341;
				n356:
				break;
			case 6:
				if (!$this->b267()) {
					goto n357;}
				goto a341;
				n357:
				// b278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n358;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f359;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f359;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f359;}
				}
				if (!$this->b281()) {
					goto f359;}
				goto k359;
				f359:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n358;
				k359:
				goto a341;
				n358:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n360;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f361;}
				}
				if (!$this->b335()) {
					goto f361;}
				goto k361;
				f361:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n360;
				k361:
				goto a341;
				n360:
				break;
			case 7:
				if (!$this->b230()) {
					goto n363;}
				goto a341;
				n363:
				// b278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n364;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f365;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f365;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f365;}
				}
				if (!$this->b281()) {
					goto f365;}
				goto k365;
				f365:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n364;
				k365:
				goto a341;
				n364:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n366;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f367;}
				}
				if (!$this->b335()) {
					goto f367;}
				goto k367;
				f367:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n366;
				k367:
				goto a341;
				n366:
				break;
			case 25:
				if (!$this->b229()) {
					goto n369;}
				goto a341;
				n369:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(201);
		}

		goto f340;
		a341:
		goto k340;
		f340:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k340:
		$this->v[$m1] = $this->reduceAtomic(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b202(): bool
	{
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(202);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f617;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e619;
			}

			$this->p++;
		}
		e619:
		if (!$this->b205()) {
			goto f617;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e621;
			}

			$this->p++;
		}
		e621:
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f617;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n622;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e624;
				}

				if (!$this->b224()) {
					goto e624;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e624;
				}

				$k2++;
			}
			e624:

			if ($k2 < 1) {
				goto f623;
			}
			goto k623;
			f623:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n622;
			k623:
			n622:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k617;
		f617:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k617:

		return true;
	}

	private function b205(): bool
	{
		if (!isset(self::Tcd64b9c0[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(205);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 32:
			case 82:
				// b208
				if (!isset(self::T977f027a[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(208);
					}

					goto n854;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b201()) {
					goto f855;}
				if (!$this->b209()) {
					goto f855;}
				goto k855;
				f855:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n854;
				k855:
				goto a853;
				n854:
				break;
			case 26:
				if (!$this->b206()) {
					goto n856;}
				goto a853;
				n856:
				break;
			case 30:
				// b200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n857;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f858;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b201()) {
					goto f858;}
				goto k858;
				f858:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n857;
				k858:
				$this->v[$m2] = $this->reduceNullable(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a853;
				n857:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(205);
		}

		goto f852;
		a853:
		goto k852;
		f852:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k852:
		$this->v[$m1] = $this->reduceSubType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b206(): bool
	{
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p > $this->f) {
				$this->rec(206);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f1118;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 12)) {
			if ($this->p >= $this->f) {
				$this->rec(23);
			}

			goto f1118;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 11)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->b199()) {
			goto f1118;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1120;
			}

			$this->p++;
		}
		e1120:
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
			}

			goto f1118;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1122;
			}

			$this->p++;
		}
		e1122:
		if (!$this->b199()) {
			goto f1118;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1124;
			}

			$this->p++;
		}
		e1124:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1118;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1126;
			}

			$this->p++;
		}
		e1126:
		if (!$this->b205()) {
			goto f1118;}
		goto k1118;
		f1118:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1118:

		return true;
	}

	private function b209(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				// b211
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				// b203
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1110;
					}

					$this->p++;
				}
				e1110:
				if (isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if (!$this->b213()) {
						goto n1111;}
					n1111:
				} elseif ($this->p > $this->f) {
					$this->rec(213);
				}
				goto a1106;
				n1107:
				break;
			case 12:
				if (!$this->b210()) {
					goto n1112;}
				goto a1106;
				n1112:
				// b211
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				// b203
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1116;
					}

					$this->p++;
				}
				e1116:
				if (isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if (!$this->b213()) {
						goto n1117;}
					n1117:
				} elseif ($this->p > $this->f) {
					$this->rec(213);
				}
				goto a1106;
				n1113:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(209);
		}

		goto f1105;
		a1106:
		goto k1105;
		f1105:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1105:

		return true;
	}

	private function b210(): bool
	{
		if (!($this->ids[$this->p] === 12)) {
			if ($this->p > $this->f) {
				$this->rec(210);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 12)) {
			if ($this->p >= $this->f) {
				$this->rec(23);
			}

			goto f1445;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 11)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->b199()) {
			goto f1445;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1447;
			}

			$this->p++;
		}
		e1447:
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
			}

			goto f1445;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1449;
			}

			$this->p++;
		}
		e1449:
		if (!$this->b199()) {
			goto f1445;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1451;
			}

			$this->p++;
		}
		e1451:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1445;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1453;
			}

			$this->p++;
		}
		e1453:
		if (!$this->b205()) {
			goto f1445;}
		goto k1445;
		f1445:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1445:

		return true;
	}

	private function b213(): bool
	{
		if (!isset(self::Tc8c984b6[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(213);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 28:
				if (!$this->b214()) {
					goto n1443;}
				goto a1442;
				n1443:
				break;
			case 29:
				if (!$this->b218()) {
					goto n1444;}
				goto a1442;
				n1444:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(213);
		}

		goto f1441;
		a1442:
		goto k1441;
		f1441:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1441:

		return true;
	}

	private function b214(): bool
	{
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(214);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		// b215
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(215);
			}

			goto f1642;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		$k2 = 0;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 28)) {
				if ($b2 > $this->f) {
					$this->rec(216);
				}

				goto e1644;
			}

			if (!$this->b216()) {
				goto e1644;}
			if ($this->p === $b2) {
				$k2 = 1;
				goto e1644;
			}

			$k2++;
		}
		e1644:

		if ($k2 < 1) {
			goto f1643;
		}
		goto k1643;
		f1643:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1642;
		k1643:
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f1642;}
		}
		goto k1642;
		f1642:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1642:

		return true;
	}

	private function b216(): bool
	{
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(216);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p >= $this->f) {
				$this->rec(49);
			}

			goto f1726;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1728;
			}

			$this->p++;
		}
		e1728:
		if (!$this->b201()) {
			goto f1726;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1730;
			}

			$this->p++;
		}
		e1730:
		goto k1726;
		f1726:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1726:

		return true;
	}

	private function b218(): bool
	{
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(218);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		// b219
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(219);
			}

			goto f1645;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		$k2 = 0;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 29)) {
				if ($b2 > $this->f) {
					$this->rec(220);
				}

				goto e1647;
			}

			if (!$this->b220()) {
				goto e1647;}
			if ($this->p === $b2) {
				$k2 = 1;
				goto e1647;
			}

			$k2++;
		}
		e1647:

		if ($k2 < 1) {
			goto f1646;
		}
		goto k1646;
		f1646:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1645;
		k1646:
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f1645;}
		}
		goto k1645;
		f1645:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1645:

		return true;
	}

	private function b220(): bool
	{
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(220);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p >= $this->f) {
				$this->rec(50);
			}

			goto f1731;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1733;
			}

			$this->p++;
		}
		e1733:
		if (!$this->b201()) {
			goto f1731;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1735;
			}

			$this->p++;
		}
		e1735:
		goto k1731;
		f1731:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1731:

		return true;
	}

	private function b224(): bool
	{
		if (!isset(self::T885cd349[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(224);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 36:
				// b225
				if (!($this->ids[$this->p] === 36)) {
					if ($this->p > $this->f) {
						$this->rec(225);
					}

					goto n831;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 36)) {
					if ($this->p >= $this->f) {
						$this->rec(57);
					}

					goto f832;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 37)) {
					if (($this->ids[$this->p] === 37)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f832;}
				}
				if (!$this->b199()) {
					goto f832;}
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f832;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k832;
				f832:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n831;
				k832:
				goto a830;
				n831:
				// b227
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(227);
					}

					goto n833;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(228);
					}

					goto f834;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f834;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k834;
				f834:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n833;
				k834:
				goto a830;
				n833:
				break;
			case 84:
				// b227
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(227);
					}

					goto n835;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(228);
					}

					goto f836;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f836;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k836;
				f836:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n835;
				k836:
				goto a830;
				n835:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(224);
		}

		goto f829;
		a830:
		goto k829;
		f829:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k829:
		$this->v[$m1] = $this->reduceArrayOrOffsetAccessItem(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b229(): bool
	{
		if (!($this->ids[$this->p] === 25)) {
			if ($this->p > $this->f) {
				$this->rec(229);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 25)) {
			if ($this->p >= $this->f) {
				$this->rec(46);
			}

			goto f633;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n634;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e636;
				}

				if (!$this->b224()) {
					goto e636;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e636;
				}

				$k2++;
			}
			e636:

			if ($k2 < 1) {
				goto f635;
			}
			goto k635;
			f635:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n634;
			k635:
			n634:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k633;
		f633:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k633:

		return true;
	}

	private function b230(): bool
	{
		if (!($this->ids[$this->p] === 7)) {
			if ($this->p > $this->f) {
				$this->rec(230);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 7)) {
			if ($this->p >= $this->f) {
				$this->rec(32);
			}

			goto f629;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f629;}
		}
		if (!$this->b231()) {
			goto f629;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n630;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e632;
				}

				if (!$this->b224()) {
					goto e632;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e632;
				}

				$k2++;
			}
			e632:

			if ($k2 < 1) {
				goto f631;
			}
			goto k631;
			f631:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n630;
			k631:
			n630:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k629;
		f629:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k629:

		return true;
	}

	private function b231(): bool
	{
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p > $this->f) {
				$this->rec(231);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p >= $this->f) {
				$this->rec(60);
			}

			goto f867;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e869;
			}

			$this->p++;
		}
		e869:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->b233()) {
				goto n870;}
			n870:
		} elseif ($this->p > $this->f) {
			$this->rec(233);
		}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e872;
			}

			$this->p++;
		}
		e872:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f867;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k867;
		f867:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k867:
		$this->v[$m1] = $this->reduceArrayShape(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b233(): bool
	{
		if (!isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(233);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				// b243
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(243);
					}

					goto n1135;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b244()) {
					goto f1136;}
				// b203
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1138;
					}

					$this->p++;
				}
				e1138:
				if (($this->ids[$this->p] === 40)) {
					if (!$this->b252()) {
						goto n1139;}
					n1139:
				} elseif ($this->p > $this->f) {
					$this->rec(252);
				}
				goto k1136;
				f1136:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1135;
				k1136:
				goto a1134;
				n1135:
				break;
			case 42:
				if (!$this->b234()) {
					goto n1140;}
				goto a1134;
				n1140:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(233);
		}

		goto f1133;
		a1134:
		goto k1133;
		f1133:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1133:

		return true;
	}

	private function b234(): bool
	{
		if (!($this->ids[$this->p] === 42)) {
			if ($this->p > $this->f) {
				$this->rec(234);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 42)) {
			if ($this->p >= $this->f) {
				$this->rec(65);
			}

			goto f1467;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1469;
			}

			$this->p++;
		}
		e1469:
		if (!$this->b235()) {
			goto f1467;}
		if (($this->ids[$this->p] === 40)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k1467;
		f1467:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1467:

		return true;
	}

	private function b235(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1650;}
				goto a1649;
				n1650:
				break;
			case 34:
			case 86:
				// b236
				if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(236);
					}

					goto n1651;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b237()) {
					goto f1652;}
				// b203
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1654;
					}

					$this->p++;
				}
				e1654:
				goto k1652;
				f1652:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1651;
				k1652:
				goto a1649;
				n1651:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1655;}
				goto a1649;
				n1655:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(235);
		}

		goto f1648;
		a1649:
		goto k1648;
		f1648:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1648:

		return true;
	}

	private function b237(): bool
	{
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(237);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(238);
			}

			goto f1736;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1738;
			}

			$this->p++;
		}
		e1738:
		if (!$this->b199()) {
			goto f1736;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1740;
			}

			$this->p++;
		}
		e1740:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->b240()) {
				goto n1741;}
			n1741:
		} elseif ($this->p > $this->f) {
			$this->rec(240);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1736;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1736;
		f1736:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1736:
		$this->v[$m1] = $this->reduceArrayShapeUnsealedType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b240(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(240);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p >= $this->f) {
				$this->rec(63);
			}

			goto f1757;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1759;
			}

			$this->p++;
		}
		e1759:
		if (!$this->b199()) {
			goto f1757;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1761;
			}

			$this->p++;
		}
		e1761:
		goto k1757;
		f1757:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1757:

		return true;
	}

	private function b244(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(244);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 20:
			case 21:
			case 22:
			case 24:
				// b245
				if (!isset(self::Tce797685[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(245);
					}

					goto n1430;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b246()) {
					goto f1431;}
				if (($this->ids[$this->p] === 30)) {
					$this->v[$this->n++] = $this->token($this->p++);
				} elseif ($this->p >= $this->f) {
					$this->rec(51);
				}
				if (!($this->ids[$this->p] === 47)) {
					if ($this->p >= $this->f) {
						$this->rec(70);
					}

					goto f1431;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f1431;}
				goto k1431;
				f1431:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1430;
				k1431:
				goto a1429;
				n1430:
				if (!$this->b199()) {
					goto n1432;}
				goto a1429;
				n1432:
				break;
			case 19:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->b199()) {
					goto n1433;}
				goto a1429;
				n1433:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(244);
		}

		goto f1428;
		a1429:
		goto k1428;
		f1428:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1428:
		$this->v[$m1] = $this->reduceArrayShapeItem(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b246(): bool
	{
		if (!isset(self::Tce797685[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(246);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				if (!$this->b247()) {
					goto n1633;}
				goto a1632;
				n1633:
				break;
			case 20:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1632;
				break;
			case 21:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1632;
				break;
			case 22:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1632;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(246);
		}

		goto f1631;
		a1632:
		goto k1631;
		f1631:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1631:
		$this->v[$m1] = $this->reduceArrayShapeKey(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b247(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(247);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1718;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			// b249
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->rec(249);
				}

				goto n1719;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p >= $this->f) {
					$this->rec(66);
				}

				goto f1720;}

			$this->v[$this->n++] = $this->token($this->p++);
			if (!isset(self::T83e30518[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(144);
				}

				goto f1720;}

			$this->v[$this->n++] = $this->token($this->p++);
			goto k1720;
			f1720:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1719;
			k1720:
			n1719:
		} elseif ($this->p > $this->f) {
			$this->rec(249);
		}
		goto k1718;
		f1718:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1718:

		return true;
	}

	private function b252(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(252);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p >= $this->f) {
				$this->rec(63);
			}

			goto f1463;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 41)) {
			$this->v[$this->n++] = $this->reduceDiscardedComment($this->indexes[$this->p], $this->indexes[$this->p]);
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(254);
		}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1465;
			}

			$this->p++;
		}
		e1465:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->b233()) {
				goto n1466;}
			n1466:
		} elseif ($this->p > $this->f) {
			$this->rec(233);
		}
		goto k1463;
		f1463:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1463:

		return true;
	}

	private function b255(): bool
	{
		if (!isset(self::Ta8d79561[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(255);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Ta8d79561[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(256);
			}

			goto f613;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f613;}
		}
		if (!$this->b257()) {
			goto f613;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n614;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e616;
				}

				if (!$this->b224()) {
					goto e616;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e616;
				}

				$k2++;
			}
			e616:

			if ($k2 < 1) {
				goto f615;
			}
			goto k615;
			f615:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n614;
			k615:
			n614:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k613;
		f613:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k613:

		return true;
	}

	private function b257(): bool
	{
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p > $this->f) {
				$this->rec(257);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p >= $this->f) {
				$this->rec(60);
			}

			goto f846;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e848;
			}

			$this->p++;
		}
		e848:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->b259()) {
				goto n849;}
			n849:
		} elseif ($this->p > $this->f) {
			$this->rec(259);
		}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e851;
			}

			$this->p++;
		}
		e851:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f846;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k846;
		f846:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k846:
		$this->v[$m1] = $this->reduceListShape(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b259(): bool
	{
		if (!isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(259);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				// b264
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(264);
					}

					goto n1099;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b244()) {
					goto f1100;}
				// b203
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1102;
					}

					$this->p++;
				}
				e1102:
				if (($this->ids[$this->p] === 40)) {
					if (!$this->b266()) {
						goto n1103;}
					n1103:
				} elseif ($this->p > $this->f) {
					$this->rec(266);
				}
				goto k1100;
				f1100:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1099;
				k1100:
				goto a1098;
				n1099:
				break;
			case 42:
				if (!$this->b260()) {
					goto n1104;}
				goto a1098;
				n1104:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(259);
		}

		goto f1097;
		a1098:
		goto k1097;
		f1097:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1097:

		return true;
	}

	private function b260(): bool
	{
		if (!($this->ids[$this->p] === 42)) {
			if ($this->p > $this->f) {
				$this->rec(260);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 42)) {
			if ($this->p >= $this->f) {
				$this->rec(65);
			}

			goto f1438;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1440;
			}

			$this->p++;
		}
		e1440:
		if (!$this->b261()) {
			goto f1438;}
		if (($this->ids[$this->p] === 40)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k1438;
		f1438:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1438:

		return true;
	}

	private function b261(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1636;}
				goto a1635;
				n1636:
				break;
			case 34:
			case 86:
				// b262
				if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(262);
					}

					goto n1637;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b263()) {
					goto f1638;}
				// b203
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1640;
					}

					$this->p++;
				}
				e1640:
				goto k1638;
				f1638:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1637;
				k1638:
				goto a1635;
				n1637:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1641;}
				goto a1635;
				n1641:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(261);
		}

		goto f1634;
		a1635:
		goto k1634;
		f1634:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1634:

		return true;
	}

	private function b263(): bool
	{
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(263);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(238);
			}

			goto f1721;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1723;
			}

			$this->p++;
		}
		e1723:
		if (!$this->b199()) {
			goto f1721;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1725;
			}

			$this->p++;
		}
		e1725:
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1721;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1721;
		f1721:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1721:
		$this->v[$m1] = $this->reduceListShapeUnsealedType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b266(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(266);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p >= $this->f) {
				$this->rec(63);
			}

			goto f1434;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 41)) {
			$this->v[$this->n++] = $this->reduceDiscardedComment($this->indexes[$this->p], $this->indexes[$this->p]);
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(254);
		}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1436;
			}

			$this->p++;
		}
		e1436:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->b259()) {
				goto n1437;}
			n1437:
		} elseif ($this->p > $this->f) {
			$this->rec(259);
		}
		goto k1434;
		f1434:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1434:

		return true;
	}

	private function b267(): bool
	{
		if (!($this->ids[$this->p] === 6)) {
			if ($this->p > $this->f) {
				$this->rec(267);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 6)) {
			if ($this->p >= $this->f) {
				$this->rec(37);
			}

			goto f625;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f625;}
		}
		if (!$this->b268()) {
			goto f625;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n626;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e628;
				}

				if (!$this->b224()) {
					goto e628;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e628;
				}

				$k2++;
			}
			e628:

			if ($k2 < 1) {
				goto f627;
			}
			goto k627;
			f627:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n626;
			k627:
			n626:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k625;
		f625:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k625:

		return true;
	}

	private function b268(): bool
	{
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p > $this->f) {
				$this->rec(268);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p >= $this->f) {
				$this->rec(60);
			}

			goto f859;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e861;
			}

			$this->p++;
		}
		e861:
		if (isset(self::Td8017923[$this->ids[$this->p]])) {
			// b270
			if (!isset(self::Td8017923[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(270);
				}

				goto n862;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b271()) {
				goto f863;}
			// b203
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e865;
				}

				$this->p++;
			}
			e865:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b274()) {
					goto n866;}
				n866:
			} elseif ($this->p > $this->f) {
				$this->rec(274);
			}
			goto k863;
			f863:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n862;
			k863:
			n862:
		} elseif ($this->p > $this->f) {
			$this->rec(270);
		}
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f859;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k859;
		f859:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k859:
		$this->v[$m1] = $this->reduceObjectShape(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b271(): bool
	{
		if (!isset(self::Td8017923[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(271);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!$this->b272()) {
			goto f1127;}
		if (($this->ids[$this->p] === 30)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(51);
		}
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1127;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b199()) {
			goto f1127;}
		goto k1127;
		f1127:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1127:
		$this->v[$m1] = $this->reduceObjectShapeItem(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b272(): bool
	{
		if (!isset(self::Td8017923[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(272);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 2:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 3:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 4:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 5:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 6:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 7:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 8:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 9:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 10:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 11:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 12:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 13:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 14:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 15:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 16:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 17:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 18:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 21:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 22:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
			case 24:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1455;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(272);
		}

		goto f1454;
		a1455:
		goto k1454;
		f1454:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1454:
		$this->v[$m1] = $this->reduceObjectShapeKey(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b274(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(274);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(275);
				}

				goto e1129;
			}

			// b275
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(275);
				}

				goto e1129;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1130;}

			$this->v[$this->n++] = $this->token($this->p++);
			// b203
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1132;
				}

				$this->p++;
			}
			e1132:
			if (!$this->b276()) {
				goto f1130;}
			goto k1130;
			f1130:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1129;
			k1130:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1129;
			}

			$k1++;
		}
		e1129:

		if ($k1 < 1) {
			goto f1128;
		}
		goto k1128;
		f1128:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1128:

		return true;
	}

	private function b276(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 21:
			case 22:
			case 24:
				// b277
				if (!isset(self::Td8017923[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(277);
					}

					goto n1458;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b271()) {
					goto f1459;}
				// b203
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1461;
					}

					$this->p++;
				}
				e1461:
				goto k1459;
				f1459:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1458;
				k1459:
				goto a1457;
				n1458:
				break;
			case 39:
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p > $this->f) {
						$this->rec(62);
					}

					goto n1462;}
				goto a1457;
				n1462:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(276);
		}

		goto f1456;
		a1457:
		goto k1456;
		f1456:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1456:

		return true;
	}

	private function b281(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n598;}
				goto a597;
				n598:
				break;
			case 32:
			case 82:
				if (!$this->b364()) {
					goto n599;}
				goto a597;
				n599:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n600;}
				goto a597;
				n600:
				break;
			case 36:
			case 84:
				// b223
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(223);
					}

					goto n601;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$k2 = 0;
				while (true) {
					$b2 = $this->p;
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($b2 > $this->f) {
							$this->rec(224);
						}

						goto e603;
					}

					if (!$this->b224()) {
						goto e603;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e603;
					}

					$k2++;
				}
				e603:

				if ($k2 < 1) {
					goto f602;
				}
				goto k602;
				f602:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n601;
				k602:
				goto a597;
				n601:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n604;}
				goto a597;
				n604:
				break;
			case 34:
				if (!$this->b282()) {
					goto n605;}
				goto a597;
				n605:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n606;}
				goto a597;
				n606:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(281);
		}

		goto f596;
		a597:
		goto k596;
		f596:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k596:

		return true;
	}

	private function b282(): bool
	{
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(282);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(54);
			}

			goto f837;}
		// b284
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(284);
			}

			goto f837;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 34:
				if (!$this->b285()) {
					goto n840;}
				goto a839;
				n840:
				if (!$this->b362()) {
					goto n841;}
				goto a839;
				n841:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(284);
		}

		goto f838;
		a839:
		goto k838;
		f838:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f837;
		k838:
		goto k837;
		f837:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k837:

		return true;
	}

	private function b285(): bool
	{
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(285);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p >= $this->f) {
				$this->rec(54);
			}

			goto f1080;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1082;
			}

			$this->p++;
		}
		e1082:
		if (!$this->b286()) {
			goto f1080;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1084;
			}

			$this->p++;
		}
		e1084:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->b300()) {
				goto n1085;}
			n1085:
		} elseif ($this->p > $this->f) {
			$this->rec(300);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1080;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f1080;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1087;
			}

			$this->p++;
		}
		e1087:
		if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
			// b306
			if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(306);
				}

				goto n1088;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b307()) {
				goto f1089;}
			// b203
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1091;
				}

				$this->p++;
			}
			e1091:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b311()) {
					goto n1092;}
				n1092:
			} elseif ($this->p > $this->f) {
				$this->rec(311);
			}
			goto k1089;
			f1089:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1088;
			k1089:
			n1088:
		} elseif ($this->p > $this->f) {
			$this->rec(306);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1080;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1080;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b315()) {
			goto f1080;}
		goto k1080;
		f1080:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1080:
		$this->v[$m1] = $this->reduceCallableWithTemplates(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b286(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1375;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f1375;}
		if (!$this->b293()) {
			goto f1375;}
		if (!$this->b296()) {
			goto f1375;}
		goto k1375;
		f1375:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1375:
		$this->v[$m1] = $this->reduceCallableTemplateArgument(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b287(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				// b290
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (($this->ids[$this->p] === 13)) {
					if (($this->ids[$this->p] === 13)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f504;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f504;}
				}
				goto k504;
				f504:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n503;
				k504:
				goto a502;
				n503:
				break;
			case 13:
				// b288
				if (!($this->ids[$this->p] === 13)) {
					if ($this->p > $this->f) {
						$this->rec(288);
					}

					goto n505;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 13)) {
					if ($this->p >= $this->f) {
						$this->rec(27);
					}

					goto f506;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f506;}
				goto k506;
				f506:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n505;
				k506:
				goto a502;
				n505:
				// b290
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (($this->ids[$this->p] === 13)) {
					if (($this->ids[$this->p] === 13)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f508;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f508;}
				}
				goto k508;
				f508:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n507;
				k508:
				goto a502;
				n507:
				break;
			case 14:
				// b289
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->rec(289);
					}

					goto n509;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p >= $this->f) {
						$this->rec(28);
					}

					goto f510;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f510;}
				goto k510;
				f510:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n509;
				k510:
				goto a502;
				n509:
				// b290
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (($this->ids[$this->p] === 13)) {
					if (($this->ids[$this->p] === 13)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f512;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f512;}
				}
				goto k512;
				f512:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n511;
				k512:
				goto a502;
				n511:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(287);
		}

		goto f501;
		a502:
		goto k501;
		f501:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k501:

		return true;
	}

	private function b293(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n515;}
				goto a514;
				n515:
				break;
			case 5:
				// b294
				if (!($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->rec(294);
					}

					goto n516;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 5)) {
					if ($this->p >= $this->f) {
						$this->rec(29);
					}

					goto f517;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f517;}
				goto k517;
				f517:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n516;
				k517:
				goto a514;
				n516:
				if (($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n518;}
				goto a514;
				n518:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(293);
		}

		goto f513;
		a514:
		goto k513;
		f513:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k513:

		return true;
	}

	private function b296(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n521;}
				goto a520;
				n521:
				break;
			case 46:
				// b297
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->rec(297);
					}

					goto n522;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p >= $this->f) {
						$this->rec(69);
					}

					goto f523;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f523;}
				goto k523;
				f523:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n522;
				k523:
				goto a520;
				n522:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n524;}
				goto a520;
				n524:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(296);
		}

		goto f519;
		a520:
		goto k519;
		f519:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k519:

		return true;
	}

	private function b300(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(300);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(301);
				}

				goto e1418;
			}

			// b301
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(301);
				}

				goto e1418;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1419;}

			$this->v[$this->n++] = $this->token($this->p++);
			// b203
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1421;
				}

				$this->p++;
			}
			e1421:
			if (!$this->b302()) {
				goto f1419;}
			goto k1419;
			f1419:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1418;
			k1419:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1418;
			}

			$k1++;
		}
		e1418:

		if ($k1 < 1) {
			goto f1417;
		}
		goto k1417;
		f1417:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1417:

		return true;
	}

	private function b302(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				// b304
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(304);
					}

					goto n1626;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b286()) {
					goto f1627;}
				// b203
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1629;
					}

					$this->p++;
				}
				e1629:
				goto k1627;
				f1627:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1626;
				k1627:
				goto a1625;
				n1626:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n1630;}
				goto a1625;
				n1630:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(302);
		}

		goto f1624;
		a1625:
		goto k1624;
		f1624:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1624:

		return true;
	}

	private function b307(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(307);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!$this->b199()) {
			goto f1045;}
		if (($this->ids[$this->p] === 27)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(48);
		}
		if (($this->ids[$this->p] === 42)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(65);
		}
		if (($this->ids[$this->p] === 26)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(47);
		}
		if (($this->ids[$this->p] === 46)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(69);
		}
		goto k1045;
		f1045:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1045:
		$this->v[$m1] = $this->reduceCallableParameter(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b311(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(311);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(312);
				}

				goto e1047;
			}

			// b312
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(312);
				}

				goto e1047;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1048;}

			$this->v[$this->n++] = $this->token($this->p++);
			// b203
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1050;
				}

				$this->p++;
			}
			e1050:
			if (!$this->b313()) {
				goto f1048;}
			goto k1048;
			f1048:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1047;
			k1048:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1047;
			}

			$k1++;
		}
		e1047:

		if ($k1 < 1) {
			goto f1046;
		}
		goto k1046;
		f1046:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1046:

		return true;
	}

	private function b313(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				// b314
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(314);
					}

					goto n1398;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b307()) {
					goto f1399;}
				// b203
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1401;
					}

					$this->p++;
				}
				e1401:
				goto k1399;
				f1399:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1398;
				k1399:
				goto a1397;
				n1398:
				break;
			case 33:
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p > $this->f) {
						$this->rec(53);
					}

					goto n1402;}
				goto a1397;
				n1402:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(313);
		}

		goto f1396;
		a1397:
		goto k1396;
		f1396:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1396:

		return true;
	}

	private function b315(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(315);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 5:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				// b331
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(331);
					}

					goto n1053;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(332);
					}

					goto f1054;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1054;}
				}
				if (!$this->b318()) {
					goto f1054;}
				goto k1054;
				f1054:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1053;
				k1054:
				goto a1052;
				n1053:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1055;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1056;}
				}
				if (!$this->b335()) {
					goto f1056;}
				goto k1056;
				f1056:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1055;
				k1056:
				goto a1052;
				n1055:
				break;
			case 19:
			case 20:
			case 21:
			case 22:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1058;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1059;}
				}
				if (!$this->b335()) {
					goto f1059;}
				goto k1059;
				f1059:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1058;
				k1059:
				goto a1052;
				n1058:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->b255()) {
					goto n1061;}
				goto a1052;
				n1061:
				if (!$this->b317()) {
					goto n1062;}
				goto a1052;
				n1062:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1063;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1064;}
				}
				if (!$this->b335()) {
					goto f1064;}
				goto k1064;
				f1064:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1063;
				k1064:
				goto a1052;
				n1063:
				break;
			case 32:
			case 82:
				if (!$this->b316()) {
					goto n1066;}
				goto a1052;
				n1066:
				break;
			case 6:
				if (!$this->b267()) {
					goto n1067;}
				goto a1052;
				n1067:
				if (!$this->b317()) {
					goto n1068;}
				goto a1052;
				n1068:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1069;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1070;}
				}
				if (!$this->b335()) {
					goto f1070;}
				goto k1070;
				f1070:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1069;
				k1070:
				goto a1052;
				n1069:
				break;
			case 7:
				if (!$this->b230()) {
					goto n1072;}
				goto a1052;
				n1072:
				if (!$this->b317()) {
					goto n1073;}
				goto a1052;
				n1073:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1074;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1075;}
				}
				if (!$this->b335()) {
					goto f1075;}
				goto k1075;
				f1075:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1074;
				k1075:
				goto a1052;
				n1074:
				break;
			case 25:
				if (!$this->b229()) {
					goto n1077;}
				goto a1052;
				n1077:
				break;
			case 30:
				// b200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n1078;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f1079;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b201()) {
					goto f1079;}
				goto k1079;
				f1079:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1078;
				k1079:
				$this->v[$m2] = $this->reduceNullable(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a1052;
				n1078:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(315);
		}

		goto f1051;
		a1052:
		goto k1051;
		f1051:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1051:
		$this->v[$m1] = $this->reduceCallableReturnType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b316(): bool
	{
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(316);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f1413;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b205()) {
			goto f1413;}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1413;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1414;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e1416;
				}

				if (!$this->b224()) {
					goto e1416;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1416;
				}

				$k2++;
			}
			e1416:

			if ($k2 < 1) {
				goto f1415;
			}
			goto k1415;
			f1415:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1414;
			k1415:
			n1414:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1413;
		f1413:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1413:

		return true;
	}

	private function b317(): bool
	{
		if (!isset(self::T4020d016[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(317);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T4020d016[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(279);
			}

			goto f1412;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f1412;}
		}
		if (($this->ids[$this->p] === 38)) {
			if (($this->ids[$this->p] === 38)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f1412;}
		}
		if (!$this->b318()) {
			goto f1412;}
		goto k1412;
		f1412:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1412:

		return true;
	}

	private function b318(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1405;}
				goto a1404;
				n1405:
				break;
			case 34:
			case 86:
				if (!$this->b319()) {
					goto n1406;}
				goto a1404;
				n1406:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1407;}
				goto a1404;
				n1407:
				break;
			case 36:
			case 84:
				// b223
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(223);
					}

					goto n1408;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$k2 = 0;
				while (true) {
					$b2 = $this->p;
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($b2 > $this->f) {
							$this->rec(224);
						}

						goto e1410;
					}

					if (!$this->b224()) {
						goto e1410;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e1410;
					}

					$k2++;
				}
				e1410:

				if ($k2 < 1) {
					goto f1409;
				}
				goto k1409;
				f1409:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1408;
				k1409:
				goto a1404;
				n1408:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1411;}
				goto a1404;
				n1411:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(318);
		}

		goto f1403;
		a1404:
		goto k1403;
		f1403:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1403:

		return true;
	}

	private function b319(): bool
	{
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(319);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!$this->b320()) {
			goto f1620;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1621;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e1623;
				}

				if (!$this->b224()) {
					goto e1623;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1623;
				}

				$k2++;
			}
			e1623:

			if ($k2 < 1) {
				goto f1622;
			}
			goto k1622;
			f1622:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1621;
			k1622:
			n1621:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1620;
		f1620:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1620:

		return true;
	}

	private function b320(): bool
	{
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(320);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(238);
			}

			goto f525;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e527;
			}

			$this->p++;
		}
		e527:
		if (!$this->b321()) {
			goto f525;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e529;
			}

			$this->p++;
		}
		e529:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->b327()) {
				goto n530;}
			n530:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f525;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k525;
		f525:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k525:
		$this->v[$m1] = $this->reduceGenericInReturnType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b321(): bool
	{
		if (!isset(self::Tb3cd2a63[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(321);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				// b324
				if (!isset(self::Tfc0ac6dd[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(324);
					}

					goto n779;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T05cf281c[$this->ids[$this->p]])) {
					if (isset(self::T05cf281c[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f780;}
				}
				if (!$this->b199()) {
					goto f780;}
				goto k780;
				f780:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n779;
				k780:
				goto a778;
				n779:
				break;
			case 1:
			case 2:
				// b322
				if (!isset(self::T05cf281c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(322);
					}

					goto n781;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T05cf281c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(323);
					}

					goto f782;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f782;}
				goto k782;
				f782:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n781;
				k782:
				goto a778;
				n781:
				break;
			case 50:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a778;
				break;
			case 85:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a778;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(321);
		}

		goto f777;
		a778:
		goto k777;
		f777:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k777:
		$this->v[$m1] = $this->reduceGenericArgument(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b327(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(327);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(328);
				}

				goto e784;
			}

			// b328
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(328);
				}

				goto e784;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f785;}

			$this->v[$this->n++] = $this->token($this->p++);
			// b203
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e787;
				}

				$this->p++;
			}
			e787:
			if (!$this->b329()) {
				goto f785;}
			goto k785;
			f785:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e784;
			k785:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e784;
			}

			$k1++;
		}
		e784:

		if ($k1 < 1) {
			goto f783;
		}
		goto k783;
		f783:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k783:

		return true;
	}

	private function b329(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 50:
			case 82:
			case 85:
				// b330
				if (!isset(self::Tb3cd2a63[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(330);
					}

					goto n1015;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b321()) {
					goto f1016;}
				// b203
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1018;
					}

					$this->p++;
				}
				e1018:
				goto k1016;
				f1016:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1015;
				k1016:
				goto a1014;
				n1015:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n1019;}
				goto a1014;
				n1019:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(329);
		}

		goto f1013;
		a1014:
		goto k1013;
		f1013:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1013:

		return true;
	}

	private function b335(): bool
	{
		if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(335);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (isset(self::T1e81050f[$this->ids[$this->p]])) {
			$sf = $this->f;
			$se = $this->er;
			$sp = $this->p;
			$matched = $this->s337();
			$this->f = $sf;
			$this->er = $se;
			$this->p = $sp;
			if ($matched) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f608;}
		}
		if (!$this->b340()) {
			goto f608;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n610;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e612;
				}

				if (!$this->b224()) {
					goto e612;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e612;
				}

				$k2++;
			}
			e612:

			if ($k2 < 1) {
				goto f611;
			}
			goto k611;
			f611:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n610;
			k611:
			n610:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k608;
		f608:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k608:

		return true;
	}

	private function b340(): bool
	{
		if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(340);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 24:
				if (!$this->b357()) {
					goto n172;}
				goto a171;
				n172:
				break;
			case 7:
			case 18:
				if (!$this->b347()) {
					goto n173;}
				goto a171;
				n173:
				break;
			case 21:
			case 22:
				if (!$this->b343()) {
					goto n174;}
				goto a171;
				n174:
				break;
			case 36:
			case 84:
				if (!$this->b361()) {
					goto n175;}
				goto a171;
				n175:
				break;
			case 15:
				$this->v[$this->n++] = $this->reduceConstantFalse($this->indexes[$this->p], $this->indexes[$this->p]);
				$this->p++;
				goto a171;
				break;
			case 16:
				$this->v[$this->n++] = $this->reduceConstantTrue($this->indexes[$this->p], $this->indexes[$this->p]);
				$this->p++;
				goto a171;
				break;
			case 17:
				$this->v[$this->n++] = $this->reduceConstantNull($this->indexes[$this->p], $this->indexes[$this->p]);
				$this->p++;
				goto a171;
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceConstantFloat($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a171;
				break;
			case 20:
				$this->v[$this->n++] = $this->reduceConstantInteger($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a171;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(340);
		}

		goto f170;
		a171:
		goto k170;
		f170:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k170:

		return true;
	}

	private function b343(): bool
	{
		if (!isset(self::T24d0f25b[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(343);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 21:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a426;
				break;
			case 22:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a426;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(343);
		}

		goto f425;
		a426:
		goto k425;
		f425:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k425:
		$this->v[$m1] = $this->reduceConstantString(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b347(): bool
	{
		if (!isset(self::T1ad6c327[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(347);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T1ad6c327[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(339);
			}

			goto f423;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f423;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if (!$this->b349()) {
				goto n424;}
			n424:
		} elseif ($this->p > $this->f) {
			$this->rec(349);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f423;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k423;
		f423:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k423:
		$this->v[$m1] = $this->reduceConstantArrayLiteral(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b349(): bool
	{
		if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(349);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		// b350
		if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(350);
			}

			goto f699;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b340()) {
			goto f700;}
		if (!$this->b351()) {
			goto f700;}
		goto k700;
		f700:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f699;
		k700:
		$this->v[$m2] = $this->reduceConstantArrayItem(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
		$this->n = $m2 + 1;
		if (($this->ids[$this->p] === 40)) {
			// b355
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(355);
				}

				goto n701;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(356);
					}

					goto e703;
				}

				if (!$this->b356()) {
					goto e703;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e703;
				}

				$k2++;
			}
			e703:

			if ($k2 < 1) {
				goto f702;
			}
			goto k702;
			f702:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n701;
			k702:
			n701:
		} elseif ($this->p > $this->f) {
			$this->rec(355);
		}
		if (($this->ids[$this->p] === 40)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k699;
		f699:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k699:

		return true;
	}

	private function b351(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n951;}
				goto a950;
				n951:
				break;
			case 44:
				// b352
				if (!($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->rec(352);
					}

					goto n952;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 44)) {
					if ($this->p >= $this->f) {
						$this->rec(67);
					}

					goto f953;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b340()) {
					goto f953;}
				goto k953;
				f953:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n952;
				k953:
				goto a950;
				n952:
				if (($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n954;}
				goto a950;
				n954:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(351);
		}

		goto f949;
		a950:
		goto k949;
		f949:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k949:

		return true;
	}

	private function b356(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(356);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p >= $this->f) {
				$this->rec(63);
			}

			goto f955;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b350
		if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(350);
			}

			goto f955;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b340()) {
			goto f956;}
		if (!$this->b351()) {
			goto f956;}
		goto k956;
		f956:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f955;
		k956:
		$this->v[$m2] = $this->reduceConstantArrayItem(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
		$this->n = $m2 + 1;
		goto k955;
		f955:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k955:

		return true;
	}

	private function b357(): bool
	{
		if (!isset(self::Td08a6883[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(357);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (isset(self::T1ad6c327[$this->ids[$this->p]])) {
			if (isset(self::T1ad6c327[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f420;}
		}
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f420;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			// b360
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->rec(360);
				}

				goto n421;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p >= $this->f) {
					$this->rec(66);
				}

				goto f422;}

			$this->v[$this->n++] = $this->token($this->p++);
			if (!$this->b163()) {
				goto f422;}
			goto k422;
			f422:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n421;
			k422:
			n421:
		} elseif ($this->p > $this->f) {
			$this->rec(360);
		}
		goto k420;
		f420:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k420:
		$this->v[$m1] = $this->reduceConstantFetch(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b361(): bool
	{
		if (!isset(self::T885cd349[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(361);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T885cd349[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(228);
			}

			goto f427;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if (!$this->b349()) {
				goto n428;}
			n428:
		} elseif ($this->p > $this->f) {
			$this->rec(349);
		}
		if (!($this->ids[$this->p] === 37)) {
			if ($this->p >= $this->f) {
				$this->rec(59);
			}

			goto f427;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k427;
		f427:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k427:
		$this->v[$m1] = $this->reduceConstantArrayShorthand(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b362(): bool
	{
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(362);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!$this->b363()) {
			goto f1093;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1094;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e1096;
				}

				if (!$this->b224()) {
					goto e1096;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1096;
				}

				$k2++;
			}
			e1096:

			if ($k2 < 1) {
				goto f1095;
			}
			goto k1095;
			f1095:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1094;
			k1095:
			n1094:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1093;
		f1093:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1093:

		return true;
	}

	private function b363(): bool
	{
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(363);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p >= $this->f) {
				$this->rec(54);
			}

			goto f1422;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1424;
			}

			$this->p++;
		}
		e1424:
		if (!$this->b321()) {
			goto f1422;}
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1426;
			}

			$this->p++;
		}
		e1426:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->b327()) {
				goto n1427;}
			n1427:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1422;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1422;
		f1422:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1422:
		$this->v[$m1] = $this->reduceGeneric(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b364(): bool
	{
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(364);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f821;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b203
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e823;
			}

			$this->p++;
		}
		e823:
		if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
			// b306
			if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(306);
				}

				goto n824;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b307()) {
				goto f825;}
			// b203
			$o3 = $this->p;
			$m3 = $this->n;
			$cs3 = $this->comments;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e827;
				}

				$this->p++;
			}
			e827:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b311()) {
					goto n828;}
				n828:
			} elseif ($this->p > $this->f) {
				$this->rec(311);
			}
			goto k825;
			f825:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n824;
			k825:
			n824:
		} elseif ($this->p > $this->f) {
			$this->rec(306);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f821;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f821;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b315()) {
			goto f821;}
		goto k821;
		f821:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k821:
		$this->v[$m1] = $this->reduceCallable(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b368(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				// b383
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f373;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f373;}
				}
				goto k373;
				f373:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n372;
				k373:
				goto a371;
				n372:
				break;
			case 28:
			case 29:
			case 41:
			case 80:
				// b369
				if (!isset(self::T8458d590[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(369);
					}

					goto n374;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				// b203
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e377;
					}

					$this->p++;
				}
				e377:
				if (!$this->b370()) {
					goto f375;}
				goto k375;
				f375:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n374;
				k375:
				goto a371;
				n374:
				// b383
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f379;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f379;}
				}
				goto k379;
				f379:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n378;
				k379:
				goto a371;
				n378:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(368);
		}

		goto f370;
		a371:
		goto k370;
		f370:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k370:

		return true;
	}

	private function b370(): bool
	{
		if (!isset(self::Tc8c984b6[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(370);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 28:
				if (!$this->b371()) {
					goto n639;}
				goto a638;
				n639:
				break;
			case 29:
				if (!$this->b377()) {
					goto n640;}
				goto a638;
				n640:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(370);
		}

		goto f637;
		a638:
		goto k637;
		f637:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k637:

		return true;
	}

	private function b371(): bool
	{
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(371);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		// b372
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(372);
			}

			goto f873;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		$k2 = 0;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 28)) {
				if ($b2 > $this->f) {
					$this->rec(373);
				}

				goto e875;
			}

			if (!$this->b373()) {
				goto e875;}
			if ($this->p === $b2) {
				$k2 = 1;
				goto e875;
			}

			$k2++;
		}
		e875:

		if ($k2 < 1) {
			goto f874;
		}
		goto k874;
		f874:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f873;
		k874:
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f873;}
		}
		goto k873;
		f873:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k873:

		return true;
	}

	private function b373(): bool
	{
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(373);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p >= $this->f) {
				$this->rec(49);
			}

			goto f1141;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b201()) {
			goto f1141;}
		// b375
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		// b203
		$o3 = $this->p;
		$m3 = $this->n;
		$cs3 = $this->comments;
		while (true) {
			$b3 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b3 > $this->f) {
					$this->rec(204);
				}

				goto e1145;
			}

			$this->p++;
		}
		e1145:
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(49);
			}

			goto f1143;}
		goto k1143;
		f1143:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto n1142;
		k1143:
		n1142:
		goto k1141;
		f1141:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1141:

		return true;
	}

	private function b377(): bool
	{
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(377);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		// b378
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(378);
			}

			goto f876;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		$k2 = 0;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 29)) {
				if ($b2 > $this->f) {
					$this->rec(379);
				}

				goto e878;
			}

			if (!$this->b379()) {
				goto e878;}
			if ($this->p === $b2) {
				$k2 = 1;
				goto e878;
			}

			$k2++;
		}
		e878:

		if ($k2 < 1) {
			goto f877;
		}
		goto k877;
		f877:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f876;
		k877:
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f876;}
		}
		goto k876;
		f876:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k876:

		return true;
	}

	private function b379(): bool
	{
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(379);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p >= $this->f) {
				$this->rec(50);
			}

			goto f1146;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b201()) {
			goto f1146;}
		// b381
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		// b203
		$o3 = $this->p;
		$m3 = $this->n;
		$cs3 = $this->comments;
		while (true) {
			$b3 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b3 > $this->f) {
					$this->rec(204);
				}

				goto e1150;
			}

			$this->p++;
		}
		e1150:
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(50);
			}

			goto f1148;}
		goto k1148;
		f1148:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto n1147;
		k1148:
		n1147:
		goto k1146;
		f1146:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1146:

		return true;
	}

	private function b384(): bool
	{
		if (!($this->ids[$this->p] === 75)) {
			if ($this->p > $this->f) {
				$this->rec(384);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 75)) {
			if ($this->p >= $this->f) {
				$this->rec(90);
			}

			goto f298;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b199()) {
			goto f298;}
		if (!$this->b385()) {
			goto f298;}
		goto k298;
		f298:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k298:

		return true;
	}

	private function b385(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (!$this->b388()) {
					goto n548;}
				goto a547;
				n548:
				break;
			case 25:
			case 26:
				// b386
				if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(386);
					}

					goto n549;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(387);
					}

					goto f550;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f550;}
				$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
				goto k550;
				f550:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n549;
				k550:
				goto a547;
				n549:
				if (!$this->b388()) {
					goto n552;}
				goto a547;
				n552:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(385);
		}

		goto f546;
		a547:
		goto k546;
		f546:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k546:

		return true;
	}

	private function b388(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f267;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f267;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f267;}
		}
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f267;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k267;
		f267:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k267:

		return true;
	}

	private function b398(): bool
	{
		if (!($this->ids[$this->p] === 63)) {
			if ($this->p > $this->f) {
				$this->rec(398);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 63)) {
			if ($this->p >= $this->f) {
				$this->rec(98);
			}

			goto f273;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b399
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(399);
			}

			goto f273;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b199()) {
			goto f274;}
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f274;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f274;}
		$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
		goto k274;
		f274:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f273;
		k274:
		goto k273;
		f273:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k273:

		return true;
	}

	private function b400(): bool
	{
		if (!($this->ids[$this->p] === 67)) {
			if ($this->p > $this->f) {
				$this->rec(400);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 67)) {
			if ($this->p >= $this->f) {
				$this->rec(108);
			}

			goto f281;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b399
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(399);
			}

			goto f281;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b199()) {
			goto f282;}
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f282;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f282;}
		$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
		goto k282;
		f282:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f281;
		k282:
		goto k281;
		f281:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k281:

		return true;
	}

	private function b401(): bool
	{
		if (!($this->ids[$this->p] === 55)) {
			if ($this->p > $this->f) {
				$this->rec(401);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 55)) {
			if ($this->p >= $this->f) {
				$this->rec(87);
			}

			goto f264;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b399
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(399);
			}

			goto f264;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b199()) {
			goto f265;}
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f265;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f265;}
		$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
		goto k265;
		f265:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f264;
		k265:
		goto k264;
		f264:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k264:

		return true;
	}

	private function b402(): bool
	{
		if (!($this->ids[$this->p] === 51)) {
			if ($this->p > $this->f) {
				$this->rec(402);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 51)) {
			if ($this->p >= $this->f) {
				$this->rec(85);
			}

			goto f252;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b403
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p > $this->f) {
				$this->rec(403);
			}

			goto f252;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f253;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f253;}
		$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
		goto k253;
		f253:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f252;
		k253:
		goto k252;
		f252:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k252:

		return true;
	}

	private function b404(): bool
	{
		if (!($this->ids[$this->p] === 54)) {
			if ($this->p > $this->f) {
				$this->rec(404);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 54)) {
			if ($this->p >= $this->f) {
				$this->rec(86);
			}

			goto f261;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b403
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p > $this->f) {
				$this->rec(403);
			}

			goto f261;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f262;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f262;}
		$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
		goto k262;
		f262:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f261;
		k262:
		goto k261;
		f261:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k261:

		return true;
	}

	private function b405(): bool
	{
		if (!($this->ids[$this->p] === 52)) {
			if ($this->p > $this->f) {
				$this->rec(405);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 52)) {
			if ($this->p >= $this->f) {
				$this->rec(88);
			}

			goto f255;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b403
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p > $this->f) {
				$this->rec(403);
			}

			goto f255;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f256;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f256;}
		$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
		goto k256;
		f256:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f255;
		k256:
		goto k255;
		f255:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k255:

		return true;
	}

	private function b406(): bool
	{
		if (!($this->ids[$this->p] === 53)) {
			if ($this->p > $this->f) {
				$this->rec(406);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 53)) {
			if ($this->p >= $this->f) {
				$this->rec(89);
			}

			goto f258;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b403
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p > $this->f) {
				$this->rec(403);
			}

			goto f258;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f259;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f259;}
		$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
		goto k259;
		f259:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f258;
		k259:
		goto k258;
		f258:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k258:

		return true;
	}

	private function b409(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(409);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				// b435
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(435);
					}

					goto n288;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b199()) {
					goto f289;}
				if (!$this->b415()) {
					goto f289;}
				goto k289;
				f289:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n288;
				k289:
				goto a287;
				n288:
				break;
			case 9:
				// b410
				if (!($this->ids[$this->p] === 9)) {
					if ($this->p > $this->f) {
						$this->rec(410);
					}

					goto n290;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 9)) {
					if ($this->p >= $this->f) {
						$this->rec(25);
					}

					goto f291;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::T648acf56[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s412();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f291;}
				}
				if (!$this->b199()) {
					goto f291;}
				if (!$this->b415()) {
					goto f291;}
				goto k291;
				f291:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n290;
				k291:
				goto a287;
				n290:
				// b435
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(435);
					}

					goto n293;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b199()) {
					goto f294;}
				if (!$this->b415()) {
					goto f294;}
				goto k294;
				f294:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n293;
				k294:
				goto a287;
				n293:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(409);
		}

		goto f286;
		a287:
		goto k286;
		f286:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k286:

		return true;
	}

	private function b415(): bool
	{
		if (!isset(self::T02ca7bff[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(415);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				// b416
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(416);
					}

					goto n533;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f534;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b417()) {
					goto f534;}
				goto k534;
				f534:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n533;
				k534:
				goto a532;
				n533:
				break;
			case 32:
			case 34:
			case 82:
			case 86:
				if (!$this->b417()) {
					goto n535;}
				goto a532;
				n535:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(415);
		}

		goto f531;
		a532:
		goto k531;
		f531:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k531:

		return true;
	}

	private function b417(): bool
	{
		if (!isset(self::Tf86de613[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(417);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!$this->b418()) {
			goto f788;}
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f788;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T9373805c[$this->ids[$this->p]])) {
			// b424
			if (!isset(self::T9373805c[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(424);
				}

				goto n789;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b425()) {
				goto f790;}
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b433()) {
					goto n791;}
				n791:
			} elseif ($this->p > $this->f) {
				$this->rec(433);
			}
			goto k790;
			f790:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n789;
			k790:
			n789:
		} elseif ($this->p > $this->f) {
			$this->rec(424);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f788;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f788;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k788;
		f788:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k788:

		return true;
	}

	private function b418(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1022;}
				goto a1021;
				n1022:
				break;
			case 34:
			case 86:
				// b419
				if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(419);
					}

					goto n1023;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(238);
					}

					goto f1024;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b286()) {
					goto f1024;}
				if (($this->ids[$this->p] === 40)) {
					if (!$this->b421()) {
						goto n1025;}
					n1025:
				} elseif ($this->p > $this->f) {
					$this->rec(421);
				}
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p >= $this->f) {
						$this->rec(56);
					}

					goto f1024;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k1024;
				f1024:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1023;
				k1024:
				goto a1021;
				n1023:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1026;}
				goto a1021;
				n1026:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(418);
		}

		goto f1020;
		a1021:
		goto k1020;
		f1020:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1020:

		return true;
	}

	private function b421(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(421);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(422);
				}

				goto e1377;
			}

			// b422
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(422);
				}

				goto e1377;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1378;}

			$this->v[$this->n++] = $this->token($this->p++);
			if (!$this->b286()) {
				goto f1378;}
			goto k1378;
			f1378:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1377;
			k1378:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1377;
			}

			$k1++;
		}
		e1377:

		if ($k1 < 1) {
			goto f1376;
		}
		goto k1376;
		f1376:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1376:

		return true;
	}

	private function b425(): bool
	{
		if (!isset(self::T9373805c[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(425);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
			case 30:
			case 32:
			case 82:
				if (!$this->b426()) {
					goto n1029;}
				goto a1028;
				n1029:
				break;
			case 26:
			case 27:
			case 42:
				// b431
				if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(431);
					}

					goto n1030;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (($this->ids[$this->p] === 27)) {
					$this->v[$this->n++] = $this->token($this->p++);
				} elseif ($this->p >= $this->f) {
					$this->rec(48);
				}
				if (($this->ids[$this->p] === 42)) {
					$this->v[$this->n++] = $this->token($this->p++);
				} elseif ($this->p >= $this->f) {
					$this->rec(65);
				}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f1031;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b429()) {
					goto f1031;}
				goto k1031;
				f1031:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1030;
				k1031:
				goto a1028;
				n1030:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(425);
		}

		goto f1027;
		a1028:
		goto k1027;
		f1027:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1027:
		$this->v[$m1] = $this->reduceMethodParameter(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b426(): bool
	{
		if (!isset(self::T34c34eee[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(426);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T34c34eee[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(428);
			}

			goto f1379;}
		if (!$this->b199()) {
			goto f1379;}
		if (($this->ids[$this->p] === 27)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(48);
		}
		if (($this->ids[$this->p] === 42)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(65);
		}
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f1379;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b429()) {
			goto f1379;}
		goto k1379;
		f1379:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1379:

		return true;
	}

	private function b429(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1382;}
				goto a1381;
				n1382:
				break;
			case 46:
				// b430
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->rec(430);
					}

					goto n1383;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p >= $this->f) {
						$this->rec(69);
					}

					goto f1384;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b340()) {
					goto f1384;}
				goto k1384;
				f1384:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1383;
				k1384:
				goto a1381;
				n1383:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1385;}
				goto a1381;
				n1385:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(429);
		}

		goto f1380;
		a1381:
		goto k1380;
		f1380:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1380:

		return true;
	}

	private function b433(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(433);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(434);
				}

				goto e1033;
			}

			// b434
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(434);
				}

				goto e1033;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1034;}

			$this->v[$this->n++] = $this->token($this->p++);
			if (!$this->b425()) {
				goto f1034;}
			goto k1034;
			f1034:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1033;
			k1034:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1033;
			}

			$k1++;
		}
		e1033:

		if ($k1 < 1) {
			goto f1032;
		}
		goto k1032;
		f1032:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1032:

		return true;
	}

	private function b436(): bool
	{
		if (!($this->ids[$this->p] === 62)) {
			if ($this->p > $this->f) {
				$this->rec(436);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 62)) {
			if ($this->p >= $this->f) {
				$this->rec(100);
			}

			goto f272;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f272;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f272;}
		if (!$this->b293()) {
			goto f272;}
		if (!$this->b296()) {
			goto f272;}
		if (!$this->b388()) {
			goto f272;}
		goto k272;
		f272:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k272:

		return true;
	}

	private function b437(): bool
	{
		if (!($this->ids[$this->p] === 68)) {
			if ($this->p > $this->f) {
				$this->rec(437);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 68)) {
			if ($this->p >= $this->f) {
				$this->rec(101);
			}

			goto f284;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b438
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(438);
			}

			goto f284;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f285;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b320()) {
			goto f285;}
		if (!$this->b388()) {
			goto f285;}
		goto k285;
		f285:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f284;
		k285:
		goto k284;
		f284:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k284:

		return true;
	}

	private function b439(): bool
	{
		if (!($this->ids[$this->p] === 64)) {
			if ($this->p > $this->f) {
				$this->rec(439);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 64)) {
			if ($this->p >= $this->f) {
				$this->rec(102);
			}

			goto f276;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b438
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(438);
			}

			goto f276;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f277;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b320()) {
			goto f277;}
		if (!$this->b388()) {
			goto f277;}
		goto k277;
		f277:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f276;
		k277:
		goto k276;
		f276:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k276:

		return true;
	}

	private function b440(): bool
	{
		if (!($this->ids[$this->p] === 74)) {
			if ($this->p > $this->f) {
				$this->rec(440);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 74)) {
			if ($this->p >= $this->f) {
				$this->rec(103);
			}

			goto f296;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b438
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(438);
			}

			goto f296;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f297;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b320()) {
			goto f297;}
		if (!$this->b388()) {
			goto f297;}
		goto k297;
		f297:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f296;
		k297:
		goto k296;
		f296:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k296:

		return true;
	}

	private function b441(): bool
	{
		if (!($this->ids[$this->p] === 66)) {
			if ($this->p > $this->f) {
				$this->rec(441);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 66)) {
			if ($this->p >= $this->f) {
				$this->rec(104);
			}

			goto f278;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f278;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 46)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(69);
		}
		if (!$this->b199()) {
			goto f278;}
		if (!isset(self::T53b29bff[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(77);
			}

			goto f278;}
		goto k278;
		f278:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k278:

		return true;
	}

	private function b442(): bool
	{
		if (!($this->ids[$this->p] === 66)) {
			if ($this->p > $this->f) {
				$this->rec(442);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 66)) {
			if ($this->p >= $this->f) {
				$this->rec(104);
			}

			goto f279;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f279;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 46)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(69);
		}
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f279;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k279;
		f279:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k279:
		$this->v[$m1] = $this->reduceInvalidTypeAliasTag(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b443(): bool
	{
		if (!($this->ids[$this->p] === 58)) {
			if ($this->p > $this->f) {
				$this->rec(443);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 58)) {
			if ($this->p >= $this->f) {
				$this->rec(105);
			}

			goto f269;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f269;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 10)) {
			if ($this->p >= $this->f) {
				$this->rec(26);
			}

			goto f269;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f269;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b444()) {
			goto f269;}
		goto k269;
		f269:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k269:

		return true;
	}

	private function b444(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n491;}
				goto a490;
				n491:
				break;
			case 14:
				// b445
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->rec(445);
					}

					goto n492;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p >= $this->f) {
						$this->rec(28);
					}

					goto f493;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f493;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k493;
				f493:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n492;
				k493:
				goto a490;
				n492:
				if (($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n494;}
				goto a490;
				n494:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(444);
		}

		goto f489;
		a490:
		goto k489;
		f489:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k489:

		return true;
	}

	private function b446(): bool
	{
		if (!($this->ids[$this->p] === 59)) {
			if ($this->p > $this->f) {
				$this->rec(446);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 59)) {
			if ($this->p >= $this->f) {
				$this->rec(106);
			}

			goto f270;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 31)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(52);
		}
		if (($this->ids[$this->p] === 46)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(69);
		}
		if (!$this->b199()) {
			goto f270;}
		if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(448);
			}

			goto f270;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b449()) {
			goto f270;}
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f270;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k270;
		f270:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k270:

		return true;
	}

	private function b449(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n497;}
				goto a496;
				n497:
				break;
			case 45:
				// b450
				if (!($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->rec(450);
					}

					goto n498;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 45)) {
					if ($this->p >= $this->f) {
						$this->rec(68);
					}

					goto f499;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f499;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b451()) {
					goto f499;}
				goto k499;
				f499:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n498;
				k499:
				goto a496;
				n498:
				if (($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n500;}
				goto a496;
				n500:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(449);
		}

		goto f495;
		a496:
		goto k495;
		f495:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k495:

		return true;
	}

	private function b451(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n773;}
				goto a772;
				n773:
				break;
			case 32:
			case 82:
				// b452
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(452);
					}

					goto n774;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(16);
					}

					goto f775;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p >= $this->f) {
						$this->rec(53);
					}

					goto f775;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k775;
				f775:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n774;
				k775:
				goto a772;
				n774:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n776;}
				goto a772;
				n776:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(451);
		}

		goto f771;
		a772:
		goto k771;
		f771:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k771:

		return true;
	}

	private function b454(): bool
	{
		if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(454);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(119);
			}

			goto f241;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f241;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k241;
		f241:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k241:
		$this->v[$m1] = $this->reduceInvalidTag(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b455(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
			if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f238;}
		}
		// b457
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f239;}
		$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
		goto k239;
		f239:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f238;
		k239:
		$this->v[$m2] = $this->reduceTextChild(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
		$this->n = $m2 + 1;
		goto k238;
		f238:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k238:

		return true;
	}

	private function s0(): bool
	{
		if (!($this->ids[$this->p] === 48)) {
			if ($this->p > $this->f) {
				$this->rec(0);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 48)) {
			if ($this->p >= $this->f) {
				$this->rec(1);
			}

			goto f3;}

		$this->p++;
		if (($this->ids[$this->p] === 80)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(3);
		}
		// s5
		$o2 = $this->p;
		if (($this->ids[$this->p] === 49)) {
			if (($this->ids[$this->p] === 49)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f5;}
		}
		if (!$this->s8()) {
			goto f5;}
		// s460
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			// s461
			$o4 = $this->p;
			if (($this->ids[$this->p] === 49)) {
				if (($this->ids[$this->p] === 49)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto f8;}
			}
			if (!$this->s8()) {
				goto f8;}
			goto k8;
			f8:
			$this->p = $o4;
			goto e7;
			k8:
			if ($this->p === $b3) {
				goto e7;
			}
		}
		e7:
		goto k5;
		f5:
		$this->p = $o2;
		goto n4;
		k5:
		n4:
		if (!($this->ids[$this->p] === 49)) {
			if ($this->p >= $this->f) {
				$this->rec(7);
			}

			goto f3;}

		$this->p++;
		goto k3;
		f3:
		$this->p = $o1;
		return false;
		k3:

		return true;
	}

	private function s8(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				// s188
				$o2 = $this->p;
				if (!$this->s189()) {
					goto f193;}
				// s458
				$o3 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 49:
						if (!($this->ids[$this->p] === 49)) {
							if ($this->p > $this->f) {
								$this->rec(7);
							}

							goto n196;}
						goto a195;
						n196:
						break;
					case 80:
						$this->p++;
						goto a195;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f194;
				a195:
				goto k194;
				f194:
				$this->p = $o3;
				goto f193;
				k194:
				goto k193;
				f193:
				$this->p = $o2;
				goto n192;
				k193:
				goto a191;
				n192:
				break;
			case 76:
			case 77:
			case 78:
			case 79:
				// s9
				if (!isset(self::T942497bd[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(9);
					}

					goto n197;}

				$o2 = $this->p;
				if (!$this->s10()) {
					goto f198;}
				if (($this->ids[$this->p] === 80)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(3);
				}
				goto k198;
				f198:
				$this->p = $o2;
				goto n197;
				k198:
				goto a191;
				n197:
				// s188
				$o2 = $this->p;
				if (!$this->s189()) {
					goto f200;}
				// s458
				$o3 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 49:
						if (!($this->ids[$this->p] === 49)) {
							if ($this->p > $this->f) {
								$this->rec(7);
							}

							goto n203;}
						goto a202;
						n203:
						break;
					case 80:
						$this->p++;
						goto a202;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f201;
				a202:
				goto k201;
				f201:
				$this->p = $o3;
				goto f200;
				k201:
				goto k200;
				f200:
				$this->p = $o2;
				goto n199;
				k200:
				goto a191;
				n199:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(8);
		}

		goto f190;
		a191:
		goto k190;
		f190:
		$this->p = $o1;
		return false;
		k190:

		return true;
	}

	private function s10(): bool
	{
		if (!isset(self::T942497bd[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(10);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 76:
			case 77:
				// s11
				if (!isset(self::Tf6aec6a4[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(11);
					}

					goto n16;}

				$o2 = $this->p;
				if (!isset(self::Tf6aec6a4[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(12);
					}

					goto f17;}

				$this->p++;
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f17;}
				}
				// s19
				$o3 = $this->p;
				// s20
				$o4 = $this->p;
				while (true) {
					$b4 = $this->p;
					if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
						if ($b4 > $this->f) {
							$this->rec(21);
						}

						goto e20;
					}

					$this->p++;
				}
				e20:
				if (!$this->s74()) {
					goto f18;}
				goto k18;
				f18:
				$this->p = $o3;
				goto f17;
				k18:
				goto k17;
				f17:
				$this->p = $o2;
				goto n16;
				k17:
				goto a15;
				n16:
				// s134
				if (!isset(self::T942497bd[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(134);
					}

					goto n21;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 76:
					case 77:
						if (!$this->s187()) {
							goto n24;}
						goto a23;
						n24:
						break;
					case 78:
					case 79:
						if (!$this->s135()) {
							goto n25;}
						goto a23;
						n25:
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(134);
				}

				goto f22;
				a23:
				goto k22;
				f22:
				$this->p = $o2;
				goto n21;
				k22:
				goto a15;
				n21:
				break;
			case 78:
			case 79:
				// s134
				if (!isset(self::T942497bd[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(134);
					}

					goto n26;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 76:
					case 77:
						if (!$this->s187()) {
							goto n29;}
						goto a28;
						n29:
						break;
					case 78:
					case 79:
						if (!$this->s135()) {
							goto n30;}
						goto a28;
						n30:
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(134);
				}

				goto f27;
				a28:
				goto k27;
				f27:
				$this->p = $o2;
				goto n26;
				k27:
				goto a15;
				n26:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(10);
		}

		goto f14;
		a15:
		goto k14;
		f14:
		$this->p = $o1;
		return false;
		k14:

		return true;
	}

	private function s74(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				// s75
				$o2 = $this->p;
				if (!isset(self::T53b29bff[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(77);
					}

					goto f216;}
				if (!$this->s78()) {
					goto f216;}
				goto k216;
				f216:
				$this->p = $o2;
				goto n215;
				k216:
				goto a214;
				n215:
				// s117
				$o2 = $this->p;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f218;}
				if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
					if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f218;}
				}
				if (isset(self::Tce49e0fc[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s123();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f218;}
				}
				if (!$this->s125()) {
					goto f218;}
				goto k218;
				f218:
				$this->p = $o2;
				goto n217;
				k218:
				goto a214;
				n217:
				break;
			case 76:
			case 78:
				// s75
				$o2 = $this->p;
				if (!isset(self::T53b29bff[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(77);
					}

					goto f221;}
				if (!$this->s78()) {
					goto f221;}
				goto k221;
				f221:
				$this->p = $o2;
				goto n220;
				k221:
				goto a214;
				n220:
				// s117
				$o2 = $this->p;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f223;}
				if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
					if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f223;}
				}
				if (isset(self::Tce49e0fc[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s123();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f223;}
				}
				if (!$this->s125()) {
					goto f223;}
				goto k223;
				f223:
				$this->p = $o2;
				goto n222;
				k223:
				goto a214;
				n222:
				if (!isset(self::Tb3a20a39[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(121);
					}

					goto n225;}
				goto a214;
				n225:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(74);
		}

		goto f213;
		a214:
		goto k213;
		f213:
		$this->p = $o1;
		return false;
		k213:

		return true;
	}

	private function s78(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 80)) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s113();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto n464;}
				}
				goto a463;
				n464:
				break;
			case 80:
				if (!$this->s79()) {
					goto n466;}
				goto a463;
				n466:
				if (!$this->s110()) {
					goto n467;}
				goto a463;
				n467:
				if (($this->ids[$this->p] === 80)) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s113();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto n468;}
				}
				goto a463;
				n468:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(78);
		}

		goto f462;
		a463:
		goto k462;
		f462:
		$this->p = $o1;
		return false;
		k462:

		return true;
	}

	private function s79(): bool
	{
		if (!($this->ids[$this->p] === 80)) {
			if ($this->p > $this->f) {
				$this->rec(79);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 80)) {
			if ($this->p >= $this->f) {
				$this->rec(3);
			}

			goto f735;}

		$this->p++;
		if (isset(self::T195b3f33[$this->ids[$this->p]])) {
			if (isset(self::T195b3f33[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f735;}
		}
		// s109
		if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(109);
			}

			goto f735;}

		$o2 = $this->p;
		$k2 = 0;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(21);
				}

				goto e737;
			}

			$this->p++;

			$k2++;
		}
		e737:

		if ($k2 < 1) {
			goto f736;
		}
		goto k736;
		f736:
		$this->p = $o2;
		goto f735;
		k736:
		if (!$this->s74()) {
			goto f735;}
		goto k735;
		f735:
		$this->p = $o1;
		return false;
		k735:

		return true;
	}

	private function s110(): bool
	{
		if (!($this->ids[$this->p] === 80)) {
			if ($this->p > $this->f) {
				$this->rec(110);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 80)) {
			if ($this->p >= $this->f) {
				$this->rec(3);
			}

			goto f738;}

		$this->p++;
		if (isset(self::T195b3f33[$this->ids[$this->p]])) {
			if (isset(self::T195b3f33[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f738;}
		}
		// s111
		if (!($this->ids[$this->p] === 80)) {
			if ($this->p > $this->f) {
				$this->rec(111);
			}

			goto f738;}

		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 80:
				if (!$this->s79()) {
					goto n741;}
				goto a740;
				n741:
				if (!$this->s110()) {
					goto n742;}
				goto a740;
				n742:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(111);
		}

		goto f739;
		a740:
		goto k739;
		f739:
		$this->p = $o2;
		goto f738;
		k739:
		goto k738;
		f738:
		$this->p = $o1;
		return false;
		k738:

		return true;
	}

	private function s113(): bool
	{
		if (!($this->ids[$this->p] === 80)) {
			if ($this->p > $this->f) {
				$this->rec(113);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 80:
				// s114
				if (!($this->ids[$this->p] === 80)) {
					if ($this->p > $this->f) {
						$this->rec(114);
					}

					goto n731;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($this->p >= $this->f) {
						$this->rec(3);
					}

					goto f732;}

				$this->p++;
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if (isset(self::T195b3f33[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f732;}
				}
				if (!$this->s113()) {
					goto f732;}
				goto k732;
				f732:
				$this->p = $o2;
				goto n731;
				k732:
				goto a730;
				n731:
				// s115
				if (!($this->ids[$this->p] === 80)) {
					if ($this->p > $this->f) {
						$this->rec(115);
					}

					goto n733;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($this->p >= $this->f) {
						$this->rec(3);
					}

					goto f734;}

				$this->p++;
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if (isset(self::T195b3f33[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f734;}
				}
				if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(116);
					}

					goto f734;}

				$this->p++;
				goto k734;
				f734:
				$this->p = $o2;
				goto n733;
				k734:
				goto a730;
				n733:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(113);
		}

		goto f729;
		a730:
		goto k729;
		f729:
		$this->p = $o1;
		return false;
		k729:

		return true;
	}

	private function s123(): bool
	{
		if (!isset(self::Tce49e0fc[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(123);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tce49e0fc[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(124);
			}

			goto f470;}

		$this->p++;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f470;}

		$this->p++;
		goto k470;
		f470:
		$this->p = $o1;
		return false;
		k470:

		return true;
	}

	private function s125(): bool
	{
		$o1 = $this->p;
		// s126
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(116);
				}

				goto e245;
			}

			$this->p++;
		}
		e245:
		if (($this->ids[$this->p] === 80)) {
			if (!$this->s128()) {
				goto n246;}
			n246:
		} elseif ($this->p > $this->f) {
			$this->rec(128);
		}

		return true;
	}

	private function s128(): bool
	{
		if (!($this->ids[$this->p] === 80)) {
			if ($this->p > $this->f) {
				$this->rec(128);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b1 > $this->f) {
					$this->rec(129);
				}

				goto e485;
			}

			// s129
			if (!($this->ids[$this->p] === 80)) {
				if ($this->p > $this->f) {
					$this->rec(129);
				}

				goto e485;}

			$o2 = $this->p;
			if (!$this->s130()) {
				goto f486;}
			// s132
			if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(132);
				}

				goto f486;}

			$o3 = $this->p;
			$k3 = 0;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(116);
					}

					goto e488;
				}

				$this->p++;

				$k3++;
			}
			e488:

			if ($k3 < 1) {
				goto f487;
			}
			goto k487;
			f487:
			$this->p = $o3;
			goto f486;
			k487:
			goto k486;
			f486:
			$this->p = $o2;
			goto e485;
			k486:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e485;
			}

			$k1++;
		}
		e485:

		if ($k1 < 1) {
			goto f484;
		}
		goto k484;
		f484:
		$this->p = $o1;
		return false;
		k484:

		return true;
	}

	private function s130(): bool
	{
		if (!($this->ids[$this->p] === 80)) {
			if ($this->p > $this->f) {
				$this->rec(130);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b1 > $this->f) {
					$this->rec(131);
				}

				goto e769;
			}

			// s131
			if (!($this->ids[$this->p] === 80)) {
				if ($this->p > $this->f) {
					$this->rec(131);
				}

				goto e769;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($this->p >= $this->f) {
					$this->rec(3);
				}

				goto f770;}

			$this->p++;
			if (isset(self::T195b3f33[$this->ids[$this->p]])) {
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto f770;}
			}
			goto k770;
			f770:
			$this->p = $o2;
			goto e769;
			k770:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e769;
			}

			$k1++;
		}
		e769:

		if ($k1 < 1) {
			goto f768;
		}
		goto k768;
		f768:
		$this->p = $o1;
		return false;
		k768:

		return true;
	}

	private function s135(): bool
	{
		if (!isset(self::T46ee5f48[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(135);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::T46ee5f48[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(136);
			}

			goto f234;}

		$this->p++;
		if (!$this->s137()) {
			goto f234;}
		// s19
		$o2 = $this->p;
		// s20
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
				if ($b3 > $this->f) {
					$this->rec(21);
				}

				goto e237;
			}

			$this->p++;
		}
		e237:
		if (!$this->s74()) {
			goto f235;}
		goto k235;
		f235:
		$this->p = $o2;
		goto f234;
		k235:
		goto k234;
		f234:
		$this->p = $o1;
		return false;
		k234:

		return true;
	}

	private function s137(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n477;}
				goto a476;
				n477:
				break;
			case 32:
			case 82:
				// s138
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(138);
					}

					goto n478;}

				$o2 = $this->p;
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(16);
					}

					goto f479;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e481;
					}

					$this->p++;
				}
				e481:
				if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
					if (!$this->s141()) {
						goto n482;}
					n482:
				} elseif ($this->p > $this->f) {
					$this->rec(141);
				}
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p >= $this->f) {
						$this->rec(53);
					}

					goto f479;}

				$this->p++;
				goto k479;
				f479:
				$this->p = $o2;
				goto n478;
				k479:
				goto a476;
				n478:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n483;}
				goto a476;
				n483:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(137);
		}

		goto f475;
		a476:
		goto k475;
		f475:
		$this->p = $o1;
		return false;
		k475:

		return true;
	}

	private function s138(): bool
	{
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(138);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f1586;}

		$this->p++;
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1588;
			}

			$this->p++;
		}
		e1588:
		if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			// s141
			if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(141);
				}

				goto n1589;}

			$o2 = $this->p;
			if (!$this->s142()) {
				goto f1590;}
			// s139
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e1592;
				}

				$this->p++;
			}
			e1592:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->s182()) {
					goto n1593;}
				n1593:
			} elseif ($this->p > $this->f) {
				$this->rec(182);
			}
			goto k1590;
			f1590:
			$this->p = $o2;
			goto n1589;
			k1590:
			n1589:
		} elseif ($this->p > $this->f) {
			$this->rec(141);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1586;}

		$this->p++;
		goto k1586;
		f1586:
		$this->p = $o1;
		return false;
		k1586:

		return true;
	}

	private function s141(): bool
	{
		if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(141);
			}

			return false;}

		$o1 = $this->p;
		if (!$this->s142()) {
			goto f471;}
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e473;
			}

			$this->p++;
		}
		e473:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s182()) {
				goto n474;}
			n474:
		} elseif ($this->p > $this->f) {
			$this->rec(182);
		}
		goto k471;
		f471:
		$this->p = $o1;
		return false;
		k471:

		return true;
	}

	private function s142(): bool
	{
		if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(142);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 51:
			case 52:
			case 53:
			case 54:
			case 55:
			case 56:
			case 57:
			case 58:
			case 59:
			case 60:
			case 61:
			case 62:
			case 63:
			case 64:
			case 65:
			case 66:
			case 67:
			case 68:
			case 69:
			case 70:
			case 71:
			case 72:
			case 73:
			case 74:
			case 75:
			case 76:
			case 77:
			case 78:
			case 79:
				if (!$this->s146()) {
					goto n745;}
				goto a744;
				n745:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 24:
				if (!$this->s143()) {
					goto n746;}
				goto a744;
				n746:
				// s172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n747;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f748;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f748;}
				}
				goto k748;
				f748:
				$this->p = $o2;
				goto n747;
				k748:
				goto a744;
				n747:
				// s160
				if (!isset(self::T96867045[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(160);
					}

					goto n749;}

				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f750;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f750;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f750;}

				$this->p++;
				if (!$this->s163()) {
					goto f750;}
				goto k750;
				f750:
				$this->p = $o2;
				goto n749;
				k750:
				goto a744;
				n749:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				if (!$this->s143()) {
					goto n751;}
				goto a744;
				n751:
				// s172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n752;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f753;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f753;}
				}
				goto k753;
				f753:
				$this->p = $o2;
				goto n752;
				k753:
				goto a744;
				n752:
				break;
			case 22:
			case 23:
				// s155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n754;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->s156()) {
							goto n757;}
						goto a756;
						n757:
						break;
					case 23:
						$this->p++;
						goto a756;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f755;
				a756:
				goto k755;
				f755:
				$this->p = $o2;
				goto n754;
				k755:
				goto a744;
				n754:
				break;
			case 38:
			case 83:
				// s148
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(148);
					}

					goto n758;}

				$o2 = $this->p;
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(149);
					}

					goto f759;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e761;
					}

					$this->p++;
				}
				e761:
				if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
					if (!$this->s151()) {
						goto n762;}
					n762:
				} elseif ($this->p > $this->f) {
					$this->rec(151);
				}
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p >= $this->f) {
						$this->rec(62);
					}

					goto f759;}

				$this->p++;
				goto k759;
				f759:
				$this->p = $o2;
				goto n758;
				k759:
				goto a744;
				n758:
				break;
			case 19:
				$this->p++;
				goto a744;
				break;
			case 20:
				$this->p++;
				goto a744;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(142);
		}

		goto f743;
		a744:
		goto k743;
		f743:
		$this->p = $o1;
		return false;
		k743:

		return true;
	}

	private function s143(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(143);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f993;}

		$this->p++;
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e995;
			}

			$this->p++;
		}
		e995:
		if (!($this->ids[$this->p] === 46)) {
			if ($this->p >= $this->f) {
				$this->rec(69);
			}

			goto f993;}

		$this->p++;
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e997;
			}

			$this->p++;
		}
		e997:
		if (!$this->s145()) {
			goto f993;}
		goto k993;
		f993:
		$this->p = $o1;
		return false;
		k993:

		return true;
	}

	private function s145(): bool
	{
		if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(145);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 51:
			case 52:
			case 53:
			case 54:
			case 55:
			case 56:
			case 57:
			case 58:
			case 59:
			case 60:
			case 61:
			case 62:
			case 63:
			case 64:
			case 65:
			case 66:
			case 67:
			case 68:
			case 69:
			case 70:
			case 71:
			case 72:
			case 73:
			case 74:
			case 75:
			case 76:
			case 77:
			case 78:
			case 79:
				// s146
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(146);
					}

					goto n1302;}

				$o2 = $this->p;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f1303;}

				$this->p++;
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if (!$this->s138()) {
						goto n1304;}
					n1304:
				} elseif ($this->p > $this->f) {
					$this->rec(138);
				}
				goto k1303;
				f1303:
				$this->p = $o2;
				goto n1302;
				k1303:
				goto a1301;
				n1302:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 24:
				// s172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n1305;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1306;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1306;}
				}
				goto k1306;
				f1306:
				$this->p = $o2;
				goto n1305;
				k1306:
				goto a1301;
				n1305:
				// s160
				if (!isset(self::T96867045[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(160);
					}

					goto n1307;}

				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1308;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1308;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f1308;}

				$this->p++;
				if (!$this->s163()) {
					goto f1308;}
				goto k1308;
				f1308:
				$this->p = $o2;
				goto n1307;
				k1308:
				goto a1301;
				n1307:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				// s172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n1309;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1310;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1310;}
				}
				goto k1310;
				f1310:
				$this->p = $o2;
				goto n1309;
				k1310:
				goto a1301;
				n1309:
				break;
			case 22:
			case 23:
				// s155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n1311;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->s156()) {
							goto n1314;}
						goto a1313;
						n1314:
						break;
					case 23:
						$this->p++;
						goto a1313;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f1312;
				a1313:
				goto k1312;
				f1312:
				$this->p = $o2;
				goto n1311;
				k1312:
				goto a1301;
				n1311:
				break;
			case 38:
			case 83:
				// s148
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(148);
					}

					goto n1315;}

				$o2 = $this->p;
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(149);
					}

					goto f1316;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1318;
					}

					$this->p++;
				}
				e1318:
				if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
					if (!$this->s151()) {
						goto n1319;}
					n1319:
				} elseif ($this->p > $this->f) {
					$this->rec(151);
				}
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p >= $this->f) {
						$this->rec(62);
					}

					goto f1316;}

				$this->p++;
				goto k1316;
				f1316:
				$this->p = $o2;
				goto n1315;
				k1316:
				goto a1301;
				n1315:
				break;
			case 19:
				$this->p++;
				goto a1301;
				break;
			case 20:
				$this->p++;
				goto a1301;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(145);
		}

		goto f1300;
		a1301:
		goto k1300;
		f1300:
		$this->p = $o1;
		return false;
		k1300:

		return true;
	}

	private function s146(): bool
	{
		if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(146);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(119);
			}

			goto f987;}

		$this->p++;
		if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			// s138
			if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(138);
				}

				goto n988;}

			$o2 = $this->p;
			if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(16);
				}

				goto f989;}

			$this->p++;
			// s139
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e991;
				}

				$this->p++;
			}
			e991:
			if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
				if (!$this->s141()) {
					goto n992;}
				n992:
			} elseif ($this->p > $this->f) {
				$this->rec(141);
			}
			if (!($this->ids[$this->p] === 33)) {
				if ($this->p >= $this->f) {
					$this->rec(53);
				}

				goto f989;}

			$this->p++;
			goto k989;
			f989:
			$this->p = $o2;
			goto n988;
			k989:
			n988:
		} elseif ($this->p > $this->f) {
			$this->rec(138);
		}
		goto k987;
		f987:
		$this->p = $o1;
		return false;
		k987:

		return true;
	}

	private function s151(): bool
	{
		if (!isset(self::T1490fe7f[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(151);
			}

			return false;}

		$o1 = $this->p;
		if (!$this->s152()) {
			goto f1002;}
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1004;
			}

			$this->p++;
		}
		e1004:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s176()) {
				goto n1005;}
			n1005:
		} elseif ($this->p > $this->f) {
			$this->rec(176);
		}
		goto k1002;
		f1002:
		$this->p = $o1;
		return false;
		k1002:

		return true;
	}

	private function s152(): bool
	{
		if (!isset(self::T1490fe7f[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(152);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 51:
			case 52:
			case 53:
			case 54:
			case 55:
			case 56:
			case 57:
			case 58:
			case 59:
			case 60:
			case 61:
			case 62:
			case 63:
			case 64:
			case 65:
			case 66:
			case 67:
			case 68:
			case 69:
			case 70:
			case 71:
			case 72:
			case 73:
			case 74:
			case 75:
			case 76:
			case 77:
			case 78:
			case 79:
				// s146
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(146);
					}

					goto n1322;}

				$o2 = $this->p;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f1323;}

				$this->p++;
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if (!$this->s138()) {
						goto n1324;}
					n1324:
				} elseif ($this->p > $this->f) {
					$this->rec(138);
				}
				goto k1323;
				f1323:
				$this->p = $o2;
				goto n1322;
				k1323:
				goto a1321;
				n1322:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 24:
				// s153
				if (!isset(self::T7726a0df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(153);
					}

					goto n1325;}

				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1326;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1328;
					}

					$this->p++;
				}
				e1328:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1326;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1330;
					}

					$this->p++;
				}
				e1330:
				if (!$this->s145()) {
					goto f1326;}
				goto k1326;
				f1326:
				$this->p = $o2;
				goto n1325;
				k1326:
				goto a1321;
				n1325:
				// s172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n1331;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1332;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1332;}
				}
				goto k1332;
				f1332:
				$this->p = $o2;
				goto n1331;
				k1332:
				goto a1321;
				n1331:
				// s160
				if (!isset(self::T96867045[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(160);
					}

					goto n1333;}

				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1334;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1334;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f1334;}

				$this->p++;
				if (!$this->s163()) {
					goto f1334;}
				goto k1334;
				f1334:
				$this->p = $o2;
				goto n1333;
				k1334:
				goto a1321;
				n1333:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				// s153
				if (!isset(self::T7726a0df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(153);
					}

					goto n1335;}

				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1336;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1338;
					}

					$this->p++;
				}
				e1338:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1336;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1340;
					}

					$this->p++;
				}
				e1340:
				if (!$this->s145()) {
					goto f1336;}
				goto k1336;
				f1336:
				$this->p = $o2;
				goto n1335;
				k1336:
				goto a1321;
				n1335:
				// s172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n1341;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1342;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1342;}
				}
				goto k1342;
				f1342:
				$this->p = $o2;
				goto n1341;
				k1342:
				goto a1321;
				n1341:
				break;
			case 22:
			case 23:
				// s153
				if (!isset(self::T7726a0df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(153);
					}

					goto n1343;}

				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1344;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1346;
					}

					$this->p++;
				}
				e1346:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1344;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1348;
					}

					$this->p++;
				}
				e1348:
				if (!$this->s145()) {
					goto f1344;}
				goto k1344;
				f1344:
				$this->p = $o2;
				goto n1343;
				k1344:
				goto a1321;
				n1343:
				// s155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n1349;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->s156()) {
							goto n1352;}
						goto a1351;
						n1352:
						break;
					case 23:
						$this->p++;
						goto a1351;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f1350;
				a1351:
				goto k1350;
				f1350:
				$this->p = $o2;
				goto n1349;
				k1350:
				goto a1321;
				n1349:
				break;
			case 38:
			case 83:
				// s148
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(148);
					}

					goto n1353;}

				$o2 = $this->p;
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(149);
					}

					goto f1354;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1356;
					}

					$this->p++;
				}
				e1356:
				if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
					if (!$this->s151()) {
						goto n1357;}
					n1357:
				} elseif ($this->p > $this->f) {
					$this->rec(151);
				}
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p >= $this->f) {
						$this->rec(62);
					}

					goto f1354;}

				$this->p++;
				goto k1354;
				f1354:
				$this->p = $o2;
				goto n1353;
				k1354:
				goto a1321;
				n1353:
				break;
			case 19:
				$this->p++;
				goto a1321;
				break;
			case 20:
				// s153
				if (!isset(self::T7726a0df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(153);
					}

					goto n1358;}

				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1359;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1361;
					}

					$this->p++;
				}
				e1361:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1359;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1363;
					}

					$this->p++;
				}
				e1363:
				if (!$this->s145()) {
					goto f1359;}
				goto k1359;
				f1359:
				$this->p = $o2;
				goto n1358;
				k1359:
				goto a1321;
				n1358:
				$this->p++;
				goto a1321;
				break;
			case 21:
				// s153
				if (!isset(self::T7726a0df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(153);
					}

					goto n1364;}

				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1365;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1367;
					}

					$this->p++;
				}
				e1367:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1365;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1369;
					}

					$this->p++;
				}
				e1369:
				if (!$this->s145()) {
					goto f1365;}
				goto k1365;
				f1365:
				$this->p = $o2;
				goto n1364;
				k1365:
				goto a1321;
				n1364:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(152);
		}

		goto f1320;
		a1321:
		goto k1320;
		f1320:
		$this->p = $o1;
		return false;
		k1320:

		return true;
	}

	private function s154(): bool
	{
		if (!isset(self::T7726a0df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(154);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 24:
				// s160
				if (!isset(self::T96867045[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(160);
					}

					goto n1596;}

				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1597;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1597;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f1597;}

				$this->p++;
				if (!$this->s163()) {
					goto f1597;}
				goto k1597;
				f1597:
				$this->p = $o2;
				goto n1596;
				k1597:
				goto a1595;
				n1596:
				// s169
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(169);
					}

					goto n1598;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1599;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1599;}
				}
				goto k1599;
				f1599:
				$this->p = $o2;
				goto n1598;
				k1599:
				goto a1595;
				n1598:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				// s169
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(169);
					}

					goto n1600;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1601;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1601;}
				}
				goto k1601;
				f1601:
				$this->p = $o2;
				goto n1600;
				k1601:
				goto a1595;
				n1600:
				break;
			case 22:
			case 23:
				// s155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n1602;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->s156()) {
							goto n1605;}
						goto a1604;
						n1605:
						break;
					case 23:
						$this->p++;
						goto a1604;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f1603;
				a1604:
				goto k1603;
				f1603:
				$this->p = $o2;
				goto n1602;
				k1603:
				goto a1595;
				n1602:
				break;
			case 20:
				$this->p++;
				goto a1595;
				break;
			case 21:
				$this->p++;
				goto a1595;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(154);
		}

		goto f1594;
		a1595:
		goto k1594;
		f1594:
		$this->p = $o1;
		return false;
		k1594:

		return true;
	}

	private function s156(): bool
	{
		if (!($this->ids[$this->p] === 22)) {
			if ($this->p > $this->f) {
				$this->rec(156);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 22)) {
			if ($this->p >= $this->f) {
				$this->rec(44);
			}

			goto f998;}

		$this->p++;
		if (isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
			// s158
			if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(158);
				}

				goto n999;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(159);
					}

					goto e1001;
				}

				$this->p++;

				$k2++;
			}
			e1001:

			if ($k2 < 1) {
				goto f1000;
			}
			goto k1000;
			f1000:
			$this->p = $o2;
			goto n999;
			k1000:
			n999:
		} elseif ($this->p > $this->f) {
			$this->rec(158);
		}
		goto k998;
		f998:
		$this->p = $o1;
		return false;
		k998:

		return true;
	}

	private function s163(): bool
	{
		if (!isset(self::Td08f0236[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(163);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				// s166
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(166);
					}

					goto n706;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f707;}

				$this->p++;
				if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
					if (!$this->s168()) {
						goto n708;}
					n708:
				} elseif ($this->p > $this->f) {
					$this->rec(168);
				}
				goto k707;
				f707:
				$this->p = $o2;
				goto n706;
				k707:
				goto a705;
				n706:
				break;
			case 50:
				// s164
				if (!($this->ids[$this->p] === 50)) {
					if ($this->p > $this->f) {
						$this->rec(164);
					}

					goto n709;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 50)) {
					if ($this->p >= $this->f) {
						$this->rec(71);
					}

					goto f710;}

				$this->p++;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					if (!$this->s166()) {
						goto n711;}
					n711:
				} elseif ($this->p > $this->f) {
					$this->rec(166);
				}
				goto k710;
				f710:
				$this->p = $o2;
				goto n709;
				k710:
				goto a705;
				n709:
				break;
			case 85:
				$this->p++;
				goto a705;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(163);
		}

		goto f704;
		a705:
		goto k704;
		f704:
		$this->p = $o1;
		return false;
		k704:

		return true;
	}

	private function s164(): bool
	{
		if (!($this->ids[$this->p] === 50)) {
			if ($this->p > $this->f) {
				$this->rec(164);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 50)) {
			if ($this->p >= $this->f) {
				$this->rec(71);
			}

			goto f1271;}

		$this->p++;
		if (isset(self::T83e30518[$this->ids[$this->p]])) {
			if (!$this->s166()) {
				goto n1272;}
			n1272:
		} elseif ($this->p > $this->f) {
			$this->rec(166);
		}
		goto k1271;
		f1271:
		$this->p = $o1;
		return false;
		k1271:

		return true;
	}

	private function s166(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(166);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f960;}

		$this->p++;
		if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
			if (!$this->s168()) {
				goto n961;}
			n961:
		} elseif ($this->p > $this->f) {
			$this->rec(168);
		}
		goto k960;
		f960:
		$this->p = $o1;
		return false;
		k960:

		return true;
	}

	private function s168(): bool
	{
		if (!isset(self::T4d756b3a[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(168);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 50:
				if (!$this->s164()) {
					goto n959;}
				goto a958;
				n959:
				break;
			case 85:
				$this->p++;
				goto a958;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(168);
		}

		goto f957;
		a958:
		goto k957;
		f957:
		$this->p = $o1;
		return false;
		k957:

		return true;
	}

	private function s169(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(169);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f607;}

		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f607;}
		}
		goto k607;
		f607:
		$this->p = $o1;
		return false;
		k607:

		return true;
	}

	private function s176(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(176);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(177);
				}

				goto e1371;
			}

			// s177
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(177);
				}

				goto e1371;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1372;}

			$this->p++;
			// s139
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e1374;
				}

				$this->p++;
			}
			e1374:
			if (!$this->s178()) {
				goto f1372;}
			goto k1372;
			f1372:
			$this->p = $o2;
			goto e1371;
			k1372:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1371;
			}

			$k1++;
		}
		e1371:

		if ($k1 < 1) {
			goto f1370;
		}
		goto k1370;
		f1370:
		$this->p = $o1;
		return false;
		k1370:

		return true;
	}

	private function s178(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				// s180
				if (!isset(self::T1490fe7f[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(180);
					}

					goto n1608;}

				$o2 = $this->p;
				if (!$this->s152()) {
					goto f1609;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1611;
					}

					$this->p++;
				}
				e1611:
				goto k1609;
				f1609:
				$this->p = $o2;
				goto n1608;
				k1609:
				goto a1607;
				n1608:
				break;
			case 0:
			case 25:
			case 26:
			case 27:
			case 28:
			case 29:
			case 30:
			case 31:
			case 32:
			case 33:
			case 34:
			case 35:
			case 36:
			case 37:
			case 40:
			case 41:
			case 42:
			case 43:
			case 44:
			case 45:
			case 46:
			case 47:
			case 48:
			case 49:
			case 50:
			case 80:
			case 81:
			case 82:
			case 84:
			case 85:
			case 86:
			case -1:
				break;
			case 39:
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p > $this->f) {
						$this->rec(62);
					}

					goto n1612;}
				goto a1607;
				n1612:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(178);
		}

		goto f1606;
		a1607:
		goto k1606;
		f1606:
		$this->p = $o1;
		return false;
		k1606:

		return true;
	}

	private function s182(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(182);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(183);
				}

				goto e764;
			}

			// s183
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(183);
				}

				goto e764;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f765;}

			$this->p++;
			// s139
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e767;
				}

				$this->p++;
			}
			e767:
			if (!$this->s184()) {
				goto f765;}
			goto k765;
			f765:
			$this->p = $o2;
			goto e764;
			k765:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e764;
			}

			$k1++;
		}
		e764:

		if ($k1 < 1) {
			goto f763;
		}
		goto k763;
		f763:
		$this->p = $o1;
		return false;
		k763:

		return true;
	}

	private function s184(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				// s186
				if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(186);
					}

					goto n1008;}

				$o2 = $this->p;
				if (!$this->s142()) {
					goto f1009;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1011;
					}

					$this->p++;
				}
				e1011:
				goto k1009;
				f1009:
				$this->p = $o2;
				goto n1008;
				k1009:
				goto a1007;
				n1008:
				break;
			case 0:
			case 21:
			case 25:
			case 26:
			case 27:
			case 28:
			case 29:
			case 30:
			case 31:
			case 32:
			case 34:
			case 35:
			case 36:
			case 37:
			case 39:
			case 40:
			case 41:
			case 42:
			case 43:
			case 44:
			case 45:
			case 46:
			case 47:
			case 48:
			case 49:
			case 50:
			case 80:
			case 81:
			case 82:
			case 84:
			case 85:
			case 86:
			case -1:
				break;
			case 33:
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p > $this->f) {
						$this->rec(53);
					}

					goto n1012;}
				goto a1007;
				n1012:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(184);
		}

		goto f1006;
		a1007:
		goto k1006;
		f1006:
		$this->p = $o1;
		return false;
		k1006:

		return true;
	}

	private function s187(): bool
	{
		if (!isset(self::Tf6aec6a4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(187);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tf6aec6a4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(12);
			}

			goto f226;}

		$this->p++;
		// s138
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(138);
			}

			goto f226;}

		$o2 = $this->p;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f227;}

		$this->p++;
		// s139
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b3 >= $this->f) {
					$this->rec(3);
				}

				goto e229;
			}

			$this->p++;
		}
		e229:
		if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			if (!$this->s141()) {
				goto n230;}
			n230:
		} elseif ($this->p > $this->f) {
			$this->rec(141);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f227;}

		$this->p++;
		goto k227;
		f227:
		$this->p = $o2;
		goto f226;
		k227:
		// s19
		$o2 = $this->p;
		// s20
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
				if ($b3 > $this->f) {
					$this->rec(21);
				}

				goto e233;
			}

			$this->p++;
		}
		e233:
		if (!$this->s74()) {
			goto f231;}
		goto k231;
		f231:
		$this->p = $o2;
		goto f226;
		k231:
		goto k226;
		f226:
		$this->p = $o1;
		return false;
		k226:

		return true;
	}

	private function s189(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				// s455
				$o2 = $this->p;
				if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f42;}
				}
				// s457
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f43;}
				goto k43;
				f43:
				$this->p = $o3;
				goto f42;
				k43:
				goto k42;
				f42:
				$this->p = $o2;
				goto n41;
				k42:
				goto a40;
				n41:
				break;
			case 51:
			case 52:
			case 53:
			case 54:
			case 55:
			case 56:
			case 57:
			case 58:
			case 59:
			case 60:
			case 61:
			case 62:
			case 63:
			case 64:
			case 65:
			case 66:
			case 67:
			case 68:
			case 69:
			case 70:
			case 71:
			case 72:
			case 73:
			case 74:
			case 75:
				if (!$this->s190()) {
					goto n44;}
				goto a40;
				n44:
				if (!$this->s454()) {
					goto n45;}
				goto a40;
				n45:
				// s455
				$o2 = $this->p;
				if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f47;}
				}
				// s457
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f48;}
				goto k48;
				f48:
				$this->p = $o3;
				goto f47;
				k48:
				goto k47;
				f47:
				$this->p = $o2;
				goto n46;
				k47:
				goto a40;
				n46:
				break;
			case 76:
			case 77:
			case 78:
			case 79:
				if (!$this->s454()) {
					goto n49;}
				goto a40;
				n49:
				// s455
				$o2 = $this->p;
				if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f51;}
				}
				// s457
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f52;}
				goto k52;
				f52:
				$this->p = $o3;
				goto f51;
				k52:
				goto k51;
				f51:
				$this->p = $o2;
				goto n50;
				k51:
				goto a40;
				n50:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(189);
		}

		goto f39;
		a40:
		goto k39;
		f39:
		$this->p = $o1;
		return false;
		k39:

		return true;
	}

	private function s190(): bool
	{
		if (!isset(self::Tce49e0fc[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(190);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 51:
				// s402
				if (!($this->ids[$this->p] === 51)) {
					if ($this->p > $this->f) {
						$this->rec(402);
					}

					goto n100;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 51)) {
					if ($this->p >= $this->f) {
						$this->rec(85);
					}

					goto f101;}

				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f101;}

				$o3 = $this->p;
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f102;}

				$this->p++;
				if (!$this->s125()) {
					goto f102;}
				goto k102;
				f102:
				$this->p = $o3;
				goto f101;
				k102:
				goto k101;
				f101:
				$this->p = $o2;
				goto n100;
				k101:
				goto a99;
				n100:
				break;
			case 52:
				// s405
				if (!($this->ids[$this->p] === 52)) {
					if ($this->p > $this->f) {
						$this->rec(405);
					}

					goto n103;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 52)) {
					if ($this->p >= $this->f) {
						$this->rec(88);
					}

					goto f104;}

				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f104;}

				$o3 = $this->p;
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f105;}

				$this->p++;
				if (!$this->s125()) {
					goto f105;}
				goto k105;
				f105:
				$this->p = $o3;
				goto f104;
				k105:
				goto k104;
				f104:
				$this->p = $o2;
				goto n103;
				k104:
				goto a99;
				n103:
				break;
			case 53:
				// s406
				if (!($this->ids[$this->p] === 53)) {
					if ($this->p > $this->f) {
						$this->rec(406);
					}

					goto n106;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 53)) {
					if ($this->p >= $this->f) {
						$this->rec(89);
					}

					goto f107;}

				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f107;}

				$o3 = $this->p;
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f108;}

				$this->p++;
				if (!$this->s125()) {
					goto f108;}
				goto k108;
				f108:
				$this->p = $o3;
				goto f107;
				k108:
				goto k107;
				f107:
				$this->p = $o2;
				goto n106;
				k107:
				goto a99;
				n106:
				break;
			case 54:
				// s404
				if (!($this->ids[$this->p] === 54)) {
					if ($this->p > $this->f) {
						$this->rec(404);
					}

					goto n109;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 54)) {
					if ($this->p >= $this->f) {
						$this->rec(86);
					}

					goto f110;}

				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f110;}

				$o3 = $this->p;
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f111;}

				$this->p++;
				if (!$this->s125()) {
					goto f111;}
				goto k111;
				f111:
				$this->p = $o3;
				goto f110;
				k111:
				goto k110;
				f110:
				$this->p = $o2;
				goto n109;
				k110:
				goto a99;
				n109:
				break;
			case 55:
				// s401
				if (!($this->ids[$this->p] === 55)) {
					if ($this->p > $this->f) {
						$this->rec(401);
					}

					goto n112;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 55)) {
					if ($this->p >= $this->f) {
						$this->rec(87);
					}

					goto f113;}

				$this->p++;
				// s399
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(399);
					}

					goto f113;}

				$o3 = $this->p;
				if (!$this->s199()) {
					goto f114;}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f114;}

				$this->p++;
				if (!$this->s125()) {
					goto f114;}
				goto k114;
				f114:
				$this->p = $o3;
				goto f113;
				k114:
				goto k113;
				f113:
				$this->p = $o2;
				goto n112;
				k113:
				goto a99;
				n112:
				break;
			case 56:
				// s395
				if (!($this->ids[$this->p] === 56)) {
					if ($this->p > $this->f) {
						$this->rec(395);
					}

					goto n115;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 56)) {
					if ($this->p >= $this->f) {
						$this->rec(95);
					}

					goto f116;}

				$this->p++;
				if (!$this->s391()) {
					goto f116;}
				goto k116;
				f116:
				$this->p = $o2;
				goto n115;
				k116:
				goto a99;
				n115:
				break;
			case 57:
				// s394
				if (!($this->ids[$this->p] === 57)) {
					if ($this->p > $this->f) {
						$this->rec(394);
					}

					goto n117;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 57)) {
					if ($this->p >= $this->f) {
						$this->rec(94);
					}

					goto f118;}

				$this->p++;
				if (!$this->s391()) {
					goto f118;}
				goto k118;
				f118:
				$this->p = $o2;
				goto n117;
				k118:
				goto a99;
				n117:
				break;
			case 58:
				// s443
				if (!($this->ids[$this->p] === 58)) {
					if ($this->p > $this->f) {
						$this->rec(443);
					}

					goto n119;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 58)) {
					if ($this->p >= $this->f) {
						$this->rec(105);
					}

					goto f120;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f120;}

				$this->p++;
				if (!($this->ids[$this->p] === 10)) {
					if ($this->p >= $this->f) {
						$this->rec(26);
					}

					goto f120;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f120;}

				$this->p++;
				if (!$this->s444()) {
					goto f120;}
				goto k120;
				f120:
				$this->p = $o2;
				goto n119;
				k120:
				goto a99;
				n119:
				break;
			case 59:
				// s446
				if (!($this->ids[$this->p] === 59)) {
					if ($this->p > $this->f) {
						$this->rec(446);
					}

					goto n121;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 59)) {
					if ($this->p >= $this->f) {
						$this->rec(106);
					}

					goto f122;}

				$this->p++;
				if (($this->ids[$this->p] === 31)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(52);
				}
				if (($this->ids[$this->p] === 46)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(69);
				}
				if (!$this->s199()) {
					goto f122;}
				if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(448);
					}

					goto f122;}

				$this->p++;
				if (!$this->s449()) {
					goto f122;}
				if (!$this->s125()) {
					goto f122;}
				goto k122;
				f122:
				$this->p = $o2;
				goto n121;
				k122:
				goto a99;
				n121:
				break;
			case 60:
				// s397
				if (!($this->ids[$this->p] === 60)) {
					if ($this->p > $this->f) {
						$this->rec(397);
					}

					goto n123;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 60)) {
					if ($this->p >= $this->f) {
						$this->rec(107);
					}

					goto f124;}

				$this->p++;
				if (!$this->s391()) {
					goto f124;}
				goto k124;
				f124:
				$this->p = $o2;
				goto n123;
				k124:
				goto a99;
				n123:
				break;
			case 61:
				// s396
				if (!($this->ids[$this->p] === 61)) {
					if ($this->p > $this->f) {
						$this->rec(396);
					}

					goto n125;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 61)) {
					if ($this->p >= $this->f) {
						$this->rec(96);
					}

					goto f126;}

				$this->p++;
				if (!$this->s391()) {
					goto f126;}
				goto k126;
				f126:
				$this->p = $o2;
				goto n125;
				k126:
				goto a99;
				n125:
				break;
			case 62:
				if (!$this->s436()) {
					goto n127;}
				goto a99;
				n127:
				break;
			case 63:
				// s398
				if (!($this->ids[$this->p] === 63)) {
					if ($this->p > $this->f) {
						$this->rec(398);
					}

					goto n128;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 63)) {
					if ($this->p >= $this->f) {
						$this->rec(98);
					}

					goto f129;}

				$this->p++;
				// s399
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(399);
					}

					goto f129;}

				$o3 = $this->p;
				if (!$this->s199()) {
					goto f130;}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f130;}

				$this->p++;
				if (!$this->s125()) {
					goto f130;}
				goto k130;
				f130:
				$this->p = $o3;
				goto f129;
				k130:
				goto k129;
				f129:
				$this->p = $o2;
				goto n128;
				k129:
				goto a99;
				n128:
				break;
			case 64:
				// s439
				if (!($this->ids[$this->p] === 64)) {
					if ($this->p > $this->f) {
						$this->rec(439);
					}

					goto n131;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 64)) {
					if ($this->p >= $this->f) {
						$this->rec(102);
					}

					goto f132;}

				$this->p++;
				if (!$this->s438()) {
					goto f132;}
				goto k132;
				f132:
				$this->p = $o2;
				goto n131;
				k132:
				goto a99;
				n131:
				break;
			case 65:
				// s407
				if (!($this->ids[$this->p] === 65)) {
					if ($this->p > $this->f) {
						$this->rec(407);
					}

					goto n133;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 65)) {
					if ($this->p >= $this->f) {
						$this->rec(97);
					}

					goto f134;}

				$this->p++;
				if (!$this->s125()) {
					goto f134;}
				goto k134;
				f134:
				$this->p = $o2;
				goto n133;
				k134:
				goto a99;
				n133:
				break;
			case 66:
				// s441
				if (!($this->ids[$this->p] === 66)) {
					if ($this->p > $this->f) {
						$this->rec(441);
					}

					goto n135;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 66)) {
					if ($this->p >= $this->f) {
						$this->rec(104);
					}

					goto f136;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f136;}

				$this->p++;
				if (($this->ids[$this->p] === 46)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(69);
				}
				if (!$this->s199()) {
					goto f136;}
				if (!isset(self::T53b29bff[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(77);
					}

					goto f136;}
				goto k136;
				f136:
				$this->p = $o2;
				goto n135;
				k136:
				goto a99;
				n135:
				// s442
				if (!($this->ids[$this->p] === 66)) {
					if ($this->p > $this->f) {
						$this->rec(442);
					}

					goto n137;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 66)) {
					if ($this->p >= $this->f) {
						$this->rec(104);
					}

					goto f138;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f138;}

				$this->p++;
				if (($this->ids[$this->p] === 46)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(69);
				}
				if (!$this->s125()) {
					goto f138;}
				goto k138;
				f138:
				$this->p = $o2;
				goto n137;
				k138:
				goto a99;
				n137:
				break;
			case 67:
				// s400
				if (!($this->ids[$this->p] === 67)) {
					if ($this->p > $this->f) {
						$this->rec(400);
					}

					goto n139;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 67)) {
					if ($this->p >= $this->f) {
						$this->rec(108);
					}

					goto f140;}

				$this->p++;
				// s399
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(399);
					}

					goto f140;}

				$o3 = $this->p;
				if (!$this->s199()) {
					goto f141;}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f141;}

				$this->p++;
				if (!$this->s125()) {
					goto f141;}
				goto k141;
				f141:
				$this->p = $o3;
				goto f140;
				k141:
				goto k140;
				f140:
				$this->p = $o2;
				goto n139;
				k140:
				goto a99;
				n139:
				break;
			case 68:
				// s437
				if (!($this->ids[$this->p] === 68)) {
					if ($this->p > $this->f) {
						$this->rec(437);
					}

					goto n142;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 68)) {
					if ($this->p >= $this->f) {
						$this->rec(101);
					}

					goto f143;}

				$this->p++;
				if (!$this->s438()) {
					goto f143;}
				goto k143;
				f143:
				$this->p = $o2;
				goto n142;
				k143:
				goto a99;
				n142:
				break;
			case 69:
				// s408
				if (!($this->ids[$this->p] === 69)) {
					if ($this->p > $this->f) {
						$this->rec(408);
					}

					goto n144;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 69)) {
					if ($this->p >= $this->f) {
						$this->rec(99);
					}

					goto f145;}

				$this->p++;
				if (!$this->s409()) {
					goto f145;}
				goto k145;
				f145:
				$this->p = $o2;
				goto n144;
				k145:
				goto a99;
				n144:
				break;
			case 70:
				// s390
				if (!($this->ids[$this->p] === 70)) {
					if ($this->p > $this->f) {
						$this->rec(390);
					}

					goto n146;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 70)) {
					if ($this->p >= $this->f) {
						$this->rec(91);
					}

					goto f147;}

				$this->p++;
				if (!$this->s391()) {
					goto f147;}
				goto k147;
				f147:
				$this->p = $o2;
				goto n146;
				k147:
				goto a99;
				n146:
				break;
			case 71:
				// s392
				if (!($this->ids[$this->p] === 71)) {
					if ($this->p > $this->f) {
						$this->rec(392);
					}

					goto n148;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 71)) {
					if ($this->p >= $this->f) {
						$this->rec(92);
					}

					goto f149;}

				$this->p++;
				if (!$this->s391()) {
					goto f149;}
				goto k149;
				f149:
				$this->p = $o2;
				goto n148;
				k149:
				goto a99;
				n148:
				break;
			case 72:
				// s191
				if (!($this->ids[$this->p] === 72)) {
					if ($this->p > $this->f) {
						$this->rec(191);
					}

					goto n150;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 72)) {
					if ($this->p >= $this->f) {
						$this->rec(84);
					}

					goto f151;}

				$this->p++;
				if (!$this->s192()) {
					goto f151;}
				goto k151;
				f151:
				$this->p = $o2;
				goto n150;
				k151:
				goto a99;
				n150:
				break;
			case 73:
				// s393
				if (!($this->ids[$this->p] === 73)) {
					if ($this->p > $this->f) {
						$this->rec(393);
					}

					goto n152;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 73)) {
					if ($this->p >= $this->f) {
						$this->rec(93);
					}

					goto f153;}

				$this->p++;
				if (!$this->s391()) {
					goto f153;}
				goto k153;
				f153:
				$this->p = $o2;
				goto n152;
				k153:
				goto a99;
				n152:
				break;
			case 74:
				// s440
				if (!($this->ids[$this->p] === 74)) {
					if ($this->p > $this->f) {
						$this->rec(440);
					}

					goto n154;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 74)) {
					if ($this->p >= $this->f) {
						$this->rec(103);
					}

					goto f155;}

				$this->p++;
				if (!$this->s438()) {
					goto f155;}
				goto k155;
				f155:
				$this->p = $o2;
				goto n154;
				k155:
				goto a99;
				n154:
				break;
			case 75:
				// s384
				if (!($this->ids[$this->p] === 75)) {
					if ($this->p > $this->f) {
						$this->rec(384);
					}

					goto n156;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 75)) {
					if ($this->p >= $this->f) {
						$this->rec(90);
					}

					goto f157;}

				$this->p++;
				if (!$this->s199()) {
					goto f157;}
				if (!$this->s385()) {
					goto f157;}
				goto k157;
				f157:
				$this->p = $o2;
				goto n156;
				k157:
				goto a99;
				n156:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(190);
		}

		goto f98;
		a99:
		goto k98;
		f98:
		$this->p = $o1;
		return false;
		k98:

		return true;
	}

	private function s192(): bool
	{
		if (!isset(self::T3bf4df75[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(192);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				// s198
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(198);
					}

					goto n328;}

				$o2 = $this->p;
				if (!$this->s199()) {
					goto f329;}
				if (($this->ids[$this->p] === 27)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(48);
				}
				if (($this->ids[$this->p] === 42)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(65);
				}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f329;}

				$this->p++;
				if (!$this->s125()) {
					goto f329;}
				goto k329;
				f329:
				$this->p = $o2;
				goto n328;
				k329:
				goto a327;
				n328:
				break;
			case 26:
			case 27:
			case 42:
				// s193
				if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(193);
					}

					goto n330;}

				$o2 = $this->p;
				if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(195);
					}

					goto f331;}
				if (($this->ids[$this->p] === 27)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(48);
				}
				if (($this->ids[$this->p] === 42)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(65);
				}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f331;}

				$this->p++;
				if (!$this->s125()) {
					goto f331;}
				goto k331;
				f331:
				$this->p = $o2;
				goto n330;
				k331:
				goto a327;
				n330:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(192);
		}

		goto f326;
		a327:
		goto k326;
		f326:
		$this->p = $o1;
		return false;
		k326:

		return true;
	}

	private function s199(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(199);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 32:
			case 82:
				// s367
				if (!isset(self::T977f027a[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(367);
					}

					goto n166;}

				$o2 = $this->p;
				if (!$this->s201()) {
					goto f167;}
				if (!$this->s368()) {
					goto f167;}
				goto k167;
				f167:
				$this->p = $o2;
				goto n166;
				k167:
				goto a165;
				n166:
				break;
			case 30:
				// s200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n168;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f169;}

				$this->p++;
				if (!$this->s201()) {
					goto f169;}
				goto k169;
				f169:
				$this->p = $o2;
				goto n168;
				k169:
				goto a165;
				n168:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(199);
		}

		goto f164;
		a165:
		goto k164;
		f164:
		$this->p = $o1;
		return false;
		k164:

		return true;
	}

	private function s201(): bool
	{
		if (!isset(self::T977f027a[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(201);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 5:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				// s366
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(366);
					}

					goto n382;}

				$o2 = $this->p;
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(332);
					}

					goto f383;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f383;}
				}
				if (!$this->s281()) {
					goto f383;}
				goto k383;
				f383:
				$this->p = $o2;
				goto n382;
				k383:
				goto a381;
				n382:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n384;}

				$o2 = $this->p;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f385;}
				}
				if (!$this->s335()) {
					goto f385;}
				goto k385;
				f385:
				$this->p = $o2;
				goto n384;
				k385:
				goto a381;
				n384:
				break;
			case 19:
			case 20:
			case 21:
			case 22:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n387;}

				$o2 = $this->p;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f388;}
				}
				if (!$this->s335()) {
					goto f388;}
				goto k388;
				f388:
				$this->p = $o2;
				goto n387;
				k388:
				goto a381;
				n387:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->s255()) {
					goto n390;}
				goto a381;
				n390:
				// s278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n391;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f392;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f392;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f392;}
				}
				if (!$this->s281()) {
					goto f392;}
				goto k392;
				f392:
				$this->p = $o2;
				goto n391;
				k392:
				goto a381;
				n391:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n393;}

				$o2 = $this->p;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f394;}
				}
				if (!$this->s335()) {
					goto f394;}
				goto k394;
				f394:
				$this->p = $o2;
				goto n393;
				k394:
				goto a381;
				n393:
				break;
			case 32:
			case 82:
				if (!$this->s202()) {
					goto n396;}
				goto a381;
				n396:
				break;
			case 6:
				if (!$this->s267()) {
					goto n397;}
				goto a381;
				n397:
				// s278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n398;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f399;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f399;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f399;}
				}
				if (!$this->s281()) {
					goto f399;}
				goto k399;
				f399:
				$this->p = $o2;
				goto n398;
				k399:
				goto a381;
				n398:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n400;}

				$o2 = $this->p;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f401;}
				}
				if (!$this->s335()) {
					goto f401;}
				goto k401;
				f401:
				$this->p = $o2;
				goto n400;
				k401:
				goto a381;
				n400:
				break;
			case 7:
				if (!$this->s230()) {
					goto n403;}
				goto a381;
				n403:
				// s278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n404;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f405;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f405;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f405;}
				}
				if (!$this->s281()) {
					goto f405;}
				goto k405;
				f405:
				$this->p = $o2;
				goto n404;
				k405:
				goto a381;
				n404:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n406;}

				$o2 = $this->p;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f407;}
				}
				if (!$this->s335()) {
					goto f407;}
				goto k407;
				f407:
				$this->p = $o2;
				goto n406;
				k407:
				goto a381;
				n406:
				break;
			case 25:
				if (!$this->s229()) {
					goto n409;}
				goto a381;
				n409:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(201);
		}

		goto f380;
		a381:
		goto k380;
		f380:
		$this->p = $o1;
		return false;
		k380:

		return true;
	}

	private function s202(): bool
	{
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(202);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f661;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e663;
			}

			$this->p++;
		}
		e663:
		if (!$this->s205()) {
			goto f661;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e665;
			}

			$this->p++;
		}
		e665:
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f661;}

		$this->p++;
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n666;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e668;
				}

				if (!$this->s224()) {
					goto e668;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e668;
				}

				$k2++;
			}
			e668:

			if ($k2 < 1) {
				goto f667;
			}
			goto k667;
			f667:
			$this->p = $o2;
			goto n666;
			k667:
			n666:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k661;
		f661:
		$this->p = $o1;
		return false;
		k661:

		return true;
	}

	private function s205(): bool
	{
		if (!isset(self::Tcd64b9c0[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(205);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 32:
			case 82:
				// s208
				if (!isset(self::T977f027a[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(208);
					}

					goto n908;}

				$o2 = $this->p;
				if (!$this->s201()) {
					goto f909;}
				if (!$this->s209()) {
					goto f909;}
				goto k909;
				f909:
				$this->p = $o2;
				goto n908;
				k909:
				goto a907;
				n908:
				break;
			case 26:
				if (!$this->s206()) {
					goto n910;}
				goto a907;
				n910:
				break;
			case 30:
				// s200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n911;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f912;}

				$this->p++;
				if (!$this->s201()) {
					goto f912;}
				goto k912;
				f912:
				$this->p = $o2;
				goto n911;
				k912:
				goto a907;
				n911:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(205);
		}

		goto f906;
		a907:
		goto k906;
		f906:
		$this->p = $o1;
		return false;
		k906:

		return true;
	}

	private function s206(): bool
	{
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p > $this->f) {
				$this->rec(206);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f1235;}

		$this->p++;
		if (!($this->ids[$this->p] === 12)) {
			if ($this->p >= $this->f) {
				$this->rec(23);
			}

			goto f1235;}

		$this->p++;
		if (($this->ids[$this->p] === 11)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->s199()) {
			goto f1235;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1237;
			}

			$this->p++;
		}
		e1237:
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
			}

			goto f1235;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1239;
			}

			$this->p++;
		}
		e1239:
		if (!$this->s199()) {
			goto f1235;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1241;
			}

			$this->p++;
		}
		e1241:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1235;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1243;
			}

			$this->p++;
		}
		e1243:
		if (!$this->s205()) {
			goto f1235;}
		goto k1235;
		f1235:
		$this->p = $o1;
		return false;
		k1235:

		return true;
	}

	private function s209(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				// s211
				$o2 = $this->p;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1227;
					}

					$this->p++;
				}
				e1227:
				if (isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if (!$this->s213()) {
						goto n1228;}
					n1228:
				} elseif ($this->p > $this->f) {
					$this->rec(213);
				}
				goto a1223;
				n1224:
				break;
			case 12:
				if (!$this->s210()) {
					goto n1229;}
				goto a1223;
				n1229:
				// s211
				$o2 = $this->p;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1233;
					}

					$this->p++;
				}
				e1233:
				if (isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if (!$this->s213()) {
						goto n1234;}
					n1234:
				} elseif ($this->p > $this->f) {
					$this->rec(213);
				}
				goto a1223;
				n1230:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(209);
		}

		goto f1222;
		a1223:
		goto k1222;
		f1222:
		$this->p = $o1;
		return false;
		k1222:

		return true;
	}

	private function s210(): bool
	{
		if (!($this->ids[$this->p] === 12)) {
			if ($this->p > $this->f) {
				$this->rec(210);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 12)) {
			if ($this->p >= $this->f) {
				$this->rec(23);
			}

			goto f1528;}

		$this->p++;
		if (($this->ids[$this->p] === 11)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->s199()) {
			goto f1528;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1530;
			}

			$this->p++;
		}
		e1530:
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
			}

			goto f1528;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1532;
			}

			$this->p++;
		}
		e1532:
		if (!$this->s199()) {
			goto f1528;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1534;
			}

			$this->p++;
		}
		e1534:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1528;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1536;
			}

			$this->p++;
		}
		e1536:
		if (!$this->s205()) {
			goto f1528;}
		goto k1528;
		f1528:
		$this->p = $o1;
		return false;
		k1528:

		return true;
	}

	private function s213(): bool
	{
		if (!isset(self::Tc8c984b6[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(213);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 28:
				// s214
				if (!($this->ids[$this->p] === 28)) {
					if ($this->p > $this->f) {
						$this->rec(214);
					}

					goto n1524;}

				$o2 = $this->p;
				if (!$this->s215()) {
					goto f1525;}
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1525;}
				}
				goto k1525;
				f1525:
				$this->p = $o2;
				goto n1524;
				k1525:
				goto a1523;
				n1524:
				break;
			case 29:
				// s218
				if (!($this->ids[$this->p] === 29)) {
					if ($this->p > $this->f) {
						$this->rec(218);
					}

					goto n1526;}

				$o2 = $this->p;
				if (!$this->s219()) {
					goto f1527;}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1527;}
				}
				goto k1527;
				f1527:
				$this->p = $o2;
				goto n1526;
				k1527:
				goto a1523;
				n1526:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(213);
		}

		goto f1522;
		a1523:
		goto k1522;
		f1522:
		$this->p = $o1;
		return false;
		k1522:

		return true;
	}

	private function s215(): bool
	{
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(215);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 28)) {
				if ($b1 > $this->f) {
					$this->rec(216);
				}

				goto e1682;
			}

			// s216
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->rec(216);
				}

				goto e1682;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p >= $this->f) {
					$this->rec(49);
				}

				goto f1683;}

			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1685;
				}

				$this->p++;
			}
			e1685:
			if (!$this->s201()) {
				goto f1683;}
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1687;
				}

				$this->p++;
			}
			e1687:
			goto k1683;
			f1683:
			$this->p = $o2;
			goto e1682;
			k1683:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1682;
			}

			$k1++;
		}
		e1682:

		if ($k1 < 1) {
			goto f1681;
		}
		goto k1681;
		f1681:
		$this->p = $o1;
		return false;
		k1681:

		return true;
	}

	private function s219(): bool
	{
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(219);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 29)) {
				if ($b1 > $this->f) {
					$this->rec(220);
				}

				goto e1689;
			}

			// s220
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->rec(220);
				}

				goto e1689;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p >= $this->f) {
					$this->rec(50);
				}

				goto f1690;}

			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1692;
				}

				$this->p++;
			}
			e1692:
			if (!$this->s201()) {
				goto f1690;}
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1694;
				}

				$this->p++;
			}
			e1694:
			goto k1690;
			f1690:
			$this->p = $o2;
			goto e1689;
			k1690:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1689;
			}

			$k1++;
		}
		e1689:

		if ($k1 < 1) {
			goto f1688;
		}
		goto k1688;
		f1688:
		$this->p = $o1;
		return false;
		k1688:

		return true;
	}

	private function s224(): bool
	{
		if (!isset(self::T885cd349[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(224);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 36:
				// s225
				if (!($this->ids[$this->p] === 36)) {
					if ($this->p > $this->f) {
						$this->rec(225);
					}

					goto n885;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 36)) {
					if ($this->p >= $this->f) {
						$this->rec(57);
					}

					goto f886;}

				$this->p++;
				if (($this->ids[$this->p] === 37)) {
					if (($this->ids[$this->p] === 37)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f886;}
				}
				if (!$this->s199()) {
					goto f886;}
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f886;}

				$this->p++;
				goto k886;
				f886:
				$this->p = $o2;
				goto n885;
				k886:
				goto a884;
				n885:
				// s227
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(227);
					}

					goto n887;}

				$o2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(228);
					}

					goto f888;}

				$this->p++;
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f888;}

				$this->p++;
				goto k888;
				f888:
				$this->p = $o2;
				goto n887;
				k888:
				goto a884;
				n887:
				break;
			case 84:
				// s227
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(227);
					}

					goto n889;}

				$o2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(228);
					}

					goto f890;}

				$this->p++;
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f890;}

				$this->p++;
				goto k890;
				f890:
				$this->p = $o2;
				goto n889;
				k890:
				goto a884;
				n889:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(224);
		}

		goto f883;
		a884:
		goto k883;
		f883:
		$this->p = $o1;
		return false;
		k883:

		return true;
	}

	private function s229(): bool
	{
		if (!($this->ids[$this->p] === 25)) {
			if ($this->p > $this->f) {
				$this->rec(229);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 25)) {
			if ($this->p >= $this->f) {
				$this->rec(46);
			}

			goto f681;}

		$this->p++;
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n682;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e684;
				}

				if (!$this->s224()) {
					goto e684;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e684;
				}

				$k2++;
			}
			e684:

			if ($k2 < 1) {
				goto f683;
			}
			goto k683;
			f683:
			$this->p = $o2;
			goto n682;
			k683:
			n682:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k681;
		f681:
		$this->p = $o1;
		return false;
		k681:

		return true;
	}

	private function s230(): bool
	{
		if (!($this->ids[$this->p] === 7)) {
			if ($this->p > $this->f) {
				$this->rec(230);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 7)) {
			if ($this->p >= $this->f) {
				$this->rec(32);
			}

			goto f677;}

		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f677;}
		}
		if (!$this->s231()) {
			goto f677;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n678;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e680;
				}

				if (!$this->s224()) {
					goto e680;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e680;
				}

				$k2++;
			}
			e680:

			if ($k2 < 1) {
				goto f679;
			}
			goto k679;
			f679:
			$this->p = $o2;
			goto n678;
			k679:
			n678:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k677;
		f677:
		$this->p = $o1;
		return false;
		k677:

		return true;
	}

	private function s231(): bool
	{
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p > $this->f) {
				$this->rec(231);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p >= $this->f) {
				$this->rec(60);
			}

			goto f920;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e922;
			}

			$this->p++;
		}
		e922:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			// s233
			if (!isset(self::T50fd39f8[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(233);
				}

				goto n923;}

			$o2 = $this->p;
			switch ($this->ids[$this->p]) {
				default:
					break;
				case 1:
				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:
				case 10:
				case 11:
				case 12:
				case 13:
				case 14:
				case 15:
				case 16:
				case 17:
				case 18:
				case 19:
				case 20:
				case 21:
				case 22:
				case 24:
				case 25:
				case 30:
				case 32:
				case 82:
					if (!$this->s243()) {
						goto n926;}
					goto a925;
					n926:
					break;
				case 42:
					if (!$this->s234()) {
						goto n927;}
					goto a925;
					n927:
					break;
			}

			if ($this->p > $this->f) {
				$this->rec(233);
			}

			goto f924;
			a925:
			goto k924;
			f924:
			$this->p = $o2;
			goto n923;
			k924:
			n923:
		} elseif ($this->p > $this->f) {
			$this->rec(233);
		}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e929;
			}

			$this->p++;
		}
		e929:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f920;}

		$this->p++;
		goto k920;
		f920:
		$this->p = $o1;
		return false;
		k920:

		return true;
	}

	private function s233(): bool
	{
		if (!isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(233);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->s243()) {
					goto n1543;}
				goto a1542;
				n1543:
				break;
			case 42:
				// s234
				if (!($this->ids[$this->p] === 42)) {
					if ($this->p > $this->f) {
						$this->rec(234);
					}

					goto n1544;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 42)) {
					if ($this->p >= $this->f) {
						$this->rec(65);
					}

					goto f1545;}

				$this->p++;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1547;
					}

					$this->p++;
				}
				e1547:
				if (!$this->s235()) {
					goto f1545;}
				if (($this->ids[$this->p] === 40)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(63);
				}
				goto k1545;
				f1545:
				$this->p = $o2;
				goto n1544;
				k1545:
				goto a1542;
				n1544:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(233);
		}

		goto f1541;
		a1542:
		goto k1541;
		f1541:
		$this->p = $o1;
		return false;
		k1541:

		return true;
	}

	private function s234(): bool
	{
		if (!($this->ids[$this->p] === 42)) {
			if ($this->p > $this->f) {
				$this->rec(234);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 42)) {
			if ($this->p >= $this->f) {
				$this->rec(65);
			}

			goto f1266;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1268;
			}

			$this->p++;
		}
		e1268:
		if (!$this->s235()) {
			goto f1266;}
		if (($this->ids[$this->p] === 40)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k1266;
		f1266:
		$this->p = $o1;
		return false;
		k1266:

		return true;
	}

	private function s235(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1550;}
				goto a1549;
				n1550:
				break;
			case 34:
			case 86:
				// s236
				if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(236);
					}

					goto n1551;}

				$o2 = $this->p;
				if (!$this->s237()) {
					goto f1552;}
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1554;
					}

					$this->p++;
				}
				e1554:
				goto k1552;
				f1552:
				$this->p = $o2;
				goto n1551;
				k1552:
				goto a1549;
				n1551:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1555;}
				goto a1549;
				n1555:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(235);
		}

		goto f1548;
		a1549:
		goto k1548;
		f1548:
		$this->p = $o1;
		return false;
		k1548:

		return true;
	}

	private function s237(): bool
	{
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(237);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(238);
			}

			goto f1695;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1697;
			}

			$this->p++;
		}
		e1697:
		if (!$this->s199()) {
			goto f1695;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1699;
			}

			$this->p++;
		}
		e1699:
		if (($this->ids[$this->p] === 40)) {
			// s240
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(240);
				}

				goto n1700;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1701;}

			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1703;
				}

				$this->p++;
			}
			e1703:
			if (!$this->s199()) {
				goto f1701;}
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1705;
				}

				$this->p++;
			}
			e1705:
			goto k1701;
			f1701:
			$this->p = $o2;
			goto n1700;
			k1701:
			n1700:
		} elseif ($this->p > $this->f) {
			$this->rec(240);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1695;}

		$this->p++;
		goto k1695;
		f1695:
		$this->p = $o1;
		return false;
		k1695:

		return true;
	}

	private function s243(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(243);
			}

			return false;}

		$o1 = $this->p;
		// s244
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(244);
			}

			goto f1253;}

		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 20:
			case 21:
			case 22:
			case 24:
				if (!$this->s245()) {
					goto n1256;}
				goto a1255;
				n1256:
				if (!$this->s199()) {
					goto n1257;}
				goto a1255;
				n1257:
				break;
			case 19:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->s199()) {
					goto n1258;}
				goto a1255;
				n1258:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(244);
		}

		goto f1254;
		a1255:
		goto k1254;
		f1254:
		$this->p = $o2;
		goto f1253;
		k1254:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1260;
			}

			$this->p++;
		}
		e1260:
		if (($this->ids[$this->p] === 40)) {
			// s252
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(252);
				}

				goto n1261;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1262;}

			$this->p++;
			if (($this->ids[$this->p] === 41)) {
				$this->p++;
			} elseif ($this->p >= $this->f) {
				$this->rec(254);
			}
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1264;
				}

				$this->p++;
			}
			e1264:
			if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
				if (!$this->s233()) {
					goto n1265;}
				n1265:
			} elseif ($this->p > $this->f) {
				$this->rec(233);
			}
			goto k1262;
			f1262:
			$this->p = $o2;
			goto n1261;
			k1262:
			n1261:
		} elseif ($this->p > $this->f) {
			$this->rec(252);
		}
		goto k1253;
		f1253:
		$this->p = $o1;
		return false;
		k1253:

		return true;
	}

	private function s245(): bool
	{
		if (!isset(self::Tce797685[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(245);
			}

			return false;}

		$o1 = $this->p;
		// s246
		if (!isset(self::Tce797685[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(246);
			}

			goto f1503;}

		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				if (!$this->s247()) {
					goto n1506;}
				goto a1505;
				n1506:
				break;
			case 20:
				$this->p++;
				goto a1505;
				break;
			case 21:
				$this->p++;
				goto a1505;
				break;
			case 22:
				$this->p++;
				goto a1505;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(246);
		}

		goto f1504;
		a1505:
		goto k1504;
		f1504:
		$this->p = $o2;
		goto f1503;
		k1504:
		if (($this->ids[$this->p] === 30)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(51);
		}
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1503;}

		$this->p++;
		if (!$this->s199()) {
			goto f1503;}
		goto k1503;
		f1503:
		$this->p = $o1;
		return false;
		k1503:

		return true;
	}

	private function s247(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(247);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1673;}

		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			// s249
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->rec(249);
				}

				goto n1674;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p >= $this->f) {
					$this->rec(66);
				}

				goto f1675;}

			$this->p++;
			if (!isset(self::T83e30518[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(144);
				}

				goto f1675;}

			$this->p++;
			goto k1675;
			f1675:
			$this->p = $o2;
			goto n1674;
			k1675:
			n1674:
		} elseif ($this->p > $this->f) {
			$this->rec(249);
		}
		goto k1673;
		f1673:
		$this->p = $o1;
		return false;
		k1673:

		return true;
	}

	private function s255(): bool
	{
		if (!isset(self::Ta8d79561[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(255);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Ta8d79561[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(256);
			}

			goto f657;}

		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f657;}
		}
		if (!$this->s257()) {
			goto f657;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n658;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e660;
				}

				if (!$this->s224()) {
					goto e660;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e660;
				}

				$k2++;
			}
			e660:

			if ($k2 < 1) {
				goto f659;
			}
			goto k659;
			f659:
			$this->p = $o2;
			goto n658;
			k659:
			n658:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k657;
		f657:
		$this->p = $o1;
		return false;
		k657:

		return true;
	}

	private function s257(): bool
	{
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p > $this->f) {
				$this->rec(257);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p >= $this->f) {
				$this->rec(60);
			}

			goto f896;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e898;
			}

			$this->p++;
		}
		e898:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			// s259
			if (!isset(self::T50fd39f8[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(259);
				}

				goto n899;}

			$o2 = $this->p;
			switch ($this->ids[$this->p]) {
				default:
					break;
				case 1:
				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:
				case 10:
				case 11:
				case 12:
				case 13:
				case 14:
				case 15:
				case 16:
				case 17:
				case 18:
				case 19:
				case 20:
				case 21:
				case 22:
				case 24:
				case 25:
				case 30:
				case 32:
				case 82:
					if (!$this->s264()) {
						goto n902;}
					goto a901;
					n902:
					break;
				case 42:
					if (!$this->s260()) {
						goto n903;}
					goto a901;
					n903:
					break;
			}

			if ($this->p > $this->f) {
				$this->rec(259);
			}

			goto f900;
			a901:
			goto k900;
			f900:
			$this->p = $o2;
			goto n899;
			k900:
			n899:
		} elseif ($this->p > $this->f) {
			$this->rec(259);
		}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e905;
			}

			$this->p++;
		}
		e905:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f896;}

		$this->p++;
		goto k896;
		f896:
		$this->p = $o1;
		return false;
		k896:

		return true;
	}

	private function s259(): bool
	{
		if (!isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(259);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->s264()) {
					goto n1509;}
				goto a1508;
				n1509:
				break;
			case 42:
				// s260
				if (!($this->ids[$this->p] === 42)) {
					if ($this->p > $this->f) {
						$this->rec(260);
					}

					goto n1510;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 42)) {
					if ($this->p >= $this->f) {
						$this->rec(65);
					}

					goto f1511;}

				$this->p++;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1513;
					}

					$this->p++;
				}
				e1513:
				if (!$this->s261()) {
					goto f1511;}
				if (($this->ids[$this->p] === 40)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(63);
				}
				goto k1511;
				f1511:
				$this->p = $o2;
				goto n1510;
				k1511:
				goto a1508;
				n1510:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(259);
		}

		goto f1507;
		a1508:
		goto k1507;
		f1507:
		$this->p = $o1;
		return false;
		k1507:

		return true;
	}

	private function s260(): bool
	{
		if (!($this->ids[$this->p] === 42)) {
			if ($this->p > $this->f) {
				$this->rec(260);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 42)) {
			if ($this->p >= $this->f) {
				$this->rec(65);
			}

			goto f1219;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1221;
			}

			$this->p++;
		}
		e1221:
		if (!$this->s261()) {
			goto f1219;}
		if (($this->ids[$this->p] === 40)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k1219;
		f1219:
		$this->p = $o1;
		return false;
		k1219:

		return true;
	}

	private function s261(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1516;}
				goto a1515;
				n1516:
				break;
			case 34:
			case 86:
				// s262
				if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(262);
					}

					goto n1517;}

				$o2 = $this->p;
				if (!$this->s263()) {
					goto f1518;}
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1520;
					}

					$this->p++;
				}
				e1520:
				goto k1518;
				f1518:
				$this->p = $o2;
				goto n1517;
				k1518:
				goto a1515;
				n1517:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1521;}
				goto a1515;
				n1521:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(261);
		}

		goto f1514;
		a1515:
		goto k1514;
		f1514:
		$this->p = $o1;
		return false;
		k1514:

		return true;
	}

	private function s263(): bool
	{
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(263);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(238);
			}

			goto f1676;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1678;
			}

			$this->p++;
		}
		e1678:
		if (!$this->s199()) {
			goto f1676;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1680;
			}

			$this->p++;
		}
		e1680:
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1676;}

		$this->p++;
		goto k1676;
		f1676:
		$this->p = $o1;
		return false;
		k1676:

		return true;
	}

	private function s264(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(264);
			}

			return false;}

		$o1 = $this->p;
		// s244
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(244);
			}

			goto f1206;}

		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 20:
			case 21:
			case 22:
			case 24:
				if (!$this->s245()) {
					goto n1209;}
				goto a1208;
				n1209:
				if (!$this->s199()) {
					goto n1210;}
				goto a1208;
				n1210:
				break;
			case 19:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->s199()) {
					goto n1211;}
				goto a1208;
				n1211:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(244);
		}

		goto f1207;
		a1208:
		goto k1207;
		f1207:
		$this->p = $o2;
		goto f1206;
		k1207:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1213;
			}

			$this->p++;
		}
		e1213:
		if (($this->ids[$this->p] === 40)) {
			// s266
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(266);
				}

				goto n1214;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1215;}

			$this->p++;
			if (($this->ids[$this->p] === 41)) {
				$this->p++;
			} elseif ($this->p >= $this->f) {
				$this->rec(254);
			}
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1217;
				}

				$this->p++;
			}
			e1217:
			if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
				if (!$this->s259()) {
					goto n1218;}
				n1218:
			} elseif ($this->p > $this->f) {
				$this->rec(259);
			}
			goto k1215;
			f1215:
			$this->p = $o2;
			goto n1214;
			k1215:
			n1214:
		} elseif ($this->p > $this->f) {
			$this->rec(266);
		}
		goto k1206;
		f1206:
		$this->p = $o1;
		return false;
		k1206:

		return true;
	}

	private function s267(): bool
	{
		if (!($this->ids[$this->p] === 6)) {
			if ($this->p > $this->f) {
				$this->rec(267);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 6)) {
			if ($this->p >= $this->f) {
				$this->rec(37);
			}

			goto f669;}

		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f669;}
		}
		// s268
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p > $this->f) {
				$this->rec(268);
			}

			goto f669;}

		$o2 = $this->p;
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p >= $this->f) {
				$this->rec(60);
			}

			goto f670;}

		$this->p++;
		// s203
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b3 > $this->f) {
					$this->rec(204);
				}

				goto e672;
			}

			$this->p++;
		}
		e672:
		if (isset(self::Td8017923[$this->ids[$this->p]])) {
			if (!$this->s270()) {
				goto n673;}
			n673:
		} elseif ($this->p > $this->f) {
			$this->rec(270);
		}
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f670;}

		$this->p++;
		goto k670;
		f670:
		$this->p = $o2;
		goto f669;
		k670:
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n674;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e676;
				}

				if (!$this->s224()) {
					goto e676;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e676;
				}

				$k2++;
			}
			e676:

			if ($k2 < 1) {
				goto f675;
			}
			goto k675;
			f675:
			$this->p = $o2;
			goto n674;
			k675:
			n674:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k669;
		f669:
		$this->p = $o1;
		return false;
		k669:

		return true;
	}

	private function s270(): bool
	{
		if (!isset(self::Td8017923[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(270);
			}

			return false;}

		$o1 = $this->p;
		// s271
		if (!isset(self::Td8017923[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(271);
			}

			goto f913;}

		$o2 = $this->p;
		if (!$this->s272()) {
			goto f914;}
		if (($this->ids[$this->p] === 30)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(51);
		}
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f914;}

		$this->p++;
		if (!$this->s199()) {
			goto f914;}
		goto k914;
		f914:
		$this->p = $o2;
		goto f913;
		k914:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e916;
			}

			$this->p++;
		}
		e916:
		if (($this->ids[$this->p] === 40)) {
			// s274
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(274);
				}

				goto n917;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(275);
					}

					goto e919;
				}

				if (!$this->s275()) {
					goto e919;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e919;
				}

				$k2++;
			}
			e919:

			if ($k2 < 1) {
				goto f918;
			}
			goto k918;
			f918:
			$this->p = $o2;
			goto n917;
			k918:
			n917:
		} elseif ($this->p > $this->f) {
			$this->rec(274);
		}
		goto k913;
		f913:
		$this->p = $o1;
		return false;
		k913:

		return true;
	}

	private function s272(): bool
	{
		if (!isset(self::Td8017923[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(272);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
				$this->p++;
				goto a1245;
				break;
			case 2:
				$this->p++;
				goto a1245;
				break;
			case 3:
				$this->p++;
				goto a1245;
				break;
			case 4:
				$this->p++;
				goto a1245;
				break;
			case 5:
				$this->p++;
				goto a1245;
				break;
			case 6:
				$this->p++;
				goto a1245;
				break;
			case 7:
				$this->p++;
				goto a1245;
				break;
			case 8:
				$this->p++;
				goto a1245;
				break;
			case 9:
				$this->p++;
				goto a1245;
				break;
			case 10:
				$this->p++;
				goto a1245;
				break;
			case 11:
				$this->p++;
				goto a1245;
				break;
			case 12:
				$this->p++;
				goto a1245;
				break;
			case 13:
				$this->p++;
				goto a1245;
				break;
			case 14:
				$this->p++;
				goto a1245;
				break;
			case 15:
				$this->p++;
				goto a1245;
				break;
			case 16:
				$this->p++;
				goto a1245;
				break;
			case 17:
				$this->p++;
				goto a1245;
				break;
			case 18:
				$this->p++;
				goto a1245;
				break;
			case 21:
				$this->p++;
				goto a1245;
				break;
			case 22:
				$this->p++;
				goto a1245;
				break;
			case 24:
				$this->p++;
				goto a1245;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(272);
		}

		goto f1244;
		a1245:
		goto k1244;
		f1244:
		$this->p = $o1;
		return false;
		k1244:

		return true;
	}

	private function s275(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(275);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p >= $this->f) {
				$this->rec(63);
			}

			goto f1246;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1248;
			}

			$this->p++;
		}
		e1248:
		// s276
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 21:
			case 22:
			case 24:
				if (!$this->s277()) {
					goto n1251;}
				goto a1250;
				n1251:
				break;
			case 39:
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p > $this->f) {
						$this->rec(62);
					}

					goto n1252;}
				goto a1250;
				n1252:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(276);
		}

		goto f1249;
		a1250:
		goto k1249;
		f1249:
		$this->p = $o2;
		goto f1246;
		k1249:
		goto k1246;
		f1246:
		$this->p = $o1;
		return false;
		k1246:

		return true;
	}

	private function s277(): bool
	{
		if (!isset(self::Td8017923[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(277);
			}

			return false;}

		$o1 = $this->p;
		// s271
		if (!isset(self::Td8017923[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(271);
			}

			goto f1537;}

		$o2 = $this->p;
		if (!$this->s272()) {
			goto f1538;}
		if (($this->ids[$this->p] === 30)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(51);
		}
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1538;}

		$this->p++;
		if (!$this->s199()) {
			goto f1538;}
		goto k1538;
		f1538:
		$this->p = $o2;
		goto f1537;
		k1538:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1540;
			}

			$this->p++;
		}
		e1540:
		goto k1537;
		f1537:
		$this->p = $o1;
		return false;
		k1537:

		return true;
	}

	private function s281(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n643;}
				goto a642;
				n643:
				break;
			case 32:
			case 82:
				if (!$this->s364()) {
					goto n644;}
				goto a642;
				n644:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n645;}
				goto a642;
				n645:
				break;
			case 36:
			case 84:
				// s223
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(223);
					}

					goto n646;}

				$o2 = $this->p;
				$k2 = 0;
				while (true) {
					$b2 = $this->p;
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($b2 > $this->f) {
							$this->rec(224);
						}

						goto e648;
					}

					if (!$this->s224()) {
						goto e648;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e648;
					}

					$k2++;
				}
				e648:

				if ($k2 < 1) {
					goto f647;
				}
				goto k647;
				f647:
				$this->p = $o2;
				goto n646;
				k647:
				goto a642;
				n646:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n649;}
				goto a642;
				n649:
				break;
			case 34:
				if (!$this->s282()) {
					goto n650;}
				goto a642;
				n650:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n651;}
				goto a642;
				n651:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(281);
		}

		goto f641;
		a642:
		goto k641;
		f641:
		$this->p = $o1;
		return false;
		k641:

		return true;
	}

	private function s282(): bool
	{
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(282);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(54);
			}

			goto f891;}
		// s284
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(284);
			}

			goto f891;}

		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 34:
				if (!$this->s285()) {
					goto n894;}
				goto a893;
				n894:
				if (!$this->s362()) {
					goto n895;}
				goto a893;
				n895:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(284);
		}

		goto f892;
		a893:
		goto k892;
		f892:
		$this->p = $o2;
		goto f891;
		k892:
		goto k891;
		f891:
		$this->p = $o1;
		return false;
		k891:

		return true;
	}

	private function s285(): bool
	{
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(285);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p >= $this->f) {
				$this->rec(54);
			}

			goto f1190;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1192;
			}

			$this->p++;
		}
		e1192:
		// s286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f1190;}

		$o2 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1193;}

		$this->p++;
		if (!$this->s287()) {
			goto f1193;}
		if (!$this->s293()) {
			goto f1193;}
		if (!$this->s296()) {
			goto f1193;}
		goto k1193;
		f1193:
		$this->p = $o2;
		goto f1190;
		k1193:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1195;
			}

			$this->p++;
		}
		e1195:
		if (($this->ids[$this->p] === 40)) {
			// s300
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(300);
				}

				goto n1196;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(301);
					}

					goto e1198;
				}

				if (!$this->s301()) {
					goto e1198;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1198;
				}

				$k2++;
			}
			e1198:

			if ($k2 < 1) {
				goto f1197;
			}
			goto k1197;
			f1197:
			$this->p = $o2;
			goto n1196;
			k1197:
			n1196:
		} elseif ($this->p > $this->f) {
			$this->rec(300);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1190;}

		$this->p++;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f1190;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1200;
			}

			$this->p++;
		}
		e1200:
		if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if (!$this->s306()) {
				goto n1201;}
			n1201:
		} elseif ($this->p > $this->f) {
			$this->rec(306);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1190;}

		$this->p++;
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1190;}

		$this->p++;
		if (!$this->s315()) {
			goto f1190;}
		goto k1190;
		f1190:
		$this->p = $o1;
		return false;
		k1190:

		return true;
	}

	private function s286(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1613;}

		$this->p++;
		if (!$this->s287()) {
			goto f1613;}
		if (!$this->s293()) {
			goto f1613;}
		if (!$this->s296()) {
			goto f1613;}
		goto k1613;
		f1613:
		$this->p = $o1;
		return false;
		k1613:

		return true;
	}

	private function s287(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				// s290
				$o2 = $this->p;
				if (($this->ids[$this->p] === 13)) {
					if (($this->ids[$this->p] === 13)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f562;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f562;}
				}
				goto k562;
				f562:
				$this->p = $o2;
				goto n561;
				k562:
				goto a560;
				n561:
				break;
			case 13:
				// s288
				if (!($this->ids[$this->p] === 13)) {
					if ($this->p > $this->f) {
						$this->rec(288);
					}

					goto n563;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 13)) {
					if ($this->p >= $this->f) {
						$this->rec(27);
					}

					goto f564;}

				$this->p++;
				if (!$this->s199()) {
					goto f564;}
				goto k564;
				f564:
				$this->p = $o2;
				goto n563;
				k564:
				goto a560;
				n563:
				// s290
				$o2 = $this->p;
				if (($this->ids[$this->p] === 13)) {
					if (($this->ids[$this->p] === 13)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f566;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f566;}
				}
				goto k566;
				f566:
				$this->p = $o2;
				goto n565;
				k566:
				goto a560;
				n565:
				break;
			case 14:
				// s289
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->rec(289);
					}

					goto n567;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p >= $this->f) {
						$this->rec(28);
					}

					goto f568;}

				$this->p++;
				if (!$this->s199()) {
					goto f568;}
				goto k568;
				f568:
				$this->p = $o2;
				goto n567;
				k568:
				goto a560;
				n567:
				// s290
				$o2 = $this->p;
				if (($this->ids[$this->p] === 13)) {
					if (($this->ids[$this->p] === 13)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f570;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f570;}
				}
				goto k570;
				f570:
				$this->p = $o2;
				goto n569;
				k570:
				goto a560;
				n569:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(287);
		}

		goto f559;
		a560:
		goto k559;
		f559:
		$this->p = $o1;
		return false;
		k559:

		return true;
	}

	private function s293(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n573;}
				goto a572;
				n573:
				break;
			case 5:
				// s294
				if (!($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->rec(294);
					}

					goto n574;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 5)) {
					if ($this->p >= $this->f) {
						$this->rec(29);
					}

					goto f575;}

				$this->p++;
				if (!$this->s199()) {
					goto f575;}
				goto k575;
				f575:
				$this->p = $o2;
				goto n574;
				k575:
				goto a572;
				n574:
				if (($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n576;}
				goto a572;
				n576:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(293);
		}

		goto f571;
		a572:
		goto k571;
		f571:
		$this->p = $o1;
		return false;
		k571:

		return true;
	}

	private function s296(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n579;}
				goto a578;
				n579:
				break;
			case 46:
				// s297
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->rec(297);
					}

					goto n580;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p >= $this->f) {
						$this->rec(69);
					}

					goto f581;}

				$this->p++;
				if (!$this->s199()) {
					goto f581;}
				goto k581;
				f581:
				$this->p = $o2;
				goto n580;
				k581:
				goto a578;
				n580:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n582;}
				goto a578;
				n582:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(296);
		}

		goto f577;
		a578:
		goto k577;
		f577:
		$this->p = $o1;
		return false;
		k577:

		return true;
	}

	private function s301(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(301);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p >= $this->f) {
				$this->rec(63);
			}

			goto f1490;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1492;
			}

			$this->p++;
		}
		e1492:
		// s302
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				if (!$this->s304()) {
					goto n1495;}
				goto a1494;
				n1495:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n1496;}
				goto a1494;
				n1496:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(302);
		}

		goto f1493;
		a1494:
		goto k1493;
		f1493:
		$this->p = $o2;
		goto f1490;
		k1493:
		goto k1490;
		f1490:
		$this->p = $o1;
		return false;
		k1490:

		return true;
	}

	private function s304(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(304);
			}

			return false;}

		$o1 = $this->p;
		// s286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f1664;}

		$o2 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1665;}

		$this->p++;
		if (!$this->s287()) {
			goto f1665;}
		if (!$this->s293()) {
			goto f1665;}
		if (!$this->s296()) {
			goto f1665;}
		goto k1665;
		f1665:
		$this->p = $o2;
		goto f1664;
		k1665:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1667;
			}

			$this->p++;
		}
		e1667:
		goto k1664;
		f1664:
		$this->p = $o1;
		return false;
		k1664:

		return true;
	}

	private function s306(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(306);
			}

			return false;}

		$o1 = $this->p;
		// s307
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(307);
			}

			goto f1151;}

		$o2 = $this->p;
		if (!$this->s199()) {
			goto f1152;}
		if (($this->ids[$this->p] === 27)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(48);
		}
		if (($this->ids[$this->p] === 42)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(65);
		}
		if (($this->ids[$this->p] === 26)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(47);
		}
		if (($this->ids[$this->p] === 46)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(69);
		}
		goto k1152;
		f1152:
		$this->p = $o2;
		goto f1151;
		k1152:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1154;
			}

			$this->p++;
		}
		e1154:
		if (($this->ids[$this->p] === 40)) {
			// s311
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(311);
				}

				goto n1155;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(312);
					}

					goto e1157;
				}

				if (!$this->s312()) {
					goto e1157;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1157;
				}

				$k2++;
			}
			e1157:

			if ($k2 < 1) {
				goto f1156;
			}
			goto k1156;
			f1156:
			$this->p = $o2;
			goto n1155;
			k1156:
			n1155:
		} elseif ($this->p > $this->f) {
			$this->rec(311);
		}
		goto k1151;
		f1151:
		$this->p = $o1;
		return false;
		k1151:

		return true;
	}

	private function s312(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(312);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p >= $this->f) {
				$this->rec(63);
			}

			goto f1470;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1472;
			}

			$this->p++;
		}
		e1472:
		// s313
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->s314()) {
					goto n1475;}
				goto a1474;
				n1475:
				break;
			case 33:
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p > $this->f) {
						$this->rec(53);
					}

					goto n1476;}
				goto a1474;
				n1476:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(313);
		}

		goto f1473;
		a1474:
		goto k1473;
		f1473:
		$this->p = $o2;
		goto f1470;
		k1473:
		goto k1470;
		f1470:
		$this->p = $o1;
		return false;
		k1470:

		return true;
	}

	private function s314(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(314);
			}

			return false;}

		$o1 = $this->p;
		// s307
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(307);
			}

			goto f1656;}

		$o2 = $this->p;
		if (!$this->s199()) {
			goto f1657;}
		if (($this->ids[$this->p] === 27)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(48);
		}
		if (($this->ids[$this->p] === 42)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(65);
		}
		if (($this->ids[$this->p] === 26)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(47);
		}
		if (($this->ids[$this->p] === 46)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(69);
		}
		goto k1657;
		f1657:
		$this->p = $o2;
		goto f1656;
		k1657:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1659;
			}

			$this->p++;
		}
		e1659:
		goto k1656;
		f1656:
		$this->p = $o1;
		return false;
		k1656:

		return true;
	}

	private function s315(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(315);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 5:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				// s331
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(331);
					}

					goto n1160;}

				$o2 = $this->p;
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(332);
					}

					goto f1161;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1161;}
				}
				if (!$this->s318()) {
					goto f1161;}
				goto k1161;
				f1161:
				$this->p = $o2;
				goto n1160;
				k1161:
				goto a1159;
				n1160:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1162;}

				$o2 = $this->p;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1163;}
				}
				if (!$this->s335()) {
					goto f1163;}
				goto k1163;
				f1163:
				$this->p = $o2;
				goto n1162;
				k1163:
				goto a1159;
				n1162:
				break;
			case 19:
			case 20:
			case 21:
			case 22:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1165;}

				$o2 = $this->p;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1166;}
				}
				if (!$this->s335()) {
					goto f1166;}
				goto k1166;
				f1166:
				$this->p = $o2;
				goto n1165;
				k1166:
				goto a1159;
				n1165:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->s255()) {
					goto n1168;}
				goto a1159;
				n1168:
				// s317
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(317);
					}

					goto n1169;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f1170;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1170;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1170;}
				}
				if (!$this->s318()) {
					goto f1170;}
				goto k1170;
				f1170:
				$this->p = $o2;
				goto n1169;
				k1170:
				goto a1159;
				n1169:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1171;}

				$o2 = $this->p;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1172;}
				}
				if (!$this->s335()) {
					goto f1172;}
				goto k1172;
				f1172:
				$this->p = $o2;
				goto n1171;
				k1172:
				goto a1159;
				n1171:
				break;
			case 32:
			case 82:
				if (!$this->s316()) {
					goto n1174;}
				goto a1159;
				n1174:
				break;
			case 6:
				if (!$this->s267()) {
					goto n1175;}
				goto a1159;
				n1175:
				// s317
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(317);
					}

					goto n1176;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f1177;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1177;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1177;}
				}
				if (!$this->s318()) {
					goto f1177;}
				goto k1177;
				f1177:
				$this->p = $o2;
				goto n1176;
				k1177:
				goto a1159;
				n1176:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1178;}

				$o2 = $this->p;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1179;}
				}
				if (!$this->s335()) {
					goto f1179;}
				goto k1179;
				f1179:
				$this->p = $o2;
				goto n1178;
				k1179:
				goto a1159;
				n1178:
				break;
			case 7:
				if (!$this->s230()) {
					goto n1181;}
				goto a1159;
				n1181:
				// s317
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(317);
					}

					goto n1182;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f1183;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1183;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1183;}
				}
				if (!$this->s318()) {
					goto f1183;}
				goto k1183;
				f1183:
				$this->p = $o2;
				goto n1182;
				k1183:
				goto a1159;
				n1182:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1184;}

				$o2 = $this->p;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s169();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1185;}
				}
				if (!$this->s335()) {
					goto f1185;}
				goto k1185;
				f1185:
				$this->p = $o2;
				goto n1184;
				k1185:
				goto a1159;
				n1184:
				break;
			case 25:
				if (!$this->s229()) {
					goto n1187;}
				goto a1159;
				n1187:
				break;
			case 30:
				// s200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n1188;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f1189;}

				$this->p++;
				if (!$this->s201()) {
					goto f1189;}
				goto k1189;
				f1189:
				$this->p = $o2;
				goto n1188;
				k1189:
				goto a1159;
				n1188:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(315);
		}

		goto f1158;
		a1159:
		goto k1158;
		f1158:
		$this->p = $o1;
		return false;
		k1158:

		return true;
	}

	private function s316(): bool
	{
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(316);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f1486;}

		$this->p++;
		if (!$this->s205()) {
			goto f1486;}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1486;}

		$this->p++;
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1487;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e1489;
				}

				if (!$this->s224()) {
					goto e1489;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1489;
				}

				$k2++;
			}
			e1489:

			if ($k2 < 1) {
				goto f1488;
			}
			goto k1488;
			f1488:
			$this->p = $o2;
			goto n1487;
			k1488:
			n1487:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1486;
		f1486:
		$this->p = $o1;
		return false;
		k1486:

		return true;
	}

	private function s318(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1479;}
				goto a1478;
				n1479:
				break;
			case 34:
			case 86:
				if (!$this->s319()) {
					goto n1480;}
				goto a1478;
				n1480:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1481;}
				goto a1478;
				n1481:
				break;
			case 36:
			case 84:
				// s223
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(223);
					}

					goto n1482;}

				$o2 = $this->p;
				$k2 = 0;
				while (true) {
					$b2 = $this->p;
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($b2 > $this->f) {
							$this->rec(224);
						}

						goto e1484;
					}

					if (!$this->s224()) {
						goto e1484;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e1484;
					}

					$k2++;
				}
				e1484:

				if ($k2 < 1) {
					goto f1483;
				}
				goto k1483;
				f1483:
				$this->p = $o2;
				goto n1482;
				k1483:
				goto a1478;
				n1482:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1485;}
				goto a1478;
				n1485:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(318);
		}

		goto f1477;
		a1478:
		goto k1477;
		f1477:
		$this->p = $o1;
		return false;
		k1477:

		return true;
	}

	private function s319(): bool
	{
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(319);
			}

			return false;}

		$o1 = $this->p;
		if (!$this->s320()) {
			goto f1660;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1661;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e1663;
				}

				if (!$this->s224()) {
					goto e1663;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1663;
				}

				$k2++;
			}
			e1663:

			if ($k2 < 1) {
				goto f1662;
			}
			goto k1662;
			f1662:
			$this->p = $o2;
			goto n1661;
			k1662:
			n1661:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1660;
		f1660:
		$this->p = $o1;
		return false;
		k1660:

		return true;
	}

	private function s320(): bool
	{
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(320);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(238);
			}

			goto f583;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e585;
			}

			$this->p++;
		}
		e585:
		if (!$this->s321()) {
			goto f583;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e587;
			}

			$this->p++;
		}
		e587:
		if (($this->ids[$this->p] === 40)) {
			// s327
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(327);
				}

				goto n588;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(328);
					}

					goto e590;
				}

				if (!$this->s328()) {
					goto e590;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e590;
				}

				$k2++;
			}
			e590:

			if ($k2 < 1) {
				goto f589;
			}
			goto k589;
			f589:
			$this->p = $o2;
			goto n588;
			k589:
			n588:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f583;}

		$this->p++;
		goto k583;
		f583:
		$this->p = $o1;
		return false;
		k583:

		return true;
	}

	private function s321(): bool
	{
		if (!isset(self::Tb3cd2a63[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(321);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				// s324
				if (!isset(self::Tfc0ac6dd[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(324);
					}

					goto n799;}

				$o2 = $this->p;
				if (isset(self::T05cf281c[$this->ids[$this->p]])) {
					if (isset(self::T05cf281c[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f800;}
				}
				if (!$this->s199()) {
					goto f800;}
				goto k800;
				f800:
				$this->p = $o2;
				goto n799;
				k800:
				goto a798;
				n799:
				break;
			case 1:
			case 2:
				// s322
				if (!isset(self::T05cf281c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(322);
					}

					goto n801;}

				$o2 = $this->p;
				if (!isset(self::T05cf281c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(323);
					}

					goto f802;}

				$this->p++;
				if (!$this->s199()) {
					goto f802;}
				goto k802;
				f802:
				$this->p = $o2;
				goto n801;
				k802:
				goto a798;
				n801:
				break;
			case 50:
				$this->p++;
				goto a798;
				break;
			case 85:
				$this->p++;
				goto a798;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(321);
		}

		goto f797;
		a798:
		goto k797;
		f797:
		$this->p = $o1;
		return false;
		k797:

		return true;
	}

	private function s327(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(327);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(328);
				}

				goto e1669;
			}

			// s328
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(328);
				}

				goto e1669;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1670;}

			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1672;
				}

				$this->p++;
			}
			e1672:
			if (!$this->s329()) {
				goto f1670;}
			goto k1670;
			f1670:
			$this->p = $o2;
			goto e1669;
			k1670:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1669;
			}

			$k1++;
		}
		e1669:

		if ($k1 < 1) {
			goto f1668;
		}
		goto k1668;
		f1668:
		$this->p = $o1;
		return false;
		k1668:

		return true;
	}

	private function s328(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(328);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p >= $this->f) {
				$this->rec(63);
			}

			goto f803;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e805;
			}

			$this->p++;
		}
		e805:
		// s329
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 50:
			case 82:
			case 85:
				if (!$this->s330()) {
					goto n808;}
				goto a807;
				n808:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n809;}
				goto a807;
				n809:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(329);
		}

		goto f806;
		a807:
		goto k806;
		f806:
		$this->p = $o2;
		goto f803;
		k806:
		goto k803;
		f803:
		$this->p = $o1;
		return false;
		k803:

		return true;
	}

	private function s329(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 50:
			case 82:
			case 85:
				// s330
				if (!isset(self::Tb3cd2a63[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(330);
					}

					goto n1744;}

				$o2 = $this->p;
				if (!$this->s321()) {
					goto f1745;}
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1747;
					}

					$this->p++;
				}
				e1747:
				goto k1745;
				f1745:
				$this->p = $o2;
				goto n1744;
				k1745:
				goto a1743;
				n1744:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n1748;}
				goto a1743;
				n1748:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(329);
		}

		goto f1742;
		a1743:
		goto k1742;
		f1742:
		$this->p = $o1;
		return false;
		k1742:

		return true;
	}

	private function s330(): bool
	{
		if (!isset(self::Tb3cd2a63[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(330);
			}

			return false;}

		$o1 = $this->p;
		if (!$this->s321()) {
			goto f1035;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1037;
			}

			$this->p++;
		}
		e1037:
		goto k1035;
		f1035:
		$this->p = $o1;
		return false;
		k1035:

		return true;
	}

	private function s335(): bool
	{
		if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(335);
			}

			return false;}

		$o1 = $this->p;
		if (isset(self::T1e81050f[$this->ids[$this->p]])) {
			$sf = $this->f;
			$se = $this->er;
			$sp = $this->p;
			$matched = $this->s337();
			$this->f = $sf;
			$this->er = $se;
			$this->p = $sp;
			if ($matched) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f652;}
		}
		if (!$this->s340()) {
			goto f652;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n654;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e656;
				}

				if (!$this->s224()) {
					goto e656;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e656;
				}

				$k2++;
			}
			e656:

			if ($k2 < 1) {
				goto f655;
			}
			goto k655;
			f655:
			$this->p = $o2;
			goto n654;
			k655:
			n654:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k652;
		f652:
		$this->p = $o1;
		return false;
		k652:

		return true;
	}

	private function s337(): bool
	{
		if (!isset(self::T1e81050f[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(337);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 7:
			case 18:
				// s338
				if (!isset(self::T1ad6c327[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(338);
					}

					goto n844;}

				$o2 = $this->p;
				if (!isset(self::T1ad6c327[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(339);
					}

					goto f845;}

				$this->p++;
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(16);
					}

					goto f845;}

				$this->p++;
				goto k845;
				f845:
				$this->p = $o2;
				goto n844;
				k845:
				goto a843;
				n844:
				break;
			case 36:
				$this->p++;
				goto a843;
				break;
			case 84:
				$this->p++;
				goto a843;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(337);
		}

		goto f842;
		a843:
		goto k842;
		f842:
		$this->p = $o1;
		return false;
		k842:

		return true;
	}

	private function s340(): bool
	{
		if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(340);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 24:
				// s357
				if (!isset(self::Td08a6883[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(357);
					}

					goto n178;}

				$o2 = $this->p;
				if (isset(self::T1ad6c327[$this->ids[$this->p]])) {
					if (isset(self::T1ad6c327[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f179;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f179;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (!$this->s360()) {
						goto n180;}
					n180:
				} elseif ($this->p > $this->f) {
					$this->rec(360);
				}
				goto k179;
				f179:
				$this->p = $o2;
				goto n178;
				k179:
				goto a177;
				n178:
				break;
			case 7:
			case 18:
				if (!$this->s347()) {
					goto n181;}
				goto a177;
				n181:
				break;
			case 21:
			case 22:
				// s343
				if (!isset(self::T24d0f25b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(343);
					}

					goto n182;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 21:
						$this->p++;
						goto a184;
						break;
					case 22:
						$this->p++;
						goto a184;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(343);
				}

				goto f183;
				a184:
				goto k183;
				f183:
				$this->p = $o2;
				goto n182;
				k183:
				goto a177;
				n182:
				break;
			case 36:
			case 84:
				if (!$this->s361()) {
					goto n185;}
				goto a177;
				n185:
				break;
			case 15:
				$this->p++;
				goto a177;
				break;
			case 16:
				$this->p++;
				goto a177;
				break;
			case 17:
				$this->p++;
				goto a177;
				break;
			case 19:
				$this->p++;
				goto a177;
				break;
			case 20:
				$this->p++;
				goto a177;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(340);
		}

		goto f176;
		a177:
		goto k176;
		f176:
		$this->p = $o1;
		return false;
		k176:

		return true;
	}

	private function s347(): bool
	{
		if (!isset(self::T1ad6c327[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(347);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::T1ad6c327[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(339);
			}

			goto f430;}

		$this->p++;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f430;}

		$this->p++;
		if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if (!$this->s349()) {
				goto n431;}
			n431:
		} elseif ($this->p > $this->f) {
			$this->rec(349);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f430;}

		$this->p++;
		goto k430;
		f430:
		$this->p = $o1;
		return false;
		k430:

		return true;
	}

	private function s349(): bool
	{
		if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(349);
			}

			return false;}

		$o1 = $this->p;
		// s350
		if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(350);
			}

			goto f712;}

		$o2 = $this->p;
		if (!$this->s340()) {
			goto f713;}
		if (!$this->s351()) {
			goto f713;}
		goto k713;
		f713:
		$this->p = $o2;
		goto f712;
		k713:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s355()) {
				goto n714;}
			n714:
		} elseif ($this->p > $this->f) {
			$this->rec(355);
		}
		if (($this->ids[$this->p] === 40)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k712;
		f712:
		$this->p = $o1;
		return false;
		k712:

		return true;
	}

	private function s351(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n964;}
				goto a963;
				n964:
				break;
			case 44:
				// s352
				if (!($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->rec(352);
					}

					goto n965;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 44)) {
					if ($this->p >= $this->f) {
						$this->rec(67);
					}

					goto f966;}

				$this->p++;
				if (!$this->s340()) {
					goto f966;}
				goto k966;
				f966:
				$this->p = $o2;
				goto n965;
				k966:
				goto a963;
				n965:
				if (($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n967;}
				goto a963;
				n967:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(351);
		}

		goto f962;
		a963:
		goto k962;
		f962:
		$this->p = $o1;
		return false;
		k962:

		return true;
	}

	private function s355(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(355);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(356);
				}

				goto e969;
			}

			// s356
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(356);
				}

				goto e969;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f970;}

			$this->p++;
			// s350
			if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(350);
				}

				goto f970;}

			$o3 = $this->p;
			if (!$this->s340()) {
				goto f971;}
			if (!$this->s351()) {
				goto f971;}
			goto k971;
			f971:
			$this->p = $o3;
			goto f970;
			k971:
			goto k970;
			f970:
			$this->p = $o2;
			goto e969;
			k970:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e969;
			}

			$k1++;
		}
		e969:

		if ($k1 < 1) {
			goto f968;
		}
		goto k968;
		f968:
		$this->p = $o1;
		return false;
		k968:

		return true;
	}

	private function s360(): bool
	{
		if (!($this->ids[$this->p] === 43)) {
			if ($this->p > $this->f) {
				$this->rec(360);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 43)) {
			if ($this->p >= $this->f) {
				$this->rec(66);
			}

			goto f429;}

		$this->p++;
		if (!$this->s163()) {
			goto f429;}
		goto k429;
		f429:
		$this->p = $o1;
		return false;
		k429:

		return true;
	}

	private function s361(): bool
	{
		if (!isset(self::T885cd349[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(361);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::T885cd349[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(228);
			}

			goto f432;}

		$this->p++;
		if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if (!$this->s349()) {
				goto n433;}
			n433:
		} elseif ($this->p > $this->f) {
			$this->rec(349);
		}
		if (!($this->ids[$this->p] === 37)) {
			if ($this->p >= $this->f) {
				$this->rec(59);
			}

			goto f432;}

		$this->p++;
		goto k432;
		f432:
		$this->p = $o1;
		return false;
		k432:

		return true;
	}

	private function s362(): bool
	{
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(362);
			}

			return false;}

		$o1 = $this->p;
		if (!$this->s363()) {
			goto f1202;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1203;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e1205;
				}

				if (!$this->s224()) {
					goto e1205;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1205;
				}

				$k2++;
			}
			e1205:

			if ($k2 < 1) {
				goto f1204;
			}
			goto k1204;
			f1204:
			$this->p = $o2;
			goto n1203;
			k1204:
			n1203:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1202;
		f1202:
		$this->p = $o1;
		return false;
		k1202:

		return true;
	}

	private function s363(): bool
	{
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(363);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p >= $this->f) {
				$this->rec(54);
			}

			goto f1497;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1499;
			}

			$this->p++;
		}
		e1499:
		if (!$this->s321()) {
			goto f1497;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1501;
			}

			$this->p++;
		}
		e1501:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s327()) {
				goto n1502;}
			n1502:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1497;}

		$this->p++;
		goto k1497;
		f1497:
		$this->p = $o1;
		return false;
		k1497:

		return true;
	}

	private function s364(): bool
	{
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(364);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f879;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e881;
			}

			$this->p++;
		}
		e881:
		if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if (!$this->s306()) {
				goto n882;}
			n882:
		} elseif ($this->p > $this->f) {
			$this->rec(306);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f879;}

		$this->p++;
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f879;}

		$this->p++;
		if (!$this->s315()) {
			goto f879;}
		goto k879;
		f879:
		$this->p = $o1;
		return false;
		k879:

		return true;
	}

	private function s368(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				// s383
				$o2 = $this->p;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f413;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f413;}
				}
				goto k413;
				f413:
				$this->p = $o2;
				goto n412;
				k413:
				goto a411;
				n412:
				break;
			case 28:
			case 29:
			case 41:
			case 80:
				// s369
				if (!isset(self::T8458d590[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(369);
					}

					goto n414;}

				$o2 = $this->p;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e417;
					}

					$this->p++;
				}
				e417:
				if (!$this->s370()) {
					goto f415;}
				goto k415;
				f415:
				$this->p = $o2;
				goto n414;
				k415:
				goto a411;
				n414:
				// s383
				$o2 = $this->p;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f419;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f419;}
				}
				goto k419;
				f419:
				$this->p = $o2;
				goto n418;
				k419:
				goto a411;
				n418:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(368);
		}

		goto f410;
		a411:
		goto k410;
		f410:
		$this->p = $o1;
		return false;
		k410:

		return true;
	}

	private function s370(): bool
	{
		if (!isset(self::Tc8c984b6[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(370);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 28:
				// s371
				if (!($this->ids[$this->p] === 28)) {
					if ($this->p > $this->f) {
						$this->rec(371);
					}

					goto n687;}

				$o2 = $this->p;
				if (!$this->s372()) {
					goto f688;}
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f688;}
				}
				goto k688;
				f688:
				$this->p = $o2;
				goto n687;
				k688:
				goto a686;
				n687:
				break;
			case 29:
				// s377
				if (!($this->ids[$this->p] === 29)) {
					if ($this->p > $this->f) {
						$this->rec(377);
					}

					goto n689;}

				$o2 = $this->p;
				if (!$this->s378()) {
					goto f690;}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f690;}
				}
				goto k690;
				f690:
				$this->p = $o2;
				goto n689;
				k690:
				goto a686;
				n689:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(370);
		}

		goto f685;
		a686:
		goto k685;
		f685:
		$this->p = $o1;
		return false;
		k685:

		return true;
	}

	private function s372(): bool
	{
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(372);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 28)) {
				if ($b1 > $this->f) {
					$this->rec(373);
				}

				goto e931;
			}

			// s373
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->rec(373);
				}

				goto e931;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p >= $this->f) {
					$this->rec(49);
				}

				goto f932;}

			$this->p++;
			if (!$this->s201()) {
				goto f932;}
			// s375
			$o3 = $this->p;
			// s203
			$o4 = $this->p;
			while (true) {
				$b4 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b4 > $this->f) {
						$this->rec(204);
					}

					goto e936;
				}

				$this->p++;
			}
			e936:
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->rec(49);
				}

				goto f934;}
			goto k934;
			f934:
			$this->p = $o3;
			goto n933;
			k934:
			n933:
			goto k932;
			f932:
			$this->p = $o2;
			goto e931;
			k932:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e931;
			}

			$k1++;
		}
		e931:

		if ($k1 < 1) {
			goto f930;
		}
		goto k930;
		f930:
		$this->p = $o1;
		return false;
		k930:

		return true;
	}

	private function s378(): bool
	{
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(378);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 29)) {
				if ($b1 > $this->f) {
					$this->rec(379);
				}

				goto e938;
			}

			// s379
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->rec(379);
				}

				goto e938;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p >= $this->f) {
					$this->rec(50);
				}

				goto f939;}

			$this->p++;
			if (!$this->s201()) {
				goto f939;}
			// s381
			$o3 = $this->p;
			// s203
			$o4 = $this->p;
			while (true) {
				$b4 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b4 > $this->f) {
						$this->rec(204);
					}

					goto e943;
				}

				$this->p++;
			}
			e943:
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->rec(50);
				}

				goto f941;}
			goto k941;
			f941:
			$this->p = $o3;
			goto n940;
			k941:
			n940:
			goto k939;
			f939:
			$this->p = $o2;
			goto e938;
			k939:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e938;
			}

			$k1++;
		}
		e938:

		if ($k1 < 1) {
			goto f937;
		}
		goto k937;
		f937:
		$this->p = $o1;
		return false;
		k937:

		return true;
	}

	private function s385(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				// s388
				$o2 = $this->p;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f335;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f335;}
				}
				if (($this->ids[$this->p] === 32)) {
					if (($this->ids[$this->p] === 32)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f335;}
				}
				if (!$this->s125()) {
					goto f335;}
				goto k335;
				f335:
				$this->p = $o2;
				goto n334;
				k335:
				goto a333;
				n334:
				break;
			case 25:
			case 26:
				// s386
				if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(386);
					}

					goto n336;}

				$o2 = $this->p;
				if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(387);
					}

					goto f337;}

				$this->p++;
				if (!$this->s125()) {
					goto f337;}
				goto k337;
				f337:
				$this->p = $o2;
				goto n336;
				k337:
				goto a333;
				n336:
				// s388
				$o2 = $this->p;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f339;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f339;}
				}
				if (($this->ids[$this->p] === 32)) {
					if (($this->ids[$this->p] === 32)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f339;}
				}
				if (!$this->s125()) {
					goto f339;}
				goto k339;
				f339:
				$this->p = $o2;
				goto n338;
				k339:
				goto a333;
				n338:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(385);
		}

		goto f332;
		a333:
		goto k332;
		f332:
		$this->p = $o1;
		return false;
		k332:

		return true;
	}

	private function s391(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(391);
			}

			return false;}

		$o1 = $this->p;
		if (!$this->s199()) {
			goto f299;}
		// s388
		$o2 = $this->p;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f300;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f300;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f300;}
		}
		if (!$this->s125()) {
			goto f300;}
		goto k300;
		f300:
		$this->p = $o2;
		goto f299;
		k300:
		goto k299;
		f299:
		$this->p = $o1;
		return false;
		k299:

		return true;
	}

	private function s409(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(409);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 24:
			case 25:
			case 30:
			case 32:
			case 82:
				// s435
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(435);
					}

					goto n319;}

				$o2 = $this->p;
				if (!$this->s199()) {
					goto f320;}
				if (!$this->s415()) {
					goto f320;}
				goto k320;
				f320:
				$this->p = $o2;
				goto n319;
				k320:
				goto a318;
				n319:
				break;
			case 9:
				// s410
				if (!($this->ids[$this->p] === 9)) {
					if ($this->p > $this->f) {
						$this->rec(410);
					}

					goto n321;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 9)) {
					if ($this->p >= $this->f) {
						$this->rec(25);
					}

					goto f322;}

				$this->p++;
				if (isset(self::T648acf56[$this->ids[$this->p]])) {
					$sf = $this->f;
					$se = $this->er;
					$sp = $this->p;
					$matched = $this->s412();
					$this->f = $sf;
					$this->er = $se;
					$this->p = $sp;
					if ($matched) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f322;}
				}
				if (!$this->s199()) {
					goto f322;}
				if (!$this->s415()) {
					goto f322;}
				goto k322;
				f322:
				$this->p = $o2;
				goto n321;
				k322:
				goto a318;
				n321:
				// s435
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(435);
					}

					goto n324;}

				$o2 = $this->p;
				if (!$this->s199()) {
					goto f325;}
				if (!$this->s415()) {
					goto f325;}
				goto k325;
				f325:
				$this->p = $o2;
				goto n324;
				k325:
				goto a318;
				n324:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(409);
		}

		goto f317;
		a318:
		goto k317;
		f317:
		$this->p = $o1;
		return false;
		k317:

		return true;
	}

	private function s412(): bool
	{
		if (!isset(self::T648acf56[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(412);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 28:
			case 29:
			case 41:
			case 80:
				// s413
				if (!isset(self::T8458d590[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(413);
					}

					goto n538;}

				$o2 = $this->p;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e541;
					}

					$this->p++;
				}
				e541:
				if (!isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(414);
					}

					goto f539;}

				$this->p++;
				goto k539;
				f539:
				$this->p = $o2;
				goto n538;
				k539:
				goto a537;
				n538:
				break;
			case 32:
				$this->p++;
				goto a537;
				break;
			case 34:
				$this->p++;
				goto a537;
				break;
			case 36:
				$this->p++;
				goto a537;
				break;
			case 43:
				$this->p++;
				goto a537;
				break;
			case 82:
				$this->p++;
				goto a537;
				break;
			case 84:
				$this->p++;
				goto a537;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(412);
		}

		goto f536;
		a537:
		goto k536;
		f536:
		$this->p = $o1;
		return false;
		k536:

		return true;
	}

	private function s415(): bool
	{
		if (!isset(self::T02ca7bff[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(415);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
				// s416
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(416);
					}

					goto n593;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f594;}

				$this->p++;
				if (!$this->s417()) {
					goto f594;}
				goto k594;
				f594:
				$this->p = $o2;
				goto n593;
				k594:
				goto a592;
				n593:
				break;
			case 32:
			case 34:
			case 82:
			case 86:
				if (!$this->s417()) {
					goto n595;}
				goto a592;
				n595:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(415);
		}

		goto f591;
		a592:
		goto k591;
		f591:
		$this->p = $o1;
		return false;
		k591:

		return true;
	}

	private function s417(): bool
	{
		if (!isset(self::Tf86de613[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(417);
			}

			return false;}

		$o1 = $this->p;
		// s418
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n813;}
				goto a812;
				n813:
				break;
			case 34:
			case 86:
				if (!$this->s419()) {
					goto n814;}
				goto a812;
				n814:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n815;}
				goto a812;
				n815:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(418);
		}

		goto f811;
		a812:
		goto k811;
		f811:
		$this->p = $o2;
		goto f810;
		k811:
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f810;}

		$this->p++;
		if (isset(self::T9373805c[$this->ids[$this->p]])) {
			if (!$this->s424()) {
				goto n816;}
			n816:
		} elseif ($this->p > $this->f) {
			$this->rec(424);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f810;}

		$this->p++;
		// s125
		$o2 = $this->p;
		// s126
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
				if ($b3 > $this->f) {
					$this->rec(116);
				}

				goto e819;
			}

			$this->p++;
		}
		e819:
		if (($this->ids[$this->p] === 80)) {
			if (!$this->s128()) {
				goto n820;}
			n820:
		} elseif ($this->p > $this->f) {
			$this->rec(128);
		}
		goto k810;
		f810:
		$this->p = $o1;
		return false;
		k810:

		return true;
	}

	private function s419(): bool
	{
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(419);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(238);
			}

			goto f1038;}

		$this->p++;
		// s286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f1038;}

		$o2 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1039;}

		$this->p++;
		if (!$this->s287()) {
			goto f1039;}
		if (!$this->s293()) {
			goto f1039;}
		if (!$this->s296()) {
			goto f1039;}
		goto k1039;
		f1039:
		$this->p = $o2;
		goto f1038;
		k1039:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s421()) {
				goto n1040;}
			n1040:
		} elseif ($this->p > $this->f) {
			$this->rec(421);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1038;}

		$this->p++;
		goto k1038;
		f1038:
		$this->p = $o1;
		return false;
		k1038:

		return true;
	}

	private function s421(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(421);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(422);
				}

				goto e1387;
			}

			// s422
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(422);
				}

				goto e1387;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1388;}

			$this->p++;
			if (!$this->s286()) {
				goto f1388;}
			goto k1388;
			f1388:
			$this->p = $o2;
			goto e1387;
			k1388:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1387;
			}

			$k1++;
		}
		e1387:

		if ($k1 < 1) {
			goto f1386;
		}
		goto k1386;
		f1386:
		$this->p = $o1;
		return false;
		k1386:

		return true;
	}

	private function s424(): bool
	{
		if (!isset(self::T9373805c[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(424);
			}

			return false;}

		$o1 = $this->p;
		if (!$this->s425()) {
			goto f1041;}
		if (($this->ids[$this->p] === 40)) {
			// s433
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(433);
				}

				goto n1042;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(434);
					}

					goto e1044;
				}

				if (!$this->s434()) {
					goto e1044;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1044;
				}

				$k2++;
			}
			e1044:

			if ($k2 < 1) {
				goto f1043;
			}
			goto k1043;
			f1043:
			$this->p = $o2;
			goto n1042;
			k1043:
			n1042:
		} elseif ($this->p > $this->f) {
			$this->rec(433);
		}
		goto k1041;
		f1041:
		$this->p = $o1;
		return false;
		k1041:

		return true;
	}

	private function s425(): bool
	{
		if (!isset(self::T9373805c[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(425);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 24:
			case 30:
			case 32:
			case 82:
				// s426
				if (!isset(self::T34c34eee[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(426);
					}

					goto n1391;}

				$o2 = $this->p;
				if (!isset(self::T34c34eee[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(428);
					}

					goto f1392;}
				if (!$this->s199()) {
					goto f1392;}
				if (($this->ids[$this->p] === 27)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(48);
				}
				if (($this->ids[$this->p] === 42)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(65);
				}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f1392;}

				$this->p++;
				if (!$this->s429()) {
					goto f1392;}
				goto k1392;
				f1392:
				$this->p = $o2;
				goto n1391;
				k1392:
				goto a1390;
				n1391:
				break;
			case 26:
			case 27:
			case 42:
				// s431
				if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(431);
					}

					goto n1393;}

				$o2 = $this->p;
				if (($this->ids[$this->p] === 27)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(48);
				}
				if (($this->ids[$this->p] === 42)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(65);
				}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f1394;}

				$this->p++;
				if (!$this->s429()) {
					goto f1394;}
				goto k1394;
				f1394:
				$this->p = $o2;
				goto n1393;
				k1394:
				goto a1390;
				n1393:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(425);
		}

		goto f1389;
		a1390:
		goto k1389;
		f1389:
		$this->p = $o1;
		return false;
		k1389:

		return true;
	}

	private function s429(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1616;}
				goto a1615;
				n1616:
				break;
			case 46:
				// s430
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->rec(430);
					}

					goto n1617;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p >= $this->f) {
						$this->rec(69);
					}

					goto f1618;}

				$this->p++;
				if (!$this->s340()) {
					goto f1618;}
				goto k1618;
				f1618:
				$this->p = $o2;
				goto n1617;
				k1618:
				goto a1615;
				n1617:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1619;}
				goto a1615;
				n1619:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(429);
		}

		goto f1614;
		a1615:
		goto k1614;
		f1614:
		$this->p = $o1;
		return false;
		k1614:

		return true;
	}

	private function s434(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(434);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p >= $this->f) {
				$this->rec(63);
			}

			goto f1395;}

		$this->p++;
		if (!$this->s425()) {
			goto f1395;}
		goto k1395;
		f1395:
		$this->p = $o1;
		return false;
		k1395:

		return true;
	}

	private function s436(): bool
	{
		if (!($this->ids[$this->p] === 62)) {
			if ($this->p > $this->f) {
				$this->rec(436);
			}

			return false;}

		$o1 = $this->p;
		if (!($this->ids[$this->p] === 62)) {
			if ($this->p >= $this->f) {
				$this->rec(100);
			}

			goto f313;}

		$this->p++;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f313;}

		$this->p++;
		if (!$this->s287()) {
			goto f313;}
		if (!$this->s293()) {
			goto f313;}
		if (!$this->s296()) {
			goto f313;}
		// s388
		$o2 = $this->p;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f314;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f314;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f314;}
		}
		if (!$this->s125()) {
			goto f314;}
		goto k314;
		f314:
		$this->p = $o2;
		goto f313;
		k314:
		goto k313;
		f313:
		$this->p = $o1;
		return false;
		k313:

		return true;
	}

	private function s438(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(438);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f315;}

		$this->p++;
		if (!$this->s320()) {
			goto f315;}
		// s388
		$o2 = $this->p;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f316;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f316;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f316;}
		}
		if (!$this->s125()) {
			goto f316;}
		goto k316;
		f316:
		$this->p = $o2;
		goto f315;
		k316:
		goto k315;
		f315:
		$this->p = $o1;
		return false;
		k315:

		return true;
	}

	private function s444(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n303;}
				goto a302;
				n303:
				break;
			case 14:
				// s445
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->rec(445);
					}

					goto n304;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p >= $this->f) {
						$this->rec(28);
					}

					goto f305;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f305;}

				$this->p++;
				goto k305;
				f305:
				$this->p = $o2;
				goto n304;
				k305:
				goto a302;
				n304:
				if (($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n306;}
				goto a302;
				n306:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(444);
		}

		goto f301;
		a302:
		goto k301;
		f301:
		$this->p = $o1;
		return false;
		k301:

		return true;
	}

	private function s449(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n309;}
				goto a308;
				n309:
				break;
			case 45:
				// s450
				if (!($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->rec(450);
					}

					goto n310;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 45)) {
					if ($this->p >= $this->f) {
						$this->rec(68);
					}

					goto f311;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f311;}

				$this->p++;
				if (!$this->s451()) {
					goto f311;}
				goto k311;
				f311:
				$this->p = $o2;
				goto n310;
				k311:
				goto a308;
				n310:
				if (($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n312;}
				goto a308;
				n312:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(449);
		}

		goto f307;
		a308:
		goto k307;
		f307:
		$this->p = $o1;
		return false;
		k307:

		return true;
	}

	private function s451(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n555;}
				goto a554;
				n555:
				break;
			case 32:
			case 82:
				// s452
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(452);
					}

					goto n556;}

				$o2 = $this->p;
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(16);
					}

					goto f557;}

				$this->p++;
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p >= $this->f) {
						$this->rec(53);
					}

					goto f557;}

				$this->p++;
				goto k557;
				f557:
				$this->p = $o2;
				goto n556;
				k557:
				goto a554;
				n556:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n558;}
				goto a554;
				n558:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(451);
		}

		goto f553;
		a554:
		goto k553;
		f553:
		$this->p = $o1;
		return false;
		k553:

		return true;
	}

	private function s454(): bool
	{
		if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(454);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(119);
			}

			goto f247;}

		$this->p++;
		// s125
		$o2 = $this->p;
		// s126
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
				if ($b3 > $this->f) {
					$this->rec(116);
				}

				goto e250;
			}

			$this->p++;
		}
		e250:
		if (($this->ids[$this->p] === 80)) {
			if (!$this->s128()) {
				goto n251;}
			n251:
		} elseif ($this->p > $this->f) {
			$this->rec(128);
		}
		goto k247;
		f247:
		$this->p = $o1;
		return false;
		k247:

		return true;
	}
}
