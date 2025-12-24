<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;

/**
 * @description Information about the [ValueSet/$expand](valueset-operation-expand.html) operation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.expansion', fhirVersion: 'R4B')]
class FHIRTerminologyCapabilitiesExpansion extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null hierarchical Whether the server can return nested value sets */
        public ?FHIRBoolean $hierarchical = null,
        /** @var FHIRBoolean|null paging Whether the server supports paging on expansion */
        public ?FHIRBoolean $paging = null,
        /** @var FHIRBoolean|null incomplete Allow request for incomplete expansions? */
        public ?FHIRBoolean $incomplete = null,
        /** @var array<FHIRTerminologyCapabilitiesExpansionParameter> parameter Supported expansion parameter */
        public array $parameter = [],
        /** @var FHIRMarkdown|null textFilter Documentation about text searching works */
        public ?FHIRMarkdown $textFilter = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
