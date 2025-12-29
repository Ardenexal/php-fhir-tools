<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/decimal
 * @description A rational number with implicit precision
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'decimal', fhirVersion: 'R4B')]
class FHIRDecimal extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRElement
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|float value Primitive value for decimal */
		public ?float $value = null,
	) {
		parent::__construct($id, $extension);
	}
}
