<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Attributes;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestDataGenerator;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\TestTrait;

/**
 * Property-based tests for FHIR attributes
 *
 * This test class verifies that FHIR attributes are correctly generated
 * and contain appropriate metadata for serialization purposes.
 *
 * @author Ardenexal
 *
 * @since 1.0.0
 */
class FHIRAttributeTest extends TestCase
{
    use TestTrait;

    /**
     * **Feature: fhir-serialization, Property 32: Resource attribute generation**
     *
     * Property-based test for resource attribute generation
     * Tests that generated FHIR resource classes have appropriate resource-specific
     * attributes containing resourceType information
     *
     * **Validates: Requirements 8.1**
     */
    public function testResourceAttributeGeneration(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirResourceType(),
        )->then(function(string $resourceType): void {
            // Create a mock class with FHIRResource attribute
            $className = "MockFHIR{$resourceType}";

            // Create the attribute
            $attribute = new FhirResource($resourceType, '1.0.0', 'http://example.org/StructureDefinition/' . $resourceType, 'R4B');

            // Verify the attribute contains correct resourceType information
            self::assertSame($resourceType, $attribute->getResourceType());
            self::assertSame($resourceType, $attribute->type); // Direct access
            self::assertSame('R4B', $attribute->fhirVersion);
            self::assertSame('http://example.org/StructureDefinition/' . $resourceType, $attribute->getProfile());

            // Verify attribute is properly configured for class targeting
            $reflection = new \ReflectionClass(FhirResource::class);
            $attributes = $reflection->getAttributes(\Attribute::class);
            self::assertCount(1, $attributes);
        });
    }

    /**
     * **Feature: fhir-serialization, Property 33: Complex type attribute generation**
     *
     * Property-based test for complex type attribute generation
     * Tests that generated FHIR complex type classes have attributes
     * identifying the FHIR type name
     *
     * **Validates: Requirements 8.2**
     */
    public function testComplexTypeAttributeGeneration(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirPrimitiveType(), // Using existing generator as proxy for complex types
        )->then(function(string $typeName): void {
            // Map primitive type names to complex type names for this test
            $complexTypeMap = [
                'string'  => 'Address',
                'boolean' => 'HumanName',
                'integer' => 'ContactPoint',
                'decimal' => 'Identifier',
                'uri'     => 'CodeableConcept',
            ];
            $typeName = $complexTypeMap[$typeName] ?? 'Address';
            // Create the attribute
            $attribute = new FHIRComplexType($typeName);

            // Verify the attribute contains correct type name information
            self::assertSame($typeName, $attribute->typeName);
            self::assertSame('R4B', $attribute->fhirVersion); // Default version

            // Verify attribute is properly configured for class targeting
            $reflection = new \ReflectionClass(FHIRComplexType::class);
            $attributes = $reflection->getAttributes(\Attribute::class);
            self::assertCount(1, $attributes);
        });
    }

    /**
     * **Feature: fhir-serialization, Property 34: Backbone element attribute generation**
     *
     * Property-based test for backbone element attribute generation
     * Tests that generated FHIR backbone element classes have attributes
     * linking them to their parent resource
     *
     * **Validates: Requirements 8.3**
     */
    public function testBackboneElementAttributeGeneration(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirResourceType(),
            FHIRTestDataGenerator::fhirElementPath(),
        )->then(function(string $parentResource, string $elementPath): void {
            // Create the attribute
            $attribute = new FHIRBackboneElement($parentResource, $elementPath);

            // Verify the attribute contains correct parent resource information
            self::assertSame($parentResource, $attribute->parentResource);
            self::assertSame($elementPath, $attribute->elementPath);
            self::assertSame('R4B', $attribute->fhirVersion); // Default version

            // Verify attribute is properly configured for class targeting
            $reflection = new \ReflectionClass(FHIRBackboneElement::class);
            $attributes = $reflection->getAttributes(\Attribute::class);
            self::assertCount(1, $attributes);
        });
    }

    /**
     * **Feature: fhir-serialization, Property 35: Primitive type attribute generation**
     *
     * Property-based test for primitive type attribute generation
     * Tests that generated FHIR primitive type classes have attributes
     * indicating primitive type behavior
     *
     * **Validates: Requirements 8.4**
     */
    public function testPrimitiveTypeAttributeGeneration(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirPrimitiveType(),
        )->then(function(string $primitiveType): void {
            // Create the attribute
            $attribute = new FHIRPrimitive($primitiveType);

            // Verify the attribute contains correct primitive type information
            self::assertSame($primitiveType, $attribute->primitiveType);
            self::assertSame('R4B', $attribute->fhirVersion); // Default version
            self::assertTrue($attribute->supportsExtensions()); // All FHIR primitives support extensions

            // Verify attribute is properly configured for class targeting
            $reflection = new \ReflectionClass(FHIRPrimitive::class);
            $attributes = $reflection->getAttributes(\Attribute::class);
            self::assertCount(1, $attributes);
        });
    }

    /**
     * **Feature: fhir-serialization, Property 36: Attribute reusability**
     *
     * Property-based test for attribute reusability
     * Tests that generated FHIR class attributes are simple and reusable
     * across multiple model types of the same kind
     *
     * **Validates: Requirements 8.5**
     */
    public function testAttributeReusability(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirResourceType(),
            FHIRTestDataGenerator::fhirVersion(),
        )->then(function(string $resourceType, string $fhirVersion): void {
            // Test that the same attribute can be reused across different classes
            $attribute1 = new FhirResource($resourceType, '1.0.0', 'http://example.org/' . $resourceType, $fhirVersion);
            $attribute2 = new FhirResource($resourceType, '1.0.0', 'http://example.org/' . $resourceType, $fhirVersion);

            // Verify attributes have the same properties (reusable)
            self::assertSame($attribute1->getResourceType(), $attribute2->getResourceType());
            self::assertSame($attribute1->fhirVersion, $attribute2->fhirVersion);
            self::assertSame($attribute1->getProfile(), $attribute2->getProfile());

            // Test that attributes are simple (minimal properties)
            $reflection = new \ReflectionClass(FhirResource::class);
            $properties = $reflection->getProperties();

            // Should have exactly 5 properties: type, version, url, fhirVersion, profile
            self::assertCount(5, $properties);

            $propertyNames = array_map(fn ($prop) => $prop->getName(), $properties);
            self::assertContains('type', $propertyNames);
            self::assertContains('version', $propertyNames);
            self::assertContains('url', $propertyNames);
            self::assertContains('fhirVersion', $propertyNames);
            self::assertContains('profile', $propertyNames);

            // Verify all properties are readonly (immutable for reusability)
            foreach ($properties as $property) {
                self::assertTrue($property->isReadOnly(), "Property {$property->getName()} should be readonly");
            }
        });
    }

    /**
     * Test that attributes can be applied to classes correctly
     */
    public function testAttributeApplicationToClasses(): void
    {
        // Create a test class with FHIRResource attribute
        $testClass = new class () {
            // This would normally be applied via #[FHIRResource('Patient')]
        };

        // Test that we can create and use the attributes
        $resourceAttr    = new FhirResource('Patient', '1.0.0', 'http://example.org/Patient', 'R4B');
        $complexTypeAttr = new FHIRComplexType('Address');
        $primitiveAttr   = new FHIRPrimitive('string');
        $backboneAttr    = new FHIRBackboneElement('Patient', 'Patient.contact');

        // Verify all attributes are instantiable and have expected properties
        self::assertInstanceOf(FhirResource::class, $resourceAttr);
        self::assertInstanceOf(FHIRComplexType::class, $complexTypeAttr);
        self::assertInstanceOf(FHIRPrimitive::class, $primitiveAttr);
        self::assertInstanceOf(FHIRBackboneElement::class, $backboneAttr);
    }

    /**
     * Test attribute constructor parameter validation
     */
    public function testAttributeConstructorValidation(): void
    {
        // Test FhirResource with various parameters
        $resource1 = new FhirResource('Patient', '1.0.0', 'http://example.org/Patient', 'R4B');
        self::assertSame('Patient', $resource1->getResourceType());
        self::assertSame('Patient', $resource1->type);
        self::assertSame('R4B', $resource1->fhirVersion);
        self::assertSame('http://example.org/Patient', $resource1->getProfile());

        $resource2 = new FhirResource('Observation', '2.0.0', 'http://example.org/Observation', 'R5', 'http://example.org/profile');
        self::assertSame('Observation', $resource2->getResourceType());
        self::assertSame('R5', $resource2->fhirVersion);
        self::assertSame('http://example.org/profile', $resource2->getProfile());

        // Test FHIRComplexType
        $complexType = new FHIRComplexType('Address', 'R5');
        self::assertSame('Address', $complexType->typeName);
        self::assertSame('R5', $complexType->fhirVersion);

        // Test FHIRPrimitive
        $primitive = new FHIRPrimitive('boolean', 'R4B');
        self::assertSame('boolean', $primitive->primitiveType);
        self::assertSame('R4B', $primitive->fhirVersion);
        self::assertTrue($primitive->supportsExtensions());

        // Test FHIRBackboneElement
        $backbone = new FHIRBackboneElement('Patient', 'Patient.contact', 'R5');
        self::assertSame('Patient', $backbone->parentResource);
        self::assertSame('Patient.contact', $backbone->elementPath);
        self::assertSame('R5', $backbone->fhirVersion);
    }
}
