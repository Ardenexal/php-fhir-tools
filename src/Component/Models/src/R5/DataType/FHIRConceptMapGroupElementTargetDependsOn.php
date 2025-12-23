<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ConceptMap.group.element.target.dependsOn
 * @description A set of additional dependencies for this mapping to hold. This mapping is only applicable if the specified data attribute can be resolved, and it has the specified value.
 */
class FHIRConceptMapGroupElementTargetDependsOn extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode attribute A reference to a mapping attribute defined in ConceptMap.additionalAttribute */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode $attribute = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity valueX Value of the referenced data element */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|null $valueX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical valueSet The mapping depends on a data element with a value from this value set */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $valueSet = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
