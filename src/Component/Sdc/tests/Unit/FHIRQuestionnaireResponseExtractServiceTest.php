<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationOutcomeResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Sdc\ExtractContext;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnaireResponseExtractService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;

/**
 * Deterministic unit coverage for observation-based `$extract`: asserts the produced transaction
 * Bundle's structure and per-Observation `code`/`value`/`subject`/`derivedFrom` directly (via
 * serialization), independent of the vendored reference oracle.
 */
#[CoversClass(FHIRQuestionnaireResponseExtractService::class)]
final class FHIRQuestionnaireResponseExtractServiceTest extends TestCase
{
    private const string OBSERVATION_EXTRACT_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-observationExtract';

    private FHIRQuestionnaireResponseExtractService $service;

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->service    = new FHIRQuestionnaireResponseExtractService();
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testExtractsObservationFromFlaggedItem(): void
    {
        $questionnaire = new QuestionnaireResource(
            item: [
                new QuestionnaireItem(
                    extension: [new Extension(url: self::OBSERVATION_EXTRACT_URL, value: true)],
                    linkId: 'weight',
                    code: [new Coding(system: new UriPrimitive(value: 'http://loinc.org'), code: new CodePrimitive(value: '29463-7'))],
                ),
            ],
        );

        $response = new QuestionnaireResponseResource(
            id: 'qr1',
            subject: new Reference(reference: 'Patient/patient1'),
            author: new Reference(reference: 'Practitioner/prac1'),
            item: [
                new QuestionnaireResponseItem(
                    linkId: 'weight',
                    answer: [new QuestionnaireResponseItemAnswer(value: new Quantity(value: '72.5', unit: 'kg'))],
                ),
            ],
        );

        $result = $this->service->extract($response, new ExtractContext(questionnaire: $questionnaire));

        $bundle = $this->decode($result->getResource());
        self::assertSame('Bundle', $bundle['resourceType'] ?? null);
        self::assertSame('transaction', $bundle['type'] ?? null);
        self::assertIsArray($bundle['entry'] ?? null);
        self::assertCount(1, $bundle['entry']);

        $entry = $bundle['entry'][0];
        self::assertIsArray($entry);
        self::assertIsString($entry['fullUrl'] ?? null);
        self::assertStringStartsWith('urn:uuid:', $entry['fullUrl']);
        self::assertSame('POST', $entry['request']['method'] ?? null);
        self::assertSame('Observation', $entry['request']['url'] ?? null);

        $observation = $entry['resource'];
        self::assertIsArray($observation);
        self::assertSame('Observation', $observation['resourceType'] ?? null);
        self::assertSame('final', $observation['status'] ?? null);
        self::assertSame('29463-7', $observation['code']['coding'][0]['code'] ?? null);
        self::assertSame('Patient/patient1', $observation['subject']['reference'] ?? null);
        self::assertSame('Practitioner/prac1', $observation['performer'][0]['reference'] ?? null);
        self::assertEquals(72.5, $observation['valueQuantity']['value'] ?? null);
        self::assertSame('kg', $observation['valueQuantity']['unit'] ?? null);
        self::assertSame('QuestionnaireResponse/qr1', $observation['derivedFrom'][0]['reference'] ?? null);

        // Something was extracted -> no informational OperationOutcome.
        self::assertNull($result->getIssues());
    }

    public function testMultipleAnswersProduceMultipleObservations(): void
    {
        $questionnaire = new QuestionnaireResource(
            item: [
                new QuestionnaireItem(
                    extension: [new Extension(url: self::OBSERVATION_EXTRACT_URL, value: true)],
                    linkId: 'symptom',
                    code: [new Coding(code: new CodePrimitive(value: 'symptom'))],
                ),
            ],
        );

        $response = new QuestionnaireResponseResource(
            id: 'qr2',
            item: [
                new QuestionnaireResponseItem(
                    linkId: 'symptom',
                    answer: [
                        new QuestionnaireResponseItemAnswer(value: 'cough'),
                        new QuestionnaireResponseItemAnswer(value: 'fever'),
                    ],
                ),
            ],
        );

        $result = $this->service->extract($response, new ExtractContext(questionnaire: $questionnaire));
        $bundle = $this->decode($result->getResource());

        self::assertCount(2, $bundle['entry'] ?? []);
    }

    public function testItemWithoutExtractFlagIsIgnored(): void
    {
        $questionnaire = new QuestionnaireResource(
            item: [
                new QuestionnaireItem(
                    linkId: 'note',
                    code: [new Coding(code: new CodePrimitive(value: 'note'))],
                ),
            ],
        );

        $response = new QuestionnaireResponseResource(
            item: [
                new QuestionnaireResponseItem(
                    linkId: 'note',
                    answer: [new QuestionnaireResponseItemAnswer(value: 'ignore me')],
                ),
            ],
        );

        $result = $this->service->extract($response, new ExtractContext(questionnaire: $questionnaire));
        $bundle = $this->decode($result->getResource());

        // Empty transaction Bundle + an informational "nothing extracted" OperationOutcome.
        self::assertSame('transaction', $bundle['type'] ?? null);
        self::assertArrayNotHasKey('entry', $bundle);

        $issues = $result->getIssues();
        self::assertInstanceOf(OperationOutcomeResource::class, $issues);
        $outcome = $this->decode($issues);
        self::assertSame('information', $outcome['issue'][0]['severity'] ?? null);
    }

    /**
     * The service is version-generic and input-tolerant: it no longer type-guards its input to an R4
     * QuestionnaireResponse (that guard blocked R4B/R5 parity). An unrecognised object simply yields
     * nothing to extract — an empty transaction Bundle plus an informational "nothing extracted"
     * outcome — rather than throwing.
     */
    public function testUnrecognisedInputYieldsEmptyBundleWithoutThrowing(): void
    {
        $result = $this->service->extract(new \stdClass(), new ExtractContext());

        $bundle = $this->decode($result->getResource());
        self::assertSame('Bundle', $bundle['resourceType'] ?? null);
        self::assertSame('transaction', $bundle['type'] ?? null);
        self::assertSame([], $bundle['entry'] ?? []);

        $issues = $result->getIssues();
        self::assertInstanceOf(OperationOutcomeResource::class, $issues);
    }

    /**
     * Exercises the real deserializer-origin path (constructor-bypassed objects with potentially
     * uninitialized typed properties — the model-init footgun) using the vendored input fixtures the
     * reference oracle will use, asserting the structural contract independently of the frozen Bundle.
     */
    public function testExtractsFromDeserializedInputFixtures(): void
    {
        $dir           = __DIR__ . '/../Fixtures/Extract';
        $questionnaire = $this->serializer->deserializeFromJson(
            (string) file_get_contents($dir . '/observation-extract-basic.questionnaire.json'),
            QuestionnaireResource::class,
        );
        $response = $this->serializer->deserializeFromJson(
            (string) file_get_contents($dir . '/observation-extract-basic.response.json'),
            QuestionnaireResponseResource::class,
        );

        $result = $this->service->extract($response, new ExtractContext(questionnaire: $questionnaire));
        $bundle = $this->decode($result->getResource());

        self::assertSame('transaction', $bundle['type'] ?? null);
        self::assertCount(1, $bundle['entry'] ?? []);

        $observation = $bundle['entry'][0]['resource'] ?? null;
        self::assertIsArray($observation);
        self::assertSame('29463-7', $observation['code']['coding'][0]['code'] ?? null);
        self::assertSame('Patient/example', $observation['subject']['reference'] ?? null);
        self::assertSame('2026-07-09T10:00:00Z', $observation['effectiveDateTime'] ?? null);
        self::assertEquals(72.5, $observation['valueQuantity']['value'] ?? null);
        self::assertSame(
            'QuestionnaireResponse/observation-extract-basic-qr',
            $observation['derivedFrom'][0]['reference'] ?? null,
        );
    }

    /**
     * Definition-based extraction of a Patient from the vendored input fixtures: hierarchical writing
     * (one merged `name`), canonical→class resolution, and the `POST Patient` request directive
     * (which the conformance test's ignore-list drops, so it is asserted here).
     */
    public function testDefinitionBasedExtractionFromFixtures(): void
    {
        $dir           = __DIR__ . '/../Fixtures/Extract';
        $questionnaire = $this->serializer->deserializeFromJson(
            (string) file_get_contents($dir . '/definition-extract-basic.questionnaire.json'),
            QuestionnaireResource::class,
        );
        $response = $this->serializer->deserializeFromJson(
            (string) file_get_contents($dir . '/definition-extract-basic.response.json'),
            QuestionnaireResponseResource::class,
        );

        $result = $this->service->extract($response, new ExtractContext(questionnaire: $questionnaire));
        $bundle = $this->decode($result->getResource());

        self::assertSame('transaction', $bundle['type'] ?? null);
        self::assertCount(1, $bundle['entry'] ?? []);

        $entry = $bundle['entry'][0];
        self::assertIsArray($entry);
        self::assertSame('POST', $entry['request']['method'] ?? null);
        self::assertSame('Patient', $entry['request']['url'] ?? null);

        $patient = $entry['resource'];
        self::assertIsArray($patient);
        self::assertSame('Patient', $patient['resourceType'] ?? null);
        // Hierarchical: given + family land in ONE name element (not one per path).
        self::assertCount(1, $patient['name'] ?? []);
        self::assertSame('Chalmers', $patient['name'][0]['family'] ?? null);
        self::assertSame(['Peter', 'James'], $patient['name'][0]['given'] ?? null);
        self::assertSame('1974-12-25', $patient['birthDate'] ?? null);

        self::assertNull($result->getIssues());
    }

    /**
     * A definition-extracted resource whose logical `id` is written during extraction (here via a hidden
     * item mapped to `Patient.id`, the SDC-recommended mechanism for retaining an id for update) yields a
     * `PUT Type/id` request directive rather than the `POST Type` used for id-less creates. The
     * conformance harness drops `request.url`, so this is the RUNTIME proof of the PUT branch.
     *
     * @see https://build.fhir.org/ig/HL7/sdc/en/extraction.html — POST when no id, PUT (to Type/id) when id present.
     */
    public function testDefinitionExtractWithLogicalIdProducesPutDirective(): void
    {
        $definitionExtractUrl = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-definitionExtract';

        $questionnaire = new QuestionnaireResource(
            item: [
                new QuestionnaireItem(
                    extension: [new Extension(
                        url: $definitionExtractUrl,
                        extension: [new Extension(url: 'definition', value: new UriPrimitive(value: 'http://hl7.org/fhir/StructureDefinition/Patient'))],
                    )],
                    linkId: 'patient',
                    item: [
                        // Hidden item carrying the existing resource id → the entry becomes an update (PUT).
                        new QuestionnaireItem(
                            definition: new UriPrimitive(value: 'http://hl7.org/fhir/StructureDefinition/Patient#Patient.id'),
                            linkId: 'patient-id',
                        ),
                        new QuestionnaireItem(
                            definition: new UriPrimitive(value: 'http://hl7.org/fhir/StructureDefinition/Patient#Patient.name.family'),
                            linkId: 'family',
                        ),
                    ],
                ),
            ],
        );

        $response = new QuestionnaireResponseResource(
            item: [new QuestionnaireResponseItem(
                linkId: 'patient',
                item: [
                    new QuestionnaireResponseItem(linkId: 'patient-id', answer: [new QuestionnaireResponseItemAnswer(value: 'existing-123')]),
                    new QuestionnaireResponseItem(linkId: 'family', answer: [new QuestionnaireResponseItemAnswer(value: 'Chalmers')]),
                ],
            )],
        );

        $result = $this->service->extract($response, new ExtractContext(questionnaire: $questionnaire));
        $bundle = $this->decode($result->getResource());

        self::assertCount(1, $bundle['entry'] ?? []);
        $entry = $bundle['entry'][0];
        self::assertIsArray($entry);
        self::assertSame('PUT', $entry['request']['method'] ?? null, 'A resource with a logical id must be a PUT (update).');
        self::assertSame('Patient/existing-123', $entry['request']['url'] ?? null);

        $patient = $entry['resource'];
        self::assertIsArray($patient);
        self::assertSame('existing-123', $patient['id'] ?? null);
        self::assertSame('Chalmers', $patient['name'][0]['family'] ?? null);
    }

    public function testUnresolvableDefinitionCanonicalReportsIssueWithoutCrashing(): void
    {
        $questionnaire = new QuestionnaireResource(
            item: [
                new QuestionnaireItem(
                    extension: [new Extension(
                        url: 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-definitionExtract',
                        extension: [new Extension(url: 'definition', value: new UriPrimitive(value: 'http://example.org/StructureDefinition/NotARealResource'))],
                    )],
                    linkId: 'root',
                ),
            ],
        );
        $response = new QuestionnaireResponseResource(
            item: [new QuestionnaireResponseItem(linkId: 'root')],
        );

        $result = $this->service->extract($response, new ExtractContext(questionnaire: $questionnaire));
        $bundle = $this->decode($result->getResource());

        self::assertArrayNotHasKey('entry', $bundle);
        $issues = $result->getIssues();
        self::assertInstanceOf(OperationOutcomeResource::class, $issues);
        $outcome = $this->decode($issues);
        self::assertSame('warning', $outcome['issue'][0]['severity'] ?? null);
    }

    public function testMalformedDefinitionExtractValueExpressionReportsIssueWithoutCrashing(): void
    {
        $definitionExtractValueUrl = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-definitionExtractValue';

        $questionnaire = new QuestionnaireResource(
            item: [
                new QuestionnaireItem(
                    extension: [new Extension(
                        url: 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-definitionExtract',
                        extension: [new Extension(url: 'definition', value: new UriPrimitive(value: 'http://hl7.org/fhir/StructureDefinition/Patient'))],
                    )],
                    linkId: 'patient',
                    item: [
                        new QuestionnaireItem(
                            extension: [new Extension(
                                url: $definitionExtractValueUrl,
                                extension: [
                                    new Extension(url: 'definition', value: new UriPrimitive(value: 'http://hl7.org/fhir/StructureDefinition/Patient#Patient.identifier.system')),
                                    // Malformed FHIRPath (unbalanced paren) — must surface an issue, not crash.
                                    new Extension(url: 'expression', value: new Expression(language: 'text/fhirpath', expression: '(1 +')),
                                ],
                            )],
                            linkId: 'mrn',
                        ),
                    ],
                ),
            ],
        );
        $response = new QuestionnaireResponseResource(
            item: [new QuestionnaireResponseItem(
                linkId: 'patient',
                item: [new QuestionnaireResponseItem(linkId: 'mrn')],
            )],
        );

        $result = $this->service->extract($response, new ExtractContext(questionnaire: $questionnaire));

        // Run completes and still produces the Patient entry.
        $bundle = $this->decode($result->getResource());
        self::assertSame('Patient', $bundle['entry'][0]['resource']['resourceType'] ?? null);

        // The malformed expression is reported as a warning issue.
        $issues = $result->getIssues();
        self::assertInstanceOf(OperationOutcomeResource::class, $issues);
        $outcome = $this->decode($issues);
        self::assertSame('warning', $outcome['issue'][0]['severity'] ?? null);
        self::assertStringContainsString('failed to evaluate', (string) ($outcome['issue'][0]['diagnostics'] ?? ''));
    }

    /**
     * A single Questionnaire mixing observation-, definition-, and template-based extraction produces
     * ONE merged transaction Bundle carrying an entry from each method. There is no reference oracle
     * that implements all three methods, so this is a composition test of the service's merge — each
     * method's extraction fidelity is proven separately against its own vendored oracle.
     */
    public function testMixedMethodQuestionnaireYieldsOneMergedBundle(): void
    {
        $dir           = __DIR__ . '/../Fixtures/Extract';
        $questionnaire = $this->serializer->deserializeFromJson(
            (string) file_get_contents($dir . '/extract-mixed-methods.questionnaire.json'),
            QuestionnaireResource::class,
        );
        $response = $this->serializer->deserializeFromJson(
            (string) file_get_contents($dir . '/extract-mixed-methods.response.json'),
            QuestionnaireResponseResource::class,
        );

        $result = $this->service->extract($response, new ExtractContext(questionnaire: $questionnaire));
        $bundle = $this->decode($result->getResource());

        self::assertSame('transaction', $bundle['type'] ?? null);
        $entries = $bundle['entry'] ?? [];
        self::assertIsArray($entries);

        // One Bundle, entries from all three methods: the observation-based Observation (LOINC 29463-7),
        // the definition-based Patient (family Chalmers), and the template-based Observation (the note).
        $byType = [];
        foreach ($entries as $entry) {
            self::assertIsArray($entry);
            $resource = $entry['resource'] ?? [];
            self::assertIsArray($resource);
            $byType[] = $resource['resourceType'] ?? null;
        }

        self::assertContains('Patient', $byType, 'definition-based Patient missing from the merged Bundle');
        self::assertSame(2, array_sum(array_map(static fn ($t): int => $t === 'Observation' ? 1 : 0, $byType)), 'expected exactly two Observations: one observation-based, one template-based');
        self::assertCount(3, $entries, 'the merged Bundle must contain exactly the three extracted resources');

        // The definition-based Patient carries the answered family name.
        $patient = null;
        foreach ($entries as $entry) {
            if (($entry['resource']['resourceType'] ?? null) === 'Patient') {
                $patient = $entry['resource'];
            }
        }
        self::assertIsArray($patient);
        self::assertSame('Chalmers', $patient['name'][0]['family'] ?? null);

        // The template-based Observation carries the note answer substituted via templateExtractValue.
        $noteValues = [];
        foreach ($entries as $entry) {
            if (($entry['resource']['resourceType'] ?? null) === 'Observation') {
                $noteValues[] = $entry['resource']['valueString'] ?? null;
            }
        }
        self::assertContains('Patient reports feeling well.', $noteValues, 'template-based Observation note missing');
    }

    /**
     * With `emitProvenance`, the transaction Bundle carries an extra cardinality-complete `Provenance`
     * entry: `target` references every extracted resource by its shipped `fullUrl`, `entity` (`role =
     * source`) references the source QuestionnaireResponse, and the required `recorded` + `agent.who`
     * are populated. Opt-out by default (asserted by every other test seeing no Provenance).
     */
    public function testProvenanceEntryEmittedWhenRequested(): void
    {
        $questionnaire = new QuestionnaireResource(
            item: [
                new QuestionnaireItem(
                    extension: [new Extension(url: self::OBSERVATION_EXTRACT_URL, value: true)],
                    linkId: 'weight',
                    code: [new Coding(system: new UriPrimitive(value: 'http://loinc.org'), code: new CodePrimitive(value: '29463-7'))],
                ),
            ],
        );
        $response = new QuestionnaireResponseResource(
            id: 'qr1',
            item: [new QuestionnaireResponseItem(
                linkId: 'weight',
                answer: [new QuestionnaireResponseItemAnswer(value: new Quantity(value: '72.5', unit: 'kg'))],
            )],
        );

        $result = $this->service->extract($response, new ExtractContext(questionnaire: $questionnaire, emitProvenance: true));
        $bundle = $this->decode($result->getResource());

        $entries = $bundle['entry'] ?? [];
        self::assertIsArray($entries);
        self::assertCount(2, $entries, 'expected the extracted Observation plus a Provenance entry');

        $observationFullUrl = null;
        $provenance         = null;
        foreach ($entries as $entry) {
            self::assertIsArray($entry);
            $type = $entry['resource']['resourceType'] ?? null;
            if ($type === 'Observation') {
                $observationFullUrl = $entry['fullUrl'] ?? null;
            } elseif ($type === 'Provenance') {
                $provenance = $entry['resource'];
            }
        }

        self::assertIsString($observationFullUrl);
        self::assertIsArray($provenance);

        // target references the extracted resource by the SAME fullUrl the entry ships with.
        self::assertSame($observationFullUrl, $provenance['target'][0]['reference'] ?? null);
        // entity: role = source, what → the source QuestionnaireResponse.
        self::assertSame('source', $provenance['entity'][0]['role'] ?? null);
        self::assertSame('QuestionnaireResponse/qr1', $provenance['entity'][0]['what']['reference'] ?? null);
        // Required cardinality: recorded (instant) + agent.who are present.
        self::assertIsString($provenance['recorded'] ?? null);
        self::assertNotSame('', $provenance['recorded']);
        self::assertNotNull($provenance['agent'][0]['who'] ?? null);
    }

    /**
     * The malformed-expression contract (warning + skip + continue, never crash) holds on the
     * template path too, not just the definition path: a template whose `templateExtractValue` carries
     * broken FHIRPath surfaces a warning and is skipped rather than aborting the run.
     */
    public function testMalformedTemplateExtractValueExpressionReportsIssueWithoutCrashing(): void
    {
        $templateExtractUrl      = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-templateExtract';
        $templateExtractValueUrl = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-templateExtractValue';

        $template = new ObservationResource(
            id: 'obsTmpl',
            status: new ObservationStatusType('final'),
            code: new CodeableConcept(text: 'note'),
            value: new StringPrimitive(
                value: 'placeholder',
                // Malformed FHIRPath (unbalanced paren) on the value's templateExtractValue. Wrapped in a
                // StringPrimitive so it serialises as `valueString` (a raw PHP string guesses `valueDecimal`).
                extension: [new Extension(url: $templateExtractValueUrl, value: new StringPrimitive(value: '(1 +'))],
            ),
        );

        $questionnaire = new QuestionnaireResource(
            contained: [$template],
            item: [
                new QuestionnaireItem(
                    extension: [new Extension(
                        url: $templateExtractUrl,
                        extension: [new Extension(url: 'template', value: new Reference(reference: '#obsTmpl'))],
                    )],
                    linkId: 'note',
                ),
            ],
        );
        $response = new QuestionnaireResponseResource(
            item: [new QuestionnaireResponseItem(
                linkId: 'note',
                answer: [new QuestionnaireResponseItemAnswer(value: 'a note')],
            )],
        );

        // Must not throw.
        $result = $this->service->extract($response, new ExtractContext(questionnaire: $questionnaire));

        $issues = $result->getIssues();
        self::assertInstanceOf(OperationOutcomeResource::class, $issues);
        $outcome    = $this->decode($issues);
        $severities = array_map(static fn (array $i): mixed => $i['severity'] ?? null, $outcome['issue'] ?? []);
        self::assertContains('warning', $severities, 'a malformed templateExtractValue must surface a warning issue');
    }

    /**
     * @return array<string, mixed>
     */
    private function decode(object $resource): array
    {
        $decoded = json_decode($this->serializer->serializeToJson($resource), true);
        self::assertIsArray($decoded);

        /** @var array<string, mixed> $decoded */
        return $decoded;
    }
}
