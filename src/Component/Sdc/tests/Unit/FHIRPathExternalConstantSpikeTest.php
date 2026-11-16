<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Extension\SafeExtensionReader;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * M01 linchpin spikes for expression-based `$populate`. These two tests gate the whole plan:
 * before any production population code exists, they prove — against **deserializer-origin** model
 * objects, the only origin population ever sees at runtime — that
 *
 *  1. a launch-context resource bound as a FHIRPath external constant (`%patient`) resolves for
 *     *path navigation* (`%patient.name.given`), and
 *  2. `SafeExtensionReader::readValue()` yields the actual `Expression` value carried by an
 *     `sdc-questionnaire-initialExpression` extension, not a silently-dropped null.
 *
 * ## Why deserializer-origin specifically
 *
 * Constructor-bypassed objects (`newInstanceWithoutConstructor`) leave intermediate typed properties
 * uninitialized; the FHIRPath evaluator wraps object reads in `try/catch (\Error)` and degrades to an
 * empty Collection with **no exception** (see the `model-object-initialization` footgun and
 * `FHIRPathEvaluator::navigateProperty`). A programmatically-built Patient navigates fine and would
 * mask exactly that failure — the same trap logged for `$extract` ("programmatic tests mask it"). So
 * every input here is round-tripped through the real {@see FHIRSerializationService} and every
 * assertion is on the literal expected value, never merely `!isEmpty()`: empty-vs-wrong is the point.
 *
 * If either assertion fails, the M01 kill criterion applies — expression-based population is unviable
 * on the current engine and the finding must be escalated, not worked around.
 */
final class FHIRPathExternalConstantSpikeTest extends TestCase
{
    private const string INITIAL_EXPRESSION_URL =
        'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-initialExpression';

    /**
     * RISKY linchpin: a deserialized Patient bound as `%patient` resolves for path navigation.
     */
    public function testExternalConstantResolvesPathNavigationAgainstDeserializedPatient(): void
    {
        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4);

        // Deserializer-origin object: never constructed in-memory, so intermediate typed properties
        // are exactly as population sees them at runtime.
        $patient = $serializer->deserializeFromJson(
            (string) json_encode([
                'resourceType' => 'Patient',
                'name'         => [
                    ['use' => 'official', 'family' => 'Chalmers', 'given' => ['Peter', 'James']],
                ],
            ]),
            PatientResource::class,
        );
        self::assertInstanceOf(PatientResource::class, $patient);

        $context = (new EvaluationContext())->withExternalConstant('patient', $patient);

        // Focus/root is deliberately null: `%patient` MUST resolve from the external constant, not from
        // being the root resource — that is the population binding contract being proven.
        $result = (new FHIRPathService())->evaluate('%patient.name.given', null, $context, 'R4');

        self::assertSame(
            ['Peter', 'James'],
            $result->toArray(),
            'External-constant path navigation returned empty/wrong against a deserialized Patient — '
            . 'M01 kill criterion: expression-based population is unviable on this engine. Escalate.',
        );
    }

    /**
     * RISKY linchpin: `SafeExtensionReader::readValue()` returns the actual `Expression`, guarding
     * against the silent choice-value-drop footgun on a deserialized Questionnaire item.
     */
    public function testGuardedReadReturnsInitialExpressionValueFromDeserializedQuestionnaire(): void
    {
        $serializer = FHIRSerializationService::createDefault(FhirVersion::R4);

        $questionnaire = $serializer->deserializeFromJson(
            (string) json_encode([
                'resourceType' => 'Questionnaire',
                'status'       => 'active',
                'item'         => [
                    [
                        'linkId'    => 'patient-name',
                        'type'      => 'string',
                        'extension' => [
                            [
                                'url'             => self::INITIAL_EXPRESSION_URL,
                                'valueExpression' => [
                                    'language'   => 'text/fhirpath',
                                    'expression' => '%patient.name.given.first()',
                                ],
                            ],
                        ],
                    ],
                ],
            ]),
            QuestionnaireResource::class,
        );
        self::assertInstanceOf(QuestionnaireResource::class, $questionnaire);

        $reader = new SafeExtensionReader();
        $item   = $questionnaire->item[0];

        $initialExpressionExt = null;
        foreach ($item->extension as $ext) {
            if ($reader->readUrl($ext) === self::INITIAL_EXPRESSION_URL) {
                $initialExpressionExt = $ext;
                break;
            }
        }
        self::assertNotNull($initialExpressionExt, 'initialExpression extension not found on the item.');

        $value = $reader->readValue($initialExpressionExt);

        self::assertInstanceOf(
            Expression::class,
            $value,
            'readValue() dropped the valueExpression — the silent choice-value-drop footgun has bitten '
            . 'initialExpression. Guarded reading cannot recover the expression; escalate.',
        );

        $expression = $value->expression;
        $expression = $expression instanceof \Stringable ? (string) $expression : $expression;
        self::assertSame('%patient.name.given.first()', $expression);
    }
}
