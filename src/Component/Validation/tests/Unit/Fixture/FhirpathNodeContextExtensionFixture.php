<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * Node-returning fhirpath context: yields the bearing element's `type` node. Non-empty
 * results permit; an empty result must DEFER (not deny) because the engine cannot
 * distinguish "no match" from "could not resolve".
 */
#[FHIRExtensionContext(type: 'fhirpath', expression: 'type')]
final class FhirpathNodeContextExtensionFixture implements FHIRExtensionInterface
{
    public function getExtensionUrl(): ?string
    {
        return 'http://example.org/ext/fhirpath-node-context';
    }
}
