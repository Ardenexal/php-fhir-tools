---
category: webtestcase-assertselector-pins-to-current-client
last_reviewed: 2026-08-05
---

# Footguns: `WebTestCase::assertSelector*`/`assertResponse*` helpers always check the "current" client, not whichever client made the last request

## Footgun: a two-client test silently asserts against the wrong visitor's page

**Status:** active | **Created:** 2026-08-05 | **Evidence:** OBSERVED (sdc-questionnaire-playground M07)

`Symfony\Bundle\FrameworkBundle\Test\WebTestAssertionsTrait`'s helpers (`assertSelectorTextContains`,
`assertResponseIsSuccessful`, `assertSelectorExists`, etc.) don't take a client argument — they all read
from `self::getClient()` with no argument, which returns whichever `KernelBrowser` was last registered as
"current" via `self::getClient($client)`. `WebTestCase::createClient()` registers its client as current
internally. A **second** client — constructed directly (e.g. `new KernelBrowser(self::$kernel, [], null,
new CookieJar())`, the standard pattern for simulating a second concurrent visitor sharing one booted
kernel) — is never registered this way, so calling `assertSelectorTextContains(...)` after making a
request with that second client silently asserts against the **first** client's last response instead.

This surfaced writing `SdcSessionCredentialIsolationTest` (proving two visitors' session-scoped
credentials don't leak into each other): the test looked correct and even ran, but the failure messages
showed content from `clientA`'s page while the assertion was meant to check `clientB`'s — the two
`request()` calls silently drifted onto asserting the same (first) client's state every time.

**Mitigation:** for any test using more than one client instance, assert on each client's own
`$client->getResponse()->getContent()` directly (`assertStringContainsString(...)`, manual DOM parsing,
etc.) instead of the `assertSelector*`/`assertResponse*` trait helpers — those are only safe when exactly
one client exists in the test. If you must use the trait helpers with multiple clients, call
`self::getClient($theClientYouWantToAssertOn)` immediately before each assertion to explicitly re-pin
which one is "current".
