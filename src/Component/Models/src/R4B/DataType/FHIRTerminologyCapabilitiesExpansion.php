<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element TerminologyCapabilities.expansion
 * @description Information about the [ValueSet/$expand](valueset-operation-expand.html) operation.
 */
class FHIRTerminologyCapabilitiesExpansion extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean hierarchical Whether the server can return nested value sets */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $hierarchical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean paging Whether the server supports paging on expansion */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $paging = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean incomplete Allow request for incomplete expansions? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $incomplete = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTerminologyCapabilitiesExpansionParameter> parameter Supported expansion parameter */
		public array $parameter = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown textFilter Documentation about text searching works */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown $textFilter = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
