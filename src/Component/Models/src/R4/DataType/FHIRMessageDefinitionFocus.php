<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element MessageDefinition.focus
 * @description Identifies the resource (or resources) that are being addressed by the event.  For example, the Encounter for an admit message or two Account records for a merge.
 */
class FHIRMessageDefinitionFocus extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResourceTypeType code Type of resource */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResourceTypeType $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical profile Profile that must be adhered to by focus */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical $profile = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt min Minimum number of focuses of this type */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt $min = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string max Maximum number of focuses of this type */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $max = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
