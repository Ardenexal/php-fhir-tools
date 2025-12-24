<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Location
 *
 * @description Details and position information for a physical place where services are provided and resources and participants may be stored, found, contained, or accommodated.
 */
#[FhirResource(type: 'Location', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Location', fhirVersion: 'R4')]
class FHIRLocation extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Unique code or number identifying the location to its users */
        public array $identifier = [],
        /** @var FHIRLocationStatusType|null status active | suspended | inactive */
        public ?FHIRLocationStatusType $status = null,
        /** @var FHIRCoding|null operationalStatus The operational status of the location (typically only for a bed/room) */
        public ?FHIRCoding $operationalStatus = null,
        /** @var FHIRString|string|null name Name of the location as used by humans */
        public FHIRString|string|null $name = null,
        /** @var array<FHIRString|string> alias A list of alternate names that the location is known as, or was known as, in the past */
        public array $alias = [],
        /** @var FHIRString|string|null description Additional details about the location that could be displayed as further information to identify the location beyond its name */
        public FHIRString|string|null $description = null,
        /** @var FHIRLocationModeType|null mode instance | kind */
        public ?FHIRLocationModeType $mode = null,
        /** @var array<FHIRCodeableConcept> type Type of function performed */
        public array $type = [],
        /** @var array<FHIRContactPoint> telecom Contact details of the location */
        public array $telecom = [],
        /** @var FHIRAddress|null address Physical location */
        public ?FHIRAddress $address = null,
        /** @var FHIRCodeableConcept|null physicalType Physical form of the location */
        public ?FHIRCodeableConcept $physicalType = null,
        /** @var FHIRLocationPosition|null position The absolute geographic location */
        public ?FHIRLocationPosition $position = null,
        /** @var FHIRReference|null managingOrganization Organization responsible for provisioning and upkeep */
        public ?FHIRReference $managingOrganization = null,
        /** @var FHIRReference|null partOf Another Location this one is physically a part of */
        public ?FHIRReference $partOf = null,
        /** @var array<FHIRLocationHoursOfOperation> hoursOfOperation What days/times during a week is this location usually open */
        public array $hoursOfOperation = [],
        /** @var FHIRString|string|null availabilityExceptions Description of availability exceptions */
        public FHIRString|string|null $availabilityExceptions = null,
        /** @var array<FHIRReference> endpoint Technical endpoints providing access to services operated for the location */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
