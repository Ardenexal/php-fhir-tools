<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemOperatorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItemEnableWhen;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Expression as R5Expression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension as R5Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireItemTypeType as R5QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireResponseStatusType as R5QuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItem as R5QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource as R5QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItem as R5QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer as R5QuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponseResource as R5QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationReport;
use PHPUnit\Framework\TestCase;

/**
 * SDC calculatedExpression (#92): a read-only item's answers must equal the result of its
 * calculated expression, evaluated with in-scope `variable` extensions bound. Anything that
 * cannot be evaluated with confidence is skipped rather than reported.
 */
final class FHIRQuestionnaireValidatorCalculatedExpressionTest extends TestCase
{
    private const string CALCULATED = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-calculatedExpression';

    private const string VARIABLE = 'http://hl7.org/fhir/StructureDefinition/variable';

    private FHIRQuestionnaireValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new FHIRQuestionnaireValidator();
    }

    private static function calculated(string $expression, string $language = 'text/fhirpath'): Extension
    {
        return new Extension(url: self::CALCULATED, value: new Expression(language: $language, expression: $expression));
    }

    /** FHIRPath reading an item's answer values from anywhere in the response. */
    private static function answerOf(string $linkId): string
    {
        return "%resource.repeat(item).where(linkId='" . $linkId . "').answer.value";
    }

    private static function variable(string $name, string $expression): Extension
    {
        return new Extension(url: self::VARIABLE, value: new Expression(
            name: new IdPrimitive(value: $name),
            language: 'text/fhirpath',
            expression: $expression,
        ));
    }

    private static function integerItem(string $linkId): QuestionnaireItem
    {
        return new QuestionnaireItem(linkId: $linkId, type: new QuestionnaireItemTypeType('integer'));
    }

    /**
     * A read-only integer item 'total' (readOnly unless $readOnly is false) carrying the given
     * extensions.
     *
     * @param list<Extension> $extensions
     */
    private static function totalItem(array $extensions, bool $readOnly = true): QuestionnaireItem
    {
        return new QuestionnaireItem(
            linkId: 'total',
            type: new QuestionnaireItemTypeType('integer'),
            readOnly: $readOnly,
            extension: $extensions,
        );
    }

    private static function integerAnswer(string $linkId, int ...$values): QuestionnaireResponseItem
    {
        return new QuestionnaireResponseItem(
            linkId: $linkId,
            answer: array_map(static fn (int $value): QuestionnaireResponseItemAnswer => new QuestionnaireResponseItemAnswer(value: $value), array_values($values)),
        );
    }

    private static function response(QuestionnaireResponseItem ...$items): QuestionnaireResponseResource
    {
        return new QuestionnaireResponseResource(
            status: new QuestionnaireResponseStatusType('completed'),
            item: array_values($items),
        );
    }

    /**
     * Questionnaire with integer items 'a' and 'b' plus a read-only 'total' calculated as a + b.
     *
     * @param list<Extension> $rootExtensions
     */
    private static function sumQuestionnaire(?Extension $calculation = null, array $rootExtensions = [], bool $readOnly = true): QuestionnaireResource
    {
        $calculation ??= self::calculated(self::answerOf('a') . ' + ' . self::answerOf('b'));

        return new QuestionnaireResource(
            extension: $rootExtensions,
            item: [self::integerItem('a'), self::integerItem('b'), self::totalItem([$calculation], $readOnly)],
        );
    }

    private static function assertCalculationError(FHIRValidationReport $report, string $path, string $messagePart): void
    {
        $matches = array_values(array_filter(
            $report->errors(),
            static fn ($error): bool => str_contains($error->message, 'calculatedExpression'),
        ));

        self::assertCount(1, $matches);
        self::assertSame($path, $matches[0]->path);
        self::assertStringContainsString($messagePart, $matches[0]->message);
    }

    public function testMatchingCalculatedAnswerIsValid(): void
    {
        $report = $this->validator->validate(
            self::sumQuestionnaire(),
            self::response(self::integerAnswer('a', 2), self::integerAnswer('b', 3), self::integerAnswer('total', 5)),
        );

        self::assertCount(0, $report->errors());
    }

    public function testMismatchedCalculatedAnswerOnReadOnlyItemIsAnError(): void
    {
        $report = $this->validator->validate(
            self::sumQuestionnaire(),
            self::response(self::integerAnswer('a', 2), self::integerAnswer('b', 3), self::integerAnswer('total', 6)),
        );

        self::assertCalculationError($report, 'item[2]', "read-only item 'total' (6) does not match its calculatedExpression result (5)");
    }

    public function testReadOnlyItemWithoutAnswerIsAnErrorWhenCalculationHasAValue(): void
    {
        $report = $this->validator->validate(
            self::sumQuestionnaire(),
            self::response(self::integerAnswer('a', 2), self::integerAnswer('b', 3), new QuestionnaireResponseItem(linkId: 'total')),
        );

        self::assertCalculationError($report, 'item[2]', '(no answer)');
    }

    public function testEditableItemMayOverrideTheCalculatedValue(): void
    {
        $report = $this->validator->validate(
            self::sumQuestionnaire(readOnly: false),
            self::response(self::integerAnswer('a', 2), self::integerAnswer('b', 3), self::integerAnswer('total', 6)),
        );

        self::assertCount(0, $report->errors());
    }

    public function testEmptyCalculationResultIsNotChecked(): void
    {
        // 'b' is unanswered, so a + b is empty: nothing to compare against.
        $report = $this->validator->validate(
            self::sumQuestionnaire(),
            self::response(self::integerAnswer('a', 2), self::integerAnswer('total', 99)),
        );

        self::assertCount(0, $report->errors());
    }

    public function testInvalidExpressionIsDeferred(): void
    {
        $report = $this->validator->validate(
            self::sumQuestionnaire(self::calculated('answer.value +')),
            self::response(self::integerAnswer('a', 2), self::integerAnswer('b', 3), self::integerAnswer('total', 6)),
        );

        self::assertCount(0, $report->errors());
    }

    public function testNonFhirPathLanguageIsNotEvaluated(): void
    {
        $report = $this->validator->validate(
            self::sumQuestionnaire(self::calculated('1', 'text/cql')),
            self::response(self::integerAnswer('a', 2), self::integerAnswer('b', 3), self::integerAnswer('total', 6)),
        );

        self::assertCount(0, $report->errors());
    }

    public function testCalculationThatReadsTheClockIsNotChecked(): void
    {
        // today() at validation time is not the day the form was filled in.
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'completed',
            type: new QuestionnaireItemTypeType('string'),
            readOnly: true,
            extension: [self::calculated('today().toString()')],
        )]);

        $report = $this->validator->validate($questionnaire, self::response(new QuestionnaireResponseItem(
            linkId: 'completed',
            answer: [new QuestionnaireResponseItemAnswer(value: new StringPrimitive(value: '2001-01-01'))],
        )));

        self::assertCount(0, $report->errors());
    }

    public function testVariableThatReadsTheClockDefersTheCheck(): void
    {
        $report = $this->validator->validate(
            self::sumQuestionnaire(self::calculated('5'), [self::variable('stamp', 'now()')]),
            self::response(self::integerAnswer('a', 2), self::integerAnswer('b', 3), self::integerAnswer('total', 6)),
        );

        self::assertCount(0, $report->errors());
    }

    public function testRootVariablesAreBound(): void
    {
        $questionnaire = self::sumQuestionnaire(
            self::calculated('%a * %b'),
            [self::variable('a', "repeat(item).where(linkId='a').answer.value"), self::variable('b', "repeat(item).where(linkId='b').answer.value")],
        );

        $valid = $this->validator->validate(
            $questionnaire,
            self::response(self::integerAnswer('a', 4), self::integerAnswer('b', 5), self::integerAnswer('total', 20)),
        );
        $invalid = $this->validator->validate(
            $questionnaire,
            self::response(self::integerAnswer('a', 4), self::integerAnswer('b', 5), self::integerAnswer('total', 9)),
        );

        self::assertCount(0, $valid->errors());
        self::assertCalculationError($invalid, 'item[2]', '(9) does not match its calculatedExpression result (20)');
    }

    public function testLaterVariablesSeeEarlierOnes(): void
    {
        $questionnaire = self::sumQuestionnaire(
            self::calculated('%doubled'),
            [self::variable('a', "repeat(item).where(linkId='a').answer.value"), self::variable('doubled', '%a * 2')],
        );

        $report = $this->validator->validate(
            $questionnaire,
            self::response(self::integerAnswer('a', 4), self::integerAnswer('b', 0), self::integerAnswer('total', 7)),
        );

        self::assertCalculationError($report, 'item[2]', 'calculatedExpression result (8)');
    }

    public function testUnknownVariableDefersTheCheck(): void
    {
        $report = $this->validator->validate(
            self::sumQuestionnaire(self::calculated('%undeclared + 1')),
            self::response(self::integerAnswer('a', 2), self::integerAnswer('b', 3), self::integerAnswer('total', 6)),
        );

        self::assertCount(0, $report->errors());
    }

    public function testGroupVariableIsEvaluatedAgainstItsOwnRepeatInstance(): void
    {
        // Each 'row' instance computes its own total from its own 'qty' answer.
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'row',
            type: new QuestionnaireItemTypeType('group'),
            repeats: true,
            extension: [self::variable('qty', "item.where(linkId='qty').answer.value")],
            item: [self::integerItem('qty'), self::totalItem([self::calculated('%qty * 10')])],
        )]);

        $row = static fn (int $qty, int $total): QuestionnaireResponseItem => new QuestionnaireResponseItem(
            linkId: 'row',
            item: [self::integerAnswer('qty', $qty), self::integerAnswer('total', $total)],
        );

        $report = $this->validator->validate($questionnaire, self::response($row(1, 10), $row(2, 25)));

        self::assertCalculationError($report, 'item[1].item[1]', '(25) does not match its calculatedExpression result (20)');
    }

    public function testRepeatingAnswersCompareAsAnUnorderedSet(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'total',
            type: new QuestionnaireItemTypeType('integer'),
            repeats: true,
            readOnly: true,
            extension: [self::calculated('1 | 2')],
        )]);

        $valid   = $this->validator->validate($questionnaire, self::response(self::integerAnswer('total', 2, 1)));
        $invalid = $this->validator->validate($questionnaire, self::response(self::integerAnswer('total', 1, 3)));

        self::assertCount(0, $valid->errors());
        self::assertCalculationError($invalid, 'item[0]', '(1, 3) does not match its calculatedExpression result (1, 2)');
    }

    public function testDecimalCalculationMatchesDecimalAnswer(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'bmi',
            type: new QuestionnaireItemTypeType('decimal'),
            readOnly: true,
            extension: [self::calculated('(70 / 1.8.power(2)).round(1)')],
        )]);

        $answer = static fn (string $value): QuestionnaireResponseItem => new QuestionnaireResponseItem(
            linkId: 'bmi',
            answer: [new QuestionnaireResponseItemAnswer(value: $value)],
        );

        self::assertCount(0, $this->validator->validate($questionnaire, self::response($answer('21.6')))->errors());
        self::assertCalculationError($this->validator->validate($questionnaire, self::response($answer('21.5'))), 'item[0]', 'result (21.6)');
    }

    public function testIncomparableKindsAreNotChecked(): void
    {
        // A text calculation on an integer item: the kinds differ, so nothing is reported.
        $report = $this->validator->validate(
            self::sumQuestionnaire(self::calculated("'abc'")),
            self::response(self::integerAnswer('a', 2), self::integerAnswer('b', 3), self::integerAnswer('total', 6)),
        );

        self::assertCount(0, $report->errors());
    }

    public function testStringCalculationIsCompared(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'greeting',
            type: new QuestionnaireItemTypeType('string'),
            readOnly: true,
            extension: [self::calculated("'hello'")],
        )]);

        $answer = static fn (string $value): QuestionnaireResponseItem => new QuestionnaireResponseItem(
            linkId: 'greeting',
            answer: [new QuestionnaireResponseItemAnswer(value: new StringPrimitive(value: $value))],
        );

        self::assertCount(0, $this->validator->validate($questionnaire, self::response($answer('hello')))->errors());
        self::assertCalculationError($this->validator->validate($questionnaire, self::response($answer('goodbye'))), 'item[0]', '(goodbye) does not match its calculatedExpression result (hello)');
    }

    public function testDisabledItemIsNotChecked(): void
    {
        $total = new QuestionnaireItem(
            linkId: 'total',
            type: new QuestionnaireItemTypeType('integer'),
            readOnly: true,
            extension: [self::calculated('5')],
            enableWhen: [new QuestionnaireItemEnableWhen(
                question: 'a',
                operator: new QuestionnaireItemOperatorType('exists'),
                answer: false,
            )],
        );

        $report = $this->validator->validate(
            new QuestionnaireResource(item: [self::integerItem('a'), $total]),
            self::response(self::integerAnswer('a', 1), self::integerAnswer('total', 6)),
        );

        // Only the enableWhen warning: the disabled item's calculation is not checked.
        self::assertCount(0, $report->errors());
        self::assertCount(1, $report->warnings());
    }

    public function testContextIsTheItemsOwnResponseItem(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'label',
            type: new QuestionnaireItemTypeType('string'),
            readOnly: true,
            extension: [self::calculated('%context.linkId')],
        )]);

        $report = $this->validator->validate($questionnaire, self::response(new QuestionnaireResponseItem(
            linkId: 'label',
            answer: [new QuestionnaireResponseItemAnswer(value: new StringPrimitive(value: 'wrong'))],
        )));

        self::assertCalculationError($report, 'item[0]', '(wrong) does not match its calculatedExpression result (label)');
    }

    public function testUnroundedDecimalCalculationIsCompared(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'dose',
            type: new QuestionnaireItemTypeType('decimal'),
            readOnly: true,
            extension: [self::calculated('2.5')],
        )]);

        $answer = static fn (string $value): QuestionnaireResponseItem => new QuestionnaireResponseItem(
            linkId: 'dose',
            answer: [new QuestionnaireResponseItemAnswer(value: $value)],
        );

        self::assertCount(0, $this->validator->validate($questionnaire, self::response($answer('2.5')))->errors());
        self::assertCalculationError($this->validator->validate($questionnaire, self::response($answer('9.5'))), 'item[0]', 'result (2.5)');
    }

    public function testNumericLookingStringCalculationIsCompared(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'code',
            type: new QuestionnaireItemTypeType('string'),
            readOnly: true,
            extension: [self::calculated("'123'")],
        )]);

        $answer = static fn (string $value): QuestionnaireResponseItem => new QuestionnaireResponseItem(
            linkId: 'code',
            answer: [new QuestionnaireResponseItemAnswer(value: new StringPrimitive(value: $value))],
        );

        self::assertCount(0, $this->validator->validate($questionnaire, self::response($answer('123')))->errors());
        self::assertCalculationError($this->validator->validate($questionnaire, self::response($answer('456'))), 'item[0]', '(456) does not match its calculatedExpression result (123)');
    }

    public function testItemInsideDisabledGroupIsNotChecked(): void
    {
        $questionnaire = new QuestionnaireResource(item: [
            self::integerItem('a'),
            new QuestionnaireItem(
                linkId: 'group',
                type: new QuestionnaireItemTypeType('group'),
                enableWhen: [new QuestionnaireItemEnableWhen(
                    question: 'a',
                    operator: new QuestionnaireItemOperatorType('exists'),
                    answer: false,
                )],
                item: [self::totalItem([self::calculated('5')])],
            ),
        ]);

        $report = $this->validator->validate($questionnaire, self::response(
            self::integerAnswer('a', 1),
            new QuestionnaireResponseItem(linkId: 'group', item: [self::integerAnswer('total', 6)]),
        ));

        // Only the disabled group's enableWhen warning.
        self::assertCount(0, $report->errors());
        self::assertCount(1, $report->warnings());
    }

    public function testClockCallHiddenByCommentOrBackticksIsStillSkipped(): void
    {
        foreach (['today /* completion date */ ().toString()', '`today`().toString()'] as $expression) {
            $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
                linkId: 'completed',
                type: new QuestionnaireItemTypeType('string'),
                readOnly: true,
                extension: [self::calculated($expression)],
            )]);

            $report = $this->validator->validate($questionnaire, self::response(new QuestionnaireResponseItem(
                linkId: 'completed',
                answer: [new QuestionnaireResponseItemAnswer(value: new StringPrimitive(value: '2001-01-01'))],
            )));

            self::assertCount(0, $report->errors(), $expression);
        }
    }

    public function testClockNameInsideAStringLiteralIsStillChecked(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'label',
            type: new QuestionnaireItemTypeType('string'),
            readOnly: true,
            extension: [self::calculated("'today()'")],
        )]);

        $report = $this->validator->validate($questionnaire, self::response(new QuestionnaireResponseItem(
            linkId: 'label',
            answer: [new QuestionnaireResponseItemAnswer(value: new StringPrimitive(value: 'tomorrow'))],
        )));

        self::assertCalculationError($report, 'item[0]', 'result (today())');
    }

    public function testR5ResourcesAreSupported(): void
    {
        $questionnaire = new R5QuestionnaireResource(item: [new R5QuestionnaireItem(
            linkId: 'total',
            type: new R5QuestionnaireItemTypeType('integer'),
            readOnly: true,
            extension: [new R5Extension(url: self::CALCULATED, value: new R5Expression(language: 'text/fhirpath', expression: '2 + 2'))],
        )]);

        $response = new R5QuestionnaireResponseResource(
            status: new R5QuestionnaireResponseStatusType('completed'),
            item: [new R5QuestionnaireResponseItem(linkId: 'total', answer: [new R5QuestionnaireResponseItemAnswer(value: 5)])],
        );

        self::assertCalculationError($this->validator->validate($questionnaire, $response), 'item[0]', 'result (4)');
    }
}
