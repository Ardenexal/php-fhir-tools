<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PaymentReconciliation\PaymentReconciliationDetail;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PaymentReconciliation\PaymentReconciliationProcessNote;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/PaymentReconciliation
 *
 * @description This resource provides the details including amount of a payment and allocates the payment items being paid.
 */
#[FhirResource(
    type: 'PaymentReconciliation',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/PaymentReconciliation',
    fhirVersion: 'R4',
)]
class PaymentReconciliationResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for a payment reconciliation */
        public array $identifier = [],
        /** @var FinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FinancialResourceStatusCodesType $status = null,
        /** @var Period|null period Period covered */
        public ?Period $period = null,
        /** @var DateTimePrimitive|null created Creation date */
        #[NotBlank]
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null paymentIssuer Party generating payment */
        public ?Reference $paymentIssuer = null,
        /** @var Reference|null request Reference to requesting resource */
        public ?Reference $request = null,
        /** @var Reference|null requestor Responsible practitioner */
        public ?Reference $requestor = null,
        /** @var ClaimProcessingCodesType|null outcome queued | complete | error | partial */
        public ?ClaimProcessingCodesType $outcome = null,
        /** @var StringPrimitive|string|null disposition Disposition message */
        public StringPrimitive|string|null $disposition = null,
        /** @var DatePrimitive|null paymentDate When payment issued */
        #[NotBlank]
        public ?DatePrimitive $paymentDate = null,
        /** @var Money|null paymentAmount Total amount of Payment */
        #[NotBlank]
        public ?Money $paymentAmount = null,
        /** @var Identifier|null paymentIdentifier Business identifier for the payment */
        public ?Identifier $paymentIdentifier = null,
        /** @var array<PaymentReconciliationDetail> detail Settlement particulars */
        public array $detail = [],
        /** @var CodeableConcept|null formCode Printed form identifier */
        public ?CodeableConcept $formCode = null,
        /** @var array<PaymentReconciliationProcessNote> processNote Note concerning processing */
        public array $processNote = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
