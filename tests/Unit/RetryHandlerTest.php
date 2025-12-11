<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit;

use Ardenexal\FHIRTools\RetryHandler;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Ardenexal\FHIRTools\Exception\PackageException;

/**
 * Unit tests for RetryHandler functionality
 *
 * This test class verifies retry logic for network operations
 * and other transient failures using the actual RetryHandler API.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class RetryHandlerTest extends TestCase
{
    private RetryHandler $retryHandler;

    protected function setUp(): void
    {
        $this->retryHandler = new RetryHandler();
    }

    /**
     * Test RetryHandler instantiation
     */
    public function testRetryHandlerInstantiation(): void
    {
        self::assertInstanceOf(RetryHandler::class, $this->retryHandler);
    }

    /**
     * Test successful operation on first attempt
     */
    public function testSuccessfulOperationOnFirstAttempt(): void
    {
        $callCount = 0;
        $operation = function() use (&$callCount) {
            ++$callCount;

            return 'success';
        };

        $result = $this->retryHandler->executeWithRetry($operation);

        self::assertSame('success', $result);
        self::assertSame(1, $callCount, 'Operation should be called only once');
    }

    /**
     * Test successful operation after retries
     */
    public function testSuccessfulOperationAfterRetries(): void
    {
        $callCount = 0;
        $operation = function() use (&$callCount) {
            ++$callCount;
            if ($callCount < 3) {
                throw new \RuntimeException('Temporary failure');
            }

            return 'success';
        };

        $result = $this->retryHandler->executeWithRetry(
            $operation,
            5, // maxAttempts
            10, // baseDelayMs - short delay for testing
            2.0, // backoffMultiplier
            30000, // maxDelayMs
            ['RuntimeException'], // retryableExceptions
        );

        self::assertSame('success', $result);
        self::assertSame(3, $callCount, 'Operation should succeed on third attempt');
    }

    /**
     * Test failure after maximum attempts
     */
    public function testFailureAfterMaximumAttempts(): void
    {
        $callCount = 0;
        $operation = function() use (&$callCount) {
            ++$callCount;
            throw new \RuntimeException('Persistent failure');
        };

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Persistent failure');

        try {
            $this->retryHandler->executeWithRetry(
                $operation,
                3, // maxAttempts
                10, // baseDelayMs
                2.0, // backoffMultiplier
                30000, // maxDelayMs
                ['RuntimeException'], // retryableExceptions
            );
        } finally {
            self::assertSame(3, $callCount, 'Operation should be attempted maximum times');
        }
    }

    /**
     * Test HTTP retry functionality
     */
    public function testHttpRetryFunctionality(): void
    {
        $callCount     = 0;
        $httpOperation = function() use (&$callCount) {
            ++$callCount;
            if ($callCount < 2) {
                // Use an exception type that HTTP retry will handle
                throw new PackageException('Network error');
            }

            return 'http success';
        };

        $result = $this->retryHandler->executeHttpWithRetry($httpOperation, 3);

        self::assertSame('http success', $result);
        self::assertSame(2, $callCount, 'HTTP operation should succeed on second attempt');
    }

    /**
     * Test file retry functionality
     */
    public function testFileRetryFunctionality(): void
    {
        $callCount     = 0;
        $fileOperation = function() use (&$callCount) {
            ++$callCount;
            if ($callCount < 2) {
                throw new \RuntimeException('File system error');
            }

            return 'file success';
        };

        $result = $this->retryHandler->executeFileWithRetry($fileOperation, 3);

        self::assertSame('file success', $result);
        self::assertSame(2, $callCount, 'File operation should succeed on second attempt');
    }

    /**
     * Test exponential backoff timing (basic verification)
     */
    public function testExponentialBackoffTiming(): void
    {
        $callTimes = [];
        $operation = function() use (&$callTimes) {
            $callTimes[] = microtime(true);
            if (count($callTimes) < 3) {
                throw new \RuntimeException('Temporary failure');
            }

            return 'success';
        };

        $this->retryHandler->executeWithRetry(
            $operation,
            5, // maxAttempts
            100, // baseDelayMs
            2.0, // backoffMultiplier
            30000, // maxDelayMs
            ['RuntimeException'], // retryableExceptions
        );

        // Verify we made the expected number of attempts
        self::assertCount(3, $callTimes);

        // Verify there was some delay between attempts
        $firstDelay = ($callTimes[1] - $callTimes[0]) * 1000;
        self::assertGreaterThan(50, $firstDelay, 'Should have some delay between attempts');
    }
}
