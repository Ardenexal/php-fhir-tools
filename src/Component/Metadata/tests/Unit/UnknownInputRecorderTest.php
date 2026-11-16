<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\UnknownInput;
use Ardenexal\FHIRTools\Component\Metadata\UnknownInputRecorder;
use PHPUnit\Framework\TestCase;

/**
 * The side table's emptiness signal, which a reader uses to skip a reflection walk.
 *
 * @author Ardenexal
 */
class UnknownInputRecorderTest extends TestCase
{
    /** Each test starts from a table nothing has written to. */
    protected function setUp(): void
    {
        UnknownInputRecorder::reset();
    }

    /** The table is static, so it must not survive into another test. */
    protected function tearDown(): void
    {
        UnknownInputRecorder::reset();
    }

    /** Nothing recorded yet, so a reader may skip the walk. */
    public function testAnUntouchedTableIsEmpty(): void
    {
        self::assertTrue(UnknownInputRecorder::isEmpty());
    }

    /** A live record makes the table non-empty and readable back. */
    public function testATableHoldingARecordIsNotEmpty(): void
    {
        $target = new \stdClass();
        UnknownInputRecorder::record($target, new UnknownInput('mode1', UnknownInput::FORMAT_XML));

        self::assertFalse(UnknownInputRecorder::isEmpty());
        self::assertCount(1, UnknownInputRecorder::forObject($target));
    }

    /**
     * The signal must recover once the objects it was keyed on are gone.
     *
     * `isEmpty()` used to test only whether the backing field was null, which latched false for the rest
     * of the process: the `WeakMap` stays allocated after every entry it held has been collected. One
     * document carrying unknown input therefore cost every later clean resource the full reflection walk
     * that this signal exists to skip — permanently, in any long-running worker.
     */
    public function testATableWhoseEntriesHaveBeenCollectedIsEmptyAgain(): void
    {
        $target = new \stdClass();
        UnknownInputRecorder::record($target, new UnknownInput('mode1', UnknownInput::FORMAT_XML));
        self::assertFalse(UnknownInputRecorder::isEmpty());

        unset($target);
        gc_collect_cycles();

        self::assertTrue(
            UnknownInputRecorder::isEmpty(),
            'the table still reports records after the only object it was keyed on was collected',
        );
    }

    /** An object with no records reads as an empty list, not an error. */
    public function testReadingAnObjectNeverRecordedAgainstYieldsNothing(): void
    {
        self::assertSame([], UnknownInputRecorder::forObject(new \stdClass()));
    }
}
