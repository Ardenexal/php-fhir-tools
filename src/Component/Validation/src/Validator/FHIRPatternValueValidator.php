<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates that a property contains the partial pattern declared in a StructureDefinition.
 *
 * Enforces a #[FHIRPatternValue] constraint: every key in the pattern must be present in the
 * instance with an equal value, applied recursively. Unlike a fixed value, the instance may
 * carry additional keys. A value that does not contain the pattern raises an ERROR violation.
 */
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
                if (!is_array($value[$key])) {
                    return false;
                }

                if (array_is_list($expected)) {
                    foreach ($expected as $expectedItem) {
                        $found = false;
                        foreach ($value[$key] as $actualItem) {
                            if ($this->isSubset((array) $expectedItem, (array) $actualItem)) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            return false;
                        }
                    }
                } elseif (!$this->isSubset($expected, $value[$key])) {
                    return false;
                }
            } elseif ((string) $value[$key] !== (string) $expected) {
                return false;
            }
        }

        return true;
    }
}
