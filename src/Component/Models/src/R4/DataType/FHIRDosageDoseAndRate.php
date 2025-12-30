<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @description The amount of medication administered.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Dosage.doseAndRate', fhirVersion: 'R4')]
class FHIRDosageDoseAndRate extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type The kind of dose or rate specified */
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity doseX Amount of medication per dose */
		public FHIRRange|FHIRQuantity|null $doseX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity rateX Amount of medication per unit of time */
		public FHIRRatio|FHIRRange|FHIRQuantity|null $rateX = null,
	) {
		parent::__construct($id, $extension);
	}
}
