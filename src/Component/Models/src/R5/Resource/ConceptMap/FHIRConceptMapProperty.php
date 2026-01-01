<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A property defines a slot through which additional information can be provided about a map from source -> target.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.property', fhirVersion: 'R5')]
class FHIRConceptMapProperty extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode code Identifies the property on the mappings, and when referred to in the $translate operation */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri uri Formal identifier for the property */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $uri = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string description Why the property is defined, and/or what it conveys */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConceptMapPropertyTypeType type Coding | string | integer | boolean | dateTime | decimal | code */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConceptMapPropertyTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical system The CodeSystem from which code values come */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $system = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
