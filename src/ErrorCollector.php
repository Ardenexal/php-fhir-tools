<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector as ComponentErrorCollector;

/**
 * Alias for the ErrorCollector class in the Component namespace
 *
 * This class provides backward compatibility for tests and services
 * that expect ErrorCollector to be in the root namespace.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class ErrorCollector extends ComponentErrorCollector
{
    // This class inherits all functionality from the Component ErrorCollector
}
