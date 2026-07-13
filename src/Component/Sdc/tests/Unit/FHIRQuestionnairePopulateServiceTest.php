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
