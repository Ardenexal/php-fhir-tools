<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * contextInvariant expression that the engine cannot evaluate: conformsTo() requires a
 * profile validator that is not configured in unit tests, so evaluation throws. This must
 * surface as an INFO eval-error, not an ERROR constraint failure.
 */
#[FHIRContextInvariant(expression: "conformsTo('http://example.org/StructureDefinition/x')")]
final class EvalErrorInvariantExtensionFixture implements FHIRExtensionInterface
{
    public function getExtensionUrl(): ?string
    {
        return 'http://example.org/ext/eval-error-invariant';
    }
}
