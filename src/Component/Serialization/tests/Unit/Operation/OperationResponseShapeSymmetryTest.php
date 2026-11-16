<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Operation;

use Ardenexal\FHIRTools\Component\Models\R5\Operation\ListFind\ListFindOperation;
use Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemLookup\CodeSystemLookupOutput;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationMappingException;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationParameterMapper;
use PHPUnit\Framework\TestCase;

/**
 * `NoOutput` must mean the same thing in both directions.
 *
 * `fromResponse()` treats a body arriving for a `NoOutput` operation as a contract violation and
 * throws, with the reasoning stated on the arm itself: "A body arriving here is a contract violation
 * worth surfacing, not ignoring." `toResponse()` had the mirror case as a bare `=> null`, so an
 * output computed for a `NoOutput` operation was discarded without a word.
 *
 * It is the same disagreement between an operation's declared shape and the object in hand, and it
 * should be reported the same way. A handler that returns something for `List/$find` has a bug — in
 * its own logic or in the shape metadata — and silently dropping the value hides which.
 *
 * `ListFindOperation` is the fixture: R5 `List/$find`, `outputShape: NoOutput`.
 */
final class OperationResponseShapeSymmetryTest extends TestCase
{
    private function mapper(): OperationParameterMapper
    {
        return OperationParameterMapper::createDefault(FhirVersion::R5);
    }

    /**
     * The read direction already did this. Pinned so the symmetry cannot regress from either side.
     */
    public function testFromResponseRejectsABodyForANoOutputOperation(): void
    {
        $this->expectException(OperationMappingException::class);

        $this->mapper()->fromResponse(
            new CodeSystemLookupOutput(name: 'x', display: 'y'),
            ListFindOperation::class,
        );
    }

    /**
     * The write direction must now do the same rather than returning null.
     */
    public function testToResponseRejectsAnOutputForANoOutputOperation(): void
    {
        $this->expectException(OperationMappingException::class);

        $this->mapper()->toResponse(
            new CodeSystemLookupOutput(name: 'x', display: 'y'),
            ListFindOperation::class,
        );
    }

    /**
     * The legitimate case still works: nothing in, nothing out, no complaint.
     *
     * Without this the fix could be "always throw for NoOutput", which would break every correct
     * caller.
     */
    public function testToResponseStillReturnsNullWhenThereIsNoOutput(): void
    {
        self::assertNull($this->mapper()->toResponse(null, ListFindOperation::class));
    }

    public function testFromResponseStillReturnsNullForAnEmptyBody(): void
    {
        self::assertNull($this->mapper()->fromResponse(null, ListFindOperation::class));
    }
}
