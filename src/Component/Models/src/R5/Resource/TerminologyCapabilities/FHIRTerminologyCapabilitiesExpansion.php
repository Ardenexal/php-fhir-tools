<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Information about the [ValueSet/$expand](valueset-operation-expand.html) operation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.expansion', fhirVersion: 'R5')]
class FHIRTerminologyCapabilitiesExpansion extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean hierarchical Whether the server can return nested value sets */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $hierarchical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean paging Whether the server supports paging on expansion */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $paging = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean incomplete Allow request for incomplete expansions? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $incomplete = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTerminologyCapabilitiesExpansionParameter> parameter Supported expansion parameter */
		public array $parameter = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown textFilter Documentation about text searching works */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $textFilter = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
