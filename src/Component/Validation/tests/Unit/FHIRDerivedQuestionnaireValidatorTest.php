<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItemAnswerOption;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRDerivedQuestionnaireValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FHIRDerivedQuestionnaireValidator::class)]
final class FHIRDerivedQuestionnaireValidatorTest extends TestCase
{
    private FHIRDerivedQuestionnaireValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new FHIRDerivedQuestionnaireValidator();
    }

    public function testCompliesWithTypeChangeProducesError(): void
    {
        $base    = $this->questionnaire([$this->item('a1', 'string')]);
        $derived = $this->questionnaire([$this->item('a1', 'integer')], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(1, $report->errors());
        self::assertStringContainsString("type 'string'", $report->errors()[0]->message);
        self::assertStringContainsString("'integer'", $report->errors()[0]->message);
    }

    public function testCompliesWithRequiredWeakeningProducesError(): void
    {
        $base    = $this->questionnaire([$this->item('a1', 'string', required: true)]);
        $derived = $this->questionnaire([$this->item('a1', 'string', required: false)], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(1, $report->errors());
        self::assertStringContainsString('required in base', $report->errors()[0]->message);
    }

    public function testCompliesWithRequiredTighteningNoError(): void
    {
        $base    = $this->questionnaire([$this->item('a1', 'string', required: false)]);
        $derived = $this->questionnaire([$this->item('a1', 'string', required: true)], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(0, $report->errors());
    }

    public function testCompliesWithRepeatsLooseningProducesError(): void
    {
        $base    = $this->questionnaire([$this->item('a1', 'string', repeats: false)]);
        $derived = $this->questionnaire([$this->item('a1', 'string', repeats: true)], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(1, $report->errors());
        self::assertStringContainsString('does not repeat in base', $report->errors()[0]->message);
    }

    public function testCompliesWithRepeatsTighteningNoError(): void
    {
        $base    = $this->questionnaire([$this->item('a1', 'string', repeats: true)]);
        $derived = $this->questionnaire([$this->item('a1', 'string', repeats: false)], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(0, $report->errors());
    }

    public function testCompliesWithNewLinkIdProducesError(): void
    {
        $base    = $this->questionnaire([$this->item('a1', 'string')]);
        $derived = $this->questionnaire([$this->item('a1', 'string'), $this->item('a2', 'string')], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(1, $report->errors());
        self::assertStringContainsString("linkId 'a2'", $report->errors()[0]->message);
    }

    public function testExtendsNewLinkIdNoError(): void
    {
        $base    = $this->questionnaire([$this->item('a1', 'string')]);
        $derived = $this->questionnaire([$this->item('a1', 'string'), $this->item('a2', 'string')], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'extends');

        self::assertCount(0, $report->errors());
    }

    public function testExtendsTypeChangeProducesError(): void
    {
        $base    = $this->questionnaire([$this->item('a1', 'string')]);
        $derived = $this->questionnaire([$this->item('a1', 'integer')], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'extends');

        self::assertCount(1, $report->errors());
        self::assertStringContainsString("type 'string'", $report->errors()[0]->message);
    }

    public function testAnswerOptionNotInBaseProducesError(): void
    {
        $sys  = 'http://example.org/cs';
        $base = $this->questionnaire([
            $this->item('b1', 'coding', answerOptions: [
                $this->codingOption($sys, 'c1'),
                $this->codingOption($sys, 'c2'),
            ]),
        ]);
        $derived = $this->questionnaire([
            $this->item('b1', 'coding', answerOptions: [
                $this->codingOption($sys, 'c1'),
                $this->codingOption($sys, 'c3'),
            ]),
        ], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(1, $report->errors());
        self::assertStringContainsString("answerOption 'c3'", $report->errors()[0]->message);
    }

    public function testInspiredByReturnsEmptyReport(): void
    {
        $base    = $this->questionnaire([$this->item('a1', 'string')]);
        $derived = $this->questionnaire([$this->item('a1', 'integer')], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'inspiredBy');

        self::assertCount(0, $report->violations);
    }

    public function testNoDerivedFromReturnsEmptyReport(): void
    {
        $base    = $this->questionnaire([$this->item('a1', 'string')]);
        $derived = $this->questionnaire([$this->item('a1', 'integer')], hasDerivedFrom: false);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(0, $report->violations);
    }

    public function testNestedItemViolationPathIsCorrect(): void
    {
        $base = $this->questionnaire([
            $this->item('a3', 'group', children: [
                $this->item('b1', 'coding'),
            ]),
        ]);
        $derived = $this->questionnaire([
            $this->item('a3', 'group', children: [
                $this->item('b1', 'string'),
            ]),
        ], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(1, $report->errors());
        self::assertStringContainsString('item[0].item[0]', $report->errors()[0]->path);
        self::assertStringContainsString("type 'coding'", $report->errors()[0]->message);
    }

    public function testExtractDerivationTypeFromJsonReturnsCode(): void
    {
        $decoded = [
            'derivedFrom'  => ['http://example.org/base'],
            '_derivedFrom' => [
                [
                    'extension' => [
                        [
                            'url'         => 'http://hl7.org/fhir/StructureDefinition/questionnaire-derivationType',
                            'valueCoding' => ['system' => 'http://hl7.org/fhir/questionnaire-derivationType', 'code' => 'extends'],
                        ],
                    ],
                ],
            ],
        ];

        self::assertSame('extends', FHIRDerivedQuestionnaireValidator::extractDerivationTypeFromJson($decoded));
    }

    public function testExtractDerivationTypeFromJsonDefaultsToCompliesWith(): void
    {
        self::assertSame('compliesWith', FHIRDerivedQuestionnaireValidator::extractDerivationTypeFromJson([]));
    }

    public function testMinOccursWeakeningProducesError(): void
    {
        $base    = $this->questionnaire([$this->itemWithBounds('i1', minOccurs: 2)]);
        $derived = $this->questionnaire([$this->itemWithBounds('i1', minOccurs: 1)], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(1, $report->errors());
        self::assertStringContainsString('minOccurs', $report->errors()[0]->message);
    }

    public function testMinOccursTighteningNoError(): void
    {
        $base    = $this->questionnaire([$this->itemWithBounds('i1', minOccurs: 2)]);
        $derived = $this->questionnaire([$this->itemWithBounds('i1', minOccurs: 3)], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(0, $report->errors());
    }

    public function testMaxOccursWideningProducesError(): void
    {
        $base    = $this->questionnaire([$this->itemWithBounds('i1', maxOccurs: 5)]);
        $derived = $this->questionnaire([$this->itemWithBounds('i1', maxOccurs: 10)], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(1, $report->errors());
        self::assertStringContainsString('maxOccurs', $report->errors()[0]->message);
    }

    public function testMaxOccursNarrowingNoError(): void
    {
        $base    = $this->questionnaire([$this->itemWithBounds('i1', maxOccurs: 5)]);
        $derived = $this->questionnaire([$this->itemWithBounds('i1', maxOccurs: 4)], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(0, $report->errors());
    }

    public function testBaseHasNoBoundDerivedSetsOneNoError(): void
    {
        $base    = $this->questionnaire([$this->item('i1', 'string')]);
        $derived = $this->questionnaire([$this->itemWithBounds('i1', minOccurs: 1, maxOccurs: 3)], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(0, $report->errors());
    }

    public function testBothBoundsAbsentNoError(): void
    {
        $base    = $this->questionnaire([$this->item('i1', 'string')]);
        $derived = $this->questionnaire([$this->item('i1', 'string')], hasDerivedFrom: true);

        $report = $this->validator->validate($derived, $base, 'compliesWith');

        self::assertCount(0, $report->errors());
    }

    // --- Helpers ---

    /**
     * @param array<QuestionnaireItem> $items
     */
    private function questionnaire(array $items, bool $hasDerivedFrom = false): QuestionnaireResource
    {
        return new QuestionnaireResource(
            item: $items,
            derivedFrom: $hasDerivedFrom
                ? [new CanonicalPrimitive(value: 'http://example.org/base')]
                : [],
        );
    }

    /**
     * @param array<QuestionnaireItemAnswerOption> $answerOptions
     * @param array<QuestionnaireItem>             $children
     */
    private function item(
        string $linkId,
        string $type,
        bool $required = false,
        bool $repeats = false,
        array $answerOptions = [],
        array $children = [],
    ): QuestionnaireItem {
        return new QuestionnaireItem(
            linkId: $linkId,
            type: new QuestionnaireItemTypeType($type),
            required: $required,
            repeats: $repeats,
            answerOption: $answerOptions,
            item: $children,
        );
    }

    private function codingOption(string $system, string $code): QuestionnaireItemAnswerOption
    {
        return new QuestionnaireItemAnswerOption(
            value: new Coding(
                system: new UriPrimitive(value: $system),
                code: new CodePrimitive(value: $code),
            ),
        );
    }

    private function itemWithBounds(string $linkId, ?int $minOccurs = null, ?int $maxOccurs = null): QuestionnaireItem
    {
        $extensions = [];

        if ($minOccurs !== null) {
            $extensions[] = new Extension(
                url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-minOccurs',
                value: $minOccurs,
            );
        }

        if ($maxOccurs !== null) {
            $extensions[] = new Extension(
                url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-maxOccurs',
                value: $maxOccurs,
            );
        }

        return new QuestionnaireItem(
            linkId: $linkId,
            type: new QuestionnaireItemTypeType('string'),
            extension: $extensions,
        );
    }
}
