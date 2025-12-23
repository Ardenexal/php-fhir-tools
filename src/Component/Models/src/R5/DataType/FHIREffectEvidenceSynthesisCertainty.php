<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element EffectEvidenceSynthesis.certainty
 * @description A description of the certainty of the effect estimate.
 */
class FHIREffectEvidenceSynthesisCertainty extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> rating Certainty rating */
		public array $rating = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Used for footnotes or explanatory notes */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREffectEvidenceSynthesisCertaintyCertaintySubcomponent> certaintySubcomponent A component that contributes to the overall certainty */
		public array $certaintySubcomponent = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
