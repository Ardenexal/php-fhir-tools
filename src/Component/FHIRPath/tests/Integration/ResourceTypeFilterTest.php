<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Integration;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for FHIRPath resource type filter semantics.
 *
 * Per the FHIRPath 2.0 specification, the first token in a path may be the
 * resource type name (e.g. `Patient` in `Patient.name.given`). This acts as a
 * type filter — it returns the root context when the resource type matches, and
 * returns an empty collection when it does not. All tests go through the full
 * FHIRPathService pipeline (lexer → parser → evaluator) to replicate the
 * execution path used by the demo web UI.
 *
 * Regression test for: evaluator returning empty when a path begins with the
 * resource type identifier (e.g. `Patient.name.given` returning 0 results
 * against a Patient resource).
 *
 * @covers \Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator::visitIdentifier
 * @covers \Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator::matchesResourceType
 */
final class ResourceTypeFilterTest extends TestCase
{
    private FHIRPathService $service;

    /** @var array<string, mixed> A minimal but realistic R4 Patient resource. */
    private array $patient;

    /** @var array<string, mixed> A minimal R4 Observation resource. */
    private array $observation;

    protected function setUp(): void
    {
        $this->service = new FHIRPathService();

        $this->patient = [
            'resourceType' => 'Patient',
            'id'           => 'example',
            'name'         => [
                [
                    'use'    => 'official',
                    'family' => 'Chalmers',
                    'given'  => ['Peter', 'James'],
                ],
                [
                    'use'   => 'usual',
                    'given' => ['Jim'],
                ],
            ],
            'gender'    => 'male',
            'birthDate' => '1974-12-25',
            'active'    => true,
        ];

        $this->observation = [
            'resourceType' => 'Observation',
            'id'           => 'blood-pressure',
            'status'       => 'final',
            'code'         => [
                'coding' => [
                    ['system' => 'http://loinc.org', 'code' => '85354-9'],
                ],
            ],
            'valueQuantity' => [
                'value'  => 120,
                'unit'   => 'mmHg',
                'system' => 'http://unitsofmeasure.org',
            ],
        ];
    }

    // -------------------------------------------------------------------------
    // Resource type prefix acts as a type filter (the regression scenario)
    // -------------------------------------------------------------------------

    public function testResourceTypePrefixReturnsNestedCollection(): void
    {
        // Regression: this expression previously returned 0 results.
        $result = $this->service->evaluate('Patient.name.given', $this->patient);

        self::assertSame(3, $result->count());
        self::assertSame(['Peter', 'James', 'Jim'], $result->toArray());
    }

    public function testResourceTypePrefixScalarField(): void
    {
        $result = $this->service->evaluate('Patient.gender', $this->patient);

        self::assertSame(1, $result->count());
        self::assertSame('male', $result->first());
    }

    public function testResourceTypePrefixBooleanField(): void
    {
        $result = $this->service->evaluate('Patient.active', $this->patient);

        self::assertSame(1, $result->count());
        self::assertTrue($result->first());
    }

    public function testResourceTypePrefixDeepChain(): void
    {
        $result = $this->service->evaluate('Patient.name.family', $this->patient);

        // Only the 'official' name has a family; the 'usual' entry does not.
        self::assertSame(1, $result->count());
        self::assertSame('Chalmers', $result->first());
    }

    // -------------------------------------------------------------------------
    // Wrong resource type prefix returns empty (type filter behaviour)
    // -------------------------------------------------------------------------

    public function testMismatchedResourceTypePrefixReturnsEmpty(): void
    {
        // Evaluating an Observation path against a Patient must yield nothing.
        $result = $this->service->evaluate('Observation.name.given', $this->patient);

        self::assertTrue($result->isEmpty());
    }

    public function testPatientPrefixAgainstObservationReturnsEmpty(): void
    {
        $result = $this->service->evaluate('Patient.status', $this->observation);

        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Equivalent plain paths (no resource type prefix) still work
    // -------------------------------------------------------------------------

    public function testPlainPathWithoutPrefixStillWorks(): void
    {
        $result = $this->service->evaluate('name.given', $this->patient);

        self::assertSame(3, $result->count());
        self::assertSame(['Peter', 'James', 'Jim'], $result->toArray());
    }

    // -------------------------------------------------------------------------
    // Resource type prefix combined with FHIRPath functions
    // -------------------------------------------------------------------------

    public function testResourceTypePrefixWithWhereFunction(): void
    {
        $result = $this->service->evaluate("Patient.name.where(use = 'official').given", $this->patient);

        self::assertSame(2, $result->count());
        self::assertSame(['Peter', 'James'], $result->toArray());
    }

    public function testResourceTypePrefixWithExistsFunction(): void
    {
        $result = $this->service->evaluate('Patient.name.family.exists()', $this->patient);

        self::assertSame(1, $result->count());
        self::assertTrue($result->first());
    }

    // -------------------------------------------------------------------------
    // Resource type filter with typed PHP objects (Models component path)
    // -------------------------------------------------------------------------

    public function testResourceTypePrefixWithObjectUsingGetter(): void
    {
        // Simulates the typed PHP objects produced by the Models component,
        // which expose resourceType via getResourceType() rather than an array key.
        $patientObject = new class () {
            public function getResourceType(): string
            {
                return 'Patient';
            }

            public function getName(): string
            {
                return 'Chalmers';
            }
        };

        $result = $this->service->evaluate('Patient.name', $patientObject);

        self::assertSame(1, $result->count());
        self::assertSame('Chalmers', $result->first());
    }

    public function testMismatchedResourceTypePrefixWithObjectReturnsEmpty(): void
    {
        $patientObject = new class () {
            public function getResourceType(): string
            {
                return 'Patient';
            }
        };

        $result = $this->service->evaluate('Observation.id', $patientObject);

        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Resource type filter with typed model classes (#[FhirResource] attribute)
    // -------------------------------------------------------------------------

    public function testResourceTypePrefixWithFhirResourceAttribute(): void
    {
        // PatientResource carries #[FhirResource(type: 'Patient', ...)] — the evaluator
        // must detect it via reflection and treat the type prefix as a type filter.
        $patient = new PatientResource(id: 'test-123');
        $result  = $this->service->evaluate('Patient.id', $patient);

        self::assertSame(['test-123'], $result->toArray());
    }

    public function testMismatchedResourceTypePrefixWithFhirResourceAttributeReturnsEmpty(): void
    {
        $patient = new PatientResource(id: 'test-123');
        $result  = $this->service->evaluate('Observation.id', $patient);

        self::assertTrue($result->isEmpty());
    }
}
