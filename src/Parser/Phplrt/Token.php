<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser\Phplrt;

/**
 * A token the parser has read, the way the reducers of the AST builder are
 * given it.
 *
 * Its offset is the index of the token of the lexer it stands for, which is
 * what the attributes of the AST are told about.
 *
 * @internal
 */
final class Token
{

	public int $id;

	public string $value;

	public int $offset;

	public function __construct(int $id, string $value, int $offset)
	{
		$this->id = $id;
		$this->value = $value;
		$this->offset = $offset;
	}

}
