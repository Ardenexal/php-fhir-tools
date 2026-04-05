<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Benchmarks\Serialization;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;
use PhpBench\Attributes as Bench;

/**
 * Benchmarks for FHIR metadata extraction, comparing cold and warm cache behaviour.
 *
 * Cold: cache is cleared before each subject — measures reflection + attribute parsing.
 * Warm: cache is populated before each subject — measures pure cache lookup.
 */
class SerializationMetadataBench
{
    private FHIRMetadataExtractor $extractor;

    private PatientResource $patient;

    public function setUp(): void
    {
        $this->extractor = new FHIRMetadataExtractor();
        $this->patient   = new PatientResource(id: 'bench-meta-001');
    }

    /** Populate the metadata cache before the warm benchmarks. */
    public function warmCache(): void
    {
        $this->setUp();
        // Prime resource metadata and property metadata for PatientResource.
        $this->extractor->extractResourceType($this->patient);
        $this->extractor->getPropertyMetadataProvider()->getPropertyMetadata(PatientResource::class);
    }

    /** Clear the metadata cache before the cold benchmarks. */
    public function resetCache(): void
    {
        $this->setUp();
        $this->extractor->clearCache();
    }

    /**
     * Extract resource type from PatientResource with a populated cache (cache hit path).
     */
    #[Bench\BeforeMethods(['warmCache'])]
    public function benchMetadataWarmCache(): void
    {
        $this->extractor->extractResourceType($this->patient);
    }

    /**
     * Extract resource type from PatientResource with an empty cache (cold reflection path).
     */
    #[Bench\BeforeMethods(['resetCache'])]
    public function benchMetadataColdCache(): void
    {
        $this->extractor->extractResourceType($this->patient);
    }

    /**
     * Extract full property metadata map for PatientResource (warm cache).
     */
    #[Bench\BeforeMethods(['warmCache'])]
    public function benchPropertyMetadataWarmCache(): void
    {
        $this->extractor->getPropertyMetadataProvider()->getPropertyMetadata(PatientResource::class);
    }

    /**
     * Extract full property metadata map for PatientResource (cold — fresh extractor).
     */
    #[Bench\BeforeMethods(['resetCache'])]
    public function benchPropertyMetadataColdCache(): void
    {
        $this->extractor->getPropertyMetadataProvider()->getPropertyMetadata(PatientResource::class);
    }
}
