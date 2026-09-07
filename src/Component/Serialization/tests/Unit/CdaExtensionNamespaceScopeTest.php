<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Act;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuAsEmployment;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuAsEntityIdentifier;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuEmployerOrganization;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuId;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuOrganization;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Patient;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Precondition2;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\PreconditionBase;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Section;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuSubjectOf2;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuSubstitutionPermission;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\AuOrganizationName;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ST;
use Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * Where an AU/sdtc extension element's namespace stops.
 *
 * A property-level `xmlNamespace` is emitted as `@xmlns`, which XML treats as a *default-namespace
 * redefinition*: it applies to the element and every descendant that does not redeclare. So an
 * extension element used to drag its whole subtree into the extension namespace, and the wrapped
 * type's own `#[LogicalModel]` namespace was never consulted.
 *
 * The distinction is invisible to the obvious assertion. The document stays well-formed, every value
 * is present and correctly spelled, and `local-name()` — the natural thing to reach for when a prefix
 * is inconvenient — matches either way. Only the namespace URI separates them, and it is the half
 * that decides element identity: `<name>` in the AU namespace is a different element from CDA's
 * `<name>`, so a validating consumer sees an organisation with no name, no id and no parent
 * organisation. Every assertion here therefore resolves `namespaceURI` through the DOM rather than
 * matching serialized text.
 *
 * @see https://github.com/Ardenexal/php-fhir-tools/issues/116
 */
final class CdaExtensionNamespaceScopeTest extends TestCase
{
    /** The CDA default namespace, declared once on the document root and inherited from there. */
    private const CDA_NS = 'urn:hl7-org:v3';

    /** The ADHA extension namespace, carried by individual AU extension elements. */
    private const AU_EXTENSION_NS = 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0';

    /** The CDA R2 sdtc extension namespace, which behaves the same way as the AU one. */
    private const SDTC_NS = 'urn:hl7-org:sdtc';

    /** A serializer with the IG registry loaded, which is what resolves the AU CDA profiles. */
    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createWithIG(version: FhirVersion::R5);
    }

    /**
     * `employerOrganization` genuinely is an AU extension element, so the property carries the
     * extension namespace. `AuEmployerOrganization` then declares `urn:hl7-org:v3` and its
     * `id`/`name` carry no override of their own, so they are CDA elements and must resolve there.
     *
     * The `id` subtree is the one that matters in practice: it carries the practice group's HPI-O,
     * which is mandatory for My Health Record.
     */
    public function testCdaTypedChildrenOfAnExtensionElementStayInTheCdaNamespace(): void
    {
        $employment = new AuAsEmployment(
            classCode: 'EMP',
            employerOrganization: new AuEmployerOrganization(
                id: [new II(root: '1.2.36.1.2001.1001.101', extension: 'ORG-1')],
                name: new AuOrganizationName(item: [ChoiceGroupItem::text('Example Clinic')]),
            ),
        );

        $namespaces = $this->namespacesByLocalName($this->service()->serializeToXml($employment));

        self::assertSame(self::AU_EXTENSION_NS, $namespaces['employerOrganization']);
        self::assertSame(self::CDA_NS, $namespaces['id']);
        self::assertSame(self::CDA_NS, $namespaces['name']);
    }

    /**
     * The same defect one level shallower: `jobCode` is an extension element holding a plain CDA
     * `CD`, whose `originalText` belongs to CDA rather than to the extension.
     */
    public function testCdaDatatypeContentUnderAnExtensionElementStaysInTheCdaNamespace(): void
    {
        $employment = new AuAsEmployment(
            classCode: 'EMP',
            jobCode: new CD(code: 'DOC', originalText: new ED(xmlText: 'Doctor')),
        );

        $namespaces = $this->namespacesByLocalName($this->service()->serializeToXml($employment));

        self::assertSame(self::AU_EXTENSION_NS, $namespaces['jobCode']);
        self::assertSame(self::CDA_NS, $namespaces['originalText']);
    }

    /**
     * The counter-example, and the reason the fix reads each property's own metadata rather than
     * assuming everything under an extension element is CDA. `asEntityIdentifier`'s content genuinely
     * is in the extension namespace, and `AuAsEntityIdentifier` says so: its `id` carries an explicit
     * property-level `xmlNamespace`. Only children with no override of their own fall back to the
     * declaring type's namespace.
     */
    public function testExtensionContentWithItsOwnNamespaceOverrideIsUnaffected(): void
    {
        $organization = new AuOrganization(
            asEntityIdentifier: [new AuAsEntityIdentifier(
                id: new AuId(root: '1.2.36.1.2001.1003.0', extension: 'HPIO-1'),
            )],
        );

        $namespaces = $this->namespacesByLocalName($this->service()->serializeToXml($organization));

        self::assertSame(self::AU_EXTENSION_NS, $namespaces['asEntityIdentifier']);
        self::assertSame(self::AU_EXTENSION_NS, $namespaces['id']);
    }

    /**
     * The second counter-example. `substitutionPermission.code` is a CDA `CE` reached through a
     * property that overrides the namespace, so the element itself stays in the extension namespace
     * even though the type it holds declares CDA.
     */
    public function testSubstitutionPermissionContentIsUnaffected(): void
    {
        $subjectOf2 = new AuSubjectOf2(
            substitutionPermission: new AuSubstitutionPermission(code: new CE(code: 'N')),
        );

        $namespaces = $this->namespacesByLocalName($this->service()->serializeToXml($subjectOf2));

        self::assertSame(self::AU_EXTENSION_NS, $namespaces['substitutionPermission']);
        self::assertSame(self::AU_EXTENSION_NS, $namespaces['code']);
    }

    /**
     * Ordinary CDA content declares the namespace once, on the root. A fix that ends the extension
     * scope correctly must not start redeclaring on every child of an ordinary element: that bloats
     * the output and breaks byte-level round-trips against published CDA examples.
     */
    public function testOrdinaryCdaContentDeclaresTheNamespaceOnlyOnTheRoot(): void
    {
        $organization = new AuOrganization(
            name: [new AuOrganizationName(item: [ChoiceGroupItem::text('Example Clinic')])],
        );

        $xml = $this->service()->serializeToXml($organization);

        self::assertSame(1, substr_count($xml, 'xmlns='), $xml);
    }

    /**
     * The sdtc half of the same rule, and the larger half by site count: 82 properties declare
     * `urn:hl7-org:sdtc` against 87 AU ones. `Patient.sdtcRaceCode` is an sdtc extension element
     * holding a plain CDA `CE`, so the element is sdtc and its `originalText` is CDA.
     *
     * Worth its own case because every pre-existing sdtc assertion in the suite covers an
     * attribute-only element (`<raceCode code="SDTCRACE"/>`), which cannot show the difference — the
     * whole defect is about children, and those cases have none.
     */
    public function testCdaDatatypeContentUnderAnSdtcExtensionElementStaysInTheCdaNamespace(): void
    {
        $patient = new Patient(
            sdtcRaceCode: [new CE(code: 'SDTCRACE', originalText: new ED(xmlText: 'Some race'))],
        );

        $namespaces = $this->namespacesByLocalName($this->service()->serializeToXml($patient));

        self::assertSame(self::SDTC_NS, $namespaces['raceCode']);
        self::assertSame(self::CDA_NS, $namespaces['originalText']);
    }

    /**
     * The sdtc counter-example. Six CDA types declare `urn:hl7-org:sdtc` at class level, meaning
     * their content genuinely is sdtc; `Precondition2` is one. Reached through an sdtc property, its
     * own namespace already matches the scope, so its children declare nothing and the output is
     * unchanged — the same reasoning that leaves `asEntityIdentifier` alone, arriving from the class
     * rather than from the property.
     */
    public function testSdtcTypedContentUnderAnSdtcExtensionElementIsUnaffected(): void
    {
        $act = new Act(sdtcPrecondition2: [new Precondition2(allTrue: new PreconditionBase())]);

        $xml        = $this->service()->serializeToXml($act);
        $namespaces = $this->namespacesByLocalName($xml);

        self::assertSame(self::SDTC_NS, $namespaces['precondition2']);
        self::assertSame(self::SDTC_NS, $namespaces['allTrue']);
        // Declared on the root and on precondition2, and nowhere else: allTrue inherits.
        self::assertSame(2, substr_count($xml, 'xmlns='), $xml);
    }

    /**
     * Ending an extension scope walks the element's children to declare the type's namespace on
     * them. Narrative is not one of those children: it is markup injected as element *content*, and
     * a walk that treated it as a child would either re-namespace the StrucDoc tree or declare
     * `xmlns` in the middle of the narrative. Both stay well-formed with every word intact, which is
     * why this is asserted rather than eyeballed.
     */
    public function testNarrativeContentIsUntouchedByTheNamespaceWalk(): void
    {
        $section = new Section(
            title: new ST(xmlText: 'Employment'),
            text: '<paragraph>Employed at Example Clinic.</paragraph>',
        );

        $xml        = $this->service()->serializeToXml($section);
        $namespaces = $this->namespacesByLocalName($xml);

        self::assertSame(self::CDA_NS, $namespaces['text']);
        self::assertSame(self::CDA_NS, $namespaces['paragraph']);
        self::assertSame(self::CDA_NS, $namespaces['title']);
        self::assertSame(1, substr_count($xml, 'xmlns='), $xml);
    }

    /**
     * The redeclared `xmlns` is a new attribute on elements that never carried one, so decode has to
     * be exercised, not assumed: a fix that repaired encoding while breaking decoding of the very
     * documents it produces would be worse than the bug. Asserts the values survive and that a second
     * encode reproduces the first byte for byte.
     */
    public function testFixedOutputRoundTripsWithoutLosingContent(): void
    {
        $service = $this->service();

        $employment = new AuAsEmployment(
            classCode: 'EMP',
            jobCode: new CD(code: 'DOC', originalText: new ED(xmlText: 'Doctor')),
            employerOrganization: new AuEmployerOrganization(
                id: [new II(root: '1.2.36.1.2001.1001.101', extension: 'ORG-1')],
            ),
        );

        $encoded = $service->serializeToXml($employment);
        $decoded = $service->deserializeFromXml($encoded, AuAsEmployment::class);

        self::assertSame('1.2.36.1.2001.1001.101', $decoded->employerOrganization?->id[0]?->root);
        self::assertSame('ORG-1', $decoded->employerOrganization?->id[0]?->extension);
        self::assertSame('Doctor', $decoded->jobCode?->originalText?->xmlText);
        self::assertSame($encoded, $service->serializeToXml($decoded));
    }

    /**
     * Resolve every element's namespace URI through the DOM, keyed by local name.
     *
     * Reading URIs rather than prefixes is the point of these tests: the serializer emits default
     * `xmlns` declarations, so there are no prefixes to compare, and a prefix would not settle
     * identity anyway.
     *
     * @return array<string, string|null> local element name => namespace URI
     */
    private function namespacesByLocalName(string $xml): array
    {
        $document = new \DOMDocument();
        self::assertTrue($document->loadXML($xml), 'Serialized output is not well-formed XML');

        $elements = (new \DOMXPath($document))->query('//*');
        self::assertNotFalse($elements);

        $namespaces = [];
        foreach ($elements as $element) {
            if ($element instanceof \DOMElement && $element->localName !== null) {
                $namespaces[$element->localName] = $element->namespaceURI;
            }
        }

        return $namespaces;
    }
}
