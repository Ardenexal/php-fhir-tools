<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Additional information codes regarding exceptions, special considerations, the condition, situation, prior or concurrent issues.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.supportingInfo', fhirVersion: 'R4B')]
class FHIRExplanationOfBenefitSupportingInfo extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt sequence Information instance identifier */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt $sequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept category Classification of the supplied information */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept code Type of information */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod timingX When it occurred */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod|null $timingX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference valueX Data to be provided */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference|null $valueX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding reason Explanation for the information */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding $reason = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
