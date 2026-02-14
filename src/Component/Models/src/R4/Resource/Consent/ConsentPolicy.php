<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @description The references to the policies that are included in this consent scope. Policies may be organizational, but are often defined jurisdictionally, or in law.
 */
#[FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.policy', fhirVersion: 'R4')]
class ConsentPolicy extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var UriPrimitive|null authority Enforcement source for policy */
        public ?UriPrimitive $authority = null,
        /** @var UriPrimitive|null uri Specific policy covered by this consent */
        public ?UriPrimitive $uri = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
