<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use Iterator;
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
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPUnit\Framework\TestCase;

class ConstExprParserTest extends TestCase
{

	/** @var Lexer */
	private $lexer;

	/** @var ConstExprParser */
	private $constExprParser;

	protected function setUp(): void
	{
		parent::setUp();
		$this->lexer = new Lexer();
		$this->constExprParser = new ConstExprParser(true);
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
			'-123',
			new ConstExprIntegerNode('-123'),
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
	}


	public function provideStringNodeParseData(): Iterator
	{
		yield [
			'"foo"',
			new ConstExprStringNode('"foo"'),
		];

		yield [
			'"Foo \\n\\"\\r Bar"',
			new ConstExprStringNode('"Foo \\n\\"\\r Bar"'),
		];

		yield [
			'\'bar\'',
			new ConstExprStringNode('\'bar\''),
		];

		yield [
			'\'Foo \\\' Bar\'',
			new ConstExprStringNode('\'Foo \\\' Bar\''),
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
					new ConstExprIntegerNode('123')
				),
			]),
		];

		yield [
			'[1, 2, 3]',
			new ConstExprArrayNode([
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('1')
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('2')
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('3')
				),
			]),
		];

		yield [
			'[1, 2, 3, ]',
			new ConstExprArrayNode([
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('1')
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('2')
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('3')
				),
			]),
		];

		yield [
			'[1 => 2]',
			new ConstExprArrayNode([
				new ConstExprArrayItemNode(
					new ConstExprIntegerNode('1'),
					new ConstExprIntegerNode('2')
				),
			]),
		];

		yield [
			'[1 => 2, 3]',
			new ConstExprArrayNode([
				new ConstExprArrayItemNode(
					new ConstExprIntegerNode('1'),
					new ConstExprIntegerNode('2')
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('3')
				),
			]),
		];

		yield [
			'[1, [2, 3]]',
			new ConstExprArrayNode([
				new ConstExprArrayItemNode(
					null,
					new ConstExprIntegerNode('1')
				),
				new ConstExprArrayItemNode(
					null,
					new ConstExprArrayNode([
						new ConstExprArrayItemNode(
							null,
							new ConstExprIntegerNode('2')
						),
						new ConstExprArrayItemNode(
							null,
							new ConstExprIntegerNode('3')
						),
					])
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

	/**
	 * @dataProvider provideWithTrimStringsStringNodeParseData
	 */
	public function testParseWithTrimStrings(string $input, ConstExprNode $expectedExpr, int $nextTokenType = Lexer::TOKEN_END): void
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$exprNode = $this->constExprParser->parse($tokens, true);

		$this->assertSame((string) $expectedExpr, (string) $exprNode);
		$this->assertEquals($expectedExpr, $exprNode);
		$this->assertSame($nextTokenType, $tokens->currentTokenType());
	}

	public function provideWithTrimStringsStringNodeParseData(): Iterator
	{
		yield [
			'"foo"',
			new ConstExprStringNode('foo'),
		];

		yield [
			'"Foo \\n\\"\\r Bar"',
			new ConstExprStringNode("Foo \n\"\r Bar"),
		];

		yield [
			'\'bar\'',
			new ConstExprStringNode('bar'),
		];

		yield [
			'\'Foo \\\' Bar\'',
			new ConstExprStringNode('Foo \' Bar'),
		];

		yield [
			'"\u{1f601}"',
			new ConstExprStringNode("\u{1f601}"),
		];

		yield [
			'"\u{ffffffff}"',
			new ConstExprStringNode("\u{fffd}"),
		];
	}

}
