<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - Structured Documents WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-otherConfidentiality
 *
 * @description Carries additional confidentiality codes beyond the base fixed code specified in the CDA document.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-otherConfidentiality', fhirVersion: 'R4B')]
class OtherConfidentialityExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-otherConfidentiality',
            value: $this->valueCoding,
        );
    }
}
