<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ClaimUseType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ExplanationOfBenefitStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\RemittanceOutcomeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitAccident;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitAddItem;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitBenefitBalance;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitCareTeam;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitDiagnosis;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitInsurance;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitItem;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemAdjudication;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitPayee;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitPayment;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitProcedure;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitProcessNote;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitRelated;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitSupportingInfo;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitTotal;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ExplanationOfBenefit
 *
 * @description This resource provides: the claim details; adjudication details from the processing of a Claim; and optionally account balance information, for informing the subscriber of the benefits provided.
 */
#[FhirResource(
    type: 'ExplanationOfBenefit',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ExplanationOfBenefit',
    fhirVersion: 'R4B',
)]
class ExplanationOfBenefitResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for the resource */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var ExplanationOfBenefitStatusType|null status active | cancelled | draft | entered-in-error */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?ExplanationOfBenefitStatusType $status = null,
        /** @var CodeableConcept|null type Category or discipline */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null subType More granular claim type */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $subType = null,
        /** @var ClaimUseType|null use claim | preauthorization | predetermination */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?ClaimUseType $use = null,
        /** @var Reference|null patient The recipient of the products and services */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $patient = null,
        /** @var Period|null billablePeriod Relevant time frame for the claim */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $billablePeriod = null,
        /** @var DateTimePrimitive|null created Response creation date */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null enterer Author of the claim */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $enterer = null,
        /** @var Reference|null insurer Party responsible for reimbursement */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $insurer = null,
        /** @var Reference|null provider Party responsible for the claim */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $provider = null,
        /** @var CodeableConcept|null priority Desired processing urgency */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $priority = null,
        /** @var CodeableConcept|null fundsReserveRequested For whom to reserve funds */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $fundsReserveRequested = null,
        /** @var CodeableConcept|null fundsReserve Funds reserved status */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $fundsReserve = null,
        /** @var array<ExplanationOfBenefitRelated> related Prior or corollary claims */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitRelated',
        )]
        public array $related = [],
        /** @var Reference|null prescription Prescription authorizing services or products */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $prescription = null,
        /** @var Reference|null originalPrescription Original prescription if superceded by fulfiller */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $originalPrescription = null,
        /** @var ExplanationOfBenefitPayee|null payee Recipient of benefits payable */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?ExplanationOfBenefitPayee $payee = null,
        /** @var Reference|null referral Treatment Referral */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $referral = null,
        /** @var Reference|null facility Servicing Facility */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $facility = null,
        /** @var Reference|null claim Claim reference */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $claim = null,
        /** @var Reference|null claimResponse Claim response reference */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $claimResponse = null,
        /** @var RemittanceOutcomeType|null outcome queued | complete | error | partial */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?RemittanceOutcomeType $outcome = null,
        /** @var StringPrimitive|string|null disposition Disposition Message */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $disposition = null,
        /** @var array<StringPrimitive|string> preAuthRef Preauthorization reference */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $preAuthRef = [],
        /** @var array<Period> preAuthRefPeriod Preauthorization in-effect period */
        #[FhirProperty(
            fhirType: 'Period',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
        )]
        public array $preAuthRefPeriod = [],
        /** @var array<ExplanationOfBenefitCareTeam> careTeam Care Team members */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitCareTeam',
        )]
        public array $careTeam = [],
        /** @var array<ExplanationOfBenefitSupportingInfo> supportingInfo Supporting information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitSupportingInfo',
        )]
        public array $supportingInfo = [],
        /** @var array<ExplanationOfBenefitDiagnosis> diagnosis Pertinent diagnosis information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitDiagnosis',
        )]
        public array $diagnosis = [],
        /** @var array<ExplanationOfBenefitProcedure> procedure Clinical procedures performed */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitProcedure',
        )]
        public array $procedure = [],
        /** @var PositiveIntPrimitive|null precedence Precedence (primary, secondary, etc.) */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $precedence = null,
        /** @var array<ExplanationOfBenefitInsurance> insurance Patient insurance information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            isRequired: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitInsurance',
        )]
        public array $insurance = [],
        /** @var ExplanationOfBenefitAccident|null accident Details of the event */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?ExplanationOfBenefitAccident $accident = null,
        /** @var array<ExplanationOfBenefitItem> item Product or service provided */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitItem',
        )]
        public array $item = [],
        /** @var array<ExplanationOfBenefitAddItem> addItem Insurer added line items */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitAddItem',
        )]
        public array $addItem = [],
        /** @var array<ExplanationOfBenefitItemAdjudication> adjudication Header-level adjudication */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemAdjudication',
        )]
        public array $adjudication = [],
        /** @var array<ExplanationOfBenefitTotal> total Adjudication totals */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitTotal',
        )]
        public array $total = [],
        /** @var ExplanationOfBenefitPayment|null payment Payment Details */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?ExplanationOfBenefitPayment $payment = null,
        /** @var CodeableConcept|null formCode Printed form identifier */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $formCode = null,
        /** @var Attachment|null form Printed reference or actual form */
        #[FhirProperty(fhirType: 'Attachment', propertyKind: 'complex')]
        public ?Attachment $form = null,
        /** @var array<ExplanationOfBenefitProcessNote> processNote Note concerning adjudication */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitProcessNote',
        )]
        public array $processNote = [],
        /** @var Period|null benefitPeriod When the benefits are applicable */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $benefitPeriod = null,
        /** @var array<ExplanationOfBenefitBenefitBalance> benefitBalance Balance by Benefit Category */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExplanationOfBenefit\ExplanationOfBenefitBenefitBalance',
        )]
        public array $benefitBalance = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
