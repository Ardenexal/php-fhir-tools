<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
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
     * `fullUrl` is `0..1` and many corpus bundles omit it. The reference validator reports nothing for
     * an entry without one, so neither may we — an absent value is not a relative value.
     */
    public function testAbsentFullUrlIsNotAFinding(): void
    {
        self::assertSame([], $this->messagesFor(new BundleResource(entry: [new BundleEntry()])));
        self::assertSame([], $this->messagesFor(new BundleResource(entry: [
            new BundleEntry(fullUrl: new UriPrimitive(value: null)),
        ])));
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

    /** @return list<string> */
    private function messagesFor(object $resource): array
    {
        return array_map(
            static fn (FHIRValidationViolation $v): string => $v->message,
            (new BundleEntryFullUrlChecker())->check($resource),
        );
    }
}
