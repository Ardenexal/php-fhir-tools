<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\OAuth;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * Decorates an `HttpClientInterface`, attaching one fixed header (name + value) to every request
 * verbatim — no token exchange, no expiry, no caching. Covers a hand-obtained `Authorization: Bearer
 * <token>` or an arbitrary header like `X-API-Key`, for servers that don't need (or don't support) a
 * full OAuth token exchange.
 *
 * `FHIRHttpClient` requires no changes to benefit from this, for the same reason as
 * {@see OAuthHttpClient}: it is simply constructed with this decorator in place of the raw
 * `HttpClientInterface` it already expects.
 */
final class StaticHeaderHttpClient implements HttpClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $inner,
        private readonly string $headerName,
        private readonly string $headerValue,
    ) {
    }

    /**
     * @param array<string, mixed> $options
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $headers = $options['headers'] ?? [];

        if (\is_iterable($headers)) {
            $headersArray = \is_array($headers) ? $headers : iterator_to_array($headers);
        } else {
            $headersArray = [];
        }

        if (array_is_list($headersArray)) {
            $headersArray[] = $this->headerName . ': ' . $this->headerValue;
        } else {
            $headersArray[$this->headerName] = $this->headerValue;
        }

        $options['headers'] = $headersArray;

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
        return new self($this->inner->withOptions($options), $this->headerName, $this->headerValue);
    }
}
