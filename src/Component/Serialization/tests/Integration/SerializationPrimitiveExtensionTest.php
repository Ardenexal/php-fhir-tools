<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\IssueSeverityType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\IssueTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\OperationOutcomeResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\OperationOutcome\OperationOutcomeIssue;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for array primitive extension serialization in complex types and backbone elements.
 *
 * Verifies that FHIRComplexTypeJsonNormalizer and FHIRBackboneElementJsonNormalizer emit
 * the correct FHIR JSON parallel-array shape (fieldName[] + _fieldName[]) for primitive
 * array properties that carry extensions.
 */
#[CoversClass(FHIRSerializationService::class)]
final class SerializationPrimitiveExtensionTest extends TestCase
{
    private FHIRSerializationService $service;

    protected function setUp(): void
    {
        $this->service = FHIRSerializationService::createDefault(FhirVersion::R5);
    }

    /**
     * Meta.profile is canonical[] on a complex type (DataType).
     * FHIRComplexTypeJsonNormalizer must emit meta.profile as a scalar array
     * and meta._profile as a parallel extension array.
     */
    public function testComplexTypeCanonicalArrayWithExtension(): void
    {
        $extUrl = 'http://example.org/ext';

        $patient = new PatientResource(
            meta: new Meta(
                profile: [
                    new CanonicalPrimitive(
                        value: 'http://example.org/profile',
                        extension: [
                            new Extension(url: $extUrl),
                        ],
                    ),
                ],
            ),
        );

        $json    = $this->service->serializeToJson($patient);
        $decoded = json_decode($json, true);

        self::assertIsArray($decoded);

        // Scalar value array
        self::assertSame('http://example.org/profile', $decoded['meta']['profile'][0]);

        // Parallel extension array
        self::assertArrayHasKey('_profile', $decoded['meta']);
        self::assertSame($extUrl, $decoded['meta']['_profile'][0]['extension'][0]['url']);

        // Round-trip: re-deserialize and confirm extension survives
        $roundTripped = $this->service->deserialize($json, PatientResource::class);
        self::assertInstanceOf(PatientResource::class, $roundTripped);
        self::assertNotNull($roundTripped->meta);
        self::assertInstanceOf(CanonicalPrimitive::class, $roundTripped->meta->profile[0]);
        self::assertCount(1, $roundTripped->meta->profile[0]->extension);
        self::assertSame($extUrl, $roundTripped->meta->profile[0]->extension[0]->url);
    }

    /**
     * OperationOutcomeIssue.expression is string[] on a backbone element.
     * FHIRBackboneElementJsonNormalizer must emit issue[0].expression as a scalar array
     * and issue[0]._expression as a parallel extension array.
     */
    public function testBackboneElementStringArrayWithExtension(): void
    {
        $extUrl = 'http://example.org/expression-ext';

        $outcome = new OperationOutcomeResource(
            issue: [
                new OperationOutcomeIssue(
                    severity: new IssueSeverityType(value: 'error'),
                    code: new IssueTypeType(value: 'invalid'),
                    expression: [
                        new StringPrimitive(
                            value: 'Patient.name',
                            extension: [
                                new Extension(url: $extUrl),
                            ],
                        ),
                    ],
                ),
            ],
        );

        $json    = $this->service->serializeToJson($outcome);
        $decoded = json_decode($json, true);

        self::assertIsArray($decoded);

        $issue = $decoded['issue'][0];

        // Scalar value array
        self::assertSame('Patient.name', $issue['expression'][0]);

        // Parallel extension array
        self::assertArrayHasKey('_expression', $issue);
        self::assertSame($extUrl, $issue['_expression'][0]['extension'][0]['url']);

        // Round-trip: re-deserialize and confirm extension survives
        $roundTripped = $this->service->deserialize($json, OperationOutcomeResource::class);
        self::assertInstanceOf(OperationOutcomeResource::class, $roundTripped);
        $rtIssue = $roundTripped->issue[0];
        self::assertInstanceOf(OperationOutcomeIssue::class, $rtIssue);
        self::assertInstanceOf(StringPrimitive::class, $rtIssue->expression[0]);
        self::assertCount(1, $rtIssue->expression[0]->extension);
        self::assertSame($extUrl, $rtIssue->expression[0]->extension[0]->url);
    }
}
