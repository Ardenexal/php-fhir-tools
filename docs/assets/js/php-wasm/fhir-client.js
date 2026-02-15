/**
 * FHIR PHP Client Module
 * 
 * Wrapper around PHP-WASM for FHIR-specific operations.
 * Provides high-level methods for serialization, deserialization, and FHIRPath evaluation.
 * 
 * @module php-wasm/fhir-client
 * @author PHP FHIRTools Team
 */

import { phpWasmLoader } from './loader.js';
import { 
    createOperationOutcome, 
    processingError, 
    structureError 
} from '../utils/operation-outcome.js';

/**
 * FHIRPHPClient - High-level FHIR operations using PHP in browser
 */
export class FHIRPHPClient {
    constructor() {
        this.loader = phpWasmLoader;
        this.isReady = false;
    }

    /**
     * Initialize the FHIR client (initializes PHP-WASM if needed)
     * 
     * @param {Function} progressCallback - Optional progress callback
     * @returns {Promise<void>}
     */
    async initialize(progressCallback = null) {
        if (this.isReady) {
            return;
        }

        try {
            await this.loader.initialize(progressCallback);
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
     * Serialize a FHIR resource (JSON to PHP object representation)
     * 
     * @param {Object} fhirResource - FHIR resource as JSON object
     * @param {Object} options - Serialization options
     * @param {string} options.validationMode - 'strict' or 'lenient'
     * @returns {Promise<Object>} Serialization result or OperationOutcome
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
            const validationMode = options.validationMode || 'strict';
            const resourceJson = JSON.stringify(fhirResource);
            
            // Escape the JSON for PHP
            const escapedJson = resourceJson.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            
            // PHP code for serialization
            // In production, this would use actual FHIRSerializationService
            const phpCode = `<?php
// Mock FHIR Serialization
$json = '${escapedJson}';
$resource = json_decode($json, true);

if (!isset($resource['resourceType'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing resourceType'
    ]);
} else {
    // In production: use FHIRSerializationService
    echo json_encode([
        'success' => true,
        'resourceType' => $resource['resourceType'],
        'serialized' => $resource,
        'validationMode' => '${validationMode}'
    ]);
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

            // Parse the result
            const output = result.stdout.trim();
            const parsedResult = JSON.parse(output);
            
            if (!parsedResult.success) {
                return structureError(
                    parsedResult.error || 'Serialization failed',
                    'Unknown'
                );
            }

            return {
                success: true,
                data: parsedResult.serialized,
                resourceType: parsedResult.resourceType,
                validationMode: parsedResult.validationMode
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
     * Deserialize a FHIR resource (PHP object to JSON)
     * 
     * @param {Object} serializedResource - Serialized FHIR resource
     * @param {Object} options - Deserialization options
     * @returns {Promise<Object>} Deserialization result or OperationOutcome
     */
    async deserialize(serializedResource, options = {}) {
        if (!this.isReady) {
            throw processingError(
                'FHIR client not initialized',
                'Call initialize() before using deserialize()',
                ['FHIRPHPClient.deserialize']
            );
        }

        try {
            const resourceJson = JSON.stringify(serializedResource);
            const escapedJson = resourceJson.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            
            // PHP code for deserialization
            const phpCode = `<?php
// Mock FHIR Deserialization
$json = '${escapedJson}';
$resource = json_decode($json, true);

// In production: use FHIRSerializationService
echo json_encode([
    'success' => true,
    'deserialized' => $resource
]);
?>`;

            const result = await this.loader.runPHP(phpCode);
            
            if (result.exitCode !== 0) {
                return processingError(
                    'PHP deserialization failed',
                    result.stderr || 'Unknown error',
                    ['FHIRPHPClient.deserialize']
                );
            }

            const output = result.stdout.trim();
            const parsedResult = JSON.parse(output);
            
            if (!parsedResult.success) {
                return structureError(
                    'Deserialization failed',
                    'Unknown'
                );
            }

            return {
                success: true,
                data: parsedResult.deserialized
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
     * Evaluate a FHIRPath expression against a FHIR resource
     * 
     * @param {Object} fhirResource - FHIR resource to evaluate against
     * @param {string} expression - FHIRPath expression
     * @returns {Promise<Object>} Evaluation result or OperationOutcome
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
            const resourceJson = JSON.stringify(fhirResource);
            const escapedJson = resourceJson.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            const escapedExpr = expression.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            
            // PHP code for FHIRPath evaluation
            const phpCode = `<?php
// Mock FHIRPath Evaluation
$json = '${escapedJson}';
$expression = '${escapedExpr}';
$resource = json_decode($json, true);

// In production: use FHIRPathEvaluator
// Simple path navigation for demo
$result = null;
$parts = explode('.', $expression);

if (count($parts) > 0) {
    $current = $resource;
    foreach ($parts as $part) {
        $part = trim($part);
        if (isset($current[$part])) {
            $current = $current[$part];
        } else {
            $current = null;
            break;
        }
    }
    $result = $current;
}

echo json_encode([
    'success' => true,
    'result' => $result,
    'expression' => $expression
]);
?>`;

            const result = await this.loader.runPHP(phpCode);
            
            if (result.exitCode !== 0) {
                return processingError(
                    'FHIRPath evaluation failed',
                    result.stderr || 'Unknown error',
                    ['FHIRPHPClient.evaluateFHIRPath']
                );
            }

            const output = result.stdout.trim();
            const parsedResult = JSON.parse(output);
            
            return {
                success: true,
                result: parsedResult.result,
                expression: parsedResult.expression
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
     * Check if client is ready for use
     * 
     * @returns {boolean}
     */
    isInitialized() {
        return this.isReady && this.loader.isReady();
    }

    /**
     * Get PHP runtime version
     * 
     * @returns {string|null}
     */
    getPhpVersion() {
        return this.loader.getVersion();
    }
}

// Export singleton instance
export const fhirClient = new FHIRPHPClient();
