<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;

/**
 * Reads FHIR type ancestry off generated model inheritance.
 *
 * @author Ardenexal
 */
final class FHIRTypeAncestryProvider implements FHIRTypeAncestryProviderInterface
{
    /** @var array<string, list<string>> */
    private array $cache = [];

    public function __construct(
        private readonly FHIRModelClassLocatorInterface $modelClasses = new FHIRModelClassLocator(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function ancestryOf(string $fhirTypeName, ?string $fhirVersion = null): array
    {
        $key = $fhirTypeName . '|' . ($fhirVersion ?? '*');

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $class = $this->modelClasses->locate($fhirTypeName, $fhirVersion);

        if ($class === null) {
            return $this->cache[$key] = [];
        }

        $names = [];

        for ($cursor = get_parent_class($class); $cursor !== false; $cursor = get_parent_class($cursor)) {
            $name = $this->fhirTypeNameOf($cursor);

            if ($name !== null && !in_array($name, $names, true)) {
                $names[] = $name;
            }
        }

        return $this->cache[$key] = $names;
    }

    /**
     * The FHIR type name a single class declares, across the three structural attributes that carry
     * one under a different argument name.
     *
     * Asking each attribute by its own class is deliberate. A scan for an argument called `type`
     * answers only for resources, because `FHIRComplexType` spells it `typeName` and `FHIRPrimitive`
     * spells it `primitiveType` — a miss that stays invisible whenever the PHP short name happens to
     * equal the FHIR type name.
     *
     * @param class-string $class
     */
    private function fhirTypeNameOf(string $class): ?string
    {
        $resource = $this->attributes->classAttributes($class, FhirResource::class);
        if ($resource !== []) {
            return $resource[0]->getResourceType();
        }

        $complex = $this->attributes->classAttributes($class, FHIRComplexType::class);
        if ($complex !== []) {
            return $complex[0]->typeName;
        }

        $primitive = $this->attributes->classAttributes($class, FHIRPrimitive::class);
        if ($primitive !== []) {
            return $primitive[0]->primitiveType;
        }

        return null;
    }
}
