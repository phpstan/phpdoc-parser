<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Attributes;

use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use PHPUnit\Framework\TestCase;

final class AttributesTest extends TestCase
{

	private PhpDocNode $phpDocNode;

	protected function setUp(): void
	{
		parent::setUp();

		$config = new ParserConfig([]);
		$lexer = new Lexer($config);
		$constExprParser = new ConstExprParser($config);
		$phpDocParser = new PhpDocParser($config, new TypeParser($config, $constExprParser), $constExprParser);

		$input = '/** @var string */';
		$tokens = new TokenIterator($lexer->tokenize($input));
		$this->phpDocNode = $phpDocParser->parse($tokens);
	}

	public function testGetAttribute(): void
	{
		$unKnownValue = $this->phpDocNode->getAttribute('unknown');
		$this->assertNull($unKnownValue);
	}

	public function testSetAttribute(): void
	{
		$this->phpDocNode->setAttribute('key', 'value');

		$attributeValue = $this->phpDocNode->getAttribute('key');
		$this->assertSame('value', $attributeValue);
	}

}
