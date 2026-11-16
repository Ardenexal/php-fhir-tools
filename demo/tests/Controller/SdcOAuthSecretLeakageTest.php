<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * M06's single most important guardrail: a token-fetch failure must never put the configured OAuth
 * client secret anywhere the browser can see it. This exercises the REAL wiring end-to-end (real
 * ExternalClientFactory -> OAuthHttpClient -> OAuthClientCredentialsTokenProvider, via env vars set
 * before the container boots) — not a swapped-in test double — through the actual `/sdc/populate`
 * action, with a token endpoint that will fail (unreachable host).
 *
 * The existing library architecture already gives this defense in depth: FHIRHttpClient::request()
 * catches HttpClientExceptionInterface (which the OAuth decorator's failures are converted to) and
 * returns null, so the failure surfaces only as a fixed, generic "fetch failed" populate issue —
 * never a thrown exception reaching SdcController's error panel at all. This test proves that chain
 * holds, not just that OAuthTokenException's own message is secret-free in isolation.
 */
final class SdcOAuthSecretLeakageTest extends WebTestCase
{
    private const string CLIENT_SECRET = 'super-secret-value-must-never-leak';

    protected function setUp(): void
    {
        putenv('FHIR_SERVER_URL=https://fhir.invalid.example/r4');
        putenv('FHIR_SERVER_OAUTH_TOKEN_URL=https://idp.invalid.example/token');
        putenv('FHIR_SERVER_OAUTH_CLIENT_ID=demo-client');
        putenv('FHIR_SERVER_OAUTH_CLIENT_SECRET=' . self::CLIENT_SECRET);
        $_ENV['FHIR_SERVER_URL']                    = 'https://fhir.invalid.example/r4';
        $_ENV['FHIR_SERVER_OAUTH_TOKEN_URL']        = 'https://idp.invalid.example/token';
        $_ENV['FHIR_SERVER_OAUTH_CLIENT_ID']        = 'demo-client';
        $_ENV['FHIR_SERVER_OAUTH_CLIENT_SECRET']    = self::CLIENT_SECRET;
        $_SERVER['FHIR_SERVER_URL']                 = 'https://fhir.invalid.example/r4';
        $_SERVER['FHIR_SERVER_OAUTH_TOKEN_URL']     = 'https://idp.invalid.example/token';
        $_SERVER['FHIR_SERVER_OAUTH_CLIENT_ID']     = 'demo-client';
        $_SERVER['FHIR_SERVER_OAUTH_CLIENT_SECRET'] = self::CLIENT_SECRET;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        foreach (['FHIR_SERVER_URL', 'FHIR_SERVER_OAUTH_TOKEN_URL', 'FHIR_SERVER_OAUTH_CLIENT_ID', 'FHIR_SERVER_OAUTH_CLIENT_SECRET'] as $name) {
            putenv($name);
            unset($_ENV[$name], $_SERVER[$name]);
        }
    }

    public function testTokenFetchFailureNeverLeaksTheClientSecretThroughPopulate(): void
    {
        $client = static::createClient();

        $questionnaireJson = json_encode([
            'resourceType' => 'Questionnaire',
            'status'       => 'active',
            'item'         => [[
                'linkId'    => 'grp',
                'type'      => 'group',
                'extension' => [[
                    'url'             => 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-itemPopulationContext',
                    'valueExpression' => [
                        'name'       => 'match',
                        'language'   => 'application/x-fhir-query',
                        'expression' => 'Patient?_count=1',
                    ],
                ]],
                'item' => [[
                    'linkId'    => 'child',
                    'type'      => 'string',
                    'extension' => [[
                        'url'             => 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-initialExpression',
                        'valueExpression' => ['language' => 'text/fhirpath', 'expression' => '%match.id'],
                    ]],
                ]],
            ]],
        ], JSON_THROW_ON_ERROR);

        $client->request('POST', '/sdc/populate', [
            'questionnaireJson' => $questionnaireJson,
            'launchContextJson' => '',
        ]);

        self::assertResponseIsSuccessful();

        $html = (string) $client->getResponse()->getContent();
        self::assertStringNotContainsString(self::CLIENT_SECRET, $html, 'The OAuth client secret must never appear anywhere in the rendered page.');

        // Confirms the OAuth path was actually exercised and actually failed (not vacuously skipped) —
        // the token endpoint is unreachable, so the x-fhir-query fetch fails gracefully with the
        // library's existing fixed "fetch failed" issue text, never a thrown exception.
        self::assertSelectorNotExists('[data-error-panel]');
        self::assertStringContainsString('fetch failed', $html);
    }
}
