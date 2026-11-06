<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\BareTypeElementExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\ExtensionContextOnlyExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\FhirpathBooleanContextExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\ForeignRootAndExtensionContextExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\NestableParentExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\PatientExtensionPathOnlyExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\PatientExtensionResourceFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\PatientNameOnlyExtensionFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * type=extension context classification (M11, issue #69): an extension declaring a
 * type=extension context is permitted only when nested inside an extension carrying the
 * declared canonical URL. Denial requires a fully-known ancestor chain (including the
 * definitive empty chain at element level); unknown chains defer.
 */
final class FHIRExtensionContextNestedExtensionTest extends TestCase
{
    private FHIRValidationService $service;

    protected function setUp(): void
    {
        $validator = $this->createStub(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());

        $this->service = new FHIRValidationService($validator, new FHIRPathService());
    }

    public function testNestedInsideDeclaredParentUrlIsPermitted(): void
    {
        $parent = new NestableParentExtensionFixture(
            ExtensionContextOnlyExtensionFixture::DECLARED_PARENT_URL,
            [new ExtensionContextOnlyExtensionFixture()],
        );
        $resource = new PatientExtensionResourceFixture([$parent]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'Extension nested inside its declared parent URL must be permitted');
    }

    public function testNestedInsideDifferentParentUrlIsDenied(): void
    {
        $parent = new NestableParentExtensionFixture(
            'http://example.org/ext/some-other-parent',
            [new ExtensionContextOnlyExtensionFixture()],
        );
        $resource = new PatientExtensionResourceFixture([$parent]);

        $report = $this->service->validate($resource);

        self::assertCount(1, $report->errors(), 'Extension nested inside a non-matching parent URL must be denied');

        $violation = $report->errors()[0];
        self::assertSame('error', $violation->severity);
        self::assertSame('extension[0].extension', $violation->path);
        self::assertSame(FHIRExtensionContext::class, $violation->constraintClass);
        self::assertStringContainsString('http://example.org/ext/extension-context-only', $violation->message);
    }

    public function testTopLevelExtensionWithExtensionContextIsDenied(): void
    {
        // Borne directly on the resource: the ancestor chain is definitively empty,
        // so the absence of the declared parent is a confident denial, not a deferral.
        $resource = new PatientExtensionResourceFixture([new ExtensionContextOnlyExtensionFixture()]);

        $report = $this->service->validate($resource);

        self::assertCount(1, $report->errors(), 'Top-level extension with only a type=extension context must be denied');
        self::assertSame('extension', $report->errors()[0]->path);
    }

    public function testUnknownParentUrlDefersInsteadOfDenying(): void
    {
        // Parent URL unreadable (null): the ancestor chain cannot be fully assembled,
        // so the nested type=extension context defers — never a false denial.
        $parent   = new NestableParentExtensionFixture(null, [new ExtensionContextOnlyExtensionFixture()]);
        $resource = new PatientExtensionResourceFixture([$parent]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'Unknown ancestor chain must defer, not deny');
    }

    public function testDeeplyNestedExtensionMatchesAncestorAnywhereInChain(): void
    {
        // declared-parent → intermediate → fixture: the chain carries ALL enclosing URLs,
        // so the declared parent matches even when it is not the immediate parent.
        $intermediate = new NestableParentExtensionFixture(
            'http://example.org/ext/intermediate',
            [new ExtensionContextOnlyExtensionFixture()],
        );
        $outer = new NestableParentExtensionFixture(
            ExtensionContextOnlyExtensionFixture::DECLARED_PARENT_URL,
            [$intermediate],
        );
        $resource = new PatientExtensionResourceFixture([$outer]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'Declared parent URL anywhere in the ancestor chain must permit');
    }

    public function testSiblingDeferringElementContextMasksExtensionArmDenial(): void
    {
        // Footgun extension-context-or-semantics: OR aggregation (anyDeferred) masks the
        // extension arm's DENY. Two levels down the carrier's path is not known for certain,
        // so the foreign-root element context defers there and the extension must NOT be denied.
        $parent = new NestableParentExtensionFixture(
            'http://example.org/ext/some-other-parent',
            [new ForeignRootAndExtensionContextExtensionFixture()],
        );
        $grandparent = new NestableParentExtensionFixture('http://example.org/ext/outer', [$parent]);
        $resource    = new PatientExtensionResourceFixture([$grandparent]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'Sibling deferred element context must mask the extension-arm denial (OR semantics)');
    }

    public function testNestedFhirpathContextEvaluatingFalseAgainstParentExtensionDenies(): void
    {
        // Per M09 finding 2 the bearing element IS the parent extension at a nested site,
        // so a fhirpath context that confidently evaluates to a single `false` against it
        // is a legitimate denial — NOT gated like element contexts. The parent fixture has
        // no `type` property, so "type.exists() and type = 'composed-of'" yields false.
        $parent = new NestableParentExtensionFixture(
            'http://example.org/ext/some-other-parent',
            [new FhirpathBooleanContextExtensionFixture()],
        );
        $resource = new PatientExtensionResourceFixture([$parent]);

        $report = $this->service->validate($resource);

        self::assertCount(1, $report->errors(), 'Nested fhirpath context confidently false against the parent extension must deny');
        self::assertSame('extension[0].extension', $report->errors()[0]->path);
    }

    /**
     * The reference validator places a sub-extension at `[Extension, Patient.extension]`, so an
     * element context naming anything else denies it, as `bodySite` inside `patient-animal` is
     * denied in `patient-extension-complex-bad1`.
     */
    public function testElementContextMismatchOnNestedExtensionDenies(): void
    {
        $parent = new NestableParentExtensionFixture(
            'http://example.org/ext/some-other-parent',
            [new PatientNameOnlyExtensionFixture()],
        );
        $resource = new PatientExtensionResourceFixture([$parent]);

        $report = $this->service->validate($resource);

        self::assertCount(1, $report->errors(), 'An element context that names neither Extension nor the carrier path must deny');
        self::assertSame('extension[0].extension', $report->errors()[0]->path);
    }

    public function testElementContextNamingTheCarrierPathPermitsNestedExtension(): void
    {
        $parent = new NestableParentExtensionFixture(
            'http://example.org/ext/some-other-parent',
            [new PatientExtensionPathOnlyExtensionFixture()],
        );

        $report = $this->service->validate(new PatientExtensionResourceFixture([$parent]));

        self::assertCount(0, $report->errors(), 'Patient.extension is where a sub-extension of a Patient extension sits');
    }

    public function testElementContextNamingAnExtensionSupertypePermitsNestedExtension(): void
    {
        $parent = new NestableParentExtensionFixture(
            'http://example.org/ext/some-other-parent',
            [new BareTypeElementExtensionFixture()],
        );

        $report = $this->service->validate(new PatientExtensionResourceFixture([$parent]));

        self::assertCount(0, $report->errors(), 'Extension is an Element, so an Element context permits it');
    }

    public function testElementContextMismatchTwoLevelsDownDefers(): void
    {
        $inner    = new NestableParentExtensionFixture('http://example.org/ext/inner', [new PatientNameOnlyExtensionFixture()]);
        $outer    = new NestableParentExtensionFixture('http://example.org/ext/outer', [$inner]);
        $resource = new PatientExtensionResourceFixture([$outer]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'Below the first level the carrier path is not known for certain, so a mismatch defers');
    }
}
