<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/diagnosticReport-extends
 *
 * @description The report references related ("sibling") reports. This extension has been replaced by DiagnosticReport.relatesTo element in R6.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/diagnosticReport-extends', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'DiagnosticReport')]
class DRExtendsExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/diagnosticReport-extends',
            value: $this->valueReference,
        );
    }
}
