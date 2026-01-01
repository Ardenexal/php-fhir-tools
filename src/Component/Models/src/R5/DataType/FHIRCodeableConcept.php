<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/CodeableConcept
 * @description A concept that may be defined by a formal reference to a terminology or ontology or may be provided by text.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'CodeableConcept', fhirVersion: 'R5')]
class FHIRCodeableConcept extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding> coding Code defined by a terminology system */
		public array $coding = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string text Plain text representation of the concept */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $text = null,
	) {
		parent::__construct($id, $extension);
	}
}
