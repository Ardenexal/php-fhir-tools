<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use PHPUnit\Framework\TestCase;

final class FHIRQuestionnaireValidatorDisplayTest extends TestCase
{
    private const string VS_URL = 'http://hl7.org/fhir/ValueSet/answer-vs';

    private const string SYSTEM = 'http://loinc.org';

    private const string CODE = '8867-4';

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private static function singleItemQuestionnaire(string $type): QuestionnaireResource
    {
        return new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType($type),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
        )]);
    }

    private static function responseWithCoding(string $system, string $code, ?string $display): QuestionnaireResponseResource
    {
        return new QuestionnaireResponseResource(
            status: new QuestionnaireResponseStatusType('completed'),
            item: [new QuestionnaireResponseItem(
                linkId: 'q1',
                answer: [new QuestionnaireResponseItemAnswer(value: new Coding(
                    system: new UriPrimitive(value: $system),
                    code: new CodePrimitive(value: $code),
                    display: $display,
                ))],
            )],
        );
    }

    private static function responseWithStringAnswer(string $value): QuestionnaireResponseResource
    {
        return new QuestionnaireResponseResource(
            status: new QuestionnaireResponseStatusType('completed'),
            item: [new QuestionnaireResponseItem(
                linkId: 'q1',
                answer: [new QuestionnaireResponseItemAnswer(value: new StringPrimitive(value: $value))],
            )],
        );
    }

    // -------------------------------------------------------------------------
    // Display-bearing Codings: mismatch → warning
    // -------------------------------------------------------------------------

    public function testCodingWithWrongDisplayEmitsWarningWhenCodeIsValid(): void
    {
        $client = new InMemoryFHIRTerminologyClient(
            map: [self::VS_URL => [self::SYSTEM . '|' . self::CODE => true]],
            displayMap: [self::VS_URL => [self::SYSTEM . '|' . self::CODE => 'Heart rate']],
        );

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);
        $q         = self::singleItemQuestionnaire('choice');
        $qr        = self::responseWithCoding(self::SYSTEM, self::CODE, 'wrong display');
        $outcome   = $validator->validate($q, $qr);

        $violations = $outcome->violations;
        self::assertCount(1, $violations);
        self::assertSame('warning', $violations[0]->severity);
        self::assertStringContainsString('wrong display', $violations[0]->message);
        self::assertStringContainsString('Heart rate', $violations[0]->message);
    }

    public function testCodingWithCorrectDisplayEmitsNoWarning(): void
    {
        $client = new InMemoryFHIRTerminologyClient(
            map: [self::VS_URL => [self::SYSTEM . '|' . self::CODE => true]],
            displayMap: [],
        );

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);
        $q         = self::singleItemQuestionnaire('choice');
        $qr        = self::responseWithCoding(self::SYSTEM, self::CODE, 'Heart rate');
        $outcome   = $validator->validate($q, $qr);

        self::assertEmpty($outcome->violations);
    }

    // -------------------------------------------------------------------------
    // Display-bearing Codings: invalid code → error (not warning)
    // -------------------------------------------------------------------------

    public function testCodingWithDisplayAndInvalidCodeEmitsError(): void
    {
        $client = new InMemoryFHIRTerminologyClient(
            map: [self::VS_URL => [self::SYSTEM . '|bad' => false]],
            displayMap: [],
        );

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);
        $q         = self::singleItemQuestionnaire('choice');
        $qr        = self::responseWithCoding(self::SYSTEM, 'bad', 'Some display');
        $outcome   = $validator->validate($q, $qr);

        $violations = $outcome->violations;
        self::assertCount(1, $violations);
        self::assertSame('error', $violations[0]->severity);
    }

    // -------------------------------------------------------------------------
    // Codings without display: validateCoding() used (no display check)
    // -------------------------------------------------------------------------

    public function testCodingWithoutDisplayUsesValidateCodingNotWithDisplay(): void
    {
        // map has no display map entry — if validateCodingWithDisplay were called
        // and misread the absence as a mismatch, we'd get a spurious warning.
        // This test confirms no warning fires when display is absent.
        $client = new InMemoryFHIRTerminologyClient(
            map: [self::VS_URL => [self::SYSTEM . '|' . self::CODE => true]],
        );

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);
        $q         = self::singleItemQuestionnaire('choice');
        $qr        = self::responseWithCoding(self::SYSTEM, self::CODE, null);
        $outcome   = $validator->validate($q, $qr);

        self::assertEmpty($outcome->violations);
    }

    public function testCodingWithoutDisplayAndInvalidCodeEmitsError(): void
    {
        $client = new InMemoryFHIRTerminologyClient(
            map: [self::VS_URL => [self::SYSTEM . '|bad' => false]],
        );

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);
        $q         = self::singleItemQuestionnaire('choice');
        $qr        = self::responseWithCoding(self::SYSTEM, 'bad', null);
        $outcome   = $validator->validate($q, $qr);

        $violations = $outcome->violations;
        self::assertCount(1, $violations);
        self::assertSame('error', $violations[0]->severity);
    }

    // -------------------------------------------------------------------------
    // open-choice: StringPrimitive answer — no display check
    // -------------------------------------------------------------------------

    public function testOpenChoiceStringAnswerIsNotSubjectToDisplayCheck(): void
    {
        $client = new InMemoryFHIRTerminologyClient(
            map: [self::VS_URL => ['|somestring' => true]],
            displayMap: [],
        );

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);
        $q         = self::singleItemQuestionnaire('open-choice');
        $qr        = self::responseWithStringAnswer('somestring');
        $outcome   = $validator->validate($q, $qr);

        self::assertEmpty($outcome->violations);
    }

    // -------------------------------------------------------------------------
    // Null client → no violations (unchanged backward-compat)
    // -------------------------------------------------------------------------

    public function testNullClientProducesNoViolations(): void
    {
        $validator = new FHIRQuestionnaireValidator();
        $q         = self::singleItemQuestionnaire('choice');
        $qr        = self::responseWithCoding(self::SYSTEM, self::CODE, 'any display');
        $outcome   = $validator->validate($q, $qr);

        self::assertEmpty($outcome->violations);
    }
}
