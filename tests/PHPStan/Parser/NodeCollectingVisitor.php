<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast\AbstractNodeVisitor;
use PHPStan\PhpDocParser\Ast\Node;

class NodeCollectingVisitor extends AbstractNodeVisitor
{

	/** @var list<Node> */
	public $nodes = [];

	public function enterNode(Node $node)
	{
		$this->nodes[] = $node;

		return null;
	}

}
