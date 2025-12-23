<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element SubstanceSpecification.structure.isotope
 * @description Applicable for single substances that contain a radionuclide or a non-natural isotopic ratio.
 */
class FHIRSubstanceSpecificationStructureIsotope extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier identifier Substance identifier for each non-natural or radioisotope */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept name Substance name for each non-natural or radioisotope */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept substitution The type of isotopic substitution present in a single substance */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $substitution = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity halfLife Half life - for a non-natural nuclide */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $halfLife = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceSpecificationStructureIsotopeMolecularWeight molecularWeight The molecular weight or weight range (for proteins, polymers or nucleic acids) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceSpecificationStructureIsotopeMolecularWeight $molecularWeight = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
