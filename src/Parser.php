<?php declare(strict_types = 1);

namespace PhpStan\TypeParser;

class Parser
{
	/** @var array */
	private $tokens;

	/** @var int */
	private $tokenIndex;

	/** @var int */
	private $tokenType;


	public function parseUntilEnd(array $tokens): Ast\Node
	{
		try {
			$this->setUp($tokens);
			$type = $this->parseType();
			$this->consumeEnd();

		} finally {
			$this->tearDown();
		}

		return $type;
	}


	public function parseUntilBoundary(array $tokens): Ast\Node
	{
		try {
			$this->setUp($tokens);
			$type = $this->parseType();
			$this->consumeBoundary();

		} finally {
			$this->tearDown();
		}

		return $type;
	}


	private function setUp(array $tokens): void
	{
		$this->tokens = $tokens;

		if ($tokens[0][Lexer::TYPE_OFFSET] !== Lexer::TOKEN_WS) {
			$this->tokenIndex = 0;
			$this->tokenType = $tokens[0][Lexer::TYPE_OFFSET];

		} else {
			$this->tokenIndex = 1;
			$this->tokenType = $tokens[1][Lexer::TYPE_OFFSET];
		}
	}


	private function tearDown(): void
	{
		$this->tokens = []; // release memory
	}


	private function parseType(): Ast\Node
	{
		if ($this->tokenType === Lexer::TOKEN_NULLABLE) {
			$type = $this->parseNullable();

		} else {
			$type = $this->parseAtomic();

			if ($this->tokenType === Lexer::TOKEN_UNION) {
				$type = $this->parseUnion($type);

			} elseif ($this->tokenType === Lexer::TOKEN_INTERSECTION) {
				$type = $this->parseIntersection($type);
			}
		}

		return $type;
	}


	private function parseAtomic(): Ast\Node
	{
		if ($this->tokenType === Lexer::TOKEN_OPEN_PARENTHESES) {
			$this->consume(Lexer::TOKEN_OPEN_PARENTHESES);
			$type = $this->parseType();
			$this->consume(Lexer::TOKEN_CLOSE_PARENTHESES);

			if ($this->tokenType === Lexer::TOKEN_OPEN_SQUARE_BRACKET) {
				$type = $this->parseArray($type);
			}

		} else {
			$type = new Ast\SimpleNode($this->value());
			$this->consume(Lexer::TOKEN_IDENTIFIER);

			if ($this->tokenType === Lexer::TOKEN_OPEN_ANGLE_BRACKET) {
				$type = $this->parseGeneric($type);

			} elseif ($this->tokenType === Lexer::TOKEN_OPEN_SQUARE_BRACKET) {
				$type = $this->parseArray($type);
			}
		}

		return $type;
	}


	private function parseUnion(Ast\Node $type): Ast\Node
	{
		$types = [$type];

		do {
			$this->consume(Lexer::TOKEN_UNION);
			$types[] = $this->parseAtomic();

		} while ($this->tokenType === Lexer::TOKEN_UNION) ;

		return new Ast\UnionNode($types);
	}


	private function parseIntersection(Ast\Node $type): Ast\Node
	{
		$types = [$type];

		do {
			$this->consume(Lexer::TOKEN_INTERSECTION);
			$types[] = $this->parseAtomic();

		} while ($this->tokenType === Lexer::TOKEN_INTERSECTION) ;

		return new Ast\IntersectionNode($types);
	}


	private function parseNullable(): Ast\Node
	{
		$this->consume(Lexer::TOKEN_NULLABLE);

		$type = new Ast\SimpleNode($this->value());
		$this->consume(Lexer::TOKEN_IDENTIFIER);

		if ($this->tokenType === Lexer::TOKEN_OPEN_ANGLE_BRACKET) {
			$type = $this->parseGeneric($type);
		}

		return new Ast\NullableNode($type);
	}


	private function parseGeneric(Ast\SimpleNode $baseType): Ast\Node
	{
		$this->consume(Lexer::TOKEN_OPEN_ANGLE_BRACKET);
		$genericTypes[] = $this->parseType();

		while ($this->tokenType === Lexer::TOKEN_COMMA) {
			$this->consume(Lexer::TOKEN_COMMA);
			$genericTypes[] = $this->parseType();
		}

		$this->consume(Lexer::TOKEN_CLOSE_ANGLE_BRACKET);
		return new Ast\GenericNode($baseType, $genericTypes);
	}


	private function parseArray(Ast\Node $type): Ast\Node
	{
		do {
			$this->consume(Lexer::TOKEN_OPEN_SQUARE_BRACKET);
			$this->consume(Lexer::TOKEN_CLOSE_SQUARE_BRACKET);
			$type = new Ast\ArrayNode($type);

		} while ($this->tokenType === Lexer::TOKEN_OPEN_SQUARE_BRACKET);

		return $type;
	}


	private function consume(int $consumedTokenType): void
	{
		if ($this->tokenType !== $consumedTokenType) {
			$this->error();
		}

		$this->tokenIndex++;

		if ($this->tokens[$this->tokenIndex][Lexer::TYPE_OFFSET] === Lexer::TOKEN_WS) {
			$this->tokenIndex++;
		}

		$this->tokenType = $this->tokens[$this->tokenIndex][Lexer::TYPE_OFFSET];
	}


	private function consumeEnd(): void
	{
		if ($this->tokenType !== Lexer::TOKEN_END) {
			$this->error();
		}
	}


	private function consumeBoundary(): void
	{
		if ($this->tokenType !== Lexer::TOKEN_END && $this->tokens[$this->tokenIndex - 1][Lexer::TYPE_OFFSET] !== Lexer::TOKEN_WS) {
			$this->error();
		}
	}


	private function value(): string
	{
		return $this->tokens[$this->tokenIndex][Lexer::VALUE_OFFSET];
	}


	private function offset(): int
	{
		$offset = 0;
		for ($i = 0; $i < $this->tokenIndex; $i++) {
			$offset += strlen($this->tokens[$i][Lexer::VALUE_OFFSET]);
		}

		return $offset;
	}


	private function error(): void
	{
		throw new ParserException(sprintf(
			'Unexpected token \'%s\' at offset %d',
			$this->value(),
			$this->offset()
		));
	}
}
