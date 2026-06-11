<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
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
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding as R4BCoding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\QuestionnaireItemTypeType as R4BQuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\QuestionnaireResponseStatusType as R4BQuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive as R4BCanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive as R4BCodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive as R4BUriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Questionnaire\QuestionnaireItem as R4BQuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResource as R4BQuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponse\QuestionnaireResponseItem as R4BQuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer as R4BQuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponseResource as R4BQuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding as R5Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireItemTypeType as R5QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireResponseStatusType as R5QuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive as R5CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive as R5CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive as R5UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItem as R5QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource as R5QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItem as R5QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer as R5QuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponseResource as R5QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use PHPUnit\Framework\TestCase;

final class FHIRQuestionnaireValidatorTerminologyTest extends TestCase
{
    private const string VS_URL       = 'http://hl7.org/fhir/ValueSet/answer-vs';

    private const string UNIT_VS_URL  = 'http://hl7.org/fhir/ValueSet/unit-vs';

    private const string UCUM_SYSTEM  = 'http://unitsofmeasure.org';

    private const string LOINC_SYSTEM = 'http://loinc.org';

    private static function response(QuestionnaireResponseItem ...$items): QuestionnaireResponseResource
    {
        return new QuestionnaireResponseResource(
            status: new QuestionnaireResponseStatusType('completed'),
            item: array_values($items),
        );
    }

    private static function codingAnswer(string $system, string $code): QuestionnaireResponseItemAnswer
    {
        return new QuestionnaireResponseItemAnswer(value: new Coding(
            system: new UriPrimitive(value: $system),
            code: new CodePrimitive(value: $code),
        ));
    }

    private static function stringAnswer(string $value): QuestionnaireResponseItemAnswer
    {
        return new QuestionnaireResponseItemAnswer(value: new StringPrimitive(value: $value));
    }

    private static function quantityAnswer(?string $system, ?string $code): QuestionnaireResponseItemAnswer
    {
        return new QuestionnaireResponseItemAnswer(value: new Quantity(
            value: '1.0',
            system: $system !== null ? new UriPrimitive(value: $system) : null,
            code: $code     !== null ? new CodePrimitive(value: $code) : null,
        ));
    }

    private static function unitValueSetExtension(string $url): Extension
    {
        return new Extension(
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-unitValueSet',
            value: new CanonicalPrimitive(value: $url),
        );
    }

    // -------------------------------------------------------------------------
    // choice type — answerValueSet
    // -------------------------------------------------------------------------

    public function testChoiceItemClientReturnsFalseProducesError(): void
    {
        $client = new InMemoryFHIRTerminologyClient([], false);

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('choice'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
        )]);

        $response = self::response(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::codingAnswer(self::LOINC_SYSTEM, 'bad-code')],
        ));

        $report = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors());
        self::assertStringContainsString('bad-code', $report->errors()[0]->message);
        self::assertStringContainsString(self::VS_URL, $report->errors()[0]->message);
    }

    public function testChoiceItemClientReturnsTrueProducesNoViolation(): void
    {
        $client = new InMemoryFHIRTerminologyClient([
            self::VS_URL => [self::LOINC_SYSTEM . '|valid-code' => true],
        ], false);

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('choice'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
        )]);

        $response = self::response(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::codingAnswer(self::LOINC_SYSTEM, 'valid-code')],
        ));

        $report = $validator->validate($questionnaire, $response);

        self::assertEmpty($report->errors());
    }

    // -------------------------------------------------------------------------
    // open-choice type — answerValueSet
    // -------------------------------------------------------------------------

    public function testOpenChoiceCodingAnswerClientReturnsFalseProducesError(): void
    {
        $client = new InMemoryFHIRTerminologyClient([], false);

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('open-choice'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
        )]);

        $response = self::response(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::codingAnswer(self::LOINC_SYSTEM, 'bad-code')],
        ));

        $report = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors());
        self::assertStringContainsString('bad-code', $report->errors()[0]->message);
    }

    public function testOpenChoiceStringPrimitiveAnswerSkipsTerminologyCheck(): void
    {
        $client = new InMemoryFHIRTerminologyClient([], false);

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('open-choice'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
        )]);

        $response = self::response(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('free text answer')],
        ));

        $report = $validator->validate($questionnaire, $response);

        self::assertEmpty($report->errors());
    }

    // -------------------------------------------------------------------------
    // string type — answerValueSet
    // -------------------------------------------------------------------------

    public function testStringItemWithAnswerValueSetClientReturnsFalseProducesError(): void
    {
        $client = new InMemoryFHIRTerminologyClient([], false);

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('string'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
        )]);

        $response = self::response(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('invalid-code')],
        ));

        $report = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors());
        self::assertStringContainsString('invalid-code', $report->errors()[0]->message);
    }

    // -------------------------------------------------------------------------
    // quantity type — unitValueSet
    // -------------------------------------------------------------------------

    public function testQuantityItemWithValidUnitProducesNoViolation(): void
    {
        $client = new InMemoryFHIRTerminologyClient([
            self::UNIT_VS_URL => [self::UCUM_SYSTEM . '|kg' => true],
        ], false);

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('quantity'),
            extension: [self::unitValueSetExtension(self::UNIT_VS_URL)],
        )]);

        $response = self::response(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::quantityAnswer(self::UCUM_SYSTEM, 'kg')],
        ));

        $report = $validator->validate($questionnaire, $response);

        self::assertEmpty($report->errors());
    }

    public function testQuantityItemWithInvalidUnitProducesError(): void
    {
        $client = new InMemoryFHIRTerminologyClient([], false);

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('quantity'),
            extension: [self::unitValueSetExtension(self::UNIT_VS_URL)],
        )]);

        $response = self::response(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::quantityAnswer(self::UCUM_SYSTEM, 'invalid-unit')],
        ));

        $report = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors());
        self::assertStringContainsString('invalid-unit', $report->errors()[0]->message);
    }

    public function testQuantityItemWithMissingCodeProducesError(): void
    {
        $client = new InMemoryFHIRTerminologyClient([], true);

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('quantity'),
            extension: [self::unitValueSetExtension(self::UNIT_VS_URL)],
        )]);

        $response = self::response(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::quantityAnswer(null, null)],
        ));

        $report = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors());
        self::assertStringContainsString('no unit code', $report->errors()[0]->message);
    }

    // -------------------------------------------------------------------------
    // null client — all checks silently skipped
    // -------------------------------------------------------------------------

    public function testNullClientSkipsAllAnswerValueSetChecks(): void
    {
        $validator = new FHIRQuestionnaireValidator(); // no client

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('choice'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
        )]);

        $response = self::response(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::codingAnswer(self::LOINC_SYSTEM, 'anything')],
        ));

        $report = $validator->validate($questionnaire, $response);

        self::assertEmpty($report->errors());
    }

    public function testNullClientSkipsUnitValueSetChecks(): void
    {
        $validator = new FHIRQuestionnaireValidator(); // no client

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('quantity'),
            extension: [self::unitValueSetExtension(self::UNIT_VS_URL)],
        )]);

        $response = self::response(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::quantityAnswer(self::UCUM_SYSTEM, 'anything')],
        ));

        $report = $validator->validate($questionnaire, $response);

        self::assertEmpty($report->errors());
    }

    // -------------------------------------------------------------------------
    // choice type — Coding with no code emits specific error
    // -------------------------------------------------------------------------

    public function testChoiceItemWithNoCodingCodeProducesSpecificError(): void
    {
        $client = new InMemoryFHIRTerminologyClient([], true); // would pass if code were present

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('choice'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
        )]);

        $response = self::response(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: new Coding(
                system: new UriPrimitive(value: self::LOINC_SYSTEM),
            ))], // code intentionally absent
        ));

        $report = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors());
        self::assertStringContainsString('no code', $report->errors()[0]->message);
        self::assertStringContainsString(self::VS_URL, $report->errors()[0]->message);
    }

    // -------------------------------------------------------------------------
    // R4B and R5 Coding branches
    // -------------------------------------------------------------------------

    public function testR4BCodingAnswerClientReturnsFalseProducesError(): void
    {
        $client = new InMemoryFHIRTerminologyClient([], false);

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);

        $questionnaire = new R4BQuestionnaireResource(item: [new R4BQuestionnaireItem(
            linkId: 'q1',
            type: new R4BQuestionnaireItemTypeType('choice'),
            answerValueSet: new R4BCanonicalPrimitive(value: self::VS_URL),
        )]);

        $response = new R4BQuestionnaireResponseResource(
            status: new R4BQuestionnaireResponseStatusType('completed'),
            item: [new R4BQuestionnaireResponseItem(
                linkId: 'q1',
                answer: [new R4BQuestionnaireResponseItemAnswer(value: new R4BCoding(
                    system: new R4BUriPrimitive(value: self::LOINC_SYSTEM),
                    code: new R4BCodePrimitive(value: 'bad-code'),
                ))],
            )],
        );

        $report = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors());
        self::assertStringContainsString('bad-code', $report->errors()[0]->message);
    }

    public function testR5CodingAnswerClientReturnsFalseProducesError(): void
    {
        $client = new InMemoryFHIRTerminologyClient([], false);

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $client);

        $questionnaire = new R5QuestionnaireResource(item: [new R5QuestionnaireItem(
            linkId: 'q1',
            type: new R5QuestionnaireItemTypeType('coding'),
            answerValueSet: new R5CanonicalPrimitive(value: self::VS_URL),
        )]);

        $response = new R5QuestionnaireResponseResource(
            status: new R5QuestionnaireResponseStatusType('completed'),
            item: [new R5QuestionnaireResponseItem(
                linkId: 'q1',
                answer: [new R5QuestionnaireResponseItemAnswer(value: new R5Coding(
                    system: new R5UriPrimitive(value: self::LOINC_SYSTEM),
                    code: new R5CodePrimitive(value: 'bad-code'),
                ))],
            )],
        );

        $report = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors());
        self::assertStringContainsString('bad-code', $report->errors()[0]->message);
    }
}
