<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use PHPStan\PhpDocParser\Ast\AbstractNodeVisitor;
use PHPStan\PhpDocParser\Ast\Attribute;
use PHPStan\PhpDocParser\Ast\Comment;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayItemNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\NodeVisitor;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineAnnotation;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineArgument;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineArray;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine\DoctrineArrayItem;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamClosureThisTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamImmediatelyInvokedCallableTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamLaterInvokedCallableTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PureUnlessCallableIsImpureTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeUnsealedTypeNode;
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
use PHPStan\PhpDocParser\ParserConfig;
use PHPUnit\Framework\TestCase;
use function array_map;
use function array_pop;
use function array_slice;
use function array_splice;
use function array_unshift;
use function array_values;
use function assert;
use function count;
use function implode;
use function preg_match;
use function preg_replace_callback;
use function preg_split;
use function str_repeat;
use function str_replace;
use function strlen;
use const PHP_EOL;

class PrinterTest extends TestCase
{

	private TypeParser $typeParser;

	private PhpDocParser $phpDocParser;

	protected function setUp(): void
	{
		$config = new ParserConfig(['lines' => true, 'indexes' => true, 'comments' => true]);
		$constExprParser = new ConstExprParser($config);
		$this->typeParser = new TypeParser($config, $constExprParser);
		$this->phpDocParser = new PhpDocParser(
			$config,
			$this->typeParser,
			$constExprParser,
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
		yield ['/** @api */', '/** @api */', $noopVisitor];
		yield ['/**
 */', '/**
 */', $noopVisitor];
		yield [
			'/** @param Foo $foo */',
			'/** @param Foo $foo */',
			$noopVisitor,
		];
		yield [
			self::nowdoc('
				/**
				 * @param Foo $foo
				 */'),
			self::nowdoc('
				/**
				 * @param Foo $foo
				 */'),
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
			self::nowdoc('
			/**
			 * @param Foo $foo
			 */'),
			self::nowdoc('
			/**
			 */'),
			$removeFirst,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @param Bar $bar
			 */'),
			$removeFirst,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @param Bar $bar
			 */'),
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
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @param Foo $foo
			 */'),
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
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @param Foo $foo
			 */'),
			$removeSecond,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Bar $bar
			 * @param Baz $baz
			 */'),
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Baz $baz
			 */'),
			$removeSecond,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Bar $bar
			 * @param Baz $baz
			 */'),
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Baz $baz
			 */'),
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
			self::nowdoc('
			/**
			 * @return Foo
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @return Bar
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			$changeReturnType,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @return Foo
			 * @param Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @return Bar
			 * @param Bar $bar
			 */'),
			$changeReturnType,
		];

		yield [
			self::nowdoc('
			/**
			 * @return Foo
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @return Bar
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			$changeReturnType,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo Foo description
			 * @return Foo Foo return description
			 * @param Bar $bar Bar description
			 */'),
			self::nowdoc('
			/**
			 * @param Foo $foo Foo description
			 * @return Bar Foo return description
			 * @param Bar $bar Bar description
			 */'),
			$changeReturnType,
		];

		$replaceFirst = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					$node->children[0] = new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('Baz'), false, '$a', '', false));
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
			self::nowdoc('
			/**
			 * @param Foo $foo
			 */'),
			self::nowdoc('
			/**
			 * @param Baz $a
			 */'),
			$replaceFirst,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 */'),
			self::nowdoc('
			/**
			 * @param Baz $a
			 */'),
			$replaceFirst,
		];

		$insertFirst = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					array_unshift($node->children, new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('Baz'), false, '$a', '', false)));

					return $node;
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 */'),
			self::nowdoc('
			/**
			 * @param Baz $a
			 * @param Foo $foo
			 */'),
			$insertFirst,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 */'),
			self::nowdoc('
			/**
			 * @param Baz $a
			 * @param Foo $foo
			 */'),
			$insertFirst,
		];

		$insertSecond = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					array_splice($node->children, 1, 0, [
						new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('Baz'), false, '$a', '', false)),
					]);

					return $node;
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 */'),
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Baz $a
			 */'),
			$insertSecond,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Baz $a
			 * @param Bar $bar
			 */'),
			$insertSecond,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Baz $a
			 * @param Bar $bar
			 */'),
			$insertSecond,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Baz $a
			 * @param Bar $bar
			 */'),
			$insertSecond,
		];

		$replaceLast = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof PhpDocNode) {
					$node->children[count($node->children) - 1] = new PhpDocTagNode('@param', new ParamTagValueNode(new IdentifierTypeNode('Baz'), false, '$a', '', false));
					return $node;
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 */'),
			self::nowdoc('
			/**
			 * @param Baz $a
			 */'),
			$replaceLast,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @param Foo $foo
			 * @param Baz $a
			 */'),
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
			self::nowdoc('
			/**
			 * @param Bar|Baz $foo
			 */'),
			self::nowdoc('
			/**
			 * @param Foo|Bar|Baz $foo
			 */'),
			$insertFirstTypeInUnionType,
		];

		yield [
			self::nowdoc('
			/**
			 * @param Bar|Baz $foo
			 * @param Foo $bar
			 */'),
			self::nowdoc('
			/**
			 * @param Foo|Bar|Baz $foo
			 * @param Foo $bar
			 */'),
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
			self::nowdoc('
			/**
			 * @param Foo|Bar $bar
			 */'),
			self::nowdoc('
			/**
			 * @param Lorem|Ipsum $bar
			 */'),
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

		$addCallableTemplateType = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof CallableTypeNode) {
					$node->templateTypes[] = new TemplateTagValueNode(
						'T',
						new IdentifierTypeNode('int'),
						'',
					);
				}

				return $node;
			}

		};

		yield [
			'/** @var Closure(): T */',
			'/** @var Closure<T of int>(): T */',
			$addCallableTemplateType,
		];

		yield [
			'/** @var \Closure<U>(U): T */',
			'/** @var \Closure<U, T of int>(U): T */',
			$addCallableTemplateType,
		];

		yield [
			self::nowdoc('
			/**
			 * @param callable(): void $cb
			 */'),
			self::nowdoc('
			/**
			 * @param callable(Foo $foo, Bar $bar): void $cb
			 */'),
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
			self::nowdoc('
			/**
			 * @param callable(Foo $foo, Bar $bar): void $cb
			 */'),
			self::nowdoc('
			/**
			 * @param callable(): void $cb
			 */'),
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
			self::nowdoc('
			/**
			 * @param callable(Foo $foo, Bar $bar): void $cb
			 * @param callable(): void $cb2
			 */'),
			self::nowdoc('
			/**
			 * @param Closure(Foo $foo, Bar $bar): void $cb
			 * @param Closure(): void $cb2
			 */'),
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
			self::nowdoc('
			/**
			 * @return array{float, Foo}
			 */'),
			self::nowdoc('
			/**
			 * @return array{float, int, Foo, string}
			 */'),
			$addItemsToArrayShape,
		];

		yield [
			self::nowdoc('
			/**
			 * @return array{
			 *   float,
			 *   Foo,
			 * }
			 */'),
			self::nowdoc('
			/**
			 * @return array{
			 *   float,
			 *   int,
			 *   Foo,
			 *   string,
			 * }
			 */'),
			$addItemsToArrayShape,
		];

		yield [
			self::nowdoc('
			/**
			 * @return array{
			 *   float,
			 *   Foo
			 * }
			 */'),
			self::nowdoc('
			/**
			 * @return array{
			 *   float,
			 *   int,
			 *   Foo,
			 *   string
			 * }
			 */'),
			$addItemsToArrayShape,
		];

		yield [
			self::nowdoc('
			/**
			 * @return array{
			 *     float,
			 *     Foo
			 * }
			 */'),
			self::nowdoc('
			/**
			 * @return array{
			 *     float,
			 *     int,
			 *     Foo,
			 *     string
			 * }
			 */'),
			$addItemsToArrayShape,
		];

		yield [
			self::nowdoc('
			/**
			 * @return array{
			 *   float,
			 *   Foo,
			 * }
			 */'),
			self::nowdoc('
			/**
			 * @return array{
			 *   float,
			 *   int,
			 *   Foo,
			 *   string,
			 * }
			 */'),
			$addItemsToArrayShape,
		];

		yield [
			self::nowdoc('
			/**
			 * @return array{
			 *   float,
			 *   Foo
			 * }
			 */'),
			self::nowdoc('
			/**
			 * @return array{
			 *   float,
			 *   int,
			 *   Foo,
			 *   string
			 * }
			 */'),
			$addItemsToArrayShape,
		];

		yield [
			self::nowdoc('
			/**
			 * @return array{
			 *     float,
			 *     Foo
			 * }
			 */'),
			self::nowdoc('
			/**
			 * @return array{
			 *     float,
			 *     int,
			 *     Foo,
			 *     string
			 * }
			 */'),
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
			self::nowdoc('
				/**
				 * @return object{}
				 */'),
			self::nowdoc('
				/**
				 * @return object{foo: int}
				 */'),
			$addItemsToObjectShape,
		];

		yield [
			self::nowdoc('
				/**
				 * @return object{bar: string}
				 */'),
			self::nowdoc('
				/**
				 * @return object{bar: string, foo: int}
				 */'),
			$addItemsToObjectShape,
		];

		yield [
			self::nowdoc('
			/**
			 * @return object{bar: string}
			 */'),
			self::nowdoc('
			/**
			 * @return object{bar: string, foo: int}
			 */'),
			$addItemsToObjectShape,
		];

		$addItemsWithCommentsToMultilineArrayShape = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ArrayShapeNode) {
					$commentedNode = new ArrayShapeItemNode(new IdentifierTypeNode('b'), false, new IdentifierTypeNode('int'));
					$commentedNode->setAttribute(Attribute::COMMENTS, [new Comment('// bar')]);
					array_splice($node->items, 1, 0, [
						$commentedNode,
					]);
					$commentedNode = new ArrayShapeItemNode(new IdentifierTypeNode('d'), false, new IdentifierTypeNode('string'));
					$commentedNode->setAttribute(Attribute::COMMENTS, [new Comment(
						PrinterTest::nowdoc('
						// first comment'),
					)]);
					$node->items[] = $commentedNode;

					$commentedNode = new ArrayShapeItemNode(new IdentifierTypeNode('e'), false, new IdentifierTypeNode('string'));
					$commentedNode->setAttribute(Attribute::COMMENTS, [new Comment(
						PrinterTest::nowdoc('
						// second comment'),
					)]);
					$node->items[] = $commentedNode;

					$commentedNode = new ArrayShapeItemNode(new IdentifierTypeNode('f'), false, new IdentifierTypeNode('string'));
					$commentedNode->setAttribute(Attribute::COMMENTS, [
						new Comment('// third comment'),
						new Comment('// fourth comment'),
					]);
					$node->items[] = $commentedNode;
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
			/**
			 * @return array{
			 *  // foo
			 *	a: int,
			 *	c: string
			 * }
			 */'),
			self::nowdoc('
			/**
			 * @return array{
			 *  // foo
			 *	a: int,
			 *  // bar
			 *  b: int,
			 *	c: string,
			 *  // first comment
			 *  d: string,
			 *  // second comment
			 *  e: string,
			 *  // third comment
			 *  // fourth comment
			 *  f: string
			 * }
			 */'),
			$addItemsWithCommentsToMultilineArrayShape,
		];

		$prependItemsWithCommentsToMultilineArrayShape = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ArrayShapeNode) {
					$commentedNode = new ArrayShapeItemNode(new IdentifierTypeNode('a'), false, new IdentifierTypeNode('int'));
					$commentedNode->setAttribute(Attribute::COMMENTS, [new Comment('// first item')]);
					array_splice($node->items, 0, 0, [
						$commentedNode,
					]);
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
			/**
			 * @return array{
			 *  b: int,
			 * }
			 */'),
			self::nowdoc('
			/**
			 * @return array{
			 *  // first item
			 *  a: int,
			 *  b: int,
			 * }
			 */'),
			$prependItemsWithCommentsToMultilineArrayShape,
		];

		$changeCommentOnArrayShapeItem = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ArrayShapeItemNode) {
					$node->setAttribute(Attribute::COMMENTS, [new Comment('// puppies')]);
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
			/**
			 * @return array{
			 *   a: int,
			 * }
			 */'),
			self::nowdoc('
			/**
			 * @return array{
			 *   // puppies
			 *   a: int,
			 * }
			 */'),
			$changeCommentOnArrayShapeItem,
		];

		$addItemsWithCommentsToObjectShape = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ObjectShapeNode) {
					$item = new ObjectShapeItemNode(new IdentifierTypeNode('foo'), false, new IdentifierTypeNode('int'));
					$item->setAttribute(Attribute::COMMENTS, [new Comment('// favorite foo')]);
					$node->items[] = $item;
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
				/**
				 * @return object{
				 *   // your favorite bar
				 *   bar: string
				 * }
				 */'),
			self::nowdoc('
				/**
				 * @return object{
				 *   // your favorite bar
				 *   bar: string,
				 *	 // favorite foo
				 *	 foo: int
				 * }
				 */'),
			$addItemsWithCommentsToObjectShape,
		];

		$removeItemWithComment = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if (!$node instanceof ArrayShapeNode) {
					return null;
				}

				foreach ($node->items as $i => $item) {
					if ($item->keyName === null) {
						continue;
					}

					$comments = $item->keyName->getAttribute(Attribute::COMMENTS);
					if ($comments === null) {
						continue;
					}
					if ($comments === []) {
						continue;
					}

					unset($node->items[$i]);
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
			/**
			 * @return array{
			 *  a: string,
			 *  // b comment
			 *  b: int,
			 * }
			 */'),
			self::nowdoc('
			/**
			 * @return array{
			 *  a: string,
			 * }
			 */'),
			$removeItemWithComment,
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

		yield [
			'/** @template T super string */',
			'/** @template T of int super string */',
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

		$addTemplateTagLowerBound = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof TemplateTagValueNode) {
					$node->lowerBound = new IdentifierTypeNode('int');
				}

				return $node;
			}

		};

		yield [
			'/** @template T */',
			'/** @template T super int */',
			$addTemplateTagLowerBound,
		];

		yield [
			'/** @template T super string */',
			'/** @template T super int */',
			$addTemplateTagLowerBound,
		];

		yield [
			'/** @template T of string */',
			'/** @template T of string super int */',
			$addTemplateTagLowerBound,
		];

		$removeTemplateTagLowerBound = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof TemplateTagValueNode) {
					$node->lowerBound = null;
				}

				return $node;
			}

		};

		yield [
			'/** @template T super int */',
			'/** @template T */',
			$removeTemplateTagLowerBound,
		];

		$addKeyNameToArrayShapeItemNode = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ArrayShapeItemNode) {
					$node->keyName = new ConstExprStringNode('test', ConstExprStringNode::SINGLE_QUOTED);
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
			self::nowdoc('
			/**
			 * @param int $a
			 */'),
			self::nowdoc('
			/**
			 * @param int $bz
			 */'),
			$changeParameterName,
		];

		yield [
			self::nowdoc('
			/**
			 * @param int $a
			 * @return string
			 */'),
			self::nowdoc('
			/**
			 * @param int $bz
			 * @return string
			 */'),
			$changeParameterName,
		];

		yield [
			self::nowdoc('
			/**
			 * @param int $a haha description
			 * @return string
			 */'),
			self::nowdoc('
			/**
			 * @param int $bz haha description
			 * @return string
			 */'),
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
			self::nowdoc('
			/**
			 * @param int $a haha
			 */'),
			self::nowdoc('
			/**
			 * @param int $a hehe
			 */'),
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
			self::nowdoc('
			/**
			 * @param Foo[awesome] $a haha
			 */'),
			self::nowdoc('
			/**
			 * @param Foo[baz] $a haha
			 */'),
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
			self::nowdoc('
			/**
			 * @phpstan-import-type TypeAlias from AnotherClass as DifferentAlias
			 */'),
			self::nowdoc('
			/**
			 * @phpstan-import-type TypeAlias from AnotherClass as Ciao
			 */'),
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
			self::nowdoc('
			/**
			 * @phpstan-import-type TypeAlias from AnotherClass as DifferentAlias
			 */'),
			self::nowdoc('
			/**
			 * @phpstan-import-type TypeAlias from AnotherClass
			 */'),
			$removeImportAs,
		];

		$addMethodTemplateType = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof MethodTagValueNode) {
					$node->templateTypes[] = new TemplateTagValueNode(
						'T',
						new IdentifierTypeNode('int'),
						'',
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

		$changeCallableReturnTypeFactory = static fn (TypeNode $type): NodeVisitor => new class ($type) extends AbstractNodeVisitor {

			private TypeNode $type;

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

		$changeCallableReturnTypeCallbackFactory = fn (callable $callback): NodeVisitor => new class ($callback) extends AbstractNodeVisitor {

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

		yield [
			'/** @param callable(): int $a */',
			'/** @param callable(): string $a */',
			$changeCallableReturnTypeCallbackFactory(static fn (TypeNode $typeNode) => new IdentifierTypeNode('string')),
		];

		yield [
			'/** @param callable(): (int) $a */',
			'/** @param callable(): string $a */',
			$changeCallableReturnTypeCallbackFactory(static fn (TypeNode $typeNode) => new IdentifierTypeNode('string')),
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
			self::nowdoc('
			/** @Foo(
			 *     1,
			 *     2,
			 *  )
			 */'),
			self::nowdoc('
			/** @Foo(
			 *     1,
			 *     2,
			 *     3,
			 *  )
			 */'),
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

		yield [
			'/** @param-immediately-invoked-callable $foo test */',
			'/** @param-immediately-invoked-callable $bar foo */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ParamImmediatelyInvokedCallableTagValueNode) {
						$node->parameterName = '$bar';
						$node->description = 'foo';
					}

					return $node;
				}

			},
		];

		yield [
			'/** @param-later-invoked-callable $foo test */',
			'/** @param-later-invoked-callable $bar foo */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ParamLaterInvokedCallableTagValueNode) {
						$node->parameterName = '$bar';
						$node->description = 'foo';
					}

					return $node;
				}

			},
		];

		yield [
			'/** @param-closure-this Foo $test haha */',
			'/** @param-closure-this Bar $taste hehe */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ParamClosureThisTagValueNode) {
						$node->type = new IdentifierTypeNode('Bar');
						$node->parameterName = '$taste';
						$node->description = 'hehe';
					}

					return $node;
				}

			},
		];

		yield [
			'/** @pure-unless-callable-is-impure $foo test */',
			'/** @pure-unless-callable-is-impure $bar foo */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof PureUnlessCallableIsImpureTagValueNode) {
						$node->parameterName = '$bar';
						$node->description = 'foo';
					}

					return $node;
				}

			},
		];

		yield [
			'/** @return Foo[abc] */',
			'/** @return self::FOO[abc] */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ReturnTagValueNode && $node->type instanceof OffsetAccessTypeNode) {
						$node->type->type = new ConstTypeNode(new ConstFetchNode('self', 'FOO'));
					}

					return $node;
				}

			},
		];

		yield [
			'/** @return array{foo: int, ...} */',
			'/** @return array{foo: int} */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ArrayShapeNode) {
						$node->sealed = true;
					}

					return $node;
				}

			},
		];

		yield [
			'/** @return array{foo: int, ...} */',
			'/** @return array{foo: int, ...<string>} */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ArrayShapeNode) {
						$node->unsealedType = new ArrayShapeUnsealedTypeNode(new IdentifierTypeNode('string'), null);
					}

					return $node;
				}

			},
		];

		yield [
			'/** @return array{foo: int, ...<string>} */',
			'/** @return array{foo: int, ...<int, string>} */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ArrayShapeNode) {
						assert($node->unsealedType !== null);
						$node->unsealedType->keyType = new IdentifierTypeNode('int');
					}

					return $node;
				}

			},
		];

		yield [
			'/** @return array{foo: int, ...<string>} */',
			'/** @return array{foo: int} */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ArrayShapeNode) {
						$node->sealed = true;
						$node->unsealedType = null;
					}

					return $node;
				}

			},
		];

		yield [
			'/** @return list{int, ...} */',
			'/** @return list{int} */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ArrayShapeNode) {
						$node->sealed = true;
					}

					return $node;
				}

			},
		];

		yield [
			'/** @return list{int, ...} */',
			'/** @return list{int, ...<string>} */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ArrayShapeNode) {
						$node->unsealedType = new ArrayShapeUnsealedTypeNode(new IdentifierTypeNode('string'), null);
					}

					return $node;
				}

			},
		];

		yield [
			'/** @return list{int, ...<string>} */',
			'/** @return list{int} */',
			new class extends AbstractNodeVisitor {

				public function enterNode(Node $node)
				{
					if ($node instanceof ArrayShapeNode) {
						$node->sealed = true;
						$node->unsealedType = null;
					}

					return $node;
				}

			},
		];

		$singleCommentLineAddFront = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ArrayShapeNode) {
					array_unshift($node->items, PrinterTest::withComment(
						new ArrayShapeItemNode(null, false, new IdentifierTypeNode('float')),
						'// A fractional number',
					));
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
				/**
				 * @param array{} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{float} $foo
				 */'),
			$singleCommentLineAddFront,
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{string} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{// A fractional number
				 *  float,
				 *  string} $foo
				 */'),
			$singleCommentLineAddFront,
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{
				 *   string,int} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{
				 *   // A fractional number
				 *   float,
				 *   string,int} $foo
				 */'),
			$singleCommentLineAddFront,
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{
				 *   string,
				 *	 int
				 * } $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{
				 *   // A fractional number
				 *   float,
				 *   string,
				 *   int
				 * } $foo
				 */'),
			$singleCommentLineAddFront,
		];

		$singleCommentLineAddMiddle = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				$newItem = PrinterTest::withComment(
					new ArrayShapeItemNode(null, false, new IdentifierTypeNode('float')),
					'// A fractional number',
				);

				if ($node instanceof ArrayShapeNode) {
					if (count($node->items) === 0) {
						$node->items[] = $newItem;
					} else {
						array_splice($node->items, 1, 0, [$newItem]);
					}
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
				/**
				 * @param array{} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{float} $foo
				 */'),
			$singleCommentLineAddMiddle,
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{string} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{string,
				 *  // A fractional number
				 *  float} $foo
				 */'),
			$singleCommentLineAddMiddle,
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{
				 *   string,int} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{
				 *   string,
				 *   // A fractional number
				 *   float,int} $foo
				 */'),
			$singleCommentLineAddMiddle,
		];

		yield [
			self::nowdoc('
				/**
				 * @param array{
				 *   string,
				 *	 int
				 * } $foo
				 */'),
			self::nowdoc('
				/**
				 * @param array{
				 *   string,
				 *   // A fractional number
				 *   float,
				 *   int
				 * } $foo
				 */'),
			$singleCommentLineAddMiddle,
		];

		$addCommentToCallableParamsFront = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof CallableTypeNode) {
					array_unshift($node->parameters, PrinterTest::withComment(
						new CallableTypeParameterNode(new IdentifierTypeNode('Foo'), false, false, '$foo', false),
						'// never pet a burning dog',
					));
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
				/**
				 * @param callable(Bar $bar): int $a
				 */'),
			self::nowdoc('
				/**
				 * @param callable(// never pet a burning dog
				 *  Foo $foo,
				 *  Bar $bar): int $a
				 */'),
			$addCommentToCallableParamsFront,
		];

		$addCommentToCallableParamsMiddle = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof CallableTypeNode) {
					$node->parameters[] = PrinterTest::withComment(
						new CallableTypeParameterNode(new IdentifierTypeNode('Bar'), false, false, '$bar', false),
						'// never pet a burning dog',
					);
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
				/**
				 * @param callable(Foo $foo): int $a
				 */'),
			self::nowdoc('
				/**
				 * @param callable(Foo $foo,
				 *  // never pet a burning dog
				 *  Bar $bar): int $a
				 */'),
			$addCommentToCallableParamsMiddle,
		];

		$addCommentToObjectShapeItemFront = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ObjectShapeNode) {
					array_unshift($node->items, PrinterTest::withComment(
						new ObjectShapeItemNode(new IdentifierTypeNode('foo'), false, new IdentifierTypeNode('float')),
						'// A fractional number',
					));
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
				/**
				 * @param object{bar: string} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{// A fractional number
				 *  foo: float,
				 *  bar: string} $foo
				 */'),
			$addCommentToObjectShapeItemFront,
		];

		yield [
			self::nowdoc('
				/**
				 * @param object{
				 *   bar:string,naz:int} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{
				 *   // A fractional number
				 *   foo: float,
				 *   bar:string,naz:int} $foo
				 */'),
			$addCommentToObjectShapeItemFront,
		];

		yield [
			self::nowdoc('
				/**
				 * @param object{
				 *   bar:string,
				 *	 naz:int
				 * } $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{
				 *   // A fractional number
				 *   foo: float,
				 *   bar:string,
				 *   naz:int
				 * } $foo
				 */'),
			$addCommentToObjectShapeItemFront,
		];

		$addCommentToObjectShapeItemMiddle = new class extends AbstractNodeVisitor {

			public function enterNode(Node $node)
			{
				if ($node instanceof ObjectShapeNode) {
					$newItem = PrinterTest::withComment(
						new ObjectShapeItemNode(new IdentifierTypeNode('bar'), false, new IdentifierTypeNode('float')),
						'// A fractional number',
					);
					if (count($node->items) === 0) {
						$node->items[] = $newItem;
					} else {
						array_splice($node->items, 1, 0, [$newItem]);
					}
				}

				return $node;
			}

		};

		yield [
			self::nowdoc('
				/**
				 * @param object{} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{bar: float} $foo
				 */'),
			$addCommentToObjectShapeItemMiddle,
		];

		yield [
			self::nowdoc('
				/**
				 * @param object{foo:string} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{foo:string,
				 *  // A fractional number
				 *  bar: float} $foo
				 */'),
			$addCommentToObjectShapeItemMiddle,
		];

		yield [
			self::nowdoc('
				/**
				 * @param object{
				 *   foo:string,naz:int} $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{
				 *   foo:string,
				 *   // A fractional number
				 *   bar: float,naz:int} $foo
				 */'),
			$addCommentToObjectShapeItemMiddle,
		];

		yield [
			self::nowdoc('
				/**
				 * @param object{
				 *   foo:string,
				 *	 naz:int
				 * } $foo
				 */'),
			self::nowdoc('
				/**
				 * @param object{
				 *   foo:string,
				 *   // A fractional number
				 *   bar: float,
				 *   naz:int
				 * } $foo
				 */'),
			$addCommentToObjectShapeItemMiddle,
		];
	}

	/**
	 * @dataProvider dataPrintFormatPreserving
	 */
	public function testPrintFormatPreserving(string $phpDoc, string $expectedResult, NodeVisitor $visitor): void
	{
		$config = new ParserConfig(['lines' => true, 'indexes' => true, 'comments' => true]);
		$lexer = new Lexer($config);
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
			$this->unsetAttributes($this->phpDocParser->parse(new TokenIterator($lexer->tokenize($newPhpDoc)))),
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
				$node->setAttribute(Attribute::COMMENTS, null);

				return $node;
			}

		};

		$traverser = new NodeTraverser([$visitor]);

		/** @var PhpDocNode */
		return $traverser->traverse([$node])[0];
	}

	/**
	 * @return iterable<array{TypeNode, string}>
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
				],
			),
			'array<int, int|string>',
		];
		yield [
			new CallableTypeNode(new IdentifierTypeNode('callable'), [], new UnionTypeNode([
				new IdentifierTypeNode('int'),
				new IdentifierTypeNode('string'),
			]), []),
			'callable(): (int|string)',
		];
		yield [
			new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ArrayTypeNode(new ArrayTypeNode(new ArrayTypeNode(new IdentifierTypeNode('int')))), []),
			'callable(): int[][][]',
		];
		yield [
			new ArrayTypeNode(
				new ArrayTypeNode(
					new CallableTypeNode(new IdentifierTypeNode('callable'), [], new ArrayTypeNode(new IdentifierTypeNode('int')), []),
				),
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
		yield [
			new OffsetAccessTypeNode(
				new ConstTypeNode(new ConstFetchNode('self', 'TYPES')),
				new IdentifierTypeNode('int'),
			),
			'self::TYPES[int]',
		];
		yield [
			ArrayShapeNode::createSealed([
				new ArrayShapeItemNode(
					new IdentifierTypeNode('name'),
					false,
					new IdentifierTypeNode('string'),
				),
				new ArrayShapeItemNode(
					new ConstExprStringNode('Full Name', ConstExprStringNode::SINGLE_QUOTED),
					false,
					new IdentifierTypeNode('string'),
				),
			]),
			"array{name: string, 'Full Name': string}",
		];
		yield [
			new ObjectShapeNode([
				new ObjectShapeItemNode(
					new IdentifierTypeNode('name'),
					false,
					new IdentifierTypeNode('string'),
				),
				new ObjectShapeItemNode(
					new ConstExprStringNode('Full Name', ConstExprStringNode::SINGLE_QUOTED),
					false,
					new IdentifierTypeNode('string'),
				),
			]),
			"object{name: string, 'Full Name': string}",
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

		$config = new ParserConfig([]);
		$lexer = new Lexer($config);
		$this->assertEquals(
			$this->unsetAttributes($node),
			$this->unsetAttributes($this->typeParser->parse(new TokenIterator($lexer->tokenize($phpDoc)))),
		);
	}

	/**
	 * @return iterable<array{PhpDocNode, string}>
	 */
	public function dataPrintPhpDocNode(): iterable
	{
		yield [
			new PhpDocNode([
				new PhpDocTagNode('@param', new ParamTagValueNode(
					new IdentifierTypeNode('int'),
					false,
					'$a',
					'',
					false,
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

		$config = new ParserConfig([]);
		$lexer = new Lexer($config);
		$this->assertEquals(
			$this->unsetAttributes($node),
			$this->unsetAttributes($this->phpDocParser->parse(new TokenIterator($lexer->tokenize($phpDoc)))),
		);
	}

	/**
	 * @template TNode of Node
	 * @param TNode $node
	 * @return TNode
	 */
	public static function withComment(Node $node, string $comment): Node
	{
		$node->setAttribute(Attribute::COMMENTS, [new Comment($comment)]);
		return $node;
	}

	public static function nowdoc(string $str): string
	{
		$lines = preg_split('/\\n/', $str);

		if ($lines === false) {
			return '';
		}

		if (count($lines) < 2) {
			return '';
		}

		// Toss out the first line
		$lines = array_slice($lines, 1, count($lines) - 1);

		// normalize any tabs to spaces
		$lines = array_map(static fn ($line) => preg_replace_callback('/(\t+)/m', static function ($matches) {
			$fixed = str_repeat('  ', strlen($matches[1]));
			return $fixed;
		}, $line), $lines);

		// take the ws from the first line and subtract them from all lines
		$matches = [];

		if (preg_match('/(^[ \t]+)/', $lines[0] ?? '', $matches) !== 1) {
			return '';
		}

		$numLines = count($lines);
		for ($i = 0; $i < $numLines; ++$i) {
			$lines[$i] = str_replace($matches[0], '', $lines[$i] ?? '');
		}

		return implode("\n", $lines);
	}

}
