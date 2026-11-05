<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Evaluator;

/**
 * A Bundle and its lazily built lookups, so resolving many references scans the entries only once.
 *
 * {@see FHIRPathEvaluator::getBundleIndex()} keeps one per Bundle until the evaluation ends, so its id
 * tells Bundles apart however often evaluation navigates back into one. Nothing may hold it longer,
 * because model properties are mutable and a Bundle changed between evaluations would then be answered
 * from a stale index.
 *
 * @author FHIR Tools Contributors
 */
final class BundleIndex
{
    /** @var array<array-key, list<array{array<array-key, mixed>|object, BundleEntryScope}>>|null Each entry's resource and scope by `fullUrl`, built on first use */
    public ?array $entriesByFullUrl = null;

    /** @var array<array-key, list<BundleEntryScope>>|null Entries by the Reference values their resource holds, built on first use */
    public ?array $entriesByReference = null;

    /** Whether a Bundle is held anywhere inside an entry, worked out along with the reference lookup */
    public bool $containsNestedBundle = false;

    /**
     * @param string $id Tells this Bundle apart from the others in the evaluation
     */
    public function __construct(
        public readonly mixed $bundle,
        private readonly string $id = '',
    ) {
    }

    /**
     * Describe the entry at the given position of this Bundle.
     */
    public function scopeFor(int $position, mixed $entry): BundleEntryScope
    {
        return new BundleEntryScope($this, $entry, $this->id . '/' . $position);
    }
}
