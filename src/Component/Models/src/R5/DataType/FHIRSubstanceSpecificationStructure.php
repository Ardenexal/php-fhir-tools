<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element SubstanceSpecification.structure
 * @description Structural information.
 */
class FHIRSubstanceSpecificationStructure extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept stereochemistry Stereochemistry type */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $stereochemistry = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept opticalActivity Optical activity type */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $opticalActivity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string molecularFormula Molecular formula */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $molecularFormula = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string molecularFormulaByMoiety Specified per moiety according to the Hill system, i.e. first C, then H, then alphabetical, each moiety separated by a dot */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $molecularFormulaByMoiety = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceSpecificationStructureIsotope> isotope Applicable for single substances that contain a radionuclide or a non-natural isotopic ratio */
		public array $isotope = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceSpecificationStructureIsotopeMolecularWeight molecularWeight The molecular weight or weight range (for proteins, polymers or nucleic acids) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceSpecificationStructureIsotopeMolecularWeight $molecularWeight = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> source Supporting literature */
		public array $source = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceSpecificationStructureRepresentation> representation Molecular structural representation */
		public array $representation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
