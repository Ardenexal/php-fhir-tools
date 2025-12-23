<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/SampledData
 * @description A series of measurements taken by a device, with upper and lower limits. There may be more than one dimension in the data.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'SampledData', fhirVersion: 'R4')]
class FHIRSampledData extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity origin Zero value and units */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity $origin = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal period Number of milliseconds between samples */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal factor Multiply data by this before adding to origin */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal lowerLimit Lower limit of detection */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $lowerLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal upperLimit Upper limit of detection */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $upperLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt dimensions Number of sample points at each time point */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt $dimensions = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string data Decimal values with spaces, or "E" | "U" | "L" */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $data = null,
	) {
		parent::__construct($id, $extension);
	}
}
