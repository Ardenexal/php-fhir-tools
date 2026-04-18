<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Structured Documents
 *
 * @see http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-otherConfidentiality
 *
 * @description Carries additional confidentiality codes beyond the base fixed code specified in the CDA document.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-otherConfidentiality', fhirVersion: 'R5')]
class CompositionOtherConfidentialityExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-otherConfidentiality',
            value: $this->valueCoding,
        );
    }
}
