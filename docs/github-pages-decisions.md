# GitHub Pages Site - Decision Matrix & Recommendations

## Executive Summary

This document helps make key architectural and implementation decisions for the PHP FHIRTools showcase site.

---

## Decision 1: Site Generator

### Options Comparison

| Feature | Plain HTML/CSS/JS | Jekyll | Hugo | Docusaurus |
|---------|-------------------|--------|------|------------|
| Setup Complexity | ⭐ Simple | ⭐⭐ Medium | ⭐⭐ Medium | ⭐⭐⭐ Complex |
| Build Time | Instant | Fast | Very Fast | Medium |
| Learning Curve | Minimal | Low | Low | High |
| Flexibility | Maximum | High | High | Medium |
| GitHub Pages Native | ✅ | ✅ | ❌ | ❌ |
| Interactive Demos | Easy | Easy | Easy | Medium |
| Template System | Manual | Liquid | Go | React |
| Search Built-in | ❌ | ❌ | ❌ | ✅ |

### Recommendation: **Jekyll** ⭐ **DECISION CONFIRMED**

**Reasons:**
- Native GitHub Pages support (automatic builds)
- Liquid templating for reusable components
- Modern CSS with CSS variables support
- Easy to add interactive JavaScript demos
- Built-in support for search plugins
- Good documentation and community
- No separate build pipeline needed

**Implementation Details:**
- Use modern CSS with CSS variables for theming
- Vanilla JavaScript with Web Components for interactive parts
- Jekyll's recommended search solution
- Leverage Liquid templates for DRY principles

---

## Decision 2: PHP Execution Architecture

### Options Comparison

| Approach | Cost | Ease of Setup | Latency | Offline Support | Notes |
|----------|------|---------------|---------|----------------|-------|
| **php-wasm (Browser)** | $0 | ⭐⭐⭐ Easy | Instant | ✅ Yes | Runs PHP in browser via WebAssembly |
| **Separate Backend API** | $0-15/month | ⭐⭐ Medium | 100-500ms | ❌ No | Traditional server approach |
| **Serverless Functions** | $0-5/month | ⭐⭐ Medium | 100-1000ms | ❌ No | Cold start issues |

### Recommendation: **php-wasm (Browser-based)** ⭐ **NEW!**

**Why php-wasm is Better:**
- ✅ **Zero cost** - No backend server required
- ✅ **Zero latency** - Runs directly in browser
- ✅ **Offline capable** - Works without internet connection
- ✅ **No CORS issues** - Everything runs client-side
- ✅ **Simplified deployment** - Just GitHub Pages
- ✅ **Better UX** - Instant responses, no loading states
- ✅ **Privacy** - Data never leaves user's browser

**How it Works:**
```javascript
// Load php-wasm
import { PhpWeb } from 'php-wasm/PhpWeb';

// Initialize PHP runtime
const php = new PhpWeb();

// Run PHP code
const result = await php.run(`<?php
    // Load FHIR libraries
    require_once 'vendor/autoload.php';
    
    // Use FHIRTools
    $serializer = new FHIRSerializationService();
    echo $serializer->serialize($fhirResource);
`);
```

**Implementation Requirements:**
- Include php-wasm library (~2-5 MB initial download)
- Bundle PHP FHIRTools dependencies
- Create JavaScript wrapper for PHP functions
- Handle async PHP execution in browser

**Limitations:**
- Initial load time (download WASM + dependencies)
- Browser compatibility (needs WebAssembly support)
- Memory constraints (browser limits)
- Can't access external APIs from PHP

**When to Use Backend Instead:**
- Need to access external FHIR servers
- Processing very large files (>100MB)
- Browser compatibility requirements (IE11)
- Need server-side caching
- Want to track usage analytics server-side

### Alternative: Backend API Hosting (if needed)

| Provider | Free Tier | Ease of Setup | Cost (Paid) | PHP Support | Notes |
|----------|-----------|---------------|-------------|-------------|-------|
| **Railway** | $5 credit/month | ⭐⭐⭐ Easy | $5-20/month | ✅ Good | Generous free tier |
| **Fly.io** | Limited free | ⭐⭐ Medium | $5-15/month | ✅ Good | Docker-based |
| **Render** | Limited free | ⭐⭐⭐ Easy | $7/month | ✅ Good | Good for web services |
| **DigitalOcean App Platform** | ❌ | ⭐⭐ Medium | $5/month | ✅ Excellent | Reliable, affordable |

**Recommendation if Backend Needed:** Railway or DigitalOcean

---

## Decision 3: Rollout Strategy

### Options Comparison

| Strategy | Time to Launch | Features at Launch | Ongoing Work | Risk |
|----------|----------------|-------------------|--------------|------|
| **Option A: Minimal** | 2-3 weeks | Docs only | High | Low |
| **Option B: Full Featured** | 8-10 weeks | Everything | Low | High |
| **Option C: Phased** | 3 weeks initial | Docs + 1 demo | Medium | Low |

### Feature Matrix

| Feature | Option A | Option B | Option C |
|---------|----------|----------|----------|
| Documentation Pages | ✅ | ✅ | ✅ |
| Code Examples | ✅ | ✅ | ✅ |
| Backend API | ❌ | ✅ | ✅ (Phase 2) |
| Serialization Demo | ❌ | ✅ | ✅ (Phase 2) |
| FHIRPath Demo | ❌ | ✅ | ❌ (Phase 3) |
| Model Explorer | ❌ | ✅ | ❌ (Phase 3) |
| Interactive Features | ❌ | ✅ | ⚠️ Limited |

### Recommendation: **Option C: Phased Rollout** ⭐

**Phase 1 (Week 1-3)**: Launch basic site
- Complete documentation
- Static code examples
- Example FHIR resources
- Getting started guide
- **Goal**: Get something useful live quickly

**Phase 2 (Week 4-6)**: Add first demo
- Deploy backend API
- Add serialization demo
- **Goal**: Showcase interactive capabilities

**Phase 3 (Week 7-9)**: Complete feature set
- Add FHIRPath demo
- Add model explorer
- Polish and optimize
- **Goal**: Full featured showcase site

**Reasons for Phased Approach:**
- Quick time to value (something live in 3 weeks)
- Allows for user feedback early
- Can adjust based on community needs
- Lower risk (can stop at any phase if needed)
- Easier to manage development

---

## Decision 4: Architecture Pattern

### Options Comparison

| Approach | Pros | Cons | Best For |
|----------|------|------|----------|
| **php-wasm (Client-side)** | Zero cost, instant execution, no server, offline support | Initial load time, browser-only | Interactive demos |
| **Separate Backend** | Full PHP capabilities, server-side caching, external APIs | Costs money, deployment complexity, CORS | Production apps |
| **Hybrid (php-wasm + Backend)** | Best of both worlds, fallback options | Most complex, dual maintenance | Advanced use cases |

### Recommendation: **php-wasm (Client-side)** ⭐ **NEW!**

**For Showcase Site:**
Use php-wasm for all interactive demos. This gives:
- Zero hosting costs
- Instant user experience
- Complete privacy (data stays in browser)
- Works offline once loaded

**Structure:**
```
Ardenexal/php-fhir-tools (main repo)
  ├── docs/ (GitHub Pages site)
  │   ├── index.html
  │   ├── assets/
  │   │   ├── js/
  │   │   │   ├── php-wasm-loader.js
  │   │   │   └── fhir-demos.js
  │   │   └── php-wasm/ (PHP WASM runtime)
  │   └── lib/ (PHP FHIRTools bundled for WASM)
  └── ...
```

**Reasons:**
- Clear separation of concerns
- Independent deployment pipelines
- Can version API separately
- Easier to scale/maintain
- No impact on main repository

**Implementation:**
```bash
# Create new repository
composer create-project symfony/skeleton php-fhir-tools-api
cd php-fhir-tools-api
composer require ardenexal/fhir-bundle
```

---

## Decision 5: CSS Framework

### Options Comparison

| Framework | Size (min+gzip) | Learning Curve | Customization | Best For |
|-----------|-----------------|----------------|---------------|----------|
| **Custom CSS** | Minimal | Low | Maximum | Full control |
| **Tailwind CSS** | ~30KB | Medium | High | Rapid development |
| **Bootstrap** | ~50KB | Low | Medium | Standard UI |
| **Bulma** | ~30KB | Low | High | Clean CSS |
| **Pure.css** | ~4KB | Low | High | Minimal sites |

### Recommendation: **Modern CSS with CSS Variables** ⭐ **DECISION CONFIRMED**

**Confirmed Approach:**
- Modern CSS with CSS custom properties (variables)
- No framework overhead
- CSS Grid and Flexbox for layouts
- Responsive design with media queries
- Design tokens for consistency

**Sample Structure:**
```
assets/css/
├── variables.css    (Design tokens - colors, spacing, etc.)
├── reset.css        (Normalize)
├── base.css         (Typography, base elements)
├── layout.css       (Grid, containers)
├── components.css   (Buttons, cards, etc.)
└── demos.css        (Demo-specific styles)
```

**Key Features:**
- CSS custom properties for theming
- Dark mode support via CSS variables
- Mobile-first responsive design
- Performance-optimized (no framework bloat)

---

## Decision 6: JavaScript Framework

### Options Comparison

| Framework | Size | Learning Curve | Best For | Overhead |
|-----------|------|----------------|----------|----------|
| **Vanilla JS + Web Components** | 0KB | Low | Reusable components | None |
| **Alpine.js** | 15KB | Low | Lightweight reactivity | Minimal |
| **Vue.js** | 34KB | Medium | Complex UIs | Medium |
| **React** | 45KB | High | SPAs | High |
| **Svelte** | Minimal | Medium | Modern apps | Build step |

### Recommendation: **Vanilla JavaScript + Web Components** ⭐ **DECISION CONFIRMED**

**Confirmed Approach:**
- Vanilla JavaScript (ES6+) for all functionality
- Web Components for reusable interactive parts
- Modern JavaScript features (async/await, fetch, modules)
- No framework dependencies

**Use Web Components For:**
- Interactive demo interfaces (serialization tester, FHIRPath evaluator)
- Reusable UI components (code editors, syntax highlighters)
- Stateful widgets (search, navigation)

**Sample Architecture:**
```javascript
// Web Component for demo
class FHIRSerializationDemo extends HTMLElement {
    connectedCallback() {
        this.render();
        this.attachEventListeners();
    }
}
customElements.define('fhir-serialization-demo', FHIRSerializationDemo);

// Usage in HTML
<fhir-serialization-demo></fhir-serialization-demo>
```

**Benefits:**
- Native browser support (no polyfills needed)
- Encapsulated styles and behavior
- Reusable across pages
- Modern JavaScript features
- Zero framework overhead

---

## Decision 7: Monitoring & Analytics

### Options Comparison

| Solution | Cost | Privacy | Features | Complexity |
|----------|------|---------|----------|------------|
| **Google Analytics** | Free | ⚠️ Cookies | Comprehensive | Low |
| **Plausible** | $9/month | ✅ Privacy-first | Good | Low |
| **Matomo** | Free (self-hosted) | ✅ Full control | Comprehensive | Medium |
| **Simple Analytics** | $19/month | ✅ Privacy-first | Simple | Low |
| **None** | Free | ✅ Perfect | None | None |

### Recommendation: **Plausible Analytics** or **No Analytics Initially** ⭐

**Phase 1**: Launch without analytics
- Focus on building features
- Less complexity
- No privacy concerns

**Phase 2**: Add Plausible Analytics (if budget allows)
- Privacy-friendly (no cookies)
- GDPR compliant
- Simple, clean interface
- $9/month (affordable)

**Budget Alternative**: Google Analytics
- Free
- Comprehensive features
- Requires cookie consent

**Self-hosted Alternative**: Matomo
- Free (self-hosted)
- Full data control
- Requires hosting

---

## Decision 8: Search Functionality

### Options Comparison

| Solution | Cost | Setup | Features | Jekyll Support |
|----------|------|-------|----------|----------------|
| **Jekyll Simple Search** | Free | Easy | Good | ✅ Native |
| **Lunr.js** | Free | Easy | Good | ✅ Popular plugin |
| **Algolia DocSearch** | Free | Medium | Excellent | ✅ Plugin available |
| **Pagefind** | Free | Easy | Good | ⚠️ Manual |
| **Google Custom Search** | Free | Easy | Good | ✅ Embeddable |

### Recommendation: **Jekyll's Recommended Search** ⭐ **DECISION CONFIRMED**

**Confirmed Approach:**
Use Jekyll's recommended search solution, which is typically **Jekyll Simple Search** with Lunr.js:

**Implementation:**
```yaml
# _config.yml
plugins:
  - jekyll-lunr-js-search

lunr_search:
  excludes: [rss.xml, atom.xml]
  stopwords: 'stopwords.txt'
  min_length: 3
```

**Benefits:**
- Native Jekyll integration
- Client-side search (no backend)
- Easy setup with Jekyll plugins
- Good performance for documentation sites
- Works with GitHub Pages

**Alternative**: If Jekyll Simple Search is insufficient, can upgrade to Algolia DocSearch (free for open source)

---

## Decision 9: Custom Domain

### Options Comparison

| Option | Cost | Ease | Professional | SEO |
|--------|------|------|--------------|-----|
| **GitHub Subdomain** | Free | Instant | Good | Good |
| **Custom Domain** | $12/year | Easy | Better | Better |

### Recommendation: **GitHub Subdomain** ⭐ **DECISION CONFIRMED**

**Confirmed Approach**: `ardenexal.github.io/php-fhir-tools`
- Free
- Works immediately
- SSL included
- Professional enough
- No additional configuration needed

---

## Decision 10: Analytics

### Recommendation: **Deferred** ⭐ **DECISION CONFIRMED**

**Confirmed Approach:**
- Launch without analytics
- Can add later if needed (Google Analytics or Plausible)
- Focus on core functionality first

---

## Summary of Recommendations

### Immediate Decisions (Start of Project) ⭐ **UPDATED**

| Decision | Recommendation | Reason |
|----------|----------------|--------|
| **Site Generator** | **Jekyll** ⭐ | Native GitHub Pages, Liquid templates, good search support |
| **CSS Approach** | **Modern CSS with Variables** ⭐ | Clean, performant, no framework bloat |
| **JavaScript** | **Vanilla JS + Web Components** ⭐ | Modern, reusable, zero overhead |
| **Search** | **Jekyll's Recommended (Lunr.js)** ⭐ | Native integration, client-side |
| **Domain** | **GitHub Subdomain** ⭐ | Free, instant, SSL included |
| **Analytics** | **Deferred** ⭐ | Add later if needed |
| **PHP Execution** | **php-wasm (Browser-based)** ⭐ | Zero cost, instant execution, no server needed |
| **Rollout Strategy** | **Phased (Option C)** ⭐ | Quick launch, iterative improvement |

### Deferred Decisions (Can Decide Later)

| Decision | When to Decide | Default |
|----------|----------------|---------|
| Analytics | After launch | Deferred per decision |
| Custom Domain | If needed later | Use GitHub subdomain |
| Backend API | If php-wasm insufficient | Add as fallback option |
| Advanced Features | Based on usage | Add based on feedback |

---

## Implementation Priority

### Must Have (Phase 1)
1. ✅ Jekyll site structure with modern CSS
2. ✅ Landing page
3. ✅ Documentation pages with Liquid templates
4. ✅ Getting started guide
5. ✅ Code examples

### Should Have (Phase 2)
6. ✅ php-wasm integration
7. ✅ Serialization demo (browser-based with Web Components)
8. ✅ Jekyll search integration
9. ✅ Syntax highlighting
10. ✅ Mobile responsive

### Nice to Have (Phase 3+)
10. ⭐ FHIRPath demo
11. ⭐ Model explorer
12. ⭐ Search functionality
13. ⭐ Analytics
14. ⭐ Dark mode

---

## Budget Summary

### Free Option with php-wasm (Recommended) ⭐ **NEW!**
- GitHub Pages: **$0**
- php-wasm (client-side): **$0**
- Domain: **$0** (use GitHub subdomain)
- **Total: $0/month** ✨

**This is now the primary recommendation!**

### Free Option with Backend (Alternative)
- GitHub Pages: **$0**
- Railway: **$0** (free tier, limited)
- Domain: **$0** (use GitHub subdomain)
- **Total: $0/month**

### Paid Option (If Backend Needed)
- GitHub Pages: **$0**
- DigitalOcean App Platform: **$5/month**
- Plausible Analytics: **$9/month**
- Custom Domain: **$1/month** ($12/year)
- **Total: $15/month**

---

## Risk Assessment

### Low Risk Decisions ✅
- Use plain HTML/CSS/JS
- Use php-wasm for demos
- Phased rollout
- Start without analytics
- Use GitHub subdomain

### Medium Risk Decisions ⚠️
- php-wasm browser compatibility (needs WebAssembly)
- Initial load time with WASM (~2-5 MB)

### High Risk Decisions ❌
- None identified (all decisions are reversible)

---

## Next Actions

### This Week
1. ✅ Approve these decisions
2. ✅ Create docs/ directory structure
3. ✅ Build landing page
4. ✅ Enable GitHub Pages
5. ✅ Create basic documentation pages

### Next Week
1. Complete Phase 1 documentation
2. Research php-wasm integration options
3. Create proof-of-concept with php-wasm
4. Bundle FHIRTools for browser use

---

## Questions for Stakeholders

1. **php-wasm**: Are we comfortable with browser-only execution (no backend)?
2. **Timeline**: Is 3 weeks for Phase 1 acceptable, or do we need faster/slower?
3. **Features**: Are interactive demos essential for launch, or can they come later?
4. **Branding**: Do we want a custom domain, or is GitHub subdomain okay?
5. **Analytics**: Do we need usage tracking, or is it optional?

---

**Document Version**: 1.0  
**Last Updated**: 2025-12-31  
**Status**: Ready for Review

---

## Appendix: Quick Start Commands

### Create Site Structure
```bash
cd /path/to/php-fhir-tools

# Create docs directory structure
mkdir -p docs/{assets/{css,js,images,data/examples},pages/{components,demos}}

# Create .nojekyll (disable Jekyll)
touch docs/.nojekyll

# Create basic files
touch docs/index.html
touch docs/assets/css/main.css
touch docs/assets/js/main.js
```

### Enable GitHub Pages
1. Go to repository Settings
2. Click "Pages" in sidebar
3. Source: Deploy from branch
4. Branch: main
5. Folder: /docs
6. Save

### Test Locally
```bash
# Simple Python server
cd docs
python3 -m http.server 8000

# Or PHP
php -S localhost:8000

# Visit http://localhost:8000
```

### Deploy (Automatic via GitHub Pages)
```bash
git add docs/
git commit -m "feat: add GitHub Pages site"
git push origin main

# Site will be live at:
# https://ardenexal.github.io/php-fhir-tools/
```
