<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;

/**
 * Resolves FHIR element types by reading #[FhirProperty(fhirType: ...)] attributes
 * from generated model class properties, and FHIR type hierarchies by walking the
 * generated PHP class inheritance chain reading #[FhirResource]/#[FHIRComplexType].
 * No codegen changes are required — every generated model carries these attributes.
 *
 * Results are cached per class (and per class+property) to avoid repeated reflection.
 */
final class FhirPropertyTypeHierarchyResolver implements FHIRTypeHierarchyResolverInterface
{
    /** @var array<string, string|null> */
    private array $propertyCache = [];

    /** @var array<class-string, list<string>> */
    private array $hierarchyCache = [];

    /**
     * Resolves the FHIR type of a property by reading its #[FhirProperty] attribute.
     *
     * @param object $element      The FHIR element (model object) to inspect
     * @param string $propertyName The property name to resolve the type for
     *
     * @return string|null The FHIR type name, or null if the property has no FhirProperty attribute or is a 'choice' polymorphic element
     */
    public function resolvePropertyType(object $element, string $propertyName): ?string
    {
        $key = $element::class . '::' . $propertyName;

        if (array_key_exists($key, $this->propertyCache)) {
            return $this->propertyCache[$key];
        }

        return $this->propertyCache[$key] = $this->resolve($element, $propertyName);
    }

    /**
     * Resolves the FHIR type hierarchy of an element by walking its class inheritance chain.
     *
     * @param object $element The FHIR element (model object) to inspect
     *
     * @return list<string> List of FHIR type names from the element up through its parent classes
     */
    public function resolveTypeHierarchy(object $element): array
    {
        $class = $element::class;

        if (isset($this->hierarchyCache[$class])) {
            return $this->hierarchyCache[$class];
        }

        $names = [];
        $ref   = new \ReflectionClass($element);

        for ($cursor = $ref; $cursor !== false; $cursor = $cursor->getParentClass()) {
            $name = $this->fhirTypeNameOf($cursor);
            if ($name !== null && !in_array($name, $names, true)) {
                $names[] = $name;
            }
        }

        return $this->hierarchyCache[$class] = $names;
    }

    /**
     * Helper method that performs uncached property type resolution by reading the FhirProperty attribute.
     *
     * @param object $element      The FHIR element to inspect
     * @param string $propertyName The property name to resolve
     *
     * @return string|null The FHIR type from the attribute, or null if not found or if the type is 'choice'
     */
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

    /**
     * @param \ReflectionClass<object> $ref
     */
    private function fhirTypeNameOf(\ReflectionClass $ref): ?string
    {
        foreach ($ref->getAttributes(FhirResource::class) as $attr) {
            return $attr->newInstance()->type;
        }

        foreach ($ref->getAttributes(FHIRComplexType::class) as $attr) {
            return $attr->newInstance()->typeName;
        }

        return null;
    }
}
