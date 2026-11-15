<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use PHPUnit\Framework\TestCase;

/**
 * Pins what the per-class instantiation caches must not change.
 *
 * Deserialization builds every element through instantiateWithDefaults() or
 * instantiateWithConstructorDefaults(), and both now reuse per-class work: a cloned template instance and a
 * cached argument list. If either leaked state between instances, two documents deserialized in the
 * same process would share data, so independence is asserted directly.
 */
final class FHIRModelAccessorInstantiationTest extends TestCase
{
    public function testInstantiateWithDefaultsReturnsIndependentInstances(): void
    {
        $accessor = new FHIRModelAccessor();

        $first = $accessor->instantiateWithDefaults(InstantiationFixture::class);
        self::assertInstanceOf(InstantiationFixture::class, $first);
        $first->items[] = 'written to the first instance';
        $first->label   = 'changed';

        $second = $accessor->instantiateWithDefaults(InstantiationFixture::class);
        self::assertInstanceOf(InstantiationFixture::class, $second);

        self::assertNotSame($first, $second);
        self::assertSame([], $second->items, 'a non-nullable array starts empty in every instance');
        self::assertSame('default label', $second->label, 'the constructor default is applied to every instance');
        self::assertFalse((new \ReflectionProperty(InstantiationFixture::class, 'required'))->isInitialized($second), 'a slot with no default stays uninitialized');
    }

    public function testInstantiateWithDefaultsNeverClonesAClassThatDefinesClone(): void
    {
        $accessor = new FHIRModelAccessor();

        CloneCountingFixture::$clones = 0;
        $first                        = $accessor->instantiateWithDefaults(CloneCountingFixture::class);
        $second                       = $accessor->instantiateWithDefaults(CloneCountingFixture::class);

        self::assertNotSame($first, $second);
        self::assertSame(0, CloneCountingFixture::$clones, '__clone() must never run on the instantiation path');
        self::assertInstanceOf(CloneCountingFixture::class, $second);
        self::assertSame([], $second->items);
    }

    public function testInstantiateWithConstructorDefaultsGivesEachInstanceItsOwnObjectDefault(): void
    {
        $accessor = new FHIRModelAccessor();

        $first  = $accessor->instantiateWithConstructorDefaults(ObjectDefaultFixture::class);
        $second = $accessor->instantiateWithConstructorDefaults(ObjectDefaultFixture::class);

        self::assertInstanceOf(ObjectDefaultFixture::class, $first);
        self::assertInstanceOf(ObjectDefaultFixture::class, $second);
        self::assertNotSame($first->holder, $second->holder, 'a `new` default must not be shared between instances');
        self::assertSame('default label', $second->label);
        self::assertNull($second->noDefault, 'a parameter without a default receives null');
    }
}

/** Fixture with a constructor default, a non-nullable array, and a slot nothing initializes. */
final class InstantiationFixture
{
    /** @var list<string> */
    public array $items;

    public string $required;

    public function __construct(public string $label = 'default label')
    {
    }
}

/** Fixture whose __clone() would be observable, so the accessor must build it fresh each time. */
final class CloneCountingFixture
{
    public static int $clones = 0;

    /** @var list<string> */
    public array $items;

    public function __clone()
    {
        ++self::$clones;
    }
}

/** Fixture whose constructor default builds an object, which must stay per-instance. */
final class ObjectDefaultFixture
{
    /**
     * @param \ArrayObject<int, mixed> $holder
     */
    public function __construct(
        public ?string $noDefault,
        public \ArrayObject $holder = new \ArrayObject(),
        public string $label = 'default label',
    ) {
    }
}
