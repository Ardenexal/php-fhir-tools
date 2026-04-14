<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-preferenceType
 *
 * @description Indicates what mode of communication the patient prefers to use for the indicated language.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-preferenceType', fhirVersion: 'R4B')]
class PreferenceTypeExtension extends Extension
{
    public function __construct(
        /** @var Coding|null valueCoding Value of extension */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding $valueCoding = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/patient-preferenceType',
            value: $this->valueCoding,
        );
    }
}
