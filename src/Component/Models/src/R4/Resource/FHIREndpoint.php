<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Endpoint
 * @description The technical details of an endpoint that can be used for electronic services, such as for web services providing XDS.b or a REST endpoint for another FHIR server. This may include any security context information.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Endpoint', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Endpoint', fhirVersion: 'R4')]
class FHIREndpoint extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Identifies this endpoint across multiple systems */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREndpointStatusType status active | suspended | error | off | entered-in-error | test */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIREndpointStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding connectionType Protocol/Profile/Standard to be used with this endpoint connection */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCoding $connectionType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string name A name that this endpoint can be identified by */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference managingOrganization Organization that manages this endpoint (might not be the organization that exposes the endpoint) */
		public ?FHIRReference $managingOrganization = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContactPoint> contact Contact details for source (e.g. troubleshooting) */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod period Interval the endpoint is expected to be operational */
		public ?FHIRPeriod $period = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> payloadType The type of content that may be used at this endpoint (e.g. XDS Discharge summaries) */
		public array $payloadType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMimeTypesType> payloadMimeType Mimetype to send. If not specified, the content could be anything (including no payload, if the connectionType defined this) */
		public array $payloadMimeType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUrl address The technical base address for connecting to this endpoint */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRUrl $address = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string> header Usage depends on the channel type */
		public array $header = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
