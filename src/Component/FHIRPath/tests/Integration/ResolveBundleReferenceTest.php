<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Integration;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
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
    private function requestEntry(string $fullUrl, string $reference, array $contained = []): array
    {
        $resource = [
            'resourceType' => 'ServiceRequest',
            'id'           => 'sr1',
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

    private function evaluate(string $expression, mixed $resource): Collection
    {
        $ast = (new FHIRPathParser())->parse((new FHIRPathLexer())->tokenize($expression));

        return $this->evaluator->evaluate($ast, $resource);
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
    public function testRelativeReferenceThatEntriesDisagreeOnIsNotGuessed(bool $asModel): void
    {
        // union() merges two identical values, so nothing says which entry the survivor came from.
        // The bases give different targets, and picking either could return the wrong patient.
        $this->configureServer();
        $bundle = $this->bundle([
            $this->patientEntry('http://a.example/fhir/Patient/p1', 'FromA'),
            $this->requestEntry('http://a.example/fhir/ServiceRequest/sr1', 'Patient/p1'),
            $this->patientEntry('http://b.example/fhir/Patient/p1', 'FromB'),
            $this->requestEntry('http://b.example/fhir/ServiceRequest/sr2', 'Patient/p1'),
        ], $asModel);

        $resolved = $this->evaluate('(Bundle.entry[1].resource.subject.reference | Bundle.entry[3].resource.subject.reference).resolve()', $bundle);

        self::assertTrue($resolved->isEmpty());
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
}
