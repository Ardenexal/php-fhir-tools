<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/PaymentNotice
 *
 * @description This resource provides the status of the payment for goods and services rendered, and the request and response resource references.
 */
#[FhirResource(type: 'PaymentNotice', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/PaymentNotice', fhirVersion: 'R4')]
class PaymentNoticeResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for the payment noctice */
        public array $identifier = [],
        /** @var FinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FinancialResourceStatusCodesType $status = null,
        /** @var Reference|null request Request reference */
        public ?Reference $request = null,
        /** @var Reference|null response Response reference */
        public ?Reference $response = null,
        /** @var DateTimePrimitive|null created Creation date */
        #[NotBlank]
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null provider Responsible practitioner */
        public ?Reference $provider = null,
        /** @var Reference|null payment Payment reference */
        #[NotBlank]
        public ?Reference $payment = null,
        /** @var DatePrimitive|null paymentDate Payment or clearing date */
        public ?DatePrimitive $paymentDate = null,
        /** @var Reference|null payee Party being paid */
        public ?Reference $payee = null,
        /** @var Reference|null recipient Party being notified */
        #[NotBlank]
        public ?Reference $recipient = null,
        /** @var Money|null amount Monetary amount of the payment */
        #[NotBlank]
        public ?Money $amount = null,
        /** @var CodeableConcept|null paymentStatus Issued or cleared Status of the payment */
        public ?CodeableConcept $paymentStatus = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
