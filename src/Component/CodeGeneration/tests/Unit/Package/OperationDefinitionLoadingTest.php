<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Package;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use PHPUnit\Framework\TestCase;

/**
 * Verifies OperationDefinition resources survive the path from package load to generator dispatch.
 *
 * Before M00, PackageLoader's resourceType switch handled only StructureDefinition, ValueSet and
 * CodeSystem; every other resourceType fell through to `default: // No action needed` and was
 * silently discarded. OperationDefinitions therefore never reached BuilderContext, and no generator
 * could ever be dispatched for them.
 *
 * Two properties matter and are asserted separately:
 *  1. Admission — an OperationDefinition placed in the definitions array reaches getDefinitions().
 *  2. Survival  — sortDefinitionsByInheritance() (run inside loadDefinitions) separates
 *     StructureDefinitions for topological sorting and merges "other resources" back afterwards.
 *     A non-StructureDefinition must come out the far side unmodified.
 */
final class OperationDefinitionLoadingTest extends TestCase
{
    private const LOOKUP_URL = 'http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup';

    /**
     * An OperationDefinition loaded into the context is retrievable and byte-identical.
     */
    public function testOperationDefinitionSurvivesLoadDefinitions(): void
    {
        $context   = new BuilderContext();
        $operation = self::lookupDefinition();

        $context->loadDefinitions([self::LOOKUP_URL => $operation]);

        $definitions = $context->getDefinitions();

        self::assertArrayHasKey(
            self::LOOKUP_URL,
            $definitions,
            'OperationDefinition was dropped between loadDefinitions() and getDefinitions().',
        );
        self::assertSame(
            $operation,
            $definitions[self::LOOKUP_URL],
            'OperationDefinition was mutated in transit; generators would receive altered input.',
        );
    }

    /**
     * The topological sort reorders StructureDefinitions without discarding other resource types.
     */
    public function testOperationDefinitionSurvivesAlongsideStructureDefinitions(): void
    {
        $context = new BuilderContext();

        $base = [
            'resourceType' => 'StructureDefinition',
            'url'          => 'http://hl7.org/fhir/StructureDefinition/Base',
            'name'         => 'Base',
            'kind'         => 'resource',
        ];
        $derived = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://hl7.org/fhir/StructureDefinition/Derived',
            'name'           => 'Derived',
            'kind'           => 'resource',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Base',
        ];

        $context->loadDefinitions([
            $derived['url']  => $derived,
            self::LOOKUP_URL => self::lookupDefinition(),
            $base['url']     => $base,
        ]);

        $definitions = $context->getDefinitions();

        self::assertArrayHasKey(self::LOOKUP_URL, $definitions);
        self::assertSame('OperationDefinition', $definitions[self::LOOKUP_URL]['resourceType']);

        // The sort must still do its job for StructureDefinitions: Base before Derived.
        $urls            = array_keys($definitions);
        $basePosition    = array_search($base['url'], $urls, true);
        $derivedPosition = array_search($derived['url'], $urls, true);

        self::assertIsInt($basePosition);
        self::assertIsInt($derivedPosition);
        self::assertLessThan(
            $derivedPosition,
            $basePosition,
            'Admitting a non-StructureDefinition perturbed the inheritance topological sort.',
        );
    }

    /**
     * Guards the discriminating property the model builder relies on: it skips any definition whose
     * resourceType is not StructureDefinition (FHIRModelGeneratorCommand, search: "resourceType']
     * !== 'StructureDefinition'"). An OperationDefinition must therefore be inert for model
     * generation while still being present for a future operation generator.
     */
    public function testOperationDefinitionIsInertForModelGeneration(): void
    {
        $context = new BuilderContext();
        $context->loadDefinitions([self::LOOKUP_URL => self::lookupDefinition()]);

        // Replicates the model builder's guard verbatim.
        $generatable = array_filter(
            $context->getDefinitions(),
            static fn (array $def): bool => ($def['resourceType'] ?? '') === 'StructureDefinition',
        );

        self::assertSame(
            [],
            $generatable,
            'An OperationDefinition reached the model builder as a generatable StructureDefinition; '
            . 'it would be routed by its "kind" (operation) and emit a bogus class.',
        );
    }

    /**
     * Minimal CodeSystem $lookup definition: the nested part groups and the allowed-type extension
     * are the two structures the operation generator has to cope with, so the fixture carries both.
     *
     * @return array<string, mixed>
     */
    private static function lookupDefinition(): array
    {
        return [
            'resourceType' => 'OperationDefinition',
            'id'           => 'CodeSystem-lookup',
            'url'          => self::LOOKUP_URL,
            'name'         => 'Concept Look Up & Decomposition',
            'status'       => 'draft',
            'kind'         => 'operation',
            'code'         => 'lookup',
            'resource'     => ['CodeSystem'],
            'system'       => false,
            'type'         => true,
            'instance'     => false,
            'parameter'    => [
                [
                    'name' => 'code',
                    'use'  => 'in',
                    'min'  => 0,
                    'max'  => '1',
                    'type' => 'code',
                ],
                [
                    'name' => 'property',
                    'use'  => 'out',
                    'min'  => 0,
                    'max'  => '*',
                    'part' => [
                        [
                            'name' => 'code',
                            'use'  => 'out',
                            'min'  => 1,
                            'max'  => '1',
                            'type' => 'code',
                        ],
                        [
                            'extension' => [
                                [
                                    'url'      => 'http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type',
                                    'valueUri' => 'code',
                                ],
                                [
                                    'url'      => 'http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type',
                                    'valueUri' => 'Coding',
                                ],
                            ],
                            'name' => 'value',
                            'use'  => 'out',
                            'min'  => 0,
                            'max'  => '1',
                            'type' => 'Element',
                        ],
                    ],
                ],
            ],
        ];
    }
}
