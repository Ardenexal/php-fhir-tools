<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/CoverageEligibilityRequest
 * @description The CoverageEligibilityRequest provides patient and insurance coverage information to an insurer for them to respond, in the form of an CoverageEligibilityResponse, with information regarding whether the stated coverage is valid and in-force and optionally to provide the insurance details of the policy.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'CoverageEligibilityRequest',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/CoverageEligibilityRequest',
	fhirVersion: 'R4',
)]
class CoverageEligibilityRequestResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier for coverage eligiblity request */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept priority Desired processing priority */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $priority = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\EligibilityRequestPurposeType> purpose auth-requirements | benefits | discovery | validation */
		public array $purpose = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference patient Intended recipient of products and services */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period servicedX Estimated date or dates of service */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|null $servicedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive created Creation date */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference enterer Author */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $enterer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference provider Party responsible for the request */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $provider = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference insurer Coverage issuer */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $insurer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference facility Servicing facility */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $facility = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityRequest\CoverageEligibilityRequestSupportingInfo> supportingInfo Supporting information */
		public array $supportingInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityRequest\CoverageEligibilityRequestInsurance> insurance Patient insurance information */
		public array $insurance = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityRequest\CoverageEligibilityRequestItem> item Item to be evaluated for eligibiity */
		public array $item = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
