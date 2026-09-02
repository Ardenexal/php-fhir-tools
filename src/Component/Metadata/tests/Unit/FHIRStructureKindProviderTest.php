<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKind;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKindProvider;
use PHPUnit\Framework\TestCase;

/**
 * The point of this class is that declared and inherited are different questions, so the tests that
 * matter most are the ones where the two answers disagree.
 *
 * PHP does not inherit class-level attributes. A profile subclass therefore declares no structural
 * attribute of its own while plainly being an instance of the thing it profiles. Migrating a call
 * site to the wrong method changes behaviour only for profiled types, which ordinary fixtures do not
 * exercise -- so it is pinned here instead.
 */
final class FHIRStructureKindProviderTest extends TestCase
{
    private const string PROFILE = 'Ardenexal\FHIRTools\Component\Models\R4\Profile\ShareableActivityDefinitionProfile';

    private const string RESOURCE = 'Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource';

    private const string COMPLEX_TYPE = 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity';

    private const string PRIMITIVE = 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive';

    /** The resource PROFILE profiles; the disagreement case depends on this really being its parent. */
    private const string PROFILED_BASE = 'Ardenexal\FHIRTools\Component\Models\R4\Resource\ActivityDefinitionResource';

    /**
     * A class marked as a resource reports the same kind either way.
     */
    public function testTheTwoReadingsAgreeOnADirectlyMarkedClass(): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertSame(FHIRStructureKind::Resource, $provider->declaredKindOf(self::RESOURCE));
        self::assertSame(FHIRStructureKind::Resource, $provider->inheritedKindOf(self::RESOURCE));
    }

    /**
     * A profile subclass declares nothing itself but inherits its base's kind -- the case that makes
     * these two separate methods rather than one.
     */
    public function testTheTwoReadingsDisagreeOnAProfileSubclass(): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertTrue(is_subclass_of(self::PROFILE, self::PROFILED_BASE), 'the fixture must really be a subclass');
        self::assertNull(
            $provider->declaredKindOf(self::PROFILE),
            'a profile declares #[FHIRProfile], not a structural attribute of its own',
        );
        self::assertSame(
            FHIRStructureKind::Resource,
            $provider->inheritedKindOf(self::PROFILE),
            'walking the chain finds the resource it profiles',
        );
    }

    /**
     * Complex types and primitives are told apart, so one caller's answer cannot be served to another.
     */
    public function testDistinctKindsAreNotConflated(): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertSame(FHIRStructureKind::ComplexType, $provider->declaredKindOf(self::COMPLEX_TYPE));
        self::assertSame(FHIRStructureKind::PrimitiveType, $provider->declaredKindOf(self::PRIMITIVE));
    }

    /**
     * A negative answer is cached, so repeated misses do not re-reflect.
     */
    public function testANegativeAnswerIsStableAcrossRepeatedCalls(): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertNull($provider->declaredKindOf(self::PROFILE));
        self::assertNull($provider->declaredKindOf(self::PROFILE));
        self::assertSame(FHIRStructureKind::Resource, $provider->inheritedKindOf(self::PROFILE));
    }

    /**
     * A string naming no loadable class is answered, not thrown at.
     */
    public function testAnUnloadableClassNameIsNull(): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertNull($provider->declaredKindOf('No\Such\Class'));
        self::assertNull($provider->inheritedKindOf('No\Such\Class'));
        self::assertFalse($provider->isExtensionDefinition('No\Such\Class'));
    }

    /**
     * Extension-ness is asked separately because an extension definition is also a complex type.
     */
    public function testExtensionDefinitionIsOrthogonalToStructureKind(): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertFalse($provider->isExtensionDefinition(self::RESOURCE));
        self::assertFalse($provider->isExtensionDefinition(self::COMPLEX_TYPE));
    }
}
