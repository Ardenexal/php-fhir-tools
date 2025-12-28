<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubscriptionStatus
 *
 * @description The SubscriptionStatus resource describes the state of a Subscription during notifications.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'SubscriptionStatus',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/SubscriptionStatus',
    fhirVersion: 'R4B',
)]
class FHIRSubscriptionStatus extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRSubscriptionStatusCodesType|null status requested | active | error | off | entered-in-error */
        public ?\FHIRSubscriptionStatusCodesType $status = null,
        /** @var FHIRSubscriptionNotificationTypeType|null type handshake | heartbeat | event-notification | query-status | query-event */
        #[NotBlank]
        public ?\FHIRSubscriptionNotificationTypeType $type = null,
        /** @var FHIRString|string|null eventsSinceSubscriptionStart Events since the Subscription was created */
        public \FHIRString|string|null $eventsSinceSubscriptionStart = null,
        /** @var array<FHIRSubscriptionStatusNotificationEvent> notificationEvent Detailed information about any events relevant to this notification */
        public array $notificationEvent = [],
        /** @var FHIRReference|null subscription Reference to the Subscription responsible for this notification */
        #[NotBlank]
        public ?\FHIRReference $subscription = null,
        /** @var FHIRCanonical|null topic Reference to the SubscriptionTopic this notification relates to */
        public ?\FHIRCanonical $topic = null,
        /** @var array<FHIRCodeableConcept> error List of errors on the subscription */
        public array $error = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
