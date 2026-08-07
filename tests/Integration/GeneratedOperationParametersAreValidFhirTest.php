<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationParameterMapper;
use Ardenexal\FHIRTools\Component\Validation\FhirPropertyTypeHierarchyResolver;
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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;

/**
 * The `Parameters` resources the mapper emits are valid FHIR, not merely round-trip-symmetric.
 *
 * Round-trip identity cannot see this class of defect at all: output that is wrong in the same way in
 * both directions passes an identity check perfectly. Only an independent conformance judgement — the
 * validator, reading the invariants published on the model — can say the wire format is legal.
 *
 * ## This test walks the tree itself, because the validation service does not
 *
 * `FHIRValidationService::validate()` delegates to Symfony's validator, which descends into nested
 * objects **only** where a property carries `#[Assert\Valid]`. No generated model carries it, so every
 * invariant declared on a backbone element — including `Parameters.parameter`'s `inv-1`, the one that
 * governs everything this mapper emits — is never evaluated when a resource is validated as a whole.
 *
 * That was measured, not assumed (M03 note N8). The same invalid `ParametersParameter` reports **zero**
 * errors nested inside a `ParametersResource` and **one** error when passed as the validation root, and
 * the split reproduces through the production DI container as well as here. 117 / 123 / 179 invariant
 * declarations in R4 / R4B / R5 are dead for the same reason.
 *
 * So `validateEveryNode()` below re-roots each nested parameter and validates it directly. That is a
 * workaround, deliberately scoped to this test rather than fixed here: the real fix is either emitting
 * `#[Assert\Valid]` from `FHIRModelGenerator` or giving the service its own recursive walk, both of
 * which are `Validation` changes with blast radius far beyond operations. Backlogged under
 * "Nested-element invariants are never evaluated".
 *
 * **If that fix lands, this test should keep passing and its traversal can be deleted.**
 *
 * @see self::testTheTraversalReachesNestedParametersAndWouldFailOnAnInvalidOne — the guard that stops
 *      this whole file from silently becoming vacuous, which is exactly what it did on first write
 */
final class GeneratedOperationParametersAreValidFhirTest extends TestCase
{
    /**
     * @return \Generator<string, array{string}>
     */
    public static function versions(): \Generator
    {
        foreach (GeneratedOperationCorpus::VERSIONS as $version) {
            yield $version => [$version];
        }
    }

    /**
     * A richly-populated class-A output — nested two deep, with both a complex and a primitive
     * polymorphic variant — emits valid FHIR in every version.
     *
     * `$lookup` is chosen because it is the worst case the generator produces: `property` groups
     * carrying `subproperty` groups, plus a `value[x]` choice that exercises both arms of
     * `resolveChoiceVariant`. If the emitted `Parameters` is legal here it is legal for the flatter
     * shapes.
     */
    #[DataProvider('versions')]
    public function testPopulatedLookupOutputEmitsValidParameters(string $version): void
    {
        $parameters = $this->mapper($version)->toParameters($this->populatedLookupOutput($version));

        $this->assertEveryNodeValid($version, $parameters, 'CodeSystemLookupOutput');
    }

    /**
     * Typed Inputs across the output-shape classes emit valid FHIR.
     *
     * The request body of an operation is a `Parameters` regardless of how the *response* is shaped,
     * so an Input from a bare-resource operation is as much a conformance question as a class-A one.
     */
    #[DataProvider('versions')]
    public function testTypedInputsEmitValidParameters(string $version): void
    {
        foreach ($this->inputCases($version) as $label => $payload) {
            $this->assertEveryNodeValid($version, $this->mapper($version)->toParameters($payload), $label);
        }
    }

    /**
     * The guard: prove the traversal actually reaches nested parameters.
     *
     * Without this, every assertion above passes both when the emitted output is valid **and** when
     * the validator never looked at it — and the second is what actually happened on first write. This
     * is M02 note N28's weakest proof shape, caught in the act. `inv-1` ("a parameter must have one and
     * only one of value, resource, part") is violated by a parameter carrying none of the three, which
     * is unreachable through the mapper and so has to be constructed directly.
     */
    #[DataProvider('versions')]
    public function testTheTraversalReachesNestedParametersAndWouldFailOnAnInvalidOne(string $version): void
    {
        $parametersClass = $this->mapper($version)->parametersResourceClass();

        // The backbone class lives under the resource's own namespace, e.g.
        // Models\R4\Resource\Parameters\ParametersParameter.
        $backbone = sprintf(
            'Ardenexal\FHIRTools\Component\Models\%s\Resource\Parameters\ParametersParameter',
            $version,
        );
        self::assertTrue(class_exists($backbone), sprintf('%s does not exist.', $backbone));

        $invalid = new $backbone(name: 'no-value-no-resource-no-part');
        $subject = new $parametersClass(parameter: [$invalid]);

        $violations = $this->collectErrors($version, $subject);

        self::assertNotSame([], $violations, sprintf(
            'The self-traversal did not reach the nested parameter in %s. Every other assertion in this '
            . 'file is therefore vacuous — they would pass on invalid output too.',
            $version,
        ));

        $keys = array_map(static fn (object $v): ?string => $v->invariantKey ?? null, $violations);
        self::assertContains('inv-1', $keys, sprintf(
            'Reached the nested parameter in %s but did not report inv-1; got: %s',
            $version,
            implode(', ', array_map(static fn (?string $k): string => $k ?? '(none)', $keys)),
        ));
    }

    /**
     * Assert an emitted `Parameters` and every node beneath it is error-free.
     */
    private function assertEveryNodeValid(string $version, object $parameters, string $label): void
    {
        $errors = $this->collectErrors($version, $parameters);

        $rendered = array_map(
            static fn (object $v): string => sprintf(
                '%s: %s',
                $v->invariantKey ?? $v->code ?? 'error',
                $v->message      ?? '(no message)',
            ),
            $errors,
        );

        self::assertSame([], $rendered, sprintf(
            '%s emitted a Parameters that is not valid FHIR in %s:%s%s',
            $label,
            $version,
            \PHP_EOL,
            implode(\PHP_EOL, $rendered),
        ));
    }

    /**
     * Validate a resource and, separately, every `parameter` node beneath it at any depth.
     *
     * @return list<object> violations at error severity
     */
    private function collectErrors(string $version, object $resource): array
    {
        $service = $this->service($version);
        $errors  = $service->validate($resource)->errors();

        foreach ($this->parameterNodes($resource) as $node) {
            foreach ($service->validate($node)->errors() as $violation) {
                $errors[] = $violation;
            }
        }

        return array_values($errors);
    }

    /**
     * Every `Parameters.parameter` node beneath a resource, at any depth, including `part[]`.
     *
     * @return list<object>
     */
    private function parameterNodes(object $resource): array
    {
        $nodes = [];

        $walk = static function(object $node) use (&$walk, &$nodes): void {
            foreach (['parameter', 'part'] as $property) {
                if (!property_exists($node, $property)) {
                    continue;
                }

                $children = $node->{$property};

                if (!is_array($children)) {
                    continue;
                }

                foreach ($children as $child) {
                    if (!is_object($child)) {
                        continue;
                    }

                    $nodes[] = $child;
                    $walk($child);
                }
            }
        };

        $walk($resource);

        return $nodes;
    }

    private function mapper(string $version): OperationParameterMapper
    {
        return OperationParameterMapper::createDefault(FhirVersion::from($version));
    }

    /**
     * Representative typed Inputs, one per output-shape class where the shape has a constructible Input.
     *
     * @return array<string, object>
     */
    private function inputCases(string $version): array
    {
        $namespace = sprintf('Ardenexal\FHIRTools\Component\Models\%s\Operation', $version);

        $cases = [];

        $lookupInput                              = $namespace . '\CodeSystemLookup\CodeSystemLookupInput';
        $cases['CodeSystemLookupInput (class A)'] = new $lookupInput(code: 'AB', system: 'http://loinc.org');

        // Class B — the response is a bare resource, but the *request* is still a Parameters.
        $expandInput                            = $namespace . '\ValueSetExpand\ValueSetExpandInput';
        $cases['ValueSetExpandInput (class B)'] = new $expandInput(url: 'http://example.org/ValueSet/vs');

        return $cases;
    }

    /**
     * Mirrors `OperationParameterMapperTest::populatedOutput()` — a complex variant at the outer level
     * and a primitive one nested, so both arms of `resolveChoiceVariant` are exercised.
     */
    private function populatedLookupOutput(string $version): object
    {
        $codePrimitive = sprintf('Ardenexal\FHIRTools\Component\Models\%s\Primitive\CodePrimitive', $version);
        $coding        = sprintf('Ardenexal\FHIRTools\Component\Models\%s\DataType\Coding', $version);
        $base          = sprintf('Ardenexal\FHIRTools\Component\Models\%s\Operation\CodeSystemLookup', $version);

        $outputClass      = $base . '\CodeSystemLookupOutput';
        $propertyClass    = $base . '\CodeSystemLookupOutProperty';
        $subpropertyClass = $base . '\CodeSystemLookupOutPropertySubproperty';

        return new $outputClass(
            name: 'ACME Codes',
            version: '2026-01',
            display: 'Left displacement',
            property: [
                new $propertyClass(
                    code: 'parent',
                    value: new $coding(display: 'Parent of'),
                    description: 'Parent concept',
                    subproperty: [
                        new $subpropertyClass(
                            code: 'inherited',
                            value: new $codePrimitive(value: 'inherited-from'),
                            description: 'Inherited from parent',
                        ),
                    ],
                ),
            ],
        );
    }

    /**
     * Build a validation service wired with the FHIR constraint validators.
     *
     * Duplicates `FHIRValidatorSpecificationTest::createValidationService()` because that helper is
     * private to a component test and this check spans three components. Kept in step with it
     * deliberately: a constraint missing here reports as a "class not found" fatal from
     * `ConstraintValidatorFactory`, not as a silent pass.
     */
    private function service(string $version): FHIRValidationService
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $registry = new FHIRValidationMessageRegistry();
        $pathSvc  = new FHIRPathService();
        $matcher  = new SliceDiscriminatorMatcher($accessor);
        $resolver = new NullFHIRReferenceResolver();
        $default  = new ConstraintValidatorFactory();
        $enumNs   = sprintf('Ardenexal\FHIRTools\Component\Models\%s\Enum', $version);

        $factory = new class ($accessor, $registry, $pathSvc, $matcher, $resolver, $default, $enumNs) implements ConstraintValidatorFactoryInterface {
            public function __construct(
                private readonly PropertyAccessorInterface $accessor,
                private readonly FHIRValidationMessageRegistry $registry,
                private readonly FHIRPathService $pathSvc,
                private readonly SliceDiscriminatorMatcher $matcher,
                private readonly NullFHIRReferenceResolver $resolver,
                private readonly ConstraintValidatorFactory $default,
                private readonly string $enumNs,
            ) {
            }

            public function getInstance(Constraint $constraint): ConstraintValidatorInterface
            {
                return match (true) {
                    $constraint instanceof FHIRProfileConstraint => new FHIRProfileConstraintValidator($this->accessor),
                    $constraint instanceof FHIRPathInvariant     => new FHIRPathInvariantValidator($this->pathSvc, $this->registry),
                    $constraint instanceof FHIRValueSetBinding   => new FHIRValueSetBindingValidator($this->registry, [$this->enumNs]),
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

        return new FHIRValidationService($validator, $pathSvc, typeResolver: new FhirPropertyTypeHierarchyResolver());
    }
}
