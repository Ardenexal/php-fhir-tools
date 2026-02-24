<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * getValue(): System.[type]
 *
 * FHIR R4 FHIRPath extension function.
 *
 * Returns the underlying system primitive value for each item in the input
 * collection. Used to extract the raw PHP value from FHIR primitive wrapper
 * objects (e.g. FHIRString → PHP string, FHIRBoolean → PHP bool).
 *
 * Unwrapping rules (applied per item):
 *  - PHP scalar (int, float, string, bool) → returned as-is (already system value)
 *  - BackedEnum                            → enum->value
 *  - Object with #[FHIRPrimitive] attribute on class or any ancestor
 *      and a public $value property        → $object->value
 *  - Object with null $value              → item omitted (empty contribution)
 *  - Complex FHIR object (no FHIRPrimitive attribute) → item omitted
 *  - null / array                         → item omitted
 *
 * Note: properties accessed through normal FHIRPath navigation (e.g. name.given)
 * are already unwrapped by the evaluator's wrapValue()/normalizeValue() pipeline.
 * getValue() is most useful when $this is the root wrapper object, or when an
 * un-navigated primitive wrapper appears directly in the focus collection.
 *
 * Does NOT import CodeGeneration classes — uses reflection with string matching
 * on the attribute name to detect the #[FHIRPrimitive] attribute, keeping the
 * FHIRPath component independent of CodeGeneration.
 *
 * Spec reference: FHIR R4 FHIRPath supplement §2.2
 *
 * @author FHIR Tools Contributors
 */
final class GetValueFunction extends AbstractFunction
{
    /**
     * Per-class cache: class name → is FHIR primitive?
     * Avoids repeated reflection within a single evaluator/function lifetime.
     *
     * @var array<string, bool>
     */
    private array $primitiveCache = [];

    public function __construct()
    {
        parent::__construct('getValue');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $results = [];
        foreach ($input as $item) {
            $unwrapped = $this->unwrap($item);
            if ($unwrapped !== null) {
                $results[] = $unwrapped;
            }
        }

        return Collection::from($results);
    }

    /**
     * Unwrap a value to its underlying PHP system primitive.
     *
     * Returns null for complex types, null wrappers, and non-primitive values
     * (null result causes the item to be omitted from the output collection).
     */
    private function unwrap(mixed $value): mixed
    {
        // PHP scalars are already the system value
        if (is_bool($value) || is_int($value) || is_float($value) || is_string($value)) {
            return $value;
        }

        if ($value === null) {
            return null;
        }

        // BackedEnum → underlying scalar
        if ($value instanceof \BackedEnum) {
            return $value->value;
        }

        if (!is_object($value)) {
            return null;
        }

        // FHIR primitive wrapper: look for #[FHIRPrimitive] on the class hierarchy
        $class = get_class($value);
        if (!array_key_exists($class, $this->primitiveCache)) {
            $this->primitiveCache[$class] = $this->detectFhirPrimitive($class);
        }

        if ($this->primitiveCache[$class] && property_exists($value, 'value')) {
            // Return the wrapped scalar; null means an empty/unset primitive wrapper
            return $value->value;
        }

        // Complex type with no underlying system primitive
        return null;
    }

    /**
     * Walk the class hierarchy and return true if any class carries a
     * #[FHIRPrimitive] attribute.
     *
     * Uses attribute name string matching (str_ends_with 'FHIRPrimitive') so that
     * this function does not need to import the CodeGeneration attribute class.
     */
    private function detectFhirPrimitive(string $class): bool
    {
        if (!class_exists($class)) {
            return false;
        }

        $reflection = new \ReflectionClass($class);
        do {
            foreach ($reflection->getAttributes() as $attribute) {
                if (str_ends_with($attribute->getName(), 'FHIRPrimitive')) {
                    return true;
                }
            }

            $reflection = $reflection->getParentClass();
        } while ($reflection !== false);

        return false;
    }
}
