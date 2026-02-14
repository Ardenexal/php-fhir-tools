<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

/**
 * @description Names applicable to this substance.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.name', fhirVersion: 'R4')]
class SubstanceSpecificationName extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name The actual name */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Name type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept status The status of the name */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $status = null,
		/** @var null|bool preferred If this is the preferred name for this substance */
		public ?bool $preferred = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> language Language of the name */
		public array $language = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> domain The use context of this name for example if there is a different name a drug active ingredient as opposed to a food colour additive */
		public array $domain = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> jurisdiction The jurisdiction where this name applies */
		public array $jurisdiction = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationName> synonym A synonym of this name */
		public array $synonym = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationName> translation A translation for this name */
		public array $translation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationNameOfficial> official Details of the official nature of this name */
		public array $official = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> source Supporting literature */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
