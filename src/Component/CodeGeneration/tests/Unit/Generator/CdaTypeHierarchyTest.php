<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\CdaTypeHierarchy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ardenexal\FHIRTools\Component\CodeGeneration\Generator\CdaTypeHierarchy
 */
final class CdaTypeHierarchyTest extends TestCase
{
    private const string ANY  = 'urn:test:ANY';

    private const string CD   = 'urn:test:CD';

    private const string CV   = 'urn:test:CV';

    private const string CE   = 'urn:test:CE';

    private const string ST   = 'urn:test:ST';

    private const string QTY  = 'urn:test:QTY';

    private const string PQ   = 'urn:test:PQ';

    private const string IVPQ = 'urn:test:IVL-PQ';

    /**
     * A miniature of the real CDA shape: two multi-level datatype chains under one abstract root,
     * plus a sibling that shares only the root.
     */
    private function hierarchy(): CdaTypeHierarchy
    {
        return new CdaTypeHierarchy(
            [
                self::ANY  => 'ANY',
                self::CD   => 'CD',
                self::CV   => 'CV',
                self::CE   => 'CE',
                self::ST   => 'ST',
                self::QTY  => 'QTY',
                self::PQ   => 'PQ',
                self::IVPQ => 'IVL_PQ',
            ],
            [
                self::ANY  => '',
                self::CD   => self::ANY,
                self::CV   => self::CD,
                self::CE   => self::CV,
                self::ST   => self::ANY,
                self::QTY  => self::ANY,
                self::PQ   => self::QTY,
                self::IVPQ => self::PQ,
            ],
        );
    }

    public function testThePublishedNameIsReturnedEvenWhenItDiffersFromTheUrlSegment(): void
    {
        // The whole reason the name is read from the definition: the URL says IVL-PQ, CDA says IVL_PQ.
        self::assertSame('IVL_PQ', $this->hierarchy()->typeName(self::IVPQ));
        self::assertSame('', $this->hierarchy()->typeName('urn:test:not-a-definition'));
    }

    public function testTheAncestorChainRunsSelfFirstToTheRoot(): void
    {
        self::assertSame(
            [self::CE, self::CV, self::CD, self::ANY],
            $this->hierarchy()->ancestors(self::CE),
        );
    }

    public function testAnUnknownUrlHasNoAncestorChain(): void
    {
        self::assertSame([], $this->hierarchy()->ancestors('urn:test:unknown'));
    }

    public function testACyclicParentLinkTerminatesInsteadOfHanging(): void
    {
        $cyclic = new CdaTypeHierarchy(
            ['urn:a' => 'A', 'urn:b' => 'B'],
            ['urn:a' => 'urn:b', 'urn:b' => 'urn:a'],
        );

        self::assertSame(['urn:a', 'urn:b'], $cyclic->ancestors('urn:a'));
    }

    public function testAnAdmittedTypeThatIsAncestorOfTheRestIsItselfTheLeastCommonAncestor(): void
    {
        // IVL_PQ derives from PQ, so PQ already holds both. Answering QTY here would widen the
        // property past what the element admits — the failure this method exists to avoid.
        self::assertSame(self::PQ, $this->hierarchy()->leastCommonAncestor([self::PQ, self::IVPQ]));
    }

    public function testSiblingsUnderOneParentResolveToThatParent(): void
    {
        // CV and ST share only the root; PQ and IVL_PQ share PQ. Two subtypes of QTY resolve to QTY.
        $hierarchy = new CdaTypeHierarchy(
            [self::ANY => 'ANY', self::QTY => 'QTY', 'urn:test:MO' => 'MO', self::PQ => 'PQ'],
            [self::ANY => '', self::QTY => self::ANY, 'urn:test:MO' => self::QTY, self::PQ => self::QTY],
        );

        self::assertSame(self::QTY, $hierarchy->leastCommonAncestor([self::PQ, 'urn:test:MO']));
    }

    public function testTheLeastCommonAncestorAcrossChainsIsTheAbstractRoot(): void
    {
        self::assertSame(
            self::ANY,
            $this->hierarchy()->leastCommonAncestor([self::CD, self::ST, self::IVPQ]),
        );
    }

    public function testTheLeastCommonAncestorOfASingleTypeIsThatTypeItself(): void
    {
        self::assertSame(self::CE, $this->hierarchy()->leastCommonAncestor([self::CE]));
    }

    public function testTypesInSeparateHierarchiesHaveNoLeastCommonAncestor(): void
    {
        $split = new CdaTypeHierarchy(
            ['urn:a' => 'A', 'urn:b' => 'B'],
            ['urn:a' => '', 'urn:b' => ''],
        );

        self::assertNull($split->leastCommonAncestor(['urn:a', 'urn:b']));
        self::assertNull($this->hierarchy()->leastCommonAncestor([]));
    }

    public function testASupertypeIsMovedAfterItsSubtypes(): void
    {
        // The published order: CD ahead of the types that derive from it.
        $sorted = $this->hierarchy()->sortDescendantFirst([self::CD, self::CV, self::CE]);

        self::assertSame([self::CE, self::CV, self::CD], $sorted);
    }

    public function testASubtypeIsFoundPastAnUnrelatedTypeSittingBetween(): void
    {
        // The case a stop-at-first-non-ancestor scan gets wrong: ST is unrelated to both CD and CE,
        // and sits between them. CE must still overtake CD.
        $sorted = $this->hierarchy()->sortDescendantFirst([self::CD, self::ST, self::CE]);

        self::assertSame(self::CE, $sorted[0], 'CE derives from CD and must precede it');
        self::assertContains(self::ST, $sorted);
        self::assertCount(3, $sorted);
        self::assertGreaterThan(
            array_search(self::CE, $sorted, true),
            array_search(self::CD, $sorted, true),
        );
    }

    public function testUnrelatedTypesKeepTheirGivenOrder(): void
    {
        // Stability matters: most members of a real slot are unrelated siblings, and churning their
        // order would rewrite generated files on every run.
        $given = [self::ST, self::CD, self::QTY];

        self::assertSame($given, $this->hierarchy()->sortDescendantFirst($given));
    }

    public function testSortingAnAlreadyCorrectOrderChangesNothing(): void
    {
        $given = [self::CE, self::CV, self::CD];

        self::assertSame($given, $this->hierarchy()->sortDescendantFirst($given));
    }

    public function testTheSortLeavesNoAncestorAheadOfItsOwnDescendant(): void
    {
        $hierarchy = $this->hierarchy();
        $sorted    = $hierarchy->sortDescendantFirst(
            [self::CD, self::PQ, self::ST, self::CE, self::QTY, self::IVPQ, self::CV],
        );

        foreach ($sorted as $index => $earlier) {
            foreach (array_slice($sorted, $index + 1) as $later) {
                self::assertFalse(
                    $hierarchy->isDescendantOf($later, $earlier),
                    "{$earlier} precedes its descendant {$later}",
                );
            }
        }
    }

    public function testATypeIsNotItsOwnDescendant(): void
    {
        self::assertFalse($this->hierarchy()->isDescendantOf(self::CD, self::CD));
        self::assertTrue($this->hierarchy()->isDescendantOf(self::CE, self::ANY));
        self::assertFalse($this->hierarchy()->isDescendantOf(self::ANY, self::CE));
    }
}
