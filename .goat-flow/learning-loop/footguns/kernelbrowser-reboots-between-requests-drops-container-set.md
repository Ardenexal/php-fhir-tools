---
category: kernelbrowser-reboots-between-requests-drops-container-set
last_reviewed: 2026-08-05
---

# Footguns: `KernelBrowser` reboots the kernel before every request, silently discarding a `self::getContainer()->set(...)` override made between two requests in the same test

## Footgun: a swapped-in test double works for the first request in a test, then silently reverts to the real service for the second

**Status:** active | **Created:** 2026-08-05 | **Evidence:** OBSERVED (sdc-questionnaire-playground, post-M07 fetch-by-id feature)

`Symfony\Bundle\FrameworkBundle\KernelBrowser` reboots the kernel — and therefore rebuilds the entire DI
container — before **every** `request()` call, unless `disableReboot()` has been called. A
`self::getContainer()->set($id, $testDouble)` override only patches the container instance that exists
*at the moment `set()` runs*. The next `$client->request(...)` call boots a **fresh** container from
scratch (re-running all factory services, `%env(...)%` resolution, etc.), which has no memory of the
override — silently reverting to whatever the real service definition resolves to.

This surfaced testing the fetch-launch-context-by-ID feature: a test did
`self::getContainer()->set(FHIRHttpClientInterface::class, $spy)`, then a `/sdc/render` request (to get
a fresh `questionnaireJson` value), then a `/sdc/populate` request expecting the *same* `$spy` to receive
the fetch call. The first request worked; by the second request, the container had rebooted and
`FHIRHttpClientInterface` had reverted to the real (env-configured, here `NullFHIRHttpClient`) service —
the populate action failed with "could not fetch" even though the spy was still sitting in a local PHP
variable, correctly configured, just no longer reachable from the container the second request used.
Confirmed via `self::getContainer()->get(FHIRHttpClientInterface::class) === $spy` returning `true`
immediately after `set()`, but the *next* request's controller receiving a different instance.

**Mitigation**, in order of preference:
- **Avoid the second request entirely** when possible — e.g. build the value you needed from the first
  request (like a `questionnaireJson` string) directly from a fixture file (`file_get_contents(...)`)
  instead of an HTTP round trip, so the whole test needs only one request after `set()`.
- If a genuine multi-request sequence is required with the SAME overridden service throughout, call
  `$client->disableReboot()` right after `static::createClient()` (or before the sequence) — this keeps
  the same container (and therefore the same `set()` override) alive across all subsequent requests in
  that test.
- Do not assume a passing single-request test using this pattern generalizes to a multi-request one —
  add a second assertion (e.g. `$spy->lastRequestPath`) whenever extending a swapped-service test to
  more than one request, specifically to catch this class of silent reversion.
