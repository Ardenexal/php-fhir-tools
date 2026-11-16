---
category: requeststack-getsession-throws-outside-http-request
last_reviewed: 2026-08-05
---

# Footguns: `RequestStack::getSession()` throws when a service resolves outside a real HTTP request

## Footgun: a service that calls `RequestStack::getSession()` breaks any `KernelTestCase`-only (non-`WebTestCase`) test that resolves it

**Status:** active | **Created:** 2026-08-05 | **Evidence:** OBSERVED (sdc-questionnaire-playground M07)

`Symfony\Component\HttpFoundation\RequestStack::getSession()` throws `SessionNotFoundException` unless
there is a current request on the stack **and** that request has a session attached
(`$request->hasSession()`). A real HTTP request (via `WebTestCase::createClient()->request(...)`, or a
live browser) always satisfies both. A bare `KernelTestCase::bootKernel()` + `self::getContainer()->
get(SomeService::class)` — with no HTTP request ever made — does **not**: no `Request` is ever pushed
onto the `RequestStack`, so `getCurrentRequest()` is `null` and `getSession()` throws immediately.

This broke `ExternalClientFactoryContainerTest` (a `KernelTestCase`, not `WebTestCase` — it resolves
`FHIRHttpClientInterface` directly to prove the DI wiring, with no request involved) the moment
`ExternalClientFactory::httpClient()` started calling `$this->requestStack->getSession()` to check for
session-scoped credential overrides (M07). The exception surfaced as three failures: two errors
(`SessionNotFoundException` propagating out of a passing-until-then test) and one failure (a test
expecting `\RuntimeException` got `SessionNotFoundException` instead, since that class doesn't extend
`\RuntimeException`).

**Mitigation:** never call `RequestStack::getSession()` directly in a service that might resolve outside
a real request. Check explicitly first:
```php
$request = $this->requestStack->getCurrentRequest();
if ($request === null || !$request->hasSession()) {
    return null; // or whatever "no session available" should mean for this call site
}
$value = $request->getSession()->get($key);
```
This mirrors exactly what `getSession()` checks internally before throwing — the difference is handling
the "no session" case as a normal branch instead of an exception. For most services (config/credential
overrides, feature flags read from session), "no active request/session" and "no value in the session"
should be the same fallback path, not a distinct error.
