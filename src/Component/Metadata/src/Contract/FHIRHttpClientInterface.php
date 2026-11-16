<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Contract;

/**
 * Contract for talking to a FHIR REST server.
 *
 * Lives in `Metadata` because it is consumed across components (SDC's x-fhir-query population, and any
 * operation-style caller such as a terminology client) while its concrete HTTP implementation lives in the
 * `HttpClient` component — keeping consumers off a hard dependency on that component.
 *
 * Implementations **degrade gracefully**: any transport, HTTP-status, or parse error yields `null` rather
 * than throwing (mirroring the terminology-client posture). The null object performs no I/O.
 */
interface FHIRHttpClientInterface
{
    /**
     * Execute a FHIR search and deserialize the result Bundle into a typed model.
     *
     * @param string $search      a resource-type-rooted FHIR search string, e.g. `Observation?subject=Patient/1`
     * @param string $fhirVersion the model namespace the Bundle is deserialized into: `R4`, `R4B`, or `R5`
     *
     * @return object|null the typed Bundle resource, or null on any transport/HTTP/parse error
     */
    public function search(string $search, string $fhirVersion): ?object;

    /**
     * Send a raw request to a server-relative FHIR path and return the response body.
     *
     * The low-level transport escape hatch for operation-style callers (e.g. a terminology client invoking
     * `$validate-code`) that parse the response body themselves.
     *
     * @param string                $method  HTTP method, e.g. `GET` or `POST`
     * @param string                $path    server-relative path, e.g. `ValueSet/$validate-code?url=...`
     * @param string|null           $body    request body, or null for none
     * @param array<string, string> $headers extra request headers (a caller `Accept` overrides the default)
     *
     * @return string|null the raw response body on a 2xx response, or null on any non-2xx/transport error
     */
    public function request(string $method, string $path, ?string $body = null, array $headers = []): ?string;

    /**
     * Follow an absolute URL taken from a server-supplied `Bundle.link` (e.g. `relation = 'next'`) and
     * deserialize the result into a typed Bundle, mirroring {@see self::search()}.
     *
     * Implementations MUST reject (return null for) any URL whose origin does not match the client's own
     * configured server — this is the SSRF guardrail for server-supplied links, since a `search()` search
     * string cannot carry a foreign host but an absolute `link.url` can. Rejecting a cross-origin link is
     * indistinguishable from any other failure to the caller: pagination simply stops.
     *
     * @param string $url         an absolute URL taken verbatim from `Bundle.link.url`
     * @param string $fhirVersion the model namespace the Bundle is deserialized into: `R4`, `R4B`, or `R5`
     *
     * @return object|null the typed Bundle resource, or null when the URL is cross-origin or on any
     *                     transport/HTTP/parse error
     */
    public function followLink(string $url, string $fhirVersion): ?object;
}
