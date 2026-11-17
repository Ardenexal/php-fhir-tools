<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\BundleTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\XhtmlPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition\CompositionSection;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CompositionResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ListResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequestResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ProvenanceResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Validation\ContainedReferenceChecker;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use PHPUnit\Framework\TestCase;

/**
 * Each case is the shape of a vendored corpus fixture, named in the test that carries it. The
 * expected sentences are the reference validator's, which is how the comparison pairs them.
 */
final class ContainedReferenceCheckerTest extends TestCase
{
    /** `hakan-se`: `#1505…` points into a `contained` list the resource does not have. */
    public function testFragmentWithNoContainedResourceIsReported(): void
    {
        $request = new MedicationRequestResource(requester: new Reference(reference: '#1505724e'));

        $violations = (new ContainedReferenceChecker())->check($request);

        self::assertCount(1, $violations);
        self::assertSame("Unable to resolve resource with reference '#1505724e'", $violations[0]->message);
        self::assertSame('requester', $violations[0]->path);
    }

    /**
     * `list-contained-bad` (R5): `#pat` names no contained resource, and the one that is there, `pat1`,
     * is referred to by nothing. Two findings, on the reference and on the contained resource.
     */
    public function testUnresolvedFragmentAndUnreferencedContainedAreBothReported(): void
    {
        $list = new ListResource(
            contained: [new PatientResource(id: 'pat1')],
            subject: new Reference(reference: '#pat'),
        );

        self::assertSame(
            [
                ['subject', "Unable to resolve resource with reference '#pat'"],
                ['contained[0]', "The contained resource 'pat1' is not referenced to from elsewhere in the containing resource nor does it refer to the containing resource (dom-3)"],
            ],
            $this->findings($list),
        );
    }

    /** The ordinary case: one contained resource, referred to once. */
    public function testReferencedContainedResourceIsSilent(): void
    {
        $list = new ListResource(
            contained: [new PatientResource(id: 'pat1')],
            subject: new Reference(reference: '#pat1'),
        );

        self::assertSame([], $this->findings($list));
    }

    /** `#` on its own is the containing resource, which is always there, and refers back to it. */
    public function testContainedResourceReferringToItsContainerIsSilent(): void
    {
        $request = new MedicationRequestResource(
            contained: [new ProvenanceResource(id: 'prov', target: [new Reference(reference: '#')])],
        );

        self::assertSame([], $this->findings($request));
    }

    /** A canonical counts as a reference: a ValueSet may point at the CodeSystem it contains. */
    public function testCanonicalFragmentCountsAsAReference(): void
    {
        $questionnaire = new QuestionnaireResource(
            contained: [new PatientResource(id: 'cs')],
            derivedFrom: [new CanonicalPrimitive(value: '#cs')],
        );

        self::assertSame([], $this->findings($questionnaire));
    }

    /**
     * Narrative may be the only thing pointing at a contained resource (`<img src="#pic1"/>`), and the
     * reference validator accepts that. See FHIRPathInvariantValidator::narrativeAccountsForContained().
     */
    public function testNarrativeFragmentCountsAsAReference(): void
    {
        $patient = new PatientResource(
            text: new Narrative(div: new XhtmlPrimitive(value: '<div xmlns="http://www.w3.org/1999/xhtml"><img src="#pic1"/></div>')),
            contained: [new PatientResource(id: 'pic1')],
        );

        self::assertSame([], $this->findings($patient));
    }

    /**
     * `binary-ref-internal`: the only pointer to a contained Binary is an `<img>` in a Composition
     * section's narrative, not the resource's own. Any narrative in the container counts.
     */
    public function testSectionNarrativeFragmentCountsAsAReference(): void
    {
        $composition = new CompositionResource(
            contained: [new PatientResource(id: 'imageResource')],
            section: [new CompositionSection(text: new Narrative(div: new XhtmlPrimitive(value: '<div xmlns="http://www.w3.org/1999/xhtml"><img src="#imageResource" alt="x"/></div>')))],
        );

        self::assertSame([], $this->findings($composition));
    }

    /** `patient-duplicate-contained` (R5): a contained resource without an id cannot be referred to. */
    public function testContainedResourceWithoutAnIdIsReported(): void
    {
        $patient = new PatientResource(contained: [new PatientResource()]);

        self::assertSame(
            [['contained[0]', "The contained resource '(no id)' is not referenced to from elsewhere in the containing resource nor does it refer to the containing resource (dom-3)"]],
            $this->findings($patient),
        );
    }

    /**
     * Each Bundle entry is its own containing resource. A fragment in one entry never resolves against
     * another entry's `contained`, and the path carries the entry.
     */
    public function testEachBundleEntryIsItsOwnContainer(): void
    {
        $bundle = new BundleResource(
            type: new BundleTypeType(value: 'collection'),
            entry: [
                new BundleEntry(
                    fullUrl: new UriPrimitive(value: 'urn:uuid:1'),
                    resource: new ListResource(contained: [new PatientResource(id: 'p')], subject: new Reference(reference: '#p')),
                ),
                new BundleEntry(
                    fullUrl: new UriPrimitive(value: 'urn:uuid:2'),
                    resource: new ListResource(subject: new Reference(reference: '#p')),
                ),
            ],
        );

        self::assertSame(
            [['entry[1].resource.subject', "Unable to resolve resource with reference '#p'"]],
            $this->findings($bundle),
        );
    }

    /** The dom-3 finding carries the invariant key, so it reads as that invariant in a report. */
    public function testUnreferencedContainedCarriesTheInvariantKey(): void
    {
        $violations = (new ContainedReferenceChecker())->check(new PatientResource(contained: [new PatientResource(id: 'x')]));

        self::assertCount(1, $violations);
        self::assertSame('dom-3', $violations[0]->invariantKey);
        self::assertSame('error', $violations[0]->severity);
    }

    /** @return list<array{string, string}> */
    private function findings(object $resource): array
    {
        return array_map(
            static fn (FHIRValidationViolation $violation): array => [$violation->path, $violation->message],
            (new ContainedReferenceChecker())->check($resource),
        );
    }
}
