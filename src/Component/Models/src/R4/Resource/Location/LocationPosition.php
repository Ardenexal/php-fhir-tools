<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Location;

/**
 * @description The absolute geographic location of the Location, expressed using the WGS84 datum (This is the same co-ordinate system used in KML).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Location', elementPath: 'Location.position', fhirVersion: 'R4')]
class LocationPosition extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|float longitude Longitude with WGS84 datum */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?float $longitude = null,
		/** @var null|float latitude Latitude with WGS84 datum */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?float $latitude = null,
		/** @var null|float altitude Altitude with WGS84 datum */
		public ?float $altitude = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
