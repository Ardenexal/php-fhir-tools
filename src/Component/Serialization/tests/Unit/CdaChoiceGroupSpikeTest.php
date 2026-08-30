<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

/**
 * Fixture logical-model standing in for CDA `AD`: an `item` property carrying the transparent
 * xml-choice-group (propertyKind 'choiceGroup'). Used instead of the real `AD` because `AD.item`
 * does not gain `choiceGroup` metadata until codegen (M7 task 7); this fixture lets the
 * metadata-driven ordered emit be exercised independently.
 */
#[LogicalModel(url: 'urn:test:ChoiceGroupAddress', name: 'addr', fhirVersion: '5.0.0', xmlNamespace: 'urn:hl7-org:v3')]
class ChoiceGroupAddressFixture
{
    /**
     * @param list<ChoiceGroupItem> $item
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://hl7.org/fhir/StructureDefinition/Base',
            propertyKind: 'choiceGroup',
            isArray: true,
            phpType: ChoiceGroupItem::class,
            variants: [
                ['fhirType' => 'ADXP', 'propertyKind' => 'complex', 'phpType' => ADXP::class, 'jsonKey' => 'streetAddressLine'],
                ['fhirType' => 'ADXP', 'propertyKind' => 'complex', 'phpType' => ADXP::class, 'jsonKey' => 'city'],
                ['fhirType' => 'string', 'propertyKind' => 'scalar', 'phpType' => 'string', 'jsonKey' => 'additionalLocator'],
            ],
        )]
        public array $item = [],
    ) {
    }
}

/**
 * Fixture container nesting the choice-group address (stands in for PatientRole/addr), so the
 * ordered emit can be exercised when the choice-group element is deep in a document with multiple
 * sibling occurrences.
 */
#[LogicalModel(url: 'urn:test:ChoiceGroupRole', name: 'patientRole', fhirVersion: '5.0.0', xmlNamespace: 'urn:hl7-org:v3')]
class ChoiceGroupRoleFixture
{
    /**
     * @param list<ChoiceGroupAddressFixture> $addr
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'urn:test:ChoiceGroupAddress',
            propertyKind: 'complex',
            isArray: true,
            phpType: ChoiceGroupAddressFixture::class,
        )]
        public array $addr = [],
    ) {
    }
}

/**
 * M7 task 5 — metadata-driven ordered-DOM emit for transparent xml-choice-group properties.
 *
 * Drives the real pipeline (FHIRSerializationService -> FHIRLogicalModelXmlNormalizer ->
 * XmlEncoder) on a fixture whose `item` property is propertyKind 'choiceGroup', and asserts the
 * heterogeneous children emit directly under the parent, in true document order, with no wrapper
 * element. Also re-confirms the spike's decode findings (XmlEncoder->decode destroys interleaved
 * order; a DOM read in document order recovers it) against the metadata-driven output.
 *
 * Note on value fidelity: an ADXP value normalizes to its full XML form, so the address-part
 * elements currently also carry ST's default `representation="TXT"`/`mediaType="text/plain"`
 * attributes. That is lossless and round-trip-stable; suppressing those defaults to match published
 * CDA byte-for-byte is a separate fidelity concern (see M7 plan, acceptance task). The assertions
 * below therefore key on element name, text content, document order, and @partType — not on the
 * presence/absence of those default attributes.
 */
final class CdaChoiceGroupSpikeTest extends TestCase
{
    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createWithIG(version: FhirVersion::R5);
    }

    /**
     * Interleaved address: two streetAddressLine parts around a city part, the last carrying a
     * partType attribute, so any grouping or reordering is immediately visible.
     */
    private function interleavedAddress(): ChoiceGroupAddressFixture
    {
        return new ChoiceGroupAddressFixture(item: [
            new ChoiceGroupItem('streetAddressLine', new ADXP(xmlText: '123 Main St')),
            new ChoiceGroupItem('city', new ADXP(xmlText: 'Sydney')),
            new ChoiceGroupItem('streetAddressLine', new ADXP(xmlText: 'Apt 4', partType: 'AL')),
        ]);
    }

    /**
     * @return list<array{elementName: string, text: string, partType: string|null}>
     */
    private function expectedOrderedParts(): array
    {
        return [
            ['elementName' => 'streetAddressLine', 'text' => '123 Main St', 'partType' => null],
            ['elementName' => 'city', 'text' => 'Sydney', 'partType' => null],
            ['elementName' => 'streetAddressLine', 'text' => 'Apt 4', 'partType' => 'AL'],
        ];
    }

    public function testChoiceGroupChildrenEmitInDocumentOrderNotGrouped(): void
    {
        $xml = $this->service()->serializeToXml($this->interleavedAddress());

        // Each part is a direct child of <addr>, named by its element name, with no <item> wrapper
        // and no CDATA escaping.
        self::assertStringContainsString('<streetAddressLine', $xml);
        self::assertStringContainsString('<city', $xml);
        self::assertStringNotContainsString('<item', $xml);
        self::assertStringNotContainsString('<![CDATA[', $xml);

        // True document order: street, city, street — NOT grouped as street, street, city.
        $posStreet1 = strpos($xml, '123 Main St');
        $posCity    = strpos($xml, 'Sydney');
        $posStreet2 = strpos($xml, 'Apt 4');
        self::assertNotFalse($posStreet1);
        self::assertNotFalse($posCity);
        self::assertNotFalse($posStreet2);
        self::assertLessThan($posCity, $posStreet1, 'first streetAddressLine must precede city');
        self::assertLessThan($posStreet2, $posCity, 'city must precede the second streetAddressLine (proves not grouped by kind)');
    }

    public function testChoiceGroupCoexistsWithNamespaceAndPartTypeAttribute(): void
    {
        $xml = $this->service()->serializeToXml($this->interleavedAddress());

        // Root namespace declared exactly once, on the root (M5 behaviour preserved).
        self::assertStringContainsString('xmlns="urn:hl7-org:v3"', $xml);
        self::assertSame(1, substr_count($xml, 'xmlns="urn:hl7-org:v3"'), 'namespace declared once, on the root');

        // @partType is carried on its own part as a derived/optional attribute (read back via DOM
        // so attribute ordering is irrelevant).
        $parts        = $this->readChoiceGroupInDocumentOrder($xml);
        $withPartType = array_values(array_filter($parts, static fn (array $p): bool => $p['partType'] !== null));
        self::assertCount(1, $withPartType);
        self::assertSame('AL', $withPartType[0]['partType']);
        self::assertSame('Apt 4', $withPartType[0]['text']);
    }

    public function testStringValuedChoiceItemEmitsAsTextContent(): void
    {
        // A bare-string ChoiceGroupItem value (vs an object) emits as the element's text content.
        $address = new ChoiceGroupAddressFixture(item: [
            new ChoiceGroupItem('additionalLocator', 'Building B'),
        ]);

        $xml = $this->service()->serializeToXml($address);

        self::assertStringContainsString('<additionalLocator>Building B</additionalLocator>', $xml);
    }

    public function testSymfonyXmlEncoderDecodeDestroysInterleavedOrder(): void
    {
        $xml = $this->service()->serializeToXml($this->interleavedAddress());

        $decoded = (new XmlEncoder())->decode($xml, 'xml');
        self::assertIsArray($decoded);

        // The two streetAddressLine parts collapse into one grouped array and lose their position
        // relative to <city>. This is WHY denormalize (M7 task 6) must read raw XML in document
        // order via DOM rather than trusting the encoder-decoded array.
        self::assertArrayHasKey('streetAddressLine', $decoded);
        self::assertIsArray($decoded['streetAddressLine']);
        self::assertCount(2, $decoded['streetAddressLine'], 'both streetAddressLine parts collapsed into one grouped array — interleaving with <city> is gone');
        self::assertArrayHasKey('city', $decoded);
    }

    public function testOrderedDomReadRoundTripsLosslessly(): void
    {
        $xml = $this->service()->serializeToXml($this->interleavedAddress());

        $reconstructed = $this->readChoiceGroupInDocumentOrder($xml);

        // Element names, text values, @partType, AND order all survive a DOM read in document order.
        self::assertSame($this->expectedOrderedParts(), $reconstructed);
    }

    public function testChoiceGroupRoundTripsXmlToObjectToXmlByteStable(): void
    {
        // The M7 exit criterion: an <addr> with interleaved parts round-trips XML -> object -> XML
        // with document order preserved. Byte-stability proves order + text + @partType all survive.
        $xml1 = $this->service()->serializeToXml($this->interleavedAddress());
        $xml2 = $this->service()->serializeToXml(
            $this->service()->deserializeFromXml($xml1, ChoiceGroupAddressFixture::class),
        );

        self::assertSame($xml1, $xml2);
    }

    public function testDeserializeRecoversChoiceGroupItemsInDocumentOrder(): void
    {
        $xml = $this->service()->serializeToXml($this->interleavedAddress());

        $address = $this->service()->deserializeFromXml($xml, ChoiceGroupAddressFixture::class);

        self::assertCount(3, $address->item);
        $summary = array_map(
            static fn (ChoiceGroupItem $i): array => [
                'elementName' => $i->elementName,
                'text'        => $i->value instanceof ADXP ? $i->value->xmlText : $i->value,
            ],
            $address->item,
        );

        self::assertSame([
            ['elementName' => 'streetAddressLine', 'text' => '123 Main St'],
            ['elementName' => 'city', 'text' => 'Sydney'],
            ['elementName' => 'streetAddressLine', 'text' => 'Apt 4'],
        ], $summary);
    }

    public function testDeserializeRecoversPartTypeAsDerivedAttribute(): void
    {
        $xml = $this->service()->serializeToXml($this->interleavedAddress());

        $address = $this->service()->deserializeFromXml($xml, ChoiceGroupAddressFixture::class);

        // @partType round-trips onto its own part, never as the discriminator (the discriminator is
        // the element name).
        $lastValue = $address->item[2]->value;
        self::assertInstanceOf(ADXP::class, $lastValue);
        self::assertSame('AL', $lastValue->partType);
    }

    public function testNestedChoiceGroupEmitsCleanlyWithNamespaceDeclaredOnce(): void
    {
        $role = new ChoiceGroupRoleFixture(addr: [
            $this->interleavedAddress(),
            new ChoiceGroupAddressFixture(item: [
                new ChoiceGroupItem('city', new ADXP(xmlText: 'Melbourne')),
                new ChoiceGroupItem('streetAddressLine', new ADXP(xmlText: '5 King St')),
            ]),
        ]);

        $xml = $this->service()->serializeToXml($role);

        // Namespace declared exactly once on the document root, NOT re-declared on each nested
        // choice-group child.
        self::assertSame(1, substr_count($xml, 'xmlns="urn:hl7-org:v3"'));
        self::assertStringNotContainsString('<item', $xml);

        // Each <addr> recovers its own distinct document order from its own DOM node.
        $byAddress = $this->readNestedChoiceGroupsInDocumentOrder($xml);
        self::assertSame([
            [
                ['elementName' => 'streetAddressLine', 'text' => '123 Main St'],
                ['elementName' => 'city', 'text' => 'Sydney'],
                ['elementName' => 'streetAddressLine', 'text' => 'Apt 4'],
            ],
            [
                ['elementName' => 'city', 'text' => 'Melbourne'],
                ['elementName' => 'streetAddressLine', 'text' => '5 King St'],
            ],
        ], $byAddress);
    }

    public function testNestedChoiceGroupRoundTripsWithPerAddressOrderPreserved(): void
    {
        $role = new ChoiceGroupRoleFixture(addr: [
            $this->interleavedAddress(),
            new ChoiceGroupAddressFixture(item: [
                new ChoiceGroupItem('city', new ADXP(xmlText: 'Melbourne')),
                new ChoiceGroupItem('streetAddressLine', new ADXP(xmlText: '5 King St')),
            ]),
        ]);

        $xml1 = $this->service()->serializeToXml($role);
        $obj  = $this->service()->deserializeFromXml($xml1, ChoiceGroupRoleFixture::class);
        $xml2 = $this->service()->serializeToXml($obj);

        // Byte-stable nested round-trip: choice-group order is recovered even when the element is
        // deep in the document with multiple sibling occurrences (source DOM element threaded down).
        self::assertSame($xml1, $xml2);

        // Each address recovers its OWN distinct order — no cross-contamination between siblings.
        self::assertCount(2, $obj->addr);
        $elementNames = static fn (ChoiceGroupAddressFixture $a): array => array_map(static fn (ChoiceGroupItem $i): string => $i->elementName, $a->item);
        self::assertSame(['streetAddressLine', 'city', 'streetAddressLine'], $elementNames($obj->addr[0]));
        self::assertSame(['city', 'streetAddressLine'], $elementNames($obj->addr[1]));
    }

    /**
     * Read the choice-group children of the root element in document order — the ordered read the
     * M7 task-6 denormalize path must perform (the XmlEncoder-decoded array cannot provide it).
     *
     * @return list<array{elementName: string, text: string, partType: string|null}>
     */
    private function readChoiceGroupInDocumentOrder(string $xml): array
    {
        $document = new \DOMDocument();
        $document->loadXML($xml);

        $root = $document->documentElement;
        self::assertInstanceOf(\DOMElement::class, $root);

        $parts = [];
        foreach ($root->childNodes as $child) {
            if (!$child instanceof \DOMElement) {
                continue;
            }

            $parts[] = [
                'elementName' => $child->localName,
                'text'        => $child->textContent,
                'partType'    => $child->hasAttribute('partType') ? $child->getAttribute('partType') : null,
            ];
        }

        return $parts;
    }

    /**
     * Read each nested <addr> element's choice-group children from ITS own node, in document order.
     *
     * @return list<list<array{elementName: string, text: string}>>
     */
    private function readNestedChoiceGroupsInDocumentOrder(string $xml): array
    {
        $document = new \DOMDocument();
        $document->loadXML($xml);

        $root = $document->documentElement;
        self::assertInstanceOf(\DOMElement::class, $root);

        $addresses = [];
        foreach ($root->childNodes as $node) {
            if (!$node instanceof \DOMElement || $node->localName !== 'addr') {
                continue;
            }

            $parts = [];
            foreach ($node->childNodes as $part) {
                if (!$part instanceof \DOMElement) {
                    continue;
                }
                $parts[] = ['elementName' => $part->localName, 'text' => $part->textContent];
            }
            $addresses[] = $parts;
        }

        return $addresses;
    }
}
