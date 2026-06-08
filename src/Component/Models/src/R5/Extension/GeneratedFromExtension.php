<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-generatedFrom
 *
 * @description This artifact was algorithmically produced by applying the referenced artifact to the context relevant for this request.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-generatedFrom', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'CarePlan')]
#[FHIRExtensionContext(type: 'element', expression: 'CarePlan.activity.plannedActivityReference')]
#[FHIRExtensionContext(type: 'element', expression: 'Communication')]
#[FHIRExtensionContext(type: 'element', expression: 'CommunicationRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'FamilyMemberHistory')]
#[FHIRExtensionContext(type: 'element', expression: 'ImmunizationEvaluation')]
#[FHIRExtensionContext(type: 'element', expression: 'ImmunizationRecommendation')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'NutritionOrder')]
#[FHIRExtensionContext(type: 'element', expression: 'Procedure')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestOrchestration')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestGroup')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'ServiceRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'Task')]
#[FHIRExtensionContext(type: 'element', expression: 'VisionPrescription')]
class GeneratedFromExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var CanonicalPrimitive|null valueCanonical Value of extension */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $valueCanonical = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-generatedFrom',
            value: $this->valueCanonical,
        );
    }
}
