<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element SubstanceDefinition.structure
 * @description Structural information.
 */
class FHIRSubstanceDefinitionStructure extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string molecularFormula An expression which states the number and type of atoms present in a molecule of a substance */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $molecularFormula = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string molecularFormulaByMoiety Specified per moiety according to the Hill system */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $molecularFormulaByMoiety = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceDefinitionMolecularWeight molecularWeight The molecular weight or weight range */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceDefinitionMolecularWeight $molecularWeight = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> technique The method used to find the structure e.g. X-ray, NMR */
		public array $technique = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> sourceDocument Source of information for the structure */
		public array $sourceDocument = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceDefinitionStructureRepresentation> representation A depiction of the structure of the substance */
		public array $representation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
