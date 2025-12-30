<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/HealthcareService
 *
 * @description The details of a healthcare service available at a location.
 */
#[FhirResource(
    type: 'HealthcareService',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/HealthcareService',
    fhirVersion: 'R4',
)]
class FHIRHealthcareService extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External identifiers for this item */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether this HealthcareService record is in active use */
        public ?FHIRBoolean $active = null,
        /** @var FHIRReference|null providedBy Organization that provides this service */
        public ?FHIRReference $providedBy = null,
        /** @var array<FHIRCodeableConcept> category Broad category of service being performed or delivered */
        public array $category = [],
        /** @var array<FHIRCodeableConcept> type Type of service that may be delivered or performed */
        public array $type = [],
        /** @var array<FHIRCodeableConcept> specialty Specialties handled by the HealthcareService */
        public array $specialty = [],
        /** @var array<FHIRReference> location Location(s) where service may be provided */
        public array $location = [],
        /** @var FHIRString|string|null name Description of service as presented to a consumer while searching */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null comment Additional description and/or any specific issues not covered elsewhere */
        public FHIRString|string|null $comment = null,
        /** @var FHIRMarkdown|null extraDetails Extra details about the service that can't be placed in the other fields */
        public ?FHIRMarkdown $extraDetails = null,
        /** @var FHIRAttachment|null photo Facilitates quick identification of the service */
        public ?FHIRAttachment $photo = null,
        /** @var array<FHIRContactPoint> telecom Contacts related to the healthcare service */
        public array $telecom = [],
        /** @var array<FHIRReference> coverageArea Location(s) service is intended for/available to */
        public array $coverageArea = [],
        /** @var array<FHIRCodeableConcept> serviceProvisionCode Conditions under which service is available/offered */
        public array $serviceProvisionCode = [],
        /** @var array<FHIRHealthcareServiceEligibility> eligibility Specific eligibility requirements required to use the service */
        public array $eligibility = [],
        /** @var array<FHIRCodeableConcept> program Programs that this service is applicable to */
        public array $program = [],
        /** @var array<FHIRCodeableConcept> characteristic Collection of characteristics (attributes) */
        public array $characteristic = [],
        /** @var array<FHIRCodeableConcept> communication The language that this service is offered in */
        public array $communication = [],
        /** @var array<FHIRCodeableConcept> referralMethod Ways that the service accepts referrals */
        public array $referralMethod = [],
        /** @var FHIRBoolean|null appointmentRequired If an appointment is required for access to this service */
        public ?FHIRBoolean $appointmentRequired = null,
        /** @var array<FHIRHealthcareServiceAvailableTime> availableTime Times the Service Site is available */
        public array $availableTime = [],
        /** @var array<FHIRHealthcareServiceNotAvailable> notAvailable Not available during this time due to provided reason */
        public array $notAvailable = [],
        /** @var FHIRString|string|null availabilityExceptions Description of availability exceptions */
        public FHIRString|string|null $availabilityExceptions = null,
        /** @var array<FHIRReference> endpoint Technical endpoints providing access to electronic services operated for the healthcare service */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
