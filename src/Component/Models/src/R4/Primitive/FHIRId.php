<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/id
 * @description Any combination of letters, numerals, "-" and ".", with a length limit of 64 characters.  (This might be an integer, an unprefixed OID, UUID or any other identifier pattern that meets these constraints.)  Ids are case-insensitive.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'id', fhirVersion: 'R4')]
class FHIRId extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|string value Primitive value for id */
		public ?string $value = null,
	) {
		parent::__construct($id, $extension, $value);
	}
}
