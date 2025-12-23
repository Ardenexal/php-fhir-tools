<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element SubstanceNucleicAcid.subunit.linkage
 * @description The linkages between sugar residues will also be captured.
 */
class FHIRSubstanceNucleicAcidSubunitLinkage extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string connectivity The entity that links the sugar residues together should also be captured for nearly all naturally occurring nucleic acid the linkage is a phosphate group. For many synthetic oligonucleotides phosphorothioate linkages are often seen. Linkage connectivity is assumed to be 3’-5’. If the linkage is either 3’-3’ or 5’-5’ this should be specified */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $connectivity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier identifier Each linkage will be registered as a fragment and have an ID */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string name Each linkage will be registered as a fragment and have at least one name. A single name shall be assigned to each linkage */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string residueSite Residues shall be captured as described in 5.3.6.8.3 */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $residueSite = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
