<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Sdc\ExtractModelFactory;
use Ardenexal\FHIRTools\Component\Sdc\TemplateExtractor;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Unit coverage for {@see TemplateExtractor}'s robustness branches — the "skip with a diagnostic,
 * continue" behaviour that the conformance oracle (a well-formed form) never exercises. Guards that a
 * malformed `templateExtract` never throws, always surfaces a valid diagnostic, and never poisons the
 * sibling templates that do resolve.
 */
final class TemplateExtractorTest extends TestCase
{
    private const string TEMPLATE_EXTRACT_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-templateExtract';

    /** A dangling and a Bundle template are skipped with valid diagnostics while a valid sibling extracts. */
    public function testUnresolvedAndBundleTemplatesAreSkippedWithDiagnosticsWhileValidTemplatesExtract(): void
    {
        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
        $factory    = new ExtractModelFactory(FhirVersion::R4);

        // A Questionnaire with three templateExtract items: one valid (#patTemplate), one dangling
        // (#nonexistent), and one pointing at a Bundle template (templateExtractBundle — deferred).
        $questionnaire = [
            'resourceType' => 'Questionnaire',
            'contained'    => [
                ['resourceType' => 'Patient', 'id' => 'patTemplate', 'gender' => 'male'],
                ['resourceType' => 'Bundle', 'id' => 'bundleTemplate', 'type' => 'collection'],
            ],
            'item' => [
                ['linkId' => 'patient', 'type' => 'group', 'extension' => [$this->templateExtract('#patTemplate')]],
                ['linkId' => 'ghost', 'type' => 'group', 'extension' => [$this->templateExtract('#nonexistent')]],
                ['linkId' => 'bundleitem', 'type' => 'group', 'extension' => [$this->templateExtract('#bundleTemplate')]],
            ],
        ];

        $response = $serializer->deserializeFromJson(
            (string) json_encode([
                'resourceType' => 'QuestionnaireResponse',
                'status'       => 'completed',
                'item'         => [
                    ['linkId' => 'patient'],
                    ['linkId' => 'ghost'],
                    ['linkId' => 'bundleitem'],
                ],
            ]),
            QuestionnaireResponseResource::class,
        );

        $evalContext = new EvaluationContext(rootResource: $response, resourceNode: $response);
        $extractor   = new TemplateExtractor(new FHIRPathService(), new PropertyMetadataProvider());

        /** @var list<object> $issues collected extraction diagnostics */
        $issues  = [];
        $entries = $extractor->extract($questionnaire, $response, $factory, $evalContext, $serializer, $issues);

        // The valid template still extracts, unpoisoned by its malformed siblings.
        self::assertCount(1, $entries, 'The dangling and Bundle templates must not suppress the valid one.');
        $patient = $entries[0]['resource'];
        self::assertInstanceOf(PatientResource::class, $patient);
        self::assertSame('male', $patient->gender->value ?? $patient->gender ?? null);

        // Both malformed templates surfaced a diagnostic (never threw).
        self::assertCount(2, $issues, 'Expected one diagnostic per malformed templateExtract.');
        $outcome     = $serializer->serializeToJson($factory->operationOutcome($issues));
        $decoded     = json_decode($outcome, true);
        self::assertIsArray($decoded);
        $diagnostics = array_map(
            static fn (mixed $issue): string => is_array($issue) && is_string($issue['diagnostics'] ?? null) ? $issue['diagnostics'] : '',
            is_array($decoded['issue'] ?? null) ? $decoded['issue'] : [],
        );

        self::assertNotEmpty(
            array_filter($diagnostics, static fn (string $diagnostic): bool => str_contains($diagnostic, '#nonexistent') && str_contains($diagnostic, 'not found')),
            'Expected a diagnostic for the dangling template reference.',
        );
        self::assertNotEmpty(
            array_filter($diagnostics, static fn (string $diagnostic): bool => str_contains($diagnostic, '#bundleTemplate') && str_contains($diagnostic, 'Bundle')),
            'Expected a diagnostic for the deferred Bundle template.',
        );

        // Every emitted issue carries a valid FHIR issue-type code (guards against a bad literal like
        // "notsupported" which is not in the R4 issue-type value set).
        foreach (is_array($decoded['issue'] ?? null) ? $decoded['issue'] : [] as $issue) {
            self::assertIsArray($issue, 'each OperationOutcome.issue must decode to an object.');
            self::assertContains($issue['code'] ?? null, ['processing', 'invalid', 'security', 'transient', 'informational'], 'issue.code must be a valid R4 issue-type.');
        }
    }

    /**
     * Build a `templateExtract` complex extension pointing at a contained template.
     *
     * @param string $reference the `#id` reference to the contained template
     *
     * @return array<string, mixed> the decoded `templateExtract` extension
     */
    private function templateExtract(string $reference): array
    {
        return [
            'url'       => self::TEMPLATE_EXTRACT_URL,
            'extension' => [
                ['url' => 'template', 'valueReference' => ['reference' => $reference]],
            ],
        ];
    }
}
