<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

/**
 * @description Structural information.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.structure', fhirVersion: 'R4')]
class SubstanceSpecificationStructure extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept stereochemistry Stereochemistry type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $stereochemistry = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept opticalActivity Optical activity type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $opticalActivity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string molecularFormula Molecular formula */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $molecularFormula = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string molecularFormulaByMoiety Specified per moiety according to the Hill system, i.e. first C, then H, then alphabetical, each moiety separated by a dot */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $molecularFormulaByMoiety = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationStructureIsotope> isotope Applicable for single substances that contain a radionuclide or a non-natural isotopic ratio */
		public array $isotope = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationStructureIsotopeMolecularWeight molecularWeight The molecular weight or weight range (for proteins, polymers or nucleic acids) */
		public ?SubstanceSpecificationStructureIsotopeMolecularWeight $molecularWeight = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> source Supporting literature */
		public array $source = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationStructureRepresentation> representation Molecular structural representation */
		public array $representation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
