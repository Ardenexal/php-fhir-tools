<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Binds an execution context to a validator a test built itself.
 *
 * ConstraintValidator::initialize() is deprecated from symfony/validator 8.1, and its replacement,
 * validateInContext(), does not exist on the 6.4 and 7.4 lines this package also supports. Setting
 * the property works on all three, and is how ConstraintValidatorTestCase::setUp() binds its own
 * validator on 8.1.
 */
trait BindsValidatorContext
{
    /**
     * Gives the validator the test's context, or the one passed in, ready for validate().
     */
    private function bindContext(ConstraintValidator $validator, ?ExecutionContextInterface $context = null): void
    {
        (new \ReflectionProperty(ConstraintValidator::class, 'context'))->setValue($validator, $context ?? $this->context);
    }
}
