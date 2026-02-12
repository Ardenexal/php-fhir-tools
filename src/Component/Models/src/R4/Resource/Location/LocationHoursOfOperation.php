<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Location;

/**
 * @description What days/times during a week is this location usually open.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Location', elementPath: 'Location.hoursOfOperation', fhirVersion: 'R4')]
class LocationHoursOfOperation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\DaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
		public array $daysOfWeek = [],
		/** @var null|bool allDay The Location is open all day */
		public ?bool $allDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive openingTime Time that the Location opens */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive $openingTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive closingTime Time that the Location closes */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive $closingTime = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
