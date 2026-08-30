<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast\AbstractNodeVisitor;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\PhpDoc\InvalidTagValueNode;
use PHPStan\PhpDocParser\Ast\ToString\PhpDocToStringTest;
use PHPStan\PhpDocParser\Ast\Type\InvalidTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use PHPStan\PhpDocParser\Printer\PrinterTest;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Process\Process;
use Throwable;
use function array_diff;
use function array_map;
use function array_values;
use function count;
use function file_get_contents;
use function file_put_contents;
use function implode;
use function in_array;
use function is_array;
use function is_dir;
use function is_file;
use function is_iterable;
use function is_string;
use function json_decode;
use function json_encode;
use function mkdir;
use function preg_match;
use function sprintf;
use function str_replace;
use function strlen;
use function strpos;
use function substr;
use const JSON_THROW_ON_ERROR;
use const PHP_BINARY;
use const PHP_EOL;
use const PHP_VERSION_ID;

/**
 * Holds the grammars in "doc/grammars" to what the parser already reads.
 *
 * "FuzzyTest" asks the parser to read what a grammar writes; this asks a
 * grammar about what the parser reads, over the inputs of every other test in
 * this project. The two together are what keeps the grammars from drifting: a
 * feature added to the parser and written down as a test is one the grammars
 * have to describe by the next run, and a rule written too widely is one the
 * fuzzer walks into.
 *
 * Only reading is asked of the grammar. Nothing is built out of what it reads,
 * so there is no second implementation of the AST to keep in step.
 *
 * The corpus is read out of the other tests by reflection rather than written
 * down again here, so it grows on its own as they do.
 */
class GrammarSyncTest extends TestCase
{

	/**
	 * The oldest PHP the grammar compiler runs on.
	 */
	private const REQUIRED_PHP_VERSION = 80400;

	/**
	 * The inputs the grammars are known to say something else about than the
	 * parser does, and why.
	 *
	 * Every one of them is a place where the parser looks at whether a space is
	 * written between two tokens, which a grammar reading one token at a time
	 * cannot say. Here the type is the generic type "Foo<strong>", read as one
	 * because nothing closes the "<strong>" again, and the description that
	 * follows begins against it rather than after a space, which is what the
	 * parser turns the tag down for.
	 *
	 * The list is required to be exactly right: an input that starts agreeing
	 * has to be taken off it, the same way a new disagreement has to be fixed.
	 *
	 */
	private const KNOWN_DISAGREEMENTS = [
		'phpdoc.pp3' => [
			"/**\n * @return Foo <strong>Important description\n */",
		],
	];

	private Lexer $lexer;

	private TypeParser $typeParser;

	private ConstExprParser $constExprParser;

	private PhpDocParser $phpDocParser;

	protected function setUp(): void
	{
		parent::setUp();
		$config = new ParserConfig([]);
		$this->lexer = new Lexer($config);
		$this->constExprParser = new ConstExprParser($config);
		$this->typeParser = new TypeParser($config, $this->constExprParser);
		$this->phpDocParser = new PhpDocParser($config, $this->typeParser, $this->constExprParser);
	}

	public function testTypeGrammarReadsWhatTypeParserReads(): void
	{
		$this->assertGrammarAgrees(
			'type.pp3',
			$this->corpusOf([TypeParserTest::class], false),
			fn (TokenIterator $tokens): Node => $this->typeParser->parse($tokens),
		);
	}

	public function testConstantExprGrammarReadsWhatConstExprParserReads(): void
	{
		$this->assertGrammarAgrees(
			'constant-expr.pp3',
			$this->corpusOf([ConstExprParserTest::class], false),
			fn (TokenIterator $tokens): Node => $this->constExprParser->parse($tokens),
		);
	}

	public function testPhpDocGrammarReadsWhatPhpDocParserReads(): void
	{
		$this->assertGrammarAgrees(
			'phpdoc.pp3',
			$this->corpusOf([PhpDocParserTest::class, PrinterTest::class, PhpDocToStringTest::class], true),
			fn (TokenIterator $tokens): Node => $this->phpDocParser->parse($tokens),
		);
	}

	/**
	 * Asks the grammar about every input of the corpus, and requires it to say
	 * what the parser says: everything the parser reads in full and without
	 * complaint the grammar has to recognize, and everything it stops at or
	 * turns into an "Invalid..." node the grammar has to turn down.
	 *
	 * @param list<string> $corpus
	 * @param callable(TokenIterator): Node $parse
	 */
	private function assertGrammarAgrees(string $grammar, array $corpus, callable $parse): void
	{
		$verdicts = $this->askTheGrammar($grammar, $corpus);

		if ($verdicts === null) {
			self::markTestSkipped('The grammars need PHP 8.4 and the toolchain of "make grammars-install"');
		}

		$known = self::KNOWN_DISAGREEMENTS[$grammar] ?? [];
		$disagreements = [];
		$unexpected = [];

		foreach ($corpus as $index => $input) {
			$readable = $this->isReadInFull($input, $parse);

			if ($readable === $verdicts[$index]) {
				continue;
			}

			$disagreements[] = $input;

			if (in_array($input, $known, true)) {
				continue;
			}

			$unexpected[] = sprintf(
				'%s %s',
				$readable ? 'the grammar turned down what the parser reads: ' : 'the grammar recognized what the parser does not read:',
				self::shorten($input),
			);
		}

		self::assertSame([], $unexpected, sprintf(
			'The grammar in "doc/grammars/%s" and the parser disagree about %d of %d inputs. Either the grammar has to be'
				. ' written to say what the parser says, or the input has to be listed among the known disagreements with'
				. ' the reason why it cannot be:%s%s',
			$grammar,
			count($unexpected),
			count($corpus),
			PHP_EOL,
			implode(PHP_EOL, $unexpected),
		));

		$settled = array_values(array_diff($known, $disagreements));

		self::assertSame([], array_map([self::class, 'shorten'], $settled), sprintf(
			'The grammar in "doc/grammars/%s" and the parser agree about inputs that are still listed as known'
				. ' disagreements. Take them off the list.',
			$grammar,
		));
	}

	/**
	 * Whether the parser reads the whole input and is happy with all of it.
	 *
	 * @param callable(TokenIterator): Node $parse
	 */
	private function isReadInFull(string $input, callable $parse): bool
	{
		try {
			$tokens = new TokenIterator($this->lexer->tokenize($input));
			$node = $parse($tokens);
		} catch (Throwable $e) {
			return false;
		}

		if ($tokens->currentTokenType() !== Lexer::TOKEN_END) {
			return false;
		}

		$visitor = new class extends AbstractNodeVisitor {

			public bool $invalid = false;

			public function enterNode(Node $node)
			{
				if ($node instanceof InvalidTagValueNode || $node instanceof InvalidTypeNode) {
					$this->invalid = true;
				}

				return $node;
			}

		};

		$traverser = new NodeTraverser([$visitor]);
		$traverser->traverse([$node]);

		return !$visitor->invalid;
	}

	/**
	 * Runs the grammar over the corpus, in a process of its own because the
	 * compiler reading it has a toolchain of its own.
	 *
	 * @param list<string> $corpus
	 * @return list<bool>|null "null" where the toolchain is not there
	 */
	private function askTheGrammar(string $grammar, array $corpus): ?array
	{
		$root = __DIR__ . '/../../..';

		if (
			PHP_VERSION_ID < self::REQUIRED_PHP_VERSION
			|| !is_file(sprintf('%s/tools/phplrt/vendor/autoload.php', $root))
		) {
			return null;
		}

		$directory = sprintf('%s/temp/grammar-sync', $root);

		if (!is_dir($directory) && !mkdir($directory, 0777, true)) {
			self::fail(sprintf('Cannot create %s', $directory));
		}

		$inputs = sprintf('%s/%s.inputs.json', $directory, $grammar);
		$verdicts = sprintf('%s/%s.verdicts.json', $directory, $grammar);
		// Not every input of this project's own corpus is valid UTF-8, so they
		// travel encoded rather than as the text they are
		file_put_contents($inputs, json_encode(array_map('base64_encode', $corpus), JSON_THROW_ON_ERROR));

		$process = new Process([
			PHP_BINARY,
			sprintf('%s/tools/phplrt/recognize.php', $root),
			sprintf('%s/doc/grammars/%s', $root, $grammar),
			$inputs,
			$verdicts,
		]);
		$process->mustRun();

		return json_decode((string) file_get_contents($verdicts), true, 512, JSON_THROW_ON_ERROR);
	}

	/**
	 * Reads the inputs of another test out of the providers it is written with,
	 * so that what is tested here grows with what is tested there.
	 *
	 * @param list<class-string<TestCase>> $tests
	 * @param bool $phpDocs whether the inputs are whole PHPDocs, which the
	 *        providers write after a label rather than first
	 * @return list<string>
	 */
	private function corpusOf(array $tests, bool $phpDocs): array
	{
		$corpus = [];

		foreach ($tests as $test) {
			$reflection = new ReflectionClass($test);
			$instance = $reflection->newInstance();

			foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				if (
					preg_match('~^(provide|data)~', $method->getName()) !== 1
					|| $method->getNumberOfRequiredParameters() > 0
					|| $method->getDeclaringClass()->getName() !== $test
				) {
					continue;
				}

				$rows = $method->invoke($instance);

				if (!is_iterable($rows)) {
					continue;
				}

				foreach ($rows as $row) {
					if (!is_array($row)) {
						continue;
					}

					$input = self::inputOf($row, $phpDocs);

					if ($input === null) {
						continue;
					}

					$corpus[$input] = $input;
				}
			}
		}

		return array_values($corpus);
	}

	/**
	 * @param array<mixed> $row
	 */
	private static function inputOf(array $row, bool $phpDocs): ?string
	{
		foreach ($row as $value) {
			if (!is_string($value)) {
				continue;
			}

			// A whole PHPDoc is written after the label its provider names it
			// with, and everything else is written first
			if (!$phpDocs) {
				return $value;
			}

			if (strpos($value, '/**') === 0) {
				return $value;
			}
		}

		return null;
	}

	private static function shorten(string $input): string
	{
		$printed = str_replace(["\n", "\t"], ['\n', '\t'], $input);

		return strlen($printed) > 120 ? substr($printed, 0, 117) . '...' : $printed;
	}

}
