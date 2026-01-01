<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/SampledData
 * @description A series of measurements taken by a device, with upper and lower limits. There may be more than one dimension in the data.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'SampledData', fhirVersion: 'R5')]
class FHIRSampledData extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity origin Zero value and units */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRQuantity $origin = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal interval Number of intervalUnits between samples */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $interval = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUCUMCodesType intervalUnit The measurement unit of the interval between samples */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRUCUMCodesType $intervalUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal factor Multiply data by this before adding to origin */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal lowerLimit Lower limit of detection */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $lowerLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal upperLimit Upper limit of detection */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $upperLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt dimensions Number of sample points at each time point */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $dimensions = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical codeMap Defines the codes used in the data */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $codeMap = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string offsets Offsets, typically in time, at which data values were taken */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $offsets = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string data Decimal values with spaces, or "E" | "U" | "L", or another code */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $data = null,
	) {
		parent::__construct($id, $extension);
	}
}
