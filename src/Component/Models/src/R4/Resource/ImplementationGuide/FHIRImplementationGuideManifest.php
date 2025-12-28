<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Information about an assembled implementation guide, created by the publication tooling.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.manifest', fhirVersion: 'R4')]
class FHIRImplementationGuideManifest extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUrl|null rendering Location of rendered implementation guide */
        public ?\FHIRUrl $rendering = null,
        /** @var array<FHIRImplementationGuideManifestResource> resource Resource in the implementation guide */
        public array $resource = [],
        /** @var array<FHIRImplementationGuideManifestPage> page HTML page within the parent IG */
        public array $page = [],
        /** @var array<FHIRString|string> image Image within the IG */
        public array $image = [],
        /** @var array<FHIRString|string> other Additional linkable file in IG */
        public array $other = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
