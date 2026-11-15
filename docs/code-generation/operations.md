---
description: Generate typed PHP classes for FHIR operations from OperationDefinition resources.
icon: sliders
---

# Generating Operation Classes

`fhir:generate` emits typed input and output classes for every FHIR operation declared in the core
packages, alongside the resources and data types. An operation like `CodeSystem/$lookup` stops being
a hand-built `Parameters` resource and becomes three classes you can autocomplete against.

To use the generated classes — build a request, read a response — see
[Operations & the Parameters Mapping](../serialization/operations.md). This page covers what is
generated, where it lands, and how the names are derived.

## What gets generated

Counts are per FHIR version, from the core packages:

| Version | Operations | Payload classes |
| ------- | ---------- | --------------- |
| R4      | 47         | 115             |
| R4B     | 47         | 115             |
| R5      | 60         | 145             |

Each operation produces up to three kinds of class under
`src/Component/Models/src/{R4,R4B,R5}/Operation/{Stem}/`:

- **`{Stem}Input`** — the `in` parameters, one constructor-promoted property each.
- **`{Stem}Output`** — the `out` parameters, when the operation answers with a `Parameters`.
- **`{Stem}Operation`** — a metadata-only holder carrying `#[FhirOperation]`: the canonical URL, the
  operation code, which resource types it applies to, whether it is invocable at instance / type /
  system level, and the declared output shape.

Nested `part` groups become their own classes in the same directory, keyed by parameter path — so
`$lookup`'s `property` and `property.subproperty` groups are `CodeSystemLookupOutProperty` and
`CodeSystemLookupOutPropertySubproperty`.

```
src/Component/Models/src/R4/Operation/CodeSystemLookup/
├── CodeSystemLookupInput.php
├── CodeSystemLookupOperation.php
├── CodeSystemLookupOutput.php
├── CodeSystemLookupOutDesignation.php
├── CodeSystemLookupOutProperty.php
└── CodeSystemLookupOutPropertySubproperty.php
```

### Output shapes

"The response is a `Parameters`" holds for only about a quarter of operations. The shape is decided at
generation time and recorded on the holder as `OperationOutputShape`, because a mapper that assumes
`Parameters` produces wrong output for the majority case:

| Shape               | R4 | R4B | R5 | Meaning                                                        | Example                 |
| ------------------- | -- | --- | -- | -------------------------------------------------------------- | ----------------------- |
| `Parameters`        | 14 | 14  | 16 | A genuine `Parameters`; an `Output` class is generated.         | `CodeSystem/$lookup`    |
| `BareResource`      | 27 | 27  | 39 | The response **is** the resource — no wrapper to unpack.        | `Patient/$everything`   |
| `NamedBareResource` | 3  | 3   | 3  | A sole resource-typed `out` parameter **not** named `return`.   | `Resource/$graph`       |
| `NoOutput`          | 3  | 3   | 2  | No `out` parameters; a successful invocation yields no body.    | `Composition/$document` |

`BareResource` is not an optimisation this library chose; it is the specification's own rule. From
`hl7.org/fhir/R4/operations.html`, verbatim: *"If there is only one out parameter, which is a
Resource with the parameter name `return` then the parameter format is not used, and the response is
simply the resource itself."*

Read that condition closely, because `NamedBareResource` exists entirely because of it: the un-wrap
rule is keyed on the **name** `return`. A sole resource-typed `out` parameter under any other name
fails the condition, so the parameter format *is* used and the resource arrives inside a
one-parameter `Parameters` after all. Only three operations per version qualify, and collapsing them
into `BareResource` would be wrong in both directions — it reads a wrapped body as bare, and emits a
bare body a server would have to guess at. Despite the case name, this shape is not bare.

All of which is why [`fromResponse()`](../serialization/operations.md#reading-a-response) consults the
declared shape rather than guessing from the body it was handed.

### Polymorphic parameters

A parameter typed `Element` or `*` is constrained to a closed set of concrete types, and the
generated property carries that set as choice variants — the same metadata the models already use
for `value[x]`. `$lookup`'s `property.value` resolves to seven variants, emitted in this order and
identical across R4, R4B and R5:

```
code · Coding · boolean · dateTime · decimal · integer · string
```

That order is not cosmetic and is not alphabetical. Variants are matched by `instanceof` in
declaration order, and the generated primitive wrappers form real inheritance chains
(`CodePrimitive extends StringPrimitive`, `UrlPrimitive extends UriPrimitive`), so a superclass
listed before its subclass silently steals the match and serializes under the wrong `value[x]` key —
with no error and a structurally valid-looking result. Any code building a variant list must order it
subclass-before-superclass. Alphabetical sorting is the specific trap: it is correct for most pairs
by luck, but `{uri, url}` sorts to `uri, url` while `UrlPrimitive extends UriPrimitive`, so a URL
emits as `valueUri`.

Where that set is read from is a genuine trap, because the specification and the shipped packages
disagree. R5 added a first-class `parameter.allowedType` element and presents it as *the* way to
express this — but **no shipped `OperationDefinition` in any version populates it.** Every definition
carrying allowed-type information uses the pre-R5
`operationdefinition-allowed-type` extension, R5 included. `AllowedTypeReader` therefore unions both
sources on all versions and deliberately does **not** branch on version: branching reads the empty
source exactly where the packages have nothing, so R5 resolves to no variants while R4 works.

## How class names are derived

FHIR names are not PHP identifiers, and no single transformation makes them into one: the corpus
contains hyphens (`validate-code`), leading underscores (`_count`), dots
(`targetIdentifier.period`), PHP reserved words (`use`, `return`, `default`), and — in R5 — a
published typo (`targetIdentifer.preferred`) that has to survive verbatim on the wire.

`OperationClassNamer` applies these rules. They are pinned by
`tests/Integration/OperationDocsExamplesTest.php::testHowClassNamesAreDerived`, so the table below
and the code cannot drift apart silently.

| Input                                  | Result                            | Rule                                            |
| -------------------------------------- | --------------------------------- | ----------------------------------------------- |
| `resource: [ValueSet]`, `code: validate-code` | `ValueSetValidateCode`     | Stem is `resource[0]` + `code`, each PascalCased |
| `resource: [CodeSystem]`, `code: lookup`      | `CodeSystemLookup`         | Same rule, nothing to map                        |
| `out` + path `[property]`              | `OutProperty`                     | Part classes are keyed by `use` **and** path     |
| `out` + path `[property, subproperty]` | `OutPropertySubproperty`          | Path segments concatenate                        |
| `in` + path `[property]`               | `InProperty`                      | `use` in the key stops in/out collisions         |
| `_count`                               | `$count`                          | Leading underscore dropped                       |
| `targetIdentifier.period`              | `$targetIdentifierPeriod`         | Dots camelCase                                   |
| `use`                                  | `$use`                            | Properties are **not** reserved-word escaped     |
| `code: use`, no `resource`              | `UseOperation`                    | Class names **are**, with an `Operation` suffix  |

Two consequences worth knowing:

**The mapping is one-way.** `_count` and `count` both yield `count`, so a PHP property name cannot be
turned back into a wire name. Generated classes therefore store both — `#[FhirOperationParameter]`
carries `name` (the wire name) and `phpName` separately, and the mapper writes `name` to the wire.

**Properties and classes are guarded differently.** `…\Designation\Use` is a fatal parse error, so
class names that land on a reserved word get an `Operation` suffix. A property named `$use` is
perfectly legal, so it is emitted verbatim. Applying the class guard to properties was a real bug:
it emitted `$useParameter`, and an `assertNull($d->use)` written against it read a property that did
not exist, got `null`, and passed.

### Collisions are fatal, never silent

If two operations or two parameters derive the same identifier, generation throws and names both
sources. This is deliberate. The same slugging surface elsewhere in this codebase once *skipped* a
colliding name, which made a legal coded value unrepresentable with no error at all — a generator
that quietly drops one of two operations produces output that looks complete.

## Limitation: IG-sourced OperationDefinitions are not generated

Operation generation runs only under [`fhir:generate`](base-models.md), from the core packages.
[`fhir:generate-ig`](implementation-guides.md) generates extensions, profiles and constrained
complex types, and does **not** emit operation classes — so an `OperationDefinition` published by an
Implementation Guide (or authored locally) produces nothing today.

The obstacle is wiring rather than design: the operation generator needs a type index built from the
full `BuilderContext` to classify parameter types and resolve allowed-type variants, and the IG
command does not build one. If you need typed classes for your own operations, the workable path
today is to add the `OperationDefinition` to a package that `fhir:generate` loads.

Adding it needs a decision about where IG operation classes land in the isolated
`Models/src/IG/{version}/{Package}/` tree and how their stems avoid colliding with core ones, so it
is a small milestone rather than a patch.
