<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Talks to a FHIR REST server over HTTP, returning typed models and degrading gracefully.
 *
 * This is the shared transport for the toolkit: it owns base-URL joining, FHIR JSON headers, status
 * handling, and the graceful-null error posture. Higher-level FHIR operations (search, `$validate-code`,
 * …) compose it — the transport itself is operation-agnostic.
 *
 * A search deserializes the result Bundle into the model namespace for the requested FHIR version. Because
 * the serializer stack is version-scoped, one is built (and memoised) per version on first use.
 */
final class FHIRHttpClient implements FHIRHttpClientInterface
{
    /**
     * Version-scoped serializers, built lazily and reused across calls.
     *
     * @var array<string, FHIRSerializationService>
     */
    private array $serializers = [];

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $baseUrl,
    ) {
    }

    public function search(string $search, string $fhirVersion): ?object
    {
        // Version is threaded explicitly: FHIR JSON carries no reliable version marker, so the target model
        // class cannot be auto-detected. An unknown version is a programmer error (\ValueError), not a
        // runtime server condition, so it is intentionally not swallowed by the graceful-null posture.
        $version = FhirVersion::from($fhirVersion);

        $body = $this->request('GET', $search);
        if ($body === null) {
            return null;
        }

        $bundleClass = sprintf('Ardenexal\\FHIRTools\\Component\\Models\\%s\\Resource\\BundleResource', $version->value);

        try {
            return $this->serializerFor($version)->deserializeFromJson($body, $bundleClass);
        } catch (\Throwable) { // -- rationale: graceful degradation — a malformed/unexpected server body yields null, never an exception (mirrors the transport-error posture); the specific failure mode is not actionable to the caller.
            return null;
        }
    }

    public function request(string $method, string $path, ?string $body = null, array $headers = []): ?string
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');

        // A caller-supplied Accept overrides the default; other caller headers are added.
        $options = ['headers' => $headers + ['Accept' => 'application/fhir+json']];
        if ($body !== null) {
            $options['body'] = $body;
        }

        try {
            $response = $this->httpClient->request($method, $url, $options);

            $status = $response->getStatusCode();
            if ($status < 200 || $status >= 300) {
                return null;
            }

            return $response->getContent();
        } catch (HttpClientExceptionInterface) {
            return null;
        }
    }

    private function serializerFor(FhirVersion $version): FHIRSerializationService
    {
        return $this->serializers[$version->value] ??= FHIRSerializationService::createDefault($version);
    }
}
