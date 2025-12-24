<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ExtendedContactDetail
 *
 * @description Specifies contact information for a specific purpose over a period of time, might be handled/monitored by a specific named person or organization.
 */
#[FHIRComplexType(typeName: 'ExtendedContactDetail', fhirVersion: 'R5')]
class FHIRExtendedContactDetail extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRCodeableConcept|null purpose The type of contact */
        public ?FHIRCodeableConcept $purpose = null,
        /** @var array<FHIRHumanName> name Name of an individual to contact */
        public array $name = [],
        /** @var array<FHIRContactPoint> telecom Contact details (e.g.phone/fax/url) */
        public array $telecom = [],
        /** @var FHIRAddress|null address Address for the contact */
        public ?FHIRAddress $address = null,
        /** @var FHIRReference|null organization This contact detail is handled/monitored by a specific organization */
        public ?FHIRReference $organization = null,
        /** @var FHIRPeriod|null period Period that this contact was valid for usage */
        public ?FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension);
    }
}
