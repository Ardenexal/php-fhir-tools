<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRMetadataExtractor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The four structural predicates, asked of real generated classes rather than local fixtures.
 *
 * The predicates delegate to `FHIRStructureKindProviderInterface`, which answers from *declared*
 * kinds one class at a time -- so the cases that matter are the ones where a class's own marker and
 * the marker it inherits disagree, and a hand-written fixture that carries a single attribute and
 * extends nothing cannot express that. Both live cases come from the generated hierarchy:
 *
 *  - A backbone element declares `#[FHIRBackboneElement]` and inherits `#[FHIRComplexType]`. It must
 *    stay a complex type, because `FHIRComplexTypeJsonNormalizer` claims backbone elements and
 *    handles them internally; `nearestKindAmong()` reaches that answer only by walking *past* the
 *    declared backbone kind rather than stopping there.
 *  - A primitive declares `#[FHIRPrimitive]` and also inherits `#[FHIRComplexType]` from `Element`,
 *    and must NOT be a complex type -- the complex normalizer is registered ahead of the primitive
 *    one, so a true here serializes `meta.profile` as `[{"value": "..."}]`.
 *
 * Instances are built without their constructors throughout: the predicates read class attributes, so
 * an uninitialized instance answers identically and no per-class constructor arguments are needed.
 *
 * @author Ardenexal
 */
final class FHIRMetadataExtractorStructuralPredicatesTest extends TestCase
{
    private const string RESOURCE = 'Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource';

    private const string COMPLEX_TYPE = 'Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName';

    private const string PRIMITIVE = 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive';

    /** Declares `#[FHIRBackboneElement]` and inherits `#[FHIRComplexType]`, so both answers are true. */
    private const string BACKBONE_ELEMENT = 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient\PatientContact';

    /**
     * @return iterable<string, array{class-string, bool, bool, bool, bool}>
     */
    public static function structuralAnswers(): iterable
    {
        //                        class                   resource complex primitive backbone
        yield 'resource'         => [self::RESOURCE, true, false, false, false];
        yield 'complex type'     => [self::COMPLEX_TYPE, false, true, false, false];
        yield 'primitive'        => [self::PRIMITIVE, false, false, true, false];
        yield 'backbone element' => [self::BACKBONE_ELEMENT, false, true, false, true];
    }

    /**
     * @param class-string $className
     */
    #[DataProvider('structuralAnswers')]
    public function testEachPredicateAnswersForAGeneratedClass(
        string $className,
        bool $isResource,
        bool $isComplexType,
        bool $isPrimitive,
        bool $isBackbone,
    ): void {
        $extractor = new FHIRMetadataExtractor();
        $object    = (new \ReflectionClass($className))->newInstanceWithoutConstructor();

        self::assertSame($isResource, $extractor->isResource($object), 'isResource');
        self::assertSame($isComplexType, $extractor->isComplexType($object), 'isComplexType');
        self::assertSame($isPrimitive, $extractor->isPrimitiveType($object), 'isPrimitiveType');
        self::assertSame($isBackbone, $extractor->isBackboneElement($object), 'isBackboneElement');
    }

    /**
     * The answers do not depend on which predicate is asked first.
     *
     * Each predicate memoizes into `FHIRMetadataCache` under its own kind. One shared slot per class
     * made the first question asked answer all four, and the order genuinely varies: serializing to
     * XML asks `isComplexType()` about a primitive before anything asks `isPrimitiveType()`.
     */
    public function testAnswersDoNotDependOnQuestionOrder(): void
    {
        $primitiveFirst = new FHIRMetadataExtractor();
        $complexFirst   = new FHIRMetadataExtractor();

        $a = (new \ReflectionClass(self::PRIMITIVE))->newInstanceWithoutConstructor();
        $b = (new \ReflectionClass(self::PRIMITIVE))->newInstanceWithoutConstructor();

        self::assertTrue($primitiveFirst->isPrimitiveType($a));
        self::assertFalse($primitiveFirst->isComplexType($a));

        self::assertFalse($complexFirst->isComplexType($b));
        self::assertTrue($complexFirst->isPrimitiveType($b));
    }
}
