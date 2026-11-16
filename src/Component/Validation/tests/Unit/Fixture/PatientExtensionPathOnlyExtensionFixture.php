<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * Permitted only as a sub-extension of an extension on Patient: its one context names the
 * carrying extension's path, which is where the reference validator places a nested extension.
 */
#[FHIRExtensionContext(type: 'element', expression: 'Patient.extension')]
final class PatientExtensionPathOnlyExtensionFixture implements FHIRExtensionInterface
{
    public function getExtensionUrl(): ?string
    {
        return 'http://example.org/ext/patient-extension-path-only';
    }
}
