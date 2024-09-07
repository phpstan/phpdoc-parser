<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use Iterator;
use PHPStan\PhpDocParser\Ast\Attribute;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayItemNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFalseNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFloatNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNullNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprTrueNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use PHPUnit\Framework\TestCase;

class ConstExprParserTest extends TestCase
{

	private Lexer $lexer;

	private ConstExprParser $constExprParser;

	protected function setUp(): void
	{
		parent::setUp();
		$config = new ParserConfig([]);
		$this->lexer = new Lexer($config);
		$this->constExprParser = new ConstExprParser($config);
	}


	/**
	 * @dataProvider provideTrueNodeParseData
	 * @dataProvider provideFalseNodeParseData
	 * @dataProvider provideNullNodeParseData
	 * @dataProvider provideIntegerNodeParseData
	 * @dataProvider provideFloatNodeParseData
	 * @dataProvider provideStringNodeParseData
	 * @dataProvider provideArrayNodeParseData
	 * @dataProvider provideFetchNodeParseData
	 */
	public function testParse(string $input, ConstExprNode $expectedExpr, int $nextTokenType = Lexer::TOKEN_END): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$exprNode = $this->constExprParser->parse($tokens);

		$this->assertSame((string) $expectedExpr, (string) $exprNode);
		$this->assertEquals($expectedExpr, $exprNode);
		$this->assertSame($nextTokenType, $tokens->currentTokenType());
	}


	/**
	 * @dataProvider provideTrueNodeParseData
	 * @dataProvider provideFalseNodeParseData
	 * @dataProvider provideNullNodeParseData
	 * @dataProvider provideIntegerNodeParseData
	 * @dataProvider provideFloatNodeParseData
	 * @dataProvider provideStringNodeParseData
	 * @dataProvider provideArrayNodeParseData
	 * @dataProvider provideFetchNodeParseData
	 */
	public function testVerifyAttributes(string $input): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$config = new ParserConfig([
			'lines' => true,
			'indexes' => true,
		]);
		$constExprParser = new ConstExprParser($config);
		$visitor = new NodeCollectingVisitor();
		$traverser = new NodeTraverser([$visitor]);
		$traverser->traverse([$constExprParser->parse($tokens)]);

		foreach ($visitor->nodes as $node) {
			$this->assertNotNull($node->getAttribute(Attribute::START_LINE), (string) $node);
			$this->assertNotNull($node->getAttribute(Attribute::END_LINE), (string) $node);
			$this->assertNotNull($node->getAttribute(Attribute::START_INDEX), (string) $node);
			$this->assertNotNull($node->getAttribute(Attribute::END_INDEX), (string) $node);
		}
	}


	public function provideTrueNodeParseData(): Iterator
	{
		yield [
			'true',
			new ConstExprTrueNode(),
		];

		yield [
			'TRUE',
			new ConstExprTrueNode(),
		];

		yield [
			'tRUe',
			new ConstExprTrueNode(),
		];
	}


	public function provideFalseNodeParseData(): Iterator
	{
		yield [
			'false',
			new ConstExprFalseNode(),
		];

		yield [
			'FALSE',
			new ConstExprFalseNode(),
		];

		yield [
			'fALse',
			new ConstExprFalseNode(),
		];
	}


	public function provideNullNodeParseData(): Iterator
	{
		yield [
			'null',
			new ConstExprNullNode(),
		];

		yield [
			'NULL',
			new ConstExprNullNode(),
		];

		yield [
			'nULl',
			new ConstExprNullNode(),
		];
	}


	public function provideIntegerNodeParseData(): Iterator
	{
		yield [
			'123',
			new ConstExprIntegerNode('123'),
		];

		yield [
			'+123',
			new ConstExprIntegerNode('+123'),
		];

		yield [
			'-123',
			new ConstExprIntegerNode('-123'),
		];

		yield [
			'0b0110101',
			new ConstExprIntegerNode('0b0110101'),
		];

		yield [
			'0o777',
			new ConstExprIntegerNode('0o777'),
		];

		yield [
			'0x7Fb4',
			new ConstExprIntegerNode('0x7Fb4'),
		];

		yield [
			'-0O777',
			new ConstExprIntegerNode('-0O777'),
		];

		yield [
			'-0X7Fb4',
			new ConstExprIntegerNode('-0X7Fb4'),
		];

		yield [
			'123_456',
			new ConstExprIntegerNode('123456'),
		];

		yield [
			'0b01_01_01',
			new ConstExprIntegerNode('0b010101'),
		];

		yield [
			'-0X7_Fb_4',
			new ConstExprIntegerNode('-0X7Fb4'),
		];

		yield [
			'18_446_744_073_709_551_616', // 64-bit unsigned long + 1, larger than PHP_INT_MAX
			new ConstExprIntegerNode('18446744073709551616'),
		];
	}


	public function provideFloatNodeParseData(): Iterator
	{
		yield [
			'123.4',
			new ConstExprFloatNode('123.4'),
		];

		yield [
			'.123',
			new ConstExprFloatNode('.123'),
		];

		yield [
			'123.',
			new ConstExprFloatNode('123.'),
		];

		yield [
			'123e4',
			new ConstExprFloatNode('123e4'),
		];

		yield [
			'123E4',
			new ConstExprFloatNode('123E4'),
		];

		yield [
			'12.3e4',
			new ConstExprFloatNode('12.3e4'),
		];

		yield [
			'+123.5',
			new ConstExprFloatNode('+123.5'),
		];

		yield [
			'-123.',
			new ConstExprFloatNode('-123.'),
		];

		yield [
			'-123.4',
			new ConstExprFloatNode('-123.4'),
		];

		yield [
			'-.123',
			new ConstExprFloatNode('-.123'),
		];

		yield [
			'-123.',
			new ConstExprFloatNode('-123.'),
		];

		yield [
			'-123e-4',
			new ConstExprFloatNode('-123e-4'),
		];

		yield [
			'-12.3e-4',
			new ConstExprFloatNode('-12.3e-4'),
		];

		yield [
			'-1_2.3_4e5_6',
			new ConstExprFloatNode('-12.34e56'),
		];

		yield [
			'123.4e+8',
			new ConstExprFloatNode('123.4e+8'),
		];

		yield [
			'.4e+8',
			new ConstExprFloatNode('.4e+8'),
		];

		yield [
			'123E+80',
			new ConstExprFloatNode('123E+80'),
		];

		yield [
			'8.2023437675747321', // greater precision than 64-bit double
			new ConstExprFloatNode('8.2023437675747321'),
		];

		yield [
			'-0.0',
			new ConstExprFloatNode('-0.0'),
		];
	}


	public function provideStringNodeParseData(): Iterator
	{
		yield [
			'"foo"',
			new ConstExprStringNode('foo', ConstExprStringNode::DOUBLE_QUOTED),
		];

		yield [
			'"Foo \\n\\"\\r Bar"',
			new ConstExprStringNode("Foo \n\"\r Bar", ConstExprStringNode::DOUBLE_QUOTED),
		];

		yield [
			'\'bar\'',
			new ConstExprStringNode('bar', ConstExprStringNode::SINGLE_QUOTED),
		];

		yield [
			'\'Foo \\\' Bar\'',
			new ConstExprStringNode('Foo \' Bar', ConstExprStringNode::SINGLE_QUOTED),
		];

		yield [
			'"\u{1f601}"',
			new ConstExprStringNode("\u{1f601}", ConstExprStringNode::DOUBLE_QUOTED),
		];

		yield [
			'"\u{ffffffff}"',
			new ConstExprStringNode("\u{fffd}", ConstExprStringNode::DOUBLE_QUOTED),
		];
	}


	public function provideArrayNodeParseData(): Iterator
	{
		yield [
			'[]',
			new ConstExprArrayNode([]),
		];

		yield [
			'[123]',
			new ConstExprArrayNode([
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('123'),
				),
			]),
		];

		yield [
			'[1, 2, 3]',
			new ConstExprArrayNode([
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('1'),
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('2'),
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('3'),
				),
			]),
		];

		yield [
			'[1, 2, 3, ]',
			new ConstExprArrayNode([
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('1'),
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('2'),
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('3'),
				),
			]),
		];

		yield [
			'[1 => 2]',
			new ConstExprArrayNode([
				new ConstExprArrayItemNode(
					new ConstExprIntegerNode('1'),
					new ConstExprIntegerNode('2'),
				),
			]),
		];

		yield [
			'[1 => 2, 3]',
			new ConstExprArrayNode([
				new ConstExprArrayItemNode(
					new ConstExprIntegerNode('1'),
					new ConstExprIntegerNode('2'),
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('3'),
				),
			]),
		];

		yield [
			'[1, [2, 3]]',
			new ConstExprArrayNode([
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('1'),
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprArrayNode([
						new ConstExprArrayItemNode(
							null,
							new ConstExprIntegerNode('2'),
						),
						new ConstExprArrayItemNode(
							null,
							new ConstExprIntegerNode('3'),
						),
					]),
				),
			]),
		];
	}


	public function provideFetchNodeParseData(): Iterator
	{
		yield [
			'GLOBAL_CONSTANT',
			new ConstFetchNode('', 'GLOBAL_CONSTANT'),
		];

		yield [
			'Foo\\Bar\\GLOBAL_CONSTANT',
			new ConstFetchNode('', 'Foo\\Bar\\GLOBAL_CONSTANT'),
		];

		yield [
			'Foo\\Bar::CLASS_CONSTANT',
			new ConstFetchNode('Foo\\Bar', 'CLASS_CONSTANT'),
		];

		yield [
			'self::CLASS_CONSTANT',
			new ConstFetchNode('self', 'CLASS_CONSTANT'),
		];
	}

}
