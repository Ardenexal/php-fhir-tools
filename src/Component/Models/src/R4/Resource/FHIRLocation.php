<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Location
 * @description Details and position information for a physical place where services are provided and resources and participants may be stored, found, contained, or accommodated.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Location', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Location', fhirVersion: 'R4')]
class FHIRLocation extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier Unique code or number identifying the location to its users */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRLocationStatusType status active | suspended | inactive */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRLocationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding operationalStatus The operational status of the location (typically only for a bed/room) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding $operationalStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string name Name of the location as used by humans */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string> alias A list of alternate names that the location is known as, or was known as, in the past */
		public array $alias = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string description Additional details about the location that could be displayed as further information to identify the location beyond its name */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRLocationModeType mode instance | kind */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRLocationModeType $mode = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> type Type of function performed */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactPoint> telecom Contact details of the location */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAddress address Physical location */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAddress $address = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept physicalType Physical form of the location */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $physicalType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRLocationPosition position The absolute geographic location */
		public ?FHIRLocationPosition $position = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference managingOrganization Organization responsible for provisioning and upkeep */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $managingOrganization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference partOf Another Location this one is physically a part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $partOf = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRLocationHoursOfOperation> hoursOfOperation What days/times during a week is this location usually open */
		public array $hoursOfOperation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string availabilityExceptions Description of availability exceptions */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $availabilityExceptions = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> endpoint Technical endpoints providing access to services operated for the location */
		public array $endpoint = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
