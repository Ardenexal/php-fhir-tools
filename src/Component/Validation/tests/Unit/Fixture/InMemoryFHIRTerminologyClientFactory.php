<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientFactoryInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;

/**
 * Test-only factory: maps server URL to a pre-configured InMemoryFHIRTerminologyClient;
 * falls back to $default for unknown URLs.
 */
final class InMemoryFHIRTerminologyClientFactory implements FHIRTerminologyClientFactoryInterface
{
    /** @param array<string, InMemoryFHIRTerminologyClient> $clientsByUrl */
    public function __construct(
        private readonly array $clientsByUrl,
        private readonly InMemoryFHIRTerminologyClient $default,
    ) {
    }

    public function createForServer(string $baseUrl): FHIRTerminologyClientInterface
    {
        return $this->clientsByUrl[$baseUrl] ?? $this->default;
    }
}
