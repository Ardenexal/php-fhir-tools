<?php

declare(strict_types=1);

/**
 * FHIR Profile Validation - Proof of Concept Example
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Ardenexal\FHIRTools\Component\Validation\Model\ProfileConstraint;
use Ardenexal\FHIRTools\Component\Validation\Model\ValidationIssue;
use Ardenexal\FHIRTools\Component\Validation\Model\ValidationResult;

echo "=== FHIR Profile Validation Proof of Concept ===\n\n";
echo "This example demonstrates the model classes for profile validation.\n\n";

// Example 1: Create a profile constraint
$constraint = new ProfileConstraint(
    path: 'Patient.name',
    key: 'pat-name-1',
    expression: 'family.exists() or given.exists()',
    human: 'Patient SHALL have at least family or given name',
    min: 1,
    max: '*',
    severity: 'error'
);

echo "Created ProfileConstraint:\n";
echo "  Path: {$constraint->path}\n";
echo "  Has FHIRPath: " . ($constraint->hasFHIRPathConstraint() ? 'Yes' : 'No') . "\n";
echo "  Has Cardinality: " . ($constraint->hasCardinalityConstraint() ? 'Yes' : 'No') . "\n\n";

// Example 2: Create validation result
$result = new ValidationResult();
$result->addIssue(ValidationIssue::error(
    'Patient.name',
    'Patient must have at least one name',
    'pat-name-1',
    'http://example.org/StructureDefinition/MyPatient'
));

echo "Validation Result:\n";
echo "  Valid: " . ($result->isValid() ? 'Yes' : 'No') . "\n";
echo "  Errors: {$result->getErrorCount()}\n\n";

echo "See docs/FHIR_PROFILE_VALIDATION_ANALYSIS.md for full analysis.\n";
