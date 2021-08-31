<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use PHPStan\PhpDocParser\Ast\Node;
use PHPUnit\Framework\TestCase;

final class NodePrintTest extends TestCase
{

	/**
	 * @dataProvider providePhpDocData
	 */
	public function testPrintMultiline(Node $node, string $expectedPrinted): void
	{
		$this->assertSame($expectedPrinted, (string) $node);
	}


	public function providePhpDocData(): \Iterator
	{
		yield [
			new PhpDocNode([
				new PhpDocTextNode('It works'),
			]),
			'/**
 * It works
 */',
		];

		yield [
			new PhpDocNode([
				new PhpDocTextNode('It works'),
				new PhpDocTextNode(''),
				new PhpDocTextNode('with empty lines'),
			]),
			'/**
 * It works
 *
 * with empty lines
 */',
		];
	}

}
