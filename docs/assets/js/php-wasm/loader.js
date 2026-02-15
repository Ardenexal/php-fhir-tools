/**
 * PHP-WASM Loader Module
 *
 * Initializes the real php-wasm runtime (seanmorris/php-wasm),
 * loads PHP source bundles into the virtual filesystem, and
 * provides a runPHP() method that captures stdout.
 *
 * @module php-wasm/loader
 */

const PHP_WASM_CDN = 'https://cdn.jsdelivr.net/npm/php-wasm/PhpWeb.mjs';

/**
 * PHPWasmLoader - Manages the real PHP WebAssembly runtime.
 */
export class PHPWasmLoader {
    constructor() {
        if (PHPWasmLoader.instance) {
            return PHPWasmLoader.instance;
        }

        this.php = null;
        this.isInitialized = false;
        this.isInitializing = false;
        this.initPromise = null;
        this._loadedBundles = new Set();

        PHPWasmLoader.instance = this;
    }

    /**
     * Initialize the PHP WebAssembly runtime and load the specified bundles.
     *
     * @param {Function} progressCallback - Optional callback: ({stage, progress, message}) => void
     * @param {string[]} bundles - Bundle names to load (e.g. ['fhirpath', 'serialization'])
     * @returns {Promise<void>}
     */
    async initialize(progressCallback = null, bundles = ['fhirpath']) {
        if (this.isInitializing && this.initPromise) {
            return this.initPromise;
        }

        if (this.isInitialized && this.php) {
            // Already initialized — just load any additional bundles
            for (const bundle of bundles) {
                if (!this._loadedBundles.has(bundle)) {
                    await this._loadBundle(bundle, progressCallback);
                }
            }
            return;
        }

        this.isInitializing = true;

        this.initPromise = (async () => {
            try {
                this._progress(progressCallback, 'loading', 0, 'Checking WebAssembly support...');

                if (typeof WebAssembly === 'undefined') {
                    throw new Error(
                        'WebAssembly is not supported in this browser. '
                        + 'Please use a modern browser (Chrome 91+, Firefox 89+, Safari 15+, Edge 91+).'
                    );
                }

                // 1. Import PhpWeb from CDN
                this._progress(progressCallback, 'downloading', 10, 'Downloading PHP runtime (~8 MB)...');
                const { PhpWeb } = await import(PHP_WASM_CDN);

                // 2. Create instance and wait for ready
                this._progress(progressCallback, 'initializing', 30, 'Starting PHP runtime...');
                this.php = new PhpWeb();

                await new Promise((resolve, reject) => {
                    const timeout = setTimeout(() => reject(new Error('PHP runtime startup timed out')), 30000);
                    this.php.addEventListener('ready', () => {
                        clearTimeout(timeout);
                        resolve();
                    });
                    this.php.addEventListener('error', (e) => {
                        clearTimeout(timeout);
                        reject(new Error(e.detail || 'PHP runtime error'));
                    });
                });

                // 3. Configure PHP environment
                this._progress(progressCallback, 'configuring', 50, 'Configuring PHP environment...');
                await this._runSilent(`<?php
                    error_reporting(E_ALL);
                    ini_set('display_errors', '0');
                ?>`);

                // 4. Load requested bundles
                const bundleProgressStart = 55;
                const bundleProgressRange = 40;
                for (let i = 0; i < bundles.length; i++) {
                    const pct = bundleProgressStart + Math.round((i / bundles.length) * bundleProgressRange);
                    this._progress(progressCallback, 'bundles', pct, `Loading ${bundles[i]} component...`);
                    await this._loadBundle(bundles[i], progressCallback);
                }

                // 5. Done
                this._progress(progressCallback, 'complete', 100, 'PHP runtime ready!');
                this.isInitialized = true;
                this.isInitializing = false;

            } catch (error) {
                this.isInitializing = false;
                this.isInitialized = false;
                this.php = null;
                console.error('Failed to initialize PHP-WASM:', error);
                throw new Error(`PHP-WASM initialization failed: ${error.message}`);
            }
        })();

        return this.initPromise;
    }

    /**
     * Execute PHP code and return captured stdout.
     *
     * @param {string} code - PHP code including <?php tags
     * @returns {Promise<{exitCode: number, stdout: string, stderr: string}>}
     */
    async runPHP(code) {
        if (!this.isInitialized || !this.php) {
            throw new Error('PHP runtime not initialized. Call initialize() first.');
        }

        let stdout = '';
        let stderr = '';

        const onOutput = (e) => { stdout += e.detail; };
        const onError = (e) => { stderr += e.detail; };

        this.php.addEventListener('output', onOutput);
        this.php.addEventListener('error', onError);

        try {
            const exitCode = await this.php.run(code);
            return { exitCode, stdout, stderr };
        } catch (error) {
            console.error('PHP execution error:', error);
            throw new Error(`PHP execution failed: ${error.message}`);
        } finally {
            this.php.removeEventListener('output', onOutput);
            this.php.removeEventListener('error', onError);
        }
    }

    /**
     * Check if PHP runtime is ready.
     * @returns {boolean}
     */
    isReady() {
        return this.isInitialized && this.php !== null;
    }

    /**
     * Get PHP version string.
     * @returns {string|null}
     */
    getVersion() {
        return this.php ? (this.php.version || null) : null;
    }

    /**
     * Reset the PHP runtime.
     */
    reset() {
        this.php = null;
        this.isInitialized = false;
        this.isInitializing = false;
        this.initPromise = null;
        this._loadedBundles = new Set();
    }

    // --- Private helpers ---

    /**
     * Run PHP code silently (discard output).
     * @private
     */
    async _runSilent(code) {
        const onOutput = () => {};
        const onError = () => {};
        this.php.addEventListener('output', onOutput);
        this.php.addEventListener('error', onError);
        try {
            await this.php.run(code);
        } finally {
            this.php.removeEventListener('output', onOutput);
            this.php.removeEventListener('error', onError);
        }
    }

    /**
     * Load a PHP source bundle into the virtual filesystem.
     *
     * Fetches the JSON bundle, writes each file to the VFS using
     * file_put_contents(), then runs the bootstrap autoloader.
     *
     * @param {string} name - Bundle name (e.g. 'fhirpath')
     * @param {Function} progressCallback
     * @private
     */
    async _loadBundle(name, progressCallback) {
        if (this._loadedBundles.has(name)) {
            return;
        }

        const basePath = this._resolveBasePath();
        const url = `${basePath}/assets/php-bundles/${name}-bundle.json`;

        let bundle;
        try {
            const resp = await fetch(url);
            if (!resp.ok) {
                throw new Error(`HTTP ${resp.status}: ${resp.statusText}`);
            }
            bundle = await resp.json();
        } catch (error) {
            throw new Error(`Failed to fetch ${name} bundle: ${error.message}`);
        }

        const files = bundle.files || {};
        const paths = Object.keys(files);

        // Batch files into chunks to avoid excessively large PHP strings.
        // Each chunk writes multiple files in one php.run() call.
        const CHUNK_SIZE = 20;
        for (let i = 0; i < paths.length; i += CHUNK_SIZE) {
            const chunk = paths.slice(i, i + CHUNK_SIZE);
            let phpCode = '<?php\n';

            for (const vfsPath of chunk) {
                const dir = vfsPath.substring(0, vfsPath.lastIndexOf('/'));
                const contentB64 = btoa(unescape(encodeURIComponent(files[vfsPath])));

                phpCode += `@mkdir('${dir}', 0755, true);\n`;
                phpCode += `file_put_contents('${vfsPath}', base64_decode('${contentB64}'));\n`;
            }

            await this._runSilent(phpCode);
        }

        // Run the bootstrap autoloader
        await this._runSilent(`<?php require_once '/fhir/bootstrap.php'; ?>`);

        this._loadedBundles.add(name);
    }

    /**
     * Resolve the base path for bundle URLs.
     * Handles both local dev and GitHub Pages deployments.
     * @private
     */
    _resolveBasePath() {
        // Check for a <base> tag or data attribute
        const base = document.querySelector('base');
        if (base && base.href) {
            return base.href.replace(/\/$/, '');
        }

        // Infer from current script location (assets/js/php-wasm/loader.js → assets/)
        const scripts = document.querySelectorAll('script[src*="php-wasm/loader"]');
        if (scripts.length > 0) {
            const src = scripts[0].src;
            const idx = src.indexOf('/assets/');
            if (idx !== -1) {
                return src.substring(0, idx);
            }
        }

        // Fallback: use the site baseurl from Jekyll config
        return '/php-fhir-tools';
    }

    /**
     * Send a progress update to the callback.
     * @private
     */
    _progress(callback, stage, progress, message) {
        if (callback) {
            callback({ stage, progress, message });
        }
    }
}

// Singleton instance
export const phpWasmLoader = new PHPWasmLoader();
