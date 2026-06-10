<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Contract for FHIR terminology validation. Implementations call a terminology server's
 * $validate-code operation; the null object returns true unconditionally (graceful degradation).
 */
interface FHIRTerminologyClientInterface
{
    /**
     * Returns true when $value is a valid member of the named value set, false otherwise.
     *
     * Implementations may accept a string code, an int, or a BackedEnum instance as $value.
     */
    public function validateCode(string $valueSetUrl, mixed $value): bool;

    /**
     * Returns true when the system+code pair is a valid member of the named value set, false otherwise.
     */
    public function validateCoding(string $valueSetUrl, string $system, string $code): bool;

    /**
     * Validates the system+code pair and checks whether the provided display matches the
     * canonical display. Returns valid=false when the code is not in the value set.
     * When valid=true and correctDisplay is non-null, the provided display was wrong.
     */
    public function validateCodingWithDisplay(
        string $valueSetUrl,
        string $system,
        string $code,
        string $display,
    ): CodingValidationResult;
}
