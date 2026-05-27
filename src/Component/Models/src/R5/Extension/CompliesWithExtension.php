<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-compliesWith
 *
 * @description The action requested by this resource is intended to satisfy the expectations established by the referenced Definition resource.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-compliesWith', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'CarePlan')]
#[FHIRExtensionContext(type: 'element', expression: 'CarePlan.activity.plannedActivityReference')]
#[FHIRExtensionContext(type: 'element', expression: 'CommunicationRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'ImmunizationRecommendation')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'NutritionOrder')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestOrchestration')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestGroup')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'ServiceRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'Task')]
#[FHIRExtensionContext(type: 'element', expression: 'VisionPrescription')]
#[FHIRExtensionContext(type: 'element', expression: 'ObservationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'SpecimenDefinition')]
class CompliesWithExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-compliesWith',
            value: $value,
        );
    }
}
