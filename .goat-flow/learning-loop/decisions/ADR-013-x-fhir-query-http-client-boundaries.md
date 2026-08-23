---
date: 2026-08-04
status: accepted
---

# ADR-013: `x-fhir-query` / `HttpClient` Boundaries — Opt-In Live Fetch, Component Placement, SSRF/PHI Posture

**Status:** accepted
**Date:** 2026-08-04
**Milestone:** x-fhir-query M01–M04

## Context

ADR-011 (Decision 1) fixed SDC `$populate` as offline-first: the toolkit shipped no FHIR search client, so
`x-fhir-query` / `dataEndpoint` / `sourceQueries` were unsupported — the populate service skipped them with
a warning. The `x-fhir-query` plan built the missing piece: a pure offline template resolver
(`XFhirQueryResolver`, M01), a shared FHIR HTTP transport (`HttpClient` component + `FHIRHttpClient` /
`NullFHIRHttpClient`, M02), and wired both into `$populate` as an **opt-in** live path (M03). M04 hardened
the result (SSRF/timeout/empty-substitution) and records the boundaries below. It mirrors ADR-010/ADR-011's
posture: offline-first by default, live behaviour additive and caller-configured, PHI out of scope.

## Decision 1 — Population stays offline-first by default; live `x-fhir-query` is opt-in (supersedes ADR-011 Decision 1)

ADR-011 Decision 1 said no live fetching happens inside the library. That remains true **unless the caller
explicitly opts in**.

**Decision:** `PopulateContext::$queryProvider` (a `QueryPopulationDataProviderInterface`) defaults to
`null`. Every `x-fhir-query`-handling branch in `FHIRQuestionnairePopulateService` is gated behind
`$queryProvider !== null`; with no provider, execution falls through unchanged to the pre-existing
FHIRPath-only code path (the same `isFhirPath()` guard that has always deferred non-FHIRPath languages with
a warning). This was proven, not just designed: `FHIRQuestionnaireXFhirQueryPopulateTest::
testOfflineDefaultSkipsXFhirQueryWithWarningWhenNoProvider` and the full offline `sdc-populate-spec`
conformance corpus (15 fixtures) pass unchanged with the opt-in path present. `initialExpression` carrying
`application/x-fhir-query` is explicitly **out of scope** for this opt-in (a fetched resource feeding the
answer path directly is nonsensical without a projection step) — only `variable` and
`itemPopulationContext` context expressions support live resolution.

## Decision 2 — A new `HttpClient` component owns FHIR transport; the terminology client relocates onto it (pre-1.0, no compat shim)

The one existing real network client, `HttpFHIRTerminologyClient`, was stranded in `Validation` and
duplicated transport plumbing (base-URL join, FHIR headers, status→graceful-null) that a search client also
needs.

**Decision:** a new component, `ardenexal/fhir-http-client`
(`Ardenexal\FHIRTools\Component\HttpClient\`), owns the shared transport core (`FHIRHttpClient`), the
null-object default (`NullFHIRHttpClient`), the offline resolver (`XFhirQuery\XFhirQueryResolver`), and the
relocated terminology client + its caching decorator + factory. The repo is pre-1.0 (tags at `0.4.0`), so
the relocation is a straight move with **no backwards-compatibility shim** — this was the explicit scope
decision recorded in the plan's `ISSUE.md` (2026-07-16). `Sdc` depends on the new component
(`Sdc → HttpClient`, alongside the existing `Sdc → Validation` / `Sdc → Metadata`); `FHIRHttpClientInterface`
lives in `Metadata` (the shared-interface home per CLAUDE.md), consistent with ADR-011 Decision 4's
placement rule.

## Decision 3 — SSRF guardrail: percent-encoding + fixed-authority join, not an allowlist

The `x-fhir-query` template's literal text is Questionnaire-authored (semi-trusted, like code), but its
substituted values come from FHIRPath evaluation of caller-supplied launch context — data the library does
not control the shape of. A naive base-URL-plus-search-string join could let a crafted value redirect a
request to a different host.

**Decision:** no allowlist or URL-parsing guard was added; the existing mechanism already closes the gap
structurally and this was verified rather than assumed:

- `XFhirQueryResolver` percent-encodes every evaluated leaf value (`rawurlencode`) before substitution — an
  evaluated value can never contribute a literal `://` or unescaped `/` to the resolved search string. Only
  the template's own literal text (not evaluated data) can contain those characters raw.
- `FHIRHttpClient::request()` builds the outbound URL as
  `rtrim($baseUrl, '/') . '/' . ltrim($path, '/')` — the caller-configured `baseUrl` is always the request's
  scheme+authority; `$path` (the resolved search) can only ever land in the path/query of that same
  authority, even when it is shaped like an absolute or protocol-relative URL.

Proven by `XFhirQueryResolverTest::testEvaluatedValueContainingSchemeIsPercentEncoded` /
`testEvaluatedValueContainingProtocolRelativeHostIsPercentEncoded` and
`FHIRHttpClientTest::testSchemeLikeSearchStringCannotRedirectToAnotherHost` /
`testProtocolRelativeSearchStringCannotRedirectToAnotherHost`.

## Decision 4 — PHI / access-control posture extends to live-fetched data (extends ADR-011 Decision 5)

ADR-011 Decision 5 made PHI authorization a caller responsibility for offline-supplied data. Live fetching
introduces a second data path (the FHIR server's response) with the same question.

**Decision:** the same posture extends unchanged — `FHIRHttpClient` / `XFhirQueryPopulationDataProvider`
fetch whatever the configured server returns for the resolved search and apply **no** permission filtering.
Constraining what a query can see (query design, launch-context scoping, server-side authorization) is the
caller's responsibility. Documented in `HttpClient/README.md`; no code-level guardrail was built because the
library has no basis to implement an authorization model (mirrors the M04 kill criteria: a half-measure was
rejected in favor of documenting the boundary).

## Decision 5 — Searchset entries are filtered to `search.mode = 'match'`

A `$populate` `itemPopulationContext`/`variable` x-fhir-query fetch returns a full FHIR searchset Bundle,
which may carry `_include`d resources (`search.mode = 'include'`) and `OperationOutcome` entries
(`search.mode = 'outcome'`) alongside the actual matches.

**Decision:** `XFhirQueryPopulationDataProvider` filters to `entry.where(search.mode = 'match').resource` —
only matched resources become population context results; included/outcome entries are never bound as
spurious `%<name>` values. Proven by
`XFhirQueryPopulationDataProviderTest::testFiltersOutNonMatchSearchModeEntries`.

## Decision 6 — Multi-page result following: same-origin `next` links, bounded page count, no partial-failure signal

M02/M03 fetched a single search page only, deferred explicitly (`backlog.md` "Next" tier). Population
queries that legitimately return more than one page need every page followed.

The SSRF guardrail in Decision 3 covers *resolved search strings* — they can never carry a foreign host
because the resolver percent-encodes evaluated values and `request()` always joins onto the fixed
`baseUrl`. A `Bundle.link.url` (`relation = 'next'`) is different: it is server-supplied and **absolute**,
so that guarantee does not automatically extend to it. Following it naively would reopen exactly the gap
Decision 3 closed, just via a different input (server response instead of resolved query).

**Decision:**

- `FHIRHttpClientInterface` gained `followLink(string $url, string $fhirVersion): ?object`, implemented in
  `FHIRHttpClient` by reducing the URL to a path and re-dispatching through the same fixed-authority
  `request()` join used everywhere else — never fetching the absolute URL directly. The URL is accepted
  only when its origin (scheme+host+port, default ports normalized) matches `baseUrl`'s, **and** its path
  is under `baseUrl`'s own path prefix (a path outside that prefix can't be re-expressed through the
  `request()` join without duplicating or losing the prefix — rejected rather than risk fetching the wrong
  URL). `NullFHIRHttpClient` implements it as a no-op, matching its other methods.
- `XFhirQueryPopulationDataProvider` extracts the next-page URL via
  `link.where(relation = 'next').url` — a plain FHIRPath expression, not manual reflection — which reads
  uniformly across FHIR versions despite `Bundle.link.relation` being a plain string/`StringPrimitive` in
  R4/R4B and a code-typed enum wrapper (`LinkRelationTypesType`) in R5; FHIRPath's existing primitive
  normalization erases that difference for free.
- **Page-count bound:** a same-origin check alone doesn't bound iteration count — a misbehaving or
  malicious same-origin server could still serve an endless chain of `next` links. `XFhirQueryPopulationDataProvider`
  caps at 50 pages (`MAX_PAGES`), a distinct, orthogonal guard from the origin check.
- **Partial-failure posture:** a later page's fetch failing (transport/HTTP error) is treated identically to
  reaching a legitimate end of pagination (no `next` link) or a rejected cross-origin link — pagination
  simply stops, and whatever pages were fetched successfully are returned. This mirrors the
  graceful-degradation posture already used throughout the transport stack (every other failure mode in
  this stack degrades to `null`/omission with no distinct signal); introducing a new partial-failure
  channel here would be inconsistent with that posture for a comparatively rare case. Only the **first**
  page's failure remains distinguished, unchanged from M03: `resourcesForQuery()` still returns `null`
  (not `[]`) when the initial `search()` call fails, so `FHIRQuestionnairePopulateService`'s existing
  warning-issue path is unaffected.

Proven by `FHIRHttpClientTest::testFollowLinkFetchesSameOriginUrl` /
`testFollowLinkRejectsCrossOriginHost` / `...Scheme` / `...Port` / `testFollowLinkRejectsSameOriginPathOutsideBasePrefix`
/ `testFollowLinkRejectsMalformedUrl`, and
`XFhirQueryPopulationDataProviderTest::testFollowsNextLinkAcrossMultiplePages` /
`testStopsPaginationWhenNextPageFetchFails` / `testPageLimitBoundsIterationCount`.

## Consequences

- `$populate` is safe to run without a server (offline default unchanged, verified against the fixture
  corpus); live fetching is strictly additive and off unless a `QueryPopulationDataProviderInterface` is
  wired.
- No public interface in `Validation` moved (per ADR-011 Decision 4); `HttpFHIRTerminologyClient` itself
  relocated with no shim, acceptable only under the pre-1.0 freedom to relocate freely.
- SSRF is closed by construction (encoding + fixed-authority join), not by a runtime allowlist — there is no
  configurable "trusted hosts" list to maintain or get out of sync.
- PHI/authorization remains entirely a caller concern across both the offline and live paths, documented in
  both `Sdc/README.md` and `HttpClient/README.md`.
- Still deferred (see `x-fhir-query/backlog.md`): `sourceQueries`, operation-style x-fhir-query, and result
  caching/ETag. Multi-page result following (Decision 6, M06) and adopting the shared transport in FHIRPath
  `resolve()`/`memberOf()` (M05) are no longer deferred — both shipped on this plan.
