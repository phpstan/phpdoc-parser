<?php declare(strict_types = 1);

namespace PhpStan\TypeParser;


class TypeParser
{
	/** @var TokenIterator */
	private $tokens;

	/** @var int */
	private $tokenType;


	public function parser(TokenIterator $tokens): Ast\Node
	{
		try {
			$this->setUp($tokens);
			return $this->parseType();

		} finally {
			$this->tearDown();
		}
	}


	private function setUp(TokenIterator $tokens): void
	{
		if ($tokens->isCurrentTokenType(Lexer::TOKEN_WS)) {
			$tokens->next();
		}

		$this->tokens = $tokens;
		$this->tokenType = $tokens->currentTokenType();
	}


	private function tearDown(): void
	{
		$this->tokens = null; // release memory
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
			$type = new Ast\IdentifierNode($this->tokens->currentTokenValue());
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

		$type = new Ast\IdentifierNode($this->tokens->currentTokenValue());
		$this->consume(Lexer::TOKEN_IDENTIFIER);

		if ($this->tokenType === Lexer::TOKEN_OPEN_ANGLE_BRACKET) {
			$type = $this->parseGeneric($type);
		}

		return new Ast\NullableNode($type);
	}


	private function parseGeneric(Ast\IdentifierNode $baseType): Ast\Node
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

		$this->tokens->next();

		if ($this->tokens->isCurrentTokenType(Lexer::TOKEN_WS)) {
			$this->tokens->next();
		}

		$this->tokenType = $this->tokens->currentTokenType();
	}


	private function error(): void
	{
		throw new ParserException(sprintf(
			'Unexpected token \'%s\' at offset %d',
			$this->tokens->currentTokenValue(),
			$this->tokens->currentTokenOffset()
		));
	}
}
