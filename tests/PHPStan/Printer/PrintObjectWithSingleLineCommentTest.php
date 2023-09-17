<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use PHPStan\PhpDocParser\Ast\AbstractNodeVisitor;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\NodeVisitor;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use function array_splice;
use function array_unshift;
use function count;

class PrintObjectWithSingleLineCommentTest extends PrinterTestBase
{

	/**
	 * @return iterable<array{string, string}>
	 */
	public function dataPrintArrayFormatPreservingAddFront(): iterable
	{
		yield [
			self::nowdoc('
				/**
				 * @param object{bar: string} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{// A fractional number
				 *  foo: float,
				 *  bar: string} $foo
				 */'),
		];

		yield [
			self::nowdoc('
				/**
				 * @param object{
				 *   bar:string,naz:int} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{
				 *   // A fractional number
				 *   foo: float,
				 *   bar:string,naz:int} $foo
				 */'),
		];

		yield [
			self::nowdoc('
				/**
				 * @param object{
				 *   bar:string,
				 *	 naz:int
				 * } $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{
				 *   // A fractional number
				 *   foo: float,
				 *   bar:string,
				 *   naz:int
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
				if ($node instanceof ObjectShapeNode) {
					array_unshift($node->items, PrinterTestBase::withComment(
						new ObjectShapeItemNode(new IdentifierTypeNode('foo'), false, new IdentifierTypeNode('float')),
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
	public function dataPrintObjectFormatPreservingAddMiddle(): iterable
	{
		yield [
			self::nowdoc('
				/**
				 * @param object{} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{bar: float} $foo
				 */'),
		];

		yield [
			self::nowdoc('
				/**
				 * @param object{foo:string} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{foo:string,
				 *  // A fractional number
				 *  bar: float} $foo
				 */'),
		];

		yield [
			self::nowdoc('
				/**
				 * @param object{
				 *   foo:string,naz:int} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{
				 *   foo:string,
				 *   // A fractional number
				 *   bar: float,naz:int} $foo
				 */'),
		];

		yield [
			self::nowdoc('
				/**
				 * @param object{
				 *   foo:string,
				 *	 naz:int
				 * } $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{
				 *   foo:string,
				 *   // A fractional number
				 *   bar: float,
				 *   naz:int
				 * } $foo
				 */'),
		];
	}

	/**
	 * @dataProvider dataPrintObjectFormatPreservingAddMiddle
	 */
	public function testPrintFormatPreservingSingleLineAddMiddle(string $phpDoc, string $expectedResult): void
	{
		$visitor = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ObjectShapeNode) {
					$newItem = PrinterTestBase::withComment(
						new ObjectShapeItemNode(new IdentifierTypeNode('bar'), false, new IdentifierTypeNode('float')),
						'// A fractional number'
					);
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
