<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRTypedScalar;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRTypeResolver;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Count;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Distance;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\OidPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UuidPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\XhtmlPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Profile\SimpleQuantityProfile;
use PHPUnit\Framework\Attributes\DataProvider;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Pins every FHIR type-conformance answer the FHIRPath resolver gives.
 *
 * The resolver used to hand-maintain a 17-entry parent map. It disagreed with generated model
 * inheritance in four places, and in all four the models matched the R4 StructureDefinitions and the
 * table did not. Conformance now reads that inheritance through
 * `Metadata\Type\FHIRTypeAncestryProviderInterface`, and the cases below were re-pinned as a
 * deliberate change with each flip judged, not edited to clear a red suite.
 *
 * Cases marked CORRECTED are the ones whose answer changed. Every one of them changed towards the
 * StructureDefinition: `uri`, `base64Binary` and `instant` derive from `Element`, not from `string`
 * or `dateTime`, and R4 redefined `Money` as its own type rather than a `Quantity` specialization.
 *
 * Do not edit an expectation here to make a suite green. A flip means either the models changed or
 * the resolver did, and both deserve a look before an expectation moves.
 */
class TypeConformanceBaselineTest extends TestCase
{
    /**
     * Every entry currently in the parent table, with the answer observed today.
     *
     * @return iterable<string, array{0: object, 1: string, 2: bool}>
     */
    public static function conformanceCases(): iterable
    {
        // Agree with the generated models and with the spec.
        yield 'code is string'          => [new CodePrimitive(value: 'x'), 'string', true];
        yield 'id is string'            => [new IdPrimitive(value: 'x'), 'string', true];
        yield 'markdown is string'      => [new MarkdownPrimitive(value: 'x'), 'string', true];
        yield 'url is uri'              => [new UrlPrimitive(value: 'http://x'), 'uri', true];
        yield 'canonical is uri'        => [new CanonicalPrimitive(value: 'http://x'), 'uri', true];
        yield 'oid is uri'              => [new OidPrimitive(value: 'urn:oid:1.2'), 'uri', true];
        yield 'uuid is uri'             => [new UuidPrimitive(value: 'urn:uuid:x'), 'uri', true];
        yield 'positiveInt is integer'  => [new PositiveIntPrimitive(value: 1), 'integer', true];
        yield 'unsignedInt is integer'  => [new UnsignedIntPrimitive(value: 0), 'integer', true];

        // Agree, and are redundant: these four also extend Quantity in PHP, so the inheritance walk
        // below the table already answers them. See testTheInheritanceWalkAnswersWithoutATableEntry.
        yield 'Age is Quantity'         => [new Age(), 'Quantity', true];
        yield 'Count is Quantity'       => [new Count(), 'Quantity', true];
        yield 'Distance is Quantity'    => [new Distance(), 'Quantity', true];
        yield 'Duration is Quantity'    => [new Duration(), 'Quantity', true];

        // CORRECTED. R4 gives all four a baseDefinition of Element, and the retired table was the
        // only thing that ever answered true here.
        yield 'uri is string'           => [new UriPrimitive(value: 'http://x'), 'string', false];
        yield 'base64Binary is string'  => [new Base64BinaryPrimitive(value: 'eA=='), 'string', false];
        yield 'Money is Quantity'       => [new Money(), 'Quantity', false];
        yield 'instant is dateTime'     => [
            new InstantPrimitive(value: FHIRInstant::parse('2020-01-01T00:00:00Z')),
            'dateTime',
            false,
        ];
    }

    /**
     * Every parent-table answer the `is` operator gives today, held exactly as it is.
     *
     * @param object $subject     an instance of the derived type under test
     * @param string $parentType  the type the table claims it conforms to
     * @param bool   $doesConform the answer observed today, which this test freezes
     */
    #[DataProvider('conformanceCases')]
    public function testTheIsOperatorAnswerIsUnchanged(object $subject, string $parentType, bool $doesConform): void
    {
        self::assertSame($doesConform, (new FHIRTypeResolver())->isOfType($subject, $parentType, false));
    }

    /**
     * The ancestry walk is gated on non-strict matching, and `as` and `ofType` match strictly, so no
     * conformance answer reaches them and a filter such as ofType(string) does not over-select.
     *
     * @param object $subject     an instance of the derived type under test
     * @param string $parentType  the candidate ancestor type
     * @param bool   $doesConform unused here; strict matching rejects every case regardless
     */
    #[DataProvider('conformanceCases')]
    public function testStrictMatchingIgnoresTheAncestryWalkEntirely(object $subject, string $parentType, bool $doesConform): void
    {
        self::assertFalse((new FHIRTypeResolver())->isOfType($subject, $parentType, true));
    }

    /**
     * The mechanism that should own all of this already exists, directly below the table: a walk over
     * PHP inheritance. SimpleQuantityProfile has no entry in the table and still conforms, which is
     * why the four Quantity entries are redundant rather than load-bearing.
     */
    public function testTheInheritanceWalkAnswersWithoutATableEntry(): void
    {
        $profile = new SimpleQuantityProfile();

        self::assertInstanceOf(Quantity::class, $profile);
        self::assertTrue((new FHIRTypeResolver())->isOfType($profile, 'Quantity', false));
    }

    /**
     * The corrected answers now agree with PHP inheritance, which is what makes them checkable.
     *
     * Money does not extend Quantity and a uri primitive does not extend the string primitive, so
     * under the retired table the resolver contradicted the very models it was answering about. The
     * conformance answer and the `instanceof` answer now agree.
     */
    public function testTheCorrectedAnswersAgreeWithInheritance(): void
    {
        self::assertNotInstanceOf(Quantity::class, new Money());
        self::assertNotInstanceOf(UriPrimitive::class, new Base64BinaryPrimitive(value: 'eA=='));

        $resolver = new FHIRTypeResolver();
        self::assertFalse($resolver->isOfType(new Money(), 'Quantity', false));
        self::assertFalse($resolver->isOfType(new UriPrimitive(value: 'http://x'), 'string', false));
    }

    /**
     * The same conformance questions asked of a value that has no class behind it.
     *
     * `FHIRTypedScalar` is what the evaluator wraps a resource property in when the property is stored
     * as a PHP scalar, so it carries a FHIR type name and nothing else. Every case above supplies an
     * instance, and an instance can be answered by walking PHP inheritance; these cannot. The parent
     * table is the only mechanism that answers them, which is what the negative cases below establish.
     *
     * @return iterable<string, array{0: string, 1: string, 2: bool}>
     */
    public static function nameOnlyConformanceCases(): iterable
    {
        // Answered by a table entry.
        yield 'code is string'         => ['code', 'string', true];
        yield 'id is string'           => ['id', 'string', true];
        yield 'positiveInt is integer' => ['positiveInt', 'integer', true];

        // CORRECTED, by consequence rather than directly: url derives from uri, and uri derives from
        // Element, so the chain to string that the table asserted never existed.
        yield 'url is string'          => ['url', 'string', false];

        // CORRECTED. Each derives from Element in R4.
        yield 'uri is string'          => ['uri', 'string', false];
        yield 'base64Binary is string' => ['base64Binary', 'string', false];
        yield 'instant is dateTime'    => ['instant', 'dateTime', false];

        // CORRECTED. The table stopped at its own last entry, so an ancestor further up was
        // unreachable; a derived walk reaches the whole chain.
        yield 'code is Element'        => ['code', 'Element', true];

        // Negative control: a type the locator cannot place answers nothing at all.
        yield 'xhtml is string'        => ['xhtml', 'string', false];
    }

    /**
     * Freezes the answers for values carrying a type name and no class.
     *
     * @param string $fhirType    the FHIR type name the scalar carries
     * @param string $parentType  the type it is asked to conform to
     * @param bool   $doesConform the answer observed today, which this test freezes
     */
    #[DataProvider('nameOnlyConformanceCases')]
    public function testTheNameOnlyAnswerIsUnchanged(string $fhirType, string $parentType, bool $doesConform): void
    {
        $scalar = new FHIRTypedScalar('x', $fhirType);

        self::assertSame($doesConform, (new FHIRTypeResolver())->isOfType($scalar, $parentType, false));
    }

    /**
     * CORRECTED. The primitive set now follows the generated models rather than a hand-written list.
     *
     * `XhtmlPrimitive` is generated and carries #[FHIRPrimitive], but `xhtml` had no entry in the
     * resolver's map, so it was not a known type name and `x as xhtml` was an execution error for a
     * type FHIR defines. Membership is read from the models now, which closes that gap and keeps the
     * set from drifting again.
     */
    public function testThePrimitiveSetFollowsTheGeneratedModels(): void
    {
        $resolver = new FHIRTypeResolver();

        self::assertTrue(class_exists(XhtmlPrimitive::class), 'the generated primitive exists');
        self::assertTrue($resolver->isPrimitiveType('xhtml'));
        self::assertTrue($resolver->isKnownTypeName('xhtml'));

        self::assertTrue($resolver->isPrimitiveType('markdown'));
        self::assertTrue($resolver->isKnownTypeName('markdown'));

        // Complex types and resources are still not primitives.
        self::assertFalse($resolver->isPrimitiveType('Quantity'));
        self::assertFalse($resolver->isPrimitiveType('Patient'));
    }
}
