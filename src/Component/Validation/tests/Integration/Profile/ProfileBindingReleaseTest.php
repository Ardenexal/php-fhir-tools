<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\Extension\EndpointFhirVersionExtension as R4EndpointFhirVersion;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive as R4Code;
use Ardenexal\FHIRTools\Component\Models\R5\Extension\EndpointFhirVersionExtension as R5EndpointFhirVersion;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive as R5Code;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileBindingValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * A profile binding is checked against the enum of the release its element belongs to.
 *
 * The bundle configures one binding validator for every release, searching R4 first. FHIR-version
 * keeps its URL in each release but only R5's enum has `5.0.0`, so an R5 element read against the R4
 * enum would reject a valid code.
 */
#[CoversClass(FHIRProfileBindingValidator::class)]
#[CoversClass(FHIRValueSetBindingValidator::class)]
final class ProfileBindingReleaseTest extends TestCase
{
    /** The bundle's enum roots, in the bundle's order */
    private const array ROOTS = [
        'Ardenexal\FHIRTools\Component\Models\R4\Enum',
        'Ardenexal\FHIRTools\Component\Models\R4B\Enum',
        'Ardenexal\FHIRTools\Component\Models\R5\Enum',
    ];

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $registry = new FHIRValidationMessageRegistry();

        $factory = new class ($accessor, $registry, self::ROOTS) implements ConstraintValidatorFactoryInterface {
            private readonly ConstraintValidatorFactory $fallback;

            /**
             * @param list<string> $roots
             */
            public function __construct(
                private readonly PropertyAccessorInterface $accessor,
                private readonly FHIRValidationMessageRegistry $registry,
                private readonly array $roots,
            ) {
                $this->fallback = new ConstraintValidatorFactory();
            }

            public function getInstance(Constraint $constraint): ConstraintValidatorInterface
            {
                return match (true) {
                    $constraint instanceof FHIRProfileBinding  => new FHIRProfileBindingValidator($this->accessor),
                    $constraint instanceof FHIRValueSetBinding => new FHIRValueSetBindingValidator($this->registry, $this->roots),
                    default                                    => $this->fallback->getInstance($constraint),
                };
            }
        };

        $this->validator = Validation::createValidatorBuilder()->setConstraintValidatorFactory($factory)->getValidator();
    }

    public function testAnR5CodeOnlyR5DefinesIsAccepted(): void
    {
        self::assertSame([], $this->findings(new R5EndpointFhirVersion(new R5Code(value: '5.0.0'))));
    }

    public function testACodeNoReleaseDefinesIsStillReported(): void
    {
        self::assertSame(
            ['value: The value unicorn is not a valid case of value set http://hl7.org/fhir/ValueSet/FHIR-version.'],
            $this->findings(new R5EndpointFhirVersion(new R5Code(value: 'unicorn'))),
        );
    }

    public function testAnR4ElementIsCheckedAgainstTheR4Enum(): void
    {
        self::assertSame(
            ['value: The value 5.0.0 is not a valid case of value set http://hl7.org/fhir/ValueSet/FHIR-version.'],
            $this->findings(new R4EndpointFhirVersion(new R4Code(value: '5.0.0'))),
        );
    }

    /**
     * The class's own profile binding, run on it, as path and message.
     *
     * @return list<string>
     */
    private function findings(object $extension): array
    {
        $bindings = array_map(
            static fn (\ReflectionAttribute $a): object => $a->newInstance(),
            (new \ReflectionClass($extension))->getAttributes(FHIRProfileBinding::class),
        );
        self::assertCount(1, $bindings);

        $found = [];
        foreach ($this->validator->validate($extension, $bindings) as $violation) {
            $found[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
        }

        return $found;
    }
}
