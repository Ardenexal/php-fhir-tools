<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\OAuth;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Transport-level wrapper for an {@see OAuthTokenException}, thrown by {@see OAuthHttpClient::request()}.
 *
 * Implementing the contracts' `TransportExceptionInterface` lets a token-fetch failure flow through any
 * existing `catch (HttpClientExceptionInterface)` handling (such as `FHIRHttpClient`'s graceful-null path)
 * without this package depending on `symfony/http-client` at runtime just for its concrete
 * `TransportException`. The message is copied from the wrapped `OAuthTokenException`, so it is equally
 * secret-free.
 */
final class OAuthTransportException extends \RuntimeException implements TransportExceptionInterface
{
}
