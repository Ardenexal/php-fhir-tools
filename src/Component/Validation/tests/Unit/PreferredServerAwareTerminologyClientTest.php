<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\CodingValidationResult;
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\PreferredServerAwareTerminologyClient;
use PHPUnit\Framework\TestCase;

final class PreferredServerAwareTerminologyClientTest extends TestCase
{
    private const string VS = 'http://example.com/vs';

    public function testEmptyPreferredListDelegatesToFallback(): void
    {
        $fallback = new InMemoryFHIRTerminologyClient(
            [self::VS => ['|only-in-fallback' => true]],
            false,
        );
        $client   = new PreferredServerAwareTerminologyClient([], $fallback);

        self::assertTrue($client->validateCode(self::VS, 'only-in-fallback'));
        self::assertFalse($client->validateCode(self::VS, 'not-in-vs'));
    }

    public function testFirstPreferredReturnsTrueShortCircuits(): void
    {
        $preferred = new InMemoryFHIRTerminologyClient(
            [self::VS => ['|code' => true, 'http://sys|code' => true]],
            false,
        );
        $fallback  = new InMemoryFHIRTerminologyClient([], false);
        $client    = new PreferredServerAwareTerminologyClient([$preferred], $fallback);

        self::assertTrue($client->validateCode(self::VS, 'code'));
        // fallback would have returned false — short-circuit means we trust preferred
        self::assertTrue($client->validateCoding(self::VS, 'http://sys', 'code'));
    }

    public function testFirstPreferredReturnsFalseIsFinal(): void
    {
        $preferred = new InMemoryFHIRTerminologyClient([], false); // denies everything
        // fallback is permissive — must NOT be consulted when preferred returns false
        $fallback  = new NullFHIRTerminologyClient();
        $client    = new PreferredServerAwareTerminologyClient([$preferred], $fallback);

        self::assertFalse($client->validateCode(self::VS, 'any-code'));
        self::assertFalse($client->validateCoding(self::VS, 'sys', 'any-code'));
    }

    public function testFirstPreferredThrowsTriesSecond(): void
    {
        $throwing  = $this->createThrowingClient();
        $second    = new InMemoryFHIRTerminologyClient([self::VS => ['|code' => true]], false);
        $fallback  = new InMemoryFHIRTerminologyClient([], false);
        $client    = new PreferredServerAwareTerminologyClient([$throwing, $second], $fallback);

        self::assertTrue($client->validateCode(self::VS, 'code'));
        self::assertFalse($client->validateCode(self::VS, 'not-in-second'));
    }

    public function testAllPreferredThrowFallsBackToFallback(): void
    {
        $a        = $this->createThrowingClient();
        $b        = $this->createThrowingClient();
        $fallback = new InMemoryFHIRTerminologyClient([self::VS => ['|code' => true]], false);
        $client   = new PreferredServerAwareTerminologyClient([$a, $b], $fallback);

        self::assertTrue($client->validateCode(self::VS, 'code'));
        self::assertFalse($client->validateCode(self::VS, 'not-in-fallback'));
    }

    public function testValidateCodingThrowOnFirstTriesSecond(): void
    {
        $throwing = $this->createThrowingClient();
        $second   = new InMemoryFHIRTerminologyClient([self::VS => ['sys|code' => true]], false);
        $client   = new PreferredServerAwareTerminologyClient([$throwing, $second], new InMemoryFHIRTerminologyClient([], false));

        self::assertTrue($client->validateCoding(self::VS, 'sys', 'code'));
    }

    private function createThrowingClient(): FHIRTerminologyClientInterface
    {
        return new class () implements FHIRTerminologyClientInterface {
            public function validateCode(string $valueSetUrl, mixed $value): bool
            {
                throw new \RuntimeException('server unavailable');
            }

            public function validateCoding(string $valueSetUrl, string $system, string $code): bool
            {
                throw new \RuntimeException('server unavailable');
            }

            public function validateCodingWithDisplay(string $valueSetUrl, string $system, string $code, string $display): CodingValidationResult
            {
                throw new \RuntimeException('server unavailable');
            }
        };
    }
}
