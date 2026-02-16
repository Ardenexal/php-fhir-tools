<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Package;

use Ardenexal\FHIRTools\Exception\FHIRToolsException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Handles retry logic with exponential backoff for network operations
 *
 * This class provides robust retry mechanisms for operations that may fail
 * temporarily, such as network requests or file operations. It implements:
 *
 * - Exponential backoff with configurable multiplier
 * - Jitter to prevent thundering herd problems
 * - Configurable maximum delay and attempt limits
 * - Selective retry based on exception types
 * - Comprehensive logging of retry attempts and failures
 * - Specialized methods for HTTP and file operations
 *
 * The retry handler is designed to make network operations more resilient
 * by automatically handling transient failures while providing detailed
 * logging for debugging purposes.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class RetryHandler
{
    /**
     * Logger instance for recording retry attempts and failures
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Construct a new RetryHandler with optional logger
     *
     * @param LoggerInterface|null $logger Logger for recording retry attempts (optional)
     */
    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * Execute a callable with retry logic and exponential backoff
     *
     * This method executes the provided operation with automatic retry logic.
     * If the operation fails with a retryable exception, it will be retried
     * up to the maximum number of attempts with exponential backoff delays.
     *
     * The delay between attempts increases exponentially with each retry,
     * and jitter is added to prevent thundering herd problems when multiple
     * processes are retrying simultaneously.
     *
     * @template T
     *
     * @param callable(): T $operation           The operation to execute with retry logic
     * @param int           $maxAttempts         Maximum number of attempts (default: 3)
     * @param int           $baseDelayMs         Base delay in milliseconds (default: 1000)
     * @param float         $backoffMultiplier   Multiplier for exponential backoff (default: 2.0)
     * @param int           $maxDelayMs          Maximum delay in milliseconds (default: 30000)
     * @param array<string> $retryableExceptions Array of exception class names that should trigger retry
     *
     * @return T The result of the successful operation
     *
     * @throws FHIRToolsException When all retry attempts are exhausted
     */
    public function executeWithRetry(
        callable $operation,
        int $maxAttempts = 3,
        int $baseDelayMs = 1000,
        float $backoffMultiplier = 2.0,
        int $maxDelayMs = 30000,
        array $retryableExceptions = []
    ): mixed {
        $attempt       = 1;
        $lastException = null;

        // Default retryable exceptions for network operations
        if (empty($retryableExceptions)) {
            $retryableExceptions = [
                'Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface',
                'Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface',
                'Ardenexal\FHIRTools\Exception\PackageException',
            ];
        }

        while ($attempt <= $maxAttempts) {
            try {
                $this->logger->debug("Executing operation, attempt {$attempt}/{$maxAttempts}");

                return $operation();
            } catch (\Throwable $exception) {
                $lastException = $exception;

                $this->logger->warning(
                    "Operation failed on attempt {$attempt}/{$maxAttempts}: {$exception->getMessage()}",
                    [
                        'attempt'           => $attempt,
                        'max_attempts'      => $maxAttempts,
                        'exception_class'   => get_class($exception),
                        'exception_message' => $exception->getMessage(),
                    ],
                );

                // Check if this exception type is retryable
                if (!$this->isRetryableException($exception, $retryableExceptions)) {
                    $this->logger->error(
                        "Non-retryable exception encountered: {$exception->getMessage()}",
                        ['exception_class' => get_class($exception)],
                    );
                    throw $exception;
                }

                // Don't delay after the last attempt
                if ($attempt < $maxAttempts) {
                    $delayMs = $this->calculateDelay($attempt, $baseDelayMs, $backoffMultiplier, $maxDelayMs);

                    $this->logger->info(
                        "Retrying in {$delayMs}ms (attempt {$attempt}/{$maxAttempts})",
                        ['delay_ms' => $delayMs, 'attempt' => $attempt],
                    );

                    usleep($delayMs * 1000); // Convert to microseconds
                }

                ++$attempt;
            }
        }

        // All attempts failed
        if ($lastException !== null) {
            $this->logger->error(
                "All {$maxAttempts} attempts failed. Last error: {$lastException->getMessage()}",
                [
                    'max_attempts'           => $maxAttempts,
                    'last_exception_class'   => get_class($lastException),
                    'last_exception_message' => $lastException->getMessage(),
                ],
            );

            throw $lastException;
        }

        throw new \RuntimeException("Operation failed after {$maxAttempts} attempts with no recorded exception");
    }

    /**
     * Calculate delay with exponential backoff and jitter
     *
     * @param int   $attempt
     * @param int   $baseDelayMs
     * @param float $backoffMultiplier
     * @param int   $maxDelayMs
     *
     * @return int
     */
    private function calculateDelay(int $attempt, int $baseDelayMs, float $backoffMultiplier, int $maxDelayMs): int
    {
        // Calculate exponential backoff
        $delay = (int) ($baseDelayMs * pow($backoffMultiplier, $attempt - 1));

        // Apply maximum delay limit
        $delay = min($delay, $maxDelayMs);

        // Add jitter (±25% of the delay) to avoid thundering herd
        $jitter = (int) ($delay * 0.25 * (mt_rand() / mt_getrandmax() * 2 - 1));
        $delay += $jitter;

        // Ensure delay is not negative
        return max($delay, 0);
    }

    /**
     * Check if an exception is retryable
     *
     * @param \Throwable    $exception
     * @param array<string> $retryableExceptions
     *
     * @return bool
     */
    private function isRetryableException(\Throwable $exception, array $retryableExceptions): bool
    {
        foreach ($retryableExceptions as $retryableClass) {
            if ($exception instanceof $retryableClass) {
                return true;
            }
        }

        return false;
    }

    /**
     * Execute with retry for HTTP operations specifically
     *
     * @template T
     *
     * @param callable(): T $httpOperation
     * @param int           $maxAttempts
     *
     * @return T
     *
     * @throws FHIRToolsException
     */
    public function executeHttpWithRetry(callable $httpOperation, int $maxAttempts = 3): mixed
    {
        return $this->executeWithRetry(
            $httpOperation,
            $maxAttempts,
            1000, // 1 second base delay
            2.0,  // Double delay each time
            10000, // Max 10 seconds
            [
                'Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface',
                'Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException',
            ],
        );
    }

    /**
     * Execute with retry for file operations specifically
     *
     * @template T
     *
     * @param callable(): T $fileOperation
     * @param int           $maxAttempts
     *
     * @return T
     *
     * @throws FHIRToolsException
     */
    public function executeFileWithRetry(callable $fileOperation, int $maxAttempts = 2): mixed
    {
        return $this->executeWithRetry(
            $fileOperation,
            $maxAttempts,
            500,  // 500ms base delay
            1.5,  // Smaller multiplier for file ops
            2000, // Max 2 seconds
            [
                'Symfony\Component\Filesystem\Exception\IOException',
                'RuntimeException', // For file system errors
            ],
        );
    }
}
