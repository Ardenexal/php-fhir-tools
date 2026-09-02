<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRReferenceResolverInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface;

/**
 * The optional collaborators the comparison harness wires, and the ones it leaves absent on purpose.
 *
 * ## Why this class exists
 *
 * An absent collaborator does not fail. The pass that needs it runs, finds nothing, and its missing
 * findings read as a capability gap — a number someone then sizes work against. That has now
 * mis-sized work three times in one plan:
 *
 * - **The IG type registry (M07).** `FHIRValidationService` gates its whole extension pass on
 *   `$this->registry !== null`. The harness passed none, so the extension family measured a false
 *   zero and the milestone was sized against a pass that never ran.
 * - **`FHIRReferenceResolverInterface` (found in M05).** Still absent, and declared below.
 * - **`FHIRTerminologyClientInterface` (found in M08).** Deliberately absent, and declared below.
 *
 * `footguns/conformance-harness-wiring.md` records the shape. This class is the guard: absence is
 * declared with a reason, and `OracleHarnessCollaboratorsTest` asserts every declaration against
 * what `OracleValidationServiceFactory::create()` actually builds. Wiring a collaborator means
 * deleting its row; the test fails until you do. Leaving a new one absent means adding a row, which
 * forces the decision to be written down rather than discovered by the next person to measure.
 *
 * ## What "absent" means
 *
 * Either `null`, or a null object. This repository spells a null object with a `Null` class-name
 * prefix — `NullFHIRReferenceResolver`, `NullFHIRTerminologyClient`, `NullFHIRTypeHierarchyResolver`
 * — and `isAbsent()` reads that convention. A null object is the dangerous case precisely because it
 * satisfies the type and answers every call, so nothing downstream can tell it apart from a wired
 * one.
 *
 * @author Ardenexal
 */
final readonly class OracleHarnessCollaborators
{
    /**
     * Collaborators the harness leaves absent, mapped to why.
     *
     * A reason must say what the absence costs, not merely that it exists — the point of the row is
     * that the next person measuring an affected capability reads it before trusting a number.
     *
     * @var array<class-string, string>
     */
    public const array DECLARED_ABSENT = [
        FHIRReferenceResolverInterface::class => 'Unowned, not deliberate. FHIRTargetProfileValidator '
            . 'takes this resolver, so with the null object its constraints are silently skipped in '
            . 'every comparison run. No bundle:resolve figure is affected — those come from the '
            . 'reference validator\'s own structural walk — but any target-profile measurement is '
            . 'reading a disabled pass. Wire a real resolver before sizing that capability.',

        FHIRTerminologyClientInterface::class => 'Deliberate: the corpus is validated offline, so no '
            . 'server can answer a code or display lookup here. The reachable surface is the two '
            . 'findings M08 declared; the other 41 need a licensed code system. A Symfony consumer '
            . 'gets terminology-backed validation by overriding one alias — see '
            . 'src/Bundle/FHIRBundle/src/Resources/config/services.yaml (search: terminologyClient).',
    ];

    /**
     * Record what the harness wired into each optional slot, null object and all.
     *
     * @param FHIRReferenceResolverInterface      $referenceResolver reaches FHIRTargetProfileValidator
     * @param FHIRTerminologyClientInterface|null $terminologyClient reaches FHIRValueSetBindingValidator
     * @param FHIRIGTypeRegistry|null             $igTypeRegistry    gates FHIRValidationService's extension pass
     */
    public function __construct(
        public FHIRReferenceResolverInterface $referenceResolver,
        public ?FHIRTerminologyClientInterface $terminologyClient,
        public ?FHIRIGTypeRegistry $igTypeRegistry,
    ) {
    }

    /**
     * The collaborators that are absent, keyed by the interface each one satisfies.
     *
     * Sorted so a test can compare it against a declaration without depending on property order.
     *
     * @return list<class-string> the interfaces whose slot holds nothing that can answer
     */
    public function absent(): array
    {
        $absent = [];

        if (self::isAbsent($this->referenceResolver)) {
            $absent[] = FHIRReferenceResolverInterface::class;
        }

        if (self::isAbsent($this->terminologyClient)) {
            $absent[] = FHIRTerminologyClientInterface::class;
        }

        if (self::isAbsent($this->igTypeRegistry)) {
            $absent[] = FHIRIGTypeRegistry::class;
        }

        sort($absent);

        return $absent;
    }

    /**
     * Whether a collaborator slot holds nothing that can actually answer.
     *
     * The class-name prefix is the test, not `instanceof`: there is no shared marker interface for
     * null objects, and adding one would change production types for a harness concern.
     *
     * @param object|null $collaborator whatever the harness put in the slot, if anything
     *
     * @return bool true when the slot is empty or holds a null object
     */
    public static function isAbsent(?object $collaborator): bool
    {
        if ($collaborator === null) {
            return true;
        }

        $shortName = (new \ReflectionClass($collaborator))->getShortName();

        return str_starts_with($shortName, 'Null');
    }
}
