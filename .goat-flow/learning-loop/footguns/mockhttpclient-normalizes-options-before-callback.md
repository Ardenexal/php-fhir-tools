---
category: mockhttpclient-normalizes-options-before-callback
last_reviewed: 2026-08-05
---

# Footguns: `MockHttpClient`'s callback observes resolved options, not the raw ones passed to `request()`

## Footgun: asserting on `$options['auth_bearer']` or the raw `headers` shape inside a `MockHttpClient` callback fails — Symfony resolves semantic options into real headers first

**Status:** active | **Created:** 2026-08-05 | **Evidence:** OBSERVED (sdc-questionnaire-playground M06)

Symfony's `HttpClientInterface::request()` options include semantic shortcuts like `auth_bearer` (a
token — resolves to a real `Authorization: Bearer <token>` header) and `auth_basic`, and the `headers`
option itself accepts either an associative array (`['Name' => 'value']`) or a list of `"Name: value"`
strings. When testing a decorator that sets these via a `MockHttpClient(function($method, $url, $options)
{ ... })` callback, the `$options` the callback receives are **already resolved/normalized** by Symfony's
HttpClient stack before the mock "transport" is invoked — `auth_bearer` has already become an
`Authorization` header, and `headers` (whatever shape was passed in) has already been normalized to the
canonical `"Name: value"` list form, with defaults like `Accept: */*` already merged in.

Writing `self::assertSame('abc123', $capturedOptions['auth_bearer'] ?? null)` or
`self::assertSame(['Authorization' => 'value'], $capturedOptions['headers'])` against those captured
options fails — not because the decorator is broken, but because the assertion is checking the wrong
representation. Found while testing `OAuthHttpClient`/`StaticHeaderHttpClient`
(`src/Component/HttpClient/src/OAuth/`): both decorators worked correctly on the first try, but the tests
initially asserted on the pre-resolution option shape.

**Mitigation:** assert on the **resolved** `headers` list form instead — `self::assertContains('Authorization:
Bearer abc123', $capturedOptions['headers'])` (order-independent, since defaults like `Accept: */*` may
also be present). Never assert exact array equality on `$options['headers']` inside a `MockHttpClient`
callback unless you've verified there are no Symfony-added defaults mixed in.
