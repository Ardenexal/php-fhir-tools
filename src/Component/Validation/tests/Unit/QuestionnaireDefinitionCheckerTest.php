<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItemEnableWhen;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\QuestionnaireDefinitionChecker;
use PHPUnit\Framework\TestCase;

/**
 * Shape and wording from `R4.questionnaire-enableWhen-test-invalid`: a group enabled by a question
 * nested inside it.
 */
final class QuestionnaireDefinitionCheckerTest extends TestCase
{
    public function testEnableWhenOnAnInnerQuestionIsReportedAtTheItemCarryingIt(): void
    {
        $violations = (new QuestionnaireDefinitionChecker())->check(new QuestionnaireResource(item: [
            new QuestionnaireItem(
                linkId: 'group',
                enableWhen: [new QuestionnaireItemEnableWhen(question: 'condition', answer: true)],
                item: [new QuestionnaireItem(linkId: 'condition'), new QuestionnaireItem(linkId: 'enable-when')],
            ),
        ]));

        self::assertSame([QuestionnaireDefinitionChecker::INNER_QUESTION_MESSAGE], $this->messages($violations));
        self::assertSame('item[0]', $violations[0]->path);
        self::assertSame('error', $violations[0]->severity);
    }

    /**
     * The question may sit anywhere below the item, not only among its direct children.
     */
    public function testEnableWhenOnADeeperDescendantIsReported(): void
    {
        $violations = (new QuestionnaireDefinitionChecker())->check(new QuestionnaireResource(item: [
            new QuestionnaireItem(linkId: 'outer', item: [
                new QuestionnaireItem(
                    linkId: 'group',
                    enableWhen: [new QuestionnaireItemEnableWhen(question: 'deep', answer: true)],
                    item: [new QuestionnaireItem(linkId: 'inner', item: [new QuestionnaireItem(linkId: 'deep')])],
                ),
            ]),
        ]));

        self::assertSame(['item[0].item[0]'], array_map(static fn (FHIRValidationViolation $v): string => $v->path, $violations));
    }

    public function testEnableWhenOnASiblingOrAncestorQuestionIsAccepted(): void
    {
        self::assertSame([], (new QuestionnaireDefinitionChecker())->check(new QuestionnaireResource(item: [
            new QuestionnaireItem(linkId: 'condition'),
            new QuestionnaireItem(
                linkId: 'group',
                enableWhen: [new QuestionnaireItemEnableWhen(question: 'condition', answer: true)],
                item: [new QuestionnaireItem(linkId: 'child')],
            ),
        ])));
    }

    /**
     * A Questionnaire is checked wherever it appears, here `contained` in a response.
     */
    public function testContainedQuestionnaireIsChecked(): void
    {
        $violations = (new QuestionnaireDefinitionChecker())->check(new QuestionnaireResponseResource(contained: [
            new QuestionnaireResource(item: [
                new QuestionnaireItem(
                    linkId: 'group',
                    enableWhen: [new QuestionnaireItemEnableWhen(question: 'condition', answer: true)],
                    item: [new QuestionnaireItem(linkId: 'condition')],
                ),
            ]),
        ]));

        self::assertSame(['contained[0].item[0]'], array_map(static fn (FHIRValidationViolation $v): string => $v->path, $violations));
    }

    /**
     * A finding names the item where it sits, also when the list's keys are not 0..n.
     */
    public function testFindingKeepsTheItemsOwnIndex(): void
    {
        $violations = (new QuestionnaireDefinitionChecker())->check(new QuestionnaireResource(item: [
            3 => new QuestionnaireItem(
                linkId: 'group',
                enableWhen: [new QuestionnaireItemEnableWhen(question: 'condition', answer: true)],
                item: [new QuestionnaireItem(linkId: 'condition')],
            ),
        ]));

        self::assertSame(['item[3]'], array_map(static fn (FHIRValidationViolation $v): string => $v->path, $violations));
    }

    /**
     * An item placed inside itself cannot come from a document, but a model built in code can hold
     * one, and the walk must still end.
     */
    public function testItemCycleTerminates(): void
    {
        $group = new QuestionnaireItem(
            linkId: 'group',
            enableWhen: [new QuestionnaireItemEnableWhen(question: 'condition', answer: true)],
        );
        $group->item = [$group, new QuestionnaireItem(linkId: 'condition')];

        $violations = (new QuestionnaireDefinitionChecker())->check(new QuestionnaireResource(item: [$group]));

        self::assertSame(['item[0]'], array_map(static fn (FHIRValidationViolation $v): string => $v->path, $violations));
    }

    /**
     * @param list<FHIRValidationViolation> $violations
     *
     * @return list<string>
     */
    private function messages(array $violations): array
    {
        return array_map(static fn (FHIRValidationViolation $v): string => $v->message, $violations);
    }
}
