<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Invoice
 *
 * @description Invoice containing collected ChargeItems from an Account with calculated individual and total price for Billing purpose.
 */
#[FhirResource(type: 'Invoice', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Invoice', fhirVersion: 'R4B')]
class FHIRInvoice extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business Identifier for item */
        public array $identifier = [],
        /** @var FHIRInvoiceStatusType|null status draft | issued | balanced | cancelled | entered-in-error */
        #[NotBlank]
        public ?FHIRInvoiceStatusType $status = null,
        /** @var FHIRString|string|null cancelledReason Reason for cancellation of this Invoice */
        public FHIRString|string|null $cancelledReason = null,
        /** @var FHIRCodeableConcept|null type Type of Invoice */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRReference|null subject Recipient(s) of goods and services */
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null recipient Recipient of this invoice */
        public ?FHIRReference $recipient = null,
        /** @var FHIRDateTime|null date Invoice date / posting date */
        public ?FHIRDateTime $date = null,
        /** @var array<FHIRInvoiceParticipant> participant Participant in creation of this Invoice */
        public array $participant = [],
        /** @var FHIRReference|null issuer Issuing Organization of Invoice */
        public ?FHIRReference $issuer = null,
        /** @var FHIRReference|null account Account that is being balanced */
        public ?FHIRReference $account = null,
        /** @var array<FHIRInvoiceLineItem> lineItem Line items of this Invoice */
        public array $lineItem = [],
        /** @var array<FHIRInvoiceLineItemPriceComponent> totalPriceComponent Components of Invoice total */
        public array $totalPriceComponent = [],
        /** @var FHIRMoney|null totalNet Net total of this Invoice */
        public ?FHIRMoney $totalNet = null,
        /** @var FHIRMoney|null totalGross Gross total of this Invoice */
        public ?FHIRMoney $totalGross = null,
        /** @var FHIRMarkdown|null paymentTerms Payment details */
        public ?FHIRMarkdown $paymentTerms = null,
        /** @var array<FHIRAnnotation> note Comments made about the invoice */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
