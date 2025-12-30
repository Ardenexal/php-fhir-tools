<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSubscriptionStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Subscription
 *
 * @description The subscription resource is used to define a push-based subscription from a server to another system. Once a subscription is registered with the server, the server checks every resource that is created or updated, and if the resource matches the given criteria, it sends a message on the defined "channel" so that another system can take an appropriate action.
 */
#[FhirResource(type: 'Subscription', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Subscription', fhirVersion: 'R4B')]
class FHIRSubscription extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRSubscriptionStatusCodesType|null status requested | active | error | off */
        #[NotBlank]
        public ?FHIRSubscriptionStatusCodesType $status = null,
        /** @var array<FHIRContactPoint> contact Contact details for source (e.g. troubleshooting) */
        public array $contact = [],
        /** @var FHIRInstant|null end When to automatically delete the subscription */
        public ?FHIRInstant $end = null,
        /** @var FHIRString|string|null reason Description of why this subscription was created */
        #[NotBlank]
        public FHIRString|string|null $reason = null,
        /** @var FHIRString|string|null criteria Rule for server push */
        #[NotBlank]
        public FHIRString|string|null $criteria = null,
        /** @var FHIRString|string|null error Latest error note */
        public FHIRString|string|null $error = null,
        /** @var FHIRSubscriptionChannel|null channel The channel on which to report matches to the criteria */
        #[NotBlank]
        public ?FHIRSubscriptionChannel $channel = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
