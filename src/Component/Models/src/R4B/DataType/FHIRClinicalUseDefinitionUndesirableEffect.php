<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ClinicalUseDefinition.undesirableEffect
 * @description Describe the possible undesirable effects (negative outcomes) from the use of the medicinal product as treatment.
 */
class FHIRClinicalUseDefinitionUndesirableEffect extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference symptomConditionEffect The situation in which the undesirable effect may manifest */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference $symptomConditionEffect = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept classification High level classification of the effect */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $classification = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept frequencyOfOccurrence How often the effect is seen */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $frequencyOfOccurrence = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
