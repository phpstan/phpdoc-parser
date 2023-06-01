<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\ToString;

use Generator;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayItemNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFalseNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFloatNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNullNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprTrueNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Printer\Printer;
use PHPUnit\Framework\TestCase;

class ConstExprToStringTest extends TestCase
{

	/**
	 * @dataProvider provideConstExprCases
	 */
	public function testToString(string $expected, Node $node): void
	{
		$this->assertSame($expected, (string) $node);
	}

	/**
	 * @dataProvider provideConstExprCases
	 */
	public function testPrinter(string $expected, Node $node): void
	{
		$printer = new Printer();
		$this->assertSame($expected, $printer->print($node));
	}

	public static function provideConstExprCases(): Generator
	{
		yield from [
			['null', new ConstExprNullNode()],
			['true', new ConstExprTrueNode()],
			['false', new ConstExprFalseNode()],
			['8', new ConstExprIntegerNode('8')],
			['21.37', new ConstExprFloatNode('21.37')],
			['\'foo\'', new ConstExprStringNode('foo', ConstExprStringNode::SINGLE_QUOTED)],
			['"foo"', new ConstExprStringNode('foo', ConstExprStringNode::DOUBLE_QUOTED)],
			['FooBar', new ConstFetchNode('', 'FooBar')],
			['Foo\\Bar::Baz', new ConstFetchNode('Foo\\Bar', 'Baz')],
			['[]', new ConstExprArrayNode([])],
			[
				"['foo', 4 => 'foo', 'bar' => 'baz']",
				new ConstExprArrayNode([
					new ConstExprArrayItemNode(null, new ConstExprStringNode('foo', ConstExprStringNode::SINGLE_QUOTED)),
					new ConstExprArrayItemNode(new ConstExprIntegerNode('4'), new ConstExprStringNode('foo', ConstExprStringNode::SINGLE_QUOTED)),
					new ConstExprArrayItemNode(new ConstExprStringNode('bar', ConstExprStringNode::SINGLE_QUOTED), new ConstExprStringNode('baz', ConstExprStringNode::SINGLE_QUOTED)),
				]),
			],
		];
	}

}
