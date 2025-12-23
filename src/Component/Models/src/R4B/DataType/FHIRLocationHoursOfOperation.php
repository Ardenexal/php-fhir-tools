<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Location.hoursOfOperation
 * @description What days/times during a week is this location usually open.
 */
class FHIRLocationHoursOfOperation extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
		public array $daysOfWeek = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean allDay The Location is open all day */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $allDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime openingTime Time that the Location opens */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime $openingTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime closingTime Time that the Location closes */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime $closingTime = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
