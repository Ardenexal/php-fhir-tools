<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

/**
 * Marks a model property or constructor parameter as must-support in the FHIR sense.
 *
 * Present when the corresponding StructureDefinition element declares mustSupport=true.
 * This is an informational marker only — no Symfony Validator is attached. Consuming code
 * discovers must-support properties via ReflectionProperty::getAttributes(FHIRMustSupport::class).
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
final class FHIRMustSupport
{
}
