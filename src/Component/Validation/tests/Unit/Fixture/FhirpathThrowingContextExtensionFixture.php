<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * fhirpath context expression the engine cannot evaluate: conformsTo() requires a profile
 * validator that is not configured in unit tests, so evaluation throws FHIRPathException.
 * This must DEFER (no violation), never DENY — engine limitation is not non-conformance.
 */
#[FHIRExtensionContext(type: 'fhirpath', expression: "conformsTo('http://example.org/StructureDefinition/x')")]
final class FhirpathThrowingContextExtensionFixture implements FHIRExtensionInterface
{
    public function getExtensionUrl(): ?string
    {
        return 'http://example.org/ext/fhirpath-throwing-context';
    }
}
