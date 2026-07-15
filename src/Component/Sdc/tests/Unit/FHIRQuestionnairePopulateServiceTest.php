<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\PatientResource as R5PatientResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource as R5QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnairePopulateService;
use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireResolverInterface;
use PHPUnit\Framework\TestCase;
use Ardenexal\FHIRTools\Component\Sdc\PopulateResult;
use Ardenexal\FHIRTools\Component\Sdc\Tests\Integration\Populate\FHIRPopulateConformanceTest;

/**
 * Deterministic unit coverage for {@see FHIRQuestionnairePopulateService} (M01, R4).
 *
 * Complements the reference-seeded {@see FHIRPopulateConformanceTest}
 * (which structurally compares against the frozen forms-lab oracle) by asserting the behaviours the
 * oracle comparison deliberately ignores or cannot show: `subject` being set (the oracle omits it), the
 * `StringPrimitive` answer wrapper (→ `valueString`, not `valueDecimal`), and the observable
 * `OperationOutcome` issues for empty / unbound launch-context expressions.
 *
 * Every input is round-tripped through the real {@see FHIRSerializationService}, so the service is
 * exercised against deserializer-origin objects — the only origin it sees at runtime.
 */
final class FHIRQuestionnairePopulateServiceTest extends TestCase
{
    private const string FIXTURE_DIR = __DIR__ . '/../Fixtures/Populate';

    public function testSetsSubjectAndWrapsStringAnswerAsStringPrimitive(): void
    {
        $result = $this->populate($this->patientFixture());

        $response = $result->getResponse();
        self::assertInstanceOf(QuestionnaireResponseResource::class, $response);

        // subject — asserted directly here because the conformance oracle drops it (forms-lab omits it).
        self::assertNotNull($response->subject);
        self::assertSame('Patient/patient-spike', $this->stringify($response->subject->reference ?? null));

        self::assertSame('in-progress', $this->stringify($response->status?->value ?? null));

        // First item's answer MUST be a StringPrimitive so the choice serializes as valueString, not the
        // sibling `decimal` variant (also phpType string). This is the coercion the conformance test proves
        // end-to-end; asserted here at the model level too.
        $answerValue = $response->item[0]->answer[0]->value ?? null;
        self::assertInstanceOf(StringPrimitive::class, $answerValue);
        self::assertSame('Peter', $answerValue->value);
    }

    public function testBoundButEmptyExpressionEmitsInformationIssueAndOmitsItem(): void
    {
        // Patient bound as %patient but carrying no name: `%patient.name.first().given.first()` resolves
        // to empty (not an error). The item must be omitted and an information issue raised.
        $result = $this->populate($this->deserialize('{"resourceType":"Patient","id":"nameless"}', PatientResource::class));

        $response = $result->getResponse();
        self::assertInstanceOf(QuestionnaireResponseResource::class, $response);
        self::assertSame([], $response->item, 'Items with no answer and no answered descendant must be omitted.');

        $issues = $result->getIssues();
        self::assertNotNull($issues, 'A bound-but-empty launch-context expression must be observable.');
        self::assertContains('information', $this->issueSeverities($issues));
    }

    public function testMissingLaunchContextDegradesGracefullyWithoutThrowing(): void
    {
        // No launch-context resource supplied at all: the declared `patient` context is missing, and each
        // `%patient` expression is unbound (raises) — the service must NOT crash; it degrades to issues.
        $service = new FHIRQuestionnairePopulateService();
        $result  = $service->populate(
            $this->questionnaireFixture(),
            new PopulateContext(fhirVersion: FhirVersion::R4, launchContextResources: [], subject: null),
        );

        $issues = $result->getIssues();
        self::assertNotNull($issues);
        $severities = $this->issueSeverities($issues);
        self::assertContains('information', $severities, 'Missing declared launchContext should raise an information issue.');
        self::assertContains('warning', $severities, 'An unbound %patient expression should degrade to a warning, not throw.');
    }

    public function testResolvesCanonicalUrlViaResolverAndPopulates(): void
    {
        $r5 = FHIRSerializationService::createDefault(FhirVersion::R5);

        $questionnaire = $r5->deserializeFromJson(
            $this->fixture('populate-launchcontext-initial.questionnaire.json'),
            R5QuestionnaireResource::class,
        );
        self::assertInstanceOf(R5QuestionnaireResource::class, $questionnaire);

        $patient = $r5->deserializeFromJson(
            $this->fixture('populate-launchcontext-initial.patient.json'),
            R5PatientResource::class,
        );

        $resolver = new class ($questionnaire) implements FHIRQuestionnaireResolverInterface {
            public function __construct(private readonly R5QuestionnaireResource $questionnaire)
            {
            }

            public function resolve(string $canonicalUrl): ?R5QuestionnaireResource
            {
                return $canonicalUrl === 'http://example.org/Questionnaire/populate-spike' ? $this->questionnaire : null;
            }
        };

        $service = new FHIRQuestionnairePopulateService(questionnaireResolver: $resolver);
        $result  = $service->populate(
            'http://example.org/Questionnaire/populate-spike',
            new PopulateContext(fhirVersion: FhirVersion::R5, launchContextResources: ['patient' => $patient]),
        );

        // Canonical resolved → R5 QR populated from the R5 launch Patient (this also exercises the R5 path).
        $answerValue = $result->getResponse()->item[0]->answer[0]->value ?? null;
        self::assertSame('Peter', $this->stringify($answerValue));
    }

    public function testUnresolvableCanonicalUrlWithoutResolverDegradesToWarning(): void
    {
        $service = new FHIRQuestionnairePopulateService();
        $result  = $service->populate(
            'http://example.org/Questionnaire/does-not-exist',
            new PopulateContext(fhirVersion: FhirVersion::R4),
        );

        $response = $result->getResponse();
        self::assertInstanceOf(QuestionnaireResponseResource::class, $response);
        self::assertSame([], $response->item, 'An unresolvable canonical must yield an empty QR, not a crash.');

        $issues = $result->getIssues();
        self::assertNotNull($issues);
        self::assertContains('warning', $this->issueSeverities($issues));
    }

    public function testMalformedExpressionEmitsInvalidWarningAndContinuesPopulation(): void
    {
        // Two string items: the first carries a syntactically-broken FHIRPath expression, the second a valid
        // literal. A malformed expression must degrade to a warning (code `invalid`) without aborting the run,
        // so the second item still populates.
        $questionnaire = $this->questionnaire([
            $this->stringItemArray('broken', 'foo('),
            $this->stringItemArray('ok', "'value'"),
        ]);

        $result   = $this->populateQuestionnaire($questionnaire);
        $response = $result->getResponse();
        self::assertInstanceOf(QuestionnaireResponseResource::class, $response);

        // The valid item still produced its answer despite the sibling's broken expression.
        self::assertCount(1, $response->item);
        self::assertSame('ok', $this->stringify($response->item[0]->linkId ?? null));
        self::assertSame('value', $this->stringify($response->item[0]->answer[0]->value ?? null));

        $issues = $result->getIssues();
        self::assertNotNull($issues);
        self::assertContains('warning', $this->issueSeverities($issues));
        self::assertContains('invalid', $this->issueCodes($issues), 'A malformed expression must report code `invalid`.');
    }

    public function testEmptyStringResultOmitsAnswerAsNotAnsweredNotMismatch(): void
    {
        // A string item whose initialExpression resolves to '' (a present but empty string) must be treated
        // as "not answered" — no answer entry, and an *information* issue, never an incompatible-type warning.
        $questionnaire = $this->questionnaire([$this->stringItemArray('empty', "''")]);

        $result   = $this->populateQuestionnaire($questionnaire);
        $response = $result->getResponse();
        self::assertInstanceOf(QuestionnaireResponseResource::class, $response);
        self::assertSame([], $response->item, 'An empty-string result must omit the item, not emit valueString "".');

        $issues = $result->getIssues();
        self::assertNotNull($issues);
        $severities = $this->issueSeverities($issues);
        self::assertContains('information', $severities);
        self::assertNotContains('warning', $severities, 'An empty string is not a type mismatch.');
    }

    public function testBooleanEmptyExpressionIsNotCoercedToFalse(): void
    {
        // A boolean item whose initialExpression returns the empty collection must be left unanswered — the
        // spec's "empty set = not answered" rule; it must NOT be coerced to `valueBoolean: false`.
        $questionnaire = $this->questionnaire([$this->itemArray('flag', 'boolean', '{}')]);

        $result   = $this->populateQuestionnaire($questionnaire);
        $response = $result->getResponse();
        self::assertInstanceOf(QuestionnaireResponseResource::class, $response);
        self::assertSame([], $response->item, 'An empty boolean result must be omitted, not set to false.');

        $issues = $result->getIssues();
        self::assertNotNull($issues);
        self::assertContains('information', $this->issueSeverities($issues));
    }

    public function testItemWithoutLinkIdIsDroppedWithWarning(): void
    {
        // An item carrying no linkId cannot be represented in the QuestionnaireResponse. It is skipped, but
        // the drop must be observable: a warning (code `invalid`), not a silent disappearance.
        $questionnaire = $this->deserialize(
            '{"resourceType":"Questionnaire","status":"active","item":[{"type":"string"}]}',
            QuestionnaireResource::class,
        );

        $result   = $this->populateQuestionnaire($questionnaire);
        $response = $result->getResponse();
        self::assertInstanceOf(QuestionnaireResponseResource::class, $response);
        self::assertSame([], $response->item);

        $issues = $result->getIssues();
        self::assertNotNull($issues, 'A null-linkId drop must be observable.');
        self::assertContains('warning', $this->issueSeverities($issues));
        self::assertContains('invalid', $this->issueCodes($issues));
    }

    public function testEmptyItemPopulationContextEmitsInformationIssueAndOmitsGroup(): void
    {
        // A group whose itemPopulationContext resolves to nothing omits the whole group and its descendants.
        // That omission must be observable — an information issue — not silent.
        $questionnaire = $this->deserialize(
            json_encode([
                'resourceType' => 'Questionnaire',
                'status'       => 'active',
                'item'         => [[
                    'linkId'    => 'grp',
                    'type'      => 'group',
                    'extension' => [$this->itemPopulationContextExtension('ctx', '{}')],
                    'item'      => [$this->stringItemArray('child', "'x'")],
                ]],
            ], JSON_THROW_ON_ERROR),
            QuestionnaireResource::class,
        );

        $result   = $this->populateQuestionnaire($questionnaire);
        $response = $result->getResponse();
        self::assertInstanceOf(QuestionnaireResponseResource::class, $response);
        self::assertSame([], $response->item, 'An empty itemPopulationContext must omit the group.');

        $issues = $result->getIssues();
        self::assertNotNull($issues, 'An empty-context group omission must be observable.');
        self::assertContains('information', $this->issueSeverities($issues));
    }

    public function testNestedRepeatingGroupsPopulate(): void
    {
        // itemPopulationContext nested inside itemPopulationContext: the outer repeats per Patient.name, the
        // inner repeats per that name's given[]. Every given across every name must produce a leaf answer.
        $patient = $this->deserialize(
            '{"resourceType":"Patient","name":[' .
            '{"family":"Smith","given":["John","Jack"]},' .
            '{"family":"Doe","given":["Jane"]}]}',
            PatientResource::class,
        );

        $questionnaire = $this->deserialize(
            json_encode([
                'resourceType' => 'Questionnaire',
                'status'       => 'active',
                'item'         => [[
                    'linkId'    => 'names',
                    'type'      => 'group',
                    'extension' => [$this->itemPopulationContextExtension('nm', '%patient.name')],
                    'item'      => [[
                        'linkId'    => 'givens',
                        'type'      => 'group',
                        'extension' => [$this->itemPopulationContextExtension('gv', '%nm.given')],
                        'item'      => [$this->stringItemArray('given', '%gv')],
                    ]],
                ]],
            ], JSON_THROW_ON_ERROR),
            QuestionnaireResource::class,
        );

        $result = (new FHIRQuestionnairePopulateService())->populate(
            $questionnaire,
            new PopulateContext(fhirVersion: FhirVersion::R4, launchContextResources: ['patient' => $patient]),
        );

        $answers = $this->collectStringAnswers($result->getResponse());
        sort($answers);
        self::assertSame(['Jack', 'Jane', 'John'], $answers, 'Each given across each name must populate a leaf answer.');
    }

    private function populateQuestionnaire(object $questionnaire): PopulateResult
    {
        return (new FHIRQuestionnairePopulateService())->populate(
            $questionnaire,
            new PopulateContext(fhirVersion: FhirVersion::R4),
        );
    }

    /**
     * Deserialize a Questionnaire built from an array of item arrays.
     *
     * @param list<array<string, mixed>> $items
     */
    private function questionnaire(array $items): object
    {
        return $this->deserialize(
            json_encode(['resourceType' => 'Questionnaire', 'status' => 'active', 'item' => $items], JSON_THROW_ON_ERROR),
            QuestionnaireResource::class,
        );
    }

    /**
     * A Questionnaire item as a plain array carrying a string-typed `initialExpression`.
     *
     * @return array<string, mixed>
     */
    private function stringItemArray(string $linkId, string $expression): array
    {
        return $this->itemArray($linkId, 'string', $expression);
    }

    /**
     * @return array<string, mixed>
     */
    private function itemArray(string $linkId, string $type, string $expression): array
    {
        return [
            'linkId'    => $linkId,
            'type'      => $type,
            'extension' => [[
                'url'             => 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-initialExpression',
                'valueExpression' => ['language' => 'text/fhirpath', 'expression' => $expression],
            ]],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function itemPopulationContextExtension(string $name, string $expression): array
    {
        return [
            'url'             => 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-itemPopulationContext',
            'valueExpression' => ['name' => $name, 'language' => 'text/fhirpath', 'expression' => $expression],
        ];
    }

    /**
     * Every string answer value anywhere in a QuestionnaireResponse item tree.
     *
     * @return list<string>
     */
    private function collectStringAnswers(object $response): array
    {
        $collect = static function(array $items, callable $self): array {
            $out = [];
            foreach ($items as $item) {
                foreach ($item->answer ?? [] as $answer) {
                    $value = $answer->value ?? null;
                    if (\is_object($value) && property_exists($value, 'value') && \is_string($value->value ?? null)) {
                        $out[] = $value->value;
                    }
                }
                $out = [...$out, ...$self($item->item ?? [], $self)];
            }

            return $out;
        };

        return $collect($response->item ?? [], $collect);
    }

    /**
     * @return list<string>
     */
    private function issueCodes(object $outcome): array
    {
        $codes = [];
        foreach ($outcome->issue ?? [] as $issue) {
            if (\is_object($issue)) {
                $code = $this->stringify($issue->code->value ?? null);
                if ($code !== null) {
                    $codes[] = $code;
                }
            }
        }

        return $codes;
    }

    private function populate(object $patient): PopulateResult
    {
        return (new FHIRQuestionnairePopulateService())->populate(
            $this->questionnaireFixture(),
            new PopulateContext(
                fhirVersion: FhirVersion::R4,
                launchContextResources: ['patient' => $patient],
                subject: 'Patient/patient-spike',
            ),
        );
    }

    private function questionnaireFixture(): object
    {
        return $this->deserialize(
            $this->fixture('populate-launchcontext-initial.questionnaire.json'),
            QuestionnaireResource::class,
        );
    }

    private function patientFixture(): object
    {
        return $this->deserialize(
            $this->fixture('populate-launchcontext-initial.patient.json'),
            PatientResource::class,
        );
    }

    /**
     * @param class-string $class
     */
    private function deserialize(string $json, string $class): object
    {
        return FHIRSerializationService::createDefault(FhirVersion::R4)->deserializeFromJson($json, $class);
    }

    /**
     * @return list<string>
     */
    private function issueSeverities(object $outcome): array
    {
        $severities = [];
        foreach ($outcome->issue ?? [] as $issue) {
            if (\is_object($issue)) {
                $severity = $this->stringify($issue->severity->value ?? null);
                if ($severity !== null) {
                    $severities[] = $severity;
                }
            }
        }

        return $severities;
    }

    private function stringify(mixed $value): ?string
    {
        if ($value instanceof \BackedEnum) {
            return (string) $value->value;
        }
        if (\is_string($value)) {
            return $value;
        }
        if (\is_object($value) && property_exists($value, 'value')) {
            $inner = $value->value ?? null;

            return $inner instanceof \BackedEnum ? (string) $inner->value : (\is_string($inner) ? $inner : null);
        }

        return null;
    }

    private function fixture(string $name): string
    {
        $contents = file_get_contents(self::FIXTURE_DIR . '/' . $name);
        self::assertIsString($contents);

        return $contents;
    }
}
