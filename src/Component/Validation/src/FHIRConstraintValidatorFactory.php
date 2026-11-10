<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRFixedValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPatternValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileBindingValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileInvariantValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRTargetProfileValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;

/**
 * Constructs each FHIR constraint validator with its collaborators; any other constraint falls back
 * to Symfony's own factory.
 *
 * @internal built by {@see FHIRValidationServiceBuilder}
 */
final class FHIRConstraintValidatorFactory implements ConstraintValidatorFactoryInterface
{
    /** The generated enum namespaces bindings are checked against, for every release, as FHIRBundle configures them. */
    private const array ENUM_NAMESPACE_ROOTS = [
        'Ardenexal\FHIRTools\Component\Models\R4\Enum',
        'Ardenexal\FHIRTools\Component\Models\R4B\Enum',
        'Ardenexal\FHIRTools\Component\Models\R5\Enum',
    ];

    private readonly ConstraintValidatorFactory $fallback;

    /** @var array<class-string<Constraint>, ConstraintValidatorInterface> */
    private array $validators = [];

    public function __construct(
        private readonly PropertyAccessorInterface $accessor,
        private readonly FHIRPathService $pathService,
        private readonly FHIRValidationMessageRegistry $messages,
        private readonly FHIRAttributeReaderInterface $sliceAttributes,
        private readonly ?FHIRTerminologyClientInterface $terminologyClient,
        private readonly FHIRReferenceResolverInterface $referenceResolver,
    ) {
        $this->fallback = new ConstraintValidatorFactory();
    }

    public function getInstance(Constraint $constraint): ConstraintValidatorInterface
    {
        // One instance per constraint class, as Symfony's own factory keeps
        return $this->validators[$constraint::class] ??= match (true) {
            $constraint instanceof FHIRPathInvariant     => new FHIRPathInvariantValidator($this->pathService, $this->messages),
            $constraint instanceof FHIRValueSetBinding   => new FHIRValueSetBindingValidator($this->messages, self::ENUM_NAMESPACE_ROOTS, $this->terminologyClient),
            $constraint instanceof FHIRFixedValue        => new FHIRFixedValueValidator($this->messages),
            $constraint instanceof FHIRPatternValue      => new FHIRPatternValueValidator($this->messages),
            $constraint instanceof FHIRProfileConstraint => new FHIRProfileConstraintValidator($this->accessor),
            $constraint instanceof FHIRProfileInvariant  => new FHIRProfileInvariantValidator($this->pathService, $this->messages, $this->accessor),
            $constraint instanceof FHIRProfileBinding    => new FHIRProfileBindingValidator($this->accessor),
            $constraint instanceof FHIRSliceConstraint   => new FHIRSliceConstraintValidator($this->accessor, new SliceDiscriminatorMatcher($this->accessor), $this->sliceAttributes),
            $constraint instanceof FHIRTargetProfile     => new FHIRTargetProfileValidator($this->referenceResolver, $this->messages),
            default                                      => $this->fallback->getInstance($constraint),
        };
    }
}
