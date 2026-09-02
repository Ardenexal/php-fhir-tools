---
category: verification
last_reviewed: 2026-09-02
---

# Lessons: Verification

## Lesson: Harness checks match exact text patterns, not semantic equivalents

**Status:** active | **Created:** 2026-05-10 | **Evidence:** OBSERVED

When rewriting CLAUDE.md to satisfy harness checks, paraphrasing the skill-reference pointer
in the READ step ("Check `.goat-flow/skill-docs/`...") caused the `instruction-file-skill-reference-pointer` setup check to fail, even though the intent was identical to the original wording.

The harness audits for specific strings (e.g. "Before declaring any tool or capability unavailable, read the matching playbook in `.goat-flow/skill-docs/`"). Semantically equivalent rewrites do not pass.

**Rule:** When editing instruction files, diff against the exact strings the harness checks before removing or paraphrasing any existing pointer text. The `howToFix` field in the audit JSON contains the exact required wording.

**Evidence:** CLAUDE.md READ section (search: `Before declaring any tool or capability unavailable`) — must match exactly.

## Lesson: A guard's own condition does not tell you whether the guard decides anything

**Created:** 2026-09-02
**Decision changed:** When judging whether a refactor is behaviour-neutral, compare the outputs. Do
not conclude a branch is observable from reading the branch, and do not conclude it is unobservable
from reading it either -- run both shapes and diff.
**Trigger phase:** SCOPE
**What happened:** Migrating the Serialization normalizers off reflection meant collapsing an
`isInitialized`-then-read pair into one guarded read, which merges "slot never written" with "slot
holds null". Two resource normalizers looked unsafe to collapse: they pass the value to
`shouldOmitValue`, which skips null only when the caller opted in via `omitNullValues`. Reading that,
the conclusion was that with the option off, collapsing would start emitting nulls for properties
nobody had set -- a silent behaviour change on a public option with no test coverage. That was
reported as a finding.

It was wrong. Serializing both shapes with `omitNullValues: false` produced byte-identical JSON *and*
XML. Every emit branch downstream independently guards `$normalizedValue !== null`, so the option only
chooses *where* a null is discarded, never whether. The guard whose condition looked decisive decided
nothing.

**Evidence:** `src/Component/Serialization/src/Normalizer/Common/AbstractFHIRNormalizer.php`
(search: `if ($value === null && $context->omitNullValues)`) -- the conditional skip. Then
`src/Component/Serialization/src/Normalizer/Json/FHIRResourceJsonNormalizer.php`
(search: `if ($normalizedValue !== null && !$this->shouldOmitValue($normalizedValue, $fhirContext))`)
-- the unconditional one that makes it moot. Pinned by
`src/Component/Serialization/tests/Integration/OutputSnapshotBaselineTest.php`
(search: `testUnwrittenAndExplicitlyNullPropertiesSerializeIdenticallyEvenWhenNullsAreKept`).

**Prevention:** A neutrality claim about a code path is a claim about that path's *output*. Build the
two inputs, serialize both, diff them. Reading one guard tells you what that line does, not what the
function returns -- and in a pipeline where several stages filter the same value, the first guard you
find is rarely the one that matters.

## Lesson: When a refactor's neutrality rests on a claim about generated code, count the corpus

**Created:** 2026-09-02
**Decision changed:** Turn "these two lookups should agree for our generated classes" into an
enumeration over every generated class with a disagreement count, before writing the migration.
**Trigger phase:** SCOPE
**What happened:** Two Serialization rows resolved a property's declared type through
`$property->getDeclaringClass()->getName()` rather than the concrete class. On a profile subclass
those differ, so substituting the concrete class was flagged as the one row that might not be
behaviour-neutral, and was nearly deferred to a later milestone on that basis.

Enumerating instead settled it in one run: 45,340 public properties across every generated and CDA
class, 16,596 of them inherited, and `declaredClassOf()` / `declaredTypeOf()` returned the same answer
either way in every single case. Zero disagreements. The reason is structural -- PHP resolves an
inherited property to the same `ReflectionProperty` whichever class you reach it through -- so the
substitution is sound rather than lucky, and the row migrated with the rest.

The same sweep answered a second question for free: no generated class carries a public *static*
property (6,579 scanned), which matters because the accessor's name enumeration includes statics.

**Evidence:** `src/Component/Metadata/src/Type/FHIRModelAccessor.php`
(search: `private static function propertyType`) -- both lookups route through one reflection call, so
the equivalence follows from PHP's resolution rather than from the generator's habits.

**Prevention:** Generated code is a finite, enumerable corpus, so a question about all of it is a
loop, not a judgement. Write the loop and report the count. A deferral justified by "this might
differ" is worth about ten minutes of measurement, and the measurement usually removes the deferral.
