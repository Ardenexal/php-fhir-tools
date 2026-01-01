<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Along with the link to a Medicinal Product Definition resource, this information provides common definitional elements that are needed to understand the specific medication that is being described.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.definitional', fhirVersion: 'R5')]
class FHIRMedicationKnowledgeDefinitional extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> definition Definitional resources that provide more information about this medication */
		public array $definition = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept doseForm powder | tablets | capsule + */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $doseForm = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> intendedRoute The intended or approved route of administration */
		public array $intendedRoute = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeDefinitionalIngredient> ingredient Active or inactive ingredient */
		public array $ingredient = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeDefinitionalDrugCharacteristic> drugCharacteristic Specifies descriptive properties of the medicine */
		public array $drugCharacteristic = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
