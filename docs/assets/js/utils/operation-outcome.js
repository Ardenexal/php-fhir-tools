/**
 * FHIR OperationOutcome Utility
 * 
 * Creates FHIR-compliant OperationOutcome resources for error handling.
 * Normative R4/R5 specification: https://fhir.hl7.org/fhir/operationoutcome.html
 * 
 * @author PHP FHIRTools Team
 */

/**
 * Create a FHIR OperationOutcome resource
 * 
 * @param {Object} params - OperationOutcome parameters
 * @param {string} params.severity - Issue severity: 'fatal' | 'error' | 'warning' | 'information'
 * @param {string} params.code - Issue type code (IssueType binding)
 * @param {string} params.details - Human-readable details text
 * @param {string} [params.diagnostics] - Additional diagnostic information
 * @param {string[]} [params.expression] - FHIRPath expressions pointing to error locations
 * @returns {Object} FHIR OperationOutcome resource
 * 
 * @see https://fhir.hl7.org/fhir/operationoutcome-definitions.html
 * @see https://fhir.hl7.org/fhir/valueset-issue-severity.html
 * @see https://fhir.hl7.org/fhir/valueset-issue-type.html
 */
export function createOperationOutcome({ severity, code, details, diagnostics, expression = [] }) {
    return {
        resourceType: 'OperationOutcome',
        issue: [{
            severity,
            code,
            details: { text: details },
            ...(diagnostics && { diagnostics }),
            ...(expression.length > 0 && { expression })
        }]
    };
}

/**
 * Create a validation error OperationOutcome
 * 
 * Used when resource validation fails (required fields, invalid values, etc.)
 * 
 * @param {string} details - Description of the validation error
 * @param {string|string[]} expression - FHIRPath expression(s) pointing to error location
 * @param {string} [diagnostics] - Additional diagnostic information
 * @returns {Object} FHIR OperationOutcome resource
 * 
 * @example
 * const outcome = validationError(
 *     'Patient.birthDate is required',
 *     'Patient.birthDate'
 * );
 */
export function validationError(details, expression, diagnostics = null) {
    const expressions = Array.isArray(expression) ? expression : [expression];
    return createOperationOutcome({
        severity: 'error',
        code: 'required',
        details,
        diagnostics,
        expression: expressions
    });
}

/**
 * Create a structure error OperationOutcome
 * 
 * Used when resource structure is invalid (JSON parse errors, schema violations, etc.)
 * 
 * @param {string} details - Description of the structure error
 * @param {string} [diagnostics] - Additional diagnostic information
 * @param {string[]} [expression] - FHIRPath expressions (if applicable)
 * @returns {Object} FHIR OperationOutcome resource
 * 
 * @example
 * const outcome = structureError(
 *     'Invalid JSON format',
 *     'Unexpected token at line 5'
 * );
 */
export function structureError(details, diagnostics = null, expression = []) {
    return createOperationOutcome({
        severity: 'error',
        code: 'structure',
        details,
        diagnostics,
        expression
    });
}

/**
 * Create a processing error OperationOutcome
 * 
 * Used when processing fails (serialization, evaluation, etc.)
 * 
 * @param {string} details - Description of the processing error
 * @param {string} [diagnostics] - Additional diagnostic information
 * @param {string[]} [expression] - FHIRPath expressions (if applicable)
 * @returns {Object} FHIR OperationOutcome resource
 * 
 * @example
 * const outcome = processingError(
 *     'Serialization failed',
 *     error.message,
 *     ['Resource']
 * );
 */
export function processingError(details, diagnostics = null, expression = []) {
    return createOperationOutcome({
        severity: 'error',
        code: 'exception',
        details,
        diagnostics,
        expression
    });
}

/**
 * Create an informational message OperationOutcome
 * 
 * Used for informational messages that are not errors
 * 
 * @param {string} details - Informational message
 * @param {string} [diagnostics] - Additional information
 * @returns {Object} FHIR OperationOutcome resource
 * 
 * @example
 * const outcome = informationalMessage(
 *     'Resource processed successfully',
 *     'Created Patient/123'
 * );
 */
export function informationalMessage(details, diagnostics = null) {
    return createOperationOutcome({
        severity: 'information',
        code: 'informational',
        details,
        diagnostics,
        expression: []
    });
}

/**
 * Create a warning message OperationOutcome
 * 
 * Used for warnings that don't prevent processing but should be noted
 * 
 * @param {string} details - Warning message
 * @param {string|string[]} [expression] - FHIRPath expression(s) (if applicable)
 * @param {string} [diagnostics] - Additional information
 * @returns {Object} FHIR OperationOutcome resource
 * 
 * @example
 * const outcome = warningMessage(
 *     'Deprecated field usage',
 *     'Patient.animal',
 *     'This field is deprecated in R4'
 * );
 */
export function warningMessage(details, expression = [], diagnostics = null) {
    const expressions = Array.isArray(expression) ? expression : [expression].filter(Boolean);
    return createOperationOutcome({
        severity: 'warning',
        code: 'business-rule',
        details,
        diagnostics,
        expression: expressions
    });
}

/**
 * Check if a response is an OperationOutcome
 * 
 * @param {Object} response - Response object to check
 * @returns {boolean} True if response is an OperationOutcome
 */
export function isOperationOutcome(response) {
    return response && response.resourceType === 'OperationOutcome';
}

/**
 * Check if an OperationOutcome contains errors
 * 
 * @param {Object} outcome - OperationOutcome resource
 * @returns {boolean} True if outcome contains error or fatal issues
 */
export function hasErrors(outcome) {
    if (!isOperationOutcome(outcome)) return false;
    return outcome.issue.some(issue => 
        issue.severity === 'error' || issue.severity === 'fatal'
    );
}

/**
 * Get all error messages from an OperationOutcome
 * 
 * @param {Object} outcome - OperationOutcome resource
 * @returns {string[]} Array of error message strings
 */
export function getErrorMessages(outcome) {
    if (!isOperationOutcome(outcome)) return [];
    return outcome.issue
        .filter(issue => issue.severity === 'error' || issue.severity === 'fatal')
        .map(issue => issue.details?.text || issue.diagnostics || 'Unknown error');
}

/**
 * Format an OperationOutcome for display
 * 
 * @param {Object} outcome - OperationOutcome resource
 * @returns {string} Formatted string representation
 */
export function formatOperationOutcome(outcome) {
    if (!isOperationOutcome(outcome)) return 'Invalid OperationOutcome';
    
    return outcome.issue.map(issue => {
        const severity = issue.severity.toUpperCase();
        const details = issue.details?.text || '';
        const diagnostics = issue.diagnostics ? ` (${issue.diagnostics})` : '';
        const location = issue.expression?.length > 0 ? ` at ${issue.expression.join(', ')}` : '';
        return `[${severity}] ${details}${diagnostics}${location}`;
    }).join('\n');
}
