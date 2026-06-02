<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-triggeredBy
 *
 * @description This resource came into being as a result of expectations set in the referenced Definition resource.  I.e. This resource represents a 'step' dictated within the protocol/order set/etc.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-triggeredBy', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'CarePlan')]
#[FHIRExtensionContext(type: 'element', expression: 'CarePlan.activity.plannedActivityReference')]
#[FHIRExtensionContext(type: 'element', expression: 'CommunicationRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'Encounter')]
#[FHIRExtensionContext(type: 'element', expression: 'ImmunizationRecommendation')]
#[FHIRExtensionContext(type: 'element', expression: 'NutritionOrder')]
#[FHIRExtensionContext(type: 'element', expression: 'Procedure')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestOrchestration')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestGroup')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'ServiceRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'Task')]
#[FHIRExtensionContext(type: 'element', expression: 'Condition')]
#[FHIRExtensionContext(type: 'element', expression: 'Communication')]
#[FHIRExtensionContext(type: 'element', expression: 'DiagnosticReport')]
#[FHIRExtensionContext(type: 'element', expression: 'DocumentReference')]
#[FHIRExtensionContext(type: 'element', expression: 'FamilyMemberHistory')]
#[FHIRExtensionContext(type: 'element', expression: 'Immunization')]
#[FHIRExtensionContext(type: 'element', expression: 'Observation')]
#[FHIRExtensionContext(type: 'element', expression: 'QuestionnaireResponse')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyDelivery')]
#[FHIRExtensionContext(type: 'element', expression: 'VisionPrescription')]
#[FHIRExtensionContext(type: 'element', expression: 'BiologicallyDerivedProductDispense')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceDispense')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceUsage')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceUseStatement')]
#[FHIRExtensionContext(type: 'element', expression: 'NutritionIntake')]
#[FHIRExtensionContext(type: 'element', expression: 'Transport')]
class TriggeredByExtension extends Extension
{
    public function __construct(
        /** @var CanonicalPrimitive|Reference|UriPrimitive|null value Value of extension */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        CanonicalPrimitive|Reference|UriPrimitive|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-triggeredBy',
            value: $value,
        );
    }
}
