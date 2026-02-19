# CLAUDE.MD - Instructions for AI Assistant

This file contains important instructions for working on the phpdoc-parser project.

## Project Overview

**phpstan/phpdoc-parser** is a library that represents PHPDocs with an AST (Abstract Syntax Tree). It supports parsing and modifying PHPDocs, and is primarily used by PHPStan for static analysis.

* [PHPDoc Basics](https://phpstan.org/writing-php-code/phpdocs-basics) (list of PHPDoc tags)
* [PHPDoc Types](https://phpstan.org/writing-php-code/phpdoc-types) (list of PHPDoc types)
* [phpdoc-parser API Reference](https://phpstan.github.io/phpdoc-parser/2.3.x/namespace-PHPStan.PhpDocParser.html) with all the AST node types

### Key Features
- Parses PHPDoc comments into an AST representation
- Supports all PHPDoc tags and types
- Format-preserving printer for modifying and printing AST nodes (inspired by [nikic/PHP-Parser](https://github.com/nikic/PHP-Parser))
- Support for Doctrine Annotations parsing
- Nullable, intersection, generic, and conditional types support

### Requirements
- PHP ^7.4 || ^8.0
- Platform target: PHP 7.4.6 (set in composer.json config)

Note: While phpstan-src is on PHP 8.1+ thanks to PHAR build downgrade, this repository still supports PHP 7.4+.

## Project Structure

### Source Code (`src/`)

The source code is organized into the following main components:

1. **Lexer** (`src/Lexer/`)
   - `Lexer.php` - Tokenizes PHPDoc strings into tokens

2. **Parser** (`src/Parser/`)
   - `PhpDocParser.php` - Main PHPDoc parser (parses tags and structure)
   - `TypeParser.php` - Parses PHPDoc type expressions
   - `ConstExprParser.php` - Parses constant expressions
   - `TokenIterator.php` - Iterator for consuming tokens
   - `StringUnescaper.php` - Handles string unescaping
   - `ParserException.php` - Exception handling for parse errors

3. **AST** (`src/Ast/`)
   - `Node.php` - Base AST node interface
   - `NodeAttributes.php` - Trait for node attributes (lines, indexes, comments)
   - `NodeTraverser.php` - Traverses and transforms AST trees
   - `NodeVisitor.php` - Visitor interface for AST traversal
   - `AbstractNodeVisitor.php` - Abstract base class for node visitors
   - `Attribute.php` - Attribute constants (START_LINE, END_LINE, START_INDEX, END_INDEX, ORIGINAL_NODE, COMMENTS)
   - `Comment.php` - Represents a comment within a PHPDoc
   - `Type/` - Type nodes (GenericTypeNode, ArrayTypeNode, UnionTypeNode, IntersectionTypeNode, CallableTypeNode, ConditionalTypeNode, ArrayShapeNode, ObjectShapeNode, OffsetAccessTypeNode, etc.)
   - `PhpDoc/` - PHPDoc tag nodes (ParamTagValueNode, ReturnTagValueNode, VarTagValueNode, ThrowsTagValueNode, MethodTagValueNode, PropertyTagValueNode, TemplateTagValueNode, ExtendsTagValueNode, ImplementsTagValueNode, AssertTagValueNode, TypeAliasTagValueNode, SealedTagValueNode, etc.)
   - `PhpDoc/Doctrine/` - Doctrine annotation AST nodes (DoctrineAnnotation, DoctrineArgument, DoctrineArray, DoctrineArrayItem, DoctrineTagValueNode)
   - `ConstExpr/` - Constant expression nodes (ConstExprIntegerNode, ConstExprStringNode, ConstExprArrayNode, ConstFetchNode, etc.)
   - `NodeVisitor/` - Built-in visitors (CloningVisitor)

4. **Printer** (`src/Printer/`)
   - `Printer.php` - Prints AST back to PHPDoc format (supports format-preserving printing)
   - `Differ.php` - Computes differences between AST node lists
   - `DiffElem.php` - Represents diff elements (keep, remove, add, replace)

5. **Configuration**
   - `ParserConfig.php` - Parser configuration (used attributes: lines, indexes, comments)

### Tests (`tests/PHPStan/`)

Tests mirror the source structure and include:

1. **Parser Tests** (`tests/PHPStan/Parser/`)
   - `TypeParserTest.php` - Type parsing tests
   - `PhpDocParserTest.php` - PHPDoc parsing tests
   - `ConstExprParserTest.php` - Constant expression parsing tests
   - `TokenIteratorTest.php` - Token iterator tests
   - `FuzzyTest.php` - Fuzzy testing
   - `Doctrine/` - Doctrine annotation test fixtures

2. **AST Tests** (`tests/PHPStan/Ast/`)
   - `NodeTraverserTest.php` - Node traversal tests
   - `Attributes/` - AST attribute tests
   - `ToString/` - Tests for converting AST to string
   - `NodeVisitor/` - Visitor pattern tests

3. **Printer Tests** (`tests/PHPStan/Printer/`)
   - `PrinterTest.php` - Printer unit tests
   - `DifferTest.php` - Differ algorithm tests
   - `IntegrationPrinterWithPhpParserTest.php` - Integration tests with nikic/php-parser

### Configuration Files

- `phpunit.xml` - PHPUnit test configuration
- `phpstan.neon` - PHPStan static analysis configuration (level 8)
- `phpstan-baseline.neon` - PHPStan baseline (known issues)
- `phpcs.xml` - PHP CodeSniffer configuration (references `build-cs/phpcs.xml`)
- `composer.json` - Dependencies and autoloading

## How the Parser Works

The parsing flow follows these steps:

1. **Lexing**: `Lexer` tokenizes the PHPDoc string into tokens
2. **Parsing**: `PhpDocParser` uses `TypeParser` and `ConstExprParser` to build an AST
3. **Traversal/Modification**: `NodeTraverser` with `NodeVisitor` can traverse and modify the AST
4. **Printing**: `Printer` converts the AST back to PHPDoc format (optionally preserving formatting)

### Basic Usage Example

```php
$config = new ParserConfig(usedAttributes: []);
$lexer = new Lexer($config);
$constExprParser = new ConstExprParser($config);
$typeParser = new TypeParser($config, $constExprParser);
$phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);

$tokens = new TokenIterator($lexer->tokenize('/** @param Lorem $a */'));
$phpDocNode = $phpDocParser->parse($tokens);
```

### Format-Preserving Printing

For format-preserving printing (used when modifying existing PHPDocs), enable these attributes:
- `lines` - Preserve line information
- `indexes` - Preserve token indexes
- `comments` - Preserve comments

```php
$config = new ParserConfig(usedAttributes: ['lines' => true, 'indexes' => true, 'comments' => true]);
// ... setup lexer, parsers as above ...

$cloningTraverser = new NodeTraverser([new CloningVisitor()]);
[$newPhpDocNode] = $cloningTraverser->traverse([$phpDocNode]);

// modify $newPhpDocNode...

$printer = new Printer();
$newPhpDoc = $printer->printFormatPreserving($newPhpDocNode, $phpDocNode, $tokens);
```

## Initial Setup

```bash
composer install
make cs-install
```

The `make cs-install` step clones the [phpstan/build-cs](https://github.com/phpstan/build-cs) repository and installs its dependencies. This is required before running code style checks (`make cs` or `make cs-fix`).

## Common Development Tasks

### Adding a New PHPDoc Tag
1. Create a new `*TagValueNode` class in `src/Ast/PhpDoc/`
2. Add parsing logic in `PhpDocParser.php`
3. Add tests in `tests/PHPStan/Parser/PhpDocParserTest.php`
4. Run tests and PHPStan

### Adding a New Type Node
1. Create a new `*TypeNode` class in `src/Ast/Type/`
2. Add parsing logic in `TypeParser.php`
3. Add printing logic in `Printer.php`
4. Add tests in `tests/PHPStan/Parser/TypeParserTest.php`
5. Run tests and PHPStan

### Modifying the Lexer
1. Update token generation in `Lexer.php`
2. Update parsers that consume those tokens
3. Add/update tests
4. Run comprehensive checks with `make check`

## Testing and Quality Checks

### Running Tests

Tests are run using PHPUnit:
```bash
make tests
```

Or directly:
```bash
php vendor/bin/phpunit
```

### Running PHPStan

PHPStan static analysis is run with:
```bash
make phpstan
```

Or directly:
```bash
php vendor/bin/phpstan
```

### Running All Checks

To run all quality checks (lint, code style, tests, and PHPStan):
```bash
make check
```

This runs:
- `lint` - PHP syntax checking with parallel-lint
- `cs` - Code style checking with phpcs (requires `make cs-install` first)
- `tests` - PHPUnit test suite
- `phpstan` - Static analysis

## CRITICAL RULES

### MANDATORY: Run After Every Change

**You MUST run both tests and PHPStan after every code change:**

```bash
make tests && make phpstan
```

Or use the comprehensive check:
```bash
make check
```

**DO NOT** commit or consider work complete until both tests and PHPStan pass successfully.

### NEVER Delete Tests

**NEVER delete any tests.** Tests are critical to the project's quality and regression prevention. If tests are failing:
- Fix the implementation to make tests pass
- Only modify tests if they contain actual bugs or if requirements have legitimately changed
- When in doubt, ask before modifying any test

## Other Available Commands

- `make cs-install` - Clone and install phpstan/build-cs (required before `make cs` or `make cs-fix`)
- `make cs-fix` - Automatically fix code style issues
- `make lint` - Check PHP syntax only
- `make cs` - Check code style only
- `make phpstan-generate-baseline` - Generate PHPStan baseline (use sparingly)

## Workflow Summary

1. Run `composer install` (initial setup)
2. Run `make cs-install` (initial setup, required for code style checks)
3. Make code changes
4. Run `make tests` - ensure all tests pass
5. Run `make phpstan` - ensure static analysis passes
6. Fix any issues found
7. Commit only when both pass

**Remember: Tests and PHPStan MUST pass before any commit.**

## Coding Standards and Best Practices

### Code Style
- Code style is enforced by phpcs using rules from [phpstan/build-cs](https://github.com/phpstan/build-cs)
- Uses tabs for indentation (tab-width 4)
- Run `make cs-fix` to automatically fix code style issues
- Always run `make cs` to verify code style before committing

### PHPStan Rules
- Project uses PHPStan level 8
- All code must pass static analysis
- Avoid adding to phpstan-baseline.neon unless absolutely necessary
- Type hints are required for all public APIs

### Testing Best Practices
- All new features must include tests
- Tests should be in the corresponding test directory matching src/ structure
- Use data providers for testing multiple similar cases
- Test both valid and invalid inputs
- Include edge cases and error conditions

### AST Node Conventions
- All AST nodes implement the `Node` interface
- Nodes should be immutable where possible
- Use `__toString()` for debugging output
- Follow the visitor pattern for AST traversal

### Parser Patterns
- Parsers should be recursive descent style
- Use `TokenIterator` for token consumption
- Throw `ParserException` for syntax errors
- Support optional attributes through `ParserConfig`
- Maintain backwards compatibility when adding features

## Important Notes

### Backwards Compatibility
- This library is used by PHPStan and many other tools
- Breaking changes should be avoided
- New features should be opt-in when possible
- Deprecate before removing functionality

### Performance Considerations
- The parser is performance-critical (runs on large codebases)
- Avoid unnecessary object allocations
- Be careful with regex patterns
- Consider memory usage in loops
