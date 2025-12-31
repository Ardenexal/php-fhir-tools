<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/boolean
 * @description Value of "true" or "false"
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive(primitiveType: 'boolean', fhirVersion: 'R4')]
class FHIRBoolean extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRElement
{
	public function __construct(
		/** @var null|string id xml:id (or equivalent in JSON) */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|bool value Primitive value for boolean */
		public ?bool $value = null,
	) {
		parent::__construct($id, $extension);
	}
}
