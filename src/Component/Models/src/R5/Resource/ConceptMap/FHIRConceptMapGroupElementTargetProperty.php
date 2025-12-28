<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A property value for this source -> target mapping.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element.target.property', fhirVersion: 'R5')]
class FHIRConceptMapGroupElementTargetProperty extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode code Reference to ConceptMap.property.code */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode valueX Value of the property for this concept */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
