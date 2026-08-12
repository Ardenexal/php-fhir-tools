<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource as PatientResourceR4B;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\PatientResource as PatientResourceR5;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * `Resource.id` carries FHIR's `[A-Za-z0-9\-\.]{1,64}` rule, but its element definition states only
 * `http://hl7.org/fhirpath/System.String` with no `regex` extension, so nothing used to constrain it
 * and every resource id was accepted. The reference validator reports
 * `Invalid Resource id: Invalid Characters ('bad-id_1')` / `Too long (115 chars)` on these inputs.
 *
 * Constraining `IdPrimitive` does not fix it: the property is generated as a bare `?string`, so the
 * primitive wrapper class is never consulted.
 *
 * The counts below are the real assertion. Two traps sit either side of "the constraint exists":
 *
 *  - `base.path` is `Resource.id` on the abstract base *and* on every concrete resource, and Symfony
 *    merges a parent's property constraints into a child that redeclares the property. Emitting on
 *    both yields **two** violations where the reference validator reports one — a false positive that
 *    an already-BELOW corpus case would absorb invisibly.
 *  - `Element.id` (Narrative.id, Coding.id, …) has the identical `System.String` shape but carries no
 *    such rule, so a discriminator keyed on the type instead of the path would over-apply.
 */
final class GeneratedResourceIdConstraintTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        // `Regex` is a stock Symfony constraint served by the stock ConstraintValidatorFactory, which
        // is exactly how `services.yaml` serves it — it registers `validator.constraint_validator`
        // tags only for the project's own FHIR* constraints. So there is no DI divergence here.
        //
        // enableAttributeMapping() is mandatory: without it no PHP attribute is read at all and every
        // assertion below would pass vacuously.
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    /**
     * Regex violations on one property.
     *
     * `validateProperty()` rather than `validate()` deliberately: a whole PatientResource cascades
     * into value-set bindings, invariants and slices, none of which this change touches. It still
     * reads the same merged parent+child property metadata, so the double-emission trap below stays
     * detectable.
     */
    private function regexViolationsOn(object $subject, string $path): int
    {
        $count = 0;
        foreach ($this->validator->validateProperty($subject, $path) as $violation) {
            if ($violation->getConstraint() instanceof Regex) {
                ++$count;
            }
        }

        return $count;
    }

    #[DataProvider('resourceIds')]
    public function testResourceIdIsConstrained(string $id, int $expected): void
    {
        self::assertSame($expected, $this->regexViolationsOn(new PatientResource(id: $id), 'id'));
    }

    /** @return iterable<string, array{string, int}> */
    public static function resourceIds(): iterable
    {
        yield 'ordinary id'                      => ['example', 0];
        yield 'hyphens and dots are allowed'     => ['pat-1.2.3', 0];
        yield 'underscore is not allowed'        => ['bad-id_1', 1];
        yield 'space is not allowed'             => ['bad-id 1', 1];
        yield 'slash and equals are not allowed' => ['/foobar==', 1];
        yield '64 characters is the limit'       => [str_repeat('a', 64), 0];
        yield '115 characters is too long'       => [str_repeat('a', 115), 1];
    }

    /**
     * The double-emission guard. `str_repeat('a', 115)` is 115 *allowed* characters, so it can only
     * fail the length half of the rule — if this ever reports 2, the constraint has been emitted on
     * both the abstract base and the concrete class, or split into a separate Regex + Length pair.
     */
    public function testAnInvalidIdIsReportedExactlyOnce(): void
    {
        self::assertSame(1, $this->regexViolationsOn(new PatientResource(id: 'bad-id_1'), 'id'));
        self::assertSame(1, $this->regexViolationsOn(new PatientResource(id: str_repeat('a', 115)), 'id'));
    }

    /** An absent id is legal — a resource being submitted for creation has none yet. */
    public function testNullIdIsAllowed(): void
    {
        self::assertSame(0, $this->regexViolationsOn(new PatientResource(id: null), 'id'));
    }

    /**
     * `Element.id` is a different rule ("any string value that does not contain spaces") and is not
     * what the reference validator checks here, so it must stay unconstrained by this change.
     */
    public function testElementIdIsNotConstrained(): void
    {
        self::assertSame(0, $this->regexViolationsOn(new Narrative(id: 'bad-id_1'), 'id'));
    }

    /** Byte-identical rule across the three generated versions. */
    public function testConstraintAppliesInEveryVersion(): void
    {
        self::assertSame(1, $this->regexViolationsOn(new PatientResource(id: 'bad-id_1'), 'id'));
        self::assertSame(1, $this->regexViolationsOn(new PatientResourceR4B(id: 'bad-id_1'), 'id'));
        self::assertSame(1, $this->regexViolationsOn(new PatientResourceR5(id: 'bad-id_1'), 'id'));

        self::assertSame(0, $this->regexViolationsOn(new PatientResource(id: 'example'), 'id'));
        self::assertSame(0, $this->regexViolationsOn(new PatientResourceR4B(id: 'example'), 'id'));
        self::assertSame(0, $this->regexViolationsOn(new PatientResourceR5(id: 'example'), 'id'));
    }
}
