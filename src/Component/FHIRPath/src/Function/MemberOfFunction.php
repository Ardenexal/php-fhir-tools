<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * memberOf(valueSet: String): Boolean
 *
 * FHIR R4 FHIRPath extension function.
 *
 * Checks whether a code, Coding, or CodeableConcept is a member of a given ValueSet
 * by querying the terminology server's `ValueSet/$validate-code` operation.
 *
 * Input type dispatch:
 *  - String          → plain code; queries `url={vsUrl}&code={value}`
 *  - Coding array    → array with `code` key; queries with `system`, `code`, and optionally `display`
 *  - CodeableConcept → array with `coding` key; iterates each Coding, returns true if any match
 *
 * Returns:
 *  - `[true]`  — code is a member of the ValueSet
 *  - `[false]` — code is not a member of the ValueSet
 *  - `[]`      — bad input, non-2xx response, or malformed Parameters response
 *
 * Throws EvaluationException when no terminology URL or HTTP client is configured
 * (mirrors the JS reference implementation's throw behaviour).
 *
 * Configuration on FHIRPathEvaluator:
 *   $evaluator->setTerminologyUrl('https://tx.fhir.org/r4');
 *   $evaluator->setHttpClient($psr18Client, $psr17RequestFactory);
 *
 * Falls back to fhirServerUrl when terminologyUrl is not explicitly set.
 *
 * Does NOT depend on Models or CodeGeneration.
 *
 * Spec reference: FHIR R4 FHIRPath supplement §2.4
 *
 * @author FHIR Tools Contributors
 */
final class MemberOfFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('memberOf');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        // Spec: input must be exactly one non-null item
        if ($input->count() !== 1) {
            return Collection::empty();
        }

        $item = $input->first();
        if ($item === null) {
            return Collection::empty();
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context', 0, 0);
        }

        // Resolve the ValueSet URL parameter
        $paramResult = $evaluator->evaluateWithContext($parameters[0], $context);
        $vsUrl       = $paramResult->first();
        if (!is_string($vsUrl) || $vsUrl === '') {
            return Collection::empty();
        }

        // Require a terminology server URL (mirrors JS throw behaviour)
        $terminologyUrl = $evaluator->getTerminologyUrl();
        if ($terminologyUrl === null) {
            throw new EvaluationException('memberOf() requires a terminology server URL — call setTerminologyUrl() or setFhirServerUrl() on the evaluator', 0, 0);
        }

        // Require an HTTP client (mirrors JS throw behaviour)
        $httpClient     = $evaluator->getHttpClient();
        $requestFactory = $evaluator->getRequestFactory();
        if ($httpClient === null || $requestFactory === null) {
            throw new EvaluationException('memberOf() requires an HTTP client — call setHttpClient() on the evaluator', 0, 0);
        }

        return $this->dispatchInput($item, $vsUrl, $terminologyUrl, $httpClient, $requestFactory);
    }

    /**
     * Dispatch input to the correct validation strategy based on FHIR type.
     *
     * @param string                  $vsUrl          ValueSet URL
     * @param string                  $terminologyUrl Base terminology server URL (no trailing slash)
     * @param ClientInterface         $httpClient     PSR-18 HTTP client
     * @param RequestFactoryInterface $requestFactory PSR-17 request factory
     */
    private function dispatchInput(
        mixed $item,
        string $vsUrl,
        string $terminologyUrl,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory
    ): Collection {
        // Plain code string
        if (is_string($item)) {
            $result = $this->validateCode($vsUrl, ['url' => $vsUrl, 'code' => $item], $terminologyUrl, $httpClient, $requestFactory);

            return $result !== null ? Collection::single($result) : Collection::empty();
        }

        if (!is_array($item)) {
            return Collection::empty();
        }

        // CodeableConcept: has a `coding` key containing a list of Codings
        if (array_key_exists('coding', $item) && is_array($item['coding'])) {
            return $this->validateCodeableConcept($item['coding'], $vsUrl, $terminologyUrl, $httpClient, $requestFactory);
        }

        // Coding: has a `code` key
        if (array_key_exists('code', $item)) {
            $queryParams = $this->buildCodingQueryParams($item, $vsUrl);
            if ($queryParams === null) {
                return Collection::empty();
            }

            $result = $this->validateCode($vsUrl, $queryParams, $terminologyUrl, $httpClient, $requestFactory);

            return $result !== null ? Collection::single($result) : Collection::empty();
        }

        return Collection::empty();
    }

    /**
     * Validate a CodeableConcept by iterating its Coding list.
     * Returns true if any Coding validates, false if none do, or empty on error.
     *
     * @param array<int, mixed>       $codings        List of Coding arrays
     * @param string                  $vsUrl          ValueSet URL
     * @param string                  $terminologyUrl Base terminology server URL
     * @param ClientInterface         $httpClient     PSR-18 HTTP client
     * @param RequestFactoryInterface $requestFactory PSR-17 request factory
     */
    private function validateCodeableConcept(
        array $codings,
        string $vsUrl,
        string $terminologyUrl,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory
    ): Collection {
        $anyResult = false;

        foreach ($codings as $coding) {
            if (!is_array($coding)) {
                continue;
            }

            $queryParams = $this->buildCodingQueryParams($coding, $vsUrl);
            if ($queryParams === null) {
                continue;
            }

            $result = $this->validateCode($vsUrl, $queryParams, $terminologyUrl, $httpClient, $requestFactory);

            if ($result === true) {
                return Collection::single(true);
            }

            if ($result === false) {
                // At least one Coding had a definitive server response — track it
                $anyResult = true;
            }
        }

        // Only return false when we got at least one definitive response
        return $anyResult ? Collection::single(false) : Collection::empty();
    }

    /**
     * Build query params for a Coding array.
     *
     * Extracts `system`, `code`, and optionally `display` and `version`.
     * Returns null if `code` is absent or empty (required field).
     *
     * @param array<string, mixed> $coding The Coding array
     * @param string               $vsUrl  ValueSet URL (always included as `url`)
     *
     * @return array<string, string>|null
     */
    private function buildCodingQueryParams(array $coding, string $vsUrl): ?array
    {
        $code = array_key_exists('code', $coding) ? $coding['code'] : null;
        if (!is_string($code) || $code === '') {
            return null;
        }

        $params = ['url' => $vsUrl, 'code' => $code];

        $system = array_key_exists('system', $coding) ? $coding['system'] : null;
        if (is_string($system) && $system !== '') {
            $params['system'] = $system;
        }

        $version = array_key_exists('version', $coding) ? $coding['version'] : null;
        if (is_string($version) && $version !== '') {
            $params['version'] = $version;
        }

        $display = array_key_exists('display', $coding) ? $coding['display'] : null;
        if (is_string($display) && $display !== '') {
            $params['display'] = $display;
        }

        return $params;
    }

    /**
     * Call the terminology server's `ValueSet/$validate-code` endpoint and parse the result.
     *
     * Returns true/false from the `result` parameter, or null on HTTP/parse error.
     *
     * @param string                  $vsUrl          ValueSet URL (used only for URL building)
     * @param array<string, string>   $queryParams    Query params to send
     * @param string                  $terminologyUrl Base terminology server URL (no trailing slash)
     * @param ClientInterface         $httpClient     PSR-18 HTTP client
     * @param RequestFactoryInterface $requestFactory PSR-17 request factory
     */
    private function validateCode(
        string $vsUrl,
        array $queryParams,
        string $terminologyUrl,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory
    ): ?bool {
        $url  = $terminologyUrl . '/ValueSet/$validate-code?' . http_build_query($queryParams);
        $data = $this->fetch($url, $httpClient, $requestFactory);

        if ($data === null) {
            return null;
        }

        // Parameters resource: find parameter with name='result' and a valueBoolean
        if (!isset($data['parameter']) || !is_array($data['parameter'])) {
            return null;
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

        return null;
    }

    /**
     * Send a GET request via the PSR-18 client and decode the JSON response body.
     *
     * Returns null on any transport error, non-2xx response, or non-JSON body.
     *
     * @param string                  $url            URL to fetch
     * @param ClientInterface         $httpClient     PSR-18 HTTP client
     * @param RequestFactoryInterface $requestFactory PSR-17 request factory
     *
     * @return array<string, mixed>|null
     */
    private function fetch(string $url, ClientInterface $httpClient, RequestFactoryInterface $requestFactory): ?array
    {
        try {
            $request  = $requestFactory->createRequest('GET', $url);
            $response = $httpClient->sendRequest($request);

            if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
                return null;
            }

            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            return is_array($data) ? $data : null;
        } catch (ClientExceptionInterface) {
            return null;
        }
    }
}
