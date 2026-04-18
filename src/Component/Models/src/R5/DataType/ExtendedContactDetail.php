<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ExtendedContactDetail
 *
 * @description Specifies contact information for a specific purpose over a period of time, might be handled/monitored by a specific named person or organization.
 */
#[FHIRComplexType(typeName: 'ExtendedContactDetail', fhirVersion: 'R5')]
class ExtendedContactDetail extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var CodeableConcept|null purpose The type of contact */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $purpose = null,
        /** @var array<HumanName> name Name of an individual to contact */
        #[FhirProperty(
            fhirType: 'HumanName',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\HumanName',
        )]
        public array $name = [],
        /** @var array<ContactPoint> telecom Contact details (e.g.phone/fax/url) */
        #[FhirProperty(
            fhirType: 'ContactPoint',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint',
        )]
        public array $telecom = [],
        /** @var Address|null address Address for the contact */
        #[FhirProperty(fhirType: 'Address', propertyKind: 'complex')]
        public ?Address $address = null,
        /** @var Reference|null organization This contact detail is handled/monitored by a specific organization */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $organization = null,
        /** @var Period|null period Period that this contact was valid for usage */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension);
    }
}
