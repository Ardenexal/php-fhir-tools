<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicationKnowledge
 *
 * @description Information about a medication that is used to support knowledge.
 */
#[FhirResource(
    type: 'MedicationKnowledge',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationKnowledge',
    fhirVersion: 'R5',
)]
class FHIRMedicationKnowledge extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier for this medication */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null code Code that identifies this medication */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRMedicationKnowledgeStatusCodesType|null status active | entered-in-error | inactive */
        public ?FHIRMedicationKnowledgeStatusCodesType $status = null,
        /** @var FHIRReference|null author Creator or owner of the knowledge or information about the medication */
        public ?FHIRReference $author = null,
        /** @var array<FHIRCodeableConcept> intendedJurisdiction Codes that identify the different jurisdictions for which the information of this resource was created */
        public array $intendedJurisdiction = [],
        /** @var array<FHIRString|string> name A name associated with the medication being described */
        public array $name = [],
        /** @var array<FHIRMedicationKnowledgeRelatedMedicationKnowledge> relatedMedicationKnowledge Associated or related medication information */
        public array $relatedMedicationKnowledge = [],
        /** @var array<FHIRReference> associatedMedication The set of medication resources that are associated with this medication */
        public array $associatedMedication = [],
        /** @var array<FHIRCodeableConcept> productType Category of the medication or product */
        public array $productType = [],
        /** @var array<FHIRMedicationKnowledgeMonograph> monograph Associated documentation about the medication */
        public array $monograph = [],
        /** @var FHIRMarkdown|null preparationInstruction The instructions for preparing the medication */
        public ?FHIRMarkdown $preparationInstruction = null,
        /** @var array<FHIRMedicationKnowledgeCost> cost The pricing of the medication */
        public array $cost = [],
        /** @var array<FHIRMedicationKnowledgeMonitoringProgram> monitoringProgram Program under which a medication is reviewed */
        public array $monitoringProgram = [],
        /** @var array<FHIRMedicationKnowledgeIndicationGuideline> indicationGuideline Guidelines or protocols for administration of the medication for an indication */
        public array $indicationGuideline = [],
        /** @var array<FHIRMedicationKnowledgeMedicineClassification> medicineClassification Categorization of the medication within a formulary or classification system */
        public array $medicineClassification = [],
        /** @var array<FHIRMedicationKnowledgePackaging> packaging Details about packaged medications */
        public array $packaging = [],
        /** @var array<FHIRReference> clinicalUseIssue Potential clinical issue with or between medication(s) */
        public array $clinicalUseIssue = [],
        /** @var array<FHIRMedicationKnowledgeStorageGuideline> storageGuideline How the medication should be stored */
        public array $storageGuideline = [],
        /** @var array<FHIRMedicationKnowledgeRegulatory> regulatory Regulatory information about a medication */
        public array $regulatory = [],
        /** @var FHIRMedicationKnowledgeDefinitional|null definitional Minimal definition information about the medication */
        public ?FHIRMedicationKnowledgeDefinitional $definitional = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
