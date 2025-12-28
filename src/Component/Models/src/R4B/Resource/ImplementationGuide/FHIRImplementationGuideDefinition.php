<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The information needed by an IG publisher tool to publish the whole implementation guide.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.definition', fhirVersion: 'R4B')]
class FHIRImplementationGuideDefinition extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRImplementationGuideDefinitionGrouping> grouping Grouping used to present related resources in the IG */
        public array $grouping = [],
        /** @var array<FHIRImplementationGuideDefinitionResource> resource Resource in the implementation guide */
        public array $resource = [],
        /** @var FHIRImplementationGuideDefinitionPage|null page Page/Section in the Guide */
        public ?\FHIRImplementationGuideDefinitionPage $page = null,
        /** @var array<FHIRImplementationGuideDefinitionParameter> parameter Defines how IG is built by tools */
        public array $parameter = [],
        /** @var array<FHIRImplementationGuideDefinitionTemplate> template A template for building resources */
        public array $template = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
