<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;

/**
 * @description Information about the [ValueSet/$expand](valueset-operation-expand.html) operation.
 */
#[FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.expansion', fhirVersion: 'R4')]
class TerminologyCapabilitiesExpansion extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var bool|null hierarchical Whether the server can return nested value sets */
        public ?bool $hierarchical = null,
        /** @var bool|null paging Whether the server supports paging on expansion */
        public ?bool $paging = null,
        /** @var bool|null incomplete Allow request for incomplete expansions? */
        public ?bool $incomplete = null,
        /** @var array<TerminologyCapabilitiesExpansionParameter> parameter Supported expansion parameter */
        public array $parameter = [],
        /** @var MarkdownPrimitive|null textFilter Documentation about text searching works */
        public ?MarkdownPrimitive $textFilter = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
