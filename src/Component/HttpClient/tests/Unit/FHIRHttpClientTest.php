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

    /**
     * Pagination (M06): a `Bundle.link[relation=next]` URL is server-supplied and absolute, unlike a
     * resolver-produced search string — it can carry a foreign host. followLink() must reduce it to a
     * relative path and re-dispatch through the same fixed-authority request() join, never fetching the
     * absolute URL directly.
     */
    public function testFollowLinkFetchesSameOriginUrl(): void
    {
        $response = new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]);
        $client   = new MockHttpClient($response);
        $fhir     = new FHIRHttpClient($client, 'https://example.org/fhir/');

        $bundle = $fhir->followLink('https://example.org/fhir/Observation?page=2', 'R5');

        self::assertInstanceOf(BundleResource::class, $bundle);
        self::assertSame('https://example.org/fhir/Observation?page=2', $response->getRequestUrl());
    }

    public function testFollowLinkTreatsExplicitDefaultPortAsSameOrigin(): void
    {
        $response = new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]);
        $client   = new MockHttpClient($response);
        $fhir     = new FHIRHttpClient($client, 'https://example.org/fhir/');

        $bundle = $fhir->followLink('https://example.org:443/fhir/Observation?page=2', 'R5');

        self::assertInstanceOf(BundleResource::class, $bundle);
    }

    public function testFollowLinkRejectsCrossOriginHost(): void
    {
        $response = new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]);
        $client   = new MockHttpClient($response);
        $fhir     = new FHIRHttpClient($client, 'https://example.org/fhir/');

        self::assertNull($fhir->followLink('https://evil.example/fhir/Observation?page=2', 'R5'));
        self::assertSame(0, $client->getRequestsCount(), 'A cross-origin link must never be dispatched at all.');
    }

    public function testFollowLinkRejectsCrossOriginScheme(): void
    {
        $client = new MockHttpClient(new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]));
        $fhir   = new FHIRHttpClient($client, 'https://example.org/fhir/');

        self::assertNull($fhir->followLink('http://example.org/fhir/Observation?page=2', 'R5'));
    }

    public function testFollowLinkRejectsCrossOriginPort(): void
    {
        $client = new MockHttpClient(new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]));
        $fhir   = new FHIRHttpClient($client, 'https://example.org/fhir/');

        self::assertNull($fhir->followLink('https://example.org:8443/fhir/Observation?page=2', 'R5'));
    }

    public function testFollowLinkRejectsSameOriginPathOutsideBasePrefix(): void
    {
        // Same host+scheme+port, but a path this client's fixed base path cannot re-express without
        // either duplicating or losing the "/fhir" prefix — rejected rather than risk fetching the wrong URL.
        $client = new MockHttpClient(new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]));
        $fhir   = new FHIRHttpClient($client, 'https://example.org/fhir/');

        self::assertNull($fhir->followLink('https://example.org/other/Observation?page=2', 'R5'));
    }

    public function testFollowLinkRejectsSameOriginSiblingPathSharingBasePathPrefix(): void
    {
        // A base path of "/fhir" must not treat "/fhir2/..." as under its prefix — only a whole-segment
        // match ("/fhir" itself or "/fhir/...") counts, never a same-origin sibling path with the string
        // "/fhir" as a mere prefix.
        $client = new MockHttpClient(new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]));
        $fhir   = new FHIRHttpClient($client, 'https://example.org/fhir/');

        self::assertNull($fhir->followLink('https://example.org/fhir2/Observation?page=2', 'R5'));
        self::assertSame(0, $client->getRequestsCount(), 'A sibling path sharing only a string prefix must never be dispatched.');
    }

    public function testFollowLinkFetchesUrlWhosePathExactlyMatchesBasePath(): void
    {
        // A link path equal to the base path itself (no trailing segment) is the whole-segment match's
        // boundary case — it must still resolve, just to an empty relative path.
        $response = new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]);
        $client   = new MockHttpClient($response);
        $fhir     = new FHIRHttpClient($client, 'https://example.org/fhir/');

        $bundle = $fhir->followLink('https://example.org/fhir?_getpages=abc', 'R5');

        self::assertInstanceOf(BundleResource::class, $bundle);
        self::assertSame('https://example.org/fhir/?_getpages=abc', $response->getRequestUrl());
    }

    public function testFollowLinkRejectsMalformedUrl(): void
    {
        $client = new MockHttpClient(new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]));
        $fhir   = new FHIRHttpClient($client, 'https://example.org/fhir/');

        self::assertNull($fhir->followLink('not a url at all ://', 'R5'));
    }

    public function testFollowLinkWithRootBaseUrlFetchesAnySameOriginPath(): void
    {
        $response = new MockResponse(self::BUNDLE_JSON, ['http_code' => 200]);
        $client   = new MockHttpClient($response);
        $fhir     = new FHIRHttpClient($client, 'https://example.org');

        $bundle = $fhir->followLink('https://example.org/Observation?page=2', 'R5');

        self::assertInstanceOf(BundleResource::class, $bundle);
        self::assertSame('https://example.org/Observation?page=2', $response->getRequestUrl());
    }
}
