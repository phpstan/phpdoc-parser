<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser\Generated;

use PHPStan\PhpDocParser\Ast\Comment;
use PHPStan\PhpDocParser\Parser\Phplrt\Token;
use function array_keys;
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
	private const T4546fd6c = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,24=>true];
	private const T5448ab4c = [41=>true,80=>true];
	private const T7680fe2e = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true,21=>true,22=>true,24=>true,25=>true,30=>true,32=>true,82=>true];
	private const T7726a0df = [1=>true,2=>true,3=>true,4=>true,5=>true,6=>true,7=>true,8=>true,9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,20=>true,21=>true,22=>true,23=>true,24=>true];
	private const T8458d590 = [28=>true,29=>true,41=>true,80=>true];
	private const T34048a70 = [38=>true,83=>true];
	private const T942497bd = [76=>true,77=>true,78=>true,79=>true];
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

	abstract protected function reduceDiscardedComment(int $begin, int $end): void;

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
		$this->v[$m1] = $this->reducePhpDoc(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				goto f218;}
		}
		if (!$this->b8()) {
			goto f218;}
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
					goto f221;}
			}
			if (!$this->b8()) {
				goto f221;}
			goto k221;
			f221:
			$this->p = $o3;
			$this->n = $m3;
			$this->comments = $cs3;
			goto e220;
			k221:
			if ($this->p === $b2) {
				goto e220;
			}
		}
		e220:
		goto k218;
		f218:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k218:

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
					goto f504;}
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

							goto n507;}
						goto a506;
						n507:
						break;
					case 80:
						$this->p++;
						goto a506;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f505;
				a506:
				goto k505;
				f505:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f504;
				k505:
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
			case 76:
			case 77:
			case 78:
			case 79:
				// b9
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b10()) {
					goto f509;}
				if (($this->ids[$this->p] === 80)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(3);
				}
				goto k509;
				f509:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n508;
				k509:
				goto a502;
				n508:
				// b188
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b189()) {
					goto f511;}
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

							goto n514;}
						goto a513;
						n514:
						break;
					case 80:
						$this->p++;
						goto a513;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f512;
				a513:
				goto k512;
				f512:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f511;
				k512:
				goto k511;
				f511:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n510;
				k511:
				goto a502;
				n510:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(8);
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
		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f235;}
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

				goto e239;
			}

			$this->p++;
		}
		e239:
		if (!$this->s74()) {
			goto f237;}
		goto k237;
		f237:
		$this->p = $o3;
		goto f235;
		k237:
		$this->v[$this->n++] = $this->reduceDoctrineText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k235;
		f235:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k235:
		$this->v[$m1] = $this->reduceGenericTag(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b125(): bool
	{
		$o1 = $this->p;
		if (!$this->s125()) {
			return false;}
		$this->v[$this->n++] = $this->reduceText(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)));

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
				// b187
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b138()) {
					goto f243;}
				// b19
				$o3 = $this->p;
				// s19
				$o4 = $this->p;
				// s20
				$o5 = $this->p;
				while (true) {
					$b5 = $this->p;
					if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
						if ($b5 > $this->f) {
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
				$this->p = $o4;
				goto f243;
				k245:
				$this->v[$this->n++] = $this->reduceDoctrineText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
				goto k243;
				f243:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n242;
				k243:
				goto a241;
				n242:
				break;
			case 78:
			case 79:
				// b135
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b137()) {
					goto f249;}
				// b19
				$o3 = $this->p;
				// s19
				$o4 = $this->p;
				// s20
				$o5 = $this->p;
				while (true) {
					$b5 = $this->p;
					if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
						if ($b5 > $this->f) {
							$this->rec(21);
						}

						goto e253;
					}

					$this->p++;
				}
				e253:
				if (!$this->s74()) {
					goto f251;}
				goto k251;
				f251:
				$this->p = $o4;
				goto f249;
				k251:
				$this->v[$this->n++] = $this->reduceDoctrineText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
				goto k249;
				f249:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n248;
				k249:
				goto a241;
				n248:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(134);
		}

		goto f240;
		a241:
		goto k240;
		f240:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k240:
		$this->v[$m1] = $this->reduceDoctrineTag(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b137(): bool
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
					goto n526;}
				goto a525;
				n526:
				break;
			case 32:
			case 82:
				if (!$this->b138()) {
					goto n527;}
				goto a525;
				n527:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n528;}
				goto a525;
				n528:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(137);
		}

		goto f524;
		a525:
		goto k524;
		f524:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k524:

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

				goto e518;
			}

			$this->p++;
		}
		e518:
		if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			// b141
			if (!isset(self::T1e2ed78c[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(141);
				}

				goto n519;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b142()) {
				goto f520;}
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

					goto e522;
				}

				$this->p++;
			}
			e522:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b182()) {
					goto n523;}
				n523:
			} elseif ($this->p > $this->f) {
				$this->rec(182);
			}
			goto k520;
			f520:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n519;
			k520:
			n519:
		} elseif ($this->p > $this->f) {
			$this->rec(141);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f516;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k516;
		f516:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k516:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if (!$this->b138()) {
						goto n787;}
					n787:
				} elseif ($this->p > $this->f) {
					$this->rec(138);
				}
				$this->v[$m2] = $this->reduceDoctrineNestedAnnotation(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a784;
				n785:
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
					goto n788;}
				goto a784;
				n788:
				// b172
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f790;}
				}
				goto k790;
				f790:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n789;
				k790:
				$this->v[$m2] = $this->reduceDoctrineIdentifier(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a784;
				n789:
				if (!$this->b160()) {
					goto n791;}
				goto a784;
				n791:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				if (!$this->b143()) {
					goto n792;}
				goto a784;
				n792:
				// b172
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f794;}
				}
				goto k794;
				f794:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n793;
				k794:
				$this->v[$m2] = $this->reduceDoctrineIdentifier(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a784;
				n793:
				break;
			case 22:
			case 23:
				if (!$this->b155()) {
					goto n795;}
				goto a784;
				n795:
				break;
			case 38:
			case 83:
				if (!$this->b148()) {
					goto n796;}
				goto a784;
				n796:
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceDoctrineFloat($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a784;
				break;
			case 20:
				$this->v[$this->n++] = $this->reduceDoctrineInteger($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a784;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(142);
		}

		goto f783;
		a784:
		goto k783;
		f783:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k783:
		$this->v[$m1] = $this->reduceDoctrineArgument(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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

				goto e1055;
			}

			$this->p++;
		}
		e1055:
		if (!($this->ids[$this->p] === 46)) {
			if ($this->p >= $this->f) {
				$this->rec(69);
			}

			goto f1053;}

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

				goto e1057;
			}

			$this->p++;
		}
		e1057:
		if (!$this->b145()) {
			goto f1053;}
		goto k1053;
		f1053:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1053:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if (!$this->b138()) {
						goto n1337;}
					n1337:
				} elseif ($this->p > $this->f) {
					$this->rec(138);
				}
				$this->v[$m2] = $this->reduceDoctrineNestedAnnotation(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a1334;
				n1335:
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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1339;}
				}
				goto k1339;
				f1339:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1338;
				k1339:
				$this->v[$m2] = $this->reduceDoctrineIdentifier(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a1334;
				n1338:
				if (!$this->b160()) {
					goto n1340;}
				goto a1334;
				n1340:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				// b172
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
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
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1341;
				k1342:
				$this->v[$m2] = $this->reduceDoctrineIdentifier(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a1334;
				n1341:
				break;
			case 22:
			case 23:
				if (!$this->b155()) {
					goto n1343;}
				goto a1334;
				n1343:
				break;
			case 38:
			case 83:
				if (!$this->b148()) {
					goto n1344;}
				goto a1334;
				n1344:
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceDoctrineFloat($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a1334;
				break;
			case 20:
				$this->v[$this->n++] = $this->reduceDoctrineInteger($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a1334;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(145);
		}

		goto f1333;
		a1334:
		goto k1333;
		f1333:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1333:

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

				goto e1068;
			}

			$this->p++;
		}
		e1068:
		if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
			// b151
			if (!isset(self::T1490fe7f[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(151);
				}

				goto n1069;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b152()) {
				goto f1070;}
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

					goto e1072;
				}

				$this->p++;
			}
			e1072:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b176()) {
					goto n1073;}
				n1073:
			} elseif ($this->p > $this->f) {
				$this->rec(176);
			}
			goto k1070;
			f1070:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1069;
			k1070:
			n1069:
		} elseif ($this->p > $this->f) {
			$this->rec(151);
		}
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f1066;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1066;
		f1066:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1066:
		$this->v[$m1] = $this->reduceDoctrineArray(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if (!$this->b138()) {
						goto n1349;}
					n1349:
				} elseif ($this->p > $this->f) {
					$this->rec(138);
				}
				$this->v[$m2] = $this->reduceDoctrineNestedAnnotation(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a1346;
				n1347:
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
					goto n1350;}
				goto a1346;
				n1350:
				// b172
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1352;}
				}
				goto k1352;
				f1352:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1351;
				k1352:
				$this->v[$m2] = $this->reduceDoctrineIdentifier(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a1346;
				n1351:
				if (!$this->b160()) {
					goto n1353;}
				goto a1346;
				n1353:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				if (!$this->b153()) {
					goto n1354;}
				goto a1346;
				n1354:
				// b172
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1356;}
				}
				goto k1356;
				f1356:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1355;
				k1356:
				$this->v[$m2] = $this->reduceDoctrineIdentifier(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a1346;
				n1355:
				break;
			case 22:
			case 23:
				if (!$this->b153()) {
					goto n1357;}
				goto a1346;
				n1357:
				if (!$this->b155()) {
					goto n1358;}
				goto a1346;
				n1358:
				break;
			case 38:
			case 83:
				if (!$this->b148()) {
					goto n1359;}
				goto a1346;
				n1359:
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceDoctrineFloat($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a1346;
				break;
			case 20:
				if (!$this->b153()) {
					goto n1360;}
				goto a1346;
				n1360:
				$this->v[$this->n++] = $this->reduceDoctrineInteger($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a1346;
				break;
			case 21:
				if (!$this->b153()) {
					goto n1361;}
				goto a1346;
				n1361:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(152);
		}

		goto f1345;
		a1346:
		goto k1345;
		f1345:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1345:
		$this->v[$m1] = $this->reduceDoctrineArrayItem(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
			goto f1605;}
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

				goto e1607;
			}

			$this->p++;
		}
		e1607:
		if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(171);
			}

			goto f1605;}

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

				goto e1609;
			}

			$this->p++;
		}
		e1609:
		if (!$this->b145()) {
			goto f1605;}
		goto k1605;
		f1605:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1605:

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
					goto n1723;}
				goto a1722;
				n1723:
				// b169
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1725;}
				}
				goto k1725;
				f1725:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1724;
				k1725:
				goto a1722;
				n1724:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				// b169
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1727;}
				}
				goto k1727;
				f1727:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1726;
				k1727:
				goto a1722;
				n1726:
				break;
			case 22:
			case 23:
				if (!$this->b155()) {
					goto n1728;}
				goto a1722;
				n1728:
				break;
			case 20:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1722;
				break;
			case 21:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1722;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(154);
		}

		goto f1721;
		a1722:
		goto k1721;
		f1721:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1721:
		$this->v[$m1] = $this->reduceDoctrineArrayKey(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				// b156
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					// b158
					if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->rec(158);
						}

						goto n1063;}

					$o3 = $this->p;
					$m3 = $this->n;
					$cs3 = $this->comments;
					$k3 = 0;
					while (true) {
						$b3 = $this->p;
						if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
							if ($b3 > $this->f) {
								$this->rec(159);
							}

							goto e1065;
						}

						$this->v[$this->n++] = $this->token($this->p++);

						$k3++;
					}
					e1065:

					if ($k3 < 1) {
						goto f1064;
					}
					goto k1064;
					f1064:
					$this->p = $o3;
					$this->n = $m3;
					$this->comments = $cs3;
					goto n1063;
					k1064:
					n1063:
				} elseif ($this->p > $this->f) {
					$this->rec(158);
				}
				goto a1060;
				n1061:
				break;
			case 23:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1060;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(155);
		}

		goto f1059;
		a1060:
		goto k1059;
		f1059:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1059:
		$this->v[$m1] = $this->reduceDoctrineString(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
		$this->n = $m1 + 1;

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
				goto f1058;}
		}
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f1058;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 43)) {
			if ($this->p >= $this->f) {
				$this->rec(66);
			}

			goto f1058;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b163()) {
			goto f1058;}
		goto k1058;
		f1058:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1058:
		$this->v[$m1] = $this->reduceDoctrineConstantFetch(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				if (!$this->b166()) {
					goto n753;}
				goto a752;
				n753:
				break;
			case 50:
				// b164
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					if (!$this->b166()) {
						goto n756;}
					n756:
				} elseif ($this->p > $this->f) {
					$this->rec(166);
				}
				goto a752;
				n754:
				break;
			case 85:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a752;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(163);
		}

		goto f751;
		a752:
		goto k751;
		f751:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k751:

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
		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
			if (!$this->b168()) {
				goto n1044;}
			n1044:
		} elseif ($this->p > $this->f) {
			$this->rec(168);
		}

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
				// b164
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (isset(self::T83e30518[$this->ids[$this->p]])) {
					// b166
					if (!isset(self::T83e30518[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->rec(166);
						}

						goto n1330;}

					$o3 = $this->p;
					$m3 = $this->n;
					$cs3 = $this->comments;
					$this->v[$this->n++] = $this->token($this->p++);
					if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
						if (!$this->b168()) {
							goto n1332;}
						n1332:
					} elseif ($this->p > $this->f) {
						$this->rec(168);
					}
					n1330:
				} elseif ($this->p > $this->f) {
					$this->rec(166);
				}
				goto a1327;
				n1328:
				break;
			case 85:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1327;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(168);
		}

		goto f1326;
		a1327:
		goto k1326;
		f1326:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1326:

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

				goto e1363;
			}

			// b177
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(177);
				}

				goto e1363;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
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

					goto e1366;
				}

				$this->p++;
			}
			e1366:
			if (!$this->b178()) {
				goto f1364;}
			goto k1364;
			f1364:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1363;
			k1364:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1363;
			}

			$k1++;
		}
		e1363:

		if ($k1 < 1) {
			goto f1362;
		}
		goto k1362;
		f1362:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1362:

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

					goto n1612;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b152()) {
					goto f1613;}
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

						goto e1615;
					}

					$this->p++;
				}
				e1615:
				goto k1613;
				f1613:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1612;
				k1613:
				goto a1611;
				n1612:
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

					goto n1616;}
				goto a1611;
				n1616:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(178);
		}

		goto f1610;
		a1611:
		goto k1610;
		f1610:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1610:

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

				goto e798;
			}

			// b183
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(183);
				}

				goto e798;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
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

					goto e801;
				}

				$this->p++;
			}
			e801:
			if (!$this->b184()) {
				goto f799;}
			goto k799;
			f799:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e798;
			k799:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e798;
			}

			$k1++;
		}
		e798:

		if ($k1 < 1) {
			goto f797;
		}
		goto k797;
		f797:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k797:

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

					goto n1076;}

				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b142()) {
					goto f1077;}
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

						goto e1079;
					}

					$this->p++;
				}
				e1079:
				goto k1077;
				f1077:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1076;
				k1077:
				goto a1075;
				n1076:
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

					goto n1080;}
				goto a1075;
				n1080:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(184);
		}

		goto f1074;
		a1075:
		goto k1074;
		f1074:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1074:

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
				// b454
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f36;}
				$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
				goto k36;
				f36:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n35;
				k36:
				$this->v[$m2] = $this->reduceInvalidTag(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a32;
				n35:
				if (!$this->b455()) {
					goto n38;}
				goto a32;
				n38:
				break;
			case 76:
			case 77:
			case 78:
			case 79:
				// b454
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f40;}
				$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
				goto k40;
				f40:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n39;
				k40:
				$this->v[$m2] = $this->reduceInvalidTag(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a32;
				n39:
				if (!$this->b455()) {
					goto n42;}
				goto a32;
				n42:
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
				// b402
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f60;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b125()) {
					goto f61;}
				goto k61;
				f61:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f60;
				k61:
				goto k60;
				f60:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n59;
				k60:
				goto a58;
				n59:
				break;
			case 52:
				// b405
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f63;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b125()) {
					goto f64;}
				goto k64;
				f64:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f63;
				k64:
				goto k63;
				f63:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n62;
				k63:
				goto a58;
				n62:
				break;
			case 53:
				// b406
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f66;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b125()) {
					goto f67;}
				goto k67;
				f67:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f66;
				k67:
				goto k66;
				f66:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n65;
				k66:
				goto a58;
				n65:
				break;
			case 54:
				// b404
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f69;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b125()) {
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
				goto a58;
				n68:
				break;
			case 55:
				// b401
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b399
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(399);
					}

					goto f72;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f73;}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f73;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b125()) {
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
				goto a58;
				n71:
				break;
			case 56:
				// b395
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f75;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f76;}
				if (!$this->b388()) {
					goto f76;}
				goto k76;
				f76:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f75;
				k76:
				goto k75;
				f75:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n74;
				k75:
				goto a58;
				n74:
				break;
			case 57:
				// b394
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f78;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f79;}
				if (!$this->b388()) {
					goto f79;}
				goto k79;
				f79:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f78;
				k79:
				goto k78;
				f78:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n77;
				k78:
				goto a58;
				n77:
				break;
			case 58:
				// b443
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f81;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!($this->ids[$this->p] === 10)) {
					if ($this->p >= $this->f) {
						$this->rec(26);
					}

					goto f81;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f81;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b444()) {
					goto f81;}
				goto k81;
				f81:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n80;
				k81:
				goto a58;
				n80:
				break;
			case 59:
				if (!$this->b446()) {
					goto n82;}
				goto a58;
				n82:
				break;
			case 60:
				// b397
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f84;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f85;}
				if (!$this->b388()) {
					goto f85;}
				goto k85;
				f85:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f84;
				k85:
				goto k84;
				f84:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n83;
				k84:
				goto a58;
				n83:
				break;
			case 61:
				// b396
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
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
				goto a58;
				n86:
				break;
			case 62:
				if (!$this->b436()) {
					goto n89;}
				goto a58;
				n89:
				break;
			case 63:
				// b398
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b399
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(399);
					}

					goto f91;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f92;}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f92;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b125()) {
					goto f92;}
				goto k92;
				f92:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f91;
				k92:
				goto k91;
				f91:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n90;
				k91:
				goto a58;
				n90:
				break;
			case 64:
				// b439
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b438
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(438);
					}

					goto f94;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b320()) {
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
				goto a58;
				n93:
				break;
			case 65:
				// b407
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f97;}
				$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
				goto k97;
				f97:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n96;
				k97:
				goto a58;
				n96:
				break;
			case 66:
				// b441
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f100;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 46)) {
					$this->v[$this->n++] = $this->token($this->p++);
				} elseif ($this->p >= $this->f) {
					$this->rec(69);
				}
				if (!$this->b199()) {
					goto f100;}
				if (!isset(self::T53b29bff[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(77);
					}

					goto f100;}
				goto k100;
				f100:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n99;
				k100:
				goto a58;
				n99:
				if (!$this->b442()) {
					goto n101;}
				goto a58;
				n101:
				break;
			case 67:
				if (!$this->b400()) {
					goto n102;}
				goto a58;
				n102:
				break;
			case 68:
				// b437
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b438
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(438);
					}

					goto f104;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b320()) {
					goto f105;}
				if (!$this->b388()) {
					goto f105;}
				goto k105;
				f105:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f104;
				k105:
				goto k104;
				f104:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n103;
				k104:
				goto a58;
				n103:
				break;
			case 69:
				// b408
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b409()) {
					goto f107;}
				goto k107;
				f107:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n106;
				k107:
				goto a58;
				n106:
				break;
			case 70:
				// b390
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f109;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f110;}
				if (!$this->b388()) {
					goto f110;}
				goto k110;
				f110:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f109;
				k110:
				goto k109;
				f109:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n108;
				k109:
				goto a58;
				n108:
				break;
			case 71:
				// b392
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f112;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f113;}
				if (!$this->b388()) {
					goto f113;}
				goto k113;
				f113:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f112;
				k113:
				goto k112;
				f112:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n111;
				k112:
				goto a58;
				n111:
				break;
			case 72:
				// b191
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b192()) {
					goto f115;}
				goto k115;
				f115:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n114;
				k115:
				goto a58;
				n114:
				break;
			case 73:
				// b393
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b391
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(391);
					}

					goto f117;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				if (!$this->b199()) {
					goto f118;}
				if (!$this->b388()) {
					goto f118;}
				goto k118;
				f118:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f117;
				k118:
				goto k117;
				f117:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n116;
				k117:
				goto a58;
				n116:
				break;
			case 74:
				// b440
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b438
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(438);
					}

					goto f120;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b320()) {
					goto f121;}
				if (!$this->b388()) {
					goto f121;}
				goto k121;
				f121:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f120;
				k121:
				goto k120;
				f120:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n119;
				k120:
				goto a58;
				n119:
				break;
			case 75:
				// b384
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f123;}
				if (!$this->b385()) {
					goto f123;}
				goto k123;
				f123:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n122;
				k123:
				goto a58;
				n122:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(190);
		}

		goto f57;
		a58:
		goto k57;
		f57:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k57:
		$this->v[$m1] = $this->reduceKnownTag(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
					goto n323;}
				goto a322;
				n323:
				break;
			case 26:
			case 27:
			case 42:
				if (!$this->b193()) {
					goto n324;}
				goto a322;
				n324:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(192);
		}

		goto f321;
		a322:
		goto k321;
		f321:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k321:

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

			goto f615;}
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

			goto f615;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f615;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k615;
		f615:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k615:

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
			goto f613;}
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

			goto f613;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f613;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k613;
		f613:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k613:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b201()) {
					goto f186;}
				if (!$this->b368()) {
					goto f186;}
				goto k186;
				f186:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n185;
				k186:
				goto a184;
				n185:
				break;
			case 30:
				// b200
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b201()) {
					goto f188;}
				goto k188;
				f188:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n187;
				k188:
				$this->v[$m2] = $this->reduceNullable(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a184;
				n187:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(199);
		}

		goto f183;
		a184:
		goto k183;
		f183:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k183:
		$this->v[$m1] = $this->reduceType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f389;}
				}
				if (!$this->b281()) {
					goto f389;}
				goto k389;
				f389:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n388;
				k389:
				goto a387;
				n388:
				// b333
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
						goto f391;}
				}
				if (!$this->b335()) {
					goto f391;}
				goto k391;
				f391:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n390;
				k391:
				goto a387;
				n390:
				break;
			case 19:
			case 20:
			case 21:
			case 22:
				// b333
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
						goto f394;}
				}
				if (!$this->b335()) {
					goto f394;}
				goto k394;
				f394:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n393;
				k394:
				goto a387;
				n393:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->b255()) {
					goto n396;}
				goto a387;
				n396:
				// b278
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f398;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f398;}
				}
				if (!$this->b281()) {
					goto f398;}
				goto k398;
				f398:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n397;
				k398:
				goto a387;
				n397:
				// b333
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
						goto f400;}
				}
				if (!$this->b335()) {
					goto f400;}
				goto k400;
				f400:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n399;
				k400:
				goto a387;
				n399:
				break;
			case 32:
			case 82:
				if (!$this->b202()) {
					goto n402;}
				goto a387;
				n402:
				break;
			case 6:
				if (!$this->b267()) {
					goto n403;}
				goto a387;
				n403:
				// b278
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
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
				if (!$this->b281()) {
					goto f405;}
				goto k405;
				f405:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n404;
				k405:
				goto a387;
				n404:
				// b333
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
						goto f407;}
				}
				if (!$this->b335()) {
					goto f407;}
				goto k407;
				f407:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n406;
				k407:
				goto a387;
				n406:
				break;
			case 7:
				if (!$this->b230()) {
					goto n409;}
				goto a387;
				n409:
				// b278
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f411;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f411;}
				}
				if (!$this->b281()) {
					goto f411;}
				goto k411;
				f411:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n410;
				k411:
				goto a387;
				n410:
				// b333
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
						goto f413;}
				}
				if (!$this->b335()) {
					goto f413;}
				goto k413;
				f413:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n412;
				k413:
				goto a387;
				n412:
				break;
			case 25:
				if (!$this->b229()) {
					goto n415;}
				goto a387;
				n415:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(201);
		}

		goto f386;
		a387:
		goto k386;
		f386:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k386:
		$this->v[$m1] = $this->reduceAtomic(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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

				goto e676;
			}

			$this->p++;
		}
		e676:
		if (!$this->b205()) {
			goto f674;}
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

				goto e678;
			}

			$this->p++;
		}
		e678:
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f674;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n679;}

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

					goto e681;
				}

				if (!$this->b224()) {
					goto e681;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e681;
				}

				$k2++;
			}
			e681:

			if ($k2 < 1) {
				goto f680;
			}
			goto k680;
			f680:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n679;
			k680:
			n679:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k674;
		f674:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k674:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b201()) {
					goto f935;}
				if (!$this->b209()) {
					goto f935;}
				goto k935;
				f935:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n934;
				k935:
				goto a933;
				n934:
				break;
			case 26:
				if (!$this->b206()) {
					goto n936;}
				goto a933;
				n936:
				break;
			case 30:
				// b200
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b201()) {
					goto f938;}
				goto k938;
				f938:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n937;
				k938:
				$this->v[$m2] = $this->reduceNullable(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a933;
				n937:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(205);
		}

		goto f932;
		a933:
		goto k932;
		f932:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k932:
		$this->v[$m1] = $this->reduceSubType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 12)) {
			if ($this->p >= $this->f) {
				$this->rec(23);
			}

			goto f1229;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 11)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->b199()) {
			goto f1229;}
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

				goto e1231;
			}

			$this->p++;
		}
		e1231:
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
			}

			goto f1229;}

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

				goto e1233;
			}

			$this->p++;
		}
		e1233:
		if (!$this->b199()) {
			goto f1229;}
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

				goto e1235;
			}

			$this->p++;
		}
		e1235:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1229;}

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

				goto e1237;
			}

			$this->p++;
		}
		e1237:
		if (!$this->b205()) {
			goto f1229;}
		goto k1229;
		f1229:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1229:

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

						goto e1221;
					}

					$this->p++;
				}
				e1221:
				if (isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if (!$this->b213()) {
						goto n1222;}
					n1222:
				} elseif ($this->p > $this->f) {
					$this->rec(213);
				}
				goto a1217;
				n1218:
				break;
			case 12:
				if (!$this->b210()) {
					goto n1223;}
				goto a1217;
				n1223:
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

						goto e1227;
					}

					$this->p++;
				}
				e1227:
				if (isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if (!$this->b213()) {
						goto n1228;}
					n1228:
				} elseif ($this->p > $this->f) {
					$this->rec(213);
				}
				goto a1217;
				n1224:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(209);
		}

		goto f1216;
		a1217:
		goto k1216;
		f1216:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1216:

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
		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 11)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->b199()) {
			goto f1504;}
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

				goto e1506;
			}

			$this->p++;
		}
		e1506:
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
			}

			goto f1504;}

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

				goto e1508;
			}

			$this->p++;
		}
		e1508:
		if (!$this->b199()) {
			goto f1504;}
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

				goto e1510;
			}

			$this->p++;
		}
		e1510:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1504;}

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

				goto e1512;
			}

			$this->p++;
		}
		e1512:
		if (!$this->b205()) {
			goto f1504;}
		goto k1504;
		f1504:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1504:

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
				// b214
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				// b215
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				$k3 = 0;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 28)) {
						if ($b3 > $this->f) {
							$this->rec(216);
						}

						goto e1499;
					}

					if (!$this->b216()) {
						goto e1499;}
					if ($this->p === $b3) {
						$k3 = 1;
						goto e1499;
					}

					$k3++;
				}
				e1499:

				if ($k3 < 1) {
					goto f1498;
				}
				goto k1498;
				f1498:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f1497;
				k1498:
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1497;}
				}
				goto k1497;
				f1497:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1496;
				k1497:
				goto a1495;
				n1496:
				break;
			case 29:
				// b218
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				// b219
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				$k3 = 0;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 29)) {
						if ($b3 > $this->f) {
							$this->rec(220);
						}

						goto e1503;
					}

					if (!$this->b220()) {
						goto e1503;}
					if ($this->p === $b3) {
						$k3 = 1;
						goto e1503;
					}

					$k3++;
				}
				e1503:

				if ($k3 < 1) {
					goto f1502;
				}
				goto k1502;
				f1502:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f1501;
				k1502:
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1501;}
				}
				goto k1501;
				f1501:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1500;
				k1501:
				goto a1495;
				n1500:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(213);
		}

		goto f1494;
		a1495:
		goto k1494;
		f1494:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1494:

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

				goto e1663;
			}

			$this->p++;
		}
		e1663:
		if (!$this->b201()) {
			goto f1661;}
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

				goto e1665;
			}

			$this->p++;
		}
		e1665:
		goto k1661;
		f1661:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1661:

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

				goto e1668;
			}

			$this->p++;
		}
		e1668:
		if (!$this->b201()) {
			goto f1666;}
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

				goto e1670;
			}

			$this->p++;
		}
		e1670:
		goto k1666;
		f1666:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1666:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 37)) {
					if (($this->ids[$this->p] === 37)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f893;}
				}
				if (!$this->b199()) {
					goto f893;}
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f893;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k893;
				f893:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n892;
				k893:
				goto a891;
				n892:
				// b227
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f895;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k895;
				f895:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n894;
				k895:
				goto a891;
				n894:
				break;
			case 84:
				// b227
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f897;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k897;
				f897:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n896;
				k897:
				goto a891;
				n896:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(224);
		}

		goto f890;
		a891:
		goto k890;
		f890:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k890:
		$this->v[$m1] = $this->reduceArrayOrOffsetAccessItem(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n691;}

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

					goto e693;
				}

				if (!$this->b224()) {
					goto e693;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e693;
				}

				$k2++;
			}
			e693:

			if ($k2 < 1) {
				goto f692;
			}
			goto k692;
			f692:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n691;
			k692:
			n691:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}

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
		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f686;}
		}
		if (!$this->b231()) {
			goto f686;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n687;}

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

					goto e689;
				}

				if (!$this->b224()) {
					goto e689;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e689;
				}

				$k2++;
			}
			e689:

			if ($k2 < 1) {
				goto f688;
			}
			goto k688;
			f688:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n687;
			k688:
			n687:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k686;
		f686:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k686:

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

				goto e949;
			}

			$this->p++;
		}
		e949:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->b233()) {
				goto n950;}
			n950:
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

				goto e952;
			}

			$this->p++;
		}
		e952:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f947;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k947;
		f947:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k947:
		$this->v[$m1] = $this->reduceArrayShape(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				if (!$this->b243()) {
					goto n1246;}
				goto a1245;
				n1246:
				break;
			case 42:
				// b234
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
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

						goto e1250;
					}

					$this->p++;
				}
				e1250:
				if (!$this->b235()) {
					goto f1248;}
				if (($this->ids[$this->p] === 40)) {
					$this->v[$this->n++] = $this->token($this->p++);
				} elseif ($this->p >= $this->f) {
					$this->rec(63);
				}
				goto k1248;
				f1248:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1247;
				k1248:
				goto a1245;
				n1247:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(233);
		}

		goto f1244;
		a1245:
		goto k1244;
		f1244:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1244:

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
					goto n1532;}
				goto a1531;
				n1532:
				break;
			case 34:
			case 86:
				// b236
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b237()) {
					goto f1534;}
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

						goto e1536;
					}

					$this->p++;
				}
				e1536:
				goto k1534;
				f1534:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1533;
				k1534:
				goto a1531;
				n1533:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1537;}
				goto a1531;
				n1537:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(235);
		}

		goto f1530;
		a1531:
		goto k1530;
		f1530:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1530:

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

				goto e1673;
			}

			$this->p++;
		}
		e1673:
		if (!$this->b199()) {
			goto f1671;}
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

				goto e1675;
			}

			$this->p++;
		}
		e1675:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->b240()) {
				goto n1676;}
			n1676:
		} elseif ($this->p > $this->f) {
			$this->rec(240);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1671;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1671;
		f1671:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1671:
		$this->v[$m1] = $this->reduceArrayShapeUnsealedType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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

				goto e1731;
			}

			$this->p++;
		}
		e1731:
		if (!$this->b199()) {
			goto f1729;}
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
		goto k1729;
		f1729:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1729:

		return true;
	}

	private function b243(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(243);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!$this->b244()) {
			goto f1522;}
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

				goto e1524;
			}

			$this->p++;
		}
		e1524:
		if (($this->ids[$this->p] === 40)) {
			// b252
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(252);
				}

				goto n1525;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$this->v[$this->n++] = $this->token($this->p++);
			if (($this->ids[$this->p] === 41)) {
				$this->reduceDiscardedComment($this->indexes[$this->p], $this->indexes[$this->p]);
				$this->p++;
			} elseif ($this->p >= $this->f) {
				$this->rec(254);
			}
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

					goto e1528;
				}

				$this->p++;
			}
			e1528:
			if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
				if (!$this->b233()) {
					goto n1529;}
				n1529:
			} elseif ($this->p > $this->f) {
				$this->rec(233);
			}
			n1525:
		} elseif ($this->p > $this->f) {
			$this->rec(252);
		}
		goto k1522;
		f1522:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1522:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b246()) {
					goto f1476;}
				if (($this->ids[$this->p] === 30)) {
					$this->v[$this->n++] = $this->token($this->p++);
				} elseif ($this->p >= $this->f) {
					$this->rec(51);
				}
				if (!($this->ids[$this->p] === 47)) {
					if ($this->p >= $this->f) {
						$this->rec(70);
					}

					goto f1476;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f1476;}
				goto k1476;
				f1476:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1475;
				k1476:
				goto a1474;
				n1475:
				if (!$this->b199()) {
					goto n1477;}
				goto a1474;
				n1477:
				break;
			case 19:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->b199()) {
					goto n1478;}
				goto a1474;
				n1478:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(244);
		}

		goto f1473;
		a1474:
		goto k1473;
		f1473:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1473:
		$this->v[$m1] = $this->reduceArrayShapeItem(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				// b247
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					// b249
					if (!($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->rec(249);
						}

						goto n1654;}

					$o3 = $this->p;
					$m3 = $this->n;
					$cs3 = $this->comments;
					$this->v[$this->n++] = $this->token($this->p++);
					if (!isset(self::T83e30518[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->rec(144);
						}

						goto f1655;}

					$this->v[$this->n++] = $this->token($this->p++);
					goto k1655;
					f1655:
					$this->p = $o3;
					$this->n = $m3;
					$this->comments = $cs3;
					goto n1654;
					k1655:
					n1654:
				} elseif ($this->p > $this->f) {
					$this->rec(249);
				}
				goto a1651;
				n1652:
				break;
			case 20:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1651;
				break;
			case 21:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1651;
				break;
			case 22:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1651;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(246);
		}

		goto f1650;
		a1651:
		goto k1650;
		f1650:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1650:
		$this->v[$m1] = $this->reduceArrayShapeKey(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
		$this->n = $m1 + 1;

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
		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f670;}
		}
		if (!$this->b257()) {
			goto f670;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n671;}

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

					goto e673;
				}

				if (!$this->b224()) {
					goto e673;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e673;
				}

				$k2++;
			}
			e673:

			if ($k2 < 1) {
				goto f672;
			}
			goto k672;
			f672:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n671;
			k672:
			n671:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k670;
		f670:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k670:

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

				goto e924;
			}

			$this->p++;
		}
		e924:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			// b259
			if (!isset(self::T50fd39f8[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(259);
				}

				goto n925;}

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
				case 19:
				case 20:
				case 21:
				case 22:
				case 24:
				case 25:
				case 30:
				case 32:
				case 82:
					if (!$this->b264()) {
						goto n928;}
					goto a927;
					n928:
					break;
				case 42:
					if (!$this->b260()) {
						goto n929;}
					goto a927;
					n929:
					break;
			}

			if ($this->p > $this->f) {
				$this->rec(259);
			}

			goto f926;
			a927:
			goto k926;
			f926:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n925;
			k926:
			n925:
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

				goto e931;
			}

			$this->p++;
		}
		e931:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f922;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k922;
		f922:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k922:
		$this->v[$m1] = $this->reduceListShape(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				if (!$this->b264()) {
					goto n1481;}
				goto a1480;
				n1481:
				break;
			case 42:
				// b260
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
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

						goto e1485;
					}

					$this->p++;
				}
				e1485:
				if (!$this->b261()) {
					goto f1483;}
				if (($this->ids[$this->p] === 40)) {
					$this->v[$this->n++] = $this->token($this->p++);
				} elseif ($this->p >= $this->f) {
					$this->rec(63);
				}
				goto k1483;
				f1483:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1482;
				k1483:
				goto a1480;
				n1482:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(259);
		}

		goto f1479;
		a1480:
		goto k1479;
		f1479:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1479:

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

				goto e1215;
			}

			$this->p++;
		}
		e1215:
		if (!$this->b261()) {
			goto f1213;}
		if (($this->ids[$this->p] === 40)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k1213;
		f1213:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1213:

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
					goto n1488;}
				goto a1487;
				n1488:
				break;
			case 34:
			case 86:
				// b262
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b263()) {
					goto f1490;}
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

						goto e1492;
					}

					$this->p++;
				}
				e1492:
				goto k1490;
				f1490:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1489;
				k1490:
				goto a1487;
				n1489:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1493;}
				goto a1487;
				n1493:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(261);
		}

		goto f1486;
		a1487:
		goto k1486;
		f1486:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1486:

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

				goto e1658;
			}

			$this->p++;
		}
		e1658:
		if (!$this->b199()) {
			goto f1656;}
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

				goto e1660;
			}

			$this->p++;
		}
		e1660:
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1656;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1656;
		f1656:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1656:
		$this->v[$m1] = $this->reduceListShapeUnsealedType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
		$this->n = $m1 + 1;

		return true;
	}

	private function b264(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(264);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
		if (!$this->b244()) {
			goto f1205;}
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

				goto e1207;
			}

			$this->p++;
		}
		e1207:
		if (($this->ids[$this->p] === 40)) {
			// b266
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(266);
				}

				goto n1208;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$this->v[$this->n++] = $this->token($this->p++);
			if (($this->ids[$this->p] === 41)) {
				$this->reduceDiscardedComment($this->indexes[$this->p], $this->indexes[$this->p]);
				$this->p++;
			} elseif ($this->p >= $this->f) {
				$this->rec(254);
			}
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

					goto e1211;
				}

				$this->p++;
			}
			e1211:
			if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
				if (!$this->b259()) {
					goto n1212;}
				n1212:
			} elseif ($this->p > $this->f) {
				$this->rec(259);
			}
			n1208:
		} elseif ($this->p > $this->f) {
			$this->rec(266);
		}
		goto k1205;
		f1205:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1205:

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
		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f682;}
		}
		if (!$this->b268()) {
			goto f682;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n683;}

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

					goto e685;
				}

				if (!$this->b224()) {
					goto e685;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e685;
				}

				$k2++;
			}
			e685:

			if ($k2 < 1) {
				goto f684;
			}
			goto k684;
			f684:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n683;
			k684:
			n683:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k682;
		f682:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k682:

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

				goto e941;
			}

			$this->p++;
		}
		e941:
		if (isset(self::Td8017923[$this->ids[$this->p]])) {
			// b270
			if (!isset(self::Td8017923[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(270);
				}

				goto n942;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b271()) {
				goto f943;}
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

					goto e945;
				}

				$this->p++;
			}
			e945:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b274()) {
					goto n946;}
				n946:
			} elseif ($this->p > $this->f) {
				$this->rec(274);
			}
			goto k943;
			f943:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n942;
			k943:
			n942:
		} elseif ($this->p > $this->f) {
			$this->rec(270);
		}
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f939;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k939;
		f939:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k939:
		$this->v[$m1] = $this->reduceObjectShape(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
			goto f1238;}
		if (($this->ids[$this->p] === 30)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(51);
		}
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1238;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b199()) {
			goto f1238;}
		goto k1238;
		f1238:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1238:
		$this->v[$m1] = $this->reduceObjectShapeItem(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				goto a1514;
				break;
			case 2:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 3:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 4:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 5:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 6:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 7:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 8:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 9:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 10:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 11:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 12:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 13:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 14:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 15:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 16:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 17:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 18:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 21:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 22:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
			case 24:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a1514;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(272);
		}

		goto f1513;
		a1514:
		goto k1513;
		f1513:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1513:
		$this->v[$m1] = $this->reduceObjectShapeKey(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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

				goto e1240;
			}

			// b275
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(275);
				}

				goto e1240;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
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

					goto e1243;
				}

				$this->p++;
			}
			e1243:
			if (!$this->b276()) {
				goto f1241;}
			goto k1241;
			f1241:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1240;
			k1241:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1240;
			}

			$k1++;
		}
		e1240:

		if ($k1 < 1) {
			goto f1239;
		}
		goto k1239;
		f1239:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1239:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b271()) {
					goto f1518;}
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

						goto e1520;
					}

					$this->p++;
				}
				e1520:
				goto k1518;
				f1518:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1517;
				k1518:
				goto a1516;
				n1517:
				break;
			case 39:
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p > $this->f) {
						$this->rec(62);
					}

					goto n1521;}
				goto a1516;
				n1521:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(276);
		}

		goto f1515;
		a1516:
		goto k1515;
		f1515:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1515:

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
					goto n650;}
				goto a649;
				n650:
				break;
			case 32:
			case 82:
				if (!$this->b364()) {
					goto n651;}
				goto a649;
				n651:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n652;}
				goto a649;
				n652:
				break;
			case 36:
			case 84:
				// b223
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

						goto e655;
					}

					if (!$this->b224()) {
						goto e655;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e655;
					}

					$k2++;
				}
				e655:

				if ($k2 < 1) {
					goto f654;
				}
				goto k654;
				f654:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n653;
				k654:
				goto a649;
				n653:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n656;}
				goto a649;
				n656:
				break;
			case 34:
				// b282
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->rec(54);
					}

					goto f658;}
				// b284
				if (!($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->rec(284);
					}

					goto f658;}

				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 34:
						if (!$this->b285()) {
							goto n661;}
						goto a660;
						n661:
						if (!$this->b362()) {
							goto n662;}
						goto a660;
						n662:
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(284);
				}

				goto f659;
				a660:
				goto k659;
				f659:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f658;
				k659:
				goto k658;
				f658:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n657;
				k658:
				goto a649;
				n657:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n663;}
				goto a649;
				n663:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(281);
		}

		goto f648;
		a649:
		goto k648;
		f648:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k648:

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

				goto e900;
			}

			$this->p++;
		}
		e900:
		// b286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f898;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f901;}
		if (!$this->b293()) {
			goto f901;}
		if (!$this->b296()) {
			goto f901;}
		goto k901;
		f901:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f898;
		k901:
		$this->v[$m2] = $this->reduceCallableTemplateArgument(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
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

				goto e903;
			}

			$this->p++;
		}
		e903:
		if (($this->ids[$this->p] === 40)) {
			// b300
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(300);
				}

				goto n904;}

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

					goto e906;
				}

				if (!$this->b301()) {
					goto e906;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e906;
				}

				$k2++;
			}
			e906:

			if ($k2 < 1) {
				goto f905;
			}
			goto k905;
			f905:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n904;
			k905:
			n904:
		} elseif ($this->p > $this->f) {
			$this->rec(300);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f898;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f898;}

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

				goto e908;
			}

			$this->p++;
		}
		e908:
		if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
			// b306
			if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(306);
				}

				goto n909;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b307()) {
				goto f910;}
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

					goto e912;
				}

				$this->p++;
			}
			e912:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b311()) {
					goto n913;}
				n913:
			} elseif ($this->p > $this->f) {
				$this->rec(311);
			}
			goto k910;
			f910:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n909;
			k910:
			n909:
		} elseif ($this->p > $this->f) {
			$this->rec(306);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f898;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f898;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b315()) {
			goto f898;}
		goto k898;
		f898:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k898:
		$this->v[$m1] = $this->reduceCallableWithTemplates(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				$this->n = $m2;
				$this->comments = $cs2;
				goto n569;
				k570:
				goto a568;
				n569:
				break;
			case 13:
				// b288
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f572;}
				goto k572;
				f572:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n571;
				k572:
				goto a568;
				n571:
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
						goto f574;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f574;}
				}
				goto k574;
				f574:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n573;
				k574:
				goto a568;
				n573:
				break;
			case 14:
				// b289
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f576;}
				goto k576;
				f576:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n575;
				k576:
				goto a568;
				n575:
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
						goto f578;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f578;}
				}
				goto k578;
				f578:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n577;
				k578:
				goto a568;
				n577:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(287);
		}

		goto f567;
		a568:
		goto k567;
		f567:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k567:

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
					goto n581;}
				goto a580;
				n581:
				break;
			case 5:
				// b294
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f583;}
				goto k583;
				f583:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n582;
				k583:
				goto a580;
				n582:
				if (($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n584;}
				goto a580;
				n584:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(293);
		}

		goto f579;
		a580:
		goto k579;
		f579:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k579:

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
					goto n587;}
				goto a586;
				n587:
				break;
			case 46:
				// b297
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f589;}
				goto k589;
				f589:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n588;
				k589:
				goto a586;
				n588:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n590;}
				goto a586;
				n590:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(296);
		}

		goto f585;
		a586:
		goto k585;
		f585:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k585:

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

				goto e1194;
			}

			$this->p++;
		}
		e1194:
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
					goto n1197;}
				goto a1196;
				n1197:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n1198;}
				goto a1196;
				n1198:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(302);
		}

		goto f1195;
		a1196:
		goto k1195;
		f1195:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1192;
		k1195:
		goto k1192;
		f1192:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1192:

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
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f1470;}
		if (!$this->b293()) {
			goto f1470;}
		if (!$this->b296()) {
			goto f1470;}
		goto k1470;
		f1470:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1469;
		k1470:
		$this->v[$m2] = $this->reduceCallableTemplateArgument(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
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

				goto e1472;
			}

			$this->p++;
		}
		e1472:
		goto k1469;
		f1469:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1469:

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
			goto f1154;}
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
		goto k1154;
		f1154:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1154:
		$this->v[$m1] = $this->reduceCallableParameter(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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

				goto e1156;
			}

			// b312
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(312);
				}

				goto e1156;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
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

					goto e1159;
				}

				$this->p++;
			}
			e1159:
			if (!$this->b313()) {
				goto f1157;}
			goto k1157;
			f1157:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1156;
			k1157:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1156;
			}

			$k1++;
		}
		e1156:

		if ($k1 < 1) {
			goto f1155;
		}
		goto k1155;
		f1155:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1155:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b307()) {
					goto f1452;}
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

						goto e1454;
					}

					$this->p++;
				}
				e1454:
				goto k1452;
				f1452:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1451;
				k1452:
				goto a1450;
				n1451:
				break;
			case 33:
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p > $this->f) {
						$this->rec(53);
					}

					goto n1455;}
				goto a1450;
				n1455:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(313);
		}

		goto f1449;
		a1450:
		goto k1449;
		f1449:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1449:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1163;}
				}
				if (!$this->b318()) {
					goto f1163;}
				goto k1163;
				f1163:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1162;
				k1163:
				goto a1161;
				n1162:
				// b333
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
						goto f1165;}
				}
				if (!$this->b335()) {
					goto f1165;}
				goto k1165;
				f1165:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1164;
				k1165:
				goto a1161;
				n1164:
				break;
			case 19:
			case 20:
			case 21:
			case 22:
				// b333
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
						goto f1168;}
				}
				if (!$this->b335()) {
					goto f1168;}
				goto k1168;
				f1168:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1167;
				k1168:
				goto a1161;
				n1167:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->b255()) {
					goto n1170;}
				goto a1161;
				n1170:
				// b317
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1172;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1172;}
				}
				if (!$this->b318()) {
					goto f1172;}
				goto k1172;
				f1172:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1171;
				k1172:
				goto a1161;
				n1171:
				// b333
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
						goto f1174;}
				}
				if (!$this->b335()) {
					goto f1174;}
				goto k1174;
				f1174:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1173;
				k1174:
				goto a1161;
				n1173:
				break;
			case 32:
			case 82:
				if (!$this->b316()) {
					goto n1176;}
				goto a1161;
				n1176:
				break;
			case 6:
				if (!$this->b267()) {
					goto n1177;}
				goto a1161;
				n1177:
				// b317
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1179;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1179;}
				}
				if (!$this->b318()) {
					goto f1179;}
				goto k1179;
				f1179:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1178;
				k1179:
				goto a1161;
				n1178:
				// b333
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
						goto f1181;}
				}
				if (!$this->b335()) {
					goto f1181;}
				goto k1181;
				f1181:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1180;
				k1181:
				goto a1161;
				n1180:
				break;
			case 7:
				if (!$this->b230()) {
					goto n1183;}
				goto a1161;
				n1183:
				// b317
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1185;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1185;}
				}
				if (!$this->b318()) {
					goto f1185;}
				goto k1185;
				f1185:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1184;
				k1185:
				goto a1161;
				n1184:
				// b333
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
						goto f1187;}
				}
				if (!$this->b335()) {
					goto f1187;}
				goto k1187;
				f1187:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1186;
				k1187:
				goto a1161;
				n1186:
				break;
			case 25:
				if (!$this->b229()) {
					goto n1189;}
				goto a1161;
				n1189:
				break;
			case 30:
				// b200
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b201()) {
					goto f1191;}
				goto k1191;
				f1191:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1190;
				k1191:
				$this->v[$m2] = $this->reduceNullable(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a1161;
				n1190:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(315);
		}

		goto f1160;
		a1161:
		goto k1160;
		f1160:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1160:
		$this->v[$m1] = $this->reduceCallableReturnType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b205()) {
			goto f1465;}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1465;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1466;}

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

					goto e1468;
				}

				if (!$this->b224()) {
					goto e1468;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1468;
				}

				$k2++;
			}
			e1468:

			if ($k2 < 1) {
				goto f1467;
			}
			goto k1467;
			f1467:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1466;
			k1467:
			n1466:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1465;
		f1465:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1465:

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
					goto n1458;}
				goto a1457;
				n1458:
				break;
			case 34:
			case 86:
				if (!$this->b319()) {
					goto n1459;}
				goto a1457;
				n1459:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1460;}
				goto a1457;
				n1460:
				break;
			case 36:
			case 84:
				// b223
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

						goto e1463;
					}

					if (!$this->b224()) {
						goto e1463;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e1463;
					}

					$k2++;
				}
				e1463:

				if ($k2 < 1) {
					goto f1462;
				}
				goto k1462;
				f1462:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1461;
				k1462:
				goto a1457;
				n1461:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1464;}
				goto a1457;
				n1464:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(318);
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
			goto f1646;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1647;}

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

					goto e1649;
				}

				if (!$this->b224()) {
					goto e1649;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1649;
				}

				$k2++;
			}
			e1649:

			if ($k2 < 1) {
				goto f1648;
			}
			goto k1648;
			f1648:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1647;
			k1648:
			n1647:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1646;
		f1646:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1646:

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

				goto e303;
			}

			$this->p++;
		}
		e303:
		if (!$this->b321()) {
			goto f301;}
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

				goto e305;
			}

			$this->p++;
		}
		e305:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->b327()) {
				goto n306;}
			n306:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f301;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k301;
		f301:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k301:
		$this->v[$m1] = $this->reduceGenericInReturnType(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (isset(self::T05cf281c[$this->ids[$this->p]])) {
					if (isset(self::T05cf281c[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f594;}
				}
				if (!$this->b199()) {
					goto f594;}
				goto k594;
				f594:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n593;
				k594:
				goto a592;
				n593:
				break;
			case 1:
			case 2:
				// b322
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b199()) {
					goto f596;}
				goto k596;
				f596:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n595;
				k596:
				goto a592;
				n595:
				break;
			case 50:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a592;
				break;
			case 85:
				$this->v[$this->n++] = $this->token($this->p++);
				goto a592;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(321);
		}

		goto f591;
		a592:
		goto k591;
		f591:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k591:
		$this->v[$m1] = $this->reduceGenericArgument(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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

				goto e598;
			}

			// b328
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(328);
				}

				goto e598;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
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

					goto e601;
				}

				$this->p++;
			}
			e601:
			if (!$this->b329()) {
				goto f599;}
			goto k599;
			f599:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e598;
			k599:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e598;
			}

			$k1++;
		}
		e598:

		if ($k1 < 1) {
			goto f597;
		}
		goto k597;
		f597:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k597:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b321()) {
					goto f857;}
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

						goto e859;
					}

					$this->p++;
				}
				e859:
				goto k857;
				f857:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n856;
				k857:
				goto a855;
				n856:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n860;}
				goto a855;
				n860:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(329);
		}

		goto f854;
		a855:
		goto k854;
		f854:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k854:

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
				goto f665;}
		}
		if (!$this->b340()) {
			goto f665;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n667;}

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

					goto e669;
				}

				if (!$this->b224()) {
					goto e669;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e669;
				}

				$k2++;
			}
			e669:

			if ($k2 < 1) {
				goto f668;
			}
			goto k668;
			f668:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n667;
			k668:
			n667:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k665;
		f665:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k665:

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
					goto n197;}
				goto a196;
				n197:
				break;
			case 7:
			case 18:
				if (!$this->b347()) {
					goto n198;}
				goto a196;
				n198:
				break;
			case 21:
			case 22:
				// b343
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 21:
						$this->v[$this->n++] = $this->token($this->p++);
						goto a201;
						break;
					case 22:
						$this->v[$this->n++] = $this->token($this->p++);
						goto a201;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(343);
				}

				goto f200;
				a201:
				goto k200;
				f200:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n199;
				k200:
				$this->v[$m2] = $this->reduceConstantString(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
				$this->n = $m2 + 1;
				goto a196;
				n199:
				break;
			case 36:
			case 84:
				if (!$this->b361()) {
					goto n202;}
				goto a196;
				n202:
				break;
			case 15:
				$this->v[$this->n++] = $this->reduceConstantFalse($this->indexes[$this->p], $this->indexes[$this->p]);
				$this->p++;
				goto a196;
				break;
			case 16:
				$this->v[$this->n++] = $this->reduceConstantTrue($this->indexes[$this->p], $this->indexes[$this->p]);
				$this->p++;
				goto a196;
				break;
			case 17:
				$this->v[$this->n++] = $this->reduceConstantNull($this->indexes[$this->p], $this->indexes[$this->p]);
				$this->p++;
				goto a196;
				break;
			case 19:
				$this->v[$this->n++] = $this->reduceConstantFloat($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a196;
				break;
			case 20:
				$this->v[$this->n++] = $this->reduceConstantInteger($this->indexes[$this->p], $this->indexes[$this->p], [$this->token($this->p)]);
				$this->p++;
				goto a196;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(340);
		}

		goto f195;
		a196:
		goto k195;
		f195:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k195:

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
		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f474;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if (!$this->b349()) {
				goto n475;}
			n475:
		} elseif ($this->p > $this->f) {
			$this->rec(349);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f474;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k474;
		f474:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k474:
		$this->v[$m1] = $this->reduceConstantArrayLiteral(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b340()) {
			goto f758;}
		if (!$this->b351()) {
			goto f758;}
		goto k758;
		f758:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f757;
		k758:
		$this->v[$m2] = $this->reduceConstantArrayItem(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
		$this->n = $m2 + 1;
		if (($this->ids[$this->p] === 40)) {
			// b355
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(355);
				}

				goto n759;}

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

					goto e761;
				}

				if (!$this->b356()) {
					goto e761;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e761;
				}

				$k2++;
			}
			e761:

			if ($k2 < 1) {
				goto f760;
			}
			goto k760;
			f760:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n759;
			k760:
			n759:
		} elseif ($this->p > $this->f) {
			$this->rec(355);
		}
		if (($this->ids[$this->p] === 40)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k757;
		f757:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k757:

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
					goto n1047;}
				goto a1046;
				n1047:
				break;
			case 44:
				// b352
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b340()) {
					goto f1049;}
				goto k1049;
				f1049:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1048;
				k1049:
				goto a1046;
				n1048:
				if (($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1050;}
				goto a1046;
				n1050:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(351);
		}

		goto f1045;
		a1046:
		goto k1045;
		f1045:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1045:

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
		$this->v[$this->n++] = $this->token($this->p++);
		// b350
		if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(350);
			}

			goto f1051;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b340()) {
			goto f1052;}
		if (!$this->b351()) {
			goto f1052;}
		goto k1052;
		f1052:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1051;
		k1052:
		$this->v[$m2] = $this->reduceConstantArrayItem(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
		$this->n = $m2 + 1;
		goto k1051;
		f1051:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1051:

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
				goto f471;}
		}
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f471;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 43)) {
			// b360
			if (!($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->rec(360);
				}

				goto n472;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$this->v[$this->n++] = $this->token($this->p++);
			if (!$this->b163()) {
				goto f473;}
			goto k473;
			f473:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n472;
			k473:
			n472:
		} elseif ($this->p > $this->f) {
			$this->rec(360);
		}
		goto k471;
		f471:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k471:
		$this->v[$m1] = $this->reduceConstantFetch(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if (!$this->b349()) {
				goto n477;}
			n477:
		} elseif ($this->p > $this->f) {
			$this->rec(349);
		}
		if (!($this->ids[$this->p] === 37)) {
			if ($this->p >= $this->f) {
				$this->rec(59);
			}

			goto f476;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k476;
		f476:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k476:
		$this->v[$m1] = $this->reduceConstantArrayShorthand(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
			goto f914;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// b223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n915;}

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

					goto e917;
				}

				if (!$this->b224()) {
					goto e917;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e917;
				}

				$k2++;
			}
			e917:

			if ($k2 < 1) {
				goto f916;
			}
			goto k916;
			f916:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n915;
			k916:
			n915:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k914;
		f914:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k914:

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

				goto e1201;
			}

			$this->p++;
		}
		e1201:
		if (!$this->b321()) {
			goto f1199;}
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

				goto e1203;
			}

			$this->p++;
		}
		e1203:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->b327()) {
				goto n1204;}
			n1204:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1199;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1199;
		f1199:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1199:
		$this->v[$m1] = $this->reduceGeneric(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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

				goto e884;
			}

			$this->p++;
		}
		e884:
		if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
			// b306
			if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(306);
				}

				goto n885;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b307()) {
				goto f886;}
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

					goto e888;
				}

				$this->p++;
			}
			e888:
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b311()) {
					goto n889;}
				n889:
			} elseif ($this->p > $this->f) {
				$this->rec(311);
			}
			goto k886;
			f886:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n885;
			k886:
			n885:
		} elseif ($this->p > $this->f) {
			$this->rec(306);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f882;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f882;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b315()) {
			goto f882;}
		goto k882;
		f882:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k882:
		$this->v[$m1] = $this->reduceCallable(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
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
				$this->n = $m2;
				$this->comments = $cs2;
				goto n418;
				k419:
				goto a417;
				n418:
				break;
			case 28:
			case 29:
			case 41:
			case 80:
				if (!$this->b369()) {
					goto n420;}
				goto a417;
				n420:
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
						goto f422;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f422;}
				}
				goto k422;
				f422:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n421;
				k422:
				goto a417;
				n421:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(368);
		}

		goto f416;
		a417:
		goto k416;
		f416:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k416:

		return true;
	}

	private function b369(): bool
	{
		if (!isset(self::T8458d590[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(369);
			}

			return false;}

		$o1 = $this->p;
		$m1 = $this->n;
		$cs1 = $this->comments;
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

				goto e696;
			}

			$this->p++;
		}
		e696:
		if (!$this->b370()) {
			goto f694;}
		goto k694;
		f694:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k694:

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
				// b371
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				// b372
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				$k3 = 0;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 28)) {
						if ($b3 > $this->f) {
							$this->rec(373);
						}

						goto e958;
					}

					if (!$this->b373()) {
						goto e958;}
					if ($this->p === $b3) {
						$k3 = 1;
						goto e958;
					}

					$k3++;
				}
				e958:

				if ($k3 < 1) {
					goto f957;
				}
				goto k957;
				f957:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f956;
				k957:
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f956;}
				}
				goto k956;
				f956:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n955;
				k956:
				goto a954;
				n955:
				break;
			case 29:
				// b377
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				// b378
				$o3 = $this->p;
				$m3 = $this->n;
				$cs3 = $this->comments;
				$k3 = 0;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 29)) {
						if ($b3 > $this->f) {
							$this->rec(379);
						}

						goto e962;
					}

					if (!$this->b379()) {
						goto e962;}
					if ($this->p === $b3) {
						$k3 = 1;
						goto e962;
					}

					$k3++;
				}
				e962:

				if ($k3 < 1) {
					goto f961;
				}
				goto k961;
				f961:
				$this->p = $o3;
				$this->n = $m3;
				$this->comments = $cs3;
				goto f960;
				k961:
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f960;}
				}
				goto k960;
				f960:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n959;
				k960:
				goto a954;
				n959:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(370);
		}

		goto f953;
		a954:
		goto k953;
		f953:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k953:

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
		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b201()) {
			goto f1251;}
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

				goto e1255;
			}

			$this->p++;
		}
		e1255:
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(49);
			}

			goto f1253;}
		goto k1253;
		f1253:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto n1252;
		k1253:
		n1252:
		goto k1251;
		f1251:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1251:

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
		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b201()) {
			goto f1256;}
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

				goto e1260;
			}

			$this->p++;
		}
		e1260:
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(50);
			}

			goto f1258;}
		goto k1258;
		f1258:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto n1257;
		k1258:
		n1257:
		goto k1256;
		f1256:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1256:

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
					goto n327;}
				goto a326;
				n327:
				break;
			case 25:
			case 26:
				// b386
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				// b125
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f329;}
				$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
				goto k329;
				f329:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n328;
				k329:
				goto a326;
				n328:
				if (!$this->b388()) {
					goto n331;}
				goto a326;
				n331:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(385);
		}

		goto f325;
		a326:
		goto k325;
		f325:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k325:

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
				goto f290;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f290;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f290;}
		}
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f290;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k290;
		f290:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k290:

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
		$this->v[$this->n++] = $this->token($this->p++);
		// b399
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(399);
			}

			goto f309;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		if (!$this->b199()) {
			goto f310;}
		if (!($this->ids[$this->p] === 26)) {
			if ($this->p >= $this->f) {
				$this->rec(47);
			}

			goto f310;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f310;}
		$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
		goto k310;
		f310:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f309;
		k310:
		goto k309;
		f309:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k309:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b199()) {
					goto f315;}
				if (!$this->b415()) {
					goto f315;}
				goto k315;
				f315:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n314;
				k315:
				goto a313;
				n314:
				break;
			case 9:
				// b410
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
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
						goto f317;}
				}
				if (!$this->b199()) {
					goto f317;}
				if (!$this->b415()) {
					goto f317;}
				goto k317;
				f317:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n316;
				k317:
				goto a313;
				n316:
				// b435
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!$this->b199()) {
					goto f320;}
				if (!$this->b415()) {
					goto f320;}
				goto k320;
				f320:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n319;
				k320:
				goto a313;
				n319:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(409);
		}

		goto f312;
		a313:
		goto k312;
		f312:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k312:

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
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b417()) {
					goto f605;}
				goto k605;
				f605:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n604;
				k605:
				goto a603;
				n604:
				break;
			case 32:
			case 34:
			case 82:
			case 86:
				if (!$this->b417()) {
					goto n606;}
				goto a603;
				n606:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(415);
		}

		goto f602;
		a603:
		goto k602;
		f602:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k602:

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
					goto n864;}
				goto a863;
				n864:
				break;
			case 34:
			case 86:
				if (!$this->b419()) {
					goto n865;}
				goto a863;
				n865:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n866;}
				goto a863;
				n866:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(418);
		}

		goto f862;
		a863:
		goto k862;
		f862:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f861;
		k862:
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f861;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (isset(self::T9373805c[$this->ids[$this->p]])) {
			// b424
			if (!isset(self::T9373805c[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(424);
				}

				goto n867;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			if (!$this->b425()) {
				goto f868;}
			if (($this->ids[$this->p] === 40)) {
				if (!$this->b433()) {
					goto n869;}
				n869:
			} elseif ($this->p > $this->f) {
				$this->rec(433);
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
			$this->rec(424);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f861;}

		$this->v[$this->n++] = $this->token($this->p++);
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f861;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k861;
		f861:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k861:

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
		$this->v[$this->n++] = $this->token($this->p++);
		// b286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f1120;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f1121;}
		if (!$this->b293()) {
			goto f1121;}
		if (!$this->b296()) {
			goto f1121;}
		goto k1121;
		f1121:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1120;
		k1121:
		$this->v[$m2] = $this->reduceCallableTemplateArgument(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
		$this->n = $m2 + 1;
		if (($this->ids[$this->p] === 40)) {
			// b421
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(421);
				}

				goto n1122;}

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

					goto e1124;
				}

				if (!$this->b422()) {
					goto e1124;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1124;
				}

				$k2++;
			}
			e1124:

			if ($k2 < 1) {
				goto f1123;
			}
			goto k1123;
			f1123:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto n1122;
			k1123:
			n1122:
		} elseif ($this->p > $this->f) {
			$this->rec(421);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1120;}

		$this->v[$this->n++] = $this->token($this->p++);
		goto k1120;
		f1120:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1120:

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
		$this->v[$this->n++] = $this->token($this->p++);
		// b286
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(286);
			}

			goto f1417;}

		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f1418;}
		if (!$this->b293()) {
			goto f1418;}
		if (!$this->b296()) {
			goto f1418;}
		goto k1418;
		f1418:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f1417;
		k1418:
		$this->v[$m2] = $this->reduceCallableTemplateArgument(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
		$this->n = $m2 + 1;
		goto k1417;
		f1417:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1417:

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
				// b426
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				if (!isset(self::T34c34eee[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(428);
					}

					goto f1128;}
				if (!$this->b199()) {
					goto f1128;}
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

					goto f1128;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b429()) {
					goto f1128;}
				goto k1128;
				f1128:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1127;
				k1128:
				goto a1126;
				n1127:
				break;
			case 26:
			case 27:
			case 42:
				// b431
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

					goto f1130;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b429()) {
					goto f1130;}
				goto k1130;
				f1130:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1129;
				k1130:
				goto a1126;
				n1129:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(425);
		}

		goto f1125;
		a1126:
		goto k1125;
		f1125:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1125:
		$this->v[$m1] = $this->reduceMethodParameter(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
		$this->n = $m1 + 1;

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
					goto n1421;}
				goto a1420;
				n1421:
				break;
			case 46:
				// b430
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b340()) {
					goto f1423;}
				goto k1423;
				f1423:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n1422;
				k1423:
				goto a1420;
				n1422:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1424;}
				goto a1420;
				n1424:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(429);
		}

		goto f1419;
		a1420:
		goto k1419;
		f1419:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1419:

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

				goto e1132;
			}

			// b434
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(434);
				}

				goto e1132;}

			$o2 = $this->p;
			$m2 = $this->n;
			$cs2 = $this->comments;
			$this->v[$this->n++] = $this->token($this->p++);
			if (!$this->b425()) {
				goto f1133;}
			goto k1133;
			f1133:
			$this->p = $o2;
			$this->n = $m2;
			$this->comments = $cs2;
			goto e1132;
			k1133:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1132;
			}

			$k1++;
		}
		e1132:

		if ($k1 < 1) {
			goto f1131;
		}
		goto k1131;
		f1131:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k1131:

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
		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f300;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b287()) {
			goto f300;}
		if (!$this->b293()) {
			goto f300;}
		if (!$this->b296()) {
			goto f300;}
		if (!$this->b388()) {
			goto f300;}
		goto k300;
		f300:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k300:

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
		$this->v[$this->n++] = $this->token($this->p++);
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f307;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (($this->ids[$this->p] === 46)) {
			$this->v[$this->n++] = $this->token($this->p++);
		} elseif ($this->p >= $this->f) {
			$this->rec(69);
		}
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f307;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k307;
		f307:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k307:
		$this->v[$m1] = $this->reduceInvalidTypeAliasTag(($o1 < $this->p ? $this->indexes[$o1] : ($o1 > 0 ? $this->indexes[$o1 - 1] + 1 : 0)), ($o1 < $this->p ? $this->indexes[$this->p - 1] : ($o1 > 0 ? $this->indexes[$o1 - 1] : -1)), $this->n === $m1 + 1 ? [$this->v[$m1]] : \array_slice($this->v, $m1, $this->n - $m1));
		$this->n = $m1 + 1;

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
					goto n294;}
				goto a293;
				n294:
				break;
			case 14:
				// b445
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f296;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k296;
				f296:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n295;
				k296:
				goto a293;
				n295:
				if (($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n297;}
				goto a293;
				n297:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(444);
		}

		goto f292;
		a293:
		goto k292;
		f292:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k292:

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
			goto f298;}
		if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(448);
			}

			goto f298;}

		$this->v[$this->n++] = $this->token($this->p++);
		if (!$this->b449()) {
			goto f298;}
		// b125
		$o2 = $this->p;
		if (!$this->s125()) {
			goto f298;}
		$this->v[$this->n++] = $this->reduceText(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)));
		goto k298;
		f298:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k298:

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
					goto n563;}
				goto a562;
				n563:
				break;
			case 45:
				// b450
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f565;}

				$this->v[$this->n++] = $this->token($this->p++);
				if (!$this->b451()) {
					goto f565;}
				goto k565;
				f565:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n564;
				k565:
				goto a562;
				n564:
				if (($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n566;}
				goto a562;
				n566:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(449);
		}

		goto f561;
		a562:
		goto k561;
		f561:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k561:

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
					goto n850;}
				goto a849;
				n850:
				break;
			case 32:
			case 82:
				// b452
				$o2 = $this->p;
				$m2 = $this->n;
				$cs2 = $this->comments;
				$this->v[$this->n++] = $this->token($this->p++);
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p >= $this->f) {
						$this->rec(53);
					}

					goto f852;}

				$this->v[$this->n++] = $this->token($this->p++);
				goto k852;
				f852:
				$this->p = $o2;
				$this->n = $m2;
				$this->comments = $cs2;
				goto n851;
				k852:
				goto a849;
				n851:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n853;}
				goto a849;
				n853:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(451);
		}

		goto f848;
		a849:
		goto k848;
		f848:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k848:

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
				goto f279;}
		}
		// b457
		$o2 = $this->p;
		$m2 = $this->n;
		$cs2 = $this->comments;
		// b125
		$o3 = $this->p;
		if (!$this->s125()) {
			goto f280;}
		$this->v[$this->n++] = $this->reduceText(($o3 < $this->p ? $this->indexes[$o3] : ($o3 > 0 ? $this->indexes[$o3 - 1] + 1 : 0)), ($o3 < $this->p ? $this->indexes[$this->p - 1] : ($o3 > 0 ? $this->indexes[$o3 - 1] : -1)));
		goto k280;
		f280:
		$this->p = $o2;
		$this->n = $m2;
		$this->comments = $cs2;
		goto f279;
		k280:
		$this->v[$m2] = $this->reduceTextChild(($o2 < $this->p ? $this->indexes[$o2] : ($o2 > 0 ? $this->indexes[$o2 - 1] + 1 : 0)), ($o2 < $this->p ? $this->indexes[$this->p - 1] : ($o2 > 0 ? $this->indexes[$o2 - 1] : -1)), $this->n === $m2 + 1 ? [$this->v[$m2]] : \array_slice($this->v, $m2, $this->n - $m2));
		$this->n = $m2 + 1;
		goto k279;
		f279:
		$this->p = $o1;
		$this->n = $m1;
		$this->comments = $cs1;
		return false;
		k279:

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
					goto f225;}
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

							goto n228;}
						goto a227;
						n228:
						break;
					case 80:
						$this->p++;
						goto a227;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f226;
				a227:
				goto k226;
				f226:
				$this->p = $o3;
				goto f225;
				k226:
				goto k225;
				f225:
				$this->p = $o2;
				goto n224;
				k225:
				goto a223;
				n224:
				break;
			case 76:
			case 77:
			case 78:
			case 79:
				if (!$this->s9()) {
					goto n229;}
				goto a223;
				n229:
				// s188
				$o2 = $this->p;
				if (!$this->s189()) {
					goto f231;}
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

							goto n234;}
						goto a233;
						n234:
						break;
					case 80:
						$this->p++;
						goto a233;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(458);
				}

				goto f232;
				a233:
				goto k232;
				f232:
				$this->p = $o3;
				goto f231;
				k232:
				goto k231;
				f231:
				$this->p = $o2;
				goto n230;
				k231:
				goto a223;
				n230:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(8);
		}

		goto f222;
		a223:
		goto k222;
		f222:
		$this->p = $o1;
		return false;
		k222:

		return true;
	}

	private function s9(): bool
	{
		if (!isset(self::T942497bd[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(9);
			}

			return false;}

		$o1 = $this->p;
		if (!$this->s10()) {
			goto f515;}
		if (($this->ids[$this->p] === 80)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(3);
		}
		goto k515;
		f515:
		$this->p = $o1;
		return false;
		k515:

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
				$o2 = $this->p;
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

					goto f257;}
				if (!$this->s78()) {
					goto f257;}
				goto k257;
				f257:
				$this->p = $o2;
				goto n256;
				k257:
				goto a255;
				n256:
				// s117
				$o2 = $this->p;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f259;}
				if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
					if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f259;}
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
						goto f259;}
				}
				if (!$this->s125()) {
					goto f259;}
				goto k259;
				f259:
				$this->p = $o2;
				goto n258;
				k259:
				goto a255;
				n258:
				break;
			case 76:
			case 78:
				// s75
				$o2 = $this->p;
				if (!isset(self::T53b29bff[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(77);
					}

					goto f262;}
				if (!$this->s78()) {
					goto f262;}
				goto k262;
				f262:
				$this->p = $o2;
				goto n261;
				k262:
				goto a255;
				n261:
				// s117
				$o2 = $this->p;
				if (!isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(119);
					}

					goto f264;}
				if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
					if (isset(self::Tb3a20a39[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f264;}
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
						goto f264;}
				}
				if (!$this->s125()) {
					goto f264;}
				goto k264;
				f264:
				$this->p = $o2;
				goto n263;
				k264:
				goto a255;
				n263:
				if (!isset(self::Tb3a20a39[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(121);
					}

					goto n266;}
				goto a255;
				n266:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(74);
		}

		goto f254;
		a255:
		goto k254;
		f254:
		$this->p = $o1;
		return false;
		k254:

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
						goto n531;}
				}
				goto a530;
				n531:
				break;
			case 80:
				// s79
				$o2 = $this->p;
				$this->p++;
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if (isset(self::T195b3f33[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f534;}
				}
				// s109
				if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(109);
					}

					goto f534;}

				$o3 = $this->p;
				$k3 = 0;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(21);
						}

						goto e536;
					}

					$this->p++;

					$k3++;
				}
				e536:

				if ($k3 < 1) {
					goto f535;
				}
				goto k535;
				f535:
				$this->p = $o3;
				goto f534;
				k535:
				if (!$this->s74()) {
					goto f534;}
				goto k534;
				f534:
				$this->p = $o2;
				goto n533;
				k534:
				goto a530;
				n533:
				// s110
				$o2 = $this->p;
				$this->p++;
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if (isset(self::T195b3f33[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f538;}
				}
				if (!$this->s111()) {
					goto f538;}
				goto k538;
				f538:
				$this->p = $o2;
				goto n537;
				k538:
				goto a530;
				n537:
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
						goto n539;}
				}
				goto a530;
				n539:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(78);
		}

		goto f529;
		a530:
		goto k529;
		f529:
		$this->p = $o1;
		return false;
		k529:

		return true;
	}

	private function s111(): bool
	{
		if (!($this->ids[$this->p] === 80)) {
			if ($this->p > $this->f) {
				$this->rec(111);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 80:
				// s79
				$o2 = $this->p;
				$this->p++;
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if (isset(self::T195b3f33[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f811;}
				}
				// s109
				if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(109);
					}

					goto f811;}

				$o3 = $this->p;
				$k3 = 0;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T0979f8dc[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(21);
						}

						goto e813;
					}

					$this->p++;

					$k3++;
				}
				e813:

				if ($k3 < 1) {
					goto f812;
				}
				goto k812;
				f812:
				$this->p = $o3;
				goto f811;
				k812:
				if (!$this->s74()) {
					goto f811;}
				goto k811;
				f811:
				$this->p = $o2;
				goto n810;
				k811:
				goto a809;
				n810:
				// s110
				$o2 = $this->p;
				$this->p++;
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if (isset(self::T195b3f33[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f815;}
				}
				if (!$this->s111()) {
					goto f815;}
				goto k815;
				f815:
				$this->p = $o2;
				goto n814;
				k815:
				goto a809;
				n814:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(111);
		}

		goto f808;
		a809:
		goto k808;
		f808:
		$this->p = $o1;
		return false;
		k808:

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
				$o2 = $this->p;
				$this->p++;
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if (isset(self::T195b3f33[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f805;}
				}
				if (!$this->s113()) {
					goto f805;}
				goto k805;
				f805:
				$this->p = $o2;
				goto n804;
				k805:
				goto a803;
				n804:
				// s115
				$o2 = $this->p;
				$this->p++;
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if (isset(self::T195b3f33[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f807;}
				}
				if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(116);
					}

					goto f807;}

				$this->p++;
				goto k807;
				f807:
				$this->p = $o2;
				goto n806;
				k807:
				goto a803;
				n806:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(113);
		}

		goto f802;
		a803:
		goto k802;
		f802:
		$this->p = $o1;
		return false;
		k802:

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
		$this->p++;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f541;}

		$this->p++;
		goto k541;
		f541:
		$this->p = $o1;
		return false;
		k541:

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

				goto e284;
			}

			$this->p++;
		}
		e284:
		if (($this->ids[$this->p] === 80)) {
			// s128
			if (!($this->ids[$this->p] === 80)) {
				if ($this->p > $this->f) {
					$this->rec(128);
				}

				goto n285;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b2 > $this->f) {
						$this->rec(129);
					}

					goto e287;
				}

				if (!$this->s129()) {
					goto e287;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e287;
				}

				$k2++;
			}
			e287:

			if ($k2 < 1) {
				goto f286;
			}
			goto k286;
			f286:
			$this->p = $o2;
			goto n285;
			k286:
			n285:
		} elseif ($this->p > $this->f) {
			$this->rec(128);
		}

		return true;
	}

	private function s129(): bool
	{
		if (!($this->ids[$this->p] === 80)) {
			if ($this->p > $this->f) {
				$this->rec(129);
			}

			return false;}

		$o1 = $this->p;
		// s130
		$o2 = $this->p;
		$k2 = 0;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 > $this->f) {
					$this->rec(131);
				}

				goto e557;
			}

			// s131
			if (!($this->ids[$this->p] === 80)) {
				if ($this->p > $this->f) {
					$this->rec(131);
				}

				goto e557;}

			$o3 = $this->p;
			$this->p++;
			if (isset(self::T195b3f33[$this->ids[$this->p]])) {
				if (isset(self::T195b3f33[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto f558;}
			}
			goto k558;
			f558:
			$this->p = $o3;
			goto e557;
			k558:
			if ($this->p === $b2) {
				$k2 = 1;
				goto e557;
			}

			$k2++;
		}
		e557:

		if ($k2 < 1) {
			goto f556;
		}
		goto k556;
		f556:
		$this->p = $o2;
		goto f555;
		k556:
		// s132
		if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(132);
			}

			goto f555;}

		$o2 = $this->p;
		$k2 = 0;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T61cd0e44[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(116);
				}

				goto e560;
			}

			$this->p++;

			$k2++;
		}
		e560:

		if ($k2 < 1) {
			goto f559;
		}
		goto k559;
		f559:
		$this->p = $o2;
		goto f555;
		k559:
		goto k555;
		f555:
		$this->p = $o1;
		return false;
		k555:

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
		$this->p++;
		if (!$this->s137()) {
			goto f275;}
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

				goto e278;
			}

			$this->p++;
		}
		e278:
		if (!$this->s74()) {
			goto f276;}
		goto k276;
		f276:
		$this->p = $o2;
		goto f275;
		k276:
		goto k275;
		f275:
		$this->p = $o1;
		return false;
		k275:

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
					goto n548;}
				goto a547;
				n548:
				break;
			case 32:
			case 82:
				// s138
				$o2 = $this->p;
				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e552;
					}

					$this->p++;
				}
				e552:
				if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
					if (!$this->s141()) {
						goto n553;}
					n553:
				} elseif ($this->p > $this->f) {
					$this->rec(141);
				}
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p >= $this->f) {
						$this->rec(53);
					}

					goto f550;}

				$this->p++;
				goto k550;
				f550:
				$this->p = $o2;
				goto n549;
				k550:
				goto a547;
				n549:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n554;}
				goto a547;
				n554:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(137);
		}

		goto f546;
		a547:
		goto k546;
		f546:
		$this->p = $o1;
		return false;
		k546:

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
			goto f542;}
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e544;
			}

			$this->p++;
		}
		e544:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s182()) {
				goto n545;}
			n545:
		} elseif ($this->p > $this->f) {
			$this->rec(182);
		}
		goto k542;
		f542:
		$this->p = $o1;
		return false;
		k542:

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
					goto n818;}
				goto a817;
				n818:
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
				// s143
				$o2 = $this->p;
				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e822;
					}

					$this->p++;
				}
				e822:
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p >= $this->f) {
						$this->rec(69);
					}

					goto f820;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e824;
					}

					$this->p++;
				}
				e824:
				if (!$this->s145()) {
					goto f820;}
				goto k820;
				f820:
				$this->p = $o2;
				goto n819;
				k820:
				goto a817;
				n819:
				// s172
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f826;}
				}
				goto k826;
				f826:
				$this->p = $o2;
				goto n825;
				k826:
				goto a817;
				n825:
				// s160
				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f828;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f828;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f828;}

				$this->p++;
				if (!$this->s163()) {
					goto f828;}
				goto k828;
				f828:
				$this->p = $o2;
				goto n827;
				k828:
				goto a817;
				n827:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				// s143
				$o2 = $this->p;
				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e832;
					}

					$this->p++;
				}
				e832:
				if (!($this->ids[$this->p] === 46)) {
					if ($this->p >= $this->f) {
						$this->rec(69);
					}

					goto f830;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e834;
					}

					$this->p++;
				}
				e834:
				if (!$this->s145()) {
					goto f830;}
				goto k830;
				f830:
				$this->p = $o2;
				goto n829;
				k830:
				goto a817;
				n829:
				// s172
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f836;}
				}
				goto k836;
				f836:
				$this->p = $o2;
				goto n835;
				k836:
				goto a817;
				n835:
				break;
			case 22:
			case 23:
				if (!$this->s155()) {
					goto n837;}
				goto a817;
				n837:
				break;
			case 38:
			case 83:
				// s148
				$o2 = $this->p;
				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e841;
					}

					$this->p++;
				}
				e841:
				if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
					if (!$this->s151()) {
						goto n842;}
					n842:
				} elseif ($this->p > $this->f) {
					$this->rec(151);
				}
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p >= $this->f) {
						$this->rec(62);
					}

					goto f839;}

				$this->p++;
				goto k839;
				f839:
				$this->p = $o2;
				goto n838;
				k839:
				goto a817;
				n838:
				break;
			case 19:
				$this->p++;
				goto a817;
				break;
			case 20:
				$this->p++;
				goto a817;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(142);
		}

		goto f816;
		a817:
		goto k816;
		f816:
		$this->p = $o1;
		return false;
		k816:

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
				if (!$this->s146()) {
					goto n1089;}
				goto a1088;
				n1089:
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
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1091;}
				}
				goto k1091;
				f1091:
				$this->p = $o2;
				goto n1090;
				k1091:
				goto a1088;
				n1090:
				// s160
				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1093;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1093;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f1093;}

				$this->p++;
				if (!$this->s163()) {
					goto f1093;}
				goto k1093;
				f1093:
				$this->p = $o2;
				goto n1092;
				k1093:
				goto a1088;
				n1092:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				// s172
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1095;}
				}
				goto k1095;
				f1095:
				$this->p = $o2;
				goto n1094;
				k1095:
				goto a1088;
				n1094:
				break;
			case 22:
			case 23:
				if (!$this->s155()) {
					goto n1096;}
				goto a1088;
				n1096:
				break;
			case 38:
			case 83:
				// s148
				$o2 = $this->p;
				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1100;
					}

					$this->p++;
				}
				e1100:
				if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
					if (!$this->s151()) {
						goto n1101;}
					n1101:
				} elseif ($this->p > $this->f) {
					$this->rec(151);
				}
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p >= $this->f) {
						$this->rec(62);
					}

					goto f1098;}

				$this->p++;
				goto k1098;
				f1098:
				$this->p = $o2;
				goto n1097;
				k1098:
				goto a1088;
				n1097:
				break;
			case 19:
				$this->p++;
				goto a1088;
				break;
			case 20:
				$this->p++;
				goto a1088;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(145);
		}

		goto f1087;
		a1088:
		goto k1087;
		f1087:
		$this->p = $o1;
		return false;
		k1087:

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
		$this->p++;
		if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			// s138
			if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(138);
				}

				goto n1082;}

			$o2 = $this->p;
			$this->p++;
			// s139
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e1085;
				}

				$this->p++;
			}
			e1085:
			if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
				if (!$this->s141()) {
					goto n1086;}
				n1086:
			} elseif ($this->p > $this->f) {
				$this->rec(141);
			}
			if (!($this->ids[$this->p] === 33)) {
				if ($this->p >= $this->f) {
					$this->rec(53);
				}

				goto f1083;}

			$this->p++;
			goto k1083;
			f1083:
			$this->p = $o2;
			goto n1082;
			k1083:
			n1082:
		} elseif ($this->p > $this->f) {
			$this->rec(138);
		}

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
			goto f1109;}
		// s139
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b2 >= $this->f) {
					$this->rec(3);
				}

				goto e1111;
			}

			$this->p++;
		}
		e1111:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s176()) {
				goto n1112;}
			n1112:
		} elseif ($this->p > $this->f) {
			$this->rec(176);
		}
		goto k1109;
		f1109:
		$this->p = $o1;
		return false;
		k1109:

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
				if (!$this->s146()) {
					goto n1369;}
				goto a1368;
				n1369:
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
				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1371;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1373;
					}

					$this->p++;
				}
				e1373:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1371;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1375;
					}

					$this->p++;
				}
				e1375:
				if (!$this->s145()) {
					goto f1371;}
				goto k1371;
				f1371:
				$this->p = $o2;
				goto n1370;
				k1371:
				goto a1368;
				n1370:
				// s172
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1377;}
				}
				goto k1377;
				f1377:
				$this->p = $o2;
				goto n1376;
				k1377:
				goto a1368;
				n1376:
				// s160
				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1379;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1379;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f1379;}

				$this->p++;
				if (!$this->s163()) {
					goto f1379;}
				goto k1379;
				f1379:
				$this->p = $o2;
				goto n1378;
				k1379:
				goto a1368;
				n1378:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				// s153
				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1381;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1383;
					}

					$this->p++;
				}
				e1383:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1381;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1385;
					}

					$this->p++;
				}
				e1385:
				if (!$this->s145()) {
					goto f1381;}
				goto k1381;
				f1381:
				$this->p = $o2;
				goto n1380;
				k1381:
				goto a1368;
				n1380:
				// s172
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1387;}
				}
				goto k1387;
				f1387:
				$this->p = $o2;
				goto n1386;
				k1387:
				goto a1368;
				n1386:
				break;
			case 22:
			case 23:
				// s153
				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1389;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1391;
					}

					$this->p++;
				}
				e1391:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1389;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1393;
					}

					$this->p++;
				}
				e1393:
				if (!$this->s145()) {
					goto f1389;}
				goto k1389;
				f1389:
				$this->p = $o2;
				goto n1388;
				k1389:
				goto a1368;
				n1388:
				if (!$this->s155()) {
					goto n1394;}
				goto a1368;
				n1394:
				break;
			case 38:
			case 83:
				// s148
				$o2 = $this->p;
				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1398;
					}

					$this->p++;
				}
				e1398:
				if (isset(self::T1490fe7f[$this->ids[$this->p]])) {
					if (!$this->s151()) {
						goto n1399;}
					n1399:
				} elseif ($this->p > $this->f) {
					$this->rec(151);
				}
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p >= $this->f) {
						$this->rec(62);
					}

					goto f1396;}

				$this->p++;
				goto k1396;
				f1396:
				$this->p = $o2;
				goto n1395;
				k1396:
				goto a1368;
				n1395:
				break;
			case 19:
				$this->p++;
				goto a1368;
				break;
			case 20:
				// s153
				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1401;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1403;
					}

					$this->p++;
				}
				e1403:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1401;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1405;
					}

					$this->p++;
				}
				e1405:
				if (!$this->s145()) {
					goto f1401;}
				goto k1401;
				f1401:
				$this->p = $o2;
				goto n1400;
				k1401:
				goto a1368;
				n1400:
				$this->p++;
				goto a1368;
				break;
			case 21:
				// s153
				$o2 = $this->p;
				if (!$this->s154()) {
					goto f1407;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1409;
					}

					$this->p++;
				}
				e1409:
				if (!isset(self::Ta7068161[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(171);
					}

					goto f1407;}

				$this->p++;
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1411;
					}

					$this->p++;
				}
				e1411:
				if (!$this->s145()) {
					goto f1407;}
				goto k1407;
				f1407:
				$this->p = $o2;
				goto n1406;
				k1407:
				goto a1368;
				n1406:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(152);
		}

		goto f1367;
		a1368:
		goto k1367;
		f1367:
		$this->p = $o1;
		return false;
		k1367:

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
				$o2 = $this->p;
				if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
					if (isset(self::T00b73ddb[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1620;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f1620;}

				$this->p++;
				if (!($this->ids[$this->p] === 43)) {
					if ($this->p >= $this->f) {
						$this->rec(66);
					}

					goto f1620;}

				$this->p++;
				if (!$this->s163()) {
					goto f1620;}
				goto k1620;
				f1620:
				$this->p = $o2;
				goto n1619;
				k1620:
				goto a1618;
				n1619:
				// s169
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1622;}
				}
				goto k1622;
				f1622:
				$this->p = $o2;
				goto n1621;
				k1622:
				goto a1618;
				n1621:
				break;
			case 7:
			case 15:
			case 16:
			case 17:
			case 18:
				// s169
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1624;}
				}
				goto k1624;
				f1624:
				$this->p = $o2;
				goto n1623;
				k1624:
				goto a1618;
				n1623:
				break;
			case 22:
			case 23:
				if (!$this->s155()) {
					goto n1625;}
				goto a1618;
				n1625:
				break;
			case 20:
				$this->p++;
				goto a1618;
				break;
			case 21:
				$this->p++;
				goto a1618;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(154);
		}

		goto f1617;
		a1618:
		goto k1617;
		f1617:
		$this->p = $o1;
		return false;
		k1617:

		return true;
	}

	private function s155(): bool
	{
		if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(155);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 22:
				// s156
				$o2 = $this->p;
				$this->p++;
				if (isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
					// s158
					if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->rec(158);
						}

						goto n1106;}

					$o3 = $this->p;
					$k3 = 0;
					while (true) {
						$b3 = $this->p;
						if (!isset(self::T7dfbf2bf[$this->ids[$this->p]])) {
							if ($b3 > $this->f) {
								$this->rec(159);
							}

							goto e1108;
						}

						$this->p++;

						$k3++;
					}
					e1108:

					if ($k3 < 1) {
						goto f1107;
					}
					goto k1107;
					f1107:
					$this->p = $o3;
					goto n1106;
					k1107:
					n1106:
				} elseif ($this->p > $this->f) {
					$this->rec(158);
				}
				goto a1103;
				n1104:
				break;
			case 23:
				$this->p++;
				goto a1103;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(155);
		}

		goto f1102;
		a1103:
		goto k1102;
		f1102:
		$this->p = $o1;
		return false;
		k1102:

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
				$o2 = $this->p;
				$this->p++;
				if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
					// s168
					if (!isset(self::T4d756b3a[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->rec(168);
						}

						goto n482;}

					$o3 = $this->p;
					switch ($this->ids[$this->p]) {
						default:
							break;
						case 50:
							// s164
							$o4 = $this->p;
							$this->p++;
							if (isset(self::T83e30518[$this->ids[$this->p]])) {
								if (!$this->s166()) {
									goto n487;}
								n487:
							} elseif ($this->p > $this->f) {
								$this->rec(166);
							}
							goto a484;
							n485:
							break;
						case 85:
							$this->p++;
							goto a484;
							break;
					}

					if ($this->p > $this->f) {
						$this->rec(168);
					}

					goto f483;
					a484:
					goto k483;
					f483:
					$this->p = $o3;
					goto n482;
					k483:
					n482:
				} elseif ($this->p > $this->f) {
					$this->rec(168);
				}
				goto a479;
				n480:
				break;
			case 50:
				if (!$this->s164()) {
					goto n488;}
				goto a479;
				n488:
				break;
			case 85:
				$this->p++;
				goto a479;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(163);
		}

		goto f478;
		a479:
		goto k478;
		f478:
		$this->p = $o1;
		return false;
		k478:

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
		$this->p++;
		if (isset(self::T83e30518[$this->ids[$this->p]])) {
			// s166
			if (!isset(self::T83e30518[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(166);
				}

				goto n770;}

			$o2 = $this->p;
			$this->p++;
			if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
				// s168
				if (!isset(self::T4d756b3a[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(168);
					}

					goto n772;}

				$o3 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 50:
						if (!$this->s164()) {
							goto n775;}
						goto a774;
						n775:
						break;
					case 85:
						$this->p++;
						goto a774;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(168);
				}

				goto f773;
				a774:
				goto k773;
				f773:
				$this->p = $o3;
				goto n772;
				k773:
				n772:
			} elseif ($this->p > $this->f) {
				$this->rec(168);
			}
			n770:
		} elseif ($this->p > $this->f) {
			$this->rec(166);
		}

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
		$this->p++;
		if (isset(self::T4d756b3a[$this->ids[$this->p]])) {
			// s168
			if (!isset(self::T4d756b3a[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(168);
				}

				goto n763;}

			$o2 = $this->p;
			switch ($this->ids[$this->p]) {
				default:
					break;
				case 50:
					// s164
					$o3 = $this->p;
					$this->p++;
					if (isset(self::T83e30518[$this->ids[$this->p]])) {
						if (!$this->s166()) {
							goto n768;}
						n768:
					} elseif ($this->p > $this->f) {
						$this->rec(166);
					}
					goto a765;
					n766:
					break;
				case 85:
					$this->p++;
					goto a765;
					break;
			}

			if ($this->p > $this->f) {
				$this->rec(168);
			}

			goto f764;
			a765:
			goto k764;
			f764:
			$this->p = $o2;
			goto n763;
			k764:
			n763:
		} elseif ($this->p > $this->f) {
			$this->rec(168);
		}

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
		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f664;}
		}
		goto k664;
		f664:
		$this->p = $o1;
		return false;
		k664:

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

				goto e1413;
			}

			// s177
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(177);
				}

				goto e1413;}

			$o2 = $this->p;
			$this->p++;
			// s139
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e1416;
				}

				$this->p++;
			}
			e1416:
			if (!$this->s178()) {
				goto f1414;}
			goto k1414;
			f1414:
			$this->p = $o2;
			goto e1413;
			k1414:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1413;
			}

			$k1++;
		}
		e1413:

		if ($k1 < 1) {
			goto f1412;
		}
		goto k1412;
		f1412:
		$this->p = $o1;
		return false;
		k1412:

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

					goto n1628;}

				$o2 = $this->p;
				if (!$this->s152()) {
					goto f1629;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1631;
					}

					$this->p++;
				}
				e1631:
				goto k1629;
				f1629:
				$this->p = $o2;
				goto n1628;
				k1629:
				goto a1627;
				n1628:
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

					goto n1632;}
				goto a1627;
				n1632:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(178);
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

				goto e844;
			}

			// s183
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(183);
				}

				goto e844;}

			$o2 = $this->p;
			$this->p++;
			// s139
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!($this->ids[$this->p] === 80)) {
					if ($b3 >= $this->f) {
						$this->rec(3);
					}

					goto e847;
				}

				$this->p++;
			}
			e847:
			if (!$this->s184()) {
				goto f845;}
			goto k845;
			f845:
			$this->p = $o2;
			goto e844;
			k845:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e844;
			}

			$k1++;
		}
		e844:

		if ($k1 < 1) {
			goto f843;
		}
		goto k843;
		f843:
		$this->p = $o1;
		return false;
		k843:

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

					goto n1115;}

				$o2 = $this->p;
				if (!$this->s142()) {
					goto f1116;}
				// s139
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!($this->ids[$this->p] === 80)) {
						if ($b3 >= $this->f) {
							$this->rec(3);
						}

						goto e1118;
					}

					$this->p++;
				}
				e1118:
				goto k1116;
				f1116:
				$this->p = $o2;
				goto n1115;
				k1116:
				goto a1114;
				n1115:
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

					goto n1119;}
				goto a1114;
				n1119:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(184);
		}

		goto f1113;
		a1114:
		goto k1113;
		f1113:
		$this->p = $o1;
		return false;
		k1113:

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
		$this->p++;
		// s138
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(138);
			}

			goto f267;}

		$o2 = $this->p;
		$this->p++;
		// s139
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!($this->ids[$this->p] === 80)) {
				if ($b3 >= $this->f) {
					$this->rec(3);
				}

				goto e270;
			}

			$this->p++;
		}
		e270:
		if (isset(self::T1e2ed78c[$this->ids[$this->p]])) {
			if (!$this->s141()) {
				goto n271;}
			n271:
		} elseif ($this->p > $this->f) {
			$this->rec(141);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f268;}

		$this->p++;
		goto k268;
		f268:
		$this->p = $o2;
		goto f267;
		k268:
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

				goto e274;
			}

			$this->p++;
		}
		e274:
		if (!$this->s74()) {
			goto f272;}
		goto k272;
		f272:
		$this->p = $o2;
		goto f267;
		k272:
		goto k267;
		f267:
		$this->p = $o1;
		return false;
		k267:

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
						goto f46;}
				}
				// s457
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f47;}
				goto k47;
				f47:
				$this->p = $o3;
				goto f46;
				k47:
				goto k46;
				f46:
				$this->p = $o2;
				goto n45;
				k46:
				goto a44;
				n45:
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
					goto n48;}
				goto a44;
				n48:
				if (!$this->s454()) {
					goto n49;}
				goto a44;
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
				goto a44;
				n50:
				break;
			case 76:
			case 77:
			case 78:
			case 79:
				if (!$this->s454()) {
					goto n53;}
				goto a44;
				n53:
				// s455
				$o2 = $this->p;
				if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
					if (isset(self::Tf4b693bf[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f55;}
				}
				// s457
				$o3 = $this->p;
				if (!$this->s125()) {
					goto f56;}
				goto k56;
				f56:
				$this->p = $o3;
				goto f55;
				k56:
				goto k55;
				f55:
				$this->p = $o2;
				goto n54;
				k55:
				goto a44;
				n54:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(189);
		}

		goto f43;
		a44:
		goto k43;
		f43:
		$this->p = $o1;
		return false;
		k43:

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
				$o2 = $this->p;
				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f127;}

				$o3 = $this->p;
				$this->p++;
				if (!$this->s125()) {
					goto f128;}
				goto k128;
				f128:
				$this->p = $o3;
				goto f127;
				k128:
				goto k127;
				f127:
				$this->p = $o2;
				goto n126;
				k127:
				goto a125;
				n126:
				break;
			case 52:
				// s405
				$o2 = $this->p;
				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f130;}

				$o3 = $this->p;
				$this->p++;
				if (!$this->s125()) {
					goto f131;}
				goto k131;
				f131:
				$this->p = $o3;
				goto f130;
				k131:
				goto k130;
				f130:
				$this->p = $o2;
				goto n129;
				k130:
				goto a125;
				n129:
				break;
			case 53:
				// s406
				$o2 = $this->p;
				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f133;}

				$o3 = $this->p;
				$this->p++;
				if (!$this->s125()) {
					goto f134;}
				goto k134;
				f134:
				$this->p = $o3;
				goto f133;
				k134:
				goto k133;
				f133:
				$this->p = $o2;
				goto n132;
				k133:
				goto a125;
				n132:
				break;
			case 54:
				// s404
				$o2 = $this->p;
				$this->p++;
				// s403
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p > $this->f) {
						$this->rec(403);
					}

					goto f136;}

				$o3 = $this->p;
				$this->p++;
				if (!$this->s125()) {
					goto f137;}
				goto k137;
				f137:
				$this->p = $o3;
				goto f136;
				k137:
				goto k136;
				f136:
				$this->p = $o2;
				goto n135;
				k136:
				goto a125;
				n135:
				break;
			case 55:
				// s401
				$o2 = $this->p;
				$this->p++;
				// s399
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(399);
					}

					goto f139;}

				$o3 = $this->p;
				if (!$this->s199()) {
					goto f140;}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f140;}

				$this->p++;
				if (!$this->s125()) {
					goto f140;}
				goto k140;
				f140:
				$this->p = $o3;
				goto f139;
				k140:
				goto k139;
				f139:
				$this->p = $o2;
				goto n138;
				k139:
				goto a125;
				n138:
				break;
			case 56:
				// s395
				$o2 = $this->p;
				$this->p++;
				if (!$this->s391()) {
					goto f142;}
				goto k142;
				f142:
				$this->p = $o2;
				goto n141;
				k142:
				goto a125;
				n141:
				break;
			case 57:
				// s394
				$o2 = $this->p;
				$this->p++;
				if (!$this->s391()) {
					goto f144;}
				goto k144;
				f144:
				$this->p = $o2;
				goto n143;
				k144:
				goto a125;
				n143:
				break;
			case 58:
				if (!$this->s443()) {
					goto n145;}
				goto a125;
				n145:
				break;
			case 59:
				// s446
				$o2 = $this->p;
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
					goto f147;}
				if (!isset(self::T29c7f960[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(448);
					}

					goto f147;}

				$this->p++;
				if (!$this->s449()) {
					goto f147;}
				if (!$this->s125()) {
					goto f147;}
				goto k147;
				f147:
				$this->p = $o2;
				goto n146;
				k147:
				goto a125;
				n146:
				break;
			case 60:
				// s397
				$o2 = $this->p;
				$this->p++;
				if (!$this->s391()) {
					goto f149;}
				goto k149;
				f149:
				$this->p = $o2;
				goto n148;
				k149:
				goto a125;
				n148:
				break;
			case 61:
				// s396
				$o2 = $this->p;
				$this->p++;
				if (!$this->s391()) {
					goto f151;}
				goto k151;
				f151:
				$this->p = $o2;
				goto n150;
				k151:
				goto a125;
				n150:
				break;
			case 62:
				if (!$this->s436()) {
					goto n152;}
				goto a125;
				n152:
				break;
			case 63:
				// s398
				$o2 = $this->p;
				$this->p++;
				// s399
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(399);
					}

					goto f154;}

				$o3 = $this->p;
				if (!$this->s199()) {
					goto f155;}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f155;}

				$this->p++;
				if (!$this->s125()) {
					goto f155;}
				goto k155;
				f155:
				$this->p = $o3;
				goto f154;
				k155:
				goto k154;
				f154:
				$this->p = $o2;
				goto n153;
				k154:
				goto a125;
				n153:
				break;
			case 64:
				// s439
				$o2 = $this->p;
				$this->p++;
				if (!$this->s438()) {
					goto f157;}
				goto k157;
				f157:
				$this->p = $o2;
				goto n156;
				k157:
				goto a125;
				n156:
				break;
			case 65:
				// s407
				$o2 = $this->p;
				$this->p++;
				if (!$this->s125()) {
					goto f159;}
				goto k159;
				f159:
				$this->p = $o2;
				goto n158;
				k159:
				goto a125;
				n158:
				break;
			case 66:
				// s441
				$o2 = $this->p;
				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f161;}

				$this->p++;
				if (($this->ids[$this->p] === 46)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(69);
				}
				if (!$this->s199()) {
					goto f161;}
				if (!isset(self::T53b29bff[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(77);
					}

					goto f161;}
				goto k161;
				f161:
				$this->p = $o2;
				goto n160;
				k161:
				goto a125;
				n160:
				// s442
				$o2 = $this->p;
				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f163;}

				$this->p++;
				if (($this->ids[$this->p] === 46)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(69);
				}
				if (!$this->s125()) {
					goto f163;}
				goto k163;
				f163:
				$this->p = $o2;
				goto n162;
				k163:
				goto a125;
				n162:
				break;
			case 67:
				// s400
				$o2 = $this->p;
				$this->p++;
				// s399
				if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(399);
					}

					goto f165;}

				$o3 = $this->p;
				if (!$this->s199()) {
					goto f166;}
				if (!($this->ids[$this->p] === 26)) {
					if ($this->p >= $this->f) {
						$this->rec(47);
					}

					goto f166;}

				$this->p++;
				if (!$this->s125()) {
					goto f166;}
				goto k166;
				f166:
				$this->p = $o3;
				goto f165;
				k166:
				goto k165;
				f165:
				$this->p = $o2;
				goto n164;
				k165:
				goto a125;
				n164:
				break;
			case 68:
				// s437
				$o2 = $this->p;
				$this->p++;
				if (!$this->s438()) {
					goto f168;}
				goto k168;
				f168:
				$this->p = $o2;
				goto n167;
				k168:
				goto a125;
				n167:
				break;
			case 69:
				// s408
				$o2 = $this->p;
				$this->p++;
				if (!$this->s409()) {
					goto f170;}
				goto k170;
				f170:
				$this->p = $o2;
				goto n169;
				k170:
				goto a125;
				n169:
				break;
			case 70:
				// s390
				$o2 = $this->p;
				$this->p++;
				if (!$this->s391()) {
					goto f172;}
				goto k172;
				f172:
				$this->p = $o2;
				goto n171;
				k172:
				goto a125;
				n171:
				break;
			case 71:
				// s392
				$o2 = $this->p;
				$this->p++;
				if (!$this->s391()) {
					goto f174;}
				goto k174;
				f174:
				$this->p = $o2;
				goto n173;
				k174:
				goto a125;
				n173:
				break;
			case 72:
				// s191
				$o2 = $this->p;
				$this->p++;
				if (!$this->s192()) {
					goto f176;}
				goto k176;
				f176:
				$this->p = $o2;
				goto n175;
				k176:
				goto a125;
				n175:
				break;
			case 73:
				// s393
				$o2 = $this->p;
				$this->p++;
				if (!$this->s391()) {
					goto f178;}
				goto k178;
				f178:
				$this->p = $o2;
				goto n177;
				k178:
				goto a125;
				n177:
				break;
			case 74:
				// s440
				$o2 = $this->p;
				$this->p++;
				if (!$this->s438()) {
					goto f180;}
				goto k180;
				f180:
				$this->p = $o2;
				goto n179;
				k180:
				goto a125;
				n179:
				break;
			case 75:
				// s384
				$o2 = $this->p;
				$this->p++;
				if (!$this->s199()) {
					goto f182;}
				if (!$this->s385()) {
					goto f182;}
				goto k182;
				f182:
				$this->p = $o2;
				goto n181;
				k182:
				goto a125;
				n181:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(190);
		}

		goto f124;
		a125:
		goto k124;
		f124:
		$this->p = $o1;
		return false;
		k124:

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
				$o2 = $this->p;
				if (!$this->s199()) {
					goto f375;}
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

					goto f375;}

				$this->p++;
				if (!$this->s125()) {
					goto f375;}
				goto k375;
				f375:
				$this->p = $o2;
				goto n374;
				k375:
				goto a373;
				n374:
				break;
			case 26:
			case 27:
			case 42:
				// s193
				$o2 = $this->p;
				if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(195);
					}

					goto f377;}
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

					goto f377;}

				$this->p++;
				if (!$this->s125()) {
					goto f377;}
				goto k377;
				f377:
				$this->p = $o2;
				goto n376;
				k377:
				goto a373;
				n376:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(192);
		}

		goto f372;
		a373:
		goto k372;
		f372:
		$this->p = $o1;
		return false;
		k372:

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
				$o2 = $this->p;
				if (!$this->s201()) {
					goto f192;}
				if (!$this->s368()) {
					goto f192;}
				goto k192;
				f192:
				$this->p = $o2;
				goto n191;
				k192:
				goto a190;
				n191:
				break;
			case 30:
				// s200
				$o2 = $this->p;
				$this->p++;
				if (!$this->s201()) {
					goto f194;}
				goto k194;
				f194:
				$this->p = $o2;
				goto n193;
				k194:
				goto a190;
				n193:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(199);
		}

		goto f189;
		a190:
		goto k189;
		f189:
		$this->p = $o1;
		return false;
		k189:

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
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f426;}
				}
				if (!$this->s281()) {
					goto f426;}
				goto k426;
				f426:
				$this->p = $o2;
				goto n425;
				k426:
				goto a424;
				n425:
				// s333
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
						goto f428;}
				}
				if (!$this->s335()) {
					goto f428;}
				goto k428;
				f428:
				$this->p = $o2;
				goto n427;
				k428:
				goto a424;
				n427:
				break;
			case 19:
			case 20:
			case 21:
			case 22:
				// s333
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
						goto f431;}
				}
				if (!$this->s335()) {
					goto f431;}
				goto k431;
				f431:
				$this->p = $o2;
				goto n430;
				k431:
				goto a424;
				n430:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->s255()) {
					goto n433;}
				goto a424;
				n433:
				// s278
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f435;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f435;}
				}
				if (!$this->s281()) {
					goto f435;}
				goto k435;
				f435:
				$this->p = $o2;
				goto n434;
				k435:
				goto a424;
				n434:
				// s333
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
						goto f437;}
				}
				if (!$this->s335()) {
					goto f437;}
				goto k437;
				f437:
				$this->p = $o2;
				goto n436;
				k437:
				goto a424;
				n436:
				break;
			case 32:
			case 82:
				if (!$this->s202()) {
					goto n439;}
				goto a424;
				n439:
				break;
			case 6:
				if (!$this->s267()) {
					goto n440;}
				goto a424;
				n440:
				// s278
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f442;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f442;}
				}
				if (!$this->s281()) {
					goto f442;}
				goto k442;
				f442:
				$this->p = $o2;
				goto n441;
				k442:
				goto a424;
				n441:
				// s333
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
						goto f444;}
				}
				if (!$this->s335()) {
					goto f444;}
				goto k444;
				f444:
				$this->p = $o2;
				goto n443;
				k444:
				goto a424;
				n443:
				break;
			case 7:
				if (!$this->s230()) {
					goto n446;}
				goto a424;
				n446:
				// s278
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f448;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f448;}
				}
				if (!$this->s281()) {
					goto f448;}
				goto k448;
				f448:
				$this->p = $o2;
				goto n447;
				k448:
				goto a424;
				n447:
				// s333
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
						goto f450;}
				}
				if (!$this->s335()) {
					goto f450;}
				goto k450;
				f450:
				$this->p = $o2;
				goto n449;
				k450:
				goto a424;
				n449:
				break;
			case 25:
				// s229
				$o2 = $this->p;
				$this->p++;
				if (isset(self::T885cd349[$this->ids[$this->p]])) {
					// s223
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->rec(223);
						}

						goto n454;}

					$o3 = $this->p;
					$k3 = 0;
					while (true) {
						$b3 = $this->p;
						if (!isset(self::T885cd349[$this->ids[$this->p]])) {
							if ($b3 > $this->f) {
								$this->rec(224);
							}

							goto e456;
						}

						if (!$this->s224()) {
							goto e456;}
						if ($this->p === $b3) {
							$k3 = 1;
							goto e456;
						}

						$k3++;
					}
					e456:

					if ($k3 < 1) {
						goto f455;
					}
					goto k455;
					f455:
					$this->p = $o3;
					goto n454;
					k455:
					n454:
				} elseif ($this->p > $this->f) {
					$this->rec(223);
				}
				goto a424;
				n452:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(201);
		}

		goto f423;
		a424:
		goto k423;
		f423:
		$this->p = $o1;
		return false;
		k423:

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
		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e723;
			}

			$this->p++;
		}
		e723:
		if (!$this->s205()) {
			goto f721;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e725;
			}

			$this->p++;
		}
		e725:
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f721;}

		$this->p++;
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n726;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e728;
				}

				if (!$this->s224()) {
					goto e728;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e728;
				}

				$k2++;
			}
			e728:

			if ($k2 < 1) {
				goto f727;
			}
			goto k727;
			f727:
			$this->p = $o2;
			goto n726;
			k727:
			n726:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k721;
		f721:
		$this->p = $o1;
		return false;
		k721:

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
				$o2 = $this->p;
				if (!$this->s201()) {
					goto f1014;}
				if (!$this->s209()) {
					goto f1014;}
				goto k1014;
				f1014:
				$this->p = $o2;
				goto n1013;
				k1014:
				goto a1012;
				n1013:
				break;
			case 26:
				if (!$this->s206()) {
					goto n1015;}
				goto a1012;
				n1015:
				break;
			case 30:
				// s200
				$o2 = $this->p;
				$this->p++;
				if (!$this->s201()) {
					goto f1017;}
				goto k1017;
				f1017:
				$this->p = $o2;
				goto n1016;
				k1017:
				goto a1012;
				n1016:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(205);
		}

		goto f1011;
		a1012:
		goto k1011;
		f1011:
		$this->p = $o1;
		return false;
		k1011:

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
		$this->p++;
		if (!($this->ids[$this->p] === 12)) {
			if ($this->p >= $this->f) {
				$this->rec(23);
			}

			goto f1303;}

		$this->p++;
		if (($this->ids[$this->p] === 11)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->s199()) {
			goto f1303;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1305;
			}

			$this->p++;
		}
		e1305:
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
			}

			goto f1303;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1307;
			}

			$this->p++;
		}
		e1307:
		if (!$this->s199()) {
			goto f1303;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1309;
			}

			$this->p++;
		}
		e1309:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1303;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1311;
			}

			$this->p++;
		}
		e1311:
		if (!$this->s205()) {
			goto f1303;}
		goto k1303;
		f1303:
		$this->p = $o1;
		return false;
		k1303:

		return true;
	}

	private function s209(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (!$this->s211()) {
					goto n1300;}
				goto a1299;
				n1300:
				break;
			case 12:
				if (!$this->s210()) {
					goto n1301;}
				goto a1299;
				n1301:
				if (!$this->s211()) {
					goto n1302;}
				goto a1299;
				n1302:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(209);
		}

		goto f1298;
		a1299:
		goto k1298;
		f1298:
		$this->p = $o1;
		return false;
		k1298:

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
		$this->p++;
		if (($this->ids[$this->p] === 11)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(24);
		}
		if (!$this->s199()) {
			goto f1576;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1578;
			}

			$this->p++;
		}
		e1578:
		if (!($this->ids[$this->p] === 30)) {
			if ($this->p >= $this->f) {
				$this->rec(51);
			}

			goto f1576;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1580;
			}

			$this->p++;
		}
		e1580:
		if (!$this->s199()) {
			goto f1576;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1582;
			}

			$this->p++;
		}
		e1582:
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1576;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1584;
			}

			$this->p++;
		}
		e1584:
		if (!$this->s205()) {
			goto f1576;}
		goto k1576;
		f1576:
		$this->p = $o1;
		return false;
		k1576:

		return true;
	}

	private function s211(): bool
	{
		$o1 = $this->p;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1574;
			}

			$this->p++;
		}
		e1574:
		if (isset(self::Tc8c984b6[$this->ids[$this->p]])) {
			if (!$this->s213()) {
				goto n1575;}
			n1575:
		} elseif ($this->p > $this->f) {
			$this->rec(213);
		}

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
				$o2 = $this->p;
				if (!$this->s215()) {
					goto f1703;}
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1703;}
				}
				goto k1703;
				f1703:
				$this->p = $o2;
				goto n1702;
				k1703:
				goto a1701;
				n1702:
				break;
			case 29:
				// s218
				$o2 = $this->p;
				if (!$this->s219()) {
					goto f1705;}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f1705;}
				}
				goto k1705;
				f1705:
				$this->p = $o2;
				goto n1704;
				k1705:
				goto a1701;
				n1704:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(213);
		}

		goto f1700;
		a1701:
		goto k1700;
		f1700:
		$this->p = $o1;
		return false;
		k1700:

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

				goto e1748;
			}

			// s216
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->rec(216);
				}

				goto e1748;}

			$o2 = $this->p;
			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1751;
				}

				$this->p++;
			}
			e1751:
			if (!$this->s201()) {
				goto f1749;}
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1753;
				}

				$this->p++;
			}
			e1753:
			goto k1749;
			f1749:
			$this->p = $o2;
			goto e1748;
			k1749:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1748;
			}

			$k1++;
		}
		e1748:

		if ($k1 < 1) {
			goto f1747;
		}
		goto k1747;
		f1747:
		$this->p = $o1;
		return false;
		k1747:

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

				goto e1755;
			}

			// s220
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->rec(220);
				}

				goto e1755;}

			$o2 = $this->p;
			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1758;
				}

				$this->p++;
			}
			e1758:
			if (!$this->s201()) {
				goto f1756;}
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1760;
				}

				$this->p++;
			}
			e1760:
			goto k1756;
			f1756:
			$this->p = $o2;
			goto e1755;
			k1756:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1755;
			}

			$k1++;
		}
		e1755:

		if ($k1 < 1) {
			goto f1754;
		}
		goto k1754;
		f1754:
		$this->p = $o1;
		return false;
		k1754:

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
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 37)) {
					if (($this->ids[$this->p] === 37)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f744;}
				}
				if (!$this->s199()) {
					goto f744;}
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f744;}

				$this->p++;
				goto k744;
				f744:
				$this->p = $o2;
				goto n743;
				k744:
				goto a742;
				n743:
				// s227
				$o2 = $this->p;
				$this->p++;
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f746;}

				$this->p++;
				goto k746;
				f746:
				$this->p = $o2;
				goto n745;
				k746:
				goto a742;
				n745:
				break;
			case 84:
				// s227
				$o2 = $this->p;
				$this->p++;
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f748;}

				$this->p++;
				goto k748;
				f748:
				$this->p = $o2;
				goto n747;
				k748:
				goto a742;
				n747:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(224);
		}

		goto f741;
		a742:
		goto k741;
		f741:
		$this->p = $o1;
		return false;
		k741:

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
		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f737;}
		}
		if (!$this->s231()) {
			goto f737;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n738;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e740;
				}

				if (!$this->s224()) {
					goto e740;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e740;
				}

				$k2++;
			}
			e740:

			if ($k2 < 1) {
				goto f739;
			}
			goto k739;
			f739:
			$this->p = $o2;
			goto n738;
			k739:
			n738:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k737;
		f737:
		$this->p = $o1;
		return false;
		k737:

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
		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1025;
			}

			$this->p++;
		}
		e1025:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->s233()) {
				goto n1026;}
			n1026:
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

				goto e1028;
			}

			$this->p++;
		}
		e1028:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f1023;}

		$this->p++;
		goto k1023;
		f1023:
		$this->p = $o1;
		return false;
		k1023:

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
					goto n1321;}
				goto a1320;
				n1321:
				break;
			case 42:
				// s234
				$o2 = $this->p;
				$this->p++;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1325;
					}

					$this->p++;
				}
				e1325:
				if (!$this->s235()) {
					goto f1323;}
				if (($this->ids[$this->p] === 40)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(63);
				}
				goto k1323;
				f1323:
				$this->p = $o2;
				goto n1322;
				k1323:
				goto a1320;
				n1322:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(233);
		}

		goto f1319;
		a1320:
		goto k1319;
		f1319:
		$this->p = $o1;
		return false;
		k1319:

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
					goto n1599;}
				goto a1598;
				n1599:
				break;
			case 34:
			case 86:
				// s236
				$o2 = $this->p;
				if (!$this->s237()) {
					goto f1601;}
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1603;
					}

					$this->p++;
				}
				e1603:
				goto k1601;
				f1601:
				$this->p = $o2;
				goto n1600;
				k1601:
				goto a1598;
				n1600:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1604;}
				goto a1598;
				n1604:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(235);
		}

		goto f1597;
		a1598:
		goto k1597;
		f1597:
		$this->p = $o1;
		return false;
		k1597:

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
		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1712;
			}

			$this->p++;
		}
		e1712:
		if (!$this->s199()) {
			goto f1710;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1714;
			}

			$this->p++;
		}
		e1714:
		if (($this->ids[$this->p] === 40)) {
			// s240
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(240);
				}

				goto n1715;}

			$o2 = $this->p;
			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1718;
				}

				$this->p++;
			}
			e1718:
			if (!$this->s199()) {
				goto f1716;}
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1720;
				}

				$this->p++;
			}
			e1720:
			goto k1716;
			f1716:
			$this->p = $o2;
			goto n1715;
			k1716:
			n1715:
		} elseif ($this->p > $this->f) {
			$this->rec(240);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1710;}

		$this->p++;
		goto k1710;
		f1710:
		$this->p = $o1;
		return false;
		k1710:

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
		if (!$this->s244()) {
			goto f1593;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1595;
			}

			$this->p++;
		}
		e1595:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s252()) {
				goto n1596;}
			n1596:
		} elseif ($this->p > $this->f) {
			$this->rec(252);
		}
		goto k1593;
		f1593:
		$this->p = $o1;
		return false;
		k1593:

		return true;
	}

	private function s244(): bool
	{
		if (!isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(244);
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
			case 20:
			case 21:
			case 22:
			case 24:
				// s245
				$o2 = $this->p;
				if (!$this->s246()) {
					goto f1685;}
				if (($this->ids[$this->p] === 30)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(51);
				}
				if (!($this->ids[$this->p] === 47)) {
					if ($this->p >= $this->f) {
						$this->rec(70);
					}

					goto f1685;}

				$this->p++;
				if (!$this->s199()) {
					goto f1685;}
				goto k1685;
				f1685:
				$this->p = $o2;
				goto n1684;
				k1685:
				goto a1683;
				n1684:
				if (!$this->s199()) {
					goto n1686;}
				goto a1683;
				n1686:
				break;
			case 19:
			case 25:
			case 30:
			case 32:
			case 82:
				if (!$this->s199()) {
					goto n1687;}
				goto a1683;
				n1687:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(244);
		}

		goto f1682;
		a1683:
		goto k1682;
		f1682:
		$this->p = $o1;
		return false;
		k1682:

		return true;
	}

	private function s246(): bool
	{
		if (!isset(self::Tce797685[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(246);
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
				// s247
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					// s249
					if (!($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->rec(249);
						}

						goto n1745;}

					$o3 = $this->p;
					$this->p++;
					if (!isset(self::T83e30518[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->rec(144);
						}

						goto f1746;}

					$this->p++;
					goto k1746;
					f1746:
					$this->p = $o3;
					goto n1745;
					k1746:
					n1745:
				} elseif ($this->p > $this->f) {
					$this->rec(249);
				}
				goto a1742;
				n1743:
				break;
			case 20:
				$this->p++;
				goto a1742;
				break;
			case 21:
				$this->p++;
				goto a1742;
				break;
			case 22:
				$this->p++;
				goto a1742;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(246);
		}

		goto f1741;
		a1742:
		goto k1741;
		f1741:
		$this->p = $o1;
		return false;
		k1741:

		return true;
	}

	private function s252(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(252);
			}

			return false;}

		$o1 = $this->p;
		$this->p++;
		if (($this->ids[$this->p] === 41)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(254);
		}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1708;
			}

			$this->p++;
		}
		e1708:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->s233()) {
				goto n1709;}
			n1709:
		} elseif ($this->p > $this->f) {
			$this->rec(233);
		}

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
		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f717;}
		}
		if (!$this->s257()) {
			goto f717;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n718;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e720;
				}

				if (!$this->s224()) {
					goto e720;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e720;
				}

				$k2++;
			}
			e720:

			if ($k2 < 1) {
				goto f719;
			}
			goto k719;
			f719:
			$this->p = $o2;
			goto n718;
			k719:
			n718:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k717;
		f717:
		$this->p = $o1;
		return false;
		k717:

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
		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1007;
			}

			$this->p++;
		}
		e1007:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->s259()) {
				goto n1008;}
			n1008:
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

				goto e1010;
			}

			$this->p++;
		}
		e1010:
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f1005;}

		$this->p++;
		goto k1005;
		f1005:
		$this->p = $o1;
		return false;
		k1005:

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
					goto n1293;}
				goto a1292;
				n1293:
				break;
			case 42:
				// s260
				$o2 = $this->p;
				$this->p++;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1297;
					}

					$this->p++;
				}
				e1297:
				if (!$this->s261()) {
					goto f1295;}
				if (($this->ids[$this->p] === 40)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(63);
				}
				goto k1295;
				f1295:
				$this->p = $o2;
				goto n1294;
				k1295:
				goto a1292;
				n1294:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(259);
		}

		goto f1291;
		a1292:
		goto k1291;
		f1291:
		$this->p = $o1;
		return false;
		k1291:

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
					goto n1569;}
				goto a1568;
				n1569:
				break;
			case 34:
			case 86:
				if (!$this->s262()) {
					goto n1570;}
				goto a1568;
				n1570:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1571;}
				goto a1568;
				n1571:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(261);
		}

		goto f1567;
		a1568:
		goto k1567;
		f1567:
		$this->p = $o1;
		return false;
		k1567:

		return true;
	}

	private function s262(): bool
	{
		if (!isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(262);
			}

			return false;}

		$o1 = $this->p;
		// s263
		$o2 = $this->p;
		$this->p++;
		// s203
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b3 > $this->f) {
					$this->rec(204);
				}

				goto e1695;
			}

			$this->p++;
		}
		e1695:
		if (!$this->s199()) {
			goto f1693;}
		// s203
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b3 > $this->f) {
					$this->rec(204);
				}

				goto e1697;
			}

			$this->p++;
		}
		e1697:
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1693;}

		$this->p++;
		goto k1693;
		f1693:
		$this->p = $o2;
		goto f1692;
		k1693:
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
		goto k1692;
		f1692:
		$this->p = $o1;
		return false;
		k1692:

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
		if (!$this->s244()) {
			goto f1563;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1565;
			}

			$this->p++;
		}
		e1565:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s266()) {
				goto n1566;}
			n1566:
		} elseif ($this->p > $this->f) {
			$this->rec(266);
		}
		goto k1563;
		f1563:
		$this->p = $o1;
		return false;
		k1563:

		return true;
	}

	private function s266(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(266);
			}

			return false;}

		$o1 = $this->p;
		$this->p++;
		if (($this->ids[$this->p] === 41)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(254);
		}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1690;
			}

			$this->p++;
		}
		e1690:
		if (isset(self::T50fd39f8[$this->ids[$this->p]])) {
			if (!$this->s259()) {
				goto n1691;}
			n1691:
		} elseif ($this->p > $this->f) {
			$this->rec(259);
		}

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
		$this->p++;
		if (($this->ids[$this->p] === 43)) {
			if (($this->ids[$this->p] === 43)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f729;}
		}
		// s268
		if (!($this->ids[$this->p] === 38)) {
			if ($this->p > $this->f) {
				$this->rec(268);
			}

			goto f729;}

		$o2 = $this->p;
		$this->p++;
		// s203
		$o3 = $this->p;
		while (true) {
			$b3 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b3 > $this->f) {
					$this->rec(204);
				}

				goto e732;
			}

			$this->p++;
		}
		e732:
		if (isset(self::Td8017923[$this->ids[$this->p]])) {
			if (!$this->s270()) {
				goto n733;}
			n733:
		} elseif ($this->p > $this->f) {
			$this->rec(270);
		}
		if (!($this->ids[$this->p] === 39)) {
			if ($this->p >= $this->f) {
				$this->rec(62);
			}

			goto f730;}

		$this->p++;
		goto k730;
		f730:
		$this->p = $o2;
		goto f729;
		k730:
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n734;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e736;
				}

				if (!$this->s224()) {
					goto e736;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e736;
				}

				$k2++;
			}
			e736:

			if ($k2 < 1) {
				goto f735;
			}
			goto k735;
			f735:
			$this->p = $o2;
			goto n734;
			k735:
			n734:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k729;
		f729:
		$this->p = $o1;
		return false;
		k729:

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
		$o2 = $this->p;
		if (!$this->s272()) {
			goto f1019;}
		if (($this->ids[$this->p] === 30)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(51);
		}
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1019;}

		$this->p++;
		if (!$this->s199()) {
			goto f1019;}
		goto k1019;
		f1019:
		$this->p = $o2;
		goto f1018;
		k1019:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1021;
			}

			$this->p++;
		}
		e1021:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s274()) {
				goto n1022;}
			n1022:
		} elseif ($this->p > $this->f) {
			$this->rec(274);
		}
		goto k1018;
		f1018:
		$this->p = $o1;
		return false;
		k1018:

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
				goto a1313;
				break;
			case 2:
				$this->p++;
				goto a1313;
				break;
			case 3:
				$this->p++;
				goto a1313;
				break;
			case 4:
				$this->p++;
				goto a1313;
				break;
			case 5:
				$this->p++;
				goto a1313;
				break;
			case 6:
				$this->p++;
				goto a1313;
				break;
			case 7:
				$this->p++;
				goto a1313;
				break;
			case 8:
				$this->p++;
				goto a1313;
				break;
			case 9:
				$this->p++;
				goto a1313;
				break;
			case 10:
				$this->p++;
				goto a1313;
				break;
			case 11:
				$this->p++;
				goto a1313;
				break;
			case 12:
				$this->p++;
				goto a1313;
				break;
			case 13:
				$this->p++;
				goto a1313;
				break;
			case 14:
				$this->p++;
				goto a1313;
				break;
			case 15:
				$this->p++;
				goto a1313;
				break;
			case 16:
				$this->p++;
				goto a1313;
				break;
			case 17:
				$this->p++;
				goto a1313;
				break;
			case 18:
				$this->p++;
				goto a1313;
				break;
			case 21:
				$this->p++;
				goto a1313;
				break;
			case 22:
				$this->p++;
				goto a1313;
				break;
			case 24:
				$this->p++;
				goto a1313;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(272);
		}

		goto f1312;
		a1313:
		goto k1312;
		f1312:
		$this->p = $o1;
		return false;
		k1312:

		return true;
	}

	private function s274(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(274);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(275);
				}

				goto e1315;
			}

			// s275
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(275);
				}

				goto e1315;}

			$o2 = $this->p;
			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1318;
				}

				$this->p++;
			}
			e1318:
			if (!$this->s276()) {
				goto f1316;}
			goto k1316;
			f1316:
			$this->p = $o2;
			goto e1315;
			k1316:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1315;
			}

			$k1++;
		}
		e1315:

		if ($k1 < 1) {
			goto f1314;
		}
		goto k1314;
		f1314:
		$this->p = $o1;
		return false;
		k1314:

		return true;
	}

	private function s276(): bool
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
			case 21:
			case 22:
			case 24:
				// s277
				$o2 = $this->p;
				// s271
				$o3 = $this->p;
				if (!$this->s272()) {
					goto f1589;}
				if (($this->ids[$this->p] === 30)) {
					$this->p++;
				} elseif ($this->p >= $this->f) {
					$this->rec(51);
				}
				if (!($this->ids[$this->p] === 47)) {
					if ($this->p >= $this->f) {
						$this->rec(70);
					}

					goto f1589;}

				$this->p++;
				if (!$this->s199()) {
					goto f1589;}
				goto k1589;
				f1589:
				$this->p = $o3;
				goto f1588;
				k1589:
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1591;
					}

					$this->p++;
				}
				e1591:
				goto k1588;
				f1588:
				$this->p = $o2;
				goto n1587;
				k1588:
				goto a1586;
				n1587:
				break;
			case 39:
				if (!($this->ids[$this->p] === 39)) {
					if ($this->p > $this->f) {
						$this->rec(62);
					}

					goto n1592;}
				goto a1586;
				n1592:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(276);
		}

		goto f1585;
		a1586:
		goto k1585;
		f1585:
		$this->p = $o1;
		return false;
		k1585:

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
					goto n699;}
				goto a698;
				n699:
				break;
			case 32:
			case 82:
				// s364
				$o2 = $this->p;
				$this->p++;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e703;
					}

					$this->p++;
				}
				e703:
				if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
					if (!$this->s306()) {
						goto n704;}
					n704:
				} elseif ($this->p > $this->f) {
					$this->rec(306);
				}
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p >= $this->f) {
						$this->rec(53);
					}

					goto f701;}

				$this->p++;
				if (!($this->ids[$this->p] === 47)) {
					if ($this->p >= $this->f) {
						$this->rec(70);
					}

					goto f701;}

				$this->p++;
				if (!$this->s315()) {
					goto f701;}
				goto k701;
				f701:
				$this->p = $o2;
				goto n700;
				k701:
				goto a698;
				n700:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n705;}
				goto a698;
				n705:
				break;
			case 36:
			case 84:
				// s223
				$o2 = $this->p;
				$k2 = 0;
				while (true) {
					$b2 = $this->p;
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($b2 > $this->f) {
							$this->rec(224);
						}

						goto e708;
					}

					if (!$this->s224()) {
						goto e708;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e708;
					}

					$k2++;
				}
				e708:

				if ($k2 < 1) {
					goto f707;
				}
				goto k707;
				f707:
				$this->p = $o2;
				goto n706;
				k707:
				goto a698;
				n706:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n709;}
				goto a698;
				n709:
				break;
			case 34:
				if (!$this->s282()) {
					goto n710;}
				goto a698;
				n710:
				if (($this->ids[$this->p] === 34)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n711;}
				goto a698;
				n711:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(281);
		}

		goto f697;
		a698:
		goto k697;
		f697:
		$this->p = $o1;
		return false;
		k697:

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

			goto f1004;}
		if (!$this->s284()) {
			goto f1004;}
		goto k1004;
		f1004:
		$this->p = $o1;
		return false;
		k1004:

		return true;
	}

	private function s284(): bool
	{
		if (!($this->ids[$this->p] === 34)) {
			if ($this->p > $this->f) {
				$this->rec(284);
			}

			return false;}

		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				break;
			case 34:
				if (!$this->s285()) {
					goto n1285;}
				goto a1284;
				n1285:
				// s362
				$o2 = $this->p;
				if (!$this->s363()) {
					goto f1287;}
				if (isset(self::T885cd349[$this->ids[$this->p]])) {
					// s223
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->rec(223);
						}

						goto n1288;}

					$o3 = $this->p;
					$k3 = 0;
					while (true) {
						$b3 = $this->p;
						if (!isset(self::T885cd349[$this->ids[$this->p]])) {
							if ($b3 > $this->f) {
								$this->rec(224);
							}

							goto e1290;
						}

						if (!$this->s224()) {
							goto e1290;}
						if ($this->p === $b3) {
							$k3 = 1;
							goto e1290;
						}

						$k3++;
					}
					e1290:

					if ($k3 < 1) {
						goto f1289;
					}
					goto k1289;
					f1289:
					$this->p = $o3;
					goto n1288;
					k1289:
					n1288:
				} elseif ($this->p > $this->f) {
					$this->rec(223);
				}
				goto k1287;
				f1287:
				$this->p = $o2;
				goto n1286;
				k1287:
				goto a1284;
				n1286:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(284);
		}

		goto f1283;
		a1284:
		goto k1283;
		f1283:
		$this->p = $o1;
		return false;
		k1283:

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
		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1548;
			}

			$this->p++;
		}
		e1548:
		if (!$this->s286()) {
			goto f1546;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1550;
			}

			$this->p++;
		}
		e1550:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s300()) {
				goto n1551;}
			n1551:
		} elseif ($this->p > $this->f) {
			$this->rec(300);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1546;}

		$this->p++;
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f1546;}

		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1553;
			}

			$this->p++;
		}
		e1553:
		if (isset(self::T7680fe2e[$this->ids[$this->p]])) {
			if (!$this->s306()) {
				goto n1554;}
			n1554:
		} elseif ($this->p > $this->f) {
			$this->rec(306);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1546;}

		$this->p++;
		if (!($this->ids[$this->p] === 47)) {
			if ($this->p >= $this->f) {
				$this->rec(70);
			}

			goto f1546;}

		$this->p++;
		if (!$this->s315()) {
			goto f1546;}
		goto k1546;
		f1546:
		$this->p = $o1;
		return false;
		k1546:

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
		$this->p++;
		if (!$this->s287()) {
			goto f1633;}
		// s293
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1636;}
				goto a1635;
				n1636:
				break;
			case 5:
				// s294
				$o3 = $this->p;
				$this->p++;
				if (!$this->s199()) {
					goto f1638;}
				goto k1638;
				f1638:
				$this->p = $o3;
				goto n1637;
				k1638:
				goto a1635;
				n1637:
				if (($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1639;}
				goto a1635;
				n1639:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(293);
		}

		goto f1634;
		a1635:
		goto k1634;
		f1634:
		$this->p = $o2;
		goto f1633;
		k1634:
		// s296
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1642;}
				goto a1641;
				n1642:
				break;
			case 46:
				// s297
				$o3 = $this->p;
				$this->p++;
				if (!$this->s199()) {
					goto f1644;}
				goto k1644;
				f1644:
				$this->p = $o3;
				goto n1643;
				k1644:
				goto a1641;
				n1643:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1645;}
				goto a1641;
				n1645:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(296);
		}

		goto f1640;
		a1641:
		goto k1640;
		f1640:
		$this->p = $o2;
		goto f1633;
		k1640:
		goto k1633;
		f1633:
		$this->p = $o1;
		return false;
		k1633:

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
						goto f626;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f626;}
				}
				goto k626;
				f626:
				$this->p = $o2;
				goto n625;
				k626:
				goto a624;
				n625:
				break;
			case 13:
				// s288
				$o2 = $this->p;
				$this->p++;
				if (!$this->s199()) {
					goto f628;}
				goto k628;
				f628:
				$this->p = $o2;
				goto n627;
				k628:
				goto a624;
				n627:
				// s290
				$o2 = $this->p;
				if (($this->ids[$this->p] === 13)) {
					if (($this->ids[$this->p] === 13)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f630;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f630;}
				}
				goto k630;
				f630:
				$this->p = $o2;
				goto n629;
				k630:
				goto a624;
				n629:
				break;
			case 14:
				// s289
				$o2 = $this->p;
				$this->p++;
				if (!$this->s199()) {
					goto f632;}
				goto k632;
				f632:
				$this->p = $o2;
				goto n631;
				k632:
				goto a624;
				n631:
				// s290
				$o2 = $this->p;
				if (($this->ids[$this->p] === 13)) {
					if (($this->ids[$this->p] === 13)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f634;}
				}
				if (($this->ids[$this->p] === 14)) {
					if (($this->ids[$this->p] === 14)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f634;}
				}
				goto k634;
				f634:
				$this->p = $o2;
				goto n633;
				k634:
				goto a624;
				n633:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(287);
		}

		goto f623;
		a624:
		goto k623;
		f623:
		$this->p = $o1;
		return false;
		k623:

		return true;
	}

	private function s300(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(300);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(301);
				}

				goto e1678;
			}

			// s301
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(301);
				}

				goto e1678;}

			$o2 = $this->p;
			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1681;
				}

				$this->p++;
			}
			e1681:
			if (!$this->s302()) {
				goto f1679;}
			goto k1679;
			f1679:
			$this->p = $o2;
			goto e1678;
			k1679:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1678;
			}

			$k1++;
		}
		e1678:

		if ($k1 < 1) {
			goto f1677;
		}
		goto k1677;
		f1677:
		$this->p = $o1;
		return false;
		k1677:

		return true;
	}

	private function s302(): bool
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
			case 24:
				// s304
				$o2 = $this->p;
				if (!$this->s286()) {
					goto f1737;}
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1739;
					}

					$this->p++;
				}
				e1739:
				goto k1737;
				f1737:
				$this->p = $o2;
				goto n1736;
				k1737:
				goto a1735;
				n1736:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n1740;}
				goto a1735;
				n1740:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(302);
		}

		goto f1734;
		a1735:
		goto k1734;
		f1734:
		$this->p = $o1;
		return false;
		k1734:

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
		$o2 = $this->p;
		if (!$this->s199()) {
			goto f964;}
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
		goto k964;
		f964:
		$this->p = $o2;
		goto f963;
		k964:
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e966;
			}

			$this->p++;
		}
		e966:
		if (($this->ids[$this->p] === 40)) {
			if (!$this->s311()) {
				goto n967;}
			n967:
		} elseif ($this->p > $this->f) {
			$this->rec(311);
		}
		goto k963;
		f963:
		$this->p = $o1;
		return false;
		k963:

		return true;
	}

	private function s311(): bool
	{
		if (!($this->ids[$this->p] === 40)) {
			if ($this->p > $this->f) {
				$this->rec(311);
			}

			return false;}

		$o1 = $this->p;
		$k1 = 0;
		while (true) {
			$b1 = $this->p;
			if (!($this->ids[$this->p] === 40)) {
				if ($b1 > $this->f) {
					$this->rec(312);
				}

				goto e1262;
			}

			// s312
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(312);
				}

				goto e1262;}

			$o2 = $this->p;
			$this->p++;
			// s203
			$o3 = $this->p;
			while (true) {
				$b3 = $this->p;
				if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
					if ($b3 > $this->f) {
						$this->rec(204);
					}

					goto e1265;
				}

				$this->p++;
			}
			e1265:
			if (!$this->s313()) {
				goto f1263;}
			goto k1263;
			f1263:
			$this->p = $o2;
			goto e1262;
			k1263:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1262;
			}

			$k1++;
		}
		e1262:

		if ($k1 < 1) {
			goto f1261;
		}
		goto k1261;
		f1261:
		$this->p = $o1;
		return false;
		k1261:

		return true;
	}

	private function s313(): bool
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
			case 82:
				// s314
				$o2 = $this->p;
				// s307
				$o3 = $this->p;
				if (!$this->s199()) {
					goto f1542;}
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
				goto k1542;
				f1542:
				$this->p = $o3;
				goto f1541;
				k1542:
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1544;
					}

					$this->p++;
				}
				e1544:
				goto k1541;
				f1541:
				$this->p = $o2;
				goto n1540;
				k1541:
				goto a1539;
				n1540:
				break;
			case 33:
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p > $this->f) {
						$this->rec(53);
					}

					goto n1545;}
				goto a1539;
				n1545:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(313);
		}

		goto f1538;
		a1539:
		goto k1538;
		f1538:
		$this->p = $o1;
		return false;
		k1538:

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
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f971;}
				}
				if (!$this->s318()) {
					goto f971;}
				goto k971;
				f971:
				$this->p = $o2;
				goto n970;
				k971:
				goto a969;
				n970:
				// s333
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
						goto f973;}
				}
				if (!$this->s335()) {
					goto f973;}
				goto k973;
				f973:
				$this->p = $o2;
				goto n972;
				k973:
				goto a969;
				n972:
				break;
			case 19:
			case 20:
			case 21:
			case 22:
				// s333
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
						goto f976;}
				}
				if (!$this->s335()) {
					goto f976;}
				goto k976;
				f976:
				$this->p = $o2;
				goto n975;
				k976:
				goto a969;
				n975:
				break;
			case 3:
			case 4:
			case 8:
				if (!$this->s255()) {
					goto n978;}
				goto a969;
				n978:
				// s317
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f980;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f980;}
				}
				if (!$this->s318()) {
					goto f980;}
				goto k980;
				f980:
				$this->p = $o2;
				goto n979;
				k980:
				goto a969;
				n979:
				// s333
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
						goto f982;}
				}
				if (!$this->s335()) {
					goto f982;}
				goto k982;
				f982:
				$this->p = $o2;
				goto n981;
				k982:
				goto a969;
				n981:
				break;
			case 32:
			case 82:
				if (!$this->s316()) {
					goto n984;}
				goto a969;
				n984:
				break;
			case 6:
				if (!$this->s267()) {
					goto n985;}
				goto a969;
				n985:
				// s317
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f987;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f987;}
				}
				if (!$this->s318()) {
					goto f987;}
				goto k987;
				f987:
				$this->p = $o2;
				goto n986;
				k987:
				goto a969;
				n986:
				// s333
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
						goto f989;}
				}
				if (!$this->s335()) {
					goto f989;}
				goto k989;
				f989:
				$this->p = $o2;
				goto n988;
				k989:
				goto a969;
				n988:
				break;
			case 7:
				if (!$this->s230()) {
					goto n991;}
				goto a969;
				n991:
				// s317
				$o2 = $this->p;
				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					if (($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f993;}
				}
				if (($this->ids[$this->p] === 38)) {
					if (($this->ids[$this->p] === 38)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f993;}
				}
				if (!$this->s318()) {
					goto f993;}
				goto k993;
				f993:
				$this->p = $o2;
				goto n992;
				k993:
				goto a969;
				n992:
				// s333
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
						goto f995;}
				}
				if (!$this->s335()) {
					goto f995;}
				goto k995;
				f995:
				$this->p = $o2;
				goto n994;
				k995:
				goto a969;
				n994:
				break;
			case 25:
				// s229
				$o2 = $this->p;
				$this->p++;
				if (isset(self::T885cd349[$this->ids[$this->p]])) {
					// s223
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->rec(223);
						}

						goto n999;}

					$o3 = $this->p;
					$k3 = 0;
					while (true) {
						$b3 = $this->p;
						if (!isset(self::T885cd349[$this->ids[$this->p]])) {
							if ($b3 > $this->f) {
								$this->rec(224);
							}

							goto e1001;
						}

						if (!$this->s224()) {
							goto e1001;}
						if ($this->p === $b3) {
							$k3 = 1;
							goto e1001;
						}

						$k3++;
					}
					e1001:

					if ($k3 < 1) {
						goto f1000;
					}
					goto k1000;
					f1000:
					$this->p = $o3;
					goto n999;
					k1000:
					n999:
				} elseif ($this->p > $this->f) {
					$this->rec(223);
				}
				goto a969;
				n997:
				break;
			case 30:
				// s200
				$o2 = $this->p;
				$this->p++;
				if (!$this->s201()) {
					goto f1003;}
				goto k1003;
				f1003:
				$this->p = $o2;
				goto n1002;
				k1003:
				goto a969;
				n1002:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(315);
		}

		goto f968;
		a969:
		goto k968;
		f968:
		$this->p = $o1;
		return false;
		k968:

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
		$this->p++;
		if (!$this->s205()) {
			goto f1279;}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f1279;}

		$this->p++;
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n1280;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e1282;
				}

				if (!$this->s224()) {
					goto e1282;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1282;
				}

				$k2++;
			}
			e1282:

			if ($k2 < 1) {
				goto f1281;
			}
			goto k1281;
			f1281:
			$this->p = $o2;
			goto n1280;
			k1281:
			n1280:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k1279;
		f1279:
		$this->p = $o1;
		return false;
		k1279:

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
					goto n1268;}
				goto a1267;
				n1268:
				break;
			case 34:
			case 86:
				// s319
				$o2 = $this->p;
				if (!$this->s320()) {
					goto f1270;}
				if (isset(self::T885cd349[$this->ids[$this->p]])) {
					// s223
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->rec(223);
						}

						goto n1271;}

					$o3 = $this->p;
					$k3 = 0;
					while (true) {
						$b3 = $this->p;
						if (!isset(self::T885cd349[$this->ids[$this->p]])) {
							if ($b3 > $this->f) {
								$this->rec(224);
							}

							goto e1273;
						}

						if (!$this->s224()) {
							goto e1273;}
						if ($this->p === $b3) {
							$k3 = 1;
							goto e1273;
						}

						$k3++;
					}
					e1273:

					if ($k3 < 1) {
						goto f1272;
					}
					goto k1272;
					f1272:
					$this->p = $o3;
					goto n1271;
					k1272:
					n1271:
				} elseif ($this->p > $this->f) {
					$this->rec(223);
				}
				goto k1270;
				f1270:
				$this->p = $o2;
				goto n1269;
				k1270:
				goto a1267;
				n1269:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1274;}
				goto a1267;
				n1274:
				break;
			case 36:
			case 84:
				// s223
				$o2 = $this->p;
				$k2 = 0;
				while (true) {
					$b2 = $this->p;
					if (!isset(self::T885cd349[$this->ids[$this->p]])) {
						if ($b2 > $this->f) {
							$this->rec(224);
						}

						goto e1277;
					}

					if (!$this->s224()) {
						goto e1277;}
					if ($this->p === $b2) {
						$k2 = 1;
						goto e1277;
					}

					$k2++;
				}
				e1277:

				if ($k2 < 1) {
					goto f1276;
				}
				goto k1276;
				f1276:
				$this->p = $o2;
				goto n1275;
				k1276:
				goto a1267;
				n1275:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1278;}
				goto a1267;
				n1278:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(318);
		}

		goto f1266;
		a1267:
		goto k1266;
		f1266:
		$this->p = $o1;
		return false;
		k1266:

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
		$this->p++;
		// s203
		$o2 = $this->p;
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
		if (!$this->s321()) {
			goto f635;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e639;
			}

			$this->p++;
		}
		e639:
		if (($this->ids[$this->p] === 40)) {
			// s327
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(327);
				}

				goto n640;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(328);
					}

					goto e642;
				}

				if (!$this->s328()) {
					goto e642;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e642;
				}

				$k2++;
			}
			e642:

			if ($k2 < 1) {
				goto f641;
			}
			goto k641;
			f641:
			$this->p = $o2;
			goto n640;
			k641:
			n640:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f635;}

		$this->p++;
		goto k635;
		f635:
		$this->p = $o1;
		return false;
		k635:

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
				$o2 = $this->p;
				if (isset(self::T05cf281c[$this->ids[$this->p]])) {
					if (isset(self::T05cf281c[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f874;}
				}
				if (!$this->s199()) {
					goto f874;}
				goto k874;
				f874:
				$this->p = $o2;
				goto n873;
				k874:
				goto a872;
				n873:
				break;
			case 1:
			case 2:
				// s322
				$o2 = $this->p;
				$this->p++;
				if (!$this->s199()) {
					goto f876;}
				goto k876;
				f876:
				$this->p = $o2;
				goto n875;
				k876:
				goto a872;
				n875:
				break;
			case 50:
				$this->p++;
				goto a872;
				break;
			case 85:
				$this->p++;
				goto a872;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(321);
		}

		goto f871;
		a872:
		goto k871;
		f871:
		$this->p = $o1;
		return false;
		k871:

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
		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e879;
			}

			$this->p++;
		}
		e879:
		if (!$this->s329()) {
			goto f877;}
		goto k877;
		f877:
		$this->p = $o1;
		return false;
		k877:

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
				$o2 = $this->p;
				if (!$this->s321()) {
					goto f1137;}
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e1139;
					}

					$this->p++;
				}
				e1139:
				goto k1137;
				f1137:
				$this->p = $o2;
				goto n1136;
				k1137:
				goto a1135;
				n1136:
				break;
			case 35:
				if (!($this->ids[$this->p] === 35)) {
					if ($this->p > $this->f) {
						$this->rec(56);
					}

					goto n1140;}
				goto a1135;
				n1140:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(329);
		}

		goto f1134;
		a1135:
		goto k1134;
		f1134:
		$this->p = $o1;
		return false;
		k1134:

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
				goto f712;}
		}
		if (!$this->s340()) {
			goto f712;}
		if (isset(self::T885cd349[$this->ids[$this->p]])) {
			// s223
			if (!isset(self::T885cd349[$this->ids[$this->p]])) {
				if ($this->p > $this->f) {
					$this->rec(223);
				}

				goto n714;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!isset(self::T885cd349[$this->ids[$this->p]])) {
					if ($b2 > $this->f) {
						$this->rec(224);
					}

					goto e716;
				}

				if (!$this->s224()) {
					goto e716;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e716;
				}

				$k2++;
			}
			e716:

			if ($k2 < 1) {
				goto f715;
			}
			goto k715;
			f715:
			$this->p = $o2;
			goto n714;
			k715:
			n714:
		} elseif ($this->p > $this->f) {
			$this->rec(223);
		}
		goto k712;
		f712:
		$this->p = $o1;
		return false;
		k712:

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
				$o2 = $this->p;
				$this->p++;
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(16);
					}

					goto f921;}

				$this->p++;
				goto k921;
				f921:
				$this->p = $o2;
				goto n920;
				k921:
				goto a919;
				n920:
				break;
			case 36:
				$this->p++;
				goto a919;
				break;
			case 84:
				$this->p++;
				goto a919;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(337);
		}

		goto f918;
		a919:
		goto k918;
		f918:
		$this->p = $o1;
		return false;
		k918:

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
				$o2 = $this->p;
				if (isset(self::T1ad6c327[$this->ids[$this->p]])) {
					if (isset(self::T1ad6c327[$this->ids[$this->p]])) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f206;}
				}
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f206;}

				$this->p++;
				if (($this->ids[$this->p] === 43)) {
					// s360
					if (!($this->ids[$this->p] === 43)) {
						if ($this->p > $this->f) {
							$this->rec(360);
						}

						goto n207;}

					$o3 = $this->p;
					$this->p++;
					if (!$this->s163()) {
						goto f208;}
					goto k208;
					f208:
					$this->p = $o3;
					goto n207;
					k208:
					n207:
				} elseif ($this->p > $this->f) {
					$this->rec(360);
				}
				goto k206;
				f206:
				$this->p = $o2;
				goto n205;
				k206:
				goto a204;
				n205:
				break;
			case 7:
			case 18:
				// s347
				$o2 = $this->p;
				$this->p++;
				if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(16);
					}

					goto f210;}

				$this->p++;
				if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
					if (!$this->s349()) {
						goto n211;}
					n211:
				} elseif ($this->p > $this->f) {
					$this->rec(349);
				}
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p >= $this->f) {
						$this->rec(53);
					}

					goto f210;}

				$this->p++;
				goto k210;
				f210:
				$this->p = $o2;
				goto n209;
				k210:
				goto a204;
				n209:
				break;
			case 21:
			case 22:
				// s343
				$o2 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 21:
						$this->p++;
						goto a214;
						break;
					case 22:
						$this->p++;
						goto a214;
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(343);
				}

				goto f213;
				a214:
				goto k213;
				f213:
				$this->p = $o2;
				goto n212;
				k213:
				goto a204;
				n212:
				break;
			case 36:
			case 84:
				// s361
				$o2 = $this->p;
				$this->p++;
				if (isset(self::T69bf8cc4[$this->ids[$this->p]])) {
					if (!$this->s349()) {
						goto n217;}
					n217:
				} elseif ($this->p > $this->f) {
					$this->rec(349);
				}
				if (!($this->ids[$this->p] === 37)) {
					if ($this->p >= $this->f) {
						$this->rec(59);
					}

					goto f216;}

				$this->p++;
				goto k216;
				f216:
				$this->p = $o2;
				goto n215;
				k216:
				goto a204;
				n215:
				break;
			case 15:
				$this->p++;
				goto a204;
				break;
			case 16:
				$this->p++;
				goto a204;
				break;
			case 17:
				$this->p++;
				goto a204;
				break;
			case 19:
				$this->p++;
				goto a204;
				break;
			case 20:
				$this->p++;
				goto a204;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(340);
		}

		goto f203;
		a204:
		goto k203;
		f203:
		$this->p = $o1;
		return false;
		k203:

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
		$o2 = $this->p;
		if (!$this->s340()) {
			goto f490;}
		// s351
		$o3 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n493;}
				goto a492;
				n493:
				break;
			case 44:
				// s352
				$o4 = $this->p;
				$this->p++;
				if (!$this->s340()) {
					goto f495;}
				goto k495;
				f495:
				$this->p = $o4;
				goto n494;
				k495:
				goto a492;
				n494:
				if (($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n496;}
				goto a492;
				n496:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(351);
		}

		goto f491;
		a492:
		goto k491;
		f491:
		$this->p = $o3;
		goto f490;
		k491:
		goto k490;
		f490:
		$this->p = $o2;
		goto f489;
		k490:
		if (($this->ids[$this->p] === 40)) {
			// s355
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(355);
				}

				goto n497;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(356);
					}

					goto e499;
				}

				// s356
				if (!($this->ids[$this->p] === 40)) {
					if ($this->p > $this->f) {
						$this->rec(356);
					}

					goto e499;}

				$o3 = $this->p;
				$this->p++;
				if (!$this->s350()) {
					goto f500;}
				goto k500;
				f500:
				$this->p = $o3;
				goto e499;
				k500:
				if ($this->p === $b2) {
					$k2 = 1;
					goto e499;
				}

				$k2++;
			}
			e499:

			if ($k2 < 1) {
				goto f498;
			}
			goto k498;
			f498:
			$this->p = $o2;
			goto n497;
			k498:
			n497:
		} elseif ($this->p > $this->f) {
			$this->rec(355);
		}
		if (($this->ids[$this->p] === 40)) {
			$this->p++;
		} elseif ($this->p >= $this->f) {
			$this->rec(63);
		}
		goto k489;
		f489:
		$this->p = $o1;
		return false;
		k489:

		return true;
	}

	private function s350(): bool
	{
		if (!isset(self::T69bf8cc4[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(350);
			}

			return false;}

		$o1 = $this->p;
		if (!$this->s340()) {
			goto f776;}
		// s351
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n779;}
				goto a778;
				n779:
				break;
			case 44:
				// s352
				$o3 = $this->p;
				$this->p++;
				if (!$this->s340()) {
					goto f781;}
				goto k781;
				f781:
				$this->p = $o3;
				goto n780;
				k781:
				goto a778;
				n780:
				if (($this->ids[$this->p] === 44)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n782;}
				goto a778;
				n782:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(351);
		}

		goto f777;
		a778:
		goto k777;
		f777:
		$this->p = $o2;
		goto f776;
		k777:
		goto k776;
		f776:
		$this->p = $o1;
		return false;
		k776:

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
		$this->p++;
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1557;
			}

			$this->p++;
		}
		e1557:
		if (!$this->s321()) {
			goto f1555;}
		// s203
		$o2 = $this->p;
		while (true) {
			$b2 = $this->p;
			if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
				if ($b2 > $this->f) {
					$this->rec(204);
				}

				goto e1559;
			}

			$this->p++;
		}
		e1559:
		if (($this->ids[$this->p] === 40)) {
			// s327
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(327);
				}

				goto n1560;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(328);
					}

					goto e1562;
				}

				if (!$this->s328()) {
					goto e1562;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1562;
				}

				$k2++;
			}
			e1562:

			if ($k2 < 1) {
				goto f1561;
			}
			goto k1561;
			f1561:
			$this->p = $o2;
			goto n1560;
			k1561:
			n1560:
		} elseif ($this->p > $this->f) {
			$this->rec(327);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1555;}

		$this->p++;
		goto k1555;
		f1555:
		$this->p = $o1;
		return false;
		k1555:

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
						goto f460;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f460;}
				}
				goto k460;
				f460:
				$this->p = $o2;
				goto n459;
				k460:
				goto a458;
				n459:
				break;
			case 28:
			case 29:
			case 41:
			case 80:
				// s369
				$o2 = $this->p;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e464;
					}

					$this->p++;
				}
				e464:
				// s370
				if (!isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(370);
					}

					goto f462;}

				$o3 = $this->p;
				switch ($this->ids[$this->p]) {
					default:
						break;
					case 28:
						if (!$this->s371()) {
							goto n467;}
						goto a466;
						n467:
						break;
					case 29:
						if (!$this->s377()) {
							goto n468;}
						goto a466;
						n468:
						break;
				}

				if ($this->p > $this->f) {
					$this->rec(370);
				}

				goto f465;
				a466:
				goto k465;
				f465:
				$this->p = $o3;
				goto f462;
				k465:
				goto k462;
				f462:
				$this->p = $o2;
				goto n461;
				k462:
				goto a458;
				n461:
				// s383
				$o2 = $this->p;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f470;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f470;}
				}
				goto k470;
				f470:
				$this->p = $o2;
				goto n469;
				k470:
				goto a458;
				n469:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(368);
		}

		goto f457;
		a458:
		goto k457;
		f457:
		$this->p = $o1;
		return false;
		k457:

		return true;
	}

	private function s371(): bool
	{
		if (!($this->ids[$this->p] === 28)) {
			if ($this->p > $this->f) {
				$this->rec(371);
			}

			return false;}

		$o1 = $this->p;
		if (!$this->s372()) {
			goto f749;}
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f749;}
		}
		goto k749;
		f749:
		$this->p = $o1;
		return false;
		k749:

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

				goto e1030;
			}

			// s373
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->rec(373);
				}

				goto e1030;}

			$o2 = $this->p;
			$this->p++;
			if (!$this->s201()) {
				goto f1031;}
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

					goto e1035;
				}

				$this->p++;
			}
			e1035:
			if (!($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->rec(49);
				}

				goto f1033;}
			goto k1033;
			f1033:
			$this->p = $o3;
			goto n1032;
			k1033:
			n1032:
			goto k1031;
			f1031:
			$this->p = $o2;
			goto e1030;
			k1031:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1030;
			}

			$k1++;
		}
		e1030:

		if ($k1 < 1) {
			goto f1029;
		}
		goto k1029;
		f1029:
		$this->p = $o1;
		return false;
		k1029:

		return true;
	}

	private function s377(): bool
	{
		if (!($this->ids[$this->p] === 29)) {
			if ($this->p > $this->f) {
				$this->rec(377);
			}

			return false;}

		$o1 = $this->p;
		if (!$this->s378()) {
			goto f750;}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f750;}
		}
		goto k750;
		f750:
		$this->p = $o1;
		return false;
		k750:

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

				goto e1037;
			}

			// s379
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->rec(379);
				}

				goto e1037;}

			$o2 = $this->p;
			$this->p++;
			if (!$this->s201()) {
				goto f1038;}
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

					goto e1042;
				}

				$this->p++;
			}
			e1042:
			if (!($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->rec(50);
				}

				goto f1040;}
			goto k1040;
			f1040:
			$this->p = $o3;
			goto n1039;
			k1040:
			n1039:
			goto k1038;
			f1038:
			$this->p = $o2;
			goto e1037;
			k1038:
			if ($this->p === $b1) {
				$k1 = 1;
				goto e1037;
			}

			$k1++;
		}
		e1037:

		if ($k1 < 1) {
			goto f1036;
		}
		goto k1036;
		f1036:
		$this->p = $o1;
		return false;
		k1036:

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
						goto f381;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f381;}
				}
				if (($this->ids[$this->p] === 32)) {
					if (($this->ids[$this->p] === 32)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f381;}
				}
				if (!$this->s125()) {
					goto f381;}
				goto k381;
				f381:
				$this->p = $o2;
				goto n380;
				k381:
				goto a379;
				n380:
				break;
			case 25:
			case 26:
				// s386
				$o2 = $this->p;
				$this->p++;
				if (!$this->s125()) {
					goto f383;}
				goto k383;
				f383:
				$this->p = $o2;
				goto n382;
				k383:
				goto a379;
				n382:
				// s388
				$o2 = $this->p;
				if (($this->ids[$this->p] === 28)) {
					if (($this->ids[$this->p] === 28)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f385;}
				}
				if (($this->ids[$this->p] === 29)) {
					if (($this->ids[$this->p] === 29)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f385;}
				}
				if (($this->ids[$this->p] === 32)) {
					if (($this->ids[$this->p] === 32)) {
						if ($this->p > $this->f) {
							$this->f = $this->p;
							$this->er = [];
						}
						goto f385;}
				}
				if (!$this->s125()) {
					goto f385;}
				goto k385;
				f385:
				$this->p = $o2;
				goto n384;
				k385:
				goto a379;
				n384:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(385);
		}

		goto f378;
		a379:
		goto k378;
		f378:
		$this->p = $o1;
		return false;
		k378:

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
			goto f332;}
		// s388
		$o2 = $this->p;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f333;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f333;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f333;}
		}
		if (!$this->s125()) {
			goto f333;}
		goto k333;
		f333:
		$this->p = $o2;
		goto f332;
		k333:
		goto k332;
		f332:
		$this->p = $o1;
		return false;
		k332:

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
				$o2 = $this->p;
				if (!$this->s199()) {
					goto f366;}
				if (!$this->s415()) {
					goto f366;}
				goto k366;
				f366:
				$this->p = $o2;
				goto n365;
				k366:
				goto a364;
				n365:
				break;
			case 9:
				// s410
				$o2 = $this->p;
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
						goto f368;}
				}
				if (!$this->s199()) {
					goto f368;}
				if (!$this->s415()) {
					goto f368;}
				goto k368;
				f368:
				$this->p = $o2;
				goto n367;
				k368:
				goto a364;
				n367:
				// s435
				$o2 = $this->p;
				if (!$this->s199()) {
					goto f371;}
				if (!$this->s415()) {
					goto f371;}
				goto k371;
				f371:
				$this->p = $o2;
				goto n370;
				k371:
				goto a364;
				n370:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(409);
		}

		goto f363;
		a364:
		goto k363;
		f363:
		$this->p = $o1;
		return false;
		k363:

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
				$o2 = $this->p;
				// s203
				$o3 = $this->p;
				while (true) {
					$b3 = $this->p;
					if (!isset(self::T5448ab4c[$this->ids[$this->p]])) {
						if ($b3 > $this->f) {
							$this->rec(204);
						}

						goto e612;
					}

					$this->p++;
				}
				e612:
				if (!isset(self::Tc8c984b6[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(414);
					}

					goto f610;}

				$this->p++;
				goto k610;
				f610:
				$this->p = $o2;
				goto n609;
				k610:
				goto a608;
				n609:
				break;
			case 32:
				$this->p++;
				goto a608;
				break;
			case 34:
				$this->p++;
				goto a608;
				break;
			case 36:
				$this->p++;
				goto a608;
				break;
			case 43:
				$this->p++;
				goto a608;
				break;
			case 82:
				$this->p++;
				goto a608;
				break;
			case 84:
				$this->p++;
				goto a608;
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(412);
		}

		goto f607;
		a608:
		goto k607;
		f607:
		$this->p = $o1;
		return false;
		k607:

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
				$o2 = $this->p;
				$this->p++;
				if (!$this->s417()) {
					goto f646;}
				goto k646;
				f646:
				$this->p = $o2;
				goto n645;
				k646:
				goto a644;
				n645:
				break;
			case 32:
			case 34:
			case 82:
			case 86:
				if (!$this->s417()) {
					goto n647;}
				goto a644;
				n647:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(415);
		}

		goto f643;
		a644:
		goto k643;
		f643:
		$this->p = $o1;
		return false;
		k643:

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
		if (!$this->s418()) {
			goto f880;}
		if (!isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(16);
			}

			goto f880;}

		$this->p++;
		if (isset(self::T9373805c[$this->ids[$this->p]])) {
			if (!$this->s424()) {
				goto n881;}
			n881:
		} elseif ($this->p > $this->f) {
			$this->rec(424);
		}
		if (!($this->ids[$this->p] === 33)) {
			if ($this->p >= $this->f) {
				$this->rec(53);
			}

			goto f880;}

		$this->p++;
		if (!$this->s125()) {
			goto f880;}
		goto k880;
		f880:
		$this->p = $o1;
		return false;
		k880:

		return true;
	}

	private function s418(): bool
	{
		$o1 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1143;}
				goto a1142;
				n1143:
				break;
			case 34:
			case 86:
				if (!$this->s419()) {
					goto n1144;}
				goto a1142;
				n1144:
				if (isset(self::Tb46fb4f7[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1145;}
				goto a1142;
				n1145:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(418);
		}

		goto f1141;
		a1142:
		goto k1141;
		f1141:
		$this->p = $o1;
		return false;
		k1141:

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
		$this->p++;
		if (!$this->s286()) {
			goto f1425;}
		if (($this->ids[$this->p] === 40)) {
			// s421
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(421);
				}

				goto n1426;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(422);
					}

					goto e1428;
				}

				// s422
				if (!($this->ids[$this->p] === 40)) {
					if ($this->p > $this->f) {
						$this->rec(422);
					}

					goto e1428;}

				$o3 = $this->p;
				$this->p++;
				if (!$this->s286()) {
					goto f1429;}
				goto k1429;
				f1429:
				$this->p = $o3;
				goto e1428;
				k1429:
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1428;
				}

				$k2++;
			}
			e1428:

			if ($k2 < 1) {
				goto f1427;
			}
			goto k1427;
			f1427:
			$this->p = $o2;
			goto n1426;
			k1427:
			n1426:
		} elseif ($this->p > $this->f) {
			$this->rec(421);
		}
		if (!($this->ids[$this->p] === 35)) {
			if ($this->p >= $this->f) {
				$this->rec(56);
			}

			goto f1425;}

		$this->p++;
		goto k1425;
		f1425:
		$this->p = $o1;
		return false;
		k1425:

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
		// s425
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
			case 30:
			case 32:
			case 82:
				if (!$this->s426()) {
					goto n1149;}
				goto a1148;
				n1149:
				break;
			case 26:
			case 27:
			case 42:
				if (!$this->s431()) {
					goto n1150;}
				goto a1148;
				n1150:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(425);
		}

		goto f1147;
		a1148:
		goto k1147;
		f1147:
		$this->p = $o2;
		goto f1146;
		k1147:
		if (($this->ids[$this->p] === 40)) {
			// s433
			if (!($this->ids[$this->p] === 40)) {
				if ($this->p > $this->f) {
					$this->rec(433);
				}

				goto n1151;}

			$o2 = $this->p;
			$k2 = 0;
			while (true) {
				$b2 = $this->p;
				if (!($this->ids[$this->p] === 40)) {
					if ($b2 > $this->f) {
						$this->rec(434);
					}

					goto e1153;
				}

				if (!$this->s434()) {
					goto e1153;}
				if ($this->p === $b2) {
					$k2 = 1;
					goto e1153;
				}

				$k2++;
			}
			e1153:

			if ($k2 < 1) {
				goto f1152;
			}
			goto k1152;
			f1152:
			$this->p = $o2;
			goto n1151;
			k1152:
			n1151:
		} elseif ($this->p > $this->f) {
			$this->rec(433);
		}
		goto k1146;
		f1146:
		$this->p = $o1;
		return false;
		k1146:

		return true;
	}

	private function s426(): bool
	{
		if (!isset(self::T34c34eee[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(426);
			}

			return false;}

		$o1 = $this->p;
		if (!isset(self::T34c34eee[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(428);
			}

			goto f1430;}
		if (!$this->s199()) {
			goto f1430;}
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

			goto f1430;}

		$this->p++;
		// s429
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1433;}
				goto a1432;
				n1433:
				break;
			case 46:
				// s430
				$o3 = $this->p;
				$this->p++;
				if (!$this->s340()) {
					goto f1435;}
				goto k1435;
				f1435:
				$this->p = $o3;
				goto n1434;
				k1435:
				goto a1432;
				n1434:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1436;}
				goto a1432;
				n1436:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(429);
		}

		goto f1431;
		a1432:
		goto k1431;
		f1431:
		$this->p = $o2;
		goto f1430;
		k1431:
		goto k1430;
		f1430:
		$this->p = $o1;
		return false;
		k1430:

		return true;
	}

	private function s431(): bool
	{
		if (!isset(self::T553dc07b[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(431);
			}

			return false;}

		$o1 = $this->p;
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

			goto f1437;}

		$this->p++;
		// s429
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1440;}
				goto a1439;
				n1440:
				break;
			case 46:
				// s430
				$o3 = $this->p;
				$this->p++;
				if (!$this->s340()) {
					goto f1442;}
				goto k1442;
				f1442:
				$this->p = $o3;
				goto n1441;
				k1442:
				goto a1439;
				n1441:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n1443;}
				goto a1439;
				n1443:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(429);
		}

		goto f1438;
		a1439:
		goto k1438;
		f1438:
		$this->p = $o2;
		goto f1437;
		k1438:
		goto k1437;
		f1437:
		$this->p = $o1;
		return false;
		k1437:

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
		$this->p++;
		// s425
		if (!isset(self::T9373805c[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(425);
			}

			goto f1444;}

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
			case 30:
			case 32:
			case 82:
				if (!$this->s426()) {
					goto n1447;}
				goto a1446;
				n1447:
				break;
			case 26:
			case 27:
			case 42:
				if (!$this->s431()) {
					goto n1448;}
				goto a1446;
				n1448:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(425);
		}

		goto f1445;
		a1446:
		goto k1445;
		f1445:
		$this->p = $o2;
		goto f1444;
		k1445:
		goto k1444;
		f1444:
		$this->p = $o1;
		return false;
		k1444:

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
		$this->p++;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f347;}

		$this->p++;
		if (!$this->s287()) {
			goto f347;}
		// s293
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n350;}
				goto a349;
				n350:
				break;
			case 5:
				// s294
				$o3 = $this->p;
				$this->p++;
				if (!$this->s199()) {
					goto f352;}
				goto k352;
				f352:
				$this->p = $o3;
				goto n351;
				k352:
				goto a349;
				n351:
				if (($this->ids[$this->p] === 5)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n353;}
				goto a349;
				n353:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(293);
		}

		goto f348;
		a349:
		goto k348;
		f348:
		$this->p = $o2;
		goto f347;
		k348:
		// s296
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n356;}
				goto a355;
				n356:
				break;
			case 46:
				// s297
				$o3 = $this->p;
				$this->p++;
				if (!$this->s199()) {
					goto f358;}
				goto k358;
				f358:
				$this->p = $o3;
				goto n357;
				k358:
				goto a355;
				n357:
				if (($this->ids[$this->p] === 46)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n359;}
				goto a355;
				n359:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(296);
		}

		goto f354;
		a355:
		goto k354;
		f354:
		$this->p = $o2;
		goto f347;
		k354:
		// s388
		$o2 = $this->p;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f360;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f360;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f360;}
		}
		if (!$this->s125()) {
			goto f360;}
		goto k360;
		f360:
		$this->p = $o2;
		goto f347;
		k360:
		goto k347;
		f347:
		$this->p = $o1;
		return false;
		k347:

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
		$this->p++;
		if (!$this->s320()) {
			goto f361;}
		// s388
		$o2 = $this->p;
		if (($this->ids[$this->p] === 28)) {
			if (($this->ids[$this->p] === 28)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f362;}
		}
		if (($this->ids[$this->p] === 29)) {
			if (($this->ids[$this->p] === 29)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f362;}
		}
		if (($this->ids[$this->p] === 32)) {
			if (($this->ids[$this->p] === 32)) {
				if ($this->p > $this->f) {
					$this->f = $this->p;
					$this->er = [];
				}
				goto f362;}
		}
		if (!$this->s125()) {
			goto f362;}
		goto k362;
		f362:
		$this->p = $o2;
		goto f361;
		k362:
		goto k361;
		f361:
		$this->p = $o1;
		return false;
		k361:

		return true;
	}

	private function s443(): bool
	{
		if (!($this->ids[$this->p] === 58)) {
			if ($this->p > $this->f) {
				$this->rec(443);
			}

			return false;}

		$o1 = $this->p;
		$this->p++;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f334;}

		$this->p++;
		if (!($this->ids[$this->p] === 10)) {
			if ($this->p >= $this->f) {
				$this->rec(26);
			}

			goto f334;}

		$this->p++;
		if (!isset(self::T83e30518[$this->ids[$this->p]])) {
			if ($this->p > $this->f) {
				$this->rec(144);
			}

			goto f334;}

		$this->p++;
		// s444
		$o2 = $this->p;
		switch ($this->ids[$this->p]) {
			default:
				if (($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n337;}
				goto a336;
				n337:
				break;
			case 14:
				// s445
				$o3 = $this->p;
				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f339;}

				$this->p++;
				goto k339;
				f339:
				$this->p = $o3;
				goto n338;
				k339:
				goto a336;
				n338:
				if (($this->ids[$this->p] === 14)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n340;}
				goto a336;
				n340:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(444);
		}

		goto f335;
		a336:
		goto k335;
		f335:
		$this->p = $o2;
		goto f334;
		k335:
		goto k334;
		f334:
		$this->p = $o1;
		return false;
		k334:

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
					goto n343;}
				goto a342;
				n343:
				break;
			case 45:
				// s450
				$o2 = $this->p;
				$this->p++;
				if (!isset(self::T83e30518[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->rec(144);
					}

					goto f345;}

				$this->p++;
				if (!$this->s451()) {
					goto f345;}
				goto k345;
				f345:
				$this->p = $o2;
				goto n344;
				k345:
				goto a342;
				n344:
				if (($this->ids[$this->p] === 45)) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n346;}
				goto a342;
				n346:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(449);
		}

		goto f341;
		a342:
		goto k341;
		f341:
		$this->p = $o1;
		return false;
		k341:

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
					goto n619;}
				goto a618;
				n619:
				break;
			case 32:
			case 82:
				// s452
				$o2 = $this->p;
				$this->p++;
				if (!($this->ids[$this->p] === 33)) {
					if ($this->p >= $this->f) {
						$this->rec(53);
					}

					goto f621;}

				$this->p++;
				goto k621;
				f621:
				$this->p = $o2;
				goto n620;
				k621:
				goto a618;
				n620:
				if (isset(self::Tc8cfa1df[$this->ids[$this->p]])) {
					if ($this->p > $this->f) {
						$this->f = $this->p;
						$this->er = [];
					}
					goto n622;}
				goto a618;
				n622:
				break;
		}

		if ($this->p > $this->f) {
			$this->rec(451);
		}

		goto f617;
		a618:
		goto k617;
		f617:
		$this->p = $o1;
		return false;
		k617:

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
		$this->p++;
		if (!$this->s125()) {
			goto f288;}
		goto k288;
		f288:
		$this->p = $o1;
		return false;
		k288:

		return true;
	}
}
