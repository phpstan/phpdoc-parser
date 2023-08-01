<?php declare(strict_types = 1);

namespace PHPStan\PhpDocParser\Parser\Doctrine;

/**
 * ApiResource annotation.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 *
 * @Annotation
 * @Target({"CLASS"})
 */
final class ApiResource
{

	/** @var string */
	public $shortName;

	/** @var string */
	public $description;

	/** @var string */
	public $iri;

	/** @var array */
	public $itemOperations;

	/** @var array */
	public $collectionOperations;

	/** @var array */
	public $attributes = [];

}
