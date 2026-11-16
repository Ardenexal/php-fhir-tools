<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRMetadataCache;
use PHPUnit\Framework\TestCase;

/**
 * Each structure-kind question memoizes its own answer, including its negative answers.
 *
 * The cache held one slot per class carrying a single kind string, which conflated two things. A
 * recorded answer for one kind was handed to every other kind's question — harmless only because the
 * generated output never puts two structural attributes on one class — and a negative answer was
 * stored as null, which the read path could not tell apart from "never asked", so no negative ever
 * memoized and every miss reflected again.
 */
final class StructureKindCacheIsolationTest extends TestCase
{
    /**
     * A recorded answer for one kind says nothing about another kind.
     */
    public function testOneKindsAnswerIsNotServedToAnotherKind(): void
    {
        $cache = new FHIRMetadataCache();
        $cache->cacheStructureKindFlag('Acme\\Thing', 'resource', true);

        self::assertTrue($cache->getStructureKindFlag('Acme\\Thing', 'resource'));
        self::assertNull(
            $cache->getStructureKindFlag('Acme\\Thing', 'complex-type'),
            'an unasked question must read as unasked, not as a recorded no',
        );
    }

    /**
     * A negative answer is recorded, and is distinguishable from never having asked.
     */
    public function testANegativeAnswerMemoizes(): void
    {
        $cache = new FHIRMetadataCache();

        self::assertNull($cache->getStructureKindFlag('Acme\\Thing', 'primitive-type'));

        $cache->cacheStructureKindFlag('Acme\\Thing', 'primitive-type', false);

        self::assertFalse(
            $cache->getStructureKindFlag('Acme\\Thing', 'primitive-type'),
            'a recorded no must not read back as a cache miss',
        );
    }

    /**
     * Invalidation still clears the per-kind entries.
     */
    public function testInvalidationClearsEveryKind(): void
    {
        $cache = new FHIRMetadataCache();
        $cache->cacheStructureKindFlag('Acme\\Thing', 'resource', true);
        $cache->cacheStructureKindFlag('Acme\\Thing', 'complex-type', false);

        $cache->invalidateCache();

        self::assertNull($cache->getStructureKindFlag('Acme\\Thing', 'resource'));
        self::assertNull($cache->getStructureKindFlag('Acme\\Thing', 'complex-type'));
        self::assertTrue($cache->isEmpty());
    }
}
