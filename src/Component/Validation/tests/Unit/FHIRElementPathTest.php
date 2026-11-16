<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\FHIRElementPath;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Covers the translation from a StructureDefinition element path to one PropertyAccessor can follow,
 * which is what lets a choice element's cardinality rule be evaluated at all.
 */
final class FHIRElementPathTest extends TestCase
{
    public function testChoiceMarkerIsRemoved(): void
    {
        self::assertSame('effective', FHIRElementPath::toPropertyPath('effective[x]'));
    }

    public function testChoiceMarkerIsRemovedFromANestedPath(): void
    {
        self::assertSame('component.value', FHIRElementPath::toPropertyPath('component.value[x]'));
    }

    public function testAPathWithoutAChoiceMarkerIsUnchanged(): void
    {
        self::assertSame('category.coding.code', FHIRElementPath::toPropertyPath('category.coding.code'));
    }

    /** Array indexes are syntax PropertyAccessor already understands, so they must survive. */
    public function testArrayIndexesAreLeftAlone(): void
    {
        self::assertSame('coding[0].code', FHIRElementPath::toPropertyPath('coding[0].code'));
        self::assertSame('component[1].value', FHIRElementPath::toPropertyPath('component[1].value[x]'));
    }

    /**
     * The point of the translation: a choice element is generated as one polymorphic property, so the
     * unconverted path names something that does not exist and the accessor refuses it.
     */
    public function testTheConvertedPathIsReadableWhereTheOriginalIsNot(): void
    {
        $observation            = new \stdClass();
        $observation->effective = '2026-01-01';

        $accessor = PropertyAccess::createPropertyAccessor();

        self::assertFalse($accessor->isReadable($observation, 'effective[x]'));
        self::assertTrue($accessor->isReadable($observation, FHIRElementPath::toPropertyPath('effective[x]')));
    }
}
