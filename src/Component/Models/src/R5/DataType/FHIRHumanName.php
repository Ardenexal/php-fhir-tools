<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/HumanName
 * @description A name, normally of a human, that can be used for other living entities (e.g. animals but not organizations) that have been assigned names by a human and may need the use of name parts or the need for usage information.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'HumanName', fhirVersion: 'R5')]
class FHIRHumanName extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNameUseType use usual | official | temp | nickname | anonymous | old | maiden */
		public ?FHIRNameUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string text Text representation of the full name */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string family Family name (often called 'Surname') */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $family = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> given Given names (not always 'first'). Includes middle names */
		public array $given = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> prefix Parts that come before the name */
		public array $prefix = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> suffix Parts that come after the name */
		public array $suffix = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod period Time period when name was/is in use */
		public ?FHIRPeriod $period = null,
	) {
		parent::__construct($id, $extension);
	}
}
