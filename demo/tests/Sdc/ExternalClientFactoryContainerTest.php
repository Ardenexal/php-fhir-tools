<?php

declare(strict_types=1);

namespace App\Tests\Sdc;

use Ardenexal\FHIRTools\Component\HttpClient\FHIRHttpClient;
use Ardenexal\FHIRTools\Component\HttpClient\NullFHIRHttpClient;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Mid-implementation proof (M04, required before any controller/template work): a plain Symfony alias
 * cannot switch classes at runtime based on an env var's value — only at container-compile time. This
 * proves the factory-based wiring in demo/config/services.yaml actually does switch, by resolving
 * FHIRHttpClientInterface from two independently-booted containers with different FHIR_SERVER_URL values
 * in the same process (`Container::getEnv()` caches per-instance, so a fresh kernel boot re-reads the
 * env var rather than reusing a stale resolution).
 */
final class ExternalClientFactoryContainerTest extends KernelTestCase
{
    /** @var list<string> */
    private const array AUTH_ENV_VARS = [
        'FHIR_SERVER_URL',
        'FHIR_SERVER_OAUTH_TOKEN_URL',
        'FHIR_SERVER_OAUTH_CLIENT_ID',
        'FHIR_SERVER_OAUTH_CLIENT_SECRET',
        'FHIR_SERVER_AUTH_HEADER_NAME',
        'FHIR_SERVER_AUTH_HEADER_VALUE',
    ];

    protected function tearDown(): void
    {
        parent::tearDown();
        foreach (self::AUTH_ENV_VARS as $name) {
            putenv($name);
            unset($_ENV[$name], $_SERVER[$name]);
        }
    }

    /** @param array<string, string> $vars */
    private function setEnvVars(array $vars): void
    {
        foreach ($vars as $name => $value) {
            putenv($name . '=' . $value);
            $_ENV[$name]    = $value;
            $_SERVER[$name] = $value;
        }
    }

    public function testEmptyEnvVarResolvesToNullClient(): void
    {
        putenv('FHIR_SERVER_URL');
        unset($_ENV['FHIR_SERVER_URL'], $_SERVER['FHIR_SERVER_URL']);

        self::bootKernel();
        $client = self::getContainer()->get(FHIRHttpClientInterface::class);

        self::assertInstanceOf(NullFHIRHttpClient::class, $client);
    }

    public function testConfiguredEnvVarResolvesToRealClient(): void
    {
        putenv('FHIR_SERVER_URL=https://example.invalid/fhir');
        $_ENV['FHIR_SERVER_URL']    = 'https://example.invalid/fhir';
        $_SERVER['FHIR_SERVER_URL'] = 'https://example.invalid/fhir';

        self::bootKernel();
        $client = self::getContainer()->get(FHIRHttpClientInterface::class);

        self::assertInstanceOf(FHIRHttpClient::class, $client);
    }

    /**
     * M06 mid-implementation proof: the five new auth env vars actually thread through
     * `services.yaml`'s factory `arguments:` list, in the order `ExternalClientFactory::httpClient()`
     * expects them, all the way from a real container resolution.
     */
    public function testAllOAuthEnvVarsSetResolvesWithoutErrorThroughTheRealContainer(): void
    {
        $this->setEnvVars([
            'FHIR_SERVER_URL'                  => 'https://example.invalid/fhir',
            'FHIR_SERVER_OAUTH_TOKEN_URL'      => 'https://idp.example.invalid/token',
            'FHIR_SERVER_OAUTH_CLIENT_ID'      => 'demo-client',
            'FHIR_SERVER_OAUTH_CLIENT_SECRET'  => 'demo-secret',
        ]);

        self::bootKernel();
        $client = self::getContainer()->get(FHIRHttpClientInterface::class);

        self::assertInstanceOf(FHIRHttpClient::class, $client);
    }

    /**
     * M07 changed the token-URL-alone shape (client id/secret both unset) from an error to a valid state
     * — the operator enables OAuth via the token URL, and visitors supply their own session credentials.
     * This test now exercises what's still genuinely invalid: a client id set with no matching secret.
     */
    public function testMismatchedOAuthClientIdAndSecretThrowsAtContainerResolutionTime(): void
    {
        $this->setEnvVars([
            'FHIR_SERVER_URL'             => 'https://example.invalid/fhir',
            'FHIR_SERVER_OAUTH_TOKEN_URL' => 'https://idp.example.invalid/token',
            'FHIR_SERVER_OAUTH_CLIENT_ID' => 'demo-client',
            // client secret deliberately left unset — a mismatched pair, still invalid under M07.
        ]);

        self::bootKernel();

        $this->expectException(\RuntimeException::class);
        self::getContainer()->get(FHIRHttpClientInterface::class);
    }

    /**
     * M07 mid-implementation proof: the token-URL-alone shape resolves without error through the real
     * container — no client id/secret env var needed, since a visitor's session is expected to supply
     * them (see `ExternalClientFactoryAuthTest` for the decoration-level and session-override proofs).
     */
    public function testTokenUrlAloneResolvesWithoutErrorThroughTheRealContainer(): void
    {
        $this->setEnvVars([
            'FHIR_SERVER_URL'             => 'https://example.invalid/fhir',
            'FHIR_SERVER_OAUTH_TOKEN_URL' => 'https://idp.example.invalid/token',
        ]);

        self::bootKernel();
        $client = self::getContainer()->get(FHIRHttpClientInterface::class);

        self::assertInstanceOf(FHIRHttpClient::class, $client);
    }
}
