<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\CachingFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\CodingValidationResult;
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class CachingFHIRTerminologyClientTest extends TestCase
{
    private const string VS_URL = 'http://hl7.org/fhir/ValueSet/observation-status';

    private const string SYSTEM = 'http://snomed.info/sct';

    private const string CODE = '123456789';

    // -------------------------------------------------------------------------
    // validateCode — in-process cache
    // -------------------------------------------------------------------------

    public function testValidateCodeCallsInnerOnFirstCall(): void
    {
        $inner = $this->createInnerMock();
        $inner->expects($this->once())
            ->method('validateCode')
            ->with(self::VS_URL, 'final')
            ->willReturn(true);

        $client = new CachingFHIRTerminologyClient($inner);

        self::assertTrue($client->validateCode(self::VS_URL, 'final'));
    }

    public function testValidateCodeDoesNotCallInnerOnSecondCallWithSameArgs(): void
    {
        $inner = $this->createInnerMock();
        $inner->expects($this->once())
            ->method('validateCode')
            ->willReturn(false);

        $client = new CachingFHIRTerminologyClient($inner);
        $client->validateCode(self::VS_URL, 'final');

        self::assertFalse($client->validateCode(self::VS_URL, 'final'));
    }

    // -------------------------------------------------------------------------
    // validateCoding — in-process cache
    // -------------------------------------------------------------------------

    public function testValidateCodingCallsInnerOnFirstCall(): void
    {
        $inner = $this->createInnerMock();
        $inner->expects($this->once())
            ->method('validateCoding')
            ->with(self::VS_URL, self::SYSTEM, self::CODE)
            ->willReturn(true);

        $client = new CachingFHIRTerminologyClient($inner);

        self::assertTrue($client->validateCoding(self::VS_URL, self::SYSTEM, self::CODE));
    }

    public function testValidateCodingDoesNotCallInnerOnSecondCallWithSameArgs(): void
    {
        $inner = $this->createInnerMock();
        $inner->expects($this->once())
            ->method('validateCoding')
            ->willReturn(true);

        $client = new CachingFHIRTerminologyClient($inner);
        $client->validateCoding(self::VS_URL, self::SYSTEM, self::CODE);

        self::assertTrue($client->validateCoding(self::VS_URL, self::SYSTEM, self::CODE));
    }

    // -------------------------------------------------------------------------
    // PSR-6 pool — hit
    // -------------------------------------------------------------------------

    public function testValidateCodeDoesNotCallInnerOnPsr6Hit(): void
    {
        $inner = $this->createInnerMock();
        $inner->expects($this->never())->method('validateCode');

        [$pool] = $this->createPoolWithHit(true);

        $client = new CachingFHIRTerminologyClient($inner, $pool);

        self::assertTrue($client->validateCode(self::VS_URL, 'final'));
    }

    public function testValidateCodingDoesNotCallInnerOnPsr6Hit(): void
    {
        $inner = $this->createInnerMock();
        $inner->expects($this->never())->method('validateCoding');

        [$pool] = $this->createPoolWithHit(false);

        $client = new CachingFHIRTerminologyClient($inner, $pool);

        self::assertFalse($client->validateCoding(self::VS_URL, self::SYSTEM, self::CODE));
    }

    // -------------------------------------------------------------------------
    // PSR-6 pool — miss
    // -------------------------------------------------------------------------

    public function testValidateCodeCallsInnerAndSavesToPoolOnPsr6Miss(): void
    {
        $inner = $this->createInnerMock();
        $inner->expects($this->once())
            ->method('validateCode')
            ->willReturn(true);

        [$pool, $item] = $this->createPoolWithMiss();
        $item->expects($this->once())->method('set')->with(true);
        $pool->expects($this->once())->method('save');

        $client = new CachingFHIRTerminologyClient($inner, $pool);

        self::assertTrue($client->validateCode(self::VS_URL, 'final'));
    }

    public function testValidateCodingCallsInnerAndSavesToPoolOnPsr6Miss(): void
    {
        $inner = $this->createInnerMock();
        $inner->expects($this->once())
            ->method('validateCoding')
            ->willReturn(false);

        [$pool, $item] = $this->createPoolWithMiss();
        $item->expects($this->once())->method('set')->with(false);
        $pool->expects($this->once())->method('save');

        $client = new CachingFHIRTerminologyClient($inner, $pool);

        self::assertFalse($client->validateCoding(self::VS_URL, self::SYSTEM, self::CODE));
    }

    // -------------------------------------------------------------------------
    // PSR-6 pool hit bypassed by in-process array on second call
    // -------------------------------------------------------------------------

    public function testInProcessArrayPreventsPsr6GetItemOnSubsequentCall(): void
    {
        $inner = $this->createStub(FHIRTerminologyClientInterface::class);
        $inner->method('validateCode')->willReturn(true);

        $item = $this->createStub(CacheItemInterface::class);
        $item->method('isHit')->willReturn(false);

        // getItem called once (first call — pool miss), NOT twice
        $pool = $this->createMock(CacheItemPoolInterface::class);
        $pool->expects($this->once())->method('getItem')->willReturn($item);
        $pool->expects($this->once())->method('save');

        $client = new CachingFHIRTerminologyClient($inner, $pool);
        $client->validateCode(self::VS_URL, 'final');
        $client->validateCode(self::VS_URL, 'final');
    }

    // -------------------------------------------------------------------------
    // TTL = 0 → expiresAfter(null)
    // -------------------------------------------------------------------------

    public function testTtlZeroPassesNullToExpiresAfter(): void
    {
        $inner = $this->createStub(FHIRTerminologyClientInterface::class);
        $inner->method('validateCode')->willReturn(true);

        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(false);
        $item->expects($this->once())->method('expiresAfter')->with(null);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        $client = new CachingFHIRTerminologyClient($inner, $pool, 0);
        $client->validateCode(self::VS_URL, 'final');
    }

    public function testTtlZeroPassesNullToExpiresAfterForValidateCodingWithDisplay(): void
    {
        $inner = $this->createStub(FHIRTerminologyClientInterface::class);
        $inner->method('validateCodingWithDisplay')->willReturn(new CodingValidationResult(true, null));

        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(false);
        $item->expects($this->once())->method('expiresAfter')->with(null);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        $client = new CachingFHIRTerminologyClient($inner, $pool, 0);
        $client->validateCodingWithDisplay(self::VS_URL, self::SYSTEM, self::CODE, 'Heart rate');
    }

    // -------------------------------------------------------------------------
    // No pool — in-process array only
    // -------------------------------------------------------------------------

    public function testNoCachePoolFallsBackToInProcessArrayOnly(): void
    {
        $inner = $this->createInnerMock();
        $inner->expects($this->once())
            ->method('validateCode')
            ->willReturn(false);

        $client = new CachingFHIRTerminologyClient($inner);
        self::assertFalse($client->validateCode(self::VS_URL, 'x'));
        self::assertFalse($client->validateCode(self::VS_URL, 'x'));
    }

    // -------------------------------------------------------------------------
    // validateCodingWithDisplay — in-process cache
    // -------------------------------------------------------------------------

    public function testValidateCodingWithDisplayCallsInnerOnFirstCall(): void
    {
        $expected = new CodingValidationResult(true, null);
        $inner    = $this->createInnerMock();
        $inner->expects($this->once())
            ->method('validateCodingWithDisplay')
            ->with(self::VS_URL, self::SYSTEM, self::CODE, 'Heart rate')
            ->willReturn($expected);

        $client = new CachingFHIRTerminologyClient($inner);
        $result = $client->validateCodingWithDisplay(self::VS_URL, self::SYSTEM, self::CODE, 'Heart rate');

        self::assertSame($expected, $result);
    }

    public function testValidateCodingWithDisplayDoesNotCallInnerOnSecondCallWithSameArgs(): void
    {
        $expected = new CodingValidationResult(true, 'Heart Rate');
        $inner    = $this->createInnerMock();
        $inner->expects($this->once())
            ->method('validateCodingWithDisplay')
            ->willReturn($expected);

        $client = new CachingFHIRTerminologyClient($inner);
        $client->validateCodingWithDisplay(self::VS_URL, self::SYSTEM, self::CODE, 'heart rate');

        $result = $client->validateCodingWithDisplay(self::VS_URL, self::SYSTEM, self::CODE, 'heart rate');
        self::assertSame($expected, $result);
    }

    public function testValidateCodingWithDisplayCallsInnerWhenDisplayDiffers(): void
    {
        $inner = $this->createInnerMock();
        $inner->expects($this->exactly(2))
            ->method('validateCodingWithDisplay')
            ->willReturn(new CodingValidationResult(true, null));

        $client = new CachingFHIRTerminologyClient($inner);
        $client->validateCodingWithDisplay(self::VS_URL, self::SYSTEM, self::CODE, 'display-a');
        $client->validateCodingWithDisplay(self::VS_URL, self::SYSTEM, self::CODE, 'display-b');
    }

    public function testValidateCodingWithDisplayStoresAndRetrievesFromPsr6Pool(): void
    {
        $expected = new CodingValidationResult(false, null);
        $inner    = $this->createInnerMock();
        $inner->expects($this->once())
            ->method('validateCodingWithDisplay')
            ->willReturn($expected);

        [$pool, $item] = $this->createPoolWithMiss();
        $item->expects($this->once())->method('set')->with($expected);
        $pool->expects($this->once())->method('save');

        $client = new CachingFHIRTerminologyClient($inner, $pool);
        $result = $client->validateCodingWithDisplay(self::VS_URL, self::SYSTEM, self::CODE, 'Heart rate');

        self::assertSame($expected, $result);
    }

    public function testValidateCodingWithDisplayReturnsPsr6HitWithoutCallingInner(): void
    {
        $expected = new CodingValidationResult(true, 'Heart rate');
        $inner    = $this->createInnerMock();
        $inner->expects($this->never())->method('validateCodingWithDisplay');

        $item = $this->createStub(CacheItemInterface::class);
        $item->method('isHit')->willReturn(true);
        $item->method('get')->willReturn($expected);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        $client = new CachingFHIRTerminologyClient($inner, $pool);
        $result = $client->validateCodingWithDisplay(self::VS_URL, self::SYSTEM, self::CODE, 'Heart rate');

        self::assertSame($expected, $result);
    }

    // -------------------------------------------------------------------------
    // validateCoding and validateCode have separate key namespaces
    // -------------------------------------------------------------------------

    public function testValidateCodingAndValidateCodeAreCachedSeparately(): void
    {
        $inner = $this->createInnerMock();
        $inner->expects($this->once())
            ->method('validateCode')
            ->with(self::VS_URL, self::CODE)
            ->willReturn(true);
        $inner->expects($this->once())
            ->method('validateCoding')
            ->with(self::VS_URL, self::SYSTEM, self::CODE)
            ->willReturn(false);

        $client = new CachingFHIRTerminologyClient($inner);
        self::assertTrue($client->validateCode(self::VS_URL, self::CODE));
        self::assertFalse($client->validateCoding(self::VS_URL, self::SYSTEM, self::CODE));
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /** @return MockObject&FHIRTerminologyClientInterface */
    private function createInnerMock(): MockObject
    {
        return $this->createMock(FHIRTerminologyClientInterface::class);
    }

    /**
     * @return array{CacheItemPoolInterface, CacheItemInterface}
     */
    private function createPoolWithHit(bool $cachedValue): array
    {
        $item = $this->createStub(CacheItemInterface::class);
        $item->method('isHit')->willReturn(true);
        $item->method('get')->willReturn($cachedValue);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        return [$pool, $item];
    }

    /**
     * @return array{MockObject&CacheItemPoolInterface, MockObject&CacheItemInterface}
     */
    private function createPoolWithMiss(): array
    {
        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(false);

        $pool = $this->createMock(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        return [$pool, $item];
    }
}
