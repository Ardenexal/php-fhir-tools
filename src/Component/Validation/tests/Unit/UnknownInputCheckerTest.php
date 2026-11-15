<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\UnknownInputChecker;
use PHPUnit\Framework\TestCase;

/**
 * Message texts here are copied from the reference outcomes under
 * vendor/fhir/fhir-test-cases/validator/outcomes/java/, so drifting from that wording fails a test
 * instead of silently producing a finding the reference validator cannot be matched against.
 *
 * These read through the serialization service rather than building a model directly: the checker
 * reports what deserialization recorded, and a constructed object carries none of it.
 */
final class UnknownInputCheckerTest extends TestCase
{
    public function testUnknownJsonPropertyIsReportedWithTheReferenceWording(): void
    {
        self::assertSame(
            ["Unrecognized property 'other'"],
            $this->messagesFor('{"resourceType":"List","id":"val1","other":"nothing","status":"current","mode":"changes"}'),
        );
    }

    /**
     * The JSON type discriminator is not unknown input.
     *
     * It is consumed to choose the class and no model declares it, so it reaches the same
     * unknown-property path a typo does. Left there it reports once for every JSON resource ever
     * validated, which is the widest false positive this rule can produce.
     */
    public function testResourceTypeIsNotReportedAsUnknownInput(): void
    {
        self::assertSame([], $this->messagesFor('{"resourceType":"List","status":"current","mode":"changes"}'));
    }

    public function testUndefinedXmlElementNamesTheElementThatCarriedIt(): void
    {
        self::assertSame(
            ["Undefined element 'mode1' at /f:List"],
            $this->messagesFor(
                '<List xmlns="http://hl7.org/fhir"><id value="val1"/><status value="current"/>'
                . '<mode value="changes"/><mode1 value="changes"/></List>',
            ),
        );
    }

    /**
     * An element FHIR removed is undefined in the version that removed it.
     *
     * `Organization.address` is valid R4 and gone in R5, so the same bytes are clean under R4. The
     * document is not malformed; the version decides.
     */
    public function testAnElementRemovedByTheVersionIsUndefined(): void
    {
        $xml = '<Organization xmlns="http://hl7.org/fhir"><id value="org-2"/><name value="Test organization"/>'
            . '<address><use value="home"/><text value="Org address"/></address></Organization>';

        self::assertSame(["Undefined element 'address' at /f:Organization"], $this->messagesFor($xml));
        self::assertSame([], $this->messagesFor($xml, FhirVersion::R4));
    }

    /**
     * A surviving prefix means wrong namespace, which is a different rule from undefined element.
     *
     * `XmlNamespacePrefixResolver` leaves non-FHIR namespaces prefixed so one is never blessed as
     * valid FHIR. The reference validator calls this a namespace error, so reporting an undefined
     * element instead would produce a finding it cannot be matched against.
     */
    public function testAForeignNamespaceIsReportedAsWrongNamespaceNotAsUndefinedElement(): void
    {
        self::assertSame(
            ["Wrong namespace - expected 'http://hl7.org/fhir'"],
            $this->messagesFor(
                '<f:List xmlns:f="http://hl7.org/fhir"><f1:id xmlns:f1="http://hl7.org/fhir1" value="val1"/>'
                . '<f:status value="current"/><f:mode value="changes"/></f:List>',
            ),
        );
    }

    /**
     * A prefix bound to the FHIR namespace is resolved, so the element is found and nothing is said.
     */
    public function testACorrectlyPrefixedElementIsNotReported(): void
    {
        self::assertSame(
            [],
            $this->messagesFor(
                '<f:List xmlns:f="http://hl7.org/fhir"><f:id value="val1"/><f:status value="current"/>'
                . '<f:mode value="changes"/></f:List>',
            ),
        );
    }

    /**
     * Findings belong to the document they were read from, not to whatever was read last.
     *
     * Records are keyed on the model object rather than accumulated in a shared bucket, so a
     * document read after one carrying unknown input must come back clean. A shared bucket would
     * pass every test above and still report the previous document's findings against this one.
     */
    public function testFindingsDoNotLeakFromOneDocumentToTheNext(): void
    {
        $serialization = FHIRSerializationService::createDefault(FhirVersion::R5);
        $checker       = new UnknownInputChecker();

        $dirty = $serialization->deserialize('{"resourceType":"List","other":"nothing","status":"current","mode":"changes"}');
        $clean = $serialization->deserialize('{"resourceType":"List","status":"current","mode":"changes"}');

        self::assertSame(["Unrecognized property 'other'"], $this->messages($checker->check($dirty)));
        self::assertSame([], $this->messages($checker->check($clean)));
    }

    /**
     * A document that cannot be read leaves nothing behind for the next one.
     *
     * The conformance corpus throws on several cases every run, so this is the ordinary path rather
     * than an edge case.
     */
    public function testAThrowingDocumentDoesNotContaminateTheNextRead(): void
    {
        $serialization = FHIRSerializationService::createDefault(FhirVersion::R5);

        try {
            $serialization->deserialize('{"resourceType":"List","other":"nothing","status":');
        } catch (\Throwable) {
            // The point is what the next read sees, not how this one failed.
        }

        self::assertSame([], $this->messagesFor('{"resourceType":"List","status":"current","mode":"changes"}'));
    }

    /**
     * @param string      $document the raw FHIR document to read
     * @param FhirVersion $version  the version to read it as, which decides what is defined
     *
     * @return list<string> the checker's messages, in the order it reported them
     */
    private function messagesFor(string $document, FhirVersion $version = FhirVersion::R5): array
    {
        $resource = FHIRSerializationService::createDefault($version)->deserialize($document);

        self::assertIsObject($resource);

        return $this->messages((new UnknownInputChecker())->check($resource));
    }

    /**
     * @param list<FHIRValidationViolation> $violations the checker's output
     *
     * @return list<string> just the messages, which is what pairs against the reference outcomes
     */
    private function messages(array $violations): array
    {
        return array_map(static fn (FHIRValidationViolation $v): string => $v->message, $violations);
    }
}
