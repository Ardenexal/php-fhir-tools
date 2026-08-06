<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * M07's extension of M06's secret-leakage guardrail (see `SdcOAuthSecretLeakageTest`) to the
 * session-scoped credential path: a visitor's own submitted OAuth secret must never appear anywhere the
 * browser can see it, even when the token endpoint it's used against is unreachable and the failure
 * propagates through `/sdc/populate`.
 */
final class SdcSessionCredentialLeakageTest extends WebTestCase
{
    private const string SESSION_SECRET = 'visitor-supplied-secret-must-never-leak';

    protected function setUp(): void
    {
        putenv('FHIR_SERVER_URL=https://fhir.invalid.example/r4');
        putenv('FHIR_SERVER_OAUTH_TOKEN_URL=https://idp.invalid.example/token');
        $_ENV['FHIR_SERVER_URL']                 = 'https://fhir.invalid.example/r4';
        $_ENV['FHIR_SERVER_OAUTH_TOKEN_URL']     = 'https://idp.invalid.example/token';
        $_SERVER['FHIR_SERVER_URL']              = 'https://fhir.invalid.example/r4';
        $_SERVER['FHIR_SERVER_OAUTH_TOKEN_URL']  = 'https://idp.invalid.example/token';
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        foreach (['FHIR_SERVER_URL', 'FHIR_SERVER_OAUTH_TOKEN_URL'] as $name) {
            putenv($name);
            unset($_ENV[$name], $_SERVER[$name]);
        }
    }

    public function testSessionSubmittedSecretNeverLeaksThroughPopulateFailure(): void
    {
        $client = static::createClient();

        $client->request('POST', '/sdc/credentials', [
            'oauthClientId'     => 'visitor-client',
            'oauthClientSecret' => self::SESSION_SECRET,
        ]);
        self::assertResponseIsSuccessful();

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
        self::assertStringNotContainsString(self::SESSION_SECRET, $html, 'The session-submitted OAuth secret must never appear anywhere in the rendered page.');
        self::assertSelectorNotExists('[data-error-panel]');
        self::assertStringContainsString('fetch failed', $html);
    }
}
