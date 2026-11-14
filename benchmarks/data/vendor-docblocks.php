<?php declare(strict_types = 1);

// Every tenth PHPDoc of the dev dependencies of this library (PHPUnit, PHP-Parser, Doctrine
// Annotations, Symfony Process, PHPStan rules, and their dependencies, under the MIT, BSD-2-Clause,
// and BSD-3-Clause licenses), as typical PHPDocs.

return array (
  0 => '/**
 * ClassLoader implements a PSR-0, PSR-4 and classmap class loader.
 *
 *     $loader = new \\Composer\\Autoload\\ClassLoader();
 *
 *     // register classes with namespaces
 *     $loader->add(\'Symfony\\Component\', __DIR__.\'/component\');
 *     $loader->add(\'Symfony\',           __DIR__.\'/framework\');
 *
 *     // activate the autoloader
 *     $loader->register();
 *
 *     // to enable searching the include path (eg. for PEAR packages)
 *     $loader->setUseIncludePath(true);
 *
 * In this example, if you try to use a class in the Symfony\\Component
 * namespace or one of its children (Symfony\\Component\\Console for instance),
 * the autoloader will first look for the class under the component/
 * directory, and it will then fallback to the framework/ directory if not
 * found before giving up.
 *
 * This class is loosely based on the Symfony UniversalClassLoader.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @see    https://www.php-fig.org/psr/psr-0/
 * @see    https://www.php-fig.org/psr/psr-4/
 */',
  1 => '/** @var bool */',
  2 => '/**
     * @param array<string, string> $classMap Class to filename map
     *
     * @return void
     */',
  3 => '/**
     * The APCu prefix in use, or null if APCu caching is not enabled.
     *
     * @return string|null
     */',
  4 => '/**
     * @var string|null if set (by reflection by Composer), this should be set to the path where this class is being copied to
     * @internal
     */',
  5 => '/**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */',
  6 => '/** @var array{root: array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}, versions: array<string, array{pretty_version?: string, version?: string, reference?: string|null, type?: string, install_path?: string, aliases?: string[], dev_requirement: bool, replaced?: string[], provided?: string[]}>} $required */',
  7 => '/**
     * Creates a new AnnotationException describing an required error of an attribute.
     *
     * @return AnnotationException
     */',
  8 => '/**
     * {@inheritDoc}
     */',
  9 => '/**
     * Parse a class or function for use statements.
     *
     * @param ReflectionClass|ReflectionFunction $reflection
     *
     * @psalm-return array<string, string> a list with use statements in the form (Alias => FQN).
     */',
  10 => '/**
     * Proxies all methods to the delegate.
     *
     * @param mixed[] $args
     *
     * @return mixed
     */',
  11 => '/**
     * Targets as bitmask.
     *
     * @var int
     */',
  12 => '/**
 * Annotation that can be used to signal to the parser
 * to check the available values during the parsing process.
 *
 * @Annotation
 * @Attributes({
 *    @Attribute("value",   required = true,  type = "array"),
 *    @Attribute("literal", required = false, type = "array")
 * })
 */',
  13 => '/**
     * Gets the annotations applied to a class.
     *
     * @param ReflectionClass $class The ReflectionClass of the class from which
     * the class annotations should be read.
     *
     * @return array<object> An array of Annotations.
     */',
  14 => '/**
     * Gets the next non whitespace and non comment token.
     *
     * @param bool $docCommentIsComment If TRUE then a doc comment is considered a comment and skipped.
     * If FALSE then only whitespace and normal comments are skipped.
     *
     * @return mixed[]|string|null The token if exists, null otherwise.
     */',
  15 => '/**
     * Add a new annotation to the globally ignored annotation names with regard to exception handling.
     */',
  16 => '/**
     * {@inheritDoc}
     */',
  17 => '/**
     * Collects parsing metadata for a given class or function.
     *
     * @param ReflectionClass|ReflectionFunction $reflection
     */',
  18 => '/**
     * An array of default namespaces if operating in simple mode.
     *
     * @var string[]
     */',
  19 => '/**
     * Sets the default namespaces.
     *
     * @return void
     *
     * @throws RuntimeException
     */',
  20 => '/**
     * Collects parsing metadata for a given attribute.
     *
     * @param mixed[] $metadata
     */',
  21 => '/**
     * PlainValue ::= integer | string | float | boolean | Array | Annotation
     *
     * @return mixed
     *
     * @throws AnnotationException
     * @throws ReflectionException
     */',
  22 => '/**
     * Whether the next token starts immediately, or if there were
     * non-captured symbols before that
     */',
  23 => '/**
     * Trigger a deprecation for the given package and identifier when called from outside.
     *
     * "Outside" means we assume that $package is currently installed as a
     * dependency and the caller is not a file in that package. When $package
     * is installed as a root package then deprecations triggered from the
     * tests folder are also considered "outside".
     *
     * This deprecation method assumes that you are using Composer to install
     * the dependency and are using the default /vendor/ folder and not a
     * Composer plugin to change the install location. The assumption is also
     * that $package is the exact composer packge name.
     *
     * Compared to {@link trigger()} this method causes some overhead when
     * deprecation tracking is enabled even during deduplication, because it
     * needs to call {@link debug_backtrace()}
     *
     * @param float|int|string $args
     */',
  24 => '/**
     * @after
     * @final since 1.2
     */',
  25 => '/**
     * @phpstan-param ReflectionClass<T> $reflectionClass
     *
     * @throws UnexpectedValueException
     *
     * @template T of object
     */',
  26 => '/**
     * @phpstan-param ReflectionClass<T> $reflectionClass
     *
     * @template T of object
     */',
  27 => '/**
     * @deprecated Use the value, type or position property instead
     * {@inheritDoc}
     *
     * @param O $offset
     *
     * @return mixed
     * @psalm-return (
     *     O is \'value\'
     *     ? V
     *     : (
     *         O is \'type\'
     *         ? T|null
     *         : (
     *             O is \'position\'
     *             ? int
     *             : mixed
     *         )
     *     )
     * )
     *
     * @template O of array-key
     */',
  28 => '/**
     * Composed regex for input parsing.
     *
     * @var non-empty-string|null
     */',
  29 => '/**
     * Checks if given value is identical to the given token.
     *
     * @param string     $value
     * @param int|string $token
     *
     * @return bool
     */',
  30 => '/**
     * @var callable
     */',
  31 => '/**
 * @final
 */',
  32 => '/**
     * Retrieves property by name from object and all its ancestors.
     *
     * @param object|string $object
     * @param string $name
     *
     * @throws PropertyException
     * @throws ReflectionException
     *
     * @return ReflectionProperty
     */',
  33 => '/**
     * {@inheritdoc}
     */',
  34 => '/**
     * @var string
     */',
  35 => '/**
 * Matches a property by its type.
 *
 * It is recommended to use {@see DeepCopy\\TypeFilter\\TypeFilter} instead, as it applies on all occurrences
 * of given type in copied context (eg. array elements), not just on object properties.
 *
 * @final
 */',
  36 => '/**
     * @var bool
     */',
  37 => '/**
 * A PHP version, representing only the major and minor version components.
 */',
  38 => '/**
     * Check whether this is the host PHP version.
     */',
  39 => '/**
     * Whether this version supports unicode escape sequences in strings.
     */',
  40 => '/**
     * @internal
     */',
  41 => '/** @var int Number of non-leaf states */',
  42 => '/** @var callable[] Semantic action callbacks */',
  43 => '/** @var int[] Token end position stack */',
  44 => '/** @return Stmt[]|null */',
  45 => '/** @param array<string, mixed> $attributes */',
  46 => '/**
     * @param Property|Param $node
     */',
  47 => '/**
 * Error handler that handles all errors by throwing them.
 *
 * This is the default strategy used by all components.
 */',
  48 => '/**
     * Gets all comments directly preceding this node.
     *
     * The comments are also available through the "comments" attribute.
     *
     * @return Comment[]
     */',
  49 => '/**
     * Gets the comment text.
     *
     * @return string The comment text (including comment delimiters like /*)
     */',
  50 => '/**
     * @return array{nodeType:string, text:mixed, line:mixed, filePos:mixed}
     */',
  51 => '/**
     * Normalizes a doc comment: Converts plain strings to PhpParser\\Comment\\Doc.
     *
     * @param Comment\\Doc|string $docComment The doc comment to normalize
     *
     * @return Comment\\Doc The normalized doc comment
     */',
  52 => '/**
     * Gets the token offset of the last token that is part of this node.
     *
     * The offset is an index into the array returned by Lexer::getTokens().
     *
     * Requires the \'endTokenPos\' attribute to be enabled in the lexer (DISABLED by default).
     *
     * @return int Token end position (or -1 if not available)
     */',
  53 => '/**
     * Calculate diff (edit script) from $old to $new.
     *
     * @param T[] $old Original array
     * @param T[] $new New array
     *
     * @return DiffElem[] Diff (edit script)
     */',
  54 => '/**
     * Whether the given position is immediately surrounded by braces.
     *
     * @param int $startPos Start position
     * @param int $endPos End position
     */',
  55 => '/**
     * Get the code corresponding to a token offset range, optionally adjusted for indentation.
     *
     * @param int $from Token start position (inclusive)
     * @param int $to Token end position (exclusive)
     * @param int $indent By how much the code should be indented (can be negative as well)
     *
     * @return string Code corresponding to token range, adjusted for indentation
     */',
  56 => '/**
     * Get the name of the token. For single-char tokens this will be the token character.
     * Otherwise it will be a T_* style name, or null if the token ID is unknown.
     */',
  57 => '/** @var null|Node\\Name Name of extended class */',
  58 => '/** @var Name[][] Map of format [aliasType => [aliasName => originalName]] */',
  59 => '/**
     * Get shortest representation of this fully-qualified name.
     *
     * @param string $name Fully-qualified name (without leading namespace separator)
     * @param Stmt\\Use_::TYPE_* $type One of Stmt\\Use_::TYPE_*
     *
     * @return Name Shortest representation
     */',
  60 => '/** @return mixed */',
  61 => '/** @param Token[] $tokens */',
  62 => '/** @var list<Arg> Attribute arguments */',
  63 => '/** @var null|Expr|Stmt[] Hook body */',
  64 => '/** @var Expr Variable */',
  65 => '/** @var Node\\Stmt[] Statements */',
  66 => '/** @var Expr Expression */',
  67 => '/** @var Name|Expr Class name */',
  68 => '/**
     * Constructs a pre increment node.
     *
     * @param Expr $var Variable
     * @param array<string, mixed> $attributes Additional attributes
     */',
  69 => '/**
     * Returns whether this call expression is a partial function application, i.e. whether its
     * argument list contains one or more "?" placeholders or a "..." placeholder. First-class
     * callables are a special case of partial function application, so this also returns true
     * for them.
     */',
  70 => '/** @var Identifier|Expr Method name */',
  71 => '/**
     * Constructs a unary minus node.
     *
     * @param Expr $expr Expression
     * @param array<string, mixed> $attributes Additional attributes
     */',
  72 => '/**
     * Constructs a shell exec (backtick) node.
     *
     * @param (Expr|InterpolatedStringPart)[] $parts Interpolated string array
     * @param array<string, mixed> $attributes Additional attributes
     */',
  73 => '/**
     * Constructs a const fetch node.
     *
     * @param Name $name Constant name
     * @param array<string, mixed> $attributes Additional attributes
     */',
  74 => '/**
     * Constructs a boolean not node.
     *
     * @param Expr $expr Expression
     * @param array<string, mixed> $attributes Additional attributes
     */',
  75 => '/**
     * Constructs an print() node.
     *
     * @param Expr $expr Expression
     * @param array<string, mixed> $attributes Additional attributes
     */',
  76 => '/** @var Expr Variable */',
  77 => '/** @var Expr Expression body */',
  78 => '/**
     * Get identifier as string.
     *
     * @psalm-return non-empty-string
     * @return string Identifier as string.
     */',
  79 => '/**
     * Checks whether the name is explicitly relative to the current namespace. (E.g. namespace\\Name)
     *
     * @return bool Whether the name is relative
     */',
  80 => '/**
     * @psalm-var non-empty-string
     * @var string Name as string
     */',
  81 => '/**
     * Returns a string representation of the name itself, without taking the name type into
     * account (e.g., not including a leading backslash for fully qualified names).
     *
     * @psalm-return non-empty-string
     * @return string String representation
     */',
  82 => '/** @var bool Whether to assign by reference */',
  83 => '/** @var null|Identifier|Name|ComplexType Type declaration */',
  84 => '/**
     * Whether the property is final.
     */',
  85 => '/**
     * Constructs a declare node.
     *
     * @param DeclareItem[] $declares List of declares
     * @param Node\\Stmt[]|null $stmts Statements
     * @param array<string, mixed> $attributes Additional attributes
     */',
  86 => '/** @deprecated Use Modifiers::PROTECTED instead */',
  87 => '/**
     * Constructs a class node.
     *
     * @param string|Node\\Identifier|null $name Name
     * @param array{
     *     flags?: int,
     *     extends?: Node\\Name|null,
     *     implements?: Node\\Name[],
     *     stmts?: Node\\Stmt[],
     *     attrGroups?: Node\\AttributeGroup[],
     * } $subNodes Array of the following optional subnodes:
     *             \'flags\'       => 0      : Flags
     *             \'extends\'     => null   : Name of extended class
     *             \'implements\'  => array(): Names of implemented interfaces
     *             \'stmts\'       => array(): Statements
     *             \'attrGroups\'  => array(): PHP attribute groups
     * @param array<string, mixed> $attributes Additional attributes
     */',
  88 => '/**
     * Constructs a case node.
     *
     * @param null|Node\\Expr $cond Condition (null for default)
     * @param Node\\Stmt[] $stmts Statements
     * @param array<string, mixed> $attributes Additional attributes
     */',
  89 => '/** @var self::TYPE_* Type of alias */',
  90 => '/** @var int Modifiers */',
  91 => '/** @var Name Prefix for uses */',
  92 => '/**
     * Constructs an else node.
     *
     * @param Node\\Stmt[] $stmts Statements
     * @param array<string, mixed> $attributes Additional attributes
     */',
  93 => '/** @var Node\\Name|null Trait name */',
  94 => '/** @var Node\\Expr Condition */',
  95 => '/**
     * Constructs a finally node.
     *
     * @param Node\\Stmt[] $stmts Statements
     * @param array<string, mixed> $attributes Additional attributes
     */',
  96 => '/** @var Node\\Identifier|null Name */',
  97 => '/** @var Node\\Expr[] Variables to unset */',
  98 => '/** @var Node\\AttributeGroup[] PHP attribute groups */',
  99 => '/**
     * Constructs an if node.
     *
     * @param Node\\Expr $cond Condition
     * @param array{
     *     stmts?: Node\\Stmt[],
     *     elseifs?: ElseIf_[],
     *     else?: Else_|null,
     * } $subNodes Array of the following optional subnodes:
     *             \'stmts\'   => array(): Statements
     *             \'elseifs\' => array(): Elseif clauses
     *             \'else\'    => null   : Else clause
     * @param array<string, mixed> $attributes Additional attributes
     */',
  100 => '/** @var Node\\Param[] Parameters */',
  101 => '/**
     * Whether the method is final.
     */',
  102 => '/**
     * Get the declared return type or null
     *
     * @return null|Identifier|Name|ComplexType
     */',
  103 => '/**
     * Constructs an alias (use) item node.
     *
     * @param Node\\Name $name Namespace/Class to alias
     * @param null|string|Identifier $alias Alias
     * @param Use_::TYPE_* $type Type of the use element (for mixed group use only)
     * @param array<string, mixed> $attributes Additional attributes
     */',
  104 => '/** @var int Optional visibility flags */',
  105 => '/** @var int Number value */',
  106 => '/**
     * Constructs a magic constant node.
     *
     * @param array<string, mixed> $attributes Additional attributes
     */',
  107 => '/**
 * This is a base class for complex types, including nullable types and union types.
 *
 * It does not provide any shared behavior and exists only for type-checking purposes.
 */',
  108 => '/** @param (Expr|Node\\InterpolatedStringPart)[] $parts */',
  109 => '/**
     * Creates a trait builder.
     *
     * @param string $name Name of the trait
     *
     * @return Builder\\Trait_ The created trait builder
     */',
  110 => '/**
     * Creates a constant use builder.
     *
     * @param Node\\Name|string $name Name of the const to alias
     *
     * @return Builder\\Use_ The created use const builder
     */',
  111 => '/**
     * Creates a constant fetch node.
     *
     * @param string|Name $name Constant name
     */',
  112 => '/**
     * Gets the attributes of the node/token the error occurred at.
     *
     * @return array<string, mixed>
     */',
  113 => '/** @var list<Stmt\\TraitUse> */',
  114 => '/** @var null|Identifier|Name|ComplexType */',
  115 => '/**
     * Makes the property final.
     *
     * @return $this The builder instance (for fluid interface)
     */',
  116 => '/** @var list<Stmt\\TraitUse> */',
  117 => '/**
     * Returns the built class node.
     *
     * @return Stmt\\Enum_ The built enum node
     */',
  118 => '/**
     * Makes the method private.
     *
     * @return $this The builder instance (for fluid interface)
     */',
  119 => '/** @var list<Stmt\\Property> */',
  120 => '/**
     * Adds an attribute group.
     *
     * @param Node\\Attribute|Node\\AttributeGroup $attribute
     *
     * @return $this The builder instance (for fluid interface)
     */',
  121 => '/**
     * Sets alias for used name.
     *
     * @param string $alias Alias to use (last component of full name by default)
     *
     * @return $this The builder instance (for fluid interface)
     */',
  122 => '/**
     * Makes the constant private.
     *
     * @return $this The builder instance (for fluid interface)
     */',
  123 => '/**
     * Creates an interface builder.
     *
     * @param string $name Name of the interface
     */',
  124 => '/**
     * Sets the return type for PHP 7.
     *
     * @param string|Node\\Name|Node\\Identifier|Node\\ComplexType $type
     *
     * @return $this The builder instance (for fluid interface)
     */',
  125 => '/** @var array<string, mixed> */',
  126 => '/**
     * Sets default value for the parameter.
     *
     * @param mixed $value Default value to use
     *
     * @return $this The builder instance (for fluid interface)
     */',
  127 => '/**
     * Adds an attribute group.
     *
     * @param Node\\Attribute|Node\\AttributeGroup $attribute
     *
     * @return $this The builder instance (for fluid interface)
     */',
  128 => '/**
     * Adds multiple statements.
     *
     * @param (PhpParser\\Node\\Stmt|PhpParser\\Builder)[] $stmts The statements to add
     *
     * @return $this The builder instance (for fluid interface)
     */',
  129 => '/**
     * Called once after traversal.
     *
     * Return value semantics:
     *  * null:      $nodes stays as-is
     *  * otherwise: $nodes is set to the return value
     *
     * @param Node[] $nodes Array of nodes
     *
     * @return null|Node[] Array of nodes
     */',
  130 => '/** @var NameContext Naming context */',
  131 => '/** @var callable Filter callback */',
  132 => '/**
 * Visitor that connects a child node to its parent node
 * as well as its sibling nodes.
 *
 * With <code>$weakReferences=false</code> on the child node, the parent node can be accessed through
 * <code>$node->getAttribute(\'parent\')</code>, the previous
 * node can be accessed through <code>$node->getAttribute(\'previous\')</code>,
 * and the next node can be accessed through <code>$node->getAttribute(\'next\')</code>.
 *
 * With <code>$weakReferences=true</code> attribute names are prefixed by "weak_", e.g. "weak_parent".
 */',
  133 => '/**
     * @deprecated Use NodeVisitor::DONT_TRAVERSE_CHILDREN instead.
     */',
  134 => '/**
     * Recursively traverse a node.
     *
     * @param Node $node Node to traverse.
     */',
  135 => '/** @var string|null Token placed at end of doc string to ensure it is followed by a newline.
     *                   Null if flexible doc strings are used. */',
  136 => '/**
     * @var array<string, string> Map From "{$class}->{$subNode}" to string that should be inserted
     *                            between elements of this list subnode.
     */',
  137 => '/**
     * Pretty prints a file of statements (includes the opening <?php tag if it is required).
     *
     * @param Node[] $stmts Array of statements
     *
     * @return string Pretty printed statements
     */',
  138 => '/**
     * Prints reformatted text of the passed comments.
     *
     * @param Comment[] $comments List of comments
     *
     * @return string Reformatted text of comments
     */',
  139 => '/**
     * Determines whether the LHS of a call must be wrapped in parenthesis.
     *
     * @param Node $node LHS of a call
     *
     * @return bool Whether parentheses are required
     */',
  140 => '/** @var \\ReflectionClass<Node>[] Node type to reflection class map */',
  141 => '/** @var Version */',
  142 => '/** @var Requirement[] */',
  143 => '/**
     * @throws InvalidUrlException
     */',
  144 => '/** @var null|Email */',
  145 => '/** @var int */',
  146 => '/** @var LibXMLError[] */',
  147 => '/**
     * @throws NoPreReleaseSuffixException
     */',
  148 => '/**
     * @throws InvalidPreReleaseSuffixException
     */',
  149 => '/** @var int */',
  150 => '/** @var resource */',
  151 => '/** @var string */',
  152 => '/**
     * @param string $phpExecutable
     * @param string $output
     * @param bool $isHhvmType
     * @return PhpExecutable
     * @throws RunTimeException
     */',
  153 => '/**
     * @param PhpExecutable $phpExecutable
     * @param string $fileToCheck Path to file to check
     * @param bool $aspTags
     * @param bool $shortTag
     * @param bool $deprecated
     * @throws RunTimeException
     */',
  154 => '/**
     * @return string
     * @throws RunTimeException
     */',
  155 => '/** @var int */',
  156 => '/**
     * @param int $parallelJobs
     * @return ParallelLint
     */',
  157 => '/**
     * @param $showDeprecated
     * @return ParallelLint
     */',
  158 => '/** @var array */',
  159 => '/** @var array */',
  160 => '/**
     * @return int
     */',
  161 => '/** @var bool */',
  162 => '/**
     * @return string
     */',
  163 => '/**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */',
  164 => '/** @var IWriter */',
  165 => '/**
     * @param int $count
     */',
  166 => '/**
     * @param string $string
     */',
  167 => '/**
     * Check PHP code inside ASP-style <% %> tags.
     * @var bool
     */',
  168 => '/**
     * Path to git executable for blame
     * @var string
     */',
  169 => '/**
	 * @return array<string, string>
	 */',
  170 => '/**
	 * @return array<ReflectionMethod>
	 */',
  171 => '/**
	 * @template T of NodeAbstract
	 * @param array<T> $args
	 * @return list<T|Node\\Arg>|null
	 */',
  172 => '/**
	 * @return array<PhpDocTagNode>
	 */',
  173 => '/**
	 * The file type mapper.
	 *
	 */',
  174 => '/**
 * @implements Rule<MethodReturnStatementsNode>
 */',
  175 => '/**
	 * @param Arg[] $args
	 */',
  176 => '/**
 * @implements Rule<If_>
 */',
  177 => '/**
 * @implements Rule<VariableWritesNode>
 */',
  178 => '/**
 * @phpstan-implements Rule<UnaryPlus>
 */',
  179 => '/**
 * @phpstan-extends OperandInArithmeticIncrementOrDecrementRule<PostDec>
 */',
  180 => '/**
 * @implements Rule<StaticPropertyFetch>
 */',
  181 => '/**
     * @var Wizard
     */',
  182 => '/**
     * @var ?FileAnalyser
     */',
  183 => '/**
     * Sets the test data.
     */',
  184 => '/**
     * @throws UnintentionallyCoveredCodeException
     */',
  185 => '/**
     * @var array<int, string>
     */',
  186 => '/**
     * @psalm-param Identifier|Name|ComplexType $type
     */',
  187 => '/**
     * @return mixed
     */',
  188 => '/**
     * @var array
     */',
  189 => '/**
     * @throws PhpdbgNotAvailableException
     */',
  190 => '/**
     * @throws NoCodeCoverageDriverWithPathCoverageSupportAvailableException
     * @throws Xdebug2NotEnabledException
     * @throws Xdebug3NotEnabledException
     * @throws XdebugNotAvailableException
     *
     * @deprecated Use DriverSelector::forLineAndPathCoverage() instead
     */',
  191 => '/**
     * @throws PcovNotAvailableException
     */',
  192 => '/**
     * @param int[] $lines
     */',
  193 => '/**
 * @internal This class is not covered by the backward compatibility promise for phpunit/php-code-coverage
 */',
  194 => '/** @var int $line */',
  195 => '/**
     * @var string
     */',
  196 => '/**
     * @var Project
     */',
  197 => '/**
 * @internal This class is not covered by the backward compatibility promise for phpunit/php-code-coverage
 */',
  198 => '/**
     * @var DOMElement
     */',
  199 => '/**
     * @var DOMElement
     */',
  200 => '/**
     * @var string
     */',
  201 => '/**
 * @internal This class is not covered by the backward compatibility promise for phpunit/php-code-coverage
 */',
  202 => '/**
     * Moves forward to next element.
     */',
  203 => '/**
     * @var File[]
     */',
  204 => '/**
     * @var int
     */',
  205 => '/**
     * @psalm-return array{linesOfCode: int, commentLinesOfCode: int, nonCommentLinesOfCode: int}
     */',
  206 => '/**
     * @var array
     */',
  207 => '/**
     * @var array
     */',
  208 => '/**
     * @psalm-param array{linesOfCode: int, commentLinesOfCode: int, nonCommentLinesOfCode: int} $linesOfCode
     */',
  209 => '/**
     * @var float
     */',
  210 => '/**
     * @throws Throwable
     */',
  211 => '/**
     * @var int
     */',
  212 => '/**
     * @return list<ExecutionOrderDependency>
     */',
  213 => '/** @psalm-param class-string $className */',
  214 => '/**
     * Returns the name of the failing test (including data set, if any).
     */',
  215 => '/**
     * @var ?bool
     */',
  216 => '/**
     * @var TestFailure[]
     */',
  217 => '/**
     * @var bool
     */',
  218 => '/**
     * @var bool
     */',
  219 => '/**
     * @var bool
     */',
  220 => '/**
     * Informs the result that a test will be started.
     */',
  221 => '/**
     * Returns an array of TestFailure objects for the skipped tests.
     *
     * @return TestFailure[]
     */',
  222 => '/**
     * Runs a TestCase.
     *
     * @throws \\SebastianBergmann\\CodeCoverage\\InvalidArgumentException
     * @throws CodeCoverageException
     * @throws InvalidArgumentException
     * @throws UnintentionallyCoveredCodeException
     */',
  223 => '/**
     * Returns the error-to-exception conversion setting.
     */',
  224 => '/**
     * Enables or disables the stopping for skipped tests.
     */',
  225 => '/**
 * @internal
 */',
  226 => '/**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     */',
  227 => '/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */',
  228 => '/**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     */',
  229 => '/**
     * @var string
     */',
  230 => '/**
     * Returns a string representation of the constraint.
     */',
  231 => '/**
     * @var string
     */',
  232 => '/**
     * @var array<string,bool>
     */',
  233 => '/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */',
  234 => '/**
     * Returns a string representation of the constraint.
     */',
  235 => '/**
     * Translates a given type to a human readable message prefix.
     */',
  236 => '/**
     * @var Constraint
     */',
  237 => '/**
     * Returns the description of the failure.
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param mixed $other evaluated value or object
     *
     * @throws InvalidArgumentException
     */',
  238 => '/**
     * Returns the number of operands (constraints).
     */',
  239 => '/**
     * Returns the number of operands.
     */',
  240 => '/**
     * @var Constraint
     */',
  241 => '/**
     * Returns the name of this operator.
     */',
  242 => '/**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     */',
  243 => '/**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     */',
  244 => '/**
     * Returns the description of the failure.
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param mixed $other evaluated value or object
     */',
  245 => '/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */',
  246 => '/**
     * Returns a string representation of the constraint.
     *
     * @throws InvalidArgumentException
     */',
  247 => '/**
     * Returns a string representation of the constraint.
     */',
  248 => '/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */',
  249 => '/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */',
  250 => '/**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * This method can be overridden to implement the evaluation algorithm.
     *
     * @param mixed $other value or object to evaluate
     *
     * @codeCoverageIgnore
     */',
  251 => '/**
     * Counts the number of constraint elements.
     */',
  252 => '/**
     * Asserts that a haystack does not contain a needle.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws Exception
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertNotContains
     */',
  253 => '/**
     * Asserts that two variables are not equal.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertNotEquals
     */',
  254 => '/**
     * Asserts that a value is smaller than or equal to another value.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertLessThanOrEqual
     */',
  255 => '/**
     * Asserts that the contents of a string is not equal
     * to the contents of a file.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertStringNotEqualsFile
     */',
  256 => '/**
     * Asserts that a directory does not exist.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertDirectoryDoesNotExist
     */',
  257 => '/**
     * Asserts that a file does not exist.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @codeCoverageIgnore
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/4077
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertFileNotExists
     */',
  258 => '/**
     * Asserts that a condition is not false.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-assert !false $condition
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertNotFalse
     */',
  259 => '/**
     * Asserts that an object has a specified attribute.
     *
     * @param object $object
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws Exception
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertObjectHasAttribute
     */',
  260 => '/**
     * Asserts that a variable is of type float.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-assert float $actual
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertIsFloat
     */',
  261 => '/**
     * Asserts that a variable is not of type array.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-assert !array $actual
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertIsNotArray
     */',
  262 => '/**
     * Asserts that a variable is not of type callable.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-assert !callable $actual
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertIsNotCallable
     */',
  263 => '/**
     * Asserts that a string matches a given format file.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertStringMatchesFormatFile
     */',
  264 => '/**
     * Asserts that two XML files are equal.
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws Exception
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertXmlFileEqualsXmlFile
     */',
  265 => '/**
     * Asserts that two given JSON encoded objects or arrays are not equal.
     *
     * @param string $expectedJson
     * @param string $actualJson
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
     *
     * @see Assert::assertJsonStringNotEqualsJsonString
     */',
  266 => '/**
     * Returns a matcher that matches when the method is executed
     * exactly $count times.
     */',
  267 => '/**
     * @var int
     */',
  268 => '/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */',
  269 => '/**
     * @var int
     */',
  270 => '/**
     * Verifies that the current expectation is valid. If everything is OK the
     * code should just return, if not it must throw an exception.
     *
     * @throws ExpectationFailedException
     */',
  271 => '/**
     * @throws \\PHPUnit\\Framework\\Exception
     */',
  272 => '/**
     * @var bool
     */',
  273 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  274 => '/**
     * @var string
     */',
  275 => '/**
     * @var Stub
     */',
  276 => '/**
     * @var bool
     */',
  277 => '/**
     * @throws RuntimeException
     */',
  278 => '/**
     * @var mixed
     */',
  279 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  280 => '/**
     * Looks up the match builder with identification $id and returns it.
     *
     * @param string $id The identification of the match builder
     */',
  281 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  282 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  283 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  284 => '/**
 * @internal This interface is not covered by the backward compatibility promise for PHPUnit
 */',
  285 => '/**
     * @param mixed[] $arguments
     *
     * @throws \\PHPUnit\\Framework\\Exception
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     *
     * @return $this
     */',
  286 => '/**
     * @param int $argumentIndex
     *
     * @return self
     */',
  287 => '/**
     * @var string
     */',
  288 => '/**
     * @var Generator
     */',
  289 => '/**
     * Specifies the name for the mock class.
     *
     * @return $this
     */',
  290 => '/**
     * Disables the invocation of the original methods.
     *
     * @return $this
     */',
  291 => '/**
     * @var Template[]
     */',
  292 => '/**
     * @throws ReflectionException
     *
     * @return MockMethod[]
     */',
  293 => '/**
     * @var bool
     */',
  294 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  295 => '/**
     * Wrapper for getMessage() which is declared as final.
     */',
  296 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  297 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  298 => '/**
     * @var string[]
     *
     * @deprecated Use $backupGlobalsExcludeList instead
     */',
  299 => '/**
     * @var int|string
     */',
  300 => '/**
     * @var MockObject[]
     */',
  301 => '/**
     * @var mixed
     */',
  302 => '/**
     * @var bool
     */',
  303 => '/**
     * Returns a matcher that matches when the method is executed
     * at the given index.
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/4297
     *
     * @codeCoverageIgnore
     */',
  304 => '/**
     * @psalm-param class-string<Throwable> $exception
     */',
  305 => '/**
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/5062
     */',
  306 => '/**
     * @throws InvalidArgumentException
     *
     * @internal This method is not covered by the backward compatibility promise for PHPUnit
     */',
  307 => '/**
     * @internal This method is not covered by the backward compatibility promise for PHPUnit
     */',
  308 => '/**
     * @internal This method is not covered by the backward compatibility promise for PHPUnit
     */',
  309 => '/**
     * @internal This method is not covered by the backward compatibility promise for PHPUnit
     */',
  310 => '/**
     * Returns the normalized test name as class::method.
     *
     * @return list<ExecutionOrderDependency>
     */',
  311 => '/**
     * Mocks the specified class and returns the name of the mocked class.
     *
     * @param null|array $methods $methods
     *
     * @psalm-template RealInstanceType of object
     *
     * @psalm-param class-string<RealInstanceType>|string $originalClassName
     *
     * @psalm-return class-string<MockObject&RealInstanceType>
     *
     * @deprecated
     */',
  312 => '/**
     * Get the mock object generator, creating it if it doesn\'t exist.
     */',
  313 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  314 => '/**
     * @var null|ExceptionWrapper
     */',
  315 => '/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */',
  316 => '/**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param Countable|iterable $haystack
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */',
  317 => '/**
     * Asserts that a variable is empty.
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert empty $actual
     */',
  318 => '/**
     * Asserts that the contents of one file is not equal to the contents of another
     * file (canonicalizing).
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     */',
  319 => '/**
     * Asserts that a file/dir exists and is not readable.
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @codeCoverageIgnore
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/4062
     */',
  320 => '/**
     * Asserts that a directory exists and is writable.
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     */',
  321 => '/**
     * Asserts that a file exists and is not writable.
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     */',
  322 => '/**
     * Asserts that a variable is nan.
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     */',
  323 => '/**
     * Asserts that two variables do not have the same type and value.
     * Used on objects, it asserts that two variables do not reference
     * the same object.
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     */',
  324 => '/**
     * Asserts that a variable is of type resource and is closed.
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert resource $actual
     */',
  325 => '/**
     * Asserts that a variable is not of type object.
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !object $actual
     */',
  326 => '/**
     * Asserts that a string does not match a given regular expression.
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @codeCoverageIgnore
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/4089
     */',
  327 => '/**
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     */',
  328 => '/**
     * Asserts that two XML documents are not equal.
     *
     * @param DOMDocument|string $expectedXml
     * @param DOMDocument|string $actualXml
     *
     * @throws \\SebastianBergmann\\RecursionContext\\InvalidArgumentException
     * @throws ExpectationFailedException
     * @throws Xml\\Exception
     */',
  329 => '/**
     * @throws Exception
     */',
  330 => '/**
     * Reset the assertion counter.
     */',
  331 => '/**
     * @var int
     */',
  332 => '/**
     * The number of tests in the test suite.
     *
     * @var int
     */',
  333 => '/**
     * Returns a string representation of the test suite.
     */',
  334 => '/** @psalm-var class-string $className */',
  335 => '/**
     * @return list<ExecutionOrderDependency>
     */',
  336 => '/**
     * @var int
     */',
  337 => '/**
     * An error occurred.
     */',
  338 => '/**
     * Formats a buffer with a specified ANSI color sequence if colors are
     * enabled.
     */',
  339 => '/**
     * @var ?string
     */',
  340 => '/**
     * @var ?string
     */',
  341 => '/**
     * @var ?bool
     */',
  342 => '/**
     * @var ?bool
     */',
  343 => '/**
     * @var ?bool
     */',
  344 => '/**
     * @var null|string[]
     */',
  345 => '/**
     * @var ?bool
     */',
  346 => '/**
     * @var ?bool
     */',
  347 => '/**
     * @var ?string
     */',
  348 => '/**
     * @var ?string
     */',
  349 => '/**
     * @throws Exception
     */',
  350 => '/**
     * @throws Exception
     */',
  351 => '/**
     * @throws Exception
     */',
  352 => '/**
     * @throws Exception
     */',
  353 => '/**
     * @throws Exception
     */',
  354 => '/**
     * @throws Exception
     */',
  355 => '/**
     * @throws Exception
     */',
  356 => '/**
     * @throws Exception
     */',
  357 => '/**
     * @throws Exception
     */',
  358 => '/**
 * @internal This interface is not covered by the backward compatibility promise for PHPUnit
 */',
  359 => '/**
     * @var Timer
     */',
  360 => '/**
     * @var mixed
     */',
  361 => '/**
     * @var VariableCollection
     */',
  362 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 *
 * @template-implements Iterator<int, IniSetting>
 */',
  363 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  364 => '/**
     * @var File[]
     */',
  365 => '/**
     * @var string
     */',
  366 => '/**
     * @var ExtensionCollection
     */',
  367 => '/**
     * @throws Exception
     * @throws MigrationBuilderException
     * @throws MigrationException
     * @throws XmlException
     */',
  368 => '/**
     * @throws MigrationException
     */',
  369 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  370 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 *
 * @template-implements Iterator<int, TestSuite>
 */',
  371 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 *
 * @psalm-immutable
 */',
  372 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 *
 * @template-implements Iterator<int, TestFile>
 */',
  373 => '/**
     * @var TestFileCollection
     */',
  374 => '/**
     * @deprecated
     */',
  375 => '/**
     * @param Directory[] $directories
     */',
  376 => '/**
     * @var FileCollection
     */',
  377 => '/**
     * @var ?Crap4j
     */',
  378 => '/**
     * @throws Exception
     */',
  379 => '/**
     * @throws Exception
     */',
  380 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 *
 * @psalm-immutable
 */',
  381 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  382 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 *
 * @psalm-immutable
 */',
  383 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 *
 * @template-implements Iterator<int, Extension>
 */',
  384 => '/**
     * @var bool
     */',
  385 => '/**
     * @var bool
     */',
  386 => '/**
     * @var bool
     */',
  387 => '/**
     * @var bool
     */',
  388 => '/**
     * @var bool
     */',
  389 => '/**
     * @throws Exception
     */',
  390 => '/**
     * @throws Exception
     */',
  391 => '/**
     * @return Extension[]
     */',
  392 => '/**
     * @param Group[] $groups
     */',
  393 => '/**
     * @psalm-var list<string>
     */',
  394 => '/**
     * Show the help message.
     */',
  395 => '/**
     * Prettify json string.
     *
     * @throws Exception
     */',
  396 => '/**
     * Defines if should use STDERR redirection or not.
     *
     * Then $stderrRedirection is TRUE, STDERR is redirected to STDOUT.
     */',
  397 => '/**
     * Runs a single test in a separate PHP process.
     *
     * @throws InvalidArgumentException
     */',
  398 => '/**
     * Handles creating the child process and returning the STDOUT and STDERR.
     *
     * @throws Exception
     */',
  399 => '/**
     * @return \'!=\'|\'<\'|\'<=\'|\'<>\'|\'=\'|\'==\'|\'>\'|\'>=\'|\'eq\'|\'ge\'|\'gt\'|\'le\'|\'lt\'|\'ne\'
     */',
  400 => '/**
     * @throws Exception
     */',
  401 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  402 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 *
 * @psalm-immutable
 */',
  403 => '/**
     * @var DOMElement[]
     */',
  404 => '/**
     * @param null|mixed $out
     */',
  405 => '/**
     * A test started.
     */',
  406 => '/**
     * Incomplete test.
     */',
  407 => '/**
     * Maps class names to source file names.
     *
     *   - PEAR CS:   Foo_Bar_Baz -> Foo/Bar/Baz.php
     *   - Namespace: Foo\\Bar\\Baz -> Foo/Bar/Baz.php
     */',
  408 => '/**
     * @todo This constant should be private (it\'s public because of TestTest::testGetProvidedDataRegEx)
     */',
  409 => '/**
     * @psalm-param class-string $classNameInHierarchy
     */',
  410 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  411 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  412 => '/**
     * Returns the missing requirements for a test.
     *
     * @psalm-param class-string $className
     *
     * @throws Exception
     * @throws Warning
     */',
  413 => '/** @psalm-param class-string $className */',
  414 => '/**
     * @var string
     */',
  415 => '/**
     * @throws InvalidArgumentException
     */',
  416 => '/**
     * @var int[]
     */',
  417 => '/**
     * @var array<string, int> Lookup table for testname to testResults[index]
     */',
  418 => '/**
     * @throws InvalidArgumentException
     */',
  419 => '/**
     * @var int
     */',
  420 => '/**
     * Flush buffer and close output.
     */',
  421 => '/**
     * A test ended.
     */',
  422 => '/**
     * @var null|Throwable
     */',
  423 => '/**
     * A test suite ended.
     */',
  424 => '/**
     * @var bool
     */',
  425 => '/**
     * @throws Exception
     *
     * @return string[]
     */',
  426 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  427 => '/**
     * @param array|string $args
     *
     * @throws Exception
     */',
  428 => '/**
     * @var TestResultCache
     */',
  429 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  430 => '/**
     * @var TestResultCache
     */',
  431 => '/**
     * Compares test duration for sorting tests by duration ascending.
     *
     * @throws InvalidArgumentException
     */',
  432 => '/**
     * @psalm-var array<string, int>
     */',
  433 => '/**
     * @psalm-return array{loadedExtensions: list<string>, notLoadedExtensions: list<string>}
     */',
  434 => '/**
     * Returns the name of the test case.
     */',
  435 => '/**
     * @throws Exception
     */',
  436 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  437 => '/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */',
  438 => '/**
     * Returns the loader to be used.
     */',
  439 => '/**
     * Confirms if the cache contains specified cache item.
     *
     * Note: This method MAY avoid retrieving the cached value for performance reasons.
     * This could result in a race condition with CacheItemInterface::get(). To avoid
     * such situation use CacheItemInterface::isHit() instead.
     *
     * @param string $key
     *   The key for which to check existence.
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \\Psr\\Cache\\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *   True if item exists in the cache, false otherwise.
     */',
  440 => '/**
     * Retrieves the value of the item from the cache associated with this object\'s key.
     *
     * The value returned must be identical to the value originally stored by set().
     *
     * If isHit() returns false, this method MUST return null. Note that null
     * is a legitimate cached value, so the isHit() method SHOULD be used to
     * differentiate between "null value was found" and "no value was found."
     *
     * @return mixed
     *   The value corresponding to this cache item\'s key, or null if not found.
     */',
  441 => '/**
 * @psalm-immutable
 */',
  442 => '/**
     * @psalm-param class-string $interfaceName
     *
     * @throws InvalidCodeUnitException
     * @throws ReflectionException
     */',
  443 => '/**
     * @psalm-param class-string $className
     *
     * @throws ReflectionException
     */',
  444 => '/**
     * @psalm-assert-if-true InterfaceMethod $this
     */',
  445 => '/**
     * @psalm-param class-string $className
     *
     * @throws ReflectionException
     */',
  446 => '/**
     * @throws ReflectionException
     */',
  447 => '/**
 * Compares doubles for equality.
 *
 * @deprecated since v3.0.5 and v4.0.8
 */',
  448 => '/**
     * @var Comparator[]
     */',
  449 => '/**
 * Compares Exception instances for equality.
 */',
  450 => '/**
     * Expected value of the retrieval which does not match $actual.
     *
     * @var mixed
     */',
  451 => '/**
     * @return string
     */',
  452 => '/**
 * Compares numerical values for equality.
 */',
  453 => '/**
     * Returns whether the comparator can compare two values.
     *
     * @param mixed $expected The first value to compare
     * @param mixed $actual   The second value to compare
     *
     * @return bool
     */',
  454 => '/**
     * @var bool
     */',
  455 => '/**
     * @var string
     */',
  456 => '/**
     * Returns true if line ends don\'t match in a diff.
     */',
  457 => '/**
     * Calculates the longest common subsequence of two arrays.
     */',
  458 => '/**
 * Defines how an output builder should take a generated
 * diff array and return a string representation of that diff.
 */',
  459 => '/**
     * @var bool
     */',
  460 => '/**
     * @var int
     */',
  461 => '/**
     * Returns true when Zend OPcache is loaded, enabled,
     * and is configured to discard comments.
     */',
  462 => '/**
 * A nifty utility for visualizing PHP variables.
 *
 * <code>
 * <?php
 * use SebastianBergmann\\Exporter\\Exporter;
 *
 * $exporter = new Exporter;
 * print $exporter->export(new Exception);
 * </code>
 */',
  463 => '/**
     * Restores a super-global variable array from this snapshot.
     */',
  464 => '/**
     * @var array
     */',
  465 => '/**
     * Creates a snapshot a super-global variable array.
     */',
  466 => '/**
     * @var string[]
     */',
  467 => '/**
     * @var int
     */',
  468 => '/**
     * @var SplObjectStorage
     */',
  469 => '/**
     * @psalm-var non-empty-string
     */',
  470 => '/**
     * @var bool
     */',
  471 => '/**
     * @throws RuntimeException
     */',
  472 => '/**
     * @psalm-assert-if-true MixedType $this
     */',
  473 => '/**
     * @var bool
     */',
  474 => '/**
     * @throws RuntimeException
     */',
  475 => '/**
     * @var string
     */',
  476 => '/**
     * @param int|string|array $kind
     */',
  477 => '/**
     * @return array
     */',
  478 => '/**
     * Enables fetching output and error output from the underlying process.
     *
     * @return $this
     *
     * @throws RuntimeException In case the process is already running
     */',
  479 => '/**
     * Returns a string representation for the exit code returned by the process.
     *
     * This method relies on the Unix exit code status standardization
     * and might not be relevant for other operating systems.
     *
     * @return string|null A string representation for the exit status code, null if the Process is not terminated
     *
     * @see http://tldp.org/LDP/abs/html/exitcodes.html
     * @see http://en.wikipedia.org/wiki/Unix_signal
     */',
  480 => '/**
     * Stops the process.
     *
     * @param int|float $timeout The timeout in seconds
     * @param int|null  $signal  A POSIX signal to send in case the process has not stop at timeout, default is SIGKILL (9)
     *
     * @return int|null The exit-code of the process or null if it\'s not running
     */',
  481 => '/**
     * Checks if the TTY mode is enabled.
     *
     * @return bool
     */',
  482 => '/**
     * @throws LogicException in case process is not started
     */',
  483 => '/**
     * Reads pipes, executes callback.
     *
     * @param bool $blocking Whether to use blocking calls or not
     * @param bool $close    Whether to close file handles or not
     */',
  484 => '/**
 * PipesInterface manages descriptors and pipes for the use of proc_open.
 *
 * @author Romain Neutron <imprec@gmail.com>
 *
 * @internal
 */',
  485 => '/**
     * {@inheritdoc}
     */',
  486 => '/**
 * @author Romain Neutron <imprec@gmail.com>
 *
 * @internal
 */',
  487 => '/**
     * Finds an executable by name.
     *
     * @param string      $name      The executable name (without the extension)
     * @param string|null $default   The default to return if no executable is found
     * @param array       $extraDirs Additional dirs to check into
     *
     * @return string|null
     */',
  488 => '/**
     * Validates and normalizes a Process input.
     *
     * @param string $caller The name of method call that validates the input
     * @param mixed  $input  The input to validate
     *
     * @return mixed
     *
     * @throws InvalidArgumentException In case the input is not valid
     */',
  489 => '/**
     * Tells whether the write buffer is closed or not.
     */',
  490 => '/** @var int */',
);
