---
description: Validate extension context, modifier extensions, obligations, and MustSupport.
icon: puzzle-piece
---

# Extensions, Modifiers & Obligations

These are **service-level checks** that `FHIRValidationService::validate()` performs in addition to
the attribute-driven Symfony constraints. They cannot be expressed as a single property-level
attribute, so the service walks the resource tree itself:

| Check | Method | Fires when |
|---|---|---|
| Extension context | `validateExtensionContexts()` | Always (recursive walk over the full tree) |
| Extension `contextInvariant` | `validateExtensionContexts()` | Always, on the extension's use context |
| Modifier extension walk | `validateModifierExtensions()` | A `FHIRIGTypeRegistry` is provided |
| MustSupport collection | `collectMustSupportInfo()` | `$includeMustSupportInfo = true` |
| Obligation enforcement | `collectObligationViolations()` | A `FHIRObligationContext` is provided |

{% hint style="info" %}
The type-hierarchy resolver (used by extension-context resolution) and obligation contexts are
wired on the [Configuration](configuration.md) page. Symfony DI users get the defaults from
`FHIRBundle`.
{% endhint %}

## Extension context

An extension declares where it may be used. `validateExtensionContexts()` walks the resource tree
recursively and checks each extension against its declared contexts. The design is **defer-not-deny**:
a context only produces an ERROR when it can be evaluated confidently; an unknown or unreadable
situation defers (emits no violation), so the resolver can never introduce a false positive against
an already-valid resource. The three context types:

* **`type=element`** — matches against the bearing element's path or FHIR type. With
  `FhirPropertyTypeHierarchyResolver` wired (the default), bare-type contexts match the element's
  resolved supertype set, so a supertype such as `"DomainResource"` or `"Element"` permits its
  subtypes; foreign-root type-paths such as `"ElementDefinition.binding"` match when a path segment
  is typed with the context root. Resolution is monotonic — it only ever permits more. Without a
  resolver, bare-type and foreign-root contexts are deferred.
* **`type=fhirpath`** — the expression is evaluated against the element bearing the extension.
  Denial requires a confident result: only a single boolean `false` denies. An empty result or a
  FHIRPath evaluation error defers.
* **`type=extension`** — the chain of enclosing extension URLs is assembled by descending into
  nested extensions; the context permits when the declared parent URL appears anywhere in that
  chain. Denial requires a fully-known chain (including the definitive empty chain at element
  level); a chain containing an unreadable URL defers. Note OR semantics across multiple contexts:
  a sibling context that defers can mask a confident `type=extension` denial.

When OK, no violation. A confident mismatch raises an ERROR.

### Extension contextInvariant

An extension's `contextInvariant` is a FHIRPath constraint on its use context, also evaluated by
`validateExtensionContexts()`. Like ordinary invariants, an expression the engine cannot evaluate
surfaces as a `fhir:eval-error` INFO (not an ERROR) — see [Invariants](invariants.md).

## Modifier extensions

`validateModifierExtensions()` walks the tree recursively and flags **unknown modifier extension
URLs** as ERRORs. This check only runs when a `FHIRIGTypeRegistry` is provided to
`FHIRValidationService`; without one, the walk is skipped.

{% hint style="warning" %}
`#[FHIRIsModifier]` marks properties as modifier elements for introspection only — there is **no
active enforcement** of modifier element impact. Consumer applications must inspect modifier element
values themselves.
{% endhint %}

## MustSupport reporting

Pass `$includeMustSupportInfo = true` to emit INFO violations for null/empty must-support
properties. MustSupport is not a validity constraint per the FHIR spec, so these never affect
`isValid()` — they are intended for Implementation Guide conformance reporting.

```php
$report = $service->validate($patient, includeMustSupportInfo: true);

foreach ($report->info() as $info) {
    echo $info->message . "\n"; // "Must-support property 'identifier' is not populated."
}
```

## Obligations

An *obligation* is a statement in a profile that a particular actor (a system playing a role, such
as a data producer or consumer) SHALL or SHOULD do something with an element — most commonly
populate it. Obligation validation is opt-in and actor-scoped via `FHIRObligationContext`. When a
context is provided, `collectObligationViolations()` enforces matching populate obligations
(`SHALL`/`SHOULD:populate`) at ERROR/WARNING/INFO severity, and `applyNoErrorSuppression()`
suppresses errors for `SHALL:no-error` obligations.

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRObligationContext;

$context = new FHIRObligationContext(
    actorUrl: 'http://hl7.org/fhir/uv/ips/ActorDefinition/Creator',
);

$report = $service->validate(
    $resource,
    profileUrls: ['http://hl7.org/fhir/uv/ips/StructureDefinition/Patient-uv-ips'],
    obligationContext: $context,
);
```

Matching rules (`FHIRObligationContext::matchesObligation()`):

* An obligation with **no actor** applies to every context.
* An obligation with a **specific actor** only fires when `$context->actorUrl` matches.
* `new FHIRObligationContext(null)` matches only actor-less obligations.

{% hint style="info" %}
**Obligation filter evaluation is deferred.** `#[FHIRObligation]` entries with a non-null FHIRPath
`filter` are skipped; only unconditional obligations are enforced. Behaviour-only obligations
(display / persist / handle) cannot be checked from a resource instance and are not evaluated.
{% endhint %}

See [Configuration](configuration.md) for actor and type-hierarchy resolver setup.
