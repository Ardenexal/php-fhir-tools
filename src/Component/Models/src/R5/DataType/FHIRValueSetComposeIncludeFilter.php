<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ValueSet.compose.include.filter
 * @description Select concepts by specifying a matching criterion based on the properties (including relationships) defined by the system, or on filters defined by the system. If multiple filters are specified within the include, they SHALL all be true.
 */
class FHIRValueSetComposeIncludeFilter extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode property A property/filter defined by the code system */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode $property = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRFilterOperatorType op = | is-a | descendent-of | is-not-a | regex | in | not-in | generalizes | child-of | descendent-leaf | exists */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRFilterOperatorType $op = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string value Code from the system, or regex criteria, or boolean value for exists */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $value = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
