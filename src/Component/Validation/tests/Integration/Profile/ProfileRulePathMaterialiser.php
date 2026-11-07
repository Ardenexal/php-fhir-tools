<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledProfile;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;

/**
 * Builds the objects a profile's rules sit beneath, so an otherwise empty resource can break them.
 *
 * On an empty resource most profile rules never run: "code.coding needs the LOINC slice" says
 * nothing while `code` is null. For every rule path in a compiled profile this creates each object
 * above the rule's own element: an empty `code` for `code.coding`, one empty item in `component`
 * for `component.code`. The element the rule is about is left empty, so a minimum on it fails.
 *
 * Applied identically to a generated profile instance and to a base instance, it gives the parity
 * test fixtures where both sides have something to report.
 */
final class ProfileRulePathMaterialiser
{
    private readonly FHIRModelAccessor $accessor;

    public function __construct()
    {
        $this->accessor = new FHIRModelAccessor();
    }

    /**
     * Every property path a rule in these profiles is placed on, plus the slices' sliced properties.
     *
     * @param list<CompiledProfile> $profiles
     *
     * @return list<string>
     */
    public static function rulePaths(array $profiles): array
    {
        $paths = [];
        foreach ($profiles as $profile) {
            foreach ($profile->attributes as $attribute) {
                $path = match ($attribute->class) {
                    FHIRProfileConstraint::class => $attribute->arguments['path'] ?? null,
                    // The slice's items live in the sliced property, so its parent is what must exist.
                    FHIRSliceConstraint::class => isset($attribute->arguments['property']) && is_string($attribute->arguments['property'])
                        ? $attribute->arguments['property'] . '.*'
                        : null,
                    default => null,
                };
                if (is_string($path) && $path !== '') {
                    $paths[$path] = true;
                }
            }
        }

        return array_keys($paths);
    }

    /**
     * @param list<string> $paths dot-separated property paths
     */
    public function materialise(object $resource, array $paths): void
    {
        foreach ($paths as $path) {
            $this->materialisePath($resource, explode('.', $path));
        }
    }

    /**
     * Create the object behind the first segment when there are more segments after it.
     *
     * @param list<string> $segments
     */
    private function materialisePath(object $subject, array $segments): void
    {
        if (count($segments) < 2) {
            return;
        }

        $property = array_shift($segments);
        if (!property_exists($subject, $property)) {
            return;
        }

        $declaration  = (new \ReflectionProperty($subject, $property))->getAttributes(FhirProperty::class)[0] ?? null;
        $fhirProperty = $declaration?->newInstance();

        if ($fhirProperty !== null && $fhirProperty->isArray) {
            $itemClass = $fhirProperty->phpType;
            $items     = $subject->{$property};
            if (!is_array($items) || $itemClass === null || !self::isInstantiable($itemClass)) {
                return;
            }
            if ($items === []) {
                $items[]               = new $itemClass();
                $subject->{$property}  = $items;
            }
            if (is_object($items[0] ?? null)) {
                $this->materialisePath($items[0], $segments);
            }

            return;
        }

        $class = $this->accessor->declaredClassOf($subject, $property);
        if ($class === null || !self::isInstantiable($class)) {
            return;
        }

        $subject->{$property} ??= new $class();
        $child = $subject->{$property};
        if (is_object($child)) {
            $this->materialisePath($child, $segments);
        }
    }

    private static function isInstantiable(string $class): bool
    {
        if (!class_exists($class)) {
            return false;
        }

        $reflection  = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        return $reflection->isInstantiable() && ($constructor === null || $constructor->getNumberOfRequiredParameters() === 0);
    }
}
