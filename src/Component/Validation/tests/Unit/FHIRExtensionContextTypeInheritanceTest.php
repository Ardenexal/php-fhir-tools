<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Validation\FhirPropertyTypeHierarchyResolver;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTypeHierarchyResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\BareTypeDomainResourceExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\BareTypeElementExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\BareTypeObservationExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\HumanNameFamilyExtensionFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Type-inheritance-aware extension context resolution (M08), exercised against real
 * generated R5 model classes so the supertype walk and per-path-segment type resolution
 * run end-to-end.
 */
final class FHIRExtensionContextTypeInheritanceTest extends TestCase
{
    private function makeService(bool $withResolver = true): FHIRValidationService
    {
        $validator = $this->createStub(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());

        $resolver = $withResolver
            ? new FhirPropertyTypeHierarchyResolver()
            : new NullFHIRTypeHierarchyResolver();

        return new FHIRValidationService($validator, new FHIRPathService(), typeResolver: $resolver);
    }

    public function testBareSupertypeContextPermittedOnResourceRoot(): void
    {
        // 'DomainResource' must permit the extension on a Patient, because Patient is a DomainResource.
        $patient = new PatientResource(extension: [new BareTypeDomainResourceExtensionFixture()]);

        $report = $this->makeService()->validate($patient);

        self::assertCount(0, $report->errors(), '"DomainResource" context must be permitted on a Patient via supertype resolution');
    }

    public function testBareElementContextPermittedOnNestedComplexType(): void
    {
        // 'Element' must permit the extension on Patient.name (a HumanName, which is an Element).
        $name    = new HumanName(extension: [new BareTypeElementExtensionFixture()]);
        $patient = new PatientResource(name: [$name]);

        $report = $this->makeService()->validate($patient);

        self::assertCount(0, $report->errors(), '"Element" context must be permitted on a HumanName via supertype resolution');
    }

    public function testBareNonAncestorContextDefersOnNestedComplexType(): void
    {
        // 'Observation' is not in HumanName's supertype set, but at a NESTED element a
        // non-matching bare context is DEFERRED, not denied — type resolution is monotonic and
        // only ever adds permits (some abstract bare types, e.g. "Element" on a resource, are
        // more permissive than strict supertype membership, so denying nested bare types is
        // unsafe). No violation is produced.
        $name    = new HumanName(extension: [new BareTypeObservationExtensionFixture()]);
        $patient = new PatientResource(name: [$name]);

        $report = $this->makeService()->validate($patient);

        self::assertCount(0, $report->errors(), 'A non-matching bare context on a nested element must defer, not deny');
    }

    public function testBareNonAncestorContextDefersWithoutResolver(): void
    {
        // With the null resolver the nested element's type is unknown, so the bare context
        // defers (no violation). Backward compatibility preserved.
        $name    = new HumanName(extension: [new BareTypeObservationExtensionFixture()]);
        $patient = new PatientResource(name: [$name]);

        $report = $this->makeService(withResolver: false)->validate($patient);

        self::assertCount(0, $report->errors(), 'Bare context on a nested element must defer when the type is unresolvable');
    }

    public function testBareNonAncestorContextDeniedAtResourceRoot(): void
    {
        // At the resource root the type is definitively known, so a non-matching bare context is
        // a confident denial. 'Observation' is not a Patient supertype.
        $patient = new PatientResource(extension: [new BareTypeObservationExtensionFixture()]);

        $report = $this->makeService()->validate($patient);

        self::assertCount(1, $report->errors(), '"Observation" context must be denied on a Patient resource root');
        self::assertSame(FHIRExtensionContext::class, $report->errors()[0]->constraintClass);
    }

    public function testForeignRootDottedContextPermittedViaPathSegmentType(): void
    {
        // 'HumanName.family' must permit the extension on Patient.name.family, because the
        // 'name' segment is typed HumanName and 'family' is its sub-element.
        $family  = new StringPrimitive(extension: [new HumanNameFamilyExtensionFixture()], value: 'Smith');
        $name    = new HumanName(family: $family);
        $patient = new PatientResource(name: [$name]);

        $report = $this->makeService()->validate($patient);

        self::assertCount(0, $report->errors(), '"HumanName.family" must be permitted on Patient.name.family via path-segment type resolution');
    }

    public function testForeignRootDottedContextDefersWithoutResolver(): void
    {
        // Without a resolver the path segment types are unknown, so the foreign-root dotted
        // context defers rather than denying — no false positive.
        $family  = new StringPrimitive(extension: [new HumanNameFamilyExtensionFixture()], value: 'Smith');
        $name    = new HumanName(family: $family);
        $patient = new PatientResource(name: [$name]);

        $report = $this->makeService(withResolver: false)->validate($patient);

        self::assertCount(0, $report->errors(), 'Foreign-root dotted context must defer when path types are unresolvable');
    }
}
