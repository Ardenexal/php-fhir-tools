<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\ComplexExtensionChecker;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\CompositeStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\CoreStructureDefinitionPackages;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * An extension validated against its definition loaded at runtime reports exactly what its generated
 * class reports.
 *
 * Each document is read twice. The first read gives the generated extension classes, the way any
 * document is read. For the second, each extension's url is moved to an alias no generated class
 * claims, and its definition is registered at runtime under that alias, so the extension is read as
 * a plain `Extension` in document order and only the runtime definition can say anything about it.
 * With the alias put back, the two reports must match finding for finding.
 *
 * Findings inside an extension's value are left out: a typed class also exposes each value as a
 * property, which the walk reaches first, so the same finding has another path there. That is a
 * difference in the objects, not in what is checked. A finding about the value itself, such as a
 * binding or an invariant on `Extension.value[x]`, is kept: both report it at `.value`.
 *
 * The definitions are committed fixtures copied from the extensions package the classes were built
 * from, by `tests/Fixtures/Profile/seed-core-profiles.php`.
 */
#[CoversClass(FHIRValidationService::class)]
#[CoversClass(ComplexExtensionChecker::class)]
#[CoversClass(RuntimeProfileResolver::class)]
final class RuntimeExtensionParityTest extends TestCase
{
    private const string SD = 'http://hl7.org/fhir/StructureDefinition/';

    /** Where the second read finds each extension: a url no generated class claims. */
    private const string ALIAS = 'http://example.org/runtime-only/';

    /** The extensions the documents use, each seeded into the fixtures. */
    private const array EXTENSIONS = [
        'patient-animal', 'geolocation', 'patient-birthPlace', 'patient-mothersMaidenName', 'data-absent-reason',
        'allergyintolerance-substanceExposureRisk', 'resource-instance-description', 'servicerequest-order-callback-phone-number',
    ];

    /**
     * @return iterable<string, array{FhirVersion, string, int}> release, document, how many findings it must at least have
     */
    public static function documents(): iterable
    {
        $animal  = static fn (array $subExtensions): array => ['url' => self::SD . 'patient-animal', 'extension' => $subExtensions];
        $species = ['url' => 'species', 'valueCodeableConcept' => ['text' => 'dog']];

        $cases = [
            'well formed' => [[
                'resourceType' => 'Patient',
                'extension'    => [
                    $animal([$species, ['url' => 'breed', 'valueCodeableConcept' => ['text' => 'poodle']]]),
                    ['url' => self::SD . 'patient-birthPlace', 'valueAddress' => ['city' => 'Melbourne']],
                    ['url' => self::SD . 'patient-mothersMaidenName', 'valueString' => 'Smith'],
                ],
                'address' => [['city' => 'Melbourne', 'extension' => [[
                    'url'       => self::SD . 'geolocation',
                    'extension' => [['url' => 'latitude', 'valueDecimal' => -37.8], ['url' => 'longitude', 'valueDecimal' => 144.9]],
                ]]]],
            ], 0],
            'missing required sub-extension' => [[
                'resourceType' => 'Patient',
                'extension'    => [$animal([['url' => 'breed', 'valueCodeableConcept' => ['text' => 'poodle']]])],
                'address'      => [['extension' => [['url' => self::SD . 'geolocation', 'extension' => [['url' => 'latitude', 'valueDecimal' => 1]]]]]],
            ], 2],
            'undeclared sub-extension' => [[
                'resourceType' => 'Patient',
                'extension'    => [$animal([$species, ['url' => 'colour', 'valueString' => 'brown']])],
            ], 1],
            'undeclared sub-extensions around declared ones' => [[
                'resourceType' => 'Patient',
                'extension'    => [$animal([
                    ['url' => 'colour', 'valueString' => 'brown'],
                    $species,
                    ['url' => 'size', 'valueString' => 'small'],
                    ['url' => 'breed', 'valueCodeableConcept' => ['text' => 'poodle']],
                    ['url' => 'id', 'valueString' => 'a parameter name'],
                ])],
            ], 2],
            'repeated single slice with equal values' => [[
                'resourceType' => 'Patient',
                'address'      => [['extension' => [[
                    'url'       => self::SD . 'geolocation',
                    'extension' => [
                        ['url' => 'latitude', 'valueDecimal' => 1],
                        ['url' => 'colour', 'valueString' => 'brown'],
                        ['url' => 'latitude', 'valueDecimal' => 1],
                        ['url' => 'longitude', 'valueDecimal' => 2],
                    ],
                ]]]],
            ], 1],
            'value of another type' => [[
                'resourceType' => 'Patient',
                'extension'    => [
                    ['url' => self::SD . 'patient-birthPlace', 'valueString' => 'Melbourne'],
                    ['url' => self::SD . 'patient-mothersMaidenName', 'valueCoding' => ['code' => 'x']],
                ],
            ], 1],
            'modifier extension' => [[
                'resourceType'      => 'Patient',
                'modifierExtension' => [['url' => self::SD . 'patient-mothersMaidenName', 'valueString' => 'Smith']],
            ], 0],
            'outside its context' => [[
                'resourceType' => 'Observation',
                'status'       => 'final',
                'code'         => ['text' => 'x'],
                'extension'    => [
                    ['url' => self::SD . 'patient-birthPlace', 'valueAddress' => ['city' => 'Melbourne']],
                    $animal([$species]),
                ],
            ], 2],
            'context invariant' => [[
                'resourceType' => 'AllergyIntolerance',
                'patient'      => ['reference' => 'Patient/1'],
                'code'         => ['text' => 'peanut'],
                'extension'    => [[
                    'url'       => self::SD . 'allergyintolerance-substanceExposureRisk',
                    'extension' => [['url' => 'substance', 'valueCodeableConcept' => ['text' => 'peanut']], ['url' => 'exposureRisk', 'valueCodeableConcept' => ['text' => 'known']]],
                ]],
            ], 1],
            'data absent reason on a primitive' => [[
                'resourceType' => 'Patient',
                '_gender'      => ['extension' => [['url' => self::SD . 'data-absent-reason', 'valueCode' => 'unknown']]],
            ], 0],
            'invariant using the resource around the extension' => [[
                'resourceType' => 'Questionnaire',
                'status'       => 'draft',
                'description'  => 'A questionnaire',
                'extension'    => [['url' => self::SD . 'resource-instance-description', 'valueMarkdown' => 'Another description']],
            ], 1],
            'invariant on the value' => [[
                'resourceType' => 'ServiceRequest',
                'status'       => 'active',
                'intent'       => 'order',
                'subject'      => ['reference' => 'Patient/1'],
                'extension'    => [['url' => self::SD . 'servicerequest-order-callback-phone-number', 'valueContactPoint' => ['system' => 'email', 'value' => 'a@example.org']]],
            ], 1],
            'binding on the value' => [[
                'resourceType' => 'Patient',
                '_gender'      => ['extension' => [['url' => self::SD . 'data-absent-reason', 'valueCode' => 'unicorn']]],
            ], 1],
        ];

        foreach (['R4' => FhirVersion::R4, 'R4B' => FhirVersion::R4B, 'R5' => FhirVersion::R5] as $label => $release) {
            foreach ($cases as $name => [$document, $atLeast]) {
                yield "{$label} {$name}" => [$release, json_encode($document, JSON_THROW_ON_ERROR), $atLeast];
            }
        }
    }

    #[DataProvider('documents')]
    public function testAPlainExtensionMatchesItsGeneratedClass(FhirVersion $release, string $json, int $atLeast): void
    {
        $fixtures = CoreStructureDefinitionPackages::parityFixtures($release);
        if ($fixtures === null) {
            self::markTestSkipped("No {$release->value} fixtures; run tests/Fixtures/Profile/seed-core-profiles.php");
        }

        $loader   = new PackageDirectoryStructureDefinitionRegistry($fixtures, $release->value);
        $aliased  = [];
        foreach (self::EXTENSIONS as $name) {
            $definition = $loader->find(self::SD . $name, $release->value);
            self::assertNotNull($definition, "{$name} is not in the {$release->value} fixtures");
            $aliased[] = ['url' => self::ALIAS . $name] + $definition;
        }

        $serialization = FHIRSerializationService::createDefault($release);
        $service       = OracleValidationServiceFactory::create(
            $release,
            true,
            new RuntimeProfileResolver(new CompositeStructureDefinitionRegistry([$loader, new InMemoryStructureDefinitionRegistry($aliased)])),
        );
        $class = self::resourceClass($release, $json);

        $plainJson = $json;
        foreach (self::EXTENSIONS as $name) {
            $plainJson = str_replace(json_encode(self::SD . $name, JSON_THROW_ON_ERROR), json_encode(self::ALIAS . $name, JSON_THROW_ON_ERROR), $plainJson);
        }

        $generated = self::extensionFindings($service->validate($serialization->deserializeFromJson($json, $class))->violations);
        $runtime   = self::extensionFindings($service->validate($serialization->deserializeFromJson($plainJson, $class))->violations);

        self::assertGreaterThanOrEqual($atLeast, count($generated), implode("\n", $generated));
        self::assertNotSame($json, $plainJson);
        self::assertSame($generated, $runtime);
    }

    /**
     * The findings about extensions, as comparable lines, sorted.
     *
     * @param list<FHIRValidationViolation> $violations
     *
     * @return list<string>
     */
    private static function extensionFindings(array $violations): array
    {
        $lines = [];
        foreach ($violations as $violation) {
            $path = $violation->path;
            if (preg_match('/xtension\[\d+\]\.extension\[\d+\]\./', $path) === 1 || preg_match('/xtension\[\d+\]\.(?!extension)(?!value$)[a-z]/', $path) === 1) {
                continue;
            }
            if (str_contains($path, 'xtension') || str_contains($violation->message, 'xtension')) {
                // An extension's own invariants and bindings run in the default group, which is no profile
                if (in_array($violation->constraintClass, [FHIRProfileInvariant::class, FHIRProfileBinding::class], true)) {
                    self::assertNull($violation->profileGroup, "{$path}: {$violation->message}");
                }
                $lines[] = str_replace(self::ALIAS, self::SD, "{$violation->severity} {$path}: {$violation->message}");
            }
        }
        sort($lines);

        return $lines;
    }

    /**
     * @return class-string
     */
    private static function resourceClass(FhirVersion $release, string $json): string
    {
        /** @var array{resourceType: string} $document */
        $document = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        /** @var class-string $class */
        $class = "Ardenexal\\FHIRTools\\Component\\Models\\{$release->value}\\Resource\\{$document['resourceType']}Resource";

        return $class;
    }
}
