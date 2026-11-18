<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Validation\BundleReachabilityChecker;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Each expected message is the HL7 Java reference validator's sentence, so the two findings pair by
 * text. A test shaped on a vendored `fhir-test-cases` case names that case.
 */
final class BundleReachabilityCheckerTest extends TestCase
{
    private const string ORPHAN = "Entry %s isn't reachable by traversing links (forward or backward) from the Composition";

    /** Forward links from the Composition, and links onward from what it reaches, reach everything. */
    public function testEntriesReachedForwardsAreSilent(): void
    {
        self::assertSame([], $this->findings($this->bundle('document', [
            ['urn:uuid:c', ['resourceType' => 'Composition', 'subject' => ['reference' => 'urn:uuid:p']]],
            ['urn:uuid:p', ['resourceType' => 'Patient', 'generalPractitioner' => [['reference' => 'urn:uuid:o']]]],
            ['urn:uuid:o', ['resourceType' => 'Organization']],
        ])));
    }

    /** `document-css`: an entry nothing links to is an error naming its fullUrl. */
    public function testOrphanInADocumentIsAnError(): void
    {
        self::assertSame(
            [['error', 'entry[2]', sprintf(self::ORPHAN, "'http://hl7.org/fhir/test/Binary/css1'")]],
            $this->findings($this->bundle('document', [
                ['http://hl7.org/fhir/test/Composition/1', ['resourceType' => 'Composition', 'id' => '1', 'subject' => ['reference' => 'Patient/1']]],
                ['http://hl7.org/fhir/test/Patient/1', ['resourceType' => 'Patient', 'id' => '1']],
                ['http://hl7.org/fhir/test/Binary/css1', ['resourceType' => 'Binary', 'id' => 'css1', 'contentType' => 'text/css']],
            ])),
        );
    }

    /**
     * `bundle-urn`: a relative reference under a `urn:uuid:` fullUrl has no base, so it links nowhere and
     * its target is an orphan even though a resource of that type and id is present.
     */
    public function testRelativeReferenceUnderAUrnFullUrlLinksNowhere(): void
    {
        self::assertSame(
            [['error', 'entry[1]', sprintf(self::ORPHAN, "'urn:uuid:p'")]],
            $this->findings($this->bundle('document', [
                ['urn:uuid:c', ['resourceType' => 'Composition', 'subject' => ['reference' => 'Patient/1']]],
                ['urn:uuid:p', ['resourceType' => 'Patient', 'id' => '1']],
            ])),
        );
    }

    /**
     * `mni-patientOverview-bundle-example1` against `…example1b`: relative fullUrls give an empty base,
     * which resolves only while the Composition's id agrees with its own fullUrl.
     */
    #[DataProvider('compositionIds')]
    public function testEmptyBaseResolvesOnlyWhenTheSourceIdAgrees(string $compositionId, bool $expectOrphan): void
    {
        $findings = $this->findings($this->bundle('document', [
            ['Composition/1', ['resourceType' => 'Composition', 'id' => $compositionId, 'subject' => ['reference' => 'Patient/1']]],
            ['Patient/1', ['resourceType' => 'Patient', 'id' => '1']],
        ]));

        self::assertSame($expectOrphan ? [['error', 'entry[1]', sprintf(self::ORPHAN, "'Patient/1'")]] : [], $findings);
    }

    /** @return iterable<string, array{string, bool}> */
    public static function compositionIds(): iterable
    {
        yield 'example1: id matches fullUrl' => ['1', false];
        yield 'example1b: id disagrees' => ['1a', true];
    }

    /**
     * `bundle-duplicate-ids-not` (`RelatedPerson/1`): an entry reached only because it points at the
     * document is a warning, and what it leads to counts as reached.
     */
    public function testEntryReachedOnlyBackwardsIsAWarningInADocument(): void
    {
        self::assertSame(
            [['warning', 'entry[2]', "Entry 'urn:uuid:obs' isn't reachable by traversing forwards from the Composition. Only Provenance is approved to be used this way (R4 section 3.3.1)"]],
            $this->findings($this->bundle('document', [
                ['urn:uuid:c', ['resourceType' => 'Composition', 'subject' => ['reference' => 'urn:uuid:p']]],
                ['urn:uuid:p', ['resourceType' => 'Patient']],
                ['urn:uuid:obs', ['resourceType' => 'Observation', 'status' => 'final', 'code' => ['text' => 'x'], 'subject' => ['reference' => 'urn:uuid:p'], 'performer' => [['reference' => 'urn:uuid:o']]]],
                ['urn:uuid:o', ['resourceType' => 'Organization']],
            ])),
        );
    }

    /**
     * Pointing back at what it describes is what a Provenance is for, so the spec approves it. `uk-msg`
     * shows the reference validator exempts it in a message too.
     */
    #[DataProvider('anchors')]
    public function testProvenanceReachedBackwardsIsSilent(string $type, array $anchor): void
    {
        self::assertSame([], $this->findings($this->bundle($type, [
            ['urn:uuid:c', $anchor],
            ['urn:uuid:prov', ['resourceType' => 'Provenance', 'recorded' => '2020-01-01T00:00:00Z', 'target' => [['reference' => 'urn:uuid:c']], 'agent' => [['who' => ['display' => 'x']]]]],
        ])));
    }

    /** @return iterable<string, array{string, array<string, mixed>}> */
    public static function anchors(): iterable
    {
        yield 'document' => ['document', ['resourceType' => 'Composition']];
        yield 'message' => ['message', ['resourceType' => 'MessageHeader', 'eventCoding' => ['code' => 'x'], 'source' => ['endpoint' => 'http://x']]];
    }

    /**
     * The MedicationStatement in `bundle-urn`: an absolute value with a `#fragment` points into the entry
     * before the `#`, here back at the Composition, so the entry is reached backwards rather than orphaned.
     */
    public function testAbsoluteValueWithAFragmentLinksToItsEntry(): void
    {
        self::assertSame(
            [['warning', 'entry[1]', "Entry 'urn:uuid:m' isn't reachable by traversing forwards from the Composition. Only Provenance is approved to be used this way (R4 section 3.3.1)"]],
            $this->findings($this->bundle('document', [
                ['urn:uuid:c', ['resourceType' => 'Composition']],
                ['urn:uuid:m', ['resourceType' => 'Patient', 'extension' => [['url' => 'http://example.org/narrativeLink', 'valueUri' => 'urn:uuid:c#row-1']]]],
            ])),
        );
    }

    /**
     * `document-css`: a `stylesheet` link reaches its Binary by the tail of the fullUrl, even under a
     * Composition whose id gives no base. A Binary nothing names is still an orphan.
     */
    public function testStylesheetLinkReachesItsBinary(): void
    {
        $json = [
            'resourceType' => 'Bundle',
            'type'         => 'document',
            'link'         => [['relation' => 'stylesheet', 'url' => 'Binary/css'], ['relation' => 'self', 'url' => 'Binary/other']],
            'entry'        => [
                ['fullUrl' => 'http://hl7.org/fhir/test/Composition/doc', 'resource' => ['resourceType' => 'Composition', 'id' => 'not-doc']],
                ['fullUrl' => 'http://hl7.org/fhir/test/Binary/css', 'resource' => ['resourceType' => 'Binary', 'contentType' => 'text/css']],
                ['fullUrl' => 'http://hl7.org/fhir/test/Binary/other', 'resource' => ['resourceType' => 'Binary', 'contentType' => 'text/css']],
            ],
        ];

        self::assertSame(
            [['error', 'entry[2]', sprintf(self::ORPHAN, "'http://hl7.org/fhir/test/Binary/other'")]],
            $this->findings(FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize(json_encode($json, JSON_THROW_ON_ERROR))),
        );
    }

    /** `message` and `bundle-with-contained`: messages report the same walk one severity lower. */
    public function testMessageFindingsUseTheMessageHeaderWording(): void
    {
        self::assertSame(
            [
                ['info', 'entry[2]', "Entry 'urn:uuid:back' isn't reachable by traversing forwards from the MessageHeader. Check that this is meant to be included (needed to process the message)"],
                ['warning', 'entry[3]', "Entry 'urn:uuid:orphan' isn't reachable by traversing links (forward or backward) from the MessageHeader, so its presence should be reviewed (is it needed to process the message?)"],
            ],
            $this->findings($this->bundle('message', [
                ['urn:uuid:h', ['resourceType' => 'MessageHeader', 'eventCoding' => ['code' => 'x'], 'source' => ['endpoint' => 'http://x'], 'focus' => [['reference' => 'urn:uuid:p']]]],
                ['urn:uuid:p', ['resourceType' => 'Patient']],
                ['urn:uuid:back', ['resourceType' => 'Patient', 'link' => [['type' => 'seealso', 'other' => ['reference' => 'urn:uuid:p']]]]],
                ['urn:uuid:orphan', ['resourceType' => 'Organization']],
            ])),
        );
    }

    /**
     * `japanese-utf8-ok`: an entry with no fullUrl is named by nothing at all, leaving the reference
     * validator's double space, and a bare id is not a reference that resolves.
     */
    public function testEntryWithoutAFullUrlIsNamedByNothing(): void
    {
        self::assertSame(
            [
                ['error', 'entry[1]', sprintf(self::ORPHAN, "'c282c3ec'")],
                ['error', 'entry[2]', sprintf(self::ORPHAN, '')],
            ],
            $this->findings($this->bundle('document', [
                ['urn:uuid:c', ['resourceType' => 'Composition', 'subject' => ['reference' => 'c282c3ec']]],
                ['c282c3ec', ['resourceType' => 'Patient', 'id' => 'c282c3ec']],
                [null, ['resourceType' => 'Organization']],
            ])),
        );
    }

    /**
     * `bundle-document-versioned-references-bad`: two entries share one fullUrl, so a reference to it
     * links to neither. A `_history` suffix that matches one entry's versionId links to that one.
     */
    public function testAmbiguousFullUrlLinksToNeitherUnlessAVersionPicksOne(): void
    {
        $entries = static fn (string $reference): array => [
            ['http://example.org/Composition/c', ['resourceType' => 'Composition', 'id' => 'c', 'subject' => ['reference' => $reference]]],
            ['http://example.org/Patient/p', ['resourceType' => 'Patient', 'id' => 'p', 'meta' => ['versionId' => '1']]],
            ['http://example.org/Patient/p', ['resourceType' => 'Patient', 'id' => 'p', 'meta' => ['versionId' => '2']]],
        ];

        self::assertSame(
            [
                ['error', 'entry[1]', sprintf(self::ORPHAN, "'http://example.org/Patient/p'")],
                ['error', 'entry[2]', sprintf(self::ORPHAN, "'http://example.org/Patient/p'")],
            ],
            $this->findings($this->bundle('document', $entries('Patient/p'))),
        );

        self::assertSame(
            [['error', 'entry[1]', sprintf(self::ORPHAN, "'http://example.org/Patient/p'")]],
            $this->findings($this->bundle('document', $entries('Patient/p/_history/2'))),
        );
    }

    /** A `uri` value links like a Reference does, which is how the reference validator reads it too. */
    public function testUriValueIsALink(): void
    {
        self::assertSame([], $this->findings($this->bundle('document', [
            ['urn:uuid:c', ['resourceType' => 'Composition', 'section' => [['entry' => [['reference' => 'urn:uuid:d']]]]]],
            ['urn:uuid:d', ['resourceType' => 'DocumentReference', 'status' => 'current', 'content' => [['attachment' => ['url' => 'urn:uuid:b']]]]],
            ['urn:uuid:b', ['resourceType' => 'Binary', 'contentType' => 'text/plain']],
        ])));
    }

    /** References inside `contained` link too: the reference validator walks the whole entry. */
    public function testReferenceInsideContainedIsALink(): void
    {
        self::assertSame([], $this->findings($this->bundle('document', [
            ['urn:uuid:c', ['resourceType' => 'Composition', 'contained' => [['resourceType' => 'List', 'id' => 'l', 'status' => 'current', 'mode' => 'working', 'entry' => [['item' => ['reference' => 'urn:uuid:p']]]]]]],
            ['urn:uuid:p', ['resourceType' => 'Patient']],
        ])));
    }

    /** Two entries that link to each other but that nothing reaches are both orphans, and the walk ends. */
    public function testOrphanedCycleIsReportedAndTerminates(): void
    {
        self::assertSame(
            [
                ['error', 'entry[1]', sprintf(self::ORPHAN, "'urn:uuid:a'")],
                ['error', 'entry[2]', sprintf(self::ORPHAN, "'urn:uuid:b'")],
            ],
            $this->findings($this->bundle('document', [
                ['urn:uuid:c', ['resourceType' => 'Composition']],
                ['urn:uuid:a', ['resourceType' => 'Patient', 'link' => [['type' => 'seealso', 'other' => ['reference' => 'urn:uuid:b']]]]],
                ['urn:uuid:b', ['resourceType' => 'Patient', 'link' => [['type' => 'seealso', 'other' => ['reference' => 'urn:uuid:a']]]]],
            ])),
        );
    }

    /** A link from an entry to itself reaches nothing new, so it cannot rescue the entry. */
    public function testSelfLinkDoesNotReachTheEntry(): void
    {
        self::assertSame(
            [['error', 'entry[1]', sprintf(self::ORPHAN, "'urn:uuid:p'")]],
            $this->findings($this->bundle('document', [
                ['urn:uuid:c', ['resourceType' => 'Composition']],
                ['urn:uuid:p', ['resourceType' => 'Patient', 'link' => [['type' => 'seealso', 'other' => ['reference' => 'urn:uuid:p']]]]],
            ])),
        );
    }

    /**
     * The backwards step runs whole passes until one adds nothing, like the reference validator. Entry 1
     * points only at entry 2, which itself joins backwards later in the first pass, so entry 1 joins in
     * the second pass: both are warnings, never an error.
     */
    public function testBackwardsStepRepeatsUntilNothingIsAdded(): void
    {
        $warning = "Entry '%s' isn't reachable by traversing forwards from the Composition. Only Provenance is approved to be used this way (R4 section 3.3.1)";

        self::assertSame(
            [
                ['warning', 'entry[2]', sprintf($warning, 'urn:uuid:b')],
                ['warning', 'entry[1]', sprintf($warning, 'urn:uuid:a')],
            ],
            $this->findings($this->bundle('document', [
                ['urn:uuid:c', ['resourceType' => 'Composition']],
                ['urn:uuid:a', ['resourceType' => 'Patient', 'link' => [['type' => 'seealso', 'other' => ['reference' => 'urn:uuid:b']]]]],
                ['urn:uuid:b', ['resourceType' => 'Patient', 'link' => [['type' => 'seealso', 'other' => ['reference' => 'urn:uuid:c']]]]],
            ])),
        );
    }

    /** Only documents and messages are walked, and only from the resource each must open with. */
    #[DataProvider('unwalkedBundles')]
    public function testOtherBundlesAreNotWalked(string $type, string $firstResource): void
    {
        self::assertSame([], $this->findings($this->bundle($type, [
            ['urn:uuid:a', ['resourceType' => $firstResource]],
            ['urn:uuid:b', ['resourceType' => 'Patient']],
        ])));
    }

    /** @return iterable<string, array{string, string}> */
    public static function unwalkedBundles(): iterable
    {
        yield 'collection' => ['collection', 'Composition'];
        yield 'transaction' => ['transaction', 'Composition'];
        yield 'document opening with a Patient' => ['document', 'Patient'];
        yield 'message opening with a Composition' => ['message', 'Composition'];
    }

    /** A resource that is not a Bundle is not this rule's business. */
    public function testNonBundleIsSilent(): void
    {
        $patient = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize('{"resourceType":"Patient"}');

        self::assertSame([], (new BundleReachabilityChecker())->check($patient));
    }

    /**
     * @param list<array{string|null, array<string, mixed>}> $entries fullUrl and resource per entry
     */
    private function bundle(string $type, array $entries): object
    {
        $json = [
            'resourceType' => 'Bundle',
            'type'         => $type,
            'entry'        => array_map(
                static fn (array $entry): array => array_filter(
                    ['fullUrl' => $entry[0], 'resource' => $entry[1]],
                    static fn (mixed $value): bool => $value !== null,
                ),
                $entries,
            ),
        ];

        return FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize(json_encode($json, JSON_THROW_ON_ERROR));
    }

    /** @return list<array{string, string, string}> severity, path and message per finding */
    private function findings(object $bundle): array
    {
        return array_map(
            static fn (FHIRValidationViolation $violation): array => [$violation->severity, $violation->path, $violation->message],
            (new BundleReachabilityChecker())->check($bundle),
        );
    }
}
