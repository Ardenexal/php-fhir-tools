# Implementation Plan: WASM Demo Site for PHP FHIR Tools

## Overview

Replace the mock php-wasm layer on the existing GitHub Pages demo site with real
WebAssembly-powered PHP execution. Visitors will be able to evaluate FHIRPath
expressions and perform FHIR serialization/validation entirely in the browser,
using the actual PHP component code running via php-wasm.

## Starting Point

Branch `copilot/create-github-page-for-symfony-bundle` already has:
- Full Jekyll site scaffold (layouts, CSS, service worker, deploy workflow)
- 3 demo page UIs (FHIRPath, Serialization, Models) with complete HTML/JS
- 6 sample FHIR resources in `docs/assets/data/examples/`
- Mock `loader.js` (PhpWebMock) and `fhir-client.js` (naive dot-path evaluation)
- OperationOutcome utilities, cache manager

The branch is ~46 commits behind `main` (missing FHIRPath service layer, caching,
compiled expressions, etc.).

## Technology

- **php-wasm**: [seanmorris/php-wasm](https://github.com/seanmorris/php-wasm) via
  CDN (`https://cdn.jsdelivr.net/npm/php-wasm/PhpWeb.mjs`). Ships PHP 8.2 which is
  compatible with our FHIRPath component (uses `readonly class` from 8.2,
  `array_is_list` from 8.1 — no 8.3-only features).
- **PHP file delivery**: JSON bundle (Option B) — a build script packages all PHP
  source files into a single `fhirpath-bundle.json` / `serialization-bundle.json`
  that JS loads and writes to the WASM virtual filesystem.

---

## Phase 1: Branch Setup & Merge

**Goal**: Get a clean working branch with both the docs site and latest main code.

### Steps
1. Create branch `claude/plan-wasm-demo-yentV` from
   `origin/copilot/create-github-page-for-symfony-bundle`
2. Merge `origin/main` into it to pick up the 46 missing commits
3. Resolve any merge conflicts (likely in `config/` files)
4. Verify both the docs site structure and PHP source code are intact

### Files touched
- Git operations only (merge)

---

## Phase 2: Build Script for PHP Bundle JSON

**Goal**: Create a script that packages PHP component sources into JSON bundles
that can be loaded by the browser and written to php-wasm's virtual filesystem.

### Steps
1. Create `scripts/build-wasm-bundles.php` — reads all PHP files from a component
   directory and outputs a JSON file with `{ "/vfs/path": "file contents", ... }`
2. Generate two bundles:
   - `docs/assets/php-bundles/fhirpath-bundle.json` — all 87 FHIRPath files +
     bootstrap autoloader
   - `docs/assets/php-bundles/serialization-bundle.json` — standalone serialization
     files (validators, metadata, context — NOT the Symfony-coupled normalizers)
3. Create `docs/assets/php-bundles/bootstrap.php` — a minimal PSR-4 autoloader
   that maps `Ardenexal\FHIRTools\Component\FHIRPath\` →
   `/fhir/Component/FHIRPath/src/` and similar
4. Add a `composer run build-wasm-bundles` script entry

### Files created
- `scripts/build-wasm-bundles.php`
- `docs/assets/php-bundles/fhirpath-bundle.json` (generated)
- `docs/assets/php-bundles/serialization-bundle.json` (generated)

### Files modified
- `composer.json` (add script entry)

---

## Phase 3: Replace Mock Loader with Real php-wasm

**Goal**: Replace `PhpWebMock` with actual `PhpWeb` from seanmorris/php-wasm.

### Steps
1. Rewrite `docs/assets/js/php-wasm/loader.js`:
   - Import `PhpWeb` from CDN ESM
   - On `initialize()`: create `PhpWeb` instance, wait for `'ready'` event
   - Load the JSON bundle(s) via `fetch()`, iterate entries, write each file to
     the WASM VFS using `php.writeFile(path, contents)`
   - Run `bootstrap.php` to register the autoloader
   - Expose `runPHP(code)` that calls `php.run(code)` and captures stdout
2. Adapt the return interface: seanmorris `php.run()` returns stdout as a string.
   Wrap it to match what `fhir-client.js` expects (or update fhir-client.js).
3. Keep the existing progress callback mechanism for the UI loading indicators.

### Files modified
- `docs/assets/js/php-wasm/loader.js` (rewrite)

---

## Phase 4: Wire Up Real FHIRPath Evaluation

**Goal**: Replace mock FHIRPath evaluation with the real PHP FHIRPathService.

### Steps
1. Update `docs/assets/js/php-wasm/fhir-client.js`:
   - `evaluateFHIRPath()` sends PHP code that:
     ```php
     require_once '/fhir/bootstrap.php';
     $service = new \Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService();
     $resource = json_decode($resourceJson, true);
     $results = $service->evaluate($expression, $resource);
     echo json_encode(['success' => true, 'result' => $results]);
     ```
   - Handle PHP exceptions → return as OperationOutcome errors
   - Sanitize user input (expression, resource JSON) properly for PHP embedding
     (use base64 encoding to avoid escaping issues)
2. The existing FHIRPath demo page (`docs/pages/demos/fhirpath.html`) should work
   as-is since it already calls `fhirClient.evaluateFHIRPath()` — we're just
   replacing the implementation behind the interface.
3. Test with all 6 sample resources and various FHIRPath expressions.

### Files modified
- `docs/assets/js/php-wasm/fhir-client.js` (update evaluateFHIRPath method)

---

## Phase 5: Wire Up Serialization Demo (Standalone Subset)

**Goal**: Add real FHIR validation and JSON structure analysis to the
serialization demo, using the Symfony-decoupled parts of the component.

### Approach
The serialization component's normalizers are tightly coupled to Symfony
Serializer (they implement `NormalizerInterface`/`DenormalizerInterface`). However,
the following are fully standalone:
- `FHIRValidator` — business rule validation (no Symfony deps)
- `FHIRSchemaValidator` — XML schema validation (needs DOMDocument/libxml)
- `FHIRMetadataExtractor` — attribute reflection for FHIR type detection
- `FHIRTypeResolver` — polymorphic type resolution
- `FHIRSerializationContext` — configuration value object

### Steps
1. Write a standalone `fhir_validate.php` entry point that:
   - Uses `FHIRValidator` to validate FHIR resource structure
   - Uses `FHIRTypeResolver` to identify and validate types
   - Falls back to JSON schema-based validation for structure checks
   - Returns validation results as OperationOutcome
2. Write a `fhir_roundtrip.php` entry point that:
   - Takes JSON input, parses it, re-serializes with `json_encode(JSON_PRETTY_PRINT)`
   - Performs structural validation during the round-trip
   - For XML conversion: use PHP's built-in `DOMDocument` (if libxml extension is
     available in php-wasm) to create a basic FHIR XML representation
3. Update `fhir-client.js` `serialize()` and `deserialize()` methods to use
   the real PHP entry points
4. Include the standalone serialization files in the bundle

### Files modified
- `docs/assets/js/php-wasm/fhir-client.js` (update serialize/deserialize methods)
- `scripts/build-wasm-bundles.php` (add serialization files to bundle)

### Files created
- PHP entry point logic embedded in fhir-client.js (or as separate bundled files)

---

## Phase 6: Polish & Error Handling

**Goal**: Production-quality UX.

### Steps
1. **Loading states**: Show download progress for the ~8-12MB WASM binary.
   The existing demo pages have progress UI — connect it to real progress events.
2. **Error display**: FHIRPath parse errors and evaluation errors should display
   as FHIR OperationOutcome resources (utilities already exist).
3. **Expression history**: Persist to localStorage (basic implementation may
   already exist in the demo page).
4. **Fallback**: Detect WebAssembly support. Show a clear message for unsupported
   browsers with a link to supported browser versions.
5. **Caching**: The existing service worker (`docs/service-worker.js`) should
   cache the WASM binary and PHP bundles for fast subsequent loads.
6. Update the service worker cache list to include the new bundle files.

### Files modified
- `docs/assets/js/php-wasm/loader.js` (progress events)
- `docs/service-worker.js` (cache new assets)
- `docs/pages/demos/fhirpath.html` (minor UX tweaks if needed)
- `docs/pages/demos/serialization.html` (minor UX tweaks if needed)

---

## Phase 7: CI Integration

**Goal**: Keep bundles in sync with PHP source changes.

### Steps
1. Add a GitHub Actions step to the deploy workflow that runs
   `composer run build-wasm-bundles` before Jekyll builds the site
2. This ensures the JSON bundles always reflect the latest PHP source code
3. Optionally add a check in PR CI that verifies bundles are up-to-date

### Files modified
- `.github/workflows/` (deploy workflow, if one exists on the branch)

---

## Risk Assessment

| Risk | Mitigation |
|------|------------|
| php-wasm PhpWeb API differs from what we expect | Read actual API docs carefully; adapter pattern in loader.js |
| 8-12MB WASM download is too slow | Service worker caching; lazy load on first interaction; progress bar |
| FHIRPath component uses PHP features not in php-wasm 8.2 | Verified: only uses `readonly class` (8.2) and `array_is_list` (8.1) — safe |
| Serialization normalizers need Symfony | Only bundle standalone parts (validators, metadata, context) |
| Virtual filesystem file writing is slow for 87 files | JSON bundle = one fetch; batch writeFile calls; consider preload image later |
| XSS via user PHP input | Use base64 encoding for user input passed to PHP; never interpolate raw strings |

---

## Definition of Done

- [ ] FHIRPath playground evaluates real expressions using actual PHP FHIRPath component
- [ ] Serialization demo validates FHIR resources using actual PHP validators
- [ ] All 6 sample resources work correctly
- [ ] WASM binary and PHP bundles are cached by service worker
- [ ] Unsupported browsers see a clear fallback message
- [ ] Build script keeps bundles in sync with PHP source
- [ ] All changes committed and pushed to `claude/plan-wasm-demo-yentV`
