<?php declare(strict_types = 1);

use Tally\Configuration;

// Tally is installed in tools/tally, because it needs a newer PHP than this library, so its
// autoloader knows only Tally and the library's own autoloader is loaded here.
require_once __DIR__ . '/vendor/autoload.php';

return Configuration::create()
	->withPaths([__DIR__ . '/benchmarks']);
