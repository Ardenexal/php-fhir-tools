<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-useContext
 *
 * @description The content was developed with a focus and intent of supporting the contexts that are listed. These contexts may be general categories (gender, age, ...) or may be references to specific programs (insurance plans, studies, ...) and may be used to assist with indexing and searching for appropriate artifact instances.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-useContext', fhirVersion: 'R5')]
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
class ArtifactUseContextExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var UsageContext|null valueUsageContext Value of extension */
        #[FhirProperty(fhirType: 'UsageContext', propertyKind: 'complex')]
        public ?UsageContext $valueUsageContext = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-useContext',
            value: $this->valueUsageContext,
        );
    }
}
