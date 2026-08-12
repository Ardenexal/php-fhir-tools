<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Xml\XmlDoctypeGuard;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;

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
}
