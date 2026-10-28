<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Type;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use function var_export;

class NameNodeTest extends TestCase
{

	public function testFullyQualified(): void
	{
		$node = new FullyQualifiedNameNode('Foo\Bar\Baz');

		$this->assertSame('Foo\Bar\Baz', $node->name);
		$this->assertSame('Foo\Bar\Baz', $node->toString());
		$this->assertSame('\Foo\Bar\Baz', $node->toCodeString());
		$this->assertSame('\Foo\Bar\Baz', (string) $node);
		$this->assertSame(['Foo', 'Bar', 'Baz'], $node->getParts());
		$this->assertSame('Foo', $node->getFirst());
		$this->assertSame('Baz', $node->getLast());
		$this->assertTrue($node->isFullyQualified());

		$this->assertSame('Foo\Bar\Baz', (new FullyQualifiedNameNode('\Foo\Bar\Baz'))->name);
	}

	public function testRelative(): void
	{
		$node = new RelativeNameNode('Bar\Baz');

		$this->assertSame('Bar\Baz', $node->name);
		$this->assertSame('Bar\Baz', $node->toString());
		$this->assertSame('Bar\Baz', $node->toCodeString());
		$this->assertSame('Bar\Baz', (string) $node);
		$this->assertSame(['Bar', 'Baz'], $node->getParts());
		$this->assertSame('Bar', $node->getFirst());
		$this->assertSame('Baz', $node->getLast());
		$this->assertFalse($node->isFullyQualified());
	}

	public function testRelativeRejectsFullyQualifiedName(): void
	{
		$this->expectException(InvalidArgumentException::class);
		new RelativeNameNode('\Foo');
	}

	public function testSetState(): void
	{
		$node = new FullyQualifiedNameNode('Foo\Bar');
		$node->setAttribute('a', 1);
		$this->assertEquals($node, eval('return ' . var_export($node, true) . ';'));

		$relative = new RelativeNameNode('Bar');
		$this->assertEquals($relative, eval('return ' . var_export($relative, true) . ';'));
	}

}
