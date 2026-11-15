<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantFastPaths;
use PHPUnit\Framework\TestCase;

/**
 * The fast paths may only ever answer "passes"; anything that could fail goes to the engine.
 *
 * The corpus test proves the passes are right. These pin the other half: the cases that must be
 * left to the engine, because answering them here would either need data the fast path does not
 * read or would be a failure the engine has to report with its own message.
 */
final class FHIRPathInvariantFastPathsTest extends TestCase
{
    public function testEle1PassesAnElementWithAChildOtherThanItsId(): void
    {
        self::assertTrue(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::ELE_1, new Coding(code: null, display: 'Weight')));
    }

    public function testEle1LeavesAnElementHoldingOnlyAnIdToTheEngine(): void
    {
        self::assertNull(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::ELE_1, new Coding(id: 'a1')));
    }

    public function testRef1PassesAnAbsoluteOrRelativeReference(): void
    {
        self::assertTrue(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::REF_1_R4, new Reference(reference: 'Patient/1')));
        self::assertTrue(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::REF_1_R4, new Reference(display: 'Dr Smith')));
    }

    /**
     * A `#` reference resolves against `%rootResource.contained`, which the fast path never reads.
     */
    public function testRef1LeavesAContainedReferenceToTheEngine(): void
    {
        self::assertNull(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::REF_1_R4, new Reference(reference: '#med1')));
    }

    public function testExt1LeavesAnExtensionWithBothValueAndSubExtensionsToTheEngine(): void
    {
        $extension = new Extension(extension: [new Extension(url: 'part', value: 'x')], url: 'http://example.org/ext', value: 'y');

        self::assertNull(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::EXT_1, $extension));
        self::assertTrue(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::EXT_1, new Extension(url: 'http://example.org/ext', value: 'y')));
    }

    /**
     * The engine's `extension.exists()` counts a null entry or a keyed entry, so a value alongside
     * either one fails ext-1 and must not be passed here.
     */
    public function testExt1LeavesAValueBesideNullOrKeyedSubExtensionsToTheEngine(): void
    {
        foreach ([[null], ['a' => new Extension(url: 'part', value: 'z')]] as $subExtensions) {
            $extension            = new Extension(url: 'http://example.org/ext', value: 'y');
            $extension->extension = $subExtensions; // @phpstan-ignore assign.propertyType (an in-memory shape the generated type does not allow)

            self::assertNull(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::EXT_1, $extension));
        }
    }

    public function testExt1LeavesAnExtensionWithNeitherValueNorSubExtensionsToTheEngine(): void
    {
        self::assertNull(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::EXT_1, new Extension(url: 'http://example.org/ext')));
    }

    /**
     * A primitive wrapper with no value and no extensions might read as absent, so it is not a plain value.
     */
    public function testExt1PassesAWrappedValueOnlyWhenTheWrapperHoldsSomething(): void
    {
        $filled = new Extension(url: 'http://example.org/ext', value: new StringPrimitive(value: 'y'));
        $empty  = new Extension(url: 'http://example.org/ext', value: new StringPrimitive());

        self::assertTrue(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::EXT_1, $filled));
        self::assertNull(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::EXT_1, $empty));
    }

    public function testRef1PassesAReferenceWithNoTarget(): void
    {
        self::assertTrue(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::REF_1_R5, new Reference(display: 'Dr Smith')));
    }

    /**
     * A wrapped target is read through its value; a wrapper with no value may carry extensions only.
     */
    public function testRef1ReadsAWrappedTargetThroughItsValue(): void
    {
        self::assertTrue(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::REF_1_R4, new Reference(reference: new StringPrimitive(value: 'Patient/1'))));
        self::assertNull(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::REF_1_R4, new Reference(reference: new StringPrimitive(value: '#med1'))));
        self::assertNull(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::REF_1_R4, new Reference(reference: new StringPrimitive())));
    }

    public function testAValueThatIsNotAnObjectIsLeftToTheEngine(): void
    {
        self::assertNull(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::ELE_1, 'Weight'));
    }

    /**
     * Matching is on the exact text: a profile that tightens ele-1 must not be answered as ele-1.
     */
    public function testAnExpressionThatDiffersFromThePublishedTextIsLeftToTheEngine(): void
    {
        self::assertNull(FHIRPathInvariantFastPaths::passes(FHIRPathInvariantFastPaths::ELE_1 . ' and id.exists()', new Coding(display: 'Weight')));
    }
}
