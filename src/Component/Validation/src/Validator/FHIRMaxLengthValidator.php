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
 * Enforces a #[FHIRMaxLength] constraint. A plain string and a primitive wrapper (which is
 * \Stringable) are measured by their string form, in Unicode characters. Any other value, such as
 * a bare integer or boolean, is left alone. When the property repeats, each item is checked and
 * reported at its index. The message is the HL7 reference validator's.
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
            $value instanceof \Stringable => (string) $value,
            default                       => null,
        };

        if ($string === null || mb_strlen($string, 'UTF-8') <= $constraint->max) {
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
}
