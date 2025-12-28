<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Endpoint
 *
 * @description The technical details of an endpoint that can be used for electronic services, such as for web services providing XDS.b, a REST endpoint for another FHIR server, or a s/Mime email address. This may include any security context information.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Endpoint', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Endpoint', fhirVersion: 'R5')]
class FHIREndpoint extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifies this endpoint across multiple systems */
        public array $identifier = [],
        /** @var FHIREndpointStatusType|null status active | suspended | error | off | entered-in-error | test */
        #[NotBlank]
        public ?\FHIREndpointStatusType $status = null,
        /** @var array<FHIRCodeableConcept> connectionType Protocol/Profile/Standard to be used with this endpoint connection */
        public array $connectionType = [],
        /** @var FHIRString|string|null name A name that this endpoint can be identified by */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null description Additional details about the endpoint that could be displayed as further information to identify the description beyond its name */
        public \FHIRString|string|null $description = null,
        /** @var array<FHIRCodeableConcept> environmentType The type of environment(s) exposed at this endpoint */
        public array $environmentType = [],
        /** @var FHIRReference|null managingOrganization Organization that manages this endpoint (might not be the organization that exposes the endpoint) */
        public ?\FHIRReference $managingOrganization = null,
        /** @var array<FHIRContactPoint> contact Contact details for source (e.g. troubleshooting) */
        public array $contact = [],
        /** @var FHIRPeriod|null period Interval the endpoint is expected to be operational */
        public ?\FHIRPeriod $period = null,
        /** @var array<FHIREndpointPayload> payload Set of payloads that are provided by this endpoint */
        public array $payload = [],
        /** @var FHIRUrl|null address The technical base address for connecting to this endpoint */
        #[NotBlank]
        public ?\FHIRUrl $address = null,
        /** @var array<FHIRString|string> header Usage depends on the channel type */
        public array $header = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
