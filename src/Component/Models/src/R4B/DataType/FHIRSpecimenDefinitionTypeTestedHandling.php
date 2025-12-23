<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element SpecimenDefinition.typeTested.handling
 * @description Set of instructions for preservation/transport of the specimen at a defined temperature interval, prior the testing process.
 */
class FHIRSpecimenDefinitionTypeTestedHandling extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept temperatureQualifier Temperature qualifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $temperatureQualifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange temperatureRange Temperature range */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange $temperatureRange = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration maxDuration Maximum preservation time */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration $maxDuration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string instruction Preservation instruction */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $instruction = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
