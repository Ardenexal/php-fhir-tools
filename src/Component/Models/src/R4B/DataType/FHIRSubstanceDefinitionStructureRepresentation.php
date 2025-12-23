<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element SubstanceDefinition.structure.representation
 * @description A depiction of the structure or characterization of the substance.
 */
class FHIRSubstanceDefinitionStructureRepresentation extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type The kind of structural representation (e.g. full, partial) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string representation The structural representation or characterization as a text string in a standard format */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $representation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept format The format of the representation e.g. InChI, SMILES, MOLFILE (note: not the physical file format) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $format = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference document An attachment with the structural representation e.g. a structure graphic or AnIML file */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $document = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
