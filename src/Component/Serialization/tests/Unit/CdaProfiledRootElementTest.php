<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuAsEmployment;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuAsQualifiedEntity;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuPatient;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuPlace;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuSection;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\ClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\AuCode;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\AuTemplateId;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\INTPOS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVXBPQ;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\LogicalModels\ChainedRefinementLeaf;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\LogicalModels\ChainedRefinementMiddle;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * A profiled CDA logical model must not rename its own XML element.
 *
 * `#[LogicalModel]`'s `name` is the StructureDefinition name, which for a profile is an identifier
 * (`au-ClinicalDocument`) that was never an element name — no CDA schema, schematron or consumer
 * accepts `<au-ClinicalDocument>`. There the element name is the refined type's, reached by
 * following the generated `refines` link.
 *
 * Carrying `refines` is necessary but not sufficient, which is the second axis these cases pin.
 * A definition whose `type` names another published type may be profiling it, or may merely be
 * reusing it as a base while naming an element of its own; only the first may take the refined
 * name. So the suite is split: {@see profiledModels()} must be renamed, and
 * {@see modelsThatKeepTheirOwnName()} must not — including two that carry `refines` and still keep
 * their own name.
 *
 * Every assertion here reads `local-name(/*)` off the parsed document rather than searching the
 * serialized string: `au-ClinicalDocument` *contains* `ClinicalDocument`, so a substring assertion
 * passes against the very output this test exists to reject.
 *
 * `AuPlace` and `AuCode` are the cases that rule out inferring any of this from the URL: it is
 * published under the *core* HL7 URL despite being an AU profile, while `AuCode` is an AU type
 * under the AU URL that profiles nothing. A URL-authority test gets both wrong, in opposite
 * directions.
 */
#[CoversClass(FHIRSerializationService::class)]
final class CdaProfiledRootElementTest extends TestCase
{
    private const string V3_NAMESPACE = 'urn:hl7-org:v3';

    public function testProfiledClinicalDocumentSerializesUnderTheCoreElementName(): void
    {
        $document = new AuClinicalDocument(id: new II(root: '2.16.840.1.113883.19.5'));

        $root = $this->rootElement($this->service()->serializeToXml($document));

        self::assertSame('ClinicalDocument', $root->localName);
        self::assertSame(self::V3_NAMESPACE, $root->namespaceURI);
    }

    public function testCoreClinicalDocumentIsUnaffected(): void
    {
        $document = new ClinicalDocument(id: new II(root: '2.16.840.1.113883.19.5'));

        $root = $this->rootElement($this->service()->serializeToXml($document));

        self::assertSame('ClinicalDocument', $root->localName);
        self::assertSame(self::V3_NAMESPACE, $root->namespaceURI);
    }

    /**
     * The rule is general, not special-cased on the document root: any AU model refining a core type
     * emits the core type's name.
     *
     * @param class-string $profileClass
     */
    #[DataProvider('profiledModels')]
    public function testProfiledModelsEmitTheRefinedTypeName(string $profileClass, string $expectedElement): void
    {
        $root = $this->rootElement($this->service()->serializeToXml(new $profileClass()));

        self::assertSame($expectedElement, $root->localName);
    }

    /**
     * @return iterable<string, array{0: class-string, 1: string}>
     */
    public static function profiledModels(): iterable
    {
        yield 'document' => [AuClinicalDocument::class, 'ClinicalDocument'];
        yield 'section'  => [AuSection::class, 'Section'];
        yield 'patient'  => [AuPatient::class, 'Patient'];
        // Upstream publishes au-Place under the *core* HL7 URL rather than the AU one, so nothing
        // about its URL marks it as AU. Only the declared `refines` link classifies it.
        yield 'place published under the core url' => [AuPlace::class, 'Place'];
    }

    /**
     * A definition keeps its own name whenever that name is already an element name — whether it
     * carries no `refines` at all (`IVXB_PQ` must not collapse into its parent `PQ`) or carries one
     * but merely reuses another type as its base instead of profiling it.
     *
     * The first group is what a naive "walk to the base-most #[LogicalModel]" rule breaks; the
     * second is what following `refines` unconditionally breaks.
     *
     * @param class-string $modelClass
     */
    #[DataProvider('modelsThatKeepTheirOwnName')]
    public function testModelsThatAlreadyNameAnElementKeepTheirOwnName(string $modelClass, string $expectedElement): void
    {
        $root = $this->rootElement($this->service()->serializeToXml(new $modelClass()));

        self::assertSame($expectedElement, $root->localName);
    }

    /**
     * @return iterable<string, array{0: class-string, 1: string}>
     */
    public static function modelsThatKeepTheirOwnName(): iterable
    {
        // Core CDA datatypes whose SD name differs from the generated class name, and which derive
        // from a concrete parent (PQ, INT) without refining it.
        yield 'interval boundary' => [IVXBPQ::class, 'IVXB_PQ'];
        yield 'positive integer'  => [INTPOS::class, 'INT_POS'];
        // An AU type with no core counterpart: it extends the abstract root InfrastructureRoot
        // directly, and must not be emitted under that root's name.
        yield 'au nested wrapper' => [AuAsEmployment::class, 'asEmployment'];
        // AU's own `code` type derives from core CE without refining it: its SD names itself in
        // `type`, so it introduces a type and keeps its own element name.
        yield 'au type deriving without refining' => [AuCode::class, 'code'];
        // Carries `refines` — its SD declares `type` as AU's own `asQualifications` — but names an
        // element of its own. `Entity.asQualifiedEntity` and `Person.asQualifications` are two
        // different elements on two different parents, so following the link emits the other one.
        yield 'refines another type but names its own element' => [AuAsQualifiedEntity::class, 'asQualifiedEntity'];
        // Also carries `refines`, pointing at the `II` datatype — a type name that appears as no
        // CDA element. The link must not be followed here either.
        yield 'refines a datatype but names its own element' => [AuTemplateId::class, 'templateId'];
    }

    /**
     * A refinement of a refinement resolves to the type at the end of the chain, not the next one up.
     *
     * Every `refines` link in the shipped CDA packages resolves in a single hop, so no generated
     * class exercises the second iteration of the chain-following loop — including
     * `AuAsQualifiedEntity`, whose target `AuAsQualifications` refines nothing further. The fixtures
     * supply the two-hop case the packages do not, since the loop is what keeps the resolution
     * correct for a package that later publishes such a chain.
     */
    public function testARefinementOfARefinementResolvesToTheEndOfTheChain(): void
    {
        $root = $this->rootElement($this->service()->serializeToXml(new ChainedRefinementLeaf()));

        self::assertSame('ChainedBase', $root->localName);
    }

    /**
     * The intermediate link resolves to the same element name, one hop rather than two.
     *
     * Pins that the leaf's result above comes from walking the chain and not from the resolver
     * happening to land on the base-most `#[LogicalModel]` by another route.
     */
    public function testTheIntermediateRefinementResolvesToTheSameElement(): void
    {
        $root = $this->rootElement($this->service()->serializeToXml(new ChainedRefinementMiddle()));

        self::assertSame('ChainedBase', $root->localName);
    }

    /**
     * The profile identity is invisible on the wire but must survive in the model layer: the
     * document deserializes back to the AU class, not to the core class whose name it now carries.
     */
    public function testProfiledDocumentRoundTripsBackToTheProfileClass(): void
    {
        $service = $this->service();
        $xml     = $service->serializeToXml(new AuClinicalDocument(id: new II(root: '2.16.840.1.113883.19.5')));

        $restored = $service->deserializeFromXml($xml, AuClinicalDocument::class);

        self::assertInstanceOf(AuClinicalDocument::class, $restored);
        self::assertSame('2.16.840.1.113883.19.5', $restored->id?->root);
    }

    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createWithIG(version: FhirVersion::R5);
    }

    private function rootElement(string $xml): \DOMElement
    {
        $document = new \DOMDocument();
        self::assertTrue($document->loadXML($xml), 'Serialized CDA output is not well-formed XML');

        $root = $document->documentElement;
        self::assertInstanceOf(\DOMElement::class, $root);

        return $root;
    }
}
