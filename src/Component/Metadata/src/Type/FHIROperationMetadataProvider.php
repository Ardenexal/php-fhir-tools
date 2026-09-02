<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

/**
 * Reads operation attributes off generated operation classes.
 *
 * @author Ardenexal
 */
final class FHIROperationMetadataProvider implements FHIROperationMetadataProviderInterface
{
    /** @var array<class-string, FhirOperation|false> */
    private array $operationCache = [];

    /** @var array<class-string, FhirOperationPayload|false> */
    private array $payloadCache = [];

    /** @var array<class-string, list<FhirOperationParameter>> */
    private array $parameterCache = [];

    /**
     * {@inheritDoc}
     */
    public function operationOf(object|string $subject): ?FhirOperation
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return null;
        }

        if (!isset($this->operationCache[$class])) {
            /** @var FhirOperation|null $found */
            $found                        = self::firstClassAttribute($class, FhirOperation::class);
            $this->operationCache[$class] = $found ?? false;
        }

        return $this->operationCache[$class] ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function payloadOf(object|string $subject): ?FhirOperationPayload
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return null;
        }

        if (!isset($this->payloadCache[$class])) {
            /** @var FhirOperationPayload|null $found */
            $found                      = self::firstClassAttribute($class, FhirOperationPayload::class);
            $this->payloadCache[$class] = $found ?? false;
        }

        return $this->payloadCache[$class] ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function parametersOf(object|string $subject): array
    {
        $class = self::classOf($subject);

        if ($class === null) {
            return [];
        }

        if (isset($this->parameterCache[$class])) {
            return $this->parameterCache[$class];
        }

        $descriptors = [];

        // Every property, not just public ones, matching what the mapper read before this existed.
        foreach ((new \ReflectionClass($class))->getProperties() as $property) {
            foreach ($property->getAttributes(FhirOperationParameter::class) as $attribute) {
                $descriptors[] = $attribute->newInstance();
            }
        }

        return $this->parameterCache[$class] = $descriptors;
    }

    /**
     * The first instance of a class-level attribute, or null.
     *
     * @param class-string $class     Class to inspect
     * @param class-string $attribute Attribute to read
     *
     * @return object|null The instantiated attribute, or null when the class declares none
     */
    private static function firstClassAttribute(string $class, string $attribute): ?object
    {
        try {
            $attributes = (new \ReflectionClass($class))->getAttributes($attribute);
        } catch (\ReflectionException) {
            return null;
        }

        return $attributes === [] ? null : $attributes[0]->newInstance();
    }

    /**
     * Resolve a subject to a loadable class name.
     *
     * @param object|string $subject An instance or class name
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
}
