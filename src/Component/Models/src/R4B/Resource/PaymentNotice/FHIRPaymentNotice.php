<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/PaymentNotice
 * @description This resource provides the status of the payment for goods and services rendered, and the request and response resource references.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'PaymentNotice',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/PaymentNotice',
	fhirVersion: 'R4B',
)]
class FHIRPaymentNotice extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Business Identifier for the payment noctice */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRFinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRFinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference request Request reference */
		public ?FHIRReference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference response Response reference */
		public ?FHIRReference $response = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime created Creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDateTime $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference provider Responsible practitioner */
		public ?FHIRReference $provider = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference payment Payment reference */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $payment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate paymentDate Payment or clearing date */
		public ?FHIRDate $paymentDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference payee Party being paid */
		public ?FHIRReference $payee = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference recipient Party being notified */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $recipient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMoney amount Monetary amount of the payment */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRMoney $amount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept paymentStatus Issued or cleared Status of the payment */
		public ?FHIRCodeableConcept $paymentStatus = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
