<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/CodeableReference
 * @description A reference to a resource (by instance), or instead, a reference to a concept defined in a terminology or ontology (by class).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'CodeableReference', fhirVersion: 'R5')]
class FHIRCodeableReference extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept concept Reference to a concept (by class) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $concept = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference reference Reference to a resource (by instance) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $reference = null,
	) {
		parent::__construct($id, $extension);
	}
}
