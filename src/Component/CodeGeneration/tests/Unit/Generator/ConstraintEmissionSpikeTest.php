<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\CodeGeneration\tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;

/**
 * Stub attribute for spike test: custom attribute round-trip via Nette → eval → reflection.
 * Intentionally in the CodeGeneration test namespace, not the future Validation component namespace.
 * The real FHIRValueSetBinding will live in Ardenexal\FHIRTools\Component\Validation\Constraints\.
 *
 * M02: add TARGET_PROPERTY assertions here for FHIRFixedValue, FHIRPatternValue, FHIRProfileConstraint.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
final class FHIRValueSetBindingStub
{
    public function __construct(
        public readonly string $valueSetUrl,
    ) {
    }
}

/**
 * === M01 Spike: Symfony Validator + Nette PhpGenerator integration ===
 *
 * Kill criterion: NOT FIRED — Symfony Validator reads PHP attribute constraints
 * from Nette-generated promoted constructor parameters correctly at PHP 8.3.
 *
 * Confirmed targets (2026-05-20):
 * 1. symfony/validator is already in src/Component/CodeGeneration/composer.json
 * 2. Promoted parameter attributes are visible via ReflectionProperty (the path Symfony's AttributeLoader reads)
 * 3. #[Count] and #[Regex] survive the addAttribute()→Nette→eval→ReflectionProperty round-trip
 * 4. Symfony Validator (enableAttributeMapping()) reports violations on eval'd class instances
 * 5. FHIRPathService: ExpressionCacheInterface is optional — new FHIRPathService() works without DI
 * 6. Custom attribute stubs carry TARGET_PROPERTY flag and survive Nette→eval→reflect round-trip
 *
 * PHP 8.3 note: promoted constructor parameter attributes appear on both ReflectionParameter
 * and ReflectionProperty. Symfony's AttributeLoader reads via ReflectionProperty — that is
 * the tested path in testSymfonyValidatorReadsConstraintsFromNetteGeneratedPromotedParams.
 */
class ConstraintEmissionSpikeTest extends TestCase
{
    private const string SPIKE_DATATYPE_NS = 'Ardenexal\\FHIRTools\\SpikeTest\\DataType';

    private const string SPIKE_STUB_NS = 'Ardenexal\\FHIRTools\\SpikeTest\\StubCarrier';

    /**
     * Proves:
     * - Generator auto-emits #[NotBlank] on required non-array promoted param (name: min 1, max 1)
     * - #[Count(min:1, max:3)] added manually via addAttribute() is visible via ReflectionProperty
     * - #[Regex] added manually via addAttribute() is visible via ReflectionProperty
     * - Symfony Validator (enableAttributeMapping) reports correct constraint violations
     *
     * All assertions are in one method to avoid eval() class redeclaration collisions
     * when PHPUnit runs all test methods in a single process.
     */
    public function testSymfonyValidatorReadsConstraintsFromNetteGeneratedPromotedParams(): void
    {
        $context = new BuilderContext();
        $context->addElementNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource'));
        $context->addDatatypeNamespace('R4', new PhpNamespace(self::SPIKE_DATATYPE_NS));
        $context->addPrimitiveNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Primitive'));
        $context->addEnumNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum'));

        $generator = new FHIRModelGenerator();
        $class     = $generator->generateModelClass($this->buildMinimalComplexTypeSD(), 'R4', $context);

        // Manually emit Count and Regex via addAttribute() — this is the M02 approach being spiked
        $constructor = $class->getMethod('__construct');
        $constructor->getParameter('tag')->addAttribute(Count::class, ['min' => 1, 'max' => 3]);
        $constructor->getParameter('name')->addAttribute(Regex::class, ['pattern' => '/^[A-Z]+$/']);

        $fqcn = self::SPIKE_DATATYPE_NS . '\\' . $class->getName();
        $this->evalClass($class, self::SPIKE_DATATYPE_NS);

        // --- Assertion 1: NotBlank visible via ReflectionProperty ---
        $ref = new \ReflectionClass($fqcn);
        self::assertNotEmpty(
            $ref->getProperty('name')->getAttributes(NotBlank::class),
            'NotBlank must appear on ReflectionProperty — Symfony AttributeLoader reads this path, not ReflectionParameter',
        );

        // --- Assertion 2: Regex visible via ReflectionProperty ---
        self::assertNotEmpty(
            $ref->getProperty('name')->getAttributes(Regex::class),
            'Regex must survive addAttribute()→Nette→eval→ReflectionProperty round-trip',
        );

        // --- Assertion 3: Count visible via ReflectionProperty on array param ---
        self::assertNotEmpty(
            $ref->getProperty('tag')->getAttributes(Count::class),
            'Count must survive addAttribute()→Nette→eval→ReflectionProperty round-trip on promoted array param',
        );

        // --- Assertion 4: Validator reports NotBlank violation for null required field ---
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();

        $notBlankViolations = array_filter(
            iterator_to_array($validator->validate(new $fqcn(name: null, tag: ['VALID']))),
            static fn ($v) => $v->getConstraint() instanceof NotBlank,
        );
        self::assertNotEmpty($notBlankViolations, 'Symfony Validator must report NotBlank violation for null required field');

        // --- Assertion 5: Validator reports Regex violation for non-matching string ---
        $regexViolations = array_filter(
            iterator_to_array($validator->validate(new $fqcn(name: 'lowercase', tag: ['X']))),
            static fn ($v) => $v->getConstraint() instanceof Regex,
        );
        self::assertNotEmpty($regexViolations, 'Symfony Validator must report Regex violation for non-matching value');

        // --- Assertion 6: Validator reports Count violation for oversized array ---
        $countViolations = array_filter(
            iterator_to_array($validator->validate(new $fqcn(name: 'VALID', tag: ['A', 'B', 'C', 'D']))),
            static fn ($v) => $v->getConstraint() instanceof Count,
        );
        self::assertNotEmpty($countViolations, 'Symfony Validator must report Count violation for array exceeding max:3');
    }

    /**
     * FHIRPathService instantiation path documented for M02:
     * ExpressionCacheInterface is optional (default null) — new FHIRPathService() works without DI.
     * Conclusion: the invariant validator in M02 can construct FHIRPathService directly; no lazy service needed.
     */
    public function testFhirPathServiceCanBeConstructedWithoutDiContainer(): void
    {
        $service = new FHIRPathService();
        self::assertInstanceOf(FHIRPathService::class, $service);

        $result = $service->evaluate('1 + 1', new \stdClass());
        self::assertNotNull($result);
    }

    /**
     * TARGET_PROPERTY flag check + custom attribute round-trip via Nette → eval → ReflectionProperty.
     * M02: add assertions for FHIRFixedValue, FHIRPatternValue, FHIRProfileConstraint here.
     */
    public function testCustomAttributeRoundTripAndTargetPropertyFlag(): void
    {
        // TARGET_PROPERTY flag must be non-zero — a missing flag silently produces non-validating models
        $attrRef   = new \ReflectionClass(FHIRValueSetBindingStub::class);
        $attrMetas = $attrRef->getAttributes(\Attribute::class);
        self::assertNotEmpty($attrMetas, 'FHIRValueSetBindingStub must carry #[Attribute]');

        $flags = $attrMetas[0]->newInstance()->flags;
        self::assertNotEquals(
            0,
            $flags & \Attribute::TARGET_PROPERTY,
            'TARGET_PROPERTY flag must be non-zero — missing flag silently produces non-validating models',
        );

        // Custom attribute round-trip: build a minimal class, emit the stub, eval, reflect
        $class       = new ClassType('StubCarrier');
        $constructor = $class->addMethod('__construct');
        $param       = $constructor->addPromotedParameter('value', null);
        $param->setNullable(true)->setType('string');
        $param->addAttribute(FHIRValueSetBindingStub::class, ['valueSetUrl' => 'http://example.org/vs']);

        $fqcn = self::SPIKE_STUB_NS . '\\StubCarrier';
        $this->evalClass($class, self::SPIKE_STUB_NS);

        $ref       = new \ReflectionClass($fqcn);
        $stubAttrs = $ref->getProperty('value')->getAttributes(FHIRValueSetBindingStub::class);
        self::assertNotEmpty($stubAttrs, 'Custom attribute stub must survive Nette→eval→ReflectionProperty round-trip');
        self::assertSame(
            'http://example.org/vs',
            $stubAttrs[0]->newInstance()->valueSetUrl,
            'Custom attribute constructor arguments must be preserved through the round-trip',
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function buildMinimalComplexTypeSD(): array
    {
        return [
            'resourceType' => 'StructureDefinition',
            'url'          => 'http://example.org/StructureDefinition/SpikeType',
            'name'         => 'SpikeType',
            'kind'         => 'complex-type',
            'abstract'     => false,
            'snapshot'     => [
                'element' => [
                    [
                        'path' => 'SpikeType',
                        'min'  => 0,
                        'max'  => '1',
                        'base' => ['path' => 'SpikeType'],
                    ],
                    [
                        'path'  => 'SpikeType.name',
                        'min'   => 1,
                        'max'   => '1',
                        'type'  => [['code' => 'http://hl7.org/fhirpath/System.String']],
                        'base'  => ['path' => 'SpikeType.name'],
                        'short' => 'Required name',
                    ],
                    [
                        'path'  => 'SpikeType.tag',
                        'min'   => 1,
                        'max'   => '3',
                        'type'  => [['code' => 'http://hl7.org/fhirpath/System.String']],
                        'base'  => ['path' => 'SpikeType.tag'],
                        'short' => 'Tags (1-3)',
                    ],
                ],
            ],
        ];
    }

    private function evalClass(ClassType $class, string $namespace): void
    {
        $printer = new Printer();
        $code    = "<?php declare(strict_types=1);\n\nnamespace {$namespace};\n\n"
            . $printer->printClass($class, new PhpNamespace($namespace));
        eval(substr($code, 5));
    }
}
