<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\InvalidTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueParameterNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ThrowsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;

class PhpDocParserTest extends \PHPUnit\Framework\TestCase
{

	/** @var Lexer */
	private $lexer;

	/** @var PhpDocParser() */
	private $phpDocParser;

	protected function setUp()
	{
		parent::setUp();
		$this->lexer = new Lexer();
		$this->phpDocParser = new PhpDocParser(new TypeParser(), new ConstExprParser());
	}


	/**
	 * @dataProvider provideParamTagsData
	 * @dataProvider provideVarTagsData
	 * @dataProvider provideReturnTagsData
	 * @dataProvider provideThrowsTagsData
	 * @dataProvider providePropertyTagsData
	 * @dataProvider provideMethodTagsData
	 * @dataProvider provideSingleLinePhpDocData
	 * @dataProvider provideMultiLinePhpDocData
	 * @param string     $label
	 * @param string     $input
	 * @param PhpDocNode $expectedPhpDocNode
	 * @param int        $nextTokenType
	 */
	public function testParse(string $label, string $input, PhpDocNode $expectedPhpDocNode, int $nextTokenType = Lexer::TOKEN_END)
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$actualPhpDocNode = $this->phpDocParser->parse($tokens);

		$this->assertEquals($expectedPhpDocNode, $actualPhpDocNode, $label);
		$this->assertSame((string) $expectedPhpDocNode, (string) $actualPhpDocNode);
		$this->assertSame($nextTokenType, $tokens->currentTokenType());
	}


	public function provideParamTagsData(): \Iterator
	{
		yield [
			'OK without description',
			'/** @param Foo $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('Foo'),
						false,
						'$foo',
						''
					)
				),
			]),
		];

		yield [
			'OK with description',
			'/** @param Foo $foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('Foo'),
						false,
						'$foo',
						'optional description'
					)
				),
			]),
		];

		yield [
			'OK variadic without description',
			'/** @param Foo ...$foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('Foo'),
						true,
						'$foo',
						''
					)
				),
			]),
		];

		yield [
			'OK variadic with description',
			'/** @param Foo ...$foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('Foo'),
						true,
						'$foo',
						'optional description'
					)
				),
			]),
		];

		yield [
			'invalid without type, parameter name and description',
			'/** @param */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new InvalidTagValueNode(
						'',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							11,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid without type and parameter name and with description (1)',
			'/** @param #desc */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new InvalidTagValueNode(
						'#desc',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							11,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid without type and parameter name and with description (2)',
			'/** @param (Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new InvalidTagValueNode(
						'(Foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							16,
							Lexer::TOKEN_CLOSE_PARENTHESES
						)
					)
				),
			]),
		];

		yield [
			'invalid with broken type (1)',
			'/** @param (Foo $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new InvalidTagValueNode(
						'(Foo $foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							16,
							Lexer::TOKEN_CLOSE_PARENTHESES
						)
					)
				),
			]),
		];

		yield [
			'invalid with broken type (2)',
			'/** @param Foo<Bar $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new InvalidTagValueNode(
						'Foo<Bar $foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							19,
							Lexer::TOKEN_CLOSE_ANGLE_BRACKET
						)
					)
				),
			]),
		];

		yield [
			'invalid with broken type (3)',
			'/** @param Foo| $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new InvalidTagValueNode(
						'Foo| $foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							16,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid without parameter name and description',
			'/** @param Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new InvalidTagValueNode(
						'Foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							15,
							Lexer::TOKEN_VARIABLE
						)
					)
				),
			]),
		];

		yield [
			'invalid without parameter name and with description',
			'/** @param Foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new InvalidTagValueNode(
						'Foo optional description',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'optional',
							Lexer::TOKEN_IDENTIFIER,
							15,
							Lexer::TOKEN_VARIABLE
						)
					)
				),
			]),
		];
	}


	public function provideVarTagsData(): \Iterator
	{
		yield [
			'OK without description and variable name',
			'/** @var Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'',
						''
					)
				),
			]),
		];

		yield [
			'OK without description',
			'/** @var Foo $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$foo',
						''
					)
				),
			]),
		];

		yield [
			'OK without description and with no space between type and variable name',
			'/** @var Foo$foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$foo',
						''
					)
				),
			]),
		];

		yield [
			'OK without variable name',
			'/** @var Foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'',
						'optional description'
					)
				),
			]),
		];

		yield [
			'OK without variable name and complex description',
			'/** @var callable[] function (Configurator $sender, DI\Compiler $compiler); Occurs after the compiler is created */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new ArrayTypeNode(
							new IdentifierTypeNode('callable')
						),
						'',
						'function (Configurator $sender, DI\Compiler $compiler); Occurs after the compiler is created'
					)
				),
			]),
		];

		yield [
			'OK without variable name and tag in the middle of description',
			'/** @var Foo @inject */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'',
						'@inject'
					)
				),
			]),
		];

		yield [
			'OK with variable name and description',
			'/** @var Foo $foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$foo',
						'optional description'
					)
				),
			]),
		];

		yield [
			'OK with variable name and description and without all optional spaces',
			'/** @var(Foo)$foo#desc*/',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$foo',
						'#desc'
					)
				),
			]),
		];

		yield [
			'invalid without type, variable name and description',
			'/** @var */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new InvalidTagValueNode(
						'',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							9,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid without type and variable name and with description (1)',
			'/** @var #desc */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new InvalidTagValueNode(
						'#desc',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							9,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid without type and variable name and with description (2)',
			'/** @var (Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new InvalidTagValueNode(
						'(Foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							14,
							Lexer::TOKEN_CLOSE_PARENTHESES
						)
					)
				),
			]),
		];

		yield [
			'invalid with broken type (1)',
			'/** @var (Foo $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new InvalidTagValueNode(
						'(Foo $foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							14,
							Lexer::TOKEN_CLOSE_PARENTHESES
						)
					)
				),
			]),
		];

		yield [
			'invalid with broken type (2)',
			'/** @var Foo<Bar $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new InvalidTagValueNode(
						'Foo<Bar $foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							17,
							Lexer::TOKEN_CLOSE_ANGLE_BRACKET
						)
					)
				),
			]),
		];

		yield [
			'invalid with broken type (3)',
			'/** @var Foo| $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new InvalidTagValueNode(
						'Foo| $foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							14,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];
	}


	public function providePropertyTagsData(): \Iterator
	{
		yield [
			'OK without description',
			'/** @property Foo $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@property',
					new PropertyTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$foo',
						''
					)
				),
			]),
		];

		yield [
			'OK with description',
			'/** @property Foo $foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@property',
					new PropertyTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$foo',
						'optional description'
					)
				),
			]),
		];

		yield [
			'invalid without type, property name and description',
			'/** @property */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@property',
					new InvalidTagValueNode(
						'',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							14,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid without type and property name and with description (1)',
			'/** @property #desc */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@property',
					new InvalidTagValueNode(
						'#desc',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							14,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid without type and property name and with description (2)',
			'/** @property (Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@property',
					new InvalidTagValueNode(
						'(Foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							19,
							Lexer::TOKEN_CLOSE_PARENTHESES
						)
					)
				),
			]),
		];

		yield [
			'invalid with broken type (1)',
			'/** @property (Foo $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@property',
					new InvalidTagValueNode(
						'(Foo $foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							19,
							Lexer::TOKEN_CLOSE_PARENTHESES
						)
					)
				),
			]),
		];

		yield [
			'invalid with broken type (2)',
			'/** @property Foo<Bar $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@property',
					new InvalidTagValueNode(
						'Foo<Bar $foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							22,
							Lexer::TOKEN_CLOSE_ANGLE_BRACKET
						)
					)
				),
			]),
		];

		yield [
			'invalid with broken type (3)',
			'/** @property Foo| $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@property',
					new InvalidTagValueNode(
						'Foo| $foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							19,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid without property name and description',
			'/** @property Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@property',
					new InvalidTagValueNode(
						'Foo',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							18,
							Lexer::TOKEN_VARIABLE
						)
					)
				),
			]),
		];

		yield [
			'invalid without property name and with description',
			'/** @property Foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@property',
					new InvalidTagValueNode(
						'Foo optional description',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'optional',
							Lexer::TOKEN_IDENTIFIER,
							18,
							Lexer::TOKEN_VARIABLE
						)
					)
				),
			]),
		];
	}


	public function provideReturnTagsData(): \Iterator
	{
		yield [
			'OK without description',
			'/** @return Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new IdentifierTypeNode('Foo'),
						''
					)
				),
			]),
		];

		yield [
			'OK with description',
			'/** @return Foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new IdentifierTypeNode('Foo'),
						'optional description'
					)
				),
			]),
		];

		yield [
			'OK with description that starts with TOKEN_OPEN_SQUARE_BRACKET',
			'/** @return Foo [Bar] */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new IdentifierTypeNode('Foo'),
						'[Bar]'
					)
				),
			]),
		];

		yield [
			'invalid without type and description',
			'/** @return */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new InvalidTagValueNode(
						'',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							12,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid without type',
			'/** @return [int, string] */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new InvalidTagValueNode(
						'[int, string]',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'[',
							Lexer::TOKEN_OPEN_SQUARE_BRACKET,
							12,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid with type and disallowed description start token (1)',
			'/** @return Foo | #desc */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new InvalidTagValueNode(
						'Foo | #desc',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							18,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid with type and disallowed description start token (2)',
			'/** @return A & B | C */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new InvalidTagValueNode(
						'A & B | C',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'|',
							Lexer::TOKEN_UNION,
							18,
							Lexer::TOKEN_OTHER
						)
					)
				),
			]),
		];

		yield [
			'invalid with type and disallowed description start token (3)',
			'/** @return A | B & C */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new InvalidTagValueNode(
						'A | B & C',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'&',
							Lexer::TOKEN_INTERSECTION,
							18,
							Lexer::TOKEN_OTHER
						)
					)
				),
			]),
		];

		yield [
			'invalid with type and disallowed description start token (4)',
			'/** @return A | B < 123 */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new InvalidTagValueNode(
						'A | B < 123',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'123',
							Lexer::TOKEN_INTEGER,
							20,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];
	}


	public function provideThrowsTagsData(): \Iterator
	{
		yield [
			'OK without description',
			'/** @throws Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@throws',
					new ThrowsTagValueNode(
						new IdentifierTypeNode('Foo'),
						''
					)
				),
			]),
		];

		yield [
			'OK with description',
			'/** @throws Foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@throws',
					new ThrowsTagValueNode(
						new IdentifierTypeNode('Foo'),
						'optional description'
					)
				),
			]),
		];

		yield [
			'OK with description that starts with TOKEN_OPEN_SQUARE_BRACKET',
			'/** @throws Foo [Bar] */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@throws',
					new ThrowsTagValueNode(
						new IdentifierTypeNode('Foo'),
						'[Bar]'
					)
				),
			]),
		];

		yield [
			'invalid without type and description',
			'/** @throws */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@throws',
					new InvalidTagValueNode(
						'',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							12,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];

		yield [
			'invalid with type and disallowed description start token',
			'/** @throws Foo | #desc */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@throws',
					new InvalidTagValueNode(
						'Foo | #desc',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							18,
							Lexer::TOKEN_IDENTIFIER
						)
					)
				),
			]),
		];
	}


	public function provideMethodTagsData(): \Iterator
	{
		yield [
			'OK non-static, without return type',
			'/** @method foo() */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						null,
						'foo',
						[],
						''
					)
				),
			]),
		];

		yield [
			'OK non-static, with return type',
			'/** @method Foo foo() */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new IdentifierTypeNode('Foo'),
						'foo',
						[],
						''
					)
				),
			]),
		];

		yield [
			'OK non-static, with return static type',
			'/** @method static foo() */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new IdentifierTypeNode('static'),
						'foo',
						[],
						''
					)
				),
			]),
		];

		yield [
			'OK static, with return type',
			'/** @method static Foo foo() */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						true,
						new IdentifierTypeNode('Foo'),
						'foo',
						[],
						''
					)
				),
			]),
		];

		yield [
			'OK static, with return static type',
			'/** @method static static foo() */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						true,
						new IdentifierTypeNode('static'),
						'foo',
						[],
						''
					)
				),
			]),
		];

		yield [
			'OK non-static, with return type and description',
			'/** @method Foo foo() optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new IdentifierTypeNode('Foo'),
						'foo',
						[],
						'optional description'
					)
				),
			]),
		];

		yield [
			'OK non-static, with return type and single parameter without type',
			'/** @method Foo foo($a) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new IdentifierTypeNode('Foo'),
						'foo',
						[
							new MethodTagValueParameterNode(
								null,
								false,
								false,
								'$a',
								null
							),
						],
						''
					)
				),
			]),
		];

		yield [
			'OK non-static, with return type and single parameter with type',
			'/** @method Foo foo(A $a) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new IdentifierTypeNode('Foo'),
						'foo',
						[
							new MethodTagValueParameterNode(
								new IdentifierTypeNode('A'),
								false,
								false,
								'$a',
								null
							),
						],
						''
					)
				),
			]),
		];

		yield [
			'OK non-static, with return type and single parameter with type that is passed by reference',
			'/** @method Foo foo(A &$a) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new IdentifierTypeNode('Foo'),
						'foo',
						[
							new MethodTagValueParameterNode(
								new IdentifierTypeNode('A'),
								true,
								false,
								'$a',
								null
							),
						],
						''
					)
				),
			]),
		];

		yield [
			'OK non-static, with return type and single variadic parameter with type',
			'/** @method Foo foo(A ...$a) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new IdentifierTypeNode('Foo'),
						'foo',
						[
							new MethodTagValueParameterNode(
								new IdentifierTypeNode('A'),
								false,
								true,
								'$a',
								null
							),
						],
						''
					)
				),
			]),
		];

		yield [
			'OK non-static, with return type and single variadic parameter with type that is passed by reference',
			'/** @method Foo foo(A &...$a) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new IdentifierTypeNode('Foo'),
						'foo',
						[
							new MethodTagValueParameterNode(
								new IdentifierTypeNode('A'),
								true,
								true,
								'$a',
								null
							),
						],
						''
					)
				),
			]),
		];

		yield [
			'OK non-static, with return type and single parameter with default value',
			'/** @method Foo foo($a = 123) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new IdentifierTypeNode('Foo'),
						'foo',
						[
							new MethodTagValueParameterNode(
								null,
								false,
								false,
								'$a',
								new ConstExprIntegerNode('123')
							),
						],
						''
					)
				),
			]),
		];

		yield [
			'OK non-static, with return type and single variadic parameter with type that is passed by reference and default value',
			'/** @method Foo foo(A &...$a = 123) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new IdentifierTypeNode('Foo'),
						'foo',
						[
							new MethodTagValueParameterNode(
								new IdentifierTypeNode('A'),
								true,
								true,
								'$a',
								new ConstExprIntegerNode('123')
							),
						],
						''
					)
				),
			]),
		];

		yield [
			'OK non-static, with return type and multiple parameters without type',
			'/** @method Foo foo($a, $b, $c) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new IdentifierTypeNode('Foo'),
						'foo',
						[
							new MethodTagValueParameterNode(
								null,
								false,
								false,
								'$a',
								null
							),
							new MethodTagValueParameterNode(
								null,
								false,
								false,
								'$b',
								null
							),
							new MethodTagValueParameterNode(
								null,
								false,
								false,
								'$c',
								null
							),
						],
						''
					)
				),
			]),
		];

		yield [
			'invalid non-static method without parentheses',
			'/** @method a b */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new InvalidTagValueNode(
						'a b',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							16,
							Lexer::TOKEN_OPEN_PARENTHESES
						)
					)
				),
			]),
		];

		yield [
			'invalid static method without parentheses',
			'/** @method static a b */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new InvalidTagValueNode(
						'static a b',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							23,
							Lexer::TOKEN_OPEN_PARENTHESES
						)
					)
				),
			]),
		];

		yield [
			'invalid non-static method without parameter name',
			'/** @method a b(x) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new InvalidTagValueNode(
						'a b(x)',
						new \PHPStan\PhpDocParser\Parser\ParserException(
							')',
							Lexer::TOKEN_CLOSE_PARENTHESES,
							17,
							Lexer::TOKEN_VARIABLE
						)
					)
				),
			]),
		];
	}


	public function provideSingleLinePhpDocData(): \Iterator
	{
		yield [
			'empty',
			'/** */',
			new PhpDocNode([]),
		];

		yield [
			'edge-case',
			'/** /**/',
			new PhpDocNode([
				new PhpDocTextNode(
					'/*'
				),
			]),
		];

		yield [
			'single text node',
			'/** text */',
			new PhpDocNode([
				new PhpDocTextNode(
					'text'
				),
			]),
		];

		yield [
			'single text node with tag in the middle',
			'/** text @foo bar */',
			new PhpDocNode([
				new PhpDocTextNode(
					'text @foo bar'
				),
			]),
		];

		yield [
			'single tag node without value',
			'/** @foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@foo',
					new GenericTagValueNode('')
				),
			]),
		];

		yield [
			'single tag node with value',
			'/** @foo lorem ipsum */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@foo',
					new GenericTagValueNode('lorem ipsum')
				),
			]),
		];

		yield [
			'single tag node with tag in the middle of value',
			'/** @foo lorem @bar ipsum */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@foo',
					new GenericTagValueNode('lorem @bar ipsum')
				),
			]),
		];

		yield [
			'single tag node without space between tag name and its value',
			'/** @varFoo $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@varFoo',
					new GenericTagValueNode(
						'$foo'
					)
				),
			]),
		];
	}


	public function provideMultiLinePhpDocData(): array
	{
		return [
			[
				'multi-line with two tags',
				'/**
                  * @param Foo $foo 1st multi world description
                  * @param Bar $bar 2nd multi world description
                  */',
				new PhpDocNode([
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Foo'),
							false,
							'$foo',
							'1st multi world description'
						)
					),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Bar'),
							false,
							'$bar',
							'2nd multi world description'
						)
					),
				]),
			],
			[
				'multi-line with two tags and text in the middle',
				'/**
                  * @param Foo $foo 1st multi world description
                  * some text in the middle
                  * @param Bar $bar 2nd multi world description
                  */',
				new PhpDocNode([
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Foo'),
							false,
							'$foo',
							'1st multi world description'
						)
					),
					new PhpDocTextNode('some text in the middle'),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Bar'),
							false,
							'$bar',
							'2nd multi world description'
						)
					),
				]),
			],
			[
				'multi-line with two tags, text in the middle and some empty lines',
				'/**
                  *
                  *
                  * @param Foo $foo 1st multi world description
                  *
                  *
                  * some text in the middle
                  *
                  *
                  * @param Bar $bar 2nd multi world description
                  *
                  *
                  */',
				new PhpDocNode([
					new PhpDocTextNode(''),
					new PhpDocTextNode(''),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Foo'),
							false,
							'$foo',
							'1st multi world description'
						)
					),
					new PhpDocTextNode(''),
					new PhpDocTextNode(''),
					new PhpDocTextNode('some text in the middle'),
					new PhpDocTextNode(''),
					new PhpDocTextNode(''),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Bar'),
							false,
							'$bar',
							'2nd multi world description'
						)
					),
					new PhpDocTextNode(''),
					new PhpDocTextNode(''),
				]),
			],
			[
				'multi-line with just empty lines',
				'/**
                  *
                  *
                  */',
				new PhpDocNode([
					new PhpDocTextNode(''),
					new PhpDocTextNode(''),
				]),
			],
			[
				'multi-line with tag mentioned as part of text node',
				'/**
                  * Lets talk about @param
                  * @param int $foo @param string $bar
                  */',
				new PhpDocNode([
					new PhpDocTextNode('Lets talk about @param'),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('int'),
							false,
							'$foo',
							'@param string $bar'
						)
					),
				]),
			],
			[
				'multi-line with a lot of @method tags',
				'/**
                  * @method int getInteger(int $a, int $b)
                  * @method void doSomething(int $a, $b)
                  * @method self|Bar getFooOrBar()
                  * @method methodWithNoReturnType()
                  * @method static int getIntegerStatically(int $a, int $b)
                  * @method static void doSomethingStatically(int $a, $b)
                  * @method static self|Bar getFooOrBarStatically()
                  * @method static methodWithNoReturnTypeStatically()
                  * @method int getIntegerWithDescription(int $a, int $b) Get an integer with a description.
                  * @method void doSomethingWithDescription(int $a, $b) Do something with a description.
                  * @method self|Bar getFooOrBarWithDescription() Get a Foo or a Bar with a description.
                  * @method methodWithNoReturnTypeWithDescription() Do something with a description but what, who knows!
                  * @method static int getIntegerStaticallyWithDescription(int $a, int $b) Get an integer with a description statically.
                  * @method static void doSomethingStaticallyWithDescription(int $a, $b) Do something with a description statically.
                  * @method static self|Bar getFooOrBarStaticallyWithDescription() Get a Foo or a Bar with a description statically.
                  * @method static methodWithNoReturnTypeStaticallyWithDescription() Do something with a description statically, but what, who knows!
                  * @method static bool aStaticMethodThatHasAUniqueReturnTypeInThisClass()
                  * @method static string aStaticMethodThatHasAUniqueReturnTypeInThisClassWithDescription() A Description.
                  * @method int getIntegerNoParams()
                  * @method void doSomethingNoParams()
                  * @method self|Bar getFooOrBarNoParams()
                  * @method methodWithNoReturnTypeNoParams()
                  * @method static int getIntegerStaticallyNoParams()
                  * @method static void doSomethingStaticallyNoParams()
                  * @method static self|Bar getFooOrBarStaticallyNoParams()
                  * @method static methodWithNoReturnTypeStaticallyNoParams()
                  * @method int getIntegerWithDescriptionNoParams() Get an integer with a description.
                  * @method void doSomethingWithDescriptionNoParams() Do something with a description.
                  * @method self|Bar getFooOrBarWithDescriptionNoParams() Get a Foo or a Bar with a description.
                  * @method static int getIntegerStaticallyWithDescriptionNoParams() Get an integer with a description statically.
                  * @method static void doSomethingStaticallyWithDescriptionNoParams() Do something with a description statically.
                  * @method static self|Bar getFooOrBarStaticallyWithDescriptionNoParams() Get a Foo or a Bar with a description statically.
                  * @method static bool|string aStaticMethodThatHasAUniqueReturnTypeInThisClassNoParams()
                  * @method static string|float aStaticMethodThatHasAUniqueReturnTypeInThisClassWithDescriptionNoParams() A Description.
                  * @method \Aws\Result publish(array $args)
                  * @method Image rotate(float & ... $angle = array(), $backgroundColor)
                  * @method Foo overridenMethod()
                  */',
				new PhpDocNode([
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('int'),
							'getInteger',
							[
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$a',
									null
								),
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$b',
									null
								),
							],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('void'),
							'doSomething',
							[
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$a',
									null
								),
								new MethodTagValueParameterNode(
									null,
									false,
									false,
									'$b',
									null
								),
							],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new UnionTypeNode([
								new IdentifierTypeNode('self'),
								new IdentifierTypeNode('Bar'),
							]),
							'getFooOrBar',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							null,
							'methodWithNoReturnType',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('int'),
							'getIntegerStatically',
							[
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$a',
									null
								),
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$b',
									null
								),
							],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('void'),
							'doSomethingStatically',
							[
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$a',
									null
								),
								new MethodTagValueParameterNode(
									null,
									false,
									false,
									'$b',
									null
								),
							],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new UnionTypeNode([
								new IdentifierTypeNode('self'),
								new IdentifierTypeNode('Bar'),
							]),
							'getFooOrBarStatically',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('static'),
							'methodWithNoReturnTypeStatically',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('int'),
							'getIntegerWithDescription',
							[
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$a',
									null
								),
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$b',
									null
								),
							],
							'Get an integer with a description.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('void'),
							'doSomethingWithDescription',
							[
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$a',
									null
								),
								new MethodTagValueParameterNode(
									null,
									false,
									false,
									'$b',
									null
								),
							],
							'Do something with a description.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new UnionTypeNode([
								new IdentifierTypeNode('self'),
								new IdentifierTypeNode('Bar'),
							]),
							'getFooOrBarWithDescription',
							[],
							'Get a Foo or a Bar with a description.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							null,
							'methodWithNoReturnTypeWithDescription',
							[],
							'Do something with a description but what, who knows!'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('int'),
							'getIntegerStaticallyWithDescription',
							[
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$a',
									null
								),
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$b',
									null
								),
							],
							'Get an integer with a description statically.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('void'),
							'doSomethingStaticallyWithDescription',
							[
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$a',
									null
								),
								new MethodTagValueParameterNode(
									null,
									false,
									false,
									'$b',
									null
								),
							],
							'Do something with a description statically.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new UnionTypeNode([
									new IdentifierTypeNode('self'),
									new IdentifierTypeNode('Bar'),
								]),
							'getFooOrBarStaticallyWithDescription',
							[],
							'Get a Foo or a Bar with a description statically.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('static'),
							'methodWithNoReturnTypeStaticallyWithDescription',
							[],
							'Do something with a description statically, but what, who knows!'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('bool'),
							'aStaticMethodThatHasAUniqueReturnTypeInThisClass',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('string'),
							'aStaticMethodThatHasAUniqueReturnTypeInThisClassWithDescription',
							[],
							'A Description.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('int'),
							'getIntegerNoParams',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('void'),
							'doSomethingNoParams',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new UnionTypeNode([
									new IdentifierTypeNode('self'),
									new IdentifierTypeNode('Bar'),
								]),
							'getFooOrBarNoParams',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							null,
							'methodWithNoReturnTypeNoParams',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('int'),
							'getIntegerStaticallyNoParams',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('void'),
							'doSomethingStaticallyNoParams',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new UnionTypeNode([
									new IdentifierTypeNode('self'),
									new IdentifierTypeNode('Bar'),
								]),
							'getFooOrBarStaticallyNoParams',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('static'),
							'methodWithNoReturnTypeStaticallyNoParams',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('int'),
							'getIntegerWithDescriptionNoParams',
							[],
							'Get an integer with a description.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('void'),
							'doSomethingWithDescriptionNoParams',
							[],
							'Do something with a description.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new UnionTypeNode([
									new IdentifierTypeNode('self'),
									new IdentifierTypeNode('Bar'),
								]),
							'getFooOrBarWithDescriptionNoParams',
							[],
							'Get a Foo or a Bar with a description.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('int'),
							'getIntegerStaticallyWithDescriptionNoParams',
							[],
							'Get an integer with a description statically.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('void'),
							'doSomethingStaticallyWithDescriptionNoParams',
							[],
							'Do something with a description statically.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new UnionTypeNode([
									new IdentifierTypeNode('self'),
									new IdentifierTypeNode('Bar'),
								]),
							'getFooOrBarStaticallyWithDescriptionNoParams',
							[],
							'Get a Foo or a Bar with a description statically.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new UnionTypeNode([
									new IdentifierTypeNode('bool'),
									new IdentifierTypeNode('string'),
								]),
							'aStaticMethodThatHasAUniqueReturnTypeInThisClassNoParams',
							[],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new UnionTypeNode([
									new IdentifierTypeNode('string'),
									new IdentifierTypeNode('float'),
								]),
							'aStaticMethodThatHasAUniqueReturnTypeInThisClassWithDescriptionNoParams',
							[],
							'A Description.'
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('\\Aws\\Result'),
							'publish',
							[
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('array'),
									false,
									false,
									'$args',
									null
								),
							],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('Image'),
							'rotate',
							[
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('float'),
									true,
									true,
									'$angle',
									new ConstExprArrayNode([])
								),
								new MethodTagValueParameterNode(
									null,
									false,
									false,
									'$backgroundColor',
									null
								),
							],
							''
						)
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('Foo'),
							'overridenMethod',
							[],
							''
						)
					),
				]),
			],
		];
	}

}
