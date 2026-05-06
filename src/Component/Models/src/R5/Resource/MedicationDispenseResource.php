<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Dosage;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\MedicationDispenseStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationDispense\MedicationDispensePerformer;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationDispense\MedicationDispenseSubstitution;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicationDispense
 *
 * @description Indicates that a medication product is to be or has been dispensed for a named person/patient.  This includes a description of the medication product (supply) provided and the instructions for administering the medication.  The medication dispense is the result of a pharmacy system responding to a medication order.
 */
#[FhirResource(
    type: 'MedicationDispense',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationDispense',
    fhirVersion: 'R5',
)]
class MedicationDispenseResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External identifier */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var array<Reference> basedOn Plan that is fulfilled by this dispense */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $basedOn = [],
        /** @var array<Reference> partOf Event that dispense is part of */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $partOf = [],
        /** @var MedicationDispenseStatusCodesType|null status preparation | in-progress | cancelled | on-hold | completed | entered-in-error | stopped | declined | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?MedicationDispenseStatusCodesType $status = null,
        /** @var CodeableReference|null notPerformedReason Why a dispense was not performed */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $notPerformedReason = null,
        /** @var DateTimePrimitive|null statusChanged When the status changed */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $statusChanged = null,
        /** @var array<CodeableConcept> category Type of medication dispense */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $category = [],
        /** @var CodeableReference|null medication What medication was supplied */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableReference $medication = null,
        /** @var Reference|null subject Who the dispense is for */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter associated with event */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $encounter = null,
        /** @var array<Reference> supportingInformation Information that supports the dispensing of the medication */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $supportingInformation = [],
        /** @var array<MedicationDispensePerformer> performer Who performed event */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationDispense\MedicationDispensePerformer',
        )]
        public array $performer = [],
        /** @var Reference|null location Where the dispense occurred */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $location = null,
        /** @var array<Reference> authorizingPrescription Medication order that authorizes the dispense */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $authorizingPrescription = [],
        /** @var CodeableConcept|null type Trial fill, partial fill, emergency fill, etc */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var Quantity|null quantity Amount dispensed */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $quantity = null,
        /** @var Quantity|null daysSupply Amount of medication expressed as a timing amount */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $daysSupply = null,
        /** @var DateTimePrimitive|null recorded When the recording of the dispense started */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $recorded = null,
        /** @var DateTimePrimitive|null whenPrepared When product was packaged and reviewed */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $whenPrepared = null,
        /** @var DateTimePrimitive|null whenHandedOver When product was given out */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $whenHandedOver = null,
        /** @var Reference|null destination Where the medication was/will be sent */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $destination = null,
        /** @var array<Reference> receiver Who collected the medication or where the medication was delivered */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $receiver = [],
        /** @var array<Annotation> note Information about the dispense */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation',
        )]
        public array $note = [],
        /** @var MarkdownPrimitive|null renderedDosageInstruction Full representation of the dosage instructions */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $renderedDosageInstruction = null,
        /** @var array<Dosage> dosageInstruction How the medication is to be used by the patient or administered by the caregiver */
        #[FhirProperty(
            fhirType: 'Dosage',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Dosage',
        )]
        public array $dosageInstruction = [],
        /** @var MedicationDispenseSubstitution|null substitution Whether a substitution was performed on the dispense */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?MedicationDispenseSubstitution $substitution = null,
        /** @var array<Reference> eventHistory A list of relevant lifecycle events */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $eventHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
