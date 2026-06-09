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
 * @see http://hl7.org/fhir/StructureDefinition/workflow-adheresTo
 *
 * @description The action represented by this resource has been determined to satisfy the expectations established by the referenced Definition resource.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-adheresTo', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'CarePlan')]
#[FHIRExtensionContext(type: 'element', expression: 'Condition')]
#[FHIRExtensionContext(type: 'element', expression: 'Communication')]
#[FHIRExtensionContext(type: 'element', expression: 'DiagnosticReport')]
#[FHIRExtensionContext(type: 'element', expression: 'DocumentReference')]
#[FHIRExtensionContext(type: 'element', expression: 'Encounter')]
#[FHIRExtensionContext(type: 'element', expression: 'FamilyMemberHistory')]
#[FHIRExtensionContext(type: 'element', expression: 'Immunization')]
#[FHIRExtensionContext(type: 'element', expression: 'ImmunizationEvaluation')]
#[FHIRExtensionContext(type: 'element', expression: 'Observation')]
#[FHIRExtensionContext(type: 'element', expression: 'Procedure')]
#[FHIRExtensionContext(type: 'element', expression: 'QuestionnaireResponse')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyDelivery')]
#[FHIRExtensionContext(type: 'element', expression: 'Task')]
#[FHIRExtensionContext(type: 'element', expression: 'BiologicallyDerivedProductDispense')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceDispense')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceUsage')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceUseStatement')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceUseStatement')]
#[FHIRExtensionContext(type: 'element', expression: 'NutritionIntake')]
#[FHIRExtensionContext(type: 'element', expression: 'Transport')]
class AdheresToExtension extends Extension
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
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'valueCanonical',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
                    'jsonKey'      => 'valueReference',
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive',
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
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-adheresTo',
            value: $value,
        );
    }
}
