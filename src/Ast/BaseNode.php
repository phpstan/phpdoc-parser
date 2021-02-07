<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast;

abstract class BaseNode implements Node
{

	/** @var array<string, mixed> */
	private $attributes = [];

    /**
     * @param mixed $value
     */
	public function setAttribute(string $key, $value): void
	{
		$this->attributes[$key] = $value;
	}

	public function hasAttribute(string $key): bool
	{
		return array_key_exists($key, $this->attributes);
	}

	/**
	 * @param mixed|null $default
	 * @return mixed|null
	 */
	public function getAttribute(string $key, $default = null)
	{
		if ($this->hasAttribute($key)) {
			return $this->attributes[$key];
		}

		return $default;
	}

}
