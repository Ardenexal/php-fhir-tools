<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Tests for the FHIR resolve() function.
 *
 * resolve() resolves Reference.reference or URI strings to FHIR resources.
 * Resolution strategies:
 *  1. Fragment-only (#id)     → searches rootResource.contained
 *  2. Absolute URL (https://) → fetched via a raw PSR-18 HTTP client (may point at any host)
 *  3. Relative URL (Type/id)  → fetched via the configured FHIRHttpClientInterface
 *
 * @author FHIR Tools Contributors
 */
final class ResolveFunctionTest extends TestCase
{
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
     * Build a PSR-18 client + PSR-17 factory pair backed by a URL→body map.
     *
     * The $urlMap keys are exact URLs. A missing URL returns a 404 response.
     * A null value also returns a 404.
     *
     * @param array<string, array<string, mixed>|null> $urlMap
     *
     * @return array{ClientInterface, RequestFactoryInterface}
     */
    private function makeHttpStack(array $urlMap): array
    {
        // We capture the last URL seen by createRequest() so sendRequest() can look it up.
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

    // -------------------------------------------------------------------------
    // Fragment-only resolution from rootResource.contained
    // -------------------------------------------------------------------------

    public function testResolveFragmentFromContained(): void
    {
        $patient = [
            'resourceType' => 'Patient',
            'id'           => 'p1',
            'contained'    => [
                ['resourceType' => 'Organization', 'id' => 'org1', 'name' => 'Acme'],
            ],
            'managingOrganization' => ['reference' => '#org1'],
        ];

        $result = $this->evaluate('managingOrganization.resolve()', $patient);

        self::assertCount(1, $result->toArray());
        self::assertSame('Organization', $result->first()['resourceType']);
        self::assertSame('org1', $result->first()['id']);
    }

    public function testResolveFragmentFromContainedPlainStringReference(): void
    {
        // When the item in the collection is already a plain string like '#org1'
        $patient = [
            'resourceType' => 'Patient',
            'contained'    => [
                ['resourceType' => 'Medication', 'id' => 'med1'],
            ],
            'contained_ref' => '#med1',
        ];

        $result = $this->evaluate('contained_ref.resolve()', $patient);

        self::assertCount(1, $result->toArray());
        self::assertSame('Medication', $result->first()['resourceType']);
    }

    public function testResolveFragmentNotFoundReturnsEmpty(): void
    {
        $patient = [
            'resourceType' => 'Patient',
            'contained'    => [
                ['resourceType' => 'Organization', 'id' => 'org1'],
            ],
            'managingOrganization' => ['reference' => '#org99'],
        ];

        $result = $this->evaluate('managingOrganization.resolve()', $patient);

        self::assertTrue($result->isEmpty());
    }

    public function testResolveFragmentWithNoContainedReturnsEmpty(): void
    {
        $patient = [
            'resourceType'         => 'Patient',
            'managingOrganization' => ['reference' => '#org1'],
        ];

        $result = $this->evaluate('managingOrganization.resolve()', $patient);

        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Relative URL resolution via the configured FHIRHttpClientInterface
    // -------------------------------------------------------------------------

    public function testResolveRelativeUrl(): void
    {
        $fetched = ['resourceType' => 'Patient', 'id' => 'abc'];
        $this->evaluator->setFhirHttpClient($this->makeFhirHttpClient(['Patient/abc' => $fetched]));

        $resource = ['reference' => ['reference' => 'Patient/abc']];
        $result   = $this->evaluate('reference.resolve()', $resource);

        self::assertCount(1, $result->toArray());
        self::assertSame('Patient', $result->first()['resourceType']);
    }

    public function testResolveRelativeUrlWithoutFhirHttpClientReturnsEmpty(): void
    {
        // No fhirHttpClient configured — relative references cannot be resolved
        $resource = ['reference' => ['reference' => 'Patient/abc']];
        $result   = $this->evaluate('reference.resolve()', $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testRelativeUrlMustStartWithUppercaseLetter(): void
    {
        // Lowercase-prefixed paths must NOT be treated as FHIR resource references
        $this->evaluator->setFhirHttpClient($this->makeFhirHttpClient([]));

        $resource = ['ref' => ['reference' => 'urn:oid:1.2.3']];
        $result   = $this->evaluate('ref.resolve()', $resource);

        // 'urn:oid:...' does not start with [A-Z][A-Za-z]+/ so it is not a relative FHIR reference
        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Absolute URL resolution via PSR-18 client
    // -------------------------------------------------------------------------

    public function testResolveAbsoluteUrl(): void
    {
        $fetched            = ['resourceType' => 'Organization', 'id' => 'org42'];
        [$client, $factory] = $this->makeHttpStack(['https://fhir.example.com/Organization/org42' => $fetched]);

        $this->evaluator->setHttpClient($client, $factory);

        $resource = ['org' => ['reference' => 'https://fhir.example.com/Organization/org42']];
        $result   = $this->evaluate('org.resolve()', $resource);

        self::assertCount(1, $result->toArray());
        self::assertSame('Organization', $result->first()['resourceType']);
    }

    public function testResolveAbsoluteUrlWithoutHttpClientReturnsEmpty(): void
    {
        $resource = ['ref' => ['reference' => 'https://fhir.example.com/Patient/1']];
        $result   = $this->evaluate('ref.resolve()', $resource);

        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Canonical URL resolution (url|version → search query)
    // -------------------------------------------------------------------------

    public function testResolveCanonicalUrl(): void
    {
        $bundle = [
            'resourceType' => 'Bundle',
            'type'         => 'searchset',
            'entry'        => [
                ['resource' => ['resourceType' => 'ValueSet', 'id' => 'vs1', 'url' => 'http://example.com/vs']],
            ],
        ];

        $searchPath = 'ValueSet?' . http_build_query(['url' => 'http://example.com/vs', 'version' => '1.0.0']);
        $this->evaluator->setFhirHttpClient($this->makeFhirHttpClient([$searchPath => $bundle]));

        // Canonical URL with pipe-version separator triggers canonical search
        $resource = ['ref' => ['reference' => 'http://example.com/vs|1.0.0', 'type' => 'ValueSet']];
        $result   = $this->evaluate('ref.resolve()', $resource);

        self::assertCount(1, $result->toArray());
        self::assertSame('ValueSet', $result->first()['resourceType']);
    }

    // -------------------------------------------------------------------------
    // Input types: Reference object vs plain string
    // -------------------------------------------------------------------------

    public function testResolveFromReferenceArrayWithReferenceKey(): void
    {
        $fetched = ['resourceType' => 'Practitioner', 'id' => 'p1'];
        $this->evaluator->setFhirHttpClient($this->makeFhirHttpClient(['Practitioner/p1' => $fetched]));

        $result = $this->evaluate('$this.resolve()', ['reference' => 'Practitioner/p1']);

        self::assertCount(1, $result->toArray());
        self::assertSame('Practitioner', $result->first()['resourceType']);
    }

    public function testResolveFromPlainStringInCollection(): void
    {
        $fetched = ['resourceType' => 'Patient', 'id' => '99'];
        $this->evaluator->setFhirHttpClient($this->makeFhirHttpClient(['Patient/99' => $fetched]));

        $resource = ['refString' => 'Patient/99'];
        $result   = $this->evaluate('refString.resolve()', $resource);

        self::assertCount(1, $result->toArray());
        self::assertSame('Patient', $result->first()['resourceType']);
    }

    // -------------------------------------------------------------------------
    // Results without resourceType are silently omitted
    // -------------------------------------------------------------------------

    public function testResolveSilentlyOmitsResultsWithoutResourceType(): void
    {
        // HTTP client returns a payload without 'resourceType' → silently omitted
        $this->evaluator->setFhirHttpClient($this->makeFhirHttpClient([
            'Patient/bad' => ['id' => 'bad', 'data' => []],
        ]));

        $resource = ['ref' => ['reference' => 'Patient/bad']];
        $result   = $this->evaluate('ref.resolve()', $resource);

        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Edge cases
    // -------------------------------------------------------------------------

    public function testResolveOnEmptyInputReturnsEmpty(): void
    {
        $result = $this->evaluate('{}.resolve()', null);

        self::assertTrue($result->isEmpty());
    }

    public function testResolveNonReferenceItemsAreOmitted(): void
    {
        // Integers, booleans, etc. are not resolvable references
        $resource = ['count' => 42];
        $result   = $this->evaluate('count.resolve()', $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testResolveMultipleContainedReferences(): void
    {
        $patient = [
            'resourceType' => 'Patient',
            'contained'    => [
                ['resourceType' => 'Organization', 'id' => 'org1', 'name' => 'Org One'],
                ['resourceType' => 'Practitioner', 'id' => 'prac1'],
            ],
            'generalPractitioner' => [
                ['reference' => '#prac1'],
            ],
            'managingOrganization' => ['reference' => '#org1'],
        ];

        $result = $this->evaluate('generalPractitioner.resolve()', $patient);

        self::assertCount(1, $result->toArray());
        self::assertSame('Practitioner', $result->first()['resourceType']);
    }
}
