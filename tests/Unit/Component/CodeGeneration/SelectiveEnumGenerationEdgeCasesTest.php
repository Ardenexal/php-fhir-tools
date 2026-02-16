<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Nette\PhpGenerator\PhpNamespace;

use function Symfony\Component\String\u;

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
 * - Multiple references to same ValueSet
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

        // Set up namespaces for testing using the Models component pattern
        $elementNamespace   = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Resource');
        $enumNamespace      = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Enum');
        $primitiveNamespace = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Primitive');
        $datatypeNamespace  = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType');
        $this->builderContext->addElementNamespace('R4B', $elementNamespace);
        $this->builderContext->addEnumNamespace('R4B', $enumNamespace);
        $this->builderContext->addPrimitiveNamespace('R4B', $primitiveNamespace);
        $this->builderContext->addDatatypeNamespace('R4B', $datatypeNamespace);
    }

    /**
     * Create a StructureDefinition with common fields pre-populated
     *
     * @param string               $id       The structure definition ID
     * @param array<int, mixed>    $elements The elements array
     * @param string               $kind     The kind (complex-type, resource, primitive-type)
     * @param array<string, mixed> $snapshot Optional snapshot element
     *
     * @return array<string, mixed>
     */
    private function createStructureDefinition(
        string $id,
        array $elements = [],
        string $kind = 'complex-type',
        ?array $snapshot = null
    ): array {
        $name = u($id)->camel()->title()->toString();

        $definition = [
            'resourceType' => 'StructureDefinition',
            'id'           => $id,
            'url'          => "http://example.org/StructureDefinition/{$id}",
            'name'         => $name,
            'status'       => 'active',
            'kind'         => $kind,
            'abstract'     => false,
            'type'         => $name . 'Resource',
            'differential' => ['element' => $elements],
        ];

        if ($snapshot !== null) {
            $definition['snapshot'] = $snapshot;
        }

        return $definition;
    }

    /**
     * Create a FHIR element definition
     *
     * @param string                    $path    The element path (e.g., "Resource.field")
     * @param string                    $type    The type code (e.g., "string", "code", "boolean")
     * @param array<string, mixed>|null $binding Optional binding definition
     * @param array<string, mixed>|null $base    Optional base definition (for snapshot elements)
     *
     * @return array<string, mixed>
     */
    private function createElement(
        string $path,
        string $type,
        ?array $binding = null,
        ?array $base = null
    ): array {
        $element = [
            'id'    => $path,
            'path'  => $path,
            'short' => "Element: {$path}",
            'min'   => 0,
            'max'   => '1',
            'type'  => [['code' => $type]],
        ];

        if ($binding !== null) {
            $element['binding'] = $binding;
        }

        if ($base !== null) {
            $element['base'] = $base;
        }

        return $element;
    }

    /**
     * Create a root element for snapshot-based structure definitions
     *
     * @param string $path The root path
     *
     * @return array<string, mixed>
     */
    private function createRootElement(string $path): array
    {
        return [
            'id'    => $path,
            'path'  => $path,
            'short' => 'Root element',
            'min'   => 0,
            'max'   => '*',
            'base'  => ['path' => $path],
        ];
    }

    /**
     * Test empty StructureDefinitions with no bindings
     *
     * Verifies that no enums are generated when no bindings exist
     * and that the system handles empty element arrays gracefully.
     */
    public function testEmptyStructureDefinitionsWithNoBindings(): void
    {
        $structureDefinition = $this->createStructureDefinition('empty-structure');

        $initialPendingEnums = $this->builderContext->getPendingEnums();

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        self::assertNotNull($class, 'Generation should succeed for empty StructureDefinition');
        self::assertFalse($this->errorCollector->hasErrors(), 'No errors should occur for empty StructureDefinition');

        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertSame($initialPendingEnums, $finalPendingEnums, 'No enums should be added for empty StructureDefinition');
        self::assertEmpty($finalPendingEnums, 'Pending enums should remain empty');
    }

    /**
     * Test StructureDefinition with elements but no bindings
     */
    public function testStructureDefinitionWithElementsButNoBindings(): void
    {
        $elements = [
            $this->createElement('NoBindingsResource.name', 'string'),
            $this->createElement('NoBindingsResource.active', 'boolean'),
        ];

        $structureDefinition = $this->createStructureDefinition('no-bindings-structure', $elements);

        $initialPendingEnums = $this->builderContext->getPendingEnums();

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        self::assertNotNull($class, 'Generation should succeed for StructureDefinition without bindings');
        self::assertFalse($this->errorCollector->hasErrors(), 'No errors should occur for StructureDefinition without bindings');

        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertSame($initialPendingEnums, $finalPendingEnums, 'No enums should be added for StructureDefinition without bindings');
    }

    /**
     * Test malformed binding definitions
     *
     * Handles bindings with missing valueSet URLs, invalid strength values,
     * and malformed binding structures.
     */
    public function testMalformedBindingDefinitions(): void
    {
        $elements = [
            $this->createElement(
                'MalformedBindingsResource.codeWithMissingValueSet',
                'code',
                ['strength' => 'required'],  // Missing valueSet URL
            ),
            $this->createElement(
                'MalformedBindingsResource.codeWithInvalidStrength',
                'code',
                [
                    'strength' => 'invalid-strength-value',
                    'valueSet' => 'http://example.org/ValueSet/test-valueset',
                ],
            ),
        ];

        $structureDefinition = $this->createStructureDefinition('malformed-bindings-structure', $elements);

        $initialPendingEnums = $this->builderContext->getPendingEnums();

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        self::assertNotNull($class, 'Generation should succeed despite malformed bindings');

        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertSame($initialPendingEnums, $finalPendingEnums, 'No enums should be added for malformed bindings');
    }

    /**
     * Test binding with missing valueSet URL
     */
    public function testBindingWithMissingValueSetUrl(): void
    {
        $elements = [
            $this->createElement(
                'MissingValueSetUrlResource.code',
                'code',
                ['strength' => 'required'],  // valueSet is missing
            ),
        ];

        $structureDefinition = $this->createStructureDefinition('missing-valueset-url', $elements);

        $initialPendingEnums = $this->builderContext->getPendingEnums();

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        self::assertNotNull($class, 'Generation should succeed with missing valueSet URL');

        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertSame($initialPendingEnums, $finalPendingEnums, 'No enum should be added when valueSet URL is missing');
    }

    /**
     * Test circular dependency scenarios
     *
     * Handles ValueSets that reference each other, prevents infinite loops,
     * and ensures proper error reporting for circular references.
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

        $snapshotElements = [
            $this->createRootElement('CircularDependencyResource'),
            $this->createElement(
                'CircularDependencyResource.code1',
                'code',
                [
                    'strength' => 'required',
                    'valueSet' => 'http://example.org/ValueSet/circular-a',
                ],
                ['path' => 'CircularDependencyResource.code1'],
            ),
            $this->createElement(
                'CircularDependencyResource.code2',
                'code',
                [
                    'strength' => 'required',
                    'valueSet' => 'http://example.org/ValueSet/circular-b',
                ],
                ['path' => 'CircularDependencyResource.code2'],
            ),
        ];

        $structureDefinition = $this->createStructureDefinition(
            'circular-dependency-structure',
            [],
            'complex-type',
            ['element' => $snapshotElements],
        );

        $initialPendingEnums = $this->builderContext->getPendingEnums();

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        self::assertNotNull($class, 'Generation should succeed even with potential circular dependencies');

        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertCount(
            count($initialPendingEnums) + 2,
            $finalPendingEnums,
            'Two enums should be added for the two ValueSet references',
        );

        self::assertArrayHasKey('http://example.org/ValueSet/circular-a', $finalPendingEnums);
        self::assertArrayHasKey('http://example.org/ValueSet/circular-b', $finalPendingEnums);
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

        $snapshotElements = [
            $this->createRootElement('MultipleReferencesResource'),
            $this->createElement(
                'MultipleReferencesResource.code1',
                'code',
                [
                    'strength' => 'required',
                    'valueSet' => 'http://example.org/ValueSet/shared-valueset',
                ],
                ['path' => 'MultipleReferencesResource.code1'],
            ),
            $this->createElement(
                'MultipleReferencesResource.code2',
                'code',
                [
                    'strength' => 'required',
                    'valueSet' => 'http://example.org/ValueSet/shared-valueset',
                ],
                ['path' => 'MultipleReferencesResource.code2'],
            ),
        ];

        $structureDefinition = $this->createStructureDefinition(
            'multiple-references-structure',
            [],
            'complex-type',
            ['element' => $snapshotElements],
        );

        $initialPendingEnums = $this->builderContext->getPendingEnums();

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
            $this->builderContext,
        );

        self::assertNotNull($class, 'Generation should succeed with multiple references to same ValueSet');

        $finalPendingEnums = $this->builderContext->getPendingEnums();
        self::assertCount(
            count($initialPendingEnums) + 1,
            $finalPendingEnums,
            'Only one enum should be added despite multiple references to same ValueSet',
        );

        self::assertArrayHasKey('http://example.org/ValueSet/shared-valueset', $finalPendingEnums);
    }
}
