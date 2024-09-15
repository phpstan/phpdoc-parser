<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ToString;

use Generator;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNullNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\Node;
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
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\OffsetAccessTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPUnit\Framework\TestCase;

class TypeToStringTest extends TestCase
{

	/**
	 * @dataProvider provideSimpleCases
	 * @dataProvider provideArrayCases
	 * @dataProvider provideCallableCases
	 * @dataProvider provideGenericCases
	 * @dataProvider provideConditionalCases
	 * @dataProvider provideCombinedCases
	 */
	public function testToString(string $expected, Node $node): void
	{
		$this->assertSame($expected, (string) $node);
	}

	public static function provideSimpleCases(): Generator
	{
		yield from [
			['string', new IdentifierTypeNode('string')],
			['Foo\\Bar', new IdentifierTypeNode('Foo\\Bar')],
			['null', new ConstTypeNode(new ConstExprNullNode())],
			['$this', new ThisTypeNode()],
		];
	}

	public static function provideArrayCases(): Generator
	{
		yield from [
			['$this[]', new ArrayTypeNode(new ThisTypeNode())],
			['array[int]', new OffsetAccessTypeNode(new IdentifierTypeNode('array'), new IdentifierTypeNode('int'))],
		];

		yield from [
			['array{}', ArrayShapeNode::createSealed([])],
			['array{...}', ArrayShapeNode::createUnsealed([], null)],
			[
				'array{string, int, ...}',
				ArrayShapeNode::createUnsealed([
					new ArrayShapeItemNode(null, false, new IdentifierTypeNode('string')),
					new ArrayShapeItemNode(null, false, new IdentifierTypeNode('int')),
				], null),
			],
			[
				'array{\'foo\': Foo, \'bar\'?: Bar, 1: Baz}',
				ArrayShapeNode::createSealed([
					new ArrayShapeItemNode(new ConstExprStringNode('foo', ConstExprStringNode::SINGLE_QUOTED), false, new IdentifierTypeNode('Foo')),
					new ArrayShapeItemNode(new ConstExprStringNode('bar', ConstExprStringNode::SINGLE_QUOTED), true, new IdentifierTypeNode('Bar')),
					new ArrayShapeItemNode(new ConstExprIntegerNode('1'), false, new IdentifierTypeNode('Baz')),
				]),
			],
			['list{}', ArrayShapeNode::createSealed([], 'list')],
			['list{...}', ArrayShapeNode::createUnsealed([], null, 'list')],
			[
				'list{string, int, ...}',
				ArrayShapeNode::createUnsealed([
					new ArrayShapeItemNode(null, false, new IdentifierTypeNode('string')),
					new ArrayShapeItemNode(null, false, new IdentifierTypeNode('int')),
				], null, 'list'),
			],
		];
	}

	public static function provideCallableCases(): Generator
	{
		yield from [
			[
				'\\Closure(): string',
				new CallableTypeNode(new IdentifierTypeNode('\Closure'), [], new IdentifierTypeNode('string'), []),
			],
			[
				'callable(int, int $foo): void',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [
					new CallableTypeParameterNode(new IdentifierTypeNode('int'), false, false, '', false),
					new CallableTypeParameterNode(new IdentifierTypeNode('int'), false, false, '$foo', false),
				], new IdentifierTypeNode('void'), []),
			],
			[
				'callable(int=, int $foo=): void',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [
					new CallableTypeParameterNode(new IdentifierTypeNode('int'), false, false, '', true),
					new CallableTypeParameterNode(new IdentifierTypeNode('int'), false, false, '$foo', true),
				], new IdentifierTypeNode('void'), []),
			],
			[
				'callable(int &, int &$foo): void',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [
					new CallableTypeParameterNode(new IdentifierTypeNode('int'), true, false, '', false),
					new CallableTypeParameterNode(new IdentifierTypeNode('int'), true, false, '$foo', false),
				], new IdentifierTypeNode('void'), []),
			],
			[
				'callable(string ...$foo): void',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [
					new CallableTypeParameterNode(new IdentifierTypeNode('string'), false, true, '$foo', false),
				], new IdentifierTypeNode('void'), []),
			],
		];
	}

	public static function provideGenericCases(): Generator
	{
		yield from [
			[
				'array<string>',
				new GenericTypeNode(new IdentifierTypeNode('array'), [new IdentifierTypeNode('string')]),
			],
			[
				'array<string, *>',
				new GenericTypeNode(
					new IdentifierTypeNode('array'),
					[new IdentifierTypeNode('string'), new IdentifierTypeNode('int')],
					[GenericTypeNode::VARIANCE_INVARIANT, GenericTypeNode::VARIANCE_BIVARIANT],
				),
			],
			[
				'Foo\Bar<covariant string, contravariant int>',
				new GenericTypeNode(
					new IdentifierTypeNode('Foo\\Bar'),
					[new IdentifierTypeNode('string'), new IdentifierTypeNode('int')],
					[GenericTypeNode::VARIANCE_COVARIANT, GenericTypeNode::VARIANCE_CONTRAVARIANT],
				),
			],
		];
	}

	public static function provideConditionalCases(): Generator
	{
		yield from [
			[
				'(TKey is int ? list<int> : list<string>)',
				new ConditionalTypeNode(
					new IdentifierTypeNode('TKey'),
					new IdentifierTypeNode('int'),
					new GenericTypeNode(new IdentifierTypeNode('list'), [new IdentifierTypeNode('int')]),
					new GenericTypeNode(new IdentifierTypeNode('list'), [new IdentifierTypeNode('string')]),
					false,
				),
			],
			[
				'(TValue is not array ? int : int[])',
				new ConditionalTypeNode(
					new IdentifierTypeNode('TValue'),
					new IdentifierTypeNode('array'),
					new IdentifierTypeNode('int'),
					new ArrayTypeNode(new IdentifierTypeNode('int')),
					true,
				),
			],
			[
				'($foo is Exception ? never : string)',
				new ConditionalTypeForParameterNode(
					'$foo',
					new IdentifierTypeNode('Exception'),
					new IdentifierTypeNode('never'),
					new IdentifierTypeNode('string'),
					false,
				),
			],
			[
				'($foo is not Exception ? string : never)',
				new ConditionalTypeForParameterNode(
					'$foo',
					new IdentifierTypeNode('Exception'),
					new IdentifierTypeNode('string'),
					new IdentifierTypeNode('never'),
					true,
				),
			],
		];
	}

	public static function provideCombinedCases(): Generator
	{
		yield from [
			['?string', new NullableTypeNode(new IdentifierTypeNode('string'))],
			[
				'(Foo & Bar)',
				new IntersectionTypeNode([
					new IdentifierTypeNode('Foo'),
					new IdentifierTypeNode('Bar'),
				]),
			],
			[
				'(Foo | Bar)',
				new UnionTypeNode([
					new IdentifierTypeNode('Foo'),
					new IdentifierTypeNode('Bar'),
				]),
			],
			[
				'((Foo & Bar) | Baz)',
				new UnionTypeNode([
					new IntersectionTypeNode([
						new IdentifierTypeNode('Foo'),
						new IdentifierTypeNode('Bar'),
					]),
					new IdentifierTypeNode('Baz'),
				]),
			],
		];
	}

}
