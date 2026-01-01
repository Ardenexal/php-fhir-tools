<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Categorization of the medication within a formulary or classification system.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.medicineClassification', fhirVersion: 'R5')]
class FHIRMedicationKnowledgeMedicineClassification extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type The type of category for the medication (for example, therapeutic classification, therapeutic sub-classification) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri sourceX The source of the classification */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri|null $sourceX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> classification Specific category assigned to the medication */
		public array $classification = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
