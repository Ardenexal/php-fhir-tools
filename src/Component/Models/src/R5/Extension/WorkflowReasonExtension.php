<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-reason
 *
 * @description References a resource or provides a code or text that explains why this event occurred or request was created.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-reason', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'Observation')]
#[FHIRExtensionContext(type: 'element', expression: 'DiagnosticReport')]
#[FHIRExtensionContext(type: 'element', expression: 'DocumentReference')]
#[FHIRExtensionContext(type: 'element', expression: 'Flag')]
#[FHIRExtensionContext(type: 'element', expression: 'NutritionOrder')]
class WorkflowReasonExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var CodeableReference|null valueCodeableReference Value of extension */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $valueCodeableReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-reason',
            value: $this->valueCodeableReference,
        );
    }
}
