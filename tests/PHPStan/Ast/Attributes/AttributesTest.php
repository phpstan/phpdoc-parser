<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\Attributes;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPUnit\Framework\TestCase;

final class AttributesTest extends TestCase
{

	/**
	 * @var Lexer
	 */
	private $lexer;

	/**
	 * @var PhpDocParser
	 */
	private $phpDocParser;

	protected function setUp(): void
	{
		parent::setUp();
		$this->lexer = new Lexer();
		$constExprParser = new ConstExprParser();
		$this->phpDocParser = new PhpDocParser(new TypeParser($constExprParser), $constExprParser);
	}

}
