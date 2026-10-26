<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser\Generated;

use PHPStan\PhpDocParser\Ast\Comment;
use PHPStan\PhpDocParser\Parser\Phplrt\Token;
use Phplrt\Parser\Context;
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
 * this one.
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
	 * The context of whatever has been read from the given position up to the
	 * given one, or up to the position the reading is at.
	 */
	abstract protected function context(int $from, ?int $to = null): Context;

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceType(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceSubType(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceNullable(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceAtomic(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceCallableReturnType(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceArrayOrOffsetAccessItem(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceGeneric(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceGenericInReturnType(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceGenericArgument(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceCallable(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceCallableWithTemplates(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceCallableTemplateArgument(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceCallableParameter(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceArrayShape(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceListShape(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceArrayShapeUnsealedType(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceListShapeUnsealedType(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceArrayShapeItem(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceArrayShapeKey(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceObjectShape(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceObjectShapeItem(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceObjectShapeKey(Context $context, $children);

	/**
	 * @return mixed
	 */
	abstract protected function reduceDiscardedComment(Context $context);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantFloat(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantInteger(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantString(Context $context, $children);

	/**
	 * @return mixed
	 */
	abstract protected function reduceConstantTrue(Context $context);

	/**
	 * @return mixed
	 */
	abstract protected function reduceConstantFalse(Context $context);

	/**
	 * @return mixed
	 */
	abstract protected function reduceConstantNull(Context $context);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantArrayLiteral(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantArrayShorthand(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantArrayItem(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceConstantFetch(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reducePhpDoc(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceTextChild(Context $context, $children);

	/**
	 * @return mixed
	 */
	abstract protected function reduceText(Context $context);

	/**
	 * @return mixed
	 */
	abstract protected function reduceDoctrineText(Context $context);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceKnownTag(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceMethodParameter(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceGenericTag(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineTag(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceInvalidTag(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceInvalidTypeAliasTag(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineArgument(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineNestedAnnotation(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineArray(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineArrayItem(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineArrayKey(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineIdentifier(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineConstantFetch(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineFloat(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineInteger(Context $context, $children);

	/**
	 * @param list<mixed> $children
	 * @return mixed
	 */
	abstract protected function reduceDoctrineString(Context $context, $children);

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
		$this->v[$m1] = $this->reducePhpDoc($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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
				goto f200;}
		}
		if (!$this->b8()) {
			goto f200;}
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
					goto f203;}
			}
			if (!$this->b8()) {
				goto f203;}
			goto k203;
			f203:
			$this->p = $o3;
			$this->n = $m3;
			$this->comments = $cs3;
			goto e202;
			k203:
			if ($this->p === $b2) {
				goto e202;
			}
		}
		e202:
		goto k200;
		f200:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k200:

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
					goto f461;}
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

							goto n464;}
						goto a463;
						n464:
						break;
					case 80:
						$this->p++;
						goto a463;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f462;
				a463:
				goto k462;
				f462:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f461;
				k462:
				goto k461;
				f461:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n460;
				k461:
				goto a459;
				n460:
				break;
			case 76:
			case 77:
			case 78:
			case 79:
				// b9
				if (!isset(self::T942497bd[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(9);
					}

					goto n465;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b10()) {
					goto f466;}
				if (($this->ids[$this->p] === 80)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(3);
				}
				goto k466;
				f466:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n465;
				k466:
				goto a459;
				n465:
				// b188
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b189()) {
					goto f468;}
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

							goto n471;}
						goto a470;
						n471:
						break;
					case 80:
						$this->p++;
						goto a470;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f469;
				a470:
				goto k469;
				f469:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f468;
				k469:
				goto k468;
				f468:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n467;
				k468:
				goto a459;
				n467:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(8);
		}

		goto f458;
		a459:
		goto k458;
		f458:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k458:

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

			goto f218;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f218;}
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

				goto e222;
			}

			$this->p++;
		}
		e222:
		if (!$this->s74()) {
			goto f220;}
		goto k220;
		f220:
		$this->p = $o3;
		goto f218;
		k220:
		$this->v[$this->n++] = $this->reduceDoctrineText($this->context($o2));
		goto k218;
		f218:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k218:
		$this->v[$m1] = $this->reduceGenericTag($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b125(): bool
	{
		$o1 = $this->p;
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

				goto e255;
			}

			$this->p++;
		}
		e255:
		if (($this->ids[$this->p] === 80)) {
			if (!$this->s128()) {
				goto n256;}
			n256:
		} elseif ($this->p > $this->f) {
			$this->rec(128);
		}
		$this->v[$this->n++] = $this->reduceText($this->context($o1));

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
					goto n225;}
				goto a224;
				n225:
				break;
			case 78:
			case 79:
				if (!$this->b135()) {
					goto n226;}
				goto a224;
				n226:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(134);
		}

		goto f223;
		a224:
		goto k223;
		f223:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k223:
		$this->v[$m1] = $this->reduceDoctrineTag($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f477;}

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
					goto n480;}
				goto a479;
				n480:
				break;
			case 32:
			case 82:
				if (!$this->b138()) {
					goto n481;}
				goto a479;
				n481:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n482;}
				goto a479;
				n482:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(137);
		}

		goto f478;
		a479:
		goto k478;
		f478:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f477;
		k478:
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

				goto e486;
			}

			$this->p++;
		}
		e486:
		if (!$this->s74()) {
			goto f484;}
		goto k484;
		f484:
		$this->p = $o3;
		goto f477;
		k484:
		$this->v[$this->n++] = $this->reduceDoctrineText($this->context($o2));
		goto k477;
		f477:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k477:

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

			goto f731;}

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

				goto e733;
			}

			$this->p++;
		}
		e733:
		if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			// b141
			if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(141);
				}

				goto n734;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b142()) {
				goto f735;}
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

					goto e737;
				}

				$this->p++;
			}
			e737:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b182()) {
					goto n738;}
				n738:
			} elseif ($this->p > $this->f) {
				$this->rec(182);
			}
			goto k735;
			f735:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n734;
			k735:
			n734:
		} elseif ($this->p > $this->f) {
			$this->rec(141);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f731;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k731;
		f731:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k731:

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
				// b146
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(146);
					}

					goto n979;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f980;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if (!$this->b138()) {
						goto n981;}
					n981:
				} elseif ($this->p > $this->f) {
					$this->rec(138);
				}
				goto k980;
				f980:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n979;
				k980:
				$this->v[$m2] = $this->reduceDoctrineNestedAnnotation($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a978;
				n979:
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
					goto n982;}
				goto a978;
				n982:
				// b172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n983;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f984;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f984;}
				}
				goto k984;
				f984:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n983;
				k984:
				$this->v[$m2] = $this->reduceDoctrineIdentifier($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a978;
				n983:
				if (!$this->b160()) {
					goto n985;}
				goto a978;
				n985:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				if (!$this->b143()) {
					goto n986;}
				goto a978;
				n986:
				// b172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n987;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f988;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f988;}
				}
				goto k988;
				f988:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n987;
				k988:
				$this->v[$m2] = $this->reduceDoctrineIdentifier($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a978;
				n987:
				break;
			case 22:
			case 23:
				// b155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n989;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->b156()) {
							goto n992;}
						goto a991;
						n992:
						break;
					case 23:
						$this->v[$this->n++] = $this->token($this->p++);
						goto a991;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f990;
				a991:
				goto k990;
				f990:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n989;
				k990:
				$this->v[$m2] = $this->reduceDoctrineString($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a978;
				n989:
				break;
			case 38:
			case 83:
				if (!$this->b148()) {
					goto n993;}
				goto a978;
				n993:
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceDoctrineFloat($this->context($this->p, $this->p + 1), [$this->token($this->p)]);
				$this->p++;
				goto a978;
				break;
			case 20:
				$this->v[$this->n++] = $this->reduceDoctrineInteger($this->context($this->p, $this->p + 1), [$this->token($this->p)]);
				$this->p++;
				goto a978;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(142);
		}

		goto f977;
		a978:
		goto k977;
		f977:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k977:
		$this->v[$m1] = $this->reduceDoctrineArgument($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f1290;}

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

				goto e1292;
			}

			$this->p++;
		}
		e1292:
		if (!($this->ids[$this->p] === 46)) {
			if ($this->p >= $this->f) {
				$this->rec(69);
			}

			goto f1290;}

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

				goto e1294;
			}

			$this->p++;
		}
		e1294:
		if (!$this->b145()) {
			goto f1290;}
		goto k1290;
		f1290:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1290:

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
				// b146
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(146);
					}

					goto n1580;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f1581;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if (!$this->b138()) {
						goto n1582;}
					n1582:
				} elseif ($this->p > $this->f) {
					$this->rec(138);
				}
				goto k1581;
				f1581:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1580;
				k1581:
				$this->v[$m2] = $this->reduceDoctrineNestedAnnotation($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a1579;
				n1580:
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
				// b172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n1583;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1584;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1584;}
				}
				goto k1584;
				f1584:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1583;
				k1584:
				$this->v[$m2] = $this->reduceDoctrineIdentifier($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a1579;
				n1583:
				if (!$this->b160()) {
					goto n1585;}
				goto a1579;
				n1585:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				// b172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n1586;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1587;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1587;}
				}
				goto k1587;
				f1587:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1586;
				k1587:
				$this->v[$m2] = $this->reduceDoctrineIdentifier($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a1579;
				n1586:
				break;
			case 22:
			case 23:
				// b155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n1588;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->b156()) {
							goto n1591;}
						goto a1590;
						n1591:
						break;
					case 23:
						$this->v[$this->n++] = $this->token($this->p++);
						goto a1590;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f1589;
				a1590:
				goto k1589;
				f1589:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1588;
				k1589:
				$this->v[$m2] = $this->reduceDoctrineString($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a1579;
				n1588:
				break;
			case 38:
			case 83:
				if (!$this->b148()) {
					goto n1592;}
				goto a1579;
				n1592:
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceDoctrineFloat($this->context($this->p, $this->p + 1), [$this->token($this->p)]);
				$this->p++;
				goto a1579;
				break;
			case 20:
				$this->v[$this->n++] = $this->reduceDoctrineInteger($this->context($this->p, $this->p + 1), [$this->token($this->p)]);
				$this->p++;
				goto a1579;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(145);
		}

		goto f1578;
		a1579:
		goto k1578;
		f1578:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1578:

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

			goto f1300;}

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

				goto e1302;
			}

			$this->p++;
		}
		e1302:
		if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
			// b151
			if (!isset(self::T1490fe7f[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(151);
				}

				goto n1303;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b152()) {
				goto f1304;}
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

					goto e1306;
				}

				$this->p++;
			}
			e1306:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b176()) {
					goto n1307;}
				n1307:
			} elseif ($this->p > $this->f) {
				$this->rec(176);
			}
			goto k1304;
			f1304:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1303;
			k1304:
			n1303:
		} elseif ($this->p > $this->f) {
			$this->rec(151);
		}
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f1300;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1300;
		f1300:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1300:
		$this->v[$m1] = $this->reduceDoctrineArray($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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
				// b146
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(146);
					}

					goto n1595;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f1596;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if (!$this->b138()) {
						goto n1597;}
					n1597:
				} elseif ($this->p > $this->f) {
					$this->rec(138);
				}
				goto k1596;
				f1596:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1595;
				k1596:
				$this->v[$m2] = $this->reduceDoctrineNestedAnnotation($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a1594;
				n1595:
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
					goto n1598;}
				goto a1594;
				n1598:
				// b172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n1599;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1600;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1600;}
				}
				goto k1600;
				f1600:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1599;
				k1600:
				$this->v[$m2] = $this->reduceDoctrineIdentifier($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a1594;
				n1599:
				if (!$this->b160()) {
					goto n1601;}
				goto a1594;
				n1601:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				if (!$this->b153()) {
					goto n1602;}
				goto a1594;
				n1602:
				// b172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n1603;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1604;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1604;}
				}
				goto k1604;
				f1604:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1603;
				k1604:
				$this->v[$m2] = $this->reduceDoctrineIdentifier($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a1594;
				n1603:
				break;
			case 22:
			case 23:
				if (!$this->b153()) {
					goto n1605;}
				goto a1594;
				n1605:
				// b155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n1606;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->b156()) {
							goto n1609;}
						goto a1608;
						n1609:
						break;
					case 23:
						$this->v[$this->n++] = $this->token($this->p++);
						goto a1608;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f1607;
				a1608:
				goto k1607;
				f1607:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1606;
				k1607:
				$this->v[$m2] = $this->reduceDoctrineString($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a1594;
				n1606:
				break;
			case 38:
			case 83:
				if (!$this->b148()) {
					goto n1610;}
				goto a1594;
				n1610:
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceDoctrineFloat($this->context($this->p, $this->p + 1), [$this->token($this->p)]);
				$this->p++;
				goto a1594;
				break;
			case 20:
				if (!$this->b153()) {
					goto n1611;}
				goto a1594;
				n1611:
				$this->v[$this->n++] = $this->reduceDoctrineInteger($this->context($this->p, $this->p + 1), [$this->token($this->p)]);
				$this->p++;
				goto a1594;
				break;
			case 21:
				if (!$this->b153()) {
					goto n1612;}
				goto a1594;
				n1612:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(152);
		}

		goto f1593;
		a1594:
		goto k1593;
		f1593:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1593:
		$this->v[$m1] = $this->reduceDoctrineArrayItem($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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
			goto f1735;}
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

				goto e1737;
			}

			$this->p++;
		}
		e1737:
		if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(171);
			}

			goto f1735;}

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

				goto e1739;
			}

			$this->p++;
		}
		e1739:
		if (!$this->b145()) {
			goto f1735;}
		goto k1735;
		f1735:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1735:

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
					goto n1780;}
				goto a1779;
				n1780:
				// b169
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(169);
					}

					goto n1781;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1782;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1782;}
				}
				goto k1782;
				f1782:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1781;
				k1782:
				goto a1779;
				n1781:
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

					goto n1783;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1784;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1784;}
				}
				goto k1784;
				f1784:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1783;
				k1784:
				goto a1779;
				n1783:
				break;
			case 22:
			case 23:
				// b155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n1785;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->b156()) {
							goto n1788;}
						goto a1787;
						n1788:
						break;
					case 23:
						$this->v[$this->n++] = $this->token($this->p++);
						goto a1787;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f1786;
				a1787:
				goto k1786;
				f1786:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1785;
				k1786:
				$this->v[$m2] = $this->reduceDoctrineString($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a1779;
				n1785:
				break;
			case 20:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1779;
				break;
			case 21:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1779;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(154);
		}

		goto f1778;
		a1779:
		goto k1778;
		f1778:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1778:
		$this->v[$m1] = $this->reduceDoctrineArrayKey($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f1296;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
			// b158
			if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(158);
				}

				goto n1297;}

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

					goto e1299;
				}

				$this->v[$this->n++] = $this->token($this->p++);

				$k2++;
			}
			e1299:

			if ($k2 < 1) {
				goto f1298;
			}
			goto k1298;
			f1298:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1297;
			k1298:
			n1297:
		} elseif ($this->p > $this->f) {
			$this->rec(158);
		}
		goto k1296;
		f1296:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1296:

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
				goto f1295;}
		}
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1295;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 43)) {
			if ($this->p >= $this->f) {
				$this->rec(66);
			}

			goto f1295;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b163()) {
			goto f1295;}
		goto k1295;
		f1295:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1295:
		$this->v[$m1] = $this->reduceDoctrineConstantFetch($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

					goto n709;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f710;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
					if (!$this->b168()) {
						goto n711;}
					n711:
				} elseif ($this->p > $this->f) {
					$this->rec(168);
				}
				goto k710;
				f710:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n709;
				k710:
				goto a708;
				n709:
				break;
			case 50:
				// b164
				if (!($this->ids[$this->p] === 50)) {
					if ($this->p > $this->f) {
						$this->rec(164);
					}

					goto n712;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 50)) {
					if ($this->p >= $this->f) {
						$this->rec(71);
					}

					goto f713;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					if (!$this->b166()) {
						goto n714;}
					n714:
				} elseif ($this->p > $this->f) {
					$this->rec(166);
				}
				goto k713;
				f713:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n712;
				k713:
				goto a708;
				n712:
				break;
			case 85:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a708;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(163);
		}

		goto f707;
		a708:
		goto k707;
		f707:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k707:

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

			goto f1286;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T83e30518[$this->ids[$this->p]])) {
			if (!$this->b166()) {
				goto n1287;}
			n1287:
		} elseif ($this->p > $this->f) {
			$this->rec(166);
		}
		goto k1286;
		f1286:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1286:

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

			goto f952;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
			if (!$this->b168()) {
				goto n953;}
			n953:
		} elseif ($this->p > $this->f) {
			$this->rec(168);
		}
		goto k952;
		f952:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k952:

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
					goto n951;}
				goto a950;
				n951:
				break;
			case 85:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a950;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(168);
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

				goto e1614;
			}

			// b177
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(177);
				}

				goto e1614;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1615;}

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

					goto e1617;
				}

				$this->p++;
			}
			e1617:
			if (!$this->b178()) {
				goto f1615;}
			goto k1615;
			f1615:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1614;
			k1615:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1614;
			}

			$k1++;
		}
		e1614:

		if ($k1 < 1) {
			goto f1613;
		}
		goto k1613;
		f1613:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1613:

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

					goto n1742;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b152()) {
					goto f1743;}
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

						goto e1745;
					}

					$this->p++;
				}
				e1745:
				goto k1743;
				f1743:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1742;
				k1743:
				goto a1741;
				n1742:
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

					goto n1746;}
				goto a1741;
				n1746:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(178);
		}

		goto f1740;
		a1741:
		goto k1740;
		f1740:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1740:

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

				goto e995;
			}

			// b183
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(183);
				}

				goto e995;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f996;}

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

					goto e998;
				}

				$this->p++;
			}
			e998:
			if (!$this->b184()) {
				goto f996;}
			goto k996;
			f996:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e995;
			k996:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e995;
			}

			$k1++;
		}
		e995:

		if ($k1 < 1) {
			goto f994;
		}
		goto k994;
		f994:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k994:

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

					goto n1310;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b142()) {
					goto f1311;}
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

						goto e1313;
					}

					$this->p++;
				}
				e1313:
				goto k1311;
				f1311:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1310;
				k1311:
				goto a1309;
				n1310:
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

					goto n1314;}
				goto a1309;
				n1314:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(184);
		}

		goto f1308;
		a1309:
		goto k1308;
		f1308:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1308:

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

			goto f472;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b138()) {
			goto f472;}
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

				goto e476;
			}

			$this->p++;
		}
		e476:
		if (!$this->s74()) {
			goto f474;}
		goto k474;
		f474:
		$this->p = $o3;
		goto f472;
		k474:
		$this->v[$this->n++] = $this->reduceDoctrineText($this->context($o2));
		goto k472;
		f472:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k472:

		return true;
	}

	private function b189(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				// b455
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f34;}
				}
				// b457
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b125()) {
					goto f35;}
				goto k35;
				f35:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f34;
				k35:
				$this->v[$m3] = $this->reduceTextChild($this->context($o3), $this->n === $m3 + 1 && !\is_array($this->v[$m3]) ? [$this->v[$m3]] : $this->merge($m3));
				$this->n = $m3 + 1;
				goto k34;
				f34:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n33;
				k34:
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
					goto n36;}
				goto a32;
				n36:
				// b454
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(454);
					}

					goto n37;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f38;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f38;}
				$this->v[$this->n++] = $this->reduceText($this->context($o3));
				goto k38;
				f38:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n37;
				k38:
				$this->v[$m2] = $this->reduceInvalidTag($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a32;
				n37:
				// b455
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f41;}
				}
				// b457
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b125()) {
					goto f42;}
				goto k42;
				f42:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f41;
				k42:
				$this->v[$m3] = $this->reduceTextChild($this->context($o3), $this->n === $m3 + 1 && !\is_array($this->v[$m3]) ? [$this->v[$m3]] : $this->merge($m3));
				$this->n = $m3 + 1;
				goto k41;
				f41:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n40;
				k41:
				goto a32;
				n40:
				break;
			case 76:
			case 77:
			case 78:
			case 79:
				// b454
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(454);
					}

					goto n43;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f44;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f44;}
				$this->v[$this->n++] = $this->reduceText($this->context($o3));
				goto k44;
				f44:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n43;
				k44:
				$this->v[$m2] = $this->reduceInvalidTag($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a32;
				n43:
				// b455
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f47;}
				}
				// b457
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b125()) {
					goto f48;}
				goto k48;
				f48:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f47;
				k48:
				$this->v[$m3] = $this->reduceTextChild($this->context($o3), $this->n === $m3 + 1 && !\is_array($this->v[$m3]) ? [$this->v[$m3]] : $this->merge($m3));
				$this->n = $m3 + 1;
				goto k47;
				f47:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n46;
				k47:
				goto a32;
				n46:
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
					goto n65;}
				goto a64;
				n65:
				break;
			case 52:
				if (!$this->b405()) {
					goto n66;}
				goto a64;
				n66:
				break;
			case 53:
				if (!$this->b406()) {
					goto n67;}
				goto a64;
				n67:
				break;
			case 54:
				if (!$this->b404()) {
					goto n68;}
				goto a64;
				n68:
				break;
			case 55:
				if (!$this->b401()) {
					goto n69;}
				goto a64;
				n69:
				break;
			case 56:
				// b395
				if (!($this->ids[$this->p] === 56)) {
					if ($this->p > $this->f) {
						$this->rec(395);
					}

					goto n70;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 56)) {
					if ($this->p >= $this->f) {
						$this->rec(95);
					}

					goto f71;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f71;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f72;}
				if (!$this->b388()) {
					goto f72;}
				goto k72;
				f72:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f71;
				k72:
				goto k71;
				f71:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n70;
				k71:
				goto a64;
				n70:
				break;
			case 57:
				// b394
				if (!($this->ids[$this->p] === 57)) {
					if ($this->p > $this->f) {
						$this->rec(394);
					}

					goto n73;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 57)) {
					if ($this->p >= $this->f) {
						$this->rec(94);
					}

					goto f74;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f74;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f75;}
				if (!$this->b388()) {
					goto f75;}
				goto k75;
				f75:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f74;
				k75:
				goto k74;
				f74:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n73;
				k74:
				goto a64;
				n73:
				break;
			case 58:
				if (!$this->b443()) {
					goto n76;}
				goto a64;
				n76:
				break;
			case 59:
				if (!$this->b446()) {
					goto n77;}
				goto a64;
				n77:
				break;
			case 60:
				// b397
				if (!($this->ids[$this->p] === 60)) {
					if ($this->p > $this->f) {
						$this->rec(397);
					}

					goto n78;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 60)) {
					if ($this->p >= $this->f) {
						$this->rec(107);
					}

					goto f79;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f79;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f80;}
				if (!$this->b388()) {
					goto f80;}
				goto k80;
				f80:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f79;
				k80:
				goto k79;
				f79:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n78;
				k79:
				goto a64;
				n78:
				break;
			case 61:
				// b396
				if (!($this->ids[$this->p] === 61)) {
					if ($this->p > $this->f) {
						$this->rec(396);
					}

					goto n81;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 61)) {
					if ($this->p >= $this->f) {
						$this->rec(96);
					}

					goto f82;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f82;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f83;}
				if (!$this->b388()) {
					goto f83;}
				goto k83;
				f83:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f82;
				k83:
				goto k82;
				f82:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n81;
				k82:
				goto a64;
				n81:
				break;
			case 62:
				if (!$this->b436()) {
					goto n84;}
				goto a64;
				n84:
				break;
			case 63:
				if (!$this->b398()) {
					goto n85;}
				goto a64;
				n85:
				break;
			case 64:
				if (!$this->b439()) {
					goto n86;}
				goto a64;
				n86:
				break;
			case 65:
				// b407
				if (!($this->ids[$this->p] === 65)) {
					if ($this->p > $this->f) {
						$this->rec(407);
					}

					goto n87;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 65)) {
					if ($this->p >= $this->f) {
						$this->rec(97);
					}

					goto f88;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f88;}
				$this->v[$this->n++] = $this->reduceText($this->context($o3));
				goto k88;
				f88:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n87;
				k88:
				goto a64;
				n87:
				break;
			case 66:
				if (!$this->b441()) {
					goto n90;}
				goto a64;
				n90:
				if (!$this->b442()) {
					goto n91;}
				goto a64;
				n91:
				break;
			case 67:
				if (!$this->b400()) {
					goto n92;}
				goto a64;
				n92:
				break;
			case 68:
				if (!$this->b437()) {
					goto n93;}
				goto a64;
				n93:
				break;
			case 69:
				// b408
				if (!($this->ids[$this->p] === 69)) {
					if ($this->p > $this->f) {
						$this->rec(408);
					}

					goto n94;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 69)) {
					if ($this->p >= $this->f) {
						$this->rec(99);
					}

					goto f95;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b409()) {
					goto f95;}
				goto k95;
				f95:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n94;
				k95:
				goto a64;
				n94:
				break;
			case 70:
				// b390
				if (!($this->ids[$this->p] === 70)) {
					if ($this->p > $this->f) {
						$this->rec(390);
					}

					goto n96;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 70)) {
					if ($this->p >= $this->f) {
						$this->rec(91);
					}

					goto f97;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f97;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f98;}
				if (!$this->b388()) {
					goto f98;}
				goto k98;
				f98:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f97;
				k98:
				goto k97;
				f97:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n96;
				k97:
				goto a64;
				n96:
				break;
			case 71:
				// b392
				if (!($this->ids[$this->p] === 71)) {
					if ($this->p > $this->f) {
						$this->rec(392);
					}

					goto n99;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 71)) {
					if ($this->p >= $this->f) {
						$this->rec(92);
					}

					goto f100;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f100;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f101;}
				if (!$this->b388()) {
					goto f101;}
				goto k101;
				f101:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f100;
				k101:
				goto k100;
				f100:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n99;
				k100:
				goto a64;
				n99:
				break;
			case 72:
				// b191
				if (!($this->ids[$this->p] === 72)) {
					if ($this->p > $this->f) {
						$this->rec(191);
					}

					goto n102;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 72)) {
					if ($this->p >= $this->f) {
						$this->rec(84);
					}

					goto f103;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b192()) {
					goto f103;}
				goto k103;
				f103:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n102;
				k103:
				goto a64;
				n102:
				break;
			case 73:
				// b393
				if (!($this->ids[$this->p] === 73)) {
					if ($this->p > $this->f) {
						$this->rec(393);
					}

					goto n104;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 73)) {
					if ($this->p >= $this->f) {
						$this->rec(93);
					}

					goto f105;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f105;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f106;}
				if (!$this->b388()) {
					goto f106;}
				goto k106;
				f106:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f105;
				k106:
				goto k105;
				f105:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n104;
				k105:
				goto a64;
				n104:
				break;
			case 74:
				if (!$this->b440()) {
					goto n107;}
				goto a64;
				n107:
				break;
			case 75:
				// b384
				if (!($this->ids[$this->p] === 75)) {
					if ($this->p > $this->f) {
						$this->rec(384);
					}

					goto n108;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 75)) {
					if ($this->p >= $this->f) {
						$this->rec(90);
					}

					goto f109;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f109;}
				if (!$this->b385()) {
					goto f109;}
				goto k109;
				f109:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n108;
				k109:
				goto a64;
				n108:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(190);
		}

		goto f63;
		a64:
		goto k63;
		f63:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k63:
		$this->v[$m1] = $this->reduceKnownTag($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
		$this->n = $m1 + 1;

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
					goto n311;}
				goto a310;
				n311:
				break;
			case 26:
			case 27:
			case 42:
				if (!$this->b193()) {
					goto n312;}
				goto a310;
				n312:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(192);
		}

		goto f309;
		a310:
		goto k309;
		f309:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k309:

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

			goto f566;}
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

			goto f566;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f566;}
		$this->v[$this->n++] = $this->reduceText($this->context($o2));
		goto k566;
		f566:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k566:

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
			goto f564;}
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

			goto f564;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f564;}
		$this->v[$this->n++] = $this->reduceText($this->context($o2));
		goto k564;
		f564:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k564:

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

					goto n172;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b201()) {
					goto f173;}
				if (!$this->b368()) {
					goto f173;}
				goto k173;
				f173:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n172;
				k173:
				goto a171;
				n172:
				break;
			case 30:
				// b200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n174;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f175;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b201()) {
					goto f175;}
				goto k175;
				f175:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n174;
				k175:
				$this->v[$m2] = $this->reduceNullable($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a171;
				n174:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(199);
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
		$this->v[$m1] = $this->reduceType($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

					goto n368;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(332);
					}

					goto f369;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f369;}
				}
				if (!$this->b281()) {
					goto f369;}
				goto k369;
				f369:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n368;
				k369:
				goto a367;
				n368:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n370;}

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
						goto f371;}
				}
				if (!$this->b335()) {
					goto f371;}
				goto k371;
				f371:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n370;
				k371:
				goto a367;
				n370:
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

					goto n373;}

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
						goto f374;}
				}
				if (!$this->b335()) {
					goto f374;}
				goto k374;
				f374:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n373;
				k374:
				goto a367;
				n373:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->b255()) {
					goto n376;}
				goto a367;
				n376:
				// b278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n377;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f378;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f378;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f378;}
				}
				if (!$this->b281()) {
					goto f378;}
				goto k378;
				f378:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n377;
				k378:
				goto a367;
				n377:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n379;}

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
						goto f380;}
				}
				if (!$this->b335()) {
					goto f380;}
				goto k380;
				f380:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n379;
				k380:
				goto a367;
				n379:
				break;
			case 32:
			case 82:
				if (!$this->b202()) {
					goto n382;}
				goto a367;
				n382:
				break;
			case 6:
				if (!$this->b267()) {
					goto n383;}
				goto a367;
				n383:
				// b278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n384;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f385;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f385;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f385;}
				}
				if (!$this->b281()) {
					goto f385;}
				goto k385;
				f385:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n384;
				k385:
				goto a367;
				n384:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n386;}

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
						goto f387;}
				}
				if (!$this->b335()) {
					goto f387;}
				goto k387;
				f387:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n386;
				k387:
				goto a367;
				n386:
				break;
			case 7:
				if (!$this->b230()) {
					goto n389;}
				goto a367;
				n389:
				// b278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n390;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f391;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f391;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f391;}
				}
				if (!$this->b281()) {
					goto f391;}
				goto k391;
				f391:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n390;
				k391:
				goto a367;
				n390:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n392;}

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
						goto f393;}
				}
				if (!$this->b335()) {
					goto f393;}
				goto k393;
				f393:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n392;
				k393:
				goto a367;
				n392:
				break;
			case 25:
				if (!$this->b229()) {
					goto n395;}
				goto a367;
				n395:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(201);
		}

		goto f366;
		a367:
		goto k366;
		f366:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k366:
		$this->v[$m1] = $this->reduceAtomic($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f633;}

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

				goto e635;
			}

			$this->p++;
		}
		e635:
		if (!$this->b205()) {
			goto f633;}
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

				goto e637;
			}

			$this->p++;
		}
		e637:
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f633;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n638;}

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

					goto e640;
				}

				if (!$this->b224()) {
					goto e640;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e640;
				}

				$k2++;
			}
			e640:

			if ($k2 < 1) {
				goto f639;
			}
			goto k639;
			f639:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n638;
			k639:
			n638:
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

					goto n859;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b201()) {
					goto f860;}
				if (!$this->b209()) {
					goto f860;}
				goto k860;
				f860:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n859;
				k860:
				goto a858;
				n859:
				break;
			case 26:
				if (!$this->b206()) {
					goto n861;}
				goto a858;
				n861:
				break;
			case 30:
				// b200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n862;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f863;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b201()) {
					goto f863;}
				goto k863;
				f863:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n862;
				k863:
				$this->v[$m2] = $this->reduceNullable($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a858;
				n862:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(205);
		}

		goto f857;
		a858:
		goto k857;
		f857:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k857:
		$this->v[$m1] = $this->reduceSubType($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f1135;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 12)) {
			if ($this->p >= $this->f) {
				$this->rec(23);
			}

			goto f1135;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 11)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->b199()) {
			goto f1135;}
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

				goto e1137;
			}

			$this->p++;
		}
		e1137:
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
			}

			goto f1135;}

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

				goto e1139;
			}

			$this->p++;
		}
		e1139:
		if (!$this->b199()) {
			goto f1135;}
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

				goto e1141;
			}

			$this->p++;
		}
		e1141:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1135;}

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

				goto e1143;
			}

			$this->p++;
		}
		e1143:
		if (!$this->b205()) {
			goto f1135;}
		goto k1135;
		f1135:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1135:

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

						goto e1127;
					}

					$this->p++;
				}
				e1127:
				if (isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if (!$this->b213()) {
						goto n1128;}
					n1128:
				} elseif ($this->p > $this->f) {
					$this->rec(213);
				}
				goto a1123;
				n1124:
				break;
			case 12:
				if (!$this->b210()) {
					goto n1129;}
				goto a1123;
				n1129:
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

						goto e1133;
					}

					$this->p++;
				}
				e1133:
				if (isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if (!$this->b213()) {
						goto n1134;}
					n1134:
				} elseif ($this->p > $this->f) {
					$this->rec(213);
				}
				goto a1123;
				n1130:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(209);
		}

		goto f1122;
		a1123:
		goto k1122;
		f1122:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1122:

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

			goto f1467;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 11)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->b199()) {
			goto f1467;}
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
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
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

				goto e1471;
			}

			$this->p++;
		}
		e1471:
		if (!$this->b199()) {
			goto f1467;}
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

				goto e1473;
			}

			$this->p++;
		}
		e1473:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
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

				goto e1475;
			}

			$this->p++;
		}
		e1475:
		if (!$this->b205()) {
			goto f1467;}
		goto k1467;
		f1467:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1467:

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
					goto n1465;}
				goto a1464;
				n1465:
				break;
			case 29:
				if (!$this->b218()) {
					goto n1466;}
				goto a1464;
				n1466:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(213);
		}

		goto f1463;
		a1464:
		goto k1463;
		f1463:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1463:

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

			goto f1671;}

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

				goto e1673;
			}

			if (!$this->b216()) {
				goto e1673;}
			if ($this->p === $b2) {
				$k2 = 1;
				goto e1673;
			}

			$k2++;
		}
		e1673:

		if ($k2 < 1) {
			goto f1672;
		}
		goto k1672;
		f1672:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1671;
		k1672:
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f1671;}
		}
		goto k1671;
		f1671:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1671:

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

			goto f1755;}

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

				goto e1757;
			}

			$this->p++;
		}
		e1757:
		if (!$this->b201()) {
			goto f1755;}
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
		goto k1755;
		f1755:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1755:

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

			goto f1674;}

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

				goto e1676;
			}

			if (!$this->b220()) {
				goto e1676;}
			if ($this->p === $b2) {
				$k2 = 1;
				goto e1676;
			}

			$k2++;
		}
		e1676:

		if ($k2 < 1) {
			goto f1675;
		}
		goto k1675;
		f1675:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1674;
		k1675:
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f1674;}
		}
		goto k1674;
		f1674:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1674:

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

			goto f1760;}

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

				goto e1762;
			}

			$this->p++;
		}
		e1762:
		if (!$this->b201()) {
			goto f1760;}
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

				goto e1764;
			}

			$this->p++;
		}
		e1764:
		goto k1760;
		f1760:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1760:

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

					goto n837;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 36)) {
					if ($this->p >= $this->f) {
						$this->rec(57);
					}

					goto f838;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 37)) {
					if (($this->ids[$this->p] === 37)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f838;}
				}
				if (!$this->b199()) {
					goto f838;}
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f838;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k838;
				f838:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n837;
				k838:
				goto a836;
				n837:
				// b227
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(227);
					}

					goto n839;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(228);
					}

					goto f840;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f840;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k840;
				f840:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n839;
				k840:
				goto a836;
				n839:
				break;
			case 84:
				// b227
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(227);
					}

					goto n841;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(228);
					}

					goto f842;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f842;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k842;
				f842:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n841;
				k842:
				goto a836;
				n841:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(224);
		}

		goto f835;
		a836:
		goto k835;
		f835:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k835:
		$this->v[$m1] = $this->reduceArrayOrOffsetAccessItem($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f649;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n650;}

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

					goto e652;
				}

				if (!$this->b224()) {
					goto e652;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e652;
				}

				$k2++;
			}
			e652:

			if ($k2 < 1) {
				goto f651;
			}
			goto k651;
			f651:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n650;
			k651:
			n650:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k649;
		f649:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k649:

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

			goto f645;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f645;}
		}
		if (!$this->b231()) {
			goto f645;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n646;}

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

					goto e648;
				}

				if (!$this->b224()) {
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
			$this->n = $m2;
			$this->comments = $cs2;
			goto n646;
			k647:
			n646:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k645;
		f645:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k645:

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

			goto f872;}

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

				goto e874;
			}

			$this->p++;
		}
		e874:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->b233()) {
				goto n875;}
			n875:
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

				goto e877;
			}

			$this->p++;
		}
		e877:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f872;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k872;
		f872:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k872:
		$this->v[$m1] = $this->reduceArrayShape($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

					goto n1152;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b244()) {
					goto f1153;}
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

						goto e1155;
					}

					$this->p++;
				}
				e1155:
				if (($this->ids[$this->p] === 40)) {
					if (!$this->b252()) {
						goto n1156;}
					n1156:
				} elseif ($this->p > $this->f) {
					$this->rec(252);
				}
				goto k1153;
				f1153:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1152;
				k1153:
				goto a1151;
				n1152:
				break;
			case 42:
				if (!$this->b234()) {
					goto n1157;}
				goto a1151;
				n1157:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(233);
		}

		goto f1150;
		a1151:
		goto k1150;
		f1150:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1150:

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

			goto f1489;}

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

				goto e1491;
			}

			$this->p++;
		}
		e1491:
		if (!$this->b235()) {
			goto f1489;}
		if (($this->ids[$this->p] === 40)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k1489;
		f1489:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1489:

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
					goto n1679;}
				goto a1678;
				n1679:
				break;
			case 34:
			case 86:
				// b236
				if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(236);
					}

					goto n1680;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b237()) {
					goto f1681;}
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

						goto e1683;
					}

					$this->p++;
				}
				e1683:
				goto k1681;
				f1681:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1680;
				k1681:
				goto a1678;
				n1680:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1684;}
				goto a1678;
				n1684:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(235);
		}

		goto f1677;
		a1678:
		goto k1677;
		f1677:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1677:

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

			goto f1765;}

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

				goto e1767;
			}

			$this->p++;
		}
		e1767:
		if (!$this->b199()) {
			goto f1765;}
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

				goto e1769;
			}

			$this->p++;
		}
		e1769:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->b240()) {
				goto n1770;}
			n1770:
		} elseif ($this->p > $this->f) {
			$this->rec(240);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1765;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1765;
		f1765:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1765:
		$this->v[$m1] = $this->reduceArrayShapeUnsealedType($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f1789;}

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

				goto e1791;
			}

			$this->p++;
		}
		e1791:
		if (!$this->b199()) {
			goto f1789;}
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

				goto e1793;
			}

			$this->p++;
		}
		e1793:
		goto k1789;
		f1789:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1789:

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

					goto n1452;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b246()) {
					goto f1453;}
				if (($this->ids[$this->p] === 30)) {
					$this->v[$this->n++] = $this->token($this->p++);
				} elseif ($this->p >= $this->f) {
					$this->rec(51);
				}
				if (!($this->ids[$this->p] === 47)) {
					if ($this->p >= $this->f) {
						$this->rec(70);
					}

					goto f1453;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f1453;}
				goto k1453;
				f1453:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1452;
				k1453:
				goto a1451;
				n1452:
				if (!$this->b199()) {
					goto n1454;}
				goto a1451;
				n1454:
				break;
			case 19:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->b199()) {
					goto n1455;}
				goto a1451;
				n1455:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(244);
		}

		goto f1450;
		a1451:
		goto k1450;
		f1450:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1450:
		$this->v[$m1] = $this->reduceArrayShapeItem($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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
					goto n1662;}
				goto a1661;
				n1662:
				break;
			case 20:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1661;
				break;
			case 21:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1661;
				break;
			case 22:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1661;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(246);
		}

		goto f1660;
		a1661:
		goto k1660;
		f1660:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1660:
		$this->v[$m1] = $this->reduceArrayShapeKey($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f1747;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			// b249
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->rec(249);
				}

				goto n1748;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p >= $this->f) {
					$this->rec(66);
				}

				goto f1749;}

			$this->v[$this->n++] = $this->token($this->p++);
			if (!isset(self::T83e30518[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(144);
				}

				goto f1749;}

			$this->v[$this->n++] = $this->token($this->p++);
			goto k1749;
			f1749:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1748;
			k1749:
			n1748:
		} elseif ($this->p > $this->f) {
			$this->rec(249);
		}
		goto k1747;
		f1747:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1747:

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

			goto f1485;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 41)) {
			$this->v[$this->n++] = $this->reduceDiscardedComment($this->context($this->p, $this->p + 1));
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

				goto e1487;
			}

			$this->p++;
		}
		e1487:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->b233()) {
				goto n1488;}
			n1488:
		} elseif ($this->p > $this->f) {
			$this->rec(233);
		}
		goto k1485;
		f1485:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1485:

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
		if (!$this->b257()) {
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

			goto f851;}

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

				goto e853;
			}

			$this->p++;
		}
		e853:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->b259()) {
				goto n854;}
			n854:
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

				goto e856;
			}

			$this->p++;
		}
		e856:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f851;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k851;
		f851:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k851:
		$this->v[$m1] = $this->reduceListShape($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

					goto n1116;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b244()) {
					goto f1117;}
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

						goto e1119;
					}

					$this->p++;
				}
				e1119:
				if (($this->ids[$this->p] === 40)) {
					if (!$this->b266()) {
						goto n1120;}
					n1120:
				} elseif ($this->p > $this->f) {
					$this->rec(266);
				}
				goto k1117;
				f1117:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1116;
				k1117:
				goto a1115;
				n1116:
				break;
			case 42:
				if (!$this->b260()) {
					goto n1121;}
				goto a1115;
				n1121:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(259);
		}

		goto f1114;
		a1115:
		goto k1114;
		f1114:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1114:

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

			goto f1460;}

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

				goto e1462;
			}

			$this->p++;
		}
		e1462:
		if (!$this->b261()) {
			goto f1460;}
		if (($this->ids[$this->p] === 40)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k1460;
		f1460:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1460:

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
					goto n1665;}
				goto a1664;
				n1665:
				break;
			case 34:
			case 86:
				// b262
				if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(262);
					}

					goto n1666;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b263()) {
					goto f1667;}
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

						goto e1669;
					}

					$this->p++;
				}
				e1669:
				goto k1667;
				f1667:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1666;
				k1667:
				goto a1664;
				n1666:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1670;}
				goto a1664;
				n1670:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(261);
		}

		goto f1663;
		a1664:
		goto k1663;
		f1663:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1663:

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

			goto f1750;}

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

				goto e1752;
			}

			$this->p++;
		}
		e1752:
		if (!$this->b199()) {
			goto f1750;}
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

				goto e1754;
			}

			$this->p++;
		}
		e1754:
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1750;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1750;
		f1750:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1750:
		$this->v[$m1] = $this->reduceListShapeUnsealedType($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f1456;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 41)) {
			$this->v[$this->n++] = $this->reduceDiscardedComment($this->context($this->p, $this->p + 1));
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

				goto e1458;
			}

			$this->p++;
		}
		e1458:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->b259()) {
				goto n1459;}
			n1459:
		} elseif ($this->p > $this->f) {
			$this->rec(259);
		}
		goto k1456;
		f1456:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1456:

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

			goto f641;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f641;}
		}
		if (!$this->b268()) {
			goto f641;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n642;}

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

					goto e644;
				}

				if (!$this->b224()) {
					goto e644;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e644;
				}

				$k2++;
			}
			e644:

			if ($k2 < 1) {
				goto f643;
			}
			goto k643;
			f643:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n642;
			k643:
			n642:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k641;
		f641:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k641:

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

			goto f864;}

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

				goto e866;
			}

			$this->p++;
		}
		e866:
		if (isset(self::Td8017923[$this->ids[$this->p]])) {
			// b270
			if (!isset(self::Td8017923[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(270);
				}

				goto n867;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b271()) {
				goto f868;}
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

					goto e870;
				}

				$this->p++;
			}
			e870:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b274()) {
					goto n871;}
				n871:
			} elseif ($this->p > $this->f) {
				$this->rec(274);
			}
			goto k868;
			f868:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n867;
			k868:
			n867:
		} elseif ($this->p > $this->f) {
			$this->rec(270);
		}
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f864;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k864;
		f864:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k864:
		$this->v[$m1] = $this->reduceObjectShape($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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
			goto f1144;}
		if (($this->ids[$this->p] === 30)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(51);
		}
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1144;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b199()) {
			goto f1144;}
		goto k1144;
		f1144:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1144:
		$this->v[$m1] = $this->reduceObjectShapeItem($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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
				goto a1477;
				break;
			case 2:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 3:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 4:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 5:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 6:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 7:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 8:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 9:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 10:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 11:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 12:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 13:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 14:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 15:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 16:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 17:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 18:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 21:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 22:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
			case 24:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1477;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(272);
		}

		goto f1476;
		a1477:
		goto k1476;
		f1476:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1476:
		$this->v[$m1] = $this->reduceObjectShapeKey($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

				goto e1146;
			}

			// b275
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(275);
				}

				goto e1146;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1147;}

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

					goto e1149;
				}

				$this->p++;
			}
			e1149:
			if (!$this->b276()) {
				goto f1147;}
			goto k1147;
			f1147:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1146;
			k1147:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1146;
			}

			$k1++;
		}
		e1146:

		if ($k1 < 1) {
			goto f1145;
		}
		goto k1145;
		f1145:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1145:

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

					goto n1480;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b271()) {
					goto f1481;}
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

						goto e1483;
					}

					$this->p++;
				}
				e1483:
				goto k1481;
				f1481:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1480;
				k1481:
				goto a1479;
				n1480:
				break;
			case 39:
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p > $this->f) {
						$this->rec(62);
					}

					goto n1484;}
				goto a1479;
				n1484:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(276);
		}

		goto f1478;
		a1479:
		goto k1478;
		f1478:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1478:

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
					goto n613;}
				goto a612;
				n613:
				break;
			case 32:
			case 82:
				if (!$this->b364()) {
					goto n614;}
				goto a612;
				n614:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n615;}
				goto a612;
				n615:
				break;
			case 36:
			case 84:
				// b223
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(223);
					}

					goto n616;}

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

						goto e618;
					}

					if (!$this->b224()) {
						goto e618;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e618;
					}

					$k2++;
				}
				e618:

				if ($k2 < 1) {
					goto f617;
				}
				goto k617;
				f617:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n616;
				k617:
				goto a612;
				n616:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n619;}
				goto a612;
				n619:
				break;
			case 34:
				// b282
				if (!($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->rec(282);
					}

					goto n620;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->rec(54);
					}

					goto f621;}
				if (!$this->b284()) {
					goto f621;}
				goto k621;
				f621:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n620;
				k621:
				goto a612;
				n620:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n622;}
				goto a612;
				n622:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(281);
		}

		goto f611;
		a612:
		goto k611;
		f611:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k611:

		return true;
	}

	private function b284(): bool
	{
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(284);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 34:
				if (!$this->b285()) {
					goto n845;}
				goto a844;
				n845:
				if (!$this->b362()) {
					goto n846;}
				goto a844;
				n846:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(284);
		}

		goto f843;
		a844:
		goto k843;
		f843:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k843:

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

			goto f1094;}

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

				goto e1096;
			}

			$this->p++;
		}
		e1096:
		// b286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f1094;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1097;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f1097;}
		if (!$this->b293()) {
			goto f1097;}
		if (!$this->b296()) {
			goto f1097;}
		goto k1097;
		f1097:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1094;
		k1097:
		$this->v[$m2] = $this->reduceCallableTemplateArgument($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
		$this->n = $m2 + 1;
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

				goto e1099;
			}

			$this->p++;
		}
		e1099:
		if (($this->ids[$this->p] === 40)) {
			// b300
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(300);
				}

				goto n1100;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(301);
					}

					goto e1102;
				}

				if (!$this->b301()) {
					goto e1102;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1102;
				}

				$k2++;
			}
			e1102:

			if ($k2 < 1) {
				goto f1101;
			}
			goto k1101;
			f1101:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1100;
			k1101:
			n1100:
		} elseif ($this->p > $this->f) {
			$this->rec(300);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1094;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f1094;}

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

				goto e1104;
			}

			$this->p++;
		}
		e1104:
		if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
			// b306
			if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(306);
				}

				goto n1105;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b307()) {
				goto f1106;}
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

					goto e1108;
				}

				$this->p++;
			}
			e1108:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b311()) {
					goto n1109;}
				n1109:
			} elseif ($this->p > $this->f) {
				$this->rec(311);
			}
			goto k1106;
			f1106:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1105;
			k1106:
			n1105:
		} elseif ($this->p > $this->f) {
			$this->rec(306);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1094;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1094;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b315()) {
			goto f1094;}
		goto k1094;
		f1094:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1094:
		$this->v[$m1] = $this->reduceCallableWithTemplates($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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
						goto f529;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f529;}
				}
				goto k529;
				f529:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n528;
				k529:
				goto a527;
				n528:
				break;
			case 13:
				// b288
				if (!($this->ids[$this->p] === 13)) {
					if ($this->p > $this->f) {
						$this->rec(288);
					}

					goto n530;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 13)) {
					if ($this->p >= $this->f) {
						$this->rec(27);
					}

					goto f531;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f531;}
				goto k531;
				f531:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n530;
				k531:
				goto a527;
				n530:
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
						goto f533;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f533;}
				}
				goto k533;
				f533:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n532;
				k533:
				goto a527;
				n532:
				break;
			case 14:
				// b289
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->rec(289);
					}

					goto n534;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p >= $this->f) {
						$this->rec(28);
					}

					goto f535;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f535;}
				goto k535;
				f535:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n534;
				k535:
				goto a527;
				n534:
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
						goto f537;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f537;}
				}
				goto k537;
				f537:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n536;
				k537:
				goto a527;
				n536:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(287);
		}

		goto f526;
		a527:
		goto k526;
		f526:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k526:

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
					goto n540;}
				goto a539;
				n540:
				break;
			case 5:
				// b294
				if (!($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->rec(294);
					}

					goto n541;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 5)) {
					if ($this->p >= $this->f) {
						$this->rec(29);
					}

					goto f542;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f542;}
				goto k542;
				f542:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n541;
				k542:
				goto a539;
				n541:
				if (($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n543;}
				goto a539;
				n543:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(293);
		}

		goto f538;
		a539:
		goto k538;
		f538:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k538:

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
					goto n546;}
				goto a545;
				n546:
				break;
			case 46:
				// b297
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->rec(297);
					}

					goto n547;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p >= $this->f) {
						$this->rec(69);
					}

					goto f548;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f548;}
				goto k548;
				f548:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n547;
				k548:
				goto a545;
				n547:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n549;}
				goto a545;
				n549:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(296);
		}

		goto f544;
		a545:
		goto k544;
		f544:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k544:

		return true;
	}

	private function b301(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(301);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p >= $this->f) {
				$this->rec(63);
			}

			goto f1437;}

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

				goto e1439;
			}

			$this->p++;
		}
		e1439:
		// b302
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
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
				if (!$this->b304()) {
					goto n1442;}
				goto a1441;
				n1442:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n1443;}
				goto a1441;
				n1443:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(302);
		}

		goto f1440;
		a1441:
		goto k1440;
		f1440:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1437;
		k1440:
		goto k1437;
		f1437:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1437:

		return true;
	}

	private function b304(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(304);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		// b286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f1656;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1657;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f1657;}
		if (!$this->b293()) {
			goto f1657;}
		if (!$this->b296()) {
			goto f1657;}
		goto k1657;
		f1657:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1656;
		k1657:
		$this->v[$m2] = $this->reduceCallableTemplateArgument($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
		$this->n = $m2 + 1;
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

				goto e1659;
			}

			$this->p++;
		}
		e1659:
		goto k1656;
		f1656:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1656:

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
			goto f1059;}
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
		goto k1059;
		f1059:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1059:
		$this->v[$m1] = $this->reduceCallableParameter($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

				goto e1061;
			}

			// b312
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(312);
				}

				goto e1061;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1062;}

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

					goto e1064;
				}

				$this->p++;
			}
			e1064:
			if (!$this->b313()) {
				goto f1062;}
			goto k1062;
			f1062:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1061;
			k1062:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1061;
			}

			$k1++;
		}
		e1061:

		if ($k1 < 1) {
			goto f1060;
		}
		goto k1060;
		f1060:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1060:

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

					goto n1418;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b307()) {
					goto f1419;}
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
				goto k1419;
				f1419:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1418;
				k1419:
				goto a1417;
				n1418:
				break;
			case 33:
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p > $this->f) {
						$this->rec(53);
					}

					goto n1422;}
				goto a1417;
				n1422:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(313);
		}

		goto f1416;
		a1417:
		goto k1416;
		f1416:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1416:

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

					goto n1067;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(332);
					}

					goto f1068;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1068;}
				}
				if (!$this->b318()) {
					goto f1068;}
				goto k1068;
				f1068:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1067;
				k1068:
				goto a1066;
				n1067:
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
				goto a1066;
				n1069:
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

					goto n1072;}

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
						goto f1073;}
				}
				if (!$this->b335()) {
					goto f1073;}
				goto k1073;
				f1073:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1072;
				k1073:
				goto a1066;
				n1072:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->b255()) {
					goto n1075;}
				goto a1066;
				n1075:
				if (!$this->b317()) {
					goto n1076;}
				goto a1066;
				n1076:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1077;}

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
						goto f1078;}
				}
				if (!$this->b335()) {
					goto f1078;}
				goto k1078;
				f1078:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1077;
				k1078:
				goto a1066;
				n1077:
				break;
			case 32:
			case 82:
				if (!$this->b316()) {
					goto n1080;}
				goto a1066;
				n1080:
				break;
			case 6:
				if (!$this->b267()) {
					goto n1081;}
				goto a1066;
				n1081:
				if (!$this->b317()) {
					goto n1082;}
				goto a1066;
				n1082:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1083;}

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
						goto f1084;}
				}
				if (!$this->b335()) {
					goto f1084;}
				goto k1084;
				f1084:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1083;
				k1084:
				goto a1066;
				n1083:
				break;
			case 7:
				if (!$this->b230()) {
					goto n1086;}
				goto a1066;
				n1086:
				if (!$this->b317()) {
					goto n1087;}
				goto a1066;
				n1087:
				// b333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1088;}

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
						goto f1089;}
				}
				if (!$this->b335()) {
					goto f1089;}
				goto k1089;
				f1089:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1088;
				k1089:
				goto a1066;
				n1088:
				break;
			case 25:
				if (!$this->b229()) {
					goto n1091;}
				goto a1066;
				n1091:
				break;
			case 30:
				// b200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n1092;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f1093;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b201()) {
					goto f1093;}
				goto k1093;
				f1093:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1092;
				k1093:
				$this->v[$m2] = $this->reduceNullable($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a1066;
				n1092:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(315);
		}

		goto f1065;
		a1066:
		goto k1065;
		f1065:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1065:
		$this->v[$m1] = $this->reduceCallableReturnType($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f1433;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b205()) {
			goto f1433;}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1433;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1434;}

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

					goto e1436;
				}

				if (!$this->b224()) {
					goto e1436;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1436;
				}

				$k2++;
			}
			e1436:

			if ($k2 < 1) {
				goto f1435;
			}
			goto k1435;
			f1435:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1434;
			k1435:
			n1434:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1433;
		f1433:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1433:

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

			goto f1432;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f1432;}
		}
		if (($this->ids[$this->p] === 38)) {
			if (($this->ids[$this->p] === 38)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f1432;}
		}
		if (!$this->b318()) {
			goto f1432;}
		goto k1432;
		f1432:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1432:

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
					goto n1425;}
				goto a1424;
				n1425:
				break;
			case 34:
			case 86:
				if (!$this->b319()) {
					goto n1426;}
				goto a1424;
				n1426:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1427;}
				goto a1424;
				n1427:
				break;
			case 36:
			case 84:
				// b223
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(223);
					}

					goto n1428;}

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

						goto e1430;
					}

					if (!$this->b224()) {
						goto e1430;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e1430;
					}

					$k2++;
				}
				e1430:

				if ($k2 < 1) {
					goto f1429;
				}
				goto k1429;
				f1429:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1428;
				k1429:
				goto a1424;
				n1428:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1431;}
				goto a1424;
				n1431:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(318);
		}

		goto f1423;
		a1424:
		goto k1423;
		f1423:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1423:

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
			goto f1652;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1653;}

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

					goto e1655;
				}

				if (!$this->b224()) {
					goto e1655;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1655;
				}

				$k2++;
			}
			e1655:

			if ($k2 < 1) {
				goto f1654;
			}
			goto k1654;
			f1654:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1653;
			k1654:
			n1653:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1652;
		f1652:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1652:

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

			goto f787;}

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

				goto e789;
			}

			$this->p++;
		}
		e789:
		if (!$this->b321()) {
			goto f787;}
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

				goto e791;
			}

			$this->p++;
		}
		e791:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->b327()) {
				goto n792;}
			n792:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f787;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k787;
		f787:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k787:
		$this->v[$m1] = $this->reduceGenericInReturnType($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

					goto n1027;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T05cf281c[$this->ids[$this->p]])) {
					if (isset(self::T05cf281c[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1028;}
				}
				if (!$this->b199()) {
					goto f1028;}
				goto k1028;
				f1028:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1027;
				k1028:
				goto a1026;
				n1027:
				break;
			case 1:
			case 2:
				// b322
				if (!isset(self::T05cf281c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(322);
					}

					goto n1029;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T05cf281c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(323);
					}

					goto f1030;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f1030;}
				goto k1030;
				f1030:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1029;
				k1030:
				goto a1026;
				n1029:
				break;
			case 50:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1026;
				break;
			case 85:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1026;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(321);
		}

		goto f1025;
		a1026:
		goto k1025;
		f1025:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1025:
		$this->v[$m1] = $this->reduceGenericArgument($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

				goto e1032;
			}

			// b328
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(328);
				}

				goto e1032;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1033;}

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

					goto e1035;
				}

				$this->p++;
			}
			e1035:
			if (!$this->b329()) {
				goto f1033;}
			goto k1033;
			f1033:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1032;
			k1033:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1032;
			}

			$k1++;
		}
		e1032:

		if ($k1 < 1) {
			goto f1031;
		}
		goto k1031;
		f1031:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1031:

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

					goto n1392;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b321()) {
					goto f1393;}
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

						goto e1395;
					}

					$this->p++;
				}
				e1395:
				goto k1393;
				f1393:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1392;
				k1393:
				goto a1391;
				n1392:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n1396;}
				goto a1391;
				n1396:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(329);
		}

		goto f1390;
		a1391:
		goto k1390;
		f1390:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1390:

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
				goto f624;}
		}
		if (!$this->b340()) {
			goto f624;}
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
		goto k624;
		f624:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k624:

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
					goto n184;}
				goto a183;
				n184:
				break;
			case 7:
			case 18:
				if (!$this->b347()) {
					goto n185;}
				goto a183;
				n185:
				break;
			case 21:
			case 22:
				// b343
				if (!isset(self::T24d0f25b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(343);
					}

					goto n186;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 21:
						$this->v[$this->n++] = $this->token($this->p++);
						goto a188;
						break;
					case 22:
						$this->v[$this->n++] = $this->token($this->p++);
						goto a188;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(343);
				}

				goto f187;
				a188:
				goto k187;
				f187:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n186;
				k187:
				$this->v[$m2] = $this->reduceConstantString($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
				$this->n = $m2 + 1;
				goto a183;
				n186:
				break;
			case 36:
			case 84:
				if (!$this->b361()) {
					goto n189;}
				goto a183;
				n189:
				break;
			case 15:
				$this->v[$this->n++] = $this->reduceConstantFalse($this->context($this->p, $this->p + 1));
				$this->p++;
				goto a183;
				break;
			case 16:
				$this->v[$this->n++] = $this->reduceConstantTrue($this->context($this->p, $this->p + 1));
				$this->p++;
				goto a183;
				break;
			case 17:
				$this->v[$this->n++] = $this->reduceConstantNull($this->context($this->p, $this->p + 1));
				$this->p++;
				goto a183;
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceConstantFloat($this->context($this->p, $this->p + 1), [$this->token($this->p)]);
				$this->p++;
				goto a183;
				break;
			case 20:
				$this->v[$this->n++] = $this->reduceConstantInteger($this->context($this->p, $this->p + 1), [$this->token($this->p)]);
				$this->p++;
				goto a183;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(340);
		}

		goto f182;
		a183:
		goto k182;
		f182:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k182:

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

			goto f449;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f449;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if (!$this->b349()) {
				goto n450;}
			n450:
		} elseif ($this->p > $this->f) {
			$this->rec(349);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f449;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k449;
		f449:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k449:
		$this->v[$m1] = $this->reduceConstantArrayLiteral($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f715;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b340()) {
			goto f716;}
		if (!$this->b351()) {
			goto f716;}
		goto k716;
		f716:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f715;
		k716:
		$this->v[$m2] = $this->reduceConstantArrayItem($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
		$this->n = $m2 + 1;
		if (($this->ids[$this->p] === 40)) {
			// b355
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(355);
				}

				goto n717;}

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

					goto e719;
				}

				if (!$this->b356()) {
					goto e719;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e719;
				}

				$k2++;
			}
			e719:

			if ($k2 < 1) {
				goto f718;
			}
			goto k718;
			f718:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n717;
			k718:
			n717:
		} elseif ($this->p > $this->f) {
			$this->rec(355);
		}
		if (($this->ids[$this->p] === 40)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
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
					goto n956;}
				goto a955;
				n956:
				break;
			case 44:
				// b352
				if (!($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->rec(352);
					}

					goto n957;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 44)) {
					if ($this->p >= $this->f) {
						$this->rec(67);
					}

					goto f958;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b340()) {
					goto f958;}
				goto k958;
				f958:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n957;
				k958:
				goto a955;
				n957:
				if (($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n959;}
				goto a955;
				n959:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(351);
		}

		goto f954;
		a955:
		goto k954;
		f954:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k954:

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

			goto f960;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b350
		if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(350);
			}

			goto f960;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b340()) {
			goto f961;}
		if (!$this->b351()) {
			goto f961;}
		goto k961;
		f961:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f960;
		k961:
		$this->v[$m2] = $this->reduceConstantArrayItem($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
		$this->n = $m2 + 1;
		goto k960;
		f960:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k960:

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
				goto f446;}
		}
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f446;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			// b360
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->rec(360);
				}

				goto n447;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p >= $this->f) {
					$this->rec(66);
				}

				goto f448;}

			$this->v[$this->n++] = $this->token($this->p++);
			if (!$this->b163()) {
				goto f448;}
			goto k448;
			f448:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n447;
			k448:
			n447:
		} elseif ($this->p > $this->f) {
			$this->rec(360);
		}
		goto k446;
		f446:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k446:
		$this->v[$m1] = $this->reduceConstantFetch($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f451;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if (!$this->b349()) {
				goto n452;}
			n452:
		} elseif ($this->p > $this->f) {
			$this->rec(349);
		}
		if (!($this->ids[$this->p] === 37)) {
			if ($this->p >= $this->f) {
				$this->rec(59);
			}

			goto f451;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k451;
		f451:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k451:
		$this->v[$m1] = $this->reduceConstantArrayShorthand($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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
			goto f1110;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1111;}

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

					goto e1113;
				}

				if (!$this->b224()) {
					goto e1113;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1113;
				}

				$k2++;
			}
			e1113:

			if ($k2 < 1) {
				goto f1112;
			}
			goto k1112;
			f1112:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1111;
			k1112:
			n1111:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1110;
		f1110:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1110:

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

			goto f1444;}

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

				goto e1446;
			}

			$this->p++;
		}
		e1446:
		if (!$this->b321()) {
			goto f1444;}
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

				goto e1448;
			}

			$this->p++;
		}
		e1448:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->b327()) {
				goto n1449;}
			n1449:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1444;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1444;
		f1444:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1444:
		$this->v[$m1] = $this->reduceGeneric($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f827;}

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

				goto e829;
			}

			$this->p++;
		}
		e829:
		if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
			// b306
			if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(306);
				}

				goto n830;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b307()) {
				goto f831;}
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

					goto e833;
				}

				$this->p++;
			}
			e833:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b311()) {
					goto n834;}
				n834:
			} elseif ($this->p > $this->f) {
				$this->rec(311);
			}
			goto k831;
			f831:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n830;
			k831:
			n830:
		} elseif ($this->p > $this->f) {
			$this->rec(306);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f827;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f827;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b315()) {
			goto f827;}
		goto k827;
		f827:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k827:
		$this->v[$m1] = $this->reduceCallable($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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
						goto f399;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f399;}
				}
				goto k399;
				f399:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n398;
				k399:
				goto a397;
				n398:
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

					goto n400;}

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

						goto e403;
					}

					$this->p++;
				}
				e403:
				if (!$this->b370()) {
					goto f401;}
				goto k401;
				f401:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n400;
				k401:
				goto a397;
				n400:
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
						goto f405;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f405;}
				}
				goto k405;
				f405:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n404;
				k405:
				goto a397;
				n404:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(368);
		}

		goto f396;
		a397:
		goto k396;
		f396:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k396:

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
					goto n655;}
				goto a654;
				n655:
				break;
			case 29:
				if (!$this->b377()) {
					goto n656;}
				goto a654;
				n656:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(370);
		}

		goto f653;
		a654:
		goto k653;
		f653:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k653:

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

			goto f878;}

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

				goto e880;
			}

			if (!$this->b373()) {
				goto e880;}
			if ($this->p === $b2) {
				$k2 = 1;
				goto e880;
			}

			$k2++;
		}
		e880:

		if ($k2 < 1) {
			goto f879;
		}
		goto k879;
		f879:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f878;
		k879:
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f878;}
		}
		goto k878;
		f878:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k878:

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

			goto f1158;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b201()) {
			goto f1158;}
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

				goto e1162;
			}

			$this->p++;
		}
		e1162:
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(49);
			}

			goto f1160;}
		goto k1160;
		f1160:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto n1159;
		k1160:
		n1159:
		goto k1158;
		f1158:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1158:

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

			goto f881;}

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

				goto e883;
			}

			if (!$this->b379()) {
				goto e883;}
			if ($this->p === $b2) {
				$k2 = 1;
				goto e883;
			}

			$k2++;
		}
		e883:

		if ($k2 < 1) {
			goto f882;
		}
		goto k882;
		f882:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f881;
		k882:
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f881;}
		}
		goto k881;
		f881:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k881:

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

			goto f1163;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b201()) {
			goto f1163;}
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

				goto e1167;
			}

			$this->p++;
		}
		e1167:
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(50);
			}

			goto f1165;}
		goto k1165;
		f1165:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto n1164;
		k1165:
		n1164:
		goto k1163;
		f1163:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1163:

		return true;
	}

	private function b385(): bool
	{
		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				// b388
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f317;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f317;}
				}
				if (($this->ids[$this->p] === 32)) {
					if (($this->ids[$this->p] === 32)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f317;}
				}
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f317;}
				$this->v[$this->n++] = $this->reduceText($this->context($o3));
				goto k317;
				f317:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n316;
				k317:
				goto a315;
				n316:
				break;
			case 25:
			case 26:
				// b386
				if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(386);
					}

					goto n319;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(387);
					}

					goto f320;}

				$this->v[$this->n++] = $this->token($this->p++);
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f320;}
				$this->v[$this->n++] = $this->reduceText($this->context($o3));
				goto k320;
				f320:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n319;
				k320:
				goto a315;
				n319:
				// b388
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f323;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f323;}
				}
				if (($this->ids[$this->p] === 32)) {
					if (($this->ids[$this->p] === 32)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f323;}
				}
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f323;}
				$this->v[$this->n++] = $this->reduceText($this->context($o3));
				goto k323;
				f323:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n322;
				k323:
				goto a315;
				n322:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(385);
		}

		goto f314;
		a315:
		goto k314;
		f314:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k314:

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
				goto f281;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f281;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f281;}
		}
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f281;}
		$this->v[$this->n++] = $this->reduceText($this->context($o2));
		goto k281;
		f281:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k281:

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

			goto f289;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b399
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(399);
			}

			goto f289;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b199()) {
			goto f290;}
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f290;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f290;}
		$this->v[$this->n++] = $this->reduceText($this->context($o3));
		goto k290;
		f290:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f289;
		k290:
		goto k289;
		f289:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k289:

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

			goto f296;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b399
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(399);
			}

			goto f296;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b199()) {
			goto f297;}
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f297;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f297;}
		$this->v[$this->n++] = $this->reduceText($this->context($o3));
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

			goto f278;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b399
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(399);
			}

			goto f278;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b199()) {
			goto f279;}
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f279;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f279;}
		$this->v[$this->n++] = $this->reduceText($this->context($o3));
		goto k279;
		f279:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f278;
		k279:
		goto k278;
		f278:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k278:

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

			goto f266;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b403
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p > $this->f) {
				$this->rec(403);
			}

			goto f266;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f267;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f267;}
		$this->v[$this->n++] = $this->reduceText($this->context($o3));
		goto k267;
		f267:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f266;
		k267:
		goto k266;
		f266:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k266:

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

			goto f275;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b403
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p > $this->f) {
				$this->rec(403);
			}

			goto f275;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f276;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f276;}
		$this->v[$this->n++] = $this->reduceText($this->context($o3));
		goto k276;
		f276:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f275;
		k276:
		goto k275;
		f275:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k275:

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

			goto f269;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b403
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p > $this->f) {
				$this->rec(403);
			}

			goto f269;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f270;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f270;}
		$this->v[$this->n++] = $this->reduceText($this->context($o3));
		goto k270;
		f270:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f269;
		k270:
		goto k269;
		f269:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k269:

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

			goto f272;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b403
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p > $this->f) {
				$this->rec(403);
			}

			goto f272;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f273;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f273;}
		$this->v[$this->n++] = $this->reduceText($this->context($o3));
		goto k273;
		f273:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f272;
		k273:
		goto k272;
		f272:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k272:

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

					goto n302;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b199()) {
					goto f303;}
				if (!$this->b415()) {
					goto f303;}
				goto k303;
				f303:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n302;
				k303:
				goto a301;
				n302:
				break;
			case 9:
				// b410
				if (!($this->ids[$this->p] === 9)) {
					if ($this->p > $this->f) {
						$this->rec(410);
					}

					goto n304;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 9)) {
					if ($this->p >= $this->f) {
						$this->rec(25);
					}

					goto f305;}

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
						goto f305;}
				}
				if (!$this->b199()) {
					goto f305;}
				if (!$this->b415()) {
					goto f305;}
				goto k305;
				f305:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n304;
				k305:
				goto a301;
				n304:
				// b435
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(435);
					}

					goto n307;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b199()) {
					goto f308;}
				if (!$this->b415()) {
					goto f308;}
				goto k308;
				f308:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n307;
				k308:
				goto a301;
				n307:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(409);
		}

		goto f300;
		a301:
		goto k300;
		f300:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k300:

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

					goto n555;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f556;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b417()) {
					goto f556;}
				goto k556;
				f556:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n555;
				k556:
				goto a554;
				n555:
				break;
			case 32:
			case 34:
			case 82:
			case 86:
				if (!$this->b417()) {
					goto n557;}
				goto a554;
				n557:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(415);
		}

		goto f553;
		a554:
		goto k553;
		f553:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k553:

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
		// b418
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n796;}
				goto a795;
				n796:
				break;
			case 34:
			case 86:
				if (!$this->b419()) {
					goto n797;}
				goto a795;
				n797:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n798;}
				goto a795;
				n798:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(418);
		}

		goto f794;
		a795:
		goto k794;
		f794:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f793;
		k794:
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f793;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T9373805c[$this->ids[$this->p]])) {
			// b424
			if (!isset(self::T9373805c[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(424);
				}

				goto n799;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b425()) {
				goto f800;}
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b433()) {
					goto n801;}
				n801:
			} elseif ($this->p > $this->f) {
				$this->rec(433);
			}
			goto k800;
			f800:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n799;
			k800:
			n799:
		} elseif ($this->p > $this->f) {
			$this->rec(424);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f793;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f793;}
		$this->v[$this->n++] = $this->reduceText($this->context($o2));
		goto k793;
		f793:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k793:

		return true;
	}

	private function b419(): bool
	{
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(419);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(238);
			}

			goto f1036;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f1036;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1037;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f1037;}
		if (!$this->b293()) {
			goto f1037;}
		if (!$this->b296()) {
			goto f1037;}
		goto k1037;
		f1037:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1036;
		k1037:
		$this->v[$m2] = $this->reduceCallableTemplateArgument($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
		$this->n = $m2 + 1;
		if (($this->ids[$this->p] === 40)) {
			// b421
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(421);
				}

				goto n1038;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(422);
					}

					goto e1040;
				}

				if (!$this->b422()) {
					goto e1040;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1040;
				}

				$k2++;
			}
			e1040:

			if ($k2 < 1) {
				goto f1039;
			}
			goto k1039;
			f1039:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1038;
			k1039:
			n1038:
		} elseif ($this->p > $this->f) {
			$this->rec(421);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1036;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1036;
		f1036:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1036:

		return true;
	}

	private function b422(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(422);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p >= $this->f) {
				$this->rec(63);
			}

			goto f1397;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f1397;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1398;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f1398;}
		if (!$this->b293()) {
			goto f1398;}
		if (!$this->b296()) {
			goto f1398;}
		goto k1398;
		f1398:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1397;
		k1398:
		$this->v[$m2] = $this->reduceCallableTemplateArgument($this->context($o2), $this->n === $m2 + 1 && !\is_array($this->v[$m2]) ? [$this->v[$m2]] : $this->merge($m2));
		$this->n = $m2 + 1;
		goto k1397;
		f1397:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1397:

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
					goto n1043;}
				goto a1042;
				n1043:
				break;
			case 26:
			case 27:
			case 42:
				// b431
				if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(431);
					}

					goto n1044;}

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

					goto f1045;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b429()) {
					goto f1045;}
				goto k1045;
				f1045:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1044;
				k1045:
				goto a1042;
				n1044:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(425);
		}

		goto f1041;
		a1042:
		goto k1041;
		f1041:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1041:
		$this->v[$m1] = $this->reduceMethodParameter($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f1399;}
		if (!$this->b199()) {
			goto f1399;}
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

			goto f1399;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b429()) {
			goto f1399;}
		goto k1399;
		f1399:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1399:

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
					goto n1402;}
				goto a1401;
				n1402:
				break;
			case 46:
				// b430
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->rec(430);
					}

					goto n1403;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p >= $this->f) {
						$this->rec(69);
					}

					goto f1404;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b340()) {
					goto f1404;}
				goto k1404;
				f1404:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1403;
				k1404:
				goto a1401;
				n1403:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1405;}
				goto a1401;
				n1405:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(429);
		}

		goto f1400;
		a1401:
		goto k1400;
		f1400:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1400:

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

				goto e1047;
			}

			// b434
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(434);
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
			if (!$this->b425()) {
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

			goto f286;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f286;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f286;}
		if (!$this->b293()) {
			goto f286;}
		if (!$this->b296()) {
			goto f286;}
		// b388
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f287;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f287;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f287;}
		}
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f287;}
		$this->v[$this->n++] = $this->reduceText($this->context($o3));
		goto k287;
		f287:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f286;
		k287:
		goto k286;
		f286:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k286:

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

			goto f299;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b438()) {
			goto f299;}
		goto k299;
		f299:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k299:

		return true;
	}

	private function b438(): bool
	{
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(438);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f550;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b320()) {
			goto f550;}
		// b388
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f551;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f551;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f551;}
		}
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f551;}
		$this->v[$this->n++] = $this->reduceText($this->context($o3));
		goto k551;
		f551:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f550;
		k551:
		goto k550;
		f550:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k550:

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

			goto f292;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b438()) {
			goto f292;}
		goto k292;
		f292:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k292:

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

			goto f313;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b438()) {
			goto f313;}
		goto k313;
		f313:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k313:

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

			goto f293;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f293;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 46)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(69);
		}
		if (!$this->b199()) {
			goto f293;}
		if (!isset(self::T53b29bff[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(77);
			}

			goto f293;}
		goto k293;
		f293:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k293:

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

			goto f294;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f294;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 46)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(69);
		}
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f294;}
		$this->v[$this->n++] = $this->reduceText($this->context($o2));
		goto k294;
		f294:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k294:
		$this->v[$m1] = $this->reduceInvalidTypeAliasTag($this->context($o1), $this->n === $m1 + 1 && !\is_array($this->v[$m1]) ? [$this->v[$m1]] : $this->merge($m1));
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

			goto f283;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f283;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 10)) {
			if ($this->p >= $this->f) {
				$this->rec(26);
			}

			goto f283;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f283;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b444()) {
			goto f283;}
		goto k283;
		f283:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k283:

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
					goto n516;}
				goto a515;
				n516:
				break;
			case 14:
				// b445
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->rec(445);
					}

					goto n517;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p >= $this->f) {
						$this->rec(28);
					}

					goto f518;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f518;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k518;
				f518:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n517;
				k518:
				goto a515;
				n517:
				if (($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n519;}
				goto a515;
				n519:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(444);
		}

		goto f514;
		a515:
		goto k514;
		f514:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k514:

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

			goto f284;}

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
			goto f284;}
		if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(448);
			}

			goto f284;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b449()) {
			goto f284;}
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f284;}
		$this->v[$this->n++] = $this->reduceText($this->context($o2));
		goto k284;
		f284:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k284:

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
					goto n522;}
				goto a521;
				n522:
				break;
			case 45:
				// b450
				if (!($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->rec(450);
					}

					goto n523;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 45)) {
					if ($this->p >= $this->f) {
						$this->rec(68);
					}

					goto f524;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f524;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b451()) {
					goto f524;}
				goto k524;
				f524:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n523;
				k524:
				goto a521;
				n523:
				if (($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n525;}
				goto a521;
				n525:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(449);
		}

		goto f520;
		a521:
		goto k520;
		f520:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k520:

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
					goto n783;}
				goto a782;
				n783:
				break;
			case 32:
			case 82:
				// b452
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(452);
					}

					goto n784;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(16);
					}

					goto f785;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p >= $this->f) {
						$this->rec(53);
					}

					goto f785;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k785;
				f785:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n784;
				k785:
				goto a782;
				n784:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n786;}
				goto a782;
				n786:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(451);
		}

		goto f781;
		a782:
		goto k781;
		f781:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k781:

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
					goto f207;}
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

							goto n210;}
						goto a209;
						n210:
						break;
					case 80:
						$this->p++;
						goto a209;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f208;
				a209:
				goto k208;
				f208:
				$this->p = $o3;
				goto f207;
				k208:
				goto k207;
				f207:
				$this->p = $o2;
				goto n206;
				k207:
				goto a205;
				n206:
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

					goto n211;}

				$o2 = $this->p;
				if (!$this->s10()) {
					goto f212;}
				if (($this->ids[$this->p] === 80)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(3);
				}
				goto k212;
				f212:
				$this->p = $o2;
				goto n211;
				k212:
				goto a205;
				n211:
				// s188
				$o2 = $this->p;
				if (!$this->s189()) {
					goto f214;}
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

							goto n217;}
						goto a216;
						n217:
						break;
					case 80:
						$this->p++;
						goto a216;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f215;
				a216:
				goto k215;
				f215:
				$this->p = $o3;
				goto f214;
				k215:
				goto k214;
				f214:
				$this->p = $o2;
				goto n213;
				k214:
				goto a205;
				n213:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(8);
		}

		goto f204;
		a205:
		goto k204;
		f204:
		$this->p = $o1;
		return false;
		k204:

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

					goto f230;}
				if (!$this->s78()) {
					goto f230;}
				goto k230;
				f230:
				$this->p = $o2;
				goto n229;
				k230:
				goto a228;
				n229:
				// s117
				$o2 = $this->p;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f232;}
				if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
					if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f232;}
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
						goto f232;}
				}
				if (!$this->s125()) {
					goto f232;}
				goto k232;
				f232:
				$this->p = $o2;
				goto n231;
				k232:
				goto a228;
				n231:
				break;
			case 76:
			case 78:
				// s75
				$o2 = $this->p;
				if (!isset(self::T53b29bff[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(77);
					}

					goto f235;}
				if (!$this->s78()) {
					goto f235;}
				goto k235;
				f235:
				$this->p = $o2;
				goto n234;
				k235:
				goto a228;
				n234:
				// s117
				$o2 = $this->p;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f237;}
				if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
					if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f237;}
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
						goto f237;}
				}
				if (!$this->s125()) {
					goto f237;}
				goto k237;
				f237:
				$this->p = $o2;
				goto n236;
				k237:
				goto a228;
				n236:
				if (!isset(self::Tb3a20a39[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(121);
					}

					goto n239;}
				goto a228;
				n239:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(74);
		}

		goto f227;
		a228:
		goto k227;
		f227:
		$this->p = $o1;
		return false;
		k227:

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
						goto n489;}
				}
				goto a488;
				n489:
				break;
			case 80:
				if (!$this->s79()) {
					goto n491;}
				goto a488;
				n491:
				if (!$this->s110()) {
					goto n492;}
				goto a488;
				n492:
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
						goto n493;}
				}
				goto a488;
				n493:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(78);
		}

		goto f487;
		a488:
		goto k487;
		f487:
		$this->p = $o1;
		return false;
		k487:

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

			goto f745;}

		$this->p++;
		if (isset(self::T195b3f33[$this->ids[$this->p]])) {
			if (isset(self::T195b3f33[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f745;}
		}
		// s109
		if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(109);
			}

			goto f745;}

		$o2 = $this->p;
		$k2 = 0;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(21);
				}

				goto e747;
			}

			$this->p++;

			$k2++;
		}
		e747:

		if ($k2 < 1) {
			goto f746;
		}
		goto k746;
		f746:
		$this->p = $o2;
		goto f745;
		k746:
		if (!$this->s74()) {
			goto f745;}
		goto k745;
		f745:
		$this->p = $o1;
		return false;
		k745:

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

			goto f748;}

		$this->p++;
		if (isset(self::T195b3f33[$this->ids[$this->p]])) {
			if (isset(self::T195b3f33[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f748;}
		}
		// s111
		if (!($this->ids[$this->p] === 80)) {
			if ($this->p > $this->f) {
				$this->rec(111);
			}

			goto f748;}

		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 80:
				if (!$this->s79()) {
					goto n751;}
				goto a750;
				n751:
				if (!$this->s110()) {
					goto n752;}
				goto a750;
				n752:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(111);
		}

		goto f749;
		a750:
		goto k749;
		f749:
		$this->p = $o2;
		goto f748;
		k749:
		goto k748;
		f748:
		$this->p = $o1;
		return false;
		k748:

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

					goto n741;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($this->p >= $this->f) {
						$this->rec(3);
					}

					goto f742;}

				$this->p++;
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if (isset(self::T195b3f33[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f742;}
				}
				if (!$this->s113()) {
					goto f742;}
				goto k742;
				f742:
				$this->p = $o2;
				goto n741;
				k742:
				goto a740;
				n741:
				// s115
				if (!($this->ids[$this->p] === 80)) {
					if ($this->p > $this->f) {
						$this->rec(115);
					}

					goto n743;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($this->p >= $this->f) {
						$this->rec(3);
					}

					goto f744;}

				$this->p++;
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if (isset(self::T195b3f33[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f744;}
				}
				if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(116);
					}

					goto f744;}

				$this->p++;
				goto k744;
				f744:
				$this->p = $o2;
				goto n743;
				k744:
				goto a740;
				n743:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(113);
		}

		goto f739;
		a740:
		goto k739;
		f739:
		$this->p = $o1;
		return false;
		k739:

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

			goto f495;}

		$this->p++;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f495;}

		$this->p++;
		goto k495;
		f495:
		$this->p = $o1;
		return false;
		k495:

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

				goto e259;
			}

			$this->p++;
		}
		e259:
		if (($this->ids[$this->p] === 80)) {
			if (!$this->s128()) {
				goto n260;}
			n260:
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

				goto e510;
			}

			// s129
			if (!($this->ids[$this->p] === 80)) {
				if ($this->p > $this->f) {
					$this->rec(129);
				}

				goto e510;}

			$o2 = $this->p;
			if (!$this->s130()) {
				goto f511;}
			// s132
			if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(132);
				}

				goto f511;}

			$o3 = $this->p;
			$k3 = 0;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(116);
					}

					goto e513;
				}

				$this->p++;

				$k3++;
			}
			e513:

			if ($k3 < 1) {
				goto f512;
			}
			goto k512;
			f512:
			$this->p = $o3;
			goto f511;
			k512:
			goto k511;
			f511:
			$this->p = $o2;
			goto e510;
			k511:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e510;
			}

			$k1++;
		}
		e510:

		if ($k1 < 1) {
			goto f509;
		}
		goto k509;
		f509:
		$this->p = $o1;
		return false;
		k509:

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

				goto e779;
			}

			// s131
			if (!($this->ids[$this->p] === 80)) {
				if ($this->p > $this->f) {
					$this->rec(131);
				}

				goto e779;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($this->p >= $this->f) {
					$this->rec(3);
				}

				goto f780;}

			$this->p++;
			if (isset(self::T195b3f33[$this->ids[$this->p]])) {
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto f780;}
			}
			goto k780;
			f780:
			$this->p = $o2;
			goto e779;
			k780:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e779;
			}

			$k1++;
		}
		e779:

		if ($k1 < 1) {
			goto f778;
		}
		goto k778;
		f778:
		$this->p = $o1;
		return false;
		k778:

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

			goto f248;}

		$this->p++;
		if (!$this->s137()) {
			goto f248;}
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

				goto e251;
			}

			$this->p++;
		}
		e251:
		if (!$this->s74()) {
			goto f249;}
		goto k249;
		f249:
		$this->p = $o2;
		goto f248;
		k249:
		goto k248;
		f248:
		$this->p = $o1;
		return false;
		k248:

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
					goto n502;}
				goto a501;
				n502:
				break;
			case 32:
			case 82:
				// s138
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(138);
					}

					goto n503;}

				$o2 = $this->p;
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(16);
					}

					goto f504;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e506;
					}

					$this->p++;
				}
				e506:
				if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
					if (!$this->s141()) {
						goto n507;}
					n507:
				} elseif ($this->p > $this->f) {
					$this->rec(141);
				}
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p >= $this->f) {
						$this->rec(53);
					}

					goto f504;}

				$this->p++;
				goto k504;
				f504:
				$this->p = $o2;
				goto n503;
				k504:
				goto a501;
				n503:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n508;}
				goto a501;
				n508:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(137);
		}

		goto f500;
		a501:
		goto k500;
		f500:
		$this->p = $o1;
		return false;
		k500:

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

			goto f1618;}

		$this->p++;
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1620;
			}

			$this->p++;
		}
		e1620:
		if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			// s141
			if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(141);
				}

				goto n1621;}

			$o2 = $this->p;
			if (!$this->s142()) {
				goto f1622;}
			// s139
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e1624;
				}

				$this->p++;
			}
			e1624:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->s182()) {
					goto n1625;}
				n1625:
			} elseif ($this->p > $this->f) {
				$this->rec(182);
			}
			goto k1622;
			f1622:
			$this->p = $o2;
			goto n1621;
			k1622:
			n1621:
		} elseif ($this->p > $this->f) {
			$this->rec(141);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1618;}

		$this->p++;
		goto k1618;
		f1618:
		$this->p = $o1;
		return false;
		k1618:

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
			goto f496;}
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e498;
			}

			$this->p++;
		}
		e498:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s182()) {
				goto n499;}
			n499:
		} elseif ($this->p > $this->f) {
			$this->rec(182);
		}
		goto k496;
		f496:
		$this->p = $o1;
		return false;
		k496:

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
					goto n755;}
				goto a754;
				n755:
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
					goto n756;}
				goto a754;
				n756:
				// s172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n757;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f758;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f758;}
				}
				goto k758;
				f758:
				$this->p = $o2;
				goto n757;
				k758:
				goto a754;
				n757:
				// s160
				if (!isset(self::T96867045[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(160);
					}

					goto n759;}

				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f760;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f760;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f760;}

				$this->p++;
				if (!$this->s163()) {
					goto f760;}
				goto k760;
				f760:
				$this->p = $o2;
				goto n759;
				k760:
				goto a754;
				n759:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				if (!$this->s143()) {
					goto n761;}
				goto a754;
				n761:
				// s172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n762;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f763;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f763;}
				}
				goto k763;
				f763:
				$this->p = $o2;
				goto n762;
				k763:
				goto a754;
				n762:
				break;
			case 22:
			case 23:
				// s155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n764;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->s156()) {
							goto n767;}
						goto a766;
						n767:
						break;
					case 23:
						$this->p++;
						goto a766;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f765;
				a766:
				goto k765;
				f765:
				$this->p = $o2;
				goto n764;
				k765:
				goto a754;
				n764:
				break;
			case 38:
			case 83:
				// s148
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(148);
					}

					goto n768;}

				$o2 = $this->p;
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(149);
					}

					goto f769;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e771;
					}

					$this->p++;
				}
				e771:
				if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
					if (!$this->s151()) {
						goto n772;}
					n772:
				} elseif ($this->p > $this->f) {
					$this->rec(151);
				}
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p >= $this->f) {
						$this->rec(62);
					}

					goto f769;}

				$this->p++;
				goto k769;
				f769:
				$this->p = $o2;
				goto n768;
				k769:
				goto a754;
				n768:
				break;
			case 19:
				$this->p++;
				goto a754;
				break;
			case 20:
				$this->p++;
				goto a754;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(142);
		}

		goto f753;
		a754:
		goto k753;
		f753:
		$this->p = $o1;
		return false;
		k753:

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

			goto f1005;}

		$this->p++;
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1007;
			}

			$this->p++;
		}
		e1007:
		if (!($this->ids[$this->p] === 46)) {
			if ($this->p >= $this->f) {
				$this->rec(69);
			}

			goto f1005;}

		$this->p++;
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1009;
			}

			$this->p++;
		}
		e1009:
		if (!$this->s145()) {
			goto f1005;}
		goto k1005;
		f1005:
		$this->p = $o1;
		return false;
		k1005:

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

					goto n1317;}

				$o2 = $this->p;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f1318;}

				$this->p++;
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if (!$this->s138()) {
						goto n1319;}
					n1319:
				} elseif ($this->p > $this->f) {
					$this->rec(138);
				}
				goto k1318;
				f1318:
				$this->p = $o2;
				goto n1317;
				k1318:
				goto a1316;
				n1317:
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

					goto n1320;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1321;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1321;}
				}
				goto k1321;
				f1321:
				$this->p = $o2;
				goto n1320;
				k1321:
				goto a1316;
				n1320:
				// s160
				if (!isset(self::T96867045[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(160);
					}

					goto n1322;}

				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1323;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1323;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f1323;}

				$this->p++;
				if (!$this->s163()) {
					goto f1323;}
				goto k1323;
				f1323:
				$this->p = $o2;
				goto n1322;
				k1323:
				goto a1316;
				n1322:
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

					goto n1324;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1325;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1325;}
				}
				goto k1325;
				f1325:
				$this->p = $o2;
				goto n1324;
				k1325:
				goto a1316;
				n1324:
				break;
			case 22:
			case 23:
				// s155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n1326;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->s156()) {
							goto n1329;}
						goto a1328;
						n1329:
						break;
					case 23:
						$this->p++;
						goto a1328;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f1327;
				a1328:
				goto k1327;
				f1327:
				$this->p = $o2;
				goto n1326;
				k1327:
				goto a1316;
				n1326:
				break;
			case 38:
			case 83:
				// s148
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(148);
					}

					goto n1330;}

				$o2 = $this->p;
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(149);
					}

					goto f1331;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1333;
					}

					$this->p++;
				}
				e1333:
				if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
					if (!$this->s151()) {
						goto n1334;}
					n1334:
				} elseif ($this->p > $this->f) {
					$this->rec(151);
				}
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p >= $this->f) {
						$this->rec(62);
					}

					goto f1331;}

				$this->p++;
				goto k1331;
				f1331:
				$this->p = $o2;
				goto n1330;
				k1331:
				goto a1316;
				n1330:
				break;
			case 19:
				$this->p++;
				goto a1316;
				break;
			case 20:
				$this->p++;
				goto a1316;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(145);
		}

		goto f1315;
		a1316:
		goto k1315;
		f1315:
		$this->p = $o1;
		return false;
		k1315:

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

			goto f999;}

		$this->p++;
		if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			// s138
			if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(138);
				}

				goto n1000;}

			$o2 = $this->p;
			if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(16);
				}

				goto f1001;}

			$this->p++;
			// s139
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e1003;
				}

				$this->p++;
			}
			e1003:
			if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
				if (!$this->s141()) {
					goto n1004;}
				n1004:
			} elseif ($this->p > $this->f) {
				$this->rec(141);
			}
			if (!($this->ids[$this->p] === 33)) {
				if ($this->p >= $this->f) {
					$this->rec(53);
				}

				goto f1001;}

			$this->p++;
			goto k1001;
			f1001:
			$this->p = $o2;
			goto n1000;
			k1001:
			n1000:
		} elseif ($this->p > $this->f) {
			$this->rec(138);
		}
		goto k999;
		f999:
		$this->p = $o1;
		return false;
		k999:

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
			goto f1014;}
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1016;
			}

			$this->p++;
		}
		e1016:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s176()) {
				goto n1017;}
			n1017:
		} elseif ($this->p > $this->f) {
			$this->rec(176);
		}
		goto k1014;
		f1014:
		$this->p = $o1;
		return false;
		k1014:

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

					goto n1337;}

				$o2 = $this->p;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f1338;}

				$this->p++;
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if (!$this->s138()) {
						goto n1339;}
					n1339:
				} elseif ($this->p > $this->f) {
					$this->rec(138);
				}
				goto k1338;
				f1338:
				$this->p = $o2;
				goto n1337;
				k1338:
				goto a1336;
				n1337:
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

					goto n1340;}

				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1341;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1343;
					}

					$this->p++;
				}
				e1343:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1341;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1345;
					}

					$this->p++;
				}
				e1345:
				if (!$this->s145()) {
					goto f1341;}
				goto k1341;
				f1341:
				$this->p = $o2;
				goto n1340;
				k1341:
				goto a1336;
				n1340:
				// s172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n1346;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1347;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1347;}
				}
				goto k1347;
				f1347:
				$this->p = $o2;
				goto n1346;
				k1347:
				goto a1336;
				n1346:
				// s160
				if (!isset(self::T96867045[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(160);
					}

					goto n1348;}

				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1349;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1349;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f1349;}

				$this->p++;
				if (!$this->s163()) {
					goto f1349;}
				goto k1349;
				f1349:
				$this->p = $o2;
				goto n1348;
				k1349:
				goto a1336;
				n1348:
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

					goto n1350;}

				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1351;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1353;
					}

					$this->p++;
				}
				e1353:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1351;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1355;
					}

					$this->p++;
				}
				e1355:
				if (!$this->s145()) {
					goto f1351;}
				goto k1351;
				f1351:
				$this->p = $o2;
				goto n1350;
				k1351:
				goto a1336;
				n1350:
				// s172
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(172);
					}

					goto n1356;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1357;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1357;}
				}
				goto k1357;
				f1357:
				$this->p = $o2;
				goto n1356;
				k1357:
				goto a1336;
				n1356:
				break;
			case 22:
			case 23:
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
				goto a1336;
				n1358:
				// s155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n1364;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->s156()) {
							goto n1367;}
						goto a1366;
						n1367:
						break;
					case 23:
						$this->p++;
						goto a1366;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f1365;
				a1366:
				goto k1365;
				f1365:
				$this->p = $o2;
				goto n1364;
				k1365:
				goto a1336;
				n1364:
				break;
			case 38:
			case 83:
				// s148
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(148);
					}

					goto n1368;}

				$o2 = $this->p;
				if (!isset(self::T34048a70[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(149);
					}

					goto f1369;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1371;
					}

					$this->p++;
				}
				e1371:
				if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
					if (!$this->s151()) {
						goto n1372;}
					n1372:
				} elseif ($this->p > $this->f) {
					$this->rec(151);
				}
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p >= $this->f) {
						$this->rec(62);
					}

					goto f1369;}

				$this->p++;
				goto k1369;
				f1369:
				$this->p = $o2;
				goto n1368;
				k1369:
				goto a1336;
				n1368:
				break;
			case 19:
				$this->p++;
				goto a1336;
				break;
			case 20:
				// s153
				if (!isset(self::T7726a0df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(153);
					}

					goto n1373;}

				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1374;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1376;
					}

					$this->p++;
				}
				e1376:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1374;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1378;
					}

					$this->p++;
				}
				e1378:
				if (!$this->s145()) {
					goto f1374;}
				goto k1374;
				f1374:
				$this->p = $o2;
				goto n1373;
				k1374:
				goto a1336;
				n1373:
				$this->p++;
				goto a1336;
				break;
			case 21:
				// s153
				if (!isset(self::T7726a0df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(153);
					}

					goto n1379;}

				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1380;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1382;
					}

					$this->p++;
				}
				e1382:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1380;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1384;
					}

					$this->p++;
				}
				e1384:
				if (!$this->s145()) {
					goto f1380;}
				goto k1380;
				f1380:
				$this->p = $o2;
				goto n1379;
				k1380:
				goto a1336;
				n1379:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(152);
		}

		goto f1335;
		a1336:
		goto k1335;
		f1335:
		$this->p = $o1;
		return false;
		k1335:

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

					goto n1628;}

				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1629;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1629;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f1629;}

				$this->p++;
				if (!$this->s163()) {
					goto f1629;}
				goto k1629;
				f1629:
				$this->p = $o2;
				goto n1628;
				k1629:
				goto a1627;
				n1628:
				// s169
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(169);
					}

					goto n1630;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1631;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1631;}
				}
				goto k1631;
				f1631:
				$this->p = $o2;
				goto n1630;
				k1631:
				goto a1627;
				n1630:
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

					goto n1632;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1633;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1633;}
				}
				goto k1633;
				f1633:
				$this->p = $o2;
				goto n1632;
				k1633:
				goto a1627;
				n1632:
				break;
			case 22:
			case 23:
				// s155
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(155);
					}

					goto n1634;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 22:
						if (!$this->s156()) {
							goto n1637;}
						goto a1636;
						n1637:
						break;
					case 23:
						$this->p++;
						goto a1636;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(155);
				}

				goto f1635;
				a1636:
				goto k1635;
				f1635:
				$this->p = $o2;
				goto n1634;
				k1635:
				goto a1627;
				n1634:
				break;
			case 20:
				$this->p++;
				goto a1627;
				break;
			case 21:
				$this->p++;
				goto a1627;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(154);
		}

		goto f1626;
		a1627:
		goto k1626;
		f1626:
		$this->p = $o1;
		return false;
		k1626:

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

			goto f1010;}

		$this->p++;
		if (isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
			// s158
			if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(158);
				}

				goto n1011;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(159);
					}

					goto e1013;
				}

				$this->p++;

				$k2++;
			}
			e1013:

			if ($k2 < 1) {
				goto f1012;
			}
			goto k1012;
			f1012:
			$this->p = $o2;
			goto n1011;
			k1012:
			n1011:
		} elseif ($this->p > $this->f) {
			$this->rec(158);
		}
		goto k1010;
		f1010:
		$this->p = $o1;
		return false;
		k1010:

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

					goto n722;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f723;}

				$this->p++;
				if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
					if (!$this->s168()) {
						goto n724;}
					n724:
				} elseif ($this->p > $this->f) {
					$this->rec(168);
				}
				goto k723;
				f723:
				$this->p = $o2;
				goto n722;
				k723:
				goto a721;
				n722:
				break;
			case 50:
				// s164
				if (!($this->ids[$this->p] === 50)) {
					if ($this->p > $this->f) {
						$this->rec(164);
					}

					goto n725;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 50)) {
					if ($this->p >= $this->f) {
						$this->rec(71);
					}

					goto f726;}

				$this->p++;
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					if (!$this->s166()) {
						goto n727;}
					n727:
				} elseif ($this->p > $this->f) {
					$this->rec(166);
				}
				goto k726;
				f726:
				$this->p = $o2;
				goto n725;
				k726:
				goto a721;
				n725:
				break;
			case 85:
				$this->p++;
				goto a721;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(163);
		}

		goto f720;
		a721:
		goto k720;
		f720:
		$this->p = $o1;
		return false;
		k720:

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

			goto f1288;}

		$this->p++;
		if (isset(self::T83e30518[$this->ids[$this->p]])) {
			if (!$this->s166()) {
				goto n1289;}
			n1289:
		} elseif ($this->p > $this->f) {
			$this->rec(166);
		}
		goto k1288;
		f1288:
		$this->p = $o1;
		return false;
		k1288:

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

			goto f965;}

		$this->p++;
		if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
			if (!$this->s168()) {
				goto n966;}
			n966:
		} elseif ($this->p > $this->f) {
			$this->rec(168);
		}
		goto k965;
		f965:
		$this->p = $o1;
		return false;
		k965:

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
					goto n964;}
				goto a963;
				n964:
				break;
			case 85:
				$this->p++;
				goto a963;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(168);
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

			goto f623;}

		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f623;}
		}
		goto k623;
		f623:
		$this->p = $o1;
		return false;
		k623:

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

				goto e1386;
			}

			// s177
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(177);
				}

				goto e1386;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1387;}

			$this->p++;
			// s139
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e1389;
				}

				$this->p++;
			}
			e1389:
			if (!$this->s178()) {
				goto f1387;}
			goto k1387;
			f1387:
			$this->p = $o2;
			goto e1386;
			k1387:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1386;
			}

			$k1++;
		}
		e1386:

		if ($k1 < 1) {
			goto f1385;
		}
		goto k1385;
		f1385:
		$this->p = $o1;
		return false;
		k1385:

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

					goto n1640;}

				$o2 = $this->p;
				if (!$this->s152()) {
					goto f1641;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1643;
					}

					$this->p++;
				}
				e1643:
				goto k1641;
				f1641:
				$this->p = $o2;
				goto n1640;
				k1641:
				goto a1639;
				n1640:
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

					goto n1644;}
				goto a1639;
				n1644:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(178);
		}

		goto f1638;
		a1639:
		goto k1638;
		f1638:
		$this->p = $o1;
		return false;
		k1638:

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

				goto e774;
			}

			// s183
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(183);
				}

				goto e774;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f775;}

			$this->p++;
			// s139
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e777;
				}

				$this->p++;
			}
			e777:
			if (!$this->s184()) {
				goto f775;}
			goto k775;
			f775:
			$this->p = $o2;
			goto e774;
			k775:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e774;
			}

			$k1++;
		}
		e774:

		if ($k1 < 1) {
			goto f773;
		}
		goto k773;
		f773:
		$this->p = $o1;
		return false;
		k773:

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

					goto n1020;}

				$o2 = $this->p;
				if (!$this->s142()) {
					goto f1021;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1023;
					}

					$this->p++;
				}
				e1023:
				goto k1021;
				f1021:
				$this->p = $o2;
				goto n1020;
				k1021:
				goto a1019;
				n1020:
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

					goto n1024;}
				goto a1019;
				n1024:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(184);
		}

		goto f1018;
		a1019:
		goto k1018;
		f1018:
		$this->p = $o1;
		return false;
		k1018:

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

			goto f240;}

		$this->p++;
		// s138
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(138);
			}

			goto f240;}

		$o2 = $this->p;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f241;}

		$this->p++;
		// s139
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b3 >= $this->f) {
					$this->rec(3);
				}

				goto e243;
			}

			$this->p++;
		}
		e243:
		if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			if (!$this->s141()) {
				goto n244;}
			n244:
		} elseif ($this->p > $this->f) {
			$this->rec(141);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f241;}

		$this->p++;
		goto k241;
		f241:
		$this->p = $o2;
		goto f240;
		k241:
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

				goto e247;
			}

			$this->p++;
		}
		e247:
		if (!$this->s74()) {
			goto f245;}
		goto k245;
		f245:
		$this->p = $o2;
		goto f240;
		k245:
		goto k240;
		f240:
		$this->p = $o1;
		return false;
		k240:

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
						goto f52;}
				}
				// s457
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f53;}
				goto k53;
				f53:
				$this->p = $o3;
				goto f52;
				k53:
				goto k52;
				f52:
				$this->p = $o2;
				goto n51;
				k52:
				goto a50;
				n51:
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
					goto n54;}
				goto a50;
				n54:
				if (!$this->s454()) {
					goto n55;}
				goto a50;
				n55:
				// s455
				$o2 = $this->p;
				if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f57;}
				}
				// s457
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f58;}
				goto k58;
				f58:
				$this->p = $o3;
				goto f57;
				k58:
				goto k57;
				f57:
				$this->p = $o2;
				goto n56;
				k57:
				goto a50;
				n56:
				break;
			case 76:
			case 77:
			case 78:
			case 79:
				if (!$this->s454()) {
					goto n59;}
				goto a50;
				n59:
				// s455
				$o2 = $this->p;
				if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f61;}
				}
				// s457
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f62;}
				goto k62;
				f62:
				$this->p = $o3;
				goto f61;
				k62:
				goto k61;
				f61:
				$this->p = $o2;
				goto n60;
				k61:
				goto a50;
				n60:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(189);
		}

		goto f49;
		a50:
		goto k49;
		f49:
		$this->p = $o1;
		return false;
		k49:

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

					goto n112;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 51)) {
					if ($this->p >= $this->f) {
						$this->rec(85);
					}

					goto f113;}

				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f113;}

				$o3 = $this->p;
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
				goto a111;
				n112:
				break;
			case 52:
				// s405
				if (!($this->ids[$this->p] === 52)) {
					if ($this->p > $this->f) {
						$this->rec(405);
					}

					goto n115;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 52)) {
					if ($this->p >= $this->f) {
						$this->rec(88);
					}

					goto f116;}

				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f116;}

				$o3 = $this->p;
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f117;}

				$this->p++;
				if (!$this->s125()) {
					goto f117;}
				goto k117;
				f117:
				$this->p = $o3;
				goto f116;
				k117:
				goto k116;
				f116:
				$this->p = $o2;
				goto n115;
				k116:
				goto a111;
				n115:
				break;
			case 53:
				// s406
				if (!($this->ids[$this->p] === 53)) {
					if ($this->p > $this->f) {
						$this->rec(406);
					}

					goto n118;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 53)) {
					if ($this->p >= $this->f) {
						$this->rec(89);
					}

					goto f119;}

				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f119;}

				$o3 = $this->p;
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f120;}

				$this->p++;
				if (!$this->s125()) {
					goto f120;}
				goto k120;
				f120:
				$this->p = $o3;
				goto f119;
				k120:
				goto k119;
				f119:
				$this->p = $o2;
				goto n118;
				k119:
				goto a111;
				n118:
				break;
			case 54:
				// s404
				if (!($this->ids[$this->p] === 54)) {
					if ($this->p > $this->f) {
						$this->rec(404);
					}

					goto n121;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 54)) {
					if ($this->p >= $this->f) {
						$this->rec(86);
					}

					goto f122;}

				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f122;}

				$o3 = $this->p;
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f123;}

				$this->p++;
				if (!$this->s125()) {
					goto f123;}
				goto k123;
				f123:
				$this->p = $o3;
				goto f122;
				k123:
				goto k122;
				f122:
				$this->p = $o2;
				goto n121;
				k122:
				goto a111;
				n121:
				break;
			case 55:
				// s401
				if (!($this->ids[$this->p] === 55)) {
					if ($this->p > $this->f) {
						$this->rec(401);
					}

					goto n124;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 55)) {
					if ($this->p >= $this->f) {
						$this->rec(87);
					}

					goto f125;}

				$this->p++;
				// s399
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(399);
					}

					goto f125;}

				$o3 = $this->p;
				if (!$this->s199()) {
					goto f126;}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f126;}

				$this->p++;
				if (!$this->s125()) {
					goto f126;}
				goto k126;
				f126:
				$this->p = $o3;
				goto f125;
				k126:
				goto k125;
				f125:
				$this->p = $o2;
				goto n124;
				k125:
				goto a111;
				n124:
				break;
			case 56:
				// s395
				if (!($this->ids[$this->p] === 56)) {
					if ($this->p > $this->f) {
						$this->rec(395);
					}

					goto n127;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 56)) {
					if ($this->p >= $this->f) {
						$this->rec(95);
					}

					goto f128;}

				$this->p++;
				if (!$this->s391()) {
					goto f128;}
				goto k128;
				f128:
				$this->p = $o2;
				goto n127;
				k128:
				goto a111;
				n127:
				break;
			case 57:
				// s394
				if (!($this->ids[$this->p] === 57)) {
					if ($this->p > $this->f) {
						$this->rec(394);
					}

					goto n129;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 57)) {
					if ($this->p >= $this->f) {
						$this->rec(94);
					}

					goto f130;}

				$this->p++;
				if (!$this->s391()) {
					goto f130;}
				goto k130;
				f130:
				$this->p = $o2;
				goto n129;
				k130:
				goto a111;
				n129:
				break;
			case 58:
				// s443
				if (!($this->ids[$this->p] === 58)) {
					if ($this->p > $this->f) {
						$this->rec(443);
					}

					goto n131;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 58)) {
					if ($this->p >= $this->f) {
						$this->rec(105);
					}

					goto f132;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f132;}

				$this->p++;
				if (!($this->ids[$this->p] === 10)) {
					if ($this->p >= $this->f) {
						$this->rec(26);
					}

					goto f132;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f132;}

				$this->p++;
				if (!$this->s444()) {
					goto f132;}
				goto k132;
				f132:
				$this->p = $o2;
				goto n131;
				k132:
				goto a111;
				n131:
				break;
			case 59:
				// s446
				if (!($this->ids[$this->p] === 59)) {
					if ($this->p > $this->f) {
						$this->rec(446);
					}

					goto n133;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 59)) {
					if ($this->p >= $this->f) {
						$this->rec(106);
					}

					goto f134;}

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
					goto f134;}
				if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(448);
					}

					goto f134;}

				$this->p++;
				if (!$this->s449()) {
					goto f134;}
				if (!$this->s125()) {
					goto f134;}
				goto k134;
				f134:
				$this->p = $o2;
				goto n133;
				k134:
				goto a111;
				n133:
				break;
			case 60:
				// s397
				if (!($this->ids[$this->p] === 60)) {
					if ($this->p > $this->f) {
						$this->rec(397);
					}

					goto n135;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 60)) {
					if ($this->p >= $this->f) {
						$this->rec(107);
					}

					goto f136;}

				$this->p++;
				if (!$this->s391()) {
					goto f136;}
				goto k136;
				f136:
				$this->p = $o2;
				goto n135;
				k136:
				goto a111;
				n135:
				break;
			case 61:
				// s396
				if (!($this->ids[$this->p] === 61)) {
					if ($this->p > $this->f) {
						$this->rec(396);
					}

					goto n137;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 61)) {
					if ($this->p >= $this->f) {
						$this->rec(96);
					}

					goto f138;}

				$this->p++;
				if (!$this->s391()) {
					goto f138;}
				goto k138;
				f138:
				$this->p = $o2;
				goto n137;
				k138:
				goto a111;
				n137:
				break;
			case 62:
				if (!$this->s436()) {
					goto n139;}
				goto a111;
				n139:
				break;
			case 63:
				// s398
				if (!($this->ids[$this->p] === 63)) {
					if ($this->p > $this->f) {
						$this->rec(398);
					}

					goto n140;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 63)) {
					if ($this->p >= $this->f) {
						$this->rec(98);
					}

					goto f141;}

				$this->p++;
				// s399
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(399);
					}

					goto f141;}

				$o3 = $this->p;
				if (!$this->s199()) {
					goto f142;}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f142;}

				$this->p++;
				if (!$this->s125()) {
					goto f142;}
				goto k142;
				f142:
				$this->p = $o3;
				goto f141;
				k142:
				goto k141;
				f141:
				$this->p = $o2;
				goto n140;
				k141:
				goto a111;
				n140:
				break;
			case 64:
				// s439
				if (!($this->ids[$this->p] === 64)) {
					if ($this->p > $this->f) {
						$this->rec(439);
					}

					goto n143;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 64)) {
					if ($this->p >= $this->f) {
						$this->rec(102);
					}

					goto f144;}

				$this->p++;
				if (!$this->s438()) {
					goto f144;}
				goto k144;
				f144:
				$this->p = $o2;
				goto n143;
				k144:
				goto a111;
				n143:
				break;
			case 65:
				// s407
				if (!($this->ids[$this->p] === 65)) {
					if ($this->p > $this->f) {
						$this->rec(407);
					}

					goto n145;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 65)) {
					if ($this->p >= $this->f) {
						$this->rec(97);
					}

					goto f146;}

				$this->p++;
				if (!$this->s125()) {
					goto f146;}
				goto k146;
				f146:
				$this->p = $o2;
				goto n145;
				k146:
				goto a111;
				n145:
				break;
			case 66:
				// s441
				if (!($this->ids[$this->p] === 66)) {
					if ($this->p > $this->f) {
						$this->rec(441);
					}

					goto n147;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 66)) {
					if ($this->p >= $this->f) {
						$this->rec(104);
					}

					goto f148;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f148;}

				$this->p++;
				if (($this->ids[$this->p] === 46)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(69);
				}
				if (!$this->s199()) {
					goto f148;}
				if (!isset(self::T53b29bff[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(77);
					}

					goto f148;}
				goto k148;
				f148:
				$this->p = $o2;
				goto n147;
				k148:
				goto a111;
				n147:
				// s442
				if (!($this->ids[$this->p] === 66)) {
					if ($this->p > $this->f) {
						$this->rec(442);
					}

					goto n149;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 66)) {
					if ($this->p >= $this->f) {
						$this->rec(104);
					}

					goto f150;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f150;}

				$this->p++;
				if (($this->ids[$this->p] === 46)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(69);
				}
				if (!$this->s125()) {
					goto f150;}
				goto k150;
				f150:
				$this->p = $o2;
				goto n149;
				k150:
				goto a111;
				n149:
				break;
			case 67:
				// s400
				if (!($this->ids[$this->p] === 67)) {
					if ($this->p > $this->f) {
						$this->rec(400);
					}

					goto n151;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 67)) {
					if ($this->p >= $this->f) {
						$this->rec(108);
					}

					goto f152;}

				$this->p++;
				// s399
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(399);
					}

					goto f152;}

				$o3 = $this->p;
				if (!$this->s199()) {
					goto f153;}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f153;}

				$this->p++;
				if (!$this->s125()) {
					goto f153;}
				goto k153;
				f153:
				$this->p = $o3;
				goto f152;
				k153:
				goto k152;
				f152:
				$this->p = $o2;
				goto n151;
				k152:
				goto a111;
				n151:
				break;
			case 68:
				// s437
				if (!($this->ids[$this->p] === 68)) {
					if ($this->p > $this->f) {
						$this->rec(437);
					}

					goto n154;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 68)) {
					if ($this->p >= $this->f) {
						$this->rec(101);
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
				goto a111;
				n154:
				break;
			case 69:
				// s408
				if (!($this->ids[$this->p] === 69)) {
					if ($this->p > $this->f) {
						$this->rec(408);
					}

					goto n156;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 69)) {
					if ($this->p >= $this->f) {
						$this->rec(99);
					}

					goto f157;}

				$this->p++;
				if (!$this->s409()) {
					goto f157;}
				goto k157;
				f157:
				$this->p = $o2;
				goto n156;
				k157:
				goto a111;
				n156:
				break;
			case 70:
				// s390
				if (!($this->ids[$this->p] === 70)) {
					if ($this->p > $this->f) {
						$this->rec(390);
					}

					goto n158;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 70)) {
					if ($this->p >= $this->f) {
						$this->rec(91);
					}

					goto f159;}

				$this->p++;
				if (!$this->s391()) {
					goto f159;}
				goto k159;
				f159:
				$this->p = $o2;
				goto n158;
				k159:
				goto a111;
				n158:
				break;
			case 71:
				// s392
				if (!($this->ids[$this->p] === 71)) {
					if ($this->p > $this->f) {
						$this->rec(392);
					}

					goto n160;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 71)) {
					if ($this->p >= $this->f) {
						$this->rec(92);
					}

					goto f161;}

				$this->p++;
				if (!$this->s391()) {
					goto f161;}
				goto k161;
				f161:
				$this->p = $o2;
				goto n160;
				k161:
				goto a111;
				n160:
				break;
			case 72:
				// s191
				if (!($this->ids[$this->p] === 72)) {
					if ($this->p > $this->f) {
						$this->rec(191);
					}

					goto n162;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 72)) {
					if ($this->p >= $this->f) {
						$this->rec(84);
					}

					goto f163;}

				$this->p++;
				if (!$this->s192()) {
					goto f163;}
				goto k163;
				f163:
				$this->p = $o2;
				goto n162;
				k163:
				goto a111;
				n162:
				break;
			case 73:
				// s393
				if (!($this->ids[$this->p] === 73)) {
					if ($this->p > $this->f) {
						$this->rec(393);
					}

					goto n164;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 73)) {
					if ($this->p >= $this->f) {
						$this->rec(93);
					}

					goto f165;}

				$this->p++;
				if (!$this->s391()) {
					goto f165;}
				goto k165;
				f165:
				$this->p = $o2;
				goto n164;
				k165:
				goto a111;
				n164:
				break;
			case 74:
				// s440
				if (!($this->ids[$this->p] === 74)) {
					if ($this->p > $this->f) {
						$this->rec(440);
					}

					goto n166;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 74)) {
					if ($this->p >= $this->f) {
						$this->rec(103);
					}

					goto f167;}

				$this->p++;
				if (!$this->s438()) {
					goto f167;}
				goto k167;
				f167:
				$this->p = $o2;
				goto n166;
				k167:
				goto a111;
				n166:
				break;
			case 75:
				// s384
				if (!($this->ids[$this->p] === 75)) {
					if ($this->p > $this->f) {
						$this->rec(384);
					}

					goto n168;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 75)) {
					if ($this->p >= $this->f) {
						$this->rec(90);
					}

					goto f169;}

				$this->p++;
				if (!$this->s199()) {
					goto f169;}
				if (!$this->s385()) {
					goto f169;}
				goto k169;
				f169:
				$this->p = $o2;
				goto n168;
				k169:
				goto a111;
				n168:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(190);
		}

		goto f110;
		a111:
		goto k110;
		f110:
		$this->p = $o1;
		return false;
		k110:

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

					goto n354;}

				$o2 = $this->p;
				if (!$this->s199()) {
					goto f355;}
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

					goto f355;}

				$this->p++;
				if (!$this->s125()) {
					goto f355;}
				goto k355;
				f355:
				$this->p = $o2;
				goto n354;
				k355:
				goto a353;
				n354:
				break;
			case 26:
			case 27:
			case 42:
				// s193
				if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(193);
					}

					goto n356;}

				$o2 = $this->p;
				if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(195);
					}

					goto f357;}
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

					goto f357;}

				$this->p++;
				if (!$this->s125()) {
					goto f357;}
				goto k357;
				f357:
				$this->p = $o2;
				goto n356;
				k357:
				goto a353;
				n356:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(192);
		}

		goto f352;
		a353:
		goto k352;
		f352:
		$this->p = $o1;
		return false;
		k352:

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

					goto n178;}

				$o2 = $this->p;
				if (!$this->s201()) {
					goto f179;}
				if (!$this->s368()) {
					goto f179;}
				goto k179;
				f179:
				$this->p = $o2;
				goto n178;
				k179:
				goto a177;
				n178:
				break;
			case 30:
				// s200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n180;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f181;}

				$this->p++;
				if (!$this->s201()) {
					goto f181;}
				goto k181;
				f181:
				$this->p = $o2;
				goto n180;
				k181:
				goto a177;
				n180:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(199);
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

					goto n408;}

				$o2 = $this->p;
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(332);
					}

					goto f409;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f409;}
				}
				if (!$this->s281()) {
					goto f409;}
				goto k409;
				f409:
				$this->p = $o2;
				goto n408;
				k409:
				goto a407;
				n408:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n410;}

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
						goto f411;}
				}
				if (!$this->s335()) {
					goto f411;}
				goto k411;
				f411:
				$this->p = $o2;
				goto n410;
				k411:
				goto a407;
				n410:
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

					goto n413;}

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
						goto f414;}
				}
				if (!$this->s335()) {
					goto f414;}
				goto k414;
				f414:
				$this->p = $o2;
				goto n413;
				k414:
				goto a407;
				n413:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->s255()) {
					goto n416;}
				goto a407;
				n416:
				// s278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n417;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f418;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f418;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f418;}
				}
				if (!$this->s281()) {
					goto f418;}
				goto k418;
				f418:
				$this->p = $o2;
				goto n417;
				k418:
				goto a407;
				n417:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n419;}

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
						goto f420;}
				}
				if (!$this->s335()) {
					goto f420;}
				goto k420;
				f420:
				$this->p = $o2;
				goto n419;
				k420:
				goto a407;
				n419:
				break;
			case 32:
			case 82:
				if (!$this->s202()) {
					goto n422;}
				goto a407;
				n422:
				break;
			case 6:
				if (!$this->s267()) {
					goto n423;}
				goto a407;
				n423:
				// s278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n424;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f425;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f425;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f425;}
				}
				if (!$this->s281()) {
					goto f425;}
				goto k425;
				f425:
				$this->p = $o2;
				goto n424;
				k425:
				goto a407;
				n424:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n426;}

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
						goto f427;}
				}
				if (!$this->s335()) {
					goto f427;}
				goto k427;
				f427:
				$this->p = $o2;
				goto n426;
				k427:
				goto a407;
				n426:
				break;
			case 7:
				if (!$this->s230()) {
					goto n429;}
				goto a407;
				n429:
				// s278
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(278);
					}

					goto n430;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f431;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f431;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f431;}
				}
				if (!$this->s281()) {
					goto f431;}
				goto k431;
				f431:
				$this->p = $o2;
				goto n430;
				k431:
				goto a407;
				n430:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n432;}

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
						goto f433;}
				}
				if (!$this->s335()) {
					goto f433;}
				goto k433;
				f433:
				$this->p = $o2;
				goto n432;
				k433:
				goto a407;
				n432:
				break;
			case 25:
				if (!$this->s229()) {
					goto n435;}
				goto a407;
				n435:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(201);
		}

		goto f406;
		a407:
		goto k406;
		f406:
		$this->p = $o1;
		return false;
		k406:

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

			goto f677;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e679;
			}

			$this->p++;
		}
		e679:
		if (!$this->s205()) {
			goto f677;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e681;
			}

			$this->p++;
		}
		e681:
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f677;}

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
		goto k677;
		f677:
		$this->p = $o1;
		return false;
		k677:

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

					goto n913;}

				$o2 = $this->p;
				if (!$this->s201()) {
					goto f914;}
				if (!$this->s209()) {
					goto f914;}
				goto k914;
				f914:
				$this->p = $o2;
				goto n913;
				k914:
				goto a912;
				n913:
				break;
			case 26:
				if (!$this->s206()) {
					goto n915;}
				goto a912;
				n915:
				break;
			case 30:
				// s200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n916;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f917;}

				$this->p++;
				if (!$this->s201()) {
					goto f917;}
				goto k917;
				f917:
				$this->p = $o2;
				goto n916;
				k917:
				goto a912;
				n916:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(205);
		}

		goto f911;
		a912:
		goto k911;
		f911:
		$this->p = $o1;
		return false;
		k911:

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

			goto f1252;}

		$this->p++;
		if (!($this->ids[$this->p] === 12)) {
			if ($this->p >= $this->f) {
				$this->rec(23);
			}

			goto f1252;}

		$this->p++;
		if (($this->ids[$this->p] === 11)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->s199()) {
			goto f1252;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1254;
			}

			$this->p++;
		}
		e1254:
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
			}

			goto f1252;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1256;
			}

			$this->p++;
		}
		e1256:
		if (!$this->s199()) {
			goto f1252;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1258;
			}

			$this->p++;
		}
		e1258:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1252;}

		$this->p++;
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
		if (!$this->s205()) {
			goto f1252;}
		goto k1252;
		f1252:
		$this->p = $o1;
		return false;
		k1252:

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

						goto e1244;
					}

					$this->p++;
				}
				e1244:
				if (isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if (!$this->s213()) {
						goto n1245;}
					n1245:
				} elseif ($this->p > $this->f) {
					$this->rec(213);
				}
				goto a1240;
				n1241:
				break;
			case 12:
				if (!$this->s210()) {
					goto n1246;}
				goto a1240;
				n1246:
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

						goto e1250;
					}

					$this->p++;
				}
				e1250:
				if (isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if (!$this->s213()) {
						goto n1251;}
					n1251:
				} elseif ($this->p > $this->f) {
					$this->rec(213);
				}
				goto a1240;
				n1247:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(209);
		}

		goto f1239;
		a1240:
		goto k1239;
		f1239:
		$this->p = $o1;
		return false;
		k1239:

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

			goto f1550;}

		$this->p++;
		if (($this->ids[$this->p] === 11)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->s199()) {
			goto f1550;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1552;
			}

			$this->p++;
		}
		e1552:
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
			}

			goto f1550;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1554;
			}

			$this->p++;
		}
		e1554:
		if (!$this->s199()) {
			goto f1550;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1556;
			}

			$this->p++;
		}
		e1556:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1550;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1558;
			}

			$this->p++;
		}
		e1558:
		if (!$this->s205()) {
			goto f1550;}
		goto k1550;
		f1550:
		$this->p = $o1;
		return false;
		k1550:

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

					goto n1546;}

				$o2 = $this->p;
				if (!$this->s215()) {
					goto f1547;}
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1547;}
				}
				goto k1547;
				f1547:
				$this->p = $o2;
				goto n1546;
				k1547:
				goto a1545;
				n1546:
				break;
			case 29:
				// s218
				if (!($this->ids[$this->p] === 29)) {
					if ($this->p > $this->f) {
						$this->rec(218);
					}

					goto n1548;}

				$o2 = $this->p;
				if (!$this->s219()) {
					goto f1549;}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1549;}
				}
				goto k1549;
				f1549:
				$this->p = $o2;
				goto n1548;
				k1549:
				goto a1545;
				n1548:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(213);
		}

		goto f1544;
		a1545:
		goto k1544;
		f1544:
		$this->p = $o1;
		return false;
		k1544:

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

				goto e1711;
			}

			// s216
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->rec(216);
				}

				goto e1711;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p >= $this->f) {
					$this->rec(49);
				}

				goto f1712;}

			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1714;
				}

				$this->p++;
			}
			e1714:
			if (!$this->s201()) {
				goto f1712;}
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1716;
				}

				$this->p++;
			}
			e1716:
			goto k1712;
			f1712:
			$this->p = $o2;
			goto e1711;
			k1712:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1711;
			}

			$k1++;
		}
		e1711:

		if ($k1 < 1) {
			goto f1710;
		}
		goto k1710;
		f1710:
		$this->p = $o1;
		return false;
		k1710:

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

				goto e1718;
			}

			// s220
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->rec(220);
				}

				goto e1718;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p >= $this->f) {
					$this->rec(50);
				}

				goto f1719;}

			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1721;
				}

				$this->p++;
			}
			e1721:
			if (!$this->s201()) {
				goto f1719;}
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1723;
				}

				$this->p++;
			}
			e1723:
			goto k1719;
			f1719:
			$this->p = $o2;
			goto e1718;
			k1719:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1718;
			}

			$k1++;
		}
		e1718:

		if ($k1 < 1) {
			goto f1717;
		}
		goto k1717;
		f1717:
		$this->p = $o1;
		return false;
		k1717:

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

					goto n890;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 36)) {
					if ($this->p >= $this->f) {
						$this->rec(57);
					}

					goto f891;}

				$this->p++;
				if (($this->ids[$this->p] === 37)) {
					if (($this->ids[$this->p] === 37)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f891;}
				}
				if (!$this->s199()) {
					goto f891;}
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f891;}

				$this->p++;
				goto k891;
				f891:
				$this->p = $o2;
				goto n890;
				k891:
				goto a889;
				n890:
				// s227
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(227);
					}

					goto n892;}

				$o2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(228);
					}

					goto f893;}

				$this->p++;
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f893;}

				$this->p++;
				goto k893;
				f893:
				$this->p = $o2;
				goto n892;
				k893:
				goto a889;
				n892:
				break;
			case 84:
				// s227
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(227);
					}

					goto n894;}

				$o2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(228);
					}

					goto f895;}

				$this->p++;
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f895;}

				$this->p++;
				goto k895;
				f895:
				$this->p = $o2;
				goto n894;
				k895:
				goto a889;
				n894:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(224);
		}

		goto f888;
		a889:
		goto k888;
		f888:
		$this->p = $o1;
		return false;
		k888:

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

			goto f697;}

		$this->p++;
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n698;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e700;
				}

				if (!$this->s224()) {
					goto e700;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e700;
				}

				$k2++;
			}
			e700:

			if ($k2 < 1) {
				goto f699;
			}
			goto k699;
			f699:
			$this->p = $o2;
			goto n698;
			k699:
			n698:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k697;
		f697:
		$this->p = $o1;
		return false;
		k697:

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

			goto f693;}

		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f693;}
		}
		if (!$this->s231()) {
			goto f693;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n694;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e696;
				}

				if (!$this->s224()) {
					goto e696;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e696;
				}

				$k2++;
			}
			e696:

			if ($k2 < 1) {
				goto f695;
			}
			goto k695;
			f695:
			$this->p = $o2;
			goto n694;
			k695:
			n694:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k693;
		f693:
		$this->p = $o1;
		return false;
		k693:

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

			goto f925;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e927;
			}

			$this->p++;
		}
		e927:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			// s233
			if (!isset(self::T50fd39f8[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(233);
				}

				goto n928;}

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
						goto n931;}
					goto a930;
					n931:
					break;
				case 42:
					if (!$this->s234()) {
						goto n932;}
					goto a930;
					n932:
					break;
			}

			if ($this->p > $this->f) {
				$this->rec(233);
			}

			goto f929;
			a930:
			goto k929;
			f929:
			$this->p = $o2;
			goto n928;
			k929:
			n928:
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

				goto e934;
			}

			$this->p++;
		}
		e934:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f925;}

		$this->p++;
		goto k925;
		f925:
		$this->p = $o1;
		return false;
		k925:

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
					goto n1565;}
				goto a1564;
				n1565:
				break;
			case 42:
				// s234
				if (!($this->ids[$this->p] === 42)) {
					if ($this->p > $this->f) {
						$this->rec(234);
					}

					goto n1566;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 42)) {
					if ($this->p >= $this->f) {
						$this->rec(65);
					}

					goto f1567;}

				$this->p++;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1569;
					}

					$this->p++;
				}
				e1569:
				if (!$this->s235()) {
					goto f1567;}
				if (($this->ids[$this->p] === 40)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(63);
				}
				goto k1567;
				f1567:
				$this->p = $o2;
				goto n1566;
				k1567:
				goto a1564;
				n1566:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(233);
		}

		goto f1563;
		a1564:
		goto k1563;
		f1563:
		$this->p = $o1;
		return false;
		k1563:

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

			goto f1283;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1285;
			}

			$this->p++;
		}
		e1285:
		if (!$this->s235()) {
			goto f1283;}
		if (($this->ids[$this->p] === 40)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k1283;
		f1283:
		$this->p = $o1;
		return false;
		k1283:

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
					goto n1572;}
				goto a1571;
				n1572:
				break;
			case 34:
			case 86:
				// s236
				if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(236);
					}

					goto n1573;}

				$o2 = $this->p;
				if (!$this->s237()) {
					goto f1574;}
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1576;
					}

					$this->p++;
				}
				e1576:
				goto k1574;
				f1574:
				$this->p = $o2;
				goto n1573;
				k1574:
				goto a1571;
				n1573:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1577;}
				goto a1571;
				n1577:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(235);
		}

		goto f1570;
		a1571:
		goto k1570;
		f1570:
		$this->p = $o1;
		return false;
		k1570:

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

			goto f1724;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1726;
			}

			$this->p++;
		}
		e1726:
		if (!$this->s199()) {
			goto f1724;}
		// s203
		$o2 = $this->p;
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
		if (($this->ids[$this->p] === 40)) {
			// s240
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(240);
				}

				goto n1729;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1730;}

			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1732;
				}

				$this->p++;
			}
			e1732:
			if (!$this->s199()) {
				goto f1730;}
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1734;
				}

				$this->p++;
			}
			e1734:
			goto k1730;
			f1730:
			$this->p = $o2;
			goto n1729;
			k1730:
			n1729:
		} elseif ($this->p > $this->f) {
			$this->rec(240);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1724;}

		$this->p++;
		goto k1724;
		f1724:
		$this->p = $o1;
		return false;
		k1724:

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

			goto f1270;}

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
					goto n1273;}
				goto a1272;
				n1273:
				if (!$this->s199()) {
					goto n1274;}
				goto a1272;
				n1274:
				break;
			case 19:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->s199()) {
					goto n1275;}
				goto a1272;
				n1275:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(244);
		}

		goto f1271;
		a1272:
		goto k1271;
		f1271:
		$this->p = $o2;
		goto f1270;
		k1271:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1277;
			}

			$this->p++;
		}
		e1277:
		if (($this->ids[$this->p] === 40)) {
			// s252
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(252);
				}

				goto n1278;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1279;}

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

					goto e1281;
				}

				$this->p++;
			}
			e1281:
			if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
				if (!$this->s233()) {
					goto n1282;}
				n1282:
			} elseif ($this->p > $this->f) {
				$this->rec(233);
			}
			goto k1279;
			f1279:
			$this->p = $o2;
			goto n1278;
			k1279:
			n1278:
		} elseif ($this->p > $this->f) {
			$this->rec(252);
		}
		goto k1270;
		f1270:
		$this->p = $o1;
		return false;
		k1270:

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

			goto f1525;}

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
					goto n1528;}
				goto a1527;
				n1528:
				break;
			case 20:
				$this->p++;
				goto a1527;
				break;
			case 21:
				$this->p++;
				goto a1527;
				break;
			case 22:
				$this->p++;
				goto a1527;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(246);
		}

		goto f1526;
		a1527:
		goto k1526;
		f1526:
		$this->p = $o2;
		goto f1525;
		k1526:
		if (($this->ids[$this->p] === 30)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(51);
		}
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1525;}

		$this->p++;
		if (!$this->s199()) {
			goto f1525;}
		goto k1525;
		f1525:
		$this->p = $o1;
		return false;
		k1525:

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

			goto f1702;}

		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			// s249
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->rec(249);
				}

				goto n1703;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p >= $this->f) {
					$this->rec(66);
				}

				goto f1704;}

			$this->p++;
			if (!isset(self::T83e30518[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(144);
				}

				goto f1704;}

			$this->p++;
			goto k1704;
			f1704:
			$this->p = $o2;
			goto n1703;
			k1704:
			n1703:
		} elseif ($this->p > $this->f) {
			$this->rec(249);
		}
		goto k1702;
		f1702:
		$this->p = $o1;
		return false;
		k1702:

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

			goto f673;}

		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f673;}
		}
		if (!$this->s257()) {
			goto f673;}
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
		goto k673;
		f673:
		$this->p = $o1;
		return false;
		k673:

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

			goto f901;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e903;
			}

			$this->p++;
		}
		e903:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			// s259
			if (!isset(self::T50fd39f8[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(259);
				}

				goto n904;}

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
						goto n907;}
					goto a906;
					n907:
					break;
				case 42:
					if (!$this->s260()) {
						goto n908;}
					goto a906;
					n908:
					break;
			}

			if ($this->p > $this->f) {
				$this->rec(259);
			}

			goto f905;
			a906:
			goto k905;
			f905:
			$this->p = $o2;
			goto n904;
			k905:
			n904:
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

				goto e910;
			}

			$this->p++;
		}
		e910:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f901;}

		$this->p++;
		goto k901;
		f901:
		$this->p = $o1;
		return false;
		k901:

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
					goto n1531;}
				goto a1530;
				n1531:
				break;
			case 42:
				// s260
				if (!($this->ids[$this->p] === 42)) {
					if ($this->p > $this->f) {
						$this->rec(260);
					}

					goto n1532;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 42)) {
					if ($this->p >= $this->f) {
						$this->rec(65);
					}

					goto f1533;}

				$this->p++;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1535;
					}

					$this->p++;
				}
				e1535:
				if (!$this->s261()) {
					goto f1533;}
				if (($this->ids[$this->p] === 40)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(63);
				}
				goto k1533;
				f1533:
				$this->p = $o2;
				goto n1532;
				k1533:
				goto a1530;
				n1532:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(259);
		}

		goto f1529;
		a1530:
		goto k1529;
		f1529:
		$this->p = $o1;
		return false;
		k1529:

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

			goto f1236;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1238;
			}

			$this->p++;
		}
		e1238:
		if (!$this->s261()) {
			goto f1236;}
		if (($this->ids[$this->p] === 40)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k1236;
		f1236:
		$this->p = $o1;
		return false;
		k1236:

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
					goto n1538;}
				goto a1537;
				n1538:
				break;
			case 34:
			case 86:
				// s262
				if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(262);
					}

					goto n1539;}

				$o2 = $this->p;
				if (!$this->s263()) {
					goto f1540;}
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1542;
					}

					$this->p++;
				}
				e1542:
				goto k1540;
				f1540:
				$this->p = $o2;
				goto n1539;
				k1540:
				goto a1537;
				n1539:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1543;}
				goto a1537;
				n1543:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(261);
		}

		goto f1536;
		a1537:
		goto k1536;
		f1536:
		$this->p = $o1;
		return false;
		k1536:

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

			goto f1705;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1707;
			}

			$this->p++;
		}
		e1707:
		if (!$this->s199()) {
			goto f1705;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1709;
			}

			$this->p++;
		}
		e1709:
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1705;}

		$this->p++;
		goto k1705;
		f1705:
		$this->p = $o1;
		return false;
		k1705:

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

			goto f1223;}

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
					goto n1226;}
				goto a1225;
				n1226:
				if (!$this->s199()) {
					goto n1227;}
				goto a1225;
				n1227:
				break;
			case 19:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->s199()) {
					goto n1228;}
				goto a1225;
				n1228:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(244);
		}

		goto f1224;
		a1225:
		goto k1224;
		f1224:
		$this->p = $o2;
		goto f1223;
		k1224:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1230;
			}

			$this->p++;
		}
		e1230:
		if (($this->ids[$this->p] === 40)) {
			// s266
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(266);
				}

				goto n1231;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1232;}

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

					goto e1234;
				}

				$this->p++;
			}
			e1234:
			if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
				if (!$this->s259()) {
					goto n1235;}
				n1235:
			} elseif ($this->p > $this->f) {
				$this->rec(259);
			}
			goto k1232;
			f1232:
			$this->p = $o2;
			goto n1231;
			k1232:
			n1231:
		} elseif ($this->p > $this->f) {
			$this->rec(266);
		}
		goto k1223;
		f1223:
		$this->p = $o1;
		return false;
		k1223:

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

			goto f685;}

		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f685;}
		}
		// s268
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p > $this->f) {
				$this->rec(268);
			}

			goto f685;}

		$o2 = $this->p;
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p >= $this->f) {
				$this->rec(60);
			}

			goto f686;}

		$this->p++;
		// s203
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b3 > $this->f) {
					$this->rec(204);
				}

				goto e688;
			}

			$this->p++;
		}
		e688:
		if (isset(self::Td8017923[$this->ids[$this->p]])) {
			if (!$this->s270()) {
				goto n689;}
			n689:
		} elseif ($this->p > $this->f) {
			$this->rec(270);
		}
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f686;}

		$this->p++;
		goto k686;
		f686:
		$this->p = $o2;
		goto f685;
		k686:
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n690;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e692;
				}

				if (!$this->s224()) {
					goto e692;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e692;
				}

				$k2++;
			}
			e692:

			if ($k2 < 1) {
				goto f691;
			}
			goto k691;
			f691:
			$this->p = $o2;
			goto n690;
			k691:
			n690:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k685;
		f685:
		$this->p = $o1;
		return false;
		k685:

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

			goto f918;}

		$o2 = $this->p;
		if (!$this->s272()) {
			goto f919;}
		if (($this->ids[$this->p] === 30)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(51);
		}
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f919;}

		$this->p++;
		if (!$this->s199()) {
			goto f919;}
		goto k919;
		f919:
		$this->p = $o2;
		goto f918;
		k919:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e921;
			}

			$this->p++;
		}
		e921:
		if (($this->ids[$this->p] === 40)) {
			// s274
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(274);
				}

				goto n922;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(275);
					}

					goto e924;
				}

				if (!$this->s275()) {
					goto e924;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e924;
				}

				$k2++;
			}
			e924:

			if ($k2 < 1) {
				goto f923;
			}
			goto k923;
			f923:
			$this->p = $o2;
			goto n922;
			k923:
			n922:
		} elseif ($this->p > $this->f) {
			$this->rec(274);
		}
		goto k918;
		f918:
		$this->p = $o1;
		return false;
		k918:

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
				goto a1262;
				break;
			case 2:
				$this->p++;
				goto a1262;
				break;
			case 3:
				$this->p++;
				goto a1262;
				break;
			case 4:
				$this->p++;
				goto a1262;
				break;
			case 5:
				$this->p++;
				goto a1262;
				break;
			case 6:
				$this->p++;
				goto a1262;
				break;
			case 7:
				$this->p++;
				goto a1262;
				break;
			case 8:
				$this->p++;
				goto a1262;
				break;
			case 9:
				$this->p++;
				goto a1262;
				break;
			case 10:
				$this->p++;
				goto a1262;
				break;
			case 11:
				$this->p++;
				goto a1262;
				break;
			case 12:
				$this->p++;
				goto a1262;
				break;
			case 13:
				$this->p++;
				goto a1262;
				break;
			case 14:
				$this->p++;
				goto a1262;
				break;
			case 15:
				$this->p++;
				goto a1262;
				break;
			case 16:
				$this->p++;
				goto a1262;
				break;
			case 17:
				$this->p++;
				goto a1262;
				break;
			case 18:
				$this->p++;
				goto a1262;
				break;
			case 21:
				$this->p++;
				goto a1262;
				break;
			case 22:
				$this->p++;
				goto a1262;
				break;
			case 24:
				$this->p++;
				goto a1262;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(272);
		}

		goto f1261;
		a1262:
		goto k1261;
		f1261:
		$this->p = $o1;
		return false;
		k1261:

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

			goto f1263;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1265;
			}

			$this->p++;
		}
		e1265:
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
					goto n1268;}
				goto a1267;
				n1268:
				break;
			case 39:
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p > $this->f) {
						$this->rec(62);
					}

					goto n1269;}
				goto a1267;
				n1269:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(276);
		}

		goto f1266;
		a1267:
		goto k1266;
		f1266:
		$this->p = $o2;
		goto f1263;
		k1266:
		goto k1263;
		f1263:
		$this->p = $o1;
		return false;
		k1263:

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

			goto f1559;}

		$o2 = $this->p;
		if (!$this->s272()) {
			goto f1560;}
		if (($this->ids[$this->p] === 30)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(51);
		}
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1560;}

		$this->p++;
		if (!$this->s199()) {
			goto f1560;}
		goto k1560;
		f1560:
		$this->p = $o2;
		goto f1559;
		k1560:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1562;
			}

			$this->p++;
		}
		e1562:
		goto k1559;
		f1559:
		$this->p = $o1;
		return false;
		k1559:

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
					goto n659;}
				goto a658;
				n659:
				break;
			case 32:
			case 82:
				if (!$this->s364()) {
					goto n660;}
				goto a658;
				n660:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n661;}
				goto a658;
				n661:
				break;
			case 36:
			case 84:
				// s223
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(223);
					}

					goto n662;}

				$o2 = $this->p;
				$k2 = 0;
				while (true) {
					$b2 = $this->p;
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($b2 > $this->f) {
							$this->rec(224);
						}

						goto e664;
					}

					if (!$this->s224()) {
						goto e664;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e664;
					}

					$k2++;
				}
				e664:

				if ($k2 < 1) {
					goto f663;
				}
				goto k663;
				f663:
				$this->p = $o2;
				goto n662;
				k663:
				goto a658;
				n662:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n665;}
				goto a658;
				n665:
				break;
			case 34:
				if (!$this->s282()) {
					goto n666;}
				goto a658;
				n666:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n667;}
				goto a658;
				n667:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(281);
		}

		goto f657;
		a658:
		goto k657;
		f657:
		$this->p = $o1;
		return false;
		k657:

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

			goto f896;}
		// s284
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(284);
			}

			goto f896;}

		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 34:
				if (!$this->s285()) {
					goto n899;}
				goto a898;
				n899:
				if (!$this->s362()) {
					goto n900;}
				goto a898;
				n900:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(284);
		}

		goto f897;
		a898:
		goto k897;
		f897:
		$this->p = $o2;
		goto f896;
		k897:
		goto k896;
		f896:
		$this->p = $o1;
		return false;
		k896:

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

			goto f1207;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1209;
			}

			$this->p++;
		}
		e1209:
		// s286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f1207;}

		$o2 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1210;}

		$this->p++;
		if (!$this->s287()) {
			goto f1210;}
		if (!$this->s293()) {
			goto f1210;}
		if (!$this->s296()) {
			goto f1210;}
		goto k1210;
		f1210:
		$this->p = $o2;
		goto f1207;
		k1210:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1212;
			}

			$this->p++;
		}
		e1212:
		if (($this->ids[$this->p] === 40)) {
			// s300
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(300);
				}

				goto n1213;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(301);
					}

					goto e1215;
				}

				if (!$this->s301()) {
					goto e1215;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1215;
				}

				$k2++;
			}
			e1215:

			if ($k2 < 1) {
				goto f1214;
			}
			goto k1214;
			f1214:
			$this->p = $o2;
			goto n1213;
			k1214:
			n1213:
		} elseif ($this->p > $this->f) {
			$this->rec(300);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1207;}

		$this->p++;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f1207;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1217;
			}

			$this->p++;
		}
		e1217:
		if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if (!$this->s306()) {
				goto n1218;}
			n1218:
		} elseif ($this->p > $this->f) {
			$this->rec(306);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1207;}

		$this->p++;
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1207;}

		$this->p++;
		if (!$this->s315()) {
			goto f1207;}
		goto k1207;
		f1207:
		$this->p = $o1;
		return false;
		k1207:

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

			goto f1645;}

		$this->p++;
		if (!$this->s287()) {
			goto f1645;}
		if (!$this->s293()) {
			goto f1645;}
		if (!$this->s296()) {
			goto f1645;}
		goto k1645;
		f1645:
		$this->p = $o1;
		return false;
		k1645:

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
						goto f577;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f577;}
				}
				goto k577;
				f577:
				$this->p = $o2;
				goto n576;
				k577:
				goto a575;
				n576:
				break;
			case 13:
				// s288
				if (!($this->ids[$this->p] === 13)) {
					if ($this->p > $this->f) {
						$this->rec(288);
					}

					goto n578;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 13)) {
					if ($this->p >= $this->f) {
						$this->rec(27);
					}

					goto f579;}

				$this->p++;
				if (!$this->s199()) {
					goto f579;}
				goto k579;
				f579:
				$this->p = $o2;
				goto n578;
				k579:
				goto a575;
				n578:
				// s290
				$o2 = $this->p;
				if (($this->ids[$this->p] === 13)) {
					if (($this->ids[$this->p] === 13)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f581;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f581;}
				}
				goto k581;
				f581:
				$this->p = $o2;
				goto n580;
				k581:
				goto a575;
				n580:
				break;
			case 14:
				// s289
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->rec(289);
					}

					goto n582;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p >= $this->f) {
						$this->rec(28);
					}

					goto f583;}

				$this->p++;
				if (!$this->s199()) {
					goto f583;}
				goto k583;
				f583:
				$this->p = $o2;
				goto n582;
				k583:
				goto a575;
				n582:
				// s290
				$o2 = $this->p;
				if (($this->ids[$this->p] === 13)) {
					if (($this->ids[$this->p] === 13)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f585;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f585;}
				}
				goto k585;
				f585:
				$this->p = $o2;
				goto n584;
				k585:
				goto a575;
				n584:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(287);
		}

		goto f574;
		a575:
		goto k574;
		f574:
		$this->p = $o1;
		return false;
		k574:

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
					goto n588;}
				goto a587;
				n588:
				break;
			case 5:
				// s294
				if (!($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->rec(294);
					}

					goto n589;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 5)) {
					if ($this->p >= $this->f) {
						$this->rec(29);
					}

					goto f590;}

				$this->p++;
				if (!$this->s199()) {
					goto f590;}
				goto k590;
				f590:
				$this->p = $o2;
				goto n589;
				k590:
				goto a587;
				n589:
				if (($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n591;}
				goto a587;
				n591:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(293);
		}

		goto f586;
		a587:
		goto k586;
		f586:
		$this->p = $o1;
		return false;
		k586:

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
					goto n594;}
				goto a593;
				n594:
				break;
			case 46:
				// s297
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->rec(297);
					}

					goto n595;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p >= $this->f) {
						$this->rec(69);
					}

					goto f596;}

				$this->p++;
				if (!$this->s199()) {
					goto f596;}
				goto k596;
				f596:
				$this->p = $o2;
				goto n595;
				k596:
				goto a593;
				n595:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n597;}
				goto a593;
				n597:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(296);
		}

		goto f592;
		a593:
		goto k592;
		f592:
		$this->p = $o1;
		return false;
		k592:

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

			goto f1512;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1514;
			}

			$this->p++;
		}
		e1514:
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
					goto n1517;}
				goto a1516;
				n1517:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n1518;}
				goto a1516;
				n1518:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(302);
		}

		goto f1515;
		a1516:
		goto k1515;
		f1515:
		$this->p = $o2;
		goto f1512;
		k1515:
		goto k1512;
		f1512:
		$this->p = $o1;
		return false;
		k1512:

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

			goto f1693;}

		$o2 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1694;}

		$this->p++;
		if (!$this->s287()) {
			goto f1694;}
		if (!$this->s293()) {
			goto f1694;}
		if (!$this->s296()) {
			goto f1694;}
		goto k1694;
		f1694:
		$this->p = $o2;
		goto f1693;
		k1694:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1696;
			}

			$this->p++;
		}
		e1696:
		goto k1693;
		f1693:
		$this->p = $o1;
		return false;
		k1693:

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

			goto f1168;}

		$o2 = $this->p;
		if (!$this->s199()) {
			goto f1169;}
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
		goto k1169;
		f1169:
		$this->p = $o2;
		goto f1168;
		k1169:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1171;
			}

			$this->p++;
		}
		e1171:
		if (($this->ids[$this->p] === 40)) {
			// s311
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(311);
				}

				goto n1172;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(312);
					}

					goto e1174;
				}

				if (!$this->s312()) {
					goto e1174;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1174;
				}

				$k2++;
			}
			e1174:

			if ($k2 < 1) {
				goto f1173;
			}
			goto k1173;
			f1173:
			$this->p = $o2;
			goto n1172;
			k1173:
			n1172:
		} elseif ($this->p > $this->f) {
			$this->rec(311);
		}
		goto k1168;
		f1168:
		$this->p = $o1;
		return false;
		k1168:

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

			goto f1492;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1494;
			}

			$this->p++;
		}
		e1494:
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
					goto n1497;}
				goto a1496;
				n1497:
				break;
			case 33:
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p > $this->f) {
						$this->rec(53);
					}

					goto n1498;}
				goto a1496;
				n1498:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(313);
		}

		goto f1495;
		a1496:
		goto k1495;
		f1495:
		$this->p = $o2;
		goto f1492;
		k1495:
		goto k1492;
		f1492:
		$this->p = $o1;
		return false;
		k1492:

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

			goto f1685;}

		$o2 = $this->p;
		if (!$this->s199()) {
			goto f1686;}
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
		goto k1686;
		f1686:
		$this->p = $o2;
		goto f1685;
		k1686:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1688;
			}

			$this->p++;
		}
		e1688:
		goto k1685;
		f1685:
		$this->p = $o1;
		return false;
		k1685:

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

					goto n1177;}

				$o2 = $this->p;
				if (!isset(self::T1921405e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(332);
					}

					goto f1178;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1178;}
				}
				if (!$this->s318()) {
					goto f1178;}
				goto k1178;
				f1178:
				$this->p = $o2;
				goto n1177;
				k1178:
				goto a1176;
				n1177:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1179;}

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
						goto f1180;}
				}
				if (!$this->s335()) {
					goto f1180;}
				goto k1180;
				f1180:
				$this->p = $o2;
				goto n1179;
				k1180:
				goto a1176;
				n1179:
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

					goto n1182;}

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
						goto f1183;}
				}
				if (!$this->s335()) {
					goto f1183;}
				goto k1183;
				f1183:
				$this->p = $o2;
				goto n1182;
				k1183:
				goto a1176;
				n1182:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->s255()) {
					goto n1185;}
				goto a1176;
				n1185:
				// s317
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(317);
					}

					goto n1186;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f1187;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1187;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1187;}
				}
				if (!$this->s318()) {
					goto f1187;}
				goto k1187;
				f1187:
				$this->p = $o2;
				goto n1186;
				k1187:
				goto a1176;
				n1186:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1188;}

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
						goto f1189;}
				}
				if (!$this->s335()) {
					goto f1189;}
				goto k1189;
				f1189:
				$this->p = $o2;
				goto n1188;
				k1189:
				goto a1176;
				n1188:
				break;
			case 32:
			case 82:
				if (!$this->s316()) {
					goto n1191;}
				goto a1176;
				n1191:
				break;
			case 6:
				if (!$this->s267()) {
					goto n1192;}
				goto a1176;
				n1192:
				// s317
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(317);
					}

					goto n1193;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f1194;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1194;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1194;}
				}
				if (!$this->s318()) {
					goto f1194;}
				goto k1194;
				f1194:
				$this->p = $o2;
				goto n1193;
				k1194:
				goto a1176;
				n1193:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1195;}

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
						goto f1196;}
				}
				if (!$this->s335()) {
					goto f1196;}
				goto k1196;
				f1196:
				$this->p = $o2;
				goto n1195;
				k1196:
				goto a1176;
				n1195:
				break;
			case 7:
				if (!$this->s230()) {
					goto n1198;}
				goto a1176;
				n1198:
				// s317
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(317);
					}

					goto n1199;}

				$o2 = $this->p;
				if (!isset(self::T4020d016[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(279);
					}

					goto f1200;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1200;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1200;}
				}
				if (!$this->s318()) {
					goto f1200;}
				goto k1200;
				f1200:
				$this->p = $o2;
				goto n1199;
				k1200:
				goto a1176;
				n1199:
				// s333
				if (!isset(self::T4546fd6c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(333);
					}

					goto n1201;}

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
						goto f1202;}
				}
				if (!$this->s335()) {
					goto f1202;}
				goto k1202;
				f1202:
				$this->p = $o2;
				goto n1201;
				k1202:
				goto a1176;
				n1201:
				break;
			case 25:
				if (!$this->s229()) {
					goto n1204;}
				goto a1176;
				n1204:
				break;
			case 30:
				// s200
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p > $this->f) {
						$this->rec(200);
					}

					goto n1205;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 30)) {
					if ($this->p >= $this->f) {
						$this->rec(51);
					}

					goto f1206;}

				$this->p++;
				if (!$this->s201()) {
					goto f1206;}
				goto k1206;
				f1206:
				$this->p = $o2;
				goto n1205;
				k1206:
				goto a1176;
				n1205:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(315);
		}

		goto f1175;
		a1176:
		goto k1175;
		f1175:
		$this->p = $o1;
		return false;
		k1175:

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

			goto f1508;}

		$this->p++;
		if (!$this->s205()) {
			goto f1508;}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1508;}

		$this->p++;
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1509;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e1511;
				}

				if (!$this->s224()) {
					goto e1511;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1511;
				}

				$k2++;
			}
			e1511:

			if ($k2 < 1) {
				goto f1510;
			}
			goto k1510;
			f1510:
			$this->p = $o2;
			goto n1509;
			k1510:
			n1509:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1508;
		f1508:
		$this->p = $o1;
		return false;
		k1508:

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
					goto n1501;}
				goto a1500;
				n1501:
				break;
			case 34:
			case 86:
				if (!$this->s319()) {
					goto n1502;}
				goto a1500;
				n1502:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1503;}
				goto a1500;
				n1503:
				break;
			case 36:
			case 84:
				// s223
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(223);
					}

					goto n1504;}

				$o2 = $this->p;
				$k2 = 0;
				while (true) {
					$b2 = $this->p;
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($b2 > $this->f) {
							$this->rec(224);
						}

						goto e1506;
					}

					if (!$this->s224()) {
						goto e1506;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e1506;
					}

					$k2++;
				}
				e1506:

				if ($k2 < 1) {
					goto f1505;
				}
				goto k1505;
				f1505:
				$this->p = $o2;
				goto n1504;
				k1505:
				goto a1500;
				n1504:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1507;}
				goto a1500;
				n1507:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(318);
		}

		goto f1499;
		a1500:
		goto k1499;
		f1499:
		$this->p = $o1;
		return false;
		k1499:

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
			goto f1689;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1690;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e1692;
				}

				if (!$this->s224()) {
					goto e1692;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1692;
				}

				$k2++;
			}
			e1692:

			if ($k2 < 1) {
				goto f1691;
			}
			goto k1691;
			f1691:
			$this->p = $o2;
			goto n1690;
			k1691:
			n1690:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1689;
		f1689:
		$this->p = $o1;
		return false;
		k1689:

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

			goto f598;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e600;
			}

			$this->p++;
		}
		e600:
		if (!$this->s321()) {
			goto f598;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e602;
			}

			$this->p++;
		}
		e602:
		if (($this->ids[$this->p] === 40)) {
			// s327
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(327);
				}

				goto n603;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(328);
					}

					goto e605;
				}

				if (!$this->s328()) {
					goto e605;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e605;
				}

				$k2++;
			}
			e605:

			if ($k2 < 1) {
				goto f604;
			}
			goto k604;
			f604:
			$this->p = $o2;
			goto n603;
			k604:
			n603:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f598;}

		$this->p++;
		goto k598;
		f598:
		$this->p = $o1;
		return false;
		k598:

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

					goto n805;}

				$o2 = $this->p;
				if (isset(self::T05cf281c[$this->ids[$this->p]])) {
					if (isset(self::T05cf281c[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f806;}
				}
				if (!$this->s199()) {
					goto f806;}
				goto k806;
				f806:
				$this->p = $o2;
				goto n805;
				k806:
				goto a804;
				n805:
				break;
			case 1:
			case 2:
				// s322
				if (!isset(self::T05cf281c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(322);
					}

					goto n807;}

				$o2 = $this->p;
				if (!isset(self::T05cf281c[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(323);
					}

					goto f808;}

				$this->p++;
				if (!$this->s199()) {
					goto f808;}
				goto k808;
				f808:
				$this->p = $o2;
				goto n807;
				k808:
				goto a804;
				n807:
				break;
			case 50:
				$this->p++;
				goto a804;
				break;
			case 85:
				$this->p++;
				goto a804;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(321);
		}

		goto f803;
		a804:
		goto k803;
		f803:
		$this->p = $o1;
		return false;
		k803:

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

				goto e1698;
			}

			// s328
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(328);
				}

				goto e1698;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1699;}

			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1701;
				}

				$this->p++;
			}
			e1701:
			if (!$this->s329()) {
				goto f1699;}
			goto k1699;
			f1699:
			$this->p = $o2;
			goto e1698;
			k1699:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1698;
			}

			$k1++;
		}
		e1698:

		if ($k1 < 1) {
			goto f1697;
		}
		goto k1697;
		f1697:
		$this->p = $o1;
		return false;
		k1697:

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

			goto f809;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e811;
			}

			$this->p++;
		}
		e811:
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
					goto n814;}
				goto a813;
				n814:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n815;}
				goto a813;
				n815:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(329);
		}

		goto f812;
		a813:
		goto k812;
		f812:
		$this->p = $o2;
		goto f809;
		k812:
		goto k809;
		f809:
		$this->p = $o1;
		return false;
		k809:

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

					goto n1773;}

				$o2 = $this->p;
				if (!$this->s321()) {
					goto f1774;}
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1776;
					}

					$this->p++;
				}
				e1776:
				goto k1774;
				f1774:
				$this->p = $o2;
				goto n1773;
				k1774:
				goto a1772;
				n1773:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n1777;}
				goto a1772;
				n1777:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(329);
		}

		goto f1771;
		a1772:
		goto k1771;
		f1771:
		$this->p = $o1;
		return false;
		k1771:

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
			goto f1049;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1051;
			}

			$this->p++;
		}
		e1051:
		goto k1049;
		f1049:
		$this->p = $o1;
		return false;
		k1049:

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
				goto f668;}
		}
		if (!$this->s340()) {
			goto f668;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n670;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e672;
				}

				if (!$this->s224()) {
					goto e672;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e672;
				}

				$k2++;
			}
			e672:

			if ($k2 < 1) {
				goto f671;
			}
			goto k671;
			f671:
			$this->p = $o2;
			goto n670;
			k671:
			n670:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k668;
		f668:
		$this->p = $o1;
		return false;
		k668:

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

					goto n849;}

				$o2 = $this->p;
				if (!isset(self::T1ad6c327[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(339);
					}

					goto f850;}

				$this->p++;
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(16);
					}

					goto f850;}

				$this->p++;
				goto k850;
				f850:
				$this->p = $o2;
				goto n849;
				k850:
				goto a848;
				n849:
				break;
			case 36:
				$this->p++;
				goto a848;
				break;
			case 84:
				$this->p++;
				goto a848;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(337);
		}

		goto f847;
		a848:
		goto k847;
		f847:
		$this->p = $o1;
		return false;
		k847:

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

					goto n192;}

				$o2 = $this->p;
				if (isset(self::T1ad6c327[$this->ids[$this->p]])) {
					if (isset(self::T1ad6c327[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f193;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f193;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (!$this->s360()) {
						goto n194;}
					n194:
				} elseif ($this->p > $this->f) {
					$this->rec(360);
				}
				goto k193;
				f193:
				$this->p = $o2;
				goto n192;
				k193:
				goto a191;
				n192:
				break;
			case 7:
			case 18:
				if (!$this->s347()) {
					goto n195;}
				goto a191;
				n195:
				break;
			case 21:
			case 22:
				// s343
				if (!isset(self::T24d0f25b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(343);
					}

					goto n196;}

				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 21:
						$this->p++;
						goto a198;
						break;
					case 22:
						$this->p++;
						goto a198;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(343);
				}

				goto f197;
				a198:
				goto k197;
				f197:
				$this->p = $o2;
				goto n196;
				k197:
				goto a191;
				n196:
				break;
			case 36:
			case 84:
				if (!$this->s361()) {
					goto n199;}
				goto a191;
				n199:
				break;
			case 15:
				$this->p++;
				goto a191;
				break;
			case 16:
				$this->p++;
				goto a191;
				break;
			case 17:
				$this->p++;
				goto a191;
				break;
			case 19:
				$this->p++;
				goto a191;
				break;
			case 20:
				$this->p++;
				goto a191;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(340);
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

			goto f454;}

		$this->p++;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f454;}

		$this->p++;
		if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if (!$this->s349()) {
				goto n455;}
			n455:
		} elseif ($this->p > $this->f) {
			$this->rec(349);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f454;}

		$this->p++;
		goto k454;
		f454:
		$this->p = $o1;
		return false;
		k454:

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

			goto f728;}

		$o2 = $this->p;
		if (!$this->s340()) {
			goto f729;}
		if (!$this->s351()) {
			goto f729;}
		goto k729;
		f729:
		$this->p = $o2;
		goto f728;
		k729:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s355()) {
				goto n730;}
			n730:
		} elseif ($this->p > $this->f) {
			$this->rec(355);
		}
		if (($this->ids[$this->p] === 40)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k728;
		f728:
		$this->p = $o1;
		return false;
		k728:

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
					goto n969;}
				goto a968;
				n969:
				break;
			case 44:
				// s352
				if (!($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->rec(352);
					}

					goto n970;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 44)) {
					if ($this->p >= $this->f) {
						$this->rec(67);
					}

					goto f971;}

				$this->p++;
				if (!$this->s340()) {
					goto f971;}
				goto k971;
				f971:
				$this->p = $o2;
				goto n970;
				k971:
				goto a968;
				n970:
				if (($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n972;}
				goto a968;
				n972:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(351);
		}

		goto f967;
		a968:
		goto k967;
		f967:
		$this->p = $o1;
		return false;
		k967:

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

				goto e974;
			}

			// s356
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(356);
				}

				goto e974;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f975;}

			$this->p++;
			// s350
			if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(350);
				}

				goto f975;}

			$o3 = $this->p;
			if (!$this->s340()) {
				goto f976;}
			if (!$this->s351()) {
				goto f976;}
			goto k976;
			f976:
			$this->p = $o3;
			goto f975;
			k976:
			goto k975;
			f975:
			$this->p = $o2;
			goto e974;
			k975:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e974;
			}

			$k1++;
		}
		e974:

		if ($k1 < 1) {
			goto f973;
		}
		goto k973;
		f973:
		$this->p = $o1;
		return false;
		k973:

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

			goto f453;}

		$this->p++;
		if (!$this->s163()) {
			goto f453;}
		goto k453;
		f453:
		$this->p = $o1;
		return false;
		k453:

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

			goto f456;}

		$this->p++;
		if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if (!$this->s349()) {
				goto n457;}
			n457:
		} elseif ($this->p > $this->f) {
			$this->rec(349);
		}
		if (!($this->ids[$this->p] === 37)) {
			if ($this->p >= $this->f) {
				$this->rec(59);
			}

			goto f456;}

		$this->p++;
		goto k456;
		f456:
		$this->p = $o1;
		return false;
		k456:

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
			goto f1219;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1220;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e1222;
				}

				if (!$this->s224()) {
					goto e1222;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1222;
				}

				$k2++;
			}
			e1222:

			if ($k2 < 1) {
				goto f1221;
			}
			goto k1221;
			f1221:
			$this->p = $o2;
			goto n1220;
			k1221:
			n1220:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1219;
		f1219:
		$this->p = $o1;
		return false;
		k1219:

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

			goto f1519;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1521;
			}

			$this->p++;
		}
		e1521:
		if (!$this->s321()) {
			goto f1519;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1523;
			}

			$this->p++;
		}
		e1523:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s327()) {
				goto n1524;}
			n1524:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1519;}

		$this->p++;
		goto k1519;
		f1519:
		$this->p = $o1;
		return false;
		k1519:

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

			goto f884;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e886;
			}

			$this->p++;
		}
		e886:
		if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if (!$this->s306()) {
				goto n887;}
			n887:
		} elseif ($this->p > $this->f) {
			$this->rec(306);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f884;}

		$this->p++;
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f884;}

		$this->p++;
		if (!$this->s315()) {
			goto f884;}
		goto k884;
		f884:
		$this->p = $o1;
		return false;
		k884:

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
						goto f439;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f439;}
				}
				goto k439;
				f439:
				$this->p = $o2;
				goto n438;
				k439:
				goto a437;
				n438:
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

					goto n440;}

				$o2 = $this->p;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e443;
					}

					$this->p++;
				}
				e443:
				if (!$this->s370()) {
					goto f441;}
				goto k441;
				f441:
				$this->p = $o2;
				goto n440;
				k441:
				goto a437;
				n440:
				// s383
				$o2 = $this->p;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f445;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f445;}
				}
				goto k445;
				f445:
				$this->p = $o2;
				goto n444;
				k445:
				goto a437;
				n444:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(368);
		}

		goto f436;
		a437:
		goto k436;
		f436:
		$this->p = $o1;
		return false;
		k436:

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

					goto n703;}

				$o2 = $this->p;
				if (!$this->s372()) {
					goto f704;}
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f704;}
				}
				goto k704;
				f704:
				$this->p = $o2;
				goto n703;
				k704:
				goto a702;
				n703:
				break;
			case 29:
				// s377
				if (!($this->ids[$this->p] === 29)) {
					if ($this->p > $this->f) {
						$this->rec(377);
					}

					goto n705;}

				$o2 = $this->p;
				if (!$this->s378()) {
					goto f706;}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f706;}
				}
				goto k706;
				f706:
				$this->p = $o2;
				goto n705;
				k706:
				goto a702;
				n705:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(370);
		}

		goto f701;
		a702:
		goto k701;
		f701:
		$this->p = $o1;
		return false;
		k701:

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

				goto e936;
			}

			// s373
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->rec(373);
				}

				goto e936;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p >= $this->f) {
					$this->rec(49);
				}

				goto f937;}

			$this->p++;
			if (!$this->s201()) {
				goto f937;}
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

					goto e941;
				}

				$this->p++;
			}
			e941:
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->rec(49);
				}

				goto f939;}
			goto k939;
			f939:
			$this->p = $o3;
			goto n938;
			k939:
			n938:
			goto k937;
			f937:
			$this->p = $o2;
			goto e936;
			k937:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e936;
			}

			$k1++;
		}
		e936:

		if ($k1 < 1) {
			goto f935;
		}
		goto k935;
		f935:
		$this->p = $o1;
		return false;
		k935:

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

				goto e943;
			}

			// s379
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->rec(379);
				}

				goto e943;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p >= $this->f) {
					$this->rec(50);
				}

				goto f944;}

			$this->p++;
			if (!$this->s201()) {
				goto f944;}
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

					goto e948;
				}

				$this->p++;
			}
			e948:
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->rec(50);
				}

				goto f946;}
			goto k946;
			f946:
			$this->p = $o3;
			goto n945;
			k946:
			n945:
			goto k944;
			f944:
			$this->p = $o2;
			goto e943;
			k944:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e943;
			}

			$k1++;
		}
		e943:

		if ($k1 < 1) {
			goto f942;
		}
		goto k942;
		f942:
		$this->p = $o1;
		return false;
		k942:

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
						goto f361;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f361;}
				}
				if (($this->ids[$this->p] === 32)) {
					if (($this->ids[$this->p] === 32)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f361;}
				}
				if (!$this->s125()) {
					goto f361;}
				goto k361;
				f361:
				$this->p = $o2;
				goto n360;
				k361:
				goto a359;
				n360:
				break;
			case 25:
			case 26:
				// s386
				if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(386);
					}

					goto n362;}

				$o2 = $this->p;
				if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(387);
					}

					goto f363;}

				$this->p++;
				if (!$this->s125()) {
					goto f363;}
				goto k363;
				f363:
				$this->p = $o2;
				goto n362;
				k363:
				goto a359;
				n362:
				// s388
				$o2 = $this->p;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f365;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f365;}
				}
				if (($this->ids[$this->p] === 32)) {
					if (($this->ids[$this->p] === 32)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f365;}
				}
				if (!$this->s125()) {
					goto f365;}
				goto k365;
				f365:
				$this->p = $o2;
				goto n364;
				k365:
				goto a359;
				n364:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(385);
		}

		goto f358;
		a359:
		goto k358;
		f358:
		$this->p = $o1;
		return false;
		k358:

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
			goto f325;}
		// s388
		$o2 = $this->p;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f326;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f326;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f326;}
		}
		if (!$this->s125()) {
			goto f326;}
		goto k326;
		f326:
		$this->p = $o2;
		goto f325;
		k326:
		goto k325;
		f325:
		$this->p = $o1;
		return false;
		k325:

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

					goto n345;}

				$o2 = $this->p;
				if (!$this->s199()) {
					goto f346;}
				if (!$this->s415()) {
					goto f346;}
				goto k346;
				f346:
				$this->p = $o2;
				goto n345;
				k346:
				goto a344;
				n345:
				break;
			case 9:
				// s410
				if (!($this->ids[$this->p] === 9)) {
					if ($this->p > $this->f) {
						$this->rec(410);
					}

					goto n347;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 9)) {
					if ($this->p >= $this->f) {
						$this->rec(25);
					}

					goto f348;}

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
						goto f348;}
				}
				if (!$this->s199()) {
					goto f348;}
				if (!$this->s415()) {
					goto f348;}
				goto k348;
				f348:
				$this->p = $o2;
				goto n347;
				k348:
				goto a344;
				n347:
				// s435
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(435);
					}

					goto n350;}

				$o2 = $this->p;
				if (!$this->s199()) {
					goto f351;}
				if (!$this->s415()) {
					goto f351;}
				goto k351;
				f351:
				$this->p = $o2;
				goto n350;
				k351:
				goto a344;
				n350:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(409);
		}

		goto f343;
		a344:
		goto k343;
		f343:
		$this->p = $o1;
		return false;
		k343:

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

					goto n560;}

				$o2 = $this->p;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e563;
					}

					$this->p++;
				}
				e563:
				if (!isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(414);
					}

					goto f561;}

				$this->p++;
				goto k561;
				f561:
				$this->p = $o2;
				goto n560;
				k561:
				goto a559;
				n560:
				break;
			case 32:
				$this->p++;
				goto a559;
				break;
			case 34:
				$this->p++;
				goto a559;
				break;
			case 36:
				$this->p++;
				goto a559;
				break;
			case 43:
				$this->p++;
				goto a559;
				break;
			case 82:
				$this->p++;
				goto a559;
				break;
			case 84:
				$this->p++;
				goto a559;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(412);
		}

		goto f558;
		a559:
		goto k558;
		f558:
		$this->p = $o1;
		return false;
		k558:

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

					goto n608;}

				$o2 = $this->p;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f609;}

				$this->p++;
				if (!$this->s417()) {
					goto f609;}
				goto k609;
				f609:
				$this->p = $o2;
				goto n608;
				k609:
				goto a607;
				n608:
				break;
			case 32:
			case 34:
			case 82:
			case 86:
				if (!$this->s417()) {
					goto n610;}
				goto a607;
				n610:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(415);
		}

		goto f606;
		a607:
		goto k606;
		f606:
		$this->p = $o1;
		return false;
		k606:

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
					goto n819;}
				goto a818;
				n819:
				break;
			case 34:
			case 86:
				if (!$this->s419()) {
					goto n820;}
				goto a818;
				n820:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n821;}
				goto a818;
				n821:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(418);
		}

		goto f817;
		a818:
		goto k817;
		f817:
		$this->p = $o2;
		goto f816;
		k817:
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f816;}

		$this->p++;
		if (isset(self::T9373805c[$this->ids[$this->p]])) {
			if (!$this->s424()) {
				goto n822;}
			n822:
		} elseif ($this->p > $this->f) {
			$this->rec(424);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f816;}

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

				goto e825;
			}

			$this->p++;
		}
		e825:
		if (($this->ids[$this->p] === 80)) {
			if (!$this->s128()) {
				goto n826;}
			n826:
		} elseif ($this->p > $this->f) {
			$this->rec(128);
		}
		goto k816;
		f816:
		$this->p = $o1;
		return false;
		k816:

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

			goto f1052;}

		$this->p++;
		// s286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f1052;}

		$o2 = $this->p;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1053;}

		$this->p++;
		if (!$this->s287()) {
			goto f1053;}
		if (!$this->s293()) {
			goto f1053;}
		if (!$this->s296()) {
			goto f1053;}
		goto k1053;
		f1053:
		$this->p = $o2;
		goto f1052;
		k1053:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s421()) {
				goto n1054;}
			n1054:
		} elseif ($this->p > $this->f) {
			$this->rec(421);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1052;}

		$this->p++;
		goto k1052;
		f1052:
		$this->p = $o1;
		return false;
		k1052:

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

				goto e1407;
			}

			// s422
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(422);
				}

				goto e1407;}

			$o2 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p >= $this->f) {
					$this->rec(63);
				}

				goto f1408;}

			$this->p++;
			if (!$this->s286()) {
				goto f1408;}
			goto k1408;
			f1408:
			$this->p = $o2;
			goto e1407;
			k1408:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1407;
			}

			$k1++;
		}
		e1407:

		if ($k1 < 1) {
			goto f1406;
		}
		goto k1406;
		f1406:
		$this->p = $o1;
		return false;
		k1406:

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
			goto f1055;}
		if (($this->ids[$this->p] === 40)) {
			// s433
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(433);
				}

				goto n1056;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(434);
					}

					goto e1058;
				}

				if (!$this->s434()) {
					goto e1058;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1058;
				}

				$k2++;
			}
			e1058:

			if ($k2 < 1) {
				goto f1057;
			}
			goto k1057;
			f1057:
			$this->p = $o2;
			goto n1056;
			k1057:
			n1056:
		} elseif ($this->p > $this->f) {
			$this->rec(433);
		}
		goto k1055;
		f1055:
		$this->p = $o1;
		return false;
		k1055:

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

					goto n1411;}

				$o2 = $this->p;
				if (!isset(self::T34c34eee[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(428);
					}

					goto f1412;}
				if (!$this->s199()) {
					goto f1412;}
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

					goto f1412;}

				$this->p++;
				if (!$this->s429()) {
					goto f1412;}
				goto k1412;
				f1412:
				$this->p = $o2;
				goto n1411;
				k1412:
				goto a1410;
				n1411:
				break;
			case 26:
			case 27:
			case 42:
				// s431
				if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(431);
					}

					goto n1413;}

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

					goto f1414;}

				$this->p++;
				if (!$this->s429()) {
					goto f1414;}
				goto k1414;
				f1414:
				$this->p = $o2;
				goto n1413;
				k1414:
				goto a1410;
				n1413:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(425);
		}

		goto f1409;
		a1410:
		goto k1409;
		f1409:
		$this->p = $o1;
		return false;
		k1409:

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
					goto n1648;}
				goto a1647;
				n1648:
				break;
			case 46:
				// s430
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->rec(430);
					}

					goto n1649;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p >= $this->f) {
						$this->rec(69);
					}

					goto f1650;}

				$this->p++;
				if (!$this->s340()) {
					goto f1650;}
				goto k1650;
				f1650:
				$this->p = $o2;
				goto n1649;
				k1650:
				goto a1647;
				n1649:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1651;}
				goto a1647;
				n1651:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(429);
		}

		goto f1646;
		a1647:
		goto k1646;
		f1646:
		$this->p = $o1;
		return false;
		k1646:

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

			goto f1415;}

		$this->p++;
		if (!$this->s425()) {
			goto f1415;}
		goto k1415;
		f1415:
		$this->p = $o1;
		return false;
		k1415:

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

			goto f339;}

		$this->p++;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f339;}

		$this->p++;
		if (!$this->s287()) {
			goto f339;}
		if (!$this->s293()) {
			goto f339;}
		if (!$this->s296()) {
			goto f339;}
		// s388
		$o2 = $this->p;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f340;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f340;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f340;}
		}
		if (!$this->s125()) {
			goto f340;}
		goto k340;
		f340:
		$this->p = $o2;
		goto f339;
		k340:
		goto k339;
		f339:
		$this->p = $o1;
		return false;
		k339:

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

			goto f341;}

		$this->p++;
		if (!$this->s320()) {
			goto f341;}
		// s388
		$o2 = $this->p;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f342;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f342;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f342;}
		}
		if (!$this->s125()) {
			goto f342;}
		goto k342;
		f342:
		$this->p = $o2;
		goto f341;
		k342:
		goto k341;
		f341:
		$this->p = $o1;
		return false;
		k341:

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
					goto n329;}
				goto a328;
				n329:
				break;
			case 14:
				// s445
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->rec(445);
					}

					goto n330;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 14)) {
					if ($this->p >= $this->f) {
						$this->rec(28);
					}

					goto f331;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f331;}

				$this->p++;
				goto k331;
				f331:
				$this->p = $o2;
				goto n330;
				k331:
				goto a328;
				n330:
				if (($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n332;}
				goto a328;
				n332:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(444);
		}

		goto f327;
		a328:
		goto k327;
		f327:
		$this->p = $o1;
		return false;
		k327:

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
					goto n335;}
				goto a334;
				n335:
				break;
			case 45:
				// s450
				if (!($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->rec(450);
					}

					goto n336;}

				$o2 = $this->p;
				if (!($this->ids[$this->p] === 45)) {
					if ($this->p >= $this->f) {
						$this->rec(68);
					}

					goto f337;}

				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f337;}

				$this->p++;
				if (!$this->s451()) {
					goto f337;}
				goto k337;
				f337:
				$this->p = $o2;
				goto n336;
				k337:
				goto a334;
				n336:
				if (($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n338;}
				goto a334;
				n338:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(449);
		}

		goto f333;
		a334:
		goto k333;
		f333:
		$this->p = $o1;
		return false;
		k333:

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
					goto n570;}
				goto a569;
				n570:
				break;
			case 32:
			case 82:
				// s452
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(452);
					}

					goto n571;}

				$o2 = $this->p;
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(16);
					}

					goto f572;}

				$this->p++;
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p >= $this->f) {
						$this->rec(53);
					}

					goto f572;}

				$this->p++;
				goto k572;
				f572:
				$this->p = $o2;
				goto n571;
				k572:
				goto a569;
				n571:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n573;}
				goto a569;
				n573:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(451);
		}

		goto f568;
		a569:
		goto k568;
		f568:
		$this->p = $o1;
		return false;
		k568:

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

			goto f261;}

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

				goto e264;
			}

			$this->p++;
		}
		e264:
		if (($this->ids[$this->p] === 80)) {
			if (!$this->s128()) {
				goto n265;}
			n265:
		} elseif ($this->p > $this->f) {
			$this->rec(128);
		}
		goto k261;
		f261:
		$this->p = $o1;
		return false;
		k261:

		return true;
	}
}
