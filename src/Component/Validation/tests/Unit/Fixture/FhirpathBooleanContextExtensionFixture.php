<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * Boolean-shaped fhirpath context mirroring the real ArtifactIsOwnedExtension expression:
 * evaluates to a single boolean against the bearing element, so it exercises both the
 * confident PERMIT (type = composed-of) and confident DENY (type = anything else) arms.
 */
#[FHIRExtensionContext(type: 'fhirpath', expression: "type.exists() and type = 'composed-of'")]
final class FhirpathBooleanContextExtensionFixture implements FHIRExtensionInterface
{
    public function getExtensionUrl(): ?string
    {
        return 'http://example.org/ext/fhirpath-boolean-context';
    }
}
