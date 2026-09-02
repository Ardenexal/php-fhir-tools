<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Validation\FHIRChoiceVariantReader;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Validates a FHIR profile constraint at class level.
 *
 * Reads the property value at FHIRProfileConstraint::$path via PropertyAccessor, instantiates
 * the declared inner Symfony constraint with the provided options, and delegates validation to
 * the current context's validator. Group propagation is handled by Symfony — this validator is
 * only invoked when the constraint's assigned groups are active.
 *
 * @author Ardenexal
 */
final class FHIRProfileConstraintValidator extends ConstraintValidator
{
    /**
     * Violations already emitted this run, keyed by ExecutionContext then by path+group+rule.
     *
     * Static because a ConstraintValidatorFactory may construct a fresh validator for every
     * constraint, which would leave per-instance state permanently empty.
     *
     * @var \WeakMap<ExecutionContextInterface, array<string, true>>|null
     */
    private static ?\WeakMap $emitted = null;

    private readonly FHIRChoiceVariantReader $reader;

    public function __construct(
        PropertyAccessorInterface $propertyAccessor,
        ?FHIRChoiceVariantReader $reader = null,
    ) {
        $this->reader = $reader ?? new FHIRChoiceVariantReader($propertyAccessor);
    }

    /** @return \WeakMap<ExecutionContextInterface, array<string, true>> */
    private static function emitted(): \WeakMap
    {
        /** @var \WeakMap<ExecutionContextInterface, array<string, true>> $map */
        $map = self::$emitted ??= new \WeakMap();

        return $map;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRProfileConstraint) {
            throw new UnexpectedTypeException($constraint, FHIRProfileConstraint::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_object($value)) {
            throw new UnexpectedValueException($value, 'object');
        }

        // An absent element makes its whole subtree unreadable: with no `code`, the accessor cannot
        // traverse `code.coding` and throws. That is not this constraint's violation to report. The
        // reference validator names the absent ancestor once and says nothing about its descendants,
        // because an element nested inside something that is not there cannot itself be short —
        // reporting the subtree would multiply one missing element into a dozen findings. The
        // ancestor's own cardinality rule is a separate FHIRProfileConstraint and still fires.
        // One group per parent the element hangs off. No groups at all means an ancestor is absent, and
        // that is not this constraint's violation to report: the reference validator names the absent
        // ancestor once and says nothing about its descendants, where reporting the subtree would
        // multiply one missing element into a dozen findings. The ancestor's own rule is a separate
        // constraint and still fires.
        $groups = $this->reader->readGroups($value, $constraint->path);

        if ($groups === []) {
            return;
        }

        $innerConstraint = new ($constraint->constraint)(...$constraint->options);



        // Validate the property value against the inner constraint in a fresh context so we can
        // re-emit each violation via $this->context->buildViolation(). This keeps the outer
        // FHIRProfileConstraint as the violation's constraint, enabling profile-group attribution
        // in FHIRValidationService (getConstraint() returns FHIRProfileConstraint, not Count etc.).
        foreach ($groups as $group) {
            // Cardinality counts occurrences, so a Count sizes the occurrence list. Every other rule
            // inspects the element itself and gets it exactly as the model holds it.
            $subject = $innerConstraint instanceof Count ? $group['occurrences'] : $group['value'];

            $this->reportViolations(
                $this->context->getValidator()->validate($subject, $innerConstraint, ['Default']),
                $constraint,
            );
        }
    }

    /**
     * Re-emit each inner violation against the profile constraint, so profile-group attribution survives.
     *
     * @param iterable<ConstraintViolationInterface> $innerViolations Violations from the inner rule
     * @param FHIRProfileConstraint                  $constraint      The profile constraint being evaluated
     */
    private function reportViolations(iterable $innerViolations, FHIRProfileConstraint $constraint): void
    {
        foreach ($innerViolations as $v) {
            $innerPath = $v->getPropertyPath();
            $path      = match (true) {
                $innerPath    === ''         => $constraint->path,
                $innerPath[0] === '['        => $constraint->path . $innerPath,
                default                      => $constraint->path . '.' . $innerPath,
            };

            // One differential rule can reach us as several constraints. A profile that declares
            // `category` 1..* and slices it 1..1 generates two Count attributes on the same path, and
            // an absent element trips both - so the element is reported missing twice where the
            // reference validator reports it once.
            //
            // Deduped on the emitted violation rather than on the constraint, deliberately: dropping
            // the second *constraint* would also drop a genuine max violation on an element whose min
            // is satisfied, because that one produces no violation from the min-only rule to mask it.
            // Keyed by rule as well as path so a Count violation cannot suppress a fixed-value one,
            // but deliberately NOT by message: the two Count attributes word their failures
            // differently ("1 element or more" against "exactly 1 element"), so keying on the
            // message lets both through and the element is still reported twice.
            $group      = $this->context->getGroup() ?? 'Default';
            $emitKey    = $path . '|' . $group . '|' . $constraint->constraint;
            $keys       = self::emitted();
            $ctx        = $this->context;
            $seen       = $keys[$ctx] ?? [];

            if (isset($seen[$emitKey])) {
                continue;
            }

            $seen[$emitKey] = true;
            $keys[$ctx]     = $seen;

            $this->context->buildViolation($v->getMessageTemplate(), $v->getParameters())
                ->atPath($path)
                ->setCode($v->getCode() ?? FHIRViolationCode::ERROR)
                ->addViolation();
        }
    }
}
