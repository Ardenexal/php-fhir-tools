---
description: Validate that references point to resources conforming to required target profiles.
icon: link
---

# Reference & Target Profile Validation

Validates that a `Reference`- or canonical-typed property points to a resource conforming to at
least one of the declared target profile URLs, via `FHIRTargetProfileValidator`, driven by the
`#[FHIRTargetProfile]` attribute (which carries a `targetProfiles` list).

{% hint style="info" %}
Reference resolution is pluggable through `FHIRReferenceResolverInterface`. Configure a resolver
on the [Configuration](configuration.md) page. The default `NullFHIRReferenceResolver` skips all
target-profile checks silently.
{% endhint %}

## How it works

For each `Reference` object (and each element of an array of references), the validator delegates
resolution to the configured `FHIRReferenceResolverInterface::resolve()`:

1. If the resolver returns `null` (including the `NullFHIRReferenceResolver` default), the check
   is **skipped silently** — no violation.
2. If the resolved object carries no `#[FHIRProfile]` attribute, a WARNING is emitted:
   `Cannot verify target profile conformance: the resolved object carries no #[FHIRProfile] attribute.`
3. If the resolved object has profiles but none match any URL in `targetProfiles`, an ERROR is
   emitted listing expected vs. actual profile URLs.

Canonical-typed values (raw `string` or `\Stringable` objects such as `CanonicalPrimitive`) are
always skipped — they cannot be resolved to an in-process PHP object. Null values are skipped.

Both messages are overridable via the `FHIRTargetProfile` key in `FHIRValidationMessageRegistry`.

## Resolver examples

See [Configuration](configuration.md) for wiring a Bundle-scanning or registry-based resolver
that maps `Reference` objects to their target PHP model.
