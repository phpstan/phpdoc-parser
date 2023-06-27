<?php declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

if (DIRECTORY_SEPARATOR !== '\\') {
	exec(__DIR__ . '/../build-abnfgen.sh', $buildAbnfgenOutput, $buildAbnfgenExitCode);
}
