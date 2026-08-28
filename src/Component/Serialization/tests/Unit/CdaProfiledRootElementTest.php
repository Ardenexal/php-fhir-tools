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
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\INTPOS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVXBPQ;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * A profiled CDA logical model must not rename its own XML element.
 *
 * `#[LogicalModel]`'s `name` is the StructureDefinition name, which for a definition that refines
 * another type is a profile identifier (`au-ClinicalDocument`) that was never an element name — no
 * CDA schema, schematron or consumer accepts `<au-ClinicalDocument>`. The element name is the
 * refined type's, reached by following the generated `refines` link.
 *
 * Every assertion here reads `local-name(/*)` off the parsed document rather than searching the
 * serialized string: `au-ClinicalDocument` *contains* `ClinicalDocument`, so a substring assertion
 * passes against the very output this test exists to reject.
 *
 * The cases are chosen to pin the resolution to the StructureDefinition's declared `refines` link
 * rather than to the shape of the URL or the name. `AuPlace` and `AuCode` are the two that decide
 * this: inferring the refinement from the URL authority gets both wrong, in opposite directions.
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
        // A refinement of a refinement: asQualifiedEntity refines AU's own asQualifications, so the
        // chain has to be followed past the first hop rather than stopping at it.
        yield 'refinement of a refinement' => [AuAsQualifiedEntity::class, 'asQualifications'];
    }

    /**
     * A definition that introduces a type of its own carries no `refines` and keeps its own name,
     * even where it derives from another type: `IVXB_PQ` must not collapse into its parent `PQ`.
     * These are the regressions a naive "walk to the base-most #[LogicalModel]" rule ships.
     *
     * @param class-string $modelClass
     */
    #[DataProvider('unrefinedModels')]
    public function testTypesThatRefineNothingKeepTheirOwnName(string $modelClass, string $expectedElement): void
    {
        $root = $this->rootElement($this->service()->serializeToXml(new $modelClass()));

        self::assertSame($expectedElement, $root->localName);
    }

    /**
     * @return iterable<string, array{0: class-string, 1: string}>
     */
    public static function unrefinedModels(): iterable
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
