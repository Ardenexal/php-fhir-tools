<?php

declare(strict_types=1);

namespace App\Tests\Sdc;

use App\Sdc\QuestionnaireResponseBuilder;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use PHPUnit\Framework\TestCase;

/**
 * M02 kill-criterion gate: proves the `answers[<linkId>]` / `answers[<groupLinkId>][<childLinkId>]`
 * field-naming scheme survives dotted linkIds (`1.1`, `1.1.2` — common real-world SDC style) nested
 * under a non-repeating group, across every covered item type, before any controller/Twig is written.
 */
final class QuestionnaireResponseBuilderTest extends TestCase
{
    /** @return list<array<string, mixed>> */
    private function adversarialQuestionnaireItems(): array
    {
        return [
            ['linkId' => '1', 'type' => 'string', 'text' => 'String'],
            ['linkId' => '1.1', 'type' => 'text', 'text' => 'Text'],
            ['linkId' => '2', 'type' => 'boolean', 'text' => 'Boolean'],
            ['linkId' => '3', 'type' => 'integer', 'text' => 'Integer'],
            ['linkId' => '4', 'type' => 'decimal', 'text' => 'Decimal'],
            ['linkId' => '5', 'type' => 'date', 'text' => 'Date'],
            ['linkId' => '6', 'type' => 'dateTime', 'text' => 'DateTime'],
            [
                'linkId'       => '7',
                'type'         => 'choice',
                'text'         => 'Choice',
                'answerOption' => [
                    ['valueCoding' => ['system' => 'http://example.org/cs', 'code' => 'a', 'display' => 'Option A']],
                    ['valueCoding' => ['system' => 'http://example.org/cs', 'code' => 'b', 'display' => 'Option B']],
                ],
            ],
            ['linkId' => '8', 'type' => 'display', 'text' => 'A read-only display item'],
            [
                'linkId' => '1.1.2',
                'type'   => 'group',
                'text'   => 'Nested group (dotted linkId)',
                'item'   => [
                    ['linkId' => '1.1.2.1', 'type' => 'string', 'text' => 'Nested string'],
                    ['linkId' => '1.1.2.2', 'type' => 'integer', 'text' => 'Nested integer'],
                ],
            ],
        ];
    }

    /** Simulates the array shape PHP produces from `answers[1.1.2][1.1.2.1]`-style POST field names. */
    public function testDottedLinkIdsAndNestedGroupSurviveFormParsing(): void
    {
        $body = http_build_query([
            'answers' => [
                '1'       => 'hello',
                '1.1'     => 'world',
                '2'       => 'true',
                '3'       => '42',
                '4'       => '3.50',
                '5'       => '1990-05-12',
                '6'       => '1990-05-12T13:45',
                '7'       => '1',
                '1.1.2'   => [
                    '1.1.2.1' => 'nested value',
                    '1.1.2.2' => '7',
                ],
            ],
        ]);
        parse_str($body, $parsed);
        /** @var array<string, mixed> $answers */
        $answers = $parsed['answers'];

        // The naming scheme itself: dotted keys must round-trip through PHP's bracket-array parsing
        // unmangled — this is the literal kill-criterion check.
        self::assertSame('world', $answers['1.1']);
        self::assertArrayHasKey('1.1.2', $answers);
        self::assertSame('nested value', $answers['1.1.2']['1.1.2.1']);

        $builder = new QuestionnaireResponseBuilder();
        $qr      = $builder->build($this->adversarialQuestionnaireItems(), $answers);

        $byLinkId = [];
        foreach ($qr->item as $item) {
            self::assertInstanceOf(QuestionnaireResponseItem::class, $item);
            $byLinkId[(string) $item->linkId] = $item;
        }

        // display never produces a QR item.
        self::assertArrayNotHasKey('8', $byLinkId);

        self::assertInstanceOf(StringPrimitive::class, $byLinkId['1']->answer[0]->value);
        self::assertSame('hello', $byLinkId['1']->answer[0]->value->value);

        self::assertInstanceOf(StringPrimitive::class, $byLinkId['1.1']->answer[0]->value);
        self::assertSame('world', $byLinkId['1.1']->answer[0]->value->value);

        self::assertTrue($byLinkId['2']->answer[0]->value);
        self::assertSame(42, $byLinkId['3']->answer[0]->value);
        self::assertSame('3.50', $byLinkId['4']->answer[0]->value); // raw string -> valueDecimal, not cast to float

        self::assertInstanceOf(DatePrimitive::class, $byLinkId['5']->answer[0]->value);
        self::assertInstanceOf(DateTimePrimitive::class, $byLinkId['6']->answer[0]->value);

        self::assertInstanceOf(Coding::class, $byLinkId['7']->answer[0]->value);
        self::assertSame('b', $byLinkId['7']->answer[0]->value->code->value);

        $group = $byLinkId['1.1.2'];
        self::assertSame([], $group->answer);
        $nestedByLinkId = [];
        foreach ($group->item as $childItem) {
            $nestedByLinkId[(string) $childItem->linkId] = $childItem;
        }
        self::assertSame('nested value', $nestedByLinkId['1.1.2.1']->answer[0]->value->value);
        self::assertSame(7, $nestedByLinkId['1.1.2.2']->answer[0]->value);
    }

    public function testUnansweredNonGroupItemsAreOmitted(): void
    {
        $builder = new QuestionnaireResponseBuilder();
        $qr      = $builder->build($this->adversarialQuestionnaireItems(), ['1' => 'only this one']);

        self::assertCount(1, $qr->item);
        self::assertSame('1', (string) $qr->item[0]->linkId);
    }

    public function testEntirelyBlankGroupIsPruned(): void
    {
        $builder = new QuestionnaireResponseBuilder();
        $qr      = $builder->build($this->adversarialQuestionnaireItems(), []);

        self::assertSame([], $qr->item);
    }

    public function testGroupWithOneAnsweredDescendantIsEmittedInFull(): void
    {
        $builder = new QuestionnaireResponseBuilder();
        $qr      = $builder->build($this->adversarialQuestionnaireItems(), [
            '1.1.2' => ['1.1.2.1' => 'only the nested string is answered'],
        ]);

        self::assertCount(1, $qr->item);
        $group = $qr->item[0];
        self::assertSame('1.1.2', (string) $group->linkId);
        // Only the answered child is present — the sibling nested integer stayed unanswered and
        // is simply omitted (unlike the group itself, a leaf item's absence is not "empty").
        self::assertCount(1, $group->item);
        self::assertSame('1.1.2.1', (string) $group->item[0]->linkId);
    }
}
