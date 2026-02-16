<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SubscriptionStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Subscription\SubscriptionChannel;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Subscription
 *
 * @description The subscription resource is used to define a push-based subscription from a server to another system. Once a subscription is registered with the server, the server checks every resource that is created or updated, and if the resource matches the given criteria, it sends a message on the defined "channel" so that another system can take an appropriate action.
 */
#[FhirResource(type: 'Subscription', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Subscription', fhirVersion: 'R4')]
class SubscriptionResource extends DomainResourceResource
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
        /** @var SubscriptionStatusType|null status requested | active | error | off */
        #[NotBlank]
        public ?SubscriptionStatusType $status = null,
        /** @var array<ContactPoint> contact Contact details for source (e.g. troubleshooting) */
        public array $contact = [],
        /** @var InstantPrimitive|null end When to automatically delete the subscription */
        public ?InstantPrimitive $end = null,
        /** @var StringPrimitive|string|null reason Description of why this subscription was created */
        #[NotBlank]
        public StringPrimitive|string|null $reason = null,
        /** @var StringPrimitive|string|null criteria Rule for server push */
        #[NotBlank]
        public StringPrimitive|string|null $criteria = null,
        /** @var StringPrimitive|string|null error Latest error note */
        public StringPrimitive|string|null $error = null,
        /** @var SubscriptionChannel|null channel The channel on which to report matches to the criteria */
        #[NotBlank]
        public ?SubscriptionChannel $channel = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
