<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element SubstanceSpecification.structure.representation
 * @description Molecular structural representation.
 */
class FHIRSubstanceSpecificationStructureRepresentation extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type The type of structure (e.g. Full, Partial, Representative) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string representation The structural representation as text string in a format e.g. InChI, SMILES, MOLFILE, CDX */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $representation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAttachment attachment An attached file with the structural representation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAttachment $attachment = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
