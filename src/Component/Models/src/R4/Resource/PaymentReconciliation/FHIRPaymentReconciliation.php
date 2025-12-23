<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/PaymentReconciliation
 * @description This resource provides the details including amount of a payment and allocates the payment items being paid.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'PaymentReconciliation',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/PaymentReconciliation',
	fhirVersion: 'R4',
)]
class FHIRPaymentReconciliation extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Business Identifier for a payment reconciliation */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRFinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRFinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod period Period covered */
		public ?FHIRPeriod $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime created Creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDateTime $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference paymentIssuer Party generating payment */
		public ?FHIRReference $paymentIssuer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference request Reference to requesting resource */
		public ?FHIRReference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference requestor Responsible practitioner */
		public ?FHIRReference $requestor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRClaimProcessingCodesType outcome queued | complete | error | partial */
		public ?FHIRClaimProcessingCodesType $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string disposition Disposition message */
		public FHIRString|string|null $disposition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDate paymentDate When payment issued */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDate $paymentDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMoney paymentAmount Total amount of Payment */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRMoney $paymentAmount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier paymentIdentifier Business identifier for the payment */
		public ?FHIRIdentifier $paymentIdentifier = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPaymentReconciliationDetail> detail Settlement particulars */
		public array $detail = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept formCode Printed form identifier */
		public ?FHIRCodeableConcept $formCode = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPaymentReconciliationProcessNote> processNote Note concerning processing */
		public array $processNote = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
