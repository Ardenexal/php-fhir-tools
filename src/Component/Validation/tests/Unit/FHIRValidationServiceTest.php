<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\MustSupportFixture;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class FHIRValidationServiceTest extends TestCase
{
    /** @var ValidatorInterface&MockObject */
    private ValidatorInterface $validator;

    private FHIRValidationService $service;

    protected function setUp(): void
    {
        // createStub() — no expectation verification needed; suppress "no expectations" notice
        $this->validator = $this->createStub(ValidatorInterface::class);
        $this->service   = new FHIRValidationService($this->validator, new FHIRPathService());
    }

    // --- groups wiring ---

    public function testValidatePassesDefaultGroupAlways(): void
    {
        $resource  = new \stdClass();
        $mock      = $this->createMock(ValidatorInterface::class);
        $mock->expects(self::once())
            ->method('validate')
            ->with($resource, null, ['Default'])
            ->willReturn(new ConstraintViolationList());

        $report = (new FHIRValidationService($mock, new FHIRPathService()))->validate($resource);

        self::assertTrue($report->isValid());
    }

    public function testValidatePassesProfileUrlsAsAdditionalGroups(): void
    {
        $resource = new \stdClass();
        $mock     = $this->createMock(ValidatorInterface::class);
        $mock->expects(self::once())
            ->method('validate')
            ->with($resource, null, ['Default', 'http://profile.url/A', 'http://profile.url/B'])
            ->willReturn(new ConstraintViolationList());

        (new FHIRValidationService($mock, new FHIRPathService()))->validate($resource, ['http://profile.url/A', 'http://profile.url/B']);
    }

    // --- severity mapping ---

    public function testFhirErrorCodeMapsToErrorBucket(): void
    {
        $this->stubViolations([
            $this->makeViolation('identifier', 'Cardinality failed', FHIRViolationCode::ERROR),
        ]);

        $report = $this->service->validate(new \stdClass());

        self::assertCount(1, $report->errors());
        self::assertCount(0, $report->warnings());
        self::assertFalse($report->isValid());
    }

    public function testFhirWarningCodeMapsToWarningBucket(): void
    {
        $this->stubViolations([
            $this->makeViolation('status', 'Extensible binding mismatch', FHIRViolationCode::WARNING),
        ]);

        $report = $this->service->validate(new \stdClass());

        self::assertCount(0, $report->errors());
        self::assertCount(1, $report->warnings());
        self::assertTrue($report->isValid());
    }

    public function testFhirInfoCodeMapsToInfoBucket(): void
    {
        $this->stubViolations([
            $this->makeViolation('note', 'Must-support missing', FHIRViolationCode::INFO),
        ]);

        $report = $this->service->validate(new \stdClass());

        self::assertCount(0, $report->errors());
        self::assertCount(0, $report->warnings());
        self::assertCount(1, $report->info());
        self::assertTrue($report->isValid());
    }

    public function testUncheckedBindingCodeMapsToInfoBucketAndIsQueryable(): void
    {
        $this->stubViolations([
            $this->makeViolation('status', 'Terminology validation skipped', FHIRViolationCode::UNCHECKED_BINDING),
        ]);

        $report = $this->service->validate(new \stdClass());

        self::assertCount(0, $report->errors());
        self::assertCount(0, $report->warnings());
        self::assertCount(1, $report->info());
        self::assertTrue($report->isValid());
        self::assertTrue($report->hasUncheckedBindings());
        self::assertSame(FHIRViolationCode::UNCHECKED_BINDING, $report->uncheckedBindings()[0]->code);
    }

    public function testNullCodeMapsToErrorBucket(): void
    {
        $this->stubViolations([
            $this->makeViolation('identifier', 'This value should not be blank.', null),
        ]);

        $report = $this->service->validate(new \stdClass());

        self::assertCount(1, $report->errors());
    }

    public function testSymfonyBuiltInCodeMapsToErrorBucket(): void
    {
        // Symfony UUID-format internal codes (e.g. Count::TOO_FEW_ERROR) must map to error
        $this->stubViolations([
            $this->makeViolation('items', 'Too few.', 'aedfc507-6c4e-4086-a5a0-d9029a5d21d4'),
        ]);

        $report = $this->service->validate(new \stdClass());

        self::assertCount(1, $report->errors());
    }

    // --- violation field mapping ---

    public function testViolationPathIsMapped(): void
    {
        $this->stubViolations([
            $this->makeViolation('identifier[0].system', 'Bad value', FHIRViolationCode::ERROR),
        ]);

        $report = $this->service->validate(new \stdClass());

        self::assertSame('identifier[0].system', $report->errors()[0]->path);
    }

    public function testViolationMessageIsMapped(): void
    {
        $this->stubViolations([
            $this->makeViolation('id', 'Fixed value mismatch.', FHIRViolationCode::ERROR),
        ]);

        $report = $this->service->validate(new \stdClass());

        self::assertSame('Fixed value mismatch.', $report->errors()[0]->message);
    }

    public function testViolationConstraintClassIsMapped(): void
    {
        $constraint = new Count(min: 1);
        $this->stubViolationsRaw([new ConstraintViolation(
            message: 'Too few.',
            messageTemplate: 'Too few.',
            parameters: [],
            root: new \stdClass(),
            propertyPath: 'identifier',
            invalidValue: [],
            plural: null,
            code: FHIRViolationCode::ERROR,
            constraint: $constraint,
        )]);

        $report = $this->service->validate(new \stdClass());

        self::assertSame(Count::class, $report->errors()[0]->constraintClass);
    }

    public function testProfileGroupExtractedFromFHIRProfileConstraint(): void
    {
        $profileUrl        = 'http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient';
        $profileConstraint = new FHIRProfileConstraint(
            path: 'identifier',
            constraint: Count::class,
            options: ['min' => 1],
            groups: [$profileUrl],
        );

        $this->stubViolationsRaw([new ConstraintViolation(
            message: 'Too few.',
            messageTemplate: 'Too few.',
            parameters: [],
            root: new \stdClass(),
            propertyPath: 'identifier',
            invalidValue: [],
            plural: null,
            code: FHIRViolationCode::ERROR,
            constraint: $profileConstraint,
        )]);

        $report = $this->service->validate(new \stdClass(), [$profileUrl]);

        self::assertSame($profileUrl, $report->errors()[0]->profileGroup);
    }

    public function testProfileGroupIsNullForNonProfileConstraint(): void
    {
        $this->stubViolations([
            $this->makeViolation('identifier', 'Too few.', FHIRViolationCode::ERROR),
        ]);

        $report = $this->service->validate(new \stdClass());

        self::assertNull($report->errors()[0]->profileGroup);
    }

    public function testInvariantKeyExtractedFromFHIRPathInvariant(): void
    {
        $invariant = new FHIRPathInvariant(
            key: 'obs-7',
            severity: 'error',
            expression: 'value.exists()',
            human: 'Observation.value must exist',
        );

        $this->stubViolationsRaw([new ConstraintViolation(
            message: 'Observation.value must exist',
            messageTemplate: 'Observation.value must exist',
            parameters: [],
            root: new \stdClass(),
            propertyPath: '',
            invalidValue: null,
            plural: null,
            code: FHIRViolationCode::ERROR,
            constraint: $invariant,
        )]);

        $report = $this->service->validate(new \stdClass());

        self::assertSame('obs-7', $report->errors()[0]->invariantKey);
    }

    public function testInvariantKeyIsNullForNonInvariantConstraint(): void
    {
        $this->stubViolations([
            $this->makeViolation('id', 'Fixed value.', FHIRViolationCode::ERROR),
        ]);

        $report = $this->service->validate(new \stdClass());

        self::assertNull($report->errors()[0]->invariantKey);
    }

    public function testParametersAreMapped(): void
    {
        $violation = new ConstraintViolation(
            message: 'This collection should contain 1 element or more.',
            messageTemplate: 'This collection should contain {{ limit }} element or more.',
            parameters: ['{{ limit }}' => 1, '{{ count }}' => 0],
            root: new \stdClass(),
            propertyPath: 'items',
            invalidValue: [],
            plural: null,
            code: FHIRViolationCode::ERROR,
            constraint: null,
        );

        $this->stubViolationsRaw([$violation]);

        $report = $this->service->validate(new \stdClass());

        self::assertSame(['{{ limit }}' => 1, '{{ count }}' => 0], $report->errors()[0]->parameters);
    }

    // --- mustSupport path ---

    public function testMustSupportInfoSkippedWhenFlagFalse(): void
    {
        $this->validator->method('validate')->willReturn(new ConstraintViolationList());

        $resource = new MustSupportFixture(name: null);
        $report   = $this->service->validate($resource, [], false);

        self::assertCount(0, $report->info());
    }

    public function testMustSupportInfoEmittedForNullProperty(): void
    {
        $this->validator->method('validate')->willReturn(new ConstraintViolationList());

        $resource = new MustSupportFixture(name: null);
        $report   = $this->service->validate($resource, [], true);

        $infoPaths = array_map(static fn ($v) => $v->path, $report->info());
        self::assertContains('name', $infoPaths);
    }

    public function testMustSupportInfoNotEmittedForPopulatedProperty(): void
    {
        $this->validator->method('validate')->willReturn(new ConstraintViolationList());

        $resource = new MustSupportFixture(name: 'John');
        $report   = $this->service->validate($resource, [], true);

        $infoPaths = array_map(static fn ($v) => $v->path, $report->info());
        self::assertNotContains('name', $infoPaths);
    }

    // --- helpers ---

    /** @param list<ConstraintViolation> $violations */
    private function stubViolations(array $violations): void
    {
        $this->stubViolationsRaw($violations);
    }

    /** @param list<ConstraintViolation> $violations */
    private function stubViolationsRaw(array $violations): void
    {
        $list = new ConstraintViolationList($violations);
        $this->validator->method('validate')->willReturn($list);
    }

    private function makeViolation(string $path, string $message, ?string $code): ConstraintViolation
    {
        return new ConstraintViolation(
            message: $message,
            messageTemplate: $message,
            parameters: [],
            root: new \stdClass(),
            propertyPath: $path,
            invalidValue: null,
            plural: null,
            code: $code,
            constraint: null,
        );
    }
}
