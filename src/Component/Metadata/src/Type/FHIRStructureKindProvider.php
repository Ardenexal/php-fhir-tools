<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
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

            // Any attribute carrying a non-empty `type`, not a fixed list: the structural attributes
            // all spell it the same way, and pinning the list here would go stale as they are added.
            foreach ((new \ReflectionClass($class))->getAttributes() as $attribute) {
                $type = $attribute->getArguments()['type'] ?? null;

                if (is_string($type) && $type !== '') {
                    $found = $type;
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
     * The one place this class touches reflection. It takes a class name and returns a boolean, so no
     * reflection handle is created for a caller or passed between methods.
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
}
