---
category: terminology-validate-code-display
last_reviewed: 2026-08-31
---

# Footguns: reading `$validate-code` display answers

## Footgun: the `display` out-parameter is the server's preferred label, not a verdict on the one you sent

**Status:** active | **Created:** 2026-08-31 | **Evidence:** OBSERVED (code + corpus data)

`ValueSet/$validate-code` returns a `display` out-parameter described as a valid display for the
concept. It is the server's own preferred label, offered so a caller can show something sensible. It is
**not** an assertion that the display you supplied was wrong.

`HttpFHIRTerminologyClient::validateCodingWithDisplay()` used to infer a mismatch from a plain string
comparison against it, so a server answering `result: true` while preferring a different label produced
a "wrong display" finding on data that was fine. A difference of casing alone was enough. The shipped
test `testValidateCodingWithDisplayReturnsCorrectDisplayWhenResponseIncludesDisplayParam` asserted
exactly that shape, which is why it never read as a bug.

The scale is not marginal. Across the vendored reference outcomes, **every** `Wrong Display Name`
finding is language-tagged and **46 of 71** offer several valid displays for one code. A single-string
comparison cannot be right about any of them.

**Prevention:** report a wrong display only when the server **rejected** the concept details, and
separate the two reasons a rejection can happen by asking once more without the display. If the code
validates on its own, the display was the problem and the server's label is the correction; if it still
fails, it is a membership failure and the display is not the story. The second request is only paid on a
rejection.

**Watch for the same shape elsewhere:** any code that treats "the server returned something different
from what I sent" as an error. The question a terminology server answers is `result`, and the other
out-parameters are context.

**Evidence:** `HttpFHIRTerminologyClient::validateCodingWithDisplay()` (search: `Ask again without the
display`); `HttpFHIRTerminologyClientTest` (search:
`testAcceptedConceptIsNotAWrongDisplayEvenWhenTheServerPrefersAnotherLabel`);
`.goat-flow/plans/terminology-coded-validation/M01-display-text.md` (search: `Resolution, 2026-08-31`).

## Footgun: a display is language-dependent, and nothing in this repository can decide it

**Status:** active | **Created:** 2026-08-31 | **Evidence:** OBSERVED (M08 measurement)

A display correct in one declared language is wrong in another, and the reference validator reports the
full set of acceptable labels with their language tags. Nothing offline can reproduce that: generated
enums carry the display as a **docblock comment**, not as data, so no display text is in scope at
runtime for any code system, licensed or not.

Two consequences worth keeping straight. Display validation belongs to a terminology server and is
delegated to one, never reimplemented here; and `displayLanguage`, the `$validate-code` parameter that
would let a caller pin the language, is not part of
`FHIRTerminologyClientInterface::validateCodingWithDisplay()`. Adding it is a published-contract change
and a human decision, not a milestone task.

**Evidence:** `.goat-flow/plans/validation-corpus-parity/M08-terminology-display.md`
(search: `Correction, 2026-08-31`), where the family is declared for the corpus for this reason.
