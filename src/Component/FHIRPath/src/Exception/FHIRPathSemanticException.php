<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Exception;

/**
 * Exception thrown when a FHIRPath expression is semantically invalid.
 *
 * Raised during strict-mode evaluation for type errors that can only be
 * detected at runtime, such as accessing an undefined property on a typed
 * FHIR model, mismatched resource-type filters, or order-sensitive functions
 * applied to unordered collections.
 *
 * Extends FHIRPathException so existing catch blocks continue to work.
 *
 * @author FHIR Tools Contributors
 */
class FHIRPathSemanticException extends FHIRPathException
{
}
