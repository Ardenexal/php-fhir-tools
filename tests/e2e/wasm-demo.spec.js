import { test, expect } from '@playwright/test';

// -------------------------------------------------------------------
// 1. Static asset tests — fast, no WASM required
// -------------------------------------------------------------------

test.describe('Static assets', () => {
    test('PHP bundle JSON files are served correctly', async ({ request }) => {
        const fhirpath = await request.get('/assets/php-bundles/fhirpath-bundle.json');
        expect(fhirpath.ok()).toBeTruthy();
        const body = await fhirpath.json();
        expect(body.component).toBe('FHIRPath');
        expect(body.fileCount).toBeGreaterThan(0);
        expect(body.files).toBeDefined();
        expect(body.files['/fhir/bootstrap.php']).toContain('spl_autoload_register');
    });

    test('Serialization bundle is served correctly', async ({ request }) => {
        const ser = await request.get('/assets/php-bundles/serialization-bundle.json');
        expect(ser.ok()).toBeTruthy();
        const body = await ser.json();
        expect(body.component).toBe('Serialization');
        expect(body.fileCount).toBeGreaterThan(0);
        expect(body.files['/fhir/bootstrap.php']).toBeDefined();
    });

    test('Example FHIR resources are served', async ({ request }) => {
        const examples = [
            'patient-simple.json',
            'patient-complex.json',
            'observation-vital-signs.json',
        ];
        for (const name of examples) {
            const resp = await request.get(`/assets/data/examples/${name}`);
            expect(resp.ok(), `${name} should be served`).toBeTruthy();
            const body = await resp.json();
            expect(body.resourceType).toBeDefined();
        }
    });

    test('JS module files are served', async ({ request }) => {
        const files = [
            '/assets/js/php-wasm/loader.js',
            '/assets/js/php-wasm/fhir-client.js',
            '/assets/js/utils/operation-outcome.js',
        ];
        for (const file of files) {
            const resp = await request.get(file);
            expect(resp.ok(), `${file} should be served`).toBeTruthy();
        }
    });
});

// -------------------------------------------------------------------
// 2. Module loading tests — verifies ES modules parse in the browser
// -------------------------------------------------------------------

test.describe('Module loading', () => {
    test('test harness loads JS modules without errors', async ({ page }) => {
        const errors = [];
        page.on('pageerror', (err) => errors.push(err.message));

        await page.goto('/_test-harness.html');

        // Wait for modules to load
        await expect(page.locator('#status')).toHaveText('Modules loaded', {
            timeout: 10_000,
        });

        // Verify no JS errors
        expect(errors).toEqual([]);

        // Check that test objects are exposed
        const ready = await page.evaluate(() => window.__testReady);
        expect(ready).toBe(true);
    });

    test('PHPWasmLoader class has expected API surface', async ({ page }) => {
        await page.goto('/_test-harness.html');
        await page.waitForFunction(() => window.__testReady);

        const api = await page.evaluate(() => {
            const loader = window.__testLoader;
            return {
                hasInitialize: typeof loader.initialize === 'function',
                hasRunPHP: typeof loader.runPHP === 'function',
                hasIsReady: typeof loader.isReady === 'function',
                hasGetVersion: typeof loader.getVersion === 'function',
                hasReset: typeof loader.reset === 'function',
                isReadyBeforeInit: loader.isReady(),
            };
        });

        expect(api.hasInitialize).toBe(true);
        expect(api.hasRunPHP).toBe(true);
        expect(api.hasIsReady).toBe(true);
        expect(api.hasGetVersion).toBe(true);
        expect(api.hasReset).toBe(true);
        expect(api.isReadyBeforeInit).toBe(false);
    });

    test('FHIRPHPClient class has expected API surface', async ({ page }) => {
        await page.goto('/_test-harness.html');
        await page.waitForFunction(() => window.__testReady);

        const api = await page.evaluate(() => {
            const client = window.__testClient;
            return {
                hasInitialize: typeof client.initialize === 'function',
                hasEvaluateFHIRPath: typeof client.evaluateFHIRPath === 'function',
                hasValidateFHIRPath: typeof client.validateFHIRPath === 'function',
                hasSerialize: typeof client.serialize === 'function',
                hasDeserialize: typeof client.deserialize === 'function',
                hasIsInitialized: typeof client.isInitialized === 'function',
                hasGetPhpVersion: typeof client.getPhpVersion === 'function',
                isInitializedBeforeInit: client.isInitialized(),
            };
        });

        expect(api.hasInitialize).toBe(true);
        expect(api.hasEvaluateFHIRPath).toBe(true);
        expect(api.hasValidateFHIRPath).toBe(true);
        expect(api.hasSerialize).toBe(true);
        expect(api.hasDeserialize).toBe(true);
        expect(api.hasIsInitialized).toBe(true);
        expect(api.hasGetPhpVersion).toBe(true);
        expect(api.isInitializedBeforeInit).toBe(false);
    });
});

// -------------------------------------------------------------------
// 3. PHP bundle content tests — validates bundle structure and files
// -------------------------------------------------------------------

test.describe('PHP bundle content', () => {
    test('FHIRPath bundle contains FHIRPathService', async ({ request }) => {
        const resp = await request.get('/assets/php-bundles/fhirpath-bundle.json');
        const bundle = await resp.json();

        // Must contain the main service file
        const servicePath = '/fhir/Component/FHIRPath/src/Service/FHIRPathService.php';
        expect(bundle.files[servicePath]).toBeDefined();
        expect(bundle.files[servicePath]).toContain('class FHIRPathService');
        expect(bundle.files[servicePath]).toContain('function evaluate');
    });

    test('FHIRPath bundle contains lexer, parser, evaluator', async ({ request }) => {
        const resp = await request.get('/assets/php-bundles/fhirpath-bundle.json');
        const bundle = await resp.json();
        const paths = Object.keys(bundle.files);

        expect(paths.some(p => p.includes('Lexer'))).toBe(true);
        expect(paths.some(p => p.includes('Parser'))).toBe(true);
        expect(paths.some(p => p.includes('Evaluator'))).toBe(true);
        expect(paths.some(p => p.includes('Collection.php'))).toBe(true);
    });

    test('Bootstrap autoloader maps correct namespaces', async ({ request }) => {
        const resp = await request.get('/assets/php-bundles/fhirpath-bundle.json');
        const bundle = await resp.json();
        const bootstrap = bundle.files['/fhir/bootstrap.php'];

        expect(bootstrap).toContain("Ardenexal\\\\FHIRTools\\\\Component\\\\FHIRPath\\\\");
        expect(bootstrap).toContain('/fhir/Component/FHIRPath/src/');
        expect(bootstrap).toContain('spl_autoload_register');
    });

    test('Serialization bundle contains validator classes', async ({ request }) => {
        const resp = await request.get('/assets/php-bundles/serialization-bundle.json');
        const bundle = await resp.json();
        const paths = Object.keys(bundle.files);

        expect(paths.some(p => p.includes('Validator'))).toBe(true);
        expect(paths.some(p => p.includes('Context'))).toBe(true);
        expect(paths.some(p => p.includes('Metadata'))).toBe(true);
        expect(paths.some(p => p.includes('FHIRTypeResolver'))).toBe(true);
    });

    test('Bundle fileCount matches actual file count', async ({ request }) => {
        const resp = await request.get('/assets/php-bundles/fhirpath-bundle.json');
        const bundle = await resp.json();
        expect(bundle.fileCount).toBe(Object.keys(bundle.files).length);
    });
});

// -------------------------------------------------------------------
// 4. WASM runtime tests — full end-to-end with real php-wasm
//    These are slower (~30s for WASM download) and require CDN access.
//    They will be skipped when cdn.jsdelivr.net is unreachable.
// -------------------------------------------------------------------

test.describe('PHP-WASM runtime', () => {
    // These tests download ~8MB WASM binary from CDN
    test.slow();

    // Skip entire suite if CDN is unreachable (e.g. sandboxed CI)
    test.beforeAll(async ({ request }) => {
        try {
            const resp = await request.head('https://cdn.jsdelivr.net/npm/php-wasm/PhpWeb.mjs');
            if (!resp.ok()) {
                test.skip(true, 'CDN cdn.jsdelivr.net is unreachable — skipping WASM runtime tests');
            }
        } catch {
            test.skip(true, 'CDN cdn.jsdelivr.net is unreachable — skipping WASM runtime tests');
        }
    });

    test('initializes PHP runtime and loads FHIRPath bundle', async ({ page }) => {
        await page.goto('/_test-harness.html');
        await page.waitForFunction(() => window.__testReady);

        const result = await page.evaluate(async () => {
            const progressStages = [];
            try {
                await window.__testClient.initialize((p) => {
                    progressStages.push(p.stage);
                });
                return {
                    success: true,
                    isReady: window.__testClient.isInitialized(),
                    version: window.__testClient.getPhpVersion(),
                    stages: progressStages,
                };
            } catch (e) {
                return {
                    success: false,
                    error: e?.message || e?.issue?.[0]?.details?.text || String(e),
                    stages: progressStages,
                };
            }
        });

        expect(result.success, `Init failed: ${result.error}`).toBe(true);
        expect(result.isReady).toBe(true);
        expect(result.stages).toContain('complete');
    });

    test('evaluates simple FHIRPath expression: Patient.name', async ({ page }) => {
        await page.goto('/_test-harness.html');
        await page.waitForFunction(() => window.__testReady);

        const result = await page.evaluate(async () => {
            await window.__testClient.initialize();

            const patient = {
                resourceType: 'Patient',
                id: 'test-1',
                name: [{ family: 'Smith', given: ['John'] }],
            };

            return await window.__testClient.evaluateFHIRPath(patient, 'Patient.name.family');
        });

        expect(result.success).toBe(true);
        expect(result.value).toContain('Smith');
    });

    test('evaluates FHIRPath expression: Patient.id', async ({ page }) => {
        await page.goto('/_test-harness.html');
        await page.waitForFunction(() => window.__testReady);

        const result = await page.evaluate(async () => {
            await window.__testClient.initialize();

            const patient = {
                resourceType: 'Patient',
                id: 'example-123',
                active: true,
            };

            return await window.__testClient.evaluateFHIRPath(patient, 'Patient.id');
        });

        expect(result.success).toBe(true);
        expect(result.value).toContain('example-123');
    });

    test('handles invalid FHIRPath expression gracefully', async ({ page }) => {
        await page.goto('/_test-harness.html');
        await page.waitForFunction(() => window.__testReady);

        const result = await page.evaluate(async () => {
            try {
                await window.__testClient.initialize();
                const patient = { resourceType: 'Patient', id: '1' };
                return await window.__testClient.evaluateFHIRPath(patient, '!!!invalid!!!');
            } catch (e) {
                return { success: false, error: String(e) };
            }
        });

        // Should return an error, not throw
        expect(result.success === false || result.resourceType === 'OperationOutcome').toBeTruthy();
    });

    test('serialization validates a FHIR resource', async ({ page }) => {
        await page.goto('/_test-harness.html');
        await page.waitForFunction(() => window.__testReady);

        const result = await page.evaluate(async () => {
            await window.__testClient.initialize(null, ['fhirpath', 'serialization']);

            const patient = {
                resourceType: 'Patient',
                id: 'test-1',
                active: true,
            };

            return await window.__testClient.serialize(patient);
        });

        expect(result.success).toBe(true);
        expect(result.resourceType).toBe('Patient');
    });

    test('serialization rejects resource without resourceType', async ({ page }) => {
        await page.goto('/_test-harness.html');
        await page.waitForFunction(() => window.__testReady);

        const result = await page.evaluate(async () => {
            try {
                await window.__testClient.initialize(null, ['fhirpath', 'serialization']);
                return await window.__testClient.serialize({ id: 'no-type' });
            } catch (e) {
                return { success: false, resourceType: 'OperationOutcome' };
            }
        });

        // Should be an error (OperationOutcome)
        expect(
            result.success === false || result.resourceType === 'OperationOutcome'
        ).toBeTruthy();
    });
});
