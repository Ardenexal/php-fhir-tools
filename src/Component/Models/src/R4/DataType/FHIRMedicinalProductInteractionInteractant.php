<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element MedicinalProductInteraction.interactant
 * @description The specific medication, food or laboratory test that interacts.
 */
class FHIRMedicinalProductInteractionInteractant extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept itemX The specific medication, food or laboratory test that interacts */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept|null $itemX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
