<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\LocationModeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\LocationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Location\LocationHoursOfOperation;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Location\LocationPosition;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Location
 *
 * @description Details and position information for a physical place where services are provided and resources and participants may be stored, found, contained, or accommodated.
 */
#[FhirResource(type: 'Location', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Location', fhirVersion: 'R4')]
class LocationResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Unique code or number identifying the location to its users */
        public array $identifier = [],
        /** @var LocationStatusType|null status active | suspended | inactive */
        public ?LocationStatusType $status = null,
        /** @var Coding|null operationalStatus The operational status of the location (typically only for a bed/room) */
        public ?Coding $operationalStatus = null,
        /** @var StringPrimitive|string|null name Name of the location as used by humans */
        public StringPrimitive|string|null $name = null,
        /** @var array<StringPrimitive|string> alias A list of alternate names that the location is known as, or was known as, in the past */
        public array $alias = [],
        /** @var StringPrimitive|string|null description Additional details about the location that could be displayed as further information to identify the location beyond its name */
        public StringPrimitive|string|null $description = null,
        /** @var LocationModeType|null mode instance | kind */
        public ?LocationModeType $mode = null,
        /** @var array<CodeableConcept> type Type of function performed */
        public array $type = [],
        /** @var array<ContactPoint> telecom Contact details of the location */
        public array $telecom = [],
        /** @var Address|null address Physical location */
        public ?Address $address = null,
        /** @var CodeableConcept|null physicalType Physical form of the location */
        public ?CodeableConcept $physicalType = null,
        /** @var LocationPosition|null position The absolute geographic location */
        public ?LocationPosition $position = null,
        /** @var Reference|null managingOrganization Organization responsible for provisioning and upkeep */
        public ?Reference $managingOrganization = null,
        /** @var Reference|null partOf Another Location this one is physically a part of */
        public ?Reference $partOf = null,
        /** @var array<LocationHoursOfOperation> hoursOfOperation What days/times during a week is this location usually open */
        public array $hoursOfOperation = [],
        /** @var StringPrimitive|string|null availabilityExceptions Description of availability exceptions */
        public StringPrimitive|string|null $availabilityExceptions = null,
        /** @var array<Reference> endpoint Technical endpoints providing access to services operated for the location */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
