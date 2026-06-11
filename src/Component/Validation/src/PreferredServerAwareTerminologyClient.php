<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Delegates terminology calls to a list of preferred servers in declaration order, falling
 * back to a default client when the list is empty or all preferred servers throw. A definitive
 * false response from a preferred server is final — only a \Throwable triggers failover.
 */
final class PreferredServerAwareTerminologyClient implements FHIRTerminologyClientInterface
{
    /**
     * @param list<FHIRTerminologyClientInterface> $preferred Tried in order; failover on \Throwable only
     * @param FHIRTerminologyClientInterface       $fallback  Used when preferred list is empty or all throw
     */
    public function __construct(
        private readonly array $preferred,
        private readonly FHIRTerminologyClientInterface $fallback,
    ) {
    }

    /**
     * Validates $value against preferred servers in order, falling back to the default client.
     *
     * Tries each preferred server; a false result is final. Only a \Throwable causes failover
     * to the next preferred server. Falls back to $fallback when all preferred servers throw.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param mixed  $value       The code to validate; accepts string, int, or BackedEnum
     *
     * @return bool True when the code is a valid member, false otherwise
     */
    public function validateCode(string $valueSetUrl, mixed $value): bool
    {
        foreach ($this->preferred as $client) {
            try {
                return $client->validateCode($valueSetUrl, $value);
            } catch (\Throwable) {
                // try next preferred server
            }
        }

        return $this->fallback->validateCode($valueSetUrl, $value);
    }

    /**
     * Validates the system+code pair against preferred servers in order, falling back to the default client.
     *
     * Tries each preferred server; a false result is final. Only a \Throwable causes failover
     * to the next preferred server. Falls back to $fallback when all preferred servers throw.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      The coding system URI
     * @param string $code        The code within that system
     *
     * @return bool True when the coding is a valid member, false otherwise
     */
    public function validateCoding(string $valueSetUrl, string $system, string $code): bool
    {
        foreach ($this->preferred as $client) {
            try {
                return $client->validateCoding($valueSetUrl, $system, $code);
            } catch (\Throwable) {
                // try next preferred server
            }
        }

        return $this->fallback->validateCoding($valueSetUrl, $system, $code);
    }

    /**
     * Validates the system+code pair and display against preferred servers in order, falling back to the default client.
     *
     * Tries each preferred server; a definitive result (valid or invalid) is final. Only a
     * \Throwable causes failover to the next preferred server. Falls back to $fallback when
     * all preferred servers throw.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      The coding system URI
     * @param string $code        The code within that system
     * @param string $display     The display string to validate against the canonical display
     *
     * @return CodingValidationResult Validity flag and optional corrected display string
     */
    public function validateCodingWithDisplay(
        string $valueSetUrl,
        string $system,
        string $code,
        string $display,
    ): CodingValidationResult {
        foreach ($this->preferred as $client) {
            try {
                return $client->validateCodingWithDisplay($valueSetUrl, $system, $code, $display);
            } catch (\Throwable) {
                // try next preferred server
            }
        }

        return $this->fallback->validateCodingWithDisplay($valueSetUrl, $system, $code, $display);
    }
}
