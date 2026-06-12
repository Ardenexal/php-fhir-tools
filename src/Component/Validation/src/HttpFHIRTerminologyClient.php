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
        private readonly bool $usePost = false,
    ) {
    }

    /**
     * Returns true when $value is a valid member of the named value set.
     *
     * Calls GET /ValueSet/$validate-code with url and code parameters. Returns false on any
     * HTTP error, transport failure, malformed response, or when the value cannot be converted
     * to a string (e.g. null or unsupported object type).
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param mixed  $value       The code to validate; accepts string, int, or BackedEnum
     *
     * @return bool True when the code is a valid member, false otherwise or on error
     */
    public function validateCode(string $valueSetUrl, mixed $value): bool
    {
        $code = $this->toCodeString($value);
        if ($code === null) {
            return false;
        }

        $body = $this->dispatchRaw('ValueSet/$validate-code', ['url' => $valueSetUrl, 'code' => $code]);

        return $body !== null && $this->parseResultParameter($body);
    }

    /**
     * Returns true when the system+code pair is a valid member of the named value set.
     *
     * Calls GET /ValueSet/$validate-code with url, system, and code parameters. Returns false
     * on any HTTP error, transport failure, or malformed response.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      The coding system URI (e.g. 'http://loinc.org')
     * @param string $code        The code within that system
     *
     * @return bool True when the coding is a valid member, false otherwise or on error
     */
    public function validateCoding(string $valueSetUrl, string $system, string $code): bool
    {
        $body = $this->dispatchRaw('ValueSet/$validate-code', [
            'url'    => $valueSetUrl,
            'system' => $system,
            'code'   => $code,
        ]);

        return $body !== null && $this->parseResultParameter($body);
    }

    /**
     * Validates the system+code pair and checks whether the provided display matches the canonical one.
     *
     * Calls GET /ValueSet/$validate-code with url, system, code, and display parameters. Parses
     * both the result boolean and the optional display parameter from the response. Returns
     * valid=false on any HTTP error, transport failure, or malformed response.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      The coding system URI (e.g. 'http://loinc.org')
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
        $body = $this->dispatchRaw('ValueSet/$validate-code', [
            'url'     => $valueSetUrl,
            'system'  => $system,
            'code'    => $code,
            'display' => $display,
        ]);

        if ($body === null) {
            return new CodingValidationResult(false, null);
        }

        return $this->parseFullResult($body, $display);
    }

    /**
     * Dispatches the request (GET or POST) and returns the raw response body, or null on any error.
     *
     * @param array<string, string> $params
     */
    private function dispatchRaw(string $endpoint, array $params): ?string
    {
        $base = rtrim($this->serverUrl, '/') . '/' . ltrim($endpoint, '/');

        try {
            if ($this->usePost) {
                $response = $this->httpClient->request('POST', $base, [
                    'headers' => [
                        'Content-Type' => 'application/fhir+json',
                        'Accept'       => 'application/fhir+json',
                    ],
                    'body' => $this->buildParametersBody($params),
                ]);
            } else {
                $response = $this->httpClient->request('GET', $base . '?' . http_build_query($params), [
                    'headers' => ['Accept' => 'application/fhir+json'],
                ]);
            }

            if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
                return null;
            }

            return $response->getContent();
        } catch (TransportExceptionInterface|\JsonException) {
            return null;
        }
    }

    /**
     * Extracts the result boolean from a FHIR Parameters response body.
     */
    private function parseResultParameter(string $body): bool
    {
        $data = json_decode($body, true);

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

    /**
     * Parses both the result boolean and optional display correction from a FHIR Parameters response body.
     */
    private function parseFullResult(string $body, string $providedDisplay): CodingValidationResult
    {
        $data = json_decode($body, true);

        if (!is_array($data) || !isset($data['parameter']) || !is_array($data['parameter'])) {
            return new CodingValidationResult(false, null);
        }

        $valid          = false;
        $correctDisplay = null;

        foreach ($data['parameter'] as $param) {
            if (!is_array($param)) {
                continue;
            }

            if (($param['name'] ?? null) === 'result' && array_key_exists('valueBoolean', $param)) {
                $valid = (bool) $param['valueBoolean'];
            } elseif (($param['name'] ?? null) === 'display' && isset($param['valueString'])) {
                $serverDisplay = (string) $param['valueString'];
                if ($serverDisplay !== $providedDisplay) {
                    $correctDisplay = $serverDisplay;
                }
            }
        }

        return new CodingValidationResult($valid, $correctDisplay);
    }

    /**
     * Builds a FHIR Parameters JSON body for POST $validate-code requests.
     *
     * Maps each param name to the correct FHIR value type (valueUri, valueCode, valueString).
     *
     * @param array<string, string> $params
     */
    private function buildParametersBody(array $params): string
    {
        $parameters = [];

        foreach ($params as $name => $value) {
            if ($value === '') {
                continue;
            }

            $parameters[] = match ($name) {
                'url', 'system' => ['name' => $name, 'valueUri'  => $value],
                'code'          => ['name' => $name, 'valueCode' => $value],
                default         => ['name' => $name, 'valueString' => $value],
            };
        }

        return json_encode(['resourceType' => 'Parameters', 'parameter' => $parameters], JSON_THROW_ON_ERROR);
    }

    /**
     * Converts a mixed value to a string code suitable for a query parameter, or null when unsupported.
     *
     * Supports string (non-empty), int, and BackedEnum. Returns null for null, empty strings,
     * and any other type so callers can short-circuit and return false without making an HTTP call.
     *
     * @param mixed $value The raw value to convert
     *
     * @return string|null String representation, or null if the value cannot be used as a code
     */
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
