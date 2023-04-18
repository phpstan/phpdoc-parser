<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use Exception;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFloatNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\QuoteAwareConstExprStringNode;
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
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeNode;
use PHPStan\PhpDocParser\Ast\Type\OffsetAccessTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPUnit\Framework\TestCase;
use function get_class;
use function strpos;
use const PHP_EOL;

class TypeParserTest extends TestCase
{

	/** @var Lexer */
	private $lexer;

	/** @var TypeParser */
	private $typeParser;

	protected function setUp(): void
	{
		parent::setUp();
		$this->lexer = new Lexer();
		$this->typeParser = new TypeParser(new ConstExprParser(true, true), true);
	}


	/**
	 * @dataProvider provideParseData
	 * @param TypeNode|Exception $expectedResult
	 */
	public function testParse(string $input, $expectedResult, int $nextTokenType = Lexer::TOKEN_END): void
	{
		if ($expectedResult instanceof Exception) {
			$this->expectException(get_class($expectedResult));
			$this->expectExceptionMessage($expectedResult->getMessage());
		}

		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$typeNode = $this->typeParser->parse($tokens);

		$this->assertSame((string) $expectedResult, (string) $typeNode);
		$this->assertInstanceOf(get_class($expectedResult), $typeNode);
		$this->assertEquals($expectedResult, $typeNode);
		$this->assertSame($nextTokenType, $tokens->currentTokenType());

		if (strpos((string) $expectedResult, '$ref') !== false) {
			// weird case with $ref inside double-quoted string - not really possible in PHP
			return;
		}

		$typeNodeTokens = new TokenIterator($this->lexer->tokenize((string) $typeNode));
		$parsedAgainTypeNode = $this->typeParser->parse($typeNodeTokens);
		$this->assertSame((string) $typeNode, (string) $parsedAgainTypeNode);
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
				'(' . PHP_EOL .
				'  string' . PHP_EOL .
				'  &' . PHP_EOL .
				'  int' . PHP_EOL .
				')',
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
						],
						[
							GenericTypeNode::VARIANCE_INVARIANT,
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
					],
					[
						GenericTypeNode::VARIANCE_INVARIANT,
						GenericTypeNode::VARIANCE_INVARIANT,
					]
				),
			],
			[
				'array {\'a\': int}',
				new IdentifierTypeNode('array'),
				Lexer::TOKEN_OPEN_CURLY_BRACKET,
			],

			[
				'array{a: int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'array{a: ?int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new NullableTypeNode(
							new IdentifierTypeNode('int')
						)
					),
				]),
			],
			[
				'array{a?: ?int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						true,
						new NullableTypeNode(
							new IdentifierTypeNode('int')
						)
					),
				]),
			],
			[
				'array{0: int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new ConstExprIntegerNode('0'),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'array{0?: int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new ConstExprIntegerNode('0'),
						true,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'array{int, int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						null,
						false,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						null,
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'array{a: int, b: string}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'array{a?: int, b: string, 0: int, 1?: DateTime, hello: string}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						true,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						new IdentifierTypeNode('b'),
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
				'array{a: int, b: array{c: callable(): int}}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new ArrayShapeNode([
							new ArrayShapeItemNode(
								new IdentifierTypeNode('c'),
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
				'?array{a: int}',
				new NullableTypeNode(
					new ArrayShapeNode([
						new ArrayShapeItemNode(
							new IdentifierTypeNode('a'),
							false,
							new IdentifierTypeNode('int')
						),
					])
				),
			],
			[
				'array{',
				new ParserException(
					'',
					Lexer::TOKEN_END,
					6,
					Lexer::TOKEN_IDENTIFIER
				),
			],
			[
				'array{a => int}',
				new ParserException(
					'=>',
					Lexer::TOKEN_OTHER,
					8,
					Lexer::TOKEN_CLOSE_CURLY_BRACKET
				),
			],
			[
				'array{"a": int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new QuoteAwareConstExprStringNode('a', QuoteAwareConstExprStringNode::DOUBLE_QUOTED),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'array{\'a\': int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new QuoteAwareConstExprStringNode('a', QuoteAwareConstExprStringNode::SINGLE_QUOTED),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'array{\'$ref\': int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new QuoteAwareConstExprStringNode('$ref', QuoteAwareConstExprStringNode::SINGLE_QUOTED),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'array{"$ref": int}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new QuoteAwareConstExprStringNode('$ref', QuoteAwareConstExprStringNode::DOUBLE_QUOTED),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'array{
				 *	a: int
				 *}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'array{
				 	a: int,
				 }',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'array{
				 	a: int,
				 	b: string,
				 }',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'array{
				 	a: int
				 	, b: string
				 	, c: string
				 }',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new IdentifierTypeNode('string')
					),
					new ArrayShapeItemNode(
						new IdentifierTypeNode('c'),
						false,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'array{
				 	a: int,
				 	b: string
				 }',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'array{a: int, b: int, ...}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ArrayShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new IdentifierTypeNode('int')
					),
				], false),
			],
			[
				'array{int, string, ...}',
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
				], false),
			],
			[
				'array{...}',
				new ArrayShapeNode([], false),
			],
			[
				'array{
				 *	a: int,
				 *	...
				 *}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
				], false),
			],
			[
				'array{
					a: int,
					...,
				}',
				new ArrayShapeNode([
					new ArrayShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
				], false),
			],
			[
				'array{int, ..., string}',
				new ParserException(
					'string',
					Lexer::TOKEN_IDENTIFIER,
					16,
					Lexer::TOKEN_CLOSE_CURLY_BRACKET
				),
			],
			[
				'list{
				 	int,
				 	string
				 }',
				new ArrayShapeNode(
					[
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
					],
					true,
					ArrayShapeNode::KIND_LIST
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
						],
						[
							GenericTypeNode::VARIANCE_INVARIANT,
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
				'callable(): array{a: int}',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[],
					new ArrayShapeNode([
						new ArrayShapeItemNode(
							new IdentifierTypeNode('a'),
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
								],
								[
									GenericTypeNode::VARIANCE_INVARIANT,
									GenericTypeNode::VARIANCE_INVARIANT,
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
											],
											[
												GenericTypeNode::VARIANCE_INVARIANT,
											]
										),
										new IdentifierTypeNode('bar'),
									])
								),
							]),
						],
						[
							GenericTypeNode::VARIANCE_INVARIANT,
							GenericTypeNode::VARIANCE_INVARIANT,
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
				'array[ int ]',
				new OffsetAccessTypeNode(
					new IdentifierTypeNode('array'),
					new IdentifierTypeNode('int')
				),
			],
			[
				"?\t\xA009", // edge-case with \h
				new NullableTypeNode(
					new IdentifierTypeNode("\xA009")
				),
			],
			[
				'Collection<array-key, int>[]',
				new ArrayTypeNode(
					new GenericTypeNode(
						new IdentifierTypeNode('Collection'),
						[
							new IdentifierTypeNode('array-key'),
							new IdentifierTypeNode('int'),
						],
						[
							GenericTypeNode::VARIANCE_INVARIANT,
							GenericTypeNode::VARIANCE_INVARIANT,
						]
					)
				),
			],
			[
				'int | Collection<array-key, int>[]',
				new UnionTypeNode([
					new IdentifierTypeNode('int'),
					new ArrayTypeNode(
						new GenericTypeNode(
							new IdentifierTypeNode('Collection'),
							[
								new IdentifierTypeNode('array-key'),
								new IdentifierTypeNode('int'),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
								GenericTypeNode::VARIANCE_INVARIANT,
							]
						)
					),
				]),
			],
			[
				'array{foo: int}[]',
				new ArrayTypeNode(
					new ArrayShapeNode([
						new ArrayShapeItemNode(
							new IdentifierTypeNode('foo'),
							false,
							new IdentifierTypeNode('int')
						),
					])
				),
			],
			[
				'int | array{foo: int}[]',
				new UnionTypeNode([
					new IdentifierTypeNode('int'),
					new ArrayTypeNode(
						new ArrayShapeNode([
							new ArrayShapeItemNode(
								new IdentifierTypeNode('foo'),
								false,
								new IdentifierTypeNode('int')
							),
						])
					),
				]),
			],
			[
				'$this[]',
				new ArrayTypeNode(
					new ThisTypeNode()
				),
			],
			[
				'int | $this[]',
				new UnionTypeNode([
					new IdentifierTypeNode('int'),
					new ArrayTypeNode(
						new ThisTypeNode()
					),
				]),
			],
			[
				'callable(): int[]',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[],
					new ArrayTypeNode(
						new IdentifierTypeNode('int')
					)
				),
			],
			[
				'?int[]',
				new NullableTypeNode(
					new ArrayTypeNode(
						new IdentifierTypeNode('int')
					)
				),
			],
			[
				'callable(mixed...): TReturn',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [
					new CallableTypeParameterNode(new IdentifierTypeNode('mixed'), false, true, '', false),
				], new IdentifierTypeNode('TReturn')),
			],
			[
				"'foo'|'bar'",
				new UnionTypeNode([
					new ConstTypeNode(new QuoteAwareConstExprStringNode('foo', QuoteAwareConstExprStringNode::SINGLE_QUOTED)),
					new ConstTypeNode(new QuoteAwareConstExprStringNode('bar', QuoteAwareConstExprStringNode::SINGLE_QUOTED)),
				]),
			],
			[
				'Foo::FOO_CONSTANT',
				new ConstTypeNode(new ConstFetchNode('Foo', 'FOO_CONSTANT')),
			],
			[
				'123',
				new ConstTypeNode(new ConstExprIntegerNode('123')),
			],
			[
				'123.2',
				new ConstTypeNode(new ConstExprFloatNode('123.2')),
			],
			[
				'"bar"',
				new ConstTypeNode(new QuoteAwareConstExprStringNode('bar', QuoteAwareConstExprStringNode::DOUBLE_QUOTED)),
			],
			[
				'Foo::FOO_*',
				new ConstTypeNode(new ConstFetchNode('Foo', 'FOO_*')),
			],
			[
				'Foo::FOO_*BAR',
				new ConstTypeNode(new ConstFetchNode('Foo', 'FOO_*BAR')),
			],
			[
				'Foo::*FOO*',
				new ConstTypeNode(new ConstFetchNode('Foo', '*FOO*')),
			],
			[
				'Foo::A*B*C',
				new ConstTypeNode(new ConstFetchNode('Foo', 'A*B*C')),
			],
			[
				'self::*BAR',
				new ConstTypeNode(new ConstFetchNode('self', '*BAR')),
			],
			[
				'Foo::*',
				new ConstTypeNode(new ConstFetchNode('Foo', '*')),
			],
			[
				'Foo::**',
				new ConstTypeNode(new ConstFetchNode('Foo', '*')), // fails later in PhpDocParser
				Lexer::TOKEN_WILDCARD,
			],
			[
				'Foo::*a',
				new ConstTypeNode(new ConstFetchNode('Foo', '*a')),
			],
			[
				'( "foo" | Foo::FOO_* )',
				new UnionTypeNode([
					new ConstTypeNode(new QuoteAwareConstExprStringNode('foo', QuoteAwareConstExprStringNode::DOUBLE_QUOTED)),
					new ConstTypeNode(new ConstFetchNode('Foo', 'FOO_*')),
				]),
			],
			[
				'DateTimeImmutable::*|DateTime::*',
				new UnionTypeNode([
					new ConstTypeNode(new ConstFetchNode('DateTimeImmutable', '*')),
					new ConstTypeNode(new ConstFetchNode('DateTime', '*')),
				]),
			],
			[
				'ParameterTier::*|null',
				new UnionTypeNode([
					new ConstTypeNode(new ConstFetchNode('ParameterTier', '*')),
					new IdentifierTypeNode('null'),
				]),
			],
			[
				'list<QueueAttributeName::*>',
				new GenericTypeNode(new IdentifierTypeNode('list'), [
					new ConstTypeNode(new ConstFetchNode('QueueAttributeName', '*')),
				], [
					GenericTypeNode::VARIANCE_INVARIANT,
				]),
			],
			[
				'array<' . PHP_EOL .
				'  Foo' . PHP_EOL .
				'>',
				new GenericTypeNode(
					new IdentifierTypeNode('array'),
					[
						new IdentifierTypeNode('Foo'),
					],
					[
						GenericTypeNode::VARIANCE_INVARIANT,
					]
				),
			],
			[
				'array<' . PHP_EOL .
				'  Foo,' . PHP_EOL .
				'  Bar' . PHP_EOL .
				'>',
				new GenericTypeNode(
					new IdentifierTypeNode('array'),
					[
						new IdentifierTypeNode('Foo'),
						new IdentifierTypeNode('Bar'),
					],
					[
						GenericTypeNode::VARIANCE_INVARIANT,
						GenericTypeNode::VARIANCE_INVARIANT,
					]
				),
			],
			[
				'array<' . PHP_EOL .
				'  Foo, Bar' . PHP_EOL .
				'>',
				new GenericTypeNode(
					new IdentifierTypeNode('array'),
					[
						new IdentifierTypeNode('Foo'),
						new IdentifierTypeNode('Bar'),
					],
					[
						GenericTypeNode::VARIANCE_INVARIANT,
						GenericTypeNode::VARIANCE_INVARIANT,
					]
				),
			],
			[
				'array<' . PHP_EOL .
				'  Foo,' . PHP_EOL .
				'  array<' . PHP_EOL .
				'    Bar' . PHP_EOL .
				'  >' . PHP_EOL .
				'>',
				new GenericTypeNode(
					new IdentifierTypeNode('array'),
					[
						new IdentifierTypeNode('Foo'),
						new GenericTypeNode(
							new IdentifierTypeNode('array'),
							[
								new IdentifierTypeNode('Bar'),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
							]
						),
					],
					[
						GenericTypeNode::VARIANCE_INVARIANT,
						GenericTypeNode::VARIANCE_INVARIANT,
					]
				),
			],
			[
				'array<' . PHP_EOL .
				'  Foo,' . PHP_EOL .
				'  array<' . PHP_EOL .
				'    Bar,' . PHP_EOL .
				'  >' . PHP_EOL .
				'>',
				new GenericTypeNode(
					new IdentifierTypeNode('array'),
					[
						new IdentifierTypeNode('Foo'),
						new GenericTypeNode(
							new IdentifierTypeNode('array'),
							[
								new IdentifierTypeNode('Bar'),
							],
							[
								GenericTypeNode::VARIANCE_INVARIANT,
							]
						),
					],
					[
						GenericTypeNode::VARIANCE_INVARIANT,
						GenericTypeNode::VARIANCE_INVARIANT,
					]
				),
			],
			[
				'array{}',
				new ArrayShapeNode([]),
			],
			[
				'array{}|int',
				new UnionTypeNode([new ArrayShapeNode([]), new IdentifierTypeNode('int')]),
			],
			[
				'int|array{}',
				new UnionTypeNode([new IdentifierTypeNode('int'), new ArrayShapeNode([])]),
			],
			[
				'callable(' . PHP_EOL .
				'  Foo' . PHP_EOL .
				'): void',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[
						new CallableTypeParameterNode(new IdentifierTypeNode('Foo'), false, false, '', false),
					],
					new IdentifierTypeNode('void')
				),
			],
			[
				'callable(' . PHP_EOL .
				'  Foo,' . PHP_EOL .
				'  Bar' . PHP_EOL .
				'): void',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[
						new CallableTypeParameterNode(new IdentifierTypeNode('Foo'), false, false, '', false),
						new CallableTypeParameterNode(new IdentifierTypeNode('Bar'), false, false, '', false),
					],
					new IdentifierTypeNode('void')
				),
			],
			[
				'callable(' . PHP_EOL .
				'  Foo, Bar' . PHP_EOL .
				'): void',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[
						new CallableTypeParameterNode(new IdentifierTypeNode('Foo'), false, false, '', false),
						new CallableTypeParameterNode(new IdentifierTypeNode('Bar'), false, false, '', false),
					],
					new IdentifierTypeNode('void')
				),
			],
			[
				'callable(' . PHP_EOL .
				'  Foo,' . PHP_EOL .
				'  callable(' . PHP_EOL .
				'    Bar' . PHP_EOL .
				'  ): void' . PHP_EOL .
				'): void',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[
						new CallableTypeParameterNode(new IdentifierTypeNode('Foo'), false, false, '', false),
						new CallableTypeParameterNode(
							new CallableTypeNode(
								new IdentifierTypeNode('callable'),
								[
									new CallableTypeParameterNode(new IdentifierTypeNode('Bar'), false, false, '', false),
								],
								new IdentifierTypeNode('void')
							),
							false,
							false,
							'',
							false
						),
					],
					new IdentifierTypeNode('void')
				),
			],
			[
				'callable(' . PHP_EOL .
				'  Foo,' . PHP_EOL .
				'  callable(' . PHP_EOL .
				'    Bar,' . PHP_EOL .
				'  ): void' . PHP_EOL .
				'): void',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[
						new CallableTypeParameterNode(new IdentifierTypeNode('Foo'), false, false, '', false),
						new CallableTypeParameterNode(
							new CallableTypeNode(
								new IdentifierTypeNode('callable'),
								[
									new CallableTypeParameterNode(new IdentifierTypeNode('Bar'), false, false, '', false),
								],
								new IdentifierTypeNode('void')
							),
							false,
							false,
							'',
							false
						),
					],
					new IdentifierTypeNode('void')
				),
			],
			[
				'(Foo is Bar ? never : int)',
				new ConditionalTypeNode(
					new IdentifierTypeNode('Foo'),
					new IdentifierTypeNode('Bar'),
					new IdentifierTypeNode('never'),
					new IdentifierTypeNode('int'),
					false
				),
			],
			[
				'(Foo is not Bar ? never : int)',
				new ConditionalTypeNode(
					new IdentifierTypeNode('Foo'),
					new IdentifierTypeNode('Bar'),
					new IdentifierTypeNode('never'),
					new IdentifierTypeNode('int'),
					true
				),
			],
			[
				'(T is self::TYPE_STRING ? string : (T is self::TYPE_INT ? int : bool))',
				new ConditionalTypeNode(
					new IdentifierTypeNode('T'),
					new ConstTypeNode(new ConstFetchNode('self', 'TYPE_STRING')),
					new IdentifierTypeNode('string'),
					new ConditionalTypeNode(
						new IdentifierTypeNode('T'),
						new ConstTypeNode(new ConstFetchNode('self', 'TYPE_INT')),
						new IdentifierTypeNode('int'),
						new IdentifierTypeNode('bool'),
						false
					),
					false
				),
			],
			[
				'(Foo is Bar|Baz ? never : int|string)',
				new ConditionalTypeNode(
					new IdentifierTypeNode('Foo'),
					new UnionTypeNode([
						new IdentifierTypeNode('Bar'),
						new IdentifierTypeNode('Baz'),
					]),
					new IdentifierTypeNode('never'),
					new UnionTypeNode([
						new IdentifierTypeNode('int'),
						new IdentifierTypeNode('string'),
					]),
					false
				),
			],
			[
				'(' . PHP_EOL .
				'  TRandList is array ? array<TRandKey, TRandVal> : (' . PHP_EOL .
				'  TRandList is XIterator ? XIterator<TRandKey, TRandVal> :' . PHP_EOL .
				'  IteratorIterator<TRandKey, TRandVal>|LimitIterator<TRandKey, TRandVal>' . PHP_EOL .
				'))',
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
						]
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
							]
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
								]
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
								]
							),
						]),
						false
					),
					false
				),
			],
			[
				'($foo is Bar|Baz ? never : int|string)',
				new ConditionalTypeForParameterNode(
					'$foo',
					new UnionTypeNode([
						new IdentifierTypeNode('Bar'),
						new IdentifierTypeNode('Baz'),
					]),
					new IdentifierTypeNode('never'),
					new UnionTypeNode([
						new IdentifierTypeNode('int'),
						new IdentifierTypeNode('string'),
					]),
					false
				),
			],
			[
				'(' . PHP_EOL .
				'  $foo is Bar|Baz' . PHP_EOL .
				'    ? never' . PHP_EOL .
				'    : int|string' . PHP_EOL .
				')',
				new ConditionalTypeForParameterNode(
					'$foo',
					new UnionTypeNode([
						new IdentifierTypeNode('Bar'),
						new IdentifierTypeNode('Baz'),
					]),
					new IdentifierTypeNode('never'),
					new UnionTypeNode([
						new IdentifierTypeNode('int'),
						new IdentifierTypeNode('string'),
					]),
					false
				),
			],
			[
				'?Currency::CURRENCY_*',
				new NullableTypeNode(
					new ConstTypeNode(
						new ConstFetchNode(
							'Currency',
							'CURRENCY_*'
						)
					)
				),
			],
			[
				'(T is Foo ? true : T is Bar ? false : null)',
				new ConditionalTypeNode(
					new IdentifierTypeNode('T'),
					new IdentifierTypeNode('Foo'),
					new IdentifierTypeNode('true'),
					new ConditionalTypeNode(
						new IdentifierTypeNode('T'),
						new IdentifierTypeNode('Bar'),
						new IdentifierTypeNode('false'),
						new IdentifierTypeNode('null'),
						false
					),
					false
				),
			],
			[
				'(T is Foo ? T is Bar ? true : false : null)',
				new ParserException(
					'is',
					Lexer::TOKEN_IDENTIFIER,
					14,
					Lexer::TOKEN_COLON
				),
			],
			[
				'($foo is Foo ? true : $foo is Bar ? false : null)',
				new ConditionalTypeForParameterNode(
					'$foo',
					new IdentifierTypeNode('Foo'),
					new IdentifierTypeNode('true'),
					new ConditionalTypeForParameterNode(
						'$foo',
						new IdentifierTypeNode('Bar'),
						new IdentifierTypeNode('false'),
						new IdentifierTypeNode('null'),
						false
					),
					false
				),
			],
			[
				'($foo is Foo ? $foo is Bar ? true : false : null)',
				new ParserException(
					'$foo',
					Lexer::TOKEN_VARIABLE,
					15,
					Lexer::TOKEN_IDENTIFIER
				),
			],
			[
				'Foo<covariant Bar, Baz>',
				new GenericTypeNode(
					new IdentifierTypeNode('Foo'),
					[
						new IdentifierTypeNode('Bar'),
						new IdentifierTypeNode('Baz'),
					],
					[
						GenericTypeNode::VARIANCE_COVARIANT,
						GenericTypeNode::VARIANCE_INVARIANT,
					]
				),
			],
			[
				'Foo<Bar, contravariant Baz>',
				new GenericTypeNode(
					new IdentifierTypeNode('Foo'),
					[
						new IdentifierTypeNode('Bar'),
						new IdentifierTypeNode('Baz'),
					],
					[
						GenericTypeNode::VARIANCE_INVARIANT,
						GenericTypeNode::VARIANCE_CONTRAVARIANT,
					]
				),
			],
			[
				'Foo<covariant>',
				new ParserException(
					'>',
					Lexer::TOKEN_CLOSE_ANGLE_BRACKET,
					13,
					Lexer::TOKEN_IDENTIFIER
				),
			],
			[
				'Foo<typovariant Bar>',
				new ParserException(
					'Bar',
					Lexer::TOKEN_IDENTIFIER,
					16,
					Lexer::TOKEN_CLOSE_ANGLE_BRACKET
				),
			],
			[
				'Foo<Bar, *>',
				new GenericTypeNode(
					new IdentifierTypeNode('Foo'),
					[
						new IdentifierTypeNode('Bar'),
						new IdentifierTypeNode('mixed'),
					],
					[
						GenericTypeNode::VARIANCE_INVARIANT,
						GenericTypeNode::VARIANCE_BIVARIANT,
					]
				),
			],
			[
				'object{a: int}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'object{a: ?int}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new NullableTypeNode(
							new IdentifierTypeNode('int')
						)
					),
				]),
			],
			[
				'object{a?: ?int}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('a'),
						true,
						new NullableTypeNode(
							new IdentifierTypeNode('int')
						)
					),
				]),
			],
			[
				'object{a: int, b: string}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ObjectShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'object{a: int, b: array{c: callable(): int}}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ObjectShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new ArrayShapeNode([
							new ArrayShapeItemNode(
								new IdentifierTypeNode('c'),
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
				'object{a: int, b: object{c: callable(): int}}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ObjectShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new ObjectShapeNode([
							new ObjectShapeItemNode(
								new IdentifierTypeNode('c'),
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
				'?object{a: int}',
				new NullableTypeNode(
					new ObjectShapeNode([
						new ObjectShapeItemNode(
							new IdentifierTypeNode('a'),
							false,
							new IdentifierTypeNode('int')
						),
					])
				),
			],
			[
				'object{',
				new ParserException(
					'',
					Lexer::TOKEN_END,
					7,
					Lexer::TOKEN_IDENTIFIER
				),
			],
			[
				'object{a => int}',
				new ParserException(
					'=>',
					Lexer::TOKEN_OTHER,
					9,
					Lexer::TOKEN_COLON
				),
			],
			[
				'object{int}',
				new ParserException(
					'}',
					Lexer::TOKEN_CLOSE_CURLY_BRACKET,
					10,
					Lexer::TOKEN_COLON
				),
			],
			[
				'object{0: int}',
				new ParserException(
					'0',
					Lexer::TOKEN_END,
					7,
					Lexer::TOKEN_IDENTIFIER
				),
			],
			[
				'object{0?: int}',
				new ParserException(
					'0',
					Lexer::TOKEN_END,
					7,
					Lexer::TOKEN_IDENTIFIER
				),
			],
			[
				'object{"a": int}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new QuoteAwareConstExprStringNode('a', QuoteAwareConstExprStringNode::DOUBLE_QUOTED),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'object{\'a\': int}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new QuoteAwareConstExprStringNode('a', QuoteAwareConstExprStringNode::SINGLE_QUOTED),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'object{\'$ref\': int}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new QuoteAwareConstExprStringNode('$ref', QuoteAwareConstExprStringNode::SINGLE_QUOTED),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'object{"$ref": int}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new QuoteAwareConstExprStringNode('$ref', QuoteAwareConstExprStringNode::DOUBLE_QUOTED),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'object{
				 *	a: int
				 *}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'object{
				 	a: int,
				 }',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
				]),
			],
			[
				'object{
				 	a: int,
				 	b: string,
				 }',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ObjectShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'object{
				 	a: int
				 	, b: string
				 	, c: string
				 }',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ObjectShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new IdentifierTypeNode('string')
					),
					new ObjectShapeItemNode(
						new IdentifierTypeNode('c'),
						false,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'object{
				 	a: int,
				 	b: string
				 }',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('a'),
						false,
						new IdentifierTypeNode('int')
					),
					new ObjectShapeItemNode(
						new IdentifierTypeNode('b'),
						false,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'object{foo: int}[]',
				new ArrayTypeNode(
					new ObjectShapeNode([
						new ObjectShapeItemNode(
							new IdentifierTypeNode('foo'),
							false,
							new IdentifierTypeNode('int')
						),
					])
				),
			],
			[
				'int | object{foo: int}[]',
				new UnionTypeNode([
					new IdentifierTypeNode('int'),
					new ArrayTypeNode(
						new ObjectShapeNode([
							new ObjectShapeItemNode(
								new IdentifierTypeNode('foo'),
								false,
								new IdentifierTypeNode('int')
							),
						])
					),
				]),
			],
			[
				'object{}',
				new ObjectShapeNode([]),
			],
			[
				'object{}|int',
				new UnionTypeNode([new ObjectShapeNode([]), new IdentifierTypeNode('int')]),
			],
			[
				'int|object{}',
				new UnionTypeNode([new IdentifierTypeNode('int'), new ObjectShapeNode([])]),
			],
			[
				'object{attribute:string, value?:string}',
				new ObjectShapeNode([
					new ObjectShapeItemNode(
						new IdentifierTypeNode('attribute'),
						false,
						new IdentifierTypeNode('string')
					),
					new ObjectShapeItemNode(
						new IdentifierTypeNode('value'),
						true,
						new IdentifierTypeNode('string')
					),
				]),
			],
			[
				'Closure(Foo): (Closure(Foo): Bar)',
				new CallableTypeNode(
					new IdentifierTypeNode('Closure'),
					[
						new CallableTypeParameterNode(new IdentifierTypeNode('Foo'), false, false, '', false),
					],
					new CallableTypeNode(
						new IdentifierTypeNode('Closure'),
						[
							new CallableTypeParameterNode(new IdentifierTypeNode('Foo'), false, false, '', false),
						],
						new IdentifierTypeNode('Bar')
					)
				),
			],
		];
	}

}
