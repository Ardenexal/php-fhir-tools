<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPharmaceutical;

/**
 * @description Characteristics e.g. a products onset of action.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'MedicinalProductPharmaceutical',
	elementPath: 'MedicinalProductPharmaceutical.characteristics',
	fhirVersion: 'R4',
)]
class MedicinalProductPharmaceuticalCharacteristics extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code A coded characteristic */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept status The status of characteristic e.g. assigned or pending */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $status = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
