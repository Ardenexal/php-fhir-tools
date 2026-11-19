---
description: Validate against profiles and extension definitions you load as StructureDefinitions, without generating classes for them.
icon: file-import
---

# Runtime Profiles

You can validate a resource against a profile you hold only as a StructureDefinition: an
Implementation Guide package you downloaded, or a few JSON files. There is no need to run
`fhir:generate-ig` first. A profile loaded this way gives the same findings, with the same wording
and paths, as the profile class `fhir:generate-ig` would generate from it.

## Set it up in Symfony

List package directories, single StructureDefinition files, or both, under
`fhir.validation.runtime_profiles`:

```yaml
fhir:
    validation:
        runtime_profiles:
            package_dirs:
                - '%kernel.project_dir%/fhir-packages/hl7.fhir.r4.core'
                - '%kernel.project_dir%/fhir-packages/hl7.fhir.au.base'
            files:
                - '%kernel.project_dir%/config/fhir/my-observation.json'
```

* A package directory is an extracted FHIR package: the folder holding `package.json`, or the
  tarball root holding `package/`. Its `.index.json` is used when present, and the folder is scanned
  otherwise. The FHIR release comes from `fhirVersions` in `package.json`, or from each definition.
* Each file holds one StructureDefinition with its own `fhirVersion`.

The bundle then gives `FHIRValidationService` a `RuntimeProfileResolver` over them. A path that does
not exist fails when the validation service is first built, not at container compile time; a
package's definitions are read the first time one is looked up. With both lists empty, which is the
default, nothing changes.

When several sources hold the same URL and version, the files win, then the packages in the order
listed. That lets a local file replace a published definition.

List the core package of your FHIR release (`hl7.fhir.r4.core`, `hl7.fhir.r4b.core` or
`hl7.fhir.r5.core`) too, and every package a profile depends on. A profile is resolved through its
parents up to the core resource definition, and data-type profiles and extension definitions it names
are looked up the same way. A parent that no listed package holds makes the profile unusable: it is
reported as not checked, and none of its rules run.

## Set it up without the bundle

`FHIRValidationServiceBuilder` wires the service the way the bundle does:

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;

$service = FHIRValidationServiceBuilder::create()
    ->withPackageDirectories('/path/to/hl7.fhir.r4.core', '/path/to/hl7.fhir.au.base')
    ->withStructureDefinitionFiles('/path/to/my-observation.json')
    ->build();
```

Each `with*()` call returns a new builder. Definitions are searched in files, then registries, then
package directories, and the first match wins. The builder also takes a registry of your own
(`withStructureDefinitionRegistry()`, for example one backed by a database), a terminology client, a
reference resolver and an IG type registry.

To wire the service yourself, pass a `RuntimeProfileResolver` as `runtimeProfiles`, and give
`FHIRSliceConstraintValidator` a `RuntimeProfileAttributeReader` over the same resolver in your
`ConstraintValidatorFactory`. Without the reader, slices from runtime profiles are not enforced.
`InMemoryStructureDefinitionRegistry` also takes decoded definitions directly.

## Validate

Name the profile when you validate:

```php
$report = $service->validate($observation, ['http://hl7.org.au/fhir/StructureDefinition/au-bodyweight']);
```

To check each resource against the profiles in its own `meta.profile`, nested resources included,
as the HL7 reference validator does:

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationOptions;

$report = $service->validateWithOptions($bundle, new FHIRValidationOptions(profilesFromMeta: true));
```

A profile URL that the resource's class already declares is checked from the class, nested typed
instances included. Any other URL is resolved at runtime; without a resolver, a `meta.profile` URL
no class declares is reported as not checked, and a URL passed to `validate()` is ignored.

The spec also says an Observation recording a vital sign SHALL conform to the matching core
vital-signs profile, even when `meta.profile` does not name it. To apply that rule, as the HL7
reference validator does:

```php
$report = $service->validateWithOptions($bundle, new FHIRValidationOptions(profilesFromVitalSignsCodes: true));
```

Each Observation, nested ones included, whose `code.coding` holds one of these codes is then
checked against the profile, and its parents, as if `meta.profile` named it:

| Code | System | Profile |
|------|--------|---------|
| `85353-1` | LOINC | `vitalspanel` |
| `9279-1` | LOINC | `resprate` |
| `8867-4` | LOINC | `heartrate` |
| `2708-6`, `59408-5` | LOINC | `oxygensat` |
| `8310-5` | LOINC | `bodytemp` |
| `276885007` | SNOMED CT | `bodytemp` |
| `8302-2` | LOINC | `bodyheight` |
| `9843-4` | LOINC | `headcircum` |
| `29463-7` | LOINC | `bodyweight` |
| `39156-5` | LOINC | `bmi` |
| `85354-9`, `76534-7` | LOINC | `bp` |

The spec's vital-signs table lists the first LOINC code for each profile. `59408-5`, `76534-7` and
SNOMED CT `276885007` are not in it, but the HL7 reference validator maps them too, so they are
included to match it.

The profiles are the core ones (`http://hl7.org/fhir/StructureDefinition/bp` and so on), so the
service needs the release's core package to resolve them. An implied profile that cannot be
resolved is skipped without a warning, because you did not ask for it by name. The option is off
by default, because turning it on can report new errors on data that passed before.

To check that a profile and its parents resolve before validating anything, for example at
startup:

```php
$resolution = $resolver->resolve('http://hl7.org.au/fhir/StructureDefinition/au-bodyweight', 'R4');
if (!$resolution->isResolved()) {
    throw new \RuntimeException($resolution->message); // names the missing parent or unreadable file
}
```

## What is checked

The same rules a generated profile class enforces:

* Cardinality, fixed and pattern values, must-support, obligations, slicing and slices.
* The types a choice element may hold (`value[x] only Quantity`) and the targets a Reference may
  point at (`Reference(PractitionerRole)`), at the top level and inside a slice. A literal
  `Patient/1` is checked against targets that are core resource types without being resolved; a
  target that is an IG profile is checked only through a configured reference resolver.
* The profile's own invariants and its `required` and `extensible` bindings, reported at the
  element they are about. See [Profile invariants](invariants.md#profile-invariants) and
  [Profile bindings](terminology.md#profile-bindings).
* Data-type profiles named on an element, such as SimpleQuantity on `referenceRange.high`.
* The profile's parent profiles, each under its own URL, when the profile comes from `meta.profile`
  or you set `deriveProfilesFromClass` on `FHIRValidationOptions`.

A plain `Extension` whose `url` names an extension definition in the registry is checked like its
generated class: its contexts and context invariants, its value type, required and undeclared
sub-extensions, and its own invariants and bindings. A modifier extension with such a URL is no
longer reported as unknown.

Findings carry the profile URL in `profileGroup`, as findings from a generated profile class do.

## Slicing support

Generated profile classes and profiles resolved at runtime enforce slicing the same way. An item
joins a slice only when it matches every discriminator of the slicing.

| Discriminator or rule | Enforced | Notes |
|---|---|---|
| `value` and `pattern` | Yes | Compared with the slice's fixed or pattern value at the path |
| `exists` | Yes | |
| `type` | Yes | Compares the FHIR type at the path, such as a Bundle entry's resource |
| `profile` | Yes | Needs the profile generated or resolvable at runtime; otherwise the slice is not judged |
| `$this` with a fixed or pattern value | Yes | Uses the slice's own value, such as AU Core smoking status's `social-history` category |
| `$this` with only a `required` binding | With a terminology client | Such as AU Core's `coding:pbs` and `coding:amt`; without a client the slice is not judged |
| Several discriminators on one slicing | Yes | All must match |
| A path through `resolve()` | No | Closed or openAtEnd slicing that depends on it is enforced as open. The compiler reports a `CompilerDiagnostic`, and `fhir:generate` reports it as a warning |
| Slice minimum and maximum | Yes | Not checked for a slice that could not be judged |
| `closed` and `openAtEnd` | Yes | Items that might belong to a slice that could not be judged are not rejected |
| Invariants and bindings inside a slice | Yes | Checked on the items in that slice |
| Fixed and pattern values inside a slice | Yes | |
| Types and Reference targets inside a slice | Yes | As at the top level |
| A data-type profile named on a slice | Yes | Such as `au-ihi` on `Patient.identifier:ihi` |
| `ordered` slicing | Yes | An item matching a slice declared before the slice of an earlier item is an error. Items that match no slice, and items that might belong to a slice that could not be judged, are not part of the order |
| Reslices (`component:a/b`) | No | Not compiled |
| Choice type slices (`value[x]:valueQuantity`) | As plain constraints | Checked on the variant, such as `valueQuantity` |

"Not judged" means that items that might belong to the slice are not counted against closed or
openAtEnd rules and the slice's minimum is not checked, so a conforming resource is not rejected.
[Structural validation](structural.md#slicing) describes the matching in more detail.

## When a profile cannot be checked

A profile that cannot be used is reported as a warning with the code `fhir:profile-not-checked`, and
validation goes on without it. `$report->hasUncheckedProfiles()` tells you whether that happened,
and `$report->uncheckedProfiles()` lists the findings:

* `Profile reference '…' has not been checked because it is unknown` when no registry holds it.
* `Profile reference '…' has not been checked: …` naming the reason otherwise: the definition is
  for another FHIR release, has no snapshot, has a parent that cannot be found, is not a resource,
  data type or extension definition (a logical model, for example), has parents that refer to
  each other, or needs a definition whose file cannot be read.

`parameters['reason']` on the finding names the reason in a stable form, such as `unknown`,
`unresolved-parent` or `unreadable`.

A broken file in a package only affects the profiles that need it. When `.index.json` lists a file
that is missing or not JSON, only that definition's URL fails. When the package has no
`.index.json` and a StructureDefinition in it is not valid JSON, the scan cannot tell which URL it
held, so every profile from that package is reported as not checked. An extension whose definition
cannot be read is treated as one nothing is known about.

A profile for another resource type than the one validated is an error, unless a resource nested
inside it, such as a Bundle entry, claims that profile in its `meta.profile` or class and is checked
against it instead.

## Limits

* **A snapshot is required.** Definitions without a snapshot are reported, not expanded. Published
  packages carry snapshots; a profile you write yourself needs one generated, for example by the
  IG Publisher or SUSHI.
* **Rules on an extension definition's sub-extensions are not enforced.** An invariant or binding
  on a sub-extension is skipped without a finding and kept as a `CompilerDiagnostic` in
  `resolveExtension($url, 'R4')->diagnostics`; `fhir:generate-ig` reports it as a warning. Rules
  inside a profile's slices, including a data-type profile such as `au-ihi` named on a slice, are
  enforced for the items in that slice, unless no item can be matched to the slice because its
  discriminator has nothing to match on (a path through `resolve()`, or a value the profile does
  not state); that is reported as a `CompilerDiagnostic`.
* **No terminology server, no binding check.** A binding no generated enum covers needs a
  terminology client; without one it is reported as `fhir:unchecked-binding` INFO.
* **Sub-extension cardinality, fixed and pattern values are not checked**, because the generated
  extension classes do not check them either.
* **A `maxLength` is checked only where a profile's other element rules are.** One in an extension's
  own definition, or on the root element of a profile on a primitive type, is not checked.
