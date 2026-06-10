<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Null-object implementation used when no real terminology server is configured.
 *
 * FHIRValueSetBindingValidator treats this client the same as having no client at all:
 * extensible/preferred binding checks are skipped and each skip is surfaced as a
 * fhir:unchecked-binding INFO violation (issue #71). To suppress those, wire a real
 * client (e.g. HttpFHIRTerminologyClient) or filter the code at the application layer.
 */
final class NullFHIRTerminologyClient implements FHIRTerminologyClientInterface
{
    /**
     * Always returns true — the null object treats every code as valid.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param mixed  $value       Ignored
     *
     * @return bool Always true
     */
    public function validateCode(string $valueSetUrl, mixed $value): bool
    {
        return true;
    }

    /**
     * Always returns true — the null object treats every coding as valid.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      Ignored
     * @param string $code        Ignored
     *
     * @return bool Always true
     */
    public function validateCoding(string $valueSetUrl, string $system, string $code): bool
    {
        return true;
    }

    /**
     * Always returns valid=true with no display correction — the null object assumes every display is correct.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      Ignored
     * @param string $code        Ignored
     * @param string $display     Ignored
     *
     * @return CodingValidationResult Always valid=true, correctDisplay=null
     */
    public function validateCodingWithDisplay(
        string $valueSetUrl,
        string $system,
        string $code,
        string $display,
    ): CodingValidationResult {
        return new CodingValidationResult(true, null);
    }
}
