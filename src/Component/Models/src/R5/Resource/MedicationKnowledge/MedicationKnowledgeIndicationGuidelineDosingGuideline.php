<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @description The guidelines for the dosage of the medication for the indication.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicationKnowledge',
    elementPath: 'MedicationKnowledge.indicationGuideline.dosingGuideline',
    fhirVersion: 'R5',
)]
class MedicationKnowledgeIndicationGuidelineDosingGuideline extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var CodeableConcept|null treatmentIntent Intention of the treatment */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $treatmentIntent = null,
        /** @var array<MedicationKnowledgeIndicationGuidelineDosingGuidelineDosage> dosage Dosage for the medication for the specific guidelines */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeIndicationGuidelineDosingGuidelineDosage',
        )]
        public array $dosage = [],
        /** @var CodeableConcept|null administrationTreatment Type of treatment the guideline applies to */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $administrationTreatment = null,
        /** @var array<MedicationKnowledgeIndicationGuidelineDosingGuidelinePatientCharacteristic> patientCharacteristic Characteristics of the patient that are relevant to the administration guidelines */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeIndicationGuidelineDosingGuidelinePatientCharacteristic',
        )]
        public array $patientCharacteristic = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
