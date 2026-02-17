<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description The information needed by an IG publisher tool to publish the whole implementation guide.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.definition', fhirVersion: 'R4')]
class ImplementationGuideDefinition extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<ImplementationGuideDefinitionGrouping> grouping Grouping used to present related resources in the IG */
        public array $grouping = [],
        /** @var array<ImplementationGuideDefinitionResource> resource Resource in the implementation guide */
        public array $resource = [],
        /** @var ImplementationGuideDefinitionPage|null page Page/Section in the Guide */
        public ?ImplementationGuideDefinitionPage $page = null,
        /** @var array<ImplementationGuideDefinitionParameter> parameter Defines how IG is built by tools */
        public array $parameter = [],
        /** @var array<ImplementationGuideDefinitionTemplate> template A template for building resources */
        public array $template = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
