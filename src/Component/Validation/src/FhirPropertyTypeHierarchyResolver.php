<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;

/**
 * Resolves FHIR element types by reading #[FhirProperty(fhirType: ...)] attributes
 * from generated model class properties. No codegen changes are required — every
 * generated model property already carries this attribute.
 *
 * Results are cached per class+property pair to avoid repeated reflection.
 */
final class FhirPropertyTypeHierarchyResolver implements FHIRTypeHierarchyResolverInterface
{
    /** @var array<string, string|null> */
    private array $cache = [];

    public function resolvePropertyType(object $element, string $propertyName): ?string
    {
        $key = $element::class . '::' . $propertyName;

        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }

        return $this->cache[$key] = $this->resolve($element, $propertyName);
    }

    private function resolve(object $element, string $propertyName): ?string
    {
        $ref = new \ReflectionClass($element);

        // For PHP 8 constructor-promoted properties, the attribute is propagated to
        // both the parameter and the property when TARGET_PROPERTY is set on the attribute.
        if ($ref->hasProperty($propertyName)) {
            foreach ($ref->getProperty($propertyName)->getAttributes(FhirProperty::class) as $attr) {
                $fhirType = $attr->newInstance()->fhirType;

                // 'choice' is a synthetic marker for polymorphic value[x] elements;
                // no single type can be resolved, so defer.
                return $fhirType === 'choice' ? null : $fhirType;
            }
        }

        return null;
    }
}
