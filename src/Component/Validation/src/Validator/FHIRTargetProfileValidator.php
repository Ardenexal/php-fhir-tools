<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Validation\FHIRReferenceResolverInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates that a Reference- or canonical-typed property points to a resource
 * conforming to at least one of the declared target profile URLs.
 *
 * Resolution is delegated to {@see FHIRReferenceResolverInterface}. When the resolver
 * returns null (including the NullFHIRReferenceResolver default), validation is skipped
 * silently. canonical-typed values (strings or Stringable objects) are always skipped
 * because they cannot be resolved to an in-process PHP object.
 *
 * @author Ardenexal
 */
final class FHIRTargetProfileValidator extends ConstraintValidator
{
    public const string DEFAULT_PROFILE_MISMATCH_MESSAGE = 'Resource does not conform to any declared target profile. Expected one of: {{ profiles }}. Actual profile URL(s): {{ actual }}.';

    public const string DEFAULT_UNVERIFIABLE_MESSAGE = 'Cannot verify target profile conformance: the resolved object carries no #[FHIRProfile] attribute. Expected one of: {{ profiles }}.';

    public function __construct(
        private readonly FHIRReferenceResolverInterface $resolver,
        private readonly FHIRValidationMessageRegistry $messageRegistry,
    ) {
    }

    /**
     * Validates that a value conforms to declared target profiles.
     *
     * Skips null values, strings, and Stringable objects (canonicals). For objects
     * and arrays of objects, delegates resolution to the configured resolver and
     * checks the resolved object's #[FHIRProfile] attribute against the target profiles.
     *
     * @param mixed      $value      The property value to validate (null, string, object, array, or Stringable)
     * @param Constraint $constraint The #[FHIRTargetProfile] constraint with targetProfiles list
     *
     * @throws UnexpectedTypeException If constraint is not a FHIRTargetProfile instance
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRTargetProfile) {
            throw new UnexpectedTypeException($constraint, FHIRTargetProfile::class);
        }

        if ($value === null) {
            return;
        }

        // canonical-typed values (CanonicalPrimitive implements \Stringable, or raw string)
        if (is_string($value) || $value instanceof \Stringable) {
            return;
        }

        if (is_array($value)) {
            foreach ($value as $item) {
                if (is_object($item)) {
                    $this->validateItem($item, $constraint);
                }
            }

            return;
        }

        if (is_object($value)) {
            $this->validateItem($value, $constraint);
        }
    }

    /**
     * Checks if a resolved reference object conforms to at least one target profile.
     *
     * Resolves the item using the configured resolver. If resolution fails (returns null),
     * validation is skipped. If the resolved object lacks a #[FHIRProfile] attribute, emits
     * a WARNING-level violation. If the object has a profile but it doesn't match any
     * declared target profile URL, emits an ERROR-level violation.
     *
     * @param object            $item       The reference object to validate
     * @param FHIRTargetProfile $constraint The constraint holding target profile URLs
     */
    private function validateItem(object $item, FHIRTargetProfile $constraint): void
    {
        $resolved = $this->resolver->resolve($item);

        if ($resolved === null) {
            return;
        }

        $profileAttrs = (new \ReflectionClass($resolved))->getAttributes(FHIRProfile::class);

        if ($profileAttrs === []) {
            $override = $this->messageRegistry->getOverride('FHIRTargetProfile');
            $this->context->buildViolation($override ?? self::DEFAULT_UNVERIFIABLE_MESSAGE)
                ->setParameters(['{{ profiles }}' => implode(', ', $constraint->targetProfiles)])
                ->setCode(FHIRViolationCode::WARNING)
                ->addViolation();

            return;
        }

        foreach ($profileAttrs as $attr) {
            /** @var FHIRProfile $profile */
            $profile = $attr->newInstance();
            if (in_array($profile->profileUrl, $constraint->targetProfiles, true)) {
                return;
            }
        }

        $actualUrls = array_map(
            static fn (\ReflectionAttribute $a): string => $a->newInstance()->profileUrl,
            $profileAttrs,
        );

        $override = $this->messageRegistry->getOverride('FHIRTargetProfile');
        $this->context->buildViolation($override ?? self::DEFAULT_PROFILE_MISMATCH_MESSAGE)
            ->setParameters([
                '{{ profiles }}' => implode(', ', $constraint->targetProfiles),
                '{{ actual }}'   => implode(', ', $actualUrls),
            ])
            ->setInvalidValue($resolved)
            ->setCode(FHIRViolationCode::ERROR)
            ->addViolation();
    }
}
