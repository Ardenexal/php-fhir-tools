<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;
use PHPUnit\Framework\TestCase;

/**
 * SDC sdc-questionnaire-answerExpression (#91): a FHIRPath expression on the item yields the
 * permitted answers, and each answer must be one of them. An expression the validator cannot
 * evaluate (another language, an unknown variable, an empty result) checks nothing.
 */
final class FHIRQuestionnaireValidatorAnswerExpressionTest extends TestCase
{
    private const string SYSTEM = 'http://example.org/colours';

    private const string EXT_ANSWER_EXPRESSION = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-answerExpression';

    /**
     * A questionnaire whose 'colour' item takes its answers from $expression. A sibling
     * 'palette' item lets expressions derive the answer set from other answers in the response.
     */
    private static function questionnaire(string $expression, string $type = 'choice', string $language = 'text/fhirpath'): QuestionnaireResource
    {
        return new QuestionnaireResource(item: [
            new QuestionnaireItem(
                linkId: 'palette',
                type: new QuestionnaireItemTypeType('choice'),
                repeats: true,
            ),
            new QuestionnaireItem(
                extension: [new Extension(
                    url: self::EXT_ANSWER_EXPRESSION,
                    value: new Expression(language: $language, expression: $expression),
                )],
                linkId: 'colour',
                type: new QuestionnaireItemTypeType($type),
            ),
        ]);
    }

    private static function coding(string $code): Coding
    {
        return new Coding(system: new UriPrimitive(value: self::SYSTEM), code: new CodePrimitive(value: $code));
    }

    /**
     * A completed response with 'red' and 'green' in the palette and $answer for 'colour'.
     */
    private static function response(Coding|StringPrimitive $answer): QuestionnaireResponseResource
    {
        return new QuestionnaireResponseResource(
            status: new QuestionnaireResponseStatusType('completed'),
            item: [
                new QuestionnaireResponseItem(
                    linkId: 'palette',
                    answer: [
                        new QuestionnaireResponseItemAnswer(value: self::coding('red')),
                        new QuestionnaireResponseItemAnswer(value: self::coding('green')),
                    ],
                ),
                new QuestionnaireResponseItem(
                    linkId: 'colour',
                    answer: [new QuestionnaireResponseItemAnswer(value: $answer)],
                ),
            ],
        );
    }

    private static function numericResponse(int $answer): QuestionnaireResponseResource
    {
        return new QuestionnaireResponseResource(
            status: new QuestionnaireResponseStatusType('completed'),
            item: [new QuestionnaireResponseItem(
                linkId: 'colour',
                answer: [new QuestionnaireResponseItemAnswer(value: $answer)],
            )],
        );
    }

    private const string PALETTE = "%resource.item.where(linkId = 'palette').answer.value";

    public function testAcceptsCodingInTheExpressionResult(): void
    {
        $report = (new FHIRQuestionnaireValidator())->validate(
            self::questionnaire(self::PALETTE),
            self::response(self::coding('green')),
        );

        self::assertSame([], $report->violations);
    }

    public function testRejectsCodingOutsideTheExpressionResult(): void
    {
        $report = (new FHIRQuestionnaireValidator())->validate(
            self::questionnaire(self::PALETTE),
            self::response(self::coding('blue')),
        );

        self::assertCount(1, $report->errors());
        self::assertSame('item[1].answer[0].value', $report->errors()[0]->path);
        self::assertStringContainsString("Answer 'coding:http://example.org/colours|blue' for item 'colour' is not among the answers its answerExpression allows", $report->errors()[0]->message);
    }

    public function testComparesStringAnswersByValue(): void
    {
        $validator     = new FHIRQuestionnaireValidator();
        $questionnaire = self::questionnaire("'red' | 'green'", 'string');

        self::assertSame([], $validator->validate($questionnaire, self::response(new StringPrimitive(value: 'red')))->violations);
        self::assertCount(1, $validator->validate($questionnaire, self::response(new StringPrimitive(value: 'blue')))->errors());
    }

    public function testComparesNumbersByValue(): void
    {
        $validator     = new FHIRQuestionnaireValidator();
        $questionnaire = self::questionnaire('5.0 | 6', 'integer');

        // The FHIRPath decimal 5.0 equals the integer answer 5.
        self::assertSame([], $validator->validate($questionnaire, self::numericResponse(5))->violations);
        self::assertCount(1, $validator->validate($questionnaire, self::numericResponse(7))->errors());
    }

    public function testBindsQuestionnaireAndContextVariables(): void
    {
        $validator = new FHIRQuestionnaireValidator();

        // %questionnaire is the source Questionnaire; %context (and the focus) is the response item.
        $byQuestionnaire = self::questionnaire('%questionnaire.item.linkId', 'string');
        self::assertSame([], $validator->validate($byQuestionnaire, self::response(new StringPrimitive(value: 'palette')))->violations);

        foreach (['%context.linkId', 'linkId'] as $expression) {
            $byContext = self::questionnaire($expression, 'string');
            self::assertSame([], $validator->validate($byContext, self::response(new StringPrimitive(value: 'colour')))->violations, $expression);
            self::assertCount(1, $validator->validate($byContext, self::response(new StringPrimitive(value: 'palette')))->errors(), $expression);
        }
    }

    public function testOpenChoiceAcceptsFreeTextOutsideTheExpressionResult(): void
    {
        $validator     = new FHIRQuestionnaireValidator();
        $questionnaire = self::questionnaire(self::PALETTE, 'open-choice');

        self::assertSame([], $validator->validate($questionnaire, self::response(new StringPrimitive(value: 'teal')))->violations);
        self::assertCount(1, $validator->validate($questionnaire, self::response(self::coding('blue')))->errors());
    }

    public function testSkipsExpressionsInOtherLanguages(): void
    {
        $report = (new FHIRQuestionnaireValidator())->validate(
            self::questionnaire('ValueSet?url=http://example.org/vs', 'choice', 'application/x-fhir-query'),
            self::response(self::coding('blue')),
        );

        self::assertSame([], $report->violations);
    }

    public function testSkipsExpressionThatCannotBeEvaluated(): void
    {
        // %patient is supplied by the form filler's launch context, which the validator does not have.
        $report = (new FHIRQuestionnaireValidator())->validate(
            self::questionnaire('%patient.name.given'),
            self::response(self::coding('blue')),
        );

        self::assertSame([], $report->violations);
    }

    public function testSkipsExpressionWithEmptyResult(): void
    {
        $report = (new FHIRQuestionnaireValidator())->validate(
            self::questionnaire("%resource.item.where(linkId = 'missing').answer.value"),
            self::response(self::coding('blue')),
        );

        self::assertSame([], $report->violations);
    }
}
