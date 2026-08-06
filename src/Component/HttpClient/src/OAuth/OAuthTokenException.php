<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\OAuth;

/**
 * Thrown when an OAuth client-credentials access token cannot be obtained.
 *
 * The message is always a fixed, generic string constructed by {@see OAuthClientCredentialsTokenProvider}
 * itself — never derived from the request body (which contains the client secret), the token endpoint's
 * raw response, or a wrapped transport exception's own message. This is deliberate: callers such as
 * `SdcController` surface `\Throwable::getMessage()` directly in a browser-visible error panel, so this
 * exception's message must be safe to display verbatim under any failure mode.
 */
final class OAuthTokenException extends \RuntimeException
{
}
