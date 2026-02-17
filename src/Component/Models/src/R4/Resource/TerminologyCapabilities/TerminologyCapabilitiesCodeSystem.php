<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;

/**
 * @description Identifies a code system that is supported by the server. If there is a no code system URL, then this declares the general assumptions a client can make about support for any CodeSystem resource.
 */
#[FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.codeSystem', fhirVersion: 'R4')]
class TerminologyCapabilitiesCodeSystem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CanonicalPrimitive|null uri URI for the Code System */
        public ?CanonicalPrimitive $uri = null,
        /** @var array<TerminologyCapabilitiesCodeSystemVersion> version Version of Code System supported */
        public array $version = [],
        /** @var bool|null subsumption Whether subsumption is supported */
        public ?bool $subsumption = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
