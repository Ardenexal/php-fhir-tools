<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/Coverage
 * @description Financial instrument which may be used to reimburse or pay for health care products and services. Includes both insurance and self-payment.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Coverage', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Coverage', fhirVersion: 'R4')]
class FHIRCoverage extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier Business Identifier for the coverage */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRFinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRFinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type Coverage category such as medical or accident */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference policyHolder Owner of the policy */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $policyHolder = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference subscriber Subscriber to the policy */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $subscriber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string subscriberId ID assigned to the subscriber */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $subscriberId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference beneficiary Plan beneficiary */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $beneficiary = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string dependent Dependent number */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $dependent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept relationship Beneficiary relationship to the subscriber */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $relationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod period Coverage start and end dates */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod $period = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> payor Issuer of the policy */
		public array $payor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoverageClass> class Additional coverage classifications */
		public array $class = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt order Relative order of the coverage */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt $order = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string network Insurer network */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $network = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoverageCostToBeneficiary> costToBeneficiary Patient payments for services/products */
		public array $costToBeneficiary = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean subrogation Reimbursement to insurer */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean $subrogation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> contract Contract details */
		public array $contract = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
