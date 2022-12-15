<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ToString;

use Generator;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\AssertTagMethodValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\AssertTagPropertyValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\AssertTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\DeprecatedTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ImplementsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\InvalidTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueParameterNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamOutTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\SelfOutTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ThrowsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypelessParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\UsesTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ParserException;
use PHPUnit\Framework\TestCase;

class PhpDocToStringTest extends TestCase
{

	/**
	 * @dataProvider provideFullPhpDocCases
	 */
	public function testFullPhpDocToString(string $expected, Node $node): void
	{
		$this->assertSame($expected, (string) $node);
	}

	/**
	 * @dataProvider provideOtherCases
	 * @dataProvider provideMethodCases
	 * @dataProvider provideClassCases
	 * @dataProvider provideAssertionCases
	 */
	public function testTagValueNodeToString(string $expected, Node $node): void
	{
		$this->assertSame($expected, (string) $node);
	}

	public static function provideFullPhpDocCases(): Generator
	{
		yield [
			"/**\n *\n */",
			new PhpDocNode([]),
		];

		yield [
			"/**\n * It works\n */",
			new PhpDocNode([
				new PhpDocTextNode('It works'),
			]),
		];

		yield [
			"/**\n * It works\n *\n * with empty lines\n */",
			new PhpDocNode([
				new PhpDocTextNode('It works'),
				new PhpDocTextNode(''),
				new PhpDocTextNode('with empty lines'),
			]),
		];

		yield [
			"/**\n * Foo\n *\n * @deprecated Because of reasons.\n */",
			new PhpDocNode([
				new PhpDocTextNode('Foo'),
				new PhpDocTextNode(''),
				new PhpDocTagNode('@deprecated', new DeprecatedTagValueNode('Because of reasons.')),
			]),
		];
	}

	public static function provideOtherCases(): Generator
	{
		$string = new IdentifierTypeNode('string');

		yield from [
			['', new GenericTagValueNode('')],
			['Foo bar', new GenericTagValueNode('Foo bar')],
		];

		yield [
			'#desc',
			new InvalidTagValueNode(
				'#desc',
				new ParserException('#desc', Lexer::TOKEN_OTHER, 11, Lexer::TOKEN_IDENTIFIER)
			),
		];

		yield from [
			['', new DeprecatedTagValueNode('')],
			['Because of reasons.', new DeprecatedTagValueNode('Because of reasons.')],
		];

		yield from [
			['string $foo', new VarTagValueNode($string, '$foo', '')],
			['string $foo Description.', new VarTagValueNode($string, '$foo', 'Description.')],
		];

		$bar = new IdentifierTypeNode('Foo\\Bar');
		$baz = new IdentifierTypeNode('Foo\\Baz');

		yield from [
			['TValue', new TemplateTagValueNode('TValue', null, '', null)],
			['TValue of Foo\\Bar', new TemplateTagValueNode('TValue', $bar, '', null)],
			['TValue = Foo\\Bar', new TemplateTagValueNode('TValue', null, '', $bar)],
			['TValue of Foo\\Bar = Foo\\Baz', new TemplateTagValueNode('TValue', $bar, '', $baz)],
			['TValue Description.', new TemplateTagValueNode('TValue', null, 'Description.', null)],
			['TValue of Foo\\Bar = Foo\\Baz Description.', new TemplateTagValueNode('TValue', $bar, 'Description.', $baz)],
		];
	}

	public static function provideMethodCases(): Generator
	{
		$string = new IdentifierTypeNode('string');
		$foo = new IdentifierTypeNode('Foo\\Foo');

		yield from [
			['string $foo', new ParamOutTagValueNode($string, '$foo', '')],
			['string $foo Description.', new ParamOutTagValueNode($string, '$foo', 'Description.')],
		];

		yield from [
			['Foo\\Foo', new ReturnTagValueNode($foo, '')],
			['string Description.', new ReturnTagValueNode($string, 'Description.')],
		];

		yield from [
			['string', new SelfOutTagValueNode($string, '')],
			['string Description.', new SelfOutTagValueNode($string, 'Description.')],
		];

		yield from [
			['Foo\\Foo', new ThrowsTagValueNode($foo, '')],
			['Foo\\Foo Description.', new ThrowsTagValueNode($foo, 'Description.')],
		];

		yield from [
			['string $foo', new ParamTagValueNode($string, false, '$foo', '', false)],
			['string &$foo', new ParamTagValueNode($string, false, '$foo', '', true)],
			['string ...$foo', new ParamTagValueNode($string, true, '$foo', '', false)],
			['string &...$foo', new ParamTagValueNode($string, true, '$foo', '', true)],
			['string $foo Description.', new ParamTagValueNode($string, false, '$foo', 'Description.', false)],
			['string &...$foo Description.', new ParamTagValueNode($string, true, '$foo', 'Description.', true)],
			['$foo', new TypelessParamTagValueNode(false, '$foo', '', false)],
			['&$foo', new TypelessParamTagValueNode(false, '$foo', '', true)],
			['&...$foo', new TypelessParamTagValueNode(true, '$foo', '', true)],
			['$foo Description.', new TypelessParamTagValueNode(false, '$foo', 'Description.', false)],
			['&...$foo Description.', new TypelessParamTagValueNode(true, '$foo', 'Description.', true)],
		];
	}

	public static function provideClassCases(): Generator
	{
		$string = new IdentifierTypeNode('string');
		$bar = new IdentifierTypeNode('Foo\\Bar');
		$arrayOfStrings = new GenericTypeNode(new IdentifierTypeNode('array'), [$string]);

		yield from [
			['PHPUnit\\TestCase', new MixinTagValueNode(new IdentifierTypeNode('PHPUnit\\TestCase'), '')],
			['Foo\\Bar Baz', new MixinTagValueNode(new IdentifierTypeNode('Foo\\Bar'), 'Baz')],
		];

		yield from [
			['Foo array<string>', new TypeAliasTagValueNode('Foo', $arrayOfStrings)],
			['Test from Foo\Bar', new TypeAliasImportTagValueNode('Test', $bar, null)],
			['Test from Foo\Bar as Foo', new TypeAliasImportTagValueNode('Test', $bar, 'Foo')],
		];

		yield from [
			[
				'array<string>',
				new ExtendsTagValueNode($arrayOfStrings, ''),
			],
			[
				'array<string> How did we manage to extend an array?',
				new ExtendsTagValueNode($arrayOfStrings, 'How did we manage to extend an array?'),
			],
			[
				'array<string>',
				new ImplementsTagValueNode($arrayOfStrings, ''),
			],
			[
				'array<string> How did we manage to implement an array?',
				new ImplementsTagValueNode($arrayOfStrings, 'How did we manage to implement an array?'),
			],
			[
				'array<string>',
				new UsesTagValueNode($arrayOfStrings, ''),
			],
			[
				'array<string> How did we manage to use an array?',
				new UsesTagValueNode($arrayOfStrings, 'How did we manage to use an array?'),
			],
		];

		yield from [
			['string $foo', new PropertyTagValueNode($string, '$foo', '')],
			['string $foo Description.', new PropertyTagValueNode($string, '$foo', 'Description.')],
		];

		yield from [
			[
				'foo',
				new MethodTagValueParameterNode(null, false, false, 'foo', null),
			],
			[
				'string foo',
				new MethodTagValueParameterNode($string, false, false, 'foo', null),
			],
			[
				'&foo',
				new MethodTagValueParameterNode(null, true, false, 'foo', null),
			],
			[
				'string &foo',
				new MethodTagValueParameterNode($string, true, false, 'foo', null),
			],
			[
				'string &foo = bar',
				new MethodTagValueParameterNode($string, true, false, 'foo', new ConstExprStringNode('bar')),
			],
			[
				'&...foo',
				new MethodTagValueParameterNode(null, true, true, 'foo', null),
			],
			[
				'string ...foo',
				new MethodTagValueParameterNode($string, false, true, 'foo', null),
			],
			[
				'string foo()',
				new MethodTagValueNode(false, $string, 'foo', [], '', []),
			],
			[
				'static string bar() Description',
				new MethodTagValueNode(true, $string, 'bar', [], 'Description', []),
			],
			[
				'baz(string &foo, string ...foo)',
				new MethodTagValueNode(false, null, 'baz', [
					new MethodTagValueParameterNode($string, true, false, 'foo', null),
					new MethodTagValueParameterNode($string, false, true, 'foo', null),
				], '', []),
			],
		];
	}

	public static function provideAssertionCases(): Generator
	{
		$string = new IdentifierTypeNode('string');

		yield from [
			[
				'string $foo->bar() description',
				new AssertTagMethodValueNode($string, '$foo', 'bar', false, 'description', false),
			],
			[
				'=string $foo->bar()',
				new AssertTagMethodValueNode($string, '$foo', 'bar', false, '', true),
			],
			[
				'!string $foo->bar() foobar',
				new AssertTagMethodValueNode($string, '$foo', 'bar', true, 'foobar', false),
			],
			[
				'!=string $foo->bar()',
				new AssertTagMethodValueNode($string, '$foo', 'bar', true, '', true),
			],
			[
				'string $foo->bar description',
				new AssertTagPropertyValueNode($string, '$foo', 'bar', false, 'description', false),
			],
			[
				'=string $foo->bar',
				new AssertTagPropertyValueNode($string, '$foo', 'bar', false, '', true),
			],
			[
				'!string $foo->bar foobar',
				new AssertTagPropertyValueNode($string, '$foo', 'bar', true, 'foobar', false),
			],
			[
				'!=string $foo->bar',
				new AssertTagPropertyValueNode($string, '$foo', 'bar', true, '', true),
			],
			[
				'string $foo description',
				new AssertTagValueNode($string, '$foo', false, 'description', false),
			],
			[
				'=string $foo',
				new AssertTagValueNode($string, '$foo', false, '', true),
			],
			[
				'!string $foo foobar',
				new AssertTagValueNode($string, '$foo', true, 'foobar', false),
			],
			[
				'!=string $foo',
				new AssertTagValueNode($string, '$foo', true, '', true),
			],
		];

		yield from [
			['string $foo', new ParamOutTagValueNode($string, '$foo', '')],
			['string $foo Description.', new ParamOutTagValueNode($string, '$foo', 'Description.')],
		];
	}

}
