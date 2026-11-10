<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistryFactory;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRConstraintValidatorFactory;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRReferenceResolver;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * The builder's service reads the generated models' attributes and resolves profiles from the
 * sources it is given. Its runtime profile and slice wiring is compared with the harness's in
 * RuntimeProfileValidationTest.
 */
#[CoversClass(FHIRValidationServiceBuilder::class)]
#[CoversClass(FHIRConstraintValidatorFactory::class)]
final class FHIRValidationServiceBuilderTest extends TestCase
{
    private const string PACKAGE = __DIR__ . '/../Fixtures/Profile/package-r4';

    private const string BP = 'http://hl7.org/fhir/StructureDefinition/bp';

    /**
     * A validator without attribute mapping finds nothing at all, so a base rule proves it is on.
     */
    public function testTheGeneratedModelsRulesAreChecked(): void
    {
        $service = FHIRValidationServiceBuilder::create()->build();

        $report = $service->validate(self::observation('{"resourceType":"Observation","code":{"text":"x"}}'));

        self::assertContains('status', self::errorPaths($report->violations));
    }

    public function testAProfileFromAPackageDirectoryIsChecked(): void
    {
        $builder = FHIRValidationServiceBuilder::create();
        $service = $builder->withPackageDirectories(self::PACKAGE)->build();

        $report = $service->validate(self::observation('{"resourceType":"Observation","status":"final","code":{"text":"x"}}'), [self::BP]);

        self::assertContains(self::BP, array_map(static fn (FHIRValidationViolation $v): ?string => $v->profileGroup, $report->violations));

        // with*() returns a new builder; the original still has no sources
        $unconfigured = $builder->build()->validate(self::observation('{"resourceType":"Observation","status":"final","code":{"text":"x"}}'), [self::BP]);
        self::assertSame([], $unconfigured->uncheckedProfiles());
        self::assertNotContains(self::BP, array_map(static fn (FHIRValidationViolation $v): ?string => $v->profileGroup, $unconfigured->violations));
    }

    /**
     * Every source and collaborator the builder takes reaches the service: a file overrides the
     * package's bp, and the custom registry and message registry are used.
     */
    public function testEverySourceAndCollaboratorIsUsed(): void
    {
        $file = sys_get_temp_dir() . '/fhir-builder-bp-' . bin2hex(random_bytes(4)) . '.json';
        file_put_contents($file, json_encode([
            'resourceType' => 'StructureDefinition', 'url' => self::BP, 'fhirVersion' => '4.0.1', 'kind' => 'resource',
            'type'         => 'Observation', 'derivation' => 'constraint', 'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Observation',
            'snapshot'     => ['element' => [['id' => 'Observation', 'path' => 'Observation']]],
            'differential' => ['element' => [['id' => 'Observation', 'path' => 'Observation', 'constraint' => [
                ['key' => 'loc-1', 'severity' => 'error', 'human' => 'The local bp applies', 'expression' => "status = 'cancelled'"],
            ]]]],
        ], JSON_THROW_ON_ERROR));
        $custom = new InMemoryStructureDefinitionRegistry([[
            'resourceType'   => 'StructureDefinition', 'url' => 'http://example.org/fhir/StructureDefinition/custom', 'fhirVersion' => '4.0.1',
            'kind'           => 'resource', 'type' => 'Observation', 'derivation' => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Observation',
            'snapshot'       => ['element' => [['id' => 'Observation', 'path' => 'Observation']]],
            'differential'   => ['element' => [['id' => 'Observation', 'path' => 'Observation', 'constraint' => [
                ['key' => 'cus-1', 'severity' => 'error', 'human' => 'The custom registry applies', 'expression' => 'false'],
            ]]]],
        ]]);

        try {
            $service = FHIRValidationServiceBuilder::create()
                ->withPackageDirectories(self::PACKAGE)
                ->withStructureDefinitionFiles($file)
                ->withStructureDefinitionRegistry($custom)
                ->withTerminologyClient(new NullFHIRTerminologyClient())
                ->withReferenceResolver(new NullFHIRReferenceResolver())
                ->withIgTypeRegistry(FHIRIGTypeRegistryFactory::create())
                ->withMessageRegistry(new FHIRValidationMessageRegistry())
                ->build();
        } finally {
            unlink($file);
        }

        $report = $service->validate(
            self::observation('{"resourceType":"Observation","status":"final","code":{"text":"x"}}'),
            [self::BP, 'http://example.org/fhir/StructureDefinition/custom'],
        );
        $messages = array_map(static fn (FHIRValidationViolation $v): string => $v->message, $report->violations);

        self::assertContains('The local bp applies', $messages);
        self::assertContains('The custom registry applies', $messages);
    }

    public function testAMissingPackageDirectoryFailsWhenBuilding(): void
    {
        $builder = FHIRValidationServiceBuilder::create()->withPackageDirectories(self::PACKAGE . '/missing');

        $this->expectException(\InvalidArgumentException::class);
        $builder->build();
    }

    private static function observation(string $json): object
    {
        return FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($json);
    }

    /**
     * @param list<FHIRValidationViolation> $violations
     *
     * @return list<string>
     */
    private static function errorPaths(array $violations): array
    {
        return array_values(array_map(
            static fn (FHIRValidationViolation $v): string => $v->path,
            array_filter($violations, static fn (FHIRValidationViolation $v): bool => $v->severity === 'error'),
        ));
    }
}
