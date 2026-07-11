<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Integration\Extract;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Sdc\ExtractContext;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnaireResponseExtractService;
use Ardenexal\FHIRTools\Component\Sdc\Tests\Integration\AbstractSdcConformanceTest;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Reference-seeded conformance for observation-based `QuestionnaireResponse/$extract` (R4).
 *
 * Feeds a vendored input case (Questionnaire + QuestionnaireResponse) through the extract service and
 * structurally compares the produced transaction Bundle against a **frozen expected Bundle vendored
 * from an independent SDC reference engine** — via {@see AbstractSdcConformanceTest}, which tolerates
 * spec-legal divergence (ordering, ids, `urn:uuid:` topology).
 *
 * ## Why this may skip
 *
 * The expected Bundle must be produced by an independent reference engine — never by this toolkit and
 * never hand-authored — or it degenerates into a circular snapshot of our own output (see
 * `tests/SOURCES.md` and the `questionnaire-conformance-seed-truth` discipline). The SDC IG ships no
 * observation-extract example output, and the M00-proven engine (`sqlonfhir`) is flagged untrustworthy
 * for seeding. Until a trustworthy engine (HAPI / Firely) is selected and its output frozen at
 * {@see EXPECTED_BUNDLE}, this test **skips** rather than asserting against a self-made baseline.
 *
 * The extraction logic itself is covered deterministically by the unit test
 * `FHIRQuestionnaireResponseExtractServiceTest`; this test adds independent-oracle conformance on top.
 */
final class FHIRExtractConformanceTest extends AbstractSdcConformanceTest
{
    /**
     * Extract-only additions to the shared ignore-list. Kept in this subclass — NOT the shared base —
     * because `canonicalize()` reads `static::IGNORED_KEYS`, and dropping `url` globally would collapse
     * distinct `extension.url`s and gut the `$populate` oracle.
     *
     *  - `url`: an extract Bundle entry's `request.url` (the POST target). The forms-lab reference engine
     *    emits only `request.method`; the harness's own contract compares `request.method`, not `url`
     *    (our output's `url: "Observation"` is verified deterministically in the unit test instead).
     *    Safe here: the observation-extract output carries no other meaningful `url` element.
     *
     * @var list<string>
     */
    protected const IGNORED_KEYS = ['id', 'text', 'display', 'authored', 'lastUpdated', 'url'];

    private const string FIXTURE_DIR = __DIR__ . '/../../Fixtures/Extract';

    private const string EXPECTED_BUNDLE = self::FIXTURE_DIR . '/observation-extract-basic.expected-bundle.json';

    public function testObservationExtractBasicConformsToReferenceOracle(): void
    {
        if (!is_file(self::EXPECTED_BUNDLE)) {
            self::markTestSkipped(
                'No vendored reference oracle for observation-based $extract yet. Freeze an expected '
                . 'Bundle from an independent SDC reference engine (HAPI/Firely) at '
                . basename(self::EXPECTED_BUNDLE) . ' and record the engine + version in tests/SOURCES.md. '
                . 'Do NOT seed it from this toolkit\'s own output (circular).',
            );
        }

        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4);

        $questionnaire = $serializer->deserializeFromJson(
            $this->fixture('observation-extract-basic.questionnaire.json'),
            QuestionnaireResource::class,
        );
        $response = $serializer->deserializeFromJson(
            $this->fixture('observation-extract-basic.response.json'),
            QuestionnaireResponseResource::class,
        );

        $result = (new FHIRQuestionnaireResponseExtractService())
            ->extract($response, new ExtractContext(questionnaire: $questionnaire));

        $actual   = $this->normalizeInstants($this->decode($serializer->serializeToJson($result->getResource())));
        $expected = $this->normalizeInstants($this->decode($this->fixture('observation-extract-basic.expected-bundle.json')));

        $this->assertSdcConformance($expected, $actual);
    }

    /**
     * Definition-based extraction: a `definitionExtract` Patient group with a `Patient.name` sub-group
     * and `birthDate`, compared to the frozen forms-lab reference Bundle. Exercises hierarchical
     * writing (one merged `name` element) and canonical→resource-class resolution.
     */
    public function testDefinitionExtractBasicConformsToReferenceOracle(): void
    {
        $expectedPath = self::FIXTURE_DIR . '/definition-extract-basic.expected-bundle.json';
        if (!is_file($expectedPath)) {
            self::markTestSkipped('No vendored reference oracle for definition-based $extract yet.');
        }

        [$actual, $expected] = $this->extractAndExpected('definition-extract-basic');

        $this->assertSdcConformance($expected, $actual);
    }

    /**
     * R4B parity across the full definition-based corpus: each case driven through the **R4B** model
     * namespace, compared to the frozen forms-lab Bundle. This is a genuine independent oracle for R4B —
     * forms-lab's capability statement declares `fhirVersion 4.3.0` (R4B), so the frozen expected Bundles
     * were produced by an R4B-native engine (the R4 cases reuse them via R4↔R4B wire-compatibility).
     * See tests/SOURCES.md.
     */
    #[DataProvider('definitionCorpus')]
    public function testDefinitionCorpusR4BConformsToReferenceOracle(string $case): void
    {
        $expectedPath = self::FIXTURE_DIR . '/' . $case . '.expected-bundle.json';
        if (!is_file($expectedPath)) {
            self::markTestSkipped('No vendored reference oracle for ' . $case . ' yet.');
        }

        $actual   = $this->extractForVersion($case, FhirVersion::R4B);
        $expected = $this->normalizeInstants($this->decode($this->fixture($case . '.expected-bundle.json')));

        $this->assertSdcConformance($expected, $actual);
    }

    /**
     * R5 parity across the full definition-based corpus: each case driven through the **R5** model
     * namespace.
     *
     * ## Oracle caveat — structural parity, NOT an independent R5 oracle
     *
     * No independent SDC `$extract` engine for R5 is reachable (forms-lab is R4B-native; HAPI exposes no
     * `$extract`; sqlonfhir is untrustworthy — see tests/SOURCES.md). These cases therefore assert that the
     * R5 model path produces a Bundle **structurally equivalent** to the frozen R4B/R4 oracle — legitimate
     * here because the extracted resources (Patient/RelatedPerson name/identifier/reference topology) and
     * the transaction envelope are byte-identical across R4→R4B→R5. It is a cross-version structural-parity
     * guard, documented as a plan deviation in the M02 milestone, and MUST be reseeded from a real R5
     * engine if one becomes available.
     */
    #[DataProvider('definitionCorpus')]
    public function testDefinitionCorpusR5StructurallyMatchesReferenceOracle(string $case): void
    {
        $expectedPath = self::FIXTURE_DIR . '/' . $case . '.expected-bundle.json';
        if (!is_file($expectedPath)) {
            self::markTestSkipped('No vendored reference oracle for ' . $case . ' yet.');
        }

        $actual   = $this->extractForVersion($case, FhirVersion::R5);
        $expected = $this->normalizeInstants($this->decode($this->fixture($case . '.expected-bundle.json')));

        $this->assertSdcConformance($expected, $actual);
    }

    /**
     * Observation-based extraction is R4-only (M01 scope): the produced `Observation` and its datatypes
     * are R4. A non-R4 run carrying `observationExtract` items must therefore report a diagnostic issue
     * and skip those items — never emit a wrong-version Observation into the Bundle. Drives the R4-only
     * observation fixture through the R4B model path and asserts the guard fires.
     */
    public function testObservationExtractUnderNonR4ReportsIssueAndSkips(): void
    {
        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4B);
        $namespace  = 'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Resource\\';

        $questionnaire = $serializer->deserializeFromJson(
            $this->fixture('observation-extract-basic.questionnaire.json'),
            $namespace . 'QuestionnaireResource',
        );
        $response = $serializer->deserializeFromJson(
            $this->fixture('observation-extract-basic.response.json'),
            $namespace . 'QuestionnaireResponseResource',
        );

        $result = (new FHIRQuestionnaireResponseExtractService())
            ->extract($response, new ExtractContext(fhirVersion: FhirVersion::R4B, questionnaire: $questionnaire));

        $bundle = $this->decode($serializer->serializeToJson($result->getResource()));
        self::assertSame([], $bundle['entry'] ?? [], 'No Observation entries may be emitted for a non-R4 run.');

        $issues = $result->getIssues();
        self::assertNotNull($issues, 'A non-R4 observationExtract run must surface a diagnostic issue.');
        $diagnostics = $this->decode($serializer->serializeToJson($issues));
        $texts       = array_map(
            static fn (mixed $i): string => is_array($i) && is_string($i['diagnostics'] ?? null) ? $i['diagnostics'] : '',
            is_array($diagnostics['issue'] ?? null) ? $diagnostics['issue'] : [],
        );
        self::assertNotEmpty(
            array_filter($texts, static fn (string $t): bool => str_contains($t, 'Observation-based extraction is only supported for R4')),
            'Expected an issue explaining observation-based extraction is R4-only.',
        );
    }

    /**
     * When the QuestionnaireResponse's own model version disagrees with the requested extraction version,
     * the service refuses cleanly: the extracted values are version-specific model objects that cannot be
     * grafted across versions (an R4 `DatePrimitive` will not assign to an R5 `?DatePrimitive` property),
     * so it returns an empty transaction Bundle plus a diagnostic issue rather than emitting a malformed
     * cross-version Bundle or crashing. Guards that mismatch branch: an R4 `definition-extract-basic`
     * response run under an R5 context yields no entries and an issue naming the disagreement.
     */
    public function testResponseVersionMismatchRefusesWithDiagnostic(): void
    {
        $r4Serializer  = FHIRSerializationService::createDefault(FhirVersion::R4);
        $questionnaire = $r4Serializer->deserializeFromJson(
            $this->fixture('definition-extract-basic.questionnaire.json'),
            QuestionnaireResource::class,
        );
        $response = $r4Serializer->deserializeFromJson(
            $this->fixture('definition-extract-basic.response.json'),
            QuestionnaireResponseResource::class,
        );

        // Request R5 output for an R4-typed response.
        $result     = (new FHIRQuestionnaireResponseExtractService())
            ->extract($response, new ExtractContext(fhirVersion: FhirVersion::R5, questionnaire: $questionnaire));
        $r5Serializer = FHIRSerializationService::createDefault(FhirVersion::R5);

        $bundle = $this->decode($r5Serializer->serializeToJson($result->getResource()));
        self::assertSame('Bundle', $bundle['resourceType'] ?? null);
        self::assertSame('transaction', $bundle['type'] ?? null);
        self::assertSame([], $bundle['entry'] ?? [], 'A version mismatch must extract nothing.');

        $issues = $result->getIssues();
        self::assertNotNull($issues, 'A version mismatch must surface a diagnostic issue.');
        $decoded = $this->decode($r5Serializer->serializeToJson($issues));
        $texts   = array_map(
            static fn (mixed $i): string => is_array($i) && is_string($i['diagnostics'] ?? null) ? $i['diagnostics'] : '',
            is_array($decoded['issue'] ?? null) ? $decoded['issue'] : [],
        );
        self::assertNotEmpty(
            array_filter($texts, static fn (string $t): bool => str_contains($t, 'differs from the requested extraction version')),
            'Expected an issue naming the QuestionnaireResponse/extraction version disagreement.',
        );
    }

    /**
     * Direct assertions on the `extract-complex-defn3` output (R4B), guarding the complex mappings the
     * uuid-tokenised oracle comparison could mask: choice-slice `value[x]:valueQuantity` (an
     * answer-bearing `definitionExtract` root), `fixed-value` complex/coded calculated values
     * (`code.coding`/`category.coding`/`identifier.type`), a `Coding` answer reduced to a `code`
     * (`gender`), temporal coercion (`effectiveDateTime`/`issued`), and cross-resource reference topology.
     *
     * ## Known gap (documented)
     *
     * `Patient.name.text` (a `definitionExtractValue` whose FHIRPath `item.where(...)` requires the current
     * QuestionnaireResponse item as the evaluation focus while `%resource` stays the QR root) is NOT
     * produced: the FHIRPath evaluator binds `%resource` to the focus node, so focus and `%resource`
     * cannot yet differ (separating them is a shared-FHIRPath change out of scope for this stretch).
     * `HumanName.text` is a **data-bearing** element the reference engine computed on purpose — the oracle
     * comparison only stays green because `text` is in the shared `IGNORED_KEYS`; this assertion documents
     * the gap explicitly and trips if the focus-node limitation is later lifted.
     */
    public function testComplexDefn3ExtractsTypedResources(): void
    {
        $actual  = $this->extractForVersion('extract-complex-defn3', FhirVersion::R4B);
        $entries = $actual['entry'];
        self::assertIsArray($entries);
        self::assertCount(4, $entries, 'Expected Patient + RelatedPerson + 2 Observations (complication is not its own Observation).');

        // Index entries by resourceType (+ Observation code) — order is not asserted.
        $byType = [];
        foreach ($entries as $entry) {
            self::assertIsArray($entry);
            self::assertIsArray($entry['resource']);
            $resource = $entry['resource'];
            $type     = $resource['resourceType'] ?? null;
            self::assertIsString($type);
            $key          = $type === 'Observation' ? 'Observation:' . ($resource['code']['coding'][0]['code'] ?? '?') : $type;
            $byType[$key] = $entry;
        }

        $patient = $byType['Patient']['resource'] ?? null;
        self::assertIsArray($patient);
        self::assertSame('male', $patient['gender'] ?? null, 'A Coding answer must reduce to the code leaf Patient.gender.');
        self::assertSame('1974-12-25', $patient['birthDate'] ?? null);
        self::assertSame('http://example.org/nhio', $patient['identifier'][0]['system'] ?? null);
        self::assertSame('8003608833357361', $patient['identifier'][0]['value'] ?? null);

        $patientFullUrl = $byType['Patient']['fullUrl'] ?? null;
        self::assertIsString($patientFullUrl);

        // Choice-slice value[x]:valueQuantity on the answer-bearing definitionExtract root, plus fixed-value
        // complex/coded calculated fields and temporal coercion.
        $height = $byType['Observation:8302-2']['resource'] ?? null;
        self::assertIsArray($height);
        self::assertSame('final', $height['status'] ?? null);
        self::assertSame('vital-signs', $height['category'][0]['coding'][0]['code'] ?? null);
        self::assertEqualsWithDelta(180, $height['valueQuantity']['value'] ?? null, 0.0001);
        self::assertSame('m', $height['valueQuantity']['unit'] ?? null);
        // `+00:00` is normalised to `Z` by extractForVersion()'s instant normalisation (same instant).
        self::assertSame('2024-01-15T10:30:00Z', $height['effectiveDateTime'] ?? null);
        self::assertSame('2024-01-15T10:30:00Z', $height['issued'] ?? null);
        self::assertSame('Practitioner/dr-example', $height['performer'][0]['reference'] ?? null);
        self::assertSame($patientFullUrl, $height['subject']['reference'] ?? null, 'Observation.subject must resolve to the Patient fullUrl.');
        self::assertSame('QuestionnaireResponse/extract-complex-defn3-response', $height['derivedFrom'][0]['reference'] ?? null);

        // Cross-resource reference topology: RelatedPerson.patient → the Patient entry.
        $related = $byType['RelatedPerson']['resource'] ?? null;
        self::assertIsArray($related);
        self::assertSame($patientFullUrl, $related['patient']['reference'] ?? null);

        // Documented known gap: name.text (focus-node FHIRPath) is not produced.
        self::assertArrayNotHasKey('text', is_array($patient['name'][0] ?? null) ? $patient['name'][0] : [], 'name.text is a known gap (see docblock); update this guard if the focus-node limitation is lifted.');
    }

    /**
     * The definition-based extraction cases vendored from the forms-lab oracle, exercised across versions.
     *
     * @return iterable<string, array{0: string}>
     */
    public static function definitionCorpus(): iterable
    {
        yield 'basic'       => ['definition-extract-basic'];
        yield 'allocateId'  => ['definition-extract-allocateid'];
        yield 'value'       => ['definition-extract-value'];
        yield 'put'         => ['definition-extract-put'];
        yield 'complexDefn3' => ['extract-complex-defn3'];
    }

    /**
     * `extractAllocateId` cross-reference: a Patient and a RelatedPerson linked by a single allocated
     * `urn:uuid:` (the Patient's `fullUrl` and the RelatedPerson's `patient.reference`), compared to the
     * frozen forms-lab reference Bundle. Exercises id allocation, `fullUrl` FHIRPath resolution, and a
     * `definitionExtractValue` writing the allocated id into a cross-resource reference.
     */
    public function testAllocateIdCrossReferenceConformsToReferenceOracle(): void
    {
        $expectedPath = self::FIXTURE_DIR . '/definition-extract-allocateid.expected-bundle.json';
        if (!is_file($expectedPath)) {
            self::markTestSkipped('No vendored reference oracle for allocateId cross-reference $extract yet.');
        }

        [$actual, $expected] = $this->extractAndExpected('definition-extract-allocateid');

        $this->assertSdcConformance($expected, $actual);
    }

    /**
     * `definitionExtractValue` typed coercion: a calculated `Patient.identifier.system` (a FHIRPath
     * string literal written into a `?UriPrimitive`) merged with an answered `Patient.identifier.value`
     * into one `identifier`, compared to the frozen forms-lab reference Bundle. Exercises the writer's
     * scalar→primitive wrapping and hierarchical merge of a calculated field with an answered sibling.
     */
    public function testDefinitionExtractValueTypedConformsToReferenceOracle(): void
    {
        $expectedPath = self::FIXTURE_DIR . '/definition-extract-value.expected-bundle.json';
        if (!is_file($expectedPath)) {
            self::markTestSkipped('No vendored reference oracle for definitionExtractValue typed writes yet.');
        }

        [$actual, $expected] = $this->extractAndExpected('definition-extract-value');

        $this->assertSdcConformance($expected, $actual);
    }

    /**
     * `POST`/`PUT` request directive, asserted directly on our own output through the real deserializer
     * (constructor-bypassed inputs — the model-init footgun) rather than a vendored oracle: no corpus
     * case exercises PUT, and the conformance ignore-list drops `request.url`. A definition-extracted
     * Patient whose logical `id` is written from a hidden `Patient.id` item must be a `PUT Type/id`
     * (update), while an id-less extraction stays a `POST Type` (create).
     *
     * @see https://build.fhir.org/ig/HL7/sdc/en/extraction.html — id present ⇒ PUT to Type/id, else POST.
     */
    /**
     * `POST`/`PUT` conformance against the frozen forms-lab oracle: the reference engine emits
     * `request.method: PUT` for a resource carrying a logical `id`, which the harness compares (unlike
     * `request.url`, which it ignores). Independently corroborates the request-directive switch; the
     * exact `url = Patient/pat-42` is asserted directly in {@see testDefinitionExtractWithLogicalIdProducesPutDirective}.
     */
    public function testDefinitionExtractPutConformsToReferenceOracle(): void
    {
        $expectedPath = self::FIXTURE_DIR . '/definition-extract-put.expected-bundle.json';
        if (!is_file($expectedPath)) {
            self::markTestSkipped('No vendored reference oracle for the PUT request directive yet.');
        }

        [$actual, $expected] = $this->extractAndExpected('definition-extract-put');

        $this->assertSdcConformance($expected, $actual);
    }

    public function testDefinitionExtractWithLogicalIdProducesPutDirective(): void
    {
        $actual = $this->extractOnly('definition-extract-put');

        $entries = $actual['entry'];
        self::assertIsArray($entries);
        self::assertCount(1, $entries);

        $entry = $entries[0];
        self::assertIsArray($entry);
        self::assertIsArray($entry['request']);
        self::assertSame('PUT', $entry['request']['method'] ?? null, 'A resource carrying a logical id must be an update (PUT).');
        self::assertSame('Patient/pat-42', $entry['request']['url'] ?? null);

        self::assertIsArray($entry['resource']);
        self::assertSame('pat-42', $entry['resource']['id'] ?? null);
        self::assertSame('Chalmers', $entry['resource']['name'][0]['family'] ?? null);
    }

    /**
     * The id-less counterpart of the PUT case: a definition-extracted resource with no logical `id`
     * stays a `POST Type` (create). Guards the false branch of the request-directive switch through the
     * real deserializer, so a regression that emitted PUT unconditionally would fail here.
     */
    public function testDefinitionExtractWithoutLogicalIdStaysPost(): void
    {
        $actual = $this->extractOnly('definition-extract-basic');

        $entry = $actual['entry'][0];
        self::assertIsArray($entry);
        self::assertIsArray($entry['request']);
        self::assertSame('POST', $entry['request']['method'] ?? null);
        self::assertSame('Patient', $entry['request']['url'] ?? null);
    }

    /**
     * The milestone kill criterion, asserted directly on our own output (independent of the oracle's
     * random UUIDs): the two extracted resources reference each other via a single, non-empty
     * `urn:uuid:` — the Patient's `fullUrl` equals the RelatedPerson's `patient.reference`.
     */
    public function testAllocateIdProducesResolvableCrossReference(): void
    {
        [$actual] = $this->extractAndExpected('definition-extract-allocateid');

        $entries = $actual['entry'];
        self::assertIsArray($entries);
        self::assertCount(2, $entries);

        $patientEntry      = $entries[0];
        $relatedEntry      = $entries[1];
        self::assertIsArray($patientEntry);
        self::assertIsArray($relatedEntry);

        $patientFullUrl = $patientEntry['fullUrl'] ?? null;
        self::assertIsArray($relatedEntry['resource']);
        self::assertIsArray($relatedEntry['resource']['patient']);
        $crossReference = $relatedEntry['resource']['patient']['reference'] ?? null;

        self::assertIsString($patientFullUrl);
        self::assertMatchesRegularExpression('/^urn:uuid:[0-9a-fA-F-]{36}$/', $patientFullUrl);
        self::assertSame($patientFullUrl, $crossReference, 'RelatedPerson.patient.reference must resolve to the Patient entry fullUrl.');
    }

    /**
     * Guards against a vacuous oracle for the linkage itself: if the cross-reference is broken (points
     * at a different UUID), the reference-topology comparison MUST fail against the frozen oracle. Proves
     * `tokenizeUuids` verifies linkage, not just that a `urn:uuid:` is present.
     */
    public function testAllocateIdOracleDetectsBrokenLinkage(): void
    {
        $expectedPath = self::FIXTURE_DIR . '/definition-extract-allocateid.expected-bundle.json';
        if (!is_file($expectedPath)) {
            self::markTestSkipped('No vendored reference oracle for allocateId cross-reference $extract yet.');
        }

        [$actual, $expected] = $this->extractAndExpected('definition-extract-allocateid');

        // Break the linkage: repoint the RelatedPerson at an unrelated UUID.
        $relatedEntry = $actual['entry'][1];
        self::assertIsArray($relatedEntry);
        self::assertIsArray($relatedEntry['resource']);
        self::assertIsArray($relatedEntry['resource']['patient']);
        $actual['entry'][1]['resource']['patient']['reference'] = 'urn:uuid:00000000-0000-4000-8000-000000000000';

        $this->expectException(ExpectationFailedException::class);
        $this->assertSdcConformance($expected, $actual);
    }

    /**
     * Deserialize a case's Questionnaire + QuestionnaireResponse, run `$extract`, and return the
     * normalised [actual, expected] decoded Bundles for structural comparison.
     *
     * @return array{0: array<string, mixed>, 1: array<string, mixed>}
     */
    private function extractAndExpected(string $case): array
    {
        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4);

        $questionnaire = $serializer->deserializeFromJson(
            $this->fixture($case . '.questionnaire.json'),
            QuestionnaireResource::class,
        );
        $response = $serializer->deserializeFromJson(
            $this->fixture($case . '.response.json'),
            QuestionnaireResponseResource::class,
        );

        $result = (new FHIRQuestionnaireResponseExtractService())
            ->extract($response, new ExtractContext(questionnaire: $questionnaire));

        return [
            $this->normalizeInstants($this->decode($serializer->serializeToJson($result->getResource()))),
            $this->normalizeInstants($this->decode($this->fixture($case . '.expected-bundle.json'))),
        ];
    }

    /**
     * Deserialize a case's Questionnaire + QuestionnaireResponse into a specific FHIR version's model
     * namespace, run `$extract` for that version, and return the normalised decoded actual Bundle. Backs
     * the R4B/R5 parity cases — the version-neutral input JSON is deserialized into the requested version's
     * generated classes so the version-generic extraction path is exercised end-to-end per version.
     *
     * @return array<string, mixed>
     */
    private function extractForVersion(string $case, FhirVersion $version): array
    {
        $serializer = FHIRSerializationService::createDefault($version);
        $namespace  = 'Ardenexal\\FHIRTools\\Component\\Models\\' . $version->value . '\\Resource\\';

        $questionnaire = $serializer->deserializeFromJson(
            $this->fixture($case . '.questionnaire.json'),
            $namespace . 'QuestionnaireResource',
        );
        $response = $serializer->deserializeFromJson(
            $this->fixture($case . '.response.json'),
            $namespace . 'QuestionnaireResponseResource',
        );

        $result = (new FHIRQuestionnaireResponseExtractService())
            ->extract($response, new ExtractContext(fhirVersion: $version, questionnaire: $questionnaire));

        return $this->normalizeInstants($this->decode($serializer->serializeToJson($result->getResource())));
    }

    /**
     * Deserialize a case's Questionnaire + QuestionnaireResponse from JSON fixtures (exercising the real
     * constructor-bypassed deserializer path), run `$extract`, and return only the decoded actual Bundle.
     * For direct-assertion tests that have no vendored oracle to compare against.
     *
     * @return array<string, mixed>
     */
    private function extractOnly(string $case): array
    {
        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4);

        $questionnaire = $serializer->deserializeFromJson(
            $this->fixture($case . '.questionnaire.json'),
            QuestionnaireResource::class,
        );
        $response = $serializer->deserializeFromJson(
            $this->fixture($case . '.response.json'),
            QuestionnaireResponseResource::class,
        );

        $result = (new FHIRQuestionnaireResponseExtractService())
            ->extract($response, new ExtractContext(questionnaire: $questionnaire));

        return $this->normalizeInstants($this->decode($serializer->serializeToJson($result->getResource())));
    }

    /**
     * Normalise a UTC timezone offset (`+00:00` / `-00:00`) to `Z` in `instant`/`dateTime` strings.
     *
     * forms-lab serialises `Observation.issued` (an `instant`) with an explicit `+00:00` offset while
     * this toolkit uses `Z`; both denote the identical instant, so this collapses a spec-legal
     * serialization divergence before comparison. It does NOT touch the actual instant value — a real
     * difference (a different time, or a missing `issued`) still fails.
     *
     * @return array<string, mixed>
     */
    private function normalizeInstants(mixed $value): array
    {
        /** @var array<string, mixed> $walked */
        $walked = $this->walkInstants($value);

        return $walked;
    }

    private function walkInstants(mixed $value): mixed
    {
        if (is_string($value)) {
            return (string) preg_replace(
                '/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?)[+-]00:00$/',
                '$1Z',
                $value,
            );
        }

        if (is_array($value)) {
            $out = [];
            foreach ($value as $key => $element) {
                $out[$key] = $this->walkInstants($element);
            }

            return $out;
        }

        return $value;
    }

    /**
     * Guards against a vacuous oracle: the ignore-list + instant-normalisation must not have made the
     * comparison blind to real content. A mutated expected `code` (LOINC 29463-7 → a bogus code) MUST
     * make {@see assertSdcConformance()} fail against our unchanged actual output.
     */
    public function testOracleComparisonDetectsSemanticDifferences(): void
    {
        if (!is_file(self::EXPECTED_BUNDLE)) {
            self::markTestSkipped('No vendored reference oracle yet.');
        }

        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4);

        $questionnaire = $serializer->deserializeFromJson(
            $this->fixture('observation-extract-basic.questionnaire.json'),
            QuestionnaireResource::class,
        );
        $response = $serializer->deserializeFromJson(
            $this->fixture('observation-extract-basic.response.json'),
            QuestionnaireResponseResource::class,
        );

        $result = (new FHIRQuestionnaireResponseExtractService())
            ->extract($response, new ExtractContext(questionnaire: $questionnaire));
        $actual = $this->normalizeInstants($this->decode($serializer->serializeToJson($result->getResource())));

        $mutated = $this->normalizeInstants($this->decode($this->fixture('observation-extract-basic.expected-bundle.json')));
        // Corrupt a compared field the ignore-list does NOT drop.
        $mutated['entry'][0]['resource']['code']['coding'][0]['code'] = 'BOGUS-9999';

        $this->expectException(ExpectationFailedException::class);
        $this->assertSdcConformance($mutated, $actual);
    }

    private function fixture(string $name): string
    {
        $path = self::FIXTURE_DIR . '/' . $name;
        $json = file_get_contents($path);
        self::assertIsString($json, "Missing fixture: {$path}");

        return $json;
    }

    /**
     * @return array<string, mixed>
     */
    private function decode(string $json): array
    {
        $decoded = json_decode($json, true);
        self::assertIsArray($decoded);

        /** @var array<string, mixed> $decoded */
        return $decoded;
    }
}
