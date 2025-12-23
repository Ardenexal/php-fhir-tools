<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element SubstanceNucleicAcid.subunit.sugar
 * @description 5.3.6.8.1 Sugar ID (Mandatory).
 */
class FHIRSubstanceNucleicAcidSubunitSugar extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier identifier The Substance ID of the sugar or sugar-like component that make up the nucleotide */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name The name of the sugar or sugar-like component that make up the nucleotide */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string residueSite The residues that contain a given sugar will be captured. The order of given residues will be captured in the 5‘-3‘direction consistent with the base sequences listed above */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $residueSite = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
