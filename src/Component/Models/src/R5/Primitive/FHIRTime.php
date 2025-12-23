<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/time
 * @description A time during the day, with no date specified
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'time', fhirVersion: 'R5')]
class FHIRTime extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPrimitiveType
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|string value Primitive value for time */
		public ?string $value = null,
	) {
		parent::__construct($id, $extension);
	}
}
