<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use PHPStan\PhpDocParser\Ast\AbstractNodeVisitor;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\NodeVisitor;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use function array_splice;
use function array_unshift;
use function count;

class PrintArrayShapeWithSingleLineCommentTest extends PrinterTestBase
{

	/**
	 * @return iterable<array{string, string}>
	 */
	public function dataPrintArrayFormatPreservingAddFront(): iterable
	{
		yield [
			self::nowdoc('
				/**
				 * @param array{} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{float} $foo
				 */'),
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{string} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{// A fractional number
				 *  float,
				 *  string} $foo
				 */'),
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{
				 *   string,int} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{
				 *   // A fractional number
				 *   float,
				 *   string,int} $foo
				 */'),
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{
				 *   string,
				 *	 int
				 * } $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{
				 *   // A fractional number
				 *   float,
				 *   string,
				 *   int
				 * } $foo
				 */'),
		];
	}

	/**
	 * @dataProvider dataPrintArrayFormatPreservingAddFront
	 */
	public function testPrintFormatPreservingSingleLineAddFront(string $phpDoc, string $expectedResult): void
	{
		$visitor = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ArrayShapeNode) {
					array_unshift($node->items, PrinterTestBase::withComment(
						new ArrayShapeItemNode(null, false, new IdentifierTypeNode('float')),
						'// A fractional number'
					));
				}

				return $node;
			}

		};

		$lexer = new Lexer(true);
		$tokens = new TokenIterator($lexer->tokenize($phpDoc));
		$phpDocNode = $this->phpDocParser->parse($tokens);
		$cloningTraverser = new NodeTraverser([new NodeVisitor\CloningVisitor()]);
		$newNodes = $cloningTraverser->traverse([$phpDocNode]);

		$changingTraverser = new NodeTraverser([$visitor]);

		/** @var PhpDocNode $newNode */
		[$newNode] = $changingTraverser->traverse($newNodes);

		$printer = new Printer();
		$actualResult = $printer->printFormatPreserving($newNode, $phpDocNode, $tokens);
		$this->assertSame($expectedResult, $actualResult);

		$this->assertEquals(
			$this->unsetAttributes($newNode),
			$this->unsetAttributes($this->phpDocParser->parse(new TokenIterator($lexer->tokenize($actualResult))))
		);
	}


	/**
	 * @return iterable<array{string, string}>
	 */
	public function dataPrintArrayFormatPreservingAddMiddle(): iterable
	{
		yield [
			self::nowdoc('
				/**
				 * @param array{} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{float} $foo
				 */'),
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{string} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{string,
				 *  // A fractional number
				 *  float} $foo
				 */'),
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{
				 *   string,int} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{
				 *   string,
				 *   // A fractional number
				 *   float,int} $foo
				 */'),
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{
				 *   string,
				 *	 int
				 * } $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{
				 *   string,
				 *   // A fractional number
				 *   float,
				 *   int
				 * } $foo
				 */'),
		];
	}

	/**
	 * @dataProvider dataPrintArrayFormatPreservingAddMiddle
	 */
	public function testPrintFormatPreservingSingleLineAddMiddle(string $phpDoc, string $expectedResult): void
	{
		$visitor = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				$newItem = PrinterTestBase::withComment(
					new ArrayShapeItemNode(null, false, new IdentifierTypeNode('float')),
					'// A fractional number'
				);

				if ($node instanceof ArrayShapeNode) {
					if (count($node->items) === 0) {
						$node->items[] = $newItem;
					} else {
						array_splice($node->items, 1, 0, [$newItem]);
					}
				}

				return $node;
			}

		};

		$lexer = new Lexer(true);
		$tokens = new TokenIterator($lexer->tokenize($phpDoc));
		$phpDocNode = $this->phpDocParser->parse($tokens);
		$cloningTraverser = new NodeTraverser([new NodeVisitor\CloningVisitor()]);
		$newNodes = $cloningTraverser->traverse([$phpDocNode]);

		$changingTraverser = new NodeTraverser([$visitor]);

		/** @var PhpDocNode $newNode */
		[$newNode] = $changingTraverser->traverse($newNodes);

		$printer = new Printer();
		$actualResult = $printer->printFormatPreserving($newNode, $phpDocNode, $tokens);
		$this->assertSame($expectedResult, $actualResult);

		$this->assertEquals(
			$this->unsetAttributes($newNode),
			$this->unsetAttributes($this->phpDocParser->parse(new TokenIterator($lexer->tokenize($actualResult))))
		);
	}

}
