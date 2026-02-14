<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Range
 * @description A set of ordered Quantities defined by a low and high limit.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Range', fhirVersion: 'R4')]
class Range extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity low Low limit */
		public ?Quantity $low = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity high High limit */
		public ?Quantity $high = null,
	) {
		parent::__construct($id, $extension);
	}
}
