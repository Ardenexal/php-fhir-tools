<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Integration\Populate;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnairePopulateService;
use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;
use Ardenexal\FHIRTools\Component\Sdc\Tests\Integration\AbstractSdcConformanceTest;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

/**
 * Reference-seeded conformance for expression-based `Questionnaire/$populate` (sdc-populate M01, R4).
 *
 * Feeds a minimal `launchContext` + `initialExpression` Questionnaire through
 * {@see FHIRQuestionnairePopulateService} and structurally compares the produced
 * `QuestionnaireResponse` against a **frozen expected QR vendored from an independent SDC reference
 * engine** (forms-lab, `POST Questionnaire/$populate`, R4B) — via {@see AbstractSdcConformanceTest},
 * which tolerates spec-legal divergence (ordering, ids, timestamps, optional text). See tests/SOURCES.md.
 *
 * The oracle was produced by an R4B-native engine; this test drives the **R4** model path against it —
 * legitimate because the QR content it exercises (string answers, a canonical `questionnaire`, `status`)
 * is byte-identical across R4↔R4B, exactly as the `$extract` corpus reuses its R4B oracles for R4.
 */
final class FHIRPopulateConformanceTest extends AbstractSdcConformanceTest
{
    /**
     * Populate-only addition to the shared ignore-list. Kept in this subclass — NOT the shared base —
     * because `canonicalize()` reads `static::IGNORED_KEYS`, and dropping `subject` globally would weaken
     * unrelated oracles.
     *
     *  - `subject`: the forms-lab reference engine omits `QuestionnaireResponse.subject` from its output
     *    (subject is 0..1 optional). This toolkit sets it per the SDC populate guidance, so it is dropped
     *    from both sides here. Its presence is asserted directly in the unit test instead.
     *
     * @var list<string>
     */
    protected const IGNORED_KEYS = ['id', 'text', 'display', 'authored', 'lastUpdated', 'subject'];

    private const string FIXTURE_DIR = __DIR__ . '/../../Fixtures/Populate';

    private const string EXPECTED_QR = self::FIXTURE_DIR . '/populate-launchcontext-initial.expected-qr.json';

    public function testLaunchContextInitialExpressionConformsToReferenceOracle(): void
    {
        if (!is_file(self::EXPECTED_QR)) {
            self::markTestSkipped(
                'No vendored reference oracle for expression-based $populate yet. Freeze an expected QR '
                . 'from an independent SDC reference engine (forms-lab $populate) at '
                . basename(self::EXPECTED_QR) . ' and record the engine + version in tests/SOURCES.md. '
                . 'Do NOT seed it from this toolkit\'s own output (circular).',
            );
        }

        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4);

        $questionnaire = $serializer->deserializeFromJson(
            $this->fixture('populate-launchcontext-initial.questionnaire.json'),
            QuestionnaireResource::class,
        );
        $patient = $serializer->deserializeFromJson(
            $this->fixture('populate-launchcontext-initial.patient.json'),
            PatientResource::class,
        );

        $result = (new FHIRQuestionnairePopulateService())->populate(
            $questionnaire,
            new PopulateContext(
                fhirVersion: FhirVersion::R4,
                launchContextResources: ['patient' => $patient],
                subject: 'Patient/patient-spike',
            ),
        );

        $actual   = $this->decode($serializer->serializeToJson($result->getResponse()));
        $expected = $this->decode($this->fixture('populate-launchcontext-initial.expected-qr.json'));

        $this->assertSdcConformance($expected, $actual);
    }

    /**
     * @return array<string, mixed>
     */
    private function decode(string $json): array
    {
        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return $decoded;
    }

    private function fixture(string $name): string
    {
        $path     = self::FIXTURE_DIR . '/' . $name;
        $contents = file_get_contents($path);
        self::assertIsString($contents, 'Missing fixture: ' . $name);

        return $contents;
    }
}
