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
}
