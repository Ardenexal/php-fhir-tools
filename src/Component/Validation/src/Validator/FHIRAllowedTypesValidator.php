<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRAllowedTypes;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRValueType;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Checks that a choice element holds one of the data types a profile allows.
 *
 * Enforces a #[FHIRAllowedTypes] constraint. The value's FHIR type is read by FHIRValueType.
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
        $type = FHIRValueType::of($value);

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
}
