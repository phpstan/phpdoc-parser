<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\InvalidTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueParameterNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
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
	 * @dataProvider provideParseData
	 * @param string     $input
	 * @param PhpDocNode $expectedPhpDocNode
	 * @param int        $nextTokenType
	 */
	public function testParse(string $input, PhpDocNode $expectedPhpDocNode, int $nextTokenType = Lexer::TOKEN_END)
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$actualPhpDocNode = $this->phpDocParser->parse($tokens);

		$this->assertEquals($expectedPhpDocNode, $actualPhpDocNode);
		$this->assertSame((string) $expectedPhpDocNode, (string) $actualPhpDocNode);
		$this->assertSame($nextTokenType, $tokens->currentTokenType());
	}


	public function provideParseData(): array
	{
		return [
			[
				'/** nothing */',
				new PhpDocNode([
					new PhpDocTextNode(' nothing '),
				]),
			],
			[
				'/**nothing*/',
				new PhpDocNode([
					new PhpDocTextNode('nothing'),
				]),
			],
			[
				'/** @foo lorem */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@foo',
						new GenericTagValueNode('lorem')
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @foo lorem ipsum */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@foo',
						new GenericTagValueNode('lorem ipsum')
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @param Foo $foo */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Foo'),
							false,
							'$foo',
							''
						)
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @param Foo optional description */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
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
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @param Foo $foo optional description */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Foo'),
							false,
							'$foo',
							'optional description'
						)
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @param Foo ...$foo optional description */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Foo'),
							true,
							'$foo',
							'optional description'
						)
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @return Foo */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@return',
						new ReturnTagValueNode(
							new IdentifierTypeNode('Foo'),
							''
						)
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @return Foo optional description */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@return',
						new ReturnTagValueNode(
							new IdentifierTypeNode('Foo'),
							'optional description'
						)
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @return array [int] */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@return',
						new ReturnTagValueNode(
							new IdentifierTypeNode('array'),
							'[int]'
						)
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @return [int, string] */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
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
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @return A & B | C */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
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
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @return A | B & C */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
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
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @return A | B < 123 */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
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
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @var callable[] function (Configurator $sender, DI\Compiler $compiler); Occurs after the compiler is created */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
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
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @var Foo @inject */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@var',
						new VarTagValueNode(
							new IdentifierTypeNode('Foo'),
							'',
							''
						)
					),
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@inject',
						new GenericTagValueNode('')
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @var \\\\Foo $foo */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@var',
						new InvalidTagValueNode(
							'\\\\Foo $foo',
							new \PHPStan\PhpDocParser\Parser\ParserException(
								'\\\\Foo',
								Lexer::TOKEN_OTHER,
								9,
								Lexer::TOKEN_IDENTIFIER
							)
						)
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @varFoo $foo */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@varFoo',
						new GenericTagValueNode(
							'$foo'
						)
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/** @var Foo$foo */',
				new PhpDocNode([
					new PhpDocTextNode(' '),
					new PhpDocTagNode(
						'@var',
						new VarTagValueNode(
							new IdentifierTypeNode('Foo'),
							'$foo',
							''
						)
					),
					new PhpDocTextNode(' '),
				]),
			],
			[
				'/**@var(Foo)$foo#desc*/',
				new PhpDocNode([
					new PhpDocTextNode(''),
					new PhpDocTagNode(
						'@var',
						new VarTagValueNode(
							new IdentifierTypeNode('Foo'),
							'$foo',
							'#desc'
						)
					),
					new PhpDocTextNode(''),
				]),
			],
			[
				'/**
                  * @param Foo $foo 1st multi world description
                  * @param Bar $bar 2nd multi world description
                  */',
				new PhpDocNode([
					new PhpDocTextNode("\n                  * "),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Foo'),
							false,
							'$foo',
							'1st multi world description'
						)
					),
					new PhpDocTextNode("\n                  * "),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Bar'),
							false,
							'$bar',
							'2nd multi world description'
						)
					),
					new PhpDocTextNode("\n                  "),
				]),
			],
			[
				'/**
                  * @param Foo $foo 1st multi world description
                  * some text in the middle
                  * @param Bar $bar 2nd multi world description
                  */',
				new PhpDocNode([
					new PhpDocTextNode("\n                  * "),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Foo'),
							false,
							'$foo',
							'1st multi world description'
						)
					),
					new PhpDocTextNode("\n                  * some text in the middle\n                  * "),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Bar'),
							false,
							'$bar',
							'2nd multi world description'
						)
					),
					new PhpDocTextNode("\n                  "),
				]),
			],
			[
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
                  * @method int getIntegerNoParams
                  * @method void doSomethingNoParams
                  * @method self|Bar getFooOrBarNoParams
                  * @method methodWithNoReturnTypeNoParams
                  * @method static int getIntegerStaticallyNoParams
                  * @method static void doSomethingStaticallyNoParams
                  * @method static self|Bar getFooOrBarStaticallyNoParams
                  * @method static methodWithNoReturnTypeStaticallyNoParams
                  * @method int getIntegerWithDescriptionNoParams Get an integer with a description.
                  * @method void doSomethingWithDescriptionNoParams Do something with a description.
                  * @method self|Bar getFooOrBarWithDescriptionNoParams Get a Foo or a Bar with a description.
                  * @method static int getIntegerStaticallyWithDescriptionNoParams Get an integer with a description statically.
                  * @method static void doSomethingStaticallyWithDescriptionNoParams Do something with a description statically.
                  * @method static self|Bar getFooOrBarStaticallyWithDescriptionNoParams Get a Foo or a Bar with a description statically.
                  * @method static bool|string aStaticMethodThatHasAUniqueReturnTypeInThisClassNoParams
                  * @method static string|float aStaticMethodThatHasAUniqueReturnTypeInThisClassWithDescriptionNoParams A Description.
                  * @method \Aws\Result publish(array $args)
                  * @method Image rotate(float & ... $angle = array(), $backgroundColor)
                  * @method Foo overridenMethod()
                  */',
				new PhpDocNode([
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							null,
							'methodWithNoReturnTypeStatically',
							[],
							''
						)
					),
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							null,
							'methodWithNoReturnTypeStaticallyWithDescription',
							[],
							'Do something with a description statically, but what, who knows!'
						)
					),
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							null,
							'methodWithNoReturnTypeStaticallyNoParams',
							[],
							''
						)
					),
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  * "),
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
					new PhpDocTextNode("\n                  "),
				]),
			],
		];
	}

}
