<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Evaluator;

/**
 * The Bundle entry a value was navigated out of.
 *
 * The meaning of a reference depends on the entry that holds it: `Patient/1` takes its base from that
 * entry's `fullUrl`, and `#id` looks in that entry's `contained`. A value cannot say where it came
 * from, because PHP arrays have no identity and two entries can hold identical references, so
 * {@see Collection} carries this alongside its items and resolve() reads it from there.
 *
 * @author FHIR Tools Contributors
 */
final readonly class BundleEntryScope
{
    public function __construct(
        public mixed $bundle,
        public mixed $entry,
    ) {
    }
}
