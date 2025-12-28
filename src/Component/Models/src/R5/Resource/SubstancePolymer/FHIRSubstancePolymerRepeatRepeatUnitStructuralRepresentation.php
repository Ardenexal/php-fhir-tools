<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A graphical structure for this SRU.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'SubstancePolymer',
	elementPath: 'SubstancePolymer.repeat.repeatUnit.structuralRepresentation',
	fhirVersion: 'R5',
)]
class FHIRSubstancePolymerRepeatRepeatUnitStructuralRepresentation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type The type of structure (e.g. Full, Partial, Representative) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string representation The structural representation as text string in a standard format e.g. InChI, SMILES, MOLFILE, CDX, SDF, PDB, mmCIF */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $representation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept format The format of the representation e.g. InChI, SMILES, MOLFILE, CDX, SDF, PDB, mmCIF */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $format = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment attachment An attached file with the structural representation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment $attachment = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
