<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\OperationClassNamer;
use Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemLookup\CodeSystemLookupOutput;
use Ardenexal\FHIRTools\Component\Models\R4\Operation\ValueSetValidateCode\ValueSetValidateCodeInput;
use Ardenexal\FHIRTools\Component\Models\R4\Operation\ValueSetValidateCode\ValueSetValidateCodeOperation;
use Ardenexal\FHIRTools\Component\Models\R4\Operation\ValueSetValidateCode\ValueSetValidateCodeOutput;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ParametersResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationMappingException;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationParameterMapper;
use PHPUnit\Framework\TestCase;

/**
 * Executes the worked examples published in the operations documentation.
 *
 * Every test method here corresponds to one section of `docs/serialization/operations.md` or
 * `docs/code-generation/operations.md`, named after it, and the docs point back at this file by path.
 * The pairing is the point: a documented example that nothing runs is a claim, and this codebase has
 * already been bitten by examples drifting from the API they describe.
 *
 * If you change an example here, change the corresponding doc section, and vice versa. If a method
 * name below no longer matches a heading in those pages, one of the two moved without the other.
 *
 * Scoped to R4 deliberately. Cross-version equivalence of the same payloads is
 * `OperationParameterMapperTest`'s job (M03's cross-version parity gate); duplicating it here would
 * make the docs harder to read without testing anything new.
 */
final class OperationDocsExamplesTest extends TestCase
{
    /**
     * `docs/serialization/operations.md` § "Building a request".
     *
     * A typed Input becomes a conformant `Parameters` request body. Note what the mapper does with
     * `$code`: the class declares a bare `?string`, and the wire form is `valueCode`, not
     * `valueString` — the parameter's declared FHIR type drives the choice, not the PHP type.
     */
    public function testBuildingARequest(): void
    {
        $mapper  = OperationParameterMapper::createDefault(FhirVersion::R4);
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);

        $input = new ValueSetValidateCodeInput(
            url: 'http://hl7.org/fhir/ValueSet/administrative-gender',
            code: 'female',
            system: 'http://hl7.org/fhir/administrative-gender',
        );

        $json = $service->serializeToJson($mapper->toParameters($input));

        self::assertSame(
            [
                'resourceType' => 'Parameters',
                'parameter'    => [
                    ['name' => 'url', 'valueUri' => 'http://hl7.org/fhir/ValueSet/administrative-gender'],
                    ['name' => 'code', 'valueCode' => 'female'],
                    ['name' => 'system', 'valueUri' => 'http://hl7.org/fhir/administrative-gender'],
                ],
            ],
            json_decode($json, true, 512, \JSON_THROW_ON_ERROR),
            'The documented request body no longer matches what the mapper emits.',
        );

        // The page claims the XML leg works identically on the same object. Same mapper output, same
        // serializer, different encoder — so this is a claim about the mapping being format-agnostic.
        $xml = $service->serializeToXml($mapper->toParameters($input));

        self::assertStringContainsString('<Parameters', $xml);
        self::assertStringContainsString('<valueCode value="female"/>', $xml);
    }

    /**
     * `docs/serialization/operations.md` § "Reading a response".
     *
     * `fromResponse()` — not `fromParameters()` — is the documented entry point, because it consults
     * the operation's declared output shape. This replaces exactly the hand-rolled "walk the decoded
     * JSON looking for name === 'result'" that `MemberOfFunction` still does.
     */
    public function testReadingAResponse(): void
    {
        $mapper  = OperationParameterMapper::createDefault(FhirVersion::R4);
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);

        $body = <<<'JSON'
            {
              "resourceType": "Parameters",
              "parameter": [
                { "name": "result",  "valueBoolean": false },
                { "name": "message", "valueString": "Unknown code 'flase'" },
                { "name": "display", "valueString": "Female" }
              ]
            }
            JSON;

        $parameters = $service->deserializeFromJson($body, ParametersResource::class);
        $output     = $mapper->fromResponse($parameters, ValueSetValidateCodeOperation::class);

        self::assertInstanceOf(ValueSetValidateCodeOutput::class, $output);
        self::assertFalse($output->result);
        self::assertSame("Unknown code 'flase'", $output->message);
        self::assertSame('Female', $output->display);
    }

    /**
     * `docs/serialization/operations.md` § "Nested parameter groups".
     *
     * `$lookup`'s `property` / `subproperty` groups are real typed classes, keyed by parameter path,
     * and `value[x]` resolves through the same choice machinery the models use. This is the shape
     * that makes hand-parsing a `Parameters` genuinely painful, so it is the one worth documenting.
     */
    public function testNestedParameterGroups(): void
    {
        $mapper  = OperationParameterMapper::createDefault(FhirVersion::R4);
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);

        $body = <<<'JSON'
            {
              "resourceType": "Parameters",
              "parameter": [
                { "name": "name",    "valueString": "SNOMED CT" },
                { "name": "display", "valueString": "Left displacement" },
                {
                  "name": "property",
                  "part": [
                    { "name": "code",  "valueCode": "parent" },
                    { "name": "value", "valueCode": "263678003" },
                    {
                      "name": "subproperty",
                      "part": [
                        { "name": "code",  "valueCode": "inherited" },
                        { "name": "value", "valueCode": "263679000" }
                      ]
                    }
                  ]
                }
              ]
            }
            JSON;

        $parameters = $service->deserializeFromJson($body, ParametersResource::class);
        $output     = $mapper->fromParameters($parameters, CodeSystemLookupOutput::class);

        self::assertSame('SNOMED CT', $output->name);
        self::assertCount(1, $output->property);
        self::assertSame('parent', $output->property[0]->code);
        self::assertCount(1, $output->property[0]->subproperty);
        self::assertSame('inherited', $output->property[0]->subproperty[0]->code);

        // The documented claim is a *round trip*, not just a read: re-emitting reproduces the body.
        self::assertSame(
            json_decode($body, true, 512, \JSON_THROW_ON_ERROR),
            json_decode($service->serializeToJson($mapper->toParameters($output)), true, 512, \JSON_THROW_ON_ERROR),
            'The documented nested example no longer round-trips.',
        );
    }

    /**
     * `docs/serialization/operations.md` § "Missing required parameters fail loudly".
     *
     * The documented failure mode. Worth a test because generated models make every property
     * nullable regardless of cardinality, so a cardinality-invalid payload constructs happily and
     * passes PHPStan — the mapper is the layer that notices.
     */
    public function testMissingRequiredParametersFailLoudly(): void
    {
        $mapper = OperationParameterMapper::createDefault(FhirVersion::R4);

        // `name` and `display` are both min:1 on `$lookup`'s output; omit `display`.
        $incomplete = new CodeSystemLookupOutput(name: 'SNOMED CT');

        $this->expectException(OperationMappingException::class);
        $this->expectExceptionMessageMatches('/"display" is required/');

        $mapper->toParameters($incomplete);
    }

    /**
     * `docs/code-generation/operations.md` § "How class names are derived".
     *
     * Pins the rules table in that section. Each assertion is one row; if a row changes, this fails
     * and the table has to be edited with it.
     */
    public function testHowClassNamesAreDerived(): void
    {
        $namer = new OperationClassNamer();

        // Stem = resource[0] + code, each PascalCased. Hyphens are mapped, not kept.
        self::assertSame('ValueSetValidateCode', $namer->classStem([
            'resource' => ['ValueSet'],
            'code'     => 'validate-code',
        ]));
        self::assertSame('CodeSystemLookup', $namer->classStem([
            'resource' => ['CodeSystem'],
            'code'     => 'lookup',
        ]));

        // Nested `part` groups are keyed by `use` plus the parameter path, so an `in` and an `out`
        // parameter of the same name cannot collide.
        self::assertSame('OutProperty', $namer->partClassName('out', ['property']));
        self::assertSame('OutPropertySubproperty', $namer->partClassName('out', ['property', 'subproperty']));
        self::assertSame('InProperty', $namer->partClassName('in', ['property']));

        // Wire names that are not legal PHP identifiers are mapped; the mapping is not reversible,
        // which is why the wire name is stored separately on the attribute.
        self::assertSame('count', $namer->propertyName('_count'));
        self::assertSame('count', $namer->propertyName('count'));
        self::assertSame('targetIdentifierPeriod', $namer->propertyName('targetIdentifier.period'));

        // Reserved words are guarded for *class* names only, by an `Operation` suffix. A definition
        // with no `resource` and the code `use` would otherwise emit an unparseable class name.
        self::assertSame('UseOperation', $namer->classStem(['resource' => [], 'code' => 'use']));

        // Properties never needed that guard: `$use` is legal PHP, so the wire name is emitted
        // verbatim — see `CodeSystemLookupOutDesignation::$use`.
        self::assertSame('use', $namer->propertyName('use'));
    }

    /**
     * `docs/code-generation/operations.md` § "Collisions are fatal, never silent".
     */
    public function testCollisionsAreFatalNeverSilent(): void
    {
        $namer = new OperationClassNamer();

        $this->expectExceptionMessageMatches('/both derive the identifier/');

        $namer->assertNoCollisions(
            ['CodeSystem-lookup' => 'CodeSystemLookup', 'CodeSystem_lookup' => 'CodeSystemLookup'],
            'docs example',
        );
    }
}
