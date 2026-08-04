<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Integration\Populate;

use Ardenexal\FHIRTools\Component\Sdc\Tests\Integration\AbstractSdcConformanceTest;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Validates the conformance-oracle comparison contract for `$populate`-shaped output
 * (QuestionnaireResponse), driving {@see AbstractSdcConformanceTest} through every ignore-list rule.
 *
 * These are harness self-tests: they prove the contract accepts spec-legal divergence and still
 * rejects real content differences. Reference-seeded `$populate` conformance cases (vendored golden
 * outputs) arrive with the `sdc-populate` feature plan; this file also keeps the `sdc-populate-spec`
 * test-suite wired and non-empty until then.
 */
final class SdcPopulateOracleHarnessTest extends AbstractSdcConformanceTest
{
    public function testAcceptsReorderedItemsAnswersAndExtensions(): void
    {
        $expected = [
            'resourceType' => 'QuestionnaireResponse',
            'status'       => 'completed',
            'item'         => [
                ['linkId' => 'a', 'answer' => [['valueString' => 'x'], ['valueString' => 'y']]],
                ['linkId' => 'b', 'answer' => [['valueInteger' => 1]]],
            ],
        ];
        // Same content, items/answers reordered.
        $actual = [
            'resourceType' => 'QuestionnaireResponse',
            'status'       => 'completed',
            'item'         => [
                ['linkId' => 'b', 'answer' => [['valueInteger' => 1]]],
                ['linkId' => 'a', 'answer' => [['valueString' => 'y'], ['valueString' => 'x']]],
            ],
        ];

        $this->assertSdcConformance($expected, $actual);
    }

    public function testIgnoresOptionalTextDisplayGeneratedIdsAndTimestamps(): void
    {
        $expected = [
            'resourceType' => 'QuestionnaireResponse',
            'status'       => 'completed',
            'item'         => [
                ['linkId' => 'a', 'answer' => [['valueCoding' => ['system' => 's', 'code' => 'c']]]],
            ],
        ];
        // Adds server-generated id/authored/meta.lastUpdated and optional text/display everywhere.
        $actual = [
            'resourceType' => 'QuestionnaireResponse',
            'id'           => 'generated-123',
            'meta'         => ['lastUpdated' => '2026-07-09T00:00:00Z'],
            'authored'     => '2026-07-09T00:00:00Z',
            'status'       => 'completed',
            'item'         => [
                [
                    'linkId' => 'a',
                    'text'   => 'A human-readable label',
                    'answer' => [['valueCoding' => ['system' => 's', 'code' => 'c', 'display' => 'C label']]],
                ],
            ],
        ];

        $this->assertSdcConformance($expected, $actual);
    }

    public function testTreatsAbsentAndEmptyArraysAsEqual(): void
    {
        $expected = ['resourceType' => 'QuestionnaireResponse', 'status' => 'in-progress'];
        $actual   = ['resourceType' => 'QuestionnaireResponse', 'status' => 'in-progress', 'item' => [], 'contained' => []];

        $this->assertSdcConformance($expected, $actual);
    }

    public function testRejectsDifferingAnswerValue(): void
    {
        $expected = [
            'resourceType' => 'QuestionnaireResponse',
            'status'       => 'completed',
            'item'         => [['linkId' => 'a', 'answer' => [['valueString' => 'expected']]]],
        ];
        $actual = [
            'resourceType' => 'QuestionnaireResponse',
            'status'       => 'completed',
            'item'         => [['linkId' => 'a', 'answer' => [['valueString' => 'WRONG']]]],
        ];

        $this->expectException(ExpectationFailedException::class);
        $this->assertSdcConformance($expected, $actual);
    }
}
