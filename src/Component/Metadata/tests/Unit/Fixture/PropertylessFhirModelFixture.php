<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;

/**
 * A FHIR model that declares no properties.
 *
 * `PropertyMetadataProvider::getPropertyMetadata()` answers the empty array for this class and for a
 * plain PHP object alike, which is precisely why `isFhirModelClass()` cannot be implemented by
 * testing that map for emptiness. No generated model has this shape today, so nothing in the
 * generated tree can stand in for it — the case has to be constructed.
 *
 * A named file-level class rather than an anonymous one on purpose: attribute arguments are
 * evaluated lazily, and `self::CONST` inside an attribute on an anonymous class fatals at read time.
 *
 * @author Ardenexal
 */
#[FHIRComplexType(typeName: 'PropertylessThing', fhirVersion: 'R5')]
final class PropertylessFhirModelFixture
{
}
