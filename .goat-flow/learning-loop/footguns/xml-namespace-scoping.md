---
category: xml-namespace-scoping
last_reviewed: 2026-09-09
---

## Footgun: a bulk "declare the namespace on every child" walk silently misses two child shapes

**Status:** active | **Created:** 2026-09-09 | **Evidence:** ACTUAL_MEASURED
**Decision changed:** When ending a namespace scope by walking a normalized element's children,
enumerate the shapes a child can actually take before trusting the walk. Two of them are not keyed
arrays, and neither announces itself.
**Trigger phase:** ACT
**hallucination-risk:** high
**Symptoms:** Standard CDA content under an AU or sdtc extension element keeps the extension
namespace, for some instances and not others. A populated `<name>` is declared correctly while an
empty `<name/>` from the same profile is not, so whether the bug appears depends on the instance
data. Address parts inside an extension-namespaced `addr` are always wrong. The document stays
well-formed and `local-name()` matches either way, so only `namespaceURI` separates right from wrong.
**Why it happens:** `FHIRComplexTypeXmlNormalizer::normalizeForXML` ends the scope by walking `$data`
and writing `@xmlns` into each child (search: `declareMissingElementNamespace`). Its comment reasons
that "every one of them holds an array", which is true and still insufficient:
- An **empty** child normalizes to `[]`, and `array_is_list([])` is `true` — an empty array is
  indistinguishable from an empty list. It takes the list branch, is walked for members, has none,
  and is returned exactly as it arrived. The write lands nowhere.
- A **transparent `xml-choice-group`** (`AD`, `EN` and their AU profiles) is a `DOMDocumentFragment`
  under the `#` key. The walk skips `#` deliberately, because that key holds the element's own text
  or fragment content rather than a child array — correct for arrays, and it leaves the fragment's
  real child elements unreachable. No later pass sees them either: they are DOM nodes, so no
  normalizer runs over them.
**Evidence:** `src/Component/Serialization/src/Normalizer/Xml/FHIRComplexTypeXmlNormalizer.php`
(search: `An empty array is one childless element`) and (search: `The per-child declaration walk
below cannot reach these`). Both measured against the shipped fix for issue #116, which resolved the
keyed-array case and left these two: the reported reproduction still failed on one element, and
`AuEntity.addr` members were still in the extension namespace. Pinned by
`src/Component/Serialization/tests/Unit/CdaExtensionNamespaceScopeTest.php` (search:
`testAnEmptyCdaTypedChildOfAnExtensionElementStaysInTheCdaNamespace`) and (search:
`testChoiceGroupMembersUnderAnExtensionElementStayInTheCdaNamespace`).
**Prevention:** Raw DOM built outside the normalizers is the structural hazard, not just these two
sites — anything under `#` has to be handled where it is built, because nothing downstream can
correct it. `buildNarrativeFragment` (search: `Build a CDA narrative block`) has the same shape and
is latent only because no `FhirProperty` carrying an `xmlNamespace` is typed at a `Section`-bearing
class; re-derive that with a scan of those property sites rather than assuming it holds, since
generated models move when package pins move. When a test populates the thing under test, add the
empty case too: a namespace fix verified only against populated content leaves half the branch dark.

## Footgun: libxml rewrites namespace declarations, so two plausible verifications of them prove nothing

**Status:** active | **Created:** 2026-09-09 | **Evidence:** ACTUAL_MEASURED
**Decision changed:** Do not reach for `createElementNS()` on a node destined for an imported
fragment, and do not conclude from a green namespace test that the condition guarding a declaration
is correct.
**Trigger phase:** VERIFY
**hallucination-risk:** high
**Symptoms:** Two surprises with one origin. (1) Setting a real DOM namespace on a fragment member
emits `<default:streetAddressLine xmlns="urn:hl7-org:v3">` — the right namespace, on a document
nothing published looks like. (2) A test asserting "an element already in its namespace declares
nothing" passes even when the guard is removed and the declaration made unconditional.
**Why it happens:** When a `DOMDocumentFragment` built with `createElementNS()` is imported into an
element already carrying a different default namespace, libxml reconciles the clash by inventing a
prefix instead of emitting a default re-declaration. Separately, libxml drops a default declaration
identical to the one already in scope, so a redundant `@xmlns` is unobservable in the output and a
mutation test cannot separate the conditional form from the unconditional one.
**Evidence:** `src/Component/Serialization/src/Normalizer/Xml/FHIRComplexTypeXmlNormalizer.php`
(search: `A literal xmlns attribute rather than createElementNS()`) records the prefix behaviour. The
suppression was measured on the earlier two-key implementation of this fix: removing the
declare-only-on-difference guard left every serialized document byte-identical and the suite green.
**Prevention:** Declare fragment namespaces the way the rest of this normalizer does — a literal
`xmlns` attribute via `setAttribute`, on the outermost element of the subtree only, letting
descendants inherit. To verify a namespace *condition* rather than a namespace *value*, assert on the
emitted declaration count and accept that output-equivalent variants exist; say so in the test rather
than implying the condition itself is pinned.
