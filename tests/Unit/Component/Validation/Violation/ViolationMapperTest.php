<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Validation\Violation;

use Ardenexal\FHIRTools\Component\Validation\Model\ValidationIssue;
use Ardenexal\FHIRTools\Component\Validation\Model\ValidationResult;
use Ardenexal\FHIRTools\Component\Validation\Violation\ViolationMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @covers \Ardenexal\FHIRTools\Component\Validation\Violation\ViolationMapper
 */
class ViolationMapperTest extends TestCase
{
    private ViolationMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new ViolationMapper();
    }

    public function testMapCardinalityMinViolation(): void
    {
        $issue = ValidationIssue::error(
            'Patient.name',
            'Minimum cardinality not met: expected at least 1, found 0',
            'cardinality-min',
            'http://hl7.org/fhir/StructureDefinition/Patient'
        );

        $violation = $this->mapper->mapIssue($issue);

        self::assertInstanceOf(ConstraintViolation::class, $violation);
        self::assertSame(ViolationMapper::CODE_CARDINALITY_MIN, $violation->getCode());
        self::assertSame('name', $violation->getPropertyPath());
        self::assertStringContainsString('Minimum cardinality', $violation->getMessage());
    }

    public function testMapCardinalityMaxViolation(): void
    {
        $issue = ValidationIssue::error(
            'Patient.identifier',
            'Maximum cardinality exceeded: expected at most 10, found 15',
            'cardinality-max'
        );

        $violation = $this->mapper->mapIssue($issue);

        self::assertSame(ViolationMapper::CODE_CARDINALITY_MAX, $violation->getCode());
        self::assertStringContainsString('Maximum cardinality', $violation->getMessage());
    }

    public function testMapTypeViolation(): void
    {
        $issue = ValidationIssue::error(
            'Observation.valueQuantity',
            'Type mismatch: expected Quantity, found string',
            'type-mismatch'
        );

        $violation = $this->mapper->mapIssue($issue);

        self::assertSame(ViolationMapper::CODE_TYPE, $violation->getCode());
    }

    public function testMapFHIRPathViolation(): void
    {
        $issue = ValidationIssue::error(
            'Patient.name',
            'FHIRPath constraint failed: family.exists() or given.exists()',
            'inv-name-1',
            'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'
        );

        $violation = $this->mapper->mapIssue($issue);

        self::assertSame(ViolationMapper::CODE_FHIRPATH, $violation->getCode());
        self::assertStringContainsString('FHIRPath constraint failed', $violation->getMessage());
    }

    public function testMapRequiredBindingViolation(): void
    {
        $issue = ValidationIssue::error(
            'Patient.gender',
            'Required binding violation: code not in ValueSet',
            'binding-required'
        );

        $violation = $this->mapper->mapIssue($issue);

        self::assertSame(ViolationMapper::CODE_BINDING_REQUIRED, $violation->getCode());
    }

    public function testMapExtensibleBindingViolation(): void
    {
        $issue = ValidationIssue::warning(
            'Observation.code',
            'Extensible binding: code not found in preferred ValueSet',
            'binding-extensible'
        );

        $violation = $this->mapper->mapIssue($issue);

        self::assertSame(ViolationMapper::CODE_BINDING_EXTENSIBLE, $violation->getCode());
    }

    public function testMapSliceClosedViolation(): void
    {
        $issue = ValidationIssue::error(
            'Observation.component',
            'Closed slice violation: unmatched element found',
            'slice-closed'
        );

        $violation = $this->mapper->mapIssue($issue);

        self::assertSame(ViolationMapper::CODE_SLICE_CLOSED, $violation->getCode());
    }

    public function testMapSliceCardinalityViolation(): void
    {
        $issue = ValidationIssue::error(
            'Observation.component:systolic',
            'Slice cardinality violation: expected exactly 1, found 0',
            'slice-cardinality'
        );

        $violation = $this->mapper->mapIssue($issue);

        self::assertSame(ViolationMapper::CODE_SLICE_CARDINALITY, $violation->getCode());
    }

    public function testMapProfileConflictViolation(): void
    {
        $issue = ValidationIssue::error(
            'Patient.name',
            'Profile conflict: Profile A requires min=1, Profile B requires max=0',
            'profile-conflict'
        );

        $violation = $this->mapper->mapIssue($issue);

        self::assertSame(ViolationMapper::CODE_PROFILE_CONFLICT, $violation->getCode());
    }

    public function testMapValidationResultToViolationList(): void
    {
        $result = new ValidationResult();
        $result->addIssue(ValidationIssue::error('Patient.name', 'Name is required'));
        $result->addIssue(ValidationIssue::warning('Patient.telecom', 'Telecom recommended'));
        $result->addIssue(ValidationIssue::info('Patient.address', 'Address present'));

        $violations = $this->mapper->map($result);

        self::assertInstanceOf(ConstraintViolationList::class, $violations);
        self::assertCount(3, $violations);
    }

    public function testPropertyPathNormalization(): void
    {
        // Test removal of resource type prefix
        $issue = ValidationIssue::error('Patient.name.family', 'Missing family name');
        $violation = $this->mapper->mapIssue($issue);
        self::assertSame('name.family', $violation->getPropertyPath());

        // Test empty path
        $issue2 = ValidationIssue::error('', 'Root level error');
        $violation2 = $this->mapper->mapIssue($issue2);
        self::assertSame('', $violation2->getPropertyPath());
    }

    public function testMessageEnrichment(): void
    {
        $issue = ValidationIssue::error(
            'Patient.name',
            'Name is required',
            'name-required',
            'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'
        );

        $violation = $this->mapper->mapIssue($issue);
        $message = $violation->getMessage();

        // Should include profile name
        self::assertStringContainsString('us core patient', $message);

        // Should include constraint key
        self::assertStringContainsString('[name-required]', $message);
    }

    public function testProfileNameExtraction(): void
    {
        $issue = ValidationIssue::error(
            'Patient.identifier',
            'Identifier required',
            '',
            'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'
        );

        $violation = $this->mapper->mapIssue($issue);
        $message = $violation->getMessage();

        // Profile name should be readable
        self::assertStringContainsString('us core patient', $message);
    }

    public function testCreateViolationDirectly(): void
    {
        $violation = $this->mapper->createViolation(
            ValidationIssue::SEVERITY_ERROR,
            'Patient.name',
            'Name is required',
            'name-required',
            'http://hl7.org/fhir/StructureDefinition/Patient'
        );

        self::assertInstanceOf(ConstraintViolation::class, $violation);
        self::assertSame('name', $violation->getPropertyPath());
        self::assertStringContainsString('Name is required', $violation->getMessage());
    }

    public function testViolationParametersIncludeMetadata(): void
    {
        $issue = ValidationIssue::error(
            'Patient.name',
            'Name is required',
            'name-required',
            'http://hl7.org/fhir/StructureDefinition/Patient'
        );

        $violation = $this->mapper->mapIssue($issue);
        $parameters = $violation->getParameters();

        self::assertArrayHasKey('{{ key }}', $parameters);
        self::assertArrayHasKey('{{ profile }}', $parameters);
        self::assertArrayHasKey('{{ severity }}', $parameters);
        self::assertArrayHasKey('{{ code }}', $parameters);

        self::assertSame('name-required', $parameters['{{ key }}']);
        self::assertSame('http://hl7.org/fhir/StructureDefinition/Patient', $parameters['{{ profile }}']);
        self::assertSame(ValidationIssue::SEVERITY_ERROR, $parameters['{{ severity }}']);
    }

    public function testMustSupportViolation(): void
    {
        $issue = ValidationIssue::warning(
            'Patient.identifier',
            'Element marked as mustSupport but not present',
            'mustsupport-identifier'
        );

        $violation = $this->mapper->mapIssue($issue);

        self::assertSame(ViolationMapper::CODE_MUST_SUPPORT, $violation->getCode());
    }

    public function testDefaultToInvariantCode(): void
    {
        $issue = ValidationIssue::error(
            'Patient',
            'Unknown validation error',
            '',
            ''
        );

        $violation = $this->mapper->mapIssue($issue);

        self::assertSame(ViolationMapper::CODE_INVARIANT, $violation->getCode());
    }

    public function testRootValuePreserved(): void
    {
        $root = ['resourceType' => 'Patient', 'name' => []];
        $issue = ValidationIssue::error('Patient.name', 'Name is required');

        $violation = $this->mapper->mapIssue($issue, $root);

        self::assertSame($root, $violation->getRoot());
    }
}
