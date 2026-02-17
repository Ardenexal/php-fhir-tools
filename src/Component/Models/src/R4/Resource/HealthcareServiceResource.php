<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\HealthcareService\HealthcareServiceAvailableTime;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\HealthcareService\HealthcareServiceEligibility;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\HealthcareService\HealthcareServiceNotAvailable;

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
class HealthcareServiceResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External identifiers for this item */
        public array $identifier = [],
        /** @var bool|null active Whether this HealthcareService record is in active use */
        public ?bool $active = null,
        /** @var Reference|null providedBy Organization that provides this service */
        public ?Reference $providedBy = null,
        /** @var array<CodeableConcept> category Broad category of service being performed or delivered */
        public array $category = [],
        /** @var array<CodeableConcept> type Type of service that may be delivered or performed */
        public array $type = [],
        /** @var array<CodeableConcept> specialty Specialties handled by the HealthcareService */
        public array $specialty = [],
        /** @var array<Reference> location Location(s) where service may be provided */
        public array $location = [],
        /** @var StringPrimitive|string|null name Description of service as presented to a consumer while searching */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null comment Additional description and/or any specific issues not covered elsewhere */
        public StringPrimitive|string|null $comment = null,
        /** @var MarkdownPrimitive|null extraDetails Extra details about the service that can't be placed in the other fields */
        public ?MarkdownPrimitive $extraDetails = null,
        /** @var Attachment|null photo Facilitates quick identification of the service */
        public ?Attachment $photo = null,
        /** @var array<ContactPoint> telecom Contacts related to the healthcare service */
        public array $telecom = [],
        /** @var array<Reference> coverageArea Location(s) service is intended for/available to */
        public array $coverageArea = [],
        /** @var array<CodeableConcept> serviceProvisionCode Conditions under which service is available/offered */
        public array $serviceProvisionCode = [],
        /** @var array<HealthcareServiceEligibility> eligibility Specific eligibility requirements required to use the service */
        public array $eligibility = [],
        /** @var array<CodeableConcept> program Programs that this service is applicable to */
        public array $program = [],
        /** @var array<CodeableConcept> characteristic Collection of characteristics (attributes) */
        public array $characteristic = [],
        /** @var array<CodeableConcept> communication The language that this service is offered in */
        public array $communication = [],
        /** @var array<CodeableConcept> referralMethod Ways that the service accepts referrals */
        public array $referralMethod = [],
        /** @var bool|null appointmentRequired If an appointment is required for access to this service */
        public ?bool $appointmentRequired = null,
        /** @var array<HealthcareServiceAvailableTime> availableTime Times the Service Site is available */
        public array $availableTime = [],
        /** @var array<HealthcareServiceNotAvailable> notAvailable Not available during this time due to provided reason */
        public array $notAvailable = [],
        /** @var StringPrimitive|string|null availabilityExceptions Description of availability exceptions */
        public StringPrimitive|string|null $availabilityExceptions = null,
        /** @var array<Reference> endpoint Technical endpoints providing access to electronic services operated for the healthcare service */
        public array $endpoint = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
