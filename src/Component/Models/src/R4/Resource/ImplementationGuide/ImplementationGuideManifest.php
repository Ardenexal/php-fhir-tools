<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;

/**
 * @description Information about an assembled implementation guide, created by the publication tooling.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.manifest', fhirVersion: 'R4')]
class ImplementationGuideManifest extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var UrlPrimitive|null rendering Location of rendered implementation guide */
        public ?UrlPrimitive $rendering = null,
        /** @var array<ImplementationGuideManifestResource> resource Resource in the implementation guide */
        public array $resource = [],
        /** @var array<ImplementationGuideManifestPage> page HTML page within the parent IG */
        public array $page = [],
        /** @var array<StringPrimitive|string> image Image within the IG */
        public array $image = [],
        /** @var array<StringPrimitive|string> other Additional linkable file in IG */
        public array $other = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
