<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Validation\FhirPropertyTypeHierarchyResolver;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTypeHierarchyResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\BareTypeHumanNameExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\ForeignRootOnlyExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\NestedContactWithExtensionsFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\ResourceWithTypedNamePropertyFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

final class FHIRExtensionContextTypeResolutionTest extends TestCase
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

    public function testBareTypeMatchPermitsExtension(): void
    {
        // $name property has FhirProperty(fhirType: 'HumanName'); extension context is "HumanName".
        // With the resolver, the type is resolved → extension is permitted → 0 violations.
        $name     = new NestedContactWithExtensionsFixture([new BareTypeHumanNameExtensionFixture()]);
        $resource = new ResourceWithTypedNamePropertyFixture(name: [$name]);

        $report = $this->makeService(withResolver: true)->validate($resource);

        self::assertCount(0, $report->errors(), 'Bare-type "HumanName" context must permit an extension on a HumanName-typed property');
    }

    public function testForeignRootNonMatchDefersNotDenies(): void
    {
        // ForeignRootOnlyExtensionFixture context is "Observation.component" (foreign root) placed
        // on a HumanName-typed sub-element at "Patient.name". Type resolution is monotonic: a
        // foreign-root context that does not match a type-rooted path candidate is DEFERRED, never
        // denied — confirming foreign type-paths cannot be confirmed needs full path-segment
        // resolution. So no violation is produced.
        $name     = new NestedContactWithExtensionsFixture([new ForeignRootOnlyExtensionFixture()]);
        $resource = new ResourceWithTypedNamePropertyFixture(name: [$name]);

        $report = $this->makeService(withResolver: true)->validate($resource);

        self::assertCount(0, $report->errors(), 'Foreign-root context that cannot be confirmed must defer, not deny');
    }

    public function testBareTypeNullResolverDefers(): void
    {
        // With NullFHIRTypeHierarchyResolver, bare-type context "HumanName" cannot be resolved.
        // Extension is deferred (treated as permitted) → 0 violations. Backward compat preserved.
        $name     = new NestedContactWithExtensionsFixture([new BareTypeHumanNameExtensionFixture()]);
        $resource = new ResourceWithTypedNamePropertyFixture(name: [$name]);

        $report = $this->makeService(withResolver: false)->validate($resource);

        self::assertCount(0, $report->errors(), 'Bare-type context with null resolver must be deferred (no violation)');
    }

    public function testMixedContextsBareTypeMatchWins(): void
    {
        // Extension has contexts ["HumanName" (bare type), "Observation.component" (foreign root)].
        // On a HumanName-typed element, the bare-type context matches → permitted → 0 violations.
        $ext = new class () implements FHIRExtensionInterface {
            #[FHIRExtensionContext(type: 'element', expression: 'HumanName')]
            #[FHIRExtensionContext(type: 'element', expression: 'Observation.component')]
            public function getExtensionUrl(): ?string
            {
                return 'http://example.org/ext/mixed-contexts';
            }
        };

        $name     = new NestedContactWithExtensionsFixture([$ext]);
        $resource = new ResourceWithTypedNamePropertyFixture(name: [$name]);

        $report = $this->makeService(withResolver: true)->validate($resource);

        self::assertCount(0, $report->errors(), 'Bare-type match must permit the extension even when a foreign-root context also exists');
    }
}
