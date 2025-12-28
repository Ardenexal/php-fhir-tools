<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Details concerning the specimen collection.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Specimen', elementPath: 'Specimen.collection', fhirVersion: 'R5')]
class FHIRSpecimenCollection extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference collector Who collected the specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $collector = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod collectedX Collection time */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod|null $collectedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration duration How long it took to collect specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity quantity The quantity of specimen collected */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept method Technique used to perform collection */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference device Device used to perform collection */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference $device = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference procedure The procedure that collects the specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $procedure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference bodySite Anatomical collection site */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference $bodySite = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration fastingStatusX Whether or how long patient abstained from food and/or drink */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration|null $fastingStatusX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
