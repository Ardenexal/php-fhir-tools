---
category: xfhirquery-count-does-not-bound-total-results
last_reviewed: 2026-08-05
---

# Footguns: an x-fhir-query template's `_count` bounds page size, not total results — pagination is followed automatically

## Footgun: `Observation?...&_count=3` inside an `itemPopulationContext` can still populate dozens of repeat rows

**Status:** active | **Created:** 2026-08-05 | **Evidence:** OBSERVED (sdc-questionnaire-playground, post-M07 gallery addition)

`XFhirQueryPopulationDataProvider::resourcesForQuery()` (`src/Component/Sdc/src/`) follows every
`Bundle.link[relation=next]` page automatically, up to `MAX_PAGES = 50` — this is deliberate, documented
library behavior from the `x-fhir-query` plan (ADR-013 Decision 6), not a bug. The consequence for anyone
authoring a demo/test Questionnaire: `_count=N` in the query string only bounds how many results come
back **per page** — it does not cap the *total* number of resources bound to a repeating
`itemPopulationContext` group. A query like `Observation?subject=Patient/123&_count=3&_sort=-date` against
a patient with 90 observations produced **84 repeated group instances** in a demo form, not 3 — the
provider dutifully followed all 28 pages of 3 back-to-back.

Found building a "most recent observation for this patient" gallery sample: the intended UX (3 rows) became
84 rows and a much slower populate action (28 round-trips to the public server).

**Mitigation, by what you actually want:**
- **Want exactly the single most-recent match:** don't use a repeating `itemPopulationContext` group at
  all — use a root/item `variable` extension instead (binds only `$values[0]`), with `_count` set
  *generously high* (e.g. `_count=100`) so the *entire* result set fits on one page and no `next` link is
  ever produced — zero extra pagination round-trips, and sorting (`_sort=-date`) still puts the one you
  want first.
- **Want a genuinely bounded repeating set (e.g. "up to 3 rows"):** there is currently no
  library-level "stop after N total results across pages" option — either accept that pagination will be
  followed to completion (bounded only by `MAX_PAGES=50`), or pick a query specific enough that the
  *server itself* returns a small result set (e.g. filter by a specific `code`, not just `subject`).
