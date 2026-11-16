<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use PHPUnit\Framework\TestCase;

/**
 * A pure enum, declared here because the generated models contain only backed ones and the
 * difference is exactly what `isBackedEnum` is asked to report.
 */
enum FHIRAttributeReaderPureEnumFixture
{
    case Only;
}

/**
 * The three attribute reads differ in their inheritance semantics, and a call site migrated onto the
 * wrong one changes behaviour only for profiled types. Ordinary fixtures never exercise that, so the
 * disagreement cases are pinned here.
 *
 * Fixtures are real generated classes rather than hand-written ones: the contract being protected is
 * "answers the same thing the raw reflection did", and only generated code has the profile-subclass
 * shape that makes the three readings diverge.
 */
final class FHIRAttributeReaderTest extends TestCase
{
    /** Declares no #[FhirResource] of its own; its parent does. */
    private const string PROFILE = 'Ardenexal\FHIRTools\Component\Models\R4\Profile\ShareableActivityDefinitionProfile';

    /** The resource PROFILE extends; the disagreement cases depend on this really being its parent. */
    private const string PROFILED_BASE = 'Ardenexal\FHIRTools\Component\Models\R4\Resource\ActivityDefinitionResource';

    private const string COMPLEX_TYPE = 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity';

    private const string BACKED_ENUM = 'Ardenexal\FHIRTools\Component\Models\R4\Enum\AccountStatus';

    /**
     * The ordinary case: an attribute declared on the class is returned, instantiated.
     */
    public function testClassAttributesReturnsTheDeclaredAttribute(): void
    {
        $reader = new FHIRAttributeReader();

        $attributes = $reader->classAttributes(self::COMPLEX_TYPE, FHIRComplexType::class);

        self::assertCount(1, $attributes);
        self::assertSame('Quantity', $attributes[0]->typeName);
    }

    /**
     * PHP does not inherit class attributes and this method does not simulate it. A profile subclass
     * answers empty even though it plainly is the resource it profiles -- migrating a call site onto
     * an ancestor-walking method instead would silently widen what that call site matches.
     */
    public function testClassAttributesDoesNotWalkTheParentChain(): void
    {
        $reader = new FHIRAttributeReader();

        self::assertSame([], $reader->classAttributes(self::PROFILE, FhirResource::class));
        self::assertCount(1, $reader->classAttributes(self::PROFILED_BASE, FhirResource::class));
    }

    /**
     * The same disagreement, from the other side: the hierarchy read is the one that says yes.
     */
    public function testDeclaresInHierarchyWalksTheParentChain(): void
    {
        $reader = new FHIRAttributeReader();

        self::assertTrue($reader->declaresInHierarchy(self::PROFILE, FhirResource::class));
        self::assertTrue($reader->declaresInHierarchy(self::PROFILED_BASE, FhirResource::class));
        self::assertFalse($reader->declaresInHierarchy(self::COMPLEX_TYPE, FhirResource::class));
    }

    /**
     * The property read resolves through the declaring class, which is what makes a
     * property-attribute check profile-safe without the checker knowing anything about profiles.
     *
     * Asking the profile subclass and asking its base return the same attribute values, because PHP
     * resolves an inherited property to the same handle either way.
     */
    public function testPropertyAttributesResolveThroughTheDeclaringClass(): void
    {
        $reader = new FHIRAttributeReader();

        foreach (['id', 'meta', 'language', 'text'] as $property) {
            $viaProfile = $reader->propertyAttributes(self::PROFILE, $property, FhirProperty::class);
            $viaBase    = $reader->propertyAttributes(self::PROFILED_BASE, $property, FhirProperty::class);

            self::assertCount(1, $viaProfile, "profile lost the attribute on {$property}");
            self::assertCount(1, $viaBase, "base lost the attribute on {$property}");
            self::assertSame(
                $viaBase[0]->fhirType,
                $viaProfile[0]->fhirType,
                "profile and base disagree about {$property}",
            );
        }
    }

    /**
     * An unknown property is an ordinary "no", not an exception: callers probe for optional slots.
     */
    public function testPropertyAttributesAnswersEmptyForAnUnknownProperty(): void
    {
        $reader = new FHIRAttributeReader();

        self::assertSame([], $reader->propertyAttributes(self::COMPLEX_TYPE, 'noSuchProperty', FhirProperty::class));
    }

    /**
     * An unloadable class name is also an ordinary "no". Callers build candidate names from binding
     * URLs, so a name that resolves to nothing is expected input rather than a bug.
     */
    public function testUnknownClassesAnswerEmptyRatherThanThrowing(): void
    {
        $reader = new FHIRAttributeReader();

        self::assertSame([], $reader->classAttributes('No\\Such\\Class', FhirResource::class));
        self::assertSame([], $reader->propertyAttributes('No\\Such\\Class', 'id', FhirProperty::class));
        self::assertFalse($reader->declaresInHierarchy('No\\Such\\Class', FhirResource::class));
    }

    /**
     * Backed, pure, not-an-enum and not-a-class are four different answers folded into one question.
     */
    public function testIsBackedEnumSeparatesBackedFromEverythingElse(): void
    {
        $reader = new FHIRAttributeReader();

        self::assertTrue($reader->isBackedEnum(self::BACKED_ENUM));
        self::assertFalse($reader->isBackedEnum(FHIRAttributeReaderPureEnumFixture::class));
        self::assertFalse($reader->isBackedEnum(self::COMPLEX_TYPE));
        self::assertFalse($reader->isBackedEnum('No\\Such\\Class'));
    }

    /**
     * The reader hands back instantiated attributes. A `\ReflectionAttribute` leaking through would
     * pass a naive test while leaving the caller reflecting through a proxy -- which is the coupling
     * this class exists to remove.
     */
    public function testNoReflectionHandleIsReturned(): void
    {
        $reader = new FHIRAttributeReader();

        $class    = $reader->classAttributes(self::COMPLEX_TYPE, FHIRComplexType::class);
        $property = $reader->propertyAttributes(self::PROFILED_BASE, 'id', FhirProperty::class);

        self::assertInstanceOf(FHIRComplexType::class, $class[0]);
        self::assertInstanceOf(FhirProperty::class, $property[0]);
        self::assertNotInstanceOf(\ReflectionAttribute::class, $class[0]);
        self::assertNotInstanceOf(\ReflectionAttribute::class, $property[0]);
    }

    /**
     * Repeated reads are cached, and the cache must not be able to answer for the wrong attribute
     * type: the caches are shared across attribute classes and keyed by both.
     */
    public function testCachedReadsStaySeparatedByAttributeType(): void
    {
        $reader = new FHIRAttributeReader();

        self::assertCount(1, $reader->classAttributes(self::COMPLEX_TYPE, FHIRComplexType::class));
        self::assertSame([], $reader->classAttributes(self::COMPLEX_TYPE, FhirResource::class));
        self::assertCount(1, $reader->classAttributes(self::COMPLEX_TYPE, FHIRComplexType::class));
    }
}
