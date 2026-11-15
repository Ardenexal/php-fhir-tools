<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Attributes;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use PHPUnit\Framework\TestCase;

// Stub class used by testCanBeAppliedAsPhpAttribute to verify the attribute works at the PHP level.
#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/ANY',
    name: 'ANY',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class LogicalModelStub
{
}

/**
 * @covers \Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel
 */
final class LogicalModelAttributeTest extends TestCase
{
    public function testCanInstantiateWithRequiredFieldsOnly(): void
    {
        $attr = new LogicalModel(
            url: 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
            name: 'ClinicalDocument',
            fhirVersion: '5.0.0',
        );

        self::assertSame('http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument', $attr->url);
        self::assertSame('ClinicalDocument', $attr->name);
        self::assertSame('5.0.0', $attr->fhirVersion);
        self::assertNull($attr->xmlNamespace);
    }

    public function testCanInstantiateWithXmlNamespace(): void
    {
        $attr = new LogicalModel(
            url: 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
            name: 'ClinicalDocument',
            fhirVersion: '5.0.0',
            xmlNamespace: 'urn:hl7-org:v3',
        );

        self::assertSame('urn:hl7-org:v3', $attr->xmlNamespace);
    }

    public function testIsReadonly(): void
    {
        $attr = new LogicalModel(
            url: 'http://example.org/StructureDefinition/Test',
            name: 'Test',
            fhirVersion: '5.0.0',
            xmlNamespace: 'urn:example',
        );

        $ref = new \ReflectionClass($attr);
        foreach ($ref->getProperties() as $property) {
            self::assertTrue($property->isReadOnly(), "Property {$property->getName()} must be readonly");
        }
    }

    public function testAttributeTargetIsClass(): void
    {
        $ref       = new \ReflectionClass(LogicalModel::class);
        $attrAttrs = $ref->getAttributes(\Attribute::class);

        self::assertCount(1, $attrAttrs);
        $flags = $attrAttrs[0]->getArguments()[0];
        self::assertSame(\Attribute::TARGET_CLASS, $flags);
    }

    public function testIsFinal(): void
    {
        $ref = new \ReflectionClass(LogicalModel::class);
        self::assertTrue($ref->isFinal());
    }

    public function testCanBeAppliedAsPhpAttribute(): void
    {
        $ref   = new \ReflectionClass(LogicalModelStub::class);
        $attrs = $ref->getAttributes(LogicalModel::class);

        self::assertCount(1, $attrs);
        $inst = $attrs[0]->newInstance();
        self::assertSame('http://hl7.org/cda/stds/core/StructureDefinition/ANY', $inst->url);
        self::assertSame('ANY', $inst->name);
        self::assertSame('urn:hl7-org:v3', $inst->xmlNamespace);
    }
}
