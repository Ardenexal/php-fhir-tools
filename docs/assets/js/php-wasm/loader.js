/**
 * PHP-WASM Loader Module
 * 
 * Handles initialization and management of the PHP WebAssembly runtime.
 * Uses singleton pattern to ensure single instance across the application.
 * 
 * @module php-wasm/loader
 * @author PHP FHIRTools Team
 */

/**
 * PHPWasmLoader - Manages PHP WebAssembly runtime initialization and lifecycle
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
        
        PHPWasmLoader.instance = this;
    }

    /**
     * Initialize the PHP WebAssembly runtime
     * 
     * @param {Function} progressCallback - Optional callback for progress updates
     * @returns {Promise<void>}
     * @throws {Error} If initialization fails
     */
    async initialize(progressCallback = null) {
        // Return existing promise if already initializing
        if (this.isInitializing && this.initPromise) {
            return this.initPromise;
        }

        // Return immediately if already initialized
        if (this.isInitialized && this.php) {
            return Promise.resolve();
        }

        this.isInitializing = true;
        
        this.initPromise = (async () => {
            try {
                if (progressCallback) {
                    progressCallback({ stage: 'loading', progress: 0, message: 'Loading PHP WebAssembly module...' });
                }

                // Check WebAssembly support
                if (typeof WebAssembly === 'undefined') {
                    throw new Error('WebAssembly is not supported in this browser. Please use a modern browser (Chrome 91+, Firefox 89+, Safari 15+, Edge 91+).');
                }

                if (progressCallback) {
                    progressCallback({ stage: 'downloading', progress: 25, message: 'Downloading PHP runtime (~8-12 MB)...' });
                }

                // Dynamic import of php-wasm/web
                // Note: In production, this would load from npm package
                // For now, we'll create a lightweight mock for development
                const PhpWeb = await this.loadPhpWasm();
                
                if (progressCallback) {
                    progressCallback({ stage: 'initializing', progress: 50, message: 'Initializing PHP environment...' });
                }

                this.php = new PhpWeb();
                
                if (progressCallback) {
                    progressCallback({ stage: 'configuring', progress: 75, message: 'Configuring PHP settings...' });
                }

                // Configure PHP environment
                await this.configureEnvironment();
                
                if (progressCallback) {
                    progressCallback({ stage: 'complete', progress: 100, message: 'PHP runtime ready!' });
                }

                this.isInitialized = true;
                this.isInitializing = false;
                
            } catch (error) {
                this.isInitializing = false;
                this.isInitialized = false;
                console.error('Failed to initialize PHP-WASM:', error);
                throw new Error(`PHP-WASM initialization failed: ${error.message}`);
            }
        })();

        return this.initPromise;
    }

    /**
     * Load the PHP WebAssembly library
     * 
     * @returns {Promise<Object>} PHP WebAssembly class
     * @private
     */
    async loadPhpWasm() {
        // In production, this would be:
        // const { PhpWeb } = await import('https://cdn.jsdelivr.net/npm/@php-wasm/web@latest/index.js');
        
        // For development/demo, create a mock implementation
        // This will be replaced with actual php-wasm/web in production
        return class PhpWebMock {
            constructor() {
                this.version = '8.2.0-mock';
            }

            async run(code) {
                // Mock implementation for development
                console.log('[PHP-WASM Mock] Executing PHP code:', code.substring(0, 100) + '...');
                
                // Simulate execution time
                await new Promise(resolve => setTimeout(resolve, 100));
                
                // Return mock result
                return {
                    exitCode: 0,
                    stdout: 'Mock PHP execution result',
                    stderr: ''
                };
            }

            async eval(code) {
                return this.run(code);
            }
        };
    }

    /**
     * Configure PHP environment settings
     * 
     * @private
     */
    async configureEnvironment() {
        if (!this.php) {
            throw new Error('PHP runtime not initialized');
        }

        // Set PHP configuration
        // In production with real php-wasm:
        // await this.php.run('<?php ini_set("memory_limit", "128M"); ?>');
        // await this.php.run('<?php error_reporting(E_ALL); ?>');
        
        console.log('[PHP-WASM Loader] Environment configured');
    }

    /**
     * Execute PHP code
     * 
     * @param {string} code - PHP code to execute (including <?php tags)
     * @returns {Promise<Object>} Execution result with exitCode, stdout, stderr
     * @throws {Error} If not initialized or execution fails
     */
    async runPHP(code) {
        if (!this.isInitialized || !this.php) {
            throw new Error('PHP runtime not initialized. Call initialize() first.');
        }

        try {
            const result = await this.php.run(code);
            return result;
        } catch (error) {
            console.error('PHP execution error:', error);
            throw new Error(`PHP execution failed: ${error.message}`);
        }
    }

    /**
     * Check if PHP runtime is initialized
     * 
     * @returns {boolean}
     */
    isReady() {
        return this.isInitialized && this.php !== null;
    }

    /**
     * Get PHP version
     * 
     * @returns {string|null}
     */
    getVersion() {
        return this.php ? this.php.version : null;
    }

    /**
     * Reset the PHP runtime (for cleanup or re-initialization)
     */
    reset() {
        this.php = null;
        this.isInitialized = false;
        this.isInitializing = false;
        this.initPromise = null;
    }
}

// Export singleton instance
export const phpWasmLoader = new PHPWasmLoader();
