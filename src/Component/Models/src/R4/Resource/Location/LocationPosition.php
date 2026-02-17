<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Location;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The absolute geographic location of the Location, expressed using the WGS84 datum (This is the same co-ordinate system used in KML).
 */
#[FHIRBackboneElement(parentResource: 'Location', elementPath: 'Location.position', fhirVersion: 'R4')]
class LocationPosition extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var float|null longitude Longitude with WGS84 datum */
        #[NotBlank]
        public ?float $longitude = null,
        /** @var float|null latitude Latitude with WGS84 datum */
        #[NotBlank]
        public ?float $latitude = null,
        /** @var float|null altitude Altitude with WGS84 datum */
        public ?float $altitude = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
