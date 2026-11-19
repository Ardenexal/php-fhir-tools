<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-contact
 *
 * @description Contact details to assist a user in finding and communicating with the publisher.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-contact', fhirVersion: 'R5', isModifier: false)]
#[FHIRExtensionContext(type: 'element', expression: 'ArtifactAssessment', startFhirVersion: '5.0')]
#[FHIRExtensionContext(type: 'element', expression: 'ClinicalUseDefinition', startFhirVersion: '4.3')]
#[FHIRExtensionContext(type: 'element', expression: 'ConditionDefinition', startFhirVersion: '5.0')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceDefinition', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'Group')]
#[FHIRExtensionContext(type: 'element', expression: 'Medication')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationKnowledge', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'ObservationDefinition', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'SpecimenDefinition', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'Substance')]
#[FHIRExtensionContext(type: 'element', expression: 'SubstanceDefinition', startFhirVersion: '4.3')]
#[FHIRExtensionContext(type: 'element', expression: 'SubscriptionTopic', startFhirVersion: '4.3')]
class ArtifactContactExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var ContactDetail|null valueContactDetail Value of extension */
        #[FhirProperty(fhirType: 'ContactDetail', propertyKind: 'complex')]
        public ?ContactDetail $valueContactDetail = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-contact',
            value: $this->valueContactDetail,
        );
    }
}
