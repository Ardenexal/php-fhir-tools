<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Stub carrying a transparent xml-choice-group property: an ordered list<ChoiceGroupItem> whose
 * variants are keyed by child element name (jsonKey), with isChoice intentionally FALSE.
 */
class ChoiceGroupStub
{
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://hl7.org/fhir/StructureDefinition/Base',
            propertyKind: 'choiceGroup',
            isArray: true,
            phpType: ChoiceGroupItem::class,
            variants: [
                ['fhirType' => 'ADXP', 'propertyKind' => 'complex', 'phpType' => 'Ardenexal\\FHIRTools\\Component\\CdaModels\\DataType\\ADXP', 'jsonKey' => 'streetAddressLine'],
                ['fhirType' => 'ADXP', 'propertyKind' => 'complex', 'phpType' => 'Ardenexal\\FHIRTools\\Component\\CdaModels\\DataType\\ADXP', 'jsonKey' => 'city'],
                ['fhirType' => 'string', 'propertyKind' => 'scalar', 'phpType' => 'string', 'jsonKey' => 'xmlText'],
            ],
        )]
        public array $item = [],
    ) {
    }
}

/**
 * Stub carrying a polymorphic value[x]-style choice (isChoice true) — proves the existing choice
 * variant-building path is unchanged by relaxing the gate for choiceGroup.
 */
class ValueChoiceStub
{
    public function __construct(
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'deceasedBoolean'],
                ['fhirType' => 'dateTime', 'propertyKind' => 'complex', 'phpType' => 'Ardenexal\\FHIRTools\\Component\\Models\\R5\\DataType\\DateTimePrimitive', 'jsonKey' => 'deceasedDateTime'],
            ],
        )]
        public mixed $deceased = null,
    ) {
    }
}

final class PropertyMetadataProviderChoiceGroupTest extends TestCase
{
    private PropertyMetadataProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = new PropertyMetadataProvider();
    }

    public function testChoiceGroupPropertyPopulatesVariantsEvenThoughIsChoiceIsFalse(): void
    {
        $meta = $this->provider->getPropertyMetadata(ChoiceGroupStub::class);

        self::assertArrayHasKey('item', $meta);
        $item = $meta['item'];

        self::assertSame('choiceGroup', $item->propertyKind);
        self::assertFalse($item->isChoice, 'choiceGroup must NOT set isChoice (it appends, not value[x] select)');
        self::assertTrue($item->isArray);
        self::assertSame(ChoiceGroupItem::class, $item->phpItemClass);
        self::assertNotNull($item->variants, 'choiceGroup must populate variants despite isChoice=false');
        self::assertCount(3, $item->variants);
    }

    public function testChoiceGroupVariantsAreKeyedByChildElementName(): void
    {
        $meta     = $this->provider->getPropertyMetadata(ChoiceGroupStub::class);
        $variants = $meta['item']->variants;
        self::assertNotNull($variants);

        $byElementName = [];
        foreach ($variants as $variant) {
            $byElementName[$variant->jsonKey] = $variant;
        }

        self::assertSame(['streetAddressLine', 'city', 'xmlText'], array_keys($byElementName));

        // The xmlText (bare string) variant is a builtin; the element-named ADXP variants are not.
        self::assertTrue($byElementName['xmlText']->isBuiltin);
        self::assertSame('string', $byElementName['xmlText']->phpType);
        self::assertFalse($byElementName['streetAddressLine']->isBuiltin);
        self::assertSame('complex', $byElementName['streetAddressLine']->propertyKind);
    }

    public function testValueXChoiceVariantBuildingIsUnchanged(): void
    {
        $meta = $this->provider->getPropertyMetadata(ValueChoiceStub::class);

        self::assertArrayHasKey('deceased', $meta);
        $deceased = $meta['deceased'];

        self::assertTrue($deceased->isChoice);
        self::assertNotNull($deceased->variants);
        self::assertCount(2, $deceased->variants);
        self::assertSame('deceasedBoolean', $deceased->variants[0]->jsonKey);
        self::assertTrue($deceased->variants[0]->isBuiltin);
    }
}
