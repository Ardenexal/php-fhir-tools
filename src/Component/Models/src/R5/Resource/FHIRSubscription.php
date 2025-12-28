<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Subscription
 *
 * @description The subscription resource describes a particular client's request to be notified about a SubscriptionTopic.
 */
#[FhirResource(type: 'Subscription', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Subscription', fhirVersion: 'R5')]
class FHIRSubscription extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Additional identifiers (business identifier) */
        public array $identifier = [],
        /** @var FHIRString|string|null name Human readable name for this subscription */
        public FHIRString|string|null $name = null,
        /** @var FHIRSubscriptionStatusCodesType|null status requested | active | error | off | entered-in-error */
        #[NotBlank]
        public ?FHIRSubscriptionStatusCodesType $status = null,
        /** @var FHIRCanonical|null topic Reference to the subscription topic being subscribed to */
        #[NotBlank]
        public ?FHIRCanonical $topic = null,
        /** @var array<FHIRContactPoint> contact Contact details for source (e.g. troubleshooting) */
        public array $contact = [],
        /** @var FHIRInstant|null end When to automatically delete the subscription */
        public ?FHIRInstant $end = null,
        /** @var FHIRReference|null managingEntity Entity responsible for Subscription changes */
        public ?FHIRReference $managingEntity = null,
        /** @var FHIRString|string|null reason Description of why this subscription was created */
        public FHIRString|string|null $reason = null,
        /** @var array<FHIRSubscriptionFilterBy> filterBy Criteria for narrowing the subscription topic stream */
        public array $filterBy = [],
        /** @var FHIRCoding|null channelType Channel type for notifications */
        #[NotBlank]
        public ?FHIRCoding $channelType = null,
        /** @var FHIRUrl|null endpoint Where the channel points to */
        public ?FHIRUrl $endpoint = null,
        /** @var array<FHIRSubscriptionParameter> parameter Channel type */
        public array $parameter = [],
        /** @var FHIRUnsignedInt|null heartbeatPeriod Interval in seconds to send 'heartbeat' notification */
        public ?FHIRUnsignedInt $heartbeatPeriod = null,
        /** @var FHIRUnsignedInt|null timeout Timeout in seconds to attempt notification delivery */
        public ?FHIRUnsignedInt $timeout = null,
        /** @var FHIRMimeTypesType|null contentType MIME type to send, or omit for no payload */
        public ?FHIRMimeTypesType $contentType = null,
        /** @var FHIRSubscriptionPayloadContentType|null content empty | id-only | full-resource */
        public ?FHIRSubscriptionPayloadContentType $content = null,
        /** @var FHIRPositiveInt|null maxCount Maximum number of events that can be combined in a single notification */
        public ?FHIRPositiveInt $maxCount = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
