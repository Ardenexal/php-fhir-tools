<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Evaluator;

/**
 * A Bundle and its lazily built lookups, so resolving many references scans the entries only once.
 *
 * Every {@see BundleEntryScope} from one navigation into `Bundle.entry` shares one of these, which ties
 * its lifetime to the evaluation: it is freed when the last scope goes, and the next evaluation builds
 * its own. Nothing longer lived may hold it, because model properties are mutable and a Bundle changed
 * between evaluations would then be answered from a stale index.
 *
 * @author FHIR Tools Contributors
 */
final class BundleIndex
{
    /** @var array<array-key, list<array<array-key, mixed>|object>>|null Entry resources by `fullUrl`, built on first use */
    public ?array $resourcesByFullUrl = null;

    /** @var array<array-key, list<mixed>>|null Entries by the Reference values their resource holds, built on first use */
    public ?array $entriesByReference = null;

    public function __construct(
        public readonly mixed $bundle,
    ) {
    }
}
