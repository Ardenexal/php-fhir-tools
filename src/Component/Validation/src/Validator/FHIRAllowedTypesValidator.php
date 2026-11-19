<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRAllowedTypes;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Checks that a choice element holds one of the data types a profile allows.
 *
 * Enforces a #[FHIRAllowedTypes] constraint. The value's FHIR type is read from its class: the
 * class's own #[FHIRPrimitive], #[FHIRComplexType] or #[FhirResource] attribute, or the base type
 * of a #[FHIRProfile] class. The class's own attribute is read before its parents', so an `Age`
 * (which extends `Quantity`) is an Age, not a Quantity.
 *
 * Boolean, integer and decimal variants are held as bare PHP scalars: `bool`, `int`, and a numeric
 * `string` for a decimal, which keeps its lexical form. A string that is not numeric is a malformed
 * value of some other scalar variant (`"valueBoolean": "abc"`); its type cannot be told, and the
 * format check already reports it, so it is left alone. So is any value whose type cannot be read.
 * A model built in code may also hold a plain string where a string-like type such as `string` or
 * `code` is allowed, so a bare string is only reported when the profile allows none of those.
 */
final class FHIRAllowedTypesValidator extends ConstraintValidator
{
    public const string DEFAULT_MESSAGE = "The type '{{ type }}' is not allowed here; the profile allows {{ allowed }}";

    /** FHIR types a model built in code may hold as a plain PHP string. */
    private const array STRING_HELD_TYPES = [
        'base64Binary', 'canonical', 'code', 'date', 'dateTime', 'decimal', 'id', 'instant', 'markdown',
        'oid', 'string', 'time', 'uri', 'url', 'uuid',
    ];

    /** @var array<class-string, string|null> FHIR type per PHP class, once read */
    private array $typeByClass = [];

    public function __construct(
        private readonly FHIRValidationMessageRegistry $messageRegistry = new FHIRValidationMessageRegistry(),
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRAllowedTypes) {
            throw new UnexpectedTypeException($constraint, FHIRAllowedTypes::class);
        }

        if ($value === null || $constraint->types === []) {
            return;
        }

        // A choice element does not repeat, but a list is checked item by item rather than skipped
        if (is_array($value)) {
            foreach ($value as $index => $item) {
                $this->check($item, $constraint, '[' . $index . ']');
            }

            return;
        }

        $this->check($value, $constraint, '');
    }

    private function check(mixed $value, FHIRAllowedTypes $constraint, string $path): void
    {
        $type = $this->fhirTypeOf($value);

        if ($type === null || in_array($type, $constraint->types, true)) {
            return;
        }

        if (is_string($value) && array_intersect($constraint->types, self::STRING_HELD_TYPES) !== []) {
            return;
        }

        $violation = $this->context->buildViolation($this->messageRegistry->getOverride('FHIRAllowedTypes') ?? self::DEFAULT_MESSAGE)
            ->setParameters([
                '{{ type }}'    => $type,
                '{{ allowed }}' => implode(', ', $constraint->types),
            ])
            ->setInvalidValue($value)
            ->setCode(FHIRViolationCode::ERROR);

        if ($path !== '') {
            $violation->atPath($path);
        }

        $violation->addViolation();
    }

    /**
     * The FHIR type code of a value as the model holds it, or null when it cannot be told.
     */
    private function fhirTypeOf(mixed $value): ?string
    {
        return match (true) {
            is_bool($value)   => 'boolean',
            is_int($value)    => 'integer',
            is_float($value)  => 'decimal',
            is_string($value) => is_numeric($value) ? 'decimal' : null,
            is_object($value) => $this->typeByClass[$value::class] ??= self::classType(new \ReflectionClass($value)),
            default           => null,
        };
    }

    /**
     * The FHIR type a generated class models, from the nearest class in its hierarchy that declares one.
     *
     * @param \ReflectionClass<object> $class
     */
    private static function classType(\ReflectionClass $class): ?string
    {
        for ($current = $class; $current !== false; $current = $current->getParentClass()) {
            $type = ($current->getAttributes(FHIRPrimitive::class)[0] ?? null)?->newInstance()->primitiveType
                ?? ($current->getAttributes(FHIRComplexType::class)[0] ?? null)?->newInstance()->typeName
                ?? ($current->getAttributes(FhirResource::class)[0] ?? null)?->newInstance()->type
                ?? ($current->getAttributes(FHIRProfile::class)[0] ?? null)?->newInstance()->baseType;

            if ($type !== null && $type !== '') {
                return $type;
            }
        }

        return null;
    }
}
