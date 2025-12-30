<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUseType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Claim
 *
 * @description A provider issued list of professional services and products which have been provided, or are to be provided, to a patient which is sent to an insurer for reimbursement.
 */
#[FhirResource(type: 'Claim', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Claim', fhirVersion: 'R5')]
class FHIRClaim extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business Identifier for claim */
        public array $identifier = [],
        /** @var array<FHIRIdentifier> traceNumber Number for tracking */
        public array $traceNumber = [],
        /** @var FHIRFinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FHIRFinancialResourceStatusCodesType $status = null,
        /** @var FHIRCodeableConcept|null type Category or discipline */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null subType More granular claim type */
        public ?FHIRCodeableConcept $subType = null,
        /** @var FHIRUseType|null use claim | preauthorization | predetermination */
        #[NotBlank]
        public ?FHIRUseType $use = null,
        /** @var FHIRReference|null patient The recipient of the products and services */
        #[NotBlank]
        public ?FHIRReference $patient = null,
        /** @var FHIRPeriod|null billablePeriod Relevant time frame for the claim */
        public ?FHIRPeriod $billablePeriod = null,
        /** @var FHIRDateTime|null created Resource creation date */
        #[NotBlank]
        public ?FHIRDateTime $created = null,
        /** @var FHIRReference|null enterer Author of the claim */
        public ?FHIRReference $enterer = null,
        /** @var FHIRReference|null insurer Target */
        public ?FHIRReference $insurer = null,
        /** @var FHIRReference|null provider Party responsible for the claim */
        public ?FHIRReference $provider = null,
        /** @var FHIRCodeableConcept|null priority Desired processing urgency */
        public ?FHIRCodeableConcept $priority = null,
        /** @var FHIRCodeableConcept|null fundsReserve For whom to reserve funds */
        public ?FHIRCodeableConcept $fundsReserve = null,
        /** @var array<FHIRClaimRelated> related Prior or corollary claims */
        public array $related = [],
        /** @var FHIRReference|null prescription Prescription authorizing services and products */
        public ?FHIRReference $prescription = null,
        /** @var FHIRReference|null originalPrescription Original prescription if superseded by fulfiller */
        public ?FHIRReference $originalPrescription = null,
        /** @var FHIRClaimPayee|null payee Recipient of benefits payable */
        public ?FHIRClaimPayee $payee = null,
        /** @var FHIRReference|null referral Treatment referral */
        public ?FHIRReference $referral = null,
        /** @var array<FHIRReference> encounter Encounters associated with the listed treatments */
        public array $encounter = [],
        /** @var FHIRReference|null facility Servicing facility */
        public ?FHIRReference $facility = null,
        /** @var FHIRCodeableConcept|null diagnosisRelatedGroup Package billing code */
        public ?FHIRCodeableConcept $diagnosisRelatedGroup = null,
        /** @var array<FHIRClaimEvent> event Event information */
        public array $event = [],
        /** @var array<FHIRClaimCareTeam> careTeam Members of the care team */
        public array $careTeam = [],
        /** @var array<FHIRClaimSupportingInfo> supportingInfo Supporting information */
        public array $supportingInfo = [],
        /** @var array<FHIRClaimDiagnosis> diagnosis Pertinent diagnosis information */
        public array $diagnosis = [],
        /** @var array<FHIRClaimProcedure> procedure Clinical procedures performed */
        public array $procedure = [],
        /** @var array<FHIRClaimInsurance> insurance Patient insurance information */
        public array $insurance = [],
        /** @var FHIRClaimAccident|null accident Details of the event */
        public ?FHIRClaimAccident $accident = null,
        /** @var FHIRMoney|null patientPaid Paid by the patient */
        public ?FHIRMoney $patientPaid = null,
        /** @var array<FHIRClaimItem> item Product or service provided */
        public array $item = [],
        /** @var FHIRMoney|null total Total claim cost */
        public ?FHIRMoney $total = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
