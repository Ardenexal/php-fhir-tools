<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

/**
 * @description The molecular weight or weight range (for proteins, polymers or nucleic acids).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'SubstanceSpecification',
	elementPath: 'SubstanceSpecification.structure.isotope.molecularWeight',
	fhirVersion: 'R4',
)]
class SubstanceSpecificationStructureIsotopeMolecularWeight extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept method The method by which the molecular weight was determined */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Type of molecular weight such as exact, average (also known as. number average), weight average */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity amount Used to capture quantitative values for a variety of elements. If only limits are given, the arithmetic mean would be the average. If only a single definite value for a given element is given, it would be captured in this field */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
