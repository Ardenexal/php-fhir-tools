<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskEvidenceSynthesis;

/**
 * @description A description of the certainty of the risk estimate.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'RiskEvidenceSynthesis', elementPath: 'RiskEvidenceSynthesis.certainty', fhirVersion: 'R4')]
class RiskEvidenceSynthesisCertainty extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> rating Certainty rating */
		public array $rating = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Used for footnotes or explanatory notes */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskEvidenceSynthesis\RiskEvidenceSynthesisCertaintyCertaintySubcomponent> certaintySubcomponent A component that contributes to the overall certainty */
		public array $certaintySubcomponent = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
