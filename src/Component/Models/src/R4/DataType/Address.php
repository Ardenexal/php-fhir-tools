<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Address
 *
 * @description An address expressed using postal conventions (as opposed to GPS or other location definition formats).  This data type may be used to convey addresses for use in delivering mail as well as for visiting locations which might not be valid for mail delivery.  There are a variety of postal address formats defined around the world.
 */
#[FHIRComplexType(typeName: 'Address', fhirVersion: 'R4')]
class Address extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var AddressUseType|null use home | work | temp | old | billing - purpose of this address */
        public ?AddressUseType $use = null,
        /** @var AddressTypeType|null type postal | physical | both */
        public ?AddressTypeType $type = null,
        /** @var StringPrimitive|string|null text Text representation of the address */
        public StringPrimitive|string|null $text = null,
        /** @var array<StringPrimitive|string> line Street name, number, direction & P.O. Box etc. */
        public array $line = [],
        /** @var StringPrimitive|string|null city Name of city, town etc. */
        public StringPrimitive|string|null $city = null,
        /** @var StringPrimitive|string|null district District name (aka county) */
        public StringPrimitive|string|null $district = null,
        /** @var StringPrimitive|string|null state Sub-unit of country (abbreviations ok) */
        public StringPrimitive|string|null $state = null,
        /** @var StringPrimitive|string|null postalCode Postal code for area */
        public StringPrimitive|string|null $postalCode = null,
        /** @var StringPrimitive|string|null country Country (e.g. can be ISO 3166 2 or 3 letter code) */
        public StringPrimitive|string|null $country = null,
        /** @var Period|null period Time period when address was/is in use */
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension);
    }
}
