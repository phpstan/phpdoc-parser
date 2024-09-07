<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Printer;

use PHPUnit\Framework\TestCase;
use function str_split;

/**
 * Inspired by https://github.com/nikic/PHP-Parser/tree/36a6dcd04e7b0285e8f0868f44bd4927802f7df1
 *
 * Copyright (c) 2011, Nikita Popov
 * All rights reserved.
 */
class DifferTest extends TestCase
{

	/**
	 * @param DiffElem[] $diff
	 */
	private function formatDiffString(array $diff): string
	{
		$diffStr = '';
		foreach ($diff as $diffElem) {
			switch ($diffElem->type) {
				case DiffElem::TYPE_KEEP:
					$diffStr .= $diffElem->old;
					break;
				case DiffElem::TYPE_REMOVE:
					$diffStr .= '-' . $diffElem->old;
					break;
				case DiffElem::TYPE_ADD:
					$diffStr .= '+' . $diffElem->new;
					break;
				case DiffElem::TYPE_REPLACE:
					$diffStr .= '/' . $diffElem->old . $diffElem->new;
					break;
			}
		}
		return $diffStr;
	}

	/**
	 * @dataProvider provideTestDiff
	 */
	public function testDiff(string $oldStr, string $newStr, string $expectedDiffStr): void
	{
		$differ = new Differ(static fn ($a, $b) => $a === $b);
		$diff = $differ->diff(str_split($oldStr), str_split($newStr));
		$this->assertSame($expectedDiffStr, $this->formatDiffString($diff));
	}

	/**
	 * @return list<array{string, string, string}>
	 */
	public function provideTestDiff(): array
	{
		return [
			['abc', 'abc', 'abc'],
			['abc', 'abcdef', 'abc+d+e+f'],
			['abcdef', 'abc', 'abc-d-e-f'],
			['abcdef', 'abcxyzdef', 'abc+x+y+zdef'],
			['axyzb', 'ab', 'a-x-y-zb'],
			['abcdef', 'abxyef', 'ab-c-d+x+yef'],
			['abcdef', 'cdefab', '-a-bcdef+a+b'],
		];
	}

	/**
	 * @dataProvider provideTestDiffWithReplacements
	 */
	public function testDiffWithReplacements(string $oldStr, string $newStr, string $expectedDiffStr): void
	{
		$differ = new Differ(static fn ($a, $b) => $a === $b);
		$diff = $differ->diffWithReplacements(str_split($oldStr), str_split($newStr));
		$this->assertSame($expectedDiffStr, $this->formatDiffString($diff));
	}

	/**
	 * @return list<array{string, string, string}>
	 */
	public function provideTestDiffWithReplacements(): array
	{
		return [
			['abcde', 'axyze', 'a/bx/cy/dze'],
			['abcde', 'xbcdy', '/axbcd/ey'],
			['abcde', 'axye', 'a-b-c-d+x+ye'],
			['abcde', 'axyzue', 'a-b-c-d+x+y+z+ue'],
		];
	}

}
