<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * A required binding must resolve to its own version's enum, and never to a colliding one.
 *
 * These are wired the way `services.yaml` wires production — **all three** enum namespace roots at
 * once — because that is the only configuration in which the cross-version defect appears. The
 * comparison harness builds one validator per version with a single root, so it is structurally
 * incapable of catching it; that gap is the reason this test exists as a unit test rather than a
 * corpus case.
 */
final class ValueSetBindingEnumResolutionTest extends TestCase
{
    /** Mirrors src/Bundle/FHIRBundle/src/Resources/config/services.yaml. Order matters. */
    private const PRODUCTION_ROOTS = [
        'Ardenexal\FHIRTools\Component\Models\R4\Enum',
        'Ardenexal\FHIRTools\Component\Models\R4B\Enum',
        'Ardenexal\FHIRTools\Component\Models\R5\Enum',
    ];

    /**
     * @return class-string<\BackedEnum>|null
     */
    private function resolve(FHIRValueSetBinding $binding): ?string
    {
        $validator = new FHIRValueSetBindingValidator(
            new FHIRValidationMessageRegistry(),
            self::PRODUCTION_ROOTS,
        );

        $method = (new \ReflectionClass(FHIRValueSetBindingValidator::class))->getMethod('resolveBoundEnum');
        $method->setAccessible(true);

        /** @var class-string<\BackedEnum>|null $resolved */
        $resolved = $method->invoke($validator, $binding);

        return $resolved;
    }

    /**
     * `item-type` exists in both R4 and R5 with genuinely different codes: R5 added `coding`, R4 has
     * `choice` and `open-choice`. Probing namespace roots in order always returned R4's enum, so an
     * R5 resource had `coding` rejected and `choice` accepted — both wrong.
     */
    public function testR5BindingResolvesToTheR5Enum(): void
    {
        $resolved = $this->resolve(new FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/item-type|5.0.0',
            strength: 'required',
            enumClass: 'Ardenexal\FHIRTools\Component\Models\R5\Enum\QuestionnaireItemType',
        ));

        self::assertNotNull($resolved);
        self::assertStringContainsString('\\R5\\', $resolved);
        self::assertNotNull($resolved::tryFrom('coding'), 'coding is legal in R5');
        self::assertNull($resolved::tryFrom('choice'), 'choice was removed in R5');
    }

    public function testR4BindingResolvesToTheR4Enum(): void
    {
        $resolved = $this->resolve(new FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/item-type|4.0.1',
            strength: 'required',
            enumClass: 'Ardenexal\FHIRTools\Component\Models\R4\Enum\QuestionnaireItemType',
        ));

        self::assertNotNull($resolved);
        self::assertStringContainsString('\\R4\\', $resolved);
        self::assertNotNull($resolved::tryFrom('choice'), 'choice is legal in R4');
        self::assertNull($resolved::tryFrom('coding'), 'coding did not exist in R4');
    }

    /**
     * ClassNameResolver maps `.../ValueSet/medication-statement-status` and
     * `.../ValueSet/medication-status` to the same class name. The enum that exists is the latter's,
     * holding only active|inactive|entered-in-error. Binding on the name alone rejected the legal
     * MedicationStatement.status code `unknown`, so a mismatched source URL must be declined.
     */
    public function testCollidingEnumIsDeclinedRatherThanUsed(): void
    {
        $resolved = $this->resolve(new FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/medication-statement-status|4.0.1',
            strength: 'required',
            enumClass: 'Ardenexal\FHIRTools\Component\Models\R4\Enum\MedicationStatusCodes',
        ));

        self::assertNull(
            $resolved,
            'MedicationStatusCodes declares medication-status, not medication-statement-status, '
            . 'so it must not be used to decide membership.',
        );
    }

    /**
     * An enum whose declared source matches is used, which is the whole point of the attribute.
     */
    public function testMatchingSourceIsAccepted(): void
    {
        $resolved = $this->resolve(new FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/publication-status|4.0.1',
            strength: 'required',
            enumClass: 'Ardenexal\FHIRTools\Component\Models\R4\Enum\PublicationStatus',
        ));

        self::assertNotNull($resolved);
        self::assertNotNull($resolved::tryFrom('draft'));
    }
}
