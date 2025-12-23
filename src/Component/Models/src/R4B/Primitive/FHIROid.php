<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/oid
 * @description An OID represented as a URI
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'oid', fhirVersion: 'R4B')]
class FHIROid extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|string value Primitive value for oid */
		public ?string $value = null,
	) {
		parent::__construct($id, $extension, $value);
	}
}
