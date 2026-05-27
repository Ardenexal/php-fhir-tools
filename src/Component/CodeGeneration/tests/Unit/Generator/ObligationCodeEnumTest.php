<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\Metadata\ObligationCode;
use PHPUnit\Framework\TestCase;

/**
 * Verifies ObligationCode enum covers all 46 FHIR obligation codes with correct string values
 * and method behaviour.
 */
class ObligationCodeEnumTest extends TestCase
{
    public function testFromStringResolvesCorrectly(): void
    {
        $code = ObligationCode::from('SHALL:populate');
        self::assertSame(ObligationCode::SHALL_POPULATE, $code);
    }

    public function testFromStringResolvesAllLeafCodes(): void
    {
        $samples = [
            'SHALL:populate'           => ObligationCode::SHALL_POPULATE,
            'SHOULD:display'           => ObligationCode::SHOULD_DISPLAY,
            'MAY:able-to-populate'     => ObligationCode::MAY_ABLE_TO_POPULATE,
            'SHALL:populate-if-known'  => ObligationCode::SHALL_POPULATE_IF_KNOWN,
            'SHOULD:handle'            => ObligationCode::SHOULD_HANDLE,
            'MAY:ignore'               => ObligationCode::MAY_IGNORE,
        ];

        foreach ($samples as $value => $expected) {
            self::assertSame($expected, ObligationCode::from($value), "Failed resolving '{$value}'");
        }
    }

    public function testCompositeCodesResolve(): void
    {
        self::assertSame(ObligationCode::V2_RE, ObligationCode::from('v2-re'));
        self::assertSame(ObligationCode::IHE_R2, ObligationCode::from('ihe-r2'));
        self::assertSame(ObligationCode::STD, ObligationCode::from('std'));
    }

    public function testTotalCodeCount(): void
    {
        self::assertCount(46, ObligationCode::cases(), 'ObligationCode enum must have exactly 46 cases');
    }

    public function testIsPopulationObligationTrueForPopulate(): void
    {
        self::assertTrue(ObligationCode::SHALL_POPULATE->isPopulationObligation());
        self::assertTrue(ObligationCode::SHOULD_POPULATE->isPopulationObligation());
        self::assertTrue(ObligationCode::SHALL_ABLE_TO_POPULATE->isPopulationObligation());
        self::assertTrue(ObligationCode::SHOULD_ABLE_TO_POPULATE->isPopulationObligation());
        self::assertTrue(ObligationCode::MAY_ABLE_TO_POPULATE->isPopulationObligation());
        self::assertTrue(ObligationCode::SHALL_POPULATE_IF_KNOWN->isPopulationObligation());
        self::assertTrue(ObligationCode::SHOULD_POPULATE_IF_KNOWN->isPopulationObligation());
    }

    public function testIsPopulationObligationTrueForCompositeCodes(): void
    {
        self::assertTrue(ObligationCode::V2_RE->isPopulationObligation());
        self::assertTrue(ObligationCode::IHE_R2->isPopulationObligation());
        self::assertTrue(ObligationCode::STD->isPopulationObligation());
    }

    public function testIsPopulationObligationFalseForBehaviourOnlyCodes(): void
    {
        self::assertFalse(ObligationCode::SHALL_DISPLAY->isPopulationObligation());
        self::assertFalse(ObligationCode::SHOULD_HANDLE->isPopulationObligation());
        self::assertFalse(ObligationCode::SHALL_PERSIST->isPopulationObligation());
        self::assertFalse(ObligationCode::MAY_IGNORE->isPopulationObligation());
    }

    public function testIsBehaviourOnlyTrueForDisplay(): void
    {
        self::assertTrue(ObligationCode::SHALL_DISPLAY->isBehaviourOnly());
        self::assertTrue(ObligationCode::SHOULD_DISPLAY->isBehaviourOnly());
        self::assertTrue(ObligationCode::MAY_DISPLAY->isBehaviourOnly());
    }

    public function testIsBehaviourOnlyTrueForNonPopulationCodes(): void
    {
        self::assertTrue(ObligationCode::SHALL_PERSIST->isBehaviourOnly());
        self::assertTrue(ObligationCode::SHALL_HANDLE->isBehaviourOnly());
        self::assertTrue(ObligationCode::SHALL_PROCESS->isBehaviourOnly());
        self::assertTrue(ObligationCode::SHALL_PRINT->isBehaviourOnly());
        self::assertTrue(ObligationCode::SHALL_USER_INPUT->isBehaviourOnly());
        self::assertTrue(ObligationCode::SHALL_IGNORE->isBehaviourOnly());
        self::assertTrue(ObligationCode::SHALL_NO_ALTER->isBehaviourOnly());
    }

    public function testIsBehaviourOnlyFalseForPopulate(): void
    {
        self::assertFalse(ObligationCode::SHALL_POPULATE->isBehaviourOnly());
        self::assertFalse(ObligationCode::SHALL_ABLE_TO_POPULATE->isBehaviourOnly());
        self::assertFalse(ObligationCode::SHALL_POPULATE_IF_KNOWN->isBehaviourOnly());
    }

    public function testStrengthReturnsCorrectValues(): void
    {
        self::assertSame('SHALL', ObligationCode::SHALL_POPULATE->strength());
        self::assertSame('SHOULD', ObligationCode::SHOULD_DISPLAY->strength());
        self::assertSame('MAY', ObligationCode::MAY_ABLE_TO_POPULATE->strength());
        self::assertSame('SHALL', ObligationCode::SHALL_POPULATE_IF_KNOWN->strength());
    }

    public function testStrengthThrowsForCompositeCode(): void
    {
        $this->expectException(\LogicException::class);
        ObligationCode::V2_RE->strength();
    }

    public function testIsCompositeReturnsTrueForCompositeCodes(): void
    {
        self::assertTrue(ObligationCode::V2_RE->isComposite());
        self::assertTrue(ObligationCode::IHE_R2->isComposite());
        self::assertTrue(ObligationCode::STD->isComposite());
    }

    public function testIsCompositeReturnsFalseForLeafCodes(): void
    {
        self::assertFalse(ObligationCode::SHALL_POPULATE->isComposite());
        self::assertFalse(ObligationCode::MAY_DISPLAY->isComposite());
    }

    public function testTryFromReturnsNullForUnknownCode(): void
    {
        self::assertNull(ObligationCode::tryFrom('UNKNOWN:code'));
    }
}
