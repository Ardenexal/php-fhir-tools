<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Participant2;
use Ardenexal\FHIRTools\Component\Metadata\Type\PropertyMetadata;
use Ardenexal\FHIRTools\Component\Metadata\Type\PropertyMetadataProvider;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Fixtures\FixtureInMemoryCachePool;
use PHPUnit\Framework\TestCase;

/**
 * A warm PSR-6 pool outlives a deployment, and its key carries only the class name. Metadata cached
 * before `openEnum` existed must not be served for a regenerated class, or `typeCode="CAGNT"` would
 * be routed through the strict enum path and rejected until someone clears the pool (PR #135).
 */
final class PropertyMetadataCacheSchemaTest extends TestCase
{
    public function testMetadataWarmedUnderThePreviousSchemaIsNotServed(): void
    {
        $pool = new FixtureInMemoryCachePool();

        // What a pre-upgrade deployment wrote: Participant2.typeCode as a strict enum, under v2.
        $stale = $pool->getItem('fhir.property_metadata.metadata-type-v2.' . hash('sha256', Participant2::class));
        $stale->set(['typeCode' => new PropertyMetadata(
            fhirType: 'code',
            propertyKind: 'enum',
            isArray: false,
            isRequired: true,
            isChoice: false,
            variants: null,
            jsonKey: null,
            xmlSerializedName: '@typeCode',
        )]);
        $pool->save($stale);

        $metadata = (new PropertyMetadataProvider($pool))->getPropertyMetadata(Participant2::class);

        self::assertSame('openEnum', $metadata['typeCode']->propertyKind);
    }

    public function testFreshMetadataIsWrittenUnderTheCurrentKey(): void
    {
        $pool = new FixtureInMemoryCachePool();

        (new PropertyMetadataProvider($pool))->getPropertyMetadata(Participant2::class);

        self::assertTrue($pool->hasItem(PropertyMetadataProvider::cacheKey(Participant2::class)));
        self::assertStringNotContainsString('metadata-type-v2', PropertyMetadataProvider::cacheKey(Participant2::class));
    }
}
