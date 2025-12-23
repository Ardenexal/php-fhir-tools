<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/CodeableReference
 * @description A reference to a resource (by instance), or instead, a reference to a concept defined in a terminology or ontology (by class).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'CodeableReference', fhirVersion: 'R4B')]
class FHIRCodeableReference extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept concept Reference to a concept (by class) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $concept = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference reference Reference to a resource (by instance) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $reference = null,
	) {
		parent::__construct($id, $extension);
	}
}
