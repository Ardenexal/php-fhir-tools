<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class FHIRPatternValueValidator extends ConstraintValidator
{
    public const string DEFAULT_MESSAGE = 'The value does not match the required pattern.';

    public function __construct(
        private readonly FHIRValidationMessageRegistry $messageRegistry,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRPatternValue) {
            throw new UnexpectedTypeException($constraint, FHIRPatternValue::class);
        }

        if ($value === null) {
            return;
        }

        $valueArray = $value instanceof \JsonSerializable
            ? (array) $value->jsonSerialize()
            : (array) $value;

        if (!$this->isSubset($constraint->pattern, $valueArray)) {
            $override = $this->messageRegistry->getOverride('FHIRPatternValue');
            $this->context->buildViolation($override ?? self::DEFAULT_MESSAGE)
                ->setCode(FHIRViolationCode::ERROR)
                ->addViolation();
        }
    }

    /**
     * Recursively checks that all keys in $pattern are present in $value with equal values.
     *
     * @param array<string, mixed> $pattern
     * @param array<string, mixed> $value
     */
    private function isSubset(array $pattern, array $value): bool
    {
        foreach ($pattern as $key => $expected) {
            if (!array_key_exists($key, $value)) {
                return false;
            }

            if (is_array($expected)) {
                if (!is_array($value[$key]) || !$this->isSubset($expected, $value[$key])) {
                    return false;
                }
            } elseif ($value[$key] !== $expected) {
                return false;
            }
        }

        return true;
    }
}
