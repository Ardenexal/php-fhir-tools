<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EnableWhenBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemOperatorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
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
}
