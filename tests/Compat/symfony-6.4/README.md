# Symfony 6.4 compatibility harness

`ardenexal/fhir-code-generation` declares every `symfony/*` dependency at `^6.4|^7.x`.
Nothing used to exercise that lower bound, and two separate bugs grew in the gap.

## Round one: the command layer

Both generator commands were written in Symfony's invokable style (`__invoke()` plus
`#[Option]`/`#[Ask]` parameter attributes), which only exists from console 7.3. Composer
installed happily on 6.4, autoloading succeeded, and static analysis on a 7.x machine saw
nothing wrong — PHP does not resolve attribute classes until they are reflected. The commands
appeared in `bin/console list fhir` and then failed on invoke:

```
$ php bin/console fhir:generate-ig
  You must override the execute() method in the concrete command class.

$ php bin/console fhir:generate-ig --package=hl7.fhir.au.base
  The "--package" option does not exist.
```

## Round two: the generator internals, and why this harness missed them

The first version of this harness pinned only `symfony/console`. Every other Symfony package
resolved to its *highest* allowed version, so `symfony/string` installed at 7.4 under its own
`^6.4|^7.0` constraint. The generators called `AbstractString::pascal()`, added in string 7.3
— fine here, fatal for every real 6.4 consumer:

```
$ php bin/console fhir:generate-ig --package=hl7.fhir.au.base
  [IG_GENERATION_ERROR] Call to undefined method Symfony\Component\String\UnicodeString::pascal()
      at http://hl7.org.au/fhir/StructureDefinition/au-ihi
  … 108 errors total, no output directory
```

The acceptance checks — including a real R4B generation — passed throughout, because a
partial pin tests the lower bound of one package and the upper bound of the rest. The harness
now pins **every** `symfony/*` package the component constrains to `^6.4`, and CI asserts each
one resolved to a 6.4 release before running anything.

Generation is the check that matters here: with string pinned to 6.4 and the bug present,
`fhir:generate-ig` produces 108 errors and no files, and `fhir:generate` exits non-zero having
written 103 of its usual 1700. Neither is visible from `--help`.

One caveat on how far the pin reaches: it covers the `symfony/*` packages the component
*declares*, which is the right scope, but this is not a pure 6.4 tree. Transitive Symfony
packages still resolve freely — `symfony/var-exporter` arrives at 7.4 by way of
`symfony/dependency-injection` — so the guard would not notice a generator reaching one of
those directly. Anything the generators use directly belongs in `composer.json`, where the
pin and the CI assertion can both see it.

This directory is a standalone consumer install that registers both commands in a bare
`Application`, so the declared lower bound is actually invoked in CI.

## Why not the demo app or the monorepo root

- `demo/composer.json` pins every Symfony package to `7.4.*`.
- The monorepo root cannot install console 6.4 at all: `brianium/paratest` requires `^7.4.7`.

Hence a separate install. The two components are consumed through `path` repositories with
`symlink: false`, so:

- the harness runs the current working tree rather than a published release, and
- generated output lands in the harness's own `vendor/ardenexal/Models/`, because the
  generators resolve their output directory relative to `__DIR__` of the installed component.
  A symlinked install would instead write into `src/Component/Models/`, which a CI test job
  must not touch.

The `options.versions` pins in `composer.json` exist because `ardenexal/fhir-code-generation`
requires `ardenexal/fhir-metadata: ^0.5`, and a path repository on a feature branch would
otherwise resolve to `dev-<branch>`, which that constraint cannot match.

## Running it

```bash
composer install --working-dir=tests/Compat/symfony-6.4
```

Then, from this directory:

```bash
php console.php list fhir
php console.php help fhir:generate-ig
php console.php fhir:generate-ig --package=hl7.fhir.au.base --package=hl7.fhir.au.core
php console.php fhir:generate --package=hl7.fhir.r4b.core
```

`fhir.ig.packages` is normally supplied by the bundle's DI configuration. The harness reads
the comma-separated `FHIR_IG_PACKAGES` environment variable in its place, so the config
fallback path of `fhir:generate-ig` is reachable without a kernel:

```bash
FHIR_IG_PACKAGES=hl7.fhir.au.base php console.php fhir:generate-ig
```

The harness also runs on 7.x — rewrite the `6.4.*` constraints to `7.4.*` and reinstall —
which is how the byte-for-byte output comparison between the two Symfony versions is made:

```bash
# with 6.4 pinned, current code
php console.php fhir:generate-ig --package=hl7.fhir.au.base
(cd vendor/ardenexal/Models/src && find . -name '*.php' | sort | xargs sha256sum | sha256sum)
```

Repeat on 7.4 and compare the two digests. That comparison is the reason `StringCase::pascal()`
is a transcription of upstream's `camel()->title()` rather than a reimplementation: these
strings become generated class names, so a divergence between framework versions would
silently rename classes instead of failing loudly. All 800 `hl7.fhir.au.base` files hash
identically across `pascal()` on 7.4 and the shim on 6.4.
