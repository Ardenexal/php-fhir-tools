<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/encounter-modeOfArrival
 *
 * @description Identifies how the patient arrives at the reporting facility, for example via an ambulance or other mode of transport.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/encounter-modeOfArrival', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'Encounter')]
class EncModeOfArrivalExtension extends Extension
{
    public function __construct(
        /** @var Coding|null valueCoding Value of extension */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?Coding $valueCoding = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/encounter-modeOfArrival',
            value: $this->valueCoding,
        );
    }
}
