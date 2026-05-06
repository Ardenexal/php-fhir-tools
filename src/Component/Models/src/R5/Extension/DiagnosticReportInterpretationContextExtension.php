<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/diagnosticreport-interpretationContext
 *
 * @description Other preceding or concurrent information that is critical to understand the context and significance of the DiagnosticReport. Example value set will be 'diabetic', 'fasting', and 'paraplegic'.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/diagnosticreport-interpretationContext', fhirVersion: 'R5')]
class DiagnosticReportInterpretationContextExtension extends Extension
{
    public function __construct(
        /** @var CodeableReference|null valueCodeableReference Relevant context information for interpreting the report */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $valueCodeableReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/diagnosticreport-interpretationContext',
            value: $this->valueCodeableReference,
        );
    }
}
