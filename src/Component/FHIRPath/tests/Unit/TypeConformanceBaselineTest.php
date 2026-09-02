<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit;

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
use Ardenexal\FHIRTools\Component\Models\R4\Profile\SimpleQuantityProfile;
use PHPUnit\Framework\Attributes\DataProvider;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Pins every FHIR type-conformance answer the FHIRPath resolver gives today, before the type tables
 * move into Metadata.
 *
 * `FHIRTypeResolver` (search: `TYPE_PARENTS`) hand-maintains a 17-entry parent map, and the generated
 * models encode the same hierarchy as PHP inheritance. The two disagree in four places, and in those
 * four the models match the R4 StructureDefinitions and the table does not. Those answers are pinned
 * here as they are today, WRONG INCLUDED, so that:
 *
 *  - moving the knowledge into Metadata can be proven to change nothing, and
 *  - correcting it later shows up as an explicit diff on named cases.
 *
 * Do not "fix" an expectation in this file to make a suite green. Cases marked PINS-A-DEFECT are the
 * only ones expected to change, and only in the milestone that deliberately changes them.
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

        // PINS-A-DEFECT. R4 gives all four a baseDefinition of Element. The table says otherwise, and
        // the table is the only thing producing these answers.
        yield 'uri is string'           => [new UriPrimitive(value: 'http://x'), 'string', true];
        yield 'base64Binary is string'  => [new Base64BinaryPrimitive(value: 'eA=='), 'string', true];
        yield 'Money is Quantity'       => [new Money(), 'Quantity', true];
        yield 'instant is dateTime'     => [
            new InstantPrimitive(value: FHIRInstant::parse('2020-01-01T00:00:00Z')),
            'dateTime',
            true,
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
     * The parent walk is gated on non-strict matching, and `as` and `ofType` match strictly, so none
     * of the table's answers reach them and a filter such as ofType(string) does not over-select.
     *
     * @param object $subject     an instance of the derived type under test
     * @param string $parentType  the type the table claims it conforms to
     * @param bool   $doesConform unused here; strict matching rejects every case regardless
     */
    #[DataProvider('conformanceCases')]
    public function testStrictMatchingIgnoresTheParentTableEntirely(object $subject, string $parentType, bool $doesConform): void
    {
        self::assertTrue($doesConform, 'every case in this provider conforms non-strictly');
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
     * Isolates the defect. Money does NOT extend Quantity, so the inheritance walk cannot be what
     * makes `Money is Quantity` true — only the table entry can. Same shape for uri and base64Binary,
     * whose PHP parent is Element rather than the string primitive.
     */
    public function testTheDefectiveAnswersComeFromTheTableAndNotFromInheritance(): void
    {
        self::assertNotInstanceOf(Quantity::class, new Money());
        self::assertNotInstanceOf(UriPrimitive::class, new Base64BinaryPrimitive(value: 'eA=='));

        $resolver = new FHIRTypeResolver();
        self::assertTrue($resolver->isOfType(new Money(), 'Quantity', false));
        self::assertTrue($resolver->isOfType(new UriPrimitive(value: 'http://x'), 'string', false));
    }
}
