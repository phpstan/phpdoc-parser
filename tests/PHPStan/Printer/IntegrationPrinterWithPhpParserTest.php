<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use PhpParser\Comment\Doc;
use PhpParser\Lexer\Emulative;
use PhpParser\Node as PhpNode;
use PhpParser\NodeTraverser as PhpParserNodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor as PhpParserCloningVisitor;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser\Php7;
use PHPStan\PhpDocParser\Ast\AbstractNodeVisitor;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\NodeVisitor;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPUnit\Framework\TestCase;
use function file_get_contents;

class IntegrationPrinterWithPhpParserTest extends TestCase
{

	/**
	 * @return iterable<array{string, string, NodeVisitor}>
	 */
	public function dataPrint(): iterable
	{
		$insertParameter = new class () extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					$node->children[] = new PhpDocTagNode('@param', new ParamTagValueNode(
						new IdentifierTypeNode('Bar'),
						false,
						'$b',
						''
					));
				}
				return $node;
			}

		};
		yield [
			__DIR__ . '/data/printer-1-tabs-before.php',
			__DIR__ . '/data/printer-1-tabs-after.php',
			$insertParameter,
		];
		yield [
			__DIR__ . '/data/printer-1-spaces-before.php',
			__DIR__ . '/data/printer-1-spaces-after.php',
			$insertParameter,
		];
	}

	/**
	 * @dataProvider dataPrint
	 */
	public function testPrint(string $file, string $expectedFile, NodeVisitor $visitor): void
	{
		$lexer = new Emulative([
			'usedAttributes' => [
				'comments',
				'startLine', 'endLine',
				'startTokenPos', 'endTokenPos',
			],
		]);
		$phpParser = new Php7($lexer);
		$phpTraverser = new PhpParserNodeTraverser();
		$phpTraverser->addVisitor(new PhpParserCloningVisitor());

		$printer = new PhpPrinter();
		$fileContents = file_get_contents($file);
		if ($fileContents === false) {
			$this->fail('Could not read ' . $file);
		}

		/** @var PhpNode[] $oldStmts */
		$oldStmts = $phpParser->parse($fileContents);
		$oldTokens = $lexer->getTokens();

		$phpTraverser2 = new PhpParserNodeTraverser();
		$phpTraverser2->addVisitor(new class ($visitor) extends NodeVisitorAbstract {

			/** @var NodeVisitor */
			private $visitor;

			public function __construct(NodeVisitor $visitor)
			{
				$this->visitor = $visitor;
			}

			public function enterNode(PhpNode $phpNode)
			{
				if ($phpNode->getDocComment() === null) {
					return null;
				}

				$phpDoc = $phpNode->getDocComment()->getText();

				$usedAttributes = ['lines' => true, 'indexes' => true];
				$constExprParser = new ConstExprParser(true, true, $usedAttributes);
				$phpDocParser = new PhpDocParser(
					new TypeParser($constExprParser, true, $usedAttributes),
					$constExprParser,
					true,
					true,
					$usedAttributes
				);
				$lexer = new Lexer();
				$tokens = new TokenIterator($lexer->tokenize($phpDoc));
				$phpDocNode = $phpDocParser->parse($tokens);
				$cloningTraverser = new NodeTraverser([new NodeVisitor\CloningVisitor()]);
				$newNodes = $cloningTraverser->traverse([$phpDocNode]);

				$changingTraverser = new NodeTraverser([$this->visitor]);

				/** @var PhpDocNode $newNode */
				[$newNode] = $changingTraverser->traverse($newNodes);

				$printer = new Printer();
				$newPhpDoc = $printer->printFormatPreserving($newNode, $phpDocNode, $tokens);
				$phpNode->setDocComment(new Doc($newPhpDoc));

				return $phpNode;
			}

		});

		/** @var PhpNode[] $newStmts */
		$newStmts = $phpTraverser->traverse($oldStmts);
		$newStmts = $phpTraverser2->traverse($newStmts);

		$newCode = $printer->printFormatPreserving($newStmts, $oldStmts, $oldTokens);
		$this->assertStringEqualsFile($expectedFile, $newCode);
	}

}
