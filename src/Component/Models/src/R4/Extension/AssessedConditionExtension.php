<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsAssessedCondition
 *
 * @description Used to denote condition context for genetic testing, which may influence reported variants and interpretation for large genomic testing panels e.g. lung cancer or familial breast cancer.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsAssessedCondition', fhirVersion: 'R4')]
class AssessedConditionExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsAssessedCondition',
            value: $this->valueReference,
        );
    }
}
