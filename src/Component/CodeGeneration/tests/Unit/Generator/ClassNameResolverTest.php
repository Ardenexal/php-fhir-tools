<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\CodeGeneration\tests\Unit\Generator;

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

    /**
     * AU CDA additions are named from the URL id and `Au`-prefixed (their `name` fields collide
     * with core classes — e.g. au-Address is named `AD` — or are typos / non-identifiers).
     *
     * @param string $url
     * @param string $name
     * @param string $expected
     */
    #[DataProvider('auLogicalModelNameProvider')]
    public function testAuLogicalModelClassNameIsAuPrefixedAndCollisionFree(string $url, string $name, string $expected): void
    {
        self::assertSame($expected, ClassNameResolver::logicalModelClassName($url, $name));
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function auLogicalModelNameProvider(): array
    {
        return [
            'au- prefix folded into Au'      => ['http://ns.electronichealth.net.au/cda/StructureDefinition/au-ClinicalDocument', 'au-ClinicalDocument', 'AuClinicalDocument'],
            'name collides with core AD'     => ['http://ns.electronichealth.net.au/cda/StructureDefinition/au-Address', 'AD', 'AuAddress'],
            'typo name ignored, id wins'     => ['http://ns.electronichealth.net.au/cda/StructureDefinition/addr', 'addrress', 'AuAddr'],
            'brand-new au type from id'      => ['http://ns.electronichealth.net.au/cda/StructureDefinition/asEntityIdentifier', 'asEntityIdentifier', 'AuAsEntityIdentifier'],
        ];
    }

    public function testCoreLogicalModelClassNameUnaffectedByAuRule(): void
    {
        self::assertSame('II', ClassNameResolver::logicalModelClassName('http://hl7.org/cda/stds/core/StructureDefinition/II', 'II'));
        self::assertSame('ClinicalDocument', ClassNameResolver::logicalModelClassName('http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument', 'ClinicalDocument'));
    }

    public function testAuValueSetEnumNameIsAuPrefixedWhileCoreStripsCdaPrefix(): void
    {
        // AU ValueSet → derived from id, Au-prefixed (avoids case-collision with core EntityNameUse).
        self::assertSame(
            'AuDhEntitynameuse',
            ClassNameResolver::cdaEnumClassName('http://ns.electronichealth.net.au/cda/ValueSet/dh-entitynameuse', 'Entity Name Use'),
        );
        // Core ValueSet → unchanged: the redundant CDA qualifier is stripped.
        self::assertSame(
            'EntityNameUse',
            ClassNameResolver::cdaEnumClassName('http://hl7.org/cda/stds/core/ValueSet/CDAEntityNameUse', 'CDAEntityNameUse'),
        );
    }
}
