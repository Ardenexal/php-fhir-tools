# GitHub Pages Branch Analysis

**Branch:** `copilot/create-github-page-for-symfony-bundle` (PR #26)
**Date:** 2026-02-15
**Merge base:** `d85b420f`

## Branch Divergence Summary

| Direction | Commits |
|---|---|
| Unique to GH Pages branch | 21 commits |
| On `main` but missing from branch | 46 commits |

The branch is **significantly behind main** and needs a merge before any further work.

---

## Missing from GH Pages Branch (on main)

### FHIRPath Component (1,887 insertions)
- `ExpressionCacheInterface` and `InMemoryExpressionCache` - expression caching system
- `CompiledExpression` and `FHIRPathService` - full service layer
- Comprehensive test suite (5+ new test classes, 900+ lines)
- README significantly expanded

### CodeGeneration Component (major refactoring)
- Simplified `FHIRModelGeneratorCommand` (removed `--models-component` flag)
- New `GeneratedClassInfo` DTO for class metadata
- Improved `PackageLoader` with offline support
- Removed legacy `VersionIsolationManager` (385 lines)

### FHIRBundle
- New `FHIRPathEvaluateCommand` and `FHIRPathValidateCommand`
- `FHIRValidator` service registration
- Expanded `services.yaml` (95 services)

### Test Improvements
- 7 failing integration tests resolved
- Unit test fixes across multiple components
- Code generation test quality improvements
- Several legacy test classes removed

### Infrastructure
- `CLAUDE.md` added (project guidance)
- `.claude/skills/` directory (8 skill files)
- Updated CI workflow path filters
- Full model regeneration (R4/R4B/R5)

### Expected Merge Conflicts
- `config/reference.php` - type annotation ordering differences (cosmetic but numerous)
- Most other changes merge cleanly (GH Pages branch primarily adds `docs/` files)

---

## WASM Implementation Assessment

### Working Components

| Component | File | Status |
|---|---|---|
| Service Worker | `docs/service-worker.js` | Fully implemented - offline caching works |
| OperationOutcome Utils | `docs/assets/js/utils/operation-outcome.js` | Production-ready error handling |
| Cache Manager | `docs/assets/js/cache-manager.js` | Functional SW registration and caching |
| Demo Page UIs | `docs/pages/demos/*.html` | Styled, responsive, ready for backends |
| Example FHIR Resources | `docs/assets/data/examples/*.json` | 6 valid R4 resources |

### Scaffolded but Non-Functional (Mock Implementations)

#### PHP-WASM Loader (`docs/assets/js/php-wasm/loader.js`)
- Creates `PhpWebMock` class instead of importing `@php-wasm/web`
- Comments state: *"This will be replaced with actual php-wasm/web in production"*
- Progress callbacks and lifecycle management are implemented but wrap mock runtime
- `runPHP()` returns hardcoded success responses

#### FHIR Client (`docs/assets/js/php-wasm/fhir-client.js`)
- `serialize()` - validates `resourceType` existence only, returns mock success
- `deserialize()` - echoes input back, no actual FHIR class instantiation
- `evaluateFHIRPath()` - only handles basic dot-notation (`Patient.name`), no real FHIRPath functions
- FHIRPath demo page explicitly states: *"This is a placeholder implementation. Full FHIRPath evaluation with php-wasm coming soon!"*

#### Interactive Demo Pages
- Serialization demo: UI functional, calls mock backend
- FHIRPath demo: UI functional, naive path parsing only
- Model Explorer demo: scaffolded with placeholder data

### Completely Missing

1. **No `.wasm` binary** - no PHP runtime is downloaded or executed in the browser
2. **No PHP FHIR classes bundled** - `Ardenexal\FHIRTools\*` namespaces not available in WASM
3. **No virtual filesystem** - no PHP autoloading setup for browser runtime
4. **No real FHIRPath engine** - the FHIRPath component isn't ported to WASM
5. **No FHIR StructureDefinition validation** in browser
6. **No terminology service** - `tx-client.js` referenced but not implemented
7. **No WASM file hosting/CDN strategy** (8-12MB download management)

---

## Documentation Issues

### Stale Documentation
- Component guides (`docs/component-guides/`) predate FHIRPath service layer and caching additions
- Architecture docs reference `--models-component` flag (removed on main)
- API reference doesn't reflect new `FHIRPathService`, `CompiledExpression`, or cache interfaces

### Missing Documentation
- `CLAUDE.md` not present on branch (added on main after divergence)
- No docs for new bundle commands (`FHIRPathEvaluateCommand`, `FHIRPathValidateCommand`)
- No docs for `GeneratedClassInfo` DTO or simplified codegen workflow

### Documentation-Reality Mismatch
- `PHP_WASM_RESEARCH.md` (412 lines) and 5 planning documents describe php-wasm as the chosen, functional approach
- Actual implementation is entirely mocked
- No clear indication in user-facing docs that WASM features are non-functional

---

## Recommendations

### 1. Merge main into branch (Priority: HIGH)
Bring in 46 missing commits. Resolve `config/reference.php` type annotation conflicts.

### 2. Update component documentation (Priority: HIGH)
- Refresh FHIRPath docs with service layer, caching, and new commands
- Update codegen docs to reflect simplified command and new DTOs
- Add documentation for new bundle commands

### 3. WASM Strategy Decision (Priority: MEDIUM)
Choose one approach:

**Option A - Implement Real WASM**: Replace mocks with `@php-wasm/web`, bundle PHP classes, set up virtual filesystem. High effort but delivers on the documented vision.

**Option B - Remove WASM Claims**: Make demos server-side or purely static. Honest about current state.

**Option C - Label as Coming Soon**: Keep demo UIs with clear "preview/coming soon" labels. Lowest effort, transparent to users.

### 4. Align deployment workflow (Priority: LOW)
Ensure `deploy-pages.yml` is compatible with CI changes made on main.
