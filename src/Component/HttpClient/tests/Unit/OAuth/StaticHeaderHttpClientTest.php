<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\Tests\Unit\OAuth;

use Ardenexal\FHIRTools\Component\HttpClient\OAuth\StaticHeaderHttpClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class StaticHeaderHttpClientTest extends TestCase
{
    public function testAttachesHeaderAlongsideExistingAssociativeHeaders(): void
    {
        $captured = null;
        $inner    = new MockHttpClient(function(string $_method, string $_url, array $options) use (&$captured): MockResponse {
            $captured = $options['headers'] ?? null;

            return new MockResponse('{}');
        });

        $client = new StaticHeaderHttpClient($inner, 'X-Api-Key', 'my-key');
        $client->request('GET', 'https://fhir.example.org/Patient', ['headers' => ['Accept' => 'application/fhir+json']]);

        // MockHttpClient (mirroring the real transport) normalizes headers to "Name: value" list form
        // before the callback sees them, regardless of the associative/list shape passed in.
        self::assertContains('Accept: application/fhir+json', $captured);
        self::assertContains('X-Api-Key: my-key', $captured);
    }

    public function testAttachesHeaderWhenNoHeadersOptionGiven(): void
    {
        $captured = null;
        $inner    = new MockHttpClient(function(string $_method, string $_url, array $options) use (&$captured): MockResponse {
            $captured = $options['headers'] ?? null;

            return new MockResponse('{}');
        });

        $client = new StaticHeaderHttpClient($inner, 'Authorization', 'Bearer hand-obtained-token');
        $client->request('GET', 'https://fhir.example.org/Patient');

        self::assertContains('Authorization: Bearer hand-obtained-token', $captured);
    }

    public function testAttachesHeaderToListStyleHeadersArray(): void
    {
        $captured = null;
        $inner    = new MockHttpClient(function(string $_method, string $_url, array $options) use (&$captured): MockResponse {
            $captured = $options['headers'] ?? null;

            return new MockResponse('{}');
        });

        $client = new StaticHeaderHttpClient($inner, 'X-Api-Key', 'my-key');
        $client->request('GET', 'https://fhir.example.org/Patient', ['headers' => ['Accept: application/fhir+json']]);

        self::assertContains('Accept: application/fhir+json', $captured);
        self::assertContains('X-Api-Key: my-key', $captured);
    }
}
