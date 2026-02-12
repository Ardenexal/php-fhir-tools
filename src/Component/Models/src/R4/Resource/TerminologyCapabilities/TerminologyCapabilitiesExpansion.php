<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities;

/**
 * @description Information about the [ValueSet/$expand](valueset-operation-expand.html) operation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.expansion', fhirVersion: 'R4')]
class TerminologyCapabilitiesExpansion extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|bool hierarchical Whether the server can return nested value sets */
		public ?bool $hierarchical = null,
		/** @var null|bool paging Whether the server supports paging on expansion */
		public ?bool $paging = null,
		/** @var null|bool incomplete Allow request for incomplete expansions? */
		public ?bool $incomplete = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesExpansionParameter> parameter Supported expansion parameter */
		public array $parameter = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive textFilter Documentation about text searching works */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $textFilter = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
