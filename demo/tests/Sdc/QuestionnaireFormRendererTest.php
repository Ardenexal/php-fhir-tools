<?php

declare(strict_types=1);

namespace App\Tests\Sdc;

use App\Sdc\QuestionnaireFormRenderer;
use App\Sdc\QuestionnaireResponseBuilder;
use PHPUnit\Framework\TestCase;
use App\Sdc\QuestionnaireItemCodec;

/**
 * Proves the renderer's QR -> form-value direction is the true inverse of the builder's
 * form-value -> QR direction (both funnel through the same {@see QuestionnaireItemCodec}),
 * so a populated `QuestionnaireResponse` prefills the exact values that produced an equivalent one.
 *
 * Non-repeating fields/groups carry exactly one entry in `values`/`instances` — the uniform
 * repeats-or-not shape described on {@see QuestionnaireFormRenderer}.
 */
final class QuestionnaireFormRendererTest extends TestCase
{
    /** @return list<array<string, mixed>> */
    private function items(): array
    {
        return [
            ['linkId' => 'name', 'type' => 'string', 'text' => 'Name'],
            ['linkId' => 'active', 'type' => 'boolean', 'text' => 'Active'],
            ['linkId' => 'age', 'type' => 'integer', 'text' => 'Age'],
            ['linkId' => 'dob', 'type' => 'date', 'text' => 'DOB'],
            [
                'linkId'       => 'status',
                'type'         => 'choice',
                'text'         => 'Status',
                'answerOption' => [
                    ['valueCoding' => ['system' => 'http://example.org/cs', 'code' => 'open', 'display' => 'Open']],
                    ['valueCoding' => ['system' => 'http://example.org/cs', 'code' => 'closed', 'display' => 'Closed']],
                ],
            ],
            [
                'linkId' => 'address',
                'type'   => 'group',
                'text'   => 'Address',
                'item'   => [
                    ['linkId' => 'city', 'type' => 'string', 'text' => 'City'],
                ],
            ],
        ];
    }

    public function testRenderFromResponseIsTheInverseOfBuild(): void
    {
        $answers = [
            'name'    => 'Jane Doe',
            'active'  => 'true',
            'age'     => '31',
            'dob'     => '1993-04-02',
            'status'  => '1',
            'address' => ['city' => 'Melbourne'],
        ];

        $builder  = new QuestionnaireResponseBuilder();
        $response = $builder->build($this->items(), $answers);

        $renderer = new QuestionnaireFormRenderer();
        $fields   = $renderer->renderFromResponse($this->items(), $response);

        $byLinkId = [];
        foreach ($fields as $field) {
            $byLinkId[$field['linkId']] = $field;
        }

        self::assertSame(['Jane Doe'], $byLinkId['name']['values']);
        self::assertSame(['true'], $byLinkId['active']['values']);
        self::assertSame(['31'], $byLinkId['age']['values']);
        self::assertSame(['1993-04-02'], $byLinkId['dob']['values']);
        self::assertSame(['1'], $byLinkId['status']['values']); // option index, not the display string

        self::assertCount(1, $byLinkId['address']['instances']);
        $addressChildren = [];
        foreach ($byLinkId['address']['instances'][0] as $child) {
            $addressChildren[$child['linkId']] = $child;
        }
        self::assertSame(['Melbourne'], $addressChildren['city']['values']);
    }

    public function testRenderBlankHasEmptyValuesAndChoiceOptions(): void
    {
        $renderer = new QuestionnaireFormRenderer();
        $fields   = $renderer->renderBlank($this->items());

        $byLinkId = [];
        foreach ($fields as $field) {
            $byLinkId[$field['linkId']] = $field;
        }

        self::assertSame([''], $byLinkId['name']['values']);
        self::assertSame(
            [['index' => '0', 'label' => 'Open'], ['index' => '1', 'label' => 'Closed']],
            $byLinkId['status']['options'],
        );
        self::assertCount(1, $byLinkId['address']['instances']);
    }

    public function testRenderBlankRepeatingFieldAndGroupStartWithOneRow(): void
    {
        $items = [
            ['linkId' => 'nickname', 'type' => 'string', 'text' => 'Nickname', 'repeats' => true],
            [
                'linkId'  => 'contact',
                'type'    => 'group',
                'text'    => 'Contact',
                'repeats' => true,
                'item'    => [['linkId' => 'name', 'type' => 'string', 'text' => 'Name']],
            ],
        ];

        $renderer = new QuestionnaireFormRenderer();
        $fields   = $renderer->renderBlank($items);

        $byLinkId = [];
        foreach ($fields as $field) {
            $byLinkId[$field['linkId']] = $field;
        }

        self::assertSame([''], $byLinkId['nickname']['values']);
        self::assertTrue($byLinkId['nickname']['repeats']);
        self::assertCount(1, $byLinkId['contact']['instances']);
        self::assertTrue($byLinkId['contact']['repeats']);
    }

    public function testRenderFromResponsePrefillsEveryRepeatingGroupInstance(): void
    {
        $items = [
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
        ];

        $answers = [
            'contact' => [
                '0' => ['name' => 'Alice', 'phone' => '111'],
                '1' => ['name' => 'Bob', 'phone' => '222'],
            ],
        ];

        $builder  = new QuestionnaireResponseBuilder();
        $response = $builder->build($items, $answers);

        $renderer = new QuestionnaireFormRenderer();
        $fields   = $renderer->renderFromResponse($items, $response);

        $contactField = $fields[0];
        self::assertCount(2, $contactField['instances']);

        $names = [];
        foreach ($contactField['instances'] as $instance) {
            $byLinkId       = [];
            foreach ($instance as $child) {
                $byLinkId[$child['linkId']] = $child['values'][0];
            }
            $names[] = $byLinkId['name'];
        }
        self::assertSame(['Alice', 'Bob'], $names);
    }

    public function testRenderFromResponsePrefillsEveryRepeatingLeafValue(): void
    {
        $items = [
            ['linkId' => 'nickname', 'type' => 'string', 'text' => 'Nickname', 'repeats' => true],
        ];

        $answers  = ['nickname' => ['0' => 'Jay', '1' => 'JD']];
        $builder  = new QuestionnaireResponseBuilder();
        $response = $builder->build($items, $answers);

        $renderer = new QuestionnaireFormRenderer();
        $fields   = $renderer->renderFromResponse($items, $response);

        self::assertSame(['Jay', 'JD'], $fields[0]['values']);
    }

    public function testAnswerValueSetBoundChoiceItemFlagsHasAnswerValueSetInsteadOfOptions(): void
    {
        $items = [
            [
                'linkId'         => 'maritalStatus',
                'type'           => 'choice',
                'text'           => 'Marital status',
                'answerValueSet' => 'http://hl7.org/fhir/ValueSet/marital-status',
            ],
        ];

        $renderer = new QuestionnaireFormRenderer();
        $fields   = $renderer->renderBlank($items);

        self::assertTrue($fields[0]['hasAnswerValueSet']);
        self::assertSame([], $fields[0]['options']);
    }

    public function testAnswerOptionChoiceItemDoesNotFlagHasAnswerValueSet(): void
    {
        $fields = (new QuestionnaireFormRenderer())->renderBlank($this->items());

        $byLinkId = [];
        foreach ($fields as $field) {
            $byLinkId[$field['linkId']] = $field;
        }

        self::assertFalse($byLinkId['status']['hasAnswerValueSet']);
    }

    public function testEnableWhenFirstConditionIsPassedThroughForTheClientToEvaluate(): void
    {
        $items = [
            ['linkId' => 'trigger', 'type' => 'boolean', 'text' => 'Trigger'],
            [
                'linkId'     => 'dependent',
                'type'       => 'string',
                'text'       => 'Dependent',
                'enableWhen' => [
                    ['question' => 'trigger', 'operator' => '=', 'answerBoolean' => true],
                ],
            ],
        ];

        $renderer = new QuestionnaireFormRenderer();
        $fields   = $renderer->renderBlank($items);

        self::assertNull($fields[0]['enableWhen']);
        self::assertSame(
            ['question' => 'trigger', 'operator' => '=', 'answerBoolean' => true],
            $fields[1]['enableWhen'],
        );
    }
}
