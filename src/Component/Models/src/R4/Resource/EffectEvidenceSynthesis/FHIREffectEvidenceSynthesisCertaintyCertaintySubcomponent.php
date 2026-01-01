<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description A description of a component of the overall certainty.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'EffectEvidenceSynthesis',
	elementPath: 'EffectEvidenceSynthesis.certainty.certaintySubcomponent',
	fhirVersion: 'R4',
)]
class FHIREffectEvidenceSynthesisCertaintyCertaintySubcomponent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type Type of subcomponent of certainty rating */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> rating Subcomponent certainty rating */
		public array $rating = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation> note Used for footnotes or explanatory notes */
		public array $note = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
