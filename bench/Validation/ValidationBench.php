<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Benchmarks\Validation;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\CoreStructureDefinitionPackages;
use PhpBench\Attributes as Bench;

/**
 * Benchmarks for validating deserialized FHIR resources.
 *
 * Each subject validates an object graph deserialized once in setUp, so only validation is timed.
 * The service is also built and warmed in setUp: the first validation of a class pays for attribute
 * and reflection caches, and that one-off cost would otherwise swamp the steady state.
 *
 * Inputs come from the FHIR test cases package (a dev dependency): single R4 and R5 resources, and
 * two R4 document Bundles of 55 and 34 entries. FHIRPath invariants run in every subject, because
 * they are attributes on the generated classes. The terminology subject adds an in-memory
 * terminology client, and the runtime-profile subject resolves the core `bp` profile from the
 * trimmed core package fixtures at runtime.
 */
#[Bench\BeforeMethods(['setUp'])]
#[Bench\Revs(5)]
#[Bench\Iterations(5)]
#[Bench\Warmup(1)]
class ValidationBench
{
    private const string TEST_CASES = __DIR__ . '/../../vendor/fhir/fhir-test-cases/';

    private const string BP_PROFILE = 'http://hl7.org/fhir/StructureDefinition/bp';

    private FHIRValidationService $service;

    private FHIRValidationService $terminologyService;

    private FHIRValidationService $runtimeProfileService;

    private object $patientR4;

    private object $observationR5;

    private object $diagnosticReportR5;

    private object $ipsBundleR4;

    private object $labBundleR4;

    private object $observationR4;

    public function setUp(): void
    {
        $r4 = FHIRSerializationService::createDefault(FhirVersion::R4);
        $r5 = FHIRSerializationService::createDefault(FhirVersion::R5);

        $this->patientR4          = self::load($r4, FhirVersion::R4, 'r4/examples/patient-example.json');
        $this->observationR4      = self::load($r4, FhirVersion::R4, 'r4/examples/observation-example.json');
        $this->ipsBundleR4        = self::load($r4, FhirVersion::R4, 'validator/ips-nz-pj.json');
        $this->labBundleR4        = self::load($r4, FhirVersion::R4, 'validator/molis_1A.json');
        $this->observationR5      = self::load($r5, FhirVersion::R5, 'r5/pe-observation-1.json');
        $this->diagnosticReportR5 = self::load($r5, FhirVersion::R5, 'r5/diagnosticreport-eric.json');

        $this->service            = FHIRValidationServiceBuilder::create()->build();
        $this->terminologyService = FHIRValidationServiceBuilder::create()
            ->withTerminologyClient(new InMemoryFHIRTerminologyClient([]))
            ->build();

        $packages = CoreStructureDefinitionPackages::parityFixtures(FhirVersion::R4);
        if ($packages === null) {
            throw new \RuntimeException('The trimmed R4 core package fixture is missing.');
        }
        $this->runtimeProfileService = FHIRValidationServiceBuilder::create()->withPackageDirectories($packages)->build();

        // Warm every class the subjects touch, so the measured revolutions are steady state
        foreach ([$this->patientR4, $this->observationR4, $this->ipsBundleR4, $this->labBundleR4, $this->observationR5, $this->diagnosticReportR5] as $resource) {
            $this->service->validate($resource);
        }
        $this->terminologyService->validate($this->ipsBundleR4);
        $this->runtimeProfileService->validate($this->observationR4, [self::BP_PROFILE]);
    }

    public function benchPatientR4(): void
    {
        $this->service->validate($this->patientR4);
    }

    public function benchObservationR5(): void
    {
        $this->service->validate($this->observationR5);
    }

    public function benchDiagnosticReportR5(): void
    {
        $this->service->validate($this->diagnosticReportR5);
    }

    /**
     * A 55-entry International Patient Summary document Bundle.
     */
    public function benchIpsBundleR4(): void
    {
        $this->service->validate($this->ipsBundleR4);
    }

    /**
     * A 34-entry laboratory report document Bundle, 27 of them Observations.
     */
    public function benchLabBundleR4(): void
    {
        $this->service->validate($this->labBundleR4);
    }

    /**
     * The IPS Bundle again, with bindings checked against an in-memory terminology client.
     */
    public function benchIpsBundleR4WithTerminology(): void
    {
        $this->terminologyService->validate($this->ipsBundleR4);
    }

    /**
     * An Observation checked against the core `bp` profile, resolved from a StructureDefinition at runtime.
     */
    public function benchObservationR4RuntimeProfile(): void
    {
        $this->runtimeProfileService->validate($this->observationR4, [self::BP_PROFILE]);
    }

    private static function load(FHIRSerializationService $serializer, FhirVersion $version, string $file): object
    {
        $json = (string) file_get_contents(self::TEST_CASES . $file);
        /** @var array{resourceType: string} $data */
        $data  = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        $class = sprintf('Ardenexal\\FHIRTools\\Component\\Models\\%s\\Resource\\%sResource', $version->value, $data['resourceType']);
        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('No generated class for %s in %s.', $data['resourceType'], $file));
        }

        return $serializer->deserializeFromJson($json, $class);
    }
}
