<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Terminology;

/**
 * Fallback terminology resolver with remote-first, package fallback strategy.
 *
 * Implements the production requirement: "Use terminology server first, fall back to package loader".
 *
 * Resolution strategy:
 * 1. Try remote terminology server (with circuit breaker)
 * 2. If remote fails or unavailable, fall back to package loader
 * 3. If package loader can't resolve, throw exception
 *
 * @author Alex Murray <alex@ardenexal.com>
 */
final class FallbackTerminologyResolver implements TerminologyResolverInterface
{
    public function __construct(
        private readonly TerminologyResolverInterface $remoteResolver,
        private readonly TerminologyResolverInterface $packageResolver,
    ) {
    }

    public function validateCode(
        string $valueSetUrl,
        string $system,
        string $code,
        ?string $display = null,
        ?string $version = null
    ): bool {
        // Try remote first
        try {
            return $this->remoteResolver->validateCode($valueSetUrl, $system, $code, $display, $version);
        } catch (\Exception $e) {
            // Remote failed, fall back to package
        }

        // Fall back to package resolver
        if ($this->packageResolver->canResolve($valueSetUrl)) {
            return $this->packageResolver->validateCode($valueSetUrl, $system, $code, $display, $version);
        }

        // Neither resolver can handle this ValueSet
        throw new \RuntimeException(
            sprintf(
                'Cannot resolve ValueSet "%s": remote server unavailable and not found in packages',
                $valueSetUrl
            )
        );
    }

    public function expand(
        string $valueSetUrl,
        ?string $version = null,
        ?int $count = null,
        ?int $offset = null
    ): array {
        // Try remote first
        try {
            return $this->remoteResolver->expand($valueSetUrl, $version, $count, $offset);
        } catch (\Exception $e) {
            // Remote failed, fall back to package
        }

        // Fall back to package resolver
        if ($this->packageResolver->canResolve($valueSetUrl)) {
            return $this->packageResolver->expand($valueSetUrl, $version, $count, $offset);
        }

        // Neither resolver can handle this ValueSet
        throw new \RuntimeException(
            sprintf(
                'Cannot expand ValueSet "%s": remote server unavailable and not found in packages',
                $valueSetUrl
            )
        );
    }

    public function canResolve(string $valueSetUrl): bool
    {
        // Can resolve if either resolver can handle it
        return $this->remoteResolver->canResolve($valueSetUrl) 
            || $this->packageResolver->canResolve($valueSetUrl);
    }
}
