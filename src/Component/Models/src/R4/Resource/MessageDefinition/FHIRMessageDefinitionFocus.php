<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Identifies the resource (or resources) that are being addressed by the event.  For example, the Encounter for an admit message or two Account records for a merge.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MessageDefinition', elementPath: 'MessageDefinition.focus', fhirVersion: 'R4')]
class FHIRMessageDefinitionFocus extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRResourceTypeType code Type of resource */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRResourceTypeType $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical profile Profile that must be adhered to by focus */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical $profile = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUnsignedInt min Minimum number of focuses of this type */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUnsignedInt $min = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string max Maximum number of focuses of this type */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $max = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
