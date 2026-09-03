<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Fixture\PropertylessFhirModelFixture;
use Ardenexal\FHIRTools\Component\Metadata\Type\PropertyMetadataProvider;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Pins `isFhirModelClass()` to the structural marker rather than the size of the property map.
 *
 * The interface tells callers the map cannot separate "not a FHIR model" from "a model with nothing
 * on it", and to ask this method instead. The implementation used to be
 * `getPropertyMetadata($className) !== []` — the very test the contract warns against — so the
 * promised distinction did not exist. It agreed with the structural answer only because every
 * generated model happens to declare at least one property.
 *
 * `FHIRPathEvaluator` gates three strict-mode checks on this, so the difference is not academic: a
 * propertyless model would have been treated as a non-model and skipped every one of them.
 *
 * @author Ardenexal
 */
#[CoversClass(PropertyMetadataProvider::class)]
final class PropertyMetadataProviderModelDetectionTest extends TestCase
{
    /**
     * The case the old implementation got wrong, and the reason this method exists at all.
     */
    public function testAFhirModelWithNoPropertiesIsStillAFhirModel(): void
    {
        $provider = new PropertyMetadataProvider();

        self::assertSame(
            [],
            $provider->getPropertyMetadata(PropertylessFhirModelFixture::class),
            'Premise: the property map is empty, so emptiness cannot be the test.',
        );
        self::assertTrue($provider->isFhirModelClass(PropertylessFhirModelFixture::class));
    }

    /**
     * A plain PHP object must stay a non-model, or the strict-mode gates fire on everything.
     */
    public function testAPlainPhpClassIsNotAFhirModel(): void
    {
        self::assertFalse((new PropertyMetadataProvider())->isFhirModelClass(\ArrayObject::class));
    }

    /**
     * A class that cannot be loaded answers false rather than raising.
     */
    public function testAnUnloadableClassIsNotAFhirModel(): void
    {
        self::assertFalse((new PropertyMetadataProvider())->isFhirModelClass('No\\Such\\Class'));
    }

    /**
     * The ordinary case keeps working: a real generated model with properties answers true.
     */
    public function testAGeneratedModelIsAFhirModel(): void
    {
        $provider = new PropertyMetadataProvider();

        self::assertNotSame([], $provider->getPropertyMetadata(Extension::class));
        self::assertTrue($provider->isFhirModelClass(Extension::class));
    }
}
