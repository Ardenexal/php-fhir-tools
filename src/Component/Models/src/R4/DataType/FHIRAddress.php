<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Address
 *
 * @description An address expressed using postal conventions (as opposed to GPS or other location definition formats).  This data type may be used to convey addresses for use in delivering mail as well as for visiting locations which might not be valid for mail delivery.  There are a variety of postal address formats defined around the world.
 */
#[FHIRComplexType(typeName: 'Address', fhirVersion: 'R4')]
class FHIRAddress extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRAddressUseType|null use home | work | temp | old | billing - purpose of this address */
        public ?\FHIRAddressUseType $use = null,
        /** @var FHIRAddressTypeType|null type postal | physical | both */
        public ?\FHIRAddressTypeType $type = null,
        /** @var FHIRString|string|null text Text representation of the address */
        public \FHIRString|string|null $text = null,
        /** @var array<FHIRString|string> line Street name, number, direction & P.O. Box etc. */
        public array $line = [],
        /** @var FHIRString|string|null city Name of city, town etc. */
        public \FHIRString|string|null $city = null,
        /** @var FHIRString|string|null district District name (aka county) */
        public \FHIRString|string|null $district = null,
        /** @var FHIRString|string|null state Sub-unit of country (abbreviations ok) */
        public \FHIRString|string|null $state = null,
        /** @var FHIRString|string|null postalCode Postal code for area */
        public \FHIRString|string|null $postalCode = null,
        /** @var FHIRString|string|null country Country (e.g. can be ISO 3166 2 or 3 letter code) */
        public \FHIRString|string|null $country = null,
        /** @var FHIRPeriod|null period Time period when address was/is in use */
        public ?\FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension);
    }
}
