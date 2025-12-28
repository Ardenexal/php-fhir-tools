<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The absolute geographic location of the Location, expressed using the WGS84 datum (This is the same co-ordinate system used in KML).
 */
#[FHIRBackboneElement(parentResource: 'Location', elementPath: 'Location.position', fhirVersion: 'R4B')]
class FHIRLocationPosition extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRDecimal|null longitude Longitude with WGS84 datum */
        #[NotBlank]
        public ?FHIRDecimal $longitude = null,
        /** @var FHIRDecimal|null latitude Latitude with WGS84 datum */
        #[NotBlank]
        public ?FHIRDecimal $latitude = null,
        /** @var FHIRDecimal|null altitude Altitude with WGS84 datum */
        public ?FHIRDecimal $altitude = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
