/**
 * Service Worker for PHP FHIRTools GitHub Pages
 * Provides offline caching for assets, examples, and WASM files
 * Phase 8 Implementation
 */

const CACHE_VERSION = 'v2.0.0';
const CACHE_NAME = `php-fhir-tools-${CACHE_VERSION}`;

// Assets to cache immediately on install
const PRECACHE_ASSETS = [
    '/',
    '/index.html',
    '/404.html',
    '/pages/getting-started.html',
    '/assets/css/main.css',
    '/assets/js/main.js',
    '/assets/js/php-wasm/loader.js',
    '/assets/js/php-wasm/fhir-client.js',
    '/assets/js/utils/operation-outcome.js',
    '/assets/js/terminology/tx-client.js',
    '/assets/php-bundles/fhirpath-bundle.json',
    '/assets/php-bundles/serialization-bundle.json'
];

// Example FHIR resources to cache
const EXAMPLE_RESOURCES = [
    '/assets/data/examples/patient-simple.json',
    '/assets/data/examples/patient-complex.json',
    '/assets/data/examples/observation-vital-signs.json',
    '/assets/data/examples/bundle-transaction.json',
    '/assets/data/examples/medication-request.json',
    '/assets/data/examples/practitioner.json'
];

// URLs that should always be fetched from network (no caching)
const NETWORK_ONLY_PATTERNS = [
    /^https:\/\/tx\.fhir\.org/,  // Terminology server
    /^https:\/\/.*\.fhir\.org/,  // Other FHIR servers
    /\/_site\//,                  // Jekyll build artifacts
    /\/api\//                     // API endpoints
];

// Install event - precache essential assets
self.addEventListener('install', (event) => {
    console.log('[ServiceWorker] Install event');
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[ServiceWorker] Precaching assets');
                return cache.addAll([...PRECACHE_ASSETS, ...EXAMPLE_RESOURCES]);
            })
            .then(() => {
                console.log('[ServiceWorker] Skip waiting');
                return self.skipWaiting();
            })
            .catch((error) => {
                console.error('[ServiceWorker] Precache failed:', error);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('[ServiceWorker] Activate event');
    
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        if (cacheName !== CACHE_NAME) {
                            console.log('[ServiceWorker] Deleting old cache:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('[ServiceWorker] Claiming clients');
                return self.clients.claim();
            })
    );
});

// Fetch event - serve from cache when available, with network fallback
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Skip cross-origin requests (except WASM and CDN assets)
    if (url.origin !== location.origin && 
        !url.href.includes('cdn.jsdelivr.net') &&
        !url.href.endsWith('.wasm')) {
        return;
    }
    
    // Network-only patterns (terminology services, APIs)
    if (NETWORK_ONLY_PATTERNS.some(pattern => pattern.test(request.url))) {
        event.respondWith(fetch(request));
        return;
    }
    
    // Cache-first strategy for all other requests
    event.respondWith(
        caches.match(request)
            .then((cachedResponse) => {
                if (cachedResponse) {
                    console.log('[ServiceWorker] Serving from cache:', request.url);
                    return cachedResponse;
                }
                
                // Not in cache, fetch from network
                return fetch(request)
                    .then((networkResponse) => {
                        // Don't cache non-successful responses
                        if (!networkResponse || networkResponse.status !== 200) {
                            return networkResponse;
                        }
                        
                        // Clone the response
                        const responseToCache = networkResponse.clone();
                        
                        // Cache for future use (except HTML pages to ensure fresh content)
                        // Always cache .wasm files and PHP bundles for performance
                        if (!request.url.endsWith('.html') || request.url.endsWith('.wasm')) {
                            caches.open(CACHE_NAME)
                                .then((cache) => {
                                    console.log('[ServiceWorker] Caching new resource:', request.url);
                                    cache.put(request, responseToCache);
                                });
                        }
                        
                        return networkResponse;
                    })
                    .catch((error) => {
                        console.error('[ServiceWorker] Fetch failed:', error);
                        
                        // Return offline page if available
                        if (request.destination === 'document') {
                            return caches.match('/404.html');
                        }
                        
                        throw error;
                    });
            })
    );
});

// Message event - allow manual cache refresh
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data && event.data.type === 'CLEAR_CACHE') {
        event.waitUntil(
            caches.delete(CACHE_NAME)
                .then(() => {
                    console.log('[ServiceWorker] Cache cleared');
                    return self.registration.unregister();
                })
        );
    }
});

// Background sync for future enhancement
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-fhir-resources') {
        event.waitUntil(
            // Placeholder for future sync functionality
            Promise.resolve()
        );
    }
});
