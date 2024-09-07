<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast;

use LogicException;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFloatNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPUnit\Framework\TestCase;

/**
 * Inspired by https://github.com/nikic/PHP-Parser/tree/36a6dcd04e7b0285e8f0868f44bd4927802f7df1
 *
 * Copyright (c) 2011, Nikita Popov
 * All rights reserved.
 */
class NodeTraverserTest extends TestCase
{

	public function testNonModifying(): void
	{
		$str1Node = new IdentifierTypeNode('Foo');
		$str2Node = new IdentifierTypeNode('Bar');
		$echoNode = new UnionTypeNode([$str1Node, $str2Node]);
		$nodes = [$echoNode];

		$visitor = new NodeVisitorForTesting();
		$traverser = new NodeTraverser([$visitor]);

		$this->assertEquals($nodes, $traverser->traverse($nodes));
		$this->assertEquals([
			['beforeTraverse', $nodes],
			['enterNode', $echoNode],
			['enterNode', $str1Node],
			['leaveNode', $str1Node],
			['enterNode', $str2Node],
			['leaveNode', $str2Node],
			['leaveNode', $echoNode],
			['afterTraverse', $nodes],
		], $visitor->trace);
	}

	public function testModifying(): void
	{
		$str1Node = new IdentifierTypeNode('Foo');
		$str2Node = new IdentifierTypeNode('Bar');
		$printNode = new NullableTypeNode($str1Node);

		// first visitor changes the node, second verifies the change
		$visitor1 = new NodeVisitorForTesting([
			['beforeTraverse', [], [$str1Node]],
			['enterNode', $str1Node, $printNode],
			['enterNode', $str1Node, $str2Node],
			['leaveNode', $str2Node, $str1Node],
			['leaveNode', $printNode, $str1Node],
			['afterTraverse', [$str1Node], []],
		]);
		$visitor2 = new NodeVisitorForTesting();

		$traverser = new NodeTraverser([$visitor1, $visitor2]);

		// as all operations are reversed we end where we start
		$this->assertEquals([], $traverser->traverse([]));
		$this->assertEquals([
			['beforeTraverse', [$str1Node]],
			['enterNode', $printNode],
			['enterNode', $str2Node],
			['leaveNode', $str1Node],
			['leaveNode', $str1Node],
			['afterTraverse', []],
		], $visitor2->trace);
	}

	public function testRemoveFromLeave(): void
	{
		$str1Node = new IdentifierTypeNode('Foo');
		$str2Node = new IdentifierTypeNode('Bar');

		$visitor = new NodeVisitorForTesting([
			['leaveNode', $str1Node, NodeTraverser::REMOVE_NODE],
		]);
		$visitor2 = new NodeVisitorForTesting();

		$traverser = new NodeTraverser([$visitor, $visitor2]);

		$nodes = [$str1Node, $str2Node];
		$this->assertEquals([$str2Node], $traverser->traverse($nodes));
		$this->assertEquals([
			['beforeTraverse', $nodes],
			['enterNode', $str1Node],
			['enterNode', $str2Node],
			['leaveNode', $str2Node],
			['afterTraverse', [$str2Node]],
		], $visitor2->trace);
	}

	public function testRemoveFromEnter(): void
	{
		$str1Node = new IdentifierTypeNode('Foo');
		$str2Node = new IdentifierTypeNode('Bar');

		$visitor = new NodeVisitorForTesting([
			['enterNode', $str1Node, NodeTraverser::REMOVE_NODE],
		]);
		$visitor2 = new NodeVisitorForTesting();

		$traverser = new NodeTraverser([$visitor, $visitor2]);

		$nodes = [$str1Node, $str2Node];
		$this->assertEquals([$str2Node], $traverser->traverse($nodes));
		$this->assertEquals([
			['beforeTraverse', $nodes],
			['enterNode', $str2Node],
			['leaveNode', $str2Node],
			['afterTraverse', [$str2Node]],
		], $visitor2->trace);
	}

	public function testReturnArrayFromEnter(): void
	{
		$str1Node = new IdentifierTypeNode('Str1');
		$str2Node = new IdentifierTypeNode('Str2');
		$str3Node = new IdentifierTypeNode('Str3');
		$str4Node = new IdentifierTypeNode('Str4');

		$visitor = new NodeVisitorForTesting([
			['enterNode', $str1Node, [$str3Node, $str4Node]],
		]);
		$visitor2 = new NodeVisitorForTesting();

		$traverser = new NodeTraverser([$visitor, $visitor2]);

		$nodes = [$str1Node, $str2Node];
		$this->assertEquals([$str3Node, $str4Node, $str2Node], $traverser->traverse($nodes));
		$this->assertEquals([
			['beforeTraverse', $nodes],
			['enterNode', $str2Node],
			['leaveNode', $str2Node],
			['afterTraverse', [$str3Node, $str4Node, $str2Node]],
		], $visitor2->trace);
	}

	public function testMerge(): void
	{
		$strStart = new IdentifierTypeNode('Start');
		$strMiddle = new IdentifierTypeNode('End');
		$strEnd = new IdentifierTypeNode('Middle');
		$strR1 = new IdentifierTypeNode('Replacement 1');
		$strR2 = new IdentifierTypeNode('Replacement 2');

		$visitor = new NodeVisitorForTesting([
			['leaveNode', $strMiddle, [$strR1, $strR2]],
		]);

		$traverser = new NodeTraverser([$visitor]);

		$this->assertEquals(
			[$strStart, $strR1, $strR2, $strEnd],
			$traverser->traverse([$strStart, $strMiddle, $strEnd]),
		);
	}

	public function testInvalidDeepArray(): void
	{
		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('Invalid node structure: Contains nested arrays');
		$strNode = new IdentifierTypeNode('Foo');
		$nodes = [[[$strNode]]];

		$traverser = new NodeTraverser([]);

		// @phpstan-ignore-next-line
		$this->assertEquals($nodes, $traverser->traverse($nodes));
	}

	public function testDontTraverseChildren(): void
	{
		$strNode = new IdentifierTypeNode('str');
		$printNode = new NullableTypeNode($strNode);
		$varNode = new ThisTypeNode();
		$mulNode = new UnionTypeNode([$varNode, $varNode]);
		$negNode = new NullableTypeNode($mulNode);
		$nodes = [$printNode, $negNode];

		$visitor1 = new NodeVisitorForTesting([
			['enterNode', $printNode, NodeTraverser::DONT_TRAVERSE_CHILDREN],
		]);
		$visitor2 = new NodeVisitorForTesting([
			['enterNode', $mulNode, NodeTraverser::DONT_TRAVERSE_CHILDREN],
		]);

		$expectedTrace = [
			['beforeTraverse', $nodes],
			['enterNode', $printNode],
			['leaveNode', $printNode],
			['enterNode', $negNode],
			['enterNode', $mulNode],
			['leaveNode', $mulNode],
			['leaveNode', $negNode],
			['afterTraverse', $nodes],
		];

		$traverser = new NodeTraverser([$visitor1, $visitor2]);

		$this->assertEquals($nodes, $traverser->traverse($nodes));
		$this->assertEquals($expectedTrace, $visitor1->trace);
		$this->assertEquals($expectedTrace, $visitor2->trace);
	}

	public function testDontTraverseCurrentAndChildren(): void
	{
		$strNode = new IdentifierTypeNode('str');
		$printNode = new NullableTypeNode($strNode);
		$varNode = new IdentifierTypeNode('foo');
		$mulNode = new UnionTypeNode([$varNode, $varNode]);
		$divNode = new IntersectionTypeNode([$varNode, $varNode]);
		$negNode = new NullableTypeNode($mulNode);
		$nodes = [$printNode, $negNode];

		$visitor1 = new NodeVisitorForTesting([
			['enterNode', $printNode, NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN],
			['enterNode', $mulNode, NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN],
			['leaveNode', $mulNode, $divNode],
		]);
		$visitor2 = new NodeVisitorForTesting();

		$traverser = new NodeTraverser([$visitor1, $visitor2]);

		$resultNodes = $traverser->traverse($nodes);
		$this->assertInstanceOf(NullableTypeNode::class, $resultNodes[1]);
		$this->assertInstanceOf(IntersectionTypeNode::class, $resultNodes[1]->type);

		$this->assertEquals([
			['beforeTraverse', $nodes],
			['enterNode', $printNode],
			['leaveNode', $printNode],
			['enterNode', $negNode],
			['enterNode', $mulNode],
			['leaveNode', $mulNode],
			['leaveNode', $negNode],
			['afterTraverse', $resultNodes],
		], $visitor1->trace);
		$this->assertEquals([
			['beforeTraverse', $nodes],
			['enterNode', $negNode],
			['leaveNode', $negNode],
			['afterTraverse', $resultNodes],
		], $visitor2->trace);
	}

	public function testStopTraversal(): void
	{
		$varNode1 = new IdentifierTypeNode('a');
		$varNode2 = new IdentifierTypeNode('b');
		$varNode3 = new IdentifierTypeNode('c');
		$mulNode = new UnionTypeNode([$varNode1, $varNode2]);
		$printNode = new NullableTypeNode($varNode3);
		$nodes = [$mulNode, $printNode];

		// From enterNode() with array parent
		$visitor = new NodeVisitorForTesting([
			['enterNode', $mulNode, NodeTraverser::STOP_TRAVERSAL],
		]);
		$traverser = new NodeTraverser([$visitor]);
		$this->assertEquals($nodes, $traverser->traverse($nodes));
		$this->assertEquals([
			['beforeTraverse', $nodes],
			['enterNode', $mulNode],
			['afterTraverse', $nodes],
		], $visitor->trace);

		// From enterNode with Node parent
		$visitor = new NodeVisitorForTesting([
			['enterNode', $varNode1, NodeTraverser::STOP_TRAVERSAL],
		]);
		$traverser = new NodeTraverser([$visitor]);
		$this->assertEquals($nodes, $traverser->traverse($nodes));
		$this->assertEquals([
			['beforeTraverse', $nodes],
			['enterNode', $mulNode],
			['enterNode', $varNode1],
			['afterTraverse', $nodes],
		], $visitor->trace);

		// From leaveNode with Node parent
		$visitor = new NodeVisitorForTesting([
			['leaveNode', $varNode1, NodeTraverser::STOP_TRAVERSAL],
		]);
		$traverser = new NodeTraverser([$visitor]);
		$this->assertEquals($nodes, $traverser->traverse($nodes));
		$this->assertEquals([
			['beforeTraverse', $nodes],
			['enterNode', $mulNode],
			['enterNode', $varNode1],
			['leaveNode', $varNode1],
			['afterTraverse', $nodes],
		], $visitor->trace);

		// From leaveNode with array parent
		$visitor = new NodeVisitorForTesting([
			['leaveNode', $mulNode, NodeTraverser::STOP_TRAVERSAL],
		]);
		$traverser = new NodeTraverser([$visitor]);
		$this->assertEquals($nodes, $traverser->traverse($nodes));
		$this->assertEquals([
			['beforeTraverse', $nodes],
			['enterNode', $mulNode],
			['enterNode', $varNode1],
			['leaveNode', $varNode1],
			['enterNode', $varNode2],
			['leaveNode', $varNode2],
			['leaveNode', $mulNode],
			['afterTraverse', $nodes],
		], $visitor->trace);

		// Check that pending array modifications are still carried out
		$visitor = new NodeVisitorForTesting([
			['leaveNode', $mulNode, NodeTraverser::REMOVE_NODE],
			['enterNode', $printNode, NodeTraverser::STOP_TRAVERSAL],
		]);
		$traverser = new NodeTraverser([$visitor]);
		$this->assertEquals([$printNode], $traverser->traverse($nodes));
		$this->assertEquals([
			['beforeTraverse', $nodes],
			['enterNode', $mulNode],
			['enterNode', $varNode1],
			['leaveNode', $varNode1],
			['enterNode', $varNode2],
			['leaveNode', $varNode2],
			['leaveNode', $mulNode],
			['enterNode', $printNode],
			['afterTraverse', [$printNode]],
		], $visitor->trace);
	}

	public function testNoCloneNodes(): void
	{
		$nodes = [new UnionTypeNode([new IdentifierTypeNode('Foo'), new IdentifierTypeNode('Bar')])];

		$traverser = new NodeTraverser([]);

		$this->assertSame($nodes, $traverser->traverse($nodes));
	}

	/**
	 * @dataProvider provideTestInvalidReturn
	 * @param Node[] $nodes
	 */
	public function testInvalidReturn(array $nodes, NodeVisitor $visitor, string $message): void
	{
		$this->expectException(LogicException::class);
		$this->expectExceptionMessage($message);

		$traverser = new NodeTraverser([$visitor]);
		$traverser->traverse($nodes);
	}

	/**
	 * @return list<list<mixed>>
	 */
	public function provideTestInvalidReturn(): array
	{
		$num = new ConstExprIntegerNode('42');
		$expr = new ConstTypeNode($num);
		$nodes = [$expr];

		$visitor1 = new NodeVisitorForTesting([
			['enterNode', $expr, 'foobar'],
		]);
		$visitor2 = new NodeVisitorForTesting([
			['enterNode', $num, 'foobar'],
		]);
		$visitor3 = new NodeVisitorForTesting([
			['leaveNode', $num, 'foobar'],
		]);
		$visitor4 = new NodeVisitorForTesting([
			['leaveNode', $expr, 'foobar'],
		]);
		$visitor5 = new NodeVisitorForTesting([
			['leaveNode', $num, [new ConstExprFloatNode('42.0')]],
		]);
		$visitor6 = new NodeVisitorForTesting([
			['leaveNode', $expr, false],
		]);
		$visitor7 = new NodeVisitorForTesting([
			['enterNode', $expr, new ConstExprIntegerNode('42')],
		]);
		$visitor8 = new NodeVisitorForTesting([
			['enterNode', $num, new ReturnTagValueNode(new ConstTypeNode(new ConstExprStringNode('foo', ConstExprStringNode::SINGLE_QUOTED)), '')],
		]);

		return [
			[$nodes, $visitor1, 'enterNode() returned invalid value of type string'],
			[$nodes, $visitor2, 'enterNode() returned invalid value of type string'],
			[$nodes, $visitor3, 'leaveNode() returned invalid value of type string'],
			[$nodes, $visitor4, 'leaveNode() returned invalid value of type string'],
			[$nodes, $visitor5, 'leaveNode() may only return an array if the parent structure is an array'],
			[$nodes, $visitor6, 'leaveNode() returned invalid value of type bool'],
			[$nodes, $visitor7, 'Trying to replace TypeNode with PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode'],
			[$nodes, $visitor8, 'Trying to replace ConstExprNode with PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode'],
		];
	}

}
