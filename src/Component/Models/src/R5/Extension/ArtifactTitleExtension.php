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
 * @see http://hl7.org/fhir/StructureDefinition/artifact-title
 *
 * @description A short, descriptive, user-friendly title for the artifact.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-title', fhirVersion: 'R5', isModifier: false)]
#[FHIRExtensionContext(type: 'element', expression: 'ClinicalUseDefinition', startFhirVersion: '4.3')]
#[FHIRExtensionContext(type: 'element', expression: 'CompartmentDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ConditionDefinition', startFhirVersion: '5.0')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceDefinition', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'ExampleScenario', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'GraphDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Group')]
#[FHIRExtensionContext(type: 'element', expression: 'Medication')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationKnowledge', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'NamingSystem')]
#[FHIRExtensionContext(type: 'element', expression: 'ObservationDefinition', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter')]
#[FHIRExtensionContext(type: 'element', expression: 'SpecimenDefinition', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'Substance')]
#[FHIRExtensionContext(type: 'element', expression: 'SubstanceDefinition', startFhirVersion: '4.3')]
#[FHIRExtensionContext(type: 'element', expression: 'SubscriptionTopic', startFhirVersion: '4.3')]
class ArtifactTitleExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-title',
            value: $this->valueString,
        );
    }
}
