---
category: phpunit-testsuites
last_reviewed: 2026-07-09
---

# Footguns: PHPUnit / ParaTest Test-Suite Wiring

## Footgun: an abstract TestCase base in a scanned suite dir trips ParaTest's failOnWarning (but not plain phpunit)

**Status:** active | **Created:** 2026-07-09 | **Evidence:** OBSERVED (M00 sdc-foundation, RUNTIME)

`phpunit.dist.xml` sets `failOnWarning="true"`. When a `<testsuite>` maps to a `<directory>` and
that directory contains an **abstract** `TestCase` subclass, ParaTest (`composer test-ai`, the
default runner) emits a runner warning:

```
Class <FQCN> declared in <path> is abstract
```

`failOnWarning` turns that single warning into a non-zero exit **even with 0 failures / 0 errors**
(`OK ... tests` but `composer` reports "returned with error code 1"). The trap is invisible to the
obvious checks:

- Running the suite **in isolation** (e.g. `composer test-ai-sdc-extract-spec`) does not warn —
  the abstract base lives one directory up and is not scanned by the dedicated subdirectory suite.
- Running the aggregate suite with **`--ai-serial`** (plain phpunit) does **not** warn either — only
  ParaTest surfaces the "is abstract" warning. So a serial run passes while the real parallel run fails.

Only the full parallel `composer test-ai` reproduces it, and its compact output hides the warning —
you must run `php ./vendor/bin/paratest --testsuite=<union> --no-coverage` to see the message.

**Evidence:** `phpunit.dist.xml` (search: `is abstract` exclude comment) excludes
`src/Component/Sdc/tests/Integration/AbstractSdcConformanceTest.php` from the `integration` suite for
exactly this reason; the reusable base is
`src/Component/Sdc/tests/Integration/AbstractSdcConformanceTest.php` (search: `abstract class AbstractSdcConformanceTest`).

**Mitigation:** when a reusable **abstract** test base lives inside a directory scanned by an
aggregate suite, add an explicit `<exclude>` for that file to the aggregate suite (concrete
subclasses in sub-directories run via their own dedicated suites). Verify with the full
`composer test-ai`, never only an isolated suite or an `--ai-serial` run.

**Related:** the aggregate `integration` suite and the per-module `integration-*` legs must cover the
same tests (see the `phpunit.dist.xml` comment). A new component directory added to `integration`
without a matching `integration-<component>` leg silently breaks that parity the moment a plain
(non-dedicated-suite) integration test lands in it.
