<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Tests for the FHIR memberOf() function.
 *
 * memberOf(valueSet: String): Boolean
 *  - Validates codes/Codings/CodeableConcepts against a ValueSet via the terminology server.
 *  - Returns [true]/[false] on success, [] on bad input or server error.
 *  - Throws EvaluationException when no terminology URL or HTTP client is configured.
 *
 * @author FHIR Tools Contributors
 */
final class MemberOfFunctionTest extends TestCase
{
    private const VS_URL = 'http://hl7.org/fhir/ValueSet/administrative-gender';

    private const TERM_URL = 'https://tx.fhir.org/r4';

    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    private FHIRPathEvaluator $evaluator;

    protected function setUp(): void
    {
        $this->lexer     = new FHIRPathLexer();
        $this->parser    = new FHIRPathParser();
        $this->evaluator = new FHIRPathEvaluator();
        $this->evaluator->setTerminologyUrl(self::TERM_URL);
    }

    protected function tearDown(): void
    {
        FunctionRegistry::reset();
    }

    private function evaluate(string $expression, mixed $resource): Collection
    {
        $tokens = $this->lexer->tokenize($expression);
        $ast    = $this->parser->parse($tokens);

        return $this->evaluator->evaluate($ast, $resource);
    }

    /**
     * Build a PSR-18 client + PSR-17 factory pair backed by a URL→body map.
     *
     * The $urlMap keys are exact URLs. A missing URL returns a 404 response.
     * A null value also returns 404.
     *
     * @param array<string, array<string, mixed>|null> $urlMap
     *
     * @return array{ClientInterface, RequestFactoryInterface}
     */
    private function makeHttpStack(array $urlMap): array
    {
        $capturedUrl = '';

        $requestFactory = $this->createStub(RequestFactoryInterface::class);
        $requestFactory->method('createRequest')
            ->willReturnCallback(function(string $method, mixed $uri) use (&$capturedUrl): RequestInterface {
                $capturedUrl = (string) $uri;

                $uriMock = $this->createStub(UriInterface::class);
                $uriMock->method('__toString')->willReturn($capturedUrl);

                $request = $this->createStub(RequestInterface::class);
                $request->method('getUri')->willReturn($uriMock);

                return $request;
            });

        $client = $this->createStub(ClientInterface::class);
        $client->method('sendRequest')
            ->willReturnCallback(function() use (&$capturedUrl, $urlMap): ResponseInterface {
                $body = $urlMap[$capturedUrl] ?? null;

                $stream = $this->createStub(StreamInterface::class);
                $stream->method('__toString')->willReturn($body !== null ? (string) json_encode($body) : '');

                $response = $this->createStub(ResponseInterface::class);
                $response->method('getStatusCode')->willReturn($body !== null ? 200 : 404);
                $response->method('getBody')->willReturn($stream);

                return $response;
            });

        return [$client, $requestFactory];
    }

    /**
     * Build a Parameters resource (server response) with a `result` boolean.
     *
     * @return array<string, mixed>
     */
    private function parametersResponse(bool $result): array
    {
        return [
            'resourceType' => 'Parameters',
            'parameter'    => [
                ['name' => 'result', 'valueBoolean' => $result],
            ],
        ];
    }

    /**
     * Build the expected validate-code URL for a plain code.
     */
    private function codeUrl(string $code): string
    {
        return self::TERM_URL . '/ValueSet/$validate-code?' . http_build_query([
            'url'  => self::VS_URL,
            'code' => $code,
        ]);
    }

    /**
     * Build the expected validate-code URL for a Coding.
     *
     * @param array<string, string> $params
     */
    private function codingUrl(array $params): string
    {
        return self::TERM_URL . '/ValueSet/$validate-code?' . http_build_query($params);
    }

    // -------------------------------------------------------------------------
    // String (plain code) input
    // -------------------------------------------------------------------------

    public function testStringCodeReturnsTrueWhenServerSaysTrue(): void
    {
        [$client, $factory] = $this->makeHttpStack([$this->codeUrl('active') => $this->parametersResponse(true)]);
        $this->evaluator->setHttpClient($client, $factory);

        $resource = ['status' => 'active'];
        $result   = $this->evaluate(sprintf("status.memberOf('%s')", self::VS_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertTrue($result->first());
    }

    public function testStringCodeReturnsFalseWhenServerSaysFalse(): void
    {
        [$client, $factory] = $this->makeHttpStack([$this->codeUrl('inactive') => $this->parametersResponse(false)]);
        $this->evaluator->setHttpClient($client, $factory);

        $resource = ['status' => 'inactive'];
        $result   = $this->evaluate(sprintf("status.memberOf('%s')", self::VS_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertFalse($result->first());
    }

    // -------------------------------------------------------------------------
    // Coding input
    // -------------------------------------------------------------------------

    public function testCodingReturnsTrueWhenServerSaysTrue(): void
    {
        $coding             = ['system' => 'http://loinc.org', 'code' => 'active'];
        $url                = $this->codingUrl(['url' => self::VS_URL, 'code' => 'active', 'system' => 'http://loinc.org']);
        [$client, $factory] = $this->makeHttpStack([$url => $this->parametersResponse(true)]);
        $this->evaluator->setHttpClient($client, $factory);

        $resource = ['gender' => $coding];
        $result   = $this->evaluate(sprintf("gender.memberOf('%s')", self::VS_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertTrue($result->first());
    }

    public function testCodingReturnsFalseWhenServerSaysFalse(): void
    {
        $coding             = ['system' => 'http://loinc.org', 'code' => 'unknown'];
        $url                = $this->codingUrl(['url' => self::VS_URL, 'code' => 'unknown', 'system' => 'http://loinc.org']);
        [$client, $factory] = $this->makeHttpStack([$url => $this->parametersResponse(false)]);
        $this->evaluator->setHttpClient($client, $factory);

        $resource = ['gender' => $coding];
        $result   = $this->evaluate(sprintf("gender.memberOf('%s')", self::VS_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertFalse($result->first());
    }

    // -------------------------------------------------------------------------
    // CodeableConcept input
    // -------------------------------------------------------------------------

    public function testCodeableConceptReturnsTrueWhenSecondCodingMatches(): void
    {
        // First coding does NOT match, second DOES match
        $firstUrl  = $this->codingUrl(['url' => self::VS_URL, 'code' => 'no-match', 'system' => 'http://example.com']);
        $secondUrl = $this->codingUrl(['url' => self::VS_URL, 'code' => 'active', 'system' => 'http://loinc.org']);

        [$client, $factory] = $this->makeHttpStack([
            $firstUrl  => $this->parametersResponse(false),
            $secondUrl => $this->parametersResponse(true),
        ]);
        $this->evaluator->setHttpClient($client, $factory);

        $resource = [
            'code' => [
                'coding' => [
                    ['system' => 'http://example.com', 'code' => 'no-match'],
                    ['system' => 'http://loinc.org',   'code' => 'active'],
                ],
            ],
        ];
        $result = $this->evaluate(sprintf("code.memberOf('%s')", self::VS_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertTrue($result->first());
    }

    public function testCodeableConceptReturnsFalseWhenNoCodingMatches(): void
    {
        $firstUrl  = $this->codingUrl(['url' => self::VS_URL, 'code' => 'a', 'system' => 'http://example.com']);
        $secondUrl = $this->codingUrl(['url' => self::VS_URL, 'code' => 'b', 'system' => 'http://example.com']);

        [$client, $factory] = $this->makeHttpStack([
            $firstUrl  => $this->parametersResponse(false),
            $secondUrl => $this->parametersResponse(false),
        ]);
        $this->evaluator->setHttpClient($client, $factory);

        $resource = [
            'code' => [
                'coding' => [
                    ['system' => 'http://example.com', 'code' => 'a'],
                    ['system' => 'http://example.com', 'code' => 'b'],
                ],
            ],
        ];
        $result = $this->evaluate(sprintf("code.memberOf('%s')", self::VS_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertFalse($result->first());
    }

    // -------------------------------------------------------------------------
    // Bad input → empty collection
    // -------------------------------------------------------------------------

    public function testEmptyInputReturnsEmpty(): void
    {
        [$client, $factory] = $this->makeHttpStack([]);
        $this->evaluator->setHttpClient($client, $factory);

        $result = $this->evaluate(sprintf("{}.memberOf('%s')", self::VS_URL), null);

        self::assertTrue($result->isEmpty());
    }

    public function testMultipleItemsInInputReturnsEmpty(): void
    {
        [$client, $factory] = $this->makeHttpStack([]);
        $this->evaluator->setHttpClient($client, $factory);

        // Build a collection with 2 items via union
        $resource = ['a' => 'val1', 'b' => 'val2'];
        $result   = $this->evaluate(sprintf("(a | b).memberOf('%s')", self::VS_URL), $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testParameterNotAStringReturnsEmpty(): void
    {
        [$client, $factory] = $this->makeHttpStack([]);
        $this->evaluator->setHttpClient($client, $factory);

        // Pass an integer as the parameter instead of a string
        $resource = ['status' => 'active'];
        $result   = $this->evaluate('status.memberOf(42)', $resource);

        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Missing configuration → EvaluationException
    // -------------------------------------------------------------------------

    public function testNoTerminologyUrlAndNoFhirServerUrlThrows(): void
    {
        // Fresh evaluator with neither terminologyUrl nor fhirServerUrl set
        $evaluator = new FHIRPathEvaluator();

        [$client, $factory] = $this->makeHttpStack([]);
        $evaluator->setHttpClient($client, $factory);

        $tokens = $this->lexer->tokenize(sprintf("status.memberOf('%s')", self::VS_URL));
        $ast    = $this->parser->parse($tokens);

        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessageMatches('/terminology server URL/i');

        $evaluator->evaluate($ast, ['status' => 'active']);
    }

    public function testNoHttpClientThrows(): void
    {
        // terminologyUrl is set via setUp, but no HTTP client
        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessageMatches('/HTTP client/i');

        $this->evaluate(sprintf("status.memberOf('%s')", self::VS_URL), ['status' => 'active']);
    }

    // -------------------------------------------------------------------------
    // Server error scenarios → empty collection
    // -------------------------------------------------------------------------

    public function testServerReturnsNon2xxResponseReturnsEmpty(): void
    {
        // URL not in map → makeHttpStack returns 404
        [$client, $factory] = $this->makeHttpStack([]);
        $this->evaluator->setHttpClient($client, $factory);

        $resource = ['status' => 'active'];
        $result   = $this->evaluate(sprintf("status.memberOf('%s')", self::VS_URL), $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testMalformedParametersResponseReturnsEmpty(): void
    {
        // Response is valid JSON but missing the `parameter` array
        $url                = $this->codeUrl('active');
        [$client, $factory] = $this->makeHttpStack([$url => ['resourceType' => 'Parameters']]);
        $this->evaluator->setHttpClient($client, $factory);

        $resource = ['status' => 'active'];
        $result   = $this->evaluate(sprintf("status.memberOf('%s')", self::VS_URL), $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testResponseMissingResultParameterReturnsEmpty(): void
    {
        // Parameters resource exists but has no `result` entry
        $url  = $this->codeUrl('active');
        $data = [
            'resourceType' => 'Parameters',
            'parameter'    => [
                ['name' => 'display', 'valueString' => 'Active'],
            ],
        ];
        [$client, $factory] = $this->makeHttpStack([$url => $data]);
        $this->evaluator->setHttpClient($client, $factory);

        $resource = ['status' => 'active'];
        $result   = $this->evaluate(sprintf("status.memberOf('%s')", self::VS_URL), $resource);

        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // terminologyUrl fallback to fhirServerUrl
    // -------------------------------------------------------------------------

    public function testFallsBackToFhirServerUrlWhenTerminologyUrlNotSet(): void
    {
        $evaluator = new FHIRPathEvaluator();
        $evaluator->setFhirServerUrl('https://fhir.example.com');

        $expectedUrl = 'https://fhir.example.com/ValueSet/$validate-code?' . http_build_query([
            'url'  => self::VS_URL,
            'code' => 'active',
        ]);

        [$client, $factory] = $this->makeHttpStack([$expectedUrl => $this->parametersResponse(true)]);
        $evaluator->setHttpClient($client, $factory);

        $tokens = $this->lexer->tokenize(sprintf("status.memberOf('%s')", self::VS_URL));
        $ast    = $this->parser->parse($tokens);

        $result = $evaluator->evaluate($ast, ['status' => 'active']);

        self::assertCount(1, $result->toArray());
        self::assertTrue($result->first());
    }

    // -------------------------------------------------------------------------
    // Coding with version and display fields included in query
    // -------------------------------------------------------------------------

    public function testCodingWithVersionAndDisplayIncludedInQuery(): void
    {
        $coding = [
            'system'  => 'http://loinc.org',
            'code'    => '1234-5',
            'version' => '2.68',
            'display' => 'Some Display',
        ];
        $url = $this->codingUrl([
            'url'     => self::VS_URL,
            'code'    => '1234-5',
            'system'  => 'http://loinc.org',
            'version' => '2.68',
            'display' => 'Some Display',
        ]);
        [$client, $factory] = $this->makeHttpStack([$url => $this->parametersResponse(true)]);
        $this->evaluator->setHttpClient($client, $factory);

        $resource = ['obs' => $coding];
        $result   = $this->evaluate(sprintf("obs.memberOf('%s')", self::VS_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertTrue($result->first());
    }
}
