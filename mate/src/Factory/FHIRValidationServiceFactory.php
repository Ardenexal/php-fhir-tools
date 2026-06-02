<?php

declare(strict_types=1);

namespace App\Mate\Factory;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRReferenceResolver;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRFixedValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPatternValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRTargetProfileValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class FHIRValidationServiceFactory
{
    public static function create(FHIRPathService $pathService): FHIRValidationService
    {
        $accessor       = PropertyAccess::createPropertyAccessor();
        $registry       = new FHIRValidationMessageRegistry();
        $matcher        = new SliceDiscriminatorMatcher($accessor);
        $resolver       = new NullFHIRReferenceResolver();
        $defaultFactory = new ConstraintValidatorFactory();

        $factory = new class (
            $accessor,
            $registry,
            $pathService,
            $matcher,
            $resolver,
            $defaultFactory,
        ) implements ConstraintValidatorFactoryInterface {
            public function __construct(
                private readonly PropertyAccessorInterface $accessor,
                private readonly FHIRValidationMessageRegistry $registry,
                private readonly FHIRPathService $pathService,
                private readonly SliceDiscriminatorMatcher $matcher,
                private readonly NullFHIRReferenceResolver $resolver,
                private readonly ConstraintValidatorFactory $default,
            ) {
            }

            public function getInstance(Constraint $constraint): ConstraintValidatorInterface
            {
                return match (true) {
                    $constraint instanceof FHIRProfileConstraint => new FHIRProfileConstraintValidator($this->accessor),
                    $constraint instanceof FHIRPathInvariant     => new FHIRPathInvariantValidator($this->pathService, $this->registry),
                    $constraint instanceof FHIRValueSetBinding   => new FHIRValueSetBindingValidator(
                        $this->registry,
                        [
                            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum',
                            'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Enum',
                            'Ardenexal\\FHIRTools\\Component\\Models\\R5\\Enum',
                        ],
                    ),
                    $constraint instanceof FHIRFixedValue        => new FHIRFixedValueValidator($this->registry),
                    $constraint instanceof FHIRPatternValue      => new FHIRPatternValueValidator($this->registry),
                    $constraint instanceof FHIRSliceConstraint   => new FHIRSliceConstraintValidator($this->accessor, $this->matcher),
                    $constraint instanceof FHIRTargetProfile     => new FHIRTargetProfileValidator($this->resolver, $this->registry),
                    default                                      => $this->default->getInstance($constraint),
                };
            }
        };

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory($factory)
            ->getValidator();

        return new FHIRValidationService($validator, $pathService);
    }
}
