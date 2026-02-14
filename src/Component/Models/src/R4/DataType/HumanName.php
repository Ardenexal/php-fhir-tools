<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/HumanName
 * @description A human's name with the ability to identify parts and usage.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'HumanName', fhirVersion: 'R4')]
class HumanName extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\NameUseType use usual | official | temp | nickname | anonymous | old | maiden */
		public ?NameUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string text Text representation of the full name */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string family Family name (often called 'Surname') */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $family = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> given Given names (not always 'first'). Includes middle names */
		public array $given = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> prefix Parts that come before the name */
		public array $prefix = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> suffix Parts that come after the name */
		public array $suffix = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Time period when name was/is in use */
		public ?Period $period = null,
	) {
		parent::__construct($id, $extension);
	}
}
