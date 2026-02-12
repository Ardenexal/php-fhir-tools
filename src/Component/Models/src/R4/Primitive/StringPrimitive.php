<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/string
 * @description A sequence of Unicode characters
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'string', fhirVersion: 'R4')]
class StringPrimitive extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Element
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|string value Primitive value for string */
		public ?string $value = null,
	) {
		parent::__construct($id, $extension);
	}
}
