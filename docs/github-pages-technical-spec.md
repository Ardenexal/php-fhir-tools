# GitHub Pages Technical Specification

## Executive Summary

This document provides the technical specifications for building a GitHub Pages site that showcases PHP FHIRTools components with interactive demos.

## Architecture Overview

### Client-Side Architecture with php-wasm (Recommended) ⭐

```
┌─────────────────────────────────────────────────┐
│          GitHub Pages (Static Site)             │
│  ┌───────────────────────────────────────────┐  │
│  │  HTML/CSS/JavaScript                      │  │
│  │  - Documentation                          │  │
│  │  - Interactive UI                         │  │
│  └───────────────────────────────────────────┘  │
│  ┌───────────────────────────────────────────┐  │
│  │  php-wasm (WebAssembly Runtime)           │  │
│  │  - Runs PHP in browser                    │  │
│  │  - FHIRBundle Integration                 │  │
│  │  - Serialization Service                  │  │
│  │  - FHIRPath Evaluator                     │  │
│  └───────────────────────────────────────────┘  │
└─────────────────────────────────────────────────┘

All processing happens in the user's browser
✅ Zero backend costs
✅ Instant execution
✅ Complete privacy
✅ Offline capable
```

### Alternative: Two-Tier Architecture (If Backend Needed)

```
┌─────────────────────────────────────┐
│     GitHub Pages (Static Site)      │
│  - HTML/CSS/JavaScript              │
│  - Documentation                    │
│  - Interactive UI                   │
└──────────────┬──────────────────────┘
               │ HTTPS/REST
               ▼
┌─────────────────────────────────────┐
│     Backend API (Symfony App)       │
│  - FHIRBundle Integration           │
│  - Serialization Service            │
│  - FHIRPath Evaluator              │
└─────────────────────────────────────┘
```

## Frontend Specification

### Technology Stack ⭐ **UPDATED**
- **Static Site Generator**: Jekyll (GitHub Pages native)
- **Templating**: Liquid templates
- **HTML5**: Semantic elements, accessibility features
- **CSS3**: Modern CSS with custom properties, Grid, Flexbox
- **JavaScript**: Vanilla ES6+ with Web Components
- **Search**: Jekyll Simple Search with Lunr.js
- **Syntax Highlighting**: Rouge (Jekyll default) or Prism.js
- **Icons**: SVG icons (inlined or sprite)

### File Structure (Jekyll) ⭐ **UPDATED**
```
docs/                                   # GitHub Pages root
├── _config.yml                         # Jekyll configuration
├── _layouts/                           # Jekyll layouts
│   ├── default.html                    # Base layout
│   ├── page.html                       # Page layout
│   └── docs.html                       # Documentation layout
├── _includes/                          # Reusable components
│   ├── header.html                     # Site header
│   ├── footer.html                     # Site footer
│   ├── navigation.html                 # Navigation menu
│   └── search.html                     # Search component
├── _data/                              # Data files
│   ├── navigation.yml                  # Navigation structure
│   └── examples.yml                    # FHIR examples metadata
├── index.md                            # Landing page
├── getting-started.md                  # Getting started guide
├── components/                         # Component documentation
│   ├── fhir-bundle.md
│   ├── serialization.md
│   ├── fhirpath.md
│   └── models.md
├── demos/                              # Interactive demos (single directory)
│   ├── serialization.html              # Uses Web Components
│   ├── fhirpath.html
│   └── models.html
├── 404.html                            # Custom 404 page
├── assets/
│   ├── css/
│   │   ├── variables.css               # CSS custom properties
│   │   ├── reset.css                   # Normalize
│   │   ├── base.css                    # Base styles
│   │   ├── layout.css                  # Layout components
│   │   ├── components.css              # UI components
│   │   └── demos.css                   # Demo-specific styles
│   ├── js/
│   │   ├── main.js                     # Core functionality
│   │   ├── components/                 # Web Components
│   │   │   ├── fhir-serialization-demo.js
│   │   │   ├── fhir-path-evaluator.js
│   │   │   └── fhir-model-explorer.js
│   │   ├── terminology/                # NEW: Terminology service client
│   │   │   ├── tx-client.js            # Client for tx.fhir.org operations
│   │   │   └── cache/                  # Optional: pre-expanded VS caches
│   │   │       └── indexes.json
│   │   ├── php-wasm-loader.js          # php-wasm integration
│   │   └── utils/
│   │       ├── operation-outcome.js    # NEW: OperationOutcome handler
│   │       ├── syntax-highlight.js
│   │       └── error-handler.js
│   ├── data/
│   │   ├── examples/
│   │   │   ├── patient.json
│   │   │   ├── observation.json
│   │   │   └── bundle.json
│   │   └── models-metadata.json        # Generated model info
│   └── images/
│       ├── logo.svg
│       └── screenshots/
└── lib/
    └── php-wasm/                       # PHP WASM runtime files
```

### Jekyll Configuration

#### _config.yml
```yaml
# Site settings
title: PHP FHIRTools
description: FHIR tools for PHP - Build FHIR-compliant applications
baseurl: "/php-fhir-tools"
url: "https://ardenexal.github.io"

# Build settings
markdown: kramdown
theme: null  # Custom theme

# Collections
collections:
  components:
    output: true
    permalink: /components/:path/

# Plugins
plugins:
  - jekyll-feed
  - jekyll-sitemap
  - jekyll-seo-tag
  - jekyll-lunr-js-search  # For search

# Search configuration
lunr_search:
  excludes: [rss.xml, atom.xml]
  min_length: 3

# Exclude from processing
exclude:
  - Gemfile
  - Gemfile.lock
  - node_modules
  - vendor/bundle/
```

### Jekyll Layout Templates

#### _layouts/default.html
```liquid
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ page.title }} - {{ site.title }}</title>
    <link rel="stylesheet" href="{{ '/assets/css/main.css' | relative_url }}">
    {% seo %}
</head>
<body>
    {% include header.html %}
    
    <main class="main-content">
        {{ content }}
    </main>
    
    {% include footer.html %}
    
    <script src="{{ '/assets/js/main.js' | relative_url }}"></script>
</body>
</html>
```

#### _includes/header.html
```liquid
<header class="site-header">
    <nav class="navbar">
        <div class="container">
            <a href="{{ '/' | relative_url }}" class="logo">
                {{ site.title }}
            </a>
            <ul class="nav-menu">
                {% for item in site.data.navigation %}
                <li><a href="{{ item.url | relative_url }}">{{ item.title }}</a></li>
                {% endfor %}
            </ul>
            {% include search.html %}
        </div>
    </nav>
</header>
```

### Web Components

#### assets/js/components/fhir-serialization-demo.js
```javascript
class FHIRSerializationDemo extends HTMLElement {
    constructor() {
        super();
        this.phpWasm = null;
    }
    
    async connectedCallback() {
        await this.loadPhpWasm();
        this.render();
        this.attachEventListeners();
    }
    
    async loadPhpWasm() {
        const { PhpWeb } = await import('../php-wasm-loader.js');
        this.phpWasm = new PhpWeb();
        await this.phpWasm.initialize();
    }
    
    render() {
        this.innerHTML = `
            <div class="demo-container">
                <div class="demo-header">
                    <h2>FHIR Serialization Demo</h2>
                    <p>Test JSON serialization of FHIR resources</p>
                </div>
                
                <div class="demo-content">
                    <div class="demo-panel input-panel">
                        <h3>Input</h3>
                        <select id="example-selector">
                            <option value="">Select example...</option>
                            <option value="patient">Patient</option>
                            <option value="observation">Observation</option>
                        </select>
                        <textarea id="fhir-input" rows="20"></textarea>
                    </div>
                    
                    <div class="demo-controls">
                        <button id="btn-serialize">Serialize</button>
                        <button id="btn-deserialize">Deserialize</button>
                    </div>
                    
                    <div class="demo-panel output-panel">
                        <h3>Output</h3>
                        <pre id="fhir-output"></pre>
                    </div>
                </div>
            </div>
        `;
    }
    
    attachEventListeners() {
        this.querySelector('#btn-serialize').addEventListener('click', 
            () => this.handleSerialize());
        this.querySelector('#btn-deserialize').addEventListener('click', 
            () => this.handleDeserialize());
    }
    
    async handleSerialize() {
        const input = this.querySelector('#fhir-input').value;
        const output = this.querySelector('#fhir-output');
        
        try {
            const result = await this.phpWasm.serialize(JSON.parse(input));
            output.textContent = JSON.stringify(result, null, 2);
        } catch (error) {
            output.textContent = `Error: ${error.message}`;
        }
    }
    
    async handleDeserialize() {
        const input = this.querySelector('#fhir-input').value;
        const output = this.querySelector('#fhir-output');
        
        try {
            const result = await this.phpWasm.deserialize(input, 'Patient');
            output.textContent = JSON.stringify(result, null, 2);
        } catch (error) {
            output.textContent = `Error: ${error.message}`;
        }
    }
}

// Register the custom element
customElements.define('fhir-serialization-demo', FHIRSerializationDemo);
```

#### Usage in Jekyll pages (demos/serialization.md)
```markdown
---
layout: default
title: Serialization Demo
---

# FHIR Serialization Demo

Interactive demo using php-wasm to run PHP directly in your browser.

<fhir-serialization-demo></fhir-serialization-demo>

<script type="module" src="{{ '/assets/js/components/fhir-serialization-demo.js' | relative_url }}"></script>
```

### Page Templates
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP FHIRTools</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="logo">PHP FHIRTools</a>
            <ul class="nav-menu">
                <li><a href="/pages/getting-started.html">Get Started</a></li>
                <li><a href="#components">Components</a></li>
                <li><a href="#demos">Demos</a></li>
                <li><a href="https://github.com/Ardenexal/php-fhir-tools">GitHub</a></li>
            </ul>
        </div>
    </nav>
    
    <main class="main-content">
        <!-- Page content -->
    </main>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 PHP FHIRTools. MIT License.</p>
        </div>
    </footer>
    
    <script src="/assets/js/main.js"></script>
</body>
</html>
```

### CSS Architecture

#### Design Tokens (variables.css)
```css
:root {
    /* Colors */
    --color-primary: #0066CC;
    --color-secondary: #28A745;
    --color-accent: #FFC107;
    --color-danger: #DC3545;
    --color-background: #FFFFFF;
    --color-surface: #F8F9FA;
    --color-text: #212529;
    --color-text-muted: #6C757D;
    
    /* Spacing */
    --space-xs: 0.25rem;
    --space-sm: 0.5rem;
    --space-md: 1rem;
    --space-lg: 1.5rem;
    --space-xl: 2rem;
    --space-2xl: 3rem;
    
    /* Typography */
    --font-family-base: system-ui, -apple-system, sans-serif;
    --font-family-mono: 'Fira Code', 'Courier New', monospace;
    --font-size-sm: 0.875rem;
    --font-size-base: 1rem;
    --font-size-lg: 1.125rem;
    --font-size-xl: 1.25rem;
    --font-size-2xl: 1.5rem;
    
    /* Layout */
    --container-max-width: 1200px;
    --navbar-height: 60px;
    --border-radius: 4px;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
}

/* Dark mode */
@media (prefers-color-scheme: dark) {
    :root {
        --color-background: #1A1A1A;
        --color-surface: #2D2D2D;
        --color-text: #F8F9FA;
        --color-text-muted: #ADB5BD;
    }
}
```

### Interactive Demo Components

#### Serialization Demo Interface
```html
<div class="demo-container">
    <div class="demo-header">
        <h2>FHIR Serialization Demo</h2>
        <p>Test JSON serialization and deserialization of FHIR resources</p>
    </div>
    
    <div class="demo-content">
        <div class="demo-panel input-panel">
            <div class="panel-header">
                <h3>Input</h3>
                <select id="example-selector">
                    <option value="">Select an example...</option>
                    <option value="patient">Patient</option>
                    <option value="observation">Observation</option>
                    <option value="bundle">Bundle</option>
                </select>
            </div>
            <textarea 
                id="fhir-input" 
                class="code-editor"
                rows="20"
                placeholder='{"resourceType": "Patient", ...}'
            ></textarea>
        </div>
        
        <div class="demo-controls">
            <div class="control-group">
                <label for="validation-mode">Validation Mode:</label>
                <select id="validation-mode">
                    <option value="strict">Strict</option>
                    <option value="lenient">Lenient</option>
                </select>
            </div>
            
            <div class="button-group">
                <button id="btn-serialize" class="btn btn-primary">
                    Serialize
                </button>
                <button id="btn-deserialize" class="btn btn-primary">
                    Deserialize
                </button>
                <button id="btn-validate" class="btn btn-secondary">
                    Validate
                </button>
            </div>
        </div>
        
        <div class="demo-panel output-panel">
            <div class="panel-header">
                <h3>Output</h3>
                <button id="btn-copy" class="btn-icon" title="Copy to clipboard">
                    <svg><!-- Copy icon --></svg>
                </button>
            </div>
            <pre id="fhir-output" class="code-output"></pre>
            <div id="error-display" class="error-message hidden"></div>
        </div>
    </div>
</div>
```

#### FHIRPath Demo Interface
```html
<div class="demo-container fhirpath-demo">
    <div class="demo-layout">
        <div class="main-area">
            <div class="resource-input">
                <h3>FHIR Resource</h3>
                <textarea id="fhir-resource" class="code-editor"></textarea>
            </div>
            
            <div class="expression-input">
                <h3>FHIRPath Expression</h3>
                <input 
                    type="text" 
                    id="fhirpath-expression"
                    class="expression-field"
                    placeholder="Patient.name.given"
                >
                <button id="btn-evaluate" class="btn btn-primary">
                    Evaluate
                </button>
            </div>
            
            <div class="result-output">
                <h3>Result</h3>
                <pre id="evaluation-result"></pre>
            </div>
        </div>
        
        <aside class="sidebar">
            <div class="sidebar-section">
                <h4>Example Expressions</h4>
                <ul class="expression-list">
                    <li><code class="clickable">Patient.name.given</code></li>
                    <li><code class="clickable">Observation.value.ofType(Quantity)</code></li>
                    <li><code class="clickable">Bundle.entry.resource.ofType(Patient).name</code></li>
                </ul>
            </div>
            
            <div class="sidebar-section">
                <h4>Quick Reference</h4>
                <details>
                    <summary>Functions</summary>
                    <ul>
                        <li><code>count()</code></li>
                        <li><code>where()</code></li>
                        <li><code>select()</code></li>
                        <!-- More functions -->
                    </ul>
                </details>
            </div>
        </aside>
    </div>
</div>
```

### JavaScript API Client

```javascript
// assets/js/api-client.js
class FHIRToolsAPIClient {
    constructor(baseURL) {
        this.baseURL = baseURL || 'https://api.fhir-tools.example.com';
    }
    
    async serialize(data, options = {}) {
        return this._post('/api/serialize', { data, options });
    }
    
    async deserialize(json, resourceType, options = {}) {
        return this._post('/api/deserialize', { json, resourceType, options });
    }
    
    async validate(data, options = {}) {
        return this._post('/api/validate', { data, options });
    }
    
    async evaluateFHIRPath(resource, expression) {
        return this._post('/api/fhirpath/evaluate', { resource, expression });
    }
    
    async _post(endpoint, body) {
        try {
            const response = await fetch(`${this.baseURL}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(body),
            });
            
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.error || 'API request failed');
            }
            
            return result;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }
}

// Export for use in other modules
window.FHIRToolsAPIClient = FHIRToolsAPIClient;
```

### php-wasm Integration (Recommended) ⭐

```javascript
// assets/js/php-wasm-client.js
import { PhpWeb } from 'php-wasm/PhpWeb';

class PHPWasmFHIRTools {
    constructor() {
        this.php = null;
        this.initialized = false;
    }
    
    async initialize() {
        if (this.initialized) return;
        
        // Load PHP WebAssembly runtime
        this.php = new PhpWeb();
        
        // Load FHIRTools library
        await this.php.mount('/app', {
            'vendor/': '/assets/php-wasm/vendor/', // FHIRTools dependencies
            'src/': '/assets/php-wasm/src/'        // FHIRTools source
        });
        
        // Bootstrap autoloader
        await this.php.run(`<?php
            require_once '/app/vendor/autoload.php';
            use Ardenexal\\FHIRTools\\Component\\Serialization\\FHIRSerializationService;
            use Ardenexal\\FHIRTools\\Component\\FHIRPath\\FHIRPathEvaluator;
        `);
        
        this.initialized = true;
    }
    
    async serialize(resource, options = {}) {
        await this.initialize();
        
        const phpCode = `<?php
            $serializer = new FHIRSerializationService();
            $resource = json_decode('${JSON.stringify(resource)}', true);
            $options = json_decode('${JSON.stringify(options)}', true);
            
            try {
                $result = $serializer->serialize($resource, $options);
                echo json_encode(['success' => true, 'data' => $result]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        ?>`;
        
        const result = await this.php.run(phpCode);
        return JSON.parse(result);
    }
    
    async deserialize(json, resourceType, options = {}) {
        await this.initialize();
        
        const phpCode = `<?php
            $serializer = new FHIRSerializationService();
            
            try {
                $result = $serializer->deserialize('${json}', '${resourceType}', ${JSON.stringify(options)});
                echo json_encode(['success' => true, 'data' => $result]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        ?>`;
        
        const result = await this.php.run(phpCode);
        return JSON.parse(result);
    }
    
    async evaluateFHIRPath(resource, expression) {
        await this.initialize();
        
        const phpCode = `<?php
            $evaluator = new FHIRPathEvaluator();
            $resource = json_decode('${JSON.stringify(resource)}', true);
            
            try {
                $result = $evaluator->evaluate('${expression}', $resource);
                echo json_encode([
                    'success' => true, 
                    'result' => $result,
                    'type' => gettype($result),
                    'count' => is_array($result) ? count($result) : 1
                ]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        ?>`;
        
        const result = await this.php.run(phpCode);
        return JSON.parse(result);
    }
}

// Export for use in demos
window.PHPWasmFHIRTools = PHPWasmFHIRTools;
```

**Usage in Demo:**
```javascript
// Initialize php-wasm client
const fhirTools = new PHPWasmFHIRTools();

// Serialize FHIR resource
const result = await fhirTools.serialize(patientResource, { 
    validationMode: 'strict' 
});

// Evaluate FHIRPath
const names = await fhirTools.evaluateFHIRPath(
    patientResource, 
    'Patient.name.given'
);
```

**Benefits:**
- ✅ No backend server required
- ✅ Instant execution (no network latency)
- ✅ Complete privacy (data never leaves browser)
- ✅ Works offline once loaded
- ✅ Zero hosting costs

**Setup Requirements:**
1. Include php-wasm library from CDN or bundle locally
2. Bundle PHP FHIRTools dependencies for browser
3. Configure file system mapping for vendor directory
4. Handle WASM initialization on page load

### OperationOutcome Error Handling ⭐ **NEW**

All errors and validation issues MUST be returned as FHIR OperationOutcome resources (normative in R4/R4B/R5).

**Specification:** 
- [OperationOutcome (R4/R5)](https://fhir.hl7.org/fhir/operationoutcome.html)
- [Element Definitions](https://fhir.hl7.org/fhir/operationoutcome-definitions.html)
- [FHIRPath for expression](https://fhir.hl7.org/fhir/fhirpath.html)

#### OperationOutcome Structure

```javascript
// assets/js/utils/operation-outcome.js

/**
 * Create a FHIR OperationOutcome for errors
 * @param {string} severity - error|warning|information
 * @param {string} code - IssueType value binding
 * @param {string} details - Human-readable description
 * @param {string} diagnostics - Additional diagnostic information
 * @param {string[]} expression - FHIRPath expressions pointing to issue
 */
export function createOperationOutcome({
    severity = 'error',
    code = 'invalid',
    details,
    diagnostics,
    expression = []
}) {
    return {
        resourceType: 'OperationOutcome',
        issue: [{
            severity,
            code,
            details: { text: details },
            diagnostics,
            expression
        }]
    };
}

/**
 * Example: Validation error
 */
export function validationError(message, path) {
    return createOperationOutcome({
        severity: 'error',
        code: 'invalid',
        details: message,
        diagnostics: `Validation failed at ${path}`,
        expression: [path]
    });
}

/**
 * Example: Structure error
 */
export function structureError(expected, found, path) {
    return createOperationOutcome({
        severity: 'error',
        code: 'structure',
        details: `Expected ${expected}, found ${found}`,
        diagnostics: `Type mismatch at ${path}`,
        expression: [path]
    });
}
```

#### Usage in Web Components

```javascript
// In FHIRSerializationDemo component
async handleSerialize() {
    try {
        const input = this.querySelector('#fhir-input').value;
        const resource = JSON.parse(input);
        
        // Validate resource structure
        if (!resource.resourceType) {
            const outcome = validationError(
                'Missing required field: resourceType',
                'Resource.resourceType'
            );
            this.displayError(outcome);
            return;
        }
        
        const result = await this.phpWasm.serialize(resource);
        this.displayOutput(result);
    } catch (error) {
        const outcome = createOperationOutcome({
            severity: 'error',
            code: 'exception',
            details: 'Serialization failed',
            diagnostics: error.message,
            expression: []
        });
        this.displayError(outcome);
    }
}

displayError(operationOutcome) {
    const output = this.querySelector('#fhir-output');
    output.textContent = JSON.stringify(operationOutcome, null, 2);
    output.classList.add('error');
}
```

### Terminology Service Integration ⭐ **NEW**

Default to **tx.fhir.org** for terminology operations with UI to switch servers.

**Important:** tx.fhir.org is a community server, not production-grade. Document this caveat.

**Specifications:**
- [Terminology Service Operations](https://build.fhir.org/terminology-service.html)
- [tx.fhir.org Documentation](https://confluence.hl7.org/spaces/FHIR/pages/79503413/tx.fhir.org+Documentation)

**Alternate Servers:**
- HL7 Europe: https://tx.hl7europe.eu/r4
- Swiss TX: https://tx.fhir.ch/r4

#### Terminology Client Implementation

```javascript
// assets/js/terminology/tx-client.js

const TX_SERVERS = {
    'tx.fhir.org': 'https://tx.fhir.org/r4',
    'hl7europe': 'https://tx.hl7europe.eu/r4',
    'swiss': 'https://tx.fhir.ch/r4'
};

let currentServer = TX_SERVERS['tx.fhir.org'];

/**
 * Switch terminology server
 */
export function setTerminologyServer(serverKey) {
    if (TX_SERVERS[serverKey]) {
        currentServer = TX_SERVERS[serverKey];
    }
}

/**
 * Expand a ValueSet
 * @param {string} url - ValueSet canonical URL
 * @param {object} options - filter, count, etc.
 * @returns {Promise<object>} ValueSet or OperationOutcome
 */
export async function expandValueSet(url, { filter, count, offset } = {}) {
    const body = {
        resourceType: 'Parameters',
        parameter: [
            { name: 'url', valueUri: url },
            ...(filter ? [{ name: 'filter', valueString: filter }] : []),
            ...(count ? [{ name: 'count', valueInteger: count }] : []),
            ...(offset ? [{ name: 'offset', valueInteger: offset }] : [])
        ]
    };
    
    try {
        const response = await fetch(`${currentServer}/ValueSet/$expand`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/fhir+json',
                'Accept': 'application/fhir+json'
            },
            body: JSON.stringify(body)
        });
        
        return await response.json(); // ValueSet or OperationOutcome
    } catch (error) {
        return createOperationOutcome({
            severity: 'error',
            code: 'exception',
            details: 'ValueSet expansion failed',
            diagnostics: error.message,
            expression: []
        });
    }
}

/**
 * Validate a code against a ValueSet
 * @param {object} params - url, code, system, display, version
 * @returns {Promise<object>} Parameters with result or OperationOutcome
 */
export async function validateCode({ url, code, system, display, version }) {
    const body = {
        resourceType: 'Parameters',
        parameter: [
            { name: 'url', valueUri: url },
            { name: 'code', valueCode: code },
            ...(system ? [{ name: 'system', valueUri: system }] : []),
            ...(version ? [{ name: 'systemVersion', valueString: version }] : []),
            ...(display ? [{ name: 'display', valueString: display }] : [])
        ]
    };
    
    try {
        const response = await fetch(`${currentServer}/ValueSet/$validate-code`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/fhir+json',
                'Accept': 'application/fhir+json'
            },
            body: JSON.stringify(body)
        });
        
        return await response.json(); // Parameters or OperationOutcome
    } catch (error) {
        return createOperationOutcome({
            severity: 'error',
            code: 'exception',
            details: 'Code validation failed',
            diagnostics: error.message,
            expression: []
        });
    }
}

/**
 * Lookup code details
 * @param {object} params - code, system, version, property
 * @returns {Promise<object>} Parameters or OperationOutcome
 */
export async function lookupCode({ code, system, version, property }) {
    const body = {
        resourceType: 'Parameters',
        parameter: [
            { name: 'code', valueCode: code },
            { name: 'system', valueUri: system },
            ...(version ? [{ name: 'version', valueString: version }] : []),
            ...(property ? property.map(p => ({ name: 'property', valueCode: p })) : [])
        ]
    };
    
    try {
        const response = await fetch(`${currentServer}/CodeSystem/$lookup`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/fhir+json',
                'Accept': 'application/fhir+json'
            },
            body: JSON.stringify(body)
        });
        
        return await response.json();
    } catch (error) {
        return createOperationOutcome({
            severity: 'error',
            code: 'exception',
            details: 'Code lookup failed',
            diagnostics: error.message,
            expression: []
        });
    }
}

/**
 * Check subsumption between codes
 */
export async function checkSubsumption({ codeA, codeB, system, version }) {
    const body = {
        resourceType: 'Parameters',
        parameter: [
            { name: 'codeA', valueCode: codeA },
            { name: 'codeB', valueCode: codeB },
            { name: 'system', valueUri: system },
            ...(version ? [{ name: 'version', valueString: version }] : [])
        ]
    };
    
    try {
        const response = await fetch(`${currentServer}/CodeSystem/$subsumes`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/fhir+json',
                'Accept': 'application/fhir+json'
            },
            body: JSON.stringify(body)
        });
        
        return await response.json();
    } catch (error) {
        return createOperationOutcome({
            severity: 'error',
            code: 'exception',
            details: 'Subsumption check failed',
            diagnostics: error.message,
            expression: []
        });
    }
}
```

#### Terminology Cache for Offline

```json
// assets/js/terminology/cache/indexes.json
{
    "valueSets": [
        {
            "url": "http://hl7.org/fhir/ValueSet/administrative-gender",
            "version": "4.0.1",
            "file": "administrative-gender.json",
            "size": 1024
        }
    ],
    "lastUpdated": "2025-12-31T00:00:00Z"
}
```

#### UI for Server Selection

```html
<!-- In demo pages -->
<div class="terminology-config">
    <label for="tx-server">Terminology Server:</label>
    <select id="tx-server">
        <option value="tx.fhir.org" selected>tx.fhir.org (Community)</option>
        <option value="hl7europe">HL7 Europe TX</option>
        <option value="swiss">Swiss TX</option>
    </select>
    <span class="config-note">
        ⚠️ tx.fhir.org is a community server, not production-grade
    </span>
</div>
```

### Service Worker for Offline Support ⭐ **NEW**

```javascript
// assets/js/service-worker.js

const CACHE_NAME = 'fhir-tools-v1';
const ASSETS_TO_CACHE = [
    '/',
    '/assets/css/main.css',
    '/assets/js/main.js',
    '/assets/js/components/fhir-serialization-demo.js',
    '/assets/js/components/fhir-path-evaluator.js',
    '/assets/js/components/fhir-model-explorer.js',
    '/assets/js/terminology/tx-client.js',
    '/assets/js/utils/operation-outcome.js',
    '/lib/php-wasm/php.wasm',
    '/assets/data/examples/patient.json',
    '/assets/data/examples/observation.json'
];

// Install event - cache assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(ASSETS_TO_CACHE))
            .then(() => self.skipWaiting())
    );
});

// Activate event - clean old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(
                keys.filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            ))
            .then(() => self.clients.claim())
    );
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', (event) => {
    // Skip terminology server requests (always fetch fresh)
    if (event.request.url.includes('tx.fhir.org') || 
        event.request.url.includes('tx.hl7europe.eu') ||
        event.request.url.includes('tx.fhir.ch')) {
        return;
    }
    
    event.respondWith(
        caches.match(event.request)
            .then(response => response || fetch(event.request))
    );
});
```

**Register Service Worker:**
```javascript
// In main.js
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
            .then(reg => console.log('Service Worker registered'))
            .catch(err => console.error('Service Worker registration failed', err));
    });
}
```

### CI Build Pipeline ⭐ **NEW**

Jekyll must be built in CI and deployed as static HTML (not raw Markdown/Liquid).

#### GitHub Actions Workflow

```yaml
# .github/workflows/deploy-pages.yml
name: Deploy to GitHub Pages

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]
  workflow_dispatch:

permissions:
  contents: read
  pages: write
  id-token: write

concurrency:
  group: "pages"
  cancel-in-progress: false

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout
        uses: actions/checkout@v4
      
      - name: Setup Ruby
        uses: ruby/setup-ruby@v1
        with:
          ruby-version: '3.1'
          bundler-cache: true
      
      - name: Setup Pages
        uses: actions/configure-pages@v4
      
      - name: Build with Jekyll
        run: |
          cd docs
          bundle install
          bundle exec jekyll build
        env:
          JEKYLL_ENV: production
      
      - name: Upload artifact
        uses: actions/upload-pages-artifact@v3
        with:
          path: docs/_site
  
  deploy:
    if: github.event_name != 'pull_request'
    environment:
      name: github-pages
      url: ${{ steps.deployment.outputs.page_url }}
    runs-on: ubuntu-latest
    needs: build
    steps:
      - name: Deploy to GitHub Pages
        id: deployment
        uses: actions/deploy-pages@v4
```

#### Gemfile for Jekyll

```ruby
# docs/Gemfile
source 'https://rubygems.org'

gem 'jekyll', '~> 4.3'

group :jekyll_plugins do
  gem 'jekyll-seo-tag'
  gem 'jekyll-sitemap'
  gem 'jekyll-feed'
  gem 'jekyll-lunr-js-search'  # For search functionality
end

# Windows and JRuby does not include zoneinfo files
platforms :mingw, :x64_mingw, :mswin, :jruby do
  gem 'tzinfo', '>= 1', '< 3'
  gem 'tzinfo-data'
end

# Performance-booster for watching directories
gem 'wdm', '~> 0.1', :platforms => [:mingw, :x64_mingw, :mswin]
```

**Setup Requirements:**
1. Include php-wasm library from CDN or bundle locally
2. Bundle PHP FHIRTools dependencies for browser
3. Configure file system mapping for vendor directory
4. Handle WASM initialization on page load

## Backend API Specification (Alternative)

**Note:** This section describes a traditional backend API approach. With php-wasm, this backend is **optional** and only needed for specific use cases like external API access or server-side caching.

### Technology Stack
- **Framework**: Symfony 6.4/7.4
- **Components**: 
  - FHIRBundle
  - Serialization Component
  - FHIRPath Component
  - Models Component
- **Database**: None (stateless API)
- **Cache**: Redis (optional, for rate limiting)

### API Endpoints

#### POST /api/serialize
Serialize FHIR resource object to JSON.

**Request:**
```json
{
    "data": { /* FHIR resource object */ },
    "options": {
        "validationMode": "strict",
        "prettyPrint": true
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": "{ /* serialized JSON string */ }",
    "meta": {
        "resourceType": "Patient",
        "timestamp": "2025-12-31T05:27:00Z"
    }
}
```

#### POST /api/deserialize
Deserialize JSON to FHIR resource object.

**Request:**
```json
{
    "json": "{ /* JSON string */ }",
    "resourceType": "Patient",
    "options": {
        "validationMode": "lenient"
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": { /* deserialized object */ },
    "warnings": []
}
```

#### POST /api/validate
Validate FHIR resource.

**Request:**
```json
{
    "data": { /* FHIR resource */ },
    "options": {
        "profile": "http://hl7.org/fhir/StructureDefinition/Patient"
    }
}
```

**Response:**
```json
{
    "success": true,
    "valid": true,
    "errors": [],
    "warnings": []
}
```

#### POST /api/fhirpath/evaluate
Evaluate FHIRPath expression.

**Request:**
```json
{
    "resource": { /* FHIR resource */ },
    "expression": "Patient.name.given"
}
```

**Response:**
```json
{
    "success": true,
    "result": ["John", "Jane"],
    "type": "string",
    "count": 2
}
```

### Backend Implementation

#### Controller Example
```php
<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\FHIRPath\FHIRPathEvaluator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class FHIRToolsController extends AbstractController
{
    public function __construct(
        private readonly FHIRSerializationService $serializer,
        private readonly FHIRPathEvaluator $evaluator
    ) {}

    #[Route('/serialize', name: 'serialize', methods: ['POST'])]
    public function serialize(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $resource = $data['data'] ?? null;
            $options = $data['options'] ?? [];
            
            if (!$resource) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'No data provided'
                ], 400);
            }
            
            $result = $this->serializer->serialize($resource, $options);
            
            return new JsonResponse([
                'success' => true,
                'data' => $result,
                'meta' => [
                    'resourceType' => $resource['resourceType'] ?? 'Unknown',
                    'timestamp' => date('c')
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/fhirpath/evaluate', name: 'fhirpath_evaluate', methods: ['POST'])]
    public function evaluateFHIRPath(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $resource = $data['resource'] ?? null;
            $expression = $data['expression'] ?? null;
            
            if (!$resource || !$expression) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Missing resource or expression'
                ], 400);
            }
            
            $result = $this->evaluator->evaluate($expression, $resource);
            
            return new JsonResponse([
                'success' => true,
                'result' => $result,
                'type' => gettype($result),
                'count' => is_array($result) ? count($result) : 1
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
```

#### CORS Configuration
```yaml
# config/packages/nelmio_cors.yaml
nelmio_cors:
    defaults:
        origin_regex: true
        allow_origin: ['%env(CORS_ALLOW_ORIGIN)%']
        allow_methods: ['GET', 'POST', 'OPTIONS']
        allow_headers: ['Content-Type', 'Authorization']
        expose_headers: ['Link']
        max_age: 3600
    paths:
        '^/api/':
            allow_origin: ['*']
```

#### Rate Limiting
```php
// Using Symfony Rate Limiter
#[RateLimit(limit: 100, period: 60)]
#[Route('/api/serialize', methods: ['POST'])]
public function serialize(Request $request): JsonResponse
{
    // Implementation
}
```

## Deployment Strategy

### Frontend Deployment (GitHub Pages)

#### GitHub Actions Workflow
```yaml
# .github/workflows/deploy-gh-pages.yml
name: Deploy GitHub Pages

on:
  push:
    branches: [ main ]
    paths:
      - 'docs/**'
  workflow_dispatch:

permissions:
  contents: read
  pages: write
  id-token: write

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup Pages
        uses: actions/configure-pages@v4
      
      - name: Upload artifact
        uses: actions/upload-pages-artifact@v3
        with:
          path: './docs'
      
      - name: Deploy to GitHub Pages
        id: deployment
        uses: actions/deploy-pages@v4
```

### Backend Deployment Options

#### Option 1: Heroku
```bash
# Procfile
web: heroku-php-apache2 public/
```

#### Option 2: DigitalOcean App Platform
```yaml
# .do/app.yaml
name: fhir-tools-api
services:
  - name: api
    github:
      repo: Ardenexal/php-fhir-tools-api
      branch: main
    build_command: composer install --no-dev
    run_command: php -S 0.0.0.0:$PORT -t public
    envs:
      - key: APP_ENV
        value: prod
```

#### Option 3: Railway
```toml
# railway.toml
[build]
builder = "NIXPACKS"

[deploy]
startCommand = "php -S 0.0.0.0:$PORT -t public"
```

## Security Considerations

### Frontend Security
- Content Security Policy (CSP)
- HTTPS only
- Input sanitization
- XSS prevention

### Backend Security
- Rate limiting
- Input validation
- CORS restrictions
- Request size limits
- Error message sanitization
- No sensitive data in responses

### Example CSP Header
```html
<meta http-equiv="Content-Security-Policy" 
      content="default-src 'self'; 
               script-src 'self' 'unsafe-inline'; 
               style-src 'self' 'unsafe-inline'; 
               img-src 'self' data: https:;">
```

## Performance Optimization

### Frontend
- Minify CSS/JS
- Optimize images (WebP format)
- Lazy loading for images
- Code splitting
- CDN for static assets
- Browser caching

### Backend
- Response caching
- Database query optimization (if used)
- Gzip compression
- HTTP/2

## Accessibility Requirements

- WCAG 2.1 Level AA compliance
- Semantic HTML
- ARIA labels where needed
- Keyboard navigation support
- Screen reader compatibility
- Color contrast ratios (minimum 4.5:1)
- Focus indicators

## Testing Strategy

### Frontend Testing
- Manual cross-browser testing
- Responsive design testing
- Accessibility audit (WAVE, axe)
- Performance testing (Lighthouse)
- Link validation

### Backend Testing
- PHPUnit tests for all endpoints
- Integration tests
- Load testing
- Security testing

## Monitoring & Analytics

### Frontend
- Google Analytics or Plausible (privacy-friendly)
- Error tracking (Sentry)
- Page performance monitoring

### Backend
- Application monitoring
- Error tracking
- API usage metrics
- Response time monitoring

## Documentation Requirements

### For Developers
- API reference
- Setup instructions
- Contributing guide
- Code examples

### For Users
- Getting started guide
- Component documentation
- Tutorial videos (optional)
- FAQ

## Success Criteria

### Launch Checklist
- [ ] All pages render correctly
- [ ] All demos functional
- [ ] Mobile responsive
- [ ] Accessibility compliant
- [ ] Cross-browser compatible
- [ ] API endpoints tested
- [ ] Documentation complete
- [ ] Performance optimized
- [ ] Security reviewed

### Post-Launch Metrics
- Site uptime > 99%
- Page load time < 2s
- Mobile performance score > 90
- Accessibility score > 95
- API response time < 500ms

## Maintenance Plan

### Regular Tasks
- Update dependencies
- Monitor performance
- Review analytics
- Fix reported bugs
- Update documentation
- Respond to feedback

### Quarterly Reviews
- Performance audit
- Security audit
- Content updates
- Feature additions

## Conclusion

This technical specification provides a solid foundation for building a professional GitHub Pages site with interactive demos for PHP FHIRTools. The architecture separates concerns between static documentation and dynamic API functionality, ensuring scalability and maintainability.
