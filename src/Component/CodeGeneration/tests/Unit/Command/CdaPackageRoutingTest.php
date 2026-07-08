<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\CodeGeneration\tests\Unit\Command;

use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;

/**
 * Tests CDA package routing in FHIRModelGeneratorCommand: isCdaPackage() detection,
 * ensureTerminologyPackages() bypass for CDA-only runs, and loadCdaPackageDefinitions()
 * routing of logical-model SDs into the dedicated CDA context.
 *
 * @covers \Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand
 */
final class CdaPackageRoutingTest extends TestCase
{
    private FHIRModelGeneratorCommand $command;

    private \ReflectionClass $reflection;

    protected function setUp(): void
    {
        $loader           = $this->createStub(PackageLoader::class);
        $this->command    = new FHIRModelGeneratorCommand(new Filesystem(), $loader);
        $this->reflection = new \ReflectionClass($this->command);
    }

    private function callPrivate(string $method, mixed ...$args): mixed
    {
        $m = $this->reflection->getMethod($method);
        $m->setAccessible(true);

        return $m->invoke($this->command, ...$args);
    }

    /** @return array<string, BuilderContext> */
    private function getContext(): array
    {
        $property = $this->reflection->getProperty('context');
        $property->setAccessible(true);

        /** @var array<string, BuilderContext> $context */
        $context = $property->getValue($this->command);

        return $context;
    }

    // ---- loadCdaPackageDefinitions ------------------------------------------

    public function testLoadCdaPackageDefinitionsRoutesLogicalModelToCdaContextOnly(): void
    {
        // PackageLoader returns definitions keyed by canonical URL.
        $definitions = [
            'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument' => [
                'resourceType' => 'StructureDefinition',
                'url'          => 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
                'name'         => 'ClinicalDocument',
                'kind'         => 'logical',
                'derivation'   => 'specialization',
            ],
        ];

        $result = $this->callPrivate('loadCdaPackageDefinitions', $definitions);

        self::assertSame(['CDA'], $result);

        $context = $this->getContext();
        self::assertNotEmpty($context['CDA']->getDefinitions(), 'CDA SD should land in the CDA context');
        self::assertSame([], $context['R5']->getDefinitions(), 'CDA SD must not leak into the R5 context');
    }

    public function testLoadCdaPackageDefinitionsReturnsEmptyForTerminologyOnlyPackage(): void
    {
        // PackageLoader returns definitions keyed by canonical URL.
        $definitions = [
            'http://hl7.org/cda/stds/core/ValueSet/NullFlavor' => [
                'resourceType' => 'ValueSet',
                'url'          => 'http://hl7.org/cda/stds/core/ValueSet/NullFlavor',
                'name'         => 'NullFlavor',
            ],
            'http://hl7.org/cda/stds/core/StructureDefinition/Constrained' => [
                // logical but derivation=constraint → not a generatable specialization
                'resourceType' => 'StructureDefinition',
                'url'          => 'http://hl7.org/cda/stds/core/StructureDefinition/Constrained',
                'name'         => 'Constrained',
                'kind'         => 'logical',
                'derivation'   => 'constraint',
            ],
        ];

        $result = $this->callPrivate('loadCdaPackageDefinitions', $definitions);

        self::assertSame([], $result, 'A package with no logical+specialization SD contributes no version');
    }

    // ---- isCdaPackage -------------------------------------------------------

    #[DataProvider('cdaPackageNames')]
    public function testIsCdaPackageReturnsTrueForCdaPackages(string $name): void
    {
        self::assertTrue($this->callPrivate('isCdaPackage', $name));
    }

    /** @return array<string, array{0: string}> */
    public static function cdaPackageNames(): array
    {
        return [
            'hl7.cda.uv.core'                   => ['hl7.cda.uv.core'],
            'hl7.cda.uv.core versioned'         => ['hl7.cda.uv.core#2.0.2-sd'],
            'au.digitalhealth.cda.schema'       => ['au.digitalhealth.cda.schema'],
            'hl7.cda.us.ccda'                   => ['hl7.cda.us.ccda'],
        ];
    }

    #[DataProvider('nonCdaPackageNames')]
    public function testIsCdaPackageReturnsFalseForNonCdaPackages(string $name): void
    {
        self::assertFalse($this->callPrivate('isCdaPackage', $name));
    }

    /** @return array<string, array{0: string}> */
    public static function nonCdaPackageNames(): array
    {
        return [
            'hl7.fhir.r4.core'              => ['hl7.fhir.r4.core'],
            'hl7.fhir.r5.core'              => ['hl7.fhir.r5.core'],
            'hl7.fhir.r4b.core'             => ['hl7.fhir.r4b.core'],
            'hl7.terminology.r5'            => ['hl7.terminology.r5'],
            'au.digitalhealth.au-core'      => ['au.digitalhealth.au-core'],
            'us.nlm.vsac'                   => ['us.nlm.vsac'],
            'hl7.cda'                       => ['hl7.cda'],  // prefix must include trailing dot
        ];
    }

    // ---- ensureTerminologyPackages ------------------------------------------

    public function testEnsureTerminologyPackagesSkipsInjectionForCdaOnlyList(): void
    {
        $input  = ['hl7.cda.uv.core#2.0.2-sd'];
        $result = $this->callPrivate('ensureTerminologyPackages', $input);

        // No terminology package should be prepended
        self::assertSame($input, $result);
    }

    public function testEnsureTerminologyPackagesSkipsInjectionForMultipleCdaPackages(): void
    {
        $input = [
            'hl7.cda.uv.core#2.0.2-sd',
            'au.digitalhealth.cda.schema#1.0.1',
        ];
        $result = $this->callPrivate('ensureTerminologyPackages', $input);

        self::assertSame($input, $result);
    }

    public function testEnsureTerminologyPackagesInjectsTerminologyForFhirPackages(): void
    {
        $input  = ['hl7.fhir.r4.core#4.0.1'];
        $result = $this->callPrivate('ensureTerminologyPackages', $input);

        // Must prepend a terminology package for R4
        self::assertNotSame($input, $result);
        self::assertGreaterThan(count($input), count($result));

        $firstPackageName = explode('#', $result[0])[0];
        self::assertStringContainsString('terminology', $firstPackageName);
    }
}
