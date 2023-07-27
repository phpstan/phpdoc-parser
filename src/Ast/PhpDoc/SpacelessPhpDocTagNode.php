<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Ast\PhpDoc;

use Stringable;

final class SpacelessPhpDocTagNode extends PhpDocTagNode implements Stringable
{
    public function __toString(): string
    {
        return $this->name . $this->value;
    }
}
