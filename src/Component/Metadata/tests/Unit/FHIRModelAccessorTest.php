<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use PHPUnit\Framework\TestCase;

/**
 * Pins the two accessor guarantees that the Serialization normalizers are about to depend on.
 *
 * XML element order follows property declaration order wherever the content model does not rank a
 * property explicitly, and the normalizers get that order from `publicPropertyNames()`. If the
 * accessor ever filtered or sorted, element order would shift for every unranked property and no
 * existing fixture would say why -- so the sequence is asserted against reflection directly rather
 * than trusted.
 */
final class FHIRModelAccessorTest extends TestCase
{
    /**
     * A profile subclass, which declares no property of its own and inherits every one of them.
     *
     * This is the class whose ordering is easiest to get wrong and hardest to notice: with nothing
     * declared locally, the sequence is entirely its parent's, and it is exactly the profiled path
     * this plan exists to make reliable.
     */
    private const string PROFILE = 'Ardenexal\FHIRTools\Component\Models\R4\Profile\ShareableActivityDefinitionProfile';

    /** A resource that promotes all of its own properties, so nothing is inherited. */
    private const string RESOURCE = 'Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource';

    private const string COMPLEX_TYPE = 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity';

    /**
     * The sequence must match reflection exactly, on a class that really does inherit public
     * properties -- an own-properties-only class would pass this test without exercising anything.
     */
    public function testPublicPropertyNamesReproducesReflectionOrderIncludingInheritedProperties(): void
    {
        $reflection = new \ReflectionClass(self::PROFILE);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        $inherited = array_filter(
            $properties,
            static fn (\ReflectionProperty $property): bool => $property->getDeclaringClass()->getName() !== self::PROFILE,
        );

        self::assertNotEmpty($inherited, 'the fixture must really inherit public properties');

        $expected = array_map(
            static fn (\ReflectionProperty $property): string => $property->getName(),
            $properties,
        );

        self::assertSame($expected, (new FHIRModelAccessor())->publicPropertyNames(self::PROFILE));
    }

    /**
     * The same guarantee on a class that promotes every property itself, so the two inheritance
     * shapes are both pinned rather than one standing in for the other.
     */
    public function testPublicPropertyNamesReproducesReflectionOrderWithNothingInherited(): void
    {
        $reflection = new \ReflectionClass(self::RESOURCE);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        $inherited = array_filter(
            $properties,
            static fn (\ReflectionProperty $property): bool => $property->getDeclaringClass()->getName() !== self::RESOURCE,
        );

        self::assertEmpty($inherited, 'the fixture must really declare all of its own properties');

        $expected = array_map(
            static fn (\ReflectionProperty $property): string => $property->getName(),
            $properties,
        );

        self::assertSame($expected, (new FHIRModelAccessor())->publicPropertyNames(self::RESOURCE));
    }

    /**
     * Reading the sequence off an instance must answer identically to reading it off the class name,
     * because the normalizers pass instances and the ordering proof was taken against class names.
     */
    public function testPublicPropertyNamesAnswersIdenticallyForAnInstanceAndAClassName(): void
    {
        $accessor = new FHIRModelAccessor();
        $instance = $accessor->instantiateWithDefaults(self::RESOURCE);

        self::assertSame(
            $accessor->publicPropertyNames(self::RESOURCE),
            $accessor->publicPropertyNames($instance),
        );
    }

    /**
     * The write has to land the same value a direct reflection assignment would, since it replaces
     * exactly that in the denormalizers.
     */
    public function testWriteValueAssignsTheSameValueAsAReflectionAssignment(): void
    {
        $accessor = new FHIRModelAccessor();

        $throughAccessor = $accessor->instantiateWithDefaults(self::COMPLEX_TYPE);
        $accessor->writeValue($throughAccessor, 'unit', 'mg');

        $throughReflection = $accessor->instantiateWithDefaults(self::COMPLEX_TYPE);
        (new \ReflectionProperty(self::COMPLEX_TYPE, 'unit'))->setValue($throughReflection, 'mg');

        self::assertSame('mg', $accessor->readInitializedValue($throughAccessor, 'unit'));
        self::assertSame(
            $accessor->readInitializedValue($throughReflection, 'unit'),
            $accessor->readInitializedValue($throughAccessor, 'unit'),
        );
    }

    /**
     * The probe exists because reading cannot answer this question.
     *
     * `readInitializedValue()` returns null for a slot nobody wrote and for one holding null, which
     * is why four Serialization rows could not migrate onto it: two normalizers omit the first and
     * emit the second, and that difference is only visible with `omitNullValues` turned off.
     */
    public function testIsPropertyInitializedSeparatesAnUnwrittenSlotFromOneHoldingNull(): void
    {
        $accessor = new FHIRModelAccessor();

        $instance = (new \ReflectionClass(self::COMPLEX_TYPE))->newInstanceWithoutConstructor();

        self::assertFalse(
            $accessor->isPropertyInitialized($instance, 'unit'),
            'a slot nobody wrote is not initialised',
        );
        self::assertNull(
            $accessor->readInitializedValue($instance, 'unit'),
            'and reading it answers null, which is what makes the two indistinguishable by reading',
        );

        $accessor->writeValue($instance, 'unit', null);

        self::assertTrue(
            $accessor->isPropertyInitialized($instance, 'unit'),
            'an explicit null is written, so the slot is initialised',
        );
        self::assertNull($accessor->readInitializedValue($instance, 'unit'));
    }

    /**
     * A missing property probes false rather than throwing, matching what a missing-property read
     * returns and what `reflProp(...) === null` did at the call sites being replaced.
     */
    public function testIsPropertyInitializedIsFalseForAnUndeclaredProperty(): void
    {
        $accessor = new FHIRModelAccessor();

        self::assertFalse($accessor->isPropertyInitialized(
            $accessor->instantiateWithDefaults(self::COMPLEX_TYPE),
            'noSuchPropertyExists',
        ));
    }

    /**
     * Decoded payloads carry keys no generated class declares. The write must skip those rather than
     * throw or invent a dynamic property, which is what `?->setValue()` did at the call sites.
     */
    public function testWriteValueIsASilentNoOpForAnUndeclaredProperty(): void
    {
        $accessor = new FHIRModelAccessor();
        $instance = $accessor->instantiateWithDefaults(self::COMPLEX_TYPE);

        $accessor->writeValue($instance, 'noSuchPropertyExists', 'ignored');

        self::assertFalse(property_exists($instance, 'noSuchPropertyExists'));
    }
}
