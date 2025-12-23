<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Specimen.processing
 * @description Details concerning processing and processing steps for the specimen.
 */
class FHIRSpecimenProcessing extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string description Textual description of procedure */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept method Indicates the treatment step  applied to the specimen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $method = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> additive Material used in the processing step */
		public array $additive = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod timeX Date and time of specimen processing */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|null $timeX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
