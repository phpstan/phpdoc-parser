<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast;

use Exception;
use function count;

/**
 * Inspired by https://github.com/nikic/PHP-Parser/tree/36a6dcd04e7b0285e8f0868f44bd4927802f7df1
 *
 * Copyright (c) 2011, Nikita Popov
 * All rights reserved.
 */
class NodeVisitorForTesting implements NodeVisitor
{

	/** @var list<array{string, Node|Node[]}> */
	public array $trace = [];

	/** @var list<list<mixed>> */
	private array $returns;

	private int $returnsPos;

	/**
	 * @param list<list<mixed>> $returns
	 */
	public function __construct(array $returns = [])
	{
		$this->returns = $returns;
		$this->returnsPos = 0;
	}

	public function beforeTraverse(array $nodes): ?array
	{
		return $this->traceEvent('beforeTraverse', $nodes);
	}

	public function enterNode(Node $node)
	{
		return $this->traceEvent('enterNode', $node);
	}

	public function leaveNode(Node $node)
	{
		return $this->traceEvent('leaveNode', $node);
	}

	public function afterTraverse(array $nodes): ?array
	{
		return $this->traceEvent('afterTraverse', $nodes);
	}

	/**
	 * @param Node|Node[] $param
	 * @return mixed
	 */
	private function traceEvent(string $method, $param)
	{
		$this->trace[] = [$method, $param];
		if ($this->returnsPos < count($this->returns)) {
			$currentReturn = $this->returns[$this->returnsPos];
			if ($currentReturn[0] === $method && $currentReturn[1] === $param) {
				$this->returnsPos++;
				return $currentReturn[2];
			}
		}
		return null;
	}

	public function __destruct()
	{
		if ($this->returnsPos !== count($this->returns)) {
			throw new Exception('Expected event did not occur');
		}
	}

}
