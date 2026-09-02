<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\BundleTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CompositionResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\OrganizationResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Validation\BundleReferenceResolutionChecker;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Each case here is the shape of a vendored corpus fixture, named in the test that carries it, so a
 * drift in the rule fails against the reason the rule exists rather than against a count.
 *
 * The first four tests are the `relative_reference_*` fixture matrix, and each expectation is the
 * fixture's vendored reference outcome, never its title: two of those titles end `-> OK` on documents
 * the reference validator reports. See the checker's class docblock.
 */
final class BundleReferenceResolutionCheckerTest extends TestCase
{
    /**
     * `relative_reference_to_fullUrl.id_in_Composition`, the one fixture whose reference outcome is
     * clean, and the only one of the four whose title agrees with it.
     *
     * The source entry's fullUrl decomposes as `[base]/[type]/[id]` with the id the Composition
     * actually carries, so the base is `http://zrbj.eu/x/` and `Organization/666` resolves to an entry
     * the bundle holds. The target's own missing id is irrelevant.
     */
    public function testReferenceResolvesWhenTheSourceEntryYieldsABase(): void
    {
        self::assertSame([], $this->messagesFor($this->document(
            sourceFullUrl: 'http://zrbj.eu/x/Composition/666',
            sourceId: '666',
            reference: 'Organization/666',
            targetFullUrl: 'http://zrbj.eu/x/Organization/666',
            targetId: null,
        )));
    }

    /**
     * `relative_reference_to_fullUrl.id_in_target_resource`. The Composition has no id, so its fullUrl
     * does not decompose, no base exists, and the Organization sitting right there cannot be reached.
     */
    public function testReferenceFailsWhenTheSourceResourceHasNoId(): void
    {
        self::assertSame(
            ["Can't find 'Organization/666' in the bundle. A resource with the same type and id is present at fullUrl 'http://zrbj.eu/x/Organization/666', but the fullUrl based rules around matching relative references do not match it against the source fullUrl 'http://zrbj.eu/x/Composition/666'"],
            $this->messagesFor($this->document(
                sourceFullUrl: 'http://zrbj.eu/x/Composition/666',
                sourceId: null,
                reference: 'Organization/666',
                targetFullUrl: 'http://zrbj.eu/x/Organization/666',
                targetId: '666',
            )),
        );
    }

    /**
     * `relative_reference_to_fullUrl.no_ids.PROBLEM`. Neither resource has an id, so there is no
     * same-type-and-id candidate to name. The reference validator reports plain absence here, which is
     * a different sentence and a different rule; this class stays silent rather than guessing at it.
     */
    public function testNoCandidateIsNotThisRulesFinding(): void
    {
        self::assertSame([], $this->messagesFor($this->document(
            sourceFullUrl: 'http://zrbj.eu/x/Composition/666',
            sourceId: null,
            reference: 'Organization/666',
            targetFullUrl: 'http://zrbj.eu/x/Organization/666',
            targetId: null,
        )));
    }

    /** `relative_reference_to_TYPE_ID.all_fullUrl_UUID`. A `urn:` fullUrl yields no base at all. */
    public function testUrnFullUrlYieldsNoBase(): void
    {
        self::assertSame(
            ["Can't find 'Organization/666' in the bundle. A resource with the same type and id is present at fullUrl 'urn:uuid:2aaf815f-3ebe-4a03-9b87-05d0700313b2', but the fullUrl based rules around matching relative references do not match it against the source fullUrl 'urn:uuid:10a67a86-a9ec-427e-ba3f-f042cef21733'"],
            $this->messagesFor($this->document(
                sourceFullUrl: 'urn:uuid:10a67a86-a9ec-427e-ba3f-f042cef21733',
                sourceId: null,
                reference: 'Organization/666',
                targetFullUrl: 'urn:uuid:2aaf815f-3ebe-4a03-9b87-05d0700313b2',
                targetId: '666',
            )),
        );
    }

    /**
     * The `mni-patientOverview-bundle-example1` and `…example1b` twins, which differ in one character.
     *
     * Both identify their entries relatively, so both are reported by `BundleEntryFullUrlChecker`. Only
     * `1b` is reported here, because its Composition's id (`1a`) disagrees with the id its fullUrl
     * claims (`1`), which is what destroys the base. Requiring a non-empty base collapses the two and
     * puts the clean twin into `ABOVE`.
     *
     * @return iterable<string, array{string, bool}>
     */
    public static function compositionIds(): iterable
    {
        yield 'example1, fullUrl id agrees'     => ['1', false];
        yield 'example1b, fullUrl id disagrees' => ['1a', true];
    }

    #[DataProvider('compositionIds')]
    public function testAnEmptyBaseStillResolves(string $compositionId, bool $expectViolation): void
    {
        $violations = $this->messagesFor($this->document(
            sourceFullUrl: 'Composition/1',
            sourceId: $compositionId,
            reference: 'Patient/1',
            targetFullUrl: 'Patient/1',
            targetId: '1',
            targetIsPatient: true,
        ));

        self::assertCount($expectViolation ? 1 : 0, $violations, $compositionId);
    }

    /**
     * `bnd-ambiguous-refs`, `bundle-duplicate-id` and both `ref-policy` bundles are `collection`s, and
     * the reference validator reports nothing on any of them. Only a document or a message is a closed
     * set whose internal references must resolve; every other kind is an open envelope.
     *
     * @return iterable<string, array{string, bool}>
     */
    public static function bundleTypes(): iterable
    {
        yield 'document'    => ['document', true];
        yield 'message'     => ['message', true];
        yield 'collection'  => ['collection', false];
        yield 'searchset'   => ['searchset', false];
        yield 'transaction' => ['transaction', false];
        yield 'batch'       => ['batch', false];
    }

    #[DataProvider('bundleTypes')]
    public function testOnlyDocumentAndMessageBundlesResolve(string $type, bool $expectViolation): void
    {
        $violations = $this->messagesFor($this->document(
            sourceFullUrl: 'urn:uuid:10a67a86-a9ec-427e-ba3f-f042cef21733',
            sourceId: null,
            reference: 'Organization/666',
            targetFullUrl: 'urn:uuid:2aaf815f-3ebe-4a03-9b87-05d0700313b2',
            targetId: '666',
            type: $type,
        ));

        self::assertCount($expectViolation ? 1 : 0, $violations, $type);
    }

    /**
     * `bundle-document-versioned-references-good` holds two references at `_history/1` and `_history/2`
     * against two entries sharing one fullUrl, and the reference validator accepts both. A versioned
     * reference resolves against `meta.versionId`, which is a rule this class does not implement, so it
     * declines to judge one rather than reporting a document the reference validator passes.
     */
    public function testVersionedReferencesAreNotJudged(): void
    {
        self::assertSame([], $this->messagesFor($this->document(
            sourceFullUrl: 'http://example.org/Composition/CompositionExample1',
            sourceId: 'CompositionExample1',
            reference: 'Organization/666/_history/1',
            targetFullUrl: 'http://example.org/Organization/666',
            targetId: '666',
        )));
    }

    /** A resource is not reachable through this rule when it is not in the bundle at all. */
    public function testAbsoluteAndFragmentReferencesAreNotJudged(): void
    {
        foreach (['http://example.org/Organization/666', '#contained-1', 'urn:uuid:2aaf815f'] as $reference) {
            self::assertSame([], $this->messagesFor($this->document(
                sourceFullUrl: 'urn:uuid:10a67a86-a9ec-427e-ba3f-f042cef21733',
                sourceId: null,
                reference: $reference,
                targetFullUrl: 'urn:uuid:2aaf815f-3ebe-4a03-9b87-05d0700313b2',
                targetId: '666',
            )), $reference);
        }
    }

    /** A bundle is the only root this applies to, and an entry-less one has nothing to walk. */
    public function testNonBundleAndEmptyBundleAreSilent(): void
    {
        $checker = new BundleReferenceResolutionChecker();

        self::assertSame([], $checker->check(new PatientResource(id: '1')));
        self::assertSame([], $checker->check(new BundleResource(type: new BundleTypeType(value: 'document'))));
    }

    /**
     * `bundle-dual-target`: two entries carry the same type and id, and neither fullUrl matches, so the
     * sentence turns plural and names both.
     */
    public function testTwoCandidatesAreReportedTogether(): void
    {
        $bundle = new BundleResource(
            type: new BundleTypeType(value: 'document'),
            entry: [
                new BundleEntry(
                    fullUrl: new UriPrimitive(value: 'urn:uuid:30551ce1'),
                    resource: new CompositionResource(subject: new Reference(reference: 'Patient/123')),
                ),
                new BundleEntry(
                    fullUrl: new UriPrimitive(value: 'urn:uuid:2b90dd2b'),
                    resource: new PatientResource(id: '123'),
                ),
                new BundleEntry(
                    fullUrl: new UriPrimitive(value: 'urn:uuid:f5452d76'),
                    resource: new PatientResource(id: '123'),
                ),
            ],
        );

        self::assertSame(
            ["Can't find 'Patient/123' in the bundle. 2 resources with the same type and id are present at fullUrls 'urn:uuid:2b90dd2b,urn:uuid:f5452d76', but the fullUrl based rules around matching relative references match none of them against the source fullUrl 'urn:uuid:30551ce1'"],
            $this->messagesFor($bundle),
        );
    }

    /** The violation is reported at the reference's own path, not at the entry or the bundle. */
    public function testTheViolationCarriesTheReferencesPath(): void
    {
        $violations = (new BundleReferenceResolutionChecker())->check($this->document(
            sourceFullUrl: 'urn:uuid:10a67a86',
            sourceId: null,
            reference: 'Organization/666',
            targetFullUrl: 'urn:uuid:2aaf815f',
            targetId: '666',
        ));

        self::assertCount(1, $violations);
        self::assertSame('entry[0].resource.author[0]', $violations[0]->path);
        self::assertSame('error', $violations[0]->severity);
    }

    /**
     * Build a two-entry document: a Composition referencing something, and the thing it references.
     *
     * The reference sits on `Composition.author`, which is the repeating element the corpus uses for
     * this rule, so the emitted path exercises the array-index handling too.
     */
    private function document(
        string $sourceFullUrl,
        ?string $sourceId,
        string $reference,
        string $targetFullUrl,
        ?string $targetId,
        string $type = 'document',
        bool $targetIsPatient = false,
    ): BundleResource {
        $target = $targetIsPatient
            ? new PatientResource(id: $targetId)
            : new OrganizationResource(id: $targetId);

        return new BundleResource(
            type: new BundleTypeType(value: $type),
            entry: [
                new BundleEntry(
                    fullUrl: new UriPrimitive(value: $sourceFullUrl),
                    resource: new CompositionResource(id: $sourceId, author: [new Reference(reference: $reference)]),
                ),
                new BundleEntry(
                    fullUrl: new UriPrimitive(value: $targetFullUrl),
                    resource: $target,
                ),
            ],
        );
    }

    /** @return list<string> */
    private function messagesFor(object $resource): array
    {
        return array_map(
            static fn (FHIRValidationViolation $violation): string => $violation->message,
            (new BundleReferenceResolutionChecker())->check($resource),
        );
    }
}
