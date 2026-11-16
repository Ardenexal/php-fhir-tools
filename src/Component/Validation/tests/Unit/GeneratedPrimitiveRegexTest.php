<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\BooleanPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DecimalPrimitive as R5DecimalPrimitive;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\PrimitiveFormatChecker;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * The `regex` extension on a primitive StructureDefinition carries an *undelimited* pattern
 * (`true|false`, `(\s*([0-9a-zA-Z\+/=]){4}\s*)+`). The generator used to pass those straight to
 * `Symfony\...\Regex`, which inverts the constraint rather than merely relaxing it: `preg_match()`
 * raises ("Delimiter must not be alphanumeric…", "Unknown modifier '+'") and returns `false`, and
 * `RegexValidator` reads `false` as "did not match" — so the constraint rejected *every* value,
 * the valid ones included.
 *
 * The bug is latent today only because nothing cascades `#[Assert\Valid]` into primitive wrappers,
 * so these classes are never reached from a resource root. That makes this test the only thing
 * standing between the corpus and a detonation the day the cascade is switched on.
 *
 * Wiring note: `Regex` is a stock Symfony constraint served by the stock `ConstraintValidatorFactory`
 * in `services.yaml` exactly as it is here, so there is no DI divergence for this test to reproduce.
 * The one delegation below exists because every primitive extends `Element`, which carries the
 * class-level `ele-1` `#[FHIRPathInvariant]`; without it the validator cannot even be constructed.
 */
final class GeneratedPrimitiveRegexTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $default   = new ConstraintValidatorFactory();
        $invariant = new FHIRPathInvariantValidator(new FHIRPathService(), new FHIRValidationMessageRegistry());

        $factory = new class ($default, $invariant) implements ConstraintValidatorFactoryInterface {
            public function __construct(
                private readonly ConstraintValidatorFactory $default,
                private readonly FHIRPathInvariantValidator $invariant,
            ) {
            }

            public function getInstance(Constraint $constraint): ConstraintValidatorInterface
            {
                return $constraint instanceof FHIRPathInvariant
                    ? $this->invariant
                    : $this->default->getInstance($constraint);
            }
        };

        // enableAttributeMapping() is mandatory: without it no PHP attribute is read at all and
        // every assertion below would pass vacuously.
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory($factory)
            ->getValidator();
    }

    /** Violations raised by the generated `Regex` pattern, isolated from `ele-1` and friends. */
    private function regexViolations(object $primitive): int
    {
        $count = 0;
        foreach ($this->validator->validate($primitive) as $violation) {
            if ($violation->getConstraint() instanceof Regex) {
                ++$count;
            }
        }

        return $count;
    }

    /** The reproduction that showed the bug: valid values were rejected outright. */
    public function testValidBase64BinaryValuePasses(): void
    {
        self::assertSame(0, $this->regexViolations(new Base64BinaryPrimitive(value: 'aGVsbG8=')));
    }

    public function testInvalidBase64BinaryValueFails(): void
    {
        // Four characters, so the length rule is satisfied, but '!' is outside the allowed set.
        self::assertSame(1, $this->regexViolations(new Base64BinaryPrimitive(value: '!!!!')));
    }

    /**
     * Anchoring: a value that merely *contains* a fully valid value must still be rejected. Without
     * `\A`/`\z` the delimited pattern would match the embedded substring and accept the whole value.
     */
    public function testBase64BinaryValueContainingAValidSubstringFails(): void
    {
        self::assertSame(1, $this->regexViolations(new Base64BinaryPrimitive(value: 'aGVsbG8=!!!')));
    }

    /**
     * `\z`, not `$`: PCRE's `$` also matches immediately before a trailing newline, so `^…$`
     * anchoring would silently accept "abc\n" for id, code, oid and every other line-free primitive.
     */
    public function testTrailingNewlineIsRejected(): void
    {
        self::assertSame(0, $this->regexViolations(new IdPrimitive(value: 'abc')));
        self::assertSame(1, $this->regexViolations(new IdPrimitive(value: "abc\n")));
    }

    #[DataProvider('idValues')]
    public function testIdPatternMatchesTheFhirRule(string $value, int $expected): void
    {
        self::assertSame($expected, $this->regexViolations(new IdPrimitive(value: $value)));
    }

    /** @return iterable<string, array{string, int}> */
    public static function idValues(): iterable
    {
        yield 'letters, digits, hyphen, dot' => ['Patient-1.0', 0];
        yield 'underscore is not allowed'    => ['bad-id_1', 1];
        yield 'space is not allowed'         => ['bad-id 1', 1];
        yield '64 characters is the limit'   => [str_repeat('a', 64), 0];
        yield '65 characters is too long'    => [str_repeat('a', 65), 1];
    }

    /** A null primitive value carries no lexical form, so the pattern must not fire. */
    public function testNullValueIsNotConstrained(): void
    {
        self::assertSame(0, $this->regexViolations(new Base64BinaryPrimitive(value: null)));
        self::assertSame(0, $this->regexViolations(new IdPrimitive(value: null)));
    }

    /**
     * `boolean`'s value is typed `?bool`, so a "contains a valid substring" value cannot be built as
     * an object — the pattern itself is asserted instead, read back off the generated attribute.
     * Its body is a top-level alternation, and without the non-capturing group `\Atrue|false\z`
     * would accept anything *starting* with "true" or *ending* with "false".
     */
    public function testTopLevelAlternationIsGroupedInsideTheAnchors(): void
    {
        $pattern = self::emittedPattern(BooleanPrimitive::class, 'value');

        self::assertSame('~\A(?:true|false)\z~', $pattern);
        self::assertSame(1, preg_match($pattern, 'true'));
        self::assertSame(1, preg_match($pattern, 'false'));
        self::assertSame(0, preg_match($pattern, 'xtruex'));
        self::assertSame(0, preg_match($pattern, 'truely'));
        self::assertSame(0, preg_match($pattern, 'notfalse'));
    }

    /**
     * R5's published `decimal` regex is defective, and a compilable pattern is not a correct one.
     *
     * `hl7.fhir.r5.core#5.0.0` ships `([eE][+-]?[0-9]{1,9}})?` — a stray closing brace, which PCRE
     * reads as a literal `}` rather than a typo. It compiles, so
     * {@see testEveryEmittedPrimitivePatternCompiles} cannot see it; it simply rejects every legal
     * exponent and accepts a malformed one. The generator corrects it against the HL7 Java reference
     * validator's own pattern — see `FHIRModelGenerator::REGEX_CORRECTIONS`.
     *
     * The values below are the exact set measured against the broken pattern.
     *
     * @return iterable<string, array{string, int}>
     */
    public static function r5DecimalValues(): iterable
    {
        // Rejected by the upstream pattern's stray brace; all four are legal FHIR decimals.
        yield 'bare exponent'              => ['1e1', 1];
        yield 'negative exponent'          => ['1.0e-1', 1];
        yield 'two-digit exponent'         => ['0.1e11', 1];
        yield 'fraction with exponent'     => ['0.12e3', 1];
        yield 'plain decimal'              => ['1.5', 1];
        yield 'zero'                       => ['0', 1];

        // Accepted by the upstream pattern, because the stray brace is a literal.
        yield 'trailing brace is not legal' => ['1e1}', 0];

        // Java flags this on R5.primitive-good, so the correction follows the reference validator
        // rather than upstream-minus-the-brace, which would have accepted it.
        yield 'leading zero in exponent'    => ['1e09', 0];

        // The digit ceilings are upstream's and are preserved by the correction.
        yield 'fraction over 17 digits'     => ['0.000000000000000000000001', 0];
    }

    #[DataProvider('r5DecimalValues')]
    public function testR5DecimalPatternMatchesTheReferenceValidator(string $value, int $expectedMatch): void
    {
        $pattern = self::emittedPattern(R5DecimalPrimitive::class, 'value');

        self::assertSame(
            $expectedMatch,
            preg_match($pattern, $value),
            sprintf('R5 decimal pattern disagrees on %s', var_export($value, true)),
        );
    }

    /**
     * The generated constraint and `PrimitiveFormatChecker` must not drift apart.
     *
     * The checker walks the tree itself and quotes its pattern in the violation message for parity
     * with Java's wording; the generated `Regex` is what fires once anything cascades `Assert\Valid`
     * into a primitive. Two decimal rules that disagree would mean a value accepted by one layer and
     * rejected by the other, which is precisely the split the upstream typo already caused.
     */
    public function testGeneratedDecimalPatternAgreesWithPrimitiveFormatChecker(): void
    {
        $emitted = self::emittedPattern(R5DecimalPrimitive::class, 'value');

        self::assertSame('~\A(?:' . PrimitiveFormatChecker::DECIMAL_SOURCE . ')\z~', $emitted);
    }

    /** Every emitted pattern must be a compilable PCRE — an uncompilable one silently rejects all. */
    public function testEveryEmittedPrimitivePatternCompiles(): void
    {
        $classes = [Base64BinaryPrimitive::class, BooleanPrimitive::class, IdPrimitive::class];

        foreach ($classes as $class) {
            $pattern = self::emittedPattern($class, 'value');
            self::assertNotSame(false, @preg_match($pattern, 'probe'), $class . ' emitted an uncompilable pattern');
            self::assertStringStartsWith('~\A(?:', $pattern, $class . ' emitted an unanchored pattern');
            self::assertStringEndsWith(')\z~', $pattern, $class . ' emitted an unanchored pattern');
        }
    }

    /** @param class-string $class */
    private static function emittedPattern(string $class, string $property): string
    {
        foreach ((new \ReflectionProperty($class, $property))->getAttributes(Regex::class) as $attribute) {
            $pattern = $attribute->newInstance()->pattern;
            self::assertIsString($pattern);

            return $pattern;
        }

        self::fail(sprintf('%s::$%s carries no Regex attribute', $class, $property));
    }
}
