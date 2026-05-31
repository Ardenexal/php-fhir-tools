<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\HumanName as HumanNameR5;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinitionResource;
use Ardenexal\FHIRTools\Component\Validation\FhirPropertyTypeHierarchyResolver;
use PHPUnit\Framework\TestCase;

final class FhirPropertyTypeHierarchyResolverTest extends TestCase
{
    private FhirPropertyTypeHierarchyResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new FhirPropertyTypeHierarchyResolver();
    }

    public function testResolvesComplexPropertyToFhirType(): void
    {
        $patient = new PatientResource();

        self::assertSame('HumanName', $this->resolver->resolvePropertyType($patient, 'name'));
    }

    public function testResolvesArrayComplexPropertyToFhirType(): void
    {
        $patient = new PatientResource();

        self::assertSame('Identifier', $this->resolver->resolvePropertyType($patient, 'identifier'));
    }

    public function testReturnsPrimitiveTypeName(): void
    {
        $patient = new PatientResource();

        // 'active' is a boolean scalar; fhirType is 'boolean'
        self::assertSame('boolean', $this->resolver->resolvePropertyType($patient, 'active'));
    }

    public function testReturnsNullForChoiceProperty(): void
    {
        $patient = new PatientResource();

        // deceased[x] is a choice property; fhirType is 'choice'
        self::assertNull($this->resolver->resolvePropertyType($patient, 'deceased'));
    }

    public function testReturnsNullForUnknownProperty(): void
    {
        $patient = new PatientResource();

        self::assertNull($this->resolver->resolvePropertyType($patient, 'nonExistentProperty'));
    }

    public function testCachesResultsForSameClassAndProperty(): void
    {
        $patient = new PatientResource();

        $first  = $this->resolver->resolvePropertyType($patient, 'name');
        $second = $this->resolver->resolvePropertyType($patient, 'name');

        self::assertSame($first, $second);
        self::assertSame('HumanName', $first);
    }

    public function testResolveTypeHierarchyForResourceWalksSupertypes(): void
    {
        $hierarchy = $this->resolver->resolveTypeHierarchy(new StructureDefinitionResource());

        self::assertContains('StructureDefinition', $hierarchy);
        self::assertContains('DomainResource', $hierarchy);
        self::assertContains('Resource', $hierarchy);
        // Most-derived first.
        self::assertSame('StructureDefinition', $hierarchy[0]);
    }

    public function testResolveTypeHierarchyForComplexTypeWalksSupertypes(): void
    {
        $hierarchy = $this->resolver->resolveTypeHierarchy(new HumanNameR5());

        self::assertContains('HumanName', $hierarchy);
        self::assertContains('DataType', $hierarchy);
        self::assertContains('Element', $hierarchy);
        self::assertSame('HumanName', $hierarchy[0]);
    }

    public function testResolveTypeHierarchyEmptyForUnannotatedObject(): void
    {
        $plain = new \stdClass();

        self::assertSame([], $this->resolver->resolveTypeHierarchy($plain));
    }
}
