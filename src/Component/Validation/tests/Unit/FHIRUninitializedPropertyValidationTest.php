<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Validation\FHIRObligationContext;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationReport;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\MustSupportFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\ObligationFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * B5 regression: deserializers build resources via newInstanceWithoutConstructor(), so typed
 * properties with no value in the payload are left *uninitialized* (not null). Reading such a
 * property via Reflection::getValue() throws \Error. Validation must guard with isInitialized()
 * and not crash on otherwise-valid input.
 */
final class FHIRUninitializedPropertyValidationTest extends TestCase
{
    private const string PLACER = 'http://example.org/actor/placer';

    private FHIRValidationService $service;

    protected function setUp(): void
    {
        $validator = $this->createStub(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());
        $this->service = new FHIRValidationService($validator, new FHIRPathService());
    }

    public function testMustSupportOnUninitializedPropertyEmitsNotPopulatedInfo(): void
    {
        // Constructor bypassed → $name is uninitialized (not null), as after deserialization.
        $resource = (new \ReflectionClass(MustSupportFixture::class))->newInstanceWithoutConstructor();

        $report = $this->service->validate($resource, includeMustSupportInfo: true);

        // Must not throw, AND an uninitialized (= absent) must-support field is reported as
        // not-populated, exactly as an explicit null would be.
        self::assertInstanceOf(FHIRValidationReport::class, $report);
        $infoPaths = array_map(static fn ($v) => $v->path, $report->info());
        self::assertContains('name', $infoPaths);
    }

    public function testObligationOnUninitializedPropertyFiresLikeNull(): void
    {
        $resource = (new \ReflectionClass(ObligationFixture::class))->newInstanceWithoutConstructor();

        $report = $this->service->validate($resource, obligationContext: new FHIRObligationContext(self::PLACER));

        // Must not throw, AND an uninitialized SHALL:populate field fires its obligation error,
        // matching the behaviour of an explicit null value (see FHIRObligationValidationTest).
        self::assertInstanceOf(FHIRValidationReport::class, $report);
        $errorPaths = array_map(static fn ($v) => $v->path, $report->errors());
        self::assertContains('shallPopulate', $errorPaths);
    }
}
