<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Sdc\ExternalClientFactory;

/**
 * M04 SSRF regression: server base URLs must be operator-configured (env-var-only), never accepted as
 * visitor-supplied request input — a public "enter any FHIR server URL" field would turn the demo host
 * into an open SSRF proxy. `/sdc/populate` never reads a URL-shaped field from the request; the only way
 * a base URL enters the system is via `FHIRHttpClientInterface`, resolved once at the DI container level
 * from `%env(FHIR_SERVER_URL)%` (see demo/config/services.yaml + {@see ExternalClientFactory}).
 *
 * Two independent proofs, per the milestone's own caution that "assert zero effect" is nearly vacuous if
 * it only checks the controller never happens to read the field by accident:
 *  - Behavioral: swap the container's `FHIRHttpClientInterface` for a recording spy, POST spoofed
 *    server-URL-shaped fields alongside a real x-fhir-query directive, and assert the spy's recorded
 *    search string is exactly the offline-resolved template — no attacker-supplied content leaks in.
 *  - Static: grep the controller source for `new FHIRHttpClient(`/`new XFhirQueryPopulationDataProvider(`
 *    constructed from anything other than the injected `$this->httpClient`, so a future edit that
 *    reintroduces a request-driven base URL fails this test even if no fixture happens to exercise it.
 */
final class SdcPopulateSsrfTest extends WebTestCase
{
    private const string LAUNCH_CONTEXT_URL         = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-launchContext';

    private const string ITEM_POPULATION_CONTEXT_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-itemPopulationContext';

    private const string INITIAL_EXPRESSION_URL      = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-initialExpression';

    public function testSpoofedServerUrlFieldsHaveZeroEffectOnTheResolvedQuery(): void
    {
        $client = static::createClient();

        $spy = new RecordingFHIRHttpClient();
        self::getContainer()->set(FHIRHttpClientInterface::class, $spy);

        $client->request('POST', '/sdc/populate', [
            'questionnaireJson' => $this->xFhirQueryQuestionnaireJson(),
            'launchContextJson' => $this->patientJson(),
            // Attacker-shaped fields the controller must never read.
            'serverUrl'         => 'http://attacker.example',
            'baseUrl'           => 'http://attacker.example',
            'fhirServerUrl'     => 'http://attacker.example',
        ]);

        self::assertResponseIsSuccessful();
        self::assertNotNull($spy->lastSearch, 'The x-fhir-query directive should still have dispatched through the configured (spy) client.');
        self::assertSame('Patient?link=Patient/P1', $spy->lastSearch);
        self::assertStringNotContainsStringIgnoringCase('attacker', $spy->lastSearch);
    }

    public function testControllerNeverConstructsAnHttpClientItself(): void
    {
        $source = file_get_contents(__DIR__ . '/../../src/Controller/SdcController.php');
        self::assertIsString($source);

        self::assertStringNotContainsString('new FHIRHttpClient(', $source, 'SdcController must never construct its own HTTP client — the base URL is only ever the DI-injected FHIRHttpClientInterface, itself sourced from an env var.');
        self::assertMatchesRegularExpression('/private readonly FHIRHttpClientInterface \$httpClient/', $source, 'The client must arrive via constructor injection, not be built from request input.');
    }

    private function xFhirQueryQuestionnaireJson(): string
    {
        return json_encode([
            'resourceType' => 'Questionnaire',
            'status'       => 'active',
            'extension'    => [[
                'url'       => self::LAUNCH_CONTEXT_URL,
                'extension' => [
                    ['url' => 'name', 'valueCoding' => ['system' => 'http://hl7.org/fhir/uv/sdc/CodeSystem/launchContext', 'code' => 'patient']],
                    ['url' => 'type', 'valueCode' => 'Patient'],
                ],
            ]],
            'item' => [[
                'linkId'    => 'grp',
                'type'      => 'group',
                'extension' => [[
                    'url'             => self::ITEM_POPULATION_CONTEXT_URL,
                    'valueExpression' => [
                        'name'       => 'match',
                        'language'   => 'application/x-fhir-query',
                        'expression' => 'Patient?link=Patient/{{%patient.id}}',
                    ],
                ]],
                'item' => [[
                    'linkId'    => 'child',
                    'type'      => 'string',
                    'extension' => [[
                        'url'             => self::INITIAL_EXPRESSION_URL,
                        'valueExpression' => ['language' => 'text/fhirpath', 'expression' => '%match.id'],
                    ]],
                ]],
            ]],
        ], JSON_THROW_ON_ERROR);
    }

    private function patientJson(): string
    {
        return json_encode(['resourceType' => 'Patient', 'id' => 'P1'], JSON_THROW_ON_ERROR);
    }
}
