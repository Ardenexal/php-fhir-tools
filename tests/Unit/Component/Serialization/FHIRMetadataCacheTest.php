<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataCache;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRResourceMetadata;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRComplexTypeMetadata;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRPrimitiveTypeMetadata;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRBackboneElementMetadata;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Test cases for FHIRMetadataCache
 *
 * @author Ardenexal
 */
class FHIRMetadataCacheTest extends TestCase
{
    private FHIRMetadataCache $cache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cache = new FHIRMetadataCache();
    }

    public function testResourceMetadataCaching()
    {
        $className = 'TestPatient';
        $metadata  = new FHIRResourceMetadata('Patient', 'R4B', 'http://example.com/profile');

        // Initially should return null
        self::assertNull($this->cache->getResourceMetadata($className));

        // Cache the metadata
        $this->cache->cacheResourceMetadata($className, $metadata);

        // Should return the cached metadata
        $cached = $this->cache->getResourceMetadata($className);
        self::assertSame($metadata, $cached);
        self::assertSame('Patient', $cached->resourceType);
        self::assertSame('R4B', $cached->fhirVersion);
        self::assertSame('http://example.com/profile', $cached->profile);
    }

    public function testComplexTypeMetadataCaching()
    {
        $className = 'TestAddress';
        $metadata  = new FHIRComplexTypeMetadata('Address', 'R4B');

        // Initially should return null
        self::assertNull($this->cache->getComplexTypeMetadata($className));

        // Cache the metadata
        $this->cache->cacheComplexTypeMetadata($className, $metadata);

        // Should return the cached metadata
        $cached = $this->cache->getComplexTypeMetadata($className);
        self::assertSame($metadata, $cached);
        self::assertSame('Address', $cached->typeName);
        self::assertSame('R4B', $cached->fhirVersion);
    }

    public function testPrimitiveTypeMetadataCaching()
    {
        $className = 'TestString';
        $metadata  = new FHIRPrimitiveTypeMetadata('string', 'R4B', true);

        // Initially should return null
        self::assertNull($this->cache->getPrimitiveTypeMetadata($className));

        // Cache the metadata
        $this->cache->cachePrimitiveTypeMetadata($className, $metadata);

        // Should return the cached metadata
        $cached = $this->cache->getPrimitiveTypeMetadata($className);
        self::assertSame($metadata, $cached);
        self::assertSame('string', $cached->primitiveType);
        self::assertSame('R4B', $cached->fhirVersion);
        self::assertTrue($cached->supportsExtensions);
    }

    public function testBackboneElementMetadataCaching()
    {
        $className = 'TestPatientContact';
        $metadata  = new FHIRBackboneElementMetadata('Patient', 'Patient.contact', 'R4B');

        // Initially should return null
        self::assertNull($this->cache->getBackboneElementMetadata($className));

        // Cache the metadata
        $this->cache->cacheBackboneElementMetadata($className, $metadata);

        // Should return the cached metadata
        $cached = $this->cache->getBackboneElementMetadata($className);
        self::assertSame($metadata, $cached);
        self::assertSame('Patient', $cached->parentResource);
        self::assertSame('Patient.contact', $cached->elementPath);
        self::assertSame('R4B', $cached->fhirVersion);
    }

    public function testFHIRTypeMetadataCaching()
    {
        $className = 'TestClass';
        $fhirType  = 'Patient';

        // Initially should return null
        self::assertNull($this->cache->getFHIRTypeMetadata($className));

        // Cache the FHIR type
        $this->cache->cacheFHIRTypeMetadata($className, $fhirType);

        // Should return the cached type
        self::assertSame($fhirType, $this->cache->getFHIRTypeMetadata($className));
    }

    public function testFHIRVersionMetadataCaching()
    {
        $className   = 'TestClass';
        $fhirVersion = 'R4B';

        // Initially should return null
        self::assertNull($this->cache->getFHIRVersionMetadata($className));

        // Cache the FHIR version
        $this->cache->cacheFHIRVersionMetadata($className, $fhirVersion);

        // Should return the cached version
        self::assertSame($fhirVersion, $this->cache->getFHIRVersionMetadata($className));
    }

    public function testStructureTypeMetadataCaching()
    {
        $className     = 'TestClass';
        $structureType = 'resource';

        // Initially should return null
        self::assertNull($this->cache->getStructureTypeMetadata($className));

        // Cache the structure type
        $this->cache->cacheStructureTypeMetadata($className, $structureType);

        // Should return the cached type
        self::assertSame($structureType, $this->cache->getStructureTypeMetadata($className));
    }

    public function testCacheNullValues()
    {
        $className = 'TestClass';

        // Cache null values
        $this->cache->cacheResourceMetadata($className, null);
        $this->cache->cacheFHIRTypeMetadata($className, null);
        $this->cache->cacheFHIRVersionMetadata($className, null);
        $this->cache->cacheStructureTypeMetadata($className, null);

        // Should return null but be cached (not missing)
        self::assertNull($this->cache->getResourceMetadata($className));
        self::assertNull($this->cache->getFHIRTypeMetadata($className));
        self::assertNull($this->cache->getFHIRVersionMetadata($className));
        self::assertNull($this->cache->getStructureTypeMetadata($className));
    }

    public function testInvalidateCache()
    {
        $className = 'TestClass';
        $metadata  = new FHIRResourceMetadata('Patient', 'R4B');

        // Cache some data
        $this->cache->cacheResourceMetadata($className, $metadata);
        $this->cache->cacheFHIRTypeMetadata($className, 'Patient');

        // Verify cache is not empty
        self::assertFalse($this->cache->isEmpty());

        // Invalidate cache
        $this->cache->invalidateCache();

        // Verify cache is empty
        self::assertTrue($this->cache->isEmpty());
        self::assertNull($this->cache->getResourceMetadata($className));
        self::assertNull($this->cache->getFHIRTypeMetadata($className));
    }

    public function testInvalidateClass()
    {
        $className1 = 'TestClass1';
        $className2 = 'TestClass2';
        $metadata1  = new FHIRResourceMetadata('Patient', 'R4B');
        $metadata2  = new FHIRResourceMetadata('Observation', 'R4B');

        // Cache data for both classes
        $this->cache->cacheResourceMetadata($className1, $metadata1);
        $this->cache->cacheResourceMetadata($className2, $metadata2);

        // Verify both are cached
        self::assertSame($metadata1, $this->cache->getResourceMetadata($className1));
        self::assertSame($metadata2, $this->cache->getResourceMetadata($className2));

        // Invalidate only one class
        $this->cache->invalidateClass($className1);

        // Verify only the specified class was invalidated
        self::assertNull($this->cache->getResourceMetadata($className1));
        self::assertSame($metadata2, $this->cache->getResourceMetadata($className2));
    }

    public function testGetCacheStats()
    {
        // Initially should have zero entries
        $stats = $this->cache->getCacheStats();
        self::assertSame(0, $stats['resource_entries']);
        self::assertSame(0, $stats['complex_type_entries']);
        self::assertSame(0, $stats['primitive_type_entries']);
        self::assertSame(0, $stats['backbone_element_entries']);
        self::assertSame(0, $stats['fhir_type_entries']);
        self::assertSame(0, $stats['fhir_version_entries']);
        self::assertSame(0, $stats['structure_type_entries']);

        // Add some entries
        $this->cache->cacheResourceMetadata('TestClass1', new FHIRResourceMetadata('Patient', 'R4B'));
        $this->cache->cacheComplexTypeMetadata('TestClass2', new FHIRComplexTypeMetadata('Address', 'R4B'));
        $this->cache->cacheFHIRTypeMetadata('TestClass3', 'Patient');

        // Check updated stats
        $stats = $this->cache->getCacheStats();
        self::assertSame(1, $stats['resource_entries']);
        self::assertSame(1, $stats['complex_type_entries']);
        self::assertSame(0, $stats['primitive_type_entries']);
        self::assertSame(0, $stats['backbone_element_entries']);
        self::assertSame(1, $stats['fhir_type_entries']);
        self::assertSame(0, $stats['fhir_version_entries']);
        self::assertSame(0, $stats['structure_type_entries']);
    }

    public function testIsEmpty()
    {
        // Initially should be empty
        self::assertTrue($this->cache->isEmpty());

        // Add an entry
        $this->cache->cacheResourceMetadata('TestClass', new FHIRResourceMetadata('Patient', 'R4B'));

        // Should no longer be empty
        self::assertFalse($this->cache->isEmpty());

        // Clear cache
        $this->cache->invalidateCache();

        // Should be empty again
        self::assertTrue($this->cache->isEmpty());
    }
}
