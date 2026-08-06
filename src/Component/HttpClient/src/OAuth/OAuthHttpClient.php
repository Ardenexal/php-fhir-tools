<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\OAuth;

use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * Decorates an `HttpClientInterface`, attaching a fresh (or cached) OAuth client-credentials bearer
 * token to every request via Symfony's native `auth_bearer` option — no manual header-string handling.
 *
 * `FHIRHttpClient` requires no changes to benefit from this: it is simply constructed with an
 * `OAuthHttpClient` in place of the raw `HttpClientInterface` it already expects, so authentication is
 * entirely transparent to the transport layer above it.
 *
 * A token-fetch failure ({@see OAuthTokenException}, always a fixed, secret-free message per its own
 * docblock) is rethrown as a Symfony {@see TransportException} so it flows through `FHIRHttpClient`'s
 * existing `catch (HttpClientExceptionInterface)` graceful-null handling unchanged — no new failure mode
 * for callers to handle.
 */
final class OAuthHttpClient implements HttpClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $inner,
        private readonly OAuthClientCredentialsTokenProvider $tokenProvider,
    ) {
    }

    /**
     * @param array<string, mixed> $options
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        try {
            $options['auth_bearer'] = $this->tokenProvider->getAccessToken();
        } catch (OAuthTokenException $e) {
            throw new TransportException($e->getMessage(), previous: $e);
        }

        return $this->inner->request($method, $url, $options);
    }

    public function stream(ResponseInterface|iterable $responses, ?float $timeout = null): ResponseStreamInterface
    {
        return $this->inner->stream($responses, $timeout);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function withOptions(array $options): static
    {
        return new self($this->inner->withOptions($options), $this->tokenProvider);
    }
}
