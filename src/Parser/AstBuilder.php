<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use LogicException;
use PHPStan\PhpDocParser\Ast;
use PHPStan\PhpDocParser\Ast\Attribute;
use PHPStan\PhpDocParser\Ast\Comment;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\Doctrine;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\Generated\GeneratedParser;
use PHPStan\PhpDocParser\Parser\Phplrt\Fragment;
use PHPStan\PhpDocParser\Parser\Phplrt\Token;
use PHPStan\PhpDocParser\Parser\Phplrt\TokenStream;
use PHPStan\PhpDocParser\ParserConfig;
use function array_flip;
use function array_key_last;
use function array_keys;
use function array_shift;
use function constant;
use function count;
use function defined;
use function in_array;
use function is_string;
use function str_replace;
use function strlen;
use function strncmp;
use function strtolower;
use function substr;
use function trim;

/**
 * Builds the AST out of what the parser generated from "doc/grammars" has
 * recognized.
 *
 * Every method named "reduce" followed by the name of a rule of the grammar
 * builds the value of that rule: "tools/phplrt/build.php" reads their names to
 * know which rules to keep in the trace, and the runtime calls them bottom-up,
 * left to right, once the whole input has been recognized. Each of them is
 * handed the values of what the rule has read as one flat list: the tokens it
 * has read itself, in between the values of the rules inside it that have been
 * reduced already.
 *
 * @internal
 */
final class AstBuilder extends GeneratedParser
{

	/**
	 * The tokens that may be written in so many places that they say nothing
	 * about what has been expected.
	 */
	private const INCIDENTAL_TOKENS = [
		Lexer::TOKEN_PHPDOC_EOL => true,
		Lexer::TOKEN_COMMENT => true,
		Lexer::TOKEN_NULLABLE => true,
		Lexer::TOKEN_DOUBLE_COLON => true,
	];

	/**
	 * The tags whose description follows a type right away.
	 */
	private const DESCRIPTION_AFTER_TYPE = [
		'var' => true,
		'return' => true,
		'throws' => true,
		'mixin' => true,
		'require-extends' => true,
		'require-implements' => true,
		'sealed' => true,
		'self-out' => true,
		'template' => true,
		'extends' => true,
		'implements' => true,
		'use' => true,
	];

	private ParserConfig $config;

	private bool $useAttributes;

	/** @var list<array{string, int, int}> the tokens of the lexer being read */
	private array $tokens = [];

	/** the tag the tokens read begin with, which is not one of the lexer */
	private ?string $tag = null;

	/** @var array<int, int>|null the position each token of the lexer is read at */
	private ?array $positions = null;

	/** @var array<int, int>|null the offset of each token of the lexer, in bytes */
	private ?array $byteOffsets = null;

	/** the index of the first token of the last text read, or -1 when it is empty */
	private int $textStart = -1;

	/** @var array<int, true> */
	private array $identifiers = [];

	private int $tUnion;

	private int $tIntersection;

	private int $tIs;

	private int $tNot;

	private int $tVariable;

	private int $tThis;

	private int $tColon;

	private int $tNullable;

	private int $tVariadic;

	private int $tReference;

	private int $tEqual;

	private int $tOf;

	private int $tAs;

	private int $tSuper;

	private int $tCovariant;

	private int $tContravariant;

	private int $tInteger;

	private int $tSingleQuoted;

	private int $tDoubleQuoted;

	private int $tStatic;

	private int $tNegated;

	private int $tOpenParentheses;

	private int $tOpenParenthesesWs;

	private int $tComment;

	/** @var array<int, string> the kind of tag value each known tag token is read as */
	private array $tagKinds = [];

	public function __construct(ParserConfig $config)
	{
		$this->config = $config;
		$this->useAttributes = $config->useLinesAttributes || $config->useIndexAttributes || $config->useCommentsAttributes;

		$ids = self::TOKENS;
		foreach ($ids as $name => $id) {
			if ($name !== 'T_IDENTIFIER' && strncmp($name, 'T_KEYWORD_', 10) !== 0) {
				continue;
			}

			$this->identifiers[$id] = true;
		}

		$this->tUnion = $ids['T_UNION'];
		$this->tIntersection = $ids['T_INTERSECTION'];
		$this->tIs = $ids['T_KEYWORD_IS'];
		$this->tNot = $ids['T_KEYWORD_NOT'];
		$this->tVariable = $ids['T_VARIABLE'];
		$this->tThis = $ids['T_THIS_VARIABLE'];
		$this->tColon = $ids['T_COLON'];
		$this->tNullable = $ids['T_NULLABLE'];
		$this->tVariadic = $ids['T_VARIADIC'];
		$this->tReference = $ids['T_REFERENCE'];
		$this->tEqual = $ids['T_EQUAL'];
		$this->tOf = $ids['T_KEYWORD_OF'];
		$this->tAs = $ids['T_KEYWORD_AS'];
		$this->tSuper = $ids['T_KEYWORD_SUPER'];
		$this->tCovariant = $ids['T_KEYWORD_COVARIANT'];
		$this->tContravariant = $ids['T_KEYWORD_CONTRAVARIANT'];
		$this->tInteger = $ids['T_INTEGER'];
		$this->tSingleQuoted = $ids['T_SINGLE_QUOTED_STRING'];
		$this->tDoubleQuoted = $ids['T_DOUBLE_QUOTED_STRING'];
		$this->tStatic = $ids['T_KEYWORD_STATIC'];
		$this->tNegated = $ids['T_NEGATED'];
		$this->tOpenParentheses = $ids['T_OPEN_PARENTHESES'];
		$this->tOpenParenthesesWs = $ids['T_OPEN_PARENTHESES_WS'];
		$this->tComment = $ids['T_COMMENT'];

		foreach ([
			'T_TAG_PARAM' => 'param',
			'T_TAG_VAR' => 'var',
			'T_TAG_RETURN' => 'return',
			'T_TAG_THROWS' => 'throws',
			'T_TAG_MIXIN' => 'mixin',
			'T_TAG_REQUIRE_EXTENDS' => 'require-extends',
			'T_TAG_REQUIRE_IMPLEMENTS' => 'require-implements',
			'T_TAG_SEALED' => 'sealed',
			'T_TAG_SELF_OUT' => 'self-out',
			'T_TAG_PROPERTY' => 'property',
			'T_TAG_PARAM_OUT' => 'param-out',
			'T_TAG_PARAM_CLOSURE_THIS' => 'param-closure-this',
			'T_TAG_PARAM_IMMEDIATELY_INVOKED_CALLABLE' => 'param-immediately-invoked-callable',
			'T_TAG_PARAM_LATER_INVOKED_CALLABLE' => 'param-later-invoked-callable',
			'T_TAG_PURE_UNLESS_CALLABLE_IS_IMPURE' => 'pure-unless-callable-is-impure',
			'T_TAG_PURE_UNLESS_PARAMETER_PASSED' => 'pure-unless-parameter-passed',
			'T_TAG_DEPRECATED' => 'deprecated',
			'T_TAG_METHOD' => 'method',
			'T_TAG_TEMPLATE' => 'template',
			'T_TAG_EXTENDS' => 'extends',
			'T_TAG_IMPLEMENTS' => 'implements',
			'T_TAG_USE' => 'use',
			'T_TAG_TYPE_ALIAS' => 'type-alias',
			'T_TAG_TYPE_ALIAS_IMPORT' => 'type-alias-import',
			'T_TAG_ASSERT' => 'assert',
		] as $name => $kind) {
			$this->tagKinds[$ids[$name]] = $kind;
		}
	}

	// -------------------------------------------------------------------------
	//  Reading
	// -------------------------------------------------------------------------

	/**
	 * Reads what the given rule describes from the position the iterator is at,
	 * and moves the iterator past it.
	 *
	 * The reading may stop before the end of the tokens: whatever follows is
	 * left to the caller, the way it always has been.
	 *
	 * @param string|null $tag the tag the value read belongs to, which the
	 *        tokens do not begin with
	 * @return mixed
	 * @throws ParserException
	 */
	public function read(TokenIterator $tokens, string $rule, ?string $tag = null)
	{
		$lexerTokens = $tokens->getTokens();
		$index = $tokens->currentTokenIndex();

		$stream = TokenStream::fromTokens($lexerTokens, $index);
		if ($stream !== null && $tag === null) {
			[$ids, $indexes] = $stream;
		} else {
			$ids = [];
			$indexes = [];
			if ($tag !== null) {
				// the value of a tag is read along with the tag it belongs to
				$ids[] = TokenStream::tagId($tag);
				$indexes[] = $index - 1;
			}

			if ($stream !== null) {
				foreach ($stream[0] as $position => $id) {
					$ids[] = $id;
					$indexes[] = $stream[1][$position];
				}
			} else {
				TokenStream::create($lexerTokens, $index, $ids, $indexes);
			}
		}

		// A reading is never started by another one, so the state of the last
		// one is simply overwritten
		$this->tokens = $lexerTokens;
		$this->ids = $ids;
		$this->indexes = $indexes;
		$this->tag = $tag;
		$this->positions = null;
		$this->comments = [];
		$this->byteOffsets = null;
		$this->v = [];
		$this->n = 0;

		if ($this->config->useCommentsAttributes) {
			foreach ($ids as $position => $id) {
				if ($id !== $this->tComment) {
					continue;
				}

				$commentIndex = $indexes[$position];
				$this->comments[$commentIndex] = new Comment($lexerTokens[$commentIndex][Lexer::VALUE_OFFSET], $lexerTokens[$commentIndex][Lexer::LINE_OFFSET], $commentIndex);
			}
		}

		// What has been read is given back even when the reading stops
		// before the end, unless nothing at all has been built
		if (!$this->parseRule(self::RULES[$rule], 0, true) || ($this->n === 0 && $this->ids[$this->p] !== -1)) {
			throw $this->createException($this->failure());
		}

		$tokens->setCurrentTokenIndex($indexes[$this->p]);

		return $this->n > 0 ? $this->v[0] : [];
	}

	protected function token(int $position): Token
	{
		$index = $this->indexes[$position];

		return new Token(
			$this->ids[$position],
			$position === 0 && $this->tag !== null ? $this->tag : $this->tokens[$index][Lexer::VALUE_OFFSET],
			$index,
		);
	}

	/**
	 * Describes why the given rule cannot be read at the given token, which is
	 * what a node standing for something that cannot be read carries.
	 */
	private function explain(string $rule, int $index): ParserException
	{
		$state = $this->saveReading();
		try {
			if (!$this->parseRule(self::RULES[$rule], $this->position($index), false) || $this->ids[$this->p] !== -1) {
				return $this->createException($this->failure());
			}

			// Everything that follows has been read as well, so there is nothing
			// to say but that it has to end somewhere
			return $this->createExceptionAt($this->indexes[count($this->indexes) - 1], Lexer::TOKEN_END);
		} finally {
			$this->restoreReading($state);
		}
	}

	private function position(int $index): int
	{
		if ($this->positions === null) {
			$this->positions = array_flip($this->indexes);
		}

		return $this->positions[$index];
	}

	/**
	 * @param array{int, list<int>} $failure the position and the tokens expected there
	 */
	private function createException(array $failure): ParserException
	{
		[$position, $expectedIds] = $failure;

		// The tokens that may only be written here, rather than the ones that
		// may be written anywhere, say what has been expected; where nothing
		// but those is left, a type is what has been expected
		$names = [];
		foreach ($expectedIds as $id) {
			if (!isset(self::EXPECTATIONS[$id])) {
				continue;
			}

			$names[] = self::EXPECTATIONS[$id];
		}

		$expected = Lexer::TOKEN_IDENTIFIER;
		$expectedValue = null;
		foreach (in_array('T_IDENTIFIER', $names, true) ? [] : $names as $name) {
			$type = self::lexerTokenType($name);
			if ($type === null || isset(self::INCIDENTAL_TOKENS[$type])) {
				continue;
			}

			$expected = $type;
			if (strncmp($name, 'T_KEYWORD_', 10) === 0) {
				// a word compared by its value, like the "from" of an imported type
				$expectedValue = strtolower(str_replace(['_ANY_CASE', '_'], ['', '-'], (string) substr($name, 10)));
			}
			break;
		}

		return $this->createExceptionAt($this->indexes[$position], $expected, $expectedValue);
	}

	private function createExceptionAt(int $index, int $expected, ?string $expectedValue = null): ParserException
	{
		$lexerToken = $this->tokens[$index] ?? $this->tokens[count($this->tokens) - 1];

		if ($this->byteOffsets === null) {
			$offset = 0;
			$this->byteOffsets = [];
			foreach ($this->tokens as $i => $t) {
				$this->byteOffsets[$i] = $offset;
				$offset += strlen($t[Lexer::VALUE_OFFSET]);
			}
		}

		return new ParserException(
			$lexerToken[Lexer::VALUE_OFFSET],
			$lexerToken[Lexer::TYPE_OFFSET],
			$this->byteOffsets[$index] ?? 0,
			$expected,
			$expectedValue,
			$lexerToken[Lexer::LINE_OFFSET],
		);
	}

	/**
	 * The token of the lexer a token of the grammar is read out of.
	 */
	private static function lexerTokenType(string $name): ?int
	{
		if (strncmp($name, 'T_KEYWORD_', 10) === 0) {
			return Lexer::TOKEN_IDENTIFIER;
		}

		if (strncmp($name, 'T_TAG_', 6) === 0) {
			return Lexer::TOKEN_PHPDOC_TAG;
		}

		$name = str_replace(['_WS', '_HTML'], '', substr($name, 2));
		$constant = Lexer::class . '::TOKEN_' . $name;

		return defined($constant) ? (int) constant($constant) : null;
	}

	// -------------------------------------------------------------------------
	//  Attributes
	// -------------------------------------------------------------------------

	/**
	 * @template T of Node
	 * @param T $node
	 * @return T
	 */
	private function attributesAt(Node $node, int $start, int $end, bool $takesComments = false, bool $skipsLineBreaks = false): Node
	{
		if (!$this->useAttributes) {
			return $node;
		}

		if ($this->config->useLinesAttributes) {
			$tokens = $this->tokens;
			$last = $tokens[count($tokens) - 1][Lexer::LINE_OFFSET];
			$next = $end + 1;
			if ($skipsLineBreaks) {
				// the line the reading has stopped at, which is the line of the
				// next token that is neither whitespace nor a line break
				while (isset($tokens[$next]) && ($tokens[$next][Lexer::TYPE_OFFSET] === Lexer::TOKEN_PHPDOC_EOL || $tokens[$next][Lexer::TYPE_OFFSET] === Lexer::TOKEN_HORIZONTAL_WS)) {
					$next++;
				}
			}

			$node->setAttribute(Attribute::START_LINE, $tokens[$start][Lexer::LINE_OFFSET] ?? $last);
			$node->setAttribute(Attribute::END_LINE, $tokens[$next][Lexer::LINE_OFFSET] ?? $last);
		}

		if ($this->config->useIndexAttributes) {
			$node->setAttribute(Attribute::START_INDEX, $start);
			$node->setAttribute(Attribute::END_INDEX, $end);
		}

		if ($takesComments && $this->config->useCommentsAttributes) {
			// A node is given the comments read since the last node was, and
			// only those: a node built again by the rule around it is given
			// what that rule has read, the way it always has been
			$comments = [];
			foreach ($this->comments as $index => $comment) {
				if ($index > $end) {
					break;
				}

				$comments[] = $comment;
				unset($this->comments[$index]);
			}

			$node->setAttribute(Attribute::COMMENTS, $comments);
		}

		return $node;
	}

	/**
	 * Forgets the comments read as text rather than as a part of a type.
	 */
	private function forgetComments(int $start, int $end): void
	{
		foreach (array_keys($this->comments) as $index) {
			if ($index > $end) {
				break;
			}

			if ($index < $start) {
				continue;
			}

			unset($this->comments[$index]);
		}
	}

	private function nextIndex(int $index): int
	{
		$tokens = $this->tokens;
		$index++;
		while (($tokens[$index][Lexer::TYPE_OFFSET] ?? null) === Lexer::TOKEN_HORIZONTAL_WS) {
			$index++;
		}

		return $index;
	}

	// -------------------------------------------------------------------------
	//  Types
	// -------------------------------------------------------------------------

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceType(int $begin, int $end, $children): TypeNode
	{
		$types = [];
		$kind = 0;
		foreach ($children as $child) {
			if ($child instanceof TypeNode) {
				$types[] = $child;
			} elseif ($child instanceof Token) {
				if ($child->id === $this->tUnion) {
					$kind = 1;
				} elseif ($child->id === $this->tIntersection) {
					$kind = 2;
				}
			}
		}

		if ($kind === 1) {
			$type = new Ast\Type\UnionTypeNode($types);
		} elseif ($kind === 2) {
			$type = new Ast\Type\IntersectionTypeNode($types);
		} else {
			$type = $types[0];
		}

		return $this->useAttributes ? $this->attributesAt($type, $begin, $end, true) : $type;
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceSubType(int $begin, int $end, $children): TypeNode
	{
		$types = [];
		$kind = 0;
		$negated = false;
		$parameter = null;
		foreach ($children as $child) {
			if ($child instanceof TypeNode) {
				$types[] = $child;
			} elseif ($child instanceof Token) {
				$id = $child->id;
				if ($id === $this->tUnion) {
					$kind = 1;
				} elseif ($id === $this->tIntersection) {
					$kind = 2;
				} elseif ($id === $this->tIs) {
					$kind = 3;
				} elseif ($id === $this->tNot && $kind === 3) {
					$negated = true;
				} elseif ($id === $this->tVariable && $parameter === null && $kind === 0) {
					$parameter = $child->value;
				}
			}
		}

		if ($kind === 3) {
			if ($parameter !== null) {
				$type = new Ast\Type\ConditionalTypeForParameterNode($parameter, $types[0], $types[1], $types[2], $negated);
			} else {
				$type = new Ast\Type\ConditionalTypeNode($types[0], $types[1], $types[2], $types[3], $negated);
			}
		} elseif ($kind === 1) {
			$type = new Ast\Type\UnionTypeNode($types);
		} elseif ($kind === 2) {
			$type = new Ast\Type\IntersectionTypeNode($types);
		} else {
			$type = $types[0];
		}

		return $this->useAttributes ? $this->attributesAt($type, $begin, $end, true) : $type;
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceNullable(int $begin, int $end, $children): TypeNode
	{
		foreach ($children as $child) {
			if ($child instanceof TypeNode) {
				return $this->useAttributes ? $this->attributesAt(new Ast\Type\NullableTypeNode($child), $begin, $end, true) : new Ast\Type\NullableTypeNode($child);
			}
		}

		throw new LogicException('A nullable type without a type');
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceAtomic(int $begin, int $end, $children): TypeNode
	{
		$type = $this->assembleAtomic($children, $begin);

		return $this->useAttributes ? $this->attributesAt($type, $begin, $end, true) : $type;
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceCallableReturnType(int $begin, int $end, $children): TypeNode
	{
		$type = $this->assembleAtomic($children, $begin);

		return $this->useAttributes ? $this->attributesAt($type, $begin, $end, true) : $type;
	}

	/**
	 * An atomic type, out of the name or the type it begins with and whatever
	 * is written right after it.
	 *
	 * @param list<mixed> $children
	 */
	private function assembleAtomic(array $children, int $begin): TypeNode
	{
		$type = null;
		$name = null;

		foreach ($children as $child) {
			if ($child instanceof Token) {
				if ($type === null && $name === null) {
					if (isset($this->identifiers[$child->id])) {
						$name = $child;
					} elseif ($child->id === $this->tThis) {
						$type = ($this->useAttributes ? $this->attributesAt(new Ast\Type\ThisTypeNode(), $child->offset, $child->offset, true) : new Ast\Type\ThisTypeNode());
					}
				}

				continue;
			}

			if ($child instanceof TypeNode) {
				$type ??= $child;
				continue;
			}

			if ($child instanceof ConstExprNode) {
				$type = ($this->useAttributes ? $this->attributesAt(
					new Ast\Type\ConstTypeNode($child),
					$child->getAttribute(Attribute::START_INDEX) ?? $begin,
					$child->getAttribute(Attribute::END_INDEX) ?? $begin,
					true,
				) : new Ast\Type\ConstTypeNode($child));
				continue;
			}

			if (!$child instanceof Fragment) {
				continue;
			}

			switch ($child->kind) {
				case Fragment::GENERIC:
					$type = new Ast\Type\GenericTypeNode($this->identifier($name), $child->a, $child->b);
					break;
				case Fragment::CALLABLE:
					$type = new Ast\Type\CallableTypeNode($this->identifier($name), $child->a, $child->b, $child->c);
					break;
				case Fragment::SHAPE:
					/** @var Token $name */
					/** @var Ast\Type\ArrayShapeNode::KIND_* $kind */
					$kind = $name->value;
					$type = $child->b === true
						? Ast\Type\ArrayShapeNode::createSealed($child->a, $kind)
						: Ast\Type\ArrayShapeNode::createUnsealed($child->a, $child->c, $kind);
					break;
				case Fragment::OBJECT_SHAPE:
					$type = new Ast\Type\ObjectShapeNode($child->a);
					break;
				case Fragment::OFFSET:
					$type = new Ast\Type\OffsetAccessTypeNode($type ?? $this->identifier($name), $child->a);
					break;
				default:
					$type = new Ast\Type\ArrayTypeNode($type ?? $this->identifier($name));
					break;
			}

			$type = ($this->useAttributes ? $this->attributesAt($type, $begin, $child->end, true) : $type);
		}

		return $type ?? $this->identifier($name);
	}

	private function identifier(?Token $name): Ast\Type\IdentifierTypeNode
	{
		if ($name === null) {
			throw new LogicException('A type without a name');
		}

		return $this->useAttributes ? $this->attributesAt(new Ast\Type\IdentifierTypeNode($name->value), $name->offset, $name->offset, true) : new Ast\Type\IdentifierTypeNode($name->value);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceArrayOrOffsetAccessItem(int $begin, int $end, $children): Fragment
	{
		foreach ($children as $child) {
			if ($child instanceof TypeNode) {
				return new Fragment(Fragment::OFFSET, $end, $child);
			}
		}

		return new Fragment(Fragment::ARRAY, $end);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceGeneric(int $begin, int $end, $children): Fragment
	{
		$types = [];
		$variances = [];
		foreach ($children as $child) {
			if (!$child instanceof Fragment) {
				continue;
			}

			$types[] = $child->a;
			$variances[] = $child->b;
		}

		return new Fragment(Fragment::GENERIC, $end, $types, $variances);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceGenericInReturnType(int $begin, int $end, $children): Fragment
	{
		return $this->reduceGeneric($begin, $end, $children);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceGenericArgument(int $begin, int $end, $children): Fragment
	{
		$variance = Ast\Type\GenericTypeNode::VARIANCE_INVARIANT;
		foreach ($children as $child) {
			if ($child instanceof TypeNode) {
				return new Fragment(Fragment::GENERIC_ARGUMENT, $end, $child, $variance);
			}

			if (!$child instanceof Token) {
				continue;
			}

			if ($child->id === $this->tCovariant) {
				$variance = Ast\Type\GenericTypeNode::VARIANCE_COVARIANT;
			} elseif ($child->id === $this->tContravariant) {
				$variance = Ast\Type\GenericTypeNode::VARIANCE_CONTRAVARIANT;
			} else {
				// the wildcard
				return new Fragment(
					Fragment::GENERIC_ARGUMENT,
					$end,
					($this->useAttributes ? $this->attributesAt(new Ast\Type\IdentifierTypeNode('mixed'), $begin, $end, true) : new Ast\Type\IdentifierTypeNode('mixed')),
					Ast\Type\GenericTypeNode::VARIANCE_BIVARIANT,
				);
			}
		}

		throw new LogicException('A generic argument without a type');
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceCallable(int $begin, int $end, $children): Fragment
	{
		$templates = [];
		$parameters = [];
		$returnType = null;
		foreach ($children as $child) {
			if ($child instanceof Ast\Type\CallableTypeParameterNode) {
				$parameters[] = $child;
			} elseif ($child instanceof Ast\PhpDoc\TemplateTagValueNode) {
				$templates[] = $child;
			} elseif ($child instanceof TypeNode) {
				$returnType = $child;
			}
		}

		return new Fragment(Fragment::CALLABLE, $end, $parameters, $returnType, $templates);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceCallableWithTemplates(int $begin, int $end, $children): Fragment
	{
		return $this->reduceCallable($begin, $end, $children);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceCallableTemplateArgument(int $begin, int $end, $children): Ast\PhpDoc\TemplateTagValueNode
	{
		return $this->attributesAt($this->template($children, ''), $begin, $end, true);
	}

	/**
	 * @param list<mixed> $children
	 */
	private function template(array $children, string $description): Ast\PhpDoc\TemplateTagValueNode
	{
		$name = null;
		$upperBound = null;
		$lowerBound = null;
		$default = null;
		$next = null;
		foreach ($children as $child) {
			if ($child instanceof Token) {
				if ($name === null) {
					$name = $child->value;
				} elseif ($child->id === $this->tOf || $child->id === $this->tAs) {
					$next = 'upper';
				} elseif ($child->id === $this->tSuper) {
					$next = 'lower';
				} elseif ($child->id === $this->tEqual) {
					$next = 'default';
				}
			} elseif ($child instanceof TypeNode) {
				if ($next === 'upper') {
					$upperBound = $child;
				} elseif ($next === 'lower') {
					$lowerBound = $child;
				} elseif ($next === 'default') {
					$default = $child;
				}
			}
		}

		/** @var non-empty-string $name */
		return new Ast\PhpDoc\TemplateTagValueNode($name, $upperBound, $description, $default, $lowerBound);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceCallableParameter(int $begin, int $end, $children): Ast\Type\CallableTypeParameterNode
	{
		$type = null;
		$isReference = false;
		$isVariadic = false;
		$name = '';
		$isOptional = false;
		foreach ($children as $child) {
			if ($child instanceof TypeNode) {
				$type = $child;
			} elseif ($child instanceof Token) {
				$id = $child->id;
				if ($id === $this->tReference) {
					$isReference = true;
				} elseif ($id === $this->tVariadic) {
					$isVariadic = true;
				} elseif ($id === $this->tVariable) {
					$name = $child->value;
				} elseif ($id === $this->tEqual) {
					$isOptional = true;
				}
			}
		}

		/** @var TypeNode $type */
		return $this->useAttributes ? $this->attributesAt(new Ast\Type\CallableTypeParameterNode($type, $isReference, $isVariadic, $name, $isOptional), $begin, $end, true) : new Ast\Type\CallableTypeParameterNode($type, $isReference, $isVariadic, $name, $isOptional);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceArrayShape(int $begin, int $end, $children): Fragment
	{
		$items = [];
		$sealed = true;
		$unsealedType = null;
		foreach ($children as $child) {
			if ($child instanceof Ast\Type\ArrayShapeItemNode) {
				$items[] = $child;
			} elseif ($child instanceof Ast\Type\ArrayShapeUnsealedTypeNode) {
				$unsealedType = $child;
			} elseif ($child instanceof Token && $child->id === $this->tVariadic) {
				$sealed = false;
			}
		}

		return new Fragment(Fragment::SHAPE, $end, $items, $sealed, $unsealedType);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceListShape(int $begin, int $end, $children): Fragment
	{
		return $this->reduceArrayShape($begin, $end, $children);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceArrayShapeUnsealedType(int $begin, int $end, $children): Ast\Type\ArrayShapeUnsealedTypeNode
	{
		$types = [];
		foreach ($children as $child) {
			if (!$child instanceof TypeNode) {
				continue;
			}

			$types[] = $child;
		}

		$node = count($types) === 2
			? new Ast\Type\ArrayShapeUnsealedTypeNode($types[1], $types[0])
			: new Ast\Type\ArrayShapeUnsealedTypeNode($types[0], null);

		return $this->useAttributes ? $this->attributesAt($node, $begin, $end, true) : $node;
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceListShapeUnsealedType(int $begin, int $end, $children): Ast\Type\ArrayShapeUnsealedTypeNode
	{
		return $this->reduceArrayShapeUnsealedType($begin, $end, $children);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceArrayShapeItem(int $begin, int $end, $children): Ast\Type\ArrayShapeItemNode
	{
		$nodes = [];
		$hasKey = false;
		$optional = false;
		foreach ($children as $child) {
			if ($child instanceof Node) {
				$nodes[] = $child;
			} elseif ($child instanceof Token) {
				if ($child->id === $this->tColon) {
					$hasKey = true;
				} elseif ($child->id === $this->tNullable) {
					$optional = true;
				}
			}
		}

		/** @var TypeNode $value */
		$value = $nodes[count($nodes) - 1];
		if ($hasKey) {
			/** @var Ast\ConstExpr\ConstExprIntegerNode|Ast\ConstExpr\ConstExprStringNode|Ast\ConstExpr\ConstFetchNode|Ast\Type\IdentifierTypeNode $key */
			$key = $nodes[0];
			$item = new Ast\Type\ArrayShapeItemNode($key, $optional, $value);
		} else {
			$item = new Ast\Type\ArrayShapeItemNode(null, false, $value);
		}

		return $this->useAttributes ? $this->attributesAt($item, $begin, $end, true) : $item;
	}

	/**
	 * @param list<mixed> $children
	 * @return Ast\ConstExpr\ConstExprIntegerNode|Ast\ConstExpr\ConstExprStringNode|Ast\ConstExpr\ConstFetchNode|Ast\Type\IdentifierTypeNode
	 */
	protected function reduceArrayShapeKey(int $begin, int $end, $children): Node
	{
		$first = $children[0];
		if (count($children) === 3) {
			$key = new Ast\ConstExpr\ConstFetchNode($first->value, $children[2]->value);
		} elseif ($first->id === $this->tInteger) {
			$key = new Ast\ConstExpr\ConstExprIntegerNode(str_replace('_', '', $first->value));
		} else {
			$key = $this->stringOrIdentifier($first);
		}

		return $this->useAttributes ? $this->attributesAt($key, $begin, $end, true) : $key;
	}

	/**
	 * @return Ast\ConstExpr\ConstExprStringNode|Ast\Type\IdentifierTypeNode
	 */
	private function stringOrIdentifier(Token $token): Node
	{
		if ($token->id === $this->tSingleQuoted) {
			return new Ast\ConstExpr\ConstExprStringNode(StringUnescaper::unescapeString($token->value), Ast\ConstExpr\ConstExprStringNode::SINGLE_QUOTED);
		}

		if ($token->id === $this->tDoubleQuoted) {
			return new Ast\ConstExpr\ConstExprStringNode(StringUnescaper::unescapeString($token->value), Ast\ConstExpr\ConstExprStringNode::DOUBLE_QUOTED);
		}

		return new Ast\Type\IdentifierTypeNode($token->value);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceObjectShape(int $begin, int $end, $children): Fragment
	{
		$items = [];
		foreach ($children as $child) {
			if (!$child instanceof Ast\Type\ObjectShapeItemNode) {
				continue;
			}

			$items[] = $child;
		}

		return new Fragment(Fragment::OBJECT_SHAPE, $end, $items);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceObjectShapeItem(int $begin, int $end, $children): Ast\Type\ObjectShapeItemNode
	{
		$key = null;
		$value = null;
		$optional = false;
		foreach ($children as $child) {
			if ($child instanceof Node) {
				if ($key === null) {
					$key = $child;
				} else {
					$value = $child;
				}
			} elseif ($child instanceof Token && $child->id === $this->tNullable) {
				$optional = true;
			}
		}

		/** @var Ast\ConstExpr\ConstExprStringNode|Ast\Type\IdentifierTypeNode $key */
		/** @var TypeNode $value */
		return $this->useAttributes ? $this->attributesAt(new Ast\Type\ObjectShapeItemNode($key, $optional, $value), $begin, $end, true) : new Ast\Type\ObjectShapeItemNode($key, $optional, $value);
	}

	/**
	 * @param list<mixed> $children
	 * @return Ast\ConstExpr\ConstExprStringNode|Ast\Type\IdentifierTypeNode
	 */
	protected function reduceObjectShapeKey(int $begin, int $end, $children): Node
	{
		return $this->attributesAt($this->stringOrIdentifier($children[0]), $begin, $end, true);
	}

	/**
	 * A comment read right after the comma separating the items of a shape is
	 * thrown away.
	 *
	 * @return list<mixed>
	 */
	protected function reduceDiscardedComment(int $begin, int $end): array
	{
		unset($this->comments[$begin]);

		return [];
	}

	// -------------------------------------------------------------------------
	//  Constant expressions
	// -------------------------------------------------------------------------

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceConstantFloat(int $begin, int $end, $children): ConstExprNode
	{
		return $this->useAttributes ? $this->attributesAt(new Ast\ConstExpr\ConstExprFloatNode(str_replace('_', '', $children[0]->value)), $begin, $end) : new Ast\ConstExpr\ConstExprFloatNode(str_replace('_', '', $children[0]->value));
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceConstantInteger(int $begin, int $end, $children): ConstExprNode
	{
		return $this->useAttributes ? $this->attributesAt(new Ast\ConstExpr\ConstExprIntegerNode(str_replace('_', '', $children[0]->value)), $begin, $end) : new Ast\ConstExpr\ConstExprIntegerNode(str_replace('_', '', $children[0]->value));
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceConstantString(int $begin, int $end, $children): ConstExprNode
	{
		/** @var Ast\ConstExpr\ConstExprStringNode $node */
		$node = $this->stringOrIdentifier($children[0]);

		return $this->useAttributes ? $this->attributesAt($node, $begin, $end) : $node;
	}

	protected function reduceConstantTrue(int $begin, int $end): ConstExprNode
	{
		return $this->useAttributes ? $this->attributesAt(new Ast\ConstExpr\ConstExprTrueNode(), $begin, $end) : new Ast\ConstExpr\ConstExprTrueNode();
	}

	protected function reduceConstantFalse(int $begin, int $end): ConstExprNode
	{
		return $this->useAttributes ? $this->attributesAt(new Ast\ConstExpr\ConstExprFalseNode(), $begin, $end) : new Ast\ConstExpr\ConstExprFalseNode();
	}

	protected function reduceConstantNull(int $begin, int $end): ConstExprNode
	{
		return $this->useAttributes ? $this->attributesAt(new Ast\ConstExpr\ConstExprNullNode(), $begin, $end) : new Ast\ConstExpr\ConstExprNullNode();
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceConstantArrayLiteral(int $begin, int $end, $children): ConstExprNode
	{
		$items = [];
		foreach ($children as $child) {
			if (!$child instanceof Ast\ConstExpr\ConstExprArrayItemNode) {
				continue;
			}

			$items[] = $child;
		}

		return $this->useAttributes ? $this->attributesAt(new Ast\ConstExpr\ConstExprArrayNode($items), $begin, $end) : new Ast\ConstExpr\ConstExprArrayNode($items);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceConstantArrayShorthand(int $begin, int $end, $children): ConstExprNode
	{
		return $this->reduceConstantArrayLiteral($begin, $end, $children);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceConstantArrayItem(int $begin, int $end, $children): ConstExprNode
	{
		$expressions = [];
		foreach ($children as $child) {
			if (!$child instanceof ConstExprNode) {
				continue;
			}

			$expressions[] = $child;
		}

		$item = count($expressions) === 2
			? new Ast\ConstExpr\ConstExprArrayItemNode($expressions[0], $expressions[1])
			: new Ast\ConstExpr\ConstExprArrayItemNode(null, $expressions[0]);

		return $this->useAttributes ? $this->attributesAt($item, $begin, $end) : $item;
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceConstantFetch(int $begin, int $end, $children): ConstExprNode
	{
		return $this->attributesAt($this->constantFetch($children), $begin, $end);
	}

	/**
	 * @param list<mixed> $children
	 */
	private function constantFetch(array $children): Ast\ConstExpr\ConstFetchNode
	{
		$first = $children[0];
		if (count($children) === 1) {
			return new Ast\ConstExpr\ConstFetchNode('', $first->value);
		}

		$name = '';
		for ($i = 2, $count = count($children); $i < $count; $i++) {
			$name .= $children[$i]->value;
		}

		return new Ast\ConstExpr\ConstFetchNode($first->value, $name);
	}

	// -------------------------------------------------------------------------
	//  PHPDocs
	// -------------------------------------------------------------------------

	/**
	 * @param list<mixed> $children
	 */
	protected function reducePhpDoc(int $begin, int $end, $children): Ast\PhpDoc\PhpDocNode
	{
		$nodes = [];
		foreach ($children as $child) {
			if (!$child instanceof Ast\PhpDoc\PhpDocChildNode) {
				continue;
			}

			$nodes[] = $child;
		}

		return $this->useAttributes ? $this->attributesAt(new Ast\PhpDoc\PhpDocNode($nodes), $begin, $end) : new Ast\PhpDoc\PhpDocNode($nodes);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceTextChild(int $begin, int $end, $children): Ast\PhpDoc\PhpDocTextNode
	{
		$text = '';
		foreach ($children as $child) {
			if (!is_string($child)) {
				continue;
			}

			$text = $child;
		}

		return $this->useAttributes ? $this->attributesAt(new Ast\PhpDoc\PhpDocTextNode($text), $begin, $end) : new Ast\PhpDoc\PhpDocTextNode($text);
	}

	protected function reduceText(int $begin, int $end): string
	{
		return $this->text($begin, $end);
	}

	protected function reduceDoctrineText(int $begin, int $end): string
	{
		return $this->text($begin, $end);
	}

	/**
	 * The text the rule has been read from, the way it is written save for the
	 * asterisks a PHPDoc starts its lines with.
	 */
	private function text(int $begin, int $end): string
	{
		if ($end < $begin) {
			$this->textStart = -1;
			return '';
		}

		$start = $begin;
		$this->textStart = $start;
		if ($this->comments !== []) {
			$this->forgetComments($start, $end);
		}

		return $this->textBetween($start, $end);
	}

	private function textBetween(int $start, int $end): string
	{
		$tokens = $this->tokens;
		$end++;
		$text = '';
		for ($i = $start; $i < $end; $i++) {
			[$value, $type] = $tokens[$i];
			if ($type === Lexer::TOKEN_PHPDOC_EOL) {
				$text .= $value[0] === "\r" ? "\r\n" : "\n";
				continue;
			}

			$text .= $value;
		}

		return trim($text, " \t");
	}

	/**
	 * @param list<mixed> $children
	 */
	private static function description(array $children): string
	{
		$last = $children === [] ? null : $children[array_key_last($children)];

		return is_string($last) ? $last : '';
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceKnownTag(int $begin, int $end, $children): Ast\PhpDoc\PhpDocTagNode
	{
		if ($children[0] instanceof Ast\PhpDoc\PhpDocTagNode) {
			// a type alias whose type cannot be read
			return $children[0];
		}

		/** @var Token $tag */
		$tag = $children[0];
		$valueStart = $this->nextIndex($tag->offset);
		$value = $this->tagValue($tag, $children, $valueStart, $end);

		// A description written right after a type has to be written after a
		// space, which is not something the grammar says
		if (
			isset(self::DESCRIPTION_AFTER_TYPE[$this->tagKinds[$tag->id]])
			&& $this->textStart > 0
			&& self::description($children) !== ''
			&& !($value instanceof Ast\PhpDoc\VarTagValueNode && $value->variableName !== '')
		) {
			$tokens = $this->tokens;
			$textStart = $this->textStart;
			if (
				$tokens[$textStart][Lexer::TYPE_OFFSET] !== Lexer::TOKEN_PHPDOC_EOL
				&& $tokens[$textStart - 1][Lexer::TYPE_OFFSET] !== Lexer::TOKEN_HORIZONTAL_WS
			) {
				$value = new Ast\PhpDoc\InvalidTagValueNode(
					trim($this->textBetween($valueStart, $end), " \t"),
					$this->createExceptionAt($textStart, Lexer::TOKEN_HORIZONTAL_WS),
				);
			}
		}

		$value = ($this->useAttributes ? $this->attributesAt($value, $valueStart, $end) : $value);

		return $this->useAttributes ? $this->attributesAt(new Ast\PhpDoc\PhpDocTagNode($tag->value, $value), $begin, $end) : new Ast\PhpDoc\PhpDocTagNode($tag->value, $value);
	}

	/**
	 * @param list<mixed> $children
	 */
	private function tagValue(Token $tag, array $children, int $valueStart, int $end): Ast\PhpDoc\PhpDocTagValueNode
	{
		$description = self::description($children);
		$type = null;
		$variable = null;
		$isReference = false;
		$isVariadic = false;
		foreach ($children as $i => $child) {
			if ($i === 0) {
				continue;
			}

			if ($child instanceof TypeNode) {
				$type ??= $child;
			} elseif ($child instanceof Token) {
				$id = $child->id;
				if ($id === $this->tVariable || $id === $this->tThis) {
					$variable ??= $child->value;
				} elseif ($id === $this->tReference) {
					$isReference = true;
				} elseif ($id === $this->tVariadic) {
					$isVariadic = true;
				}
			}
		}

		switch ($this->tagKinds[$tag->id]) {
			case 'param':
				/** @var string $variable */
				if ($type === null) {
					return new Ast\PhpDoc\TypelessParamTagValueNode($isVariadic, $variable, $description, $isReference);
				}

				return new Ast\PhpDoc\ParamTagValueNode($type, $isVariadic, $variable, $description, $isReference);
			case 'var':
				/** @var TypeNode $type */
				return new Ast\PhpDoc\VarTagValueNode($type, $variable ?? '', $description);
			case 'return':
				/** @var TypeNode $type */
				return new Ast\PhpDoc\ReturnTagValueNode($type, $description);
			case 'throws':
				/** @var TypeNode $type */
				return new Ast\PhpDoc\ThrowsTagValueNode($type, $description);
			case 'mixin':
				/** @var TypeNode $type */
				return new Ast\PhpDoc\MixinTagValueNode($type, $description);
			case 'require-extends':
				/** @var TypeNode $type */
				return new Ast\PhpDoc\RequireExtendsTagValueNode($type, $description);
			case 'require-implements':
				/** @var TypeNode $type */
				return new Ast\PhpDoc\RequireImplementsTagValueNode($type, $description);
			case 'sealed':
				/** @var TypeNode $type */
				return new Ast\PhpDoc\SealedTagValueNode($type, $description);
			case 'self-out':
				/** @var TypeNode $type */
				return new Ast\PhpDoc\SelfOutTagValueNode($type, $description);
			case 'property':
				/** @var TypeNode $type */
				/** @var string $variable */
				return new Ast\PhpDoc\PropertyTagValueNode($type, $variable, $description);
			case 'param-out':
				/** @var TypeNode $type */
				/** @var string $variable */
				return new Ast\PhpDoc\ParamOutTagValueNode($type, $variable, $description);
			case 'param-closure-this':
				/** @var TypeNode $type */
				/** @var string $variable */
				return new Ast\PhpDoc\ParamClosureThisTagValueNode($type, $variable, $description);
			case 'param-immediately-invoked-callable':
				/** @var string $variable */
				return new Ast\PhpDoc\ParamImmediatelyInvokedCallableTagValueNode($variable, $description);
			case 'param-later-invoked-callable':
				/** @var string $variable */
				return new Ast\PhpDoc\ParamLaterInvokedCallableTagValueNode($variable, $description);
			case 'pure-unless-callable-is-impure':
				/** @var string $variable */
				return new Ast\PhpDoc\PureUnlessCallableIsImpureTagValueNode($variable, $description);
			case 'pure-unless-parameter-passed':
				/** @var string $variable */
				return new Ast\PhpDoc\PureUnlessParameterIsPassedTagValueNode($variable, $description);
			case 'deprecated':
				return new Ast\PhpDoc\DeprecatedTagValueNode($description);
			case 'method':
				return $this->methodTagValue($children, $description, $valueStart, $end);
			case 'template':
				array_shift($children);
				return $this->template($children, $description);
			case 'extends':
			case 'implements':
			case 'use':
				return $this->extendsTagValue($this->tagKinds[$tag->id], $children, $description, $valueStart);
			case 'type-alias':
				/** @var Token $alias */
				$alias = $children[1];
				/** @var TypeNode $type */
				return new Ast\PhpDoc\TypeAliasTagValueNode($alias->value, $type);
			case 'type-alias-import':
				/** @var Token $alias */
				$alias = $children[1];
				/** @var Token $from */
				$from = $children[3];
				$as = isset($children[5]) ? $children[5]->value : null;

				return new Ast\PhpDoc\TypeAliasImportTagValueNode(
					$alias->value,
					($this->useAttributes ? $this->attributesAt(new Ast\Type\IdentifierTypeNode($from->value), $from->offset, $from->offset) : new Ast\Type\IdentifierTypeNode($from->value)),
					$as,
				);
			case 'assert':
				return $this->assertTagValue($children, $description);
		}

		throw new LogicException('Unknown tag ' . $tag->value);
	}

	/**
	 * @param list<mixed> $children
	 */
	private function methodTagValue(array $children, string $description, int $valueStart, int $end): Ast\PhpDoc\PhpDocTagValueNode
	{
		$static = null;
		$type = null;
		$name = null;
		$templates = [];
		$parameters = [];
		foreach ($children as $i => $child) {
			if ($i === 0) {
				continue;
			}

			if ($child instanceof Token) {
				if ($i === 1 && $child->id === $this->tStatic) {
					$static = $child;
				} elseif ($type !== null && $name === null && $templates === [] && isset($this->identifiers[$child->id])) {
					$name = $child;
				}
			} elseif ($child instanceof Ast\PhpDoc\TemplateTagValueNode) {
				$templates[] = $child;
			} elseif ($child instanceof Ast\PhpDoc\MethodTagValueParameterNode) {
				$parameters[] = $child;
			} elseif ($child instanceof TypeNode) {
				$type ??= $child;
			}
		}

		/** @var TypeNode $type */
		if ($name !== null) {
			return new Ast\PhpDoc\MethodTagValueNode($static !== null, $type, $name->value, $parameters, $description, $templates);
		}

		if (!$type instanceof Ast\Type\IdentifierTypeNode) {
			// A method is named by a name, never by any other type
			$after = null;
			$afterType = false;
			foreach ($children as $child) {
				if ($child === $type) {
					$afterType = true;
				} elseif ($afterType && $child instanceof Token) {
					$after = $child;
					break;
				}
			}

			return new Ast\PhpDoc\InvalidTagValueNode(
				trim($this->textBetween($valueStart, $end), " \t"),
				$this->createExceptionAt($after !== null ? $after->offset : $this->indexes[count($this->indexes) - 1], Lexer::TOKEN_IDENTIFIER),
			);
		}

		$returnType = $static !== null
			? ($this->useAttributes ? $this->attributesAt(new Ast\Type\IdentifierTypeNode('static'), $static->offset, $static->offset, true) : new Ast\Type\IdentifierTypeNode('static'))
			: null;

		return new Ast\PhpDoc\MethodTagValueNode(false, $returnType, $type->name, $parameters, $description, $templates);
	}

	/**
	 * @param list<mixed> $children
	 */
	private function extendsTagValue(string $kind, array $children, string $description, int $start): Ast\PhpDoc\PhpDocTagValueNode
	{
		/** @var Token $name */
		$name = $children[1];
		/** @var Fragment $generic */
		$generic = $children[2];
		$type = ($this->useAttributes ? $this->attributesAt(
			new Ast\Type\GenericTypeNode($this->identifier($name), $generic->a, $generic->b),
			$start,
			$generic->end,
			true,
		) : new Ast\Type\GenericTypeNode($this->identifier($name), $generic->a, $generic->b));

		switch ($kind) {
			case 'extends':
				return new Ast\PhpDoc\ExtendsTagValueNode($type, $description);
			case 'implements':
				return new Ast\PhpDoc\ImplementsTagValueNode($type, $description);
			default:
				return new Ast\PhpDoc\UsesTagValueNode($type, $description);
		}
	}

	/**
	 * @param list<mixed> $children
	 */
	private function assertTagValue(array $children, string $description): Ast\PhpDoc\PhpDocTagValueNode
	{
		$isNegated = false;
		$isEquality = false;
		$type = null;
		$parameter = null;
		$member = null;
		$isMethod = false;
		foreach ($children as $i => $child) {
			if ($i === 0) {
				continue;
			}

			if ($child instanceof TypeNode) {
				$type ??= $child;
			} elseif ($child instanceof Token) {
				$id = $child->id;
				if ($type === null) {
					if ($id === $this->tNegated) {
						$isNegated = true;
					} elseif ($id === $this->tEqual) {
						$isEquality = true;
					}
				} elseif ($parameter === null) {
					$parameter = $child->value;
				} elseif ($id === $this->tOpenParentheses || $id === $this->tOpenParenthesesWs) {
					$isMethod = true;
				} elseif ($member === null && isset($this->identifiers[$id])) {
					$member = $child->value;
				}
			}
		}

		/** @var TypeNode $type */
		/** @var string $parameter */
		if ($member === null) {
			return new Ast\PhpDoc\AssertTagValueNode($type, $parameter, $isNegated, $description, $isEquality);
		}

		if ($isMethod) {
			return new Ast\PhpDoc\AssertTagMethodValueNode($type, $parameter, $member, $isNegated, $description, $isEquality);
		}

		return new Ast\PhpDoc\AssertTagPropertyValueNode($type, $parameter, $member, $isNegated, $description, $isEquality);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceMethodParameter(int $begin, int $end, $children): Ast\PhpDoc\MethodTagValueParameterNode
	{
		$type = null;
		$isReference = false;
		$isVariadic = false;
		$name = '';
		$default = null;
		foreach ($children as $child) {
			if ($child instanceof TypeNode) {
				$type = $child;
			} elseif ($child instanceof ConstExprNode) {
				$default = $child;
			} elseif ($child instanceof Token) {
				$id = $child->id;
				if ($id === $this->tReference) {
					$isReference = true;
				} elseif ($id === $this->tVariadic) {
					$isVariadic = true;
				} elseif ($id === $this->tVariable) {
					$name = $child->value;
				}
			}
		}

		return $this->useAttributes ? $this->attributesAt(new Ast\PhpDoc\MethodTagValueParameterNode($type, $isReference, $isVariadic, $name, $default), $begin, $end) : new Ast\PhpDoc\MethodTagValueParameterNode($type, $isReference, $isVariadic, $name, $default);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceGenericTag(int $begin, int $end, $children): Ast\PhpDoc\PhpDocTagNode
	{
		/** @var Token $tag */
		$tag = $children[0];
		$valueStart = $this->nextIndex($tag->offset);
		$value = ($this->useAttributes ? $this->attributesAt(new Ast\PhpDoc\GenericTagValueNode(self::description($children)), $valueStart, $end) : new Ast\PhpDoc\GenericTagValueNode(self::description($children)));

		return $this->useAttributes ? $this->attributesAt(new Ast\PhpDoc\PhpDocTagNode($tag->value, $value), $begin, $end) : new Ast\PhpDoc\PhpDocTagNode($tag->value, $value);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceDoctrineTag(int $begin, int $end, $children): Ast\PhpDoc\PhpDocTagNode
	{
		/** @var Token $tag */
		$tag = $children[0];
		$valueStart = $this->nextIndex($tag->offset);

		$arguments = [];
		$annotationEnd = $valueStart - 1;
		foreach ($children as $i => $child) {
			if ($child instanceof Doctrine\DoctrineArgument) {
				$arguments[] = $child;
			} elseif ($i > 0 && $child instanceof Token) {
				// the closing parenthesis is the last token of the arguments
				$annotationEnd = $child->offset;
			}
		}

		$annotation = ($this->useAttributes ? $this->attributesAt(new Doctrine\DoctrineAnnotation($tag->value, $arguments), $valueStart, $annotationEnd) : new Doctrine\DoctrineAnnotation($tag->value, $arguments));
		$value = ($this->useAttributes ? $this->attributesAt(
			new Doctrine\DoctrineTagValueNode($annotation, self::description($children)),
			$valueStart,
			$end,
		) : new Doctrine\DoctrineTagValueNode($annotation, self::description($children)));

		return $this->useAttributes ? $this->attributesAt(new Ast\PhpDoc\PhpDocTagNode($tag->value, $value), $begin, $end) : new Ast\PhpDoc\PhpDocTagNode($tag->value, $value);
	}

	/**
	 * A tag whose value cannot be read: the rest of its line is its value.
	 *
	 * @param list<mixed> $children
	 */
	protected function reduceInvalidTag(int $begin, int $end, $children): Ast\PhpDoc\PhpDocTagNode
	{
		/** @var Token $tag */
		$tag = $children[0];
		$valueStart = $this->nextIndex($tag->offset);

		$exception = $this->explain(isset($this->tagKinds[$tag->id]) ? 'KnownTag' : 'UnreadTagChild', $tag->offset);
		$value = ($this->useAttributes ? $this->attributesAt(
			new Ast\PhpDoc\InvalidTagValueNode(self::description($children), $exception),
			$valueStart,
			$end,
		) : new Ast\PhpDoc\InvalidTagValueNode(self::description($children), $exception));

		return $this->useAttributes ? $this->attributesAt(new Ast\PhpDoc\PhpDocTagNode($tag->value, $value), $begin, $end) : new Ast\PhpDoc\PhpDocTagNode($tag->value, $value);
	}

	/**
	 * A type alias whose type cannot be read.
	 *
	 * @param list<mixed> $children
	 */
	protected function reduceInvalidTypeAliasTag(int $begin, int $end, $children): Ast\PhpDoc\PhpDocTagNode
	{
		/** @var Token $tag */
		$tag = $children[0];
		/** @var Token $alias */
		$alias = $children[1];
		$typeStart = $this->nextIndex($alias->offset);
		if (isset($children[2]) && $children[2] instanceof Token && $children[2]->id === $this->tEqual) {
			$typeStart = $this->nextIndex($children[2]->offset);
		}

		$exception = $this->explainTypeAlias($typeStart);
		$type = ($this->useAttributes ? $this->attributesAt(new Ast\Type\InvalidTypeNode($exception), $typeStart, $end) : new Ast\Type\InvalidTypeNode($exception));

		$valueStart = $this->nextIndex($tag->offset);
		$value = ($this->useAttributes ? $this->attributesAt(new Ast\PhpDoc\TypeAliasTagValueNode($alias->value, $type), $valueStart, $end) : new Ast\PhpDoc\TypeAliasTagValueNode($alias->value, $type));

		return $this->useAttributes ? $this->attributesAt(new Ast\PhpDoc\PhpDocTagNode($tag->value, $value), $begin, $end) : new Ast\PhpDoc\PhpDocTagNode($tag->value, $value);
	}

	private function explainTypeAlias(int $index): ParserException
	{
		$state = $this->saveReading();
		try {
			if (!$this->parseRule(self::RULES['Type'], $this->position($index), false)) {
				return $this->createException($this->failure());
			}

			$last = count($this->indexes) - 1;

			return $this->createExceptionAt($this->indexes[$this->ids[$this->p] !== -1 ? $this->p : $last], Lexer::TOKEN_PHPDOC_EOL);
		} finally {
			$this->restoreReading($state);
		}
	}

	// -------------------------------------------------------------------------
	//  Doctrine annotations
	// -------------------------------------------------------------------------

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceDoctrineArgument(int $begin, int $end, $children): Doctrine\DoctrineArgument
	{
		$key = null;
		$value = null;
		$hasKey = false;
		foreach ($children as $child) {
			if ($child instanceof Token) {
				if ($child->id === $this->tEqual) {
					$hasKey = true;
				}
			} else {
				$value = $child;
			}
		}

		if ($hasKey) {
			/** @var Token $name */
			$name = $children[0];
			$key = ($this->useAttributes ? $this->attributesAt(new Ast\Type\IdentifierTypeNode($name->value), $name->offset, $name->offset, false, true) : new Ast\Type\IdentifierTypeNode($name->value));
		}

		/** @var Doctrine\DoctrineAnnotation|Ast\Type\IdentifierTypeNode|Doctrine\DoctrineArray|ConstExprNode $value */
		return $this->useAttributes ? $this->attributesAt(new Doctrine\DoctrineArgument($key, $value), $begin, $end, false, true) : new Doctrine\DoctrineArgument($key, $value);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceDoctrineNestedAnnotation(int $begin, int $end, $children): Doctrine\DoctrineAnnotation
	{
		$arguments = [];
		foreach ($children as $child) {
			if (!$child instanceof Doctrine\DoctrineArgument) {
				continue;
			}

			$arguments[] = $child;
		}

		return $this->useAttributes ? $this->attributesAt(new Doctrine\DoctrineAnnotation($children[0]->value, $arguments), $begin, $end, false, true) : new Doctrine\DoctrineAnnotation($children[0]->value, $arguments);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceDoctrineArray(int $begin, int $end, $children): Doctrine\DoctrineArray
	{
		$items = [];
		foreach ($children as $child) {
			if (!$child instanceof Doctrine\DoctrineArrayItem) {
				continue;
			}

			$items[] = $child;
		}

		return $this->useAttributes ? $this->attributesAt(new Doctrine\DoctrineArray($items), $begin, $end, false, true) : new Doctrine\DoctrineArray($items);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceDoctrineArrayItem(int $begin, int $end, $children): Doctrine\DoctrineArrayItem
	{
		$nodes = [];
		foreach ($children as $child) {
			if ($child instanceof Token) {
				continue;
			}

			$nodes[] = $child;
		}

		$item = count($nodes) === 2
			? new Doctrine\DoctrineArrayItem($nodes[0], $nodes[1])
			: new Doctrine\DoctrineArrayItem(null, $nodes[0]);

		return $this->useAttributes ? $this->attributesAt($item, $begin, $end, false, true) : $item;
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceDoctrineArrayKey(int $begin, int $end, $children): Node
	{
		$first = $children[0];
		if ($first instanceof Node) {
			return $first;
		}

		if ($first->id === $this->tInteger) {
			$key = new Ast\ConstExpr\ConstExprIntegerNode(str_replace('_', '', $first->value));
		} else {
			$key = $this->stringOrIdentifier($first);
		}

		return $this->useAttributes ? $this->attributesAt($key, $begin, $end, false, true) : $key;
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceDoctrineIdentifier(int $begin, int $end, $children): Ast\Type\IdentifierTypeNode
	{
		return $this->useAttributes ? $this->attributesAt(new Ast\Type\IdentifierTypeNode($children[0]->value), $begin, $end, false, true) : new Ast\Type\IdentifierTypeNode($children[0]->value);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceDoctrineConstantFetch(int $begin, int $end, $children): ConstExprNode
	{
		return $this->attributesAt($this->constantFetch($children), $begin, $end, false, true);
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceDoctrineFloat(int $begin, int $end, $children): ConstExprNode
	{
		return $this->useAttributes ? $this->attributesAt(new Ast\ConstExpr\ConstExprFloatNode(str_replace('_', '', $children[0]->value)), $begin, $end, false, true) : new Ast\ConstExpr\ConstExprFloatNode(str_replace('_', '', $children[0]->value));
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceDoctrineInteger(int $begin, int $end, $children): ConstExprNode
	{
		return $this->useAttributes ? $this->attributesAt(new Ast\ConstExpr\ConstExprIntegerNode(str_replace('_', '', $children[0]->value)), $begin, $end, false, true) : new Ast\ConstExpr\ConstExprIntegerNode(str_replace('_', '', $children[0]->value));
	}

	/**
	 * @param list<mixed> $children
	 */
	protected function reduceDoctrineString(int $begin, int $end, $children): ConstExprNode
	{
		$text = '';
		foreach ($children as $child) {
			$text .= $child->value;
		}

		return $this->useAttributes ? $this->attributesAt(new Ast\ConstExpr\DoctrineConstExprStringNode(Ast\ConstExpr\DoctrineConstExprStringNode::unescape($text)), $begin, $end, false, true) : new Ast\ConstExpr\DoctrineConstExprStringNode(Ast\ConstExpr\DoctrineConstExprStringNode::unescape($text));
	}

}
