<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Coding
 * @description A reference to a code defined by a terminology system.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Coding', fhirVersion: 'R4')]
class Coding extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive system Identity of the terminology system */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $system = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string version Version of the system - if relevant */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive code Symbol in syntax defined by the system */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string display Representation defined by the system */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $display = null,
		/** @var null|bool userSelected If this coding was chosen directly by the user */
		public ?bool $userSelected = null,
	) {
		parent::__construct($id, $extension);
	}
}
