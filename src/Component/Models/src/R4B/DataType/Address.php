<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Address
 *
 * @description An address expressed using postal conventions (as opposed to GPS or other location definition formats).  This data type may be used to convey addresses for use in delivering mail as well as for visiting locations which might not be valid for mail delivery.  There are a variety of postal address formats defined around the world.
 */
#[FHIRComplexType(typeName: 'Address', fhirVersion: 'R4B')]
class Address extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var AddressUseType|null use home | work | temp | old | billing - purpose of this address */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AddressUseType $use = null,
        /** @var AddressTypeType|null type postal | physical | both */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AddressTypeType $type = null,
        /** @var StringPrimitive|string|null text Text representation of the address */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $text = null,
        /** @var array<StringPrimitive|string> line Street name, number, direction & P.O. Box etc. */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $line = [],
        /** @var StringPrimitive|string|null city Name of city, town etc. */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $city = null,
        /** @var StringPrimitive|string|null district District name (aka county) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $district = null,
        /** @var StringPrimitive|string|null state Sub-unit of country (abbreviations ok) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $state = null,
        /** @var StringPrimitive|string|null postalCode Postal code for area */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $postalCode = null,
        /** @var StringPrimitive|string|null country Country (e.g. can be ISO 3166 2 or 3 letter code) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $country = null,
        /** @var Period|null period Time period when address was/is in use */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension);
    }
}
