<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Integration;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CareTeamResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

/**
 * resolve() against deserialised models, whose values differ from raw arrays in three ways that
 * each dropped the reference silently: `Reference.reference` is a StringPrimitive rather than a
 * string, `contained` holds resource objects rather than arrays, and a resolved object carries no
 * `resourceType` array key.
 *
 * Regression tests for issue #145.
 */
final class ResolveModelReferenceTest extends TestCase
{
    private FHIRPathService $service;

    protected function setUp(): void
    {
        $this->service = new FHIRPathService();
    }

    /**
     * @return iterable<string, array{FhirVersion}>
     */
    public static function versions(): iterable
    {
        yield 'R4' => [FhirVersion::R4];
        yield 'R4B' => [FhirVersion::R4B];
        yield 'R5' => [FhirVersion::R5];
    }

    /**
     * @param array<string, mixed> $resource
     */
    private function deserialize(FhirVersion $version, array $resource): object
    {
        return FHIRSerializationService::createDefault($version)->deserialize((string) json_encode($resource));
    }

    /**
     * @return array<string, mixed>
     */
    private function serviceRequest(string $reference): array
    {
        return [
            'resourceType' => 'ServiceRequest',
            'status'       => 'active',
            'intent'       => 'order',
            'contained'    => [['resourceType' => 'Patient', 'id' => 'p2', 'name' => [['family' => 'Contained']]]],
            'subject'      => ['reference' => $reference],
        ];
    }

    #[DataProvider('versions')]
    public function testContainedReferenceResolvesToTheContainedModel(FhirVersion $version): void
    {
        $model = $this->deserialize($version, $this->serviceRequest('#p2'));

        $family = $this->service->evaluate('ServiceRequest.subject.resolve().name.family', $model);

        self::assertSame(['Contained'], $family->toArray());
    }

    public function testResolvedContainedResourceIsReturnedAsTheModelObject(): void
    {
        $model = $this->deserialize(FhirVersion::R4, $this->serviceRequest('#p2'));

        $resolved = $this->service->evaluate('ServiceRequest.subject.resolve()', $model);

        self::assertCount(1, $resolved);
        self::assertInstanceOf(PatientResource::class, $resolved->first());
    }

    public function testReferenceElementResolvesThroughItsPrimitiveWrapper(): void
    {
        $model = $this->deserialize(FhirVersion::R4, $this->serviceRequest('#p2'));

        $family = $this->service->evaluate('ServiceRequest.subject.reference.resolve().name.family', $model);

        self::assertSame(['Contained'], $family->toArray());
    }

    public function testReferenceHoldingAPlainStringResolves(): void
    {
        $model          = $this->deserialize(FhirVersion::R4, $this->serviceRequest('#p2'));
        $model->subject = new Reference(reference: '#p2');

        $family = $this->service->evaluate('ServiceRequest.subject.resolve().name.family', $model);

        self::assertSame(['Contained'], $family->toArray());
    }

    public function testUnknownContainedIdIsOmitted(): void
    {
        $model = $this->deserialize(FhirVersion::R4, $this->serviceRequest('#missing'));

        self::assertTrue($this->service->evaluate('ServiceRequest.subject.resolve()', $model)->isEmpty());
    }

    #[TestWith(['Practitioner', true])]
    #[TestWith(['Organization', false])]
    public function testInvariantsSeeTheResolvedModel(string $memberType, bool $expected): void
    {
        // ctm-1, as generated on CareTeam.participant. resolve() always came back empty on a model,
        // so this was false whatever the member was.
        $expression = 'onBehalfOf.exists() implies (member.resolve().iif(empty(), true, ofType(Practitioner).exists()))';
        $careTeam   = $this->deserialize(FhirVersion::R4, [
            'resourceType' => 'CareTeam',
            'contained'    => [['resourceType' => $memberType, 'id' => 'm1']],
            'participant'  => [['member' => ['reference' => '#m1'], 'onBehalfOf' => ['reference' => 'Organization/o1']]],
        ]);
        self::assertInstanceOf(CareTeamResource::class, $careTeam);

        $result = $this->service->evaluate($expression, $careTeam->participant[0], new EvaluationContext($careTeam));

        self::assertSame([$expected], $result->toArray());
    }

    public function testPolymorphicallyNavigatedRawReferenceResolves(): void
    {
        // `value` on a raw array finds `valueReference` and hands back a FHIRTypedCollection, which
        // the typed wrapper has to be seen through before the `reference` key is visible.
        $observation = [
            'resourceType' => 'Observation',
            'contained'    => [['resourceType' => 'Patient', 'id' => 'p9', 'name' => [['family' => 'Typed']]]],
            'extension'    => [['url' => 'http://example.org/e', 'valueReference' => ['reference' => '#p9']]],
        ];

        $family = $this->service->evaluate('Observation.extension.value.resolve().name.family', $observation);

        self::assertSame(['Typed'], $family->toArray());
    }
}
