---
layout: default
title: Home
description: PHP FHIRTools - Build FHIR-compliant applications with modern PHP. Components for serialization, FHIRPath evaluation, and FHIR resource models.
---

<div class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">PHP FHIRTools</h1>
            <p class="hero-subtitle">Build FHIR-compliant applications with modern PHP</p>
            <p class="hero-description">
                A comprehensive toolkit for working with FHIR resources in PHP applications. 
                Includes serialization, FHIRPath evaluation, model generation, and Symfony integration.
            </p>
            <div class="hero-actions">
                <a href="{{ '/pages/getting-started.html' | relative_url }}" class="btn btn-primary">Get Started</a>
                <a href="https://github.com/Ardenexal/php-fhir-tools" class="btn btn-secondary" target="_blank" rel="noopener">
                    <svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
                    </svg>
                    View on GitHub
                </a>
            </div>
        </div>
        <div class="hero-code">
```php
<?php
use Ardenexal\FHIRBundle\Serialization\FHIRSerializationService;
use Ardenexal\FHIRBundle\Models\R4\Resource\Patient;

// Create a FHIR Patient resource
$patient = new Patient();
$patient->setId('example-123');
$patient->setActive(true);

// Serialize to JSON
$serializer = new FHIRSerializationService();
$json = $serializer->serialize($patient);

// Deserialize from JSON
$patient = $serializer->deserialize($json, Patient::class);
```
        </div>
    </div>
</div>

<div class="features">
    <div class="container">
        <h2 class="section-title">Features</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">🔧</div>
                <h3>FHIRBundle</h3>
                <p>Seamless Symfony integration with dependency injection, event system, and service configuration.</p>
                <a href="{{ '/pages/components/fhir-bundle.html' | relative_url }}" class="feature-link">Learn more →</a>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🔄</div>
                <h3>Serialization</h3>
                <p>High-performance JSON and XML serialization with validation, error handling, and FHIR compliance.</p>
                <a href="{{ '/pages/components/serialization.html' | relative_url }}" class="feature-link">Learn more →</a>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🔍</div>
                <h3>FHIRPath</h3>
                <p>Full FHIRPath expression evaluator for querying and extracting data from FHIR resources.</p>
                <a href="{{ '/pages/components/fhirpath.html' | relative_url }}" class="feature-link">Learn more →</a>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📦</div>
                <h3>Models</h3>
                <p>Complete FHIR resource models for R4, R4B, and R5 with type safety and auto-completion.</p>
                <a href="{{ '/pages/components/models.html' | relative_url }}" class="feature-link">Learn more →</a>
            </div>
        </div>
    </div>
</div>

<div class="demos-section">
    <div class="container">
        <h2 class="section-title">Interactive Demos</h2>
        <p class="section-description">
            Try our interactive demos powered by php-wasm. All processing happens in your browser - no server required!
        </p>
        <div class="demo-grid">
            <div class="demo-card">
                <h3>Serialization Demo</h3>
                <p>Test JSON and XML serialization with real FHIR resources. Validate, serialize, and deserialize interactively.</p>
                <a href="{{ '/pages/demos/serialization.html' | relative_url }}" class="btn btn-outline">Try Demo</a>
            </div>
            
            <div class="demo-card">
                <h3>FHIRPath Evaluator</h3>
                <p>Evaluate FHIRPath expressions against FHIR resources. See results in real-time with step-by-step evaluation.</p>
                <a href="{{ '/pages/demos/fhirpath.html' | relative_url }}" class="btn btn-outline">Try Demo</a>
            </div>
            
            <div class="demo-card">
                <h3>Model Explorer</h3>
                <p>Browse FHIR resource models across versions (R4, R4B, R5). Explore properties, types, and cardinality.</p>
                <a href="{{ '/pages/demos/models.html' | relative_url }}" class="btn btn-outline">Try Demo</a>
            </div>
        </div>
    </div>
</div>

<div class="cta-section">
    <div class="container">
        <h2>Ready to get started?</h2>
        <p>Install PHP FHIRTools and start building FHIR-compliant applications today.</p>
        <div class="cta-actions">
            <a href="{{ '/pages/getting-started.html' | relative_url }}" class="btn btn-primary btn-lg">Get Started</a>
            <a href="https://github.com/Ardenexal/php-fhir-tools" class="btn btn-secondary btn-lg" target="_blank" rel="noopener">View Documentation</a>
        </div>
    </div>
</div>
