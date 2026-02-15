/**
 * FHIR PHP Client Module
 *
 * Wrapper around PHP-WASM for FHIR-specific operations.
 * Uses the real FHIRPathService and standalone serialization validators
 * running in PHP WebAssembly.
 *
 * @module php-wasm/fhir-client
 */

import { phpWasmLoader } from './loader.js';
import {
    createOperationOutcome,
    processingError,
    structureError
} from '../utils/operation-outcome.js';

/**
 * Safely encode a string for embedding in PHP code via base64.
 * Avoids all escaping issues with quotes, backslashes, etc.
 *
 * @param {string} str
 * @returns {string} base64-encoded string
 */
function toPhpBase64(str) {
    return btoa(unescape(encodeURIComponent(str)));
}

/**
 * FHIRPHPClient - High-level FHIR operations using PHP in browser
 */
export class FHIRPHPClient {
    constructor() {
        this.loader = phpWasmLoader;
        this.isReady = false;
        this._bundles = ['fhirpath'];
    }

    /**
     * Initialize the FHIR client (initializes PHP-WASM if needed)
     *
     * @param {Function} progressCallback - Optional progress callback
     * @param {string[]} bundles - Bundles to load (default: ['fhirpath'])
     * @returns {Promise<void>}
     */
    async initialize(progressCallback = null, bundles = null) {
        if (this.isReady) {
            return;
        }

        try {
            const bundlesToLoad = bundles || this._bundles;
            await this.loader.initialize(progressCallback, bundlesToLoad);
            this.isReady = true;
        } catch (error) {
            throw processingError(
                'Failed to initialize FHIR client',
                error.message,
                ['FHIRPHPClient']
            );
        }
    }

    /**
     * Ensure a specific bundle is loaded (loads it if not already present).
     *
     * @param {string} bundleName
     */
    async ensureBundle(bundleName) {
        if (!this.isReady) {
            throw new Error('Client not initialized');
        }
        await this.loader.initialize(null, [bundleName]);
    }

    /**
     * Evaluate a FHIRPath expression against a FHIR resource.
     * Uses the real PHP FHIRPathService running in WebAssembly.
     *
     * @param {Object} fhirResource - FHIR resource to evaluate against
     * @param {string} expression - FHIRPath expression
     * @returns {Promise<Object>} {success, value, expression, count} or OperationOutcome
     */
    async evaluateFHIRPath(fhirResource, expression) {
        if (!this.isReady) {
            throw processingError(
                'FHIR client not initialized',
                'Call initialize() before using evaluateFHIRPath()',
                ['FHIRPHPClient.evaluateFHIRPath']
            );
        }

        try {
            const resourceB64 = toPhpBase64(JSON.stringify(fhirResource));
            const expressionB64 = toPhpBase64(expression);

            const phpCode = `<?php
require_once '/fhir/bootstrap.php';

use Ardenexal\\FHIRTools\\Component\\FHIRPath\\Service\\FHIRPathService;

try {
    $resourceJson = base64_decode('${resourceB64}');
    $expression = base64_decode('${expressionB64}');
    $resource = json_decode($resourceJson, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON: ' . json_last_error_msg()]);
        exit;
    }

    $service = new FHIRPathService();
    $collection = $service->evaluate($expression, $resource);

    $items = $collection->toArray();
    echo json_encode([
        'success' => true,
        'value' => $items,
        'expression' => $expression,
        'count' => $collection->count()
    ]);
} catch (\\Throwable $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'errorClass' => get_class($e)
    ]);
}
?>`;

            const result = await this.loader.runPHP(phpCode);

            if (result.exitCode !== 0) {
                return processingError(
                    'FHIRPath evaluation failed',
                    result.stderr || 'PHP execution error',
                    ['FHIRPHPClient.evaluateFHIRPath']
                );
            }

            const output = result.stdout.trim();
            if (!output) {
                return processingError(
                    'FHIRPath evaluation returned no output',
                    result.stderr || 'Empty response from PHP',
                    ['FHIRPHPClient.evaluateFHIRPath']
                );
            }

            const parsed = JSON.parse(output);

            if (!parsed.success) {
                return processingError(
                    parsed.error || 'FHIRPath evaluation failed',
                    parsed.errorClass ? `[${parsed.errorClass}] ${parsed.error}` : parsed.error,
                    [expression]
                );
            }

            return {
                success: true,
                value: parsed.value,
                expression: parsed.expression,
                count: parsed.count
            };

        } catch (error) {
            console.error('FHIRPath evaluation error:', error);
            return processingError(
                'FHIRPath evaluation failed',
                error.message,
                ['FHIRPHPClient.evaluateFHIRPath']
            );
        }
    }

    /**
     * Validate a FHIRPath expression syntax without evaluating it.
     *
     * @param {string} expression - FHIRPath expression to validate
     * @returns {Promise<Object>} {valid: boolean, error?: string}
     */
    async validateFHIRPath(expression) {
        if (!this.isReady) {
            throw new Error('Client not initialized');
        }

        const expressionB64 = toPhpBase64(expression);

        const phpCode = `<?php
require_once '/fhir/bootstrap.php';

use Ardenexal\\FHIRTools\\Component\\FHIRPath\\Service\\FHIRPathService;

try {
    $expression = base64_decode('${expressionB64}');
    $service = new FHIRPathService();
    $valid = $service->validate($expression);

    echo json_encode(['valid' => $valid]);
} catch (\\Throwable $e) {
    echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
}
?>`;

        const result = await this.loader.runPHP(phpCode);
        const parsed = JSON.parse(result.stdout.trim());
        return parsed;
    }

    /**
     * Serialize a FHIR resource — validates structure and returns formatted JSON.
     * Uses the standalone validators (non-Symfony) from the Serialization component.
     *
     * @param {Object} fhirResource - FHIR resource as JSON object
     * @param {Object} options - {validationMode: 'strict'|'lenient', format: 'json'|'xml'}
     * @returns {Promise<Object>} Validation/serialization result or OperationOutcome
     */
    async serialize(fhirResource, options = {}) {
        if (!this.isReady) {
            throw processingError(
                'FHIR client not initialized',
                'Call initialize() before using serialize()',
                ['FHIRPHPClient.serialize']
            );
        }

        try {
            await this.ensureBundle('serialization');

            const resourceB64 = toPhpBase64(JSON.stringify(fhirResource));
            const format = options.format || 'json';

            const phpCode = `<?php
require_once '/fhir/bootstrap.php';

try {
    $resourceJson = base64_decode('${resourceB64}');
    $resource = json_decode($resourceJson, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON: ' . json_last_error_msg()]);
        exit;
    }

    $errors = [];

    // Validate basic FHIR structure
    if (!isset($resource['resourceType'])) {
        $errors[] = 'Missing required field: resourceType';
    }

    if (isset($resource['resourceType']) && !is_string($resource['resourceType'])) {
        $errors[] = 'resourceType must be a string';
    }

    // Validate id format if present
    if (isset($resource['id']) && !preg_match('/^[A-Za-z0-9\\-\\.]{1,64}$/', (string) $resource['id'])) {
        $errors[] = 'Invalid id format: must match [A-Za-z0-9\\-\\.]{1,64}';
    }

    // Validate meta if present
    if (isset($resource['meta'])) {
        if (!is_array($resource['meta'])) {
            $errors[] = 'meta must be an object';
        }
        if (isset($resource['meta']['versionId']) && !is_string($resource['meta']['versionId'])) {
            $errors[] = 'meta.versionId must be a string';
        }
    }

    $format = '${format}';
    $output = null;

    if ($format === 'xml' && empty($errors)) {
        // Convert JSON to basic FHIR XML
        $xml = new \\DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;
        $root = $xml->createElementNS('http://hl7.org/fhir', $resource['resourceType']);
        $xml->appendChild($root);

        foreach ($resource as $key => $value) {
            if ($key === 'resourceType') continue;
            if (is_scalar($value)) {
                $el = $xml->createElement($key);
                $el->setAttribute('value', (string) $value);
                $root->appendChild($el);
            }
        }
        $output = $xml->saveXML();
    } else {
        $output = json_encode($resource, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    echo json_encode([
        'success' => empty($errors),
        'errors' => $errors,
        'resourceType' => $resource['resourceType'] ?? null,
        'output' => $output,
        'format' => $format
    ]);
} catch (\\Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>`;

            const result = await this.loader.runPHP(phpCode);

            if (result.exitCode !== 0) {
                return processingError(
                    'PHP serialization failed',
                    result.stderr || 'Unknown error',
                    ['FHIRPHPClient.serialize']
                );
            }

            const parsed = JSON.parse(result.stdout.trim());

            if (!parsed.success) {
                if (parsed.errors && parsed.errors.length > 0) {
                    return structureError(parsed.errors.join('; '), parsed.resourceType || 'Unknown');
                }
                return structureError(parsed.error || 'Serialization failed', 'Unknown');
            }

            return {
                success: true,
                data: JSON.parse(parsed.output),
                output: parsed.output,
                resourceType: parsed.resourceType,
                format: parsed.format
            };

        } catch (error) {
            console.error('Serialization error:', error);
            return processingError(
                'Serialization failed',
                error.message,
                ['FHIRPHPClient.serialize']
            );
        }
    }

    /**
     * Deserialize FHIR data — parse and validate JSON or XML input.
     *
     * @param {string} input - Raw FHIR JSON or XML string
     * @param {Object} options - {format: 'json'|'xml'}
     * @returns {Promise<Object>} Deserialization result or OperationOutcome
     */
    async deserialize(input, options = {}) {
        if (!this.isReady) {
            throw processingError(
                'FHIR client not initialized',
                'Call initialize() before using deserialize()',
                ['FHIRPHPClient.deserialize']
            );
        }

        try {
            await this.ensureBundle('serialization');

            const inputB64 = toPhpBase64(typeof input === 'string' ? input : JSON.stringify(input));
            const format = options.format || 'json';

            const phpCode = `<?php
require_once '/fhir/bootstrap.php';

try {
    $input = base64_decode('${inputB64}');
    $format = '${format}';
    $errors = [];

    if ($format === 'xml') {
        libxml_use_internal_errors(true);
        $dom = new \\DOMDocument();
        $loaded = $dom->loadXML($input);

        if (!$loaded) {
            foreach (libxml_get_errors() as $err) {
                $errors[] = "Line {$err->line}: " . trim($err->message);
            }
            libxml_clear_errors();
        }

        // Convert XML to JSON representation
        $resource = [];
        if ($loaded && $dom->documentElement) {
            $resource['resourceType'] = $dom->documentElement->localName;
            foreach ($dom->documentElement->childNodes as $node) {
                if ($node->nodeType === XML_ELEMENT_NODE) {
                    $resource[$node->localName] = $node->getAttribute('value') ?: $node->textContent;
                }
            }
        }

        echo json_encode([
            'success' => empty($errors),
            'errors' => $errors,
            'data' => $resource,
            'format' => 'xml'
        ]);
    } else {
        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode([
                'success' => false,
                'errors' => ['Invalid JSON: ' . json_last_error_msg()],
                'format' => 'json'
            ]);
            exit;
        }

        if (!isset($data['resourceType'])) {
            $errors[] = 'Missing required field: resourceType';
        }

        echo json_encode([
            'success' => empty($errors),
            'errors' => $errors,
            'data' => $data,
            'format' => 'json'
        ]);
    }
} catch (\\Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>`;

            const result = await this.loader.runPHP(phpCode);

            if (result.exitCode !== 0) {
                return processingError(
                    'PHP deserialization failed',
                    result.stderr || 'Unknown error',
                    ['FHIRPHPClient.deserialize']
                );
            }

            const parsed = JSON.parse(result.stdout.trim());

            if (!parsed.success) {
                if (parsed.errors && parsed.errors.length > 0) {
                    return structureError(parsed.errors.join('; '), 'Unknown');
                }
                return structureError(parsed.error || 'Deserialization failed', 'Unknown');
            }

            return {
                success: true,
                data: parsed.data,
                format: parsed.format
            };

        } catch (error) {
            console.error('Deserialization error:', error);
            return processingError(
                'Deserialization failed',
                error.message,
                ['FHIRPHPClient.deserialize']
            );
        }
    }

    /**
     * Check if client is ready for use
     * @returns {boolean}
     */
    isInitialized() {
        return this.isReady && this.loader.isReady();
    }

    /**
     * Get PHP runtime version
     * @returns {string|null}
     */
    getPhpVersion() {
        return this.loader.getVersion();
    }
}

// Export singleton instance
export const fhirClient = new FHIRPHPClient();
