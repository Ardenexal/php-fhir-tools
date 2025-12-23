<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Reference
 * @description A reference from one resource to another.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Reference', fhirVersion: 'R4')]
class FHIRReference extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string reference Literal reference, Relative, internal or absolute URL */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $reference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri type Type the reference refers to (e.g. "Patient") */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier identifier Logical reference, when literal reference is not known */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string display Text alternative for the resource */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $display = null,
	) {
		parent::__construct($id, $extension);
	}
}
