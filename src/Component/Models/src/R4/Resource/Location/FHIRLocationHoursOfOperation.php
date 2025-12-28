<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description What days/times during a week is this location usually open.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Location', elementPath: 'Location.hoursOfOperation', fhirVersion: 'R4')]
class FHIRLocationHoursOfOperation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
		public array $daysOfWeek = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean allDay The Location is open all day */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean $allDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime openingTime Time that the Location opens */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime $openingTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime closingTime Time that the Location closes */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime $closingTime = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
