<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ContentModelOrderResolver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * The merge rule, on the shapes that broke.
 *
 * Each case here is a real CDA shape reduced to its skeleton, so a failure names the rule that
 * broke rather than pointing at a 33-element list.
 */
#[CoversClass(ContentModelOrderResolver::class)]
final class ContentModelOrderResolverTest extends TestCase
{
    /**
     * Wrap plain names as own-parameter descriptors, the shape the generator hands the resolver.
     *
     * @param list<string> $names the property names this class declares itself
     *
     * @return list<array<string, mixed>> those names as descriptors, order preserved
     */
    private static function descriptors(array $names): array
    {
        return array_map(static fn (string $name): array => ['name' => $name], $names);
    }

    /**
     * A class with no parent has nothing to merge into, so its own snapshot is its content model.
     */
    public function testRootTakesItsOwnSnapshotOrder(): void
    {
        $orders = (new ContentModelOrderResolver())->resolve(
            ['root' => null],
            ['root' => ['realmCode', 'typeId', 'templateId']],
            ['root' => self::descriptors(['realmCode', 'typeId', 'templateId'])],
        );

        self::assertSame(['realmCode', 'typeId', 'templateId'], $orders['root']);
    }

    /**
     * The reported fault: a parent contributes the leading elements, so they must lead the child too.
     * Concatenating own-then-parent is what put `templateId` last on every CDA act.
     */
    public function testInheritedLeadingElementsStayAheadOfTheChildsOwn(): void
    {
        $orders = (new ContentModelOrderResolver())->resolve(
            ['act' => 'infrastructureRoot', 'infrastructureRoot' => null],
            [
                'infrastructureRoot' => ['realmCode', 'typeId', 'templateId'],
                'act'                => ['realmCode', 'typeId', 'templateId', 'id', 'statusCode'],
            ],
            [
                'infrastructureRoot' => self::descriptors(['realmCode', 'typeId', 'templateId']),
                'act'                => self::descriptors(['id', 'statusCode']),
            ],
        );

        self::assertSame(['realmCode', 'typeId', 'templateId', 'id', 'statusCode'], $orders['act']);
    }

    /**
     * The second reported fault: an AU child's own element belongs in the MIDDLE of its parent's
     * sequence. No hierarchy-based rule can place it, which is why the snapshot supplies the position.
     */
    public function testChildsOwnElementLandsMidSequence(): void
    {
        $orders = (new ContentModelOrderResolver())->resolve(
            ['auDocument' => 'document', 'document' => null],
            [
                'document'   => ['id', 'versionNumber', 'copyTime', 'recordTarget'],
                'auDocument' => ['id', 'versionNumber', 'completionCode', 'copyTime', 'recordTarget'],
            ],
            [
                'document'   => self::descriptors(['id', 'versionNumber', 'copyTime', 'recordTarget']),
                'auDocument' => self::descriptors(['completionCode']),
            ],
        );

        self::assertSame(
            ['id', 'versionNumber', 'completionCode', 'copyTime', 'recordTarget'],
            $orders['auDocument'],
        );
    }

    /**
     * An AU profile snapshot omits the `sdtc` extension elements its core parent declares. Anchoring
     * on the parent keeps them; deriving from the child's own snapshot silently dropped 46 classes'
     * worth of them.
     */
    public function testInheritedElementsAbsentFromTheChildSnapshotSurvive(): void
    {
        $orders = (new ContentModelOrderResolver())->resolve(
            ['auAct' => 'act', 'act' => null],
            [
                'act'   => ['id', 'precondition', 'sdtcInFulfillmentOf1'],
                'auAct' => ['id', 'precondition', 'inFulfillmentOf1'],
            ],
            [
                'act'   => self::descriptors(['id', 'precondition', 'sdtcInFulfillmentOf1']),
                'auAct' => self::descriptors(['inFulfillmentOf1']),
            ],
        );

        self::assertContains('sdtcInFulfillmentOf1', $orders['auAct']);
        self::assertContains('inFulfillmentOf1', $orders['auAct']);
    }

    /**
     * CDA `Section` declares both an `ID` XML attribute and an `id` element. They are distinct
     * properties and both must survive; a case-folded lookup would keep only one.
     */
    public function testNamesDifferingOnlyByCaseBothSurvive(): void
    {
        $orders = (new ContentModelOrderResolver())->resolve(
            ['section' => null],
            ['section' => ['templateId', 'ID', 'id']],
            ['section' => self::descriptors(['templateId', 'ID', 'id'])],
        );

        self::assertSame(['templateId', 'ID', 'id'], $orders['section']);
    }

    /**
     * An own element the class's own snapshot does not position goes last, rather than being guessed
     * into the middle. Nothing in the definition says where it belongs.
     */
    public function testUnpositionedOwnElementGoesLast(): void
    {
        $orders = (new ContentModelOrderResolver())->resolve(
            ['wrapper' => 'root', 'root' => null],
            [
                'root'    => ['realmCode', 'templateId'],
                'wrapper' => ['realmCode', 'templateId'],
            ],
            [
                'root'    => self::descriptors(['realmCode', 'templateId']),
                'wrapper' => self::descriptors(['section']),
            ],
        );

        self::assertSame(['realmCode', 'templateId', 'section'], $orders['wrapper']);
    }

    /**
     * A malformed parent chain must not hang generation; the cycle guard returns a partial answer.
     */
    public function testCyclicParentChainDoesNotRecurseForever(): void
    {
        $orders = (new ContentModelOrderResolver())->resolve(
            ['a' => 'b', 'b' => 'a'],
            ['a' => ['one'], 'b' => ['two']],
            ['a' => self::descriptors(['one']), 'b' => self::descriptors(['two'])],
        );

        self::assertArrayHasKey('a', $orders);
        self::assertArrayHasKey('b', $orders);
    }
}
