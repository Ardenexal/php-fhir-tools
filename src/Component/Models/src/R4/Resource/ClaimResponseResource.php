<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimUseType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseAddItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseError;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseInsurance;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseItemAdjudication;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponsePayment;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseProcessNote;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse\ClaimResponseTotal;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ClaimResponse
 *
 * @description This resource provides the adjudication details from the processing of a Claim resource.
 */
#[FhirResource(type: 'ClaimResponse', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/ClaimResponse', fhirVersion: 'R4')]
class ClaimResponseResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for a claim response */
        public array $identifier = [],
        /** @var FinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FinancialResourceStatusCodesType $status = null,
        /** @var CodeableConcept|null type More granular claim type */
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
        /** @var DateTimePrimitive|null created Response creation date */
        #[NotBlank]
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null insurer Party responsible for reimbursement */
        #[NotBlank]
        public ?Reference $insurer = null,
        /** @var Reference|null requestor Party responsible for the claim */
        public ?Reference $requestor = null,
        /** @var Reference|null request Id of resource triggering adjudication */
        public ?Reference $request = null,
        /** @var ClaimProcessingCodesType|null outcome queued | complete | error | partial */
        #[NotBlank]
        public ?ClaimProcessingCodesType $outcome = null,
        /** @var StringPrimitive|string|null disposition Disposition Message */
        public StringPrimitive|string|null $disposition = null,
        /** @var StringPrimitive|string|null preAuthRef Preauthorization reference */
        public StringPrimitive|string|null $preAuthRef = null,
        /** @var Period|null preAuthPeriod Preauthorization reference effective period */
        public ?Period $preAuthPeriod = null,
        /** @var CodeableConcept|null payeeType Party to be paid any benefits payable */
        public ?CodeableConcept $payeeType = null,
        /** @var array<ClaimResponseItem> item Adjudication for claim line items */
        public array $item = [],
        /** @var array<ClaimResponseAddItem> addItem Insurer added line items */
        public array $addItem = [],
        /** @var array<ClaimResponseItemAdjudication> adjudication Header-level adjudication */
        public array $adjudication = [],
        /** @var array<ClaimResponseTotal> total Adjudication totals */
        public array $total = [],
        /** @var ClaimResponsePayment|null payment Payment Details */
        public ?ClaimResponsePayment $payment = null,
        /** @var CodeableConcept|null fundsReserve Funds reserved status */
        public ?CodeableConcept $fundsReserve = null,
        /** @var CodeableConcept|null formCode Printed form identifier */
        public ?CodeableConcept $formCode = null,
        /** @var Attachment|null form Printed reference or actual form */
        public ?Attachment $form = null,
        /** @var array<ClaimResponseProcessNote> processNote Note concerning adjudication */
        public array $processNote = [],
        /** @var array<Reference> communicationRequest Request for additional information */
        public array $communicationRequest = [],
        /** @var array<ClaimResponseInsurance> insurance Patient insurance information */
        public array $insurance = [],
        /** @var array<ClaimResponseError> error Processing errors */
        public array $error = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
