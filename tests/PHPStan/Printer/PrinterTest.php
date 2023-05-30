<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use PHPStan\PhpDocParser\Ast\AbstractNodeVisitor;
use PHPStan\PhpDocParser\Ast\Attribute;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayItemNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\QuoteAwareConstExprStringNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\NodeVisitor;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineAnnotation;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineArgument;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineArray;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineArrayItem;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeParameterNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeNode;
use PHPStan\PhpDocParser\Ast\Type\OffsetAccessTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
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
use const PHP_EOL;

class PrinterTest extends TestCase
{

	/** @var TypeParser */
	private $typeParser;

	/** @var PhpDocParser */
	private $phpDocParser;

	protected function setUp(): void
	{
		$usedAttributes = ['lines' => true, 'indexes' => true];
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

		$changeCallableReturnTypeFactory = function (TypeNode $type): NodeVisitor {
			return new class ($type) extends AbstractNodeVisitor {

				/** @var TypeNode */
				private $type;

				public function __construct(TypeNode $type)
				{
					$this->type = $type;
				}

				public function enterNode(Node $node)
				{
					if ($node instanceof CallableTypeNode) {
						$node->returnType = $this->type;
					}

					return $node;
				}

			};
		};

		yield [
			'/** @param callable(): int $a */',
			'/** @param callable(): string $a */',
			$changeCallableReturnTypeFactory(new IdentifierTypeNode('string')),
		];

		yield [
			'/** @param callable(): int $a */',
			'/** @param callable(): (int|string) $a */',
			$changeCallableReturnTypeFactory(new UnionTypeNode([
				new IdentifierTypeNode('int'),
				new IdentifierTypeNode('string'),
			])),
		];

		yield [
			'/** @param callable(): (int|string) $a */',
			'/** @param callable(): string $a */',
			$changeCallableReturnTypeFactory(new IdentifierTypeNode('string')),
		];

		yield [
			'/** @param callable(): (int|string) $a */',
			'/** @param callable(): (string|int) $a */',
			$changeCallableReturnTypeFactory(new UnionTypeNode([
				new IdentifierTypeNode('string'),
				new IdentifierTypeNode('int'),
			])),
		];

		$changeCallableReturnTypeCallbackFactory = function (callable $callback): NodeVisitor {
			return new class ($callback) extends AbstractNodeVisitor {

				/** @var callable(TypeNode): TypeNode */
				private $callback;

				public function __construct(callable $callback)
				{
					$this->callback = $callback;
				}

				public function enterNode(Node $node)
				{
					if ($node instanceof CallableTypeNode) {
						$cb = $this->callback;
						$node->returnType = $cb($node->returnType);
					}

					return $node;
				}

			};
		};

		yield [
			'/** @param callable(): int $a */',
			'/** @param callable(): string $a */',
			$changeCallableReturnTypeCallbackFactory(static function (TypeNode $typeNode): TypeNode {
				return new IdentifierTypeNode('string');
			}),
		];

		yield [
			'/** @param callable(): (int) $a */',
			'/** @param callable(): string $a */',
			$changeCallableReturnTypeCallbackFactory(static function (TypeNode $typeNode): TypeNode {
				return new IdentifierTypeNode('string');
			}),
		];

		yield [
			'/** @param callable(): int $a */',
			'/** @param callable(): string $a */',
			$changeCallableReturnTypeCallbackFactory(static function (IdentifierTypeNode $typeNode): TypeNode {
				$typeNode->name = 'string';

				return $typeNode;
			}),
		];

		yield [
			'/** @param callable(): (int) $a */',
			'/** @param callable(): string $a */',
			$changeCallableReturnTypeCallbackFactory(static function (IdentifierTypeNode $typeNode): TypeNode {
				$typeNode->name = 'string';

				return $typeNode;
			}),
		];

		yield [
			'/** @param Foo&Bar&Baz $a */',
			'/** @param Foo&Bar&(Lorem|Ipsum) $a */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof IntersectionTypeNode) {
						$node->types[2] = new UnionTypeNode([
							new IdentifierTypeNode('Lorem'),
							new IdentifierTypeNode('Ipsum'),
						]);
					}

					return $node;
				}

			},
		];

		yield [
			'/** @param Foo&Bar $a */',
			'/** @param Foo&Bar&(Lorem|Ipsum) $a */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof IntersectionTypeNode) {
						$node->types[] = new UnionTypeNode([
							new IdentifierTypeNode('Lorem'),
							new IdentifierTypeNode('Ipsum'),
						]);
					}

					return $node;
				}

			},
		];

		yield [
			'/** @param Foo&Bar $a */',
			'/** @param (Lorem|Ipsum)&Foo&Bar $a */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof IntersectionTypeNode) {
						array_unshift($node->types, new UnionTypeNode([
							new IdentifierTypeNode('Lorem'),
							new IdentifierTypeNode('Ipsum'),
						]));
					}

					return $node;
				}

			},
		];

		yield [
			'/** @param Foo&Bar $a */',
			'/** @param (Lorem|Ipsum)&Baz&Dolor $a */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof IntersectionTypeNode) {
						$node->types = [
							new UnionTypeNode([
								new IdentifierTypeNode('Lorem'),
								new IdentifierTypeNode('Ipsum'),
							]),
							new IdentifierTypeNode('Baz'),
							new IdentifierTypeNode('Dolor'),
						];
					}

					return $node;
				}

			},
		];

		yield [
			'/** @var string&(integer|float) */',
			'/** @var string&(int|float) */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof IdentifierTypeNode && $node->name === 'integer') {
						$node->name = 'int';
					}

					return $node;
				}

			},
		];

		yield [
			'/** @var ArrayObject<int[]> */',
			'/** @var ArrayObject<array<int>> */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ArrayTypeNode) {
						return new GenericTypeNode(new IdentifierTypeNode('array'), [
							new IdentifierTypeNode('int'),
						], [
							GenericTypeNode::VARIANCE_INVARIANT,
						]);
					}

					return $node;
				}

			},
		];

		yield [
			'/** @return callable(): (null|null) */',
			'/** @return callable(): (int|null) */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof UnionTypeNode) {
						$node->types = [
							new IdentifierTypeNode('int'),
							new IdentifierTypeNode('null'),
						];
					}

					return $node;
				}

			},
		];

		yield [
			'/** @param \DateTimeImmutable::ATOM $date */',
			'/** @param DateTimeImmutable::ATOM $date */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ParamTagValueNode) {
						$node->type = new ConstTypeNode(new ConstFetchNode('DateTimeImmutable', 'ATOM'));
					}

					return $node;
				}

			},
		];

		yield [
			'/** @param \Lorem\Ipsum $ipsum */',
			'/** @param Ipsum $ipsum */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ParamTagValueNode) {
						$node->type = new IdentifierTypeNode('Ipsum');
					}

					return $node;
				}

			},
		];

		yield [
			'/** @phpstan-import-type Foo from \Bar as Lorem */',
			'/** @phpstan-import-type Foo from Bar as Lorem */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof TypeAliasImportTagValueNode) {
						$node->importedFrom = new IdentifierTypeNode('Bar');
					}

					return $node;
				}

			},
		];

		yield [
			'/** @Foo({a: 1}) */',
			'/** @Foo({1}) */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof DoctrineArrayItem) {
						$node->key = null;
					}

					return $node;
				}

			},
		];

		yield [
			'/** @Foo({a: 1}) */',
			'/** @Foo({b: 1}) */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof DoctrineArrayItem) {
						$node->key = new IdentifierTypeNode('b');
					}

					return $node;
				}

			},
		];

		yield [
			'/** @Foo({a = 1}) */',
			'/** @Foo({b = 1}) */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof DoctrineArrayItem) {
						$node->key = new IdentifierTypeNode('b');
					}

					return $node;
				}

			},
		];

		yield [
			'/** @Foo() */',
			'/** @Foo(1, 2, 3) */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof DoctrineAnnotation) {
						$node->arguments = [
							new DoctrineArgument(null, new ConstExprIntegerNode('1')),
							new DoctrineArgument(null, new ConstExprIntegerNode('2')),
							new DoctrineArgument(null, new ConstExprIntegerNode('3')),
						];
					}

					return $node;
				}

			},
		];

		yield [
			'/** @Foo(
			  *     1,
			  *     2,
			  *  ) */',
			'/** @Foo(
			  *     1,
			  *     2,
			  *     3,
			  *  ) */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof DoctrineAnnotation) {
						$node->arguments[] = new DoctrineArgument(null, new ConstExprIntegerNode('3'));
					}

					return $node;
				}

			},
		];

		yield [
			'/**' . PHP_EOL .
			' * @X({' . PHP_EOL .
			' *     1,' . PHP_EOL .
			' *     2' . PHP_EOL .
			' *    ,    ' . PHP_EOL .
			' *     3,' . PHP_EOL .
			' * }' . PHP_EOL .
			' * )' . PHP_EOL .
			' */',
			'/**' . PHP_EOL .
			' * @X({' . PHP_EOL .
			' *     1,' . PHP_EOL .
			' *     2' . PHP_EOL .
			' *    ,    ' . PHP_EOL .
			' *     3,' . PHP_EOL .
			' *     4,' . PHP_EOL .
			' * }' . PHP_EOL .
			' * )' . PHP_EOL .
			' */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof DoctrineArray) {
						$node->items[] = new DoctrineArrayItem(null, new ConstExprIntegerNode('4'));
					}

					return $node;
				}

			},
		];

		yield [
			'/** @Foo() */',
			'/** @Bar() */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof PhpDocTagNode) {
						$node->name = '@Bar';
					}
					if ($node instanceof DoctrineAnnotation) {
						$node->name = '@Bar';
					}

					return $node;
				}

			},
		];
	}

	/**
	 * @dataProvider dataPrintFormatPreserving
	 */
	public function testPrintFormatPreserving(string $phpDoc, string $expectedResult, NodeVisitor $visitor): void
	{
		$lexer = new Lexer(true);
		$tokens = new TokenIterator($lexer->tokenize($phpDoc));
		$phpDocNode = $this->phpDocParser->parse($tokens);
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
			$this->unsetAttributes($this->phpDocParser->parse(new TokenIterator($lexer->tokenize($newPhpDoc))))
		);
	}

	private function unsetAttributes(Node $node): Node
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

	/**
	 * @return iterable<list{TypeNode, string}>
	 */
	public function dataPrintType(): iterable
	{
		yield [
			new IdentifierTypeNode('int'),
			'int',
		];
		yield [
			new UnionTypeNode([
				new IdentifierTypeNode('int'),
				new IdentifierTypeNode('string'),
			]),
			'int|string',
		];
		yield [
			new GenericTypeNode(
				new IdentifierTypeNode('array'),
				[
					new IdentifierTypeNode('int'),
					new UnionTypeNode([
						new IdentifierTypeNode('int'),
						new IdentifierTypeNode('string'),
					]),
				],
				[
					GenericTypeNode::VARIANCE_INVARIANT,
					GenericTypeNode::VARIANCE_INVARIANT,
				]
			),
			'array<int, int|string>',
		];
		yield [
			new CallableTypeNode(new IdentifierTypeNode('callable'), [], new UnionTypeNode([
				new IdentifierTypeNode('int'),
				new IdentifierTypeNode('string'),
			])),
			'callable(): (int|string)',
		];
		yield [
			new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ArrayTypeNode(new ArrayTypeNode(new ArrayTypeNode(new IdentifierTypeNode('int'))))),
			'callable(): int[][][]',
		];
		yield [
			new ArrayTypeNode(
				new ArrayTypeNode(
					new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ArrayTypeNode(new IdentifierTypeNode('int')))
				)
			),
			'(callable(): int[])[][]',
		];
		yield [
			new NullableTypeNode(new UnionTypeNode([
				new IdentifierTypeNode('Foo'),
				new IdentifierTypeNode('Bar'),
			])),
			'?(Foo|Bar)',
		];
		yield [
			new UnionTypeNode([
				new IdentifierTypeNode('Foo'),
				new IntersectionTypeNode([
					new IdentifierTypeNode('Bar'),
					new IdentifierTypeNode('Baz'),
				]),
			]),
			'Foo|(Bar&Baz)',
		];
		yield [
			new NullableTypeNode(new ArrayTypeNode(new IdentifierTypeNode('Foo'))),
			'?Foo[]',
		];
		yield [
			new ArrayTypeNode(new NullableTypeNode(new IdentifierTypeNode('Foo'))),
			'(?Foo)[]',
		];
		yield [
			new UnionTypeNode([
				new IdentifierTypeNode('Foo'),
				new IdentifierTypeNode('Bar'),
				new UnionTypeNode([
					new IdentifierTypeNode('Baz'),
					new IdentifierTypeNode('Lorem'),
				]),
			]),
			'Foo|Bar|(Baz|Lorem)',
		];
	}

	/**
	 * @dataProvider dataPrintType
	 */
	public function testPrintType(TypeNode $node, string $expectedResult): void
	{
		$printer = new Printer();
		$phpDoc = $printer->print($node);
		$this->assertSame($expectedResult, $phpDoc);

		$lexer = new Lexer();
		$this->assertEquals(
			$this->unsetAttributes($node),
			$this->unsetAttributes($this->typeParser->parse(new TokenIterator($lexer->tokenize($phpDoc))))
		);
	}

	/**
	 * @return iterable<list{PhpDocNode, string}>
	 */
	public function dataPrintPhpDocNode(): iterable
	{
		yield [
			new PhpDocNode([
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new IdentifierTypeNode('int'),
					false,
					'$a',
					''
				)),
			]),
			'/**
 * @param int $a
 */',
		];
	}

	/**
	 * @dataProvider dataPrintPhpDocNode
	 */
	public function testPrintPhpDocNode(PhpDocNode $node, string $expectedResult): void
	{
		$printer = new Printer();
		$phpDoc = $printer->print($node);
		$this->assertSame($expectedResult, $phpDoc);

		$lexer = new Lexer();
		$this->assertEquals(
			$this->unsetAttributes($node),
			$this->unsetAttributes($this->phpDocParser->parse(new TokenIterator($lexer->tokenize($phpDoc))))
		);
	}

}
