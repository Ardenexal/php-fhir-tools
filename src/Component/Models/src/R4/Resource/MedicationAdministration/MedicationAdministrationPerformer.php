<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationAdministration;

/**
 * @description Indicates who or what performed the medication administration and how they were involved.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationAdministration', elementPath: 'MedicationAdministration.performer', fhirVersion: 'R4')]
class MedicationAdministrationPerformer extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept function Type of performance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $function = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference actor Who performed the medication administration */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $actor = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
