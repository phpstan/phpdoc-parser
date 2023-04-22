<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use LogicException;
use PHPStan\PhpDocParser\Ast\Attribute;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use function array_keys;
use function count;
use function get_class;
use function get_object_vars;
use function is_array;
use function preg_match_all;
use function sprintf;
use function strlen;
use function strpos;
use const PREG_SET_ORDER;

/**
 * Inspired by https://github.com/nikic/PHP-Parser/tree/36a6dcd04e7b0285e8f0868f44bd4927802f7df1
 *
 * Copyright (c) 2011, Nikita Popov
 * All rights reserved.
 */
final class Printer
{

	/** @var Differ<Node> */
	private $differ;

	/**
	 * Map From "{$class}->{$subNode}" to string that should be inserted
	 * between elements of this list subnode
	 *
	 * @var array<string, string>
	 */
	private $listInsertionMap = [
		PhpDocNode::class . '->children' => "\n * ",
		UnionTypeNode::class . '->types' => '|',
		IntersectionTypeNode::class . '->types' => '&',
		ArrayShapeNode::class . '->items' => ', ',
		ObjectShapeNode::class . '->items' => ', ',
		CallableTypeNode::class . '->parameters' => ', ',
		GenericTypeNode::class . '->genericTypes' => ', ',
		ConstExprArrayNode::class . '->items' => ', ',
		MethodTagValueNode::class . '->parameters' => ', ',
	];

	/**
	 * [$find, $extraLeft, $extraRight]
	 *
	 * @var array<string, array{string|null, string, string}>
	 */
	protected $emptyListInsertionMap = [
		CallableTypeNode::class . '->parameters' => ['(', '', ''],
		ArrayShapeNode::class . '->items' => ['{', '', ''],
		ObjectShapeNode::class . '->items' => ['{', '', ''],
	];

	public function printFormatPreserving(PhpDocNode $node, PhpDocNode $originalNode, TokenIterator $originalTokens): string
	{
		$this->differ = new Differ(static function ($a, $b) {
			if ($a instanceof Node && $b instanceof Node) {
				return $a === $b->getAttribute(Attribute::ORIGINAL_NODE);
			}

			return false;
		});

		$tokenIndex = 0;
		$result = $this->printArrayFormatPreserving(
			$node->children,
			$originalNode->children,
			$originalTokens,
			$tokenIndex,
			PhpDocNode::class,
			'children'
		);
		if ($result !== null) {
			return $result . $originalTokens->getContentBetween($tokenIndex, $originalTokens->getTokenCount());
		}

		return $this->print($node);
	}

	public function print(Node $node): string
	{
		return (string) $node;
	}

	/**
	 * @param Node[] $nodes
	 * @param Node[] $originalNodes
	 */
	private function printArrayFormatPreserving(array $nodes, array $originalNodes, TokenIterator $originalTokens, int &$tokenIndex, string $parentNodeClass, string $subNodeName): ?string
	{
		$diff = $this->differ->diffWithReplacements($originalNodes, $nodes);
		$mapKey = $parentNodeClass . '->' . $subNodeName;
		$insertStr = $this->listInsertionMap[$mapKey] ?? null;
		$result = '';
		$beforeFirstKeepOrReplace = true;
		$delayedAdd = [];

		$insertNewline = false;
		[$isMultiline, $beforeAsteriskIndent, $afterAsteriskIndent] = $this->isMultiline($tokenIndex, $originalNodes, $originalTokens);

		if ($insertStr === "\n * ") {
			$insertStr = sprintf("\n%s*%s", $beforeAsteriskIndent, $afterAsteriskIndent);
		}

		foreach ($diff as $i => $diffElem) {
			$diffType = $diffElem->type;
			$newNode = $diffElem->new;
			$originalNode = $diffElem->old;
			if ($diffType === DiffElem::TYPE_KEEP || $diffType === DiffElem::TYPE_REPLACE) {
				$beforeFirstKeepOrReplace = false;
				if (!$newNode instanceof Node || !$originalNode instanceof Node) {
					return null;
				}
				$itemStartPos = $originalNode->getAttribute(Attribute::START_INDEX);
				$itemEndPos = $originalNode->getAttribute(Attribute::END_INDEX);
				if ($itemStartPos < 0 || $itemEndPos < 0 || $itemStartPos < $tokenIndex) {
					throw new LogicException();
				}

				$result .= $originalTokens->getContentBetween($tokenIndex, $itemStartPos);

				if (count($delayedAdd) > 0) {
					foreach ($delayedAdd as $delayedAddNode) {
						$result .= $this->printNodeFormatPreserving($delayedAddNode, $originalTokens);

						if ($insertNewline) {
							$result .= $insertStr . sprintf("\n%s*%s", $beforeAsteriskIndent, $afterAsteriskIndent);
						} else {
							$result .= $insertStr;
						}
					}

					$delayedAdd = [];
				}
			} elseif ($diffType === DiffElem::TYPE_ADD) {
				if ($insertStr === null) {
					return null;
				}
				if (!$newNode instanceof Node) {
					return null;
				}

				if ($insertStr === ', ' && $isMultiline) {
					$insertStr = ',';
					$insertNewline = true;
				}

				if ($beforeFirstKeepOrReplace) {
					// Will be inserted at the next "replace" or "keep" element
					$delayedAdd[] = $newNode;
					continue;
				}

				$itemEndPos = $tokenIndex - 1;
				if ($insertNewline) {
					$result .= $insertStr . sprintf("\n%s*%s", $beforeAsteriskIndent, $afterAsteriskIndent);
				} else {
					$result .= $insertStr;
				}

			} elseif ($diffType === DiffElem::TYPE_REMOVE) {
				if (!$originalNode instanceof Node) {
					return null;
				}

				$itemStartPos = $originalNode->getAttribute(Attribute::START_INDEX);
				$itemEndPos = $originalNode->getAttribute(Attribute::END_INDEX);
				if ($itemStartPos < 0 || $itemEndPos < 0) {
					throw new LogicException();
				}

				if ($i === 0) {
					// If we're removing from the start, keep the tokens before the node and drop those after it,
					// instead of the other way around.
					$originalTokensArray = $originalTokens->getTokens();
					for ($j = $tokenIndex; $j < $itemStartPos; $j++) {
						if ($originalTokensArray[$j][Lexer::TYPE_OFFSET] === Lexer::TOKEN_PHPDOC_EOL) {
							break;
						}
						$result .= $originalTokensArray[$j][Lexer::VALUE_OFFSET];
					}
				}

				$tokenIndex = $itemEndPos + 1;
				continue;
			}

			$result .= $this->printNodeFormatPreserving($newNode, $originalTokens);
			$tokenIndex = $itemEndPos + 1;
		}

		if (count($delayedAdd) > 0) {
			if (!isset($this->emptyListInsertionMap[$mapKey])) {
				return null;
			}

			[$findToken, $extraLeft, $extraRight] = $this->emptyListInsertionMap[$mapKey];
			if ($findToken !== null) {
				$originalTokensArray = $originalTokens->getTokens();
				for (; $tokenIndex < count($originalTokensArray); $tokenIndex++) {
					$result .= $originalTokensArray[$tokenIndex][Lexer::VALUE_OFFSET];
					if ($originalTokensArray[$tokenIndex][Lexer::VALUE_OFFSET] !== $findToken) {
						continue;
					}

					$tokenIndex++;
					break;
				}
			}
			$first = true;
			$result .= $extraLeft;
			foreach ($delayedAdd as $delayedAddNode) {
				if (!$first) {
					$result .= $insertStr;
					if ($insertNewline) {
						$result .= sprintf("\n%s*%s", $beforeAsteriskIndent, $afterAsteriskIndent);
					}
				}
				$result .= $this->printNodeFormatPreserving($delayedAddNode, $originalTokens);
				$first = false;
			}
			$result .= $extraRight;
		}

		return $result;
	}

	/**
	 * @param Node[] $nodes
	 * @return array{bool, string, string}
	 */
	private function isMultiline(int $initialIndex, array $nodes, TokenIterator $originalTokens): array
	{
		$isMultiline = count($nodes) > 1;
		$pos = $initialIndex;
		$allText = '';
		/** @var Node|null $node */
		foreach ($nodes as $node) {
			if (!$node instanceof Node) {
				continue;
			}

			$endPos = $node->getAttribute(Attribute::END_INDEX) + 1;
			$text = $originalTokens->getContentBetween($pos, $endPos);
			$allText .= $text;
			if (strpos($text, "\n") === false) {
				// We require that a newline is present between *every* item. If the formatting
				// is inconsistent, with only some items having newlines, we don't consider it
				// as multiline
				$isMultiline = false;
			}
			$pos = $endPos;
		}

		$c = preg_match_all('~\n(?<before>[\\x09\\x20]*)\*(?<after>\\x20*)~', $allText, $matches, PREG_SET_ORDER);
		if ($c === 0) {
			return [$isMultiline, '', ''];
		}

		$before = '';
		$after = '';
		foreach ($matches as $match) {
			if (strlen($match['before']) > strlen($before)) {
				$before = $match['before'];
			}
			if (strlen($match['after']) <= strlen($after)) {
				continue;
			}

			$after = $match['after'];
		}

		return [$isMultiline, $before, $after];
	}

	private function printNodeFormatPreserving(Node $node, TokenIterator $originalTokens): string
	{
		/** @var Node|null $originalNode */
		$originalNode = $node->getAttribute(Attribute::ORIGINAL_NODE);
		if ($originalNode === null) {
			return $this->print($node);
		}

		$class = get_class($node);
		if ($class !== get_class($originalNode)) {
			throw new LogicException();
		}

		$startPos = $originalNode->getAttribute(Attribute::START_INDEX);
		$endPos = $originalNode->getAttribute(Attribute::END_INDEX);
		if ($startPos < 0 || $endPos < 0) {
			throw new LogicException();
		}

		$result = '';
		$pos = $startPos;
		$subNodeNames = array_keys(get_object_vars($node));
		foreach ($subNodeNames as $subNodeName) {
			$subNode = $node->$subNodeName;
			$origSubNode = $originalNode->$subNodeName;

			if (
				(!$subNode instanceof Node && $subNode !== null)
				|| (!$origSubNode instanceof Node && $origSubNode !== null)
			) {
				if ($subNode === $origSubNode) {
					// Unchanged, can reuse old code
					continue;
				}

				if (is_array($subNode) && is_array($origSubNode)) {
					// Array subnode changed, we might be able to reconstruct it
					$listResult = $this->printArrayFormatPreserving(
						$subNode,
						$origSubNode,
						$originalTokens,
						$pos,
						$class,
						$subNodeName
					);

					if ($listResult === null) {
						return $this->print($node);
					}

					$result .= $listResult;
					continue;
				}

				return $this->print($node);
			}

			if ($origSubNode === null) {
				if ($subNode === null) {
					// Both null, nothing to do
					continue;
				}

				return $this->print($node);
			}

			$subStartPos = $origSubNode->getAttribute(Attribute::START_INDEX);
			$subEndPos = $origSubNode->getAttribute(Attribute::END_INDEX);
			if ($subStartPos < 0 || $subEndPos < 0) {
				throw new LogicException();
			}

			if ($subNode === null) {
				return $this->print($node);
			}

			$result .= $originalTokens->getContentBetween($pos, $subStartPos);
			$result .= $this->printNodeFormatPreserving($subNode, $originalTokens);
			$pos = $subEndPos + 1;
		}

		$result .= $originalTokens->getContentBetween($pos, $endPos + 1);

		return $result;
	}

}
