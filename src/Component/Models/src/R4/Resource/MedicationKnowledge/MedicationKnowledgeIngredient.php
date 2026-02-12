<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge;

/**
 * @description Identifies a particular constituent of interest in the product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.ingredient', fhirVersion: 'R4')]
class MedicationKnowledgeIngredient extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference itemX Medication(s) or substance(s) contained in the medication */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $itemX = null,
		/** @var null|bool isActive Active ingredient indicator */
		public ?bool $isActive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio strength Quantity of ingredient present */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio $strength = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
