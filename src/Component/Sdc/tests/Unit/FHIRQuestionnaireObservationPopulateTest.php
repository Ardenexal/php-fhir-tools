<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Sdc\BundlePopulationDataProvider;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnairePopulateService;
use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;
use Ardenexal\FHIRTools\Component\Sdc\PopulateResult;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * Deterministic coverage for observation-based population (`observationLinkPeriod`), R4.
 *
 * The reference engine (forms-lab) does NOT implement `observationLinkPeriod` (see tests/SOURCES.md), so
 * there is no vendored oracle; this mechanism is verified spec-driven: match `item.code`, filter to
 * eligible statuses within the link period, select the most recent by effective time, populate from
 * `Observation.value[x]`. Inputs are round-tripped through the real serializer (deserializer-origin).
 */
final class FHIRQuestionnaireObservationPopulateTest extends TestCase
{
    private const string WEIGHT_QUESTIONNAIRE = <<<'JSON'
        {
          "resourceType": "Questionnaire",
          "status": "active",
          "url": "http://example.org/Questionnaire/obs-weight",
          "item": [
            {
              "linkId": "weight",
              "type": "quantity",
              "code": [ { "system": "http://loinc.org", "code": "29463-7", "display": "Body Weight" } ],
              "extension": [
                {
                  "url": "http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-observationLinkPeriod",
                  "valuePeriod": { "start": "2020-01-01", "end": "2027-01-01" }
                }
              ]
            }
          ]
        }
        JSON;

    /**
     * A `collection` Bundle with four weight Observations: two eligible `final` results (older 72.5 kg,
     * newer 80 kg), one `preliminary` (excluded by status), and one with a different code (excluded).
     */
    private const string DATA_BUNDLE = <<<'JSON'
        {
          "resourceType": "Bundle",
          "type": "collection",
          "entry": [
            { "resource": { "resourceType": "Observation", "status": "final", "effectiveDateTime": "2024-01-01",
              "code": { "coding": [ { "system": "http://loinc.org", "code": "29463-7" } ] },
              "valueQuantity": { "value": 72.5, "unit": "kg", "system": "http://unitsofmeasure.org", "code": "kg" } } },
            { "resource": { "resourceType": "Observation", "status": "final", "effectiveDateTime": "2025-06-01",
              "code": { "coding": [ { "system": "http://loinc.org", "code": "29463-7" } ] },
              "valueQuantity": { "value": 80, "unit": "kg", "system": "http://unitsofmeasure.org", "code": "kg" } } },
            { "resource": { "resourceType": "Observation", "status": "preliminary", "effectiveDateTime": "2026-01-01",
              "code": { "coding": [ { "system": "http://loinc.org", "code": "29463-7" } ] },
              "valueQuantity": { "value": 999, "unit": "kg" } } },
            { "resource": { "resourceType": "Observation", "status": "final", "effectiveDateTime": "2026-01-01",
              "code": { "coding": [ { "system": "http://loinc.org", "code": "OTHER" } ] },
              "valueQuantity": { "value": 123, "unit": "kg" } } }
          ]
        }
        JSON;

    /**
     * Two eligible `final` weight Observations for DIFFERENT subjects: the older (2024, 72.5 kg) is about
     * `Patient/pat1`; the newer (2025, 80 kg) is about `Patient/other`. Recency alone would pick 80, so a
     * correct subject scope for `pat1` must pick 72.5 — proving the subject filter changes the outcome.
     */
    private const string SUBJECTED_BUNDLE = <<<'JSON'
        {
          "resourceType": "Bundle",
          "type": "collection",
          "entry": [
            { "resource": { "resourceType": "Observation", "status": "final", "effectiveDateTime": "2024-01-01",
              "subject": { "reference": "Patient/pat1" },
              "code": { "coding": [ { "system": "http://loinc.org", "code": "29463-7" } ] },
              "valueQuantity": { "value": 72.5, "unit": "kg", "system": "http://unitsofmeasure.org", "code": "kg" } } },
            { "resource": { "resourceType": "Observation", "status": "final", "effectiveDateTime": "2025-06-01",
              "subject": { "reference": "Patient/other" },
              "code": { "coding": [ { "system": "http://loinc.org", "code": "29463-7" } ] },
              "valueQuantity": { "value": 80, "unit": "kg", "system": "http://unitsofmeasure.org", "code": "kg" } } }
          ]
        }
        JSON;

    public function testSubjectScopeExcludesOtherPatientObservation(): void
    {
        // Without a subject, recency wins → the other patient's 80 kg (proving the filter is what changes
        // the result, not the fixture).
        $unscoped = $this->populateWeightAnswer(self::WEIGHT_QUESTIONNAIRE, self::SUBJECTED_BUNDLE);
        self::assertNotNull($unscoped);
        self::assertEquals(80, $unscoped['valueQuantity']['value']);

        // Scoped to pat1, the newer 80 kg (Patient/other) is excluded and the older 72.5 kg (Patient/pat1)
        // is selected — no wrong-patient value bleeds through.
        $scoped = $this->populateWeightAnswer(self::WEIGHT_QUESTIONNAIRE, self::SUBJECTED_BUNDLE, 'Patient/pat1');
        self::assertNotNull($scoped);
        self::assertEquals(72.5, $scoped['valueQuantity']['value']);
    }

    public function testAllCandidatesWrongSubjectLeavesUnansweredWithWarning(): void
    {
        // Only Patient/other Observations exist; populating for Patient/nobody must yield NO answer and a
        // warning (a code+status match existed but could not be confirmed for the subject) — not a silent
        // wrong-patient populate, and not the softer "nothing matched" information issue.
        $result = $this->populateWeight(self::WEIGHT_QUESTIONNAIRE, self::SUBJECTED_BUNDLE, 'Patient/nobody');

        self::assertNull($this->weightAnswer($result), 'No subject-confirmed Observation → item omitted.');
        self::assertNotNull($result->getIssues());
        self::assertContains('warning', $this->issueSeverities($result->getIssues()));
    }

    public function testSubjectAbsentObservationExcludedWhenSubjectEnforced(): void
    {
        // The default DATA_BUNDLE Observations carry no subject. Enforcing a subject means none can be
        // confirmed to be about pat1, so the item is left unanswered with a warning (strict-exclude).
        $result = $this->populateWeight(self::WEIGHT_QUESTIONNAIRE, self::DATA_BUNDLE, 'Patient/pat1');

        self::assertNull($this->weightAnswer($result), 'A subject-absent Observation is excluded under enforcement.');
        self::assertNotNull($result->getIssues());
        self::assertContains('warning', $this->issueSeverities($result->getIssues()));
    }

    public function testAbsoluteAndVersionedSubjectReferenceStillMatches(): void
    {
        // The Observation subject is an absolute, versioned URL; the requested subject is the relative
        // reference. The Type/id tail comparison must treat them as the same resource.
        $bundle = str_replace('"reference": "Patient/pat1"', '"reference": "http://ex.org/fhir/Patient/pat1/_history/3"', self::SUBJECTED_BUNDLE);

        $answer = $this->populateWeightAnswer(self::WEIGHT_QUESTIONNAIRE, $bundle, 'Patient/pat1');

        self::assertNotNull($answer, 'An absolute/versioned reference to the same resource must match.');
        self::assertEquals(72.5, $answer['valueQuantity']['value']);
    }

    public function testPopulatesMostRecentEligibleMatchingObservation(): void
    {
        $answer = $this->populateWeightAnswer(self::WEIGHT_QUESTIONNAIRE);

        self::assertNotNull($answer, 'The weight item should be populated from a matching Observation.');
        // Newer eligible final Observation (2025, 80 kg) wins over the older (2024, 72.5); the preliminary
        // 999 is excluded by status and the OTHER-coded 123 by code.
        self::assertArrayHasKey('valueQuantity', $answer);
        self::assertEquals(80, $answer['valueQuantity']['value']);
        self::assertSame('kg', $answer['valueQuantity']['unit']);
    }

    public function testDurationWindowAlsoSelectsMostRecent(): void
    {
        // A wide Duration look-back (100 years) includes every Observation, so recency still picks 80 kg —
        // exercises the Duration branch of the link-period window without date fragility.
        $questionnaire = str_replace(
            '"valuePeriod": { "start": "2020-01-01", "end": "2027-01-01" }',
            '"valueDuration": { "value": 100, "unit": "years", "system": "http://unitsofmeasure.org", "code": "a" }',
            self::WEIGHT_QUESTIONNAIRE,
        );

        $answer = $this->populateWeightAnswer($questionnaire);

        self::assertNotNull($answer);
        self::assertEquals(80, $answer['valueQuantity']['value']);
    }

    public function testNoDataProviderLeavesItemUnansweredWithIssue(): void
    {
        $serializer    = FHIRSerializationService::createDefault(FhirVersion::R4);
        $questionnaire = $serializer->deserializeFromJson(self::WEIGHT_QUESTIONNAIRE, QuestionnaireResource::class);

        $result = (new FHIRQuestionnairePopulateService())->populate(
            $questionnaire,
            new PopulateContext(fhirVersion: FhirVersion::R4),
        );

        self::assertSame([], $result->getResponse()->item, 'Without a data provider the item must be omitted.');
        self::assertNotNull($result->getIssues());
    }

    public function testNoCodeMatchLeavesItemUnansweredWithIssue(): void
    {
        // Bundle whose only Observation uses a non-matching code.
        $bundleJson = str_replace('29463-7', 'NO-MATCH', self::DATA_BUNDLE);
        $result     = $this->populateWeight(self::WEIGHT_QUESTIONNAIRE, $bundleJson);

        self::assertNull($this->weightAnswer($result), 'No code-matching Observation → item omitted.');
        // The name promises "WithIssue": the no-match diagnostic must actually be emitted, not just the
        // answer omitted — otherwise a regression that silently drops the OperationOutcome passes.
        self::assertNotNull($result->getIssues(), 'A no-match Observation must record a diagnostic issue.');
        self::assertContains('information', $this->issueSeverities($result->getIssues()));
    }

    public function testUnmappableDurationUnitWidensWindowToUnboundedWithWarning(): void
    {
        // A Duration look-back whose unit is not in the service's UCUM-mappable set ("ms"). The window
        // can't be computed, so it is treated as unbounded — the most recent eligible Observation is still
        // populated (best-effort), but the widening MUST surface a warning so it is not a silent stale read.
        $questionnaire = str_replace(
            '"valuePeriod": { "start": "2020-01-01", "end": "2027-01-01" }',
            '"valueDuration": { "value": 5, "unit": "millisecond", "code": "ms" }',
            self::WEIGHT_QUESTIONNAIRE,
        );

        $result = $this->populateWeight($questionnaire);

        $answer = $this->weightAnswer($result);
        self::assertNotNull($answer, 'Best-effort: the most recent eligible Observation is still populated.');
        self::assertEquals(80, $answer['valueQuantity']['value']);

        self::assertNotNull($result->getIssues(), 'An unmappable Duration unit must be observable.');
        self::assertContains('warning', $this->issueSeverities($result->getIssues()));
    }

    /**
     * Populate the `weight` item and return its first answer as a decoded JSON map, or null when the item
     * was omitted (no answer).
     *
     * @return array<string, mixed>|null
     */
    private function populateWeightAnswer(string $questionnaireJson, ?string $bundleJson = null, ?string $subject = null): ?array
    {
        return $this->weightAnswer($this->populateWeight($questionnaireJson, $bundleJson, $subject));
    }

    /**
     * Populate the `weight` questionnaire against the data Bundle and return the full result (so callers
     * can inspect both the answer and the `OperationOutcome` issues). Pass `$subject` to exercise the
     * subject-scoped selection (only Observations about that subject are eligible).
     */
    private function populateWeight(string $questionnaireJson, ?string $bundleJson = null, ?string $subject = null): PopulateResult
    {
        $serializer    = FHIRSerializationService::createDefault(FhirVersion::R4);
        $questionnaire = $serializer->deserializeFromJson($questionnaireJson, QuestionnaireResource::class);
        $bundle        = $serializer->deserializeFromJson($bundleJson ?? self::DATA_BUNDLE, BundleResource::class);

        return (new FHIRQuestionnairePopulateService())->populate(
            $questionnaire,
            new PopulateContext(
                fhirVersion: FhirVersion::R4,
                subject: $subject,
                dataProvider: new BundlePopulationDataProvider($bundle),
            ),
        );
    }

    /**
     * The `weight` item's first answer as a decoded JSON map, or null when the item was omitted.
     *
     * @return array<string, mixed>|null
     */
    private function weightAnswer(PopulateResult $result): ?array
    {
        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4);

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($serializer->serializeToJson($result->getResponse()), true, 512, JSON_THROW_ON_ERROR);

        foreach ($decoded['item'] ?? [] as $item) {
            if (($item['linkId'] ?? null) === 'weight') {
                $answers = $item['answer'] ?? [];

                return $answers === [] ? null : $answers[0];
            }
        }

        return null;
    }

    /**
     * The severities of an `OperationOutcome`'s issues (empty when null).
     *
     * @return list<string>
     */
    private function issueSeverities(?object $outcome): array
    {
        $severities = [];
        foreach ($outcome->issue ?? [] as $issue) {
            if (\is_object($issue)) {
                $severity = $issue->severity->value ?? null;
                if (\is_string($severity)) {
                    $severities[] = $severity;
                }
            }
        }

        return $severities;
    }
}
