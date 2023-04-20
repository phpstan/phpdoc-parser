<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\NodeVisitor;

use PHPStan\PhpDocParser\Ast\Attribute;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPUnit\Framework\TestCase;

class CloningVisitorTest extends TestCase
{

	public function testVisitor(): void
	{
		$visitor = new CloningVisitor();
		$traverser = new NodeTraverser([$visitor]);
		$identifier = new IdentifierTypeNode('Foo');
		$node = new NullableTypeNode($identifier);

		$newNodes = $traverser->traverse([$node]);
		$this->assertCount(1, $newNodes);
		$this->assertInstanceOf(NullableTypeNode::class, $newNodes[0]);
		$this->assertNotSame($node, $newNodes[0]);
		$this->assertSame($node, $newNodes[0]->getAttribute(Attribute::ORIGINAL_NODE));

		$this->assertInstanceOf(IdentifierTypeNode::class, $newNodes[0]->type);
		$this->assertNotSame($identifier, $newNodes[0]->type);
		$this->assertSame($identifier, $newNodes[0]->type->getAttribute(Attribute::ORIGINAL_NODE));
	}

}
