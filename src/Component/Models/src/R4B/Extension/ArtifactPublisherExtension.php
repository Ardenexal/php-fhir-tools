<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-publisher
 *
 * @description The name of the organization or individual responsible for the release and ongoing maintenance of the artifact.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-publisher', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'ArtifactAssessment')]
#[FHIRExtensionContext(type: 'element', expression: 'ClinicalUseDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ConditionDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Group')]
#[FHIRExtensionContext(type: 'element', expression: 'Medication')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationKnowledge')]
#[FHIRExtensionContext(type: 'element', expression: 'ObservationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ResearchStudy')]
#[FHIRExtensionContext(type: 'element', expression: 'SpecimenDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Substance')]
#[FHIRExtensionContext(type: 'element', expression: 'SubstanceDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'SubscriptionTopic')]
class ArtifactPublisherExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-publisher',
            value: $this->valueString,
        );
    }
}
