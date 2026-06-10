<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\HttpFHIRTerminologyClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class HttpFHIRTerminologyClientTest extends TestCase
{
    private const string SERVER_URL = 'https://tx.fhir.org/r4';

    private const string VS_URL     = 'http://hl7.org/fhir/ValueSet/observation-status';

    // -------------------------------------------------------------------------
    // URL construction
    // -------------------------------------------------------------------------

    public function testBuildsCorrectValidateCodeUrl(): void
    {
        $capturedUrl = null;
        $mockClient  = new MockHttpClient(function(string $_method, string $url) use (&$capturedUrl): MockResponse {
            $capturedUrl = $url;

            return new MockResponse(json_encode($this->parametersResponse(true)) ?: '{}');
        });

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);
        $client->validateCode(self::VS_URL, 'final');

        self::assertStringContainsString('/ValueSet/$validate-code?', (string) $capturedUrl);
        self::assertStringContainsString('url=' . urlencode(self::VS_URL), (string) $capturedUrl);
        self::assertStringContainsString('code=final', (string) $capturedUrl);
    }

    public function testStripsTrailingSlashFromServerUrl(): void
    {
        $capturedUrl = null;
        $mockClient  = new MockHttpClient(function(string $_method, string $url) use (&$capturedUrl): MockResponse {
            $capturedUrl = $url;

            return new MockResponse(json_encode($this->parametersResponse(true)) ?: '{}');
        });

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL . '/');
        $client->validateCode(self::VS_URL, 'final');

        self::assertStringNotContainsString('//', str_replace('https://', '', (string) $capturedUrl));
    }

    // -------------------------------------------------------------------------
    // Response parsing — true/false result
    // -------------------------------------------------------------------------

    public function testReturnsTrueWhenServerRespondsResultTrue(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(
            json_encode($this->parametersResponse(true)) ?: '{}',
        ));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertTrue($client->validateCode(self::VS_URL, 'final'));
    }

    public function testReturnsFalseWhenServerRespondsResultFalse(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(
            json_encode($this->parametersResponse(false)) ?: '{}',
        ));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCode(self::VS_URL, 'unknown-code'));
    }

    // -------------------------------------------------------------------------
    // HTTP error handling
    // -------------------------------------------------------------------------

    public function testReturnsFalseOnNon2xxResponse(): void
    {
        $mockClient = new MockHttpClient(new MockResponse('Internal Server Error', ['http_code' => 500]));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCode(self::VS_URL, 'final'));
    }

    public function testReturnsFalseOnMalformedJsonBody(): void
    {
        $mockClient = new MockHttpClient(new MockResponse('not-json'));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCode(self::VS_URL, 'final'));
    }

    public function testReturnsFalseWhenParametersKeyMissing(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(json_encode(['resourceType' => 'Parameters']) ?: '{}'));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCode(self::VS_URL, 'final'));
    }

    public function testReturnsFalseWhenResultParameterAbsent(): void
    {
        $body       = json_encode(['parameter' => [['name' => 'display', 'valueString' => 'Final']]]);
        $mockClient = new MockHttpClient(new MockResponse($body ?: '{}'));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCode(self::VS_URL, 'final'));
    }

    // -------------------------------------------------------------------------
    // Value type conversion
    // -------------------------------------------------------------------------

    public function testAcceptsIntegerValue(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(
            json_encode($this->parametersResponse(true)) ?: '{}',
        ));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertTrue($client->validateCode(self::VS_URL, 42));
    }

    public function testAcceptsBackedEnumValue(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(
            json_encode($this->parametersResponse(true)) ?: '{}',
        ));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertTrue($client->validateCode(self::VS_URL, HttpFHIRTerminologyClientTestEnum::Final));
    }

    public function testReturnsFalseForNullValue(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(
            json_encode($this->parametersResponse(true)) ?: '{}',
        ));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCode(self::VS_URL, null));
    }

    public function testReturnsFalseForEmptyStringValue(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(
            json_encode($this->parametersResponse(true)) ?: '{}',
        ));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCode(self::VS_URL, ''));
    }

    // -------------------------------------------------------------------------
    // validateCoding
    // -------------------------------------------------------------------------

    public function testValidateCodingBuildsCorrectUrl(): void
    {
        $capturedUrl = null;
        $mockClient  = new MockHttpClient(function(string $_method, string $url) use (&$capturedUrl): MockResponse {
            $capturedUrl = $url;

            return new MockResponse(json_encode($this->parametersResponse(true)) ?: '{}');
        });

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);
        $client->validateCoding(self::VS_URL, 'http://loinc.org', '8867-4');

        self::assertStringContainsString('/ValueSet/$validate-code?', (string) $capturedUrl);
        self::assertStringContainsString('url=' . urlencode(self::VS_URL), (string) $capturedUrl);
        self::assertStringContainsString('system=' . urlencode('http://loinc.org'), (string) $capturedUrl);
        self::assertStringContainsString('code=8867-4', (string) $capturedUrl);
    }

    public function testValidateCodingReturnsTrueWhenServerRespondsResultTrue(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(
            json_encode($this->parametersResponse(true)) ?: '{}',
        ));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertTrue($client->validateCoding(self::VS_URL, 'http://loinc.org', '8867-4'));
    }

    public function testValidateCodingReturnsFalseWhenServerRespondsResultFalse(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(
            json_encode($this->parametersResponse(false)) ?: '{}',
        ));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCoding(self::VS_URL, 'http://loinc.org', 'bad-code'));
    }

    public function testValidateCodingReturnsFalseOnNon2xxResponse(): void
    {
        $mockClient = new MockHttpClient(new MockResponse('Internal Server Error', ['http_code' => 500]));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCoding(self::VS_URL, 'http://loinc.org', '8867-4'));
    }

    public function testValidateCodingReturnsFalseOnMalformedJsonBody(): void
    {
        $mockClient = new MockHttpClient(new MockResponse('not-json'));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCoding(self::VS_URL, 'http://loinc.org', '8867-4'));
    }

    public function testValidateCodingReturnsFalseWhenParametersKeyMissing(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(json_encode(['resourceType' => 'Parameters']) ?: '{}'));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCoding(self::VS_URL, 'http://loinc.org', '8867-4'));
    }

    public function testValidateCodingReturnsFalseWhenResultParameterAbsent(): void
    {
        $body       = json_encode(['parameter' => [['name' => 'display', 'valueString' => 'Heart rate']]]);
        $mockClient = new MockHttpClient(new MockResponse($body ?: '{}'));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);

        self::assertFalse($client->validateCoding(self::VS_URL, 'http://loinc.org', '8867-4'));
    }

    // -------------------------------------------------------------------------
    // validateCodingWithDisplay
    // -------------------------------------------------------------------------

    public function testValidateCodingWithDisplayIncludesDisplayQueryParam(): void
    {
        $capturedUrl = null;
        $mockClient  = new MockHttpClient(function(string $_method, string $url) use (&$capturedUrl): MockResponse {
            $capturedUrl = $url;

            return new MockResponse(json_encode($this->parametersResponse(true)) ?: '{}');
        });

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);
        $client->validateCodingWithDisplay(self::VS_URL, 'http://loinc.org', '8867-4', 'Heart rate');

        self::assertStringContainsString('display=' . urlencode('Heart rate'), (string) $capturedUrl);
        self::assertStringContainsString('system=' . urlencode('http://loinc.org'), (string) $capturedUrl);
        self::assertStringContainsString('code=8867-4', (string) $capturedUrl);
    }

    public function testValidateCodingWithDisplayReturnsValidTrueAndNullCorrectDisplayWhenNoDisplayParam(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(
            json_encode($this->parametersResponse(true)) ?: '{}',
        ));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);
        $result = $client->validateCodingWithDisplay(self::VS_URL, 'http://loinc.org', '8867-4', 'Heart rate');

        self::assertTrue($result->valid);
        self::assertNull($result->correctDisplay);
    }

    public function testValidateCodingWithDisplayReturnsCorrectDisplayWhenResponseIncludesDisplayParam(): void
    {
        $body = json_encode([
            'resourceType' => 'Parameters',
            'parameter'    => [
                ['name' => 'result', 'valueBoolean' => true],
                ['name' => 'display', 'valueString' => 'Heart rate'],
            ],
        ]);
        $mockClient = new MockHttpClient(new MockResponse($body ?: '{}'));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);
        $result = $client->validateCodingWithDisplay(self::VS_URL, 'http://loinc.org', '8867-4', 'heart rate');

        self::assertTrue($result->valid);
        self::assertSame('Heart rate', $result->correctDisplay);
    }

    public function testValidateCodingWithDisplayReturnsFalseValidOnNon2xxResponse(): void
    {
        $mockClient = new MockHttpClient(new MockResponse('Error', ['http_code' => 500]));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);
        $result = $client->validateCodingWithDisplay(self::VS_URL, 'http://loinc.org', '8867-4', 'Heart rate');

        self::assertFalse($result->valid);
        self::assertNull($result->correctDisplay);
    }

    public function testValidateCodingWithDisplayReturnsFalseValidOnMalformedJson(): void
    {
        $mockClient = new MockHttpClient(new MockResponse('not-json'));

        $client = new HttpFHIRTerminologyClient($mockClient, self::SERVER_URL);
        $result = $client->validateCodingWithDisplay(self::VS_URL, 'http://loinc.org', '8867-4', 'Heart rate');

        self::assertFalse($result->valid);
        self::assertNull($result->correctDisplay);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /** @return array<string, mixed> */
    private function parametersResponse(bool $result): array
    {
        return [
            'resourceType' => 'Parameters',
            'parameter'    => [
                ['name' => 'result', 'valueBoolean' => $result],
            ],
        ];
    }
}

enum HttpFHIRTerminologyClientTestEnum: string
{
    case Final = 'final';
}
