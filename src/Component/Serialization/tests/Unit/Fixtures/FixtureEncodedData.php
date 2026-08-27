<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Fixtures;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;

/**
 * Stand-in for the CDA `ED` datatype: a complex type carrying enum-typed xmlAttr properties.
 *
 * Mirrors the shape the generator emits for CDA coded properties — `propertyKind: 'enum'` plus an
 * `xmlSerializedName` whose leading `@` marks it an XML attribute — alongside a plain-string
 * attribute and a SET<cs> enum list, so one round trip covers all three attribute shapes.
 */
#[FHIRComplexType(typeName: 'FixtureEncodedData', fhirVersion: 'R4')]
class FixtureEncodedData
{
    /**
     * @param list<FixturePostalAddressUse> $use
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: false, isRequired: false, xmlSerializedName: '@mediaType')]
        public ?string $mediaType = null,
        #[FhirProperty(fhirType: 'code', propertyKind: 'enum', isArray: false, isRequired: false, xmlSerializedName: '@representation')]
        public ?FixtureBinaryDataEncoding $representation = null,
        #[FhirProperty(fhirType: 'code', propertyKind: 'enum', isArray: true, isRequired: false, xmlSerializedName: '@use', phpType: FixturePostalAddressUse::class)]
        public array $use = [],
    ) {
    }
}
