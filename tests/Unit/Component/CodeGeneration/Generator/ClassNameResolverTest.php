<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ClassNameResolver;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for ClassNameResolver.
 *
 * Tests the class name resolution logic to ensure proper conversion of
 * FHIR definition names to PHP class names.
 */
class ClassNameResolverTest extends TestCase
{
    /**
     * Test basic class name resolution converts to PascalCase.
     */
    public function testResolvesBasicClassName(): void
    {
        $className = ClassNameResolver::resolveClassName(
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'Patient',
        );

        self::assertEquals('Patient', $className);
    }

    /**
     * Test class name resolution with lowercase input.
     */
    public function testResolvesLowercaseClassName(): void
    {
        $className = ClassNameResolver::resolveClassName(
            'http://hl7.org/fhir/StructureDefinition/patient',
            'patient',
        );

        self::assertEquals('Patient', $className);
    }

    /**
     * Test class name resolution with hyphenated input.
     */
    public function testResolvesHyphenatedClassName(): void
    {
        $className = ClassNameResolver::resolveClassName(
            'http://hl7.org/fhir/StructureDefinition/administrative-gender',
            'administrative-gender',
        );

        self::assertEquals('AdministrativeGender', $className);
    }

    /**
     * Test class name resolution with underscored input.
     */
    public function testResolvesUnderscoredClassName(): void
    {
        $className = ClassNameResolver::resolveClassName(
            'http://hl7.org/fhir/StructureDefinition/administrative_gender',
            'administrative_gender',
        );

        self::assertEquals('AdministrativeGender', $className);
    }

    /**
     * Test class name resolution with override.
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
     * Test class name resolution with spaces.
     */
    public function testResolvesClassNameWithSpaces(): void
    {
        $className = ClassNameResolver::resolveClassName(
            'http://hl7.org/fhir/StructureDefinition/human-name',
            'human name',
        );

        self::assertEquals('HumanName', $className);
    }

    /**
     * Test class name resolution preserves already PascalCase names.
     */
    public function testPreservesPascalCaseClassName(): void
    {
        $className = ClassNameResolver::resolveClassName(
            'http://hl7.org/fhir/StructureDefinition/HumanName',
            'HumanName',
        );

        self::assertEquals('HumanName', $className);
    }

    /**
     * Test class name resolution with mixed case input.
     */
    public function testResolvesMixedCaseClassName(): void
    {
        $className = ClassNameResolver::resolveClassName(
            'http://hl7.org/fhir/StructureDefinition/CamelCaseExample',
            'camelCaseExample',
        );

        self::assertEquals('CamelCaseExample', $className);
    }
}
