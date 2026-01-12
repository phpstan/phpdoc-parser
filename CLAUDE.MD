# CLAUDE.MD - Instructions for AI Assistant

This file contains important instructions for working on the phpdoc-parser project.

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
- `cs` - Code style checking with phpcs
- `tests` - PHPUnit test suite
- `phpstan` - Static analysis

## CRITICAL RULES

### ⚠️ MANDATORY: Run After Every Change

**You MUST run both tests and PHPStan after every code change:**

```bash
make tests && make phpstan
```

Or use the comprehensive check:
```bash
make check
```

**DO NOT** commit or consider work complete until both tests and PHPStan pass successfully.

### ⚠️ NEVER Delete Tests

**NEVER delete any tests.** Tests are critical to the project's quality and regression prevention. If tests are failing:
- Fix the implementation to make tests pass
- Only modify tests if they contain actual bugs or if requirements have legitimately changed
- When in doubt, ask before modifying any test

## Other Available Commands

- `make cs-fix` - Automatically fix code style issues
- `make lint` - Check PHP syntax only
- `make cs` - Check code style only
- `make phpstan-generate-baseline` - Generate PHPStan baseline (use sparingly)

## Workflow Summary

1. Make code changes
2. Run `make tests` - ensure all tests pass
3. Run `make phpstan` - ensure static analysis passes
4. Fix any issues found
5. Commit only when both pass
6. Repeat as needed

**Remember: Tests and PHPStan MUST pass before any commit.**
