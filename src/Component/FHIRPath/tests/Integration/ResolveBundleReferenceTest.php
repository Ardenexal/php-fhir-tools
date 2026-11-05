<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Integration;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * resolve() between the entries of a Bundle, following
 * https://hl7.org/fhir/R4/bundle.html#references. Every case runs against a raw array and against
 * a deserialised BundleResource, because the two reach the lookup in different shapes.
 *
 * Regression tests for issue #145.
 */
final class ResolveBundleReferenceTest extends TestCase
{
    private const URN_PATIENT = 'urn:uuid:4f6a3c2e-0000-4000-8000-000000000001';

    private const URN_REQUEST = 'urn:uuid:4f6a3c2e-0000-4000-8000-000000000002';

    private FHIRPathEvaluator $evaluator;

    /** @var list<string> URLs that resolve() tried to fetch */
    private array $requested = [];

    protected function setUp(): void
    {
        $this->evaluator = new FHIRPathEvaluator();
    }

    /**
     * @return iterable<string, array{bool}>
     */
    public static function shapes(): iterable
    {
        yield 'array' => [false];
        yield 'model' => [true];
    }

    /**
     * @param list<array<string, mixed>> $entries
     *
     * @return array<string, mixed>|BundleResource
     */
    private function bundle(array $entries, bool $asModel): array|BundleResource
    {
        $bundle = ['resourceType' => 'Bundle', 'type' => 'collection', 'entry' => $entries];
        if (!$asModel) {
            return $bundle;
        }

        return FHIRSerializationService::createDefault(FhirVersion::R4)
            ->deserializeFromJson((string) json_encode($bundle), BundleResource::class);
    }

    /**
     * @return array<string, mixed>
     */
    private function patientEntry(string $fullUrl, string $family, string $id = 'p1', ?string $versionId = null): array
    {
        $resource = ['resourceType' => 'Patient', 'id' => $id, 'name' => [['family' => $family]]];
        if ($versionId !== null) {
            $resource['meta'] = ['versionId' => $versionId];
        }

        return ['fullUrl' => $fullUrl, 'resource' => $resource];
    }

    /**
     * @param list<array<string, mixed>> $contained
     *
     * @return array<string, mixed>
     */
    private function requestEntry(string $fullUrl, string $reference, array $contained = [], string $id = 'sr1'): array
    {
        $resource = [
            'resourceType' => 'ServiceRequest',
            'id'           => $id,
            'status'       => 'active',
            'intent'       => 'order',
            'subject'      => ['reference' => $reference],
        ];
        if ($contained !== []) {
            $resource['contained'] = $contained;
        }

        return ['fullUrl' => $fullUrl, 'resource' => $resource];
    }

    /**
     * Configure a server and an HTTP client that records each attempt and then fails it.
     */
    private function configureServer(): void
    {
        $failure = new class ('no network in tests') extends \RuntimeException implements ClientExceptionInterface {
        };
        $client = new class ($failure) implements ClientInterface {
            public function __construct(private readonly ClientExceptionInterface $failure)
            {
            }

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                throw $this->failure;
            }
        };
        $requested = &$this->requested;
        $request   = $this->createStub(RequestInterface::class);
        $factory   = new class ($request, $requested) implements RequestFactoryInterface {
            /**
             * @param list<string> $requested
             */
            public function __construct(private readonly RequestInterface $request, private array &$requested)
            {
            }

            public function createRequest(string $method, $uri): RequestInterface
            {
                $this->requested[] = (string) $uri;

                return $this->request;
            }
        };

        $this->evaluator->setFhirServerUrl('https://server.example/fhir');
        $this->evaluator->setHttpClient($client, $factory);
    }

    private function evaluate(string $expression, mixed $resource, ?EvaluationContext $context = null): Collection
    {
        $ast = (new FHIRPathParser())->parse((new FHIRPathLexer())->tokenize($expression));

        return $this->evaluator->evaluate($ast, $resource, $context);
    }

    /**
     * Two requests holding the identical reference `Patient/p1` under different bases, so only the
     * entry each one came from can say which patient it means.
     *
     * @return array<string, mixed>|BundleResource
     */
    private function twoBaseBundle(bool $asModel): array|BundleResource
    {
        return $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'FromA'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/s1', 'Patient/p1', [], 's1'),
            $this->patientEntry('http://b.example/fhir/Patient/p1', 'FromB'),
            $this->requestEntry('http://b.example/fhir/ServiceRequest/s2', 'Patient/p1', [], 's2'),
        ], $asModel);
    }

    #[DataProvider('shapes')]
    public function testUrnReferenceResolvesToTheMatchingEntry(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->patientEntry(self::URN_PATIENT, 'Example'),
            $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
        ], $asModel);

        self::assertSame(['Example'], $this->evaluate('Bundle.entry[1].resource.subject.resolve().name.family', $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testReferenceElementResolvesAsAPlainString(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->patientEntry(self::URN_PATIENT, 'Example'),
            $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
        ], $asModel);

        self::assertSame(['Example'], $this->evaluate('Bundle.entry[1].resource.subject.reference.resolve().name.family', $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testAbsoluteReferenceMatchingAnEntryDoesNotTouchTheNetwork(bool $asModel): void
    {
        $this->configureServer();
        $bundle = $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'Local'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/sr1', 'http://a.example/fhir/Patient/p1'),
        ], $asModel);

        self::assertSame(['Local'], $this->evaluate('Bundle.entry[1].resource.subject.resolve().name.family', $bundle)->toArray());
        self::assertSame([], $this->requested);
    }

    #[DataProvider('shapes')]
    public function testAbsoluteReferenceWithNoEntryIsStillFetched(bool $asModel): void
    {
        $this->configureServer();
        $bundle = $this->bundle([
            $this->requestEntry('http://a.example/fhir/ServiceRequest/sr1', 'http://a.example/fhir/Patient/elsewhere'),
        ], $asModel);

        self::assertTrue($this->evaluate('Bundle.entry[0].resource.subject.resolve()', $bundle)->isEmpty());
        self::assertSame(['http://a.example/fhir/Patient/elsewhere'], $this->requested);
    }

    #[DataProvider('shapes')]
    public function testUrnReferenceWithNoEntryIsNotFetched(bool $asModel): void
    {
        $this->configureServer();
        $bundle = $this->bundle([$this->requestEntry(self::URN_REQUEST, self::URN_PATIENT)], $asModel);

        self::assertTrue($this->evaluate('Bundle.entry[0].resource.subject.resolve()', $bundle)->isEmpty());
        self::assertSame([], $this->requested);
    }

    #[DataProvider('shapes')]
    public function testRelativeReferenceIsMadeAbsoluteAgainstTheEntryBase(bool $asModel): void
    {
        $this->configureServer();
        $bundle = $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'FromA'),
            $this->patientEntry('http://b.example/fhir/Patient/p1', 'FromB'),
            $this->requestEntry('http://b.example/fhir/ServiceRequest/sr1', 'Patient/p1'),
        ], $asModel);

        self::assertSame(['FromB'], $this->evaluate('Bundle.entry[2].resource.subject.resolve().name.family', $bundle)->toArray());
        self::assertSame([], $this->requested);
    }

    #[DataProvider('shapes')]
    public function testRelativeReferenceUnderANonRestfulFullUrlHasNoBundleMeaning(bool $asModel): void
    {
        // The Patient's fullUrl has the right shape, but the referring entry has no base to apply.
        $bundle = $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'Local'),
            $this->requestEntry(self::URN_REQUEST, 'Patient/p1'),
        ], $asModel);

        self::assertTrue($this->evaluate('Bundle.entry[1].resource.subject.resolve()', $bundle)->isEmpty());
    }

    #[DataProvider('shapes')]
    public function testRelativeReferenceWithNoEntryFallsBackToTheConfiguredServer(bool $asModel): void
    {
        $this->configureServer();
        $restful = $this->bundle([$this->requestEntry('http://a.example/fhir/ServiceRequest/sr1', 'Patient/p1')], $asModel);
        $urn     = $this->bundle([$this->requestEntry(self::URN_REQUEST, 'Patient/p1')], $asModel);

        $this->evaluate('Bundle.entry[0].resource.subject.resolve()', $restful);
        $this->evaluate('Bundle.entry[0].resource.subject.resolve()', $urn);

        self::assertSame(['https://server.example/fhir/Patient/p1', 'https://server.example/fhir/Patient/p1'], $this->requested);
    }

    #[DataProvider('shapes')]
    public function testVersionSpecificReferenceMatchesMetaVersionId(bool $asModel): void
    {
        $this->configureServer();
        $bundle = $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'VersionOne', 'p1', '1'),
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'VersionTwo', 'p1', '2'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/sr1', 'http://a.example/fhir/Patient/p1/_history/2'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/sr2', 'Patient/p1/_history/1'),
        ], $asModel);

        self::assertSame(['VersionTwo'], $this->evaluate('Bundle.entry[2].resource.subject.resolve().name.family', $bundle)->toArray());
        self::assertSame(['VersionOne'], $this->evaluate('Bundle.entry[3].resource.subject.resolve().name.family', $bundle)->toArray());
        self::assertSame([], $this->requested);
    }

    #[DataProvider('shapes')]
    public function testVersionSpecificReferenceWithNoMatchingVersionIsFetched(bool $asModel): void
    {
        $this->configureServer();
        $bundle = $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'VersionOne', 'p1', '1'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/sr1', 'http://a.example/fhir/Patient/p1/_history/9'),
        ], $asModel);

        self::assertTrue($this->evaluate('Bundle.entry[1].resource.subject.resolve()', $bundle)->isEmpty());
        self::assertSame(['http://a.example/fhir/Patient/p1/_history/9'], $this->requested);
    }

    #[DataProvider('shapes')]
    public function testDuplicateFullUrlIsAmbiguousAndNotFetched(bool $asModel): void
    {
        $this->configureServer();
        $bundle = $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'VersionOne', 'p1', '1'),
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'VersionTwo', 'p1', '2'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/sr1', 'http://a.example/fhir/Patient/p1'),
        ], $asModel);

        self::assertTrue($this->evaluate('Bundle.entry[2].resource.subject.resolve()', $bundle)->isEmpty());
        self::assertSame([], $this->requested);
    }

    #[DataProvider('shapes')]
    public function testFragmentResolvesInTheReferringEntry(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->requestEntry(self::URN_REQUEST, '#p2', [['resourceType' => 'Patient', 'id' => 'p2', 'name' => [['family' => 'Contained']]]]),
        ], $asModel);

        self::assertSame(['Contained'], $this->evaluate('Bundle.entry[0].resource.subject.resolve().name.family', $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testFragmentDoesNotCrossIntoAnotherEntry(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->requestEntry(self::URN_PATIENT, '#other', [['resourceType' => 'Patient', 'id' => 'p2', 'name' => [['family' => 'Contained']]]]),
            $this->requestEntry(self::URN_REQUEST, '#p2'),
        ], $asModel);

        self::assertTrue($this->evaluate('Bundle.entry[1].resource.subject.resolve()', $bundle)->isEmpty());
    }

    #[DataProvider('shapes')]
    public function testReferenceWithFragmentResolvesInTheTargetEntry(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->requestEntry(self::URN_PATIENT, '#unused', [['resourceType' => 'Patient', 'id' => 'p2', 'name' => [['family' => 'Contained']]]]),
            $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT . '#p2'),
        ], $asModel);

        self::assertSame(['Contained'], $this->evaluate('Bundle.entry[1].resource.subject.resolve().name.family', $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testEntriesAreFoundWithoutTheResourceTypePrefix(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->patientEntry(self::URN_PATIENT, 'Example'),
            $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
        ], $asModel);

        self::assertSame(['Example'], $this->evaluate('entry[1].resource.subject.resolve().name.family', $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testValuesMergedFromEntriesThatDisagreeAreNotGuessed(bool $asModel): void
    {
        // union() merges two equal strings into one item that means two different patients. Models
        // hold separate primitive objects, which union() keeps apart, so each resolves on its own.
        $this->configureServer();
        $bundle = $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'FromA'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/sr1', 'Patient/p1'),
            $this->patientEntry('http://b.example/fhir/Patient/p1', 'FromB'),
            $this->requestEntry('http://b.example/fhir/ServiceRequest/sr2', 'Patient/p1'),
        ], $asModel);

        $resolved = $this->evaluate('(Bundle.entry[1].resource.subject.reference | Bundle.entry[3].resource.subject.reference).resolve().name.family', $bundle);

        self::assertSame($asModel ? ['FromA', 'FromB'] : [], $resolved->toArray());
        self::assertSame([], $this->requested);
    }

    #[DataProvider('shapes')]
    public function testRelativeReferenceThatEntriesAgreeOnSurvivesLostProvenance(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'FromA'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/sr1', 'Patient/p1'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/sr2', 'Patient/p1'),
        ], $asModel);

        $family = $this->evaluate('(Bundle.entry[1].resource.subject.reference | Bundle.entry[2].resource.subject.reference).resolve().name.family', $bundle);

        // union() only merges the raw strings; two equal StringPrimitive objects stay as two items.
        self::assertSame(['FromA'], array_values(array_unique($family->toArray())));
    }

    /**
     * @return iterable<string, array{string, list<string>}>
     */
    public static function trackedExpressions(): iterable
    {
        yield 'indexer' => ['Bundle.entry[3].resource.subject.resolve().name.family', ['FromB']];
        yield 'no type prefix' => ['entry[1].resource.subject.resolve().name.family', ['FromA']];
        yield '%resource' => ['%resource.entry[3].resource.subject.resolve().name.family', ['FromB']];
        yield 'reference element' => ['Bundle.entry[3].resource.subject.reference.resolve().name.family', ['FromB']];
        yield 'every entry, in order' => ['Bundle.entry.resource.subject.resolve().name.family', ['FromA', 'FromB']];
        yield 'first' => ['Bundle.entry.resource.subject.first().resolve().name.family', ['FromA']];
        yield 'last' => ['Bundle.entry.resource.subject.last().resolve().name.family', ['FromB']];
        yield 'tail' => ['Bundle.entry.resource.subject.tail().resolve().name.family', ['FromB']];
        yield 'skip' => ['Bundle.entry.resource.subject.skip(1).resolve().name.family', ['FromB']];
        yield 'take' => ['Bundle.entry.resource.subject.take(1).resolve().name.family', ['FromA']];
        yield 'single' => ['Bundle.entry[3].resource.subject.single().resolve().name.family', ['FromB']];
        yield 'trace' => ['Bundle.entry[3].resource.subject.trace(\'t\').resolve().name.family', ['FromB']];
        yield 'where on the resource' => ["Bundle.entry.resource.where(id = 's2').subject.resolve().name.family", ['FromB']];
        yield 'where on the entry' => ["Bundle.entry.where(resource.id = 's2').resource.subject.resolve().name.family", ['FromB']];
        yield 'resolve inside where' => ["Bundle.entry.resource.where(subject.resolve().name.family = 'FromB').id", ['s2']];
        yield 'select from the entry' => ['Bundle.entry.select(resource.subject).resolve().name.family', ['FromA', 'FromB']];
        yield 'select from the resource' => ['Bundle.entry.resource.select(subject).resolve().name.family', ['FromA', 'FromB']];
        yield 'resolve inside select' => ['Bundle.entry.resource.subject.select($this.resolve().name.family)', ['FromA', 'FromB']];
        yield 'iif branch' => ['Bundle.entry[3].resource.subject.iif(true, resolve().name.family)', ['FromB']];
        yield 'aggregate' => ["Bundle.entry.resource.subject.aggregate(\$this.resolve().name.family, '')", ['FromB']];
        yield 'sort by expression' => ['Bundle.entry.resource.sort(-id).subject.resolve().name.family', ['FromB', 'FromA']];
        yield 'repeat' => ['Bundle.entry.resource.where(id = \'s2\').repeat(subject).resolve().name.family', ['FromB']];
    }

    /**
     * @param list<string> $expected
     */
    #[DataProvider('trackedExpressions')]
    public function testTheReferringEntryIsTrackedAsAnArray(string $expression, array $expected): void
    {
        self::assertSame($expected, $this->evaluate($expression, $this->twoBaseBundle(false))->toArray());
    }

    /**
     * @param list<string> $expected
     */
    #[DataProvider('trackedExpressions')]
    public function testTheReferringEntryIsTrackedAsAModel(string $expression, array $expected): void
    {
        self::assertSame($expected, $this->evaluate($expression, $this->twoBaseBundle(true))->toArray());
    }

    #[DataProvider('shapes')]
    public function testExistsAndAllEvaluateEachItemInItsOwnEntry(bool $asModel): void
    {
        $bundle = $this->twoBaseBundle($asModel);

        self::assertSame([true], $this->evaluate("Bundle.entry.resource.subject.exists(resolve().name.family = 'FromB')", $bundle)->toArray());
        self::assertSame([true], $this->evaluate('Bundle.entry.resource.subject.all(resolve().exists())', $bundle)->toArray());
    }

    public function testOfTypeKeepsTheEntryOnAModel(): void
    {
        $family = $this->evaluate('Bundle.entry.resource.ofType(ServiceRequest).last().subject.resolve().name.family', $this->twoBaseBundle(true));

        self::assertSame(['FromB'], $family->toArray());
    }

    public function testAsKeepsTheEntryOnAModel(): void
    {
        $family = $this->evaluate('(Bundle.entry[3].resource as ServiceRequest).subject.resolve().name.family', $this->twoBaseBundle(true));

        self::assertSame(['FromB'], $family->toArray());
    }

    #[DataProvider('shapes')]
    public function testIdenticalFragmentsResolveInTheirOwnEntries(bool $asModel): void
    {
        $contained = static fn (string $family): array => [['resourceType' => 'Patient', 'id' => 'p2', 'name' => [['family' => $family]]]];
        $bundle    = $this->bundle([
            $this->requestEntry(self::URN_PATIENT, '#p2', $contained('First')),
            $this->requestEntry(self::URN_REQUEST, '#p2', $contained('Second')),
        ], $asModel);

        self::assertSame(['First', 'Second'], $this->evaluate('Bundle.entry.resource.subject.resolve().name.family', $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testIdenticalRelativeReferenceUnderAUrnEntryStaysUnresolved(bool $asModel): void
    {
        // A relative reference gets its meaning from its own entry, not from a sibling with a base.
        $bundle = $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'FromA'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/s1', 'Patient/p1'),
            $this->requestEntry(self::URN_REQUEST, 'Patient/p1'),
        ], $asModel);

        self::assertSame(['FromA'], $this->evaluate('Bundle.entry[1].resource.subject.resolve().name.family', $bundle)->toArray());
        self::assertTrue($this->evaluate('Bundle.entry[2].resource.subject.resolve()', $bundle)->isEmpty());
    }

    #[DataProvider('shapes')]
    public function testNestedBundleResolvesInTheInnermostBundle(bool $asModel): void
    {
        $inner = [
            'resourceType' => 'Bundle',
            'type'         => 'collection',
            'entry'        => [
                $this->patientEntry(self::URN_PATIENT, 'Inner'),
                $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
            ],
        ];
        $bundle = $this->bundle([
            $this->patientEntry(self::URN_PATIENT, 'Outer'),
            ['fullUrl' => 'urn:uuid:inner-bundle', 'resource' => $inner],
            $this->requestEntry('urn:uuid:outer-request', self::URN_PATIENT),
        ], $asModel);

        self::assertSame(['Inner'], $this->evaluate('Bundle.entry[1].resource.entry[1].resource.subject.resolve().name.family', $bundle)->toArray());
        self::assertSame(['Outer'], $this->evaluate('Bundle.entry[2].resource.subject.resolve().name.family', $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testBundleSuppliedAsRootWithAnEntryResourceAsFocus(bool $asModel): void
    {
        // The evaluation starts inside the entry, so nothing tracked it: the fallback still applies.
        $bundle = $this->bundle([
            $this->patientEntry(self::URN_PATIENT, 'Example'),
            $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
        ], $asModel);
        $focus = is_array($bundle) ? $bundle['entry'][1]['resource'] : $bundle->entry[1]->resource;

        self::assertSame(['Example'], $this->evaluate('subject.resolve().name.family', $focus, new EvaluationContext($bundle))->toArray());
    }

    /**
     * @return array<string, mixed>
     */
    private function patientSummary(): array
    {
        return [
            'fullUrl'  => 'urn:uuid:composition',
            'resource' => [
                'resourceType' => 'Composition',
                'status'       => 'final',
                'type'         => ['text' => 'Patient summary'],
                'subject'      => ['reference' => self::URN_PATIENT],
                'date'         => '2026-01-01',
                'author'       => [['display' => 'Author']],
                'title'        => 'Summary',
                'section'      => [
                    ['title' => 'Problems', 'entry' => [['reference' => 'urn:uuid:problem'], ['reference' => 'urn:uuid:not-in-bundle']]],
                    ['title' => 'Results', 'section' => [['title' => 'Labs', 'entry' => [['reference' => 'urn:uuid:lab']]]]],
                ],
            ],
        ];
    }

    #[DataProvider('shapes')]
    public function testPatientSummarySectionsResolveToTheirEntries(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->patientSummary(),
            $this->patientEntry(self::URN_PATIENT, 'Example'),
            ['fullUrl' => 'urn:uuid:problem', 'resource' => ['resourceType' => 'Condition', 'id' => 'problem', 'subject' => ['reference' => self::URN_PATIENT]]],
            ['fullUrl' => 'urn:uuid:lab', 'resource' => ['resourceType' => 'Observation', 'id' => 'lab', 'status' => 'final', 'code' => ['text' => 'Lab'], 'subject' => ['reference' => self::URN_PATIENT]]],
        ], $asModel);

        self::assertSame(['Example'], $this->evaluate('Bundle.entry[0].resource.subject.resolve().name.family', $bundle)->toArray());
        // The reference with no entry is omitted, and the ones either side of it keep their place.
        self::assertSame(['problem'], $this->evaluate('Bundle.entry[0].resource.section.entry.resolve().id', $bundle)->toArray());
        self::assertSame(['lab'], $this->evaluate('Bundle.entry[0].resource.section.section.entry.resolve().id', $bundle)->toArray());
        self::assertSame(['problem', 'lab'], $this->evaluate('Bundle.entry[0].resource.repeat(section).entry.resolve().id', $bundle)->toArray());
        // The way back: from a resolved entry, through its own reference.
        self::assertSame(['Example'], $this->evaluate("Bundle.entry.resource.where(id = 'lab').subject.resolve().name.family", $bundle)->toArray());
    }

    public function testAChangedBundleIsNotAnsweredFromAnEarlierEvaluation(): void
    {
        $bundle = $this->bundle([
            $this->patientEntry(self::URN_PATIENT, 'Example'),
            $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
        ], true);
        self::assertInstanceOf(BundleResource::class, $bundle);
        $expression = 'Bundle.entry[1].resource.subject.resolve().name.family';

        self::assertSame(['Example'], $this->evaluate($expression, $bundle)->toArray());

        $bundle->entry[0]->fullUrl = $bundle->entry[1]->fullUrl;

        self::assertSame([], $this->evaluate($expression, $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testResolvePerItemFromInsideAnEntry(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->patientSummary(),
            ['fullUrl' => 'urn:uuid:problem', 'resource' => ['resourceType' => 'Condition', 'id' => 'problem', 'subject' => ['reference' => self::URN_PATIENT]]],
        ], $asModel);
        $composition = is_array($bundle) ? $bundle['entry'][0]['resource'] : $bundle->entry[0]->resource;

        $found = $this->evaluate('section.entry.where(resolve().exists()).reference', $composition, new EvaluationContext($bundle));

        self::assertSame(['urn:uuid:problem'], $found->toArray());
    }

    public function testEvaluationsSharingAContextDoNotShareAnIndex(): void
    {
        $bundle = $this->bundle([
            $this->patientEntry(self::URN_PATIENT, 'Example'),
            $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
        ], true);
        self::assertInstanceOf(BundleResource::class, $bundle);
        $focus   = $bundle->entry[1]->resource;
        $context = new EvaluationContext($bundle);

        self::assertSame(['Example'], $this->evaluate('subject.resolve().name.family', $focus, $context)->toArray());

        $bundle->entry[0]->fullUrl = $bundle->entry[1]->fullUrl;

        self::assertSame([], $this->evaluate('subject.resolve().name.family', $focus, $context)->toArray());
    }

    public function testAnEvaluationScansTheRootBundleOnceHoweverManyTimesResolveRuns(): void
    {
        // Without a scope, each resolve() call used to build its own index, which made this quadratic.
        $bundle = new class () {
            public int $reads = 0;

            public function getResourceType(): string
            {
                return 'Bundle';
            }

            /**
             * @return list<array<string, mixed>>
             */
            public function getEntry(): array
            {
                ++$this->reads;

                return [
                    ['fullUrl' => 'urn:uuid:one', 'resource' => ['resourceType' => 'Patient', 'id' => 'one']],
                    ['fullUrl' => 'urn:uuid:two', 'resource' => ['resourceType' => 'Patient', 'id' => 'two']],
                ];
            }
        };
        $focus = ['section' => [['entry' => [['reference' => 'urn:uuid:one'], ['reference' => 'urn:uuid:two'], ['reference' => 'urn:uuid:three']]]]];

        $found = $this->evaluate('section.entry.where(resolve().exists()).reference', $focus, new EvaluationContext($bundle));

        self::assertSame(['urn:uuid:one', 'urn:uuid:two'], $found->toArray());
        // Once for each lookup table, whatever the number of resolve() calls.
        self::assertSame(2, $bundle->reads);
    }

    public function testRootBundleIndexIsNotKeptOutsideAnEvaluation(): void
    {
        $bundle = ['resourceType' => 'Bundle', 'type' => 'collection'];

        self::assertNull($this->evaluator->getRootBundleIndex(['resourceType' => 'Patient']));
        self::assertNotSame($this->evaluator->getRootBundleIndex($bundle), $this->evaluator->getRootBundleIndex($bundle));
    }

    #[DataProvider('shapes')]
    public function testFragmentIsNotMatchedWhenAnotherHolderLacksTheTarget(bool $asModel): void
    {
        // The evaluation starts inside the entry, so only one of the entries holding `#p2` can answer.
        $bundle = $this->bundle([
            $this->requestEntry(self::URN_PATIENT, '#p2', [['resourceType' => 'Patient', 'id' => 'p2', 'name' => [['family' => 'Contained']]]]),
            $this->requestEntry(self::URN_REQUEST, '#p2'),
        ], $asModel);
        $focus = is_array($bundle) ? $bundle['entry'][1]['resource'] : $bundle->entry[1]->resource;

        self::assertSame([], $this->evaluate('subject.resolve().name.family', $focus, new EvaluationContext($bundle))->toArray());
    }

    #[DataProvider('shapes')]
    public function testFragmentIsMatchedWhenEveryHolderHasAnIdenticalTarget(bool $asModel): void
    {
        $contained = [['resourceType' => 'Patient', 'id' => 'p2', 'name' => [['family' => 'Contained']]]];
        $bundle    = $this->bundle([
            $this->requestEntry(self::URN_PATIENT, '#p2', $contained),
            $this->requestEntry(self::URN_REQUEST, '#p2', $contained),
        ], $asModel);

        if ($asModel) {
            // Two models are different objects however alike they are, so they do not count as agreeing.
            self::assertSame([], $this->evaluate('(Bundle.entry[0].resource.subject.reference | Bundle.entry[1].resource.subject.reference).distinct().resolve().name.family', $bundle)->toArray());

            return;
        }

        self::assertSame(['Contained'], $this->evaluate('Bundle.entry.resource.subject.distinct().resolve().name.family', $bundle)->toArray());
    }

    public function testExistsAllAndRepeatMoveThisToTheCurrentItem(): void
    {
        $patient = ['resourceType' => 'Patient', 'name' => [['family' => 'A'], ['family' => 'B']]];

        self::assertSame([true], $this->evaluate('(1|2).exists($this = 2)', $patient)->toArray());
        self::assertSame([false], $this->evaluate('(1|2).exists($this = 3)', $patient)->toArray());
        self::assertSame([true], $this->evaluate('(1|2).all($this > 0)', $patient)->toArray());
        // The shape of the generated `ig-` invariants, which rely on it.
        self::assertSame([true], $this->evaluate('(1|2).all((1|2|3) contains $this)', $patient)->toArray());
        self::assertSame([false], $this->evaluate('(1|9).all((1|2|3) contains $this)', $patient)->toArray());
        self::assertSame(['A', 'B'], $this->evaluate('name.repeat($this.family)', $patient)->toArray());
    }

    #[DataProvider('shapes')]
    public function testAccumulatorIsNotResolvedAgainstTheCurrentEntry(bool $asModel): void
    {
        // $total holds entry 1's reference while entry 3 is the focus. Its origin is not tracked, so
        // the entries disagreeing must omit it rather than answer with entry 3's base.
        $expression = 'Bundle.entry.resource.subject.aggregate(iif($index = 0, $this, $total.resolve().name.family))';

        self::assertSame([], $this->evaluate($expression, $this->twoBaseBundle($asModel))->toArray());
    }

    #[DataProvider('shapes')]
    public function testThisResolvesInTheEntryOfTheItemItNames(bool $asModel): void
    {
        $bundle = $this->twoBaseBundle($asModel);

        self::assertSame([true], $this->evaluate("Bundle.entry.resource.subject.exists(\$this.resolve().name.family = 'FromB')", $bundle)->toArray());
        self::assertSame([false], $this->evaluate("Bundle.entry.resource.subject.first().exists(\$this.resolve().name.family = 'FromB')", $bundle)->toArray());
        self::assertSame([true], $this->evaluate('Bundle.entry.resource.subject.all($this.resolve().exists())', $bundle)->toArray());
    }

    public function testRepeatOutputWithConflictingOriginsIsNotLabelledWithTheFirst(): void
    {
        // Equal arrays merge into one output item, and it could mean either patient.
        self::assertSame([], $this->evaluate('Bundle.entry.resource.repeat(subject).resolve().name.family', $this->twoBaseBundle(false))->toArray());
    }

    public function testRepeatOutputKeepsAnOriginThatDoesNotConflict(): void
    {
        $expression = "Bundle.entry.resource.where(id = 's1' or id = 's2').where(id = 's2').repeat(subject).resolve().name.family";

        self::assertSame(['FromB'], $this->evaluate($expression, $this->twoBaseBundle(false))->toArray());
    }

    #[DataProvider('shapes')]
    public function testRepeatTraversesIdenticalValuesFromEachEntry(bool $asModel): void
    {
        $family = $this->evaluate('Bundle.entry.resource.subject.repeat(resolve()).name.family', $this->twoBaseBundle($asModel));

        self::assertSame(['FromA', 'FromB'], $family->toArray());
    }

    #[DataProvider('shapes')]
    public function testRepeatTerminatesWhenItsProjectionNavigatesIntoEntryAgain(bool $asModel): void
    {
        // Each pass makes new scopes for the same entries, so scopes cannot be what stops it.
        self::assertSame([4], $this->evaluate('Bundle.repeat(%resource.entry).count()', $this->twoBaseBundle($asModel))->toArray());
    }

    /**
     * An outer and an inner Bundle reusing the same `fullUrl` for different patients.
     *
     * @return array<string, mixed>|BundleResource
     */
    private function nestedBundle(bool $asModel): array|BundleResource
    {
        return $this->bundle([
            $this->patientEntry(self::URN_PATIENT, 'Outer'),
            ['fullUrl' => 'urn:uuid:inner-bundle', 'resource' => [
                'resourceType' => 'Bundle',
                'type'         => 'collection',
                'entry'        => [
                    $this->patientEntry(self::URN_PATIENT, 'Inner'),
                    $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
                ],
            ]],
        ], $asModel);
    }

    #[DataProvider('shapes')]
    public function testCollectionFunctionsStayInTheInnerBundle(bool $asModel): void
    {
        $bundle = $this->nestedBundle($asModel);
        $inner  = 'Bundle.entry[1].resource.entry[1].resource.subject';

        self::assertSame(['Inner'], $this->evaluate("{$inner}.resolve().name.family", $bundle)->toArray());
        self::assertSame(['Inner'], $this->evaluate("{$inner}.distinct().resolve().name.family", $bundle)->toArray());
        self::assertSame(['Inner'], $this->evaluate("({$inner} | {}).resolve().name.family", $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testEvaluationStartedInsideANestedBundleDoesNotUseTheRoot(bool $asModel): void
    {
        $bundle = $this->nestedBundle($asModel);
        $focus  = is_array($bundle) ? $bundle['entry'][1]['resource']['entry'][1]['resource'] : $bundle->entry[1]->resource->entry[1]->resource;

        self::assertSame([], $this->evaluate('subject.resolve().name.family', $focus, new EvaluationContext($bundle))->toArray());
    }

    /**
     * Outer and inner Questionnaires sharing a `fullUrl`, referred to by a canonical rather than a
     * Reference, so nothing named `reference` says the inner Bundle holds the value.
     *
     * @return array<string, mixed>|BundleResource
     */
    private function nestedQuestionnaires(bool $asModel): array|BundleResource
    {
        $questionnaire = static fn (string $title): array => ['fullUrl' => 'urn:uuid:q', 'resource' => ['resourceType' => 'Questionnaire', 'status' => 'active', 'title' => $title]];

        return $this->bundle([
            $questionnaire('OuterTitle'),
            ['fullUrl' => 'urn:uuid:inner-bundle', 'resource' => [
                'resourceType' => 'Bundle',
                'type'         => 'collection',
                'entry'        => [
                    $questionnaire('InnerTitle'),
                    ['fullUrl' => 'urn:uuid:qr', 'resource' => ['resourceType' => 'QuestionnaireResponse', 'status' => 'completed', 'questionnaire' => 'urn:uuid:q']],
                ],
            ]],
        ], $asModel);
    }

    #[DataProvider('shapes')]
    public function testACanonicalKeepsItsOriginInANestedBundle(bool $asModel): void
    {
        $bundle = $this->nestedQuestionnaires($asModel);
        $inner  = 'Bundle.entry[1].resource.entry[1].resource.questionnaire';

        self::assertSame(['InnerTitle'], $this->evaluate("{$inner}.resolve().title", $bundle)->toArray());
        self::assertSame(['InnerTitle'], $this->evaluate("{$inner}.distinct().resolve().title", $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testAValueWithNoOriginIsNotLookedUpInARootHoldingANestedBundle(bool $asModel): void
    {
        $bundle = $this->nestedQuestionnaires($asModel);
        $focus  = is_array($bundle) ? $bundle['entry'][1]['resource']['entry'][1]['resource'] : $bundle->entry[1]->resource->entry[1]->resource;

        self::assertSame([], $this->evaluate('questionnaire.resolve().title', $focus, new EvaluationContext($bundle))->toArray());
        self::assertSame([], $this->evaluate("'urn:uuid:q'.resolve().title", $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testAValueWithNoOriginIsLookedUpInAFlatRoot(bool $asModel): void
    {
        $bundle = $this->bundle([$this->patientEntry(self::URN_PATIENT, 'Flat')], $asModel);

        self::assertSame(['Flat'], $this->evaluate("'" . self::URN_PATIENT . "'.resolve().name.family", $bundle)->toArray());
    }

    public function testAValueWithNoOriginStopsAtABundleNestedBelowAnEntry(): void
    {
        // A Bundle anywhere inside an entry counts, not only one directly at `entry.resource`.
        $bundle = $this->bundle([
            $this->patientEntry(self::URN_PATIENT, 'Outer'),
            ['fullUrl' => 'urn:uuid:parameters', 'resource' => [
                'resourceType' => 'Parameters',
                'parameter'    => [['name' => 'held', 'resource' => ['resourceType' => 'Bundle', 'type' => 'collection']]],
            ]],
        ], false);

        self::assertSame([], $this->evaluate("'" . self::URN_PATIENT . "'.resolve()", $bundle)->toArray());
    }

    /**
     * @return array<string, mixed>
     */
    private function organizationEntry(string $fullUrl, string $name): array
    {
        return ['fullUrl' => $fullUrl, 'resource' => ['resourceType' => 'Organization', 'id' => 'o1', 'name' => $name]];
    }

    /**
     * @param list<array<string, mixed>> $contained
     *
     * @return array<string, mixed>
     */
    private function managedPatientEntry(string $fullUrl, string $family, string $organization, array $contained = []): array
    {
        $entry                                     = $this->patientEntry($fullUrl, $family);
        $entry['resource']['managingOrganization'] = ['reference' => $organization];
        if ($contained !== []) {
            $entry['resource']['contained'] = $contained;
        }

        return $entry;
    }

    #[DataProvider('shapes')]
    public function testChainedResolveStaysInTheBundleOfTheTarget(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->organizationEntry('urn:uuid:org', 'OuterOrg'),
            ['fullUrl' => 'urn:uuid:inner-bundle', 'resource' => [
                'resourceType' => 'Bundle',
                'type'         => 'collection',
                'entry'        => [
                    $this->organizationEntry('urn:uuid:org', 'InnerOrg'),
                    $this->managedPatientEntry(self::URN_PATIENT, 'Inner', 'urn:uuid:org'),
                    $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
                ],
            ]],
        ], $asModel);

        $name = $this->evaluate('Bundle.entry[1].resource.entry[2].resource.subject.resolve().managingOrganization.resolve().name', $bundle);

        self::assertSame(['InnerOrg'], $name->toArray());
    }

    #[DataProvider('shapes')]
    public function testChainedRelativeReferenceUsesTheBaseOfTheTarget(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->organizationEntry('http://a.example/fhir/Organization/o1', 'OrgA'),
            $this->managedPatientEntry('http://a.example/fhir/Patient/p1', 'FromA', 'Organization/o1'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/s1', 'Patient/p1'),
            $this->organizationEntry('http://b.example/fhir/Organization/o1', 'OrgB'),
            $this->managedPatientEntry('http://b.example/fhir/Patient/p1', 'FromB', 'Organization/o1'),
            $this->requestEntry('http://b.example/fhir/ServiceRequest/s2', 'Patient/p1'),
        ], $asModel);
        $expression = 'Bundle.entry[%d].resource.subject.resolve().managingOrganization.resolve().name';

        self::assertSame(['OrgA'], $this->evaluate(sprintf($expression, 2), $bundle)->toArray());
        self::assertSame(['OrgB'], $this->evaluate(sprintf($expression, 5), $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testChainedFragmentResolvesInTheEntryOfTheTarget(bool $asModel): void
    {
        $organization = static fn (string $name): array => ['resourceType' => 'Organization', 'id' => 'gp', 'name' => $name];
        $bundle       = $this->bundle([
            $this->managedPatientEntry(self::URN_PATIENT, 'Target', '#gp', [$organization('TargetGp')]),
            $this->managedPatientEntry('urn:uuid:other', 'Other', '#gp', [$organization('OtherGp')]),
            $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
        ], $asModel);

        $name = $this->evaluate('Bundle.entry[2].resource.subject.resolve().managingOrganization.resolve().name', $bundle);

        self::assertSame(['TargetGp'], $name->toArray());
    }

    #[DataProvider('shapes')]
    public function testChainFromAContainedResourceStaysInItsEntry(bool $asModel): void
    {
        // A contained resource resolves references as its container does.
        $contained = [
            ['resourceType' => 'Patient', 'id' => 'p2', 'managingOrganization' => ['reference' => '#org']],
            ['resourceType' => 'Organization', 'id' => 'org', 'name' => 'SiblingOrg'],
        ];
        $bundle = $this->bundle([$this->requestEntry(self::URN_REQUEST, '#p2', $contained)], $asModel);

        $name = $this->evaluate('Bundle.entry[0].resource.subject.resolve().managingOrganization.resolve().name', $bundle);

        self::assertSame(['SiblingOrg'], $name->toArray());
    }

    #[DataProvider('shapes')]
    public function testChainOverAFlatBundleOfUrnReferences(bool $asModel): void
    {
        $bundle = $this->bundle([
            $this->organizationEntry('urn:uuid:org', 'OnlyOrg'),
            $this->managedPatientEntry(self::URN_PATIENT, 'Only', 'urn:uuid:org'),
            $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
        ], $asModel);

        $name = $this->evaluate('Bundle.entry[2].resource.subject.resolve().managingOrganization.resolve().name', $bundle);

        self::assertSame(['OnlyOrg'], $name->toArray());
    }

    /**
     * Configure a server and an HTTP client that answers from a URL to JSON body map, and 404s otherwise.
     *
     * @param array<string, array<string, mixed>> $bodies
     */
    private function configureServerAnswering(array $bodies): void
    {
        $requested = &$this->requested;
        $found     = $this->createStub(ResponseInterface::class);
        $found->method('getStatusCode')->willReturn(200);
        $missing = $this->createStub(ResponseInterface::class);
        $missing->method('getStatusCode')->willReturn(404);

        $responses = [];
        foreach ($bodies as $url => $body) {
            $stream = $this->createStub(StreamInterface::class);
            $stream->method('__toString')->willReturn((string) json_encode($body));
            $response = $this->createStub(ResponseInterface::class);
            $response->method('getStatusCode')->willReturn(200);
            $response->method('getBody')->willReturn($stream);
            $responses[$url] = $response;
        }

        $client = new class ($responses, $missing, $requested) implements ClientInterface {
            /**
             * @param array<string, ResponseInterface> $responses
             * @param list<string>                     $requested
             */
            public function __construct(private readonly array $responses, private readonly ResponseInterface $missing, private array &$requested)
            {
            }

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                return $this->responses[(string) end($this->requested)] ?? $this->missing;
            }
        };
        $request = $this->createStub(RequestInterface::class);
        $factory = new class ($request, $requested) implements RequestFactoryInterface {
            /**
             * @param list<string> $requested
             */
            public function __construct(private readonly RequestInterface $request, private array &$requested)
            {
            }

            public function createRequest(string $method, $uri): RequestInterface
            {
                $this->requested[] = (string) $uri;

                return $this->request;
            }
        };

        $this->evaluator->setFhirServerUrl('https://server.example/fhir');
        $this->evaluator->setHttpClient($client, $factory);
    }

    #[DataProvider('shapes')]
    public function testFetchedResourceDoesNotResolveAgainstTheBundleItWasNotIn(bool $asModel): void
    {
        $this->configureServerAnswering([
            'http://elsewhere.example/fhir/Patient/x'     => ['resourceType' => 'Patient', 'id' => 'x', 'managingOrganization' => ['reference' => 'Organization/o1']],
            'https://server.example/fhir/Organization/o1' => ['resourceType' => 'Organization', 'id' => 'o1', 'name' => 'FromServer'],
        ]);
        $bundle = $this->bundle([
            $this->organizationEntry('http://a.example/fhir/Organization/o1', 'InTheBundle'),
            $this->managedPatientEntry('http://a.example/fhir/Patient/p1', 'InTheBundle', 'Organization/o1'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/s1', 'http://elsewhere.example/fhir/Patient/x'),
        ], $asModel);

        $name = $this->evaluate('Bundle.entry[2].resource.subject.resolve().managingOrganization.resolve().name', $bundle);

        self::assertSame(['FromServer'], $name->toArray());
    }

    #[DataProvider('shapes')]
    public function testFetchedResourceResolvesFragmentsInItself(bool $asModel): void
    {
        $this->configureServerAnswering([
            'http://elsewhere.example/fhir/Patient/x' => [
                'resourceType'         => 'Patient',
                'id'                   => 'x',
                'contained'            => [['resourceType' => 'Organization', 'id' => 'gp', 'name' => 'InTheFetched']],
                'managingOrganization' => ['reference' => '#gp'],
            ],
        ]);
        $bundle = $this->bundle([
            $this->managedPatientEntry(self::URN_PATIENT, 'Local', '#gp', [['resourceType' => 'Organization', 'id' => 'gp', 'name' => 'InTheBundle']]),
            $this->requestEntry(self::URN_REQUEST, 'http://elsewhere.example/fhir/Patient/x'),
        ], $asModel);

        $name = $this->evaluate('Bundle.entry[1].resource.subject.resolve().managingOrganization.resolve().name', $bundle);

        self::assertSame(['InTheFetched'], $name->toArray());
    }

    public function testFetchedResourceResolvesFragmentsInItselfOutsideABundle(): void
    {
        $this->configureServerAnswering([
            'http://elsewhere.example/fhir/Patient/x' => [
                'resourceType'         => 'Patient',
                'contained'            => [['resourceType' => 'Organization', 'id' => 'gp', 'name' => 'InTheFetched']],
                'managingOrganization' => ['reference' => '#gp'],
            ],
        ]);
        $root = [
            'resourceType' => 'ServiceRequest',
            'contained'    => [['resourceType' => 'Organization', 'id' => 'gp', 'name' => 'InTheRoot']],
            'subject'      => ['reference' => 'http://elsewhere.example/fhir/Patient/x'],
        ];

        self::assertSame(['InTheFetched'], $this->evaluate('ServiceRequest.subject.resolve().managingOrganization.resolve().name', $root)->toArray());
    }

    #[DataProvider('shapes')]
    public function testRepeatTellsIdenticalEntriesInDifferentBundlesApart(bool $asModel): void
    {
        $inner = fn (string $family): array => ['fullUrl' => "urn:uuid:bundle-{$family}", 'resource' => [
            'resourceType' => 'Bundle',
            'type'         => 'collection',
            'entry'        => [
                $this->patientEntry(self::URN_PATIENT, $family),
                $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT),
            ],
        ]];
        $bundle = $this->bundle([$inner('One'), $inner('Two')], $asModel);

        $family = $this->evaluate('Bundle.entry.resource.entry.resource.subject.repeat(resolve()).name.family', $bundle);

        self::assertSame(['One', 'Two'], $family->toArray());
    }

    public function testRepeatDoesNotCopyTheEntryForEveryItem(): void
    {
        // An origin key holding the entry's value made this quadratic: 4,000 sections took 3.3GB.
        $sections = [];
        for ($i = 0; $i < 2000; ++$i) {
            $sections[] = ['title' => "section {$i}", 'text' => ['status' => 'generated', 'div' => '<div>' . str_repeat('x', 100) . '</div>']];
        }
        $entry                        = $this->patientSummary();
        $entry['resource']['section'] = $sections;
        $bundle                       = $this->bundle([$entry], false);

        memory_reset_peak_usage();
        $before = memory_get_peak_usage();
        $count  = $this->evaluate('Bundle.entry[0].resource.repeat(section).count()', $bundle);
        $growth = memory_get_peak_usage() - $before;

        self::assertSame([2000], $count->toArray());
        self::assertLessThan(32 * 1024 * 1024, $growth, 'repeat() retained far more memory than its items');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function functionsKeepingAnOrigin(): iterable
    {
        yield 'distinct' => ['Bundle.entry[3].resource.subject.distinct().resolve().name.family'];
        yield 'union operator' => ['(Bundle.entry[3].resource.subject | {}).resolve().name.family'];
        yield 'union function' => ['Bundle.entry[3].resource.subject.union({}).resolve().name.family'];
        yield 'combine' => ['Bundle.entry[3].resource.subject.combine({}).resolve().name.family'];
        yield 'exclude' => ["Bundle.entry[3].resource.subject.exclude('other').resolve().name.family"];
        yield 'intersect' => ['Bundle.entry[3].resource.subject.reference.intersect(Bundle.entry[3].resource.subject.reference).resolve().name.family'];
        yield 'children' => ['Bundle.entry[3].resource.children().where(reference.exists()).resolve().name.family'];
        yield 'descendants' => ['Bundle.entry[3].resource.descendants().where(reference.exists()).resolve().name.family'];
        yield 'natural sort' => ['Bundle.entry[3].resource.subject.reference.sort().resolve().name.family'];
        yield 'value derived by a function' => ['Bundle.entry[3].resource.subject.reference.toString().resolve().name.family'];
        yield 'replace' => ["Bundle.entry[3].resource.subject.reference.replace('p1', 'p1').resolve().name.family"];
    }

    #[DataProvider('functionsKeepingAnOrigin')]
    public function testFunctionsKeepAnOriginOnArrays(string $expression): void
    {
        self::assertSame(['FromB'], $this->evaluate($expression, $this->twoBaseBundle(false))->toArray());
    }

    #[DataProvider('functionsKeepingAnOrigin')]
    public function testFunctionsKeepAnOriginOnModels(string $expression): void
    {
        self::assertSame(['FromB'], $this->evaluate($expression, $this->twoBaseBundle(true))->toArray());
    }

    #[DataProvider('shapes')]
    public function testChildrenAndDescendantsKeepEachItemsOwnOrigin(bool $asModel): void
    {
        $bundle = $this->twoBaseBundle($asModel);

        self::assertSame(['FromA', 'FromB'], $this->evaluate('Bundle.entry.resource.children().where(reference.exists()).resolve().name.family', $bundle)->toArray());
        self::assertSame(['FromA', 'FromB'], $this->evaluate('Bundle.entry.resource.descendants().where(reference.exists()).resolve().name.family', $bundle)->toArray());
    }

    #[DataProvider('shapes')]
    public function testDistinctOverASharedReferenceStillResolves(bool $asModel): void
    {
        // Every entry refers to one patient, the commonest shape there is.
        $this->configureServer();
        $bundle = $this->bundle([
            $this->patientEntry(self::URN_PATIENT, 'Shared'),
            $this->requestEntry('urn:uuid:one', self::URN_PATIENT, [], 'one'),
            $this->requestEntry('urn:uuid:two', self::URN_PATIENT, [], 'two'),
            $this->requestEntry('urn:uuid:three', self::URN_PATIENT, [], 'three'),
        ], $asModel);

        $family = $this->evaluate('Bundle.entry.resource.subject.distinct().resolve().name.family', $bundle);

        self::assertSame(['Shared'], array_values(array_unique($family->toArray())));
        self::assertSame([], $this->requested);
    }

    public function testMergedAbsoluteReferenceIsFetchedOnlyOnce(): void
    {
        $this->configureServer();
        $bundle = $this->bundle([
            $this->requestEntry('urn:uuid:one', 'http://elsewhere.example/fhir/Patient/x', [], 'one'),
            $this->requestEntry('urn:uuid:two', 'http://elsewhere.example/fhir/Patient/x', [], 'two'),
            $this->requestEntry('urn:uuid:three', 'http://elsewhere.example/fhir/Patient/x', [], 'three'),
        ], false);

        $this->evaluate('Bundle.entry.resource.subject.distinct().resolve()', $bundle);

        self::assertSame(['http://elsewhere.example/fhir/Patient/x'], $this->requested);
    }

    public function testDistinctOverReferencesThatMeanDifferentPatientsIsNotGuessed(): void
    {
        self::assertSame([], $this->evaluate('Bundle.entry.resource.subject.distinct().resolve().name.family', $this->twoBaseBundle(false))->toArray());
    }

    #[DataProvider('shapes')]
    public function testFetchedResourceKeepsItsContextThroughDistinct(bool $asModel): void
    {
        $this->configureServerAnswering([
            'http://elsewhere.example/fhir/Patient/x'     => ['resourceType' => 'Patient', 'id' => 'x', 'managingOrganization' => ['reference' => 'Organization/o1']],
            'https://server.example/fhir/Organization/o1' => ['resourceType' => 'Organization', 'id' => 'o1', 'name' => 'FromServer'],
        ]);
        $bundle = $this->bundle([
            $this->organizationEntry('http://a.example/fhir/Organization/o1', 'InTheBundle'),
            $this->managedPatientEntry('http://a.example/fhir/Patient/p1', 'InTheBundle', 'Organization/o1'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/s1', 'http://elsewhere.example/fhir/Patient/x'),
        ], $asModel);

        $name = $this->evaluate('Bundle.entry[2].resource.subject.resolve().managingOrganization.distinct().resolve().name', $bundle);

        self::assertSame(['FromServer'], $name->toArray());
    }

    #[DataProvider('shapes')]
    public function testDescendantsOfANestedBundleDoNotClaimTheOuterEntry(bool $asModel): void
    {
        $bundle = $this->nestedBundle($asModel);

        $family = $this->evaluate('Bundle.entry[1].resource.descendants().where(reference.exists()).resolve().name.family', $bundle);

        self::assertSame([], $family->toArray());
    }

    #[DataProvider('shapes')]
    public function testAggregateResultDoesNotClaimTheOriginOfItsInput(bool $asModel): void
    {
        // The result is the `init` value, from entry 1, though every input item is from entry 3.
        $expression = 'Bundle.entry[3].resource.subject.aggregate($total, %resource.entry[1].resource.subject).resolve().name.family';

        self::assertSame([], $this->evaluate($expression, $this->twoBaseBundle($asModel))->toArray());
    }

    /**
     * Two Bundles at the same position, because neither sits in a Bundle entry.
     *
     * @param list<array<string, mixed>> $first
     * @param list<array<string, mixed>> $second
     */
    private function parameters(array $first, array $second, bool $asModel): object|array
    {
        $bundle     = static fn (array $entries): array => ['resourceType' => 'Bundle', 'type' => 'collection', 'entry' => $entries];
        $parameters = ['resourceType' => 'Parameters', 'parameter' => [
            ['name' => 'first', 'resource' => $bundle($first)],
            ['name' => 'second', 'resource' => $bundle($second)],
        ]];

        return $asModel ? FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize((string) json_encode($parameters)) : $parameters;
    }

    #[DataProvider('shapes')]
    public function testBundlesAtTheSamePositionAreDifferentOrigins(bool $asModel): void
    {
        // Two origins, so no one entry can be claimed for what getValue() derives from them.
        $parameters = $this->parameters(
            [$this->patientEntry(self::URN_PATIENT, 'One'), $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT)],
            [$this->patientEntry(self::URN_PATIENT, 'Two'), $this->requestEntry(self::URN_REQUEST, self::URN_PATIENT)],
            $asModel,
        );
        $path = 'Parameters.parameter.resource.entry.resource.subject';

        self::assertSame(['One', 'Two'], $this->evaluate("{$path}.resolve().name.family", $parameters)->toArray());
        self::assertSame([], $this->evaluate("{$path}.reference.getValue().resolve().name.family", $parameters)->toArray());
    }

    public function testMergedValueIsAskedInEachBundleAtTheSamePosition(): void
    {
        $parameters = $this->parameters(
            [$this->patientEntry('http://a.example/fhir/Patient/p1', 'FromA'), $this->requestEntry('http://a.example/fhir/ServiceRequest/s', 'Patient/p1')],
            [$this->patientEntry('http://b.example/fhir/Patient/p1', 'FromB'), $this->requestEntry('http://b.example/fhir/ServiceRequest/s', 'Patient/p1')],
            false,
        );

        $family = $this->evaluate('Parameters.parameter.resource.entry.resource.subject.distinct().resolve().name.family', $parameters);

        self::assertSame([], $family->toArray());
    }

    #[DataProvider('shapes')]
    public function testResolveInsideWhereCanNavigateBackIntoTheBundle(bool $asModel): void
    {
        // Each pass navigates back into the same Bundle, which needs scanning only once.
        $bundle = $this->twoBaseBundle($asModel);

        $found = $this->evaluate("Bundle.entry.resource.where(%resource.entry[3].resource.subject.resolve().name.family = 'FromB').count()", $bundle);

        self::assertSame([4], $found->toArray());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function projectionsOfAnotherValue(): iterable
    {
        yield 'iif returning $total' => ['Bundle.entry.resource.subject.aggregate(iif($index = 0, $this, iif(true, $total).resolve().name.family))'];
        yield 'iif returning a literal' => ["Bundle.entry[3].resource.subject.iif(true, 'Patient/p1').resolve().name.family"];
        yield 'select returning a literal' => ["Bundle.entry[3].resource.subject.select('Patient/p1').resolve().name.family"];
        yield 'repeat returning a literal' => ["Bundle.entry[3].resource.subject.repeat('Patient/p1').resolve().name.family"];
    }

    /**
     * A projection returns what its expression evaluates to, which need not come from its input.
     */
    #[DataProvider('projectionsOfAnotherValue')]
    public function testAProjectionDoesNotLendItsInputsOrigin(string $expression): void
    {
        self::assertSame([], $this->evaluate($expression, $this->twoBaseBundle(false))->toArray());
    }

    public function testAProjectionOfAnEntrysOwnValuesKeepsItsOrigin(): void
    {
        $bundle = $this->twoBaseBundle(false);

        self::assertSame(['FromB'], $this->evaluate('Bundle.entry[3].resource.select(subject.reference.toString()).resolve().name.family', $bundle)->toArray());
        self::assertSame(['FromB'], $this->evaluate('Bundle.entry[3].resource.subject.iif(true, $this).resolve().name.family', $bundle)->toArray());
    }

    public function testAggregateResolvesWhenTheEntriesAgree(): void
    {
        // Its result has no known entry, like any other value, rather than never resolving.
        $bundle = $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'FromA'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/s1', 'Patient/p1'),
        ], false);

        self::assertSame(['FromA'], $this->evaluate('Bundle.entry[1].resource.subject.aggregate($this).resolve().name.family', $bundle)->toArray());
    }

    public function testRepeatRemembersAnUnscopedItemFromBeforeAnyScopeAppears(): void
    {
        // The first subject comes through an external constant, so it has no entry; the second does.
        // They merge into one value that could mean either patient.
        $bundle  = $this->twoBaseBundle(false);
        $context = new EvaluationContext($bundle);
        $context->setExternalConstant('untracked', $bundle['entry'][1]['resource']);

        $family = $this->evaluate('(%untracked | Bundle.entry[3].resource).repeat(subject).resolve().name.family', $bundle, $context);

        self::assertSame([], $family->toArray());
    }

    public function testRepeatTerminatesThroughEvaluateWithContext(): void
    {
        // A public way in that skips evaluate(), where a Bundle's id must stay stable all the same.
        $bundle  = $this->twoBaseBundle(false);
        $ast     = (new FHIRPathParser())->parse((new FHIRPathLexer())->tokenize('Bundle.repeat(%resource.entry).count()'));
        $context = new EvaluationContext($bundle, $bundle, [], [], $this->evaluator);

        self::assertSame([4], $this->evaluator->evaluateWithContext($ast, $context)->toArray());
    }
}
