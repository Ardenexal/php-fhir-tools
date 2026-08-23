---
category: demo-app-composer-autoload-drift
last_reviewed: 2026-08-05
---

# Footguns: `demo/composer.json` autoload drift from root `composer.json`

## Footgun: a new root-`composer.json` `autoload.psr-4` component entry does not propagate to `demo/composer.json`

**Status:** active | **Created:** 2026-08-05 | **Evidence:** OBSERVED (sdc-questionnaire-playground M01)

The root `composer.json` and `demo/composer.json` each maintain their own independent
`autoload.psr-4` map — `demo/` does not `require` the root package or inherit its autoload config.
When a new component is added under `src/Component/<Name>/src/` and wired into root
`composer.json`, nothing enforces adding the matching entry to `demo/composer.json`. The result: the
class is loadable everywhere except `demo/`, and any service that transitively constructs one (e.g. a
default constructor arg `new SomeClassFromThatComponent()`) throws `Class "..." not found` — but only
inside `demo/`, at runtime, when the container tries to build the service.

This was found via `Ardenexal\FHIRTools\Component\HttpClient\`: present in root `composer.json` but
absent from `demo/composer.json`, silently breaking `demo/`'s *existing* `ValidationControllerTest`
suite (`FHIRBundle`'s `services.yaml` unconditionally references
`HttpClient\NullFHIRHttpClient`) — pre-existing breakage, unrelated to the `Sdc` component this
milestone was adding, discovered only because DI wiring surfaced it.

**Mitigation:** when adding a new `Component\<Name>\` autoload entry to root `composer.json`, also add
it to `demo/composer.json`'s `autoload.psr-4` block and run `composer dump-autoload` from `demo/`
(not just root). Diff `demo/vendor/composer/autoload_psr4.php` against
`src/Bundle/FHIRBundle/composer.json`'s declared dependencies (or just root's psr-4 map) if a demo
service throws a `Class "...\Component\X\..." not found` error — check `demo/composer.json` before
assuming the class itself is broken.
