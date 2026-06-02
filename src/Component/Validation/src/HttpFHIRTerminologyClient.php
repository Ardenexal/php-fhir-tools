<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Calls a FHIR terminology server's ValueSet/$validate-code operation.
 * Returns false on any HTTP or parse error (graceful degradation).
 */
final class HttpFHIRTerminologyClient implements FHIRTerminologyClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $serverUrl,
    ) {
    }

    public function validateCode(string $valueSetUrl, mixed $value): bool
    {
        $code = $this->toCodeString($value);
        if ($code === null) {
            return false;
        }

        $url = rtrim($this->serverUrl, '/') . '/ValueSet/$validate-code?' . http_build_query([
            'url'  => $valueSetUrl,
            'code' => $code,
        ]);

        try {
            $response = $this->httpClient->request('GET', $url);

            if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
                return false;
            }

            $data = json_decode($response->getContent(), true);
        } catch (TransportExceptionInterface) {
            return false;
        }

        if (!is_array($data) || !isset($data['parameter']) || !is_array($data['parameter'])) {
            return false;
        }

        foreach ($data['parameter'] as $param) {
            if (
                is_array($param)
                && ($param['name'] ?? null) === 'result'
                && array_key_exists('valueBoolean', $param)
            ) {
                return (bool) $param['valueBoolean'];
            }
        }

        return false;
    }

    private function toCodeString(mixed $value): ?string
    {
        if ($value instanceof \BackedEnum) {
            return (string) $value->value;
        }

        if (is_string($value) && $value !== '') {
            return $value;
        }

        if (is_int($value)) {
            return (string) $value;
        }

        return null;
    }
}
