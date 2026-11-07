<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileBindingValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileInvariantValidator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistryFactory;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Metadata\Type\FhirPropertyTypeHierarchyResolver;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRReferenceResolver;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileAttributeReader;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRFixedValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPatternValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRTargetProfileValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;

/**
 * Builds the validation service the harness measures, wired identically to
 * FHIRValidatorSpecificationTest so harness numbers and suite numbers describe the same validator.
 *
 * enableAttributeMapping() is mandatory — Validation::createValidator() reads no PHP attributes at
 * all, so omitting it yields a validator that silently finds nothing.
 */
final class OracleValidationServiceFactory
{
    /**
     * @param RuntimeProfileResolver|null $runtimeProfiles Resolves profiles the resource's class does not declare from
     *                                                     StructureDefinitions at runtime; null uses the one
     *                                                     collaborators() builds over the release's core package
     */
    public static function create(
        FhirVersion $version,
        bool $resolveExtensions = true,
        ?RuntimeProfileResolver $runtimeProfiles = null,
    ): FHIRValidationService {
        $collaborators   = self::collaborators($resolveExtensions, $version);
        $runtimeProfiles ??= $collaborators->runtimeProfiles;
        $accessor       = PropertyAccess::createPropertyAccessor();
        $registry       = new FHIRValidationMessageRegistry();
        $pathService    = new FHIRPathService();
        $matcher        = new SliceDiscriminatorMatcher($accessor);
        $defaultFactory = new ConstraintValidatorFactory();
        // The slice validator finds sibling slices by reading attributes off the validated class. A
        // base resource checked against a runtime profile has none, so this reader adds them.
        $sliceReader    = new RuntimeProfileAttributeReader($runtimeProfiles);
        $enumNamespace  = "Ardenexal\\FHIRTools\\Component\\Models\\{$version->value}\\Enum";

        $factory = new class (
            $accessor,
            $registry,
            $pathService,
            $matcher,
            $collaborators,
            $defaultFactory,
            $enumNamespace,
            $sliceReader,
        ) implements ConstraintValidatorFactoryInterface {
            public function __construct(
                private readonly PropertyAccessorInterface $accessor,
                private readonly FHIRValidationMessageRegistry $registry,
                private readonly FHIRPathService $pathService,
                private readonly SliceDiscriminatorMatcher $matcher,
                private readonly OracleHarnessCollaborators $collaborators,
                private readonly ConstraintValidatorFactory $default,
                private readonly string $enumNamespace,
                private readonly FHIRAttributeReaderInterface $sliceReader,
            ) {
            }

            /**
             * The optional collaborators are read off $collaborators rather than defaulted, so a
             * pass that receives nothing says so at its call site instead of relying on a
             * constructor default that reads as if a client were configured.
             */
            public function getInstance(Constraint $constraint): ConstraintValidatorInterface
            {
                return match (true) {
                    $constraint instanceof FHIRProfileConstraint => new FHIRProfileConstraintValidator($this->accessor),
                    $constraint instanceof FHIRProfileInvariant  => new FHIRProfileInvariantValidator($this->pathService, $this->registry, $this->accessor),
                    $constraint instanceof FHIRProfileBinding    => new FHIRProfileBindingValidator($this->accessor),
                    $constraint instanceof FHIRPathInvariant     => new FHIRPathInvariantValidator($this->pathService, $this->registry),
                    $constraint instanceof FHIRValueSetBinding   => new FHIRValueSetBindingValidator($this->registry, [$this->enumNamespace], $this->collaborators->terminologyClient),
                    $constraint instanceof FHIRFixedValue        => new FHIRFixedValueValidator($this->registry),
                    $constraint instanceof FHIRPatternValue      => new FHIRPatternValueValidator($this->registry),
                    $constraint instanceof FHIRSliceConstraint   => new FHIRSliceConstraintValidator($this->accessor, $this->matcher, $this->sliceReader),
                    $constraint instanceof FHIRTargetProfile     => new FHIRTargetProfileValidator($this->collaborators->referenceResolver, $this->registry),
                    default                                      => $this->default->getInstance($constraint),
                };
            }
        };

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory($factory)
            ->getValidator();

        // The IG type registry gates FHIRValidationService's extension pass entirely
        // ($this->registry !== null). Without it the harness measured a validator with extension
        // resolution switched off, so neither unknown-extension rule could ever fire here.
        // create() with no IG directory still scans the generated base Extension directories.
        return new FHIRValidationService(
            $validator,
            $pathService,
            registry: $collaborators->igTypeRegistry,
            typeResolver: new FhirPropertyTypeHierarchyResolver(),
            runtimeProfiles: $runtimeProfiles,
        );
    }

    /**
     * The optional collaborators this harness wires, and the ones it leaves absent.
     *
     * Separate from create() so OracleHarnessCollaboratorsTest can assert
     * OracleHarnessCollaborators::DECLARED_ABSENT against what is actually built. create() reads
     * every optional collaborator from here, so the guard cannot pass while the wiring differs.
     *
     * @param bool        $resolveExtensions false drops the IG type registry, which switches
     *                                       FHIRValidationService's extension pass off wholesale — a
     *                                       deliberate variant compare-java-outcomes.php uses to show what
     *                                       that pass contributes, not a configuration to measure against
     * @param FhirVersion $version           selects the core package the runtime profile resolver reads
     *
     * @return OracleHarnessCollaborators the slots as create() will fill them
     */
    public static function collaborators(bool $resolveExtensions = true, FhirVersion $version = FhirVersion::R4): OracleHarnessCollaborators
    {
        return new OracleHarnessCollaborators(
            referenceResolver: new NullFHIRReferenceResolver(),
            terminologyClient: null,
            igTypeRegistry: $resolveExtensions ? FHIRIGTypeRegistryFactory::create() : null,
            runtimeProfiles: new RuntimeProfileResolver(CoreStructureDefinitionPackages::registry($version)),
        );
    }
}
