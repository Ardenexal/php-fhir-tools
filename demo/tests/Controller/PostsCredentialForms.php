<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * The credential actions require the session-bound CSRF token the `/sdc` page renders, so tests post to
 * them the way a browser would: load the page first, then send the token it rendered.
 */
trait PostsCredentialForms
{
    /**
     * @param array<string, string> $parameters
     */
    private function postCredentialForm(KernelBrowser $client, string $uri, array $parameters = []): void
    {
        $crawler = $client->request('GET', '/sdc');
        $token   = $crawler->filter('input[name="_csrf_token"]')->first()->attr('value');
        self::assertNotNull($token, 'The /sdc page should render a credentials CSRF token.');

        $client->request('POST', $uri, $parameters + ['_csrf_token' => $token]);
    }
}
