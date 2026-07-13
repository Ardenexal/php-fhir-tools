<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Sdc\BundlePopulationDataProvider;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnairePopulateService;
use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;
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
        $answer     = $this->populateWeightAnswer(self::WEIGHT_QUESTIONNAIRE, $bundleJson);

        self::assertNull($answer, 'No code-matching Observation → item omitted.');
    }

    /**
     * Populate the `weight` item and return its first answer as a decoded JSON map, or null when the item
     * was omitted (no answer).
     *
     * @return array<string, mixed>|null
     */
    private function populateWeightAnswer(string $questionnaireJson, ?string $bundleJson = null): ?array
    {
        $serializer    = FHIRSerializationService::createDefault(FhirVersion::R4);
        $questionnaire = $serializer->deserializeFromJson($questionnaireJson, QuestionnaireResource::class);
        $bundle        = $serializer->deserializeFromJson($bundleJson ?? self::DATA_BUNDLE, BundleResource::class);

        $result = (new FHIRQuestionnairePopulateService())->populate(
            $questionnaire,
            new PopulateContext(
                fhirVersion: FhirVersion::R4,
                dataProvider: new BundlePopulationDataProvider($bundle),
            ),
        );

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
}
