---
category: fhir-type-name-resolution
last_reviewed: 2026-09-02
---

# Footguns: FHIR Type Name Resolution

## Footgun: a FHIR type name does not identify a class, and the hand-written tables that paper over that are wrong exactly where derivation is version-dependent

**Status:** active | **Created:** 2026-09-02 | **Evidence:** OBSERVED (reflection-type-registry M06)
**Decision changed:** whether a hand-maintained FHIR type table can be replaced by derived model metadata inside a behaviour-neutral change
**Trigger phase:** SCOPE

The same FHIR type name exists in every release, so a bare name resolves to a class only once a version
is supplied. Any component holding a name-keyed table of model facts is therefore holding something it
cannot derive on demand, and the obvious cleanup — read it off the generated models instead — is not
the behaviour-neutral swap it looks like.

`FHIRPath\Type\FHIRTypeResolver` (search: `TYPE_PARENTS`) is the worked example: a 17-entry map from a
FHIR type to its parent, of which 13 agree with the generated models and 4 do not. Comparing generated
inheritance for all 17 across the shipped versions puts the trap in one line:

| Table types | R4 | R4B | R5 |
|---|---|---|---|
| the 13 that agree with the spec | identical | identical | identical |
| `uri`, `base64Binary`, `instant` | `Element` | `Element` | `PrimitiveType` |
| `Money` | `Element` | `Element` | `DataType` |

The four entries that are wrong are the four whose derived ancestry is version-dependent. So a
version-free replacement needs an exception list covering the same four entries the table already
hardcodes — which renames the duplicated registry rather than retiring it.

Two things make this easy to miss. A `const` array is not reflection, so a reflection inventory and a
grep-based gate both look straight past it. And the table is reachable with no object at all: FHIRPath
wraps scalar properties in `FHIRTypedScalar`, which carries a type name and no class, and for those
values the table is the *only* mechanism that answers — `xhtml is string` returns false purely because
`xhtml` has no entry.

**Fix:** separate the two questions before touching either. Name-to-class resolution belongs in
`Metadata\Type\FHIRModelClassLocatorInterface` (search: `function locate`), which takes the version and
scopes strictly to it, and which `FHIRSerializedTypeResolver` delegates to so the namespace layout has
one owner. A name-keyed table of *answers* is a separate problem: pin every current answer first,
wrong ones included, then change them in a commit whose diff names the expressions that flip. Do not
reseed an expectation to clear a red suite.

Related: [[choice-variant-ordering]] records the consumer-side half — hand-building
`Models\{version}\...` FQCNs rather than going through the resolver.
