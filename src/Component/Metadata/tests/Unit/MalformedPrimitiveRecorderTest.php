<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\MalformedPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\MalformedPrimitiveRecorder;
use PHPUnit\Framework\TestCase;

/**
 * The side table's emptiness signal and per-object read-back, as for `UnknownInputRecorder`.
 *
 * @author Ardenexal
 */
final class MalformedPrimitiveRecorderTest extends TestCase
{
    protected function setUp(): void
    {
        MalformedPrimitiveRecorder::reset();
    }

    protected function tearDown(): void
    {
        MalformedPrimitiveRecorder::reset();
    }

    public function testAnUntouchedTableIsEmpty(): void
    {
        self::assertTrue(MalformedPrimitiveRecorder::isEmpty());
        self::assertSame([], MalformedPrimitiveRecorder::forObject(new \stdClass()));
    }

    public function testRecordsAreReadBackPerObjectInOrder(): void
    {
        $target = new \stdClass();
        $first  = new MalformedPrimitive('active', MalformedPrimitive::KIND_BOOLEAN, 'TRUE');
        $second = new MalformedPrimitive('count', MalformedPrimitive::KIND_INTEGER, '1e4');

        MalformedPrimitiveRecorder::record($target, $first);
        MalformedPrimitiveRecorder::record($target, $second);

        self::assertFalse(MalformedPrimitiveRecorder::isEmpty());
        self::assertSame([$first, $second], MalformedPrimitiveRecorder::forObject($target));
        self::assertSame([], MalformedPrimitiveRecorder::forObject(new \stdClass()));
    }

    /** Records die with their objects, so the table reads empty again once they are collected. */
    public function testATableWhoseEntriesHaveBeenCollectedIsEmptyAgain(): void
    {
        $target = new \stdClass();
        MalformedPrimitiveRecorder::record($target, new MalformedPrimitive('active', MalformedPrimitive::KIND_BOOLEAN, 'yes'));

        unset($target);
        gc_collect_cycles();

        self::assertTrue(MalformedPrimitiveRecorder::isEmpty());
    }
}
