<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration\Context;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for BuilderContext
 *
 * Tests the BuilderContext class which manages pending enums, types,
 * and namespaces during FHIR model generation.
 */
class BuilderContextTest extends TestCase
{
    private BuilderContext $context;

    protected function setUp(): void
    {
        parent::setUp();
        $this->context = new BuilderContext();

        // Set up namespaces for testing
        $elementNamespace   = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Resource');
        $enumNamespace      = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Enum');
        $primitiveNamespace = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Primitive');
        $datatypeNamespace  = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType');

        $this->context->addElementNamespace('R4B', $elementNamespace);
        $this->context->addEnumNamespace('R4B', $enumNamespace);
        $this->context->addPrimitiveNamespace('R4B', $primitiveNamespace);
        $this->context->addDatatypeNamespace('R4B', $datatypeNamespace);
    }

    /**
     * Test pending enum and type management methods
     *
     * Verifies all CRUD operations for pending enums and types work correctly.
     */
    public function testPendingEnumAndTypeManagement(): void
    {
        // Test addPendingEnum method
        /** @phpstan-ignore-next-line */
        $this->context->addPendingEnum('http://example.org/ValueSet/test1', 'TestEnum1');
        $pendingEnums = $this->context->getPendingEnums();
        self::assertArrayHasKey('http://example.org/ValueSet/test1', $pendingEnums);
        self::assertSame('TestEnum1', $pendingEnums['http://example.org/ValueSet/test1']);

        // Test getPendingEnums method
        /** @phpstan-ignore-next-line */
        $this->context->addPendingEnum('http://example.org/ValueSet/test2', 'TestEnum2');
        $pendingEnums = $this->context->getPendingEnums();
        self::assertCount(2, $pendingEnums);
        self::assertArrayHasKey('http://example.org/ValueSet/test1', $pendingEnums);
        self::assertArrayHasKey('http://example.org/ValueSet/test2', $pendingEnums);

        // Test removePendingEnum method
        $this->context->removePendingEnum('http://example.org/ValueSet/test1');
        $pendingEnums = $this->context->getPendingEnums();
        self::assertCount(1, $pendingEnums);
        self::assertArrayNotHasKey('http://example.org/ValueSet/test1', $pendingEnums);
        self::assertArrayHasKey('http://example.org/ValueSet/test2', $pendingEnums);

        // Test addPendingType method
        /** @phpstan-ignore-next-line */
        $this->context->addPendingType('http://example.org/ValueSet/test2', 'TestCodeType');
        $pendingTypes = $this->context->getPendingTypes();
        self::assertArrayHasKey('http://example.org/ValueSet/test2', $pendingTypes);
        self::assertSame('TestCodeType', $pendingTypes['http://example.org/ValueSet/test2']);

        // Test hasPendingType method
        self::assertTrue($this->context->hasPendingType('http://example.org/ValueSet/test2'));
        self::assertFalse($this->context->hasPendingType('http://example.org/ValueSet/nonexistent'));

        // Test removePendingType method
        $this->context->removePendingType('http://example.org/ValueSet/test2');
        $pendingTypes = $this->context->getPendingTypes();
        self::assertArrayNotHasKey('http://example.org/ValueSet/test2', $pendingTypes);
        self::assertFalse($this->context->hasPendingType('http://example.org/ValueSet/test2'));
    }

    /**
     * Test state consistency between enums and types
     */
    public function testStateConsistency(): void
    {
        // Add multiple enums and types
        /** @phpstan-ignore-next-line */
        $this->context->addPendingEnum('http://example.org/ValueSet/enum1', 'Enum1');
        /** @phpstan-ignore-next-line */
        $this->context->addPendingEnum('http://example.org/ValueSet/enum2', 'Enum2');
        /** @phpstan-ignore-next-line */
        $this->context->addPendingType('http://example.org/ValueSet/enum1', 'CodeType1');
        /** @phpstan-ignore-next-line */
        $this->context->addPendingType('http://example.org/ValueSet/enum2', 'CodeType2');

        // Verify state consistency
        $pendingEnums = $this->context->getPendingEnums();
        $pendingTypes = $this->context->getPendingTypes();

        self::assertCount(2, $pendingEnums);
        self::assertCount(2, $pendingTypes);

        // Verify that enum and type URLs match
        foreach (array_keys($pendingEnums) as $enumUrl) {
            self::assertTrue($this->context->hasPendingType($enumUrl), "Type should exist for enum URL: {$enumUrl}");
        }

        // Remove one enum and verify consistency
        $this->context->removePendingEnum('http://example.org/ValueSet/enum1');
        $this->context->removePendingType('http://example.org/ValueSet/enum1');

        $pendingEnums = $this->context->getPendingEnums();
        $pendingTypes = $this->context->getPendingTypes();

        self::assertCount(1, $pendingEnums);
        self::assertCount(1, $pendingTypes);
        self::assertArrayHasKey('http://example.org/ValueSet/enum2', $pendingEnums);
        self::assertArrayHasKey('http://example.org/ValueSet/enum2', $pendingTypes);
    }

    /**
     * Test adding duplicate enums doesn't create multiple entries
     */
    public function testAddingDuplicateEnums(): void
    {
        // Add the same enum twice
        /** @phpstan-ignore-next-line */
        $this->context->addPendingEnum('http://example.org/ValueSet/duplicate', 'DuplicateEnum');
        /** @phpstan-ignore-next-line */
        $this->context->addPendingEnum('http://example.org/ValueSet/duplicate', 'DuplicateEnum');

        $pendingEnums = $this->context->getPendingEnums();
        self::assertCount(1, $pendingEnums, 'Duplicate enums should not create multiple entries');
        self::assertSame('DuplicateEnum', $pendingEnums['http://example.org/ValueSet/duplicate']);
    }

    /**
     * Test removing non-existent enums doesn't cause errors
     */
    public function testRemovingNonExistentEnums(): void
    {
        $initialCount = count($this->context->getPendingEnums());

        // Try to remove non-existent enum (should not throw exception)
        $this->context->removePendingEnum('http://example.org/ValueSet/nonexistent');

        $finalCount = count($this->context->getPendingEnums());
        self::assertSame($initialCount, $finalCount, 'Removing non-existent enum should not change count');
    }
}
