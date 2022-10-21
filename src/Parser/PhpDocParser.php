<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser;

use PHPStan\PhpDocParser\Ast;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\ShouldNotHappenException;
use function array_key_exists;
use function array_values;
use function count;
use function trim;

class PhpDocParser
{

	private const DISALLOWED_DESCRIPTION_START_TOKENS = [
		Lexer::TOKEN_UNION,
		Lexer::TOKEN_INTERSECTION,
	];

	/** @var TypeParser */
	private $typeParser;

	/** @var ConstExprParser */
	private $constantExprParser;

	/** @var bool */
	private $requireWhitespaceBeforeDescription;

	public function __construct(TypeParser $typeParser, ConstExprParser $constantExprParser, bool $requireWhitespaceBeforeDescription = false)
	{
		$this->typeParser = $typeParser;
		$this->constantExprParser = $constantExprParser;
		$this->requireWhitespaceBeforeDescription = $requireWhitespaceBeforeDescription;
	}


	public function parse(TokenIterator $tokens): Ast\PhpDoc\PhpDocNode
	{
		$tokens->consumeTokenType(Lexer::TOKEN_OPEN_PHPDOC);
		$tokens->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL);

		$children = [];

		if (!$tokens->isCurrentTokenType(Lexer::TOKEN_CLOSE_PHPDOC)) {
			$children[] = $this->parseChild($tokens);
			while ($tokens->tryConsumeTokenType(Lexer::TOKEN_PHPDOC_EOL) && !$tokens->isCurrentTokenType(Lexer::TOKEN_CLOSE_PHPDOC)) {
				$children[] = $this->parseChild($tokens);
			}
		}

		try {
			$tokens->consumeTokenType(Lexer::TOKEN_CLOSE_PHPDOC);
		} catch (ParserException $e) {
			$name = '';
			if (count($children) > 0) {
				$lastChild = $children[count($children) - 1];
				if ($lastChild instanceof Ast\PhpDoc\PhpDocTagNode) {
					$name = $lastChild->name;
				}
			}
			$tokens->forwardToTheEnd();
			return new Ast\PhpDoc\PhpDocNode([
				new Ast\PhpDoc\PhpDocTagNode($name, new Ast\PhpDoc\InvalidTagValueNode($e->getMessage(), $e)),
			]);
		}

		return new Ast\PhpDoc\PhpDocNode(array_values($children));
	}


	private function parseChild(TokenIterator $tokens): Ast\PhpDoc\PhpDocChildNode
	{
		if ($tokens->isCurrentTokenType(Lexer::TOKEN_PHPDOC_TAG)) {
			return $this->parseTag($tokens);

		}

		return $this->parseText($tokens);
	}


	private function parseText(TokenIterator $tokens): Ast\PhpDoc\PhpDocTextNode
	{
		$text = '';

		while (!$tokens->isCurrentTokenType(Lexer::TOKEN_PHPDOC_EOL)) {
			$text .= $tokens->getSkippedHorizontalWhiteSpaceIfAny() . $tokens->joinUntil(Lexer::TOKEN_PHPDOC_EOL, Lexer::TOKEN_CLOSE_PHPDOC, Lexer::TOKEN_END);

			if (!$tokens->isCurrentTokenType(Lexer::TOKEN_PHPDOC_EOL)) {
				break;
			}

			$tokens->pushSavePoint();
			$tokens->next();

			if ($tokens->isCurrentTokenType(Lexer::TOKEN_PHPDOC_TAG, Lexer::TOKEN_PHPDOC_EOL, Lexer::TOKEN_CLOSE_PHPDOC, Lexer::TOKEN_END)) {
				$tokens->rollback();
				break;
			}

			$tokens->dropSavePoint();
			$text .= "\n";
		}

		return new Ast\PhpDoc\PhpDocTextNode(trim($text, " \t"));
	}


	public function parseTag(TokenIterator $tokens): Ast\PhpDoc\PhpDocTagNode
	{
		$tag = $tokens->currentTokenValue();
		$tokens->next();
		$value = $this->parseTagValue($tokens, $tag);

		return new Ast\PhpDoc\PhpDocTagNode($tag, $value);
	}


	public function parseTagValue(TokenIterator $tokens, string $tag): Ast\PhpDoc\PhpDocTagValueNode
	{
		try {
			$tokens->pushSavePoint();

			switch ($tag) {
				case '@param':
				case '@phpstan-param':
				case '@psalm-param':
					$tagValue = $this->parseParamTagValue($tokens);
					break;

				case '@var':
				case '@phpstan-var':
				case '@psalm-var':
					$tagValue = $this->parseVarTagValue($tokens);
					break;

				case '@return':
				case '@phpstan-return':
				case '@psalm-return':
					$tagValue = $this->parseReturnTagValue($tokens);
					break;

				case '@throws':
				case '@phpstan-throws':
					$tagValue = $this->parseThrowsTagValue($tokens);
					break;

				case '@mixin':
					$tagValue = $this->parseMixinTagValue($tokens);
					break;

				case '@deprecated':
					$tagValue = $this->parseDeprecatedTagValue($tokens);
					break;

				case '@property':
				case '@property-read':
				case '@property-write':
				case '@phpstan-property':
				case '@phpstan-property-read':
				case '@phpstan-property-write':
				case '@psalm-property':
				case '@psalm-property-read':
				case '@psalm-property-write':
					$tagValue = $this->parsePropertyTagValue($tokens);
					break;

				case '@method':
				case '@phpstan-method':
				case '@psalm-method':
					$tagValue = $this->parseMethodTagValue($tokens);
					break;

				case '@template':
				case '@phpstan-template':
				case '@psalm-template':
				case '@template-covariant':
				case '@phpstan-template-covariant':
				case '@psalm-template-covariant':
				case '@template-contravariant':
				case '@phpstan-template-contravariant':
				case '@psalm-template-contravariant':
					$tagValue = $this->parseTemplateTagValue($tokens);
					break;

				case '@extends':
				case '@phpstan-extends':
				case '@template-extends':
					$tagValue = $this->parseExtendsTagValue('@extends', $tokens);
					break;

				case '@implements':
				case '@phpstan-implements':
				case '@template-implements':
					$tagValue = $this->parseExtendsTagValue('@implements', $tokens);
					break;

				case '@use':
				case '@phpstan-use':
				case '@template-use':
					$tagValue = $this->parseExtendsTagValue('@use', $tokens);
					break;

				case '@phpstan-type':
				case '@psalm-type':
					$tagValue = $this->parseTypeAliasTagValue($tokens);
					break;

				case '@phpstan-import-type':
				case '@psalm-import-type':
					$tagValue = $this->parseTypeAliasImportTagValue($tokens);
					break;

				case '@phpstan-assert':
				case '@phpstan-assert-if-true':
				case '@phpstan-assert-if-false':
				case '@psalm-assert':
				case '@psalm-assert-if-true':
				case '@psalm-assert-if-false':
					$tagValue = $this->parseAssertTagValue($tokens);
					break;

				case '@phpstan-this-out':
				case '@phpstan-self-out':
				case '@psalm-this-out':
				case '@psalm-self-out':
					$tagValue = $this->parseSelfOutTagValue($tokens);
					break;

				case '@param-out':
				case '@phpstan-param-out':
				case '@psalm-param-out':
					$tagValue = $this->parseParamOutTagValue($tokens);
					break;

				default:
					$tagValue = new Ast\PhpDoc\GenericTagValueNode($this->parseOptionalDescription($tokens));
					break;
			}

			$tokens->dropSavePoint();

		} catch (ParserException $e) {
			$tokens->rollback();
			$tagValue = new Ast\PhpDoc\InvalidTagValueNode($this->parseOptionalDescription($tokens), $e);
		}

		return $tagValue;
	}


	/**
	 * @return Ast\PhpDoc\ParamTagValueNode|Ast\PhpDoc\TypelessParamTagValueNode
	 */
	private function parseParamTagValue(TokenIterator $tokens): Ast\PhpDoc\PhpDocTagValueNode
	{
		if (
			$tokens->isCurrentTokenType(Lexer::TOKEN_REFERENCE)
			|| $tokens->isCurrentTokenType(Lexer::TOKEN_VARIADIC)
			|| $tokens->isCurrentTokenType(Lexer::TOKEN_VARIABLE)
		) {
			$type = null;
		} else {
			$type = $this->typeParser->parse($tokens);
		}

		$isReference = $tokens->tryConsumeTokenType(Lexer::TOKEN_REFERENCE);
		$isVariadic = $tokens->tryConsumeTokenType(Lexer::TOKEN_VARIADIC);
		$parameterName = $this->parseRequiredVariableName($tokens);
		$description = $this->parseOptionalDescription($tokens);

		if ($type !== null) {
			return new Ast\PhpDoc\ParamTagValueNode($type, $isVariadic, $parameterName, $description, $isReference);
		}

		return new Ast\PhpDoc\TypelessParamTagValueNode($isVariadic, $parameterName, $description, $isReference);
	}


	private function parseVarTagValue(TokenIterator $tokens): Ast\PhpDoc\VarTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$variableName = $this->parseOptionalVariableName($tokens);
		$description = $this->parseOptionalDescription($tokens, $variableName === '');
		return new Ast\PhpDoc\VarTagValueNode($type, $variableName, $description);
	}


	private function parseReturnTagValue(TokenIterator $tokens): Ast\PhpDoc\ReturnTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$description = $this->parseOptionalDescription($tokens, true);
		return new Ast\PhpDoc\ReturnTagValueNode($type, $description);
	}


	private function parseThrowsTagValue(TokenIterator $tokens): Ast\PhpDoc\ThrowsTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$description = $this->parseOptionalDescription($tokens, true);
		return new Ast\PhpDoc\ThrowsTagValueNode($type, $description);
	}

	private function parseMixinTagValue(TokenIterator $tokens): Ast\PhpDoc\MixinTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$description = $this->parseOptionalDescription($tokens, true);
		return new Ast\PhpDoc\MixinTagValueNode($type, $description);
	}

	private function parseDeprecatedTagValue(TokenIterator $tokens): Ast\PhpDoc\DeprecatedTagValueNode
	{
		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\DeprecatedTagValueNode($description);
	}


	private function parsePropertyTagValue(TokenIterator $tokens): Ast\PhpDoc\PropertyTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$parameterName = $this->parseRequiredVariableName($tokens);
		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\PropertyTagValueNode($type, $parameterName, $description);
	}


	private function parseMethodTagValue(TokenIterator $tokens): Ast\PhpDoc\MethodTagValueNode
	{
		$isStatic = $tokens->tryConsumeTokenValue('static');
		$returnTypeOrMethodName = $this->typeParser->parse($tokens);

		if ($tokens->isCurrentTokenType(Lexer::TOKEN_IDENTIFIER)) {
			$returnType = $returnTypeOrMethodName;
			$methodName = $tokens->currentTokenValue();
			$tokens->next();

		} elseif ($returnTypeOrMethodName instanceof Ast\Type\IdentifierTypeNode) {
			$returnType = $isStatic ? new Ast\Type\IdentifierTypeNode('static') : null;
			$methodName = $returnTypeOrMethodName->name;
			$isStatic = false;

		} else {
			$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER); // will throw exception
			exit;
		}

		$parameters = [];
		$tokens->consumeTokenType(Lexer::TOKEN_OPEN_PARENTHESES);
		if (!$tokens->isCurrentTokenType(Lexer::TOKEN_CLOSE_PARENTHESES)) {
			$parameters[] = $this->parseMethodTagValueParameter($tokens);
			while ($tokens->tryConsumeTokenType(Lexer::TOKEN_COMMA)) {
				$parameters[] = $this->parseMethodTagValueParameter($tokens);
			}
		}
		$tokens->consumeTokenType(Lexer::TOKEN_CLOSE_PARENTHESES);

		$description = $this->parseOptionalDescription($tokens);
		return new Ast\PhpDoc\MethodTagValueNode($isStatic, $returnType, $methodName, $parameters, $description);
	}


	private function parseMethodTagValueParameter(TokenIterator $tokens): Ast\PhpDoc\MethodTagValueParameterNode
	{
		switch ($tokens->currentTokenType()) {
			case Lexer::TOKEN_IDENTIFIER:
			case Lexer::TOKEN_OPEN_PARENTHESES:
			case Lexer::TOKEN_NULLABLE:
				$parameterType = $this->typeParser->parse($tokens);
				break;

			default:
				$parameterType = null;
		}

		$isReference = $tokens->tryConsumeTokenType(Lexer::TOKEN_REFERENCE);
		$isVariadic = $tokens->tryConsumeTokenType(Lexer::TOKEN_VARIADIC);

		$parameterName = $tokens->currentTokenValue();
		$tokens->consumeTokenType(Lexer::TOKEN_VARIABLE);

		if ($tokens->tryConsumeTokenType(Lexer::TOKEN_EQUAL)) {
			$defaultValue = $this->constantExprParser->parse($tokens);

		} else {
			$defaultValue = null;
		}

		return new Ast\PhpDoc\MethodTagValueParameterNode($parameterType, $isReference, $isVariadic, $parameterName, $defaultValue);
	}

	private function parseTemplateTagValue(TokenIterator $tokens): Ast\PhpDoc\TemplateTagValueNode
	{
		$name = $tokens->currentTokenValue();
		$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);

		if ($tokens->tryConsumeTokenValue('of') || $tokens->tryConsumeTokenValue('as')) {
			$bound = $this->typeParser->parse($tokens);

		} else {
			$bound = null;
		}

		if ($tokens->tryConsumeTokenValue('=')) {
			$default = $this->typeParser->parse($tokens);
		} else {
			$default = null;
		}

		$description = $this->parseOptionalDescription($tokens);

		return new Ast\PhpDoc\TemplateTagValueNode($name, $bound, $description, $default);
	}

	private function parseExtendsTagValue(string $tagName, TokenIterator $tokens): Ast\PhpDoc\PhpDocTagValueNode
	{
		$baseType = new IdentifierTypeNode($tokens->currentTokenValue());
		$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);

		$type = $this->typeParser->parseGeneric($tokens, $baseType);

		$description = $this->parseOptionalDescription($tokens);

		switch ($tagName) {
			case '@extends':
				return new Ast\PhpDoc\ExtendsTagValueNode($type, $description);
			case '@implements':
				return new Ast\PhpDoc\ImplementsTagValueNode($type, $description);
			case '@use':
				return new Ast\PhpDoc\UsesTagValueNode($type, $description);
		}

		throw new ShouldNotHappenException();
	}

	private function parseTypeAliasTagValue(TokenIterator $tokens): Ast\PhpDoc\TypeAliasTagValueNode
	{
		$alias = $tokens->currentTokenValue();
		$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);

		// support psalm-type syntax
		$tokens->tryConsumeTokenType(Lexer::TOKEN_EQUAL);

		$type = $this->typeParser->parse($tokens);

		return new Ast\PhpDoc\TypeAliasTagValueNode($alias, $type);
	}

	private function parseTypeAliasImportTagValue(TokenIterator $tokens): Ast\PhpDoc\TypeAliasImportTagValueNode
	{
		$importedAlias = $tokens->currentTokenValue();
		$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);

		$tokens->consumeTokenValue(Lexer::TOKEN_IDENTIFIER, 'from');

		$importedFrom = $tokens->currentTokenValue();
		$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);

		$importedAs = null;
		if ($tokens->tryConsumeTokenValue('as')) {
			$importedAs = $tokens->currentTokenValue();
			$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);
		}

		return new Ast\PhpDoc\TypeAliasImportTagValueNode($importedAlias, new IdentifierTypeNode($importedFrom), $importedAs);
	}

	/**
	 * @return Ast\PhpDoc\AssertTagValueNode|Ast\PhpDoc\AssertTagPropertyValueNode|Ast\PhpDoc\AssertTagMethodValueNode
	 */
	private function parseAssertTagValue(TokenIterator $tokens): Ast\PhpDoc\PhpDocTagValueNode
	{
		$isNegated = $tokens->tryConsumeTokenType(Lexer::TOKEN_NEGATED);
		$isEquality = $tokens->tryConsumeTokenType(Lexer::TOKEN_EQUAL);
		$type = $this->typeParser->parse($tokens);
		$parameter = $this->parseAssertParameter($tokens);
		$description = $this->parseOptionalDescription($tokens);

		if (array_key_exists('method', $parameter)) {
			return new Ast\PhpDoc\AssertTagMethodValueNode($type, $parameter['parameter'], $parameter['method'], $isNegated, $description, $isEquality);
		} elseif (array_key_exists('property', $parameter)) {
			return new Ast\PhpDoc\AssertTagPropertyValueNode($type, $parameter['parameter'], $parameter['property'], $isNegated, $description, $isEquality);
		}

		return new Ast\PhpDoc\AssertTagValueNode($type, $parameter['parameter'], $isNegated, $description, $isEquality);
	}

	/**
	 * @return array{parameter: string}|array{parameter: string, property: string}|array{parameter: string, method: string}
	 */
	private function parseAssertParameter(TokenIterator $tokens): array
	{
		if ($tokens->isCurrentTokenType(Lexer::TOKEN_THIS_VARIABLE)) {
			$parameter = '$this';
			$requirePropertyOrMethod = true;
			$tokens->next();
		} else {
			$parameter = $tokens->currentTokenValue();
			$requirePropertyOrMethod = false;
			$tokens->consumeTokenType(Lexer::TOKEN_VARIABLE);
		}

		if ($requirePropertyOrMethod || $tokens->isCurrentTokenType(Lexer::TOKEN_ARROW)) {
			$tokens->consumeTokenType(Lexer::TOKEN_ARROW);

			$propertyOrMethod = $tokens->currentTokenValue();
			$tokens->consumeTokenType(Lexer::TOKEN_IDENTIFIER);

			if ($tokens->tryConsumeTokenType(Lexer::TOKEN_OPEN_PARENTHESES)) {
				$tokens->consumeTokenType(Lexer::TOKEN_CLOSE_PARENTHESES);

				return ['parameter' => $parameter, 'method' => $propertyOrMethod];
			}

			return ['parameter' => $parameter, 'property' => $propertyOrMethod];
		}

		return ['parameter' => $parameter];
	}

	private function parseSelfOutTagValue(TokenIterator $tokens): Ast\PhpDoc\SelfOutTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$description = $this->parseOptionalDescription($tokens);

		return new Ast\PhpDoc\SelfOutTagValueNode($type, $description);
	}

	private function parseParamOutTagValue(TokenIterator $tokens): Ast\PhpDoc\ParamOutTagValueNode
	{
		$type = $this->typeParser->parse($tokens);
		$parameterName = $this->parseRequiredVariableName($tokens);
		$description = $this->parseOptionalDescription($tokens);

		return new Ast\PhpDoc\ParamOutTagValueNode($type, $parameterName, $description);
	}

	private function parseOptionalVariableName(TokenIterator $tokens): string
	{
		if ($tokens->isCurrentTokenType(Lexer::TOKEN_VARIABLE)) {
			$parameterName = $tokens->currentTokenValue();
			$tokens->next();
		} elseif ($tokens->isCurrentTokenType(Lexer::TOKEN_THIS_VARIABLE)) {
			$parameterName = '$this';
			$tokens->next();

		} else {
			$parameterName = '';
		}

		return $parameterName;
	}


	private function parseRequiredVariableName(TokenIterator $tokens): string
	{
		$parameterName = $tokens->currentTokenValue();
		$tokens->consumeTokenType(Lexer::TOKEN_VARIABLE);

		return $parameterName;
	}

	private function parseOptionalDescription(TokenIterator $tokens, bool $limitStartToken = false): string
	{
		if ($limitStartToken) {
			foreach (self::DISALLOWED_DESCRIPTION_START_TOKENS as $disallowedStartToken) {
				if (!$tokens->isCurrentTokenType($disallowedStartToken)) {
					continue;
				}

				$tokens->consumeTokenType(Lexer::TOKEN_OTHER); // will throw exception
			}

			if (
				$this->requireWhitespaceBeforeDescription
				&& !$tokens->isCurrentTokenType(Lexer::TOKEN_PHPDOC_EOL, Lexer::TOKEN_CLOSE_PHPDOC, Lexer::TOKEN_END)
				&& !$tokens->isPrecededByHorizontalWhitespace()
			) {
				$tokens->consumeTokenType(Lexer::TOKEN_HORIZONTAL_WS); // will throw exception
			}
		}

		return $this->parseText($tokens)->text;
	}

}
