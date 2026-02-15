/**
 * Cache Manager for Service Worker
 * Provides utilities for managing offline cache
 */

export class CacheManager {
    constructor() {
        this.swRegistration = null;
        this.updateAvailable = false;
    }
    
    /**
     * Initialize cache manager and service worker
     */
    async initialize() {
        if (!('serviceWorker' in navigator)) {
            console.warn('Service Worker not supported');
            return false;
        }
        
        try {
            this.swRegistration = await navigator.serviceWorker.register('/service-worker.js');
            console.log('Service Worker registered:', this.swRegistration);
            
            // Listen for updates
            this.swRegistration.addEventListener('updatefound', () => {
                const newWorker = this.swRegistration.installing;
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        this.updateAvailable = true;
                        this.notifyUpdate();
                    }
                });
            });
            
            return true;
        } catch (error) {
            console.error('Service Worker registration failed:', error);
            return false;
        }
    }
    
    /**
     * Check if offline mode is available
     */
    async isOfflineReady() {
        if (!this.swRegistration) {
            return false;
        }
        
        const cache = await caches.open('php-fhir-tools-v1.0.0');
        const keys = await cache.keys();
        return keys.length > 0;
    }
    
    /**
     * Clear all caches
     */
    async clearCache() {
        try {
            const cacheNames = await caches.keys();
            await Promise.all(
                cacheNames.map(name => caches.delete(name))
            );
            console.log('All caches cleared');
            return true;
        } catch (error) {
            console.error('Failed to clear cache:', error);
            return false;
        }
    }
    
    /**
     * Activate update (skip waiting)
     */
    activateUpdate() {
        if (this.swRegistration && this.swRegistration.waiting) {
            this.swRegistration.waiting.postMessage({ type: 'SKIP_WAITING' });
        }
    }
    
    /**
     * Notify user about update
     */
    notifyUpdate() {
        // Dispatch custom event for UI to handle
        const event = new CustomEvent('sw-update-available', {
            detail: { registration: this.swRegistration }
        });
        window.dispatchEvent(event);
    }
    
    /**
     * Get cache storage estimate
     */
    async getStorageEstimate() {
        if ('storage' in navigator && 'estimate' in navigator.storage) {
            return await navigator.storage.estimate();
        }
        return null;
    }
    
    /**
     * Precache specific URLs
     */
    async precacheUrls(urls) {
        if (!this.swRegistration) {
            console.warn('Service Worker not registered');
            return false;
        }
        
        try {
            const cache = await caches.open('php-fhir-tools-v1.0.0');
            await cache.addAll(urls);
            console.log('URLs precached:', urls);
            return true;
        } catch (error) {
            console.error('Failed to precache URLs:', error);
            return false;
        }
    }
}

// Export singleton instance
export const cacheManager = new CacheManager();

// Auto-initialize on load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        cacheManager.initialize();
    });
} else {
    cacheManager.initialize();
}
