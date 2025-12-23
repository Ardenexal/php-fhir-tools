<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ClinicalUseDefinition.interaction
 * @description Specifics for when this is an interaction.
 */
class FHIRClinicalUseDefinitionInteraction extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClinicalUseDefinitionInteractionInteractant> interactant The specific medication, product, food etc. or laboratory test that interacts */
		public array $interactant = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type The type of the interaction e.g. drug-drug interaction, drug-lab test interaction */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference effect The effect of the interaction, for example "reduced gastric absorption of primary medication" */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference $effect = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept incidence The incidence of the interaction, e.g. theoretical, observed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $incidence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> management Actions for managing the interaction */
		public array $management = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
