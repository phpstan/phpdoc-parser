<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use PHPStan\PhpDocParser\Ast\AbstractNodeVisitor;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\NodeVisitor;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeParameterNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use function array_unshift;

class PrintCallableWithSingleLineCommentTest extends PrinterTestBase
{

	/**
	 * @return iterable<array{string, string}>
	 */
	public function dataAddCommentToParamsFront(): iterable
	{
		yield [
			self::nowdoc('
				/**
				 * @param callable(Bar $bar): int $a
				 */'),
			self::nowdoc('
				/**
				 * @param callable(// never pet a burning dog
				 *  Foo $foo,
				 *  Bar $bar): int $a
				 */'),
		];
	}

	/**
	 * @dataProvider dataAddCommentToParamsFront
	 */
	public function testAddCommentToParamsFront(string $phpDoc, string $expectedResult): void
	{
		$visitor = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof CallableTypeNode) {
					array_unshift($node->parameters, PrinterTestBase::withComment(
						new CallableTypeParameterNode(new IdentifierTypeNode('Foo'), false, false, '$foo', false),
						'// never pet a burning dog'
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
				 * @param callable(Foo $foo): int $a
				 */'),
			self::nowdoc('
				/**
				 * @param callable(Foo $foo,
				 *  // never pet a burning dog
				 *  Bar $bar): int $a
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
				if ($node instanceof CallableTypeNode) {
					$node->parameters[] = PrinterTestBase::withComment(
						new CallableTypeParameterNode(new IdentifierTypeNode('Bar'), false, false, '$bar', false),
						'// never pet a burning dog'
					);
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
