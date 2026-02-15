<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimUseType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimAccident;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimCareTeam;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimDiagnosis;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimInsurance;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimPayee;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimProcedure;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimRelated;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimSupportingInfo;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Claim
 *
 * @description A provider issued list of professional services and products which have been provided, or are to be provided, to a patient which is sent to an insurer for reimbursement.
 */
#[FhirResource(type: 'Claim', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Claim', fhirVersion: 'R4')]
class ClaimResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for claim */
        public array $identifier = [],
        /** @var FinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FinancialResourceStatusCodesType $status = null,
        /** @var CodeableConcept|null type Category or discipline */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null subType More granular claim type */
        public ?CodeableConcept $subType = null,
        /** @var ClaimUseType|null use claim | preauthorization | predetermination */
        #[NotBlank]
        public ?ClaimUseType $use = null,
        /** @var Reference|null patient The recipient of the products and services */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var Period|null billablePeriod Relevant time frame for the claim */
        public ?Period $billablePeriod = null,
        /** @var DateTimePrimitive|null created Resource creation date */
        #[NotBlank]
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null enterer Author of the claim */
        public ?Reference $enterer = null,
        /** @var Reference|null insurer Target */
        public ?Reference $insurer = null,
        /** @var Reference|null provider Party responsible for the claim */
        #[NotBlank]
        public ?Reference $provider = null,
        /** @var CodeableConcept|null priority Desired processing ugency */
        #[NotBlank]
        public ?CodeableConcept $priority = null,
        /** @var CodeableConcept|null fundsReserve For whom to reserve funds */
        public ?CodeableConcept $fundsReserve = null,
        /** @var array<ClaimRelated> related Prior or corollary claims */
        public array $related = [],
        /** @var Reference|null prescription Prescription authorizing services and products */
        public ?Reference $prescription = null,
        /** @var Reference|null originalPrescription Original prescription if superseded by fulfiller */
        public ?Reference $originalPrescription = null,
        /** @var ClaimPayee|null payee Recipient of benefits payable */
        public ?ClaimPayee $payee = null,
        /** @var Reference|null referral Treatment referral */
        public ?Reference $referral = null,
        /** @var Reference|null facility Servicing facility */
        public ?Reference $facility = null,
        /** @var array<ClaimCareTeam> careTeam Members of the care team */
        public array $careTeam = [],
        /** @var array<ClaimSupportingInfo> supportingInfo Supporting information */
        public array $supportingInfo = [],
        /** @var array<ClaimDiagnosis> diagnosis Pertinent diagnosis information */
        public array $diagnosis = [],
        /** @var array<ClaimProcedure> procedure Clinical procedures performed */
        public array $procedure = [],
        /** @var array<ClaimInsurance> insurance Patient insurance information */
        public array $insurance = [],
        /** @var ClaimAccident|null accident Details of the event */
        public ?ClaimAccident $accident = null,
        /** @var array<ClaimItem> item Product or service provided */
        public array $item = [],
        /** @var Money|null total Total claim cost */
        public ?Money $total = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
