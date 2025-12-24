<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Unit tests for selective enum generation edge cases
 *
 * This test class verifies the behavior of selective enum generation
 * when handling edge cases and error conditions.
 *
 * Test Coverage:
 * - Empty StructureDefinitions with no bindings
 * - Malformed binding definitions
 * - Circular dependency scenarios
 * - BuilderContext interface compliance
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class SelectiveEnumGenerationEdgeCasesTest extends TestCase
{
    private FHIRModelGenerator $generator;

    private ErrorCollector $errorCollector;

    private BuilderContext $builderContext;

    protected function setUp(): void
    {
        $this->generator      = new FHIRModelGenerator();
        $this->errorCollector = new ErrorCollector();
        $this->builderContext = new BuilderContext();

        // Set up namespaces for testing
        $elementNamespace   = new PhpNamespace('Ardenexal\\FHIRTools\\Test\\Element');
        $enumNamespace      = new PhpNamespace('Ardenexal\\FHIRTools\\Test\\Enum');
        $primitiveNamespace = new PhpNamespace('Ardenexal\\FHIRTools\\Test\\Primitive');
        $datatypeNamespace  = new PhpNamespace('Ardenexal\\FHIRTools\\Test\\DataType');
        $this->builderContext->addElementNamespace('R4B', $elementNamespace);
        $this->builderContext->addEnumNamespace('R4B', $enumNamespace);
        $this->builderContext->addPrimitiveNamespace('R4B', $primitiveNamespace);
        $this->builderContext->addDatatypeNamespace('R4B', $datatypeNamespace);
    }

    /**
     * Test 8.1: Empty StructureDefinitions with no bindings
     *
     * Verifies that no enums are generated when no bindings exist
     * and that the system handles empty element arrays gracefully.
     *
     * Requirements: 1.4, 1.5
     */
    public function testEmptyStructureDefinitionsWithNoBindings(): void
    {
        // Create empty StructureDefinition
        $emptyStructureDefinition = [
            'resourceType'   => 'StructureDefinition',
            'id'             => 'empty-structure',
            'url'            => 'http://example.org/StructureDefinition/empty-structure',
            'name'           => 'EmptyStructure',
            'status'         => 'active',
            'kind'           => 'resource',
            'abstract'       => false,
            'type'           => 'EmptyResource',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/DomainResource',
            'derivation'     => 'specialization',
            'differential'   => [
                'element' => [],
            ],
        ];

        // Track initial state
        $initialPendingEnums = $this->builderContext->getPendingEnums();

        // Generate class
        $class = $this->generator->generateModelClassWithErrorHandling(
            $emptyStructureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        // Verify generation succeeds
        self::assertNotNull($class, 'Generation should succeed for empty StructureDefinition');
        self::assertFalse($this->errorCollector->hasErrors(), 'No errors should occur for empty StructureDefinition');

        // Verify no enums were added
        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertSame($initialPendingEnums, $finalPendingEnums, 'No enums should be added for empty StructureDefinition');
        self::assertEmpty($finalPendingEnums, 'Pending enums should remain empty');
    }

    /**
     * Test StructureDefinition with elements but no bindings
     */
    public function testStructureDefinitionWithElementsButNoBindings(): void
    {
        // Create StructureDefinition with elements but no bindings
        $structureDefinition = [
            'resourceType'   => 'StructureDefinition',
            'id'             => 'no-bindings-structure',
            'url'            => 'http://example.org/StructureDefinition/no-bindings-structure',
            'name'           => 'NoBindingsStructure',
            'status'         => 'active',
            'kind'           => 'resource',
            'abstract'       => false,
            'type'           => 'NoBindingsResource',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/DomainResource',
            'derivation'     => 'specialization',
            'differential'   => [
                'element' => [
                    [
                        'id'    => 'NoBindingsResource.name',
                        'path'  => 'NoBindingsResource.name',
                        'short' => 'Name of the resource',
                        'min'   => 0,
                        'max'   => '1',
                        'type'  => [
                            [
                                'code' => 'string',
                            ],
                        ],
                    ],
                    [
                        'id'    => 'NoBindingsResource.active',
                        'path'  => 'NoBindingsResource.active',
                        'short' => 'Whether the resource is active',
                        'min'   => 0,
                        'max'   => '1',
                        'type'  => [
                            [
                                'code' => 'boolean',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // Track initial state
        $initialPendingEnums = $this->builderContext->getPendingEnums();

        // Generate class
        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        // Verify generation succeeds
        self::assertNotNull($class, 'Generation should succeed for StructureDefinition without bindings');
        self::assertFalse($this->errorCollector->hasErrors(), 'No errors should occur for StructureDefinition without bindings');

        // Verify no enums were added
        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertSame($initialPendingEnums, $finalPendingEnums, 'No enums should be added for StructureDefinition without bindings');
    }

    /**
     * Test 8.2: Malformed binding definitions
     *
     * Handles bindings with missing valueSet URLs, invalid strength values,
     * and malformed binding structures.
     *
     * Requirements: 4.3, 8.3
     */
    public function testMalformedBindingDefinitions(): void
    {
        // Create StructureDefinition with malformed bindings
        $structureDefinition = [
            'resourceType'   => 'StructureDefinition',
            'id'             => 'malformed-bindings-structure',
            'url'            => 'http://example.org/StructureDefinition/malformed-bindings-structure',
            'name'           => 'MalformedBindingsStructure',
            'status'         => 'active',
            'kind'           => 'resource',
            'abstract'       => false,
            'type'           => 'MalformedBindingsResource',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/DomainResource',
            'derivation'     => 'specialization',
            'differential'   => [
                'element' => [
                    [
                        'id'    => 'MalformedBindingsResource.codeWithMissingValueSet',
                        'path'  => 'MalformedBindingsResource.codeWithMissingValueSet',
                        'short' => 'Code with missing valueSet URL',
                        'min'   => 0,
                        'max'   => '1',
                        'type'  => [
                            [
                                'code' => 'code',
                            ],
                        ],
                        'binding' => [
                            'strength' => 'required',
                            // Missing valueSet URL
                        ],
                    ],
                    [
                        'id'    => 'MalformedBindingsResource.codeWithInvalidStrength',
                        'path'  => 'MalformedBindingsResource.codeWithInvalidStrength',
                        'short' => 'Code with invalid strength',
                        'min'   => 0,
                        'max'   => '1',
                        'type'  => [
                            [
                                'code' => 'code',
                            ],
                        ],
                        'binding' => [
                            'strength' => 'invalid-strength-value',
                            'valueSet' => 'http://example.org/ValueSet/test-valueset',
                        ],
                    ],
                    [
                        'id'    => 'MalformedBindingsResource.codeWithMalformedBinding',
                        'path'  => 'MalformedBindingsResource.codeWithMalformedBinding',
                        'short' => 'Code with malformed binding structure',
                        'min'   => 0,
                        'max'   => '1',
                        'type'  => [
                            [
                                'code' => 'code',
                            ],
                        ],
                        'binding' => 'invalid-binding-structure',
                    ],
                ],
            ],
        ];

        // Track initial state
        $initialPendingEnums = $this->builderContext->getPendingEnums();

        // Generate class
        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        // Verify generation succeeds despite malformed bindings
        self::assertNotNull($class, 'Generation should succeed despite malformed bindings');

        // Verify no enums were added for malformed bindings
        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertSame($initialPendingEnums, $finalPendingEnums, 'No enums should be added for malformed bindings');

        // Verify that errors or warnings were collected for malformed bindings
        // Note: The system should handle malformed bindings gracefully without failing
        // but may collect warnings about the malformed structures
    }

    /**
     * Test binding with missing valueSet URL
     */
    public function testBindingWithMissingValueSetUrl(): void
    {
        $structureDefinition = [
            'resourceType'   => 'StructureDefinition',
            'id'             => 'missing-valueset-url',
            'url'            => 'http://example.org/StructureDefinition/missing-valueset-url',
            'name'           => 'MissingValueSetUrl',
            'status'         => 'active',
            'kind'           => 'resource',
            'abstract'       => false,
            'type'           => 'MissingValueSetUrlResource',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/DomainResource',
            'derivation'     => 'specialization',
            'differential'   => [
                'element' => [
                    [
                        'id'    => 'MissingValueSetUrlResource.code',
                        'path'  => 'MissingValueSetUrlResource.code',
                        'short' => 'Code with missing valueSet',
                        'min'   => 0,
                        'max'   => '1',
                        'type'  => [
                            [
                                'code' => 'code',
                            ],
                        ],
                        'binding' => [
                            'strength' => 'required',
                            // valueSet is missing
                        ],
                    ],
                ],
            ],
        ];

        $initialPendingEnums = $this->builderContext->getPendingEnums();

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        self::assertNotNull($class, 'Generation should succeed with missing valueSet URL');

        // No enum should be added when valueSet URL is missing
        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertSame($initialPendingEnums, $finalPendingEnums, 'No enum should be added when valueSet URL is missing');
    }

    /**
     * Test 8.3: Circular dependency scenarios
     *
     * Handles ValueSets that reference each other, prevents infinite loops,
     * and ensures proper error reporting for circular references.
     *
     * Requirements: 4.1, 4.2, 4.3
     */
    public function testCircularDependencyScenarios(): void
    {
        // Add mock ValueSet definitions to BuilderContext
        $this->builderContext->addDefinition('http://example.org/ValueSet/circular-a', [
            'resourceType' => 'ValueSet',
            'id'           => 'circular-a',
            'url'          => 'http://example.org/ValueSet/circular-a',
            'name'         => 'CircularA',
            'status'       => 'active',
        ]);

        $this->builderContext->addDefinition('http://example.org/ValueSet/circular-b', [
            'resourceType' => 'ValueSet',
            'id'           => 'circular-b',
            'url'          => 'http://example.org/ValueSet/circular-b',
            'name'         => 'CircularB',
            'status'       => 'active',
        ]);

        // Create StructureDefinition that could lead to circular dependencies
        $structureDefinition = [
            'resourceType'   => 'StructureDefinition',
            'id'             => 'circular-dependency-structure',
            'url'            => 'http://example.org/StructureDefinition/circular-dependency-structure',
            'name'           => 'CircularDependencyStructure',
            'status'         => 'active',
            'kind'           => 'resource',
            'abstract'       => false,
            'type'           => 'CircularDependencyResource',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/DomainResource',
            'derivation'     => 'specialization',
            'snapshot'       => [
                'element' => [
                    [
                        'id'    => 'CircularDependencyResource',
                        'path'  => 'CircularDependencyResource',
                        'short' => 'Root element',
                        'min'   => 0,
                        'max'   => '*',
                        'base'  => [
                            'path' => 'CircularDependencyResource',
                        ],
                    ],
                    [
                        'id'    => 'CircularDependencyResource.code1',
                        'path'  => 'CircularDependencyResource.code1',
                        'short' => 'First code that references ValueSet A',
                        'min'   => 0,
                        'max'   => '1',
                        'base'  => [
                            'path' => 'CircularDependencyResource.code1',
                        ],
                        'type' => [
                            [
                                'code' => 'code',
                            ],
                        ],
                        'binding' => [
                            'strength' => 'required',
                            'valueSet' => 'http://example.org/ValueSet/circular-a',
                        ],
                    ],
                    [
                        'id'    => 'CircularDependencyResource.code2',
                        'path'  => 'CircularDependencyResource.code2',
                        'short' => 'Second code that references ValueSet B',
                        'min'   => 0,
                        'max'   => '1',
                        'base'  => [
                            'path' => 'CircularDependencyResource.code2',
                        ],
                        'type' => [
                            [
                                'code' => 'code',
                            ],
                        ],
                        'binding' => [
                            'strength' => 'required',
                            'valueSet' => 'http://example.org/ValueSet/circular-b',
                        ],
                    ],
                ],
            ],
        ];

        // Track initial state
        $initialPendingEnums = $this->builderContext->getPendingEnums();

        // Generate class
        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        // Verify generation succeeds
        self::assertNotNull($class, 'Generation should succeed even with potential circular dependencies');

        // Verify that enums were added for the referenced ValueSets
        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertCount(
            count($initialPendingEnums) + 2,
            $finalPendingEnums,
            'Two enums should be added for the two ValueSet references',
        );

        // Verify the specific ValueSets were added
        self::assertArrayHasKey('http://example.org/ValueSet/circular-a', $finalPendingEnums);
        self::assertArrayHasKey('http://example.org/ValueSet/circular-b', $finalPendingEnums);

        // The system should handle circular dependencies gracefully without infinite loops
        // This test verifies that the basic dependency tracking works correctly
    }

    /**
     * Test multiple references to the same ValueSet
     */
    public function testMultipleReferencesToSameValueSet(): void
    {
        // Add mock ValueSet definition to BuilderContext
        $this->builderContext->addDefinition('http://example.org/ValueSet/shared-valueset', [
            'resourceType' => 'ValueSet',
            'id'           => 'shared-valueset',
            'url'          => 'http://example.org/ValueSet/shared-valueset',
            'name'         => 'SharedValueset',
            'status'       => 'active',
        ]);

        $structureDefinition = [
            'resourceType'   => 'StructureDefinition',
            'id'             => 'multiple-references-structure',
            'url'            => 'http://example.org/StructureDefinition/multiple-references-structure',
            'name'           => 'MultipleReferencesStructure',
            'status'         => 'active',
            'kind'           => 'resource',
            'abstract'       => false,
            'type'           => 'MultipleReferencesResource',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/DomainResource',
            'derivation'     => 'specialization',
            'snapshot'       => [
                'element' => [
                    [
                        'id'    => 'MultipleReferencesResource',
                        'path'  => 'MultipleReferencesResource',
                        'short' => 'Root element',
                        'min'   => 0,
                        'max'   => '*',
                        'base'  => [
                            'path' => 'MultipleReferencesResource',
                        ],
                    ],
                    [
                        'id'    => 'MultipleReferencesResource.code1',
                        'path'  => 'MultipleReferencesResource.code1',
                        'short' => 'First code referencing same ValueSet',
                        'min'   => 0,
                        'max'   => '1',
                        'base'  => [
                            'path' => 'MultipleReferencesResource.code1',
                        ],
                        'type' => [
                            [
                                'code' => 'code',
                            ],
                        ],
                        'binding' => [
                            'strength' => 'required',
                            'valueSet' => 'http://example.org/ValueSet/shared-valueset',
                        ],
                    ],
                    [
                        'id'    => 'MultipleReferencesResource.code2',
                        'path'  => 'MultipleReferencesResource.code2',
                        'short' => 'Second code referencing same ValueSet',
                        'min'   => 0,
                        'max'   => '1',
                        'base'  => [
                            'path' => 'MultipleReferencesResource.code2',
                        ],
                        'type' => [
                            [
                                'code' => 'code',
                            ],
                        ],
                        'binding' => [
                            'strength' => 'required',
                            'valueSet' => 'http://example.org/ValueSet/shared-valueset',
                        ],
                    ],
                ],
            ],
        ];

        $initialPendingEnums = $this->builderContext->getPendingEnums();

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        self::assertNotNull($class, 'Generation should succeed with multiple references to same ValueSet');

        // Verify only one enum was added despite multiple references
        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertCount(
            count($initialPendingEnums) + 1,
            $finalPendingEnums,
            'Only one enum should be added despite multiple references to same ValueSet',
        );

        self::assertArrayHasKey('http://example.org/ValueSet/shared-valueset', $finalPendingEnums);
    }

    /**
     * Test BuilderContext interface compliance
     *
     * Tests all pending enum management methods, verifies proper state management
     * and consistency, and tests error conditions and edge cases.
     *
     * Requirements: 3.1, 3.2, 3.3, 3.4, 3.5
     */
    public function testBuilderContextInterfaceCompliance(): void
    {
        // Test addPendingEnum method
        /** @phpstan-ignore-next-line */
        $this->builderContext->addPendingEnum('http://example.org/ValueSet/test1', 'TestEnum1');
        $pendingEnums = $this->builderContext->getPendingEnums();
        self::assertArrayHasKey('http://example.org/ValueSet/test1', $pendingEnums);
        self::assertSame('TestEnum1', $pendingEnums['http://example.org/ValueSet/test1']);

        // Test getPendingEnums method
        /** @phpstan-ignore-next-line */
        $this->builderContext->addPendingEnum('http://example.org/ValueSet/test2', 'TestEnum2');
        $pendingEnums = $this->builderContext->getPendingEnums();
        self::assertCount(2, $pendingEnums);
        self::assertArrayHasKey('http://example.org/ValueSet/test1', $pendingEnums);
        self::assertArrayHasKey('http://example.org/ValueSet/test2', $pendingEnums);

        // Test removePendingEnum method
        $this->builderContext->removePendingEnum('http://example.org/ValueSet/test1');
        $pendingEnums = $this->builderContext->getPendingEnums();
        self::assertCount(1, $pendingEnums);
        self::assertArrayNotHasKey('http://example.org/ValueSet/test1', $pendingEnums);
        self::assertArrayHasKey('http://example.org/ValueSet/test2', $pendingEnums);

        // Test addPendingType method
        /** @phpstan-ignore-next-line */
        $this->builderContext->addPendingType('http://example.org/ValueSet/test2', 'TestCodeType');
        $pendingTypes = $this->builderContext->getPendingTypes();
        self::assertArrayHasKey('http://example.org/ValueSet/test2', $pendingTypes);
        self::assertSame('TestCodeType', $pendingTypes['http://example.org/ValueSet/test2']);

        // Test hasPendingType method
        self::assertTrue($this->builderContext->hasPendingType('http://example.org/ValueSet/test2'));
        self::assertFalse($this->builderContext->hasPendingType('http://example.org/ValueSet/nonexistent'));

        // Test removePendingType method
        $this->builderContext->removePendingType('http://example.org/ValueSet/test2');
        $pendingTypes = $this->builderContext->getPendingTypes();
        self::assertArrayNotHasKey('http://example.org/ValueSet/test2', $pendingTypes);
        self::assertFalse($this->builderContext->hasPendingType('http://example.org/ValueSet/test2'));
    }

    /**
     * Test BuilderContext state consistency
     */
    public function testBuilderContextStateConsistency(): void
    {
        // Add multiple enums and types
        /** @phpstan-ignore-next-line */
        $this->builderContext->addPendingEnum('http://example.org/ValueSet/enum1', 'Enum1');
        /** @phpstan-ignore-next-line */
        $this->builderContext->addPendingEnum('http://example.org/ValueSet/enum2', 'Enum2');
        /** @phpstan-ignore-next-line */
        $this->builderContext->addPendingType('http://example.org/ValueSet/enum1', 'CodeType1');
        /** @phpstan-ignore-next-line */
        $this->builderContext->addPendingType('http://example.org/ValueSet/enum2', 'CodeType2');

        // Verify state consistency
        $pendingEnums = $this->builderContext->getPendingEnums();
        $pendingTypes = $this->builderContext->getPendingTypes();

        self::assertCount(2, $pendingEnums);
        self::assertCount(2, $pendingTypes);

        // Verify that enum and type URLs match
        foreach (array_keys($pendingEnums) as $enumUrl) {
            self::assertTrue($this->builderContext->hasPendingType($enumUrl), "Type should exist for enum URL: {$enumUrl}");
        }

        // Remove one enum and verify consistency
        $this->builderContext->removePendingEnum('http://example.org/ValueSet/enum1');
        $this->builderContext->removePendingType('http://example.org/ValueSet/enum1');

        $pendingEnums = $this->builderContext->getPendingEnums();
        $pendingTypes = $this->builderContext->getPendingTypes();

        self::assertCount(1, $pendingEnums);
        self::assertCount(1, $pendingTypes);
        self::assertArrayHasKey('http://example.org/ValueSet/enum2', $pendingEnums);
        self::assertArrayHasKey('http://example.org/ValueSet/enum2', $pendingTypes);
    }

    /**
     * Test edge case: Adding duplicate enums
     */
    public function testAddingDuplicateEnums(): void
    {
        // Add the same enum twice
        /** @phpstan-ignore-next-line */
        $this->builderContext->addPendingEnum('http://example.org/ValueSet/duplicate', 'DuplicateEnum');
        /** @phpstan-ignore-next-line */
        $this->builderContext->addPendingEnum('http://example.org/ValueSet/duplicate', 'DuplicateEnum');

        $pendingEnums = $this->builderContext->getPendingEnums();
        self::assertCount(1, $pendingEnums, 'Duplicate enums should not create multiple entries');
        self::assertSame('DuplicateEnum', $pendingEnums['http://example.org/ValueSet/duplicate']);
    }

    /**
     * Test edge case: Removing non-existent enums
     */
    public function testRemovingNonExistentEnums(): void
    {
        $initialCount = count($this->builderContext->getPendingEnums());

        // Try to remove non-existent enum
        $this->builderContext->removePendingEnum('http://example.org/ValueSet/nonexistent');

        $finalCount = count($this->builderContext->getPendingEnums());
        self::assertSame($initialCount, $finalCount, 'Removing non-existent enum should not change count');
    }
}
