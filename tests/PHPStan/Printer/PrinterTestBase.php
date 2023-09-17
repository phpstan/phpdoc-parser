<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use PHPStan\PhpDocParser\Ast\AbstractNodeVisitor;
use PHPStan\PhpDocParser\Ast\Attribute;
use PHPStan\PhpDocParser\Ast\Comment;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPUnit\Framework\TestCase;
use function array_map;
use function array_slice;
use function count;
use function implode;
use function preg_match;
use function preg_replace_callback;
use function preg_split;
use function str_repeat;
use function str_replace;
use function strlen;

abstract class PrinterTestBase extends TestCase
{

	/** @var TypeParser */
	protected $typeParser;

	/** @var PhpDocParser */
	protected $phpDocParser;

	/**
	 * @template TNode of Node
	 * @param TNode $node
	 * @return TNode
	 */
	public static function withComment(Node $node, string $comment): Node
	{
		$node->setAttribute(Attribute::COMMENTS, [new Comment($comment)]);
		return $node;
	}

	public static function nowdoc(string $str): string
	{
		$lines = preg_split('/\\n/', $str);

		if ($lines === false) {
			return '';
		}

		if (count($lines) < 2) {
			return '';
		}

		// Toss out the first line
		$lines = array_slice($lines, 1, count($lines) - 1);

		// normalize any tabs to spaces
		$lines = array_map(static function ($line) {
			return preg_replace_callback('/(\t+)/m', static function ($matches) {
				$fixed = str_repeat('  ', strlen($matches[1]));
				return $fixed;
			}, $line);
		}, $lines);

		// take the ws from the first line and subtract them from all lines
		$matches = [];
		preg_match('/(^[ \t]+)/', $lines[0] ?? '', $matches);

		$numLines = count($lines);
		for ($i = 0; $i < $numLines; ++$i) {
			$lines[$i] = str_replace($matches[0], '', $lines[$i] ?? '');
		}

		return implode("\n", $lines);
	}

	protected function setUp(): void
	{
		$usedAttributes = ['lines' => true, 'indexes' => true, 'comments' => true];
		$constExprParser = new ConstExprParser(true, true, $usedAttributes);
		$this->typeParser = new TypeParser($constExprParser, true, $usedAttributes);
		$this->phpDocParser = new PhpDocParser(
			$this->typeParser,
			$constExprParser,
			true,
			true,
			$usedAttributes,
			true
		);
	}

	protected function unsetAttributes(Node $node): Node
	{
		$visitor = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				$node->setAttribute(Attribute::START_LINE, null);
				$node->setAttribute(Attribute::END_LINE, null);
				$node->setAttribute(Attribute::START_INDEX, null);
				$node->setAttribute(Attribute::END_INDEX, null);
				$node->setAttribute(Attribute::ORIGINAL_NODE, null);
				$node->setAttribute(Attribute::COMMENTS, null);

				return $node;
			}

		};

		$traverser = new NodeTraverser([$visitor]);

		/** @var PhpDocNode */
		return $traverser->traverse([$node])[0];
	}

}
