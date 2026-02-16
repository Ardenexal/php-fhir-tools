<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext as ComponentBuilderContext;

/**
 * Alias for the BuilderContext class in the Component namespace
 *
 * This class provides backward compatibility for tests and services
 * that expect BuilderContext to be in the root namespace.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class BuilderContext extends ComponentBuilderContext
{
    // This class inherits all functionality from the Component BuilderContext
}
