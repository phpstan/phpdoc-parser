<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

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

class ConstExprParserTest extends \PHPUnit\Framework\TestCase
{

	/** @var Lexer */
	private $lexer;

	/** @var ConstExprParser */
	private $constExprParser;

	protected function setUp()
	{
		parent::setUp();
		$this->lexer = new Lexer();
		$this->constExprParser = new ConstExprParser();
	}


	/**
	 * @dataProvider provideParseData
	 * @param string        $input
	 * @param ConstExprNode $expectedExpr
	 * @param int           $nextTokenType
	 */
	public function testParse(string $input, ConstExprNode $expectedExpr, int $nextTokenType = Lexer::TOKEN_END)
	{
		$tokens = new TokenIterator($this->lexer->tokenize($input));
		$exprNode = $this->constExprParser->parse($tokens);

		$this->assertSame((string) $expectedExpr, (string) $exprNode);
		$this->assertEquals($expectedExpr, $exprNode);
		$this->assertSame($nextTokenType, $tokens->currentTokenType());
	}


	public function provideParseData(): array
	{
		return [
			[
				'true',
				new ConstExprTrueNode(),
			],
			[
				'True',
				new ConstExprTrueNode(),
			],
			[
				'false',
				new ConstExprFalseNode(),
			],
			[
				'False',
				new ConstExprFalseNode(),
			],
			[
				'null',
				new ConstExprNullNode(),
			],
			[
				'Null',
				new ConstExprNullNode(),
			],
			[
				'123',
				new ConstExprIntegerNode('123'),
			],
			[
				'123.4',
				new ConstExprFloatNode('123.4'),
			],
			[
				'.123',
				new ConstExprFloatNode('.123'),
			],
			[
				'123.',
				new ConstExprFloatNode('123.'),
			],
			[
				'123e4',
				new ConstExprFloatNode('123e4'),
			],
			[
				'12.3e4',
				new ConstExprFloatNode('12.3e4'),
			],
			[
				'-123',
				new ConstExprIntegerNode('-123'),
			],
			[
				'-123.4',
				new ConstExprFloatNode('-123.4'),
			],
			[
				'-.123',
				new ConstExprFloatNode('-.123'),
			],
			[
				'-123.',
				new ConstExprFloatNode('-123.'),
			],
			[
				'-123e-4',
				new ConstExprFloatNode('-123e-4'),
			],
			[
				'-12.3e-4',
				new ConstExprFloatNode('-12.3e-4'),
			],
			[
				'"foo"',
				new ConstExprStringNode('"foo"'),
			],
			[
				'\'bar\'',
				new ConstExprStringNode('\'bar\''),
			],
			[
				'GLOBAL_CONSTANT',
				new ConstFetchNode('', 'GLOBAL_CONSTANT'),
			],
			[
				'Foo\\Bar\\GLOBAL_CONSTANT',
				new ConstFetchNode('', 'Foo\\Bar\\GLOBAL_CONSTANT'),
			],
			[
				'Foo\\Bar::CLASS_CONSTANT',
				new ConstFetchNode('Foo\\Bar', 'CLASS_CONSTANT'),
			],
			[
				'self::CLASS_CONSTANT',
				new ConstFetchNode('self', 'CLASS_CONSTANT'),
			],
			[
				'[]',
				new ConstExprArrayNode([]),
			],
			[
				'[123]',
				new ConstExprArrayNode([
					new ConstExprArrayItemNode(
						null,
						new ConstExprIntegerNode('123')
					),
				]),
			],
			[
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
			],
			[
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
			],
			[
				'[1 => 2]',
				new ConstExprArrayNode([
					new ConstExprArrayItemNode(
						new ConstExprIntegerNode('1'),
						new ConstExprIntegerNode('2')
					),
				]),
			],
			[
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
			],
			[
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
			],
		];
	}

}
