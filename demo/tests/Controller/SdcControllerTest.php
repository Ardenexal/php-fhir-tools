<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SdcControllerTest extends WebTestCase
{
    private const string PATIENT_JSON = '{"resourceType":"Patient","active":true,"name":[{"given":["Jane"],"family":"Doe"}],"birthDate":"1993-04-02"}';

    public function testGetIndexPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/sdc');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'SDC Playground');
        self::assertSelectorExists('select[name="source"]');
    }

    public function testGalleryShowsCuratedEntriesAndOneClickLoadsTheForm(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/sdc');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-sample-gallery] input[value="sdc-demo-patient.questionnaire.json"]');

        $form = $crawler->filter('[data-sample-gallery] form')->first()->selectButton('Patient intake')->form();
        $client->submit($form);

        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('[data-error-panel]');
        self::assertSelectorExists('input[name="answers[patient][given]"]');
    }

    public function testPatientScopedLiveLookupPopulatesFromLaunchContextPatient(): void
    {
        $client = static::createClient();
        self::getContainer()->set(FHIRHttpClientInterface::class, new RecordingFHIRHttpClient());

        $crawler           = $client->request('POST', '/sdc/render', ['source' => 'x-fhir-query-patient-scoped-demo.questionnaire.json']);
        $questionnaireJson = $crawler->filter('input[name="questionnaireJson"]')->attr('value');
        self::assertIsString($questionnaireJson);

        $client->request('POST', '/sdc/populate', [
            'questionnaireJson' => $questionnaireJson,
            'launchContextJson' => '{"resourceType":"Patient","id":"example-123"}',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('[data-error-panel]');
        // RecordingFHIRHttpClient's search() always returns null (no real network call in this suite),
        // so the x-fhir-query fetch itself gracefully fails — this proves the sample renders and the
        // launch-context Patient ID is bound, without depending on a live server. The live-server-scoped
        // fetch itself is proven manually (see this milestone's session notes / gallery description).
        self::assertSelectorExists('input[name="answers[lookup][patientId]"][value="example-123"]');
        self::assertSelectorExists('input[name="answers[intake][given]"]');
        self::assertSelectorExists('input[name="answers[emergencyContactName]"]');
    }

    /**
     * Real-world regression, end-to-end through the actual `/sdc/populate` controller action (not just
     * the populate service directly): a Patient shaped like the user's own production data — an
     * "official" name alongside a "usual" nickname, a bare `gender` code, and an emergency `contact` with
     * its own "official" name and a `mobile`-use telecom — must populate given/family from the official
     * name, gender from its answerOption, and the emergency contact name/phone from the contact entry.
     */
    public function testPatientScopedLiveLookupPopulatesGivenFamilyGenderAndEmergencyContactFromRealisticPatient(): void
    {
        $client = static::createClient();
        self::getContainer()->set(FHIRHttpClientInterface::class, new RecordingFHIRHttpClient());

        $crawler           = $client->request('POST', '/sdc/render', ['source' => 'x-fhir-query-patient-scoped-demo.questionnaire.json']);
        $questionnaireJson = $crawler->filter('input[name="questionnaireJson"]')->attr('value');
        self::assertIsString($questionnaireJson);

        $patientJson = json_encode([
            'resourceType' => 'Patient',
            'id'           => '857925',
            'name'         => [
                ['use' => 'usual', 'given' => ['alex']],
                ['use' => 'official', 'family' => 'murray', 'given' => ['alex'], 'prefix' => ['Mr']],
            ],
            'telecom' => [
                ['system' => 'sms', 'value' => '+61427646787', 'use' => 'mobile'],
                ['system' => 'email', 'value' => 'alexmurray400@hotmail.com', 'use' => 'home'],
            ],
            'gender'    => 'male',
            'birthDate' => '1992-02-11',
            'contact'   => [[
                'name'    => ['use' => 'official', 'text' => 'kevin'],
                'telecom' => [['system' => 'phone', 'value' => '+61400000000', 'use' => 'mobile']],
            ]],
        ], JSON_THROW_ON_ERROR);

        $client->request('POST', '/sdc/populate', [
            'questionnaireJson' => $questionnaireJson,
            'launchContextJson' => $patientJson,
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('[data-error-panel]');
        self::assertSelectorExists('input[name="answers[intake][given]"][value="alex"]');
        self::assertSelectorExists('input[name="answers[intake][family]"][value="murray"]');
        self::assertSelectorExists('select[name="answers[intake][gender]"] option[value="0"][selected]');
        self::assertSelectorExists('input[name="answers[emergencyContactName]"][value="kevin"]');
        self::assertSelectorExists('input[name="answers[emergencyContactPhone]"][value="+61400000000"]');
    }

    public function testServerStatusBadgeShowsNotConfiguredByDefault(): void
    {
        $client = static::createClient();
        $client->request('GET', '/sdc');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('[data-server-status]', 'FHIR server: not configured');
        self::assertSelectorTextContains('[data-server-status]', 'Terminology server: not configured');
    }

    public function testServerStatusBadgeShowsConfiguredWhenClientIsWired(): void
    {
        $client = static::createClient();
        self::getContainer()->set(FHIRHttpClientInterface::class, new RecordingFHIRHttpClient());
        $client->request('GET', '/sdc');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('[data-server-status]', 'FHIR server: configured');
    }

    public function testServerStatusBadgeShowsOAuthMechanismWhenConfiguredThroughTheRealContainer(): void
    {
        foreach ([
            'FHIR_SERVER_URL'                 => 'https://example.invalid/fhir',
            'FHIR_SERVER_OAUTH_TOKEN_URL'     => 'https://idp.example.invalid/token',
            'FHIR_SERVER_OAUTH_CLIENT_ID'     => 'demo-client',
            'FHIR_SERVER_OAUTH_CLIENT_SECRET' => 'demo-secret',
        ] as $name => $value) {
            putenv($name . '=' . $value);
            $_ENV[$name]    = $value;
            $_SERVER[$name] = $value;
        }

        try {
            $client = static::createClient();
            $client->request('GET', '/sdc');

            self::assertResponseIsSuccessful();
            self::assertSelectorTextContains('[data-server-status]', 'FHIR server: configured (OAuth)');
        } finally {
            foreach (['FHIR_SERVER_URL', 'FHIR_SERVER_OAUTH_TOKEN_URL', 'FHIR_SERVER_OAUTH_CLIENT_ID', 'FHIR_SERVER_OAUTH_CLIENT_SECRET'] as $name) {
                putenv($name);
                unset($_ENV[$name], $_SERVER[$name]);
            }
        }
    }

    public function testRenderSampleReturnsExpectedFormFields(): void
    {
        $client = static::createClient();
        $client->request('POST', '/sdc/render', ['source' => 'sdc-demo-patient.questionnaire.json']);

        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('[data-error-panel]');
        self::assertSelectorExists('select[name="answers[patient][active]"]');
        self::assertSelectorExists('input[name="answers[patient][given]"]');
        self::assertSelectorExists('input[name="answers[patient][family]"]');
        self::assertSelectorExists('select[name="answers[patient][gender]"]');
        self::assertSelectorExists('input[name="answers[patient][dob]"]');
    }

    public function testRenderedFieldsHaveLabelForAssociations(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('POST', '/sdc/render', ['source' => 'sdc-demo-patient.questionnaire.json']);

        self::assertResponseIsSuccessful();

        $givenInput = $crawler->filter('input[name="answers[patient][given]"]');
        self::assertNotSame('', (string) $givenInput->attr('id'), 'Rendered inputs must carry an id for label association.');

        $givenId = $givenInput->attr('id');
        self::assertSelectorExists('label[for="' . $givenId . '"]');
    }

    public function testRenderUnknownSampleShowsError(): void
    {
        $client = static::createClient();
        $client->request('POST', '/sdc/render', ['source' => 'does-not-exist.json']);

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-error-panel]');
    }

    public function testRenderMalformedPastedJsonShowsFriendlyErrorNotServerError(): void
    {
        $client = static::createClient();
        $client->request('POST', '/sdc/render', [
            'source'     => 'custom',
            'customJson' => '{not valid json}',
        ]);

        self::assertResponseIsSuccessful(); // 200, not a 500 — no uncaught exception page.
        self::assertSelectorExists('[data-error-panel]');
    }

    public function testExtractWithRepeatingContactAndQuantity(): void
    {
        $client            = static::createClient();
        $crawler           = $client->request('POST', '/sdc/render', ['source' => 'sdc-demo-patient.questionnaire.json']);
        $questionnaireJson = $crawler->filter('input[name="questionnaireJson"]')->attr('value');
        self::assertIsString($questionnaireJson);

        $client->request('POST', '/sdc/extract', [
            'questionnaireJson' => $questionnaireJson,
            'answers'           => [
                'patient' => [
                    'given'   => 'Jane',
                    'contact' => [
                        '0' => ['contactGiven' => 'Alice', 'contactFamily' => 'Anderson'],
                        '1' => ['contactGiven' => 'Bob', 'contactFamily' => 'Baker'],
                    ],
                ],
                'weightObs' => ['weight' => '70.5 kg'],
            ],
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('[data-error-panel]');

        $bundleText = $client->getCrawler()->filter('[data-extract-result] pre')->text();
        /** @var array<string, mixed> $bundle */
        $bundle = json_decode($bundleText, true, 512, JSON_THROW_ON_ERROR);

        self::assertCount(2, $bundle['entry']);
        $byType = [];
        foreach ($bundle['entry'] as $entry) {
            $byType[$entry['resource']['resourceType']] = $entry['resource'];
        }

        self::assertCount(2, $byType['Patient']['contact']);
        self::assertEqualsWithDelta(70.5, $byType['Observation']['valueQuantity']['value'], 0.0001);

        $qrText = $client->getCrawler()->filter('[data-qr-json-viewer] pre')->text();
        $qr     = json_decode($qrText, true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('QuestionnaireResponse', $qr['resourceType']);
    }

    public function testPopulateThenExtractRoundTrip(): void
    {
        $client            = static::createClient();
        $crawler           = $client->request('POST', '/sdc/render', ['source' => 'sdc-demo-patient.questionnaire.json']);
        $questionnaireJson = $crawler->filter('input[name="questionnaireJson"]')->attr('value');
        self::assertIsString($questionnaireJson);

        // Populate prefills given/family/dob/active from the launch-context Patient.
        $client->request('POST', '/sdc/populate', [
            'questionnaireJson' => $questionnaireJson,
            'launchContextJson' => self::PATIENT_JSON,
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('[data-error-panel]');
        self::assertSelectorExists('input[name="answers[patient][given]"][value="Jane"]');
        self::assertSelectorExists('input[name="answers[patient][family]"][value="Doe"]');
        self::assertSelectorExists('input[name="answers[patient][dob]"][value="1993-04-02"]');

        $qrText = $client->getCrawler()->filter('[data-qr-json-viewer] pre')->text();
        $qr     = json_decode($qrText, true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('QuestionnaireResponse', $qr['resourceType']);

        // Extract with the same (plus a manually-picked gender) produces a Bundle matching the answers.
        $client->request('POST', '/sdc/extract', [
            'questionnaireJson' => $questionnaireJson,
            'answers'           => [
                'patient' => [
                    'active' => 'true',
                    'given'  => 'Jane',
                    'family' => 'Doe',
                    'gender' => '1',
                    'dob'    => '1993-04-02',
                ],
            ],
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('[data-error-panel]');
        self::assertSelectorExists('[data-extract-result]');

        $bundleText = $client->getCrawler()->filter('[data-extract-result] pre')->text();
        /** @var array<string, mixed> $bundle */
        $bundle = json_decode($bundleText, true, 512, JSON_THROW_ON_ERROR);
        /** @var array<string, mixed> $patient */
        $patient = $bundle['entry'][0]['resource'];

        self::assertSame('Patient', $patient['resourceType']);
        self::assertTrue($patient['active']);
        self::assertSame('Jane', $patient['name'][0]['given'][0]);
        self::assertSame('Doe', $patient['name'][0]['family']);
        self::assertSame('female', $patient['gender']);
        self::assertSame('1993-04-02', $patient['birthDate']);
    }

    /**
     * Read directly from disk rather than via a `/sdc/render` round trip — `KernelBrowser` reboots the
     * kernel (and its container) before *every* request by default, which would silently discard a
     * `self::getContainer()->set(...)` override made between two requests in the same test. Fixture
     * tests that need both a swapped-in test double AND a specific `questionnaireJson` value must avoid
     * a second request, not just call `set()` once.
     */
    private function sampleQuestionnaireJson(): string
    {
        $json = file_get_contents(__DIR__ . '/../../assets/sdc-samples/sdc-demo-patient.questionnaire.json');
        self::assertIsString($json);

        return $json;
    }

    public function testPopulateFetchesLaunchContextResourceByIdFromTheConfiguredServer(): void
    {
        $client                   = static::createClient();
        $spy                      = new RecordingFHIRHttpClient();
        $spy->requestResponseBody = json_encode([
            'resourceType' => 'Patient',
            'id'           => '123',
            'name'         => [['given' => ['Fetched'], 'family' => 'ByLookup']],
            'birthDate'    => '1990-01-01',
        ], JSON_THROW_ON_ERROR);
        self::getContainer()->set(FHIRHttpClientInterface::class, $spy);

        $client->request('POST', '/sdc/populate', [
            'questionnaireJson' => $this->sampleQuestionnaireJson(),
            'launchContextId'   => '123',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('[data-error-panel]');
        self::assertSame('Patient/123', $spy->lastRequestPath);
        self::assertSelectorExists('input[name="answers[patient][given]"][value="Fetched"]');
        self::assertSelectorExists('input[name="answers[patient][family]"][value="ByLookup"]');
    }

    public function testPopulateFetchByIdTakesPrecedenceOverPastedLaunchContextJson(): void
    {
        $client                   = static::createClient();
        $spy                      = new RecordingFHIRHttpClient();
        $spy->requestResponseBody = json_encode([
            'resourceType' => 'Patient',
            'id'           => '123',
            'name'         => [['given' => ['FromServer'], 'family' => 'Fetched']],
        ], JSON_THROW_ON_ERROR);
        self::getContainer()->set(FHIRHttpClientInterface::class, $spy);

        $client->request('POST', '/sdc/populate', [
            'questionnaireJson' => $this->sampleQuestionnaireJson(),
            'launchContextId'   => '123',
            'launchContextJson' => self::PATIENT_JSON,
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('input[name="answers[patient][given]"][value="FromServer"]');
    }

    public function testPopulateFetchByIdShowsFriendlyErrorWhenFetchFails(): void
    {
        $client = static::createClient();
        self::getContainer()->set(FHIRHttpClientInterface::class, new RecordingFHIRHttpClient());

        $client->request('POST', '/sdc/populate', [
            'questionnaireJson' => $this->sampleQuestionnaireJson(),
            'launchContextId'   => 'does-not-exist',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-error-panel]');
    }

    public function testPopulateFetchByIdRejectsAnInvalidIdWithoutMakingAnyRequest(): void
    {
        $client = static::createClient();
        $spy    = new RecordingFHIRHttpClient();
        self::getContainer()->set(FHIRHttpClientInterface::class, $spy);

        $client->request('POST', '/sdc/populate', [
            'questionnaireJson' => $this->sampleQuestionnaireJson(),
            'launchContextId'   => '123/../../etc/passwd',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-error-panel]');
        self::assertNull($spy->lastRequestPath, 'An invalid id must never reach the outbound request path.');
    }

    /**
     * A Questionnaire's `launchContext.type` is visitor-suppliable content (the "paste your own JSON"
     * path), not a trusted server-declared value — a malicious/unrecognized type must be rejected
     * before it ever reaches the outbound request path, the same way an invalid id is.
     */
    public function testPopulateFetchByIdRejectsAnUnrecognizedResourceTypeWithoutMakingAnyRequest(): void
    {
        $client = static::createClient();
        $spy    = new RecordingFHIRHttpClient();
        self::getContainer()->set(FHIRHttpClientInterface::class, $spy);

        $maliciousQuestionnaireJson = json_encode([
            'resourceType' => 'Questionnaire',
            'status'       => 'active',
            'extension'    => [[
                'url'       => 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-launchContext',
                'extension' => [
                    ['url' => 'name', 'valueCoding' => ['system' => 'http://hl7.org/fhir/uv/sdc/CodeSystem/launchContext', 'code' => 'patient']],
                    ['url' => 'type', 'valueCode' => 'Patient/../admin?evil=1'],
                ],
            ]],
            'item' => [],
        ], JSON_THROW_ON_ERROR);

        $client->request('POST', '/sdc/populate', [
            'questionnaireJson' => $maliciousQuestionnaireJson,
            'launchContextId'   => 'abc',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-error-panel]');
        self::assertNull($spy->lastRequestPath, 'An unrecognized resource type must never reach the outbound request path.');
    }

    public function testFetchByIdFieldHiddenWhenNoFhirServerConfigured(): void
    {
        $client = static::createClient();
        $client->request('POST', '/sdc/render', ['source' => 'sdc-demo-patient.questionnaire.json']);

        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('input[name="launchContextId"]');
    }
}
