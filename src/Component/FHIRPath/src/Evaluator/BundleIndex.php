<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Evaluator;

/**
 * A Bundle and its lazily built lookups, so resolving many references scans the entries only once.
 *
 * Every {@see BundleEntryScope} from one navigation into `Bundle.entry` shares one of these, and
 * {@see FHIRPathEvaluator::getRootBundleIndex()} holds one for a root Bundle, both only until the
 * evaluation ends. Nothing may hold it longer, because model properties are mutable and a Bundle changed
 * between evaluations would then be answered from a stale index.
 *
 * @author FHIR Tools Contributors
 */
final class BundleIndex
{
    /** @var array<array-key, list<array{array<array-key, mixed>|object, BundleEntryScope}>>|null Each entry's resource and scope by `fullUrl`, built on first use */
    public ?array $entriesByFullUrl = null;

    /** @var array<array-key, list<BundleEntryScope>>|null Entries by the Reference values their resource holds, built on first use */
    public ?array $entriesByReference = null;

    /** @var array<array-key, true> Reference values held inside nested Bundles, built with */
    public array $referencesInNestedBundles = [];

    /**
     * @param string $path Position of the entry holding this Bundle, empty when it is not in one
     */
    public function __construct(
        public readonly mixed $bundle,
        private readonly string $path = '',
    ) {
    }

    /**
     * Describe the entry at the given position of this Bundle.
     */
    public function scopeFor(int $position, mixed $entry): BundleEntryScope
    {
        return new BundleEntryScope($this, $entry, $this->path === '' ? (string) $position : $this->path . '.' . $position);
    }
}
