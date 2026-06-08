<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Marker class identifying violations produced by the questionnaire validator.
 *
 * Not a Symfony constraint: programmatically generated violations carry a marker FQCN in
 * FHIRValidationViolation::$constraintClass so consumers can distinguish violation sources
 * (mirrors FHIRExtensionContext / FHIRMustSupport usage in FHIRValidationService).
 */
final class FHIRQuestionnaireConstraint
{
}
