<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ConceptMap.additionalAttribute
 * @description An additionalAttribute defines an additional data element found in the source or target data model where the data will come from or be mapped to. Some mappings are based on data in addition to the source data element, where codes in multiple fields are combined to a single field (or vice versa).
 */
class FHIRConceptMapAdditionalAttribute extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode code Identifies this additional attribute through this resource */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri uri Formal identifier for the data element referred to in this attribte */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $uri = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string description Why the additional attribute is defined, and/or what the data element it refers to is */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapAttributeTypeType type code | Coding | string | boolean | Quantity */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapAttributeTypeType $type = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
