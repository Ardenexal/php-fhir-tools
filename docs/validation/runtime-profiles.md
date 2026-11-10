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
* **Rules inside a slice are not enforced.** An invariant or binding on an element inside a slice,
  including an extension's sub-extensions, is skipped without a finding. Each skipped rule is kept
  as a `CompilerDiagnostic`: in `diagnostics` on each entry of
  `RuntimeProfileResolver::resolve($url, 'R4')->chain` for a profile, and in
  `resolveExtension($url, 'R4')->diagnostics` for an extension. `fhir:generate-ig` reports the same
  rules as warnings.
* **No terminology server, no binding check.** A binding no generated enum covers needs a
  terminology client; without one it is reported as `fhir:unchecked-binding` INFO.
* **Sub-extension cardinality, fixed and pattern values are not checked**, because the generated
  extension classes do not check them either.
