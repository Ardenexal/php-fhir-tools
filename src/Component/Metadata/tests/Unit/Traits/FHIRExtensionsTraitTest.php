<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Traits;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Traits\FHIRExtensionsTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Ardenexal
 */
class FHIRExtensionsTraitTest extends TestCase
{
    // ---------------------------------------------------------------------------
    // Fixtures
    // ---------------------------------------------------------------------------

    /** A simple object that uses the trait with a public $extension array. */
    private function makeHolder(array $extensions = []): object
    {
        return new class ($extensions) {
            use FHIRExtensionsTrait;

            public array $extension;

            public function __construct(array $extensions)
            {
                $this->extension = $extensions;
            }
        };
    }

    /** An extension stub that also implements FHIRExtensionInterface. */
    private function makeTypedExt(string $url): object
    {
        return new class ($url) implements FHIRExtensionInterface {
            public function __construct(private readonly string $url)
            {
            }

            public function getExtensionUrl(): ?string
            {
                return $this->url;
            }
        };
    }

    /** A second, distinct typed extension class for differentiation tests. */
    private function makeOtherExt(string $url = 'http://example.com/other'): object
    {
        return new class ($url) implements FHIRExtensionInterface {
            public function __construct(private readonly string $url)
            {
            }

            public function getExtensionUrl(): ?string
            {
                return $this->url;
            }
        };
    }

    // ---------------------------------------------------------------------------
    // findExtension
    // ---------------------------------------------------------------------------

    public function testFindExtensionReturnsFirstMatchingInstance(): void
    {
        $ext1   = $this->makeTypedExt('http://example.com/one');
        $ext2   = $this->makeTypedExt('http://example.com/two');
        $holder = $this->makeHolder([$ext1, $ext2]);

        self::assertSame($ext1, $holder->findExtension($ext1::class));
    }

    public function testFindExtensionReturnsNullWhenNoMatch(): void
    {
        $holder = $this->makeHolder([$this->makeOtherExt()]);

        self::assertNull($holder->findExtension($this->makeTypedExt('x')::class));
    }

    public function testFindExtensionReturnsNullOnEmptyArray(): void
    {
        $holder = $this->makeHolder();

        self::assertNull($holder->findExtension($this->makeTypedExt('x')::class));
    }

    // ---------------------------------------------------------------------------
    // findExtensions
    // ---------------------------------------------------------------------------

    public function testFindExtensionsReturnsAllMatchingInstances(): void
    {
        $ext1   = $this->makeTypedExt('http://example.com/a');
        $ext2   = $this->makeTypedExt('http://example.com/b');
        $other  = $this->makeOtherExt();
        $holder = $this->makeHolder([$ext1, $other, $ext2]);

        self::assertSame([$ext1, $ext2], $holder->findExtensions($ext1::class));
    }

    public function testFindExtensionsReturnsEmptyArrayWhenNoMatch(): void
    {
        $holder = $this->makeHolder([$this->makeOtherExt()]);

        self::assertSame([], $holder->findExtensions($this->makeTypedExt('x')::class));
    }

    // ---------------------------------------------------------------------------
    // hasExtension
    // ---------------------------------------------------------------------------

    public function testHasExtensionReturnsTrueWhenMatchFound(): void
    {
        $ext    = $this->makeTypedExt('http://example.com/x');
        $holder = $this->makeHolder([$ext]);

        self::assertTrue($holder->hasExtension($ext::class));
    }

    public function testHasExtensionReturnsFalseWhenNoMatch(): void
    {
        $holder = $this->makeHolder([$this->makeOtherExt()]);

        self::assertFalse($holder->hasExtension($this->makeTypedExt('x')::class));
    }

    public function testHasExtensionReturnsFalseOnEmptyArray(): void
    {
        $holder = $this->makeHolder();

        self::assertFalse($holder->hasExtension($this->makeTypedExt('x')::class));
    }

    // ---------------------------------------------------------------------------
    // findExtensionByUrl
    // ---------------------------------------------------------------------------

    public function testFindExtensionByUrlReturnsFirstMatchingExtension(): void
    {
        $url    = 'http://example.com/ext';
        $ext1   = $this->makeTypedExt($url);
        $ext2   = $this->makeTypedExt($url);
        $holder = $this->makeHolder([$ext1, $ext2]);

        self::assertSame($ext1, $holder->findExtensionByUrl($url));
    }

    public function testFindExtensionByUrlReturnsNullWhenUrlNotFound(): void
    {
        $holder = $this->makeHolder([$this->makeTypedExt('http://example.com/other')]);

        self::assertNull($holder->findExtensionByUrl('http://example.com/missing'));
    }

    public function testFindExtensionByUrlReturnsNullOnEmptyArray(): void
    {
        $holder = $this->makeHolder();

        self::assertNull($holder->findExtensionByUrl('http://example.com/any'));
    }

    // ---------------------------------------------------------------------------
    // findExtensionsByUrl
    // ---------------------------------------------------------------------------

    public function testFindExtensionsByUrlReturnsAllWithMatchingUrl(): void
    {
        $url    = 'http://example.com/multi';
        $ext1   = $this->makeTypedExt($url);
        $ext2   = $this->makeTypedExt('http://example.com/other');
        $ext3   = $this->makeTypedExt($url);
        $holder = $this->makeHolder([$ext1, $ext2, $ext3]);

        self::assertSame([$ext1, $ext3], $holder->findExtensionsByUrl($url));
    }

    public function testFindExtensionsByUrlReturnsEmptyArrayWhenNoMatch(): void
    {
        $holder = $this->makeHolder([$this->makeTypedExt('http://example.com/x')]);

        self::assertSame([], $holder->findExtensionsByUrl('http://example.com/missing'));
    }
}
