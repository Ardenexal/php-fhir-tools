<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageHeader\MessageHeaderDestination;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageHeader\MessageHeaderResponse;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageHeader\MessageHeaderSource;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Infrastructure And Messaging)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MessageHeader
 *
 * @description The header for a message exchange that is either requesting or responding to an action.  The reference(s) that are the subject of the action as well as other information related to the action are typically transmitted in a bundle in which the MessageHeader resource instance is the first resource in the bundle.
 */
#[FhirResource(type: 'MessageHeader', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/MessageHeader', fhirVersion: 'R4')]
class MessageHeaderResource extends DomainResourceResource
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
        /** @var Coding|UriPrimitive|null eventX Code for the event this message represents or link to event definition */
        #[NotBlank]
        public Coding|UriPrimitive|null $eventX = null,
        /** @var array<MessageHeaderDestination> destination Message destination application(s) */
        public array $destination = [],
        /** @var Reference|null sender Real world sender of the message */
        public ?Reference $sender = null,
        /** @var Reference|null enterer The source of the data entry */
        public ?Reference $enterer = null,
        /** @var Reference|null author The source of the decision */
        public ?Reference $author = null,
        /** @var MessageHeaderSource|null source Message source application */
        #[NotBlank]
        public ?MessageHeaderSource $source = null,
        /** @var Reference|null responsible Final responsibility for event */
        public ?Reference $responsible = null,
        /** @var CodeableConcept|null reason Cause of event */
        public ?CodeableConcept $reason = null,
        /** @var MessageHeaderResponse|null response If this is a reply to prior message */
        public ?MessageHeaderResponse $response = null,
        /** @var array<Reference> focus The actual content of the message */
        public array $focus = [],
        /** @var CanonicalPrimitive|null definition Link to the definition for this message */
        public ?CanonicalPrimitive $definition = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
