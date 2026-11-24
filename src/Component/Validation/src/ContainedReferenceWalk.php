<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * What one {@see ContainedReferenceChecker::check()} pass has found so far.
 *
 * Containers are numbered in the order the walk finds them, and the other lists are indexed by
 * that number.
 *
 * @internal
 */
final class ContainedReferenceWalk
{
    /** @var list<array{string, object}> each container's path and the container itself */
    public array $containers = [];

    /** @var array<int, array<string, string>> per container: fragment `Reference.reference` values, keyed by path */
    public array $references = [];

    /** @var array<int, array<string, true>> per container: every fragment value found, as a set */
    public array $pointers = [];

    /** @var array<int, true> spl_object_id keys of objects already searched for containers */
    public array $discovered = [];

    /** @var array<int, array<int, true>> per container: spl_object_id keys of objects already collected */
    public array $collected = [];
}
