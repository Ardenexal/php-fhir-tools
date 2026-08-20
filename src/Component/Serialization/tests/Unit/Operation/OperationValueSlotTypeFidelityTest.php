<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Operation;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemFindMatches\CodeSystemFindMatchesInProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemLookup\CodeSystemLookupOutProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemLookup\CodeSystemLookupOutput;
use Ardenexal\FHIRTools\Component\Models\R5\Operation\PatientMerge\PatientMergeInput;
use Ardenexal\FHIRTools\Component\Models\R5\Operation\StructureDefinitionQuestionnaire\StructureDefinitionQuestionnaireInput;
use Ardenexal\FHIRTools\Component\Models\R5\Operation\ValueSetValidateCode\ValueSetValidateCodeInput;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationMappingException;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationParameterMapper;
use PHPUnit\Framework\TestCase;

/**
 * The mapper must not coerce a value into a shape its declared type did not ask for.
 *
 * Three defects shared one root cause: the read path took whatever the wire produced and handed it
 * to a typed constructor without ever consulting the descriptor's declared type — while the *emit*
 * path consulted exactly that metadata. These tests pin the read path to the same metadata.
 *
 * Each test below fails on the pre-fix code, and each fails for a *different* reason, which is why
 * they are separate: silent corruption, an escaping TypeError, and a refused emit are three
 * distinguishable symptoms of one missing guard.
 */
final class OperationValueSlotTypeFidelityTest extends TestCase
{
    private function mapper(): OperationParameterMapper
    {
        return OperationParameterMapper::createDefault(FhirVersion::R5);
    }

    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createDefault(FhirVersion::R5);
    }

    /**
     * A complex type declared on a parameter must survive the round trip whole.
     *
     * `unwrapPrimitive()` matched on `property_exists($value, 'value')`, which is true for
     * `Identifier` (`Identifier::$value` is the identifier's own value element) just as much as for
     * a primitive wrapper. The Identifier was therefore replaced by its scalar and every sibling
     * element — `system` above all — was discarded.
     *
     * This is the silent shape: `PatientMergeInput::$sourcePatientIdentifier` is typed bare `array`
     * (only its docblock says `list<Identifier>`), so the coerced string was accepted without a
     * murmur. An identifier stripped of its system is a different identifier, and on `$merge` it is
     * the patient-matching input.
     */
    public function testAComplexTypedParameterKeepsItsSiblingElements(): void
    {
        $body = json_encode([
            'resourceType' => 'Parameters',
            'parameter'    => [[
                'name'            => 'source-patient-identifier',
                'valueIdentifier' => ['system' => 'http://acme.example/mrn', 'value' => 'P1'],
            ]],
        ], JSON_THROW_ON_ERROR);

        $typed = $this->service()->deserializeFromJson($body, PatientMergeInput::class);

        self::assertCount(1, $typed->sourcePatientIdentifier);
        $identifier = $typed->sourcePatientIdentifier[0];

        self::assertInstanceOf(
            Identifier::class,
            $identifier,
            'A parameter declared `type: Identifier` must arrive as an Identifier, not as its ->value scalar.',
        );

        // The system is the whole point: it is what makes the value a *namespaced* identifier.
        $system = $identifier->system;
        self::assertSame('http://acme.example/mrn', $system instanceof \Stringable ? (string) $system : $system);

        // And it has to survive the return leg, or a consumer that reads and re-emits a $merge
        // payload sends a different identifier than it received.
        $emitted = json_decode($this->service()->serializeToJson($this->mapper()->toParameters($typed)), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame(
            'http://acme.example/mrn',
            $emitted['parameter'][0]['valueIdentifier']['system'] ?? null,
            'The system must round-trip; dropping it silently corrupts a patient-matching input.',
        );
    }

    /**
     * The same guard has to hold on the XML leg, which shares the mapper.
     *
     * `FHIROperationPayloadXmlNormalizer` and its JSON sibling both delegate to this one mapper, so
     * the read-path change lands on both — but every other test here enters through JSON. XML is the
     * leg where a complex type could plausibly differ, because the decoder builds the object graph
     * from elements and attributes rather than from a JSON object.
     */
    public function testTheComplexTypeGuardHoldsOnTheXmlLegToo(): void
    {
        $xml = <<<'XML'
            <Parameters xmlns="http://hl7.org/fhir">
              <parameter>
                <name value="source-patient-identifier"/>
                <valueIdentifier>
                  <system value="http://acme.example/mrn"/>
                  <value value="P1"/>
                </valueIdentifier>
              </parameter>
            </Parameters>
            XML;

        $typed = $this->service()->deserializeFromXml($xml, PatientMergeInput::class);

        self::assertCount(1, $typed->sourcePatientIdentifier);
        self::assertInstanceOf(
            Identifier::class,
            $typed->sourcePatientIdentifier[0],
            'The XML leg shares the mapper, so it must not coerce the Identifier either.',
        );

        $system = $typed->sourcePatientIdentifier[0]->system;
        self::assertSame('http://acme.example/mrn', $system instanceof \Stringable ? (string) $system : $system);
    }

    /**
     * The same coercion on a strictly-typed property produced a raw TypeError.
     *
     * `StructureDefinitionQuestionnaireInput::$identifier` is `?Identifier`, so where
     * `PatientMergeInput` silently accepted a string this one threw — from the payload constructor,
     * as a TypeError, which extends Error and so escaped `FHIRSerializationService`'s
     * `catch (\Exception)`. Nothing in the library could catch it.
     */
    public function testTheSameCoercionOnATypedPropertyIsNotATypeError(): void
    {
        $body = json_encode([
            'resourceType' => 'Parameters',
            'parameter'    => [[
                'name'            => 'identifier',
                'valueIdentifier' => ['system' => 'http://acme.example/sd', 'value' => 'sd-1'],
            ]],
        ], JSON_THROW_ON_ERROR);

        $typed = $this->service()->deserializeFromJson($body, StructureDefinitionQuestionnaireInput::class);

        self::assertInstanceOf(Identifier::class, $typed->identifier);
    }

    /**
     * A wire value the declared type cannot accept must arrive as OperationMappingException.
     *
     * `buildPayload` never checked the value against the descriptor before calling
     * `new $class(...$arguments)`, so a mismatch surfaced as a TypeError from the constructor.
     * Because TypeError extends Error, `FHIRSerializationService::deserializeFromJson`'s
     * `catch (\Exception)` did not see it and no project exception type wrapped it — on an HTTP
     * boundary that is a 500 where a 400 belongs.
     *
     * `code` on $validate-code is declared `type: code`, so a `valueCoding` is a contract violation.
     */
    public function testAMismatchedValueRaisesAMappingExceptionNotATypeError(): void
    {
        $body = json_encode([
            'resourceType' => 'Parameters',
            'parameter'    => [['name' => 'code', 'valueCoding' => ['code' => 'x']]],
        ], JSON_THROW_ON_ERROR);

        try {
            $this->service()->deserializeFromJson($body, ValueSetValidateCodeInput::class);
            self::fail('Expected the mismatched value to be rejected.');
        } catch (\TypeError $e) {
            self::fail('A TypeError escaped instead of a mapping failure: ' . $e->getMessage());
        } catch (\Exception $e) {
            // The point is that it is an Exception at all — catchable by the library's own
            // catch blocks and by any consumer that catches the FHIR exception hierarchy.
            self::assertStringContainsString(
                'code',
                $e->getMessage(),
                'The failure must name the offending parameter.',
            );
        }
    }

    /**
     * A conformant polymorphic decimal must read in *and* emit again.
     *
     * `toValueSlot`'s polymorphic arm admitted only `is_object || is_bool || is_int`. FHIR `decimal`
     * is carried as a PHP string to preserve its lexical form — the same file says so 25 lines
     * below the guard — so `valueDecimal` read in fine and then threw on the way out. decimal is
     * the only one of the seven value[x] variants that breaks: `boolean` is a bool and `integer` an
     * int, and `string` deserializes to a StringPrimitive object. That is why nothing caught it.
     *
     * Among all 54 `value[x]` variants exactly one is scalar-kind with a `string` phpType —
     * `decimal` — so a bare string in a polymorphic slot is unambiguous, not a guess.
     *
     * Note this asserts the emitted *key*, not the numeric precision: collapsing `1.2300` to `1.23`
     * on encode is a separate, pre-existing defect that affects every decimal in the library
     * (`Quantity.value` included), not something this path introduces.
     */
    public function testAPolymorphicDecimalCanBeReEmitted(): void
    {
        $body = json_encode([
            'resourceType' => 'Parameters',
            'parameter'    => [
                ['name' => 'name', 'valueString' => 'SNOMED CT'],
                ['name' => 'display', 'valueString' => 'Body mass index'],
                ['name' => 'property', 'part' => [
                    ['name' => 'code', 'valueCode' => 'kg-per-m2'],
                    ['name' => 'value', 'valueDecimal' => '1.2300'],
                ]],
            ],
        ], JSON_THROW_ON_ERROR);

        $typed = $this->service()->deserializeFromJson($body, CodeSystemLookupOutput::class);

        // Read side already worked; pinned so a fix that breaks it is caught here.
        self::assertSame('1.2300', $typed->property[0]->value);

        $emitted = json_decode(
            $this->service()->serializeToJson($this->mapper()->toParameters($typed)),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        $valueEntry = null;
        foreach ($emitted['parameter'] as $parameter) {
            foreach ($parameter['part'] ?? [] as $part) {
                if (($part['name'] ?? null) === 'value') {
                    $valueEntry = $part;
                }
            }
        }

        self::assertNotNull($valueEntry, 'The nested value parameter must be emitted.');
        self::assertArrayHasKey(
            'valueDecimal',
            $valueEntry,
            'The decimal must land in the valueDecimal slot, not another variant.',
        );
    }

    /**
     * The guard must not become a blanket "strings are fine" in polymorphic slots.
     *
     * A bare string is resolvable *only* because `decimal` is the sole scalar-kind string variant
     * among all 54 `value[x]` variants. A parameter whose own variant set excludes decimal still
     * cannot infer a type from a bare string and must keep refusing it — otherwise the fix trades a
     * loud failure for a silent mis-slotting.
     *
     * `CodeSystemFindMatchesInProperty::$value` is the case: 6 variants
     * (code, boolean, dateTime, integer, string, Coding) and no decimal. Only 4 polymorphic
     * parameters in the whole R5 corpus include decimal, so this negative is the common shape.
     */
    public function testABareStringIsStillRefusedWhereNoDecimalVariantExists(): void
    {
        $this->expectException(OperationMappingException::class);
        $this->expectExceptionMessage('is polymorphic');

        $this->mapper()->toParameters(
            new CodeSystemFindMatchesInProperty(code: 'prop', value: 'a-bare-string'),
        );
    }

    /**
     * A non-numeric string on a decimal-bearing parameter is still ambiguous, and still refused.
     *
     * This is the boundary the first attempt at the fix got wrong, and the existing suite caught it:
     * admitting *any* string wherever `decimal` is a declared variant made `'ambiguous'` a decimal.
     * It is not one — it is still an unguessable `valueCode`/`valueString`. Only the decimal lexical
     * form earns the exemption, so both halves of the guard are pinned here and in
     * {@see testABareStringIsStillRefusedWhereNoDecimalVariantExists} above.
     *
     * `CodeSystemLookupOutProperty::$value` is decimal-bearing, which is what makes this the sharp
     * case rather than a restatement of the no-decimal-variant test.
     */
    public function testANonNumericStringIsRefusedEvenWhereDecimalIsAVariant(): void
    {
        $this->expectException(OperationMappingException::class);
        $this->expectExceptionMessageMatches('/polymorphic/');

        $this->mapper()->toParameters(
            new CodeSystemLookupOutput(
                name: 'x',
                display: 'y',
                property: [new CodeSystemLookupOutProperty(code: 'p', value: 'ambiguous')],
            ),
        );
    }

    /**
     * Nor may a type that is not a variant at all slip through on the polymorphic path.
     *
     * A float is the sharp case: FHIR carries `decimal` as a string precisely so that a float never
     * represents one, so admitting floats here would reintroduce the precision loss the string
     * carrier exists to prevent.
     */
    public function testAFloatIsStillRefusedInAPolymorphicSlot(): void
    {
        $this->expectException(OperationMappingException::class);

        $this->mapper()->toParameters(
            new CodeSystemLookupOutput(
                name: 'x',
                display: 'y',
                property: [new CodeSystemLookupOutProperty(code: 'kg-per-m2', value: 1.23)],
            ),
        );
    }
}
