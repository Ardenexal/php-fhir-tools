<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @description The amount of medication administered.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Dosage.doseAndRate', fhirVersion: 'R4')]
class DosageDoseAndRate extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type The kind of dose or rate specified */
		public ?CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity doseX Amount of medication per dose */
		public Range|Quantity|null $doseX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity rateX Amount of medication per unit of time */
		public Ratio|Range|Quantity|null $rateX = null,
	) {
		parent::__construct($id, $extension);
	}
}
