<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireAnswerConstraintType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItemAnswerOption;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use PHPUnit\Framework\TestCase;

/**
 * R5 Questionnaire.item.answerConstraint (#93): optionsOnly (the default) keeps answers to the
 * declared answerOption / answerValueSet; optionsOrType also accepts any value of the item's type;
 * optionsOrString also accepts a free-text string.
 */
final class FHIRQuestionnaireValidatorAnswerConstraintTest extends TestCase
{
    private const string SYSTEM = 'http://example.org/colours';

    private const string VS_URL = 'http://example.org/ValueSet/colours';

    /**
     * A coding item offering 'red' and 'green', with the given answerConstraint (null = absent).
     */
    private static function codingQuestionnaire(?string $answerConstraint): QuestionnaireResource
    {
        return new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'colour',
            type: new QuestionnaireItemTypeType('coding'),
            answerConstraint: $answerConstraint !== null ? new QuestionnaireAnswerConstraintType($answerConstraint) : null,
            answerOption: [
                new QuestionnaireItemAnswerOption(value: self::coding('red')),
                new QuestionnaireItemAnswerOption(value: self::coding('green')),
            ],
        )]);
    }

    /**
     * A coding item bound to VS_URL (no answerOption), with the given answerConstraint.
     */
    private static function valueSetQuestionnaire(?string $answerConstraint): QuestionnaireResource
    {
        return new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'colour',
            type: new QuestionnaireItemTypeType('coding'),
            answerConstraint: $answerConstraint !== null ? new QuestionnaireAnswerConstraintType($answerConstraint) : null,
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
        )]);
    }

    private static function coding(string $code): Coding
    {
        return new Coding(system: new UriPrimitive(value: self::SYSTEM), code: new CodePrimitive(value: $code));
    }

    private static function response(Coding|StringPrimitive $value): QuestionnaireResponseResource
    {
        return new QuestionnaireResponseResource(
            status: new QuestionnaireResponseStatusType('completed'),
            item: [new QuestionnaireResponseItem(
                linkId: 'colour',
                answer: [new QuestionnaireResponseItemAnswer(value: $value)],
            )],
        );
    }

    public function testAbsentConstraintRejectsCodingOutsideOptions(): void
    {
        $report = (new FHIRQuestionnaireValidator())->validate(
            self::codingQuestionnaire(null),
            self::response(self::coding('blue')),
        );

        self::assertCount(1, $report->errors());
        self::assertStringContainsString('not among the permitted answerOption values', $report->errors()[0]->message);
    }

    public function testOptionsOnlyRejectsCodingOutsideOptions(): void
    {
        $report = (new FHIRQuestionnaireValidator())->validate(
            self::codingQuestionnaire('optionsOnly'),
            self::response(self::coding('blue')),
        );

        self::assertCount(1, $report->errors());
        self::assertStringContainsString('not among the permitted answerOption values', $report->errors()[0]->message);
    }

    public function testOptionsOrTypeAcceptsCodingOutsideOptions(): void
    {
        $report = (new FHIRQuestionnaireValidator())->validate(
            self::codingQuestionnaire('optionsOrType'),
            self::response(self::coding('blue')),
        );

        self::assertSame([], $report->violations);
    }

    public function testOptionsOrTypeRejectsFreeTextString(): void
    {
        $report = (new FHIRQuestionnaireValidator())->validate(
            self::codingQuestionnaire('optionsOrType'),
            self::response(new StringPrimitive(value: 'blue')),
        );

        self::assertCount(1, $report->errors());
        self::assertStringContainsString('not among the permitted answerOption values', $report->errors()[0]->message);
    }

    public function testOptionsOrStringAcceptsFreeTextString(): void
    {
        $report = (new FHIRQuestionnaireValidator())->validate(
            self::codingQuestionnaire('optionsOrString'),
            self::response(new StringPrimitive(value: 'blue')),
        );

        self::assertSame([], $report->violations);
    }

    public function testOptionsOrStringRejectsCodingOutsideOptions(): void
    {
        $report = (new FHIRQuestionnaireValidator())->validate(
            self::codingQuestionnaire('optionsOrString'),
            self::response(self::coding('blue')),
        );

        self::assertCount(1, $report->errors());
        self::assertStringContainsString('not among the permitted answerOption values', $report->errors()[0]->message);
    }

    public function testOptionsOrStringSkipsValueSetCheckForFreeTextString(): void
    {
        $validator = new FHIRQuestionnaireValidator(terminologyClient: new InMemoryFHIRTerminologyClient([], false));

        $report = $validator->validate(
            self::valueSetQuestionnaire('optionsOrString'),
            self::response(new StringPrimitive(value: 'blue')),
        );

        self::assertSame([], $report->violations);
    }

    public function testOptionsOrTypeSkipsValueSetCheckForCodingOfItemType(): void
    {
        $validator = new FHIRQuestionnaireValidator(terminologyClient: new InMemoryFHIRTerminologyClient([], false));

        $report = $validator->validate(
            self::valueSetQuestionnaire('optionsOrType'),
            self::response(self::coding('blue')),
        );

        self::assertSame([], $report->violations);
    }

    public function testOptionsOnlyKeepsValueSetCheck(): void
    {
        $validator = new FHIRQuestionnaireValidator(terminologyClient: new InMemoryFHIRTerminologyClient([], false));

        $report = $validator->validate(
            self::valueSetQuestionnaire('optionsOnly'),
            self::response(self::coding('blue')),
        );

        self::assertCount(1, $report->errors());
        self::assertStringContainsString('is not a member of value set', $report->errors()[0]->message);
    }
}
