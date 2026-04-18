<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\MedicationKnowledgeStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeCost;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeDefinitional;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeIndicationGuideline;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeMedicineClassification;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeMonitoringProgram;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeMonograph;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgePackaging;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeRegulatory;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeRelatedMedicationKnowledge;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeStorageGuideline;

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
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
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
        /** @var array<Identifier> identifier Business identifier for this medication */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var CodeableConcept|null code Code that identifies this medication */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $code = null,
        /** @var MedicationKnowledgeStatusCodesType|null status active | entered-in-error | inactive */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?MedicationKnowledgeStatusCodesType $status = null,
        /** @var Reference|null author Creator or owner of the knowledge or information about the medication */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $author = null,
        /** @var array<CodeableConcept> intendedJurisdiction Codes that identify the different jurisdictions for which the information of this resource was created */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $intendedJurisdiction = [],
        /** @var array<StringPrimitive|string> name A name associated with the medication being described */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $name = [],
        /** @var array<MedicationKnowledgeRelatedMedicationKnowledge> relatedMedicationKnowledge Associated or related medication information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeRelatedMedicationKnowledge',
        )]
        public array $relatedMedicationKnowledge = [],
        /** @var array<Reference> associatedMedication The set of medication resources that are associated with this medication */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $associatedMedication = [],
        /** @var array<CodeableConcept> productType Category of the medication or product */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $productType = [],
        /** @var array<MedicationKnowledgeMonograph> monograph Associated documentation about the medication */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeMonograph',
        )]
        public array $monograph = [],
        /** @var MarkdownPrimitive|null preparationInstruction The instructions for preparing the medication */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $preparationInstruction = null,
        /** @var array<MedicationKnowledgeCost> cost The pricing of the medication */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeCost',
        )]
        public array $cost = [],
        /** @var array<MedicationKnowledgeMonitoringProgram> monitoringProgram Program under which a medication is reviewed */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeMonitoringProgram',
        )]
        public array $monitoringProgram = [],
        /** @var array<MedicationKnowledgeIndicationGuideline> indicationGuideline Guidelines or protocols for administration of the medication for an indication */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeIndicationGuideline',
        )]
        public array $indicationGuideline = [],
        /** @var array<MedicationKnowledgeMedicineClassification> medicineClassification Categorization of the medication within a formulary or classification system */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeMedicineClassification',
        )]
        public array $medicineClassification = [],
        /** @var array<MedicationKnowledgePackaging> packaging Details about packaged medications */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgePackaging',
        )]
        public array $packaging = [],
        /** @var array<Reference> clinicalUseIssue Potential clinical issue with or between medication(s) */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $clinicalUseIssue = [],
        /** @var array<MedicationKnowledgeStorageGuideline> storageGuideline How the medication should be stored */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeStorageGuideline',
        )]
        public array $storageGuideline = [],
        /** @var array<MedicationKnowledgeRegulatory> regulatory Regulatory information about a medication */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeRegulatory',
        )]
        public array $regulatory = [],
        /** @var MedicationKnowledgeDefinitional|null definitional Minimal definition information about the medication */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?MedicationKnowledgeDefinitional $definitional = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
