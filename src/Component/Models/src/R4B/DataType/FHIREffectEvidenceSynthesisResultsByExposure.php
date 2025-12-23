<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element EffectEvidenceSynthesis.resultsByExposure
 * @description A description of the results for each exposure considered in the effect estimate.
 */
class FHIREffectEvidenceSynthesisResultsByExposure extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string description Description of results by exposure */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExposureStateType exposureState exposure | exposure-alternative */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExposureStateType $exposureState = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept variantState Variant exposure states */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $variantState = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference riskEvidenceSynthesis Risk evidence synthesis */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $riskEvidenceSynthesis = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
