<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRMaxLength;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Checks that a primitive value holds no more characters than a profile's maxLength allows.
 *
 * Enforces a #[FHIRMaxLength] constraint. A plain string, a primitive wrapper (which is
 * \Stringable), and a bare integer or boolean are measured by their FHIR string form; any other
 * value is left alone. When the property repeats, each item is checked and reported at its index.
 * The length and the message follow the HL7 reference validator.
 */
final class FHIRMaxLengthValidator extends ConstraintValidator
{
    public const string DEFAULT_MESSAGE = 'value is longer than permitted maximum length of {{ max }}';

    public function __construct(
        private readonly FHIRValidationMessageRegistry $messageRegistry = new FHIRValidationMessageRegistry(),
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRMaxLength) {
            throw new UnexpectedTypeException($constraint, FHIRMaxLength::class);
        }

        if (is_array($value)) {
            foreach ($value as $index => $item) {
                $this->check($item, $constraint, '[' . $index . ']');
            }

            return;
        }

        $this->check($value, $constraint, '');
    }

    private function check(mixed $value, FHIRMaxLength $constraint, string $path): void
    {
        $string = match (true) {
            is_string($value)             => $value,
            is_int($value)                => (string) $value,
            is_bool($value)               => $value ? 'true' : 'false',
            $value instanceof \Stringable => (string) $value,
            default                       => null,
        };

        if ($string === null || self::length($string) <= $constraint->max) {
            return;
        }

        $violation = $this->context->buildViolation($this->messageRegistry->getOverride('FHIRMaxLength') ?? self::DEFAULT_MESSAGE)
            ->setParameter('{{ max }}', (string) $constraint->max)
            ->setInvalidValue($value)
            ->setCode(FHIRViolationCode::ERROR);

        if ($path !== '') {
            $violation->atPath($path);
        }

        $violation->addViolation();
    }

    /**
     * The length in UTF-16 code units, as the reference validator (Java's String.length()) counts
     * it, so a character outside the Basic Multilingual Plane, such as an emoji, counts as two.
     */
    private static function length(string $value): int
    {
        return intdiv(strlen(mb_convert_encoding($value, 'UTF-16LE', 'UTF-8')), 2);
    }
}
