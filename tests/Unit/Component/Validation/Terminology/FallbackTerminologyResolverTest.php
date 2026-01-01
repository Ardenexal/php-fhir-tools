<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Validation\Terminology;

use Ardenexal\FHIRTools\Component\Validation\Terminology\FallbackTerminologyResolver;
use Ardenexal\FHIRTools\Component\Validation\Terminology\TerminologyResolverInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ardenexal\FHIRTools\Component\Validation\Terminology\FallbackTerminologyResolver
 */
final class FallbackTerminologyResolverTest extends TestCase
{
    private TerminologyResolverInterface $remoteResolver;
    private TerminologyResolverInterface $packageResolver;

    protected function setUp(): void
    {
        $this->remoteResolver = $this->createMock(TerminologyResolverInterface::class);
        $this->packageResolver = $this->createMock(TerminologyResolverInterface::class);
    }

    public function testUsesRemoteResolverFirst(): void
    {
        $resolver = new FallbackTerminologyResolver($this->remoteResolver, $this->packageResolver);

        $this->remoteResolver
            ->expects(self::once())
            ->method('validateCode')
            ->with('http://example.org/ValueSet/test', 'http://system', 'code')
            ->willReturn(true);

        $this->packageResolver->expects(self::never())->method('validateCode');

        $result = $resolver->validateCode('http://example.org/ValueSet/test', 'http://system', 'code');

        self::assertTrue($result);
    }

    public function testFallsBackToPackageOnRemoteFailure(): void
    {
        $resolver = new FallbackTerminologyResolver($this->remoteResolver, $this->packageResolver);

        $this->remoteResolver
            ->method('validateCode')
            ->willThrowException(new \RuntimeException('Remote server unavailable'));

        $this->packageResolver
            ->expects(self::once())
            ->method('canResolve')
            ->with('http://example.org/ValueSet/test')
            ->willReturn(true);

        $this->packageResolver
            ->expects(self::once())
            ->method('validateCode')
            ->with('http://example.org/ValueSet/test', 'http://system', 'code')
            ->willReturn(false);

        $result = $resolver->validateCode('http://example.org/ValueSet/test', 'http://system', 'code');

        self::assertFalse($result);
    }

    public function testThrowsWhenBothResolversFail(): void
    {
        $resolver = new FallbackTerminologyResolver($this->remoteResolver, $this->packageResolver);

        $this->remoteResolver
            ->method('validateCode')
            ->willThrowException(new \RuntimeException('Remote server unavailable'));

        $this->packageResolver
            ->method('canResolve')
            ->willReturn(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot resolve ValueSet');

        $resolver->validateCode('http://example.org/ValueSet/test', 'http://system', 'code');
    }

    public function testExpandUsesRemoteFirst(): void
    {
        $resolver = new FallbackTerminologyResolver($this->remoteResolver, $this->packageResolver);

        $expectedCodes = [
            ['system' => 'http://system', 'code' => 'code1'],
            ['system' => 'http://system', 'code' => 'code2'],
        ];

        $this->remoteResolver
            ->expects(self::once())
            ->method('expand')
            ->with('http://example.org/ValueSet/test')
            ->willReturn($expectedCodes);

        $this->packageResolver->expects(self::never())->method('expand');

        $result = $resolver->expand('http://example.org/ValueSet/test');

        self::assertSame($expectedCodes, $result);
    }

    public function testExpandFallsBackToPackage(): void
    {
        $resolver = new FallbackTerminologyResolver($this->remoteResolver, $this->packageResolver);

        $expectedCodes = [['system' => 'http://system', 'code' => 'code1']];

        $this->remoteResolver
            ->method('expand')
            ->willThrowException(new \RuntimeException('Circuit breaker is OPEN'));

        $this->packageResolver
            ->expects(self::once())
            ->method('canResolve')
            ->willReturn(true);

        $this->packageResolver
            ->expects(self::once())
            ->method('expand')
            ->willReturn($expectedCodes);

        $result = $resolver->expand('http://example.org/ValueSet/test');

        self::assertSame($expectedCodes, $result);
    }

    public function testCanResolveChecksEitherResolver(): void
    {
        $resolver = new FallbackTerminologyResolver($this->remoteResolver, $this->packageResolver);

        $this->remoteResolver
            ->method('canResolve')
            ->willReturn(false);

        $this->packageResolver
            ->method('canResolve')
            ->willReturn(true);

        self::assertTrue($resolver->canResolve('http://example.org/ValueSet/test'));
    }

    public function testCanResolveReturnsFalseWhenNeitherCanResolve(): void
    {
        $resolver = new FallbackTerminologyResolver($this->remoteResolver, $this->packageResolver);

        $this->remoteResolver
            ->method('canResolve')
            ->willReturn(false);

        $this->packageResolver
            ->method('canResolve')
            ->willReturn(false);

        self::assertFalse($resolver->canResolve('http://example.org/ValueSet/test'));
    }
}
