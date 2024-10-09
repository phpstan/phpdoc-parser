<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use LogicException;
use PhpParser\Comment\Doc;
use PhpParser\Internal\TokenStream;
use PhpParser\Node as PhpNode;
use PhpParser\NodeTraverser as PhpParserNodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor as PhpParserCloningVisitor;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
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
use PHPStan\PhpDocParser\ParserConfig;
use PHPUnit\Framework\TestCase;
use function file_get_contents;
use function str_repeat;

class IntegrationPrinterWithPhpParserTest extends TestCase
{

	private const TAB_WIDTH = 4;

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
						'',
						false,
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
		$phpParserFactory = new ParserFactory();
		$phpParser = $phpParserFactory->createForNewestSupportedVersion();
		$phpTraverser = new PhpParserNodeTraverser();
		$phpTraverser->addVisitor(new PhpParserCloningVisitor());

		$fileContents = file_get_contents($file);
		if ($fileContents === false) {
			$this->fail('Could not read ' . $file);
		}

		$oldStmts = $phpParser->parse($fileContents);
		if ($oldStmts === null) {
			throw new LogicException();
		}
		$oldTokens = $phpParser->getTokens();

		$phpTraverserIndent = new PhpParserNodeTraverser();
		$indentDetector = new PhpPrinterIndentationDetectorVisitor(new TokenStream($oldTokens, self::TAB_WIDTH));
		$phpTraverserIndent->addVisitor($indentDetector);
		$phpTraverserIndent->traverse($oldStmts);

		$phpTraverser2 = new PhpParserNodeTraverser();
		$phpTraverser2->addVisitor(new class ($visitor) extends NodeVisitorAbstract {

			private NodeVisitor $visitor;

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

				$config = new ParserConfig(['lines' => true, 'indexes' => true]);
				$constExprParser = new ConstExprParser($config);
				$phpDocParser = new PhpDocParser(
					$config,
					new TypeParser($config, $constExprParser),
					$constExprParser,
				);
				$lexer = new Lexer($config);
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

		$printer = new Standard(['indent' => str_repeat($indentDetector->indentCharacter, $indentDetector->indentSize)]);
		$newCode = $printer->printFormatPreserving($newStmts, $oldStmts, $oldTokens);
		$this->assertStringEqualsFile($expectedFile, $newCode);
	}

}
