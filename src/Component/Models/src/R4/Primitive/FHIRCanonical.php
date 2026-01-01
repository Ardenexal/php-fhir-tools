<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/canonical
 * @description A URI that is a reference to a canonical URL on a FHIR resource
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'canonical', fhirVersion: 'R4')]
class FHIRCanonical extends FHIRUri
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|string value Primitive value for canonical */
		public ?string $value = null,
	) {
		parent::__construct($id, $extension, $value);
	}
}
