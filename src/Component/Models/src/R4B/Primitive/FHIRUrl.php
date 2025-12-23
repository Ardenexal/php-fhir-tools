<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/url
 * @description A URI that is a literal reference
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'url', fhirVersion: 'R4B')]
class FHIRUrl extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|string value Primitive value for url */
		public ?string $value = null,
	) {
		parent::__construct($id, $extension, $value);
	}
}
