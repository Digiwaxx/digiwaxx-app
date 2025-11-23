/**
 * Image Fallback Handler
 *
 * This script provides client-side fallback handling for images.
 * When an image fails to load, it automatically tries alternative sources
 * in the following order:
 * 1. pCloud URL (if available)
 * 2. Local storage URL (if available)
 * 3. Placeholder image
 *
 * Usage:
 * Add these data attributes to your img tags:
 * - data-pcloud-src: The pCloud image URL
 * - data-local-src: The local image URL
 * - data-placeholder-src: The placeholder image URL
 * - onerror="handleImageError(this)"
 */

(function() {
    'use strict';

    // Track which images have been attempted to avoid infinite loops
    var attemptedSources = {};

    /**
     * Handle image load errors
     * @param {HTMLImageElement} img - The image element that failed to load
     */
    window.handleImageError = function(img) {
        var currentSrc = img.src;
        var pcloudSrc = img.getAttribute('data-pcloud-src');
        var localSrc = img.getAttribute('data-local-src');
        var placeholderSrc = img.getAttribute('data-placeholder-src');

        // Create a unique key for this image
        var imgKey = img.id || img.getAttribute('data-img-key') || currentSrc;

        // Initialize tracking for this image
        if (!attemptedSources[imgKey]) {
            attemptedSources[imgKey] = [];
        }

        // Mark current source as attempted
        if (currentSrc && attemptedSources[imgKey].indexOf(currentSrc) === -1) {
            attemptedSources[imgKey].push(currentSrc);
        }

        // Try fallback sources in order
        var fallbackSources = [];

        // Add local source as first fallback (most reliable when pCloud is down)
        if (localSrc && attemptedSources[imgKey].indexOf(localSrc) === -1) {
            fallbackSources.push(localSrc);
        }

        // Add pCloud source as second fallback (in case local was tried first)
        if (pcloudSrc && attemptedSources[imgKey].indexOf(pcloudSrc) === -1) {
            fallbackSources.push(pcloudSrc);
        }

        // Add placeholder as last resort
        if (placeholderSrc && attemptedSources[imgKey].indexOf(placeholderSrc) === -1) {
            fallbackSources.push(placeholderSrc);
        }

        // Try the next available source
        if (fallbackSources.length > 0) {
            var nextSrc = fallbackSources[0];
            attemptedSources[imgKey].push(nextSrc);
            img.src = nextSrc;

            // Add a subtle loading indicator
            img.style.opacity = '0.7';
            img.onload = function() {
                img.style.opacity = '1';
            };
        } else {
            // All sources exhausted - remove onerror to prevent loops
            img.onerror = null;

            // Add a visual indicator that the image failed
            img.alt = img.alt || 'Image not available';
            img.style.opacity = '0.5';
            img.classList.add('image-load-failed');

            // Log for debugging (in development only)
            if (window.console && window.location.hostname === 'localhost') {
                console.warn('Image fallback exhausted for:', imgKey);
            }
        }
    };

    /**
     * Initialize fallback handling for all images with data attributes
     */
    function initializeImageFallbacks() {
        // Find all images with fallback data attributes that don't have onerror set
        var images = document.querySelectorAll('img[data-pcloud-src], img[data-local-src]');

        for (var i = 0; i < images.length; i++) {
            var img = images[i];

            // Only add onerror if not already set
            if (!img.onerror) {
                img.onerror = function() {
                    handleImageError(this);
                };
            }

            // If the image is already broken, trigger the error handler
            if (img.complete && img.naturalWidth === 0) {
                handleImageError(img);
            }
        }
    }

    /**
     * Set a default placeholder for images that have no fallback attributes
     * @param {string} placeholderUrl - The placeholder image URL
     */
    window.setDefaultImagePlaceholder = function(placeholderUrl) {
        var images = document.querySelectorAll('img:not([data-placeholder-src])');

        for (var i = 0; i < images.length; i++) {
            images[i].setAttribute('data-placeholder-src', placeholderUrl);
        }
    };

    /**
     * Preload an image to check if it's available
     * @param {string} src - The image source URL
     * @param {function} onSuccess - Callback when image loads successfully
     * @param {function} onError - Callback when image fails to load
     */
    window.preloadImage = function(src, onSuccess, onError) {
        var img = new Image();
        img.onload = function() {
            if (typeof onSuccess === 'function') {
                onSuccess(src);
            }
        };
        img.onerror = function() {
            if (typeof onError === 'function') {
                onError(src);
            }
        };
        img.src = src;
    };

    /**
     * Check pCloud availability by attempting to load a test image
     */
    window.checkPCloudAvailability = function(callback) {
        var statusUrl = window.location.origin + '/image/status';

        var xhr = new XMLHttpRequest();
        xhr.open('GET', statusUrl, true);
        xhr.timeout = 5000;

        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var status = JSON.parse(xhr.responseText);
                    if (typeof callback === 'function') {
                        callback(status.pcloud_available);
                    }
                } catch (e) {
                    if (typeof callback === 'function') {
                        callback(false);
                    }
                }
            } else {
                if (typeof callback === 'function') {
                    callback(false);
                }
            }
        };

        xhr.onerror = xhr.ontimeout = function() {
            if (typeof callback === 'function') {
                callback(false);
            }
        };

        xhr.send();
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeImageFallbacks);
    } else {
        initializeImageFallbacks();
    }

    // Also run on window load for dynamically loaded images
    window.addEventListener('load', initializeImageFallbacks);

    // Expose a method to reinitialize after dynamic content loads
    window.reinitializeImageFallbacks = initializeImageFallbacks;

})();
