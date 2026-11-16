<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;

/**
 * Null-object FHIR HTTP client: performs no I/O and returns null for every call.
 *
 * Wired as the default when no live FHIR server is configured, so features that can optionally fetch from a
 * server (e.g. SDC x-fhir-query population) stay offline-first — they behave exactly as if the fetch found
 * nothing, with no network dependency.
 */
final class NullFHIRHttpClient implements FHIRHttpClientInterface
{
    public function search(string $search, string $fhirVersion): ?object
    {
        return null;
    }

    public function request(string $method, string $path, ?string $body = null, array $headers = []): ?string
    {
        return null;
    }

    public function followLink(string $url, string $fhirVersion): ?object
    {
        return null;
    }
}
