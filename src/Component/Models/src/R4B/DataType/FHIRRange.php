<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Range
 * @description A set of ordered Quantities defined by a low and high limit.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Range', fhirVersion: 'R4B')]
class FHIRRange extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity low Low limit */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $low = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity high High limit */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $high = null,
	) {
		parent::__construct($id, $extension);
	}
}
