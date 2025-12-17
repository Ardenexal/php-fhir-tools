<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolver;
use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestDataGenerator;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\TestTrait;

/**
 * Test cases for FHIR discriminator map resolver
 *
 * This test class verifies the FHIR type resolver functionality using
 * property-based testing to ensure polymorphic type resolution works
 * correctly across different FHIR element types.
 *
 * @author Kiro AI Assistant
 *
 * @since 1.0.0
 */
class FHIRTypeResolverTest extends TestCase
{
    use TestTrait;

    /**
     * Property-based test for polymorphic extension value serialization
     *
     * **Feature: fhir-serialization, Property 19: Polymorphic extension value serialization**
     * **Validates: Requirements 4.4**
     */
    public function testPolymorphicExtensionValueSerialization(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirUrl(),
            FHIRTestDataGenerator::fhirPrimitiveType(),
        )->then(function(string $extensionUrl, string $valueType): void {
            $resolver = new FHIRTypeResolver();

            // Create extension data with polymorphic value
            $extensionData = [
                'url'                         => $extensionUrl,
                'value' . ucfirst($valueType) => $this->generateValueForType($valueType),
            ];

            // Test that the resolver can identify the correct value type
            $resolvedType = $resolver->resolveExtensionValueType($extensionData);

            self::assertNotNull($resolvedType, "Extension value type should be resolved for {$valueType}");
            self::assertEquals(
                'FHIR' . ucfirst($valueType),
                $resolvedType,
                "Resolved type should match expected FHIR class name for {$valueType}",
            );

            // Test that the general resolveType method also works for extensions
            $generalResolvedType = $resolver->resolveType($extensionData);
            self::assertEquals(
                $resolvedType,
                $generalResolvedType,
                'General type resolution should match specific extension value resolution',
            );
        });
    }

    /**
     * Property-based test for polymorphic type deserialization
     *
     * **Feature: fhir-serialization, Property 20: Polymorphic type deserialization**
     * **Validates: Requirements 4.5**
     */
    public function testPolymorphicTypeDeserialization(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirResourceType(),
            FHIRTestDataGenerator::fhirPrimitiveType(),
        )->then(function(string $resourceType, string $choiceType): void {
            $resolver = new FHIRTypeResolver();

            // Test resource type deserialization
            $resourceData         = ['resourceType' => $resourceType];
            $resolvedResourceType = $resolver->resolveType($resourceData);

            self::assertNotNull($resolvedResourceType, 'Resource type should be resolved');
            self::assertEquals(
                'FHIR' . $resourceType,
                $resolvedResourceType,
                'Resolved resource type should match expected class name',
            );

            // Test choice element deserialization
            $choiceData         = ['value' . ucfirst($choiceType) => $this->generateValueForType($choiceType)];
            $resolvedChoiceType = $resolver->resolveType($choiceData);

            self::assertNotNull($resolvedChoiceType, 'Choice element type should be resolved');
            self::assertEquals(
                'FHIR' . ucfirst($choiceType),
                $resolvedChoiceType,
                'Resolved choice type should match expected class name',
            );

            // Test reference type deserialization
            $referenceData = [
                'reference' => $resourceType . '/123',
                'type'      => $resourceType,
            ];
            $resolvedReferenceType = $resolver->resolveReferenceType($referenceData);

            self::assertNotNull($resolvedReferenceType, 'Reference type should be resolved');
            self::assertEquals(
                'FHIR' . $resourceType,
                $resolvedReferenceType,
                'Resolved reference type should match expected class name',
            );
        });
    }

    /**
     * Test resource type resolution
     */
    public function testResourceTypeResolution(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirResourceType(),
        )->then(function(string $resourceType): void {
            $resolver = new FHIRTypeResolver();

            $data         = ['resourceType' => $resourceType];
            $resolvedType = $resolver->resolveResourceType($data);

            self::assertEquals('FHIR' . $resourceType, $resolvedType);
        });
    }

    /**
     * Test choice element type resolution
     */
    public function testChoiceElementTypeResolution(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirPrimitiveType(),
        )->then(function(string $primitiveType): void {
            $resolver = new FHIRTypeResolver();

            $data         = ['value' . ucfirst($primitiveType) => $this->generateValueForType($primitiveType)];
            $resolvedType = $resolver->resolveChoiceElementType('value', $data);

            self::assertEquals('FHIR' . ucfirst($primitiveType), $resolvedType);
        });
    }

    /**
     * Test reference type resolution
     */
    public function testReferenceTypeResolution(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirResourceType(),
        )->then(function(string $resourceType): void {
            $resolver = new FHIRTypeResolver();

            // Test resolution from reference URL
            $referenceData = ['reference' => $resourceType . '/123'];
            $resolvedType  = $resolver->resolveReferenceType($referenceData);

            self::assertEquals('FHIR' . $resourceType, $resolvedType);

            // Test resolution from explicit type field
            $typeData              = ['type' => $resourceType];
            $resolvedTypeFromField = $resolver->resolveReferenceType($typeData);

            self::assertEquals('FHIR' . $resourceType, $resolvedTypeFromField);
        });
    }

    /**
     * Test complex type resolution
     */
    public function testComplexTypeResolution(): void
    {
        $resolver = new FHIRTypeResolver();

        // Test Coding complex type
        $codingData   = ['system' => 'http://example.org', 'code' => 'test'];
        $resolvedType = $resolver->resolveComplexType($codingData);
        self::assertEquals('FHIRCoding', $resolvedType);

        // Test HumanName complex type
        $nameData     = ['family' => 'Doe', 'given' => ['John']];
        $resolvedType = $resolver->resolveComplexType($nameData);
        self::assertEquals('FHIRHumanName', $resolvedType);

        // Test Address complex type
        $addressData  = ['line' => ['123 Main St'], 'city' => 'Anytown'];
        $resolvedType = $resolver->resolveComplexType($addressData);
        self::assertEquals('FHIRAddress', $resolvedType);

        // Test Period complex type
        $periodData   = ['start' => '2023-01-01', 'end' => '2023-12-31'];
        $resolvedType = $resolver->resolveComplexType($periodData);
        self::assertEquals('FHIRPeriod', $resolvedType);

        // Test Quantity complex type
        $quantityData = ['value' => 10.5, 'unit' => 'mg'];
        $resolvedType = $resolver->resolveComplexType($quantityData);
        self::assertEquals('FHIRQuantity', $resolvedType);
    }

    /**
     * Test discriminator property and type mapping
     */
    public function testDiscriminatorPropertyAndMapping(): void
    {
        $resolver = new FHIRTypeResolver();

        self::assertEquals('resourceType', $resolver->getDiscriminatorProperty());

        $typeMapping = $resolver->getTypeMapping();
        self::assertIsArray($typeMapping);
    }

    /**
     * Test custom mappings
     */
    public function testCustomMappings(): void
    {
        $resourceMapping  = ['Patient' => 'CustomPatient'];
        $choiceMapping    = ['valueString' => 'CustomString'];
        $referenceMapping = ['Patient' => 'CustomPatientRef'];
        $extensionMapping = ['String' => 'CustomExtensionString'];
        $complexMapping   = ['Coding' => 'CustomCoding'];

        $resolver = new FHIRTypeResolver(
            $resourceMapping,
            $choiceMapping,
            $referenceMapping,
            $extensionMapping,
            $complexMapping,
        );

        // Test resource mapping
        $resourceData = ['resourceType' => 'Patient'];
        $resolvedType = $resolver->resolveResourceType($resourceData);
        self::assertEquals('CustomPatient', $resolvedType);

        // Test choice element mapping
        $choiceData   = ['valueString' => 'test'];
        $resolvedType = $resolver->resolveChoiceElementType('value', $choiceData);
        self::assertEquals('CustomString', $resolvedType);

        // Test extension value mapping
        $extensionData = ['url' => 'http://example.org', 'valueString' => 'test'];
        $resolvedType  = $resolver->resolveExtensionValueType($extensionData);
        self::assertEquals('CustomExtensionString', $resolvedType);

        // Test complex type mapping
        $complexData  = ['system' => 'http://example.org', 'code' => 'test'];
        $resolvedType = $resolver->resolveComplexType($complexData);
        self::assertEquals('CustomCoding', $resolvedType);
    }

    /**
     * Test mapping modification methods
     */
    public function testMappingModificationMethods(): void
    {
        $resolver = new FHIRTypeResolver();

        // Test adding mappings
        $resolver->addResourceTypeMapping('TestResource', 'CustomTestResource');
        $resolver->addChoiceElementMapping('valueTest', 'CustomTestType');
        $resolver->addReferenceTypeMapping('TestRef', 'CustomTestRef');
        $resolver->addExtensionValueMapping('Test', 'CustomExtensionTest');
        $resolver->addComplexTypeMapping('TestComplex', 'CustomTestComplex');

        // Test that mappings were added
        $resourceMappings = $resolver->getResourceTypeMappings();
        self::assertArrayHasKey('TestResource', $resourceMappings);
        self::assertEquals('CustomTestResource', $resourceMappings['TestResource']);

        $choiceMappings = $resolver->getChoiceElementMappings();
        self::assertArrayHasKey('valueTest', $choiceMappings);
        self::assertEquals('CustomTestType', $choiceMappings['valueTest']);

        $referenceMappings = $resolver->getReferenceTypeMappings();
        self::assertArrayHasKey('TestRef', $referenceMappings);
        self::assertEquals('CustomTestRef', $referenceMappings['TestRef']);

        $extensionMappings = $resolver->getExtensionValueMappings();
        self::assertArrayHasKey('Test', $extensionMappings);
        self::assertEquals('CustomExtensionTest', $extensionMappings['Test']);

        $complexMappings = $resolver->getComplexTypeMappings();
        self::assertArrayHasKey('TestComplex', $complexMappings);
        self::assertEquals('CustomTestComplex', $complexMappings['TestComplex']);
    }

    /**
     * Generate a value for the given FHIR primitive type
     */
    private function generateValueForType(string $type): mixed
    {
        return match ($type) {
            'boolean' => true,
            'integer', 'unsignedInt', 'positiveInt' => 42,
            'decimal' => 3.14,
            'string', 'code', 'id', 'markdown' => 'test-value',
            'uri', 'url', 'canonical' => 'http://example.org/test',
            'base64Binary' => base64_encode('test'),
            'instant', 'dateTime' => '2023-01-01T12:00:00Z',
            'date'  => '2023-01-01',
            'time'  => '12:00:00',
            'oid'   => '1.2.3.4.5',
            default => 'default-value',
        };
    }
}
