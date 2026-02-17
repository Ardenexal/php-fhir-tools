<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EndpointStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Endpoint
 *
 * @description The technical details of an endpoint that can be used for electronic services, such as for web services providing XDS.b or a REST endpoint for another FHIR server. This may include any security context information.
 */
#[FhirResource(type: 'Endpoint', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Endpoint', fhirVersion: 'R4')]
class EndpointResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Identifies this endpoint across multiple systems */
        public array $identifier = [],
        /** @var EndpointStatusType|null status active | suspended | error | off | entered-in-error | test */
        #[NotBlank]
        public ?EndpointStatusType $status = null,
        /** @var Coding|null connectionType Protocol/Profile/Standard to be used with this endpoint connection */
        #[NotBlank]
        public ?Coding $connectionType = null,
        /** @var StringPrimitive|string|null name A name that this endpoint can be identified by */
        public StringPrimitive|string|null $name = null,
        /** @var Reference|null managingOrganization Organization that manages this endpoint (might not be the organization that exposes the endpoint) */
        public ?Reference $managingOrganization = null,
        /** @var array<ContactPoint> contact Contact details for source (e.g. troubleshooting) */
        public array $contact = [],
        /** @var Period|null period Interval the endpoint is expected to be operational */
        public ?Period $period = null,
        /** @var array<CodeableConcept> payloadType The type of content that may be used at this endpoint (e.g. XDS Discharge summaries) */
        public array $payloadType = [],
        /** @var array<MimeTypesType> payloadMimeType Mimetype to send. If not specified, the content could be anything (including no payload, if the connectionType defined this) */
        public array $payloadMimeType = [],
        /** @var UrlPrimitive|null address The technical base address for connecting to this endpoint */
        #[NotBlank]
        public ?UrlPrimitive $address = null,
        /** @var array<StringPrimitive|string> header Usage depends on the channel type */
        public array $header = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
