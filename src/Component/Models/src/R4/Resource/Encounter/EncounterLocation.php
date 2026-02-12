<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter;

/**
 * @description List of locations where  the patient has been during this encounter.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.location', fhirVersion: 'R4')]
class EncounterLocation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference location Location the encounter takes place */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\EncounterLocationStatusType status planned | active | reserved | completed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\EncounterLocationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept physicalType The physical type of the location (usually the level in the location hierachy - bed room ward etc.) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $physicalType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Time period during which the patient was present at the location */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
