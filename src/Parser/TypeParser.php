<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast;
use PHPStan\PhpDocParser\Lexer\Lexer;

class TypeParser
{

	public function parse(TokenIterator $tokens): Ast\Type\TypeNode
	{
		if ($tokens->isCurrentTokenType(Lexer::TOKEN_NULLABLE)) {
			$type = $this->parseNullable($tokens);

		} else {
			$type = $this->parseAtomic($tokens);

			if ($tokens->isCurrentTokenType(Lexer::TOKEN_UNION)) {
				$type = $this->parseUnion($tokens, $type);

			} elseif ($tokens->isCurrentTokenType(Lexer::TOKEN_INTERSECTION)) {
				$type = $this->parseIntersection($tokens, $type);
			}
		}

		return $type;
	}


	private function parseAtomic(TokenIterator $tokens): Ast\Type\TypeNode
	{
		if ($tokens->tryConsumeTokenType(Lexer::TOKEN_OPEN_PARENTHESES)) {
			$type = $this->parse($tokens);
			$tokens->consumeTokenType(Lexer::TOKEN_CLOSE_PARENTHESES);

			if ($tokens->isCurrentTokenType(Lexer::TOKEN_OPEN_SQUARE_BRACKET)) {
				$type = $this->tryParseArray($tokens, $type);
			}

		} elseif ($tokens->tryConsumeTokenType(Lexer::TOKEN_THIS_VARIABLE)) {
			return new Ast\Type\ThisTypeNode();

		} else {
			$type = new Ast\Type\IdentifierTypeNode($tokens->currentTokenValue());
			$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);

			if ($tokens->isCurrentTokenType(Lexer::TOKEN_OPEN_ANGLE_BRACKET)) {
				$type = $this->parseGeneric($tokens, $type);

			} elseif ($tokens->isCurrentTokenType(Lexer::TOKEN_OPEN_PARENTHESES)) {
				$type = $this->tryParseCallable($tokens, $type);

			} elseif ($tokens->isCurrentTokenType(Lexer::TOKEN_OPEN_SQUARE_BRACKET)) {
				$type = $this->tryParseArray($tokens, $type);
			}
		}

		return $type;
	}


	private function parseUnion(TokenIterator $tokens, Ast\Type\TypeNode $type): Ast\Type\TypeNode
	{
		$types = [$type];

		while ($tokens->tryConsumeTokenType(Lexer::TOKEN_UNION)) {
			$types[] = $this->parseAtomic($tokens);
		}

		return new Ast\Type\UnionTypeNode($types);
	}


	private function parseIntersection(TokenIterator $tokens, Ast\Type\TypeNode $type): Ast\Type\TypeNode
	{
		$types = [$type];

		while ($tokens->tryConsumeTokenType(Lexer::TOKEN_INTERSECTION)) {
			$types[] = $this->parseAtomic($tokens);
		}

		return new Ast\Type\IntersectionTypeNode($types);
	}


	private function parseNullable(TokenIterator $tokens): Ast\Type\TypeNode
	{
		$tokens->consumeTokenType(Lexer::TOKEN_NULLABLE);

		$type = new Ast\Type\IdentifierTypeNode($tokens->currentTokenValue());
		$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);

		if ($tokens->isCurrentTokenType(Lexer::TOKEN_OPEN_ANGLE_BRACKET)) {
			$type = $this->parseGeneric($tokens, $type);
		}

		return new Ast\Type\NullableTypeNode($type);
	}


	private function parseGeneric(TokenIterator $tokens, Ast\Type\IdentifierTypeNode $baseType): Ast\Type\TypeNode
	{
		$tokens->consumeTokenType(Lexer::TOKEN_OPEN_ANGLE_BRACKET);
		$genericTypes[] = $this->parse($tokens);

		while ($tokens->tryConsumeTokenType(Lexer::TOKEN_COMMA)) {
			$genericTypes[] = $this->parse($tokens);
		}

		$tokens->consumeTokenType(Lexer::TOKEN_CLOSE_ANGLE_BRACKET);
		return new Ast\Type\GenericTypeNode($baseType, $genericTypes);
	}


	private function parseCallable(TokenIterator $tokens, Ast\Type\IdentifierTypeNode $identifier): Ast\Type\TypeNode
	{
		$tokens->consumeTokenType(Lexer::TOKEN_OPEN_PARENTHESES);

		$parameters = [];
		if (!$tokens->isCurrentTokenType(Lexer::TOKEN_CLOSE_PARENTHESES)) {
			$parameters[] = $this->parseCallableParameter($tokens);
			while ($tokens->tryConsumeTokenType(Lexer::TOKEN_COMMA)) {
				$parameters[] = $this->parseCallableParameter($tokens);
			}
		}

		$tokens->consumeTokenType(Lexer::TOKEN_CLOSE_PARENTHESES);
		$tokens->consumeTokenType(Lexer::TOKEN_COLON);
		$returnType = $this->parseCallableReturnType($tokens);

		return new Ast\Type\CallableTypeNode($identifier, $parameters, $returnType);
	}


	private function parseCallableParameter(TokenIterator $tokens): Ast\Type\CallableTypeParameterNode
	{
		$type = $this->parse($tokens);
		$isReference = $tokens->tryConsumeTokenType(Lexer::TOKEN_REFERENCE);
		$isVariadic = $tokens->tryConsumeTokenType(Lexer::TOKEN_VARIADIC);

		if ($tokens->isCurrentTokenType(Lexer::TOKEN_VARIABLE)) {
			$parameterName = $tokens->currentTokenValue();
			$tokens->consumeTokenType(Lexer::TOKEN_VARIABLE);

		} else {
			$parameterName = '';
		}

		$isOptional = $tokens->tryConsumeTokenType(Lexer::TOKEN_EQUAL);
		return new Ast\Type\CallableTypeParameterNode($type, $isReference, $isVariadic, $parameterName, $isOptional);
	}


	private function parseCallableReturnType(TokenIterator $tokens): Ast\Type\TypeNode
	{
		if ($tokens->isCurrentTokenType(Lexer::TOKEN_NULLABLE)) {
			$type = $this->parseNullable($tokens);

		} elseif ($tokens->tryConsumeTokenType(Lexer::TOKEN_OPEN_PARENTHESES)) {
			$type = $this->parse($tokens);
			$tokens->consumeTokenType(Lexer::TOKEN_CLOSE_PARENTHESES);

		} else {
			$type = new Ast\Type\IdentifierTypeNode($tokens->currentTokenValue());
			$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);

			if ($tokens->isCurrentTokenType(Lexer::TOKEN_OPEN_ANGLE_BRACKET)) {
				$type = $this->parseGeneric($tokens, $type);
			}
		}

		return $type;
	}


	private function tryParseCallable(TokenIterator $tokens, Ast\Type\IdentifierTypeNode $identifier): Ast\Type\TypeNode
	{
		try {
			$tokens->pushSavePoint();
			$type = $this->parseCallable($tokens, $identifier);
			$tokens->dropSavePoint();

		} catch (\PHPStan\PhpDocParser\Parser\ParserException $e) {
			$tokens->rollback();
			$type = $identifier;
		}

		return $type;
	}


	private function tryParseArray(TokenIterator $tokens, Ast\Type\TypeNode $type): Ast\Type\TypeNode
	{
		try {
			while ($tokens->isCurrentTokenType(Lexer::TOKEN_OPEN_SQUARE_BRACKET)) {
				$tokens->pushSavePoint();
				$tokens->consumeTokenType(Lexer::TOKEN_OPEN_SQUARE_BRACKET);
				$tokens->consumeTokenType(Lexer::TOKEN_CLOSE_SQUARE_BRACKET);
				$tokens->dropSavePoint();
				$type = new Ast\Type\ArrayTypeNode($type);
			}

		} catch (\PHPStan\PhpDocParser\Parser\ParserException $e) {
			$tokens->rollback();
		}

		return $type;
	}

}
