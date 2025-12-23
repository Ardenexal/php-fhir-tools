<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-complex-type Dosage.doseAndRate
 * @description Depending on the resource,this is the amount of medication administered, to  be administered or typical amount to be administered.
 */
class FHIRDosageDoseAndRate extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type The kind of dose or rate specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity doseX Amount of medication per dose */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|null $doseX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity rateX Amount of medication per unit of time */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|null $rateX = null,
	) {
		parent::__construct($id, $extension);
	}
}
