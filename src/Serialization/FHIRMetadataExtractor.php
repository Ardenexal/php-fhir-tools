<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor as ComponentFHIRMetadataExtractor;

/**
 * Alias for the FHIRMetadataExtractor class in the Component namespace
 *
 * This class provides backward compatibility for tests and services
 * that expect FHIRMetadataExtractor to be in the Serialization namespace.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class FHIRMetadataExtractor extends ComponentFHIRMetadataExtractor
{
    // This class inherits all functionality from the Component FHIRMetadataExtractor
}
