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

Canonical-typed values (raw `string` or `\Stringable` objects such as `CanonicalPrimitive`) never
reach the resolver, because they cannot be resolved to an in-process PHP object. Null values are skipped.

Both resolver messages are overridable via the `FHIRTargetProfile` key in
`FHIRValidationMessageRegistry`.

## Checks that need no resolver

Three rules read only the reference or canonical itself, so they run with the default
`NullFHIRReferenceResolver` too. Each reports an ERROR in the HL7 Java reference validator's wording:

| Rule | Example message |
|---|---|
| A literal reference shaped `[type]/[id]` or `[base]/[type]/[id]`, with or without `/_history/[version]`, must name a type the element allows (see [Target types](#target-types)). A contained (`#p1`), `urn:uuid:` or conditional reference names no type and is left alone | `Invalid Resource target type. Found Patient, but expected one of (PractitionerRole)` |
| A canonical shaped `[base]/[type]/[id]` must name a type the element allows (see [Target types](#target-types)). A canonical of another shape (`http://loinc.org/vs/LL123`, `urn:oid:…`) is left alone | `Canonical URL 'http://hl7.org/fhir/ValueSet/example-extensional' refers to a resource that has the wrong type. Found ValueSet expecting one of [Questionnaire]` |
| The query of a conditional reference must be `name=value` parameters joined by `&` | `The query part of the conditional reference is not a valid query string (Device?identifier=1234&?==)` |

The HL7 Java reference validator checks a literal reference's type only with its
`assumeValidRestReferences` option; this validator always does. The three messages above are fixed;
`FHIRValidationMessageRegistry` does not override them.

### Target types

The type check needs to know which resource type each target profile allows:

* A core resource type's URL (`http://hl7.org/fhir/StructureDefinition/Patient`) names it.
* Any other profile allows the type it constrains. That type is looked up in the generated class
  registered for the URL in the IG type registry, then in the core profiles generated with the models
  (`vitalsigns`, `cqf-questionnaire` ...), then in the StructureDefinitions loaded for runtime profiles
  (`StructureDefinition.type`, in the reference's own FHIR release). So `Observation/1` fails
  `Reference(au-core-patient)` without being resolved, once AU Core is generated or loaded.

If any target is `Resource` (or another abstract type), or is a profile none of these sources knows,
the type is not checked: that target could allow any type, and only resolving the reference would say.

An element whose Reference and canonical types list different targets, such as
`Reference(Group) | canonical(EvidenceVariable)`, has one `#[FHIRTargetProfile]` per kind. Each
names its `kind`, and a reference is checked only against the Reference targets, a canonical only
against the canonical ones. A kind with no target restriction gets no attribute.

## Resolver examples

See [Configuration](configuration.md) for wiring a Bundle-scanning or registry-based resolver
that maps `Reference` objects to their target PHP model.
