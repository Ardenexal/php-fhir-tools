<?php

declare(strict_types=1);

namespace App\Tests\Sdc;

use App\Sdc\QuestionnaireResponseBuilder;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use PHPUnit\Framework\TestCase;

/**
 * M03 kill-criterion gate: proves the field-naming scheme extends to `repeats: true` items and groups
 * without ambiguity, and that it disambiguates a repeating leaf/group from a non-repeating one by the
 * *Questionnaire's* `repeats` flag — never by the shape of the posted data.
 *
 * QR shape differs between the two repeating cases even though the field naming looks parallel:
 *  - a repeating LEAF produces ONE `QuestionnaireResponseItem` with multiple `answer[]` entries.
 *  - a repeating GROUP produces ONE `QuestionnaireResponseItem` PER index (each with its own children).
 */
final class QuestionnaireResponseBuilderRepeatsTest extends TestCase
{
    /** @return list<array<string, mixed>> */
    private function items(): array
    {
        return [
            ['linkId' => 'nickname', 'type' => 'string', 'text' => 'Nickname', 'repeats' => true],
            [
                'linkId'  => 'contact',
                'type'    => 'group',
                'text'    => 'Contact',
                'repeats' => true,
                'item'    => [
                    ['linkId' => 'name', 'type' => 'string', 'text' => 'Name'],
                    ['linkId' => 'phone', 'type' => 'string', 'text' => 'Phone'],
                ],
            ],
            ['linkId' => 'singleName', 'type' => 'string', 'text' => 'Single (non-repeating)'],
        ];
    }

    public function testRepeatingLeafProducesOneItemWithMultipleAnswers(): void
    {
        $builder = new QuestionnaireResponseBuilder();
        $qr      = $builder->build($this->items(), [
            'nickname' => ['0' => 'Jay', '1' => 'JD'],
        ]);

        self::assertCount(1, $qr->item);
        $item = $qr->item[0];
        self::assertSame('nickname', (string) $item->linkId);
        self::assertCount(2, $item->answer);
        self::assertSame('Jay', $item->answer[0]->value->value);
        self::assertSame('JD', $item->answer[1]->value->value);
    }

    public function testRepeatingLeafBlankEntriesAreSkippedNotEmptyAnswers(): void
    {
        $builder = new QuestionnaireResponseBuilder();
        $qr      = $builder->build($this->items(), [
            'nickname' => ['0' => 'Jay', '1' => '', '2' => 'JD'],
        ]);

        $item = $qr->item[0];
        self::assertCount(2, $item->answer);
        self::assertSame('Jay', $item->answer[0]->value->value);
        self::assertSame('JD', $item->answer[1]->value->value);
    }

    public function testRepeatingGroupProducesOneItemPerIndex(): void
    {
        $builder = new QuestionnaireResponseBuilder();
        $qr      = $builder->build($this->items(), [
            'contact' => [
                '0' => ['name' => 'Alice', 'phone' => '111'],
                '1' => ['name' => 'Bob', 'phone' => '222'],
            ],
        ]);

        $contactItems = array_values(array_filter($qr->item, static fn (QuestionnaireResponseItem $i): bool => (string) $i->linkId === 'contact'));
        self::assertCount(2, $contactItems);

        $byName = [];
        foreach ($contactItems as $contact) {
            $children = [];
            foreach ($contact->item as $child) {
                $children[(string) $child->linkId] = $child->answer[0]->value->value;
            }
            $byName[$children['name']] = $children['phone'];
        }

        self::assertSame(['Alice' => '111', 'Bob' => '222'], $byName);
    }

    public function testSparseIndicesAfterRemoveAreNotTruncated(): void
    {
        $builder = new QuestionnaireResponseBuilder();
        // Simulates removing the middle row client-side: indices 0 and 2 remain, 1 is gone.
        $qr = $builder->build($this->items(), [
            'contact' => [
                '0' => ['name' => 'Alice', 'phone' => '111'],
                '2' => ['name' => 'Carol', 'phone' => '333'],
            ],
        ]);

        $contactItems = array_values(array_filter($qr->item, static fn (QuestionnaireResponseItem $i): bool => (string) $i->linkId === 'contact'));
        self::assertCount(2, $contactItems, 'sparse indices must not be truncated to a contiguous prefix');

        $names = array_map(
            static fn (QuestionnaireResponseItem $c): string => (string) $c->item[0]->answer[0]->value->value,
            $contactItems,
        );
        self::assertSame(['Alice', 'Carol'], $names);
    }

    public function testEntirelyBlankRepeatingGroupInstanceIsSkipped(): void
    {
        $builder = new QuestionnaireResponseBuilder();
        $qr      = $builder->build($this->items(), [
            'contact' => [
                '0' => ['name' => 'Alice', 'phone' => '111'],
                '1' => ['name' => '', 'phone' => ''],
            ],
        ]);

        $contactItems = array_values(array_filter($qr->item, static fn (QuestionnaireResponseItem $i): bool => (string) $i->linkId === 'contact'));
        self::assertCount(1, $contactItems);
    }

    public function testNonRepeatingItemsUnaffectedByRepeatHandling(): void
    {
        $builder = new QuestionnaireResponseBuilder();
        $qr      = $builder->build($this->items(), ['singleName' => 'Solo']);

        self::assertCount(1, $qr->item);
        self::assertSame('singleName', (string) $qr->item[0]->linkId);
        self::assertCount(1, $qr->item[0]->answer);
    }
}
