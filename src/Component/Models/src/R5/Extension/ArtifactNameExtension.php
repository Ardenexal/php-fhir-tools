<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-name
 *
 * @description A natural language name identifying the artifact. This name should be usable as an identifier for the module by machine processing applications such as code generation.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-name', fhirVersion: 'R5', isModifier: false)]
#[FHIRExtensionContext(type: 'element', expression: 'ArtifactAssessment', startFhirVersion: '5.0')]
#[FHIRExtensionContext(type: 'element', expression: 'ClinicalUseDefinition', startFhirVersion: '4.3')]
#[FHIRExtensionContext(type: 'element', expression: 'Composition', endFhirVersion: '4.3')]
#[FHIRExtensionContext(type: 'element', expression: 'ConditionDefinition', startFhirVersion: '5.0')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceDefinition', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'Group')]
#[FHIRExtensionContext(type: 'element', expression: 'Medication')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationKnowledge', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'ObservationDefinition', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'ResearchStudy')]
#[FHIRExtensionContext(type: 'element', expression: 'SpecimenDefinition', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'Substance')]
#[FHIRExtensionContext(type: 'element', expression: 'SubstanceDefinition', startFhirVersion: '4.3')]
#[FHIRExtensionContext(type: 'element', expression: 'SubscriptionTopic', startFhirVersion: '4.3')]
class ArtifactNameExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-name',
            value: $this->valueString,
        );
    }
}
