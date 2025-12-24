<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAvailability;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtendedContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRVirtualServiceDetail;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Location
 *
 * @description Details and position information for a place where services are provided and resources and participants may be stored, found, contained, or accommodated.
 */
#[FhirResource(type: 'Location', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Location', fhirVersion: 'R5')]
class FHIRLocation extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
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
        /** @var FHIRMarkdown|null description Additional details about the location that could be displayed as further information to identify the location beyond its name */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRLocationModeType|null mode instance | kind */
        public ?FHIRLocationModeType $mode = null,
        /** @var array<FHIRCodeableConcept> type Type of function performed */
        public array $type = [],
        /** @var array<FHIRExtendedContactDetail> contact Official contact details for the location */
        public array $contact = [],
        /** @var FHIRAddress|null address Physical location */
        public ?FHIRAddress $address = null,
        /** @var FHIRCodeableConcept|null form Physical form of the location */
        public ?FHIRCodeableConcept $form = null,
        /** @var FHIRLocationPosition|null position The absolute geographic location */
        public ?FHIRLocationPosition $position = null,
        /** @var FHIRReference|null managingOrganization Organization responsible for provisioning and upkeep */
        public ?FHIRReference $managingOrganization = null,
        /** @var FHIRReference|null partOf Another Location this one is physically a part of */
        public ?FHIRReference $partOf = null,
        /** @var array<FHIRCodeableConcept> characteristic Collection of characteristics (attributes) */
        public array $characteristic = [],
        /** @var array<FHIRAvailability> hoursOfOperation What days/times during a week is this location usually open (including exceptions) */
        public array $hoursOfOperation = [],
        /** @var array<FHIRVirtualServiceDetail> virtualService Connection details of a virtual service (e.g. conference call) */
        public array $virtualService = [],
        /** @var array<FHIRReference> endpoint Technical endpoints providing access to services operated for the location */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
