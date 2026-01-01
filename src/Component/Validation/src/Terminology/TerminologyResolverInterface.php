<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Terminology;

/**
 * Interface for terminology resolution (ValueSet validation and expansion).
 *
 * @author Alex Murray <alex@ardenexal.com>
 */
interface TerminologyResolverInterface
{
    /**
     * Validate that a code is a member of a ValueSet.
     *
     * @param string $valueSetUrl Canonical URL of the ValueSet
     * @param string $system Code system
     * @param string $code Code to validate
     * @param string|null $display Optional display value
     * @param string|null $version Optional ValueSet version
     *
     * @return bool True if code is valid member of ValueSet
     */
    public function validateCode(
        string $valueSetUrl,
        string $system,
        string $code,
        ?string $display = null,
        ?string $version = null
    ): bool;

    /**
     * Expand a ValueSet to get all possible codes.
     *
     * @param string $valueSetUrl Canonical URL of the ValueSet
     * @param string|null $version Optional ValueSet version
     * @param int|null $count Maximum number of codes to return
     * @param int|null $offset Offset for pagination
     *
     * @return array<array{system: string, code: string, display?: string}> Array of codes
     */
    public function expand(
        string $valueSetUrl,
        ?string $version = null,
        ?int $count = null,
        ?int $offset = null
    ): array;

    /**
     * Check if this resolver can handle the given ValueSet.
     *
     * @param string $valueSetUrl Canonical URL of the ValueSet
     *
     * @return bool True if this resolver can handle the ValueSet
     */
    public function canResolve(string $valueSetUrl): bool;
}
