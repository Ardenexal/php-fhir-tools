<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

/**
 * @description Applicable for single substances that contain a radionuclide or a non-natural isotopic ratio.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.structure.isotope', fhirVersion: 'R4')]
class SubstanceSpecificationStructureIsotope extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier identifier Substance identifier for each non-natural or radioisotope */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept name Substance name for each non-natural or radioisotope */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept substitution The type of isotopic substitution present in a single substance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $substitution = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity halfLife Half life - for a non-natural nuclide */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $halfLife = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationStructureIsotopeMolecularWeight molecularWeight The molecular weight or weight range (for proteins, polymers or nucleic acids) */
		public ?SubstanceSpecificationStructureIsotopeMolecularWeight $molecularWeight = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
