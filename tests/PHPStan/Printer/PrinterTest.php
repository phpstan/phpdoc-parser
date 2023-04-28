<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use PHPStan\PhpDocParser\Ast\AbstractNodeVisitor;
use PHPStan\PhpDocParser\Ast\Attribute;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayItemNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\QuoteAwareConstExprStringNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\NodeVisitor;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeParameterNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeNode;
use PHPStan\PhpDocParser\Ast\Type\OffsetAccessTypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPUnit\Framework\TestCase;
use function array_pop;
use function array_splice;
use function array_unshift;
use function array_values;
use function count;

class PrinterTest extends TestCase
{

	/**
	 * @return iterable<array{string, string, NodeVisitor}>
	 */
	public function dataPrintFormatPreserving(): iterable
	{
		$noopVisitor = new class extends AbstractNodeVisitor {

		};
		yield ['/** */', '/** */', $noopVisitor];
		yield ['/**
 */', '/**
 */', $noopVisitor];
		yield [
			'/** @param Foo $foo */',
			'/** @param Foo $foo */',
			$noopVisitor,
		];
		yield [
			'/**
 * @param Foo $foo
 */',
			'/**
 * @param Foo $foo
 */',
			$noopVisitor,
		];

		$removeFirst = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					unset($node->children[0]);

					$node->children = array_values($node->children);
					return $node;
				}

				return null;
			}

		};
		yield [
			'/** @param Foo $foo */',
			'/**  */',
			$removeFirst,
		];
		yield [
			'/** @param Foo $foo*/',
			'/** */',
			$removeFirst,
		];

		yield [
			'/** @return Foo */',
			'/**  */',
			$removeFirst,
		];
		yield [
			'/** @return Foo*/',
			'/** */',
			$removeFirst,
		];

		yield [
			'/**
 * @param Foo $foo
 */',
			'/**
 */',
			$removeFirst,
		];

		yield [
			'/**
 * @param Foo $foo
 * @param Bar $bar
 */',
			'/**
 * @param Bar $bar
 */',
			$removeFirst,
		];

		yield [
			'/**
     * @param Foo $foo
     * @param Bar $bar
     */',
			'/**
     * @param Bar $bar
     */',
			$removeFirst,
		];

		$removeLast = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					array_pop($node->children);

					return $node;
				}

				return null;
			}

		};

		yield [
			'/**
 * @param Foo $foo
 * @param Bar $bar
 */',
			'/**
 * @param Foo $foo
 */',
			$removeLast,
		];

		$removeSecond = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					unset($node->children[1]);
					$node->children = array_values($node->children);

					return $node;
				}

				return null;
			}

		};

		yield [
			'/**
 * @param Foo $foo
 * @param Bar $bar
 */',
			'/**
 * @param Foo $foo
 */',
			$removeSecond,
		];

		yield [
			'/**
 * @param Foo $foo
 * @param Bar $bar
 * @param Baz $baz
 */',
			'/**
 * @param Foo $foo
 * @param Baz $baz
 */',
			$removeSecond,
		];

		yield [
			'/**
     * @param Foo $foo
     * @param Bar $bar
     * @param Baz $baz
     */',
			'/**
     * @param Foo $foo
     * @param Baz $baz
     */',
			$removeSecond,
		];

		$changeReturnType = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ReturnTagValueNode) {
					$node->type = new IdentifierTypeNode('Bar');

					return $node;
				}

				return $node;
			}

		};

		yield [
			'/** @return Foo */',
			'/** @return Bar */',
			$changeReturnType,
		];

		yield [
			'/** @return Foo*/',
			'/** @return Bar*/',
			$changeReturnType,
		];

		yield [
			'/**
* @return Foo
* @param Foo $foo
* @param Bar $bar
*/',
			'/**
* @return Bar
* @param Foo $foo
* @param Bar $bar
*/',
			$changeReturnType,
		];

		yield [
			'/**
* @param Foo $foo
* @return Foo
* @param Bar $bar
*/',
			'/**
* @param Foo $foo
* @return Bar
* @param Bar $bar
*/',
			$changeReturnType,
		];

		yield [
			'/**
* @return Foo
* @param Foo $foo
* @param Bar $bar
*/',
			'/**
* @return Bar
* @param Foo $foo
* @param Bar $bar
*/',
			$changeReturnType,
		];

		yield [
			'/**
* @param Foo $foo Foo description
* @return Foo Foo return description
* @param Bar $bar Bar description
*/',
			'/**
* @param Foo $foo Foo description
* @return Bar Foo return description
* @param Bar $bar Bar description
*/',
			$changeReturnType,
		];

		$replaceFirst = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					$node->children[0] = new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('Baz'), false, '$a', ''));
					return $node;
				}

				return $node;
			}

		};

		yield [
			'/** @param Foo $foo */',
			'/** @param Baz $a */',
			$replaceFirst,
		];

		yield [
			'/**
 * @param Foo $foo
 */',
			'/**
 * @param Baz $a
 */',
			$replaceFirst,
		];

		yield [
			'/**
     * @param Foo $foo
     */',
			'/**
     * @param Baz $a
     */',
			$replaceFirst,
		];

		$insertFirst = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					array_unshift($node->children, new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('Baz'), false, '$a', '')));

					return $node;
				}

				return $node;
			}

		};

		yield [
			'/**
 * @param Foo $foo
 */',
			'/**
 * @param Baz $a
 * @param Foo $foo
 */',
			$insertFirst,
		];

		yield [
			'/**
     * @param Foo $foo
     */',
			'/**
     * @param Baz $a
     * @param Foo $foo
     */',
			$insertFirst,
		];

		$insertSecond = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					array_splice($node->children, 1, 0, [
						new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('Baz'), false, '$a', '')),
					]);

					return $node;
				}

				return $node;
			}

		};

		yield [
			'/**
 * @param Foo $foo
 */',
			'/**
 * @param Foo $foo
 * @param Baz $a
 */',
			$insertSecond,
		];

		yield [
			'/**
 * @param Foo $foo
 * @param Bar $bar
 */',
			'/**
 * @param Foo $foo
 * @param Baz $a
 * @param Bar $bar
 */',
			$insertSecond,
		];

		yield [
			'/**
     * @param Foo $foo
     * @param Bar $bar
     */',
			'/**
     * @param Foo $foo
     * @param Baz $a
     * @param Bar $bar
     */',
			$insertSecond,
		];

		yield [
			'/**
	 * @param Foo $foo
	 * @param Bar $bar
	 */',
			'/**
	 * @param Foo $foo
	 * @param Baz $a
	 * @param Bar $bar
	 */',
			$insertSecond,
		];

		$replaceLast = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					$node->children[count($node->children) - 1] = new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('Baz'), false, '$a', ''));
					return $node;
				}

				return $node;
			}

		};

		yield [
			'/**
 * @param Foo $foo
 */',
			'/**
 * @param Baz $a
 */',
			$replaceLast,
		];

		yield [
			'/**
 * @param Foo $foo
 * @param Bar $bar
 */',
			'/**
 * @param Foo $foo
 * @param Baz $a
 */',
			$replaceLast,
		];

		$insertFirstTypeInUnionType = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof UnionTypeNode) {
					array_unshift($node->types, new IdentifierTypeNode('Foo'));
				}

				return $node;
			}

		};

		yield [
			'/**
 * @param Bar|Baz $foo
 */',
			'/**
 * @param Foo|Bar|Baz $foo
 */',
			$insertFirstTypeInUnionType,
		];

		yield [
			'/**
 * @param Bar|Baz $foo
 * @param Foo $bar
 */',
			'/**
 * @param Foo|Bar|Baz $foo
 * @param Foo $bar
 */',
			$insertFirstTypeInUnionType,
		];

		$replaceTypesInUnionType = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof UnionTypeNode) {
					$node->types = [
						new IdentifierTypeNode('Lorem'),
						new IdentifierTypeNode('Ipsum'),
					];
				}

				return $node;
			}

		};

		yield [
			'/**
 * @param Foo|Bar $bar
 */',
			'/**
 * @param Lorem|Ipsum $bar
 */',
			$replaceTypesInUnionType,
		];

		$replaceParametersInCallableType = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof CallableTypeNode) {
					$node->parameters = [
						new CallableTypeParameterNode(new IdentifierTypeNode('Foo'), false, false, '$foo', false),
						new CallableTypeParameterNode(new IdentifierTypeNode('Bar'), false, false, '$bar', false),
					];
				}

				return $node;
			}

		};

		yield [
			'/**
 * @param callable(): void $cb
 */',
			'/**
 * @param callable(Foo $foo, Bar $bar): void $cb
 */',
			$replaceParametersInCallableType,
		];

		$removeParametersInCallableType = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof CallableTypeNode) {
					$node->parameters = [];
				}

				return $node;
			}

		};

		yield [
			'/**
 * @param callable(Foo $foo, Bar $bar): void $cb
 */',
			'/**
 * @param callable(): void $cb
 */',
			$removeParametersInCallableType,
		];

		$changeCallableTypeIdentifier = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof CallableTypeNode) {
					$node->identifier = new IdentifierTypeNode('Closure');
				}

				return $node;
			}

		};

		yield [
			'/**
 * @param callable(Foo $foo, Bar $bar): void $cb
 * @param callable(): void $cb2
 */',
			'/**
 * @param Closure(Foo $foo, Bar $bar): void $cb
 * @param Closure(): void $cb2
 */',
			$changeCallableTypeIdentifier,
		];

		$addItemsToArrayShape = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ArrayShapeNode) {
					array_splice($node->items, 1, 0, [
						new ArrayShapeItemNode(null, false, new IdentifierTypeNode('int')),
					]);
					$node->items[] = new ArrayShapeItemNode(null, false, new IdentifierTypeNode('string'));
				}

				return $node;
			}

		};

		yield [
			'/**
 * @return array{float}
 */',
			'/**
 * @return array{float, int, string}
 */',
			$addItemsToArrayShape,
		];

		yield [
			'/**
 * @return array{float, Foo}
 */',
			'/**
 * @return array{float, int, Foo, string}
 */',
			$addItemsToArrayShape,
		];

		yield [
			'/**
 * @return array{
 *   float,
 *   Foo,
 * }
 */',
			'/**
 * @return array{
 *   float,
 *   int,
 *   Foo,
 *   string,
 * }
 */',
			$addItemsToArrayShape,
		];

		yield [
			'/**
 * @return array{
 *   float,
 *   Foo
 * }
 */',
			'/**
 * @return array{
 *   float,
 *   int,
 *   Foo,
 *   string
 * }
 */',
			$addItemsToArrayShape,
		];

		yield [
			'/**
 * @return array{
 *     float,
 *     Foo
 * }
 */',
			'/**
 * @return array{
 *     float,
 *     int,
 *     Foo,
 *     string
 * }
 */',
			$addItemsToArrayShape,
		];

		yield [
			'/**
	 * @return array{
	 *   float,
	 *   Foo,
	 * }
	 */',
			'/**
	 * @return array{
	 *   float,
	 *   int,
	 *   Foo,
	 *   string,
	 * }
	 */',
			$addItemsToArrayShape,
		];

		yield [
			'/**
     * @return array{
     *   float,
     *   Foo
     * }
     */',
			'/**
     * @return array{
     *   float,
     *   int,
     *   Foo,
     *   string
     * }
     */',
			$addItemsToArrayShape,
		];

		yield [
			'/**
	 * @return array{
	 *     float,
	 *     Foo
	 * }
	 */',
			'/**
	 * @return array{
	 *     float,
	 *     int,
	 *     Foo,
	 *     string
	 * }
	 */',
			$addItemsToArrayShape,
		];

		$addItemsToObjectShape = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ObjectShapeNode) {
					$node->items[] = new ObjectShapeItemNode(new IdentifierTypeNode('foo'), false, new IdentifierTypeNode('int'));
				}

				return $node;
			}

		};

		yield [
			'/**
 * @return object{}
 */',
			'/**
 * @return object{foo: int}
 */',
			$addItemsToObjectShape,
		];

		yield [
			'/**
 * @return object{bar: string}
 */',
			'/**
 * @return object{bar: string, foo: int}
 */',
			$addItemsToObjectShape,
		];

		$addItemsToConstExprArray = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ConstExprArrayNode) {
					$node->items[] = new ConstExprArrayItemNode(null, new ConstExprIntegerNode('123'));
				}

				return $node;
			}

		};

		yield [
			'/** @method int doFoo(array $foo = []) */',
			'/** @method int doFoo(array $foo = [123]) */',
			$addItemsToConstExprArray,
		];

		yield [
			'/** @method int doFoo(array $foo = [420]) */',
			'/** @method int doFoo(array $foo = [420, 123]) */',
			$addItemsToConstExprArray,
		];

		$removeKeyFromConstExprArrayItem = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ConstExprArrayNode) {
					$node->items[0]->key = null;
				}

				return $node;
			}

		};

		yield [
			'/** @method int doFoo(array $foo = [123 => 456]) */',
			'/** @method int doFoo(array $foo = [456]) */',
			$removeKeyFromConstExprArrayItem,
		];

		$addKeyToConstExprArrayItem = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ConstExprArrayNode) {
					$node->items[0]->key = new ConstExprIntegerNode('123');
				}

				return $node;
			}

		};

		yield [
			'/** @method int doFoo(array $foo = [456]) */',
			'/** @method int doFoo(array $foo = [123 => 456]) */',
			$addKeyToConstExprArrayItem,
		];

		$addTemplateTagBound = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof TemplateTagValueNode) {
					$node->bound = new IdentifierTypeNode('int');
				}

				return $node;
			}

		};

		yield [
			'/** @template T */',
			'/** @template T of int */',
			$addTemplateTagBound,
		];

		yield [
			'/** @template T of string */',
			'/** @template T of int */',
			$addTemplateTagBound,
		];

		$removeTemplateTagBound = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof TemplateTagValueNode) {
					$node->bound = null;
				}

				return $node;
			}

		};

		yield [
			'/** @template T of int */',
			'/** @template T */',
			$removeTemplateTagBound,
		];

		$addKeyNameToArrayShapeItemNode = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ArrayShapeItemNode) {
					$node->keyName = new QuoteAwareConstExprStringNode('test', QuoteAwareConstExprStringNode::SINGLE_QUOTED);
				}

				return $node;
			}

		};

		yield [
			'/** @return array{Foo} */',
			"/** @return array{'test': Foo} */",
			$addKeyNameToArrayShapeItemNode,
		];

		$removeKeyNameToArrayShapeItemNode = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ArrayShapeItemNode) {
					$node->keyName = null;
				}

				return $node;
			}

		};

		yield [
			"/** @return array{'test': Foo} */",
			'/** @return array{Foo} */',
			$removeKeyNameToArrayShapeItemNode,
		];

		$changeArrayShapeKind = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ArrayShapeNode) {
					$node->kind = ArrayShapeNode::KIND_LIST;
				}

				return $node;
			}

		};

		yield [
			'/** @return array{Foo, Bar} */',
			'/** @return list{Foo, Bar} */',
			$changeArrayShapeKind,
		];

		$changeParameterName = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ParamTagValueNode) {
					$node->parameterName = '$bz';
				}

				return $node;
			}

		};

		yield [
			'/** @param int $a */',
			'/** @param int $bz */',
			$changeParameterName,
		];

		yield [
			'/**
 * @param int $a
 */',
			'/**
 * @param int $bz
 */',
			$changeParameterName,
		];

		yield [
			'/**
 * @param int $a
 * @return string
 */',
			'/**
 * @param int $bz
 * @return string
 */',
			$changeParameterName,
		];

		yield [
			'/**
 * @param int $a haha description
 * @return string
 */',
			'/**
 * @param int $bz haha description
 * @return string
 */',
			$changeParameterName,
		];

		$changeParameterDescription = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ParamTagValueNode) {
					$node->description = 'hehe';
				}

				return $node;
			}

		};

		yield [
			'/** @param int $a */',
			'/** @param int $a hehe */',
			$changeParameterDescription,
		];

		yield [
			'/** @param int $a haha */',
			'/** @param int $a hehe */',
			$changeParameterDescription,
		];

		yield [
			'/** @param int $a */',
			'/** @param int $a hehe */',
			$changeParameterDescription,
		];

		yield [
			'/**
 * @param int $a haha
 */',
			'/**
 * @param int $a hehe
 */',
			$changeParameterDescription,
		];

		$changeOffsetAccess = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof OffsetAccessTypeNode) {
					$node->offset = new IdentifierTypeNode('baz');
				}

				return $node;
			}

		};

		yield [
			'/**
 * @param Foo[awesome] $a haha
 */',
			'/**
 * @param Foo[baz] $a haha
 */',
			$changeOffsetAccess,
		];

		$changeTypeAliasImportAs = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof TypeAliasImportTagValueNode) {
					$node->importedAs = 'Ciao';
				}

				return $node;
			}

		};

		yield [
			'/**
 * @phpstan-import-type TypeAlias from AnotherClass as DifferentAlias
 */',
			'/**
 * @phpstan-import-type TypeAlias from AnotherClass as Ciao
 */',
			$changeTypeAliasImportAs,
		];

		$removeImportAs = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof TypeAliasImportTagValueNode) {
					$node->importedAs = null;
				}

				return $node;
			}

		};

		yield [
			'/**
 * @phpstan-import-type TypeAlias from AnotherClass as DifferentAlias
 */',
			'/**
 * @phpstan-import-type TypeAlias from AnotherClass
 */',
			$removeImportAs,
		];

		$addMethodTemplateType = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof MethodTagValueNode) {
					$node->templateTypes[] = new TemplateTagValueNode(
						'T',
						new IdentifierTypeNode('int'),
						''
					);
				}

				return $node;
			}

		};

		yield [
			'/** @method int doFoo() */',
			'/** @method int doFoo<T of int>() */',
			$addMethodTemplateType,
		];

		yield [
			'/** @method int doFoo<U>() */',
			'/** @method int doFoo<U, T of int>() */',
			$addMethodTemplateType,
		];
	}

	/**
	 * @dataProvider dataPrintFormatPreserving
	 */
	public function testPrintFormatPreserving(string $phpDoc, string $expectedResult, NodeVisitor $visitor): void
	{
		$usedAttributes = ['lines' => true, 'indexes' => true];
		$constExprParser = new ConstExprParser(true, true, $usedAttributes);
		$phpDocParser = new PhpDocParser(
			new TypeParser($constExprParser, true, $usedAttributes),
			$constExprParser,
			true,
			true,
			$usedAttributes
		);
		$lexer = new Lexer();
		$tokens = new TokenIterator($lexer->tokenize($phpDoc));
		$phpDocNode = $phpDocParser->parse($tokens);
		$cloningTraverser = new NodeTraverser([new NodeVisitor\CloningVisitor()]);
		$newNodes = $cloningTraverser->traverse([$phpDocNode]);

		$changingTraverser = new NodeTraverser([$visitor]);

		/** @var PhpDocNode $newNode */
		[$newNode] = $changingTraverser->traverse($newNodes);

		$printer = new Printer();
		$newPhpDoc = $printer->printFormatPreserving($newNode, $phpDocNode, $tokens);
		$this->assertSame($expectedResult, $newPhpDoc);

		$this->assertEquals(
			$this->unsetAttributes($newNode),
			$this->unsetAttributes($phpDocParser->parse(new TokenIterator($lexer->tokenize($newPhpDoc))))
		);
	}

	private function unsetAttributes(PhpDocNode $node): PhpDocNode
	{
		$visitor = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				$node->setAttribute(Attribute::START_LINE, null);
				$node->setAttribute(Attribute::END_LINE, null);
				$node->setAttribute(Attribute::START_INDEX, null);
				$node->setAttribute(Attribute::END_INDEX, null);
				$node->setAttribute(Attribute::ORIGINAL_NODE, null);

				return $node;
			}

		};

		$traverser = new NodeTraverser([$visitor]);

		/** @var PhpDocNode */
		return $traverser->traverse([$node])[0];
	}

}
