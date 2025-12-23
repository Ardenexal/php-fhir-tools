<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/unsignedInt
 * @description An integer with a value that is not negative (e.g. >= 0)
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'unsignedInt', fhirVersion: 'R4B')]
class FHIRUnsignedInt extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|int value Primitive value for unsignedInt */
		public ?int $value = null,
	) {
		parent::__construct($id, $extension, $value);
	}
}
