<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Range
 * @description A set of ordered Quantities defined by a low and high limit.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Range', fhirVersion: 'R5')]
class FHIRRange extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity low Low limit */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $low = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity high High limit */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $high = null,
	) {
		parent::__construct($id, $extension);
	}
}
