<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Narrative
 * @description A human-readable summary of the resource conveying the essential clinical and business information for the resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Narrative', fhirVersion: 'R4')]
class Narrative extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\NarrativeStatusType status generated | extensions | additional | empty */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?NarrativeStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\XhtmlPrimitive div Limited xhtml content */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\XhtmlPrimitive $div = null,
	) {
		parent::__construct($id, $extension);
	}
}
