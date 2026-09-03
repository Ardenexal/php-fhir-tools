<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Fixture\UninstantiableAttributeFixture;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKind;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKindProvider;
use PHPUnit\Framework\Attributes\DataProvider;
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

    /** The resource PROFILE profiles; also the type name a profile is expected to answer with. */
    private const string PROFILED_BASE_TYPE = 'ActivityDefinition';

    /**
     * A complex type whose published name is dotted, so the PHP class name cannot spell it.
     *
     * The ordinary complex types are useless for this: `Coding` is called `Coding`, so a broken
     * lookup that falls through to the class name still looks right. 44 generated classes across
     * R4/R4B/R5 are shaped like this one; they are the only complex types that can catch it.
     */
    private const string DOTTED_COMPLEX_TYPE = 'Ardenexal\FHIRTools\Component\Models\R4\DataType\DosageDoseAndRate';

    private const string BACKBONE = 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Substance\SubstanceIngredient';

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

    /**
     * Each structural attribute spells its type-name argument differently, so each is pinned.
     *
     * This method once scanned every attribute for an argument named `type`. Only `FhirResource`
     * has one, so it answered null for all four other kinds -- invisibly, because its caller falls
     * back to the PHP short name and that is correct for exactly the complex types a spot-check
     * reaches for.
     */
    #[DataProvider('declaredTypeNames')]
    public function testEachStructuralAttributeYieldsItsPublishedTypeName(string $class, string $expected): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertSame($expected, $provider->declaredFhirTypeName($class));
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function declaredTypeNames(): iterable
    {
        yield 'resource drops the PHP suffix'      => [self::RESOURCE, 'Patient'];
        yield 'primitive is the lowercase name'    => [self::PRIMITIVE, 'code'];
        yield 'complex type matches its class'     => [self::COMPLEX_TYPE, 'Quantity'];
        yield 'element-level complex type is dotted' => [self::DOTTED_COMPLEX_TYPE, 'Dosage.doseAndRate'];
        yield 'backbone element is its element path' => [self::BACKBONE, 'Substance.ingredient'];
        yield 'profile answers as the type it constrains' => [self::PROFILE, self::PROFILED_BASE_TYPE];
    }

    /**
     * A profile names the type it constrains, not itself.
     *
     * Spelled out separately from the provider above because it is the one case where this method
     * and `declaredKindOf()` deliberately disagree: a profile declares no *structural* attribute, so
     * it has no kind of its own, yet `#[FHIRProfile(baseType:)]` does carry a type name. A message
     * saying `ShareableActivityDefinitionProfile.name` sends a reader hunting for a spec type that
     * does not exist.
     */
    public function testAProfileAnswersWithItsBaseTypeThoughItHasNoDeclaredKind(): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertNull($provider->declaredKindOf(self::PROFILE));
        self::assertSame(self::PROFILED_BASE_TYPE, $provider->declaredFhirTypeName(self::PROFILE));
    }

    /**
     * Every attribute listed as carrying a type name must actually be read for one.
     *
     * The precedence list and the match that reads each attribute's field are two halves of one
     * decision, and nothing in the language ties them together: an attribute added to the list but
     * not the match falls through to `default => null` and reintroduces the original bug, silently.
     * This constructs each listed attribute with marker arguments and asserts one comes back.
     */
    public function testEveryAttributeInThePrecedenceListIsActuallyRead(): void
    {
        $provider = new \ReflectionClass(FHIRStructureKindProvider::class);

        /** @var list<class-string> $listed */
        $listed = $provider->getReflectionConstant('TYPE_NAME_ATTRIBUTES')->getValue();
        self::assertNotEmpty($listed, 'the precedence list must not be empty');

        $typeNameOf = $provider->getMethod('typeNameOf');

        foreach ($listed as $attributeClass) {
            $markers    = [];
            $arguments  = [];
            $parameters = (new \ReflectionClass($attributeClass))->getConstructor()?->getParameters() ?? [];

            foreach ($parameters as $parameter) {
                $type = $parameter->getType();

                if ($type instanceof \ReflectionNamedType && $type->getName() === 'array') {
                    $arguments[] = [];

                    continue;
                }

                $marker      = 'marker-' . $parameter->getName();
                $markers[]   = $marker;
                $arguments[] = $marker;
            }

            $answer = $typeNameOf->invoke(null, (new \ReflectionClass($attributeClass))->newInstanceArgs($arguments));

            self::assertContains(
                $answer,
                $markers,
                sprintf('%s is listed in TYPE_NAME_ATTRIBUTES but has no arm in typeNameOf()', $attributeClass),
            );
        }
    }

    /**
     * A class carrying no structural attribute answers null so the caller can fall back.
     */
    public function testAClassWithNoStructuralAttributeHasNoDeclaredTypeName(): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertNull($provider->declaredFhirTypeName(self::class));
        self::assertNull($provider->declaredFhirTypeName('No\\Such\\Class'));
    }

    /**
     * The type-name answer memoizes, including across a repeat that would re-reflect.
     */
    public function testTheTypeNameAnswerIsStableAcrossRepeatedCalls(): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertSame('Dosage.doseAndRate', $provider->declaredFhirTypeName(self::DOTTED_COMPLEX_TYPE));
        self::assertSame('Dosage.doseAndRate', $provider->declaredFhirTypeName(self::DOTTED_COMPLEX_TYPE));
        self::assertNull($provider->declaredFhirTypeName(self::class));
        self::assertNull($provider->declaredFhirTypeName(self::class));
    }

    /**
     * An attribute that cannot be instantiated answers null instead of taking the process down.
     *
     * Reading a type name instantiates the attribute, where checking for a *kind* only asks whether
     * one is present. Instantiation evaluates the attribute's arguments, so it can raise -- and the
     * caller that wants the type name is `AbstractFHIRNormalizer::shortTypeName()`, formatting the
     * text of a conformance error. A fatal there would throw away the finding being reported and
     * replace it with a crash naming an attribute the document's author has never heard of.
     *
     * Losing the FHIR type name costs the message its label and nothing else: it falls back to the
     * PHP short name.
     */
    public function testAnAttributeThatCannotBeInstantiatedDoesNotEscapeAsAFatal(): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertNull(
            $provider->declaredFhirTypeName(UninstantiableAttributeFixture::class),
            'a type name that cannot be read is an absent name, not an exception',
        );
    }

    /**
     * The guard above is scoped to instantiation, and does not blind the questions that avoid it.
     *
     * Asserted separately so a future "simplification" that routes every question through
     * `newInstance()` fails here: the fixture's attribute is unreadable but plainly *present*, and
     * `declaredKindOf()` must still say so.
     */
    public function testAnUninstantiableAttributeIsStillSeenByTheKindQuestion(): void
    {
        $provider = new FHIRStructureKindProvider();

        self::assertSame(
            FHIRStructureKind::ComplexType,
            $provider->declaredKindOf(UninstantiableAttributeFixture::class),
            'presence is answerable without instantiating, so it must still be answered',
        );
    }
}
