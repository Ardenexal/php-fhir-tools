<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Patient;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\PatientRole;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\AD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ENXP;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\PN;
use Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Metadata\Type\PropertyMetadataProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Transparent XML choice groups: an element carrying the `xml-choice-group` tooling extension whose
 * heterogeneous children emit directly under the parent, in significant document order.
 *
 * CDA postal addresses and person names are the concrete cases. `<addr>` holds
 * `streetAddressLine`, `city`, `streetAddressLine`, … as siblings with no wrapper element, and the
 * order is the address. The generator previously dropped those child slices, typing `AD::$item` at
 * the FHIR `Base` marker and so at a bare string — an address that could hold no address parts.
 *
 * The ordering assertions are the point. Symfony's XmlEncoder regroups same-named siblings on
 * decode, so a naive implementation reads `streetAddressLine, city, streetAddressLine` back as two
 * street lines followed by a city: still well-formed, still wrong, and invisible to any assertion
 * that only counts elements.
 */
final class CdaChoiceGroupTest extends TestCase
{
    private const string V3 = 'urn:hl7-org:v3';

    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createWithIG(version: FhirVersion::R5);
    }

    /**
     * The five V3 datatypes whose `item` element carries the extension. Listed explicitly so that a
     * group silently losing its slices — the original defect — fails here.
     *
     * @return array<string, array{0: class-string, 1: int}>
     */
    public static function choiceGroupTypeProvider(): array
    {
        $datatype = 'Ardenexal\\FHIRTools\\Component\\CdaModels\\DataType\\';

        return [
            'postal address'      => [$datatype . 'AD', 28],
            'entity name'         => [$datatype . 'EN', 6],
            'organisation name'   => [$datatype . 'ON', 6],
            'person name'         => [$datatype . 'PN', 6],
            'trivial name'        => [$datatype . 'TN', 6],
        ];
    }

    /**
     * @param class-string $datatype
     */
    #[DataProvider('choiceGroupTypeProvider')]
    public function testChoiceGroupPropertyCarriesItsVariantsInMetadata(string $datatype, int $expectedVariants): void
    {
        $metadata = (new PropertyMetadataProvider())->getPropertyMetadata($datatype);

        self::assertArrayHasKey('item', $metadata, "{$datatype} must declare an item property");

        $item = $metadata['item'];
        self::assertSame('choiceGroup', $item->propertyKind);
        self::assertNotNull($item->variants, 'a choiceGroup without variants cannot be dispatched');
        self::assertCount($expectedVariants, $item->variants);

        // Every variant's element name is its discriminator, and each must be distinct.
        $names = array_map(static fn ($variant): string => $variant->jsonKey, $item->variants);
        self::assertSame($names, array_values(array_unique($names)), 'variant element names must be unique');
    }

    public function testAddressPartsEmitInDocumentOrderWithNoWrapperElement(): void
    {
        $role = new PatientRole(addr: [new AD(item: [
            new ChoiceGroupItem('streetAddressLine', new ADXP(xmlText: '123 Main St')),
            new ChoiceGroupItem('city', new ADXP(xmlText: 'Sydney')),
            new ChoiceGroupItem('streetAddressLine', new ADXP(xmlText: 'Apt 4')),
            new ChoiceGroupItem('postalCode', new ADXP(xmlText: '2000')),
        ])]);

        $xml = $this->service()->serializeToXml($role);

        self::assertSame(
            [
                'streetAddressLine=123 Main St',
                'city=Sydney',
                'streetAddressLine=Apt 4',
                'postalCode=2000',
            ],
            $this->childrenOf($xml, '/cda:PatientRole/cda:addr'),
            "address parts must interleave in list order: {$xml}",
        );

        // Transparent means transparent: no <item> element may appear.
        self::assertStringNotContainsString('<item', $xml);
    }

    public function testAddressPartsSurviveARoundTrip(): void
    {
        $service = $this->service();

        $role = new PatientRole(addr: [new AD(item: [
            new ChoiceGroupItem('streetAddressLine', new ADXP(xmlText: '123 Main St')),
            new ChoiceGroupItem('city', new ADXP(xmlText: 'Sydney')),
            new ChoiceGroupItem('streetAddressLine', new ADXP(xmlText: 'Apt 4')),
        ])]);

        $back = $service->deserializeFromXml($service->serializeToXml($role), PatientRole::class);

        $recovered = [];
        foreach ($back->addr[0]->item ?? [] as $item) {
            self::assertInstanceOf(ChoiceGroupItem::class, $item);
            $value = $item->value;
            self::assertInstanceOf(ADXP::class, $value);
            $recovered[] = $item->elementName . '=' . $value->xmlText;
        }

        self::assertSame(
            ['streetAddressLine=123 Main St', 'city=Sydney', 'streetAddressLine=Apt 4'],
            $recovered,
            'the repeated street line must come back either side of the city, not grouped with itself',
        );
    }

    /**
     * The mechanism is metadata-driven, so a second group works with no further code. A person name
     * is the check: different datatype, different variant set, same rule.
     */
    public function testASecondChoiceGroupWorksWithoutFurtherCode(): void
    {
        $patient = new Patient(name: [new PN(item: [
            new ChoiceGroupItem('given', new ENXP(xmlText: 'Jane')),
            new ChoiceGroupItem('given', new ENXP(xmlText: 'Alex')),
            new ChoiceGroupItem('family', new ENXP(xmlText: 'Citizen')),
        ])]);

        $xml = $this->service()->serializeToXml($patient);

        self::assertSame(
            ['given=Jane', 'given=Alex', 'family=Citizen'],
            $this->childrenOf($xml, '/cda:Patient/cda:name'),
            "name parts must interleave in list order: {$xml}",
        );
    }

    /**
     * `partType` is an optional derived attribute on the part element, never the discriminator — the
     * element name is. It must survive as an attribute without displacing that role.
     */
    public function testPartTypeRoundTripsAsAnAttributeAndNotAsTheDiscriminator(): void
    {
        $service = $this->service();

        $role = new PatientRole(addr: [new AD(item: [
            new ChoiceGroupItem('streetAddressLine', new ADXP(xmlText: '123 Main St', partType: 'SAL')),
        ])]);

        $xml = $service->serializeToXml($role);

        $dom = new \DOMDocument();
        self::assertTrue($dom->loadXML($xml));
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('cda', self::V3);

        $parts = $xpath->query('/cda:PatientRole/cda:addr/cda:streetAddressLine');
        self::assertInstanceOf(\DOMNodeList::class, $parts);
        $part = $parts->item(0);
        self::assertInstanceOf(\DOMElement::class, $part, "the element name stays the discriminator: {$xml}");
        self::assertSame('SAL', $part->getAttribute('partType'));

        $back  = $service->deserializeFromXml($xml, PatientRole::class);
        $value = ($back->addr[0]->item[0] ?? null)?->value;
        self::assertInstanceOf(ADXP::class, $value);
        self::assertSame('SAL', $value->partType);
    }

    /**
     * Child element names and text content of the element the expression selects, in document order.
     *
     * @return list<string>
     */
    private function childrenOf(string $xml, string $expression): array
    {
        $dom = new \DOMDocument();
        self::assertTrue($dom->loadXML($xml), 'serialized output must be well-formed XML');

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('cda', self::V3);

        $nodes = $xpath->query($expression . '/*');
        self::assertInstanceOf(\DOMNodeList::class, $nodes);

        $children = [];
        foreach ($nodes as $node) {
            if ($node instanceof \DOMElement) {
                $children[] = $node->localName . '=' . $node->textContent;
            }
        }

        return $children;
    }
}
