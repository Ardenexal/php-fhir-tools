<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\InvoiceStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Invoice\InvoiceLineItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Invoice\InvoiceLineItemPriceComponent;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Invoice\InvoiceParticipant;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Invoice
 *
 * @description Invoice containing collected ChargeItems from an Account with calculated individual and total price for Billing purpose.
 */
#[FhirResource(type: 'Invoice', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Invoice', fhirVersion: 'R4')]
class InvoiceResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for item */
        public array $identifier = [],
        /** @var InvoiceStatusType|null status draft | issued | balanced | cancelled | entered-in-error */
        #[NotBlank]
        public ?InvoiceStatusType $status = null,
        /** @var StringPrimitive|string|null cancelledReason Reason for cancellation of this Invoice */
        public StringPrimitive|string|null $cancelledReason = null,
        /** @var CodeableConcept|null type Type of Invoice */
        public ?CodeableConcept $type = null,
        /** @var Reference|null subject Recipient(s) of goods and services */
        public ?Reference $subject = null,
        /** @var Reference|null recipient Recipient of this invoice */
        public ?Reference $recipient = null,
        /** @var DateTimePrimitive|null date Invoice date / posting date */
        public ?DateTimePrimitive $date = null,
        /** @var array<InvoiceParticipant> participant Participant in creation of this Invoice */
        public array $participant = [],
        /** @var Reference|null issuer Issuing Organization of Invoice */
        public ?Reference $issuer = null,
        /** @var Reference|null account Account that is being balanced */
        public ?Reference $account = null,
        /** @var array<InvoiceLineItem> lineItem Line items of this Invoice */
        public array $lineItem = [],
        /** @var array<InvoiceLineItemPriceComponent> totalPriceComponent Components of Invoice total */
        public array $totalPriceComponent = [],
        /** @var Money|null totalNet Net total of this Invoice */
        public ?Money $totalNet = null,
        /** @var Money|null totalGross Gross total of this Invoice */
        public ?Money $totalGross = null,
        /** @var MarkdownPrimitive|null paymentTerms Payment details */
        public ?MarkdownPrimitive $paymentTerms = null,
        /** @var array<Annotation> note Comments made about the invoice */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
