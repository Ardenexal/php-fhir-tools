<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BundleTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ParametersResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Parameters\ParametersParameter;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive as R5UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Bundle\BundleEntry as R5BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource as R5BundleResource;
use Ardenexal\FHIRTools\Component\Validation\BundleEntryFullUrlChecker;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The message text is copied from the Java reference outcome in
 * vendor/fhir/fhir-test-cases/validator/outcomes/java/R4.bundle-duplicate-ids-not-base.json, so a drift
 * in our wording fails here rather than quietly turning a matching case into a differently worded one.
 */
final class BundleEntryFullUrlCheckerTest extends TestCase
{
    public function testRelativeFullUrlIsReportedWithTheReferenceValidatorsWording(): void
    {
        self::assertSame(
            ["The fullUrl must be an absolute URL (not 'Observation/1')"],
            $this->messagesFor(new BundleResource(entry: [
                new BundleEntry(fullUrl: new UriPrimitive(value: 'Observation/1')),
            ])),
        );
    }

    /**
     * `urn:uuid:` and `urn:oid:` are the spec's own suggested forms for a fullUrl and carry no `//`.
     * A naive `://` test would flag every one of them — which is exactly how this rule would
     * manufacture the ABOVE cases the conformance gate forbids.
     *
     * @return iterable<string, array{string, bool}>
     */
    public static function fullUrls(): iterable
    {
        yield 'http URL'          => ['http://example.org/fhir/Observation/1', false];
        yield 'https URL'         => ['https://example.org/fhir/Patient/2', false];
        yield 'urn:uuid'          => ['urn:uuid:1a19a371-91b8-4a1d-9bb0-e8a997baa655', false];
        yield 'urn:oid'           => ['urn:oid:1.2.3.4.5', false];
        yield 'relative type/id'  => ['Observation/1', true];
        yield 'bare uuid'         => ['1a19a371-91b8-4a1d-9bb0-e8a997baa655', true];
        yield 'leading slash'     => ['/Patient/1', true];
        yield 'relative fragment' => ['#contained-1', true];
    }

    #[DataProvider('fullUrls')]
    public function testAbsolutenessIsDecidedByTheUriScheme(string $fullUrl, bool $expectViolation): void
    {
        $violations = $this->messagesFor(new BundleResource(entry: [
            new BundleEntry(fullUrl: new UriPrimitive(value: $fullUrl)),
        ]));

        self::assertCount($expectViolation ? 1 : 0, $violations, $fullUrl);
    }

    /**
     * Transactions and batches may omit `fullUrl`. An extension-only `fullUrl` (no value) counts as
     * absent, not as a relative URL.
     */
    public function testAbsentFullUrlIsNotAFindingInTransactionsAndBatches(): void
    {
        foreach (['transaction', 'transaction-response', 'batch', 'batch-response'] as $type) {
            self::assertSame([], $this->messagesFor(new BundleResource(type: new BundleTypeType(value: $type), entry: [
                new BundleEntry(),
                new BundleEntry(fullUrl: new UriPrimitive(value: null)),
            ])), $type);
        }
    }

    /**
     * Wording and placement from vendor/fhir/fhir-test-cases/validator/outcomes/java/R4.bundle-with-extensions-base.json:
     * a message Bundle whose only entry has no fullUrl.
     *
     * @return iterable<string, array{string|null}>
     */
    public static function fullUrlRequiredBundleTypes(): iterable
    {
        yield 'document'   => ['document'];
        yield 'message'    => ['message'];
        yield 'collection' => ['collection'];
        yield 'searchset'  => ['searchset'];
        yield 'history'    => ['history'];
        yield 'no type'    => [null];
    }

    #[DataProvider('fullUrlRequiredBundleTypes')]
    public function testAbsentFullUrlIsReportedOutsideTransactionsAndBatches(?string $type): void
    {
        $violations = (new BundleEntryFullUrlChecker())->check(new BundleResource(type: $type === null ? null : new BundleTypeType(value: $type), entry: [
            new BundleEntry(fullUrl: new UriPrimitive(value: 'urn:uuid:1a19a371-91b8-4a1d-9bb0-e8a997baa655')),
            new BundleEntry(resource: new PatientResource(id: 'p1')),
        ]));

        self::assertCount(1, $violations);
        self::assertSame(
            'Except for transactions and batches, each entry in a Bundle must have a fullUrl which is the identity of the resource in the entry',
            $violations[0]->message,
        );
        self::assertSame('entry[1]', $violations[0]->path);
    }

    /**
     * From R4.bundle-ea-testcase-base.json: a transaction entry without fullUrl whose MeasureReport
     * references `Patient/denom-EXM104` relatively. Nothing to resolve against, so it is reported even
     * though transactions may otherwise omit fullUrl.
     */
    public function testRelativeReferenceInAnEntryWithoutFullUrlIsReported(): void
    {
        $violations = (new BundleEntryFullUrlChecker())->check(new BundleResource(type: new BundleTypeType(value: 'transaction'), entry: [
            new BundleEntry(resource: $this->observationReferencing('Patient/1')),
        ]));

        self::assertCount(1, $violations);
        self::assertSame('Bundle entry missing fullUrl', $violations[0]->message);
        self::assertSame('entry[0]', $violations[0]->path);
    }

    /**
     * From R4.japanese-utf8-ok-base.json: a document entry with no fullUrl and a relative reference gets
     * both findings, once each, however many relative references it holds.
     */
    public function testMissingFullUrlFindingsStackOncePerEntry(): void
    {
        $observation        = $this->observationReferencing('Patient/1');
        $observation->focus = [new Reference(reference: new StringPrimitive(value: 'Patient/2'))];

        self::assertSame(
            [
                'Bundle entry missing fullUrl',
                'Except for transactions and batches, each entry in a Bundle must have a fullUrl which is the identity of the resource in the entry',
            ],
            $this->messagesFor(new BundleResource(type: new BundleTypeType(value: 'document'), entry: [new BundleEntry(resource: $observation)])),
        );
    }

    /**
     * A Reference whose own `reference` needs no base can still hold a relative one in
     * `identifier.assigner`.
     */
    public function testRelativeReferenceNestedInsideAnotherReferenceIsReported(): void
    {
        $observation          = $this->observationReferencing('http://example.org/fhir/Patient/1');
        $observation->subject = new Reference(
            reference: new StringPrimitive(value: 'http://example.org/fhir/Patient/1'),
            identifier: new Identifier(assigner: new Reference(reference: new StringPrimitive(value: 'Organization/1'))),
        );

        self::assertSame(['Bundle entry missing fullUrl'], $this->messagesFor(new BundleResource(
            type: new BundleTypeType(value: 'transaction'),
            entry: [new BundleEntry(resource: $observation)],
        )));
    }

    /**
     * Absolute and fragment references need no base, so they never make a missing fullUrl a problem.
     *
     * @return iterable<string, array{string}>
     */
    public static function referencesNeedingNoBase(): iterable
    {
        yield 'absolute http' => ['http://example.org/fhir/Patient/1'];
        yield 'urn:uuid'      => ['urn:uuid:1a19a371-91b8-4a1d-9bb0-e8a997baa655'];
        yield 'fragment'      => ['#p1'];
    }

    #[DataProvider('referencesNeedingNoBase')]
    public function testReferencesThatNeedNoBaseAreNotReported(string $reference): void
    {
        self::assertSame([], $this->messagesFor(new BundleResource(type: new BundleTypeType(value: 'transaction'), entry: [
            new BundleEntry(resource: $this->observationReferencing($reference)),
        ])));
    }

    /**
     * Wording from R4.bundle-ihe-good-base.json, R4.document-css-base.json and
     * R4.relative_reference_to_fullUrl.no_ids.PROBLEM-base.json.
     *
     * @return iterable<string, array{string, string|null, string|null}>
     */
    public static function restfulFullUrls(): iterable
    {
        yield 'matching type and id' => ['http://acme.com/Patient/p1', 'p1', null];
        yield 'matching, deep base'  => ['https://example.org/fhir/r4/Patient/p1', 'p1', null];
        yield 'wrong id'             => [
            'http://acme.com/Patient/p2',
            'p1',
            "The fullUrl 'http://acme.com/Patient/p2' looks like a RESTful server URL, so it must end with the correct type and id (/Patient/p1)",
        ];
        yield 'wrong type' => [
            'http://acme.com/Observation/p1',
            'p1',
            "The fullUrl 'http://acme.com/Observation/p1' looks like a RESTful server URL, so it must end with the correct type and id (/Patient/p1)",
        ];
        yield 'no id' => [
            'http://acme.com/Patient/p1',
            null,
            "The fullUrl 'http://acme.com/Patient/p1' looks like a RESTful server URL, but the resource has no id",
        ];
        yield 'urn:uuid is not RESTful'          => ['urn:uuid:1a19a371-91b8-4a1d-9bb0-e8a997baa655', 'p1', null];
        yield 'unknown type segment'             => ['http://acme.com/Thing/p2', 'p1', null];
        yield 'hyphenated host is not RESTful'   => ['http://my-server.org/Patient/p2', 'p1', null];
        yield 'host is never the type'           => ['http://Patient/p2', 'p1', null];
        yield 'type from another release only'   => ['http://example.org/ActorDefinition/p2', 'p1', null];
        yield 'relative is left to rule 1'       => ['Patient/p2', 'p1', "The fullUrl must be an absolute URL (not 'Patient/p2')"];
    }

    #[DataProvider('restfulFullUrls')]
    public function testRestfulFullUrlMustEndWithTheResourceTypeAndId(string $fullUrl, ?string $id, ?string $expected): void
    {
        $violations = (new BundleEntryFullUrlChecker())->check(new BundleResource(type: new BundleTypeType(value: 'collection'), entry: [
            new BundleEntry(
                fullUrl: new UriPrimitive(value: $fullUrl),
                resource: new PatientResource(id: $id),
            ),
        ]));

        self::assertSame($expected === null ? [] : [$expected], array_map(static fn (FHIRValidationViolation $v): string => $v->message, $violations));

        // The RESTful rule reports on the entry; the absolute-URL rule reports on `fullUrl` itself.
        if ($expected !== null) {
            $expectedPath = str_contains($expected, 'looks like a RESTful server URL') ? 'entry[0]' : 'entry[0].fullUrl';
            self::assertSame($expectedPath, $violations[0]->path);
        }
    }

    /**
     * A Bundle nested in another resource (here Parameters, as in R5.azatadha-example-params-1) is
     * checked by its own type, and a nested Bundle's entries never borrow the outer Bundle's type.
     */
    public function testNestedBundleUsesItsOwnType(): void
    {
        $inner = new BundleResource(type: new BundleTypeType(value: 'collection'), entry: [
            new BundleEntry(resource: new PatientResource(id: 'p1')),
        ]);

        $outer = new BundleResource(type: new BundleTypeType(value: 'transaction'), entry: [
            new BundleEntry(resource: new ParametersResource(parameter: [
                new ParametersParameter(name: new StringPrimitive(value: 'bundle'), resource: $inner),
            ])),
        ]);

        $violations = (new BundleEntryFullUrlChecker())->check($outer);

        self::assertCount(1, $violations);
        self::assertSame('entry[0].resource.parameter[0].resource.entry[0]', $violations[0]->path);
    }

    /** One finding per offending entry, so a bundle's count matches Java's entry-for-entry. */
    public function testEachOffendingEntryYieldsExactlyOneFinding(): void
    {
        self::assertCount(2, $this->messagesFor(new BundleResource(entry: [
            new BundleEntry(fullUrl: new UriPrimitive(value: 'Observation/1')),
            new BundleEntry(fullUrl: new UriPrimitive(value: 'http://example.org/Patient/2')),
            new BundleEntry(fullUrl: new UriPrimitive(value: 'Patient/3')),
        ])));
    }

    /** The rule is anchored on the FHIRBackboneElement attribute, so it needs no version routing. */
    public function testR5IsCoveredByTheSameAttributeAnchor(): void
    {
        self::assertSame(
            ["The fullUrl must be an absolute URL (not 'Observation/1')"],
            $this->messagesFor(new R5BundleResource(entry: [
                new R5BundleEntry(fullUrl: new R5UriPrimitive(value: 'Observation/1')),
            ])),
        );
    }

    /** The violation must be locatable, not just countable. */
    public function testViolationCarriesTheEntryPath(): void
    {
        $violations = (new BundleEntryFullUrlChecker())->check(new BundleResource(entry: [
            new BundleEntry(fullUrl: new UriPrimitive(value: 'http://example.org/ok')),
            new BundleEntry(fullUrl: new UriPrimitive(value: 'Observation/1')),
        ]));

        self::assertCount(1, $violations);
        self::assertSame('entry[1].fullUrl', $violations[0]->path);
        self::assertSame('error', $violations[0]->severity);
    }

    private function observationReferencing(string $reference): ObservationResource
    {
        return new ObservationResource(
            id: 'o1',
            subject: new Reference(reference: new StringPrimitive(value: $reference)),
        );
    }

    /** @return list<string> */
    private function messagesFor(object $resource): array
    {
        return array_map(
            static fn (FHIRValidationViolation $v): string => $v->message,
            (new BundleEntryFullUrlChecker())->check($resource),
        );
    }
}
