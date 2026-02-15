/**
 * FHIR Terminology Service Client
 * 
 * Client for interacting with FHIR terminology servers (tx.fhir.org by default).
 * Supports ValueSet expansion, code validation, code lookup, and subsumption checking.
 * 
 * Specification: https://build.fhir.org/terminology-service.html
 * tx.fhir.org Documentation: https://confluence.hl7.org/spaces/FHIR/pages/79503413/tx.fhir.org+Documentation
 * 
 * @author PHP FHIRTools Team
 */

import { structureError, processingError } from '../utils/operation-outcome.js';

/**
 * Available terminology servers
 * 
 * Note: tx.fhir.org is a community server, not production-grade.
 * Consider using HL7 Europe or Swiss TX for production workloads.
 */
const TX_SERVERS = {
    'tx.fhir.org': 'https://tx.fhir.org',
    'hl7-europe': 'https://tx.hl7europe.eu',
    'swiss': 'https://tx.fhir.ch'
};

/**
 * Current server configuration
 */
let currentServer = 'tx.fhir.org';
let currentVersion = 'r4';

/**
 * Set the terminology server to use
 * 
 * @param {string} server - Server identifier: 'tx.fhir.org' | 'hl7-europe' | 'swiss'
 * @throws {Error} If server identifier is invalid
 */
export function setTerminologyServer(server) {
    if (!TX_SERVERS[server]) {
        throw new Error(`Invalid server: ${server}. Valid options: ${Object.keys(TX_SERVERS).join(', ')}`);
    }
    currentServer = server;
}

/**
 * Set the FHIR version to use
 * 
 * @param {string} version - FHIR version: 'r4' | 'r5'
 */
export function setFHIRVersion(version) {
    if (!['r4', 'r5'].includes(version)) {
        throw new Error(`Invalid FHIR version: ${version}. Valid options: r4, r5`);
    }
    currentVersion = version;
}

/**
 * Get the current terminology server base URL
 * 
 * @returns {string} Base URL with version
 */
function getBaseUrl() {
    return `${TX_SERVERS[currentServer]}/${currentVersion}`;
}

/**
 * Make a POST request to the terminology server
 * 
 * @param {string} path - API path (e.g., '/ValueSet/$expand')
 * @param {Object} parameters - FHIR Parameters resource
 * @returns {Promise<Object>} Response (ValueSet, Parameters, or OperationOutcome)
 */
async function post(path, parameters) {
    try {
        const response = await fetch(`${getBaseUrl()}${path}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/fhir+json',
                'Accept': 'application/fhir+json'
            },
            body: JSON.stringify(parameters)
        });

        if (!response.ok) {
            const errorText = await response.text();
            return processingError(
                `Terminology server request failed: ${response.status} ${response.statusText}`,
                errorText
            );
        }

        return await response.json();
    } catch (error) {
        return processingError(
            'Failed to connect to terminology server',
            error.message
        );
    }
}

/**
 * Expand a ValueSet
 * 
 * Operation: ValueSet/$expand
 * Specification: https://www.hl7.org/fhir/valueset-operation-expand.html
 * 
 * @param {string} url - Canonical URL of the ValueSet
 * @param {Object} [options] - Expansion options
 * @param {string} [options.filter] - Filter text to apply
 * @param {number} [options.count] - Maximum number of codes to return
 * @param {number} [options.offset] - Offset for paging
 * @param {boolean} [options.includeDesignations] - Include designations
 * @returns {Promise<Object>} Expanded ValueSet or OperationOutcome
 * 
 * @example
 * const expanded = await expandValueSet(
 *     'http://hl7.org/fhir/ValueSet/administrative-gender',
 *     { count: 10, filter: 'male' }
 * );
 */
export async function expandValueSet(url, options = {}) {
    const parameters = {
        resourceType: 'Parameters',
        parameter: [
            { name: 'url', valueUri: url }
        ]
    };

    if (options.filter) {
        parameters.parameter.push({ name: 'filter', valueString: options.filter });
    }
    if (options.count !== undefined) {
        parameters.parameter.push({ name: 'count', valueInteger: options.count });
    }
    if (options.offset !== undefined) {
        parameters.parameter.push({ name: 'offset', valueInteger: options.offset });
    }
    if (options.includeDesignations !== undefined) {
        parameters.parameter.push({ name: 'includeDesignations', valueBoolean: options.includeDesignations });
    }

    return await post('/ValueSet/$expand', parameters);
}

/**
 * Validate a code against a ValueSet
 * 
 * Operation: ValueSet/$validate-code
 * Specification: https://www.hl7.org/fhir/valueset-operation-validate-code.html
 * 
 * @param {Object} params - Validation parameters
 * @param {string} params.url - Canonical URL of the ValueSet
 * @param {string} params.code - Code to validate
 * @param {string} [params.system] - Code system URI
 * @param {string} [params.systemVersion] - Code system version
 * @param {string} [params.display] - Display text
 * @returns {Promise<Object>} Parameters resource with result or OperationOutcome
 * 
 * @example
 * const result = await validateCode({
 *     url: 'http://hl7.org/fhir/ValueSet/administrative-gender',
 *     code: 'male',
 *     system: 'http://hl7.org/fhir/administrative-gender'
 * });
 * // result.parameter[0].name === 'result'
 * // result.parameter[0].valueBoolean === true
 */
export async function validateCode({ url, code, system, systemVersion, display }) {
    const parameters = {
        resourceType: 'Parameters',
        parameter: [
            { name: 'url', valueUri: url },
            { name: 'code', valueCode: code }
        ]
    };

    if (system) {
        parameters.parameter.push({ name: 'system', valueUri: system });
    }
    if (systemVersion) {
        parameters.parameter.push({ name: 'systemVersion', valueString: systemVersion });
    }
    if (display) {
        parameters.parameter.push({ name: 'display', valueString: display });
    }

    return await post('/ValueSet/$validate-code', parameters);
}

/**
 * Look up code details in a CodeSystem
 * 
 * Operation: CodeSystem/$lookup
 * Specification: https://www.hl7.org/fhir/codesystem-operation-lookup.html
 * 
 * @param {string} system - Code system URI
 * @param {string} code - Code to look up
 * @param {Object} [options] - Lookup options
 * @param {string} [options.version] - Code system version
 * @param {string} [options.displayLanguage] - Preferred display language
 * @param {string[]} [options.property] - Properties to return
 * @returns {Promise<Object>} Parameters resource with code details or OperationOutcome
 * 
 * @example
 * const details = await lookupCode(
 *     'http://loinc.org',
 *     '29463-7',
 *     { displayLanguage: 'en' }
 * );
 */
export async function lookupCode(system, code, options = {}) {
    const parameters = {
        resourceType: 'Parameters',
        parameter: [
            { name: 'system', valueUri: system },
            { name: 'code', valueCode: code }
        ]
    };

    if (options.version) {
        parameters.parameter.push({ name: 'version', valueString: options.version });
    }
    if (options.displayLanguage) {
        parameters.parameter.push({ name: 'displayLanguage', valueCode: options.displayLanguage });
    }
    if (options.property) {
        options.property.forEach(prop => {
            parameters.parameter.push({ name: 'property', valueCode: prop });
        });
    }

    return await post('/CodeSystem/$lookup', parameters);
}

/**
 * Check subsumption relationship between two codes
 * 
 * Operation: CodeSystem/$subsumes
 * Specification: https://www.hl7.org/fhir/codesystem-operation-subsumes.html
 * 
 * @param {string} system - Code system URI
 * @param {string} codeA - First code
 * @param {string} codeB - Second code
 * @param {string} [version] - Code system version
 * @returns {Promise<Object>} Parameters resource with outcome or OperationOutcome
 * 
 * @example
 * const result = await checkSubsumption(
 *     'http://snomed.info/sct',
 *     '233604007',  // Pneumonia
 *     '50417007'    // Lower respiratory tract infection
 * );
 * // result.parameter[0].name === 'outcome'
 * // result.parameter[0].valueCode === 'subsumes' | 'subsumed-by' | 'equivalent' | 'not-subsumed'
 */
export async function checkSubsumption(system, codeA, codeB, version = null) {
    const parameters = {
        resourceType: 'Parameters',
        parameter: [
            { name: 'system', valueUri: system },
            { name: 'codeA', valueCode: codeA },
            { name: 'codeB', valueCode: codeB }
        ]
    };

    if (version) {
        parameters.parameter.push({ name: 'version', valueString: version });
    }

    return await post('/CodeSystem/$subsumes', parameters);
}

/**
 * Get available terminology servers
 * 
 * @returns {Object} Map of server identifiers to base URLs
 */
export function getAvailableServers() {
    return { ...TX_SERVERS };
}

/**
 * Get current server configuration
 * 
 * @returns {Object} Current server and version
 */
export function getCurrentConfig() {
    return {
        server: currentServer,
        version: currentVersion,
        baseUrl: getBaseUrl()
    };
}
