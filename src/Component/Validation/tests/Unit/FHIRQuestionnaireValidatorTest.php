<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EnableWhenBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemOperatorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItemEnableWhen;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItem as R5QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource as R5QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItem as R5QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer as R5QuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponseResource as R5QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireConstraint;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;
use PHPUnit\Framework\TestCase;

final class FHIRQuestionnaireValidatorTest extends TestCase
{
    private FHIRQuestionnaireValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new FHIRQuestionnaireValidator();
    }

    private static function stringItem(string $linkId, bool $required = false, bool $repeats = false): QuestionnaireItem
    {
        return new QuestionnaireItem(
            linkId: $linkId,
            type: new QuestionnaireItemTypeType('string'),
            required: $required,
            repeats: $repeats,
        );
    }

    private static function stringAnswer(string $value): QuestionnaireResponseItemAnswer
    {
        return new QuestionnaireResponseItemAnswer(value: new StringPrimitive(value: $value));
    }

    private static function response(string $status, QuestionnaireResponseItem ...$items): QuestionnaireResponseResource
    {
        return new QuestionnaireResponseResource(
            status: new QuestionnaireResponseStatusType($status),
            item: array_values($items),
        );
    }

    public function testValidResponseProducesNoViolations(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1', required: true)]);
        $response      = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('hello')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertTrue($report->isValid());
        self::assertSame([], $report->violations);
    }

    public function testResponseItemWithUnknownLinkIdProducesError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1')]);
        $response      = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'unknown',
            answer: [self::stringAnswer('hello')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertFalse($report->isValid());
        self::assertCount(1, $report->errors());
        self::assertSame('item[0].linkId', $report->errors()[0]->path);
        self::assertStringContainsString("'unknown'", $report->errors()[0]->message);
        self::assertSame(FHIRQuestionnaireConstraint::class, $report->errors()[0]->constraintClass);
    }

    public function testRequiredItemWithoutAnswerProducesErrorWhenCompleted(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1', required: true)]);
        $response      = self::response('completed');

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertStringContainsString("Required item 'q1' has no answer", $report->errors()[0]->message);
    }

    public function testRequiredItemWithoutAnswerIsSkippedWhenNotStrict(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1', required: true)]);
        $response      = self::response('completed');

        $report = $this->validator->validate($questionnaire, $response, strictStatus: false);

        self::assertTrue($report->isValid());
    }

    public function testRequiredItemWithoutAnswerIsSkippedWhenInProgress(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1', required: true)]);
        $response      = self::response('in-progress');

        $report = $this->validator->validate($questionnaire, $response);

        self::assertTrue($report->isValid());
    }

    public function testNonRepeatingItemWithTwoAnswersProducesError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1')]);
        $response      = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('a'), self::stringAnswer('b')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertSame('item[0].answer', $report->errors()[0]->path);
        self::assertStringContainsString('2 answers were provided', $report->errors()[0]->message);
    }

    public function testNonRepeatingItemAppearingTwiceProducesError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1')]);
        $response      = self::response(
            'completed',
            new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('a')]),
            new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('b')]),
        );

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertSame('item[1]', $report->errors()[0]->path);
        self::assertStringContainsString('does not repeat but appears 2 times', $report->errors()[0]->message);
    }

    public function testRepeatingItemWithMultipleAnswersIsValid(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1', repeats: true)]);
        $response      = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('a'), self::stringAnswer('b')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertTrue($report->isValid());
        self::assertSame([], $report->violations);
    }

    public function testBooleanAnswerOnStringItemProducesWarning(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1')]);
        $response      = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: true)],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertTrue($report->isValid(), 'Type mismatch is a warning, not an error');
        self::assertCount(1, $report->warnings());
        self::assertSame('item[0].answer[0].value', $report->warnings()[0]->path);
        self::assertStringContainsString("'bool' does not match declared item type 'string'", $report->warnings()[0]->message);
    }

    public function testDecimalScalarStringAnswerMatchesDecimalItemType(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('decimal'),
        )]);
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: '120.5')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->violations);
    }

    public function testEnableWhenExistsItemPresentWhileDisabledProducesWarning(): void
    {
        $questionnaire = new QuestionnaireResource(item: [
            new QuestionnaireItem(linkId: 'q1', type: new QuestionnaireItemTypeType('boolean')),
            new QuestionnaireItem(
                linkId: 'q2',
                type: new QuestionnaireItemTypeType('string'),
                enableWhen: [new QuestionnaireItemEnableWhen(
                    question: 'q1',
                    operator: new QuestionnaireItemOperatorType('exists'),
                    answer: true,
                )],
            ),
        ]);
        // q1 is unanswered, so q2 is disabled — yet q2 is present.
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q2',
            answer: [self::stringAnswer('should not be here')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->warnings());
        self::assertSame('item[0]', $report->warnings()[0]->path);
        self::assertStringContainsString("'q2' is present but its enableWhen conditions are not satisfied", $report->warnings()[0]->message);
    }

    public function testEnableWhenEqualsHiddenItemProducesNoViolations(): void
    {
        $questionnaire = new QuestionnaireResource(item: [
            new QuestionnaireItem(linkId: 'q1', type: new QuestionnaireItemTypeType('boolean')),
            new QuestionnaireItem(
                linkId: 'q2',
                type: new QuestionnaireItemTypeType('string'),
                required: true,
                enableWhen: [new QuestionnaireItemEnableWhen(
                    question: 'q1',
                    operator: new QuestionnaireItemOperatorType('='),
                    answer: true,
                )],
            ),
        ]);
        // q1 answered false → q2 disabled; q2 absent and its required flag must not fire.
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: false)],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->violations);
    }

    public function testEnableWhenEqualsSatisfiedEnablesRequiredItem(): void
    {
        $questionnaire = new QuestionnaireResource(item: [
            new QuestionnaireItem(linkId: 'q1', type: new QuestionnaireItemTypeType('boolean')),
            new QuestionnaireItem(
                linkId: 'q2',
                type: new QuestionnaireItemTypeType('string'),
                required: true,
                enableWhen: [new QuestionnaireItemEnableWhen(
                    question: 'q1',
                    operator: new QuestionnaireItemOperatorType('='),
                    answer: true,
                )],
            ),
        ]);
        // q1 answered true → q2 enabled, required, and unanswered → error.
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: true)],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertStringContainsString("Required item 'q2' has no answer", $report->errors()[0]->message);
    }

    public function testEnableWhenGreaterThanComparesIntegers(): void
    {
        $questionnaire = new QuestionnaireResource(item: [
            new QuestionnaireItem(linkId: 'age', type: new QuestionnaireItemTypeType('integer')),
            new QuestionnaireItem(
                linkId: 'followUp',
                type: new QuestionnaireItemTypeType('string'),
                enableWhen: [new QuestionnaireItemEnableWhen(
                    question: 'age',
                    operator: new QuestionnaireItemOperatorType('>'),
                    answer: 17,
                )],
            ),
        ]);
        $response = self::response(
            'completed',
            new QuestionnaireResponseItem(linkId: 'age', answer: [new QuestionnaireResponseItemAnswer(value: 18)]),
            new QuestionnaireResponseItem(linkId: 'followUp', answer: [self::stringAnswer('enabled')]),
        );

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->violations, 'age 18 > 17 enables followUp');
    }

    public function testEnableWhenBehaviorAllRequiresEveryCondition(): void
    {
        $questionnaire = new QuestionnaireResource(item: [
            new QuestionnaireItem(linkId: 'q1', type: new QuestionnaireItemTypeType('boolean')),
            new QuestionnaireItem(linkId: 'q2', type: new QuestionnaireItemTypeType('boolean')),
            new QuestionnaireItem(
                linkId: 'q3',
                type: new QuestionnaireItemTypeType('string'),
                enableWhen: [
                    new QuestionnaireItemEnableWhen(
                        question: 'q1',
                        operator: new QuestionnaireItemOperatorType('='),
                        answer: true,
                    ),
                    new QuestionnaireItemEnableWhen(
                        question: 'q2',
                        operator: new QuestionnaireItemOperatorType('='),
                        answer: true,
                    ),
                ],
                enableBehavior: new EnableWhenBehaviorType('all'),
            ),
        ]);
        // Only q1 satisfied → with behavior 'all', q3 stays disabled but is present.
        $response = self::response(
            'completed',
            new QuestionnaireResponseItem(linkId: 'q1', answer: [new QuestionnaireResponseItemAnswer(value: true)]),
            new QuestionnaireResponseItem(linkId: 'q2', answer: [new QuestionnaireResponseItemAnswer(value: false)]),
            new QuestionnaireResponseItem(linkId: 'q3', answer: [self::stringAnswer('x')]),
        );

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->warnings());
        self::assertStringContainsString("'q3' is present but its enableWhen conditions are not satisfied", $report->warnings()[0]->message);
    }

    public function testEnableWhenReferencingUnknownLinkIdProducesWarning(): void
    {
        $questionnaire = new QuestionnaireResource(item: [
            new QuestionnaireItem(
                linkId: 'q1',
                type: new QuestionnaireItemTypeType('string'),
                enableWhen: [new QuestionnaireItemEnableWhen(
                    question: 'missing',
                    operator: new QuestionnaireItemOperatorType('exists'),
                    answer: true,
                )],
            ),
        ]);
        $response = self::response('completed');

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->warnings());
        self::assertStringContainsString("enableWhen.question 'missing'", $report->warnings()[0]->message);
        self::assertSame('Questionnaire.item[linkId=q1].enableWhen[0].question', $report->warnings()[0]->path);
    }

    public function testNestedGroupItemsRecurse(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'group1',
            type: new QuestionnaireItemTypeType('group'),
            item: [self::stringItem('q1', required: true)],
        )]);
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'group1',
            item: [new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('nested')])],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->violations);
    }

    public function testNonRepeatingChildOfRepeatingGroupIsValidOncePerInstance(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'group1',
            type: new QuestionnaireItemTypeType('group'),
            repeats: true,
            item: [self::stringItem('q1')],
        )]);
        // Two instances of the repeating group, each answering q1 once — valid.
        $response = self::response(
            'completed',
            new QuestionnaireResponseItem(
                linkId: 'group1',
                item: [new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('first')])],
            ),
            new QuestionnaireResponseItem(
                linkId: 'group1',
                item: [new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('second')])],
            ),
        );

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->violations, 'Occurrence counting must be sibling-scoped');
    }

    public function testNonRepeatingChildTwiceInSameGroupInstanceProducesError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'group1',
            type: new QuestionnaireItemTypeType('group'),
            repeats: true,
            item: [self::stringItem('q1')],
        )]);
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'group1',
            item: [
                new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('first')]),
                new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('duplicate')]),
            ],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertSame('item[0].item[1]', $report->errors()[0]->path);
        self::assertStringContainsString("'q1' does not repeat but appears 2 times", $report->errors()[0]->message);
    }

    public function testRequiredGroupWithAnsweredDescendantIsValid(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'group1',
            type: new QuestionnaireItemTypeType('group'),
            required: true,
            item: [self::stringItem('q1')],
        )]);
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'group1',
            item: [new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('answered')])],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->violations);
    }

    public function testRequiredGroupWithoutAnsweredDescendantProducesError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'group1',
            type: new QuestionnaireItemTypeType('group'),
            required: true,
            item: [self::stringItem('q1')],
        )]);
        // Group present but empty — the spec requires at least one answered descendant question.
        $response = self::response('completed', new QuestionnaireResponseItem(linkId: 'group1'));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertStringContainsString("Required group 'group1' has no answered descendant question", $report->errors()[0]->message);
    }

    public function testRequiredGroupMissingEntirelyProducesError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'group1',
            type: new QuestionnaireItemTypeType('group'),
            required: true,
            item: [self::stringItem('q1')],
        )]);
        $response = self::response('completed');

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertStringContainsString("Required group 'group1' is missing", $report->errors()[0]->message);
    }

    public function testRequiredChildMissingInOneRepeatingGroupInstanceProducesError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'group1',
            type: new QuestionnaireItemTypeType('group'),
            repeats: true,
            item: [self::stringItem('q1', required: true)],
        )]);
        // Instance [0] answers the required child; instance [1] omits it.
        $response = self::response(
            'completed',
            new QuestionnaireResponseItem(
                linkId: 'group1',
                item: [new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('present')])],
            ),
            new QuestionnaireResponseItem(linkId: 'group1'),
        );

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertSame('item[1]', $report->errors()[0]->path);
        self::assertStringContainsString("Required item 'q1' has no answer", $report->errors()[0]->message);
    }

    public function testRequiredChildAnsweredInAllRepeatingGroupInstancesIsValid(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'group1',
            type: new QuestionnaireItemTypeType('group'),
            repeats: true,
            item: [self::stringItem('q1', required: true)],
        )]);
        $response = self::response(
            'completed',
            new QuestionnaireResponseItem(
                linkId: 'group1',
                item: [new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('first')])],
            ),
            new QuestionnaireResponseItem(
                linkId: 'group1',
                item: [new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('second')])],
            ),
        );

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->violations);
    }

    public function testRequiredChildOfAbsentOptionalGroupIsNotFlagged(): void
    {
        // Spec: required "only has meaning if the parent element is present. If a non-required
        // 'group' item contains a 'required' question item, it's completely fine to omit the group."
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'group1',
            type: new QuestionnaireItemTypeType('group'),
            item: [self::stringItem('q1', required: true)],
        )]);
        $response = self::response('completed');

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->violations);
    }

    public function testMisplacedChildItemProducesPlacementError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'group1',
            type: new QuestionnaireItemTypeType('group'),
            item: [self::stringItem('q1')],
        )]);
        // q1 is declared under group1 but answered at the response root.
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('misplaced')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertSame('item[0]', $report->errors()[0]->path);
        self::assertStringContainsString("Item 'q1' is not declared at this position in the Questionnaire; expected under item 'group1'", $report->errors()[0]->message);
    }

    public function testTopLevelItemNestedInsideGroupProducesPlacementError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [
            new QuestionnaireItem(
                linkId: 'group1',
                type: new QuestionnaireItemTypeType('group'),
                item: [self::stringItem('q1')],
            ),
            self::stringItem('q2'),
        ]);
        // q2 is declared at the root but answered inside group1.
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'group1',
            item: [
                new QuestionnaireResponseItem(linkId: 'q1', answer: [self::stringAnswer('fine')]),
                new QuestionnaireResponseItem(linkId: 'q2', answer: [self::stringAnswer('misplaced')]),
            ],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertSame('item[0].item[1]', $report->errors()[0]->path);
        self::assertStringContainsString("Item 'q2' is not declared at this position in the Questionnaire; expected at the response root", $report->errors()[0]->message);
    }

    public function testChoiceItemAcceptsCodingAnswer(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('choice'),
        )]);
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: new Coding(code: null))],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->violations);
    }

    public function testR5ResourcesAreSupported(): void
    {
        $questionnaire = new R5QuestionnaireResource(item: [new R5QuestionnaireItem(
            linkId: 'q1',
            type: new \Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireItemTypeType('string'),
            required: true,
        )]);
        $response = new R5QuestionnaireResponseResource(
            status: new \Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireResponseStatusType('completed'),
            item: [new R5QuestionnaireResponseItem(
                linkId: 'q1',
                answer: [new R5QuestionnaireResponseItemAnswer(
                    value: new \Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive(value: 'hello'),
                )],
            )],
        );

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->violations);
    }

    public function testRejectsNonQuestionnaireFirstArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a QuestionnaireResource');

        $this->validator->validate(new \stdClass(), self::response('completed'));
    }

    public function testRejectsNonResponseSecondArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a QuestionnaireResponseResource');

        $this->validator->validate(new QuestionnaireResource(), new \stdClass());
    }

    public function testRequiredItemWithEmptyAnswerElementProducesError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1', required: true)]);
        // An answer element with no value (answer: [{}]) answers nothing and must not satisfy required.
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer()],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertStringContainsString("Required item 'q1' has no answer", $report->errors()[0]->message);
    }

    public function testRequiredItemWithNonNullAnswerValueIsValid(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1', required: true)]);
        // Boundary counterpart: a present value satisfies the same required item that an empty element does not.
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('answered')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->violations);
    }

    public function testRequiredGroupWithEmptyDescendantAnswerProducesError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'group1',
            type: new QuestionnaireItemTypeType('group'),
            required: true,
            item: [self::stringItem('q1')],
        )]);
        // Descendant present, but its only answer element carries no value — the group is not satisfied.
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'group1',
            item: [new QuestionnaireResponseItem(linkId: 'q1', answer: [new QuestionnaireResponseItemAnswer()])],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertStringContainsString("Required group 'group1' has no answered descendant question", $report->errors()[0]->message);
    }

    public function testEnableWhenNotEqualWithIncomparableAnswerDoesNotEnable(): void
    {
        $questionnaire = new QuestionnaireResource(item: [
            new QuestionnaireItem(linkId: 'q1', type: new QuestionnaireItemTypeType('attachment')),
            new QuestionnaireItem(
                linkId: 'q2',
                type: new QuestionnaireItemTypeType('string'),
                required: true,
                enableWhen: [new QuestionnaireItemEnableWhen(
                    question: 'q1',
                    operator: new QuestionnaireItemOperatorType('!='),
                    answer: 'expected',
                )],
            ),
        ]);
        // q1's answer is an Attachment — it has no scalar normalization, so it is incomparable.
        // '!=' must not enable q2 on an incomparable operand; otherwise q2 would be wrongly
        // treated as required and flagged unanswered.
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: new Attachment(title: 'scan.pdf'))],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertSame([], $report->errors(), 'incomparable != operand must not enable & require q2');
    }

    // M15 — Questionnaire status warnings

    public function testDraftQuestionnaireEmitsWarning(): void
    {
        $questionnaire = new QuestionnaireResource(
            status: new PublicationStatusType('draft'),
            item: [self::stringItem('q1')],
        );
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('x')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->warnings(), 'draft status → 1 warning');
        self::assertStringContainsString('draft', $report->warnings()[0]->message);
    }

    public function testRetiredQuestionnaireEmitsWarning(): void
    {
        $questionnaire = new QuestionnaireResource(
            status: new PublicationStatusType('retired'),
            item: [self::stringItem('q1')],
        );
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('x')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->warnings(), 'retired status → 1 warning');
        self::assertStringContainsString('retired', $report->warnings()[0]->message);
    }

    public function testActiveQuestionnaireEmitsNoStatusWarning(): void
    {
        $questionnaire = new QuestionnaireResource(
            status: new PublicationStatusType('active'),
            item: [self::stringItem('q1')],
        );
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('x')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(0, $report->warnings(), 'active status → no warning');
    }

    public function testExpiredEffectivePeriodEmitsWarning(): void
    {
        $questionnaire = new QuestionnaireResource(
            status: new PublicationStatusType('active'),
            effectivePeriod: new Period(
                end: new DateTimePrimitive(value: FHIRDateTime::parse('2021-12-11')),
            ),
            item: [self::stringItem('q1')],
        );
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('x')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->warnings(), 'past end date → 1 warning');
        self::assertStringContainsString('effectivePeriod.end', $report->warnings()[0]->path);
    }

    public function testFutureEffectivePeriodStartEmitsWarning(): void
    {
        $futureYear    = (string) (date('Y') + 2);
        $questionnaire = new QuestionnaireResource(
            status: new PublicationStatusType('active'),
            effectivePeriod: new Period(
                start: new DateTimePrimitive(value: FHIRDateTime::parse($futureYear)),
            ),
            item: [self::stringItem('q1')],
        );
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('x')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->warnings(), 'future start date → 1 warning');
        self::assertStringContainsString('effectivePeriod.start', $report->warnings()[0]->path);
    }

    // M15 — String type \r\n restriction

    public function testStringAnswerWithNewlineEmitsError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1')]);
        $response      = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer("hello\nworld")],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors(), 'string answer with \\n → 1 error');
        self::assertStringContainsString('line breaks', $report->errors()[0]->message);
    }

    public function testStringAnswerWithCarriageReturnEmitsError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1')]);
        $response      = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer("hello\rworld")],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors(), 'string answer with \\r → 1 error');
        self::assertStringContainsString('line breaks', $report->errors()[0]->message);
    }

    public function testStringAnswerWithoutNewlineIsValid(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1')]);
        $response      = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('hello world')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(0, $report->errors(), 'clean string answer → no error');
    }

    public function testTextAnswerWithNewlineIsValid(): void
    {
        $questionnaire = new QuestionnaireResource(item: [
            new QuestionnaireItem(linkId: 'q1', type: new QuestionnaireItemTypeType('text')),
        ]);
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer("hello\nworld")],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(0, $report->errors(), 'text type allows \\n — no error');
    }

    public function testDateUpperBoundWithYearMonthPrecisionAcceptsLastDayOfMonth(): void
    {
        $item = new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('date'),
            extension: [new Extension(
                url: 'http://hl7.org/fhir/StructureDefinition/maxValue',
                value: new DatePrimitive(value: FHIRDate::parse('2022-06')),
            )],
        );
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: new DatePrimitive(value: FHIRDate::parse('2022-06-30')))],
        ));

        $report = $this->validator->validate(new QuestionnaireResource(item: [$item]), $response);

        self::assertCount(0, $report->errors(), '2022-06-30 is within maxValue 2022-06 (last day of June)');
    }

    public function testDateUpperBoundWithYearMonthPrecisionRejectsFirstDayOfNextMonth(): void
    {
        $item = new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('date'),
            extension: [new Extension(
                url: 'http://hl7.org/fhir/StructureDefinition/maxValue',
                value: new DatePrimitive(value: FHIRDate::parse('2022-06')),
            )],
        );
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: new DatePrimitive(value: FHIRDate::parse('2022-07-01')))],
        ));

        $report = $this->validator->validate(new QuestionnaireResource(item: [$item]), $response);

        self::assertCount(1, $report->errors(), '2022-07-01 exceeds maxValue 2022-06 (after end of June)');
    }

    public function testDateUpperBoundWithYearPrecisionAcceptsLastDayOfYear(): void
    {
        $item = new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('date'),
            extension: [new Extension(
                url: 'http://hl7.org/fhir/StructureDefinition/maxValue',
                value: new DatePrimitive(value: FHIRDate::parse('2022')),
            )],
        );
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: new DatePrimitive(value: FHIRDate::parse('2022-12-31')))],
        ));

        $report = $this->validator->validate(new QuestionnaireResource(item: [$item]), $response);

        self::assertCount(0, $report->errors(), '2022-12-31 is within maxValue 2022 (last day of year)');
    }

    public function testDateLowerBoundWithYearPrecisionAcceptsFirstDayOfYear(): void
    {
        $item = new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('date'),
            extension: [new Extension(
                url: 'http://hl7.org/fhir/StructureDefinition/minValue',
                value: new DatePrimitive(value: FHIRDate::parse('2020')),
            )],
        );
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: new DatePrimitive(value: FHIRDate::parse('2020-01-01')))],
        ));

        $report = $this->validator->validate(new QuestionnaireResource(item: [$item]), $response);

        self::assertCount(0, $report->errors(), '2020-01-01 is within minValue 2020 (first day of year)');
    }

    public function testDateLowerBoundWithYearPrecisionRejectsLastDayOfPreviousYear(): void
    {
        $item = new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('date'),
            extension: [new Extension(
                url: 'http://hl7.org/fhir/StructureDefinition/minValue',
                value: new DatePrimitive(value: FHIRDate::parse('2020')),
            )],
        );
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: new DatePrimitive(value: FHIRDate::parse('2019-12-31')))],
        ));

        $report = $this->validator->validate(new QuestionnaireResource(item: [$item]), $response);

        self::assertCount(1, $report->errors(), '2019-12-31 precedes minValue 2020 (before start of year)');
    }

    public function testQuantityBoundWithNoUcumSystemUsesNumericComparisonWhenInRange(): void
    {
        $item = new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('quantity'),
            extension: [
                new Extension(
                    url: 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-minQuantity',
                    value: new Quantity(value: '5', unit: 'Kg'),
                ),
                new Extension(
                    url: 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-maxQuantity',
                    value: new Quantity(value: '50', unit: 'Kg'),
                ),
            ],
        );
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: new Quantity(value: '10', unit: 'Kg'))],
        ));

        $report = $this->validator->validate(new QuestionnaireResource(item: [$item]), $response);

        self::assertCount(0, $report->errors(), 'answer 10 is within [5, 50] — no-UCUM numeric magnitude comparison validates in-range');
    }

    public function testQuantityBoundWithNoUcumSystemUsesNumericComparisonWhenOutOfRange(): void
    {
        // The brianpos reference (ADR-008) magnitude-compares display-unit bounds: quantity-min-max-qr
        // emits "Expected the minimum value 50 Kg, received 10 Kg". So a bound without a UCUM system
        // still enforces by numeric magnitude.
        $item = new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('quantity'),
            extension: [new Extension(
                url: 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-minQuantity',
                value: new Quantity(value: '50', unit: 'Kg'),
            )],
        );
        $response = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: new Quantity(value: '10', unit: 'Kg'))],
        ));

        $report = $this->validator->validate(new QuestionnaireResource(item: [$item]), $response);

        self::assertCount(1, $report->errors(), 'answer 10 is below min 50 — no-UCUM numeric fallback still catches out-of-range');
    }

    public function testNonRepeatingItemAnsweredTwiceWhenInProgressEmitsWarning(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1')]);
        $response      = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('a'), self::stringAnswer('b')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(0, $report->errors());
        self::assertCount(1, $report->warnings());
        self::assertStringContainsString('2 answers were provided', $report->warnings()[0]->message);
    }

    public function testNonRepeatingItemAnsweredTwiceWhenCompletedEmitsError(): void
    {
        $questionnaire = new QuestionnaireResource(item: [self::stringItem('q1')]);
        $response      = self::response('completed', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::stringAnswer('a'), self::stringAnswer('b')],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors());
        self::assertCount(0, $report->warnings());
        self::assertStringContainsString('2 answers were provided', $report->errors()[0]->message);
    }

    public function testValueBoundViolationRemainsErrorWhenInProgress(): void
    {
        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('integer'),
            extension: [new Extension(url: 'http://hl7.org/fhir/StructureDefinition/minValue', value: 10)],
        )]);
        $response = self::response('in-progress', new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [new QuestionnaireResponseItemAnswer(value: 5)],
        ));

        $report = $this->validator->validate($questionnaire, $response);

        self::assertCount(1, $report->errors(), 'value-bound violations are NOT downgraded for in-progress QR');
        self::assertStringContainsString('less than the allowed minimum', $report->errors()[0]->message);
    }
}
