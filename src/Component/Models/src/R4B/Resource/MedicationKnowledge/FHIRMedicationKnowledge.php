<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMedicationKnowledgeStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicationKnowledge
 *
 * @description Information about a medication that is used to support knowledge.
 */
#[FhirResource(
    type: 'MedicationKnowledge',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationKnowledge',
    fhirVersion: 'R4B',
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
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Code that identifies this medication */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRMedicationKnowledgeStatusCodesType|null status active | inactive | entered-in-error */
        public ?FHIRMedicationKnowledgeStatusCodesType $status = null,
        /** @var FHIRReference|null manufacturer Manufacturer of the item */
        public ?FHIRReference $manufacturer = null,
        /** @var FHIRCodeableConcept|null doseForm powder | tablets | capsule + */
        public ?FHIRCodeableConcept $doseForm = null,
        /** @var FHIRQuantity|null amount Amount of drug in package */
        public ?FHIRQuantity $amount = null,
        /** @var array<FHIRString|string> synonym Additional names for a medication */
        public array $synonym = [],
        /** @var array<FHIRMedicationKnowledgeRelatedMedicationKnowledge> relatedMedicationKnowledge Associated or related medication information */
        public array $relatedMedicationKnowledge = [],
        /** @var array<FHIRReference> associatedMedication A medication resource that is associated with this medication */
        public array $associatedMedication = [],
        /** @var array<FHIRCodeableConcept> productType Category of the medication or product */
        public array $productType = [],
        /** @var array<FHIRMedicationKnowledgeMonograph> monograph Associated documentation about the medication */
        public array $monograph = [],
        /** @var array<FHIRMedicationKnowledgeIngredient> ingredient Active or inactive ingredient */
        public array $ingredient = [],
        /** @var FHIRMarkdown|null preparationInstruction The instructions for preparing the medication */
        public ?FHIRMarkdown $preparationInstruction = null,
        /** @var array<FHIRCodeableConcept> intendedRoute The intended or approved route of administration */
        public array $intendedRoute = [],
        /** @var array<FHIRMedicationKnowledgeCost> cost The pricing of the medication */
        public array $cost = [],
        /** @var array<FHIRMedicationKnowledgeMonitoringProgram> monitoringProgram Program under which a medication is reviewed */
        public array $monitoringProgram = [],
        /** @var array<FHIRMedicationKnowledgeAdministrationGuidelines> administrationGuidelines Guidelines for administration of the medication */
        public array $administrationGuidelines = [],
        /** @var array<FHIRMedicationKnowledgeMedicineClassification> medicineClassification Categorization of the medication within a formulary or classification system */
        public array $medicineClassification = [],
        /** @var FHIRMedicationKnowledgePackaging|null packaging Details about packaged medications */
        public ?FHIRMedicationKnowledgePackaging $packaging = null,
        /** @var array<FHIRMedicationKnowledgeDrugCharacteristic> drugCharacteristic Specifies descriptive properties of the medicine */
        public array $drugCharacteristic = [],
        /** @var array<FHIRReference> contraindication Potential clinical issue with or between medication(s) */
        public array $contraindication = [],
        /** @var array<FHIRMedicationKnowledgeRegulatory> regulatory Regulatory information about a medication */
        public array $regulatory = [],
        /** @var array<FHIRMedicationKnowledgeKinetics> kinetics The time course of drug absorption, distribution, metabolism and excretion of a medication from the body */
        public array $kinetics = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
