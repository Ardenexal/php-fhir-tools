<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description A description of the precision of the estimate for the effect.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'EffectEvidenceSynthesis',
	elementPath: 'EffectEvidenceSynthesis.effectEstimate.precisionEstimate',
	fhirVersion: 'R4',
)]
class FHIREffectEvidenceSynthesisEffectEstimatePrecisionEstimate extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type Type of precision estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal level Level of confidence interval */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $level = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal from Lower bound */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $from = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal to Upper bound */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $to = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
