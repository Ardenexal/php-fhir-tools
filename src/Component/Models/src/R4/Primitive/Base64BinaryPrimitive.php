<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/base64Binary
 * @description A stream of bytes
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'base64Binary', fhirVersion: 'R4')]
class Base64BinaryPrimitive extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Element
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|string value Primitive value for base64Binary */
		public ?string $value = null,
	) {
		parent::__construct($id, $extension);
	}
}
