<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-versionAlgorithm
 *
 * @description Indicates the mechanism used to compare versions to determine which is more current.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-versionAlgorithm', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'ArtifactAssessment')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement')]
#[FHIRExtensionContext(type: 'element', expression: 'ClinicalUseDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'CodeSystem')]
#[FHIRExtensionContext(type: 'element', expression: 'CompartmentDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Composition')]
#[FHIRExtensionContext(type: 'element', expression: 'ConditionDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ConceptMap')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ExampleScenario')]
#[FHIRExtensionContext(type: 'element', expression: 'GraphDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Group')]
#[FHIRExtensionContext(type: 'element', expression: 'ImplementationGuide')]
#[FHIRExtensionContext(type: 'element', expression: 'Library')]
#[FHIRExtensionContext(type: 'element', expression: 'Measure')]
#[FHIRExtensionContext(type: 'element', expression: 'Medication')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationKnowledge')]
#[FHIRExtensionContext(type: 'element', expression: 'MessageDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'NamingSystem')]
#[FHIRExtensionContext(type: 'element', expression: 'ObservationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'OperationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Questionnaire')]
#[FHIRExtensionContext(type: 'element', expression: 'ResearchStudy')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter')]
#[FHIRExtensionContext(type: 'element', expression: 'SpecimenDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Substance')]
#[FHIRExtensionContext(type: 'element', expression: 'SubstanceDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'SubscriptionTopic')]
#[FHIRExtensionContext(type: 'element', expression: 'StructureDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'StructureMap')]
#[FHIRExtensionContext(type: 'element', expression: 'TerminologyCapabilities')]
#[FHIRExtensionContext(type: 'element', expression: 'ValueSet')]
class ArtifactVersionAlgorithmExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var StringPrimitive|Coding|null value Value of extension */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive',
                    'jsonKey'      => 'valueString',
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding',
                    'jsonKey'      => 'valueCoding',
                ],
            ],
        )]
        StringPrimitive|Coding|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-versionAlgorithm',
            value: $value,
        );
    }
}
