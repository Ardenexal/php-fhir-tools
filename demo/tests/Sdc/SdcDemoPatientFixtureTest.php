<?php

declare(strict_types=1);

namespace App\Tests\Sdc;

use App\Sdc\QuestionnaireFormRenderer;
use App\Sdc\QuestionnaireResponseBuilder;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Sdc\Contract\ExtractServiceInterface;
use Ardenexal\FHIRTools\Component\Sdc\Contract\PopulateServiceInterface;
use Ardenexal\FHIRTools\Component\Sdc\ExtractContext;
use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;
use Ardenexal\FHIRTools\Component\Serialization\FHIRVersionedSerializationServiceLocator;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;

/**
 * Proves `demo/assets/sdc-samples/sdc-demo-patient.questionnaire.json` — the new M02 sample covering
 * string + boolean + choice + group — actually extracts through the real wired services (not just the
 * builder in isolation), one field family at a time, matching the milestone's exit criteria coverage.
 */
final class SdcDemoPatientFixtureTest extends KernelTestCase
{
    /** @return array<string, mixed> */
    private function fixture(): array
    {
        $json = file_get_contents(__DIR__ . '/../../assets/sdc-samples/sdc-demo-patient.questionnaire.json');
        self::assertIsString($json);

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return $decoded;
    }

    /** @return array<string, mixed> */
    private function extractPatient(array $answers): array
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ExtractServiceInterface $extractService */
        $extractService = $container->get(ExtractServiceInterface::class);
        /** @var FHIRVersionedSerializationServiceLocator $locator */
        $locator = $container->get(FHIRVersionedSerializationServiceLocator::class);
        $service = $locator->get(FhirVersion::R4);

        $fixture               = $this->fixture();
        $questionnaireResponse = (new QuestionnaireResponseBuilder())->build($fixture['item'], $answers);
        $questionnaireModel    = $service->deserialize(
            json_encode($fixture, JSON_THROW_ON_ERROR),
            QuestionnaireResource::class,
        );

        $result = $extractService->extract($questionnaireResponse, new ExtractContext(
            fhirVersion: FhirVersion::R4,
            questionnaire: $questionnaireModel,
        ));

        $bundleJson = $service->serializeToJson($result->getResource());
        /** @var array<string, mixed> $bundle */
        $bundle = json_decode($bundleJson, true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('transaction', $bundle['type']);
        self::assertCount(1, $bundle['entry'], 'expected exactly one extracted Patient entry: ' . $bundleJson);

        /** @var array<string, mixed> $resource */
        $resource = $bundle['entry'][0]['resource'];
        self::assertSame('Patient', $resource['resourceType']);

        return $resource;
    }

    public function testBooleanAndStringFieldsExtract(): void
    {
        $patient = $this->extractPatient([
            'patient' => [
                'active' => 'true',
                'given'  => 'Jane',
                'family' => 'Doe',
            ],
        ]);

        self::assertTrue($patient['active']);
        self::assertSame('Jane', $patient['name'][0]['given'][0]);
        self::assertSame('Doe', $patient['name'][0]['family']);
    }

    public function testChoiceFieldExtractsAsPatientGenderCode(): void
    {
        $patient = $this->extractPatient([
            'patient' => [
                'given'  => 'Jane',
                'gender' => '1', // index 1 => female
            ],
        ]);

        self::assertSame('female', $patient['gender']);
    }

    public function testDateFieldExtractsAsPatientBirthDate(): void
    {
        $patient = $this->extractPatient([
            'patient' => [
                'given' => 'Jane',
                'dob'   => '1993-04-02',
            ],
        ]);

        self::assertSame('1993-04-02', $patient['birthDate']);
    }

    public function testAllFieldsTogetherExtractIntoOnePatient(): void
    {
        $patient = $this->extractPatient([
            'patient' => [
                'active' => 'true',
                'given'  => 'Jane',
                'family' => 'Doe',
                'gender' => '1',
                'dob'    => '1993-04-02',
            ],
        ]);

        self::assertTrue($patient['active']);
        self::assertSame('Jane', $patient['name'][0]['given'][0]);
        self::assertSame('Doe', $patient['name'][0]['family']);
        self::assertSame('female', $patient['gender']);
        self::assertSame('1993-04-02', $patient['birthDate']);
    }

    public function testRepeatingContactAndWeightQuantityExtractAlongsidePatient(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ExtractServiceInterface $extractService */
        $extractService = $container->get(ExtractServiceInterface::class);
        /** @var FHIRVersionedSerializationServiceLocator $locator */
        $locator = $container->get(FHIRVersionedSerializationServiceLocator::class);
        $service = $locator->get(FhirVersion::R4);

        $fixture = $this->fixture();
        $answers = [
            'patient' => [
                'given'          => 'Jane',
                'family'         => 'Doe',
                'hasAllergies'   => 'true',
                'allergyDetails' => 'Penicillin',
                'contact'        => [
                    '0' => ['contactGiven' => 'Alice', 'contactFamily' => 'Anderson'],
                    '1' => ['contactGiven' => 'Bob', 'contactFamily' => 'Baker'],
                ],
            ],
            'weightObs' => ['weight' => '70.5 kg'],
        ];

        $questionnaireResponse = (new QuestionnaireResponseBuilder())->build($fixture['item'], $answers);
        $questionnaireModel    = $service->deserialize(json_encode($fixture, JSON_THROW_ON_ERROR), QuestionnaireResource::class);

        $result     = $extractService->extract($questionnaireResponse, new ExtractContext(fhirVersion: FhirVersion::R4, questionnaire: $questionnaireModel));
        $bundleJson = $service->serializeToJson($result->getResource());
        $bundle     = json_decode($bundleJson, true, 512, JSON_THROW_ON_ERROR);

        self::assertCount(2, $bundle['entry'], 'expected a Patient entry and a separate Observation entry: ' . $bundleJson);

        $byType = [];
        foreach ($bundle['entry'] as $entry) {
            $byType[$entry['resource']['resourceType']] = $entry['resource'];
        }

        self::assertSame('Jane', $byType['Patient']['name'][0]['given'][0]);
        self::assertCount(2, $byType['Patient']['contact']);
        self::assertSame('Anderson', $byType['Patient']['contact'][0]['name']['family']);
        self::assertSame('Baker', $byType['Patient']['contact'][1]['name']['family']);
        // hasAllergies/allergyDetails carry no `definition` (pure enableWhen demo fields) — they are
        // collected in the QR but deliberately not extracted onto Patient.
        self::assertArrayNotHasKey('allergyDetails', $byType['Patient']);

        self::assertEqualsWithDelta(70.5, $byType['Observation']['valueQuantity']['value'], 0.0001);
        self::assertSame('kg', $byType['Observation']['valueQuantity']['unit']);
    }

    public function testPopulatePrefillsFieldsFromLaunchContextPatient(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var PopulateServiceInterface $populateService */
        $populateService = $container->get(PopulateServiceInterface::class);
        /** @var FHIRVersionedSerializationServiceLocator $locator */
        $locator = $container->get(FHIRVersionedSerializationServiceLocator::class);
        $service = $locator->get(FhirVersion::R4);

        $fixture            = $this->fixture();
        $questionnaireModel = $service->deserialize(
            json_encode($fixture, JSON_THROW_ON_ERROR),
            QuestionnaireResource::class,
        );

        $patient = $service->deserialize(
            json_encode([
                'resourceType' => 'Patient',
                'active'       => true,
                'name'         => [['given' => ['Jane'], 'family' => 'Doe']],
                'birthDate'    => '1993-04-02',
            ], JSON_THROW_ON_ERROR),
            PatientResource::class,
        );

        $result = $populateService->populate($questionnaireModel, new PopulateContext(
            fhirVersion: FhirVersion::R4,
            launchContextResources: ['patient' => $patient],
        ));

        $renderer = new QuestionnaireFormRenderer();
        $fields   = $renderer->renderFromResponse($fixture['item'], $result->getResponse());

        $byLinkId = [];
        foreach ($fields[0]['instances'][0] as $child) {
            $byLinkId[$child['linkId']] = $child;
        }

        self::assertSame('true', $byLinkId['active']['values'][0]);
        self::assertSame('Jane', $byLinkId['given']['values'][0]);
        self::assertSame('Doe', $byLinkId['family']['values'][0]);
        self::assertSame('1993-04-02', $byLinkId['dob']['values'][0]);
    }

    /**
     * Real-world regression: a Patient with both a "usual" (nickname) and an "official" name entry must
     * prefer the official one for given/family — before this fixture fix, given/family picked whichever
     * name entry happened to be first in the array (here, "usual"). Gender must also populate from a bare
     * `code` value (`%patient.gender` evaluates to the string "male", not a `Coding`) — before the
     * `AnswerValueCoercer::promoteToOptionCoding()` fix, any bare scalar for a `choice`-typed item was
     * rejected outright as a type mismatch, so gender silently never populated no matter what.
     */
    public function testPopulatePrefersOfficialNameOverUsualAndPopulatesGenderFromBareCode(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var PopulateServiceInterface $populateService */
        $populateService = $container->get(PopulateServiceInterface::class);
        /** @var FHIRVersionedSerializationServiceLocator $locator */
        $locator = $container->get(FHIRVersionedSerializationServiceLocator::class);
        $service = $locator->get(FhirVersion::R4);

        $fixture            = $this->fixture();
        $questionnaireModel = $service->deserialize(
            json_encode($fixture, JSON_THROW_ON_ERROR),
            QuestionnaireResource::class,
        );

        $patient = $service->deserialize(
            json_encode([
                'resourceType' => 'Patient',
                'name'         => [
                    ['use' => 'usual', 'given' => ['alex']],
                    ['use' => 'official', 'family' => 'murray', 'given' => ['alex']],
                ],
                'gender' => 'male',
            ], JSON_THROW_ON_ERROR),
            PatientResource::class,
        );

        $result = $populateService->populate($questionnaireModel, new PopulateContext(
            fhirVersion: FhirVersion::R4,
            launchContextResources: ['patient' => $patient],
        ));

        $renderer = new QuestionnaireFormRenderer();
        $fields   = $renderer->renderFromResponse($fixture['item'], $result->getResponse());

        $byLinkId = [];
        foreach ($fields[0]['instances'][0] as $child) {
            $byLinkId[$child['linkId']] = $child;
        }

        self::assertSame('murray', $byLinkId['family']['values'][0]);
        self::assertSame('alex', $byLinkId['given']['values'][0]);
        self::assertSame('0', $byLinkId['gender']['values'][0]); // index 0 = "male" in the answerOption list
    }
}
