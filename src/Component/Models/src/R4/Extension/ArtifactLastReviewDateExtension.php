<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-lastReviewDate
 *
 * @description When the artifact was last reviewed by the publisher.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-lastReviewDate', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'ActorDefinition')]
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
#[FHIRExtensionContext(type: 'element', expression: 'Medication')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationKnowledge')]
#[FHIRExtensionContext(type: 'element', expression: 'MessageDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'NamingSystem')]
#[FHIRExtensionContext(type: 'element', expression: 'ObservationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'OperationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Requirements')]
#[FHIRExtensionContext(type: 'element', expression: 'ResearchStudy')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter')]
#[FHIRExtensionContext(type: 'element', expression: 'SpecimenDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Substance')]
#[FHIRExtensionContext(type: 'element', expression: 'SubstanceDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'SubscriptionTopic')]
#[FHIRExtensionContext(type: 'element', expression: 'StructureDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'StructureMap')]
#[FHIRExtensionContext(type: 'element', expression: 'TerminologyCapabilities')]
#[FHIRExtensionContext(type: 'element', expression: 'TestPlan')]
#[FHIRExtensionContext(type: 'element', expression: 'TestScript')]
#[FHIRExtensionContext(type: 'element', expression: 'ValueSet')]
class ArtifactLastReviewDateExtension extends Extension
{
    public function __construct(
        /** @var DatePrimitive|null valueDate Value of extension */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $valueDate = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-lastReviewDate',
            value: $this->valueDate,
        );
    }
}
