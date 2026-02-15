# PHP-WASM Integration Research

## Overview

Research and implementation plan for integrating PHP-WASM into the GitHub Pages site to enable client-side PHP execution for FHIR operations.

## Available Libraries

### 1. php-wasm/web (Recommended)
- **Repository**: https://github.com/php-wasm/php-wasm-web
- **Features**:
  - Full PHP 8.x support in WebAssembly
  - Browser-native execution
  - No server required
  - Complete PHP standard library
  - Extension support
- **Size**: ~8-12MB WASM file
- **Browser Support**: Modern browsers with WebAssembly
- **License**: MIT

### 2. WordPress Playground (wmde/php-wasm)
- **Repository**: https://github.com/WordPress/wordpress-playground
- **Features**:
  - PHP 8.0+ compiled to WASM
  - Virtual filesystem
  - Network emulation
- **Use Case**: Primarily for WordPress, but can be adapted
- **Size**: ~10-15MB
- **License**: GPL

### 3. oraoto/pib (PHP in Browser)
- **Repository**: https://github.com/oraoto/pib
- **Features**:
  - PHP 7.4 WASM build
  - Lightweight
- **Status**: Less actively maintained
- **Size**: ~5-8MB

## Recommended Approach: php-wasm/web

### Implementation Strategy

1. **CDN Delivery**
   - Host WASM files on GitHub Pages or CDN
   - Lazy load on first interaction
   - Cache WASM module in browser

2. **PHP FHIRTools Integration**
   - Bundle required PHP classes
   - Use virtual filesystem for autoloading
   - Initialize PHP environment with dependencies

3. **API Wrapper**
   - Create JavaScript wrapper for PHP calls
   - Handle async operations
   - Manage WASM lifecycle

## Implementation Plan

### Phase 5.2: php-wasm Loader Module

**File**: `docs/assets/js/php-wasm/loader.js`

```javascript
// PHP-WASM loader and initialization
export class PHPWasmLoader {
    constructor() {
        this.php = null;
        this.initialized = false;
        this.loading = false;
    }

    async initialize(progressCallback) {
        if (this.initialized) return this.php;
        if (this.loading) {
            // Wait for existing initialization
            while (this.loading) {
                await new Promise(resolve => setTimeout(resolve, 100));
            }
            return this.php;
        }

        this.loading = true;
        try {
            // Load PHP WASM
            progressCallback?.({ stage: 'loading', progress: 0 });
            
            // Import php-wasm/web library
            const { PhpWeb } = await import('https://cdn.jsdelivr.net/npm/@php-wasm/web@latest/PhpWeb.mjs');
            
            progressCallback?.({ stage: 'loading', progress: 50 });
            
            // Initialize PHP
            this.php = new PhpWeb();
            await this.php.init();
            
            progressCallback?.({ stage: 'initializing', progress: 75 });
            
            // Set up PHP environment
            await this.setupEnvironment();
            
            progressCallback?.({ stage: 'ready', progress: 100 });
            
            this.initialized = true;
            return this.php;
        } finally {
            this.loading = false;
        }
    }

    async setupEnvironment() {
        // Set up virtual filesystem with PHP FHIRTools classes
        await this.php.run(`<?php
            // Set error reporting
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            
            // Set up autoloader (will be expanded in Phase 5.3)
            spl_autoload_register(function($class) {
                // Placeholder for autoloading FHIR classes
                echo "Autoload: $class\\n";
            });
        ?>`);
    }

    async runPHP(code) {
        if (!this.initialized) {
            throw new Error('PHP WASM not initialized. Call initialize() first.');
        }
        
        return await this.php.run(code);
    }

    async eval(expression) {
        if (!this.initialized) {
            throw new Error('PHP WASM not initialized. Call initialize() first.');
        }
        
        return await this.php.run(`<?php echo ${expression}; ?>`);
    }
}

// Singleton instance
let loaderInstance = null;

export function getPHPWasmLoader() {
    if (!loaderInstance) {
        loaderInstance = new PHPWasmLoader();
    }
    return loaderInstance;
}
```

### Phase 5.3: FHIR PHP Client Wrapper

**File**: `docs/assets/js/php-wasm/fhir-client.js`

```javascript
import { getPHPWasmLoader } from './loader.js';
import { createOperationOutcome, processingError } from '../utils/operation-outcome.js';

export class FHIRPHPClient {
    constructor() {
        this.loader = getPHPWasmLoader();
    }

    async initialize(progressCallback) {
        await this.loader.initialize(progressCallback);
    }

    async serialize(fhirResource, validationMode = 'strict') {
        try {
            const resourceJson = JSON.stringify(fhirResource);
            const escapedJson = resourceJson.replace(/'/g, "\\'");
            
            // Placeholder implementation until PHP classes are bundled
            const result = await this.loader.runPHP(`<?php
                $resourceJson = '${escapedJson}';
                $resource = json_decode($resourceJson, true);
                
                // Validate resource structure
                if (!isset($resource['resourceType'])) {
                    echo json_encode([
                        'error' => 'Missing resourceType',
                        'operationOutcome' => [
                            'resourceType' => 'OperationOutcome',
                            'issue' => [[
                                'severity' => 'error',
                                'code' => 'required',
                                'details' => ['text' => 'Resource must have a resourceType'],
                                'expression' => ['Resource.resourceType']
                            ]]
                        ]
                    ]);
                    exit;
                }
                
                // Placeholder: Echo formatted JSON (will be replaced with actual serialization)
                echo json_encode([
                    'success' => true,
                    'result' => $resource,
                    'message' => 'Serialization placeholder - PHP FHIR classes integration pending'
                ], JSON_PRETTY_PRINT);
            ?>`);
            
            const response = JSON.parse(result);
            
            if (response.error) {
                return response.operationOutcome;
            }
            
            return {
                success: true,
                data: response.result,
                message: response.message
            };
            
        } catch (error) {
            return processingError(
                'PHP execution failed',
                error.message,
                ['Resource']
            );
        }
    }

    async deserialize(fhirJson, validationMode = 'strict') {
        try {
            const escapedJson = fhirJson.replace(/'/g, "\\'");
            
            // Placeholder implementation
            const result = await this.loader.runPHP(`<?php
                $json = '${escapedJson}';
                $data = json_decode($json, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo json_encode([
                        'error' => 'Invalid JSON',
                        'operationOutcome' => [
                            'resourceType' => 'OperationOutcome',
                            'issue' => [[
                                'severity' => 'error',
                                'code' => 'structure',
                                'details' => ['text' => 'Invalid JSON format: ' . json_last_error_msg()]
                            ]]
                        ]
                    ]);
                    exit;
                }
                
                // Placeholder: Echo parsed data (will be replaced with actual deserialization)
                echo json_encode([
                    'success' => true,
                    'result' => $data,
                    'message' => 'Deserialization placeholder - PHP FHIR classes integration pending'
                ], JSON_PRETTY_PRINT);
            ?>`);
            
            const response = JSON.parse(result);
            
            if (response.error) {
                return response.operationOutcome;
            }
            
            return {
                success: true,
                data: response.result,
                message: response.message
            };
            
        } catch (error) {
            return processingError(
                'PHP execution failed',
                error.message,
                []
            );
        }
    }

    async evaluateFHIRPath(resource, expression) {
        try {
            const resourceJson = JSON.stringify(resource);
            const escapedJson = resourceJson.replace(/'/g, "\\'");
            const escapedExpr = expression.replace(/'/g, "\\'");
            
            // Placeholder implementation
            const result = await this.loader.runPHP(`<?php
                $resourceJson = '${escapedJson}';
                $expression = '${escapedExpr}';
                $resource = json_decode($resourceJson, true);
                
                // Placeholder: Basic path evaluation (will be replaced with FHIRPath library)
                echo json_encode([
                    'success' => true,
                    'result' => 'FHIRPath evaluation placeholder',
                    'expression' => $expression,
                    'message' => 'FHIRPath evaluation - PHP implementation pending'
                ], JSON_PRETTY_PRINT);
            ?>`);
            
            const response = JSON.parse(result);
            return response;
            
        } catch (error) {
            return processingError(
                'FHIRPath evaluation failed',
                error.message,
                []
            );
        }
    }
}
```

## Integration Steps

### Step 1: Add php-wasm CDN (Immediate)
- Add script tag to demo pages
- Or use ES6 module imports

### Step 2: Create Loader Module (This Phase)
- Implement PHPWasmLoader class
- Handle initialization and caching
- Progress callbacks for UI

### Step 3: Create FHIR Client Wrapper (This Phase)
- FHIRPHPClient class
- Serialize/deserialize methods
- FHIRPath evaluation

### Step 4: Update Demo Pages (This Phase)
- Replace placeholder functions
- Add loading UI
- Wire up php-wasm calls

### Step 5: Bundle PHP Classes (Future Phase)
- Package PHP FHIRTools for WASM
- Virtual filesystem setup
- Autoloader configuration

## Performance Considerations

### Initial Load
- WASM file: ~8-12MB (one-time download)
- Browser caching: Subsequent loads instant
- Lazy loading: Only on first interaction

### Runtime Performance
- Near-native PHP execution speed
- No network latency (local execution)
- Memory: ~50-100MB (acceptable for modern browsers)

### Optimization Strategies
1. **Service Worker Caching** (Phase 8)
   - Cache WASM files
   - Offline support
   
2. **Progressive Loading**
   - Show loading indicator
   - Maintain responsive UI
   
3. **Memory Management**
   - Release PHP instances when not needed
   - Garbage collection

## Browser Compatibility

### Supported Browsers
- Chrome/Edge 91+
- Firefox 89+
- Safari 15+
- Opera 77+

### Requirements
- WebAssembly support
- ES6 modules
- Async/await

### Fallback Strategy
- Detect WebAssembly support
- Show compatibility message if unsupported
- Suggest modern browser upgrade

## Security Considerations

### Safe Execution
- All PHP code runs in browser sandbox
- No file system access (virtual FS only)
- No network access from PHP
- User data never leaves browser

### XSS Prevention
- Escape user input in PHP code
- Validate all JSON before processing
- Use proper output encoding

## Next Steps

1. ✅ Research php-wasm options (Complete)
2. ⏳ Create loader module (docs/assets/js/php-wasm/loader.js)
3. ⏳ Create FHIR client wrapper (docs/assets/js/php-wasm/fhir-client.js)
4. ⏳ Update serialization demo to use php-wasm
5. ⏳ Update FHIRPath demo to use php-wasm
6. ⏳ Add loading UI and progress indicators
7. ⏳ Test in multiple browsers
8. 🔄 Future: Bundle PHP FHIRTools classes for WASM

## Documentation Links

- php-wasm/web: https://github.com/php-wasm/php-wasm-web
- WebAssembly: https://webassembly.org/
- PHP in Browser Guide: https://dev.to/seanmorris/running-php-in-the-browser-with-webassembly-2noj
