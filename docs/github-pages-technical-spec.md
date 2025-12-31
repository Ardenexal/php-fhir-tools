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

### Technology Stack
- **HTML5**: Semantic elements, accessibility features
- **CSS3**: Custom properties, CSS Grid, Flexbox
- **JavaScript**: Vanilla ES6+ (no framework required for MVP)
- **Syntax Highlighting**: Prism.js (lightweight)
- **Icons**: SVG icons (inlined or sprite)

### File Structure
```
docs/                                   # GitHub Pages root
├── index.html                          # Landing page
├── _config.yml                         # Jekyll config (optional)
├── assets/
│   ├── css/
│   │   ├── variables.css               # CSS custom properties
│   │   ├── reset.css                   # Normalize
│   │   ├── layout.css                  # Layout components
│   │   ├── components.css              # UI components
│   │   └── demos.css                   # Demo-specific styles
│   ├── js/
│   │   ├── main.js                     # Core functionality
│   │   ├── api-client.js               # Backend API client
│   │   ├── demos/
│   │   │   ├── serialization.js
│   │   │   ├── fhirpath.js
│   │   │   └── models.js
│   │   └── utils/
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
├── pages/
│   ├── getting-started.html
│   ├── components/
│   │   ├── fhir-bundle.html
│   │   ├── serialization.html
│   │   ├── fhirpath.html
│   │   └── models.html
│   ├── demos/
│   │   ├── serialization.html
│   │   ├── fhirpath.html
│   │   └── models.html
│   └── api-reference.html
└── .nojekyll                           # Disable Jekyll if not used
```

### Page Templates

#### Base Template Structure
```html
<!DOCTYPE html>
<html lang="en">
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
