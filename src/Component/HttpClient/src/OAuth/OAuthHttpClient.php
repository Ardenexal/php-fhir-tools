<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\OAuth;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
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
 * docblock) is rethrown as an {@see OAuthTransportException}, a contracts `TransportExceptionInterface`,
 * so it flows through `FHIRHttpClient`'s existing `catch (HttpClientExceptionInterface)` graceful-null
 * handling unchanged. No new failure mode for callers to handle.
 *
 * When the server answers HTTP 401, the token is dropped from the provider's caches and the request is
 * sent once more with a new token. This covers a token the authorisation server revoked, or one that
 * expired early because of clock skew. A second 401 is returned to the caller as is. To see the status,
 * `request()` waits for the response headers before returning, so responses from this client are not
 * fully lazy. A request whose body cannot be sent twice (a stream, generator or closure, or a form
 * array holding a stream) is not resent: its token is still dropped, so the next request gets a new
 * one, and its 401 is returned as is. That includes a body given as a default through this client's
 * `withOptions()`. A body default set on the inner client before it was wrapped cannot be seen here,
 * so set body defaults through this client instead.
 */
final class OAuthHttpClient implements HttpClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $inner,
        private readonly OAuthClientCredentialsTokenProvider $tokenProvider,
    ) {
    }

    /** False once a body default that cannot be sent twice has been set through {@see self::withOptions()}. */
    private bool $defaultBodyReplayable = true;

    /**
     * @param array<string, mixed> $options
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $token    = $this->accessToken();
        $response = $this->inner->request($method, $url, [...$options, 'auth_bearer' => $token]);

        try {
            $status = $response->getStatusCode();
        } catch (TransportExceptionInterface) {
            // The caller sees the same exception when it reads the response, exactly as without the retry.
            return $response;
        }

        if ($status !== 401) {
            return $response;
        }

        $this->tokenProvider->invalidate($token);

        $replayable = \array_key_exists('body', $options) ? self::isReplayable($options) : $this->defaultBodyReplayable;
        if (!$replayable) {
            return $response;
        }

        $response->cancel();

        return $this->inner->request($method, $url, [...$options, 'auth_bearer' => $this->accessToken()]);
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
        $client = new self($this->inner->withOptions($options), $this->tokenProvider);

        $client->defaultBodyReplayable = \array_key_exists('body', $options) ? self::isReplayable($options) : $this->defaultBodyReplayable;

        return $client;
    }

    /**
     * @throws OAuthTransportException
     */
    private function accessToken(): string
    {
        try {
            return $this->tokenProvider->getAccessToken();
        } catch (OAuthTokenException $e) {
            throw new OAuthTransportException($e->getMessage(), previous: $e);
        }
    }

    /**
     * A string body, a form array of scalar values, or the `json` option can be sent again as is. A
     * resource, generator or closure body is consumed by the first attempt, and so is a form array
     * holding one, because Symfony sends that as a multipart upload read from the stream.
     *
     * @param array<string, mixed> $options
     */
    private static function isReplayable(array $options): bool
    {
        $body = $options['body'] ?? null;

        return $body === null || \is_string($body) || (\is_array($body) && self::holdsOnlyScalars($body));
    }

    /**
     * @param array<mixed> $values
     */
    private static function holdsOnlyScalars(array $values): bool
    {
        foreach ($values as $value) {
            if (\is_array($value) ? !self::holdsOnlyScalars($value) : !($value === null || \is_scalar($value))) {
                return false;
            }
        }

        return true;
    }
}
