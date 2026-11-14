<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates that a property exactly equals the fixed value declared in a StructureDefinition.
 *
 * Enforces a #[FHIRFixedValue] constraint. A scalar fixed value must equal the instance value; FHIR
 * primitive wrapper objects (which are \Stringable) are coerced to string first, so a correctly-valued
 * wrapper is not flagged, and a fixed number compares by value (see scalarEquals()).
 *
 * A complex fixed value (an array, as `fixedCodeableConcept` or `fixedQuantity` decode from JSON) is
 * compared as exact structure, which is what separates it from a pattern
 * ({@see FHIRPatternValueValidator}):
 *  - every element the fixed value carries must be present with an equal value, compared recursively;
 *  - a repeating element must hold as many items as the fixed value, compared in order;
 *  - an element the instance carries and the fixed value does not is itself a violation.
 *
 * Each difference is its own ERROR violation at the element it concerns ("coding[0].code"), worded as
 * the HL7 reference validator words it, so one wrong value does not hide another. When the property
 * repeats, the fixed value applies to every item. A `FHIRFixedValue` override in the message registry
 * replaces the wrong-value message only; the missing, extra and count messages are fixed.
 */
final class FHIRFixedValueValidator extends ConstraintValidator
{
    public const string DEFAULT_MESSAGE = "Value is '{{ value }}' but is fixed to '{{ fixed }}'";

    public const string EXTRA_ELEMENT_MESSAGE = 'The element {{ element }} is present in the instance but not allowed in the applicable fixed value specified in profile';

    public const string MISSING_ELEMENT_MESSAGE = "Missing element '{{ element }}' - required by the applicable fixed value specified in profile";

    public const string COUNT_MESSAGE = 'Expected {{ expected }} but found {{ actual }} {{ element }} element(s) in the applicable fixed value specified in profile';

    public function __construct(
        private readonly FHIRValidationMessageRegistry $messageRegistry,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRFixedValue) {
            throw new UnexpectedTypeException($constraint, FHIRFixedValue::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_array($constraint->value)) {
            $this->compareScalar($value, $constraint->value, '');

            return;
        }

        // A repeating property holds a list of items, and the fixed value is for each one
        if (is_array($value) && array_is_list($value)) {
            foreach ($value as $index => $item) {
                $this->compareComplex($item, $constraint->value, '[' . $index . ']');
            }

            return;
        }

        $this->compareComplex($value, $constraint->value, '');
    }

    /**
     * Compare one instance element against a complex fixed value, reporting every difference.
     *
     * @param array<array-key, mixed> $fixed The fixed value, as decoded from the StructureDefinition's JSON
     * @param string                  $path  Path of $actual below the validated property, '' for the property itself
     */
    private function compareComplex(mixed $actual, array $fixed, string $path): void
    {
        if (!is_object($actual) && !is_array($actual)) {
            // A complex fixed value against a primitive: nothing to walk, so report the whole value
            $this->reportValue($actual, $fixed, $path);

            return;
        }

        $children = self::children($actual);
        $matched  = [];

        foreach ($fixed as $name => $fixedChild) {
            $name = (string) $name;

            // `_code` and the like carry a primitive's id and extensions in JSON. The model folds
            // them into the primitive itself, so they are compared with it, below
            if (str_starts_with($name, '_')) {
                continue;
            }

            $property                    = self::propertyFor($name, $children);
            $matched[$property ?? $name] = true;
            $childPath                   = self::childPath($path, $name);
            $actualChild                 = $property !== null ? $children[$property] : null;

            if (is_array($fixedChild) && array_is_list($fixedChild)) {
                $this->compareList($actualChild, $fixedChild, $name, $childPath);
            } elseif ($actualChild === null) {
                $this->report(self::MISSING_ELEMENT_MESSAGE, ['{{ element }}' => $name], $childPath, null);
            } elseif (is_array($fixedChild)) {
                $this->compareComplex($actualChild, $fixedChild, $childPath);
            } else {
                $this->compareScalar($actualChild, $fixedChild, $childPath);
                $this->comparePrimitiveMetadata($actualChild, $fixed['_' . $name] ?? [], $childPath);
            }
        }

        foreach (array_keys($children) as $property) {
            if (!isset($matched[$property]) && !self::aliasesAMatchedChild($children[$property], $children, $matched)) {
                $this->report(self::EXTRA_ELEMENT_MESSAGE, ['{{ element }}' => $property], self::childPath($path, $property), $children[$property]);
            }
        }
    }

    /**
     * Compare a repeating element item by item, in order, after checking it holds as many items.
     *
     * @param list<mixed> $fixed
     */
    private function compareList(mixed $actual, array $fixed, string $name, string $path): void
    {
        $items = match (true) {
            $actual === null                            => [],
            is_array($actual) && array_is_list($actual) => $actual,
            default                                     => [$actual],
        };

        if (count($items) !== count($fixed)) {
            $this->report(
                self::COUNT_MESSAGE,
                ['{{ expected }}' => (string) count($fixed), '{{ actual }}' => (string) count($items), '{{ element }}' => $name],
                $path,
                $actual,
            );

            return;
        }

        foreach ($fixed as $index => $fixedItem) {
            $itemPath = $path . '[' . $index . ']';

            if (is_array($fixedItem)) {
                $this->compareComplex($items[$index], $fixedItem, $itemPath);
            } else {
                $this->compareScalar($items[$index], $fixedItem, $itemPath);
            }
        }
    }

    /**
     * Compare a primitive's id and extensions with the fixed value's `_name` entry for it.
     *
     * Both must be absent or equal, so an extension the fixed value gives the primitive is required
     * and one it does not give is reported, as for any other element. A primitive held as a plain
     * scalar has neither.
     */
    private function comparePrimitiveMetadata(mixed $actual, mixed $fixedMetadata, string $path): void
    {
        $metadata = is_object($actual) ? self::children($actual) : [];
        unset($metadata['value']);
        $fixedMetadata = is_array($fixedMetadata) ? $fixedMetadata : [];

        if ($metadata !== [] || $fixedMetadata !== []) {
            $this->compareComplex($metadata, $fixedMetadata, $path);
        }
    }

    /**
     * Whether an unmatched child is the same object as a matched one under another name.
     *
     * A generated typed extension holds its value in both its variant property (`valueString`) and
     * the inherited choice property (`value`). That is one FHIR element, not an extra one.
     *
     * @param array<string, mixed> $children
     * @param array<string, true>  $matched
     */
    private static function aliasesAMatchedChild(mixed $child, array $children, array $matched): bool
    {
        if (!is_object($child)) {
            return false;
        }

        foreach (array_keys($matched) as $property) {
            if (($children[$property] ?? null) === $child) {
                return true;
            }
        }

        return false;
    }

    private function compareScalar(mixed $actual, mixed $fixed, string $path): void
    {
        if (!self::scalarEquals($actual, $fixed)) {
            $this->reportValue($actual, $fixed, $path);
        }
    }

    /**
     * Whether an instance value equals a scalar fixed value.
     *
     * Strict, as the fixed value's own type says what the element holds, with two exceptions that
     * follow from how each side is read. A wrapper or enum is compared by its string form. A fixed
     * number compares numerically against a numeric string: the model keeps a decimal as a string to
     * preserve its lexical form, while JSON decoding turns the fixed `4.50` into the float 4.5 and a
     * fixed `5` into the integer 5. So `4.50` equals a fixed `4.5`, and `5.0` equals a fixed `5`.
     * A whole number is compared digit by digit, so integers beyond a float's precision stay distinct.
     */
    private static function scalarEquals(mixed $actual, mixed $fixed): bool
    {
        $actual = self::scalarOf($actual);

        if (is_int($fixed) && is_string($actual) && preg_match('/^([+-]?)0*(\d+?)(?:\.0*)?$/', $actual, $whole) === 1) {
            return ($whole[2] === '0' ? '0' : ($whole[1] === '-' ? '-' : '') . $whole[2]) === (string) $fixed;
        }

        if ((is_int($fixed) || is_float($fixed)) && is_string($actual) && is_numeric($actual)) {
            return (float) $actual === (float) $fixed;
        }

        if (is_bool($fixed) && is_string($actual)) {
            return $actual === ($fixed ? 'true' : 'false');
        }

        return $actual === $fixed;
    }

    /** The comparable form of an instance value: wrappers and enums become their string value. */
    private static function scalarOf(mixed $value): mixed
    {
        return match (true) {
            $value instanceof \BackedEnum => $value->value,
            $value instanceof \Stringable => (string) $value,
            default                       => $value,
        };
    }

    /**
     * The elements an instance object or array carries, by name, absent ones left out.
     *
     * Read as public properties because that is how the models hold elements. An empty list is an
     * absent repeating element, as the model initialises every list to [].
     *
     * @param object|array<array-key, mixed> $element
     *
     * @return array<string, mixed>
     */
    private static function children(object|array $element): array
    {
        $children = [];

        foreach (is_object($element) ? get_object_vars($element) : $element as $name => $child) {
            if ($child !== null && $child !== []) {
                $children[(string) $name] = $child;
            }
        }

        return $children;
    }

    /**
     * The instance property holding the fixed value's element $name, or null when the instance lacks it.
     *
     * A choice element is named by its variant in JSON (`valueQuantity`) and held in one property in the
     * model (`value`), so a name with no property of its own is looked up by that property's prefix. The
     * value found there must be of the variant's type: a `valueCode` is not matched by a held
     * `StringPrimitive`. A plain PHP scalar carries no type, so it is accepted.
     *
     * @param array<string, mixed> $children
     */
    private static function propertyFor(string $name, array $children): ?string
    {
        if (array_key_exists($name, $children)) {
            return $name;
        }

        foreach (array_keys($children) as $property) {
            $suffix = substr($name, strlen($property));
            if (str_starts_with($name, $property) && $suffix !== '' && ctype_upper($suffix[0])
                                                  && self::holdsVariant($children[$property], $suffix)
            ) {
                return $property;
            }
        }

        return null;
    }

    /**
     * Whether a choice property's value is of the variant type $type (`Quantity`, `String`), judged by
     * the model class or an ancestor: `Quantity`, or `StringPrimitive` for a primitive.
     */
    private static function holdsVariant(mixed $value, string $type): bool
    {
        if (!is_object($value)) {
            return true;
        }

        for ($class = $value::class; $class !== false; $class = get_parent_class($class)) {
            $short = substr((string) strrchr('\\' . $class, '\\'), 1);
            if ($short === $type || $short === $type . 'Primitive') {
                return true;
            }
        }

        return false;
    }

    private static function childPath(string $path, string $name): string
    {
        return $path === '' ? $name : $path . '.' . $name;
    }

    private function reportValue(mixed $actual, mixed $fixed, string $path): void
    {
        $this->report(
            $this->messageRegistry->getOverride('FHIRFixedValue') ?? self::DEFAULT_MESSAGE,
            ['{{ value }}' => self::display($actual), '{{ fixed }}' => self::display($fixed)],
            $path,
            $actual,
        );
    }

    /** A value as it appears in a message: scalars as their FHIR string form, structures as JSON. */
    private static function display(mixed $value): string
    {
        $value = self::scalarOf($value);

        return match (true) {
            is_bool($value)                       => $value ? 'true' : 'false',
            is_scalar($value)                     => (string) $value,
            is_array($value) || is_object($value) => (string) json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            default                               => '',
        };
    }

    /**
     * @param array<string, string> $parameters
     */
    private function report(string $message, array $parameters, string $path, mixed $invalidValue): void
    {
        $builder = $this->context->buildViolation($message)
            ->setParameters($parameters)
            ->setInvalidValue($invalidValue)
            ->setCode(FHIRViolationCode::ERROR);

        if ($path !== '') {
            $builder->atPath($path);
        }

        $builder->addViolation();
    }
}
