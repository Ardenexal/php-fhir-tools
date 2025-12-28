<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Coding
 * @description A reference to a code defined by a terminology system.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Coding', fhirVersion: 'R5')]
class FHIRCoding extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri system Identity of the terminology system */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $system = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string version Version of the system - if relevant */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode code Symbol in syntax defined by the system */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string display Representation defined by the system */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean userSelected If this coding was chosen directly by the user */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $userSelected = null,
	) {
		parent::__construct($id, $extension);
	}
}
