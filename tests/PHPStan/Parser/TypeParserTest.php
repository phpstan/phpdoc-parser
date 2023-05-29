<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use Exception;
use PHPStan\PhpDocParser\Ast\Attribute;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFloatNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\QuoteAwareConstExprStringNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
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
use PHPStan\PhpDocParser\Printer\Printer;
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
		$this->assertSame($nextTokenType, $tokens->currentTokenType(), Lexer::TOKEN_LABELS[$nextTokenType]);

		if (strpos((string) $expectedResult, '$ref') !== false) {
			// weird case with $ref inside double-quoted string - not really possible in PHP
			return;
		}

		$this->assertPrintedNodeViaToString($typeNode);
		$this->assertPrintedNodeViaPrinter($typeNode);
	}


	private function assertPrintedNodeViaToString(TypeNode $typeNode): void
	{
		$this->assertPrintedNode($typeNode, (string) $typeNode);
	}


	private function assertPrintedNodeViaPrinter(TypeNode $typeNode): void
	{
		$printer = new Printer();
		$this->assertPrintedNode($typeNode, $printer->print($typeNode));
	}


	private function assertPrintedNode(TypeNode $typeNode, string $typeNodeString): void
	{
		$typeNodeTokens = new TokenIterator($this->lexer->tokenize($typeNodeString));
		$parsedAgainTypeNode = $this->typeParser->parse($typeNodeTokens);
		$this->assertSame((string) $typeNode, (string) $parsedAgainTypeNode);
		$this->assertInstanceOf(get_class($typeNode), $parsedAgainTypeNode);
		$this->assertEquals($typeNode, $parsedAgainTypeNode);
	}


	/**
	 * @dataProvider provideParseData
	 * @param TypeNode|Exception $expectedResult
	 */
	public function testVerifyAttributes(string $input, $expectedResult): void
	{
		if ($expectedResult instanceof Exception) {
			$this->expectException(get_class($expectedResult));
			$this->expectExceptionMessage($expectedResult->getMessage());
		}

		$usedAttributes = ['lines' => true, 'indexes' => true];
		$typeParser = new TypeParser(new ConstExprParser(true, true, $usedAttributes), true, $usedAttributes);
		$tokens = new TokenIterator($this->lexer->tokenize($input));

		$visitor = new NodeCollectingVisitor();
		$traverser = new NodeTraverser([$visitor]);
		$traverser->traverse([$typeParser->parse($tokens)]);

		foreach ($visitor->nodes as $node) {
			$this->assertNotNull($node->getAttribute(Attribute::START_LINE), (string) $node);
			$this->assertNotNull($node->getAttribute(Attribute::END_LINE), (string) $node);
			$this->assertNotNull($node->getAttribute(Attribute::START_INDEX), (string) $node);
			$this->assertNotNull($node->getAttribute(Attribute::END_INDEX), (string) $node);
		}
	}


	/**
	 * @return array<mixed>
	 */
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
				'callable(): Foo<Bar>[]',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[],
					new ArrayTypeNode(new GenericTypeNode(
						new IdentifierTypeNode('Foo'),
						[
							new IdentifierTypeNode('Bar'),
						],
						[
							GenericTypeNode::VARIANCE_INVARIANT,
						]
					))
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
				'123_456',
				new ConstTypeNode(new ConstExprIntegerNode('123456')),
			],
			[
				'_123',
				new IdentifierTypeNode('_123'),
			],
			[
				'123_',
				new ConstTypeNode(new ConstExprIntegerNode('123')),
				Lexer::TOKEN_IDENTIFIER,
			],
			[
				'123.2',
				new ConstTypeNode(new ConstExprFloatNode('123.2')),
			],
			[
				'123_456.789_012',
				new ConstTypeNode(new ConstExprFloatNode('123456.789012')),
			],
			[
				'+0x10_20|+8e+2 | -0b11',
				new UnionTypeNode([
					new ConstTypeNode(new ConstExprIntegerNode('+0x1020')),
					new ConstTypeNode(new ConstExprFloatNode('+8e+2')),
					new ConstTypeNode(new ConstExprIntegerNode('-0b11')),
				]),
			],
			[
				'18_446_744_073_709_551_616|8.2023437675747321e-18_446_744_073_709_551_617',
				new UnionTypeNode([
					new ConstTypeNode(new ConstExprIntegerNode('18446744073709551616')),
					new ConstTypeNode(new ConstExprFloatNode('8.2023437675747321e-18446744073709551617')),
				]),
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
			[
				'callable(): ?int',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new NullableTypeNode(new IdentifierTypeNode('int'))),
			],
			[
				'callable(): object{foo: int}',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ObjectShapeNode([
					new ObjectShapeItemNode(new IdentifierTypeNode('foo'), false, new IdentifierTypeNode('int')),
				])),
			],
			[
				'callable(): object{foo: int}[]',
				new CallableTypeNode(
					new IdentifierTypeNode('callable'),
					[],
					new ArrayTypeNode(
						new ObjectShapeNode([
							new ObjectShapeItemNode(new IdentifierTypeNode('foo'), false, new IdentifierTypeNode('int')),
						])
					)
				),
			],
			[
				'callable(): int[][][]',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ArrayTypeNode(new ArrayTypeNode(new ArrayTypeNode(new IdentifierTypeNode('int'))))),
			],
			[
				'callable(): (int[][][])',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ArrayTypeNode(new ArrayTypeNode(new ArrayTypeNode(new IdentifierTypeNode('int'))))),
			],
			[
				'(callable(): int[])[][]',
				new ArrayTypeNode(
					new ArrayTypeNode(
						new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ArrayTypeNode(new IdentifierTypeNode('int')))
					)
				),
			],
			[
				'callable(): $this',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ThisTypeNode()),
			],
			[
				'callable(): $this[]',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ArrayTypeNode(new ThisTypeNode())),
			],
			[
				'2.5|3',
				new UnionTypeNode([
					new ConstTypeNode(new ConstExprFloatNode('2.5')),
					new ConstTypeNode(new ConstExprIntegerNode('3')),
				]),
			],
			[
				'callable(): 3.5',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ConstTypeNode(new ConstExprFloatNode('3.5'))),
			],
			[
				'callable(): 3.5[]',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ArrayTypeNode(
					new ConstTypeNode(new ConstExprFloatNode('3.5'))
				)),
			],
			[
				'callable(): Foo',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new IdentifierTypeNode('Foo')),
			],
			[
				'callable(): (Foo)[]',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ArrayTypeNode(new IdentifierTypeNode('Foo'))),
			],
			[
				'callable(): Foo::BAR',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ConstTypeNode(new ConstFetchNode('Foo', 'BAR'))),
			],
			[
				'callable(): Foo::*',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ConstTypeNode(new ConstFetchNode('Foo', '*'))),
			],
			[
				'?Foo[]',
				new NullableTypeNode(new ArrayTypeNode(new IdentifierTypeNode('Foo'))),
			],
			[
				'callable(): ?Foo',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new NullableTypeNode(new IdentifierTypeNode('Foo'))),
			],
			[
				'callable(): ?Foo[]',
				new CallableTypeNode(new IdentifierTypeNode('callable'), [], new NullableTypeNode(new ArrayTypeNode(new IdentifierTypeNode('Foo')))),
			],
			[
				'?(Foo|Bar)',
				new NullableTypeNode(new UnionTypeNode([
					new IdentifierTypeNode('Foo'),
					new IdentifierTypeNode('Bar'),
				])),
			],
			[
				'Foo | (Bar & Baz)',
				new UnionTypeNode([
					new IdentifierTypeNode('Foo'),
					new IntersectionTypeNode([
						new IdentifierTypeNode('Bar'),
						new IdentifierTypeNode('Baz'),
					]),
				]),
			],
			[
				'(?Foo) | Bar',
				new UnionTypeNode([
					new NullableTypeNode(new IdentifierTypeNode('Foo')),
					new IdentifierTypeNode('Bar'),
				]),
			],
			[
				'?(Foo|Bar)',
				new NullableTypeNode(new UnionTypeNode([
					new IdentifierTypeNode('Foo'),
					new IdentifierTypeNode('Bar'),
				])),
			],
			[
				'?(Foo&Bar)',
				new NullableTypeNode(new IntersectionTypeNode([
					new IdentifierTypeNode('Foo'),
					new IdentifierTypeNode('Bar'),
				])),
			],
			[
				'?Foo[]',
				new NullableTypeNode(new ArrayTypeNode(new IdentifierTypeNode('Foo'))),
			],
			[
				'(?Foo)[]',
				new ArrayTypeNode(new NullableTypeNode(new IdentifierTypeNode('Foo'))),
			],
			[
				'Foo | Bar | (Baz | Lorem)',
				new UnionTypeNode([
					new IdentifierTypeNode('Foo'),
					new IdentifierTypeNode('Bar'),
					new UnionTypeNode([
						new IdentifierTypeNode('Baz'),
						new IdentifierTypeNode('Lorem'),
					]),
				]),
			],
		];
	}

	/**
	 * @return array<mixed>
	 */
	public function dataLinesAndIndexes(): iterable
	{
		yield [
			'int | object{foo: int}[]',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'int | object{foo: int}[]',
					1,
					1,
					0,
					12,
				],
				[
					static function (UnionTypeNode $typeNode): TypeNode {
						return $typeNode->types[0];
					},
					'int',
					1,
					1,
					0,
					0,
				],
				[
					static function (UnionTypeNode $typeNode): TypeNode {
						return $typeNode->types[1];
					},
					'object{foo: int}[]',
					1,
					1,
					4,
					12,
				],
			],
		];

		yield [
			'int | object{foo: int}[]    ',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'int | object{foo: int}[]',
					1,
					1,
					0,
					12,
				],
				[
					static function (UnionTypeNode $typeNode): TypeNode {
						return $typeNode->types[0];
					},
					'int',
					1,
					1,
					0,
					0,
				],
				[
					static function (UnionTypeNode $typeNode): TypeNode {
						return $typeNode->types[1];
					},
					'object{foo: int}[]',
					1,
					1,
					4,
					12,
				],
			],
		];

		yield [
			'array{
				a: int,
				b: string
			 }',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'array{
				a: int,
				b: string
			 }',
					1,
					4,
					0,
					14,
				],
			],
		];

		yield [
			'callable(Foo, Bar): void',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'callable(Foo, Bar): void',
					1,
					1,
					0,
					9,
				],
				[
					static function (CallableTypeNode $typeNode): TypeNode {
						return $typeNode->identifier;
					},
					'callable',
					1,
					1,
					0,
					0,
				],
				[
					static function (CallableTypeNode $typeNode): Node {
						return $typeNode->parameters[0];
					},
					'Foo',
					1,
					1,
					2,
					2,
				],
				[
					static function (CallableTypeNode $typeNode): TypeNode {
						return $typeNode->returnType;
					},
					'void',
					1,
					1,
					9,
					9,
				],
			],
		];

		yield [
			'$this',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'$this',
					1,
					1,
					0,
					0,
				],
			],
		];

		yield [
			'array{foo: int}',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'array{foo: int}',
					1,
					1,
					0,
					6,
				],
				[
					static function (ArrayShapeNode $typeNode): TypeNode {
						return $typeNode->items[0];
					},
					'foo: int',
					1,
					1,
					2,
					5,
				],
			],
		];

		yield [
			'array{}',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'array{}',
					1,
					1,
					0,
					2,
				],
			],
		];

		yield [
			'object{foo: int}',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'object{foo: int}',
					1,
					1,
					0,
					6,
				],
				[
					static function (ObjectShapeNode $typeNode): TypeNode {
						return $typeNode->items[0];
					},
					'foo: int',
					1,
					1,
					2,
					5,
				],
			],
		];

		yield [
			'object{}',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'object{}',
					1,
					1,
					0,
					2,
				],
			],
		];

		yield [
			'object{}[]',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'object{}[]',
					1,
					1,
					0,
					4,
				],
			],
		];

		yield [
			'int[][][]',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'int[][][]',
					1,
					1,
					0,
					6,
				],
				[
					static function (ArrayTypeNode $typeNode): TypeNode {
						return $typeNode->type;
					},
					'int[][]',
					1,
					1,
					0,
					4,
				],
				[
					static function (ArrayTypeNode $typeNode): TypeNode {
						if (!$typeNode->type instanceof ArrayTypeNode) {
							throw new Exception();
						}

						return $typeNode->type->type;
					},
					'int[]',
					1,
					1,
					0,
					2,
				],
				[
					static function (ArrayTypeNode $typeNode): TypeNode {
						if (!$typeNode->type instanceof ArrayTypeNode) {
							throw new Exception();
						}
						if (!$typeNode->type->type instanceof ArrayTypeNode) {
							throw new Exception();
						}

						return $typeNode->type->type->type;
					},
					'int',
					1,
					1,
					0,
					0,
				],
			],
		];

		yield [
			'int[foo][bar][baz]',
			[
				[
					static function (TypeNode $typeNode): TypeNode {
						return $typeNode;
					},
					'int[foo][bar][baz]',
					1,
					1,
					0,
					9,
				],
				[
					static function (OffsetAccessTypeNode $typeNode): TypeNode {
						return $typeNode->type;
					},
					'int[foo][bar]',
					1,
					1,
					0,
					6,
				],
				[
					static function (OffsetAccessTypeNode $typeNode): TypeNode {
						return $typeNode->offset;
					},
					'baz',
					1,
					1,
					8,
					8,
				],
				[
					static function (OffsetAccessTypeNode $typeNode): TypeNode {
						if (!$typeNode->type instanceof OffsetAccessTypeNode) {
							throw new Exception();
						}

						return $typeNode->type->type;
					},
					'int[foo]',
					1,
					1,
					0,
					3,
				],
				[
					static function (OffsetAccessTypeNode $typeNode): TypeNode {
						if (!$typeNode->type instanceof OffsetAccessTypeNode) {
							throw new Exception();
						}

						return $typeNode->type->offset;
					},
					'bar',
					1,
					1,
					5,
					5,
				],
				[
					static function (OffsetAccessTypeNode $typeNode): TypeNode {
						if (!$typeNode->type instanceof OffsetAccessTypeNode) {
							throw new Exception();
						}
						if (!$typeNode->type->type instanceof OffsetAccessTypeNode) {
							throw new Exception();
						}

						return $typeNode->type->type->type;
					},
					'int',
					1,
					1,
					0,
					0,
				],
				[
					static function (OffsetAccessTypeNode $typeNode): TypeNode {
						if (!$typeNode->type instanceof OffsetAccessTypeNode) {
							throw new Exception();
						}
						if (!$typeNode->type->type instanceof OffsetAccessTypeNode) {
							throw new Exception();
						}

						return $typeNode->type->type->offset;
					},
					'foo',
					1,
					1,
					2,
					2,
				],
			],
		];
	}

	/**
	 * @dataProvider dataLinesAndIndexes
	 * @param list<array{callable(Node): Node, string, int, int, int, int}> $assertions
	 */
	public function testLinesAndIndexes(string $input, array $assertions): void
	{
		$tokensArray = $this->lexer->tokenize($input);
		$tokens = new TokenIterator($tokensArray);
		$usedAttributes = [
			'lines' => true,
			'indexes' => true,
		];
		$typeParser = new TypeParser(new ConstExprParser(true, true), true, $usedAttributes);
		$typeNode = $typeParser->parse($tokens);

		foreach ($assertions as [$callable, $expectedContent, $startLine, $endLine, $startIndex, $endIndex]) {
			$typeToAssert = $callable($typeNode);

			$this->assertSame($startLine, $typeToAssert->getAttribute(Attribute::START_LINE));
			$this->assertSame($endLine, $typeToAssert->getAttribute(Attribute::END_LINE));
			$this->assertSame($startIndex, $typeToAssert->getAttribute(Attribute::START_INDEX));
			$this->assertSame($endIndex, $typeToAssert->getAttribute(Attribute::END_INDEX));

			$content = '';
			for ($i = $startIndex; $i <= $endIndex; $i++) {
				$content .= $tokensArray[$i][Lexer::VALUE_OFFSET];
			}
			$this->assertSame($expectedContent, $content);
		}
	}

}
