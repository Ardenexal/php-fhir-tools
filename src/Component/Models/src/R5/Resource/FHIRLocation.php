<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Location
 * @description Details and position information for a place where services are provided and resources and participants may be stored, found, contained, or accommodated.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Location', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Location', fhirVersion: 'R5')]
class FHIRLocation extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Unique code or number identifying the location to its users */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRLocationStatusType status active | suspended | inactive */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRLocationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding operationalStatus The operational status of the location (typically only for a bed/room) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding $operationalStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string name Name of the location as used by humans */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> alias A list of alternate names that the location is known as, or was known as, in the past */
		public array $alias = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Additional details about the location that could be displayed as further information to identify the location beyond its name */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRLocationModeType mode instance | kind */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRLocationModeType $mode = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> type Type of function performed */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtendedContactDetail> contact Official contact details for the location */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAddress address Physical location */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAddress $address = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept form Physical form of the location */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $form = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRLocationPosition position The absolute geographic location */
		public ?FHIRLocationPosition $position = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference managingOrganization Organization responsible for provisioning and upkeep */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $managingOrganization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference partOf Another Location this one is physically a part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $partOf = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> characteristic Collection of characteristics (attributes) */
		public array $characteristic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAvailability> hoursOfOperation What days/times during a week is this location usually open (including exceptions) */
		public array $hoursOfOperation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRVirtualServiceDetail> virtualService Connection details of a virtual service (e.g. conference call) */
		public array $virtualService = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> endpoint Technical endpoints providing access to services operated for the location */
		public array $endpoint = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
