<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Value object returned by validateCodingWithDisplay() carrying both the membership result
 * and an optional corrected display string.
 *
 * When valid is true and correctDisplay is non-null, the code is in the value set but the
 * provided display does not match the canonical one — callers should emit a warning.
 */
final class CodingValidationResult
{
    public function __construct(
        public readonly bool $valid,
        /** null = display not checked or display is correct; non-null = expected display string */
        public readonly ?string $correctDisplay = null,
    ) {
    }
}
