<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/diagnosticReport-replaces
 *
 * @description The report replaces the target resource.  For example, when a final anatomic pathology report replaces a preliminary anatomic pathology report replaces  where the subsequent observation of case and report  may be on more or different material (specimen).  Note that  this is not same concept as` DiagnosticReport.status`  = preliminary of final, but industry definition of preliminary and final. This extension has been replaced by DiagnosticReport.relatesTo element in R6.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/diagnosticReport-replaces', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'DiagnosticReport')]
class DRReplacesExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
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
            url: 'http://hl7.org/fhir/StructureDefinition/diagnosticReport-replaces',
            value: $this->valueReference,
        );
    }
}
