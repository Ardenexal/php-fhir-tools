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
 * @see http://hl7.org/fhir/StructureDefinition/workflow-shallComplyWith
 *
 * @description In satisfying this request or instantiating this definition, the expectations defined in the Definition resource are expected to be met.  (This allows requirements defined elsewhere to be brought into play by reference rather than providing all of the detail in-line necessary to satisfy the referenced Definition.).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-shallComplyWith', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Appointment')]
#[FHIRExtensionContext(type: 'element', expression: 'CarePlan')]
#[FHIRExtensionContext(type: 'element', expression: 'CommunicationRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'ImmunizationRecommendation')]
#[FHIRExtensionContext(type: 'element', expression: 'Measure')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'NutritionOrder')]
#[FHIRExtensionContext(type: 'element', expression: 'OperationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestOrchestration')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestGroup')]
#[FHIRExtensionContext(type: 'element', expression: 'ServiceRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'ObservationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'SpecimenDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Task')]
#[FHIRExtensionContext(type: 'element', expression: 'VisionPrescription')]
class ShallComplyWithExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var CanonicalPrimitive|Reference|UriPrimitive|null value Value of extension */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'valueCanonical',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
                    'jsonKey'      => 'valueReference',
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive',
                    'jsonKey'      => 'valueUri',
                ],
            ],
        )]
        CanonicalPrimitive|Reference|UriPrimitive|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-shallComplyWith',
            value: $value,
        );
    }
}
