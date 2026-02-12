<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SpecimenDefinition;

/**
 * @description Set of instructions for preservation/transport of the specimen at a defined temperature interval, prior the testing process.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SpecimenDefinition', elementPath: 'SpecimenDefinition.typeTested.handling', fhirVersion: 'R4')]
class SpecimenDefinitionTypeTestedHandling extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept temperatureQualifier Temperature qualifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $temperatureQualifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range temperatureRange Temperature range */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range $temperatureRange = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration maxDuration Maximum preservation time */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration $maxDuration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string instruction Preservation instruction */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $instruction = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
