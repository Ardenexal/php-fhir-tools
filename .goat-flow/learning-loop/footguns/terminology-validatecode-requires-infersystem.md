---
category: terminology-validatecode-requires-infersystem
last_reviewed: 2026-08-05
---

# Footguns: `HttpFHIRTerminologyClient::validateCode()` needed `inferSystem` to work against a real server

## Footgun: a bare code+url `$validate-code` call is spec-ambiguous without `inferSystem` — mocked tests can't catch this, only a live server exposes it

**Status:** resolved | **Created:** 2026-08-05 | **Evidence:** OBSERVED (sdc-questionnaire-playground M04)

`HttpFHIRTerminologyClient::validateCode(string $valueSetUrl, mixed $value): bool` sent only `url` and
`code` to `ValueSet/$validate-code`. Every unit test for this method used a `MockHttpClient` returning a
canned `Parameters` response, so they all passed regardless of what params were actually sent — the tests
proved response *parsing* worked, never that the *request* itself was answerable by a real server.

Running it live against `https://tx.fhir.org/r4` (a spec-compliant reference terminology server) for
`ValueSet/marital-status` with `code=M` — a genuinely valid code — returned:

```json
{"resourceType":"OperationOutcome","issue":[{"severity":"error","code":"invalid",
  "details":{"text":"Unable to find code to validate (looked for coding | codeableConcept | code+system | code+inferSystem in parameters"}}]}
```

A bare `code` with no `system` is ambiguous per the `$validate-code` operation's own definition unless the
server is explicitly told `inferSystem=true` (infer the system when the value set's compose makes it
unambiguous) or a `system` is supplied directly. Without either, `validateCode()` silently reported **every**
code — valid or not — as invalid against any strict server. `validateCoding()` (system+code) was unaffected
— it already had no ambiguity.

**Mitigation:** `validateCode()` now sends `inferSystem: true` alongside `url`/`code` (both GET query-string
and POST `Parameters` body paths). This is additive — no signature change, no behavior change for any
server that already worked. Verified directly: `curl` against `tx.fhir.org` with `inferSystem=true` returns
`result: true` for `code=male` against `administrative-gender`; without it, the same request returns the
"unable to find code" `OperationOutcome` regardless of code validity.

**General lesson:** when a "returns true when valid" method's *mocked* test suite is fully green, that only
proves response-parsing correctness — it says nothing about whether the *request shape* is one a real
server can actually answer meaningfully. For any wrapper around a FHIR "check this against a live spec
operation" call, treat a real reachable server as part of the testing gate, not an optional nice-to-have —
mirrors the M04 milestone's own Mid-implementation Proof / live-manual-gate structure that caught this.
