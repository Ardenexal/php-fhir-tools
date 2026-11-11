<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Function\IifFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for IifFunction: which focus the criterion and branches are evaluated against.
 */
#[CoversClass(IifFunction::class)]
final class IifFunctionTest extends TestCase
{
    /**
     * The outer focus has an `active` element, so a criterion that reads the outer focus
     * instead of the iif input picks the other branch.
     *
     * @var array<string, mixed>
     */
    private const array PATIENT = ['resourceType' => 'Patient', 'active' => true];

    private FHIRPathService $service;

    protected function setUp(): void
    {
        $this->service = new FHIRPathService();
    }

    /**
     * @return iterable<string, array{string, list<mixed>}>
     */
    public static function emptyInputCases(): iterable
    {
        yield 'empty() sees the empty literal' => ["{}.iif(empty(), 'was empty', 'not empty')", ['was empty']];
        yield 'exists() sees the missing element' => ["missing.iif(exists(), 'exists', 'absent')", ['absent']];
        yield 'criterion does not navigate from the outer focus' => ["missing.iif(active, 'read outer', 'did not')", ['did not']];
        yield '$this is the empty input' => ["missing.iif(\$this.exists(), 'outer this', 'empty this')", ['empty this']];
        yield 'true branch does not navigate from the outer focus' => ['missing.iif(true, active)', []];
        yield 'otherwise branch does not navigate from the outer focus' => ['missing.iif(false, true, active)', []];
    }

    /**
     * @param list<mixed> $expected
     */
    #[DataProvider('emptyInputCases')]
    public function testEmptyInputIsTheFocusOfCriterionAndBranches(string $expression, array $expected): void
    {
        self::assertSame($expected, $this->service->evaluate($expression, self::PATIENT)->toArray());
    }

    public function testSingleItemInputIsTheFocusOfCriterionAndBranches(): void
    {
        self::assertSame(['yes'], $this->service->evaluate("active.iif(\$this, 'yes', 'no')", self::PATIENT)->toArray());
        self::assertSame([true], $this->service->evaluate('active.iif(true, $this)', self::PATIENT)->toArray());
    }

    public function testBareIifStillUsesTheCurrentFocus(): void
    {
        // Without a member-access input, iif() runs on the current node, so `active` is the Patient's.
        self::assertSame(['active'], $this->service->evaluate("iif(active, 'active', 'inactive')", self::PATIENT)->toArray());
    }

    public function testBareIifOnANullItemKeepsThatItemAsThis(): void
    {
        // In a raw array an extension-only primitive is a null value beside its `_given` entry. It is
        // still an item, so $this exists inside select(), and a bare iif() must agree.
        $patient = [
            'resourceType' => 'Patient',
            'name'         => [['given' => [null, 'B'], '_given' => [['extension' => [['url' => 'http://example.org/x']]], null]]],
        ];

        self::assertSame(['item', 'item'], $this->service->evaluate("Patient.name.given.select(iif(\$this.exists(), 'item', 'none'))", $patient)->toArray());
    }
}
