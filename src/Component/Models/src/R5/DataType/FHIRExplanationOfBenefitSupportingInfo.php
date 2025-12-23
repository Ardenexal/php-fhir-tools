<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ExplanationOfBenefit.supportingInfo
 * @description Additional information codes regarding exceptions, special considerations, the condition, situation, prior or concurrent issues.
 */
class FHIRExplanationOfBenefitSupportingInfo extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt sequence Information instance identifier */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt $sequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept category Classification of the supplied information */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Type of information */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod timingX When it occurred */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|null $timingX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier valueX Data to be provided */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier|null $valueX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding reason Explanation for the information */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding $reason = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
