<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Identifier
 * @description An identifier - identifies some entity uniquely and unambiguously. Typically this is used for business identifiers.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Identifier', fhirVersion: 'R4')]
class Identifier extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\IdentifierUseType use usual | official | temp | secondary | old (If known) */
		public ?IdentifierUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Description of identifier */
		public ?CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive system The namespace for the identifier value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $system = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string value The value that is unique */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Time period when id is/was valid for use */
		public ?Period $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference assigner Organization that issued id (may be just text) */
		public ?Reference $assigner = null,
	) {
		parent::__construct($id, $extension);
	}
}
