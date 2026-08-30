# The grammars of the PHPDoc language

The files in this directory describe the language `phpstan/phpdoc-parser` reads,
in the [PP3 format](https://phplrt.org/docs/basics/grammar) the
[phplrt](https://phplrt.org) compiler reads. They are the specification of the
language, and they are what `tests/PHPStan/Parser/FuzzyTest.php` writes its
corpus from.

| File                | What it holds                                                   |
|---------------------|-----------------------------------------------------------------|
| `lexemes.pp3`       | Every token the language is read into                           |
| `common.pp3`        | What the grammars share: names, brackets, line breaks           |
| `types.pp3`         | The type language of `TypeParser`                               |
| `const-expr.pp3`    | The constant expressions of `ConstExprParser`                   |
| `phpdoc-block.pp3`  | The PHPDoc itself: its tags, its text, its Doctrine annotations |
| `type.pp3`          | The entry point starting at `Type`                              |
| `constant-expr.pp3` | The entry point starting at `ConstantExpr`                       |
| `phpdoc.pp3`        | The entry point starting at `PhpDoc`                             |

## What they are for

A grammar says what a PHPDoc may be written as, so it can be walked the other
way round and asked for PHPDocs instead of being asked about one:

    make grammars-install                  # the toolchain, which asks for PHP 8.4
    php vendor/bin/phpunit --filter FuzzyTest

`FuzzyTest` runs `tools/phplrt/fuzz.php` itself, once per grammar, every time it
runs: the tool compiles a grammar, walks its rules at random and writes down
what comes out, and the test then asks the parser to read every one of them in
full, and to read a type back as the very same type once it has been printed.
There is no corpus to keep, and none to refresh when a rule changes — what is
generated changes the moment the grammar is read again. The inputs of the last
run are left in `temp/fuzzy` to be looked at.

That is a great deal more of the language than a hand-written corpus covers, and
it is what replaced the `abnfgen`-driven fuzzer this project used before.

## What keeps them in step

The fuzzer walks the grammars one way; `tests/PHPStan/Parser/GrammarSyncTest.php`
walks them the other. It reads the inputs of every other test in this project —
by reflection over the providers they are written with, so the corpus grows on
its own as they do — and asks each grammar about them through
`tools/phplrt/recognize.php`:

- everything the parser reads in full and without complaint, the grammar has to
  recognize;
- everything the parser stops at or turns into an `Invalid...` node, the grammar
  has to turn down.

So a feature added to the parser and written down as a test is one the grammars
have to describe by the next run, and a rule written too widely is one the
fuzzer walks into. Only reading is asked of the grammar there: nothing is built
out of what it reads, so there is no second implementation of the AST to keep in
step — only the language itself.

The few inputs the two are known to disagree about are listed in that test with
the reason why, and the list has to be exactly right: an input that starts
agreeing has to be taken off it.

Nothing in `src/` reads these files, and the library needs neither the toolchain
nor PHP 8.4: where either is missing, `FuzzyTest` skips itself. Only the
`Grammars` job of `.github/workflows/build.yml` runs it for real.

## How they are written

The rules are named after the methods of `PhpDocParser`, `TypeParser` and
`ConstExprParser` they stand for and are written in the order those methods try
things in, so that a grammar and the parser it describes can be read side by
side.

### What the grammars describe

**A PHPDoc that is written correctly.** The parser reads a broken one as well,
by turning whatever it cannot read into an `InvalidTagValueNode` carrying the
very error it has raised, and a grammar has no way of writing that error down.
So what a broken PHPDoc means is left to the parser, and everything the grammars
describe is something the parser has to read in full.

Two things follow from wanting that to hold for **every** input rather than for
most of them:

- **A place the parser raises an error at is written as something the grammar
  cannot recognize.** Most of them are written as a `!` predicate forbidding
  whatever the error would have been raised on. For instance a name followed by
  a `<` has to go on into a generic type or into a callable, because `Foo<` is
  an error rather than the type `Foo` followed by something else:

  ```
  IdentifierAtomic
    : ...
    | !ShapeBrace() Identifier() !<T_DOUBLE_COLON> ( IdentifierSuffix() | !<T_OPEN_ANGLE_BRACKET> )
    ;
  ```

  The same predicate is what keeps a rule from **giving back** what it has read.
  `@template T of` is an error rather than a template named `T` with the
  description `of`, so the bound is written as "either a bound or no `of` at
  all":

  ```
  TemplateUpperBound
    : <T_KEYWORD_OF> Type()
    | <T_KEYWORD_AS> Type()
    | !<T_KEYWORD_OF> !<T_KEYWORD_AS>
    ;
  ```

- **A rule reads exactly the tokens its method reads**, down to the line breaks
  around it.

### The tokens are not read by phplrt

A grammar of this directory is not read by the lexer it declares: it is read by
the very tokens `PHPStan\PhpDocParser\Lexer\Lexer` produces, handed over by
`tools/phplrt/Fuzzer/TokenStream.php`.

The `%token` declarations therefore name the tokens and document the language
without being what reads it. Some of them describe something the lexer never
reads as a token of its own, and `TokenStream` is what tells those apart in the
stream:

- a word the parser compares by value (`is`, `array`, `covariant`, `static`, …)
  — every one of them is still an ordinary name as well, which is why they are
  all listed among the alternatives of `Identifier`;
- a tag whose value a rule of its own reads (`@param`, `@return`, …), told apart
  from the tags nothing reads the value of;
- a bracket or an asterisk whose neighbouring whitespace decides what it means,
  which is what tells `array{a: int}` from the type `array` followed by a brace,
  and `Foo[0]` from `Foo [0]`;
- a tag a space is written before, which is what tells the `@since` of
  `@author Foo @since 1.0` from the `@baz` of `@author Foo <foo@baz.com>`;
- a `<` opening what the parser recognizes as an HTML tag, so that
  `@return Foo<br>see below</br>` keeps meaning the type `Foo` followed by a
  description.

Telling them apart there is what lets the grammars be written without semantic
predicates, which the PP3 format has none of.

### What is left out

Three corners of the language are left out on purpose, because a grammar cannot
say what the parser does there. Each of them is written up where the rule that
skirts it is written:

- a description that ends at a tag written in the middle of a line, which the
  parser decides by reading the tag and looking at what it turns out to be;
- the same, on a line after the first, where the parser reads that line twice:
  once as part of the description and again as whatever comes next;
- a tag whose value a rule reads, written with a parenthesis after it, where
  whether the description ends there depends on whether that value can be read
  at all.
