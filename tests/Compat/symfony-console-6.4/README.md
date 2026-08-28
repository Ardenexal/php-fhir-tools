# symfony/console 6.4 compatibility harness

`ardenexal/fhir-code-generation` declares `"symfony/console": "^6.4|^7.4"`. Nothing used to
exercise the lower bound, and both generator commands were written in Symfony's invokable
style (`__invoke()` plus `#[Option]`/`#[Ask]` parameter attributes), which only exists from
7.3. Composer installed happily on 6.4, autoloading succeeded, and static analysis on a 7.x
machine saw nothing wrong — PHP does not resolve attribute classes until they are reflected.
The commands appeared in `bin/console list fhir` and then failed on invoke:

```
$ php bin/console fhir:generate-ig
  You must override the execute() method in the concrete command class.

$ php bin/console fhir:generate-ig --package=hl7.fhir.au.base
  The "--package" option does not exist.
```

This directory is a standalone consumer install that pins `symfony/console:6.4.*` and
registers both commands in a bare `Application`, so the declared lower bound is actually
invoked in CI.

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
requires `ardenexal/fhir-metadata: ^0.4`, and a path repository on a feature branch would
otherwise resolve to `dev-<branch>`, which that constraint cannot match.

## Running it

```bash
composer install --working-dir=tests/Compat/symfony-console-6.4
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

The harness also runs on 7.x — change the `symfony/console` constraint and reinstall — which
is how the byte-for-byte output comparison between the two console versions was made.
