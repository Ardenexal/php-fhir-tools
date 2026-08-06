<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;

/**
 * Test double recording the exact arguments a caller dispatches through it — used by
 * {@see SdcPopulateSsrfTest} to prove request-supplied fields never leak into the resolved search string
 * or which client/server handles it.
 */
final class RecordingFHIRHttpClient implements FHIRHttpClientInterface
{
    public ?string $lastSearch = null;

    public ?string $lastRequestPath = null;

    /** Canned response for request() — null (the default) keeps the original "always fails" behavior. */
    public ?string $requestResponseBody = null;

    public function search(string $search, string $fhirVersion): ?object
    {
        $this->lastSearch = $search;

        return null;
    }

    public function request(string $method, string $path, ?string $body = null, array $headers = []): ?string
    {
        $this->lastRequestPath = $path;

        return $this->requestResponseBody;
    }

    public function followLink(string $url, string $fhirVersion): ?object
    {
        return null;
    }
}
