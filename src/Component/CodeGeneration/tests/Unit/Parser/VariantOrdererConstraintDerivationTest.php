<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Parser;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContextInterface;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\VariantOrderer;
use PHPUnit\Framework\TestCase;

/**
 * A `derivation: constraint` type does get a PHP subclass, so it must outrank its base.
 *
 * `depthOf()` walked `baseDefinition` only while `derivation === 'specialization'`, on the stated
 * premise that "a `constraint` derivation is a profile of the same type — it adds no PHP subclass, so
 * counting it would invent depth". The shipped models contradict that:
 * `Models/src/R4/Profile/SimpleQuantityProfile.php` is `class SimpleQuantityProfile extends Quantity`,
 * generated from a constraint-derived StructureDefinition, and `MoneyQuantityProfile` likewise.
 *
 * The consequence is the exact bug `VariantOrderer` exists to prevent. Variants are matched by
 * `instanceof` in declaration order, so ranking `SimpleQuantity` at depth 0 and `Quantity` at depth 1
 * puts the *supertype* first — and a `SimpleQuantityProfile` value then matches `instanceof Quantity`
 * and serialises under `valueQuantity`. Silently, with structurally valid output.
 *
 * ## Why a synthetic context
 *
 * The committed `Fixtures/TypeIndex/*.json` contain zero constraint-derived entries (verified: 0 of
 * 209 in r4), so they cannot express this case. Hand-building the three definitions keeps the test
 * about the ordering rule rather than about fixture contents.
 *
 * ## This is a latent defect, not a live one
 *
 * No shipped operation parameter names a constraint-derived type: of the 40 distinct type codes named
 * by any operation parameter or variant across R4/R4B/R5, all 40 resolve against the packages and
 * none is constraint-derived. So the fix is expected to leave generated output byte-identical, and
 * these tests — not a regeneration — are what demonstrate the rule is now right.
 */
final class VariantOrdererConstraintDerivationTest extends TestCase
{
    private const string SD = 'http://hl7.org/fhir/StructureDefinition/';

    public function testAConstraintDerivedTypeOutranksItsBase(): void
    {
        $orderer = new VariantOrderer();
        $context = self::quantityHierarchy();

        self::assertSame(
            2,
            $orderer->depthOf('SimpleQuantity', $context),
            'SimpleQuantity constrains Quantity and the generator emits SimpleQuantityProfile extends '
            . 'Quantity, so it is one hop deeper — not depth 0.',
        );
        self::assertSame(1, $orderer->depthOf('Quantity', $context));
    }

    /**
     * The ordering consequence, stated as the property that actually matters.
     */
    public function testTheSubtypeIsDeclaredBeforeItsSupertype(): void
    {
        $ordered = (new VariantOrderer())->order(['Quantity', 'SimpleQuantity'], self::quantityHierarchy());

        self::assertSame(
            ['SimpleQuantity', 'Quantity'],
            $ordered,
            'The supertype declared first would steal the instanceof match from its own subclass.',
        );
    }

    /**
     * Input order must not change the answer — depth is the key, not the caller's argument order.
     */
    public function testOrderingIsIndependentOfInputOrder(): void
    {
        $context = self::quantityHierarchy();
        $orderer = new VariantOrderer();

        self::assertSame(
            $orderer->order(['SimpleQuantity', 'Quantity'], $context),
            $orderer->order(['Quantity', 'SimpleQuantity'], $context),
        );
    }

    /**
     * A specialization chain still counts exactly as it did — the fix must not double-count.
     *
     * `Age` is `derivation: specialization` on `Quantity` in both R4 and R5, so it was already
     * ranked correctly at depth 2. It shares that depth with the constraint-derived `SimpleQuantity`,
     * which is right: both are one PHP subclass below `Quantity`.
     */
    public function testASpecializationChainIsUnchanged(): void
    {
        $context = self::quantityHierarchy();
        $orderer = new VariantOrderer();

        self::assertSame(2, $orderer->depthOf('Age', $context));
        self::assertSame(['Age', 'Quantity'], $orderer->order(['Quantity', 'Age'], $context));
    }

    /**
     * An unknown type code still sorts last, which is the safe direction.
     */
    public function testAnUnknownTypeStillRanksZero(): void
    {
        self::assertSame(0, (new VariantOrderer())->depthOf('NotAType', self::quantityHierarchy()));
    }

    /**
     * `Element` -> `Quantity` (specialization) -> `SimpleQuantity` (constraint), plus `Age`.
     */
    private static function quantityHierarchy(): BuilderContextInterface
    {
        $context = new BuilderContext();
        $context->loadDefinitions([
            self::SD . 'Element' => [
                'resourceType'   => 'StructureDefinition',
                'url'            => self::SD . 'Element',
                'name'           => 'Element',
                'kind'           => 'complex-type',
                'abstract'       => true,
                'derivation'     => 'specialization',
                'baseDefinition' => null,
            ],
            self::SD . 'Quantity' => [
                'resourceType'   => 'StructureDefinition',
                'url'            => self::SD . 'Quantity',
                'name'           => 'Quantity',
                'kind'           => 'complex-type',
                'abstract'       => false,
                'derivation'     => 'specialization',
                'baseDefinition' => self::SD . 'Element',
            ],
            // The case the old guard dropped: constraint-derived, yet the model generator emits
            // `Profile\SimpleQuantityProfile extends Quantity` for it.
            self::SD . 'SimpleQuantity' => [
                'resourceType'   => 'StructureDefinition',
                'url'            => self::SD . 'SimpleQuantity',
                'name'           => 'SimpleQuantity',
                'kind'           => 'complex-type',
                'abstract'       => false,
                'derivation'     => 'constraint',
                'baseDefinition' => self::SD . 'Quantity',
            ],
            self::SD . 'Age' => [
                'resourceType'   => 'StructureDefinition',
                'url'            => self::SD . 'Age',
                'name'           => 'Age',
                'kind'           => 'complex-type',
                'abstract'       => false,
                'derivation'     => 'specialization',
                'baseDefinition' => self::SD . 'Quantity',
            ],
        ]);

        return $context;
    }
}
