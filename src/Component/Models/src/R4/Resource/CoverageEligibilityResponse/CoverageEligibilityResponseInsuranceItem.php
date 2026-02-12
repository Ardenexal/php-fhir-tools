<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityResponse;

/**
 * @description Benefits and optionally current balances, and authorization details by category or service.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'CoverageEligibilityResponse',
	elementPath: 'CoverageEligibilityResponse.insurance.item',
	fhirVersion: 'R4',
)]
class CoverageEligibilityResponseInsuranceItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept category Benefit classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept productOrService Billing, service, product, or drug code */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $productOrService = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> modifier Product or service billing modifiers */
		public array $modifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference provider Performing practitioner */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $provider = null,
		/** @var null|bool excluded Excluded from the plan */
		public ?bool $excluded = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Short name for the benefit */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Description of the benefit or services covered */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept network In or out of network */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $network = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept unit Individual or family */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $unit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept term Annual or lifetime */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $term = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityResponse\CoverageEligibilityResponseInsuranceItemBenefit> benefit Benefit Summary */
		public array $benefit = [],
		/** @var null|bool authorizationRequired Authorization required flag */
		public ?bool $authorizationRequired = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> authorizationSupporting Type of required supporting materials */
		public array $authorizationSupporting = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive authorizationUrl Preauthorization requirements endpoint */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $authorizationUrl = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
