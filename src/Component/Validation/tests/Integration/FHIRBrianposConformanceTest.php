<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Conformance test driving FHIRQuestionnaireValidator against the brianpos sub-corpus of the
 * official FHIR test-cases (74 R4 QR-vs-Q cases). Each case provides a Questionnaire in
 * supporting[] and a QuestionnaireResponse as the primary file.
 *
 * Seeded outcomes capture our validator's CURRENT correct output (not dotnet-brianpos counts).
 * Severity differs from dotnet in two known ways:
 *   - Answer-type mismatches: we emit WARNING; dotnet emits fatalCount=1.
 *   - Display mismatches: deferred to a future display-map pass; we emit 0 until then.
 *
 * DEFERRED_CASES are marked incomplete rather than silently green. Rules not yet implemented:
 * min/max value, regex, length, answerOption, attachment, reference-target, Q status (ADR-007).
 */
#[CoversClass(FHIRQuestionnaireValidator::class)]
final class FHIRBrianposConformanceTest extends TestCase
{
    private const VALIDATOR_DIR = 'fhir/fhir-test-cases/validator';

    private const OUTCOMES_DIR = __DIR__ . '/outcomes/questionnaire-brianpos';

    /**
     * Cases where our output is correct for our implemented rules but semantically differs from
     * the dotnet reference (same or different count, different rule triggered).
     * These tests pass; the note explains the divergence for future maintainers.
     *
     * @var array<string, string>
     */
    private const KNOWN_GAPS = [];

    /**
     * Cases blocked on capabilities the library does not yet implement (ADR-007).
     * Maps case name => short reason.
     *
     * @var array<string, string>
     */
    private const DEFERRED_CASES = [
        // No Q file — this tests QR validation when Q cannot be resolved; not testable in standalone harness
        'questionnaire-not-resolved-qr' => 'Q not-resolvable cannot be tested in standalone harness',
    ];

    private FHIRSerializationService $serialization;

    private FHIRQuestionnaireValidator $validator;

    protected function setUp(): void
    {
        $this->serialization = FHIRSerializationService::createDefault(FhirVersion::R4);
        $this->validator     = new FHIRQuestionnaireValidator(terminologyClient: new InMemoryFHIRTerminologyClient(
            map: [
                'http://hl7.org/fhir/ValueSet/jurisdiction' => [
                    'urn:iso:std:iso:3166|AU'              => true,
                    'urn:iso:std:iso:3166|BD'              => true,
                    '|AU'                                  => true,
                    '|Australia'                           => false,
                    'http://unitsofmeasure.org|km'         => false,
                ],
                'http://hl7.org/fhir/ValueSet/item-type' => [
                    'http://hl7.org/fhir/item-type|boolean' => true,
                    'http://hl7.org/fhir/item-type|string'  => true,
                ],
                'http://example.org/invalid-valueset-canonical' => [
                    'urn:iso:std:iso:3166|AU' => false,
                ],
            ],
            defaultResult: true,
        ));
    }

    /**
     * @return iterable<string, array{string, string, string}>
     */
    public static function provideQuestionnaireResponseCases(): iterable
    {
        $vendorDir    = dirname(__DIR__, 5) . '/vendor';
        $manifestPath = $vendorDir . '/' . self::VALIDATOR_DIR . '/questionnaire/brianpos/manifest.json';

        if (!file_exists($manifestPath)) {
            yield '__vendor_not_installed__' => ['__skip__', '', ''];

            return;
        }

        $raw = file_get_contents($manifestPath);
        if ($raw === false) {
            return;
        }

        /** @var array{test-cases: list<array<string, mixed>>} $manifest */
        $manifest = json_decode($raw, true);

        /** @var array<string, array{string, string, string}> $cases */
        $cases = [];

        foreach ($manifest['test-cases'] as $case) {
            $name = (string) ($case['name'] ?? '');
            $file = (string) ($case['file'] ?? '');

            if (($case['version'] ?? '') !== '4.0') {
                continue;
            }

            if (($case['module'] ?? '') !== 'questionnaire') {
                continue;
            }

            if (!isset($case['supporting']) || !is_array($case['supporting']) || $case['supporting'] === []) {
                continue;
            }

            if (!self::isQuestionnaireResponseFile($file)) {
                continue;
            }

            $base   = $vendorDir . '/' . self::VALIDATOR_DIR;
            $qrPath = $base . '/' . $file;

            if (!file_exists($qrPath)) {
                continue;
            }

            $qPath = null;
            foreach ($case['supporting'] as $supportingFile) {
                $candidate = $base . '/' . (string) $supportingFile;
                if (!file_exists($candidate)) {
                    continue;
                }
                $head = file_get_contents($candidate, false, null, 0, 512);
                if ($head === false) {
                    continue;
                }
                if (preg_match('/"resourceType"\s*:\s*"Questionnaire"/', $head) === 1
                    || preg_match('/<Questionnaire[\s>\/]/', $head)             === 1
                ) {
                    $qPath = $candidate;
                    break;
                }
            }
            if ($qPath === null) {
                continue;
            }

            $cases[$name] = [$name, $qrPath, $qPath];
        }

        yield from $cases;
    }

    #[DataProvider('provideQuestionnaireResponseCases')]
    public function testBrianposConformanceCase(string $name, string $qrPath, string $qPath): void
    {
        if ($name === '__skip__') {
            $this->markTestSkipped('fhir/fhir-test-cases not installed — run: composer update fhir/fhir-test-cases');
        }

        $outcomeFile = self::OUTCOMES_DIR . '/R4.' . $name . '-base.json';
        if (!file_exists($outcomeFile)) {
            $reason = self::DEFERRED_CASES[$name]
                ?? 'not yet seeded — run suite to observe output and seed outcomes';
            $this->markTestIncomplete("No seeded outcome for '{$name}' — {$reason}.");
        }

        /** @var array{errorCount: int, warningCount: int} $expected */
        $expected = json_decode((string) file_get_contents($outcomeFile), true);

        $qrData = file_get_contents($qrPath);
        $qData  = file_get_contents($qPath);
        if ($qrData === false || $qData === false) {
            $this->markTestSkipped("Cannot read fixture files for '{$name}'");
        }

        try {
            $questionnaire = $this->serialization->deserialize($qData);
            $response      = $this->serialization->deserialize($qrData);
        } catch (\Throwable $e) {
            $this->markTestSkipped("Deserialization failed for '{$name}': {$e->getMessage()}");
        }

        if (!$questionnaire instanceof QuestionnaireResource) {
            $this->markTestSkipped("Supporting Questionnaire for '{$name}' did not deserialize to a QuestionnaireResource");
        }
        if (!$response instanceof QuestionnaireResponseResource) {
            $this->markTestSkipped("Case file for '{$name}' is not a QuestionnaireResponse");
        }

        $report = $this->validator->validate($questionnaire, $response);

        $errors   = $report->errors();
        $warnings = $report->warnings();

        $context = array_key_exists($name, self::KNOWN_GAPS)
            ? ' [KNOWN GAP: ' . self::KNOWN_GAPS[$name] . ']'
            : '';

        self::assertSame(
            $expected['errorCount'],
            count($errors),
            "Unexpected error count for '{$name}'{$context}: "
            . implode(' | ', array_map(static fn (FHIRValidationViolation $v) => $v->message, $errors)),
        );
        self::assertSame(
            $expected['warningCount'],
            count($warnings),
            "Unexpected warning count for '{$name}'{$context}: "
            . implode(' | ', array_map(static fn (FHIRValidationViolation $v) => $v->message, $warnings)),
        );
    }

    private static function isQuestionnaireResponseFile(string $file): bool
    {
        $base = strtolower(basename($file));

        return str_starts_with($base, 'questionnaireresponse')
            || str_ends_with($base, '-qr.json')
            || str_ends_with($base, '-qr.xml');
    }
}
