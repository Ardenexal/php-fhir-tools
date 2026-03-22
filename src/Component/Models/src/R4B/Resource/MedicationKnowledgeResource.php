<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\MedicationKnowledgeStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeAdministrationGuidelines;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeCost;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeDrugCharacteristic;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeIngredient;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeKinetics;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeMedicineClassification;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeMonitoringProgram;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeMonograph;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgePackaging;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeRegulatory;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeRelatedMedicationKnowledge;

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
class MedicationKnowledgeResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Code that identifies this medication */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $code = null,
        /** @var MedicationKnowledgeStatusCodesType|null status active | inactive | entered-in-error */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?MedicationKnowledgeStatusCodesType $status = null,
        /** @var Reference|null manufacturer Manufacturer of the item */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $manufacturer = null,
        /** @var CodeableConcept|null doseForm powder | tablets | capsule + */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $doseForm = null,
        /** @var Quantity|null amount Amount of drug in package */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $amount = null,
        /** @var array<StringPrimitive|string> synonym Additional names for a medication */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $synonym = [],
        /** @var array<MedicationKnowledgeRelatedMedicationKnowledge> relatedMedicationKnowledge Associated or related medication information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeRelatedMedicationKnowledge',
        )]
        public array $relatedMedicationKnowledge = [],
        /** @var array<Reference> associatedMedication A medication resource that is associated with this medication */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        public array $associatedMedication = [],
        /** @var array<CodeableConcept> productType Category of the medication or product */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $productType = [],
        /** @var array<MedicationKnowledgeMonograph> monograph Associated documentation about the medication */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeMonograph',
        )]
        public array $monograph = [],
        /** @var array<MedicationKnowledgeIngredient> ingredient Active or inactive ingredient */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeIngredient',
        )]
        public array $ingredient = [],
        /** @var MarkdownPrimitive|null preparationInstruction The instructions for preparing the medication */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $preparationInstruction = null,
        /** @var array<CodeableConcept> intendedRoute The intended or approved route of administration */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $intendedRoute = [],
        /** @var array<MedicationKnowledgeCost> cost The pricing of the medication */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeCost',
        )]
        public array $cost = [],
        /** @var array<MedicationKnowledgeMonitoringProgram> monitoringProgram Program under which a medication is reviewed */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeMonitoringProgram',
        )]
        public array $monitoringProgram = [],
        /** @var array<MedicationKnowledgeAdministrationGuidelines> administrationGuidelines Guidelines for administration of the medication */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeAdministrationGuidelines',
        )]
        public array $administrationGuidelines = [],
        /** @var array<MedicationKnowledgeMedicineClassification> medicineClassification Categorization of the medication within a formulary or classification system */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeMedicineClassification',
        )]
        public array $medicineClassification = [],
        /** @var MedicationKnowledgePackaging|null packaging Details about packaged medications */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?MedicationKnowledgePackaging $packaging = null,
        /** @var array<MedicationKnowledgeDrugCharacteristic> drugCharacteristic Specifies descriptive properties of the medicine */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeDrugCharacteristic',
        )]
        public array $drugCharacteristic = [],
        /** @var array<Reference> contraindication Potential clinical issue with or between medication(s) */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        public array $contraindication = [],
        /** @var array<MedicationKnowledgeRegulatory> regulatory Regulatory information about a medication */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeRegulatory',
        )]
        public array $regulatory = [],
        /** @var array<MedicationKnowledgeKinetics> kinetics The time course of drug absorption, distribution, metabolism and excretion of a medication from the body */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\MedicationKnowledge\MedicationKnowledgeKinetics',
        )]
        public array $kinetics = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
