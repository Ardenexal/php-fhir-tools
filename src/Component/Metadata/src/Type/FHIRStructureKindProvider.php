<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

/**
 * The single owner of "what kind of FHIR structure is this class".
 *
 * @author Ardenexal
 */
final class FHIRStructureKindProvider implements FHIRStructureKindProviderInterface
{
    /**
     * Attribute to kind, in the order they are tested.
     *
     * Order matters for classes carrying more than one marker: a backbone element is also a complex
     * type in the generated output, and the more specific answer is the useful one.
     */
    private const array KIND_ATTRIBUTES = [
        FHIRBackboneElement::class => FHIRStructureKind::BackboneElement,
        FhirResource::class        => FHIRStructureKind::Resource,
        FHIRPrimitive::class       => FHIRStructureKind::PrimitiveType,
        LogicalModel::class        => FHIRStructureKind::LogicalModel,
        FHIRComplexType::class     => FHIRStructureKind::ComplexType,
    ];

    /**
     * Attributes that carry a declared FHIR type name, in the order they are tested.
     *
     * Ordered like {@see KIND_ATTRIBUTES} and for the same reason: a class carrying more than one
     * marker should answer with the more specific name. `FHIRProfile` is tested last so a class that
     * declares both a profile and its base marker answers from the marker.
     *
     * Every entry here needs an arm in {@see typeNameOf()}. `FHIRStructureKindProviderTest` pins the
     * two together, so an attribute added to one and not the other fails a test rather than quietly
     * answering null -- which is exactly how this method used to fail.
     *
     * @var list<class-string>
     */
    private const array TYPE_NAME_ATTRIBUTES = [
        FHIRBackboneElement::class,
        FhirResource::class,
        FHIRPrimitive::class,
        LogicalModel::class,
        FHIRComplexType::class,
        FHIRProfile::class,
    ];

    /**
     * Cached answers, keyed separately per question.
     *
     * Deliberately three maps rather than one. A single map keyed only by class name cannot represent
     * "declared nothing" and "not looked up yet" as different states, so a negative answer never
     * memoizes and every miss re-reflects. Keeping the questions apart also stops one caller's answer
     * being served to a caller that asked something else.
     *
     * @var array<class-string, FHIRStructureKind|false>
     */
    private array $declaredCache = [];

    /** @var array<class-string, FHIRStructureKind|false> */
    private array $inheritedCache = [];

    /** @var array<class-string, bool> */
    private array $extensionCache = [];

    /** @var array<class-string, FHIRPrimitive|false> */
    private array $primitiveAttributeCache = [];

    /** @var array<class-string, string|false> */
    private array $typeNameCache = [];

    /**
     * {@inheritDoc}
     */
    public function declaredKindOf(object|string $subject): ?FHIRStructureKind
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return null;
        }

        if (!isset($this->declaredCache[$class])) {
            $this->declaredCache[$class] = self::readDeclaredKind($class) ?? false;
        }

        return $this->declaredCache[$class] ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function inheritedKindOf(object|string $subject): ?FHIRStructureKind
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return null;
        }

        if (!isset($this->inheritedCache[$class])) {
            $found = null;

            for ($cursor = $class; $cursor !== false; $cursor = get_parent_class($cursor)) {
                $found = self::readDeclaredKind($cursor);

                if ($found !== null) {
                    break;
                }
            }

            $this->inheritedCache[$class] = $found ?? false;
        }

        return $this->inheritedCache[$class] ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function nearestKindAmong(object|string $subject, FHIRStructureKind ...$kinds): ?FHIRStructureKind
    {
        $class = self::classOf($subject);

        if ($class === null || $kinds === []) {
            return null;
        }

        for ($cursor = $class; $cursor !== false; $cursor = get_parent_class($cursor)) {
            $kind = self::readDeclaredKind($cursor);

            if ($kind !== null && in_array($kind, $kinds, true)) {
                return $kind;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function declaredFhirTypeName(object|string $subject): ?string
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return null;
        }

        if (!isset($this->typeNameCache[$class])) {
            $found = null;

            foreach (self::TYPE_NAME_ATTRIBUTES as $attribute) {
                $declared = self::declaredAttribute($class, $attribute);

                if ($declared === null) {
                    continue;
                }

                $name = self::typeNameOf($declared);

                if ($name !== null && $name !== '') {
                    $found = $name;
                    break;
                }
            }

            $this->typeNameCache[$class] = $found ?? false;
        }

        return $this->typeNameCache[$class] ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function nearestPrimitiveAttribute(object|string $subject): ?FHIRPrimitive
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return null;
        }

        if (!isset($this->primitiveAttributeCache[$class])) {
            $found = null;

            // Same walk as inheritedKindOf, kept separate because it stops only at FHIRPrimitive: a
            // primitive's ancestor carrying FHIRComplexType must not end the search early.
            for ($cursor = $class; $cursor !== false; $cursor = get_parent_class($cursor)) {
                $attributes = (new \ReflectionClass($cursor))->getAttributes(FHIRPrimitive::class);

                if ($attributes !== []) {
                    $found = $attributes[0]->newInstance();
                    break;
                }
            }

            $this->primitiveAttributeCache[$class] = $found ?? false;
        }

        return $this->primitiveAttributeCache[$class] ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function isExtensionDefinition(object|string $subject): bool
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return false;
        }

        return $this->extensionCache[$class] ??= self::hasDeclaredAttribute($class, FHIRExtensionDefinition::class);
    }

    /**
     * Resolve a subject to a loadable class name.
     *
     * @param object|string $subject An instance, or a class name; a string naming no loadable class answers null
     *
     * @return class-string|null The class name, or null when the string names no loadable class
     */
    private static function classOf(object|string $subject): ?string
    {
        if (is_object($subject)) {
            return $subject::class;
        }

        return class_exists($subject) ? $subject : null;
    }

    /**
     * Read the kind a single class declares, consulting no ancestor.
     *
     * @param class-string $class Class to inspect
     *
     * @return FHIRStructureKind|null The declared kind, or null when the class declares none
     */
    private static function readDeclaredKind(string $class): ?FHIRStructureKind
    {
        foreach (self::KIND_ATTRIBUTES as $attribute => $kind) {
            if (self::hasDeclaredAttribute($class, $attribute)) {
                return $kind;
            }
        }

        return null;
    }

    /**
     * Whether a class declares a given attribute itself.
     *
     * Takes a class name and returns a boolean, so no reflection handle is created for a caller or
     * passed between methods. {@see declaredAttribute()} is the sibling for callers that need the
     * attribute's own fields rather than its presence.
     *
     * @param class-string $class     Class to inspect
     * @param class-string $attribute Attribute to look for
     *
     * @return bool True when the class itself carries that attribute
     */
    private static function hasDeclaredAttribute(string $class, string $attribute): bool
    {
        try {
            return (new \ReflectionClass($class))->getAttributes($attribute) !== [];
        } catch (\ReflectionException) {
            return false;
        }
    }

    /**
     * The instance of an attribute a class declares itself, or null when it declares none.
     *
     * Sibling of {@see hasDeclaredAttribute()}, for the callers that need the attribute's own fields.
     *
     * @param class-string $class     Class to inspect
     * @param class-string $attribute Attribute to look for
     *
     * @return object|null The instantiated attribute, or null when the class does not carry it
     */
    private static function declaredAttribute(string $class, string $attribute): ?object
    {
        try {
            $attributes = (new \ReflectionClass($class))->getAttributes($attribute);
        } catch (\ReflectionException) {
            return null;
        }

        if ($attributes === []) {
            return null;
        }

        try {
            return $attributes[0]->newInstance();
        } catch (\Throwable) {
            // Swallowing a malformed attribute is a real cost -- it is a genuine bug and this hides
            // it. It is still the right trade here, because the caller is building the text of a
            // conformance error: a fatal raised while formatting that message destroys the finding
            // the caller was reporting, and replaces it with a crash that names the attribute rather
            // than the document. Answering null costs the message its FHIR type name and nothing
            // else -- it falls back to the PHP short name, which is what it printed before this
            // method resolved anything at all.
            //
            // Deliberately \Throwable and not something narrower: attribute arguments are evaluated
            // here, not at parse time, so an unresolvable constant raises \Error, a renamed
            // constructor parameter raises \ArgumentCountError, a retyped one raises \TypeError,
            // and a constructor of its own can raise anything. None of them is \ReflectionException.
            return null;
        }
    }

    /**
     * The FHIR type name an instantiated structural attribute carries.
     *
     * A match on the attribute's own type, not a lookup by argument name. The argument names disagree
     * -- `typeName`, `primitiveType`, `elementPath`, `name`, `baseType` -- and only `FhirResource`
     * spells it `type`, so the argument-name scan this replaces answered null for every class but a
     * resource, silently, because the fallback to the PHP short name happened to be right for the
     * ordinary complex types anyone would spot-check.
     *
     * Written this way PHPStan checks that each property exists, so renaming one fails analysis
     * instead of reintroducing the silent null.
     *
     * @param object $attribute An instantiated attribute from {@see TYPE_NAME_ATTRIBUTES}
     *
     * @return string|null The declared FHIR type name, or null for an attribute that carries none
     */
    private static function typeNameOf(object $attribute): ?string
    {
        return match (true) {
            // `elementPath` is already the published dotted name -- `Substance.ingredient`.
            $attribute instanceof FHIRBackboneElement => $attribute->elementPath,
            $attribute instanceof FhirResource        => $attribute->type,
            $attribute instanceof FHIRPrimitive       => $attribute->primitiveType,
            $attribute instanceof LogicalModel        => $attribute->name,
            $attribute instanceof FHIRComplexType     => $attribute->typeName,
            // A profile answers as the type it constrains. A conformance message should name a type
            // the reader can look up in the spec, not the generated profile class.
            $attribute instanceof FHIRProfile         => $attribute->baseType,
            default                                   => null,
        };
    }
}
