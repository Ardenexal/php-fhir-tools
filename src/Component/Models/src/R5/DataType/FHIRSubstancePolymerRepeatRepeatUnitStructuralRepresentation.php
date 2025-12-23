<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element SubstancePolymer.repeat.repeatUnit.structuralRepresentation
 * @description A graphical structure for this SRU.
 */
class FHIRSubstancePolymerRepeatRepeatUnitStructuralRepresentation extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type The type of structure (e.g. Full, Partial, Representative) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string representation The structural representation as text string in a standard format e.g. InChI, SMILES, MOLFILE, CDX, SDF, PDB, mmCIF */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $representation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept format The format of the representation e.g. InChI, SMILES, MOLFILE, CDX, SDF, PDB, mmCIF */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $format = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment attachment An attached file with the structural representation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAttachment $attachment = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
