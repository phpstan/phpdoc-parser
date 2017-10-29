<?php declare(strict_types = 1);

namespace PhpStan\TypeParser;


class TypeParser
{
	/** @var TokenIterator */
	private $tokens;

	/** @var int */
	private $tokenType;


	public function parser(TokenIterator $tokens): Ast\Type\TypeNode
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


	private function parseType(): Ast\Type\TypeNode
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


	private function parseAtomic(): Ast\Type\TypeNode
	{
		if ($this->tokenType === Lexer::TOKEN_OPEN_PARENTHESES) {
			$this->consume(Lexer::TOKEN_OPEN_PARENTHESES);
			$type = $this->parseType();
			$this->consume(Lexer::TOKEN_CLOSE_PARENTHESES);

			if ($this->tokenType === Lexer::TOKEN_OPEN_SQUARE_BRACKET) {
				$type = $this->parseArray($type);
			}

		} else {
			$type = new Ast\Type\IdentifierTypeNode($this->tokens->currentTokenValue());
			$this->consume(Lexer::TOKEN_IDENTIFIER);

			if ($this->tokenType === Lexer::TOKEN_OPEN_ANGLE_BRACKET) {
				$type = $this->parseGeneric($type);

			} elseif ($this->tokenType === Lexer::TOKEN_OPEN_SQUARE_BRACKET) {
				$type = $this->parseArray($type);
			}
		}

		return $type;
	}


	private function parseUnion(Ast\Type\TypeNode $type): Ast\Type\TypeNode
	{
		$types = [$type];

		do {
			$this->consume(Lexer::TOKEN_UNION);
			$types[] = $this->parseAtomic();

		} while ($this->tokenType === Lexer::TOKEN_UNION) ;

		return new Ast\Type\UnionTypeNode($types);
	}


	private function parseIntersection(Ast\Type\TypeNode $type): Ast\Type\TypeNode
	{
		$types = [$type];

		do {
			$this->consume(Lexer::TOKEN_INTERSECTION);
			$types[] = $this->parseAtomic();

		} while ($this->tokenType === Lexer::TOKEN_INTERSECTION) ;

		return new Ast\Type\IntersectionTypeNode($types);
	}


	private function parseNullable(): Ast\Type\TypeNode
	{
		$this->consume(Lexer::TOKEN_NULLABLE);

		$type = new Ast\Type\IdentifierTypeNode($this->tokens->currentTokenValue());
		$this->consume(Lexer::TOKEN_IDENTIFIER);

		if ($this->tokenType === Lexer::TOKEN_OPEN_ANGLE_BRACKET) {
			$type = $this->parseGeneric($type);
		}

		return new Ast\Type\NullableTypeNode($type);
	}


	private function parseGeneric(
		Ast\Type\IdentifierTypeNode $baseType): Ast\Type\TypeNode
	{
		$this->consume(Lexer::TOKEN_OPEN_ANGLE_BRACKET);
		$genericTypes[] = $this->parseType();

		while ($this->tokenType === Lexer::TOKEN_COMMA) {
			$this->consume(Lexer::TOKEN_COMMA);
			$genericTypes[] = $this->parseType();
		}

		$this->consume(Lexer::TOKEN_CLOSE_ANGLE_BRACKET);
		return new Ast\Type\GenericTypeNode($baseType, $genericTypes);
	}


	private function parseArray(Ast\Type\TypeNode $type): Ast\Type\TypeNode
	{
		do {
			$this->consume(Lexer::TOKEN_OPEN_SQUARE_BRACKET);
			$this->consume(Lexer::TOKEN_CLOSE_SQUARE_BRACKET);
			$type = new Ast\Type\ArrayTypeNode($type);

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
