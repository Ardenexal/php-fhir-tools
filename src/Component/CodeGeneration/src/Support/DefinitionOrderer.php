<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Support;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ClassNameResolver;

/**
 * Orders StructureDefinitions so a definition is always generated after the definition it derives
 * from.
 *
 * IG packages contain profiles that derive from other profiles in the *same* package — in
 * `hl7.fhir.au.base`, `au-pathologyreport` and `au-imagingreport` both derive from
 * `au-diagnosticreport`. The generator registers each class in the BuilderContext only once it has
 * been generated, so a child processed before its parent finds nothing in the index.
 *
 * Nothing in the package data imposes an order — the definitions arrive in whatever order the
 * package loader enumerated them, which differs between filesystems. That made the failure
 * environment-dependent: the same command on the same package succeeded on one machine and failed
 * on another, which is the same class of cross-machine flapping that
 * {@see ClassNameResolver} documents for
 * name collisions.
 *
 * Sorting the definitions up front makes parent resolution independent of enumeration order, and
 * makes the generated output deterministic across machines.
 */
final class DefinitionOrderer
{
    /**
     * Return the definitions reordered so that every intra-package `baseDefinition` precedes the
     * definitions deriving from it.
     *
     * Ordering is a depth-first walk, so a chain of any depth resolves
     * (`au-pathologyreport` → `au-diagnosticreport` → core `DiagnosticReport`). Definitions whose
     * `baseDefinition` points outside this package keep their relative order; those parents come
     * from the BuilderContext, which is already populated before an IG package is processed.
     *
     * A `baseDefinition` cycle cannot be satisfied, so the walk refuses to re-enter a definition it
     * is already visiting and emits it in first-seen order rather than looping forever. Malformed
     * data must not hang generation.
     *
     * @param array<string, array<string, mixed>> $definitions canonical URL → StructureDefinition
     *
     * @return array<string, array<string, mixed>> the same entries, dependency-ordered
     */
    public static function byBaseDefinition(array $definitions): array
    {
        $ordered = [];

        /** @var array<string, int> $state 1 = being visited, 2 = emitted */
        $state = [];

        $visit = static function(string $url) use (&$visit, &$ordered, &$state, $definitions): void {
            // Already emitted, or currently on the stack (a cycle) — either way, do not recurse.
            if (isset($state[$url])) {
                return;
            }

            $state[$url] = 1;

            $definition = $definitions[$url];
            $base       = $definition['baseDefinition'] ?? null;

            if (is_string($base) && $base !== '') {
                $bareBase = CanonicalUrl::stripVersion($base);

                // Only intra-package parents need ordering; anything else is already in the context.
                if ($bareBase !== $url && isset($definitions[$bareBase])) {
                    $visit($bareBase);
                }
            }

            $state[$url]   = 2;
            $ordered[$url] = $definition;
        };

        foreach (array_keys($definitions) as $url) {
            $visit($url);
        }

        return $ordered;
    }
}
