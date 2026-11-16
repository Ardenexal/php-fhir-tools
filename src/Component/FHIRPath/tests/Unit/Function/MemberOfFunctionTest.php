<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the FHIR memberOf() function.
 *
 * memberOf(valueSet: String): Boolean
 *  - Validates codes/Codings/CodeableConcepts against a ValueSet via the terminology server.
 *  - Returns [true]/[false] on success, [] on bad input or server error.
 *  - Throws EvaluationException when no terminology HTTP client is configured.
 *
 * @author FHIR Tools Contributors
 */
final class MemberOfFunctionTest extends TestCase
{
    private const VS_URL = 'http://hl7.org/fhir/ValueSet/administrative-gender';

    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    private FHIRPathEvaluator $evaluator;

    protected function setUp(): void
    {
        $this->lexer     = new FHIRPathLexer();
        $this->parser    = new FHIRPathParser();
        $this->evaluator = new FHIRPathEvaluator();
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
     * Build a FHIRHttpClientInterface stub backed by a server-relative-path→body map.
     *
     * The $pathMap keys are exact paths passed to request() (no base URL). A missing path
     * or a null value returns null, mirroring the interface's graceful-degradation contract.
     *
     * @param array<string, array<string, mixed>|null> $pathMap
     */
    private function makeFhirHttpClient(array $pathMap): FHIRHttpClientInterface
    {
        $client = $this->createStub(FHIRHttpClientInterface::class);
        $client->method('request')
            ->willReturnCallback(function(string $method, string $path) use ($pathMap): ?string {
                $body = $pathMap[$path] ?? null;

                return $body !== null ? json_encode($body) : null;
            });

        return $client;
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
     * Build the expected validate-code path for a plain code.
     */
    private function codePath(string $code): string
    {
        return 'ValueSet/$validate-code?' . http_build_query([
            'url'  => self::VS_URL,
            'code' => $code,
        ]);
    }

    /**
     * Build the expected validate-code path for a Coding.
     *
     * @param array<string, string> $params
     */
    private function codingPath(array $params): string
    {
        return 'ValueSet/$validate-code?' . http_build_query($params);
    }

    // -------------------------------------------------------------------------
    // String (plain code) input
    // -------------------------------------------------------------------------

    public function testStringCodeReturnsTrueWhenServerSaysTrue(): void
    {
        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([$this->codePath('active') => $this->parametersResponse(true)]));

        $resource = ['status' => 'active'];
        $result   = $this->evaluate(sprintf("status.memberOf('%s')", self::VS_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertTrue($result->first());
    }

    public function testStringCodeReturnsFalseWhenServerSaysFalse(): void
    {
        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([$this->codePath('inactive') => $this->parametersResponse(false)]));

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
        $coding = ['system' => 'http://loinc.org', 'code' => 'active'];
        $path   = $this->codingPath(['url' => self::VS_URL, 'code' => 'active', 'system' => 'http://loinc.org']);
        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([$path => $this->parametersResponse(true)]));

        $resource = ['gender' => $coding];
        $result   = $this->evaluate(sprintf("gender.memberOf('%s')", self::VS_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertTrue($result->first());
    }

    public function testCodingReturnsFalseWhenServerSaysFalse(): void
    {
        $coding = ['system' => 'http://loinc.org', 'code' => 'unknown'];
        $path   = $this->codingPath(['url' => self::VS_URL, 'code' => 'unknown', 'system' => 'http://loinc.org']);
        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([$path => $this->parametersResponse(false)]));

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
        $firstPath  = $this->codingPath(['url' => self::VS_URL, 'code' => 'no-match', 'system' => 'http://example.com']);
        $secondPath = $this->codingPath(['url' => self::VS_URL, 'code' => 'active', 'system' => 'http://loinc.org']);

        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([
            $firstPath  => $this->parametersResponse(false),
            $secondPath => $this->parametersResponse(true),
        ]));

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
        $firstPath  = $this->codingPath(['url' => self::VS_URL, 'code' => 'a', 'system' => 'http://example.com']);
        $secondPath = $this->codingPath(['url' => self::VS_URL, 'code' => 'b', 'system' => 'http://example.com']);

        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([
            $firstPath  => $this->parametersResponse(false),
            $secondPath => $this->parametersResponse(false),
        ]));

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
        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([]));

        $result = $this->evaluate(sprintf("{}.memberOf('%s')", self::VS_URL), null);

        self::assertTrue($result->isEmpty());
    }

    public function testMultipleItemsInInputReturnsEmpty(): void
    {
        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([]));

        // Build a collection with 2 items via union
        $resource = ['a' => 'val1', 'b' => 'val2'];
        $result   = $this->evaluate(sprintf("(a | b).memberOf('%s')", self::VS_URL), $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testParameterNotAStringReturnsEmpty(): void
    {
        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([]));

        // Pass an integer as the parameter instead of a string
        $resource = ['status' => 'active'];
        $result   = $this->evaluate('status.memberOf(42)', $resource);

        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Missing configuration → EvaluationException
    // -------------------------------------------------------------------------

    public function testNoTerminologyHttpClientConfiguredThrows(): void
    {
        // Fresh evaluator with neither terminologyHttpClient nor fhirHttpClient set
        $evaluator = new FHIRPathEvaluator();

        $tokens = $this->lexer->tokenize(sprintf("status.memberOf('%s')", self::VS_URL));
        $ast    = $this->parser->parse($tokens);

        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessageMatches('/terminology HTTP client/i');

        $evaluator->evaluate($ast, ['status' => 'active']);
    }

    // -------------------------------------------------------------------------
    // Server error scenarios → empty collection
    // -------------------------------------------------------------------------

    public function testServerReturnsNon2xxResponseReturnsEmpty(): void
    {
        // Path not in map → makeFhirHttpClient returns null
        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([]));

        $resource = ['status' => 'active'];
        $result   = $this->evaluate(sprintf("status.memberOf('%s')", self::VS_URL), $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testMalformedParametersResponseReturnsEmpty(): void
    {
        // Response is valid JSON but missing the `parameter` array
        $path = $this->codePath('active');
        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([$path => ['resourceType' => 'Parameters']]));

        $resource = ['status' => 'active'];
        $result   = $this->evaluate(sprintf("status.memberOf('%s')", self::VS_URL), $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testResponseMissingResultParameterReturnsEmpty(): void
    {
        // Parameters resource exists but has no `result` entry
        $path = $this->codePath('active');
        $data = [
            'resourceType' => 'Parameters',
            'parameter'    => [
                ['name' => 'display', 'valueString' => 'Active'],
            ],
        ];
        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([$path => $data]));

        $resource = ['status' => 'active'];
        $result   = $this->evaluate(sprintf("status.memberOf('%s')", self::VS_URL), $resource);

        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // terminologyHttpClient fallback to fhirHttpClient
    // -------------------------------------------------------------------------

    public function testFallsBackToFhirHttpClientWhenTerminologyHttpClientNotSet(): void
    {
        $evaluator = new FHIRPathEvaluator();

        $expectedPath = 'ValueSet/$validate-code?' . http_build_query([
            'url'  => self::VS_URL,
            'code' => 'active',
        ]);

        $evaluator->setFhirHttpClient($this->makeFhirHttpClient([$expectedPath => $this->parametersResponse(true)]));

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
        $path = $this->codingPath([
            'url'     => self::VS_URL,
            'code'    => '1234-5',
            'system'  => 'http://loinc.org',
            'version' => '2.68',
            'display' => 'Some Display',
        ]);
        $this->evaluator->setTerminologyHttpClient($this->makeFhirHttpClient([$path => $this->parametersResponse(true)]));

        $resource = ['obs' => $coding];
        $result   = $this->evaluate(sprintf("obs.memberOf('%s')", self::VS_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertTrue($result->first());
    }
}
