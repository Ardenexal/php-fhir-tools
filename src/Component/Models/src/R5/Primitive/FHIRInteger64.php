<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/integer64
 * @description A very large whole number
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'integer64', fhirVersion: 'R5')]
class FHIRInteger64 extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPrimitiveType
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|int value Primitive value for integer64 */
		public ?int $value = null,
	) {
		parent::__construct($id, $extension);
	}
}
