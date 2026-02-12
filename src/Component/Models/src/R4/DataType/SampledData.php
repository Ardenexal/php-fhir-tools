<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/SampledData
 * @description A series of measurements taken by a device, with upper and lower limits. There may be more than one dimension in the data.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'SampledData', fhirVersion: 'R4')]
class SampledData extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity origin Zero value and units */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?Quantity $origin = null,
		/** @var null|float period Number of milliseconds between samples */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?float $period = null,
		/** @var null|float factor Multiply data by this before adding to origin */
		public ?float $factor = null,
		/** @var null|float lowerLimit Lower limit of detection */
		public ?float $lowerLimit = null,
		/** @var null|float upperLimit Upper limit of detection */
		public ?float $upperLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive dimensions Number of sample points at each time point */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $dimensions = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string data Decimal values with spaces, or "E" | "U" | "L" */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $data = null,
	) {
		parent::__construct($id, $extension);
	}
}
