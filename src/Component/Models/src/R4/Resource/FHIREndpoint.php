<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIREndpointStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUrl;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Endpoint
 *
 * @description The technical details of an endpoint that can be used for electronic services, such as for web services providing XDS.b or a REST endpoint for another FHIR server. This may include any security context information.
 */
#[FhirResource(type: 'Endpoint', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Endpoint', fhirVersion: 'R4')]
class FHIREndpoint extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Identifies this endpoint across multiple systems */
        public array $identifier = [],
        /** @var FHIREndpointStatusType|null status active | suspended | error | off | entered-in-error | test */
        #[NotBlank]
        public ?FHIREndpointStatusType $status = null,
        /** @var FHIRCoding|null connectionType Protocol/Profile/Standard to be used with this endpoint connection */
        #[NotBlank]
        public ?FHIRCoding $connectionType = null,
        /** @var FHIRString|string|null name A name that this endpoint can be identified by */
        public FHIRString|string|null $name = null,
        /** @var FHIRReference|null managingOrganization Organization that manages this endpoint (might not be the organization that exposes the endpoint) */
        public ?FHIRReference $managingOrganization = null,
        /** @var array<FHIRContactPoint> contact Contact details for source (e.g. troubleshooting) */
        public array $contact = [],
        /** @var FHIRPeriod|null period Interval the endpoint is expected to be operational */
        public ?FHIRPeriod $period = null,
        /** @var array<FHIRCodeableConcept> payloadType The type of content that may be used at this endpoint (e.g. XDS Discharge summaries) */
        public array $payloadType = [],
        /** @var array<FHIRMimeTypesType> payloadMimeType Mimetype to send. If not specified, the content could be anything (including no payload, if the connectionType defined this) */
        public array $payloadMimeType = [],
        /** @var FHIRUrl|null address The technical base address for connecting to this endpoint */
        #[NotBlank]
        public ?FHIRUrl $address = null,
        /** @var array<FHIRString|string> header Usage depends on the channel type */
        public array $header = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
