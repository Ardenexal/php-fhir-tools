<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/PaymentNotice
 * @description This resource provides the status of the payment for goods and services rendered, and the request and response resource references.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'PaymentNotice', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/PaymentNotice', fhirVersion: 'R5')]
class FHIRPaymentNotice extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Business Identifier for the payment notice */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference request Request reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference response Response reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $response = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime created Creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference reporter Responsible practitioner */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $reporter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference payment Payment reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $payment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate paymentDate Payment or clearing date */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate $paymentDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference payee Party being paid */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $payee = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference recipient Party being notified */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $recipient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney amount Monetary amount of the payment */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney $amount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept paymentStatus Issued or cleared Status of the payment */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $paymentStatus = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
