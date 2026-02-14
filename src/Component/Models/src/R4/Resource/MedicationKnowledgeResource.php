<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationKnowledgeStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeAdministrationGuidelines;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeCost;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeDrugCharacteristic;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeIngredient;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeKinetics;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeMedicineClassification;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeMonitoringProgram;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeMonograph;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgePackaging;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeRegulatory;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge\MedicationKnowledgeRelatedMedicationKnowledge;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicationKnowledge
 *
 * @description Information about a medication that is used to support knowledge.
 */
#[FhirResource(
    type: 'MedicationKnowledge',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationKnowledge',
    fhirVersion: 'R4',
)]
class MedicationKnowledgeResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Code that identifies this medication */
        public ?CodeableConcept $code = null,
        /** @var MedicationKnowledgeStatusCodesType|null status active | inactive | entered-in-error */
        public ?MedicationKnowledgeStatusCodesType $status = null,
        /** @var Reference|null manufacturer Manufacturer of the item */
        public ?Reference $manufacturer = null,
        /** @var CodeableConcept|null doseForm powder | tablets | capsule + */
        public ?CodeableConcept $doseForm = null,
        /** @var Quantity|null amount Amount of drug in package */
        public ?Quantity $amount = null,
        /** @var array<StringPrimitive|string> synonym Additional names for a medication */
        public array $synonym = [],
        /** @var array<MedicationKnowledgeRelatedMedicationKnowledge> relatedMedicationKnowledge Associated or related medication information */
        public array $relatedMedicationKnowledge = [],
        /** @var array<Reference> associatedMedication A medication resource that is associated with this medication */
        public array $associatedMedication = [],
        /** @var array<CodeableConcept> productType Category of the medication or product */
        public array $productType = [],
        /** @var array<MedicationKnowledgeMonograph> monograph Associated documentation about the medication */
        public array $monograph = [],
        /** @var array<MedicationKnowledgeIngredient> ingredient Active or inactive ingredient */
        public array $ingredient = [],
        /** @var MarkdownPrimitive|null preparationInstruction The instructions for preparing the medication */
        public ?MarkdownPrimitive $preparationInstruction = null,
        /** @var array<CodeableConcept> intendedRoute The intended or approved route of administration */
        public array $intendedRoute = [],
        /** @var array<MedicationKnowledgeCost> cost The pricing of the medication */
        public array $cost = [],
        /** @var array<MedicationKnowledgeMonitoringProgram> monitoringProgram Program under which a medication is reviewed */
        public array $monitoringProgram = [],
        /** @var array<MedicationKnowledgeAdministrationGuidelines> administrationGuidelines Guidelines for administration of the medication */
        public array $administrationGuidelines = [],
        /** @var array<MedicationKnowledgeMedicineClassification> medicineClassification Categorization of the medication within a formulary or classification system */
        public array $medicineClassification = [],
        /** @var MedicationKnowledgePackaging|null packaging Details about packaged medications */
        public ?MedicationKnowledgePackaging $packaging = null,
        /** @var array<MedicationKnowledgeDrugCharacteristic> drugCharacteristic Specifies descriptive properties of the medicine */
        public array $drugCharacteristic = [],
        /** @var array<Reference> contraindication Potential clinical issue with or between medication(s) */
        public array $contraindication = [],
        /** @var array<MedicationKnowledgeRegulatory> regulatory Regulatory information about a medication */
        public array $regulatory = [],
        /** @var array<MedicationKnowledgeKinetics> kinetics The time course of drug absorption, distribution, metabolism and excretion of a medication from the body */
        public array $kinetics = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
