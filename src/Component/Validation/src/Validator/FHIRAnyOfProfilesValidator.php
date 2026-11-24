<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRAnyOfProfiles;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRValueType;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileConformance;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Util\PropertyPath;

/**
 * Checks that a value conforms to at least one of the profiles an element names.
 *
 * Enforces a #[FHIRAnyOfProfiles] constraint. Each profile is checked on the side by
 * {@see RuntimeProfileConformance}, as Java's validator does, and the findings follow Java's:
 *
 *  - No profile matches: one error saying so, then each profile's own errors, at their paths inside
 *    the value and tagged with the profile they come from, so the user can see what each one wants.
 *  - More than one matches: a warning, since the value is valid but which profile it follows is
 *    ambiguous.
 *  - No profile matches and at least one could not be checked (no registry knows it, or the check is
 *    nested too deep): the value is neither known to conform nor known not to, so it gets a
 *    `fhir:profile-not-checked` warning instead of errors.
 *
 * A value whose FHIR type the element allows without a profile passes unchecked, and so does a bare
 * scalar, which has no profile to be checked against. With no conformance checker at all, nothing is
 * reported. A repeating element is checked occurrence by occurrence, each reported at its index.
 */
final class FHIRAnyOfProfilesValidator extends ConstraintValidator
{
    public const string NO_MATCH_MESSAGE = 'Unable to find a match for the specified profile among choices: {{ profiles }}';

    /** Appended to each profile's own error, whose template and parameters are kept so it renders as it would have. */
    public const string PROFILE_ERROR_SUFFIX = ' (validating against {{ anyOfProfile }})';

    public const string MULTIPLE_MATCHES_MESSAGE = 'Found multiple matching profiles among {{ count }} choices: {{ profiles }}';

    public const string NOT_CHECKED_MESSAGE = 'Unable to check the value against the profiles {{ profiles }}: {{ unchecked }} cannot be resolved';

    public function __construct(
        private readonly ?RuntimeProfileConformance $conformance = null,
        private readonly FHIRValidationMessageRegistry $messageRegistry = new FHIRValidationMessageRegistry(),
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRAnyOfProfiles) {
            throw new UnexpectedTypeException($constraint, FHIRAnyOfProfiles::class);
        }

        if ($value === null || $constraint->profiles === [] || $this->conformance === null) {
            return;
        }

        if (is_array($value)) {
            foreach ($value as $index => $item) {
                $this->check($item, $constraint, $this->conformance, '[' . $index . ']');
            }

            return;
        }

        $this->check($value, $constraint, $this->conformance, '');
    }

    private function check(mixed $value, FHIRAnyOfProfiles $constraint, RuntimeProfileConformance $conformance, string $path): void
    {
        if (!is_object($value) || in_array(FHIRValueType::of($value), $constraint->openTypes, true)) {
            return;
        }

        $context   = $this->context;
        $validator = $context->getValidator();
        $matched   = [];
        $unchecked = [];
        $failed    = [];
        foreach ($constraint->profiles as $profile) {
            // A profile with a choice of profiles of its own runs it through this same instance, which
            // points $this->context at the side run; the findings below belong to this run
            try {
                $errors = $conformance->errors($validator, $value, $profile);
            } finally {
                $this->context = $context;
            }

            match (true) {
                $errors === null => $unchecked[]      = $profile,
                $errors === []   => $matched[]        = $profile,
                default          => $failed[$profile] = $errors,
            };
        }

        $profiles = implode(', ', $constraint->profiles);

        if (count($matched) > 1) {
            $this->report('FHIRAnyOfProfiles.multipleMatches', self::MULTIPLE_MATCHES_MESSAGE, [
                '{{ count }}'    => (string) count($constraint->profiles),
                '{{ profiles }}' => implode(', ', $matched),
            ], $path, FHIRViolationCode::WARNING);
        }

        if ($matched !== []) {
            return;
        }

        if ($unchecked !== []) {
            $this->report('FHIRAnyOfProfiles.notChecked', self::NOT_CHECKED_MESSAGE, [
                '{{ profiles }}'  => $profiles,
                '{{ unchecked }}' => implode(', ', $unchecked),
            ], $path, FHIRViolationCode::PROFILE_NOT_CHECKED);

            return;
        }

        $this->report('FHIRAnyOfProfiles', self::NO_MATCH_MESSAGE, ['{{ profiles }}' => $profiles], $path, FHIRViolationCode::ERROR);

        foreach ($failed as $profile => $errors) {
            foreach ($errors as $error) {
                $builder = $this->context->buildViolation($error->getMessageTemplate() . self::PROFILE_ERROR_SUFFIX)
                    ->setParameters([...$error->getParameters(), '{{ anyOfProfile }}' => $profile])
                    ->atPath(PropertyPath::append($path, $error->getPropertyPath()))
                    ->setCode(FHIRViolationCode::ERROR);
                if ($error->getPlural() !== null) {
                    $builder->setPlural($error->getPlural());
                }
                $builder->addViolation();
            }
        }
    }

    /**
     * @param array<string, string> $parameters
     */
    private function report(string $key, string $message, array $parameters, string $path, string $code): void
    {
        $this->context->buildViolation($this->messageRegistry->getOverride($key) ?? $message)
            ->setParameters($parameters)
            ->atPath($path)
            ->setCode($code)
            ->addViolation();
    }
}
