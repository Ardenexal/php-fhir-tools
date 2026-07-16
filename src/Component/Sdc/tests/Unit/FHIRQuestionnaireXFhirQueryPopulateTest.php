<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Sdc\Contract\QueryPopulationDataProviderInterface;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnairePopulateService;
use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;
use Ardenexal\FHIRTools\Component\Sdc\PopulateResult;

/**
 * x-fhir-query itemPopulationContext population (M03).
 *
 * Verifies the opt-in live-fetch path (a {@see QueryPopulationDataProviderInterface} on the context) and,
 * crucially, that the offline default is unchanged when no provider is supplied.
 */
final class FHIRQuestionnaireXFhirQueryPopulateTest extends TestCase
{
    private const string ITEM_POPULATION_CONTEXT_URL =
        'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-itemPopulationContext';

    private const string VARIABLE_URL = 'http://hl7.org/fhir/StructureDefinition/variable';

    public function testXFhirQueryItemPopulationContextRepeatsGroupPerFetchedResource(): void
    {
        $provider = new RecordingQueryProvider([
            $this->patient('A1'),
            $this->patient('A2'),
        ]);

        $result = $this->populate($provider);

        $answers = $this->collectStringAnswers($result->getResponse());
        sort($answers);
        self::assertSame(['A1', 'A2'], $answers, 'The group must repeat once per fetched resource.');

        // The template is resolved offline (%patient.id substituted) before the provider is called.
        self::assertSame('Patient?link=Patient/P1', $provider->lastSearch);
    }

    public function testXFhirQueryVariableBindsFirstResultWithTruncationNotice(): void
    {
        // A `variable` is a single external constant: an x-fhir-query variable that fetches several resources
        // binds the first and records a truncation information issue (matching the FHIRPath variable path).
        $provider = new RecordingQueryProvider([$this->patient('V1'), $this->patient('V2')]);

        $result = (new FHIRQuestionnairePopulateService())->populate(
            $this->variableQuestionnaire(),
            new PopulateContext(
                fhirVersion: FhirVersion::R4,
                launchContextResources: ['patient' => $this->patient('P1')],
                queryProvider: $provider,
            ),
        );

        self::assertSame(['V1'], $this->collectStringAnswers($result->getResponse()), 'A variable binds only the first fetched resource.');
        self::assertSame('Patient?link=Patient/P1', $provider->lastSearch);

        $outcome = $result->getIssues();
        self::assertNotNull($outcome);
        self::assertStringContainsStringIgnoringCase('only the first is bound', $this->issueText($outcome));
    }

    public function testOfflineDefaultSkipsXFhirQueryWithWarningWhenNoProvider(): void
    {
        // No queryProvider → x-fhir-query context is skipped with a warning (offline-first, unchanged).
        $result = (new FHIRQuestionnairePopulateService())->populate(
            $this->questionnaire(),
            new PopulateContext(fhirVersion: FhirVersion::R4, launchContextResources: ['patient' => $this->patient('P1')]),
        );

        self::assertSame([], $this->collectStringAnswers($result->getResponse()), 'Offline: no fetch, group not repeated.');

        $outcome = $result->getIssues();
        self::assertNotNull($outcome);
        self::assertStringContainsStringIgnoringCase('x-fhir-query', $this->issueText($outcome));
    }

    public function testFetchFailureEmitsWarning(): void
    {
        $provider = new RecordingQueryProvider(null); // null = fetch failure (distinct from empty match)

        $result = $this->populate($provider);

        self::assertSame([], $this->collectStringAnswers($result->getResponse()));
        $outcome = $result->getIssues();
        self::assertNotNull($outcome);
        self::assertStringContainsStringIgnoringCase('fetch failed', $this->issueText($outcome));
    }

    private function populate(QueryPopulationDataProviderInterface $provider): PopulateResult
    {
        return (new FHIRQuestionnairePopulateService())->populate(
            $this->questionnaire(),
            new PopulateContext(
                fhirVersion: FhirVersion::R4,
                launchContextResources: ['patient' => $this->patient('P1')],
                queryProvider: $provider,
            ),
        );
    }

    private function questionnaire(): object
    {
        return $this->deserialize(
            json_encode([
                'resourceType' => 'Questionnaire',
                'status'       => 'active',
                'item'         => [[
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
                            'url'             => 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-initialExpression',
                            'valueExpression' => ['language' => 'text/fhirpath', 'expression' => '%match.id'],
                        ]],
                    ]],
                ]],
            ], JSON_THROW_ON_ERROR),
            QuestionnaireResource::class,
        );
    }

    private function variableQuestionnaire(): object
    {
        return $this->deserialize(
            json_encode([
                'resourceType' => 'Questionnaire',
                'status'       => 'active',
                'item'         => [[
                    'linkId'    => 'grp',
                    'type'      => 'group',
                    'extension' => [[
                        'url'             => self::VARIABLE_URL,
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
                            'url'             => 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-initialExpression',
                            'valueExpression' => ['language' => 'text/fhirpath', 'expression' => '%match.id'],
                        ]],
                    ]],
                ]],
            ], JSON_THROW_ON_ERROR),
            QuestionnaireResource::class,
        );
    }

    private function patient(string $id): object
    {
        return $this->deserialize(
            json_encode(['resourceType' => 'Patient', 'id' => $id], JSON_THROW_ON_ERROR),
            PatientResource::class,
        );
    }

    private function deserialize(string $json, string $class): object
    {
        return FHIRSerializationService::createDefault(FhirVersion::R4)->deserializeFromJson($json, $class);
    }

    /**
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

    private function issueText(object $outcome): string
    {
        $text = '';
        foreach ($outcome->issue ?? [] as $issue) {
            $diagnostics = $issue->diagnostics ?? null;
            if (\is_object($diagnostics) && \is_string($diagnostics->value ?? null)) {
                $text .= $diagnostics->value . "\n";
            } elseif (\is_string($diagnostics)) {
                $text .= $diagnostics . "\n";
            }
        }

        return $text;
    }
}

/**
 * Records the resolved search it is asked for and returns a fixed result (or null to simulate a fetch failure).
 */
final class RecordingQueryProvider implements QueryPopulationDataProviderInterface
{
    public ?string $lastSearch = null;

    /**
     * @param list<object>|null $resources
     */
    public function __construct(private readonly ?array $resources)
    {
    }

    public function resourcesForQuery(string $resolvedSearch, string $fhirVersion): ?array
    {
        $this->lastSearch = $resolvedSearch;

        return $this->resources;
    }
}
