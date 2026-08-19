<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Xml\XmlDoctypeGuard;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * A DOCTYPE declaration must be refused before any parser sees the document.
 *
 * FHIR XML has no legitimate use for a DOCTYPE and the HL7 Java reference validator refuses one
 * outright. Before this guard existed our refusal was accidental: `list-xhtml-xxe1.xml` failed with
 * `Invalid URI: file://c:\temp\xxe.txt`, which is libxml reporting that it *resolved* the external
 * entity's system identifier and objected only to the URI being malformed. A well-formed path would
 * have been attempted, because `LIBXML_NONET` blocks network retrieval but not local `file://`
 * entities, and omitting `LIBXML_NOENT` prevents entity *substitution* rather than resolution.
 *
 * Every deserialization entry point is covered here rather than just the auto-detecting one, since
 * `deserializeFromXml()` is public API a caller can reach directly.
 */
final class XmlDoctypeRejectionTest extends TestCase
{
    private const XXE = <<<'XML'
        <?xml version="1.0" encoding="UTF-8"?>
        <!DOCTYPE foo [ <!ENTITY xxe SYSTEM "file:///etc/passwd"> ]>
        <Patient xmlns="http://hl7.org/fhir">
          <id value="&xxe;"/>
        </Patient>
        XML;

    public function testAutoDetectingDeserializeRejectsDoctype(): void
    {
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);

        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessageMatches('/DOCTYPE declarations are not accepted/');

        $service->deserialize(self::XXE);
    }

    public function testDirectXmlDeserializeRejectsDoctype(): void
    {
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);

        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessageMatches('/DOCTYPE declarations are not accepted/');

        $service->deserializeFromXml(
            self::XXE,
            PatientResource::class,
        );
    }

    /**
     * The entity must never be resolved. Before the guard, the failure message named the system
     * identifier — proof that libxml had already reached for it.
     */
    public function testRejectionMessageDoesNotNameTheSystemIdentifier(): void
    {
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);

        try {
            $service->deserialize(self::XXE);
            self::fail('Expected a DOCTYPE rejection.');
        } catch (FHIRSerializationException $e) {
            self::assertStringNotContainsString('/etc/passwd', $e->getMessage());
            self::assertStringNotContainsString('Invalid URI', $e->getMessage());
        }
    }

    /**
     * A BOM, an XML declaration, comments and processing instructions may all legally precede the
     * DOCTYPE, so the prolog walk has to step over them rather than only checking offset zero.
     */
    public function testDoctypeIsFoundBehindProlog(): void
    {
        $xml = "\xEF\xBB\xBF" . '<?xml version="1.0"?>' . "\n"
            . "<!-- a comment -->\n"
            . "<?target instruction?>\n"
            . '<!DOCTYPE foo><Patient xmlns="http://hl7.org/fhir"/>';

        $this->expectException(FHIRSerializationException::class);

        XmlDoctypeGuard::assertNoDoctype($xml);
    }

    /**
     * The scan is confined to the prolog. A document whose narrative merely contains the literal
     * text `<!DOCTYPE` is valid FHIR and must still deserialize — a whole-document scan would
     * reject it.
     */
    public function testLiteralDoctypeTextInNarrativeIsNotRejected(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<Patient xmlns="http://hl7.org/fhir">'
            . '<text><status value="generated"/>'
            . '<div xmlns="http://www.w3.org/1999/xhtml"><p>&lt;!DOCTYPE html&gt; starts a page</p></div>'
            . '</text>'
            . '</Patient>';

        XmlDoctypeGuard::assertNoDoctype($xml);

        $service  = FHIRSerializationService::createDefault(FhirVersion::R4);
        $resource = $service->deserialize($xml);

        self::assertInstanceOf(PatientResource::class, $resource);
    }

    public function testOrdinaryDocumentPasses(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><Patient xmlns="http://hl7.org/fhir"><id value="x"/></Patient>';

        XmlDoctypeGuard::assertNoDoctype($xml);

        self::assertTrue(true, 'A document with no DOCTYPE is accepted.');
    }

    /**
     * The prolog walk compares bytes, so in UTF-16 `<!DOCTYPE` never matches and the scan reports a
     * clean document. Measured before `assertUtf8()` existed: the same payload that UTF-8 refuses
     * reached libxml, which got as far as `Attribute references external entity`. The encoding is
     * therefore settled first, and these payloads are refused rather than transcoded — re-encoding
     * would leave `deserializeFromXml()` accepting documents `deserialize()` rejects.
     *
     * BOM-less UTF-16 is included deliberately: the byte order marks are not the only signal, since
     * a NUL byte lands within the first few bytes either way.
     *
     * @return iterable<string, array{string}>
     */
    public static function nonUtf8Encodings(): iterable
    {
        yield 'UTF-16LE with BOM'    => ["\xFF\xFE" . mb_convert_encoding(self::XXE, 'UTF-16LE', 'UTF-8')];
        yield 'UTF-16BE with BOM'    => ["\xFE\xFF" . mb_convert_encoding(self::XXE, 'UTF-16BE', 'UTF-8')];
        yield 'UTF-16LE without BOM' => [mb_convert_encoding(self::XXE, 'UTF-16LE', 'UTF-8')];
        yield 'UTF-32LE with BOM'    => ["\xFF\xFE\x00\x00" . mb_convert_encoding(self::XXE, 'UTF-32LE', 'UTF-8')];
    }

    #[DataProvider('nonUtf8Encodings')]
    public function testNonUtf8DoctypePayloadIsRefusedBeforeAnyParser(string $xml): void
    {
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);

        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessageMatches('/must be UTF-8 encoded/');

        $service->deserializeFromXml($xml, PatientResource::class);
    }

    /**
     * Mirrors {@see testRejectionMessageDoesNotNameTheSystemIdentifier} for the encoding branch: if
     * the message ever names the entity, libxml reached for it and the refusal came too late.
     */
    #[DataProvider('nonUtf8Encodings')]
    public function testNonUtf8RejectionDoesNotNameTheSystemIdentifier(string $xml): void
    {
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);

        try {
            $service->deserializeFromXml($xml, PatientResource::class);
            self::fail('Expected a non-UTF-8 rejection.');
        } catch (FHIRSerializationException $e) {
            self::assertStringNotContainsString('/etc/passwd', $e->getMessage());
            self::assertStringNotContainsString('external entity', $e->getMessage());
        }
    }

    /**
     * The encoding check must not cost us ordinary documents. A UTF-8 BOM is legal and 15 corpus
     * files carry one, so it has to survive a rule aimed at UTF-16/32 byte order marks.
     */
    public function testUtf8DocumentsAreNotMistakenForUtf16(): void
    {
        $xml     = '<?xml version="1.0" encoding="UTF-8"?><Patient xmlns="http://hl7.org/fhir"><id value="x"/></Patient>';
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);

        XmlDoctypeGuard::assertUtf8($xml);
        XmlDoctypeGuard::assertUtf8("\xEF\xBB\xBF" . $xml);

        self::assertInstanceOf(
            PatientResource::class,
            $service->deserializeFromXml("\xEF\xBB\xBF" . $xml, PatientResource::class),
        );
    }
}
