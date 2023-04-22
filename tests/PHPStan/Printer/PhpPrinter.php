<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use PhpParser\NodeTraverser;
use PhpParser\PrettyPrinter\Standard;
use function str_repeat;

class PhpPrinter extends Standard
{

	/** @var string */
	private $indentCharacter = ' ';

	/** @var int */
	private $indentSize = 4;

	protected function resetState(): void
	{
		parent::resetState();
		$this->indentCharacter = ' ';
		$this->indentSize = 4;
	}

	protected function preprocessNodes(array $nodes): void
	{
		parent::preprocessNodes($nodes);
		if ($this->origTokens === null) {
			return;
		}

		$traverser = new NodeTraverser();

		$visitor = new PhpPrinterIndentationDetectorVisitor($this->origTokens);
		$traverser->addVisitor($visitor);
		$traverser->traverse($nodes);

		$this->indentCharacter = $visitor->indentCharacter;
		$this->indentSize = $visitor->indentSize;
	}

	protected function setIndentLevel(int $level): void
	{
		$this->indentLevel = $level;
		$this->nl = "\n" . str_repeat($this->indentCharacter, $level);
	}

	protected function indent(): void
	{
		$this->indentLevel += $this->indentSize;
		$this->nl = "\n" . str_repeat($this->indentCharacter, $this->indentLevel);
	}

	protected function outdent(): void
	{
		$this->indentLevel -= $this->indentSize;
		$this->nl = "\n" . str_repeat($this->indentCharacter, $this->indentLevel);
	}

}
