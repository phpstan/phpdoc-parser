<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeParameterNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;

class TypeParserTest extends \PHPUnit\Framework\TestCase
{

	/** @var Lexer */
	private $lexer;

	/** @var TypeParser */
	private $typeParser;

	protected function setUp(): void
	{
		parent::setUp();
		$this->lexer = new Lexer();
		$this->typeParser = new TypeParser();
	}


	/**
	 * @dataProvider provideParseData
	 * @param string   $input
	 * @param TypeNode $expectedType
	 * @param int      $nextTokenType
	 */
	public function testParse(string $input, TypeNode $expectedType, int $nextTokenType = Lexer::TOKEN_END): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$typeNode = $this->typeParser->parse($tokens);

		$this->assertSame((string) $expectedType, (string) $typeNode);
		$this->assertEquals($expectedType, $typeNode);
		$this->assertSame($nextTokenType, $tokens->currentTokenType());
	}


	public function provideParseData(): array
	{
		return [
			[
				'string',
				new IdentifierTypeNode('string'),
			],
			[
				'  string  ',
				new IdentifierTypeNode('string'),
			],
			[
				' ( string ) ',
				new IdentifierTypeNode('string'),
			],
			[
				'( ( string ) )',
				new IdentifierTypeNode('string'),
			],
			[
				'\\Foo\Bar\\Baz',
				new IdentifierTypeNode('\\Foo\Bar\\Baz'),
			],
			[
				'  \\Foo\Bar\\Baz  ',
				new IdentifierTypeNode('\\Foo\Bar\\Baz'),
			],
			[
				' ( \\Foo\Bar\\Baz ) ',
				new IdentifierTypeNode('\\Foo\Bar\\Baz'),
			],
			[
				'( ( \\Foo\Bar\\Baz ) )',
				new IdentifierTypeNode('\\Foo\Bar\\Baz'),
			],
			[
				'string|int',
				new UnionTypeNode([
					new IdentifierTypeNode('string'),
					new IdentifierTypeNode('int'),
				]),
			],
			[
				'string | int',
				new UnionTypeNode([
					new IdentifierTypeNode('string'),
					new IdentifierTypeNode('int'),
				]),
			],
			[
				'(string | int)',
				new UnionTypeNode([
					new IdentifierTypeNode('string'),
					new IdentifierTypeNode('int'),
				]),
			],
			[
				'string | int | float',
				new UnionTypeNode([
					new IdentifierTypeNode('string'),
					new IdentifierTypeNode('int'),
					new IdentifierTypeNode('float'),
				]),
			],
			[
				'string&int',
				new IntersectionTypeNode([
					new IdentifierTypeNode('string'),
					new IdentifierTypeNode('int'),
				]),
			],
			[
				'string & int',
				new IntersectionTypeNode([
					new IdentifierTypeNode('string'),
					new IdentifierTypeNode('int'),
				]),
			],
			[
				'(string & int)',
				new IntersectionTypeNode([
					new IdentifierTypeNode('string'),
					new IdentifierTypeNode('int'),
				]),
			],
			[
				'string & int & float',
				new IntersectionTypeNode([
					new IdentifierTypeNode('string'),
					new IdentifierTypeNode('int'),
					new IdentifierTypeNode('float'),
				]),
			],
			[
				'string & (int | float)',
				new IntersectionTypeNode([
					new IdentifierTypeNode('string'),
					new UnionTypeNode([
						new IdentifierTypeNode('int'),
						new IdentifierTypeNode('float'),
					]),
				]),
			],
			[
				'string | (int & float)',
				new UnionTypeNode([
					new IdentifierTypeNode('string'),
					new IntersectionTypeNode([
						new IdentifierTypeNode('int'),
						new IdentifierTypeNode('float'),
					]),
				]),
			],
			[
				'string & int | float',
				new IntersectionTypeNode([
					new IdentifierTypeNode('string'),
					new IdentifierTypeNode('int'),
				]),
				Lexer::TOKEN_UNION,
			],
			[
				'string | int & float',
				new UnionTypeNode([
					new IdentifierTypeNode('string'),
					new IdentifierTypeNode('int'),
				]),
				Lexer::TOKEN_INTERSECTION,
			],
			[
				'string[]',
				new ArrayTypeNode(
					new IdentifierTypeNode('string')
				),
			],
			[
				'string [  ] ',
				new ArrayTypeNode(
					new IdentifierTypeNode('string')
				),
			],
			[
				'(string | int | float)[]',
				new ArrayTypeNode(
					new UnionTypeNode([
						new IdentifierTypeNode('string'),
						new IdentifierTypeNode('int'),
						new IdentifierTypeNode('float'),
					])
				),
			],
			[
				'string[][][]',
				new ArrayTypeNode(
					new ArrayTypeNode(
						new ArrayTypeNode(
							new IdentifierTypeNode('string')
						)
					)
				),
			],
			[
				'string [  ] [][]',
				new ArrayTypeNode(
					new ArrayTypeNode(
						new ArrayTypeNode(
							new IdentifierTypeNode('string')
						)
					)
				),
			],
			[
				'(((string | int | float)[])[])[]',
				new ArrayTypeNode(
					new ArrayTypeNode(
						new ArrayTypeNode(
							new UnionTypeNode([
								new IdentifierTypeNode('string'),
								new IdentifierTypeNode('int'),
								new IdentifierTypeNode('float'),
							])
						)
					)
				),
			],
			[
				'$this',
				new ThisTypeNode(),
			],
			[
				'?int',
				new NullableTypeNode(
					new IdentifierTypeNode('int')
				),
			],
			[
				'?Foo<Bar>',
				new NullableTypeNode(
					new GenericTypeNode(
						new IdentifierTypeNode('Foo'),
						[
							new IdentifierTypeNode('Bar'),
						]
					)
				),
			],
			[
				'array<int, Foo\\Bar>',
				new GenericTypeNode(
					new IdentifierTypeNode('array'),
					[
						new IdentifierTypeNode('int'),
						new IdentifierTypeNode('Foo\\Bar'),
					]
				),
			],
			[
				'array{\'a\': int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new ConstExprStringNode('\'a\''),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'array{\'a\': ?int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new ConstExprStringNode('\'a\''),
						false,
						new NullableTypeNode(
							new IdentifierTypeNode('int')
						)
					),
				]),
			],
			[
				'array{\'a\'?: ?int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new ConstExprStringNode('\'a\''),
						true,
						new NullableTypeNode(
							new IdentifierTypeNode('int')
						)
					),
				]),
			],
			[
				'array{\'a\': int, \'b\': string}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new ConstExprStringNode('\'a\''),
						false,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						new ConstExprStringNode('\'b\''),
						false,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'array{int, string, "a": string}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						null,
						false,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						null,
						false,
						new IdentifierTypeNode('string')
					),
					new ArrayShapeItemNode(
						new ConstExprStringNode('"a"'),
						false,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'array{"a"?: int, \'b\': string, 0: int, 1?: DateTime, hello: string}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new ConstExprStringNode('"a"'),
						true,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						new ConstExprStringNode('\'b\''),
						false,
						new IdentifierTypeNode('string')
					),
					new ArrayShapeItemNode(
						new ConstExprIntegerNode('0'),
						false,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						new ConstExprIntegerNode('1'),
						true,
						new IdentifierTypeNode('DateTime')
					),
					new ArrayShapeItemNode(
						new IdentifierTypeNode('hello'),
						false,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'array{\'a\': int, \'b\': array{\'c\': callable(): int}}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new ConstExprStringNode('\'a\''),
						false,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						new ConstExprStringNode('\'b\''),
						false,
						new ArrayShapeNode([
							new ArrayShapeItemNode(
								new ConstExprStringNode('\'c\''),
								false,
								new CallableTypeNode(
									new IdentifierTypeNode('callable'),
									[],
									new IdentifierTypeNode('int')
								)
							),
						])
					),
				]),
			],
			[
				'?array{\'a\': int}',
				new NullableTypeNode(
					new ArrayShapeNode([
						new ArrayShapeItemNode(
							new ConstExprStringNode('\'a\''),
							false,
							new IdentifierTypeNode('int')
						),
					])
				),
			],
			[
				'callable(): Foo',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[],
					new IdentifierTypeNode('Foo')
				),
			],
			[
				'callable(): ?Foo',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[],
					new NullableTypeNode(
						new IdentifierTypeNode('Foo')
					)
				),
			],
			[
				'callable(): Foo<Bar>',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[],
					new GenericTypeNode(
						new IdentifierTypeNode('Foo'),
						[
							new IdentifierTypeNode('Bar'),
						]
					)
				),
			],
			[
				'callable(): Foo|Bar',
				new UnionTypeNode([
					new CallableTypeNode(
						new IdentifierTypeNode('callable'),
						[],
						new IdentifierTypeNode('Foo')
					),
					new IdentifierTypeNode('Bar'),
				]),
			],
			[
				'callable(): Foo&Bar',
				new IntersectionTypeNode([
					new CallableTypeNode(
						new IdentifierTypeNode('callable'),
						[],
						new IdentifierTypeNode('Foo')
					),
					new IdentifierTypeNode('Bar'),
				]),
			],
			[
				'callable(): (Foo|Bar)',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[],
					new UnionTypeNode([
						new IdentifierTypeNode('Foo'),
						new IdentifierTypeNode('Bar'),
					])
				),
			],
			[
				'callable(): (Foo&Bar)',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[],
					new IntersectionTypeNode([
						new IdentifierTypeNode('Foo'),
						new IdentifierTypeNode('Bar'),
					])
				),
			],
			[
				'callable(): array{\'a\': int}',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[],
					new ArrayShapeNode([
						new ArrayShapeItemNode(
							new ConstExprStringNode('\'a\''),
							false,
							new IdentifierTypeNode('int')
						),
					])
				),
			],
			[
				'callable(A&...$a=, B&...=, C): Foo',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[
						new CallableTypeParameterNode(
							new IdentifierTypeNode('A'),
							true,
							true,
							'$a',
							true
						),
						new CallableTypeParameterNode(
							new IdentifierTypeNode('B'),
							true,
							true,
							'',
							true
						),
						new CallableTypeParameterNode(
							new IdentifierTypeNode('C'),
							false,
							false,
							'',
							false
						),
					],
					new IdentifierTypeNode('Foo')
				),
			],
			[
				'(Foo\\Bar<array<mixed, string>, (int | (string<foo> & bar)[])> | Lorem)',
				new UnionTypeNode([
					new GenericTypeNode(
						new IdentifierTypeNode('Foo\\Bar'),
						[
							new GenericTypeNode(
								new IdentifierTypeNode('array'),
								[
									new IdentifierTypeNode('mixed'),
									new IdentifierTypeNode('string'),
								]
							),
							new UnionTypeNode([
								new IdentifierTypeNode('int'),
								new ArrayTypeNode(
									new IntersectionTypeNode([
										new GenericTypeNode(
											new IdentifierTypeNode('string'),
											[
												new IdentifierTypeNode('foo'),
											]
										),
										new IdentifierTypeNode('bar'),
									])
								),
							]),
						]
					),
					new IdentifierTypeNode('Lorem'),
				]),
			],
			[
				'array [ int ]',
				new IdentifierTypeNode('array'),
				Lexer::TOKEN_OPEN_SQUARE_BRACKET,
			],
			[
				"?\t\xA009", // edge-case with \h
				new NullableTypeNode(
					new IdentifierTypeNode("\xA009")
				),
			],
		];
	}

}
