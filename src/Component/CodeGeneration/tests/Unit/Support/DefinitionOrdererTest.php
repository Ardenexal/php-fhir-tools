<?php

declare(strict_types=1);

/**
 * Guards the dependency ordering that lets an IG profile derive from another profile in its own package.
 */

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Support;

use Ardenexal\FHIRTools\Component\CodeGeneration\Support\DefinitionOrderer;
use PHPUnit\Framework\TestCase;

/**
 * Covers ordering StructureDefinitions so a parent is always generated before its children.
 */
class DefinitionOrdererTest extends TestCase
{
    /** Real au-base profile deriving from AU_DIAGNOSTIC — the child that failed to resolve. */
    private const string AU_PATHOLOGY = 'http://hl7.org.au/fhir/StructureDefinition/au-pathologyreport';

    /** Second real au-base child of AU_DIAGNOSTIC, so the fix is not specific to one profile. */
    private const string AU_IMAGING = 'http://hl7.org.au/fhir/StructureDefinition/au-imagingreport';

    /** The intra-package parent whose late generation caused the reported failure. */
    private const string AU_DIAGNOSTIC = 'http://hl7.org.au/fhir/StructureDefinition/au-diagnosticreport';

    /** Core FHIR grandparent — outside the package, resolved from the BuilderContext. */
    private const string CORE_DIAGNOSTIC = 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport';

    /**
     * The reported failure, reduced: au-base enumerated `au-pathologyreport` before its parent
     * `au-diagnosticreport`, so the parent was not yet in the BuilderContext and resolution fell
     * through to a fallback that emitted the non-existent `AuDiagnosticreportResource`.
     *
     * @return void
     */
    public function testIntraPackageParentIsOrderedBeforeItsChildren(): void
    {
        // Deliberately child-first, which is what the failing environment enumerated.
        $definitions = [
            self::AU_PATHOLOGY  => ['baseDefinition' => self::AU_DIAGNOSTIC],
            self::AU_IMAGING    => ['baseDefinition' => self::AU_DIAGNOSTIC],
            self::AU_DIAGNOSTIC => ['baseDefinition' => self::CORE_DIAGNOSTIC],
        ];

        $order = array_keys(DefinitionOrderer::byBaseDefinition($definitions));

        self::assertLessThan(
            array_search(self::AU_PATHOLOGY, $order, true),
            array_search(self::AU_DIAGNOSTIC, $order, true),
            'au-diagnosticreport must be generated before au-pathologyreport',
        );
        self::assertLessThan(
            array_search(self::AU_IMAGING, $order, true),
            array_search(self::AU_DIAGNOSTIC, $order, true),
            'au-diagnosticreport must be generated before au-imagingreport',
        );
    }

    /**
     * Every input definition must survive the reorder — no entry dropped, no entry invented.
     *
     * @return void
     */
    public function testOrderingPreservesEveryDefinition(): void
    {
        $definitions = [
            self::AU_PATHOLOGY             => ['baseDefinition' => self::AU_DIAGNOSTIC],
            self::AU_DIAGNOSTIC            => ['baseDefinition' => self::CORE_DIAGNOSTIC],
            'http://example.org/unrelated' => ['baseDefinition' => 'http://example.org/absent'],
        ];

        $ordered = DefinitionOrderer::byBaseDefinition($definitions);

        self::assertCount(count($definitions), $ordered);
        self::assertSame(
            [],
            array_diff_key($definitions, $ordered),
            'No definition may be dropped by the reorder',
        );
        foreach ($definitions as $url => $definition) {
            self::assertSame($definition, $ordered[$url], "Definition body changed for {$url}");
        }
    }

    /**
     * A versioned intra-package `baseDefinition` must still be recognised as the same parent.
     *
     * @return void
     */
    public function testVersionedBaseDefinitionStillOrdersTheParentFirst(): void
    {
        $definitions = [
            self::AU_PATHOLOGY  => ['baseDefinition' => self::AU_DIAGNOSTIC . '|5.0.0'],
            self::AU_DIAGNOSTIC => ['baseDefinition' => self::CORE_DIAGNOSTIC],
        ];

        $order = array_keys(DefinitionOrderer::byBaseDefinition($definitions));

        self::assertSame([self::AU_DIAGNOSTIC, self::AU_PATHOLOGY], $order);
    }

    /**
     * A parent outside the package needs no ordering — it is already in the BuilderContext — so the
     * input order is left alone.
     *
     * @return void
     */
    public function testDefinitionsWithExternalParentsKeepTheirOrder(): void
    {
        $definitions = [
            'http://example.org/a' => ['baseDefinition' => self::CORE_DIAGNOSTIC],
            'http://example.org/b' => ['baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Patient'],
            'http://example.org/c' => [],
        ];

        self::assertSame(
            array_keys($definitions),
            array_keys(DefinitionOrderer::byBaseDefinition($definitions)),
        );
    }

    /**
     * Malformed data must not hang generation: a `baseDefinition` cycle cannot be satisfied, so the
     * walk emits every entry rather than recursing forever.
     *
     * No order is asserted — a cycle has no correct order, and which member lands first is an
     * artifact of which node the walk enters first. Terminating with nothing dropped is the contract.
     *
     * @return void
     */
    public function testBaseDefinitionCycleTerminates(): void
    {
        $definitions = [
            'http://example.org/x' => ['baseDefinition' => 'http://example.org/y'],
            'http://example.org/y' => ['baseDefinition' => 'http://example.org/x'],
        ];

        $ordered = DefinitionOrderer::byBaseDefinition($definitions);

        self::assertCount(2, $ordered);
        self::assertSame([], array_diff_key($definitions, $ordered), 'A cycle must not drop definitions');
    }

    /**
     * A definition naming itself as its own parent must not recurse.
     *
     * @return void
     */
    public function testSelfReferentialBaseDefinitionTerminates(): void
    {
        $definitions = ['http://example.org/self' => ['baseDefinition' => 'http://example.org/self']];

        self::assertSame(
            ['http://example.org/self'],
            array_keys(DefinitionOrderer::byBaseDefinition($definitions)),
        );
    }
}
