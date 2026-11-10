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

List the core package of your FHIR release (`hl7.fhir.r4.core`, `hl7.fhir.r4b.core` or
`hl7.fhir.r5.core`) too, and every package a profile depends on. A profile is resolved through its
parents up to the core resource definition, and data-type profiles and extension definitions it names
are looked up the same way. A parent that no listed package holds makes the profile unusable: it is
reported as not checked, and none of its rules run.

## Set it up without the bundle

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\Profile\CompositeStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionLoader;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileAttributeReader;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;

$resolver = new RuntimeProfileResolver(new CompositeStructureDefinitionRegistry([
    new PackageDirectoryStructureDefinitionLoader('/path/to/hl7.fhir.r4.core'),
    new PackageDirectoryStructureDefinitionLoader('/path/to/hl7.fhir.au.base'),
    InMemoryStructureDefinitionRegistry::fromFiles(['/path/to/my-observation.json']),
]));

// Pass the resolver to the service...
$service = new FHIRValidationService($validator, $pathService, runtimeProfiles: $resolver);

// ...and give the slice validator the runtime attribute reader in your ConstraintValidatorFactory,
// or slices from runtime profiles are not enforced
new FHIRSliceConstraintValidator($propertyAccessor, $matcher, new RuntimeProfileAttributeReader($resolver));
```

You can also add decoded definitions directly with `new InMemoryStructureDefinitionRegistry([...])`.

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

A profile URL that the resource's class already declares is checked from the class. Any other URL
is resolved at runtime.

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

A profile that cannot be used is reported as a warning, and validation goes on without it:

* `Profile reference '…' has not been checked because it is unknown` when no registry holds it.
* `Profile reference '…' has not been checked: …` naming the reason otherwise: the definition is
  for another FHIR release, has no snapshot, has a parent that cannot be found, is not a resource,
  data type or extension definition (a logical model, for example), or has parents that refer to
  each other.

A profile for another resource type than the one validated is an error, unless a resource nested
inside it, such as a Bundle entry, is of that type and is checked against it instead.

## Limits

* **A snapshot is required.** Definitions without a snapshot are reported, not expanded. Published
  packages carry snapshots; a profile you write yourself needs one generated, for example by the
  IG Publisher or SUSHI.
* **Rules inside a slice are not enforced.** An invariant or binding on an element inside a slice,
  including an extension's sub-extensions, is skipped without a finding. The reason is kept as a
  `CompilerDiagnostic` on each entry of `RuntimeProfileResolver::resolve($url, 'R4')->chain`, and
  `fhir:generate-ig` reports the same rules as warnings.
* **No terminology server, no binding check.** A binding no generated enum covers needs a
  terminology client; without one it is reported as `fhir:unchecked-binding` INFO.
* **Sub-extension cardinality, fixed and pattern values are not checked**, because the generated
  extension classes do not check them either.
