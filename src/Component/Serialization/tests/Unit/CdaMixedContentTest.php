<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuEmployerOrganization;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Organization;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\AD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\AuOrganizationName;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\EN;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ENXP;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ON;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\PN;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\TN;
use Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Mixed element-and-text content in transparent XML choice groups.
 *
 * CDA `EN` and `AD` interleave character data with their element parts, so an organisation name is
 * written as bare element text — `<name>Example Clinic</name>`, not `<name><xmlText>…</xmlText></name>`
 * and not `<name><prefix>…</prefix></name>`. The text run is a MEMBER of the ordered group, keyed by
 * the reserved name {@see ChoiceGroupItem::TEXT_ELEMENT_NAME}, which is how the source
 * StructureDefinition models it (`EN.item.xmlText`, counted as a peer of the element slices by the
 * `EN-1` invariant).
 *
 * The assertions are on the serialized string, not the object graph. Every wrong encoding of this
 * name serializes without error and satisfies an assertion that merely checks `<name>` exists — it
 * is only rejected downstream — so an object-level check would pass on output no CDA consumer takes.
 */
final class CdaMixedContentTest extends TestCase
{
    private const string V3 = 'urn:hl7-org:v3';

    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createWithIG(version: FhirVersion::R5);
    }

    /**
     * Serialize and strip the XML declaration, leaving the root element for exact comparison.
     */
    private function serialize(object $subject): string
    {
        $xml = $this->service()->serializeToXml($subject);

        return trim(preg_replace('/^<\?xml[^?]*\?>\s*/u', '', $xml) ?? $xml);
    }

    /**
     * The four name types that inherit EN's choice group, plus AD, which has its own.
     *
     * @return array<string, array{0: class-string}>
     */
    public static function textBearingTypeProvider(): array
    {
        return [
            'entity name'       => [EN::class],
            'organisation name' => [ON::class],
            'person name'       => [PN::class],
            'trivial name'      => [TN::class],
            'postal address'    => [AD::class],
        ];
    }

    /**
     * The headline case: an organisation name is bare element text and nothing else.
     */
    public function testOrganisationNameSerializesAsBareElementText(): void
    {
        $name = new ON(item: [ChoiceGroupItem::text('Example Clinic')]);

        self::assertSame(
            '<ON xmlns="' . self::V3 . '">Example Clinic</ON>',
            $this->serialize($name),
        );
    }

    /**
     * The string form stays valid — ChoiceGroupItem::text() is sugar, not a new requirement.
     */
    public function testReservedElementNameIsEquivalentToTheTextFactory(): void
    {
        $viaFactory = new ON(item: [ChoiceGroupItem::text('Example Clinic')]);
        $viaName    = new ON(item: [new ChoiceGroupItem(ChoiceGroupItem::TEXT_ELEMENT_NAME, 'Example Clinic')]);

        self::assertSame($this->serialize($viaFactory), $this->serialize($viaName));
    }

    /**
     * Every type carrying a text slice gets the same treatment, not just the one that was reported.
     *
     * @param class-string $datatype
     */
    #[DataProvider('textBearingTypeProvider')]
    public function testEveryTextBearingTypeEmitsBareText(string $datatype): void
    {
        $subject = new $datatype(item: [ChoiceGroupItem::text('Example Clinic')]);

        self::assertStringContainsString('>Example Clinic<', $this->serialize($subject));
        self::assertStringNotContainsString('xmlText', $this->serialize($subject));
    }

    /**
     * Mixed content in both directions of interleaving. A scalar `xmlText` property alongside the
     * group could not do this: both would write the normalizer's single '#' slot and one half would
     * be dropped silently.
     */
    public function testTextAndElementPartsInterleaveInListOrder(): void
    {
        $textFirst = new EN(item: [
            ChoiceGroupItem::text('Example '),
            new ChoiceGroupItem('family', new ENXP(xmlText: 'Clinic')),
        ]);

        self::assertSame(
            '<EN xmlns="' . self::V3 . '">Example <family representation="TXT" mediaType="text/plain">Clinic</family></EN>',
            $this->serialize($textFirst),
        );

        $elementFirst = new EN(item: [
            new ChoiceGroupItem('family', new ENXP(xmlText: 'Clinic')),
            ChoiceGroupItem::text(' Pty Ltd'),
        ]);

        self::assertSame(
            '<EN xmlns="' . self::V3 . '"><family representation="TXT" mediaType="text/plain">Clinic</family> Pty Ltd</EN>',
            $this->serialize($elementFirst),
        );
    }

    /**
     * Several text runs around several parts — the case a single scalar text property cannot express
     * at all, regardless of the '#' collision.
     */
    public function testMultipleTextRunsSurviveAroundElementParts(): void
    {
        $address = new AD(item: [
            ChoiceGroupItem::text('PO Box '),
            new ChoiceGroupItem('postBox', new ADXP(xmlText: '123')),
            ChoiceGroupItem::text(' near '),
            new ChoiceGroupItem('city', new ADXP(xmlText: 'Sydney')),
        ]);

        $xml = $this->serialize($address);

        self::assertStringContainsString('>PO Box <', $xml);
        self::assertStringContainsString('> near <', $xml);
        self::assertLessThan(
            strpos($xml, 'Sydney') ?: 0,
            strpos($xml, ' near ') ?: 0,
            'text runs must keep their position relative to the element parts',
        );
    }

    /**
     * Regression guard: the element-only path that worked before this change must be untouched.
     */
    public function testPersonNameWithElementPartsOnlyIsUnchanged(): void
    {
        $name = new PN(item: [
            new ChoiceGroupItem('given', new ENXP(xmlText: 'Jo')),
            new ChoiceGroupItem('family', new ENXP(xmlText: 'Bloggs')),
        ]);

        self::assertSame(
            '<PN xmlns="' . self::V3 . '">'
            . '<given representation="TXT" mediaType="text/plain">Jo</given>'
            . '<family representation="TXT" mediaType="text/plain">Bloggs</family>'
            . '</PN>',
            $this->serialize($name),
        );
    }

    /**
     * The read half: bare text reconstructs as a text member, not as an empty name and not as a raw
     * string sitting in a `list<ON>`.
     */
    public function testBareTextNameDeserializesToATextMember(): void
    {
        $organization = $this->service()->deserializeFromXml(
            '<Organization xmlns="' . self::V3 . '"><name>Example Clinic</name></Organization>',
            Organization::class,
        );

        self::assertInstanceOf(Organization::class, $organization);
        self::assertCount(1, $organization->name);

        $name = $organization->name[0];
        self::assertInstanceOf(ON::class, $name, 'a bare-text name must still build an ON, not a raw string');
        self::assertCount(1, $name->item);
        self::assertSame(ChoiceGroupItem::TEXT_ELEMENT_NAME, $name->item[0]->elementName);
        self::assertSame('Example Clinic', $name->item[0]->value);
    }

    /**
     * Full round-trip of the reported document shape.
     */
    public function testBareTextNameRoundTrips(): void
    {
        $xml = '<Organization xmlns="' . self::V3 . '"><name>Example Clinic</name></Organization>';

        $organization = $this->service()->deserializeFromXml($xml, Organization::class);
        self::assertInstanceOf(Organization::class, $organization);

        self::assertStringContainsString('<name>Example Clinic</name>', $this->serialize($organization));
    }

    /**
     * Mixed content read back keeps both halves. Before this change the text runs were dropped and
     * the document re-serialized as `<name><family>Clinic</family></name>` — valid-looking, wrong.
     */
    public function testMixedContentSurvivesDeserialization(): void
    {
        $organization = $this->service()->deserializeFromXml(
            '<Organization xmlns="' . self::V3 . '"><name>Example <family>Clinic</family> Pty</name></Organization>',
            Organization::class,
        );

        self::assertInstanceOf(Organization::class, $organization);
        $items = $organization->name[0]->item;

        self::assertCount(3, $items);
        self::assertSame(['xmlText', 'family', 'xmlText'], array_map(
            static fn (ChoiceGroupItem $item): string => $item->elementName,
            $items,
        ));
        self::assertSame('Example ', $items[0]->value);
        self::assertSame(' Pty', $items[2]->value);
    }

    /**
     * The bare-text read builds the object itself, so it must build the PROFILE type the property
     * declares, not the core type it derives from. `AuEmployerOrganization::$name` is typed
     * `?AuOrganizationName`; coming back as a plain `ON` would be a silent profile downgrade.
     */
    public function testBareTextReadKeepsTheProfiledSubtype(): void
    {
        $xml = '<employerOrganization xmlns="' . self::V3 . '"><name>Example Clinic</name></employerOrganization>';

        $organization = $this->service()->deserializeFromXml($xml, AuEmployerOrganization::class);

        self::assertInstanceOf(AuEmployerOrganization::class, $organization);
        self::assertInstanceOf(AuOrganizationName::class, $organization->name);
        self::assertSame('Example Clinic', $organization->name->item[0]->value);
    }

    /**
     * Whitespace between element members is XML layout, not content: a pretty-printed name must read
     * back as its two parts, not as part/text/part/text/part. This guard is load-bearing — the source
     * DOM is parsed with preserveWhiteSpace on, so those nodes do reach the choice-group read.
     */
    public function testPrettyPrintedWhitespaceIsNotReadAsContent(): void
    {
        $organization = $this->service()->deserializeFromXml(
            '<Organization xmlns="' . self::V3 . "\">\n  <name>\n    <given>Jo</given>\n    <family>Bloggs</family>\n  </name>\n</Organization>",
            Organization::class,
        );

        self::assertInstanceOf(Organization::class, $organization);
        self::assertSame(['given', 'family'], array_map(
            static fn (ChoiceGroupItem $item): string => $item->elementName,
            $organization->name[0]->item,
        ));
    }

    /**
     * The other side of that rule: whitespace that is the element's only content is content, since
     * there are no element members for it to be separating.
     */
    public function testWhitespaceOnlyNameIsPreservedWhenItIsTheOnlyContent(): void
    {
        $organization = $this->service()->deserializeFromXml(
            '<Organization xmlns="' . self::V3 . '"><name> </name></Organization>',
            Organization::class,
        );

        self::assertInstanceOf(Organization::class, $organization);
        self::assertCount(1, $organization->name[0]->item);
        self::assertSame(' ', $organization->name[0]->item[0]->value);
    }
}
