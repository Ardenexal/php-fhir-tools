<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge;

/**
 * @description The maximum number of units of the medication that can be dispensed in a period.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.regulatory.maxDispense', fhirVersion: 'R4')]
class MedicationKnowledgeRegulatoryMaxDispense extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity The maximum number of units of the medication that can be dispensed */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration period The period that applies to the maximum number of units */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration $period = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
