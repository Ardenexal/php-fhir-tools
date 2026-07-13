<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Integration\Populate;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnairePopulateService;
use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;
use Ardenexal\FHIRTools\Component\Sdc\Tests\Integration\AbstractSdcConformanceTest;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\DataProvider;

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
     * Root `variable` (`%pName`) reused by an item expression, plus `date`/`boolean`/`integer` answer
     * coercion — each compared against the frozen forms-lab QR (`valueDate`/`valueBoolean`/`valueInteger`).
     */
    public function testVariablesAndTypeCoercionConformToReferenceOracle(): void
    {
        $this->assertCaseConformsToOracle('populate-variables-coercion');
    }

    /**
     * `itemPopulationContext` repeating group: a 2-result context (`%patient.name`) must yield two `names`
     * group repetitions, each populated from its bound `%nameCtx`, matching the reference engine.
     */
    public function testItemPopulationContextRepeatsGroupPerResult(): void
    {
        $this->assertCaseConformsToOracle('populate-itempopulationcontext');
    }

    /**
     * `enableWhen` is NOT applied by `$populate`: a `dependent` item disabled by `enableWhen (trigger =
     * true)` with `trigger` populated `false` is still populated. Matches the reference engine and the
     * normative SDC spec ("fill in as much data as possible"; disabled-state is display-time). See
     * tests/SOURCES.md — this locks the plan reversal against regression.
     */
    public function testEnableWhenDisabledItemIsStillPopulated(): void
    {
        $this->assertCaseConformsToOracle('populate-enablewhen-notsuppressed');
    }

    /**
     * `Quantity` answer coercion: a `quantity` item populated from `%obs.value` (an Observation supplied
     * as a second launch context) must yield `valueQuantity` with value/unit/system/code intact — the
     * datatype object passed through, matching the reference engine.
     */
    public function testQuantityCoercionConformsToReferenceOracle(): void
    {
        $this->assertCaseConformsToOracle(
            'populate-coercion-quantity',
            ['obs' => ['observation', ObservationResource::class]],
        );
    }

    /**
     * `Reference` answer coercion: a `reference` item populated from `%patient.managingOrganization` (a
     * `Reference` datatype) yields `valueReference` verbatim. A bare string / whole resource would be a
     * mismatch (the engine rejects it); only a genuine `Reference` node is accepted.
     */
    public function testReferenceCoercionConformsToReferenceOracle(): void
    {
        $this->assertCaseConformsToOracle('populate-coercion-reference');
    }

    /**
     * `Coding` answer coercion: a `choice` item populated from `%patient.maritalStatus.coding.first()` (a
     * `Coding` datatype) yields `valueCoding` with system/code/display intact — the object passed through.
     * (Binding-driven `code`→`Coding` promotion — a bare `code` systematised via the item's value-set — is
     * a documented deferral; see tests/SOURCES.md and the backlog.)
     */
    public function testCodingCoercionConformsToReferenceOracle(): void
    {
        $this->assertCaseConformsToOracle('populate-coercion-coding-marital');
    }

    /**
     * Cross-version parity: the base `launchContext` + `initialExpression` case driven through the R4,
     * R4B, and R5 model namespaces must each structurally match the frozen oracle. The oracle is
     * R4B-native (forms-lab 4.3.0); its `valueString`/`status`/canonical content is byte-identical across
     * R4↔R4B↔R5, so all three conform — mirroring how the `$extract` corpus reuses its R4B oracles.
     *
     * @param class-string $questionnaireClass
     * @param class-string $patientClass
     */
    #[DataProvider('crossVersionCases')]
    public function testLaunchContextInitialConformsAcrossVersions(FhirVersion $version, string $questionnaireClass, string $patientClass): void
    {
        $serializer = FHIRSerializationService::createDefault($version);

        $questionnaire = $serializer->deserializeFromJson(
            $this->fixture('populate-launchcontext-initial.questionnaire.json'),
            $questionnaireClass,
        );
        $patient = $serializer->deserializeFromJson(
            $this->fixture('populate-launchcontext-initial.patient.json'),
            $patientClass,
        );

        $result = (new FHIRQuestionnairePopulateService())->populate(
            $questionnaire,
            new PopulateContext(
                fhirVersion: $version,
                launchContextResources: ['patient' => $patient],
                subject: 'Patient/pt',
            ),
        );

        $actual   = $this->decode($serializer->serializeToJson($result->getResponse()));
        $expected = $this->decode($this->fixture('populate-launchcontext-initial.expected-qr.json'));

        $this->assertSdcConformance($expected, $actual, 'populate parity failed for ' . $version->value);
    }

    /**
     * @return array<string, array{FhirVersion, class-string, class-string}>
     */
    public static function crossVersionCases(): array
    {
        return [
            'R4' => [
                FhirVersion::R4,
                QuestionnaireResource::class,
                PatientResource::class,
            ],
            'R4B' => [
                FhirVersion::R4B,
                \Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResource::class,
                \Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource::class,
            ],
            'R5' => [
                FhirVersion::R5,
                \Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource::class,
                \Ardenexal\FHIRTools\Component\Models\R5\Resource\PatientResource::class,
            ],
        ];
    }

    /**
     * Drive a `<case>.questionnaire.json` + `<case>.patient.json` pair through the populate service and
     * structurally compare the produced QR against the frozen `<case>.expected-qr.json` oracle. Extra
     * launch-context resources (beyond `patient`) are loaded from `<case>.<suffix>.json` and bound as
     * `%<name>`.
     *
     * @param array<string, array{0: string, 1: class-string}> $extraLaunch name => [fixture-suffix, resource class]
     */
    private function assertCaseConformsToOracle(string $case, array $extraLaunch = []): void
    {
        $expectedPath = self::FIXTURE_DIR . '/' . $case . '.expected-qr.json';
        if (!is_file($expectedPath)) {
            self::markTestSkipped('No vendored reference oracle for ' . $case . ' yet.');
        }

        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4);

        $questionnaire = $serializer->deserializeFromJson(
            $this->fixture($case . '.questionnaire.json'),
            QuestionnaireResource::class,
        );

        $launch = [
            'patient' => $serializer->deserializeFromJson(
                $this->fixture($case . '.patient.json'),
                PatientResource::class,
            ),
        ];
        foreach ($extraLaunch as $name => [$suffix, $class]) {
            $launch[$name] = $serializer->deserializeFromJson($this->fixture($case . '.' . $suffix . '.json'), $class);
        }

        $result = (new FHIRQuestionnairePopulateService())->populate(
            $questionnaire,
            new PopulateContext(
                fhirVersion: FhirVersion::R4,
                launchContextResources: $launch,
                subject: 'Patient/pt',
            ),
        );

        $actual   = $this->decode($serializer->serializeToJson($result->getResponse()));
        $expected = $this->decode($this->fixture($case . '.expected-qr.json'));

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
