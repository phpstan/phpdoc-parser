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


	public function parse(array $tokens): Ast\Node
	{
		$this->tokens = $tokens;
		$this->tokenIndex = 0;
		$this->tokenType = $tokens[0][Lexer::TYPE_OFFSET] ?? Lexer::TOKEN_OTHER;

		if ($this->tokenType === Lexer::TOKEN_WS) {
			$this->consume(Lexer::TOKEN_WS);
		}

		$type = $this->parseType();
//		$this->consume(Lexer::TOKEN_OTHER);
		$this->tokens = [];

		return $type;
	}


	private function parseType(): Ast\Node
	{
		if ($this->tokenType === Lexer::TOKEN_COMPLEMENT) {
			$type = $this->parseComplement();

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


	private function parseComplement(): Ast\Node
	{
		$this->consume(Lexer::TOKEN_COMPLEMENT);
		return new Ast\ComplementNode($this->parseAtomic());
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


	private function consume(int $tokenType): void
	{
		if ($this->tokenType === $tokenType) {
			do {
				$this->tokenType = $this->tokens[++$this->tokenIndex][Lexer::TYPE_OFFSET] ?? Lexer::TOKEN_OTHER;

			} while ($this->tokenType === Lexer::TOKEN_WS);

		} else {
			$this->error();
		}
	}


	private function value(): string
	{
		return $this->tokens[$this->tokenIndex][Lexer::VALUE_OFFSET] ?? 'END';
	}


	private function offset(): int
	{
		$offset = -1;
		for ($i = 0; $i <= $this->tokenIndex; $i++) {
			$offset += strlen($this->tokens[$i][Lexer::VALUE_OFFSET] ?? '');
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
