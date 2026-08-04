<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\Tests\Unit;

use Ardenexal\FHIRTools\Component\HttpClient\FHIRHttpClient;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\TimeoutException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class FHIRHttpClientTest extends TestCase
{
    private const BUNDLE_JSON = '{"resourceType":"Bundle","type":"searchset","total":0}';

    public function testSearchDeserializesResultBundleAsTypedModel(): void
    {
        $client = new MockHttpClient(new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]));
        $fhir   = new FHIRHttpClient($client, 'https://example.org/fhir');

        $bundle = $fhir->search('Observation?subject=Patient/1', 'R5');

        self::assertInstanceOf(BundleResource::class, $bundle);
    }

    public function testSearchJoinsBaseUrlAndSearchString(): void
    {
        $response = new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]);
        $client   = new MockHttpClient($response);
        $fhir     = new FHIRHttpClient($client, 'https://example.org/fhir/');

        $fhir->search('Observation?subject=Patient/1', 'R5');

        self::assertSame('GET', $response->getRequestMethod());
        self::assertSame('https://example.org/fhir/Observation?subject=Patient/1', $response->getRequestUrl());
    }

    public function testSearchReturnsNullOnHttpErrorStatus(): void
    {
        $client = new MockHttpClient(new MockResponse('{"resourceType":"OperationOutcome"}', ['http_code' => 404]));
        $fhir   = new FHIRHttpClient($client, 'https://example.org/fhir');

        self::assertNull($fhir->search('Observation?subject=Patient/1', 'R5'));
    }

    public function testSearchReturnsNullOnTransportError(): void
    {
        $client = new MockHttpClient(static fn (): never => throw new TransportException('connection refused'));
        $fhir   = new FHIRHttpClient($client, 'https://example.org/fhir');

        self::assertNull($fhir->search('Observation?subject=Patient/1', 'R5'));
    }

    public function testSearchReturnsNullOnMalformedBody(): void
    {
        $client = new MockHttpClient(new MockResponse('not json at all {{{', ['http_code' => 200]));
        $fhir   = new FHIRHttpClient($client, 'https://example.org/fhir');

        self::assertNull($fhir->search('Observation?subject=Patient/1', 'R5'));
    }

    public function testSearchWithUnknownVersionThrows(): void
    {
        $this->expectException(\ValueError::class);

        $client = new MockHttpClient(new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]));
        (new FHIRHttpClient($client, 'https://example.org/fhir'))->search('Observation', 'R9');
    }

    public function testRequestReturnsBodyOnSuccess(): void
    {
        $response = new MockResponse('RAW-BODY', ['http_code' => 200]);
        $client   = new MockHttpClient($response);
        $fhir     = new FHIRHttpClient($client, 'https://example.org/fhir');

        $body = $fhir->request('GET', 'metadata');

        self::assertSame('RAW-BODY', $body);
        self::assertSame('https://example.org/fhir/metadata', $response->getRequestUrl());
    }

    public function testRequestReturnsNullOnServerError(): void
    {
        $client = new MockHttpClient(new MockResponse('boom', ['http_code' => 500]));
        $fhir   = new FHIRHttpClient($client, 'https://example.org/fhir');

        self::assertNull($fhir->request('GET', 'metadata'));
    }

    public function testRequestPostsBodyWhenProvided(): void
    {
        $response = new MockResponse('{"resourceType":"Parameters"}', ['http_code' => 200]);
        $client   = new MockHttpClient($response);
        $fhir     = new FHIRHttpClient($client, 'https://example.org/fhir');

        $body = $fhir->request('POST', 'ValueSet/$validate-code', '{"resourceType":"Parameters"}', ['Content-Type' => 'application/fhir+json']);

        self::assertSame('{"resourceType":"Parameters"}', $body);
        self::assertSame('POST', $response->getRequestMethod());
    }

    /**
     * SSRF guardrail (M04): the resolved x-fhir-query search string is untrusted (it can carry percent-encoded
     * content ultimately derived from launch-context data). Confirm that even a search string shaped like an
     * absolute or protocol-relative URL cannot redirect the request to a different host — `baseUrl` is always
     * the authority, and `$search` can only ever land in the path/query of that same authority.
     */
    public function testSchemeLikeSearchStringCannotRedirectToAnotherHost(): void
    {
        $response = new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]);
        $client   = new MockHttpClient($response);
        $fhir     = new FHIRHttpClient($client, 'https://example.org/fhir/');

        $fhir->search('http://evil.example/Patient?x=1', 'R5');

        self::assertStringStartsWith('https://example.org/fhir/', $response->getRequestUrl());
        self::assertStringNotContainsString('://evil.example', parse_url($response->getRequestUrl(), \PHP_URL_HOST) ?? '');
    }

    public function testProtocolRelativeSearchStringCannotRedirectToAnotherHost(): void
    {
        $response = new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]);
        $client   = new MockHttpClient($response);
        $fhir     = new FHIRHttpClient($client, 'https://example.org/fhir/');

        $fhir->search('//evil.example/Patient?x=1', 'R5');

        self::assertSame('example.org', parse_url($response->getRequestUrl(), \PHP_URL_HOST));
    }

    public function testRequestReturnsNullOnTimeout(): void
    {
        $client = new MockHttpClient(static fn (): never => throw new TimeoutException('timed out'));
        $fhir   = new FHIRHttpClient($client, 'https://example.org/fhir');

        self::assertNull($fhir->request('GET', 'metadata'), 'A timeout must degrade to null, never an uncaught exception.');
    }
}
