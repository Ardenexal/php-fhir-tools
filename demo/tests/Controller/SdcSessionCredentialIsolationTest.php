<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\CookieJar;

/**
 * M07's core safety property: session-scoped credentials must never leak between visitors. Two
 * independent `KernelBrowser` instances sharing one booted kernel, each with its own `CookieJar` (the
 * standard Symfony pattern for simulating two concurrent visitors in one test — `WebTestCase::
 * createClient()` itself can only be called once per test and always returns the same `test.client`
 * singleton, so a genuinely separate "visitor" needs its own cookie jar constructed directly) — proves
 * Symfony's session mechanism isolates per-visitor here, not just in theory.
 *
 * Asserts on each client's own `getResponse()->getContent()` directly rather than the `assertSelector*`
 * helpers — those implicitly operate on whichever client was last registered as "current" via
 * `self::getClient()`, which stays pinned to the first (`static::createClient()`-created) client and
 * would silently assert against the wrong visitor's page here.
 */
final class SdcSessionCredentialIsolationTest extends WebTestCase
{
    use PostsCredentialForms;

    private function secondClient(): KernelBrowser
    {
        return new KernelBrowser(self::$kernel, [], null, new CookieJar());
    }

    protected function setUp(): void
    {
        putenv('FHIR_SERVER_URL=https://fhir.example.invalid/r4');
        putenv('FHIR_SERVER_OAUTH_TOKEN_URL=https://idp.example.invalid/token');
        $_ENV['FHIR_SERVER_URL']                 = 'https://fhir.example.invalid/r4';
        $_ENV['FHIR_SERVER_OAUTH_TOKEN_URL']     = 'https://idp.example.invalid/token';
        $_SERVER['FHIR_SERVER_URL']              = 'https://fhir.example.invalid/r4';
        $_SERVER['FHIR_SERVER_OAUTH_TOKEN_URL']  = 'https://idp.example.invalid/token';
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        foreach (['FHIR_SERVER_URL', 'FHIR_SERVER_OAUTH_TOKEN_URL'] as $name) {
            putenv($name);
            unset($_ENV[$name], $_SERVER[$name]);
        }
    }

    public function testOneVisitorsSessionCredentialsAreInvisibleToAnother(): void
    {
        $clientA = static::createClient();
        $clientB = $this->secondClient();

        $this->postCredentialForm($clientA, '/sdc/credentials', [
            'oauthClientId'     => 'alice',
            'oauthClientSecret' => 'alice-test-credential',
        ]);
        $htmlA = (string) $clientA->getResponse()->getContent();
        self::assertStringContainsString('your session credentials', $htmlA);

        $clientB->request('GET', '/sdc');
        $htmlB = (string) $clientB->getResponse()->getContent();
        self::assertStringContainsString('set your credentials below', $htmlB);
        self::assertStringNotContainsString('your session credentials', $htmlB);
        self::assertStringNotContainsString('data-session-credentials-indicator', $htmlB);
    }

    public function testClearingOneVisitorsCredentialsDoesNotAffectAnother(): void
    {
        $clientA = static::createClient();
        $clientB = $this->secondClient();

        $this->postCredentialForm($clientA, '/sdc/credentials', [
            'oauthClientId'     => 'alice',
            'oauthClientSecret' => 'alice-test-credential',
        ]);
        $this->postCredentialForm($clientB, '/sdc/credentials', [
            'oauthClientId'     => 'bob',
            'oauthClientSecret' => 'bob-test-credential',
        ]);

        $this->postCredentialForm($clientA, '/sdc/credentials/clear');
        $htmlA = (string) $clientA->getResponse()->getContent();
        self::assertStringContainsString('set your credentials below', $htmlA);

        $clientB->request('GET', '/sdc');
        $htmlB = (string) $clientB->getResponse()->getContent();
        self::assertStringContainsString('your session credentials', $htmlB);
        self::assertStringContainsString('data-session-credentials-indicator', $htmlB);
    }

    /** A cross-site POST carries the session cookie but not the token, so it must change nothing. */
    public function testCredentialActionsRejectAMissingOrForgedCsrfToken(): void
    {
        $client = static::createClient();
        $this->postCredentialForm($client, '/sdc/credentials', [
            'oauthClientId'     => 'alice',
            'oauthClientSecret' => 'alice-test-credential',
        ]);

        $client->request('POST', '/sdc/credentials', [
            'oauthClientId'     => 'mallory',
            'oauthClientSecret' => 'mallory-test-credential',
        ]);
        self::assertResponseStatusCodeSame(403);

        $client->request('POST', '/sdc/credentials/clear', ['_csrf_token' => 'forged']);
        self::assertResponseStatusCodeSame(403);

        $client->request('GET', '/sdc');
        self::assertStringContainsString('your session credentials', (string) $client->getResponse()->getContent());
    }
}
