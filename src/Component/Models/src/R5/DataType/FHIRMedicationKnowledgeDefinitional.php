<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MedicationKnowledge.definitional
 * @description Along with the link to a Medicinal Product Definition resource, this information provides common definitional elements that are needed to understand the specific medication that is being described.
 */
class FHIRMedicationKnowledgeDefinitional extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> definition Definitional resources that provide more information about this medication */
		public array $definition = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept doseForm powder | tablets | capsule + */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $doseForm = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> intendedRoute The intended or approved route of administration */
		public array $intendedRoute = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeDefinitionalIngredient> ingredient Active or inactive ingredient */
		public array $ingredient = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeDefinitionalDrugCharacteristic> drugCharacteristic Specifies descriptive properties of the medicine */
		public array $drugCharacteristic = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
