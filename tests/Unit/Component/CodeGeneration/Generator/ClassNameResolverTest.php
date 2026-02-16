<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ClassNameResolver;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for ClassNameResolver.
 *
 * Tests the class name resolution logic to ensure proper conversion of
 * FHIR definition names to PHP class names.
 */
class ClassNameResolverTest extends TestCase
{
    #[DataProvider('classNameConversionProvider')]
    public function testResolvesClassName(string $url, string $name, string $expected): void
    {
        $className = ClassNameResolver::resolveClassName($url, $name);
        self::assertEquals($expected, $className);
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function classNameConversionProvider(): array
    {
        return [
            'basic PascalCase preservation' => [
                'http://hl7.org/fhir/StructureDefinition/Patient',
                'Patient',
                'Patient',
            ],
            'lowercase to PascalCase' => [
                'http://hl7.org/fhir/StructureDefinition/patient',
                'patient',
                'Patient',
            ],
            'hyphenated to PascalCase' => [
                'http://hl7.org/fhir/StructureDefinition/administrative-gender',
                'administrative-gender',
                'AdministrativeGender',
            ],
            'underscored to PascalCase' => [
                'http://hl7.org/fhir/StructureDefinition/administrative_gender',
                'administrative_gender',
                'AdministrativeGender',
            ],
            'spaces to PascalCase' => [
                'http://hl7.org/fhir/StructureDefinition/human-name',
                'human name',
                'HumanName',
            ],
            'already PascalCase' => [
                'http://hl7.org/fhir/StructureDefinition/HumanName',
                'HumanName',
                'HumanName',
            ],
            'mixed case to PascalCase' => [
                'http://hl7.org/fhir/StructureDefinition/CamelCaseExample',
                'camelCaseExample',
                'CamelCaseExample',
            ],
        ];
    }

    /**
     * Test that URL overrides take precedence over name conversion
     */
    public function testResolvesOverriddenClassName(): void
    {
        $className = ClassNameResolver::resolveClassName(
            'http://hl7.org/fhir/ValueSet/claim-use',
            'claim-use',
        );

        self::assertEquals('ClaimUse', $className);
    }
}
