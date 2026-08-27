<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\ClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Component;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Section;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\StructuredBody;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\StructuredBodyComponent;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\XhtmlPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ListResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\NarrativeXhtmlChecker;
use PHPUnit\Framework\TestCase;

/**
 * Message texts here are copied from the Java reference outcomes in
 * vendor/fhir/fhir-test-cases/validator/outcomes/java/, so a drift in our wording fails the test
 * rather than quietly turning a matching case into a differently-worded one.
 */
final class NarrativeXhtmlCheckerTest extends TestCase
{
    private const string TXT_1 = "Constraint failed: txt-1: 'The narrative SHALL contain only the basic html formatting elements and attributes described in chapters 7-11 (except section 4 of chapter 9) and 15 of the HTML 4.0 standard, <a> elements (either name or href), images and internally contained style attributes' (defined in http://hl7.org/fhir/StructureDefinition/Narrative)";

    private const string TXT_2 = "Constraint failed: txt-2: 'The narrative SHALL have some non-whitespace content' (defined in http://hl7.org/fhir/StructureDefinition/Narrative)";

    public function testCleanNarrativeIsAccepted(): void
    {
        self::assertSame([], $this->messagesFor('<div><p>This is some narrative</p></div>'));
    }

    public function testInvalidElementReportsTheElementAndTxt1(): void
    {
        self::assertSame(
            [
                self::TXT_1,
                "Invalid element name in the XHTML ('object')",
                "Invalid attribute name in the XHTML ('value' on 'object')",
            ],
            $this->messagesFor('<div><p>This is some narrative</p><object value="false"/></div>'),
        );
    }

    public function testInvalidAttributeReportsTheAttributeAndTxt1(): void
    {
        self::assertSame(
            [
                self::TXT_1,
                "Invalid attribute name in the XHTML ('onClick' on 'p')",
            ],
            $this->messagesFor('<div><p onClick="check">This is some narrative</p></div>'),
        );
    }

    public function testDoctypeIsRefusedWithoutTxt2(): void
    {
        self::assertSame(
            [
                self::TXT_1,
                'Malformed XHTML: Found a DocType declaration, and these are not allowed (XXE security vulnerability protection)',
            ],
            $this->messagesFor('<div xmlns="http://www.w3.org/1999/xhtml"><!DOCTYPE foo [ <!ENTITY xxe SYSTEM "file:///etc/passwd">]><p>x &xxe;</p></div>'),
        );
    }

    public function testParseFailureAlsoReportsTxt1AndTxt2(): void
    {
        $messages = $this->messagesFor('<div xmlns="http://www.w3.org/1999/xhtml"><p>This is some narrative</pa></div>');

        self::assertCount(3, $messages);
        self::assertStringStartsWith('Error parsing XHTML: Malformed XHTML: ', $messages[0]);
        self::assertSame(self::TXT_1, $messages[1]);
        self::assertSame(self::TXT_2, $messages[2]);
    }

    public function testUndefinedEntityIsReportedWithoutEitherInvariant(): void
    {
        self::assertSame(
            ["Invalid entity in the XHTML ('&reg;')"],
            $this->messagesFor('<div xmlns="http://www.w3.org/1999/xhtml"><p>CPT&reg;</p></div>'),
        );
    }

    public function testBlockElementInsideAParagraphIsReportedWithoutTxt1(): void
    {
        self::assertSame(
            ["Invalid element name inside a paragraph in the XHTML ('p')"],
            $this->messagesFor('<div><p>outer<p>nested Paragraph</p></p></div>'),
        );
    }

    public function testInlineElementInsideAParagraphIsAccepted(): void
    {
        self::assertSame([], $this->messagesFor('<div><p><b>bold</b> <a href="x.html">link</a> <span style="color:red">x</span></p></div>'));
    }

    public function testXmlNamespacedAttributesAreAccepted(): void
    {
        self::assertSame([], $this->messagesFor('<div xml:lang="en"><pre xml:space="preserve">x</pre></div>'));
    }

    public function testPrefixedElementIsLeftToTheSerializationLayer(): void
    {
        // The XML pipeline discards the prefix binding, so nothing here can judge it — and guessing
        // would fail correct documents. Skipped rather than reported.
        self::assertSame([], $this->messagesFor('<div><n:p>This is some narrative</n:p></div>'));
    }

    public function testTextWithoutMarkupIsNotTreatedAsMalformed(): void
    {
        // The XML normalizer strips the <div> wrapper off a text-only narrative.
        self::assertSame([], $this->messagesFor("\n      some text in no particular language\n    "));
    }

    public function testViolationIsLocatedOnTheDivProperty(): void
    {
        $violations = $this->violationsFor('<div><p onClick="check">x</p></div>');

        self::assertSame('text.div', $violations[0]->path);
        self::assertSame('error', $violations[0]->severity);
        self::assertSame('txt-1', $violations[0]->invariantKey);
        self::assertNull($violations[1]->invariantKey);
    }

    /**
     * A CDA section's narrative is a StrucDoc markup tree, not XHTML: it carries several top-level
     * nodes instead of one wrapping div, and `paragraph`/`content` are not HTML 4.0 elements. Judged
     * by FHIR's rules, a valid CDA narrative drew three errors — a spurious malformed-XHTML report
     * caused by the extra top-level nodes, txt-1 for `paragraph`, and txt-2 claiming there was no
     * content at all. Logical models are skipped so the checker cannot reject valid CDA.
     */
    public function testCdaNarrativeIsNotJudgedByFhirNarrativeRules(): void
    {
        $document = new ClinicalDocument(
            id: new II(root: '1.2.3'),
            component: new Component(
                structuredBody: new StructuredBody(
                    component: [new StructuredBodyComponent(section: new Section(
                        text: '<paragraph>Current medications</paragraph>'
                            . '<content styleCode="Bold">Review in 7 days</content>',
                    ))],
                ),
            ),
        );

        self::assertSame([], (new NarrativeXhtmlChecker())->check($document));
    }

    /** @return list<string> */
    private function messagesFor(string $div): array
    {
        return array_map(
            static fn (FHIRValidationViolation $v): string => $v->message,
            $this->violationsFor($div),
        );
    }

    /** @return list<FHIRValidationViolation> */
    private function violationsFor(string $div): array
    {
        $resource       = new ListResource();
        $resource->text = new Narrative(status: null, div: new XhtmlPrimitive(value: $div));

        return (new NarrativeXhtmlChecker())->check($resource);
    }
}
