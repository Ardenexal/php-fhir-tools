<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/PaymentReconciliation
 * @description This resource provides the details including amount of a payment and allocates the payment items being paid.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'PaymentReconciliation',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/PaymentReconciliation',
	fhirVersion: 'R5',
)]
class FHIRPaymentReconciliation extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business Identifier for a payment reconciliation */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Category of payment */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRFinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRFinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept kind Workflow originating payment */
		public ?FHIRCodeableConcept $kind = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod period Period covered */
		public ?FHIRPeriod $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime created Creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDateTime $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference enterer Who entered the payment */
		public ?FHIRReference $enterer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept issuerType Nature of the source */
		public ?FHIRCodeableConcept $issuerType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference paymentIssuer Party generating payment */
		public ?FHIRReference $paymentIssuer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference request Reference to requesting resource */
		public ?FHIRReference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference requestor Responsible practitioner */
		public ?FHIRReference $requestor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPaymentOutcomeType outcome queued | complete | error | partial */
		public ?FHIRPaymentOutcomeType $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string disposition Disposition message */
		public FHIRString|string|null $disposition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate date When payment issued */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference location Where payment collected */
		public ?FHIRReference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept method Payment instrument */
		public ?FHIRCodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string cardBrand Type of card */
		public FHIRString|string|null $cardBrand = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string accountNumber Digits for verification */
		public FHIRString|string|null $accountNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate expirationDate Expiration year-month */
		public ?FHIRDate $expirationDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string processor Processor name */
		public FHIRString|string|null $processor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string referenceNumber Check number or payment reference */
		public FHIRString|string|null $referenceNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string authorization Authorization number */
		public FHIRString|string|null $authorization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney tenderedAmount Amount offered by the issuer */
		public ?FHIRMoney $tenderedAmount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney returnedAmount Amount returned by the receiver */
		public ?FHIRMoney $returnedAmount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMoney amount Total amount of Payment */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRMoney $amount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier paymentIdentifier Business identifier for the payment */
		public ?FHIRIdentifier $paymentIdentifier = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPaymentReconciliationAllocation> allocation Settlement particulars */
		public array $allocation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept formCode Printed form identifier */
		public ?FHIRCodeableConcept $formCode = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPaymentReconciliationProcessNote> processNote Note concerning processing */
		public array $processNote = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
