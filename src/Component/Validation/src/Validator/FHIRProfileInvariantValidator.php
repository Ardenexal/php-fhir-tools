<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Validation\FHIRChoiceVariantReader;
use Ardenexal\FHIRTools\Component\Validation\FHIRPathResourceContext;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Evaluates a profile's FHIRPath invariant on each occurrence of the element it names.
 *
 * The outcome of one evaluation follows FHIRPathInvariantValidator: only a result other than a
 * single `true` fails, an empty result passes, and an expression the engine cannot evaluate is
 * reported as an eval-error. Each failure is reported at the occurrence it is about, so an
 * invariant on a repeating element names the item, `component[1]`.
 *
 * @author Ardenexal
 */
final class FHIRProfileInvariantValidator extends ConstraintValidator
{
    private readonly FHIRChoiceVariantReader $reader;

    /**
     * @param bool $reportBestPractice Whether to evaluate invariants marked `elementdefinition-bestpractice`; off by
     *                                 default, as for FHIRPathInvariantValidator
     */
    public function __construct(
        private readonly FHIRPathService $pathService,
        private readonly FHIRValidationMessageRegistry $messageRegistry,
        PropertyAccessorInterface $propertyAccessor,
        private readonly bool $reportBestPractice = false,
        ?FHIRChoiceVariantReader $reader = null,
    ) {
        $this->reader = $reader ?? new FHIRChoiceVariantReader($propertyAccessor);
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRProfileInvariant) {
            throw new UnexpectedTypeException($constraint, FHIRProfileInvariant::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_object($value)) {
            throw new UnexpectedValueException($value, 'object');
        }

        if ($constraint->bestPractice && !$this->reportBestPractice) {
            return;
        }

        $base = (string) $this->context->getPropertyPath();

        foreach ($this->occurrences($value, $constraint->path) as [$path, $focus]) {
            // %resource is the resource around this occurrence, found from the validation root
            $at      = $path === '' ? $base : ($base === '' ? $path : "{$base}.{$path}");
            $context = FHIRPathResourceContext::at($this->context->getRoot(), $at);

            try {
                $result = $this->pathService->evaluate($constraint->expression, $focus, $context);
            } catch (FHIRPathException) {
                $this->report(
                    sprintf('FHIRPath invariant `%s` could not be evaluated: %s', $constraint->key, $constraint->expression),
                    $path,
                    FHIRViolationCode::EVAL_ERROR,
                );

                continue;
            }

            if ($result->isEmpty() || ($result->count() === 1 && $result->first() === true)) {
                continue;
            }

            $this->report(
                $this->messageRegistry->getOverride('FHIRPathInvariant') ?? $constraint->human,
                $path,
                $constraint->severity === 'warning' ? FHIRViolationCode::WARNING : FHIRViolationCode::ERROR,
            );
        }
    }

    /**
     * Each occurrence of the element at $path, with its concrete path: one per item of a repeating element.
     *
     * @return list<array{string, mixed}>
     */
    private function occurrences(object $subject, string $path): array
    {
        if ($path === '') {
            return [['', $subject]];
        }

        $found = [];
        foreach ($this->reader->readGroups($subject, $path) as $group) {
            $value = $group['value'];
            if (!is_array($value)) {
                if ($value !== null) {
                    $found[] = [$group['path'], $value];
                }

                continue;
            }

            foreach ($value as $index => $item) {
                if ($item !== null) {
                    $found[] = ["{$group['path']}[{$index}]", $item];
                }
            }
        }

        return $found;
    }

    private function report(string $message, string $path, string $code): void
    {
        $builder = $this->context->buildViolation($message)->setCode($code);
        if ($path !== '') {
            $builder = $builder->atPath($path);
        }

        $builder->addViolation();
    }
}
