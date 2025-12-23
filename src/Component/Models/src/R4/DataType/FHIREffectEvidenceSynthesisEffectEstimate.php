<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element EffectEvidenceSynthesis.effectEstimate
 * @description The estimated effect of the exposure variant.
 */
class FHIREffectEvidenceSynthesisEffectEstimate extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description Description of effect estimate */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Type of efffect estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept variantState Variant exposure states */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $variantState = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal value Point estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept unitOfMeasure What unit is the outcome described in? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $unitOfMeasure = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREffectEvidenceSynthesisEffectEstimatePrecisionEstimate> precisionEstimate How precise the estimate is */
		public array $precisionEstimate = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
