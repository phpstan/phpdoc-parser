<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use Doctrine\Common\Annotations\DocParser;
use Iterator;
use PHPStan\PhpDocParser\Ast\Attribute;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayItemNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\DoctrineConstExprStringNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\PhpDoc\AssertTagMethodValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\AssertTagPropertyValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\AssertTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\DeprecatedTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineAnnotation;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineArgument;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineArray;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineArrayItem;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ImplementsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\InvalidTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueParameterNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamClosureThisTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamImmediatelyInvokedCallableTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamLaterInvokedCallableTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamOutTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PureUnlessCallableIsImpureTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\RequireExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\RequireImplementsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\SelfOutTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ThrowsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypelessParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\UsesTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeParameterNode;
use PHPStan\PhpDocParser\Ast\Type\ConditionalTypeForParameterNode;
use PHPStan\PhpDocParser\Ast\Type\ConditionalTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\InvalidTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeNode;
use PHPStan\PhpDocParser\Ast\Type\OffsetAccessTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use PHPUnit\Framework\TestCase;
use function count;
use function sprintf;
use const DIRECTORY_SEPARATOR;
use const PHP_EOL;

class PhpDocParserTest extends TestCase
{

	private Lexer $lexer;

	private PhpDocParser $phpDocParser;

	protected function setUp(): void
	{
		parent::setUp();
		$config = new ParserConfig([]);
		$this->lexer = new Lexer($config);
		$constExprParser = new ConstExprParser($config);
		$typeParser = new TypeParser($config, $constExprParser);
		$this->phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);
	}


	/**
	 * @dataProvider provideTagsWithNumbers
	 * @dataProvider provideSpecializedTags
	 * @dataProvider provideParamTagsData
	 * @dataProvider provideParamImmediatelyInvokedCallableTagsData
	 * @dataProvider provideParamLaterInvokedCallableTagsData
	 * @dataProvider provideTypelessParamTagsData
	 * @dataProvider provideParamClosureThisTagsData
	 * @dataProvider providePureUnlessCallableIsImpureTagsData
	 * @dataProvider provideVarTagsData
	 * @dataProvider provideReturnTagsData
	 * @dataProvider provideThrowsTagsData
	 * @dataProvider provideMixinTagsData
	 * @dataProvider provideRequireExtendsTagsData
	 * @dataProvider provideRequireImplementsTagsData
	 * @dataProvider provideDeprecatedTagsData
	 * @dataProvider providePropertyTagsData
	 * @dataProvider provideMethodTagsData
	 * @dataProvider provideSingleLinePhpDocData
	 * @dataProvider provideMultiLinePhpDocData
	 * @dataProvider provideTemplateTagsData
	 * @dataProvider provideExtendsTagsData
	 * @dataProvider provideTypeAliasTagsData
	 * @dataProvider provideTypeAliasImportTagsData
	 * @dataProvider provideAssertTagsData
	 * @dataProvider provideRealWorldExampleData
	 * @dataProvider provideDescriptionWithOrWithoutHtml
	 * @dataProvider provideTagsWithBackslash
	 * @dataProvider provideSelfOutTagsData
	 * @dataProvider provideParamOutTagsData
	 * @dataProvider provideDoctrineData
	 * @dataProvider provideDoctrineWithoutDoctrineCheckData
	 * @dataProvider provideCommentLikeDescriptions
	 * @dataProvider provideInlineTags
	 */
	public function testParse(
		string $label,
		string $input,
		PhpDocNode $expectedPhpDocNode
	): void
	{
		$this->executeTestParse(
			$this->phpDocParser,
			$label,
			$input,
			$expectedPhpDocNode,
		);
	}


	private function executeTestParse(PhpDocParser $phpDocParser, string $label, string $input, PhpDocNode $expectedPhpDocNode): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$actualPhpDocNode = $phpDocParser->parse($tokens);

		$this->assertEquals($expectedPhpDocNode, $actualPhpDocNode, $label);
		$this->assertSame((string) $expectedPhpDocNode, (string) $actualPhpDocNode, $label);
		$this->assertSame(Lexer::TOKEN_END, $tokens->currentTokenType(), $label);
	}


	public function provideParamTagsData(): Iterator
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
						'',
						false,
					),
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
						'optional description',
						false,
					),
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
						'',
						false,
					),
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
						'optional description',
						false,
					),
				),
			]),
		];

		yield [
			'OK reference without description',
			'/** @param Foo &$foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('Foo'),
						false,
						'$foo',
						'',
						true,
					),
				),
			]),
		];

		yield [
			'OK reference with description',
			'/** @param Foo &$foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('Foo'),
						false,
						'$foo',
						'optional description',
						true,
					),
				),
			]),
		];

		yield [
			'OK reference variadic without description',
			'/** @param Foo &...$foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('Foo'),
						true,
						'$foo',
						'',
						true,
					),
				),
			]),
		];

		yield [
			'OK reference variadic with description',
			'/** @param Foo &...$foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('Foo'),
						true,
						'$foo',
						'optional description',
						true,
					),
				),
			]),
		];

		yield [
			'OK const wildcard with description',
			'/** @param self::* $foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new ConstTypeNode(new ConstFetchNode('self', '*')),
						false,
						'$foo',
						'optional description',
						false,
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							11,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							11,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							16,
							Lexer::TOKEN_CLOSE_PARENTHESES,
							null,
							1,
						),
					),
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
						new ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							16,
							Lexer::TOKEN_CLOSE_PARENTHESES,
							null,
							1,
						),
					),
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
						new ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							19,
							Lexer::TOKEN_CLOSE_ANGLE_BRACKET,
							null,
							1,
						),
					),
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
						new ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							16,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							15,
							Lexer::TOKEN_VARIABLE,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'invalid without parameter name and description - multiline',
			'/**
			  * @param Foo
			  */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new InvalidTagValueNode(
						'Foo',
						new ParserException(
							"\n\t\t\t  ",
							Lexer::TOKEN_PHPDOC_EOL,
							21,
							Lexer::TOKEN_VARIABLE,
							null,
							2,
						),
					),
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
						new ParserException(
							'optional',
							Lexer::TOKEN_IDENTIFIER,
							15,
							Lexer::TOKEN_VARIABLE,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'Ok Wordpress @param tag',
			'/**' . PHP_EOL .
			' * @param array $parameters {' . PHP_EOL .
			' *     Optional. Parameters for filtering the list of user assignments. Default empty array.' . PHP_EOL .
			' *' . PHP_EOL .
			' *     @type bool $is_active                Pass `true` to only return active user assignments and `false` to' . PHP_EOL .
			' *                                          return  inactive user assignments.' . PHP_EOL .
			' *     @type DateTime|string $updated_since Only return user assignments that have been updated since the given' . PHP_EOL .
			' *                                          date and time.' . PHP_EOL .
			' * }' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('array'),
						false,
						'$parameters',
						'{' . PHP_EOL .
						'    Optional. Parameters for filtering the list of user assignments. Default empty array.',
						false,
					),
				),
				new PhpDocTextNode(''),
				new PhpDocTagNode('@type', new GenericTagValueNode('bool $is_active                Pass `true` to only return active user assignments and `false` to' . PHP_EOL .
					'                                         return  inactive user assignments.')),
				new PhpDocTagNode('@type', new GenericTagValueNode('DateTime|string $updated_since Only return user assignments that have been updated since the given' . PHP_EOL .
					'                                         date and time.' . PHP_EOL .
				'}')),
			]),
		];
	}

	public function provideTypelessParamTagsData(): Iterator
	{
		yield [
			'OK',
			'/** @param $foo description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new TypelessParamTagValueNode(
						false,
						'$foo',
						'description',
						false,
					),
				),
			]),
		];

		yield [
			'OK reference',
			'/** @param &$foo description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new TypelessParamTagValueNode(
						false,
						'$foo',
						'description',
						true,
					),
				),
			]),
		];

		yield [
			'OK variadic',
			'/** @param ...$foo description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new TypelessParamTagValueNode(
						true,
						'$foo',
						'description',
						false,
					),
				),
			]),
		];

		yield [
			'OK reference variadic',
			'/** @param &...$foo description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new TypelessParamTagValueNode(
						true,
						'$foo',
						'description',
						true,
					),
				),
			]),
		];

		yield [
			'OK without type and description',
			'/** @param $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new TypelessParamTagValueNode(
						false,
						'$foo',
						'',
						false,
					),
				),
			]),
		];
	}

	public function provideParamImmediatelyInvokedCallableTagsData(): Iterator
	{
		yield [
			'OK',
			'/** @param-immediately-invoked-callable $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param-immediately-invoked-callable',
					new ParamImmediatelyInvokedCallableTagValueNode(
						'$foo',
						'',
					),
				),
			]),
		];

		yield [
			'OK with description',
			'/** @param-immediately-invoked-callable $foo test two three */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param-immediately-invoked-callable',
					new ParamImmediatelyInvokedCallableTagValueNode(
						'$foo',
						'test two three',
					),
				),
			]),
		];
	}

	public function provideParamLaterInvokedCallableTagsData(): Iterator
	{
		yield [
			'OK',
			'/** @param-later-invoked-callable $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param-later-invoked-callable',
					new ParamLaterInvokedCallableTagValueNode(
						'$foo',
						'',
					),
				),
			]),
		];

		yield [
			'OK with description',
			'/** @param-later-invoked-callable $foo test two three */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param-later-invoked-callable',
					new ParamLaterInvokedCallableTagValueNode(
						'$foo',
						'test two three',
					),
				),
			]),
		];
	}

	public function provideParamClosureThisTagsData(): Iterator
	{
		yield [
			'OK',
			'/** @param-closure-this Foo $a */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param-closure-this',
					new ParamClosureThisTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$a',
						'',
					),
				),
			]),
		];

		yield [
			'OK with prefix',
			'/** @phpstan-param-closure-this Foo $a */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-param-closure-this',
					new ParamClosureThisTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$a',
						'',
					),
				),
			]),
		];

		yield [
			'OK with description',
			'/** @param-closure-this Foo $a test */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param-closure-this',
					new ParamClosureThisTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$a',
						'test',
					),
				),
			]),
		];
	}

	public function providePureUnlessCallableIsImpureTagsData(): Iterator
	{
		yield [
			'OK',
			'/** @pure-unless-callable-is-impure $foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@pure-unless-callable-is-impure',
					new PureUnlessCallableIsImpureTagValueNode(
						'$foo',
						'',
					),
				),
			]),
		];

		yield [
			'OK with description',
			'/** @pure-unless-callable-is-impure $foo test two three */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@pure-unless-callable-is-impure',
					new PureUnlessCallableIsImpureTagValueNode(
						'$foo',
						'test two three',
					),
				),
			]),
		];
	}

	public function provideVarTagsData(): Iterator
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
						'',
					),
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
						'',
					),
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
						'',
					),
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
						'optional description',
					),
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
							new IdentifierTypeNode('callable'),
						),
						'',
						'function (Configurator $sender, DI\Compiler $compiler); Occurs after the compiler is created',
					),
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
						'@inject',
					),
				),
			]),
		];

		yield [
			'OK without variable name and description in parentheses',
			'/** @var Foo (Bar) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'',
						'(Bar)',
					),
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
						'optional description',
					),
				),
			]),
		];

		yield [
			'OK without description with variable $this',
			'/** @var Foo $this */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$this',
						'',
					),
				),
			]),
		];

		yield [
			'OK without description and with no space between type and variable name with variable $this',
			'/** @var Foo$this */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$this',
						'',
					),
				),
			]),
		];

		yield [
			'OK with description with variable $this',
			'/** @var Foo $this Testing */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$this',
						'Testing',
					),
				),
			]),
		];

		yield [
			'OK with description and with no space between type and variable name with variable $this',
			'/** @var Foo$this Testing */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$this',
						'Testing',
					),
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
						'#desc',
					),
				),
			]),
		];

		yield [
			'OK with variable name and description and const expression',
			'/** @var self::* $foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new ConstTypeNode(new ConstFetchNode('self', '*')),
						'$foo',
						'optional description',
					),
				),
			]),
		];

		yield [
			'OK with no description and no trailing whitespace',
			'/** @var Foo $var*/',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'$var',
						'',
					),
				),
			]),
		];

		yield [
			'OK with no variable name and description and no trailing whitespace',
			'/** @var Foo*/',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('Foo'),
						'',
						'',
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							9,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							9,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							14,
							Lexer::TOKEN_CLOSE_PARENTHESES,
							null,
							1,
						),
					),
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
						new ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							14,
							Lexer::TOKEN_CLOSE_PARENTHESES,
							null,
							1,
						),
					),
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
						new ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							17,
							Lexer::TOKEN_CLOSE_ANGLE_BRACKET,
							null,
							1,
						),
					),
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
						new ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							14,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'invalid object shape',
			'/** @psalm-type PARTSTRUCTURE_PARAM = objecttt{attribute:string, value?:string} */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@psalm-type',
					new TypeAliasTagValueNode(
						'PARTSTRUCTURE_PARAM',
						new InvalidTypeNode(
							new ParserException(
								'{',
								Lexer::TOKEN_OPEN_CURLY_BRACKET,
								46,
								Lexer::TOKEN_PHPDOC_EOL,
								null,
								1,
							),
						),
					),
				),
			]),
		];
	}


	public function providePropertyTagsData(): Iterator
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
						'',
					),
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
						'optional description',
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							14,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							14,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							19,
							Lexer::TOKEN_CLOSE_PARENTHESES,
							null,
							1,
						),
					),
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
						new ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							19,
							Lexer::TOKEN_CLOSE_PARENTHESES,
							null,
							1,
						),
					),
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
						new ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							22,
							Lexer::TOKEN_CLOSE_ANGLE_BRACKET,
							null,
							1,
						),
					),
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
						new ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							19,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							18,
							Lexer::TOKEN_VARIABLE,
							null,
							1,
						),
					),
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
						new ParserException(
							'optional',
							Lexer::TOKEN_IDENTIFIER,
							18,
							Lexer::TOKEN_VARIABLE,
							null,
							1,
						),
					),
				),
			]),
		];
	}


	public function provideReturnTagsData(): Iterator
	{
		yield [
			'OK without description',
			'/** @return Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new IdentifierTypeNode('Foo'),
						'',
					),
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
						'optional description',
					),
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
						'[Bar]',
					),
				),
			]),
		];

		yield [
			'OK with offset access type',
			'/** @return Foo[Bar] */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new OffsetAccessTypeNode(
							new IdentifierTypeNode('Foo'),
							new IdentifierTypeNode('Bar'),
						),
						'',
					),
				),
			]),
		];

		yield [
			'OK with HTML description',
			'/** @return MongoCollection <p>Returns a collection object representing the new collection.</p> */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new IdentifierTypeNode('MongoCollection'),
						'<p>Returns a collection object representing the new collection.</p>',
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							12,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'[',
							Lexer::TOKEN_OPEN_SQUARE_BRACKET,
							12,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							18,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'|',
							Lexer::TOKEN_UNION,
							18,
							Lexer::TOKEN_OTHER,
							null,
							1,
						),
					),
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
						new ParserException(
							'&',
							Lexer::TOKEN_INTERSECTION,
							18,
							Lexer::TOKEN_OTHER,
							null,
							1,
						),
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							24,
							Lexer::TOKEN_CLOSE_ANGLE_BRACKET,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'OK with type and const expr as generic type variable',
			'/** @return A | B < 123 > */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new UnionTypeNode([
							new IdentifierTypeNode('A'),
							new GenericTypeNode(
								new IdentifierTypeNode('B'),
								[
									new ConstTypeNode(new ConstExprIntegerNode('123')),
								],
								[
									GenericTypeNode::VARIANCE_INVARIANT,
								],
							),
						]),
						'',
					),
				),
			]),
		];

		yield [
			'OK with constant wildcard and description',
			'/** @return self::* example description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new ConstTypeNode(new ConstFetchNode('self', '*')),
						'example description',
					),
				),
			]),
		];

		yield [
			'OK with conditional type',
			'/** @return (Foo is Bar ? never : int) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new ConditionalTypeNode(
							new IdentifierTypeNode('Foo'),
							new IdentifierTypeNode('Bar'),
							new IdentifierTypeNode('never'),
							new IdentifierTypeNode('int'),
							false,
						),
						'',
					),
				),
			]),
		];

		yield [
			'OK with negated conditional type',
			'/** @return (Foo is not Bar ? never : int) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new ConditionalTypeNode(
							new IdentifierTypeNode('Foo'),
							new IdentifierTypeNode('Bar'),
							new IdentifierTypeNode('never'),
							new IdentifierTypeNode('int'),
							true,
						),
						'',
					),
				),
			]),
		];

		yield [
			'OK with nested conditional type',
			'/**
			  * @return (T is self::TYPE_STRING ? string : (T is self::TYPE_INT ? int : bool))
			  */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new ConditionalTypeNode(
							new IdentifierTypeNode('T'),
							new ConstTypeNode(new ConstFetchNode('self', 'TYPE_STRING')),
							new IdentifierTypeNode('string'),
							new ConditionalTypeNode(
								new IdentifierTypeNode('T'),
								new ConstTypeNode(new ConstFetchNode('self', 'TYPE_INT')),
								new IdentifierTypeNode('int'),
								new IdentifierTypeNode('bool'),
								false,
							),
							false,
						),
						'',
					),
				),
			]),
		];

		yield [
			'invalid non-parenthesized conditional type',
			'/** @return Foo is not Bar ? never : int */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new IdentifierTypeNode('Foo'),
						'is not Bar ? never : int',
					),
				),
			]),
		];

		yield [
			'OK with conditional type for parameter',
			'/** @return ($foo is Bar ? never : int) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new ConditionalTypeForParameterNode(
							'$foo',
							new IdentifierTypeNode('Bar'),
							new IdentifierTypeNode('never'),
							new IdentifierTypeNode('int'),
							false,
						),
						'',
					),
				),
			]),
		];

		yield [
			'OK with negated conditional type for parameter',
			'/** @return ($foo is not Bar ? never : int) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new ConditionalTypeForParameterNode(
							'$foo',
							new IdentifierTypeNode('Bar'),
							new IdentifierTypeNode('never'),
							new IdentifierTypeNode('int'),
							true,
						),
						'',
					),
				),
			]),
		];

		yield [
			'OK with nested conditional type for parameter',
			'/**
			  * @return ($T is self::TYPE_STRING ? string : ($T is self::TYPE_INT ? int : bool))
			  */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new ConditionalTypeForParameterNode(
							'$T',
							new ConstTypeNode(new ConstFetchNode('self', 'TYPE_STRING')),
							new IdentifierTypeNode('string'),
							new ConditionalTypeForParameterNode(
								'$T',
								new ConstTypeNode(new ConstFetchNode('self', 'TYPE_INT')),
								new IdentifierTypeNode('int'),
								new IdentifierTypeNode('bool'),
								false,
							),
							false,
						),
						'',
					),
				),
			]),
		];

		yield [
			'invalid non-parenthesized conditional type',
			'/** @return $foo is not Bar ? never : int */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new InvalidTagValueNode(
						'$foo is not Bar ? never : int',
						new ParserException(
							'$foo',
							Lexer::TOKEN_VARIABLE,
							12,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'OK variadic callable',
			'/** @return \Closure(int ...$u, string): string */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new CallableTypeNode(
							new IdentifierTypeNode('\Closure'),
							[
								new CallableTypeParameterNode(
									new IdentifierTypeNode('int'),
									false,
									true,
									'$u',
									false,
								),
								new CallableTypeParameterNode(
									new IdentifierTypeNode('string'),
									false,
									false,
									'',
									false,
								),
							],
							new IdentifierTypeNode('string'),
							[],
						),
						'',
					),
				),
			]),
		];

		yield [
			'invalid variadic callable',
			'/** @return \Closure(...int, string): string */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new InvalidTagValueNode(
						'\Closure(...int, string): string',
						new ParserException(
							'(',
							Lexer::TOKEN_OPEN_PARENTHESES,
							20,
							Lexer::TOKEN_HORIZONTAL_WS,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'valid CallableTypeNode without space after "callable"',
			'/** @return callable(int, string): void */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new CallableTypeNode(new IdentifierTypeNode('callable'), [
							new CallableTypeParameterNode(new IdentifierTypeNode('int'), false, false, '', false),
							new CallableTypeParameterNode(new IdentifierTypeNode('string'), false, false, '', false),
						], new IdentifierTypeNode('void'), []),
						'',
					),
				),
			]),
		];

		yield [
			'valid CallableTypeNode with space after "callable"',
			'/** @return callable (int, string): void */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new CallableTypeNode(new IdentifierTypeNode('callable'), [
							new CallableTypeParameterNode(new IdentifierTypeNode('int'), false, false, '', false),
							new CallableTypeParameterNode(new IdentifierTypeNode('string'), false, false, '', false),
						], new IdentifierTypeNode('void'), []),
						'',
					),
				),
			]),
		];

		yield [
			'valid IdentifierTypeNode with space after "callable" turns the rest to description',
			'/** @return callable (int, string) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(new IdentifierTypeNode('callable'), '(int, string)'),
				),
			]),
		];

		yield [
			'invalid CallableTypeNode without space after "callable"',
			'/** @return callable(int, string) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new InvalidTagValueNode('callable(int, string)', new ParserException(
						'(',
						4,
						20,
						27,
						null,
						1,
					)),
				),
			]),
		];
	}


	public function provideThrowsTagsData(): Iterator
	{
		yield [
			'OK without description',
			'/** @throws Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@throws',
					new ThrowsTagValueNode(
						new IdentifierTypeNode('Foo'),
						'',
					),
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
						'optional description',
					),
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
						'[Bar]',
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							12,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
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
						new ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							18,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];
	}

	public function provideMixinTagsData(): Iterator
	{
		yield [
			'OK without description',
			'/** @mixin Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@mixin',
					new MixinTagValueNode(
						new IdentifierTypeNode('Foo'),
						'',
					),
				),
			]),
		];

		yield [
			'OK with description',
			'/** @mixin Foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@mixin',
					new MixinTagValueNode(
						new IdentifierTypeNode('Foo'),
						'optional description',
					),
				),
			]),
		];

		yield [
			'OK with description that starts with TOKEN_OPEN_SQUARE_BRACKET',
			'/** @mixin Foo [Bar] */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@mixin',
					new MixinTagValueNode(
						new IdentifierTypeNode('Foo'),
						'[Bar]',
					),
				),
			]),
		];

		yield [
			'invalid without type and description',
			'/** @mixin */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@mixin',
					new InvalidTagValueNode(
						'',
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							11,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'invalid with type and disallowed description start token',
			'/** @mixin Foo | #desc */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@mixin',
					new InvalidTagValueNode(
						'Foo | #desc',
						new ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							17,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'generic @mixin',
			'/** @mixin Foo<Bar> */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@mixin',
					new MixinTagValueNode(
						new GenericTypeNode(new IdentifierTypeNode('Foo'), [
							new IdentifierTypeNode('Bar'),
						], [
							GenericTypeNode::VARIANCE_INVARIANT,
						]),
						'',
					),
				),
			]),
		];
	}

	public function provideRequireExtendsTagsData(): Iterator
	{
		yield [
			'OK without description',
			'/** @phpstan-require-extends Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-require-extends',
					new RequireExtendsTagValueNode(
						new IdentifierTypeNode('Foo'),
						'',
					),
				),
			]),
		];

		yield [
			'OK with description',
			'/** @phpstan-require-extends Foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-require-extends',
					new RequireExtendsTagValueNode(
						new IdentifierTypeNode('Foo'),
						'optional description',
					),
				),
			]),
		];

		yield [
			'OK with psalm-prefix description',
			'/** @psalm-require-extends Foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@psalm-require-extends',
					new RequireExtendsTagValueNode(
						new IdentifierTypeNode('Foo'),
						'optional description',
					),
				),
			]),
		];

		yield [
			'invalid without type and description',
			'/** @phpstan-require-extends */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-require-extends',
					new InvalidTagValueNode(
						'',
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							29,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];
	}

	public function provideRequireImplementsTagsData(): Iterator
	{
		yield [
			'OK without description',
			'/** @phpstan-require-implements Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-require-implements',
					new RequireImplementsTagValueNode(
						new IdentifierTypeNode('Foo'),
						'',
					),
				),
			]),
		];

		yield [
			'OK with description',
			'/** @phpstan-require-implements Foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-require-implements',
					new RequireImplementsTagValueNode(
						new IdentifierTypeNode('Foo'),
						'optional description',
					),
				),
			]),
		];

		yield [
			'OK with psalm-prefix description',
			'/** @psalm-require-implements Foo optional description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@psalm-require-implements',
					new RequireImplementsTagValueNode(
						new IdentifierTypeNode('Foo'),
						'optional description',
					),
				),
			]),
		];

		yield [
			'invalid without type and description',
			'/** @phpstan-require-implements */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-require-implements',
					new InvalidTagValueNode(
						'',
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							32,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];
	}

	public function provideDeprecatedTagsData(): Iterator
	{
		yield [
			'OK with no description',
			'/** @deprecated */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@deprecated',
					new DeprecatedTagValueNode(''),
				),
			]),
		];

		yield [
			'OK with simple description description',
			'/** @deprecated text string */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@deprecated',
					new DeprecatedTagValueNode('text string'),
				),
			]),
		];
		yield [
			'OK with two simple description with break',
			'/** @deprecated text first
        *
        * @deprecated text second
        */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@deprecated',
					new DeprecatedTagValueNode('text first'),
				),
				new PhpDocTextNode(''),
				new PhpDocTagNode(
					'@deprecated',
					new DeprecatedTagValueNode('text second'),
				),
			]),
		];

		yield [
			'OK with two simple description without break',
			'/** @deprecated text first
        * @deprecated text second
        */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@deprecated',
					new DeprecatedTagValueNode('text first'),
				),
				new PhpDocTagNode(
					'@deprecated',
					new DeprecatedTagValueNode('text second'),
				),
			]),
		];

		yield [
			'OK with long descriptions',
			'/** @deprecated in Drupal 8.6.0 and will be removed before Drupal 9.0.0. In
			*   Drupal 9 there will be no way to set the status and in Drupal 8 this
			*   ability has been removed because mb_*() functions are supplied using
			*   Symfony\'s polyfill. */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@deprecated',
					new DeprecatedTagValueNode('in Drupal 8.6.0 and will be removed before Drupal 9.0.0. In
  Drupal 9 there will be no way to set the status and in Drupal 8 this
  ability has been removed because mb_*() functions are supplied using
  Symfony\'s polyfill.'),
				),
			]),
		];
		yield [
			'OK with multiple and long descriptions',
			'/**
      * Sample class
      *
      * @author Foo Baz <foo@baz.com>
      *
      * @deprecated in Drupal 8.6.0 and will be removed before Drupal 9.0.0. In
			*   Drupal 9 there will be no way to set the status and in Drupal 8 this
			*   ability has been removed because mb_*() functions are supplied using
			*   Symfony\'s polyfill.
			*/',
			new PhpDocNode([
				new PhpDocTextNode('Sample class'),
				new PhpDocTextNode(''),
				new PhpDocTagNode(
					'@author',
					new GenericTagValueNode('Foo Baz <foo@baz.com>'),
				),
				new PhpDocTextNode(''),
				new PhpDocTagNode(
					'@deprecated',
					new DeprecatedTagValueNode('in Drupal 8.6.0 and will be removed before Drupal 9.0.0. In
  Drupal 9 there will be no way to set the status and in Drupal 8 this
  ability has been removed because mb_*() functions are supplied using
  Symfony\'s polyfill.'),
				),
			]),
		];
	}

	public function provideMethodTagsData(): Iterator
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
						'',
						[],
					),
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
						'',
						[],
					),
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
						'',
						[],
					),
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
						'',
						[],
					),
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
						'',
						[],
					),
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
						'optional description',
						[],
					),
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
								null,
							),
						],
						'',
						[],
					),
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
								null,
							),
						],
						'',
						[],
					),
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
								null,
							),
						],
						'',
						[],
					),
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
								null,
							),
						],
						'',
						[],
					),
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
								null,
							),
						],
						'',
						[],
					),
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
								new ConstExprIntegerNode('123'),
							),
						],
						'',
						[],
					),
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
								new ConstExprIntegerNode('123'),
							),
						],
						'',
						[],
					),
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
								null,
							),
							new MethodTagValueParameterNode(
								null,
								false,
								false,
								'$b',
								null,
							),
							new MethodTagValueParameterNode(
								null,
								false,
								false,
								'$c',
								null,
							),
						],
						'',
						[],
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							16,
							Lexer::TOKEN_OPEN_PARENTHESES,
							null,
							1,
						),
					),
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
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							23,
							Lexer::TOKEN_OPEN_PARENTHESES,
							null,
							1,
						),
					),
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
						new ParserException(
							')',
							Lexer::TOKEN_CLOSE_PARENTHESES,
							17,
							Lexer::TOKEN_VARIABLE,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'OK non-static, with return type and parameter with generic type',
			'/** @method ?T randomElement<T = string>(array<array-key, T> $array = [\'a\', \'b\']) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new NullableTypeNode(new IdentifierTypeNode('T')),
						'randomElement',
						[
							new MethodTagValueParameterNode(
								new GenericTypeNode(
									new IdentifierTypeNode('array'),
									[
										new IdentifierTypeNode('array-key'),
										new IdentifierTypeNode('T'),
									],
									[
										GenericTypeNode::VARIANCE_INVARIANT,
										GenericTypeNode::VARIANCE_INVARIANT,
									],
								),
								false,
								false,
								'$array',
								new ConstExprArrayNode([
									new ConstExprArrayItemNode(
										null,
										new ConstExprStringNode('a', ConstExprStringNode::SINGLE_QUOTED),
									),
									new ConstExprArrayItemNode(
										null,
										new ConstExprStringNode('b', ConstExprStringNode::SINGLE_QUOTED),
									),
								]),
							),
						],
						'',
						[
							new TemplateTagValueNode(
								'T',
								null,
								'',
								new IdentifierTypeNode('string'),
							),
						],
					),
				),
			]),
		];

		yield [
			'OK static, with return type and multiple parameters with generic type',
			'/** @method static bool compare<T1, T2 of Bar, T3 as Baz>(T1 $t1, T2 $t2, T3 $t3) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						true,
						new IdentifierTypeNode('bool'),
						'compare',
						[
							new MethodTagValueParameterNode(
								new IdentifierTypeNode('T1'),
								false,
								false,
								'$t1',
								null,
							),
							new MethodTagValueParameterNode(
								new IdentifierTypeNode('T2'),
								false,
								false,
								'$t2',
								null,
							),
							new MethodTagValueParameterNode(
								new IdentifierTypeNode('T3'),
								false,
								false,
								'$t3',
								null,
							),
						],
						'',
						[
							new TemplateTagValueNode('T1', null, ''),
							new TemplateTagValueNode('T2', new IdentifierTypeNode('Bar'), ''),
							new TemplateTagValueNode('T3', new IdentifierTypeNode('Baz'), ''),
						],
					),
				),
			]),
		];

		yield [
			'OK non-static with return type that starts with static type',
			'/** @method static|null foo() */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@method',
					new MethodTagValueNode(
						false,
						new UnionTypeNode([
							new IdentifierTypeNode('static'),
							new IdentifierTypeNode('null'),
						]),
						'foo',
						[],
						'',
						[],
					),
				),
			]),
		];
	}


	public function provideSingleLinePhpDocData(): Iterator
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
					'/*',
				),
			]),
		];

		yield [
			'single text node',
			'/** text */',
			new PhpDocNode([
				new PhpDocTextNode(
					'text',
				),
			]),
		];

		yield [
			'single text node with tag in the middle',
			'/** text @foo bar */',
			new PhpDocNode([
				new PhpDocTextNode(
					'text @foo bar',
				),
			]),
		];

		yield [
			'single tag node without value',
			'/** @foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@foo',
					new GenericTagValueNode(''),
				),
			]),
		];

		yield [
			'single tag node with value',
			'/** @foo lorem ipsum */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@foo',
					new GenericTagValueNode('lorem ipsum'),
				),
			]),
		];

		yield [
			'single tag node with tag in the middle of value',
			'/** @foo lorem @bar ipsum */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@foo',
					new GenericTagValueNode('lorem'),
				),
				new PhpDocTagNode(
					'@bar',
					new GenericTagValueNode('ipsum'),
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
						'$foo',
					),
				),
			]),
		];

		yield [
			'@example with description starting at next line',
			'/** ' . PHP_EOL .
			' * @example' . PHP_EOL .
			' *   entity_managers:' . PHP_EOL .
			' *     default:' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@example',
					new GenericTagValueNode(PHP_EOL .
						'  entity_managers:' . PHP_EOL .
						'    default:'),
				),
			]),
		];

		yield [
			'callable with space between keyword and parameters',
			'/** @var callable (int): void */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new CallableTypeNode(
							new IdentifierTypeNode('callable'),
							[
								new CallableTypeParameterNode(new IdentifierTypeNode('int'), false, false, '', false),
							],
							new IdentifierTypeNode('void'),
							[],
						),
						'',
						'',
					),
				),
			]),
		];

		yield [
			'callable with description in parentheses',
			'/** @var callable (int) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new VarTagValueNode(
						new IdentifierTypeNode('callable'),
						'',
						'(int)',
					),
				),
			]),
		];

		yield [
			'callable with incomplete signature without return type',
			'/** @var callable(int) */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@var',
					new InvalidTagValueNode(
						'callable(int)',
						new ParserException(
							'(',
							Lexer::TOKEN_OPEN_PARENTHESES,
							17,
							Lexer::TOKEN_HORIZONTAL_WS,
							null,
							1,
						),
					),
				),
			]),
		];
	}

	/**
	 * @return iterable<array<mixed>>
	 */
	public function provideMultiLinePhpDocData(): iterable
	{
		yield from [
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
							'1st multi world description',
							false,
						),
					),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Bar'),
							false,
							'$bar',
							'2nd multi world description',
							false,
						),
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
							'1st multi world description
some text in the middle',
							false,
						),
					),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Bar'),
							false,
							'$bar',
							'2nd multi world description',
							false,
						),
					),
				]),
			],
			[
				'multi-line with two tags, text in the middle and some empty lines',
				'/**
				  *
				  *
				  * @param Foo $foo 1st multi world description with empty lines
				  *
				  *
				  * some text in the middle
				  *
				  *
				  * @param Bar $bar 2nd multi world description with empty lines
				  *
				  *
				  * test
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
							'1st multi world description with empty lines


some text in the middle',
							false,
						),
					),
					new PhpDocTextNode(''),
					new PhpDocTextNode(''),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('Bar'),
							false,
							'$bar',
							'2nd multi world description with empty lines


test',
							false,
						),
					),

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
							'@param string $bar',
							false,
						),
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
									null,
								),
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$b',
									null,
								),
							],
							'',
							[],
						),
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
									null,
								),
								new MethodTagValueParameterNode(
									null,
									false,
									false,
									'$b',
									null,
								),
							],
							'',
							[],
						),
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
							'',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							null,
							'methodWithNoReturnType',
							[],
							'',
							[],
						),
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
									null,
								),
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$b',
									null,
								),
							],
							'',
							[],
						),
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
									null,
								),
								new MethodTagValueParameterNode(
									null,
									false,
									false,
									'$b',
									null,
								),
							],
							'',
							[],
						),
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
							'',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('static'),
							'methodWithNoReturnTypeStatically',
							[],
							'',
							[],
						),
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
									null,
								),
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$b',
									null,
								),
							],
							'Get an integer with a description.',
							[],
						),
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
									null,
								),
								new MethodTagValueParameterNode(
									null,
									false,
									false,
									'$b',
									null,
								),
							],
							'Do something with a description.',
							[],
						),
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
							'Get a Foo or a Bar with a description.',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							null,
							'methodWithNoReturnTypeWithDescription',
							[],
							'Do something with a description but what, who knows!',
							[],
						),
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
									null,
								),
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('int'),
									false,
									false,
									'$b',
									null,
								),
							],
							'Get an integer with a description statically.',
							[],
						),
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
									null,
								),
								new MethodTagValueParameterNode(
									null,
									false,
									false,
									'$b',
									null,
								),
							],
							'Do something with a description statically.',
							[],
						),
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
							'Get a Foo or a Bar with a description statically.',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('static'),
							'methodWithNoReturnTypeStaticallyWithDescription',
							[],
							'Do something with a description statically, but what, who knows!',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('bool'),
							'aStaticMethodThatHasAUniqueReturnTypeInThisClass',
							[],
							'',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('string'),
							'aStaticMethodThatHasAUniqueReturnTypeInThisClassWithDescription',
							[],
							'A Description.',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('int'),
							'getIntegerNoParams',
							[],
							'',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('void'),
							'doSomethingNoParams',
							[],
							'',
							[],
						),
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
							'',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							null,
							'methodWithNoReturnTypeNoParams',
							[],
							'',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('int'),
							'getIntegerStaticallyNoParams',
							[],
							'',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('void'),
							'doSomethingStaticallyNoParams',
							[],
							'',
							[],
						),
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
							'',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('static'),
							'methodWithNoReturnTypeStaticallyNoParams',
							[],
							'',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('int'),
							'getIntegerWithDescriptionNoParams',
							[],
							'Get an integer with a description.',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('void'),
							'doSomethingWithDescriptionNoParams',
							[],
							'Do something with a description.',
							[],
						),
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
							'Get a Foo or a Bar with a description.',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('int'),
							'getIntegerStaticallyWithDescriptionNoParams',
							[],
							'Get an integer with a description statically.',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							true,
							new IdentifierTypeNode('void'),
							'doSomethingStaticallyWithDescriptionNoParams',
							[],
							'Do something with a description statically.',
							[],
						),
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
							'Get a Foo or a Bar with a description statically.',
							[],
						),
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
							'',
							[],
						),
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
							'A Description.',
							[],
						),
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
									null,
								),
							],
							'',
							[],
						),
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
									new ConstExprArrayNode([]),
								),
								new MethodTagValueParameterNode(
									null,
									false,
									false,
									'$backgroundColor',
									null,
								),
							],
							'',
							[],
						),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new IdentifierTypeNode('Foo'),
							'overridenMethod',
							[],
							'',
							[],
						),
					),
				]),
			],
			[
				'OK with template method',
				'/**
				  * @template TKey as array-key
				  * @template TValue
				  * @method TKey|null find(TValue $v) find index of $v
				  */',
				new PhpDocNode([
					new PhpDocTagNode(
						'@template',
						new TemplateTagValueNode('TKey', new IdentifierTypeNode('array-key'), ''),
					),
					new PhpDocTagNode(
						'@template',
						new TemplateTagValueNode('TValue', null, ''),
					),
					new PhpDocTagNode(
						'@method',
						new MethodTagValueNode(
							false,
							new UnionTypeNode([
								new IdentifierTypeNode('TKey'),
								new IdentifierTypeNode('null'),
							]),
							'find',
							[
								new MethodTagValueParameterNode(
									new IdentifierTypeNode('TValue'),
									false,
									false,
									'$v',
									null,
								),
							],
							'find index of $v',
							[],
						),
					),
				]),
			],
			[
				'OK with multiline conditional return type',
				'/**
				  * @template TRandKey as array-key
				  * @template TRandVal
				  * @template TRandList as array<TRandKey, TRandVal>|XIterator<TRandKey, TRandVal>|Traversable<TRandKey, TRandVal>
				  *
				  * @param TRandList $list
				  *
				  * @return (
				  *        TRandList is array ? array<TRandKey, TRandVal> : (
				  *        TRandList is XIterator ?    XIterator<TRandKey, TRandVal> :
				  *        IteratorIterator<TRandKey, TRandVal>|LimitIterator<TRandKey, TRandVal>
				  * ))
				  */',
				new PhpDocNode([
					new PhpDocTagNode(
						'@template',
						new TemplateTagValueNode('TRandKey', new IdentifierTypeNode('array-key'), ''),
					),
					new PhpDocTagNode(
						'@template',
						new TemplateTagValueNode('TRandVal', null, ''),
					),
					new PhpDocTagNode(
						'@template',
						new TemplateTagValueNode(
							'TRandList',
							new UnionTypeNode([
								new GenericTypeNode(
									new IdentifierTypeNode('array'),
									[
										new IdentifierTypeNode('TRandKey'),
										new IdentifierTypeNode('TRandVal'),
									],
									[
										GenericTypeNode::VARIANCE_INVARIANT,
										GenericTypeNode::VARIANCE_INVARIANT,
									],
								),
								new GenericTypeNode(
									new IdentifierTypeNode('XIterator'),
									[
										new IdentifierTypeNode('TRandKey'),
										new IdentifierTypeNode('TRandVal'),
									],
									[
										GenericTypeNode::VARIANCE_INVARIANT,
										GenericTypeNode::VARIANCE_INVARIANT,
									],
								),
								new GenericTypeNode(
									new IdentifierTypeNode('Traversable'),
									[
										new IdentifierTypeNode('TRandKey'),
										new IdentifierTypeNode('TRandVal'),
									],
									[
										GenericTypeNode::VARIANCE_INVARIANT,
										GenericTypeNode::VARIANCE_INVARIANT,
									],
								),
							]),
							'',
						),
					),
					new PhpDocTextNode(''),
					new PhpDocTagNode(
						'@param',
						new ParamTagValueNode(
							new IdentifierTypeNode('TRandList'),
							false,
							'$list',
							'',
							false,
						),
					),
					new PhpDocTextNode(''),
					new PhpDocTagNode(
						'@return',
						new ReturnTagValueNode(
							new ConditionalTypeNode(
								new IdentifierTypeNode('TRandList'),
								new IdentifierTypeNode('array'),
								new GenericTypeNode(
									new IdentifierTypeNode('array'),
									[
										new IdentifierTypeNode('TRandKey'),
										new IdentifierTypeNode('TRandVal'),
									],
									[
										GenericTypeNode::VARIANCE_INVARIANT,
										GenericTypeNode::VARIANCE_INVARIANT,
									],
								),
								new ConditionalTypeNode(
									new IdentifierTypeNode('TRandList'),
									new IdentifierTypeNode('XIterator'),
									new GenericTypeNode(
										new IdentifierTypeNode('XIterator'),
										[
											new IdentifierTypeNode('TRandKey'),
											new IdentifierTypeNode('TRandVal'),
										],
										[
											GenericTypeNode::VARIANCE_INVARIANT,
											GenericTypeNode::VARIANCE_INVARIANT,
										],
									),
									new UnionTypeNode([
										new GenericTypeNode(
											new IdentifierTypeNode('IteratorIterator'),
											[
												new IdentifierTypeNode('TRandKey'),
												new IdentifierTypeNode('TRandVal'),
											],
											[
												GenericTypeNode::VARIANCE_INVARIANT,
												GenericTypeNode::VARIANCE_INVARIANT,
											],
										),
										new GenericTypeNode(
											new IdentifierTypeNode('LimitIterator'),
											[
												new IdentifierTypeNode('TRandKey'),
												new IdentifierTypeNode('TRandVal'),
											],
											[
												GenericTypeNode::VARIANCE_INVARIANT,
												GenericTypeNode::VARIANCE_INVARIANT,
											],
										),
									]),
									false,
								),
								false,
							),
							'',
						),
					),
				]),
			],
		];

		yield [
			'Empty lines before end',
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a' . PHP_EOL .
			' *' . PHP_EOL .
			' *' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', '', false)),
				new PhpDocTextNode(''),
				new PhpDocTextNode(''),
			]),
		];

		yield [
			'Empty lines before end 2',
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a' . PHP_EOL .
			' *' . PHP_EOL .
			' *' . PHP_EOL .
			' * test' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('int'),
						false,
						'$a',
						PHP_EOL
						. PHP_EOL
						. PHP_EOL
						. 'test',
						false,
					),
				),
			]),
		];

		yield [
			'Real-world test case multiline PHPDoc',
			'/**' . PHP_EOL .
			' *' . PHP_EOL .
			' * MultiLine' . PHP_EOL .
			' * description' . PHP_EOL .
			' * @param bool $a' . PHP_EOL .
			' *' . PHP_EOL .
			' * @return void' . PHP_EOL .
			' *' . PHP_EOL .
			' * @throws \Exception' . PHP_EOL .
			' *' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode(
					PHP_EOL .
					'MultiLine' . PHP_EOL .
					'description',
				),
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new IdentifierTypeNode('bool'),
					false,
					'$a',
					'',
					false,
				)),
				new PhpDocTextNode(''),
				new PhpDocTagNode('@return', new ReturnTagValueNode(
					new IdentifierTypeNode('void'),
					'',
				)),
				new PhpDocTextNode(''),
				new PhpDocTagNode('@throws', new ThrowsTagValueNode(
					new IdentifierTypeNode('\Exception'),
					'',
				)),
				new PhpDocTextNode(''),
			]),
		];

		yield [
			'Multiline PHPDoc with new line across generic type declaration',
			'/**' . PHP_EOL .
			' * @param array<string, array{' . PHP_EOL .
			' *     foo: int,' . PHP_EOL .
			' *     bar?: array<string,' . PHP_EOL .
			' *         array{foo1: int, bar1?: true}' . PHP_EOL .
			' *         | array{foo1: int, foo2: true, bar1?: true}' . PHP_EOL .
			' *     >,' . PHP_EOL .
			' * }> $a' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new GenericTypeNode(
						new IdentifierTypeNode('array'),
						[
							new IdentifierTypeNode('string'),
							ArrayShapeNode::createSealed([
								new ArrayShapeItemNode(new IdentifierTypeNode('foo'), false, new IdentifierTypeNode('int')),
								new ArrayShapeItemNode(new IdentifierTypeNode('bar'), true, new GenericTypeNode(
									new IdentifierTypeNode('array'),
									[
										new IdentifierTypeNode('string'),
										new UnionTypeNode([
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo1'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar1'), true, new IdentifierTypeNode('true')),
											]),
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo1'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('foo2'), false, new IdentifierTypeNode('true')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar1'), true, new IdentifierTypeNode('true')),
											]),
										]),
									],
									[
										GenericTypeNode::VARIANCE_INVARIANT,
										GenericTypeNode::VARIANCE_INVARIANT,
									],
								)),
							]),
						],
						[
							GenericTypeNode::VARIANCE_INVARIANT,
							GenericTypeNode::VARIANCE_INVARIANT,
						],
					),
					false,
					'$a',
					'',
					false,
				)),
			]),
		];

		yield [
			'Multiline PHPDoc with new line within type declaration',
			'/**' . PHP_EOL .
			' * @param array<string, array{' . PHP_EOL .
			' *     foo: int,' . PHP_EOL .
			' * ' . PHP_EOL .
			' * ' . PHP_EOL .
			' *     bar?: array<string,' . PHP_EOL .
			' * ' . PHP_EOL .
			' *         array{foo1: int, bar1?: true}' . PHP_EOL .
			' * ' . PHP_EOL .
			' *         | array{foo1: int, foo2: true, bar1?: true}' . PHP_EOL .
			' *     >,' . PHP_EOL .
			' * }> $a' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new GenericTypeNode(
						new IdentifierTypeNode('array'),
						[
							new IdentifierTypeNode('string'),
							ArrayShapeNode::createSealed([
								new ArrayShapeItemNode(new IdentifierTypeNode('foo'), false, new IdentifierTypeNode('int')),
								new ArrayShapeItemNode(new IdentifierTypeNode('bar'), true, new GenericTypeNode(
									new IdentifierTypeNode('array'),
									[
										new IdentifierTypeNode('string'),
										new UnionTypeNode([
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo1'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar1'), true, new IdentifierTypeNode('true')),
											]),
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo1'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('foo2'), false, new IdentifierTypeNode('true')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar1'), true, new IdentifierTypeNode('true')),
											]),
										]),
									],
									[
										GenericTypeNode::VARIANCE_INVARIANT,
										GenericTypeNode::VARIANCE_INVARIANT,
									],
								)),
							]),
						],
						[
							GenericTypeNode::VARIANCE_INVARIANT,
							GenericTypeNode::VARIANCE_INVARIANT,
						],
					),
					false,
					'$a',
					'',
					false,
				)),
			]),
		];

		yield [
			'Multiline PHPDoc with new line within type declaration including usage of braces',
			'/**' . PHP_EOL .
			' * @phpstan-type FactoriesConfigurationType = array<' . PHP_EOL .
			' *      string,' . PHP_EOL .
			' *      (class-string<Factory\FactoryInterface>|Factory\FactoryInterface)' . PHP_EOL .
			' *      |callable(ContainerInterface,?string,array<mixed>|null):object' . PHP_EOL .
			' * >' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@phpstan-type', new TypeAliasTagValueNode(
					'FactoriesConfigurationType',
					new GenericTypeNode(
						new IdentifierTypeNode('array'),
						[
							new IdentifierTypeNode('string'),
							new UnionTypeNode([
								new UnionTypeNode([
									new GenericTypeNode(
										new IdentifierTypeNode('class-string'),
										[new IdentifierTypeNode('Factory\\FactoryInterface')],
										[GenericTypeNode::VARIANCE_INVARIANT],
									),
									new IdentifierTypeNode('Factory\\FactoryInterface'),
								]),
								new CallableTypeNode(
									new IdentifierTypeNode('callable'),
									[
										new CallableTypeParameterNode(new IdentifierTypeNode('ContainerInterface'), false, false, '', false),
										new CallableTypeParameterNode(
											new NullableTypeNode(
												new IdentifierTypeNode('string'),
											),
											false,
											false,
											'',
											false,
										),
										new CallableTypeParameterNode(
											new UnionTypeNode([
												new GenericTypeNode(
													new IdentifierTypeNode('array'),
													[new IdentifierTypeNode('mixed')],
													[GenericTypeNode::VARIANCE_INVARIANT],
												),
												new IdentifierTypeNode('null'),
											]),
											false,
											false,
											'',
											false,
										),
									],
									new IdentifierTypeNode('object'),
									[],
								),
							]),
						],
						[
							GenericTypeNode::VARIANCE_INVARIANT,
							GenericTypeNode::VARIANCE_INVARIANT,
						],
					),
				)),
			]),
		];

		yield [
			'Multiline PHPDoc with multiple new line within union type declaration',
			'/**' . PHP_EOL .
			' * @param array<string, array{' . PHP_EOL .
			' *     foo: int,' . PHP_EOL .
			' *     bar?: array<string,' . PHP_EOL .
			' *         array{foo1: int, bar1?: true}' . PHP_EOL .
			' *         | array{foo1: int, foo2: true, bar1?: true}' . PHP_EOL .
			' *         | array{foo2: int, foo3: bool, bar2?: true}' . PHP_EOL .
			' *         | array{foo1: int, foo3: true, bar3?: false}' . PHP_EOL .
			' *     >,' . PHP_EOL .
			' * }> $a' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new GenericTypeNode(
						new IdentifierTypeNode('array'),
						[
							new IdentifierTypeNode('string'),
							ArrayShapeNode::createSealed([
								new ArrayShapeItemNode(new IdentifierTypeNode('foo'), false, new IdentifierTypeNode('int')),
								new ArrayShapeItemNode(new IdentifierTypeNode('bar'), true, new GenericTypeNode(
									new IdentifierTypeNode('array'),
									[
										new IdentifierTypeNode('string'),
										new UnionTypeNode([
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo1'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar1'), true, new IdentifierTypeNode('true')),
											]),
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo1'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('foo2'), false, new IdentifierTypeNode('true')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar1'), true, new IdentifierTypeNode('true')),
											]),
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo2'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('foo3'), false, new IdentifierTypeNode('bool')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar2'), true, new IdentifierTypeNode('true')),
											]),
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo1'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('foo3'), false, new IdentifierTypeNode('true')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar3'), true, new IdentifierTypeNode('false')),
											]),
										]),
									],
									[
										GenericTypeNode::VARIANCE_INVARIANT,
										GenericTypeNode::VARIANCE_INVARIANT,
									],
								)),
							]),
						],
						[
							GenericTypeNode::VARIANCE_INVARIANT,
							GenericTypeNode::VARIANCE_INVARIANT,
						],
					),
					false,
					'$a',
					'',
					false,
				)),
			]),
		];

		yield [
			'Multiline PHPDoc with multiple new line within intersection type declaration',
			'/**' . PHP_EOL .
			' * @param array<string, array{' . PHP_EOL .
			' *     foo: int,' . PHP_EOL .
			' *     bar?: array<string,' . PHP_EOL .
			' *         array{foo1: int, bar1?: true}' . PHP_EOL .
			' *         & array{foo1: int, foo2: true, bar1?: true}' . PHP_EOL .
			' *         & array{foo2: int, foo3: bool, bar2?: true}' . PHP_EOL .
			' *         & array{foo1: int, foo3: true, bar3?: false}' . PHP_EOL .
			' *     >,' . PHP_EOL .
			' * }> $a' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new GenericTypeNode(
						new IdentifierTypeNode('array'),
						[
							new IdentifierTypeNode('string'),
							ArrayShapeNode::createSealed([
								new ArrayShapeItemNode(new IdentifierTypeNode('foo'), false, new IdentifierTypeNode('int')),
								new ArrayShapeItemNode(new IdentifierTypeNode('bar'), true, new GenericTypeNode(
									new IdentifierTypeNode('array'),
									[
										new IdentifierTypeNode('string'),
										new IntersectionTypeNode([
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo1'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar1'), true, new IdentifierTypeNode('true')),
											]),
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo1'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('foo2'), false, new IdentifierTypeNode('true')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar1'), true, new IdentifierTypeNode('true')),
											]),
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo2'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('foo3'), false, new IdentifierTypeNode('bool')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar2'), true, new IdentifierTypeNode('true')),
											]),
											ArrayShapeNode::createSealed([
												new ArrayShapeItemNode(new IdentifierTypeNode('foo1'), false, new IdentifierTypeNode('int')),
												new ArrayShapeItemNode(new IdentifierTypeNode('foo3'), false, new IdentifierTypeNode('true')),
												new ArrayShapeItemNode(new IdentifierTypeNode('bar3'), true, new IdentifierTypeNode('false')),
											]),
										]),
									],
									[
										GenericTypeNode::VARIANCE_INVARIANT,
										GenericTypeNode::VARIANCE_INVARIANT,
									],
								)),
							]),
						],
						[
							GenericTypeNode::VARIANCE_INVARIANT,
							GenericTypeNode::VARIANCE_INVARIANT,
						],
					),
					false,
					'$a',
					'',
					false,
				)),
			]),
		];

		yield [
			'Multiline PHPDoc with multiple new line being invalid due to union and intersection type declaration',
			'/**' . PHP_EOL .
			' * @param array<string, array{' . PHP_EOL .
			' *     foo: int,' . PHP_EOL .
			' *     bar?: array<string,' . PHP_EOL .
			' *         array{foo1: int, bar1?: true}' . PHP_EOL .
			' *         & array{foo1: int, foo2: true, bar1?: true}' . PHP_EOL .
			' *         | array{foo2: int, foo3: bool, bar2?: true}' . PHP_EOL .
			' *         & array{foo1: int, foo3: true, bar3?: false}' . PHP_EOL .
			' *     >,' . PHP_EOL .
			' * }> $a' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new InvalidTagValueNode(
					'array<string, array{' . PHP_EOL .
					'    foo: int,' . PHP_EOL .
					'    bar?: array<string,' . PHP_EOL .
					'        array{foo1: int, bar1?: true}' . PHP_EOL .
					'        & array{foo1: int, foo2: true, bar1?: true}' . PHP_EOL .
					'        | array{foo2: int, foo3: bool, bar2?: true}' . PHP_EOL .
					'        & array{foo1: int, foo3: true, bar3?: false}' . PHP_EOL .
					'    >,' . PHP_EOL .
					'}> $a',
					new ParserException(
						'?',
						Lexer::TOKEN_NULLABLE,
						DIRECTORY_SEPARATOR === '\\' ? 65 : 62,
						Lexer::TOKEN_CLOSE_CURLY_BRACKET,
						null,
						4,
					),
				)),
			]),
		];

		/**
		 * @return object{
		 *   a: int,
		 *
		 *   b: int,
		 * }
		 */

		yield [
			'Multiline PHPDoc with new line within object type declaration',
			'/**' . PHP_EOL .
			' * @return object{' . PHP_EOL .
			' *   a: int,' . PHP_EOL .
			' *' . PHP_EOL .
			' *   b: int,' . PHP_EOL .
			' * }' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new ObjectShapeNode(
							[
								new ObjectShapeItemNode(
									new IdentifierTypeNode('a'),
									false,
									new IdentifierTypeNode('int'),
								),
								new ObjectShapeItemNode(
									new IdentifierTypeNode('b'),
									false,
									new IdentifierTypeNode('int'),
								),
							],
						),
						'',
					),
				),
			]),
		];
	}

	public function provideTemplateTagsData(): Iterator
	{
		yield [
			'OK without bound and description',
			'/** @template T */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new TemplateTagValueNode(
						'T',
						null,
						'',
					),
				),
			]),
		];

		yield [
			'OK without bound',
			'/** @template T the value type*/',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new TemplateTagValueNode(
						'T',
						null,
						'the value type',
					),
				),
			]),
		];

		yield [
			'OK without description',
			'/** @template T of DateTime */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new TemplateTagValueNode(
						'T',
						new IdentifierTypeNode('DateTime'),
						'',
					),
				),
			]),
		];

		yield [
			'OK without description',
			'/** @template T as DateTime */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new TemplateTagValueNode(
						'T',
						new IdentifierTypeNode('DateTime'),
						'',
					),
				),
			]),
		];

		yield [
			'OK with upper bound and description',
			'/** @template T of DateTime the value type */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new TemplateTagValueNode(
						'T',
						new IdentifierTypeNode('DateTime'),
						'the value type',
					),
				),
			]),
		];

		yield [
			'OK with lower bound and description',
			'/** @template T super DateTimeImmutable the value type */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new TemplateTagValueNode(
						'T',
						null,
						'the value type',
						null,
						new IdentifierTypeNode('DateTimeImmutable'),
					),
				),
			]),
		];

		yield [
			'OK with both bounds and description',
			'/** @template T of DateTimeInterface super DateTimeImmutable the value type */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new TemplateTagValueNode(
						'T',
						new IdentifierTypeNode('DateTimeInterface'),
						'the value type',
						null,
						new IdentifierTypeNode('DateTimeImmutable'),
					),
				),
			]),
		];

		yield [
			'invalid without bounds and description',
			'/** @template */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new InvalidTagValueNode(
						'',
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							14,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'invalid without bound and with description',
			'/** @template #desc */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new InvalidTagValueNode(
						'#desc',
						new ParserException(
							'#desc',
							Lexer::TOKEN_OTHER,
							14,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'OK with covariance',
			'/** @template-covariant T */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template-covariant',
					new TemplateTagValueNode(
						'T',
						null,
						'',
					),
				),
			]),
		];

		yield [
			'OK with contravariance',
			'/** @template-contravariant T */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template-contravariant',
					new TemplateTagValueNode(
						'T',
						null,
						'',
					),
				),
			]),
		];

		yield [
			'OK with default',
			'/** @template T = string */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new TemplateTagValueNode(
						'T',
						null,
						'',
						new IdentifierTypeNode('string'),
					),
				),
			]),
		];

		yield [
			'OK with default and description',
			'/** @template T = string the value type */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new TemplateTagValueNode(
						'T',
						null,
						'the value type',
						new IdentifierTypeNode('string'),
					),
				),
			]),
		];

		yield [
			'OK with bound and default and description',
			'/** @template T of string = \'\' the value type */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@template',
					new TemplateTagValueNode(
						'T',
						new IdentifierTypeNode('string'),
						'the value type',
						new ConstTypeNode(new ConstExprStringNode('', ConstExprStringNode::SINGLE_QUOTED)),
					),
				),
			]),
		];
	}

	public function provideExtendsTagsData(): Iterator
	{
		yield [
			'OK with one argument',
			'/** @extends Foo<A> */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@extends',
					new ExtendsTagValueNode(
						new GenericTypeNode(
							new IdentifierTypeNode('Foo'),
							[
								new IdentifierTypeNode('A'),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
							],
						),
						'',
					),
				),
			]),
		];

		yield [
			'OK with two arguments',
			'/** @extends Foo<A,B> */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@extends',
					new ExtendsTagValueNode(
						new GenericTypeNode(
							new IdentifierTypeNode('Foo'),
							[
								new IdentifierTypeNode('A'),
								new IdentifierTypeNode('B'),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
								GenericTypeNode::VARIANCE_INVARIANT,
							],
						),
						'',
					),
				),
			]),
		];

		yield [
			'OK @implements',
			'/** @implements Foo<A,B> */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@implements',
					new ImplementsTagValueNode(
						new GenericTypeNode(
							new IdentifierTypeNode('Foo'),
							[
								new IdentifierTypeNode('A'),
								new IdentifierTypeNode('B'),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
								GenericTypeNode::VARIANCE_INVARIANT,
							],
						),
						'',
					),
				),
			]),
		];

		yield [
			'OK @use',
			'/** @use Foo<A,B> */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@use',
					new UsesTagValueNode(
						new GenericTypeNode(
							new IdentifierTypeNode('Foo'),
							[
								new IdentifierTypeNode('A'),
								new IdentifierTypeNode('B'),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
								GenericTypeNode::VARIANCE_INVARIANT,
							],
						),
						'',
					),
				),
			]),
		];

		yield [
			'OK with description',
			'/** @extends Foo<A> extends foo*/',
			new PhpDocNode([
				new PhpDocTagNode(
					'@extends',
					new ExtendsTagValueNode(
						new GenericTypeNode(
							new IdentifierTypeNode('Foo'),
							[new IdentifierTypeNode('A')],
							[GenericTypeNode::VARIANCE_INVARIANT],
						),
						'extends foo',
					),
				),
			]),
		];

		yield [
			'invalid without type',
			'/** @extends */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@extends',
					new InvalidTagValueNode(
						'',
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							13,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'invalid without arguments',
			'/** @extends Foo */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@extends',
					new InvalidTagValueNode(
						'Foo',
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							17,
							Lexer::TOKEN_OPEN_ANGLE_BRACKET,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'class-string in @return',
			'/** @return class-string */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new IdentifierTypeNode('class-string'),
						'',
					),
				),
			]),
		];

		yield [
			'class-string in @return with description',
			'/** @return class-string some description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new IdentifierTypeNode('class-string'),
						'some description',
					),
				),
			]),
		];

		yield [
			'class-string in @param',
			'/** @param class-string $test */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('class-string'),
						false,
						'$test',
						'',
						false,
					),
				),
			]),
		];

		yield [
			'class-string in @param with description',
			'/** @param class-string $test some description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('class-string'),
						false,
						'$test',
						'some description',
						false,
					),
				),
			]),
		];
	}

	public function provideTypeAliasTagsData(): Iterator
	{
		yield [
			'OK',
			'/** @phpstan-type TypeAlias string|int */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-type',
					new TypeAliasTagValueNode(
						'TypeAlias',
						new UnionTypeNode([
							new IdentifierTypeNode('string'),
							new IdentifierTypeNode('int'),
						]),
					),
				),
			]),
		];

		yield [
			'OK with psalm syntax',
			'/** @psalm-type TypeAlias=string|int */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@psalm-type',
					new TypeAliasTagValueNode(
						'TypeAlias',
						new UnionTypeNode([
							new IdentifierTypeNode('string'),
							new IdentifierTypeNode('int'),
						]),
					),
				),
			]),
		];

		yield [
			'invalid without type',
			'/** @phpstan-type TypeAlias */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-type',
					new TypeAliasTagValueNode(
						'TypeAlias',
						new InvalidTypeNode(new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							28,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						)),
					),
				),
			]),
		];

		yield [
			'invalid without type with newline',
			'/**
			  * @phpstan-type TypeAlias
			  */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-type',
					new TypeAliasTagValueNode(
						'TypeAlias',
						new InvalidTypeNode(new ParserException(
							"\n\t\t\t  ",
							Lexer::TOKEN_PHPDOC_EOL,
							34,
							Lexer::TOKEN_IDENTIFIER,
							null,
							2,
						)),
					),
				),
			]),
		];

		yield [
			'invalid without type but valid tag below',
			'/**
			  * @phpstan-type TypeAlias
			  * @mixin T
			  */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-type',
					new TypeAliasTagValueNode(
						'TypeAlias',
						new InvalidTypeNode(new ParserException(
							"\n\t\t\t  * ",
							Lexer::TOKEN_PHPDOC_EOL,
							34,
							Lexer::TOKEN_IDENTIFIER,
							null,
							2,
						)),
					),
				),
				new PhpDocTagNode(
					'@mixin',
					new MixinTagValueNode(
						new IdentifierTypeNode('T'),
						'',
					),
				),
			]),
		];

		yield [
			'invalid type that should be an error',
			'/**
 * @phpstan-type Foo array{}
 * @phpstan-type InvalidFoo what{}
 */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-type',
					new TypeAliasTagValueNode(
						'Foo',
						ArrayShapeNode::createSealed([]),
					),
				),
				new PhpDocTagNode(
					'@phpstan-type',
					new TypeAliasTagValueNode(
						'InvalidFoo',
						new InvalidTypeNode(new ParserException(
							'{',
							Lexer::TOKEN_OPEN_CURLY_BRACKET,
							65,
							Lexer::TOKEN_PHPDOC_EOL,
							null,
							3,
						)),
					),
				),
			]),
		];

		yield [
			'invalid type that should be an error followed by valid again',
			'/**
 * @phpstan-type Foo array{}
 * @phpstan-type InvalidFoo what{}
 * @phpstan-type Bar array{}
 */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-type',
					new TypeAliasTagValueNode(
						'Foo',
						ArrayShapeNode::createSealed([]),
					),
				),
				new PhpDocTagNode(
					'@phpstan-type',
					new TypeAliasTagValueNode(
						'InvalidFoo',
						new InvalidTypeNode(new ParserException(
							'{',
							Lexer::TOKEN_OPEN_CURLY_BRACKET,
							65,
							Lexer::TOKEN_PHPDOC_EOL,
							null,
							3,
						)),
					),
				),
				new PhpDocTagNode(
					'@phpstan-type',
					new TypeAliasTagValueNode(
						'Bar',
						ArrayShapeNode::createSealed([]),
					),
				),
			]),
		];

		yield [
			'invalid empty',
			'/** @phpstan-type */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-type',
					new InvalidTagValueNode(
						'',
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							18,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];
	}

	public function provideTypeAliasImportTagsData(): Iterator
	{
		yield [
			'OK',
			'/** @phpstan-import-type TypeAlias from AnotherClass */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-import-type',
					new TypeAliasImportTagValueNode(
						'TypeAlias',
						new IdentifierTypeNode('AnotherClass'),
						null,
					),
				),
			]),
		];

		yield [
			'OK with alias',
			'/** @phpstan-import-type TypeAlias from AnotherClass as DifferentAlias */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-import-type',
					new TypeAliasImportTagValueNode(
						'TypeAlias',
						new IdentifierTypeNode('AnotherClass'),
						'DifferentAlias',
					),
				),
			]),
		];

		yield [
			'invalid non-identifier from',
			'/** @phpstan-import-type TypeAlias from 42 */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-import-type',
					new InvalidTagValueNode(
						'TypeAlias from 42',
						new ParserException(
							'42',
							Lexer::TOKEN_INTEGER,
							40,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'invalid non-simple-identifier from',
			'/** @phpstan-import-type TypeAlias from AnotherClass[] */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-import-type',
					new InvalidTagValueNode(
						'Unexpected token "[", expected \'*/\' at offset 52 on line 1',
						new ParserException(
							'[',
							Lexer::TOKEN_OPEN_SQUARE_BRACKET,
							52,
							Lexer::TOKEN_CLOSE_PHPDOC,
							null,
							1,
						),
					),
				),
			]),
		];

		yield [
			'invalid missing from',
			'/** @phpstan-import-type TypeAlias */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-import-type',
					new InvalidTagValueNode(
						'TypeAlias',
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							35,
							Lexer::TOKEN_IDENTIFIER,
							'from',
							1,
						),
					),
				),
			]),
		];

		yield [
			'invalid missing from with alias',
			'/** @phpstan-import-type TypeAlias as DifferentAlias */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-import-type',
					new InvalidTagValueNode(
						'TypeAlias as DifferentAlias',
						new ParserException(
							'as',
							Lexer::TOKEN_IDENTIFIER,
							35,
							Lexer::TOKEN_IDENTIFIER,
							'from',
							1,
						),
					),
				),
			]),
		];

		yield [
			'invalid empty',
			'/** @phpstan-import-type */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-import-type',
					new InvalidTagValueNode(
						'',
						new ParserException(
							'*/',
							Lexer::TOKEN_CLOSE_PHPDOC,
							25,
							Lexer::TOKEN_IDENTIFIER,
							null,
							1,
						),
					),
				),
			]),
		];
	}

	public function provideAssertTagsData(): Iterator
	{
		yield [
			'OK',
			'/** @phpstan-assert Type $var */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagValueNode(
						new IdentifierTypeNode('Type'),
						'$var',
						false,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK with psalm syntax',
			'/** @psalm-assert Type $var */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@psalm-assert',
					new AssertTagValueNode(
						new IdentifierTypeNode('Type'),
						'$var',
						false,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK with description',
			'/** @phpstan-assert Type $var assert Type to $var */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagValueNode(
						new IdentifierTypeNode('Type'),
						'$var',
						false,
						'assert Type to $var',
						false,
					),
				),
			]),
		];

		yield [
			'OK with union type',
			'/** @phpstan-assert Type|Other $var */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagValueNode(
						new UnionTypeNode([
							new IdentifierTypeNode('Type'),
							new IdentifierTypeNode('Other'),
						]),
						'$var',
						false,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK $var->method()',
			'/** @phpstan-assert Type $var->method() */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagMethodValueNode(
						new IdentifierTypeNode('Type'),
						'$var',
						'method',
						false,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK $var->property',
			'/** @phpstan-assert Type $var->property */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagPropertyValueNode(
						new IdentifierTypeNode('Type'),
						'$var',
						'property',
						false,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK $this',
			'/** @phpstan-assert Type $this */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagValueNode(
						new IdentifierTypeNode('Type'),
						'$this',
						false,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK $this with description',
			'/** @phpstan-assert Type $this assert Type to $this */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagValueNode(
						new IdentifierTypeNode('Type'),
						'$this',
						false,
						'assert Type to $this',
						false,
					),
				),
			]),
		];

		yield [
			'OK $this with generic type',
			'/** @phpstan-assert GenericType<T> $this */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagValueNode(
						new GenericTypeNode(
							new IdentifierTypeNode('GenericType'),
							[
								new IdentifierTypeNode('T'),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
							],
						),
						'$this',
						false,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK $this->method()',
			'/** @phpstan-assert Type $this->method() */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagMethodValueNode(
						new IdentifierTypeNode('Type'),
						'$this',
						'method',
						false,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK $this->property',
			'/** @phpstan-assert Type $this->property */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagPropertyValueNode(
						new IdentifierTypeNode('Type'),
						'$this',
						'property',
						false,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK assert-if-true',
			'/** @phpstan-assert-if-true Type $var */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert-if-true',
					new AssertTagValueNode(
						new IdentifierTypeNode('Type'),
						'$var',
						false,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK assert-if-false',
			'/** @phpstan-assert-if-false Type $var */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert-if-false',
					new AssertTagValueNode(
						new IdentifierTypeNode('Type'),
						'$var',
						false,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK negated',
			'/** @phpstan-assert !Type $var */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagValueNode(
						new IdentifierTypeNode('Type'),
						'$var',
						true,
						'',
						false,
					),
				),
			]),
		];

		yield [
			'OK equality',
			'/** @phpstan-assert =Type $var */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagValueNode(
						new IdentifierTypeNode('Type'),
						'$var',
						false,
						'',
						true,
					),
				),
			]),
		];

		yield [
			'OK negated equality',
			'/** @phpstan-assert !=Type $var */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-assert',
					new AssertTagValueNode(
						new IdentifierTypeNode('Type'),
						'$var',
						true,
						'',
						true,
					),
				),
			]),
		];
	}

	public function providerDebug(): Iterator
	{
		$sample = '/**
			 * Returns the schema for the field.
			 *
			 * This method is static because the field schema information is needed on
			 * creation of the field. FieldItemInterface objects instantiated at that
			 * time are not reliable as field settings might be missing.
			 *
			 * Computed fields having no schema should return an empty array.
			 */';
		yield [
			'OK class line',
			$sample,
			new PhpDocNode([
				new PhpDocTextNode('Returns the schema for the field.

This method is static because the field schema information is needed on
creation of the field. FieldItemInterface objects instantiated at that
time are not reliable as field settings might be missing.

Computed fields having no schema should return an empty array.'),
			]),
		];
	}

	public function provideRealWorldExampleData(): Iterator
	{
			$sample = "/**
			 * Returns the schema for the field.
			 *
			 * This method is static because the field schema information is needed on
			 * creation of the field. FieldItemInterface objects instantiated at that
			 * time are not reliable as field settings might be missing.
			 *
			 * Computed fields having no schema should return an empty array.
			 *
			 * @param \Drupal\Core\Field\FieldStorageDefinitionInterface \$field_definition
			 *   The field definition.
			 *
			 * @return array
			 *   An empty array if there is no schema, or an associative array with the
			 *   following key/value pairs:
			 *   - columns: An array of Schema API column specifications, keyed by column
			 *     name. The columns need to be a subset of the properties defined in
			 *     propertyDefinitions(). The 'not null' property is ignored if present,
			 *     as it is determined automatically by the storage controller depending
			 *     on the table layout and the property definitions. It is recommended to
			 *     avoid having the column definitions depend on field settings when
			 *     possible. No assumptions should be made on how storage engines
			 *     internally use the original column name to structure their storage.
			 *   - unique keys: (optional) An array of Schema API unique key definitions.
			 *     Only columns that appear in the 'columns' array are allowed.
			 *   - indexes: (optional) An array of Schema API index definitions. Only
			 *     columns that appear in the 'columns' array are allowed. Those indexes
			 *     will be used as default indexes. Field definitions can specify
			 *     additional indexes or, at their own risk, modify the default indexes
			 *     specified by the field-type module. Some storage engines might not
			 *     support indexes.
			 *   - foreign keys: (optional) An array of Schema API foreign key
			 *     definitions. Note, however, that the field data is not necessarily
			 *     stored in SQL. Also, the possible usage is limited, as you cannot
			 *     specify another field as related, only existing SQL tables,
			 *     such as {taxonomy_term_data}.
			 */";
		yield [
			'OK FieldItemInterface::schema',
			$sample,
			new PhpDocNode([
				new PhpDocTextNode('Returns the schema for the field.

This method is static because the field schema information is needed on
creation of the field. FieldItemInterface objects instantiated at that
time are not reliable as field settings might be missing.

Computed fields having no schema should return an empty array.'),
				new PhpDocTextNode(''),
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('\Drupal\Core\Field\FieldStorageDefinitionInterface'),
						false,
						'$field_definition',
						'
  The field definition.',
						false,
					),
				),
				new PhpDocTextNode(''),
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new IdentifierTypeNode('array'),
						"
  An empty array if there is no schema, or an associative array with the
  following key/value pairs:
  - columns: An array of Schema API column specifications, keyed by column
    name. The columns need to be a subset of the properties defined in
    propertyDefinitions(). The 'not null' property is ignored if present,
    as it is determined automatically by the storage controller depending
    on the table layout and the property definitions. It is recommended to
    avoid having the column definitions depend on field settings when
    possible. No assumptions should be made on how storage engines
    internally use the original column name to structure their storage.
  - unique keys: (optional) An array of Schema API unique key definitions.
    Only columns that appear in the 'columns' array are allowed.
  - indexes: (optional) An array of Schema API index definitions. Only
    columns that appear in the 'columns' array are allowed. Those indexes
    will be used as default indexes. Field definitions can specify
    additional indexes or, at their own risk, modify the default indexes
    specified by the field-type module. Some storage engines might not
    support indexes.
  - foreign keys: (optional) An array of Schema API foreign key
    definitions. Note, however, that the field data is not necessarily
    stored in SQL. Also, the possible usage is limited, as you cannot
    specify another field as related, only existing SQL tables,
    such as {taxonomy_term_data}.",
					),
				),
			]),
		];

		$sample = '/**
     *  Parses a chunked request and return relevant information.
     *
     *  This function must return an array containing the following
     *  keys and their corresponding values:
     *    - last: Wheter this is the last chunk of the uploaded file
     *    - uuid: A unique id which distinguishes two uploaded files
     *            This uuid must stay the same among the task of
     *            uploading a chunked file.
     *    - index: A numerical representation of the currently uploaded
     *            chunk. Must be higher that in the previous request.
     *    - orig: The original file name.
     *
     * @param Request $request - The request object
     *
     * @return array
     */';
		yield [
			'OK AbstractChunkedController::parseChunkedRequest',
			$sample,
			new PhpDocNode([
				new PhpDocTextNode('Parses a chunked request and return relevant information.

 This function must return an array containing the following
 keys and their corresponding values:
   - last: Wheter this is the last chunk of the uploaded file
   - uuid: A unique id which distinguishes two uploaded files
           This uuid must stay the same among the task of
           uploading a chunked file.
   - index: A numerical representation of the currently uploaded
           chunk. Must be higher that in the previous request.
   - orig: The original file name.'),
				new PhpDocTextNode(''),
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('Request'),
						false,
						'$request',
						'- The request object',
						false,
					),
				),
				new PhpDocTextNode(''),
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new IdentifierTypeNode('array'),
						'',
					),
				),
			]),
		];

		yield [
			'Description with indented <code>',
			"/**
			  * Finder allows searching through directory trees using iterator.
			  *
			  * <code>
			  * Finder::findFiles('*.php')
			  *     ->size('> 10kB')
			  *     ->from('.')
			  *     ->exclude('temp');
			  * </code>
			  */",
			new PhpDocNode([
				new PhpDocTextNode("Finder allows searching through directory trees using iterator.

<code>
Finder::findFiles('*.php')
    ->size('> 10kB')
    ->from('.')
    ->exclude('temp');
</code>"),
			]),
		];

		yield [
			'string literals in @return',
			"/** @return 'foo'|'bar' */",
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new UnionTypeNode([
							new ConstTypeNode(new ConstExprStringNode('foo', ConstExprStringNode::SINGLE_QUOTED)),
							new ConstTypeNode(new ConstExprStringNode('bar', ConstExprStringNode::SINGLE_QUOTED)),
						]),
						'',
					),
				),
			]),
		];

		yield [
			'malformed const fetch',
			'/** @param Foo::** $a */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new InvalidTagValueNode('Foo::** $a', new ParserException('*', Lexer::TOKEN_WILDCARD, 17, Lexer::TOKEN_VARIABLE, null, 1))),
			]),
		];

		yield [
			'multiline generic types',
			'/**' . PHP_EOL .
			' * @implements Foo<' . PHP_EOL .
			' *    A, B' . PHP_EOL .
			' * >' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@implements',
					new ImplementsTagValueNode(
						new GenericTypeNode(
							new IdentifierTypeNode('Foo'),
							[
								new IdentifierTypeNode('A'),
								new IdentifierTypeNode('B'),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
								GenericTypeNode::VARIANCE_INVARIANT,
							],
						),
						'',
					),
				),
			]),
		];

		yield [
			'multiline generic types - leading comma',
			'/**' . PHP_EOL .
			' * @implements Foo<' . PHP_EOL .
			' *    A' . PHP_EOL .
			' *    , B' . PHP_EOL .
			' * >' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@implements',
					new ImplementsTagValueNode(
						new GenericTypeNode(
							new IdentifierTypeNode('Foo'),
							[
								new IdentifierTypeNode('A'),
								new IdentifierTypeNode('B'),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
								GenericTypeNode::VARIANCE_INVARIANT,
							],
						),
						'',
					),
				),
			]),
		];

		yield [
			'multiline generic types - traling comma',
			'/**' . PHP_EOL .
			' * @implements Foo<' . PHP_EOL .
			' *    A,' . PHP_EOL .
			' *    B,' . PHP_EOL .
			' * >' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@implements',
					new ImplementsTagValueNode(
						new GenericTypeNode(
							new IdentifierTypeNode('Foo'),
							[
								new IdentifierTypeNode('A'),
								new IdentifierTypeNode('B'),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
								GenericTypeNode::VARIANCE_INVARIANT,
							],
						),
						'',
					),
				),
			]),
		];

		yield [
			'multiline callable types',
			'/**' . PHP_EOL .
			' * @param callable(' . PHP_EOL .
			' *    A, B' . PHP_EOL .
			' * ): void $foo' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new CallableTypeNode(
							new IdentifierTypeNode('callable'),
							[
								new CallableTypeParameterNode(new IdentifierTypeNode('A'), false, false, '', false),
								new CallableTypeParameterNode(new IdentifierTypeNode('B'), false, false, '', false),
							],
							new IdentifierTypeNode('void'),
							[],
						),
						false,
						'$foo',
						'',
						false,
					),
				),
			]),
		];

		yield [
			'multiline callable types - leading comma',
			'/**' . PHP_EOL .
			' * @param callable(' . PHP_EOL .
			' *    A' . PHP_EOL .
			' *    , B' . PHP_EOL .
			' * ): void $foo' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new CallableTypeNode(
							new IdentifierTypeNode('callable'),
							[
								new CallableTypeParameterNode(new IdentifierTypeNode('A'), false, false, '', false),
								new CallableTypeParameterNode(new IdentifierTypeNode('B'), false, false, '', false),
							],
							new IdentifierTypeNode('void'),
							[],
						),
						false,
						'$foo',
						'',
						false,
					),
				),
			]),
		];

		yield [
			'multiline callable types - traling comma',
			'/**' . PHP_EOL .
			' * @param callable(' . PHP_EOL .
			' *    A,' . PHP_EOL .
			' *    B,' . PHP_EOL .
			' * ): void $foo' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new CallableTypeNode(
							new IdentifierTypeNode('callable'),
							[
								new CallableTypeParameterNode(new IdentifierTypeNode('A'), false, false, '', false),
								new CallableTypeParameterNode(new IdentifierTypeNode('B'), false, false, '', false),
							],
							new IdentifierTypeNode('void'),
							[],
						),
						false,
						'$foo',
						'',
						false,
					),
				),
			]),
		];

		yield [
			'complex stub from Psalm',
			'/**' . PHP_EOL .
			' * @psalm-pure' . PHP_EOL .
			' * @template TFlags as int-mask<0, 256, 512>' . PHP_EOL .
			' *' . PHP_EOL .
			' * @param string $pattern' . PHP_EOL .
			' * @param string $subject' . PHP_EOL .
			' * @param mixed $matches' . PHP_EOL .
			' * @param TFlags $flags' . PHP_EOL .
			" * @param-out (TFlags is 256 ? array<array-key, array{string, 0|positive-int}|array{'', -1}> :" . PHP_EOL .
			' *             TFlags is 512 ? array<array-key, string|null> :' . PHP_EOL .
			' *             TFlags is 768 ? array<array-key, array{string, 0|positive-int}|array{null, -1}> :' . PHP_EOL .
			' *                             array<array-key, string>' . PHP_EOL .
			' *            ) $matches' . PHP_EOL .
			' * @return 1|0|false' . PHP_EOL .
			' * @psalm-ignore-falsable-return' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@psalm-pure', new GenericTagValueNode('')),
				new PhpDocTagNode(
					'@template',
					new TemplateTagValueNode(
						'TFlags',
						new GenericTypeNode(
							new IdentifierTypeNode('int-mask'),
							[
								new ConstTypeNode(new ConstExprIntegerNode('0')),
								new ConstTypeNode(new ConstExprIntegerNode('256')),
								new ConstTypeNode(new ConstExprIntegerNode('512')),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
								GenericTypeNode::VARIANCE_INVARIANT,
								GenericTypeNode::VARIANCE_INVARIANT,
							],
						),
						'',
					),
				),
				new PhpDocTextNode(''),
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('string'),
						false,
						'$pattern',
						'',
						false,
					),
				),
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('string'),
						false,
						'$subject',
						'',
						false,
					),
				),
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('mixed'),
						false,
						'$matches',
						'',
						false,
					),
				),
				new PhpDocTagNode(
					'@param',
					new ParamTagValueNode(
						new IdentifierTypeNode('TFlags'),
						false,
						'$flags',
						'',
						false,
					),
				),
				new PhpDocTagNode(
					'@param-out',
					new ParamOutTagValueNode(
						new ConditionalTypeNode(
							new IdentifierTypeNode('TFlags'),
							new ConstTypeNode(new ConstExprIntegerNode('256')),
							new GenericTypeNode(
								new IdentifierTypeNode('array'),
								[
									new IdentifierTypeNode('array-key'),
									new UnionTypeNode([
										ArrayShapeNode::createSealed([
											new ArrayShapeItemNode(null, false, new IdentifierTypeNode('string')),
											new ArrayShapeItemNode(
												null,
												false,
												new UnionTypeNode([
													new ConstTypeNode(new ConstExprIntegerNode('0')),
													new IdentifierTypeNode('positive-int'),
												]),
											),
										]),
										ArrayShapeNode::createSealed([
											new ArrayShapeItemNode(null, false, new ConstTypeNode(new ConstExprStringNode('', ConstExprStringNode::SINGLE_QUOTED))),
											new ArrayShapeItemNode(null, false, new ConstTypeNode(new ConstExprIntegerNode('-1'))),
										]),
									]),
								],
								[
									GenericTypeNode::VARIANCE_INVARIANT,
									GenericTypeNode::VARIANCE_INVARIANT,
								],
							),
							new ConditionalTypeNode(
								new IdentifierTypeNode('TFlags'),
								new ConstTypeNode(new ConstExprIntegerNode('512')),
								new GenericTypeNode(
									new IdentifierTypeNode('array'),
									[
										new IdentifierTypeNode('array-key'),
										new UnionTypeNode([
											new IdentifierTypeNode('string'),
											new IdentifierTypeNode('null'),
										]),
									],
									[
										GenericTypeNode::VARIANCE_INVARIANT,
										GenericTypeNode::VARIANCE_INVARIANT,
									],
								),
								new ConditionalTypeNode(
									new IdentifierTypeNode('TFlags'),
									new ConstTypeNode(new ConstExprIntegerNode('768')),
									new GenericTypeNode(
										new IdentifierTypeNode('array'),
										[
											new IdentifierTypeNode('array-key'),
											new UnionTypeNode([
												ArrayShapeNode::createSealed([
													new ArrayShapeItemNode(null, false, new IdentifierTypeNode('string')),
													new ArrayShapeItemNode(
														null,
														false,
														new UnionTypeNode([
															new ConstTypeNode(new ConstExprIntegerNode('0')),
															new IdentifierTypeNode('positive-int'),
														]),
													),
												]),
												ArrayShapeNode::createSealed([
													new ArrayShapeItemNode(null, false, new IdentifierTypeNode('null')),
													new ArrayShapeItemNode(null, false, new ConstTypeNode(new ConstExprIntegerNode('-1'))),
												]),
											]),
										],
										[
											GenericTypeNode::VARIANCE_INVARIANT,
											GenericTypeNode::VARIANCE_INVARIANT,
										],
									),
									new GenericTypeNode(
										new IdentifierTypeNode('array'),
										[
											new IdentifierTypeNode('array-key'),
											new IdentifierTypeNode('string'),
										],
										[
											GenericTypeNode::VARIANCE_INVARIANT,
											GenericTypeNode::VARIANCE_INVARIANT,
										],
									),
									false,
								),
								false,
							),
							false,
						),
						'$matches',
						'',
					),
				),
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new UnionTypeNode([
							new ConstTypeNode(new ConstExprIntegerNode('1')),
							new ConstTypeNode(new ConstExprIntegerNode('0')),
							new IdentifierTypeNode('false'),
						]),
						'',
					),
				),
				new PhpDocTagNode('@psalm-ignore-falsable-return', new GenericTagValueNode('')),
			]),
		];
	}

	public function provideDescriptionWithOrWithoutHtml(): Iterator
	{
		yield [
			'Description with HTML tags in @return tag (close tags together)',
			'/**' . PHP_EOL .
			' * @return Foo <strong>Important <i>description</i></strong>' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new ReturnTagValueNode(
						new IdentifierTypeNode('Foo'),
						'<strong>Important <i>description</i></strong>',
					),
				),
			]),
		];

		yield [
			'Description with HTML tags in @throws tag (closed tags with text between)',
			'/**' . PHP_EOL .
			' * @throws FooException <strong>Important <em>description</em> etc</strong>' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@throws',
					new ThrowsTagValueNode(
						new IdentifierTypeNode('FooException'),
						'<strong>Important <em>description</em> etc</strong>',
					),
				),
			]),
		];

		yield [
			'Description with HTML tags in @mixin tag',
			'/**' . PHP_EOL .
			' * @mixin Mixin <strong>Important description</strong>' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@mixin',
					new MixinTagValueNode(
						new IdentifierTypeNode('Mixin'),
						'<strong>Important description</strong>',
					),
				),
			]),
		];

		yield [
			'Description with unclosed HTML tags in @return tag - unclosed HTML tag is parsed as generics',
			'/**' . PHP_EOL .
			' * @return Foo <strong>Important description' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@return',
					new InvalidTagValueNode(
						'Foo <strong>Important description',
						new ParserException(
							'Important',
							Lexer::TOKEN_IDENTIFIER,
							PHP_EOL === "\n" ? 27 : 28,
							Lexer::TOKEN_HORIZONTAL_WS,
							null,
							2,
						),
					),
				),
			]),
		];
	}

	/**
	 * @return array<mixed>
	 */
	public function dataParseTagValue(): array
	{
		return [
			[
				'@param',
				'DateTimeImmutable::ATOM $a',
				new ParamTagValueNode(
					new ConstTypeNode(new ConstFetchNode('DateTimeImmutable', 'ATOM')),
					false,
					'$a',
					'',
					false,
				),
			],
			[
				'@var',
				'$foo string[]',
				new InvalidTagValueNode(
					'$foo string[]',
					new ParserException(
						'$foo',
						Lexer::TOKEN_VARIABLE,
						0,
						Lexer::TOKEN_IDENTIFIER,
						null,
						1,
					),
				),
			],
		];
	}

	public function provideTagsWithNumbers(): Iterator
	{
		yield [
			'OK without description and tag with number in it',
			'/** @special3 Foo  */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@special3',
					new GenericTagValueNode('Foo'),
				),
			]),
		];
	}

	public function provideTagsWithBackslash(): Iterator
	{
		yield [
			'OK without description and tag with backslashes in it',
			'/** @ORM\Mapping\Entity User */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@ORM\Mapping\Entity',
					new GenericTagValueNode('User'),
				),
			]),
		];

		yield [
			'OK without description and tag with backslashes in it and parenthesis',
			'/** @ORM\Mapping\JoinColumn(name="column_id", referencedColumnName="id") */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@ORM\Mapping\JoinColumn',
					new DoctrineTagValueNode(new DoctrineAnnotation('@ORM\Mapping\JoinColumn', [
						new DoctrineArgument(new IdentifierTypeNode('name'), new DoctrineConstExprStringNode('column_id')),
						new DoctrineArgument(new IdentifierTypeNode('referencedColumnName'), new DoctrineConstExprStringNode('id')),
					]), ''),
				),
			]),
		];
	}

	public function provideSelfOutTagsData(): Iterator
	{
		yield [
			'OK phpstan-self-out',
			'/** @phpstan-self-out self<T> */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-self-out',
					new SelfOutTagValueNode(
						new GenericTypeNode(new IdentifierTypeNode('self'), [new IdentifierTypeNode('T')], [GenericTypeNode::VARIANCE_INVARIANT]),
						'',
					),
				),
			]),
		];

		yield [
			'OK phpstan-this-out',
			'/** @phpstan-this-out self<T> */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-this-out',
					new SelfOutTagValueNode(
						new GenericTypeNode(new IdentifierTypeNode('self'), [new IdentifierTypeNode('T')], [GenericTypeNode::VARIANCE_INVARIANT]),
						'',
					),
				),
			]),
		];

		yield [
			'OK psalm-self-out',
			'/** @psalm-self-out self<T> */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@psalm-self-out',
					new SelfOutTagValueNode(
						new GenericTypeNode(new IdentifierTypeNode('self'), [new IdentifierTypeNode('T')], [GenericTypeNode::VARIANCE_INVARIANT]),
						'',
					),
				),
			]),
		];

		yield [
			'OK psalm-this-out',
			'/** @psalm-this-out self<T> */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@psalm-this-out',
					new SelfOutTagValueNode(
						new GenericTypeNode(new IdentifierTypeNode('self'), [new IdentifierTypeNode('T')], [GenericTypeNode::VARIANCE_INVARIANT]),
						'',
					),
				),
			]),
		];

		yield [
			'OK with description',
			'/** @phpstan-self-out self<T> description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@phpstan-self-out',
					new SelfOutTagValueNode(
						new GenericTypeNode(new IdentifierTypeNode('self'), [new IdentifierTypeNode('T')], [GenericTypeNode::VARIANCE_INVARIANT]),
						'description',
					),
				),
			]),
		];
	}

	public function provideCommentLikeDescriptions(): Iterator
	{
		yield [
			'Comment after @param',
			'/** @param int $a // this is a description */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new IdentifierTypeNode('int'),
					false,
					'$a',
					'// this is a description',
					false,
				)),
			]),
		];

		yield [
			'Comment after @param with https://',
			'/** @param int $a https://phpstan.org/ */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new IdentifierTypeNode('int'),
					false,
					'$a',
					'https://phpstan.org/',
					false,
				)),
			]),
		];

		yield [
			'Comment after @param with https:// in // comment',
			'/** @param int $a // comment https://phpstan.org/ */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new IdentifierTypeNode('int'),
					false,
					'$a',
					'// comment https://phpstan.org/',
					false,
				)),
			]),
		];

		yield [
			'Comment in PHPDoc tag outside of type',
			'/** @param // comment */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new InvalidTagValueNode('// comment', new ParserException(
					'// comment ',
					37,
					11,
					24,
					null,
					1,
				))),
			]),
		];

		yield [
			'Comment on a separate line',
			'/**' . PHP_EOL .
			' * @param int $a' . PHP_EOL .
			' * // this is a comment' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new IdentifierTypeNode('int'),
					false,
					'$a',
					PHP_EOL . '// this is a comment',
					false,
				)),
			]),
		];
		yield [
			'Comment on a separate line 2',
			'/**' . PHP_EOL .
			' * @param int $a' . PHP_EOL .
			' *' . PHP_EOL .
			' * // this is a comment' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new IdentifierTypeNode('int'),
					false,
					'$a',
					PHP_EOL . PHP_EOL . '// this is a comment',
					false,
				)),
			]),
		];
		yield [
			'Comment after Doctrine tag 1',
			'/** @ORM\Doctrine // this is a description */',
			new PhpDocNode([
				new PhpDocTagNode('@ORM\Doctrine', new GenericTagValueNode('// this is a description')),
			]),
		];
		yield [
			'Comment after Doctrine tag 2',
			'/** @\ORM\Doctrine // this is a description */',
			new PhpDocNode([
				new PhpDocTagNode('@\ORM\Doctrine', new DoctrineTagValueNode(
					new DoctrineAnnotation('@\ORM\Doctrine', []),
					'// this is a description',
				)),
			]),
		];
		yield [
			'Comment after Doctrine tag 3',
			'/** @\ORM\Doctrine() // this is a description */',
			new PhpDocNode([
				new PhpDocTagNode('@\ORM\Doctrine', new DoctrineTagValueNode(
					new DoctrineAnnotation('@\ORM\Doctrine', []),
					'// this is a description',
				)),
			]),
		];
		yield [
			'Comment after Doctrine tag 4',
			'/** @\ORM\Doctrine() @\ORM\Entity() // this is a description */',
			new PhpDocNode([
				new PhpDocTagNode('@\ORM\Doctrine', new DoctrineTagValueNode(
					new DoctrineAnnotation('@\ORM\Doctrine', []),
					'',
				)),
				new PhpDocTagNode('@\ORM\Entity', new DoctrineTagValueNode(
					new DoctrineAnnotation('@\ORM\Entity', []),
					'// this is a description',
				)),
			]),
		];
	}

	public function provideInlineTags(): Iterator
	{
		yield [
			'Inline @link tag in @copyright',
			'/**' . PHP_EOL .
			' * Unit tests for stored_progress_bar_cleanup' . PHP_EOL .
			' *' . PHP_EOL .
			' * @package   core' . PHP_EOL .
			' * @copyright 2024 onwards Catalyst IT EU {@link https://catalyst-eu.net}' . PHP_EOL .
			' * @\ORM\Entity() 2024 onwards Catalyst IT EU {@link https://catalyst-eu.net}' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Unit tests for stored_progress_bar_cleanup'),
				new PhpDocTextNode(''),
				new PhpDocTagNode('@package', new GenericTagValueNode('core')),
				new PhpDocTagNode('@copyright', new GenericTagValueNode('2024 onwards Catalyst IT EU {@link https://catalyst-eu.net}')),
				new PhpDocTagNode('@\ORM\Entity', new DoctrineTagValueNode(new DoctrineAnnotation('@\ORM\Entity', []), '2024 onwards Catalyst IT EU {@link https://catalyst-eu.net}')),
			]),
		];
	}

	public function provideParamOutTagsData(): Iterator
	{
		yield [
			'OK param-out',
			'/** @param-out string $s */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param-out',
					new ParamOutTagValueNode(
						new IdentifierTypeNode('string'),
						'$s',
						'',
					),
				),
			]),
		];

		yield [
			'OK param-out description',
			'/** @param-out string $s description */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@param-out',
					new ParamOutTagValueNode(
						new IdentifierTypeNode('string'),
						'$s',
						'description',
					),
				),
			]),
		];
	}

	public function provideDoctrineData(): Iterator
	{
		yield [
			'single tag node with empty parameters',
			'/**' . PHP_EOL .
			' * @X() Content' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@X',
					new DoctrineTagValueNode(
						new DoctrineAnnotation('@X', []),
						'Content',
					),
				),
			]),
			[new Doctrine\X()],
		];

		$xWithZ = new Doctrine\X();
		$xWithZ->a = new Doctrine\Z();
		yield [
			'single tag node with nested PHPDoc tag',
			'/**' . PHP_EOL .
			' * @X(@Z) Content' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@X',
					new DoctrineTagValueNode(
						new DoctrineAnnotation('@X', [
							new DoctrineArgument(null, new DoctrineAnnotation('@Z', [])),
						]),
						'Content',
					),
				),
			]),
			[$xWithZ],
		];

		yield [
			'single tag node with nested PHPDoc tag with field name',
			'/**' . PHP_EOL .
			' * @X(a=@Z) Content' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@X',
					new DoctrineTagValueNode(
						new DoctrineAnnotation('@X', [
							new DoctrineArgument(new IdentifierTypeNode('a'), new DoctrineAnnotation('@Z', [])),
						]),
						'Content',
					),
				),
			]),
			[$xWithZ],
		];

		yield [
			'single tag node with nested Doctrine tag',
			'/**' . PHP_EOL .
			' * @X(@\PHPStan\PhpDocParser\Parser\Doctrine\Z) Content' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@X',
					new DoctrineTagValueNode(
						new DoctrineAnnotation('@X', [
							new DoctrineArgument(null, new DoctrineAnnotation('@\PHPStan\PhpDocParser\Parser\Doctrine\Z', [])),
						]),
						'Content',
					),
				),
			]),
			[$xWithZ],
		];

		yield [
			'single tag node with nested Doctrine tag with field name',
			'/**' . PHP_EOL .
			' * @X( a = @\PHPStan\PhpDocParser\Parser\Doctrine\Z) Content' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@X',
					new DoctrineTagValueNode(
						new DoctrineAnnotation('@X', [
							new DoctrineArgument(new IdentifierTypeNode('a'), new DoctrineAnnotation('@\PHPStan\PhpDocParser\Parser\Doctrine\Z', [])),
						]),
						'Content',
					),
				),
			]),
			[$xWithZ],
		];

		yield [
			'single tag node with empty parameters with crazy whitespace',
			'/**' . PHP_EOL .
			' * @X  (   ' . PHP_EOL .
			' * ) Content' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@X',
					new DoctrineTagValueNode(
						new DoctrineAnnotation('@X', []),
						'Content',
					),
				),
			]),
			[new Doctrine\X()],
		];

		yield [
			'single tag node with empty parameters with crazy whitespace with extra text node',
			'/**' . PHP_EOL .
			' * @X ()' . PHP_EOL .
			' * Content' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@X',
					new DoctrineTagValueNode(
						new DoctrineAnnotation('@X', []),
						PHP_EOL .
						'Content',
					),
				),
			]),
			[new Doctrine\X()],
		];

		yield [
			'single FQN tag node without parentheses',
			'/**' . PHP_EOL .
			' * @\PHPStan\PhpDocParser\Parser\Doctrine\X Content' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@\PHPStan\PhpDocParser\Parser\Doctrine\X',
					new DoctrineTagValueNode(
						new DoctrineAnnotation('@\PHPStan\PhpDocParser\Parser\Doctrine\X', []),
						'Content',
					),
				),
			]),
			[new Doctrine\X()],
		];

		yield [
			'single FQN tag node with empty parameters',
			'/**' . PHP_EOL .
			' * @\PHPStan\PhpDocParser\Parser\Doctrine\X() Content' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@\PHPStan\PhpDocParser\Parser\Doctrine\X',
					new DoctrineTagValueNode(
						new DoctrineAnnotation('@\PHPStan\PhpDocParser\Parser\Doctrine\X', []),
						'Content',
					),
				),
			]),
			[new Doctrine\X()],
		];

		$x = new Doctrine\X();
		$x->a = Doctrine\Y::SOME;

		$z = new Doctrine\Z();
		$z->code = 123;
		$x->b = [$z];
		yield [
			'single tag node with other tags in parameters',
			'/**' . PHP_EOL .
			' * @X(' . PHP_EOL .
			' *     a=Y::SOME,' . PHP_EOL .
			' *     b={' . PHP_EOL .
			' *         @Z(' . PHP_EOL .
			' *             code=123' . PHP_EOL .
			' *         )' . PHP_EOL .
			' *     }' . PHP_EOL .
			' * ) Content' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@X',
					new DoctrineTagValueNode(
						new DoctrineAnnotation(
							'@X',
							[
								new DoctrineArgument(new IdentifierTypeNode('a'), new ConstFetchNode('Y', 'SOME')),
								new DoctrineArgument(new IdentifierTypeNode('b'), new DoctrineArray([
									new DoctrineArrayItem(null, new DoctrineAnnotation('@Z', [
										new DoctrineArgument(new IdentifierTypeNode('code'), new ConstExprIntegerNode('123')),
									])),
								])),
							],
						),
						'Content',
					),
				),
			]),
			[$x],
		];

		yield [
			'single tag node with other tags in parameters with crazy whitespace inbetween',
			'/**' . PHP_EOL .
			' * @X   (' . PHP_EOL .
			' *     a' . PHP_EOL .
			' *      =  Y::SOME,' . PHP_EOL .
			' *     b = ' . PHP_EOL .
			' *     {' . PHP_EOL .
			' *         @Z (' . PHP_EOL .
			' *             code=123,' . PHP_EOL .
			' *         ),' . PHP_EOL .
			' *     },' . PHP_EOL .
			' * ) Content' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@X',
					new DoctrineTagValueNode(
						new DoctrineAnnotation(
							'@X',
							[
								new DoctrineArgument(new IdentifierTypeNode('a'), new ConstFetchNode('Y', 'SOME')),
								new DoctrineArgument(new IdentifierTypeNode('b'), new DoctrineArray([
									new DoctrineArrayItem(null, new DoctrineAnnotation('@Z', [
										new DoctrineArgument(new IdentifierTypeNode('code'), new ConstExprIntegerNode('123')),
									])),
								])),
							],
						),
						'Content',
					),
				),
			]),
			[$x],
		];

		yield [
			'Multiline tag behaviour 1',
			'/**' . PHP_EOL .
			' * @X() test' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(new DoctrineAnnotation(
					'@X',
					[],
				), 'test')),
			]),
			[new Doctrine\X()],
		];

		yield [
			'Multiline tag behaviour 2',
			'/**' . PHP_EOL .
			' * @X() test' . PHP_EOL .
			' * test2' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(new DoctrineAnnotation(
					'@X',
					[],
				), 'test' . PHP_EOL . 'test2')),
			]),
			[new Doctrine\X()],
		];
		yield [
			'Multiline tag behaviour 3',
			'/**' . PHP_EOL .
			' * @X() test' . PHP_EOL .
			' *' . PHP_EOL .
			' * test2' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(new DoctrineAnnotation(
					'@X',
					[],
				), 'test' . PHP_EOL .
					PHP_EOL . 'test2')),
			]),
			[new Doctrine\X()],
		];
		yield [
			'Multiline tag behaviour 4',
			'/**' . PHP_EOL .
			' * @X() test' . PHP_EOL .
			' *' . PHP_EOL .
			' * test2' . PHP_EOL .
			' * @Z()' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(new DoctrineAnnotation(
					'@X',
					[],
				), 'test' . PHP_EOL .
					PHP_EOL . 'test2')),
				new PhpDocTagNode('@Z', new DoctrineTagValueNode(new DoctrineAnnotation(
					'@Z',
					[],
				), '')),
			]),
			[new Doctrine\X(), new Doctrine\Z()],
		];

		yield [
			'Multiline generic tag behaviour 1',
			'/**' . PHP_EOL .
			' * @X test' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new GenericTagValueNode('test')),
			]),
			[new Doctrine\X()],
		];

		yield [
			'Multiline generic tag behaviour 2',
			'/**' . PHP_EOL .
			' * @X test' . PHP_EOL .
			' * test2' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new GenericTagValueNode('test' . PHP_EOL . 'test2')),
			]),
			[new Doctrine\X()],
		];
		yield [
			'Multiline generic tag behaviour 3',
			'/**' . PHP_EOL .
			' * @X test' . PHP_EOL .
			' *' . PHP_EOL .
			' * test2' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new GenericTagValueNode('test' . PHP_EOL .
					PHP_EOL .
					'test2')),
			]),
			[new Doctrine\X()],
		];
		yield [
			'Multiline generic tag behaviour 4',
			'/**' . PHP_EOL .
			' * @X test' . PHP_EOL .
			' *' . PHP_EOL .
			' * test2' . PHP_EOL .
			' * @Z' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new GenericTagValueNode('test' . PHP_EOL .
					PHP_EOL .
					'test2')),
				new PhpDocTagNode('@Z', new GenericTagValueNode('')),
			]),
			[new Doctrine\X(), new Doctrine\Z()],
		];

		yield [
			'More tags on the same line',
			'/** @X() @Z() */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(new DoctrineAnnotation('@X', []), '')),
				new PhpDocTagNode('@Z', new DoctrineTagValueNode(new DoctrineAnnotation('@Z', []), '')),
			]),
			[new Doctrine\X(), new Doctrine\Z()],
		];

		yield [
			'More tags on the same line with description inbetween',
			'/** @X() test @Z() */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(new DoctrineAnnotation('@X', []), 'test')),
				new PhpDocTagNode('@Z', new DoctrineTagValueNode(new DoctrineAnnotation('@Z', []), '')),
			]),
			[new Doctrine\X(), new Doctrine\Z()],
		];

		yield [
			'More tags on the same line with description inbetween, first one generic',
			'/** @X test @Z() */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new GenericTagValueNode('test')),
				new PhpDocTagNode('@Z', new DoctrineTagValueNode(new DoctrineAnnotation('@Z', []), '')),
			]),
			[new Doctrine\X(), new Doctrine\Z()],
		];

		yield [
			'More generic tags on the same line with description inbetween, 2nd one @param which should become description',
			'/** @X @phpstan-param int $z */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new GenericTagValueNode('@phpstan-param int $z')),
			]),
			[new Doctrine\X()],
		];

		yield [
			'More generic tags on the same line with description inbetween, 2nd one @param which should become description can have a parse error',
			'/** @X @phpstan-param |int $z */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new GenericTagValueNode('@phpstan-param |int $z')),
			]),
			[new Doctrine\X()],
		];

		yield [
			'More tags on the same line with description inbetween, 2nd one @param which should become description',
			'/** @X() @phpstan-param int $z */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(new DoctrineAnnotation('@X', []), '@phpstan-param int $z')),
			]),
			[new Doctrine\X()],
		];

		yield [
			'More tags on the same line with description inbetween, 2nd one @param which should become description can have a parse error',
			'/** @X() @phpstan-param |int $z */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(new DoctrineAnnotation('@X', []), '@phpstan-param |int $z')),
			]),
			[new Doctrine\X()],
		];

		$apiResource = new Doctrine\ApiResource();
		$apiResource->itemOperations = [
			'get' => [
				'security' => 'is_granted(' . PHP_EOL .
					"constant('REDACTED')," . PHP_EOL .
					'object' . PHP_EOL . ')',
				'normalization_context' => [
					'groups' => ['Redacted:read'],
				],
			],
		];
		yield [
			'Regression test for issue #207',
			'/**' . PHP_EOL .
			' * @ApiResource(' . PHP_EOL .
			' *     itemOperations={' . PHP_EOL .
			' *         "get"={' . PHP_EOL .
			' *             "security"="is_granted(' . PHP_EOL .
			"constant('REDACTED')," . PHP_EOL .
			'object' . PHP_EOL .
			')",' . PHP_EOL .
			' *              "normalization_context"={"groups"={"Redacted:read"}}' . PHP_EOL .
			' *         }' . PHP_EOL .
			' *     }' . PHP_EOL .
			' * )' . PHP_EOL .
			'  */',
			new PhpDocNode([
				new PhpDocTagNode('@ApiResource', new DoctrineTagValueNode(
					new DoctrineAnnotation('@ApiResource', [
						new DoctrineArgument(new IdentifierTypeNode('itemOperations'), new DoctrineArray([
							new DoctrineArrayItem(
								new DoctrineConstExprStringNode('get'),
								new DoctrineArray([
									new DoctrineArrayItem(
										new DoctrineConstExprStringNode('security'),
										new DoctrineConstExprStringNode('is_granted(' . PHP_EOL .
											"constant('REDACTED')," . PHP_EOL .
											'object' . PHP_EOL .
											')'),
									),
									new DoctrineArrayItem(
										new DoctrineConstExprStringNode('normalization_context'),
										new DoctrineArray([
											new DoctrineArrayItem(
												new DoctrineConstExprStringNode('groups'),
												new DoctrineArray([
													new DoctrineArrayItem(null, new DoctrineConstExprStringNode('Redacted:read')),
												]),
											),
										]),
									),
								]),
							),
						])),
					]),
					'',
				)),
			]),
			[$apiResource],
		];

		$xWithString = new Doctrine\X();
		$xWithString->a = '"bar"';
		yield [
			'Escaped strings',
			'/** @X(a="""bar""") */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(
					new DoctrineAnnotation('@X', [
						new DoctrineArgument(new IdentifierTypeNode('a'), new DoctrineConstExprStringNode($xWithString->a)),
					]),
					'',
				)),
			]),
			[$xWithString],
		];

		$xWithString2 = new Doctrine\X();
		$xWithString2->a = 'Allowed choices are "bar" or "baz".';
		yield [
			'Escaped strings 2',
			'/** @X(a="Allowed choices are ""bar"" or ""baz"".") */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(
					new DoctrineAnnotation('@X', [
						new DoctrineArgument(new IdentifierTypeNode('a'), new DoctrineConstExprStringNode($xWithString2->a)),
					]),
					'',
				)),
			]),
			[$xWithString2],
		];

		$xWithString3 = new Doctrine\X();
		$xWithString3->a = 'In PHP, "" is an empty string';
		yield [
			'Escaped strings 3',
			'/** @X(a="In PHP, """" is an empty string") */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(
					new DoctrineAnnotation('@X', [
						new DoctrineArgument(new IdentifierTypeNode('a'), new DoctrineConstExprStringNode($xWithString3->a)),
					]),
					'',
				)),
			]),
			[$xWithString3],
		];

		$xWithString4 = new Doctrine\X();
		$xWithString4->a = '"May the Force be with you," he said.';
		yield [
			'Escaped strings 4',
			'/** @X(a="""May the Force be with you,"" he said.") */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(
					new DoctrineAnnotation('@X', [
						new DoctrineArgument(new IdentifierTypeNode('a'), new DoctrineConstExprStringNode($xWithString4->a)),
					]),
					'',
				)),
			]),
			[$xWithString4],
		];
	}

	public function provideDoctrineWithoutDoctrineCheckData(): Iterator
	{
		yield [
			'Dummy 1',
			'/** @DummyAnnotation(dummyValue="hello") */',
			new PhpDocNode([
				new PhpDocTagNode('@DummyAnnotation', new DoctrineTagValueNode(
					new DoctrineAnnotation('@DummyAnnotation', [
						new DoctrineArgument(new IdentifierTypeNode('dummyValue'), new DoctrineConstExprStringNode('hello')),
					]),
					'',
				)),
			]),
		];
		yield [
			'Dummy 2',
			'/**
 * @DummyJoinTable(name="join_table",
 *      joinColumns={@DummyJoinColumn(name="col1", referencedColumnName="col2")},
 *      inverseJoinColumns={
 *          @DummyJoinColumn(name="col3", referencedColumnName="col4")
 *      })
 */',
			new PhpDocNode([
				new PhpDocTagNode('@DummyJoinTable', new DoctrineTagValueNode(
					new DoctrineAnnotation('@DummyJoinTable', [
						new DoctrineArgument(new IdentifierTypeNode('name'), new DoctrineConstExprStringNode('join_table')),
						new DoctrineArgument(new IdentifierTypeNode('joinColumns'), new DoctrineArray([
							new DoctrineArrayItem(null, new DoctrineAnnotation('@DummyJoinColumn', [
								new DoctrineArgument(new IdentifierTypeNode('name'), new DoctrineConstExprStringNode('col1')),
								new DoctrineArgument(new IdentifierTypeNode('referencedColumnName'), new DoctrineConstExprStringNode('col2')),
							])),
						])),
						new DoctrineArgument(new IdentifierTypeNode('inverseJoinColumns'), new DoctrineArray([
							new DoctrineArrayItem(null, new DoctrineAnnotation('@DummyJoinColumn', [
								new DoctrineArgument(new IdentifierTypeNode('name'), new DoctrineConstExprStringNode('col3')),
								new DoctrineArgument(new IdentifierTypeNode('referencedColumnName'), new DoctrineConstExprStringNode('col4')),
							])),
						])),
					]),
					'',
				)),
			]),
		];

		yield [
			'Annotation in annotation',
			'/** @AnnotationTargetAll(@AnnotationTargetAnnotation) */',
			new PhpDocNode([
				new PhpDocTagNode('@AnnotationTargetAll', new DoctrineTagValueNode(
					new DoctrineAnnotation('@AnnotationTargetAll', [
						new DoctrineArgument(null, new DoctrineAnnotation('@AnnotationTargetAnnotation', [])),
					]),
					'',
				)),
			]),
		];

		yield [
			'Dangling comma annotation',
			'/** @DummyAnnotation(dummyValue = "bar",) */',
			new PhpDocNode([
				new PhpDocTagNode('@DummyAnnotation', new DoctrineTagValueNode(
					new DoctrineAnnotation('@DummyAnnotation', [
						new DoctrineArgument(new IdentifierTypeNode('dummyValue'), new DoctrineConstExprStringNode('bar')),
					]),
					'',
				)),
			]),
		];

		yield [
			'Multiple on one line',
			'/**
			 * @DummyId @DummyColumn(type="integer") @DummyGeneratedValue
			 * @var int
			 */',
			new PhpDocNode([
				new PhpDocTagNode('@DummyId', new GenericTagValueNode('')),
				new PhpDocTagNode('@DummyColumn', new DoctrineTagValueNode(
					new DoctrineAnnotation('@DummyColumn', [
						new DoctrineArgument(new IdentifierTypeNode('type'), new DoctrineConstExprStringNode('integer')),
					]),
					'',
				)),
				new PhpDocTagNode('@DummyGeneratedValue', new GenericTagValueNode('')),
				new PhpDocTagNode('@var', new VarTagValueNode(new IdentifierTypeNode('int'), '', '')),
			]),
		];

		yield [
			'Parse error with dashes',
			'/** @AlsoDoNot\Parse-me */',
			new PhpDocNode([
				new PhpDocTagNode('@AlsoDoNot\Parse-me', new GenericTagValueNode('')),
			]),
		];

		yield [
			'Annotation with constant',
			'/** @AnnotationWithConstants(PHP_EOL) */',
			new PhpDocNode([
				new PhpDocTagNode('@AnnotationWithConstants', new DoctrineTagValueNode(
					new DoctrineAnnotation('@AnnotationWithConstants', [
						new DoctrineArgument(null, new IdentifierTypeNode('PHP_EOL')),
					]),
					'',
				)),
			]),
		];

		yield [
			'Nested arrays with nested annotations',
			'/** @Name(foo={1,2, {"key"=@Name}}) */',
			new PhpDocNode([
				new PhpDocTagNode('@Name', new DoctrineTagValueNode(
					new DoctrineAnnotation('@Name', [
						new DoctrineArgument(new IdentifierTypeNode('foo'), new DoctrineArray([
							new DoctrineArrayItem(null, new ConstExprIntegerNode('1')),
							new DoctrineArrayItem(null, new ConstExprIntegerNode('2')),
							new DoctrineArrayItem(null, new DoctrineArray([
								new DoctrineArrayItem(new DoctrineConstExprStringNode('key'), new DoctrineAnnotation(
									'@Name',
									[],
								)),
							])),
						])),
					]),
					'',
				)),
			]),
		];

		yield [
			'Namespaced constant',
			'/** @AnnotationWithConstants(Doctrine\Tests\Common\Annotations\Fixtures\AnnotationWithConstants::FLOAT) */',
			new PhpDocNode([
				new PhpDocTagNode('@AnnotationWithConstants', new DoctrineTagValueNode(
					new DoctrineAnnotation('@AnnotationWithConstants', [
						new DoctrineArgument(null, new ConstFetchNode('Doctrine\Tests\Common\Annotations\Fixtures\AnnotationWithConstants', 'FLOAT')),
					]),
					'',
				)),
			]),
		];

		yield [
			'Another namespaced constant',
			'/** @AnnotationWithConstants(\Doctrine\Tests\Common\Annotations\Fixtures\AnnotationWithConstants::FLOAT) */',
			new PhpDocNode([
				new PhpDocTagNode('@AnnotationWithConstants', new DoctrineTagValueNode(
					new DoctrineAnnotation('@AnnotationWithConstants', [
						new DoctrineArgument(null, new ConstFetchNode('\Doctrine\Tests\Common\Annotations\Fixtures\AnnotationWithConstants', 'FLOAT')),
					]),
					'',
				)),
			]),
		];

		yield [
			'Array with namespaced constants',
			'/** @AnnotationWithConstants({
    Doctrine\Tests\Common\Annotations\Fixtures\InterfaceWithConstants::SOME_KEY = AnnotationWithConstants::INTEGER
}) */',
			new PhpDocNode([
				new PhpDocTagNode('@AnnotationWithConstants', new DoctrineTagValueNode(
					new DoctrineAnnotation('@AnnotationWithConstants', [
						new DoctrineArgument(null, new DoctrineArray([
							new DoctrineArrayItem(
								new ConstFetchNode('Doctrine\Tests\Common\Annotations\Fixtures\InterfaceWithConstants', 'SOME_KEY'),
								new ConstFetchNode('AnnotationWithConstants', 'INTEGER'),
							),
						])),
					]),
					'',
				)),
			]),
		];

		yield [
			'Array with colon',
			'/** @Name({"foo": "bar"}) */',
			new PhpDocNode([
				new PhpDocTagNode('@Name', new DoctrineTagValueNode(
					new DoctrineAnnotation('@Name', [
						new DoctrineArgument(null, new DoctrineArray([
							new DoctrineArrayItem(new DoctrineConstExprStringNode('foo'), new DoctrineConstExprStringNode('bar')),
						])),
					]),
					'',
				)),
			]),
		];

		yield [
			'More tags on the same line with description inbetween, second Doctrine one cannot have parse error',
			'/** @X test @Z(test= */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new GenericTagValueNode('test')),
				new PhpDocTagNode('@Z', new InvalidTagValueNode('(test=', new ParserException(
					'=',
					14,
					19,
					5,
					null,
					1,
				))),
			]),
			[new Doctrine\X()],
		];

		yield [
			'More tags on the same line with description inbetween, second Doctrine one cannot have parse error 2',
			'/** @X() test @Z(test= */',
			new PhpDocNode([
				new PhpDocTagNode('@X', new DoctrineTagValueNode(new DoctrineAnnotation('@X', []), 'test')),
				new PhpDocTagNode('@Z', new InvalidTagValueNode('(test=', new ParserException(
					'=',
					14,
					21,
					5,
					null,
					1,
				))),
			]),
			[new Doctrine\X()],
		];

		yield [
			'Doctrine tag after common tag is just a description',
			'/** @phpstan-param int $z @X() */',
			new PhpDocNode([
				new PhpDocTagNode('@phpstan-param', new ParamTagValueNode(
					new IdentifierTypeNode('int'),
					false,
					'$z',
					'@X()',
					false,
				)),
			]),
		];

		yield [
			'Doctrine tag after common tag is just a description 2',
			'/** @phpstan-param int $z @\X\Y() */',
			new PhpDocNode([
				new PhpDocTagNode('@phpstan-param', new ParamTagValueNode(
					new IdentifierTypeNode('int'),
					false,
					'$z',
					'@\X\Y()',
					false,
				)),
			]),
		];

		yield [
			'Generic tag after common tag is just a description',
			'/** @phpstan-param int $z @X */',
			new PhpDocNode([
				new PhpDocTagNode('@phpstan-param', new ParamTagValueNode(
					new IdentifierTypeNode('int'),
					false,
					'$z',
					'@X',
					false,
				)),
			]),
		];

		yield [
			'Slevomat CS issue #1608',
			'/**' . PHP_EOL .
			' * `"= "`' . PHP_EOL .
			' * a' . PHP_EOL .
			' * "' . PHP_EOL .
			' *' . PHP_EOL .
			' * @package foo' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('`"= "`' . PHP_EOL .
					' * a' . PHP_EOL .
					' * "'),
				new PhpDocTextNode(''),
				new PhpDocTagNode('@package', new GenericTagValueNode('foo')),
			]),
		];
	}

	public function provideSpecializedTags(): Iterator
	{
		yield [
			'Ok specialized tag',
			'/** @special:param this is special */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@special:param',
					new GenericTagValueNode(
						'this is special',
					),
				),
			]),
		];
	}

	/**
	 * @dataProvider dataParseTagValue
	 * @param PhpDocNode $expectedPhpDocNode
	 */
	public function testParseTagValue(string $tag, string $phpDoc, Node $expectedPhpDocNode): void
	{
		$this->executeTestParseTagValue($this->phpDocParser, $tag, $phpDoc, $expectedPhpDocNode);
	}

	private function executeTestParseTagValue(PhpDocParser $phpDocParser, string $tag, string $phpDoc, Node $expectedPhpDocNode): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($phpDoc));
		$actualPhpDocNode = $phpDocParser->parseTagValue($tokens, $tag);

		$this->assertEquals($expectedPhpDocNode, $actualPhpDocNode);
		$this->assertSame((string) $expectedPhpDocNode, (string) $actualPhpDocNode);
		$this->assertSame(Lexer::TOKEN_END, $tokens->currentTokenType());
	}

	public function testBug132(): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize('/**
 * @see \Symplify\SymfonyStaticDumper\Tests\Application\SymfonyStaticDumperApplicationTest
 */'));
		$phpDocNode = $this->phpDocParser->parse($tokens);
		$this->assertSame('/**
 * @see \Symplify\SymfonyStaticDumper\Tests\Application\SymfonyStaticDumperApplicationTest
 */', $phpDocNode->__toString());

		$seeNode = $phpDocNode->children[0];
		$this->assertInstanceOf(PhpDocTagNode::class, $seeNode);
		$this->assertInstanceOf(GenericTagValueNode::class, $seeNode->value);

		$this->assertSame('@see \Symplify\SymfonyStaticDumper\Tests\Application\SymfonyStaticDumperApplicationTest', $seeNode->__toString());
		$this->assertSame('\Symplify\SymfonyStaticDumper\Tests\Application\SymfonyStaticDumperApplicationTest', $seeNode->value->__toString());
	}

	public function testNegatedAssertionToString(): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize('/** @phpstan-assert !Type $param */'));
		$phpDocNode = $this->phpDocParser->parse($tokens);

		$assertNode = $phpDocNode->children[0];
		$this->assertInstanceOf(PhpDocTagNode::class, $assertNode);
		$this->assertInstanceOf(AssertTagValueNode::class, $assertNode->value);

		$this->assertSame('@phpstan-assert !Type $param', $assertNode->__toString());
	}

	/**
	 * @return array<mixed>
	 */
	public function dataLinesAndIndexes(): iterable
	{
		yield [
			'/** @param Foo $a */',
			[
				[1, 1, 1, 5],
			],
		];

		yield [
			'/**
			  * @param Foo $foo 1st multi world description
			  * @param Bar $bar 2nd multi world description
			  */',
			[
				[2, 2, 2, 15],
				[3, 3, 17, 30],
			],
		];

		yield [
			'/**
			  * @template TRandKey as array-key
			  * @template TRandVal
			  * @template TRandList as array<TRandKey, TRandVal>|XIterator<TRandKey, TRandVal>|Traversable<TRandKey, TRandVal>
			  *
			  * @param TRandList $list
			  *
			  * @return (
			  *        TRandList is array ? array<TRandKey, TRandVal> : (
			  *        TRandList is XIterator ?    XIterator<TRandKey, TRandVal> :
			  *        IteratorIterator<TRandKey, TRandVal>|LimitIterator<TRandKey, TRandVal>
			  * ))
			  */',
			[
				[2, 2, 2, 8],
				[3, 3, 10, 12],
				[4, 4, 14, 42],
				[5, 5, 44, 43],
				[6, 6, 45, 49],
				[7, 7, 51, 50],
				[8, 12, 52, 114],
			],
		];

		yield [
			'/** @param Foo( */',
			[
				[1, 1, 1, 4],
			],
		];

		yield [
			'/** @phpstan-import-type TypeAlias from AnotherClass[] */',
			[
				[1, 1, 8, 11],
			],
		];

		yield [
			'/** @param Foo::** $a */',
			[
				[1, 1, 1, 8],
			],
		];

		yield [
			'/** @param Foo::** $a*/',
			[
				[1, 1, 1, 8],
			],
		];

		yield [
			'/** @return Foo */',
			[
				[1, 1, 1, 3],
			],
		];

		yield [
			'/** @return Foo*/',
			[
				[1, 1, 1, 3],
			],
		];

		yield [
			'/** @api */',
			[
				[1, 1, 1, 1],
			],
		];
	}

	/**
	 * @dataProvider dataLinesAndIndexes
	 * @param list<array{int, int, int, int}> $childrenLines
	 */
	public function testLinesAndIndexes(string $phpDoc, array $childrenLines): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($phpDoc));
		$config = new ParserConfig([
			'lines' => true,
			'indexes' => true,
		]);
		$constExprParser = new ConstExprParser($config);
		$typeParser = new TypeParser($config, $constExprParser);
		$phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);
		$phpDocNode = $phpDocParser->parse($tokens);
		$children = $phpDocNode->children;
		$this->assertCount(count($childrenLines), $children);
		foreach ($children as $i => $child) {
			$this->assertSame($childrenLines[$i][0], $child->getAttribute(Attribute::START_LINE));
			$this->assertSame($childrenLines[$i][1], $child->getAttribute(Attribute::END_LINE));
			$this->assertSame($childrenLines[$i][2], $child->getAttribute(Attribute::START_INDEX));
			$this->assertSame($childrenLines[$i][3], $child->getAttribute(Attribute::END_INDEX));
		}
	}


	/**
	 * @return iterable<array{string, list<array{int, int, int, int}>}>
	 */
	public function dataDeepNodesLinesAndIndexes(): iterable
	{
		yield [
			'/**' . PHP_EOL .
			' * @X({' . PHP_EOL .
			' *     1,' . PHP_EOL .
			' *     2' . PHP_EOL .
			' *    ,    ' . PHP_EOL .
			' *     3,' . PHP_EOL .
			' * }' . PHP_EOL .
			' * )' . PHP_EOL .
			' */',
			[
				[1, 9, 0, 25], // PhpDocNode
				[2, 8, 2, 23], // PhpDocTagNode
				[2, 8, 3, 23], // DoctrineTagValueNode
				[2, 8, 3, 23], // DoctrineAnnotation
				[2, 8, 4, 21], // DoctrineArgument
				[2, 8, 4, 21], // DoctrineArray
				[3, 3, 7, 7], // DoctrineArrayItem
				[3, 3, 7, 7], // ConstExprIntegerNode
				[4, 5, 11, 11], // DoctrineArrayItem
				[4, 5, 11, 11], // ConstExprIntegerNode
				[6, 6, 18, 18], // DoctrineArrayItem
				[6, 6, 18, 18], // ConstExprIntegerNode
			],
		];

		yield [
			'/**' . PHP_EOL .
			' * @\Foo\Bar({' . PHP_EOL .
			' * }' . PHP_EOL .
			' * )' . PHP_EOL .
			' */',
			[
				[1, 5, 0, 10], // PhpDocNode
				[2, 4, 2, 8], // PhpDocTagNode
				[2, 4, 3, 8], // DoctrineTagValueNode
				[2, 4, 3, 8], // DoctrineAnnotation
				[2, 4, 4, 6], // DoctrineArgument
				[2, 4, 4, 6], // DoctrineArray
			],
		];

		yield [
			'/** @api */',
			[
				[1, 1, 0, 3],
				[1, 1, 1, 1],
				[1, 1, 3, 1], // GenericTagValueNode is empty so start index is higher than end index
			],
		];
	}


	/**
	 * @dataProvider dataDeepNodesLinesAndIndexes
	 * @param list<array{int, int, int, int}> $nodeAttributes
	 */
	public function testDeepNodesLinesAndIndexes(string $phpDoc, array $nodeAttributes): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($phpDoc));
		$config = new ParserConfig([
			'lines' => true,
			'indexes' => true,
		]);
		$constExprParser = new ConstExprParser($config);
		$typeParser = new TypeParser($config, $constExprParser);
		$phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);
		$visitor = new NodeCollectingVisitor();
		$traverser = new NodeTraverser([$visitor]);
		$traverser->traverse([$phpDocParser->parse($tokens)]);
		$nodes = $visitor->nodes;
		$this->assertCount(count($nodeAttributes), $nodes);
		foreach ($nodes as $i => $node) {
			$this->assertSame($nodeAttributes[$i][0], $node->getAttribute(Attribute::START_LINE), sprintf('Start line of %d. node', $i + 1));
			$this->assertSame($nodeAttributes[$i][1], $node->getAttribute(Attribute::END_LINE), sprintf('End line of %d. node', $i + 1));
			$this->assertSame($nodeAttributes[$i][2], $node->getAttribute(Attribute::START_INDEX), sprintf('Start index of %d. node', $i + 1));
			$this->assertSame($nodeAttributes[$i][3], $node->getAttribute(Attribute::END_INDEX), sprintf('End index of %d. node', $i + 1));
		}
	}

	/**
	 * @return array<mixed>
	 */
	public function dataReturnTypeLinesAndIndexes(): iterable
	{
		yield [
			'/** @return Foo */',
			[1, 1, 3, 3],
		];

		yield [
			'/** @return Foo*/',
			[1, 1, 3, 3],
		];

		yield [
			'/**
			  * @param Foo $foo
			  * @return Foo
			  */',
			[3, 3, 10, 10],
		];

		yield [
			'/**
			  * @return Foo
			  * @param Foo $foo
			  */',
			[2, 2, 4, 4],
		];

		yield [
			'/**
			  * @param Foo $foo
			  * @return Foo */',
			[3, 3, 10, 10],
		];

		yield [
			'/**
			  * @param Foo $foo
			  * @return Foo*/',
			[3, 3, 10, 10],
		];
	}

	/**
	 * @dataProvider dataReturnTypeLinesAndIndexes
	 * @param array{int, int, int, int} $lines
	 */
	public function testReturnTypeLinesAndIndexes(string $phpDoc, array $lines): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($phpDoc));
		$config = new ParserConfig([
			'lines' => true,
			'indexes' => true,
		]);
		$constExprParser = new ConstExprParser($config);
		$typeParser = new TypeParser($config, $constExprParser);
		$phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);
		$phpDocNode = $phpDocParser->parse($tokens);
		$returnTag = $phpDocNode->getReturnTagValues()[0];
		$type = $returnTag->type;
		$this->assertInstanceOf(IdentifierTypeNode::class, $type);

		$this->assertSame($lines[0], $type->getAttribute(Attribute::START_LINE));
		$this->assertSame($lines[1], $type->getAttribute(Attribute::END_LINE));
		$this->assertSame($lines[2], $type->getAttribute(Attribute::START_INDEX));
		$this->assertSame($lines[3], $type->getAttribute(Attribute::END_INDEX));
	}

	/**
	 * @dataProvider provideTagsWithNumbers
	 * @dataProvider provideSpecializedTags
	 * @dataProvider provideParamTagsData
	 * @dataProvider provideTypelessParamTagsData
	 * @dataProvider provideParamImmediatelyInvokedCallableTagsData
	 * @dataProvider provideParamLaterInvokedCallableTagsData
	 * @dataProvider provideParamClosureThisTagsData
	 * @dataProvider provideVarTagsData
	 * @dataProvider provideReturnTagsData
	 * @dataProvider provideThrowsTagsData
	 * @dataProvider provideMixinTagsData
	 * @dataProvider provideDeprecatedTagsData
	 * @dataProvider providePropertyTagsData
	 * @dataProvider provideMethodTagsData
	 * @dataProvider provideSingleLinePhpDocData
	 * @dataProvider provideMultiLinePhpDocData
	 * @dataProvider provideTemplateTagsData
	 * @dataProvider provideExtendsTagsData
	 * @dataProvider provideTypeAliasTagsData
	 * @dataProvider provideTypeAliasImportTagsData
	 * @dataProvider provideAssertTagsData
	 * @dataProvider provideRealWorldExampleData
	 * @dataProvider provideDescriptionWithOrWithoutHtml
	 * @dataProvider provideTagsWithBackslash
	 * @dataProvider provideSelfOutTagsData
	 * @dataProvider provideParamOutTagsData
	 * @dataProvider provideDoctrineData
	 * @dataProvider provideDoctrineWithoutDoctrineCheckData
	 */
	public function testVerifyAttributes(string $label, string $input): void
	{
		$config = new ParserConfig([
			'lines' => true,
			'indexes' => true,
		]);
		$constExprParser = new ConstExprParser($config);
		$typeParser = new TypeParser($config, $constExprParser);
		$phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);
		$tokens = new TokenIterator($this->lexer->tokenize($input));

		$visitor = new NodeCollectingVisitor();
		$traverser = new NodeTraverser([$visitor]);
		$traverser->traverse([$phpDocParser->parse($tokens)]);

		foreach ($visitor->nodes as $node) {
			$this->assertNotNull($node->getAttribute(Attribute::START_LINE), sprintf('%s: %s', $label, $node));
			$this->assertNotNull($node->getAttribute(Attribute::END_LINE), sprintf('%s: %s', $label, $node));
			$this->assertNotNull($node->getAttribute(Attribute::START_INDEX), sprintf('%s: %s', $label, $node));
			$this->assertNotNull($node->getAttribute(Attribute::END_INDEX), sprintf('%s: %s', $label, $node));
		}
	}

	/**
	 * @dataProvider provideDoctrineData
	 * @param list<object> $expectedAnnotations
	 */
	public function testDoctrine(
		string $label,
		string $input,
		PhpDocNode $expectedPhpDocNode,
		array $expectedAnnotations = []
	): void
	{
		$parser = new DocParser();
		$parser->addNamespace('PHPStan\PhpDocParser\Parser\Doctrine');
		$this->assertEquals($expectedAnnotations, $parser->parse($input, $label), $label);
	}

	/**
	 * @return iterable<array{string, PhpDocNode}>
	 */
	public function dataTextBetweenTagsBelongsToDescription(): iterable
	{
		yield [
			'/**' . PHP_EOL .
			  ' * Real description' . PHP_EOL .
			  ' * @param int $a' . PHP_EOL .
			  ' *   paramA description' . PHP_EOL .
			  ' * @param int $b' . PHP_EOL .
			  ' *   paramB description' . PHP_EOL .
			  ' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', PHP_EOL . '  paramA description', false)),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$b', PHP_EOL . '  paramB description', false)),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a' . PHP_EOL .
			' *' . PHP_EOL .
			' * @param int $b' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', '', false)),
				new PhpDocTextNode(''),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$b', '', false)),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a aaaa' . PHP_EOL .
			' *   bbbb' . PHP_EOL .
			' *' . PHP_EOL .
			' * ccc' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', 'aaaa' . PHP_EOL . '  bbbb' . PHP_EOL . PHP_EOL . 'ccc', false)),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @ORM\Column()' . PHP_EOL .
			' *   bbbb' . PHP_EOL .
			' *' . PHP_EOL .
			' * ccc' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@ORM\Column', new DoctrineTagValueNode(new DoctrineAnnotation('@ORM\Column', []), PHP_EOL . '  bbbb' . PHP_EOL . PHP_EOL . 'ccc')),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @ORM\Column() aaaa' . PHP_EOL .
			' *   bbbb' . PHP_EOL .
			' *' . PHP_EOL .
			' * ccc' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@ORM\Column', new DoctrineTagValueNode(new DoctrineAnnotation('@ORM\Column', []), 'aaaa' . PHP_EOL . '  bbbb' . PHP_EOL . PHP_EOL . 'ccc')),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @ORM\Column() aaaa' . PHP_EOL .
			' *   bbbb' . PHP_EOL .
			' *' . PHP_EOL .
			' * ccc' . PHP_EOL .
			' * @param int $b' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@ORM\Column', new DoctrineTagValueNode(new DoctrineAnnotation('@ORM\Column', []), 'aaaa' . PHP_EOL . '  bbbb' . PHP_EOL . PHP_EOL . 'ccc')),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$b', '', false)),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a' . PHP_EOL .
			' *' . PHP_EOL .
			' *' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', '', false)),
				new PhpDocTextNode(''),
				new PhpDocTextNode(''),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a' . PHP_EOL .
			' *' . PHP_EOL .
			' *' . PHP_EOL .
			' * test' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', PHP_EOL . PHP_EOL . PHP_EOL . 'test', false)),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a test' . PHP_EOL .
			' *' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', 'test', false)),
				new PhpDocTextNode(''),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a test' . PHP_EOL .
			' *' . PHP_EOL .
			' *' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', 'test', false)),
				new PhpDocTextNode(''),
				new PhpDocTextNode(''),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a' . PHP_EOL .
			' *  test' . PHP_EOL .
			' *' . PHP_EOL .
			' *' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', PHP_EOL . ' test', false)),
				new PhpDocTextNode(''),
				new PhpDocTextNode(''),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a' . PHP_EOL .
			' *  test' . PHP_EOL .
			' *' . PHP_EOL .
			' *' . PHP_EOL .
			' *' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', PHP_EOL . ' test', false)),
				new PhpDocTextNode(''),
				new PhpDocTextNode(''),
				new PhpDocTextNode(''),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a' . PHP_EOL .
			' *  test' . PHP_EOL .
			' *' . PHP_EOL .
			' * test 2' . PHP_EOL .
			' *' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', PHP_EOL . ' test' . PHP_EOL . PHP_EOL . 'test 2', false)),
				new PhpDocTextNode(''),
			]),
		];
		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @param int $a' . PHP_EOL .
			' *  test' . PHP_EOL .
			' *' . PHP_EOL .
			' * test 2' . PHP_EOL .
			' *' . PHP_EOL .
			' *' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('int'), false, '$a', PHP_EOL . ' test' . PHP_EOL . PHP_EOL . 'test 2', false)),
				new PhpDocTextNode(''),
				new PhpDocTextNode(''),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * Real description' . PHP_EOL .
			' * @ORM\Column()' . PHP_EOL .
			' *  test' . PHP_EOL .
			' *' . PHP_EOL .
			' * test 2' . PHP_EOL .
			' *' . PHP_EOL .
			' *' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('Real description'),
				new PhpDocTagNode('@ORM\Column', new DoctrineTagValueNode(new DoctrineAnnotation('@ORM\Column', []), PHP_EOL . ' test' . PHP_EOL . PHP_EOL . 'test 2')),
				new PhpDocTextNode(''),
				new PhpDocTextNode(''),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' * `"= "`' . PHP_EOL .
			' * a' . PHP_EOL .
			' * "' . PHP_EOL .
			' *' . PHP_EOL .
			' * @package foo' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode('`"= "`' . PHP_EOL .
					' * a' . PHP_EOL .
					' * "'),
				new PhpDocTextNode(''),
				new PhpDocTagNode('@package', new GenericTagValueNode('foo')),
			]),
		];

		yield [
			'/** @deprecated in Drupal 8.6.0 and will be removed before Drupal 9.0.0. In
			*   Drupal 9 there will be no way to set the status and in Drupal 8 this
			*   ability has been removed because mb_*() functions are supplied using
			*   Symfony\'s polyfill. */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@deprecated',
					new DeprecatedTagValueNode('in Drupal 8.6.0 and will be removed before Drupal 9.0.0. In
  Drupal 9 there will be no way to set the status and in Drupal 8 this
  ability has been removed because mb_*() functions are supplied using
  Symfony\'s polyfill.'),
				),
			]),
		];

		yield [
			'/** @\ORM\Column() in Drupal 8.6.0 and will be removed before Drupal 9.0.0. In
			*   Drupal 9 there will be no way to set the status and in Drupal 8 this
			*   ability has been removed because mb_*() functions are supplied using
			*   Symfony\'s polyfill. */',
			new PhpDocNode([
				new PhpDocTagNode(
					'@\ORM\Column',
					new DoctrineTagValueNode(
						new DoctrineAnnotation('@\ORM\Column', []),
						'in Drupal 8.6.0 and will be removed before Drupal 9.0.0. In
  Drupal 9 there will be no way to set the status and in Drupal 8 this
  ability has been removed because mb_*() functions are supplied using
  Symfony\'s polyfill.',
					),
				),
			]),
		];

		yield [
			'/**' . PHP_EOL .
			' *' . PHP_EOL .
			' * MultiLine' . PHP_EOL .
			' * description' . PHP_EOL .
			' * @param bool $a' . PHP_EOL .
			' *' . PHP_EOL .
			' * @return void' . PHP_EOL .
			' *' . PHP_EOL .
			' * @throws \Exception' . PHP_EOL .
			' *' . PHP_EOL .
			' */',
			new PhpDocNode([
				new PhpDocTextNode(
					PHP_EOL .
					'MultiLine' . PHP_EOL .
					'description',
				),
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new IdentifierTypeNode('bool'),
					false,
					'$a',
					'',
					false,
				)),
				new PhpDocTextNode(''),
				new PhpDocTagNode('@return', new ReturnTagValueNode(
					new IdentifierTypeNode('void'),
					'',
				)),
				new PhpDocTextNode(''),
				new PhpDocTagNode('@throws', new ThrowsTagValueNode(
					new IdentifierTypeNode('\Exception'),
					'',
				)),
				new PhpDocTextNode(''),
			]),
		];
	}

	/**
	 * @dataProvider dataTextBetweenTagsBelongsToDescription
	 */
	public function testTextBetweenTagsBelongsToDescription(
		string $input,
		PhpDocNode $expectedPhpDocNode
	): void
	{
		$config = new ParserConfig([]);
		$constExprParser = new ConstExprParser($config);
		$typeParser = new TypeParser($config, $constExprParser);
		$phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);

		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$actualPhpDocNode = $phpDocParser->parse($tokens);

		$this->assertEquals($expectedPhpDocNode, $actualPhpDocNode);
		$this->assertSame((string) $expectedPhpDocNode, (string) $actualPhpDocNode);
		$this->assertSame(Lexer::TOKEN_END, $tokens->currentTokenType());
	}

}
