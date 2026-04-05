<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MessageHeader\MessageHeaderDestination;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MessageHeader\MessageHeaderResponse;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\MessageHeader\MessageHeaderSource;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Infrastructure And Messaging)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MessageHeader
 *
 * @description The header for a message exchange that is either requesting or responding to an action.  The reference(s) that are the subject of the action as well as other information related to the action are typically transmitted in a bundle in which the MessageHeader resource instance is the first resource in the bundle.
 */
#[FhirResource(
    type: 'MessageHeader',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MessageHeader',
    fhirVersion: 'R4B',
)]
class MessageHeaderResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var Coding|UriPrimitive|null event Code for the event this message represents or link to event definition */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isRequired: true,
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding',
                    'jsonKey'      => 'eventCoding',
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive',
                    'jsonKey'      => 'eventUri',
                ],
            ],
        )]
        #[NotBlank]
        public Coding|UriPrimitive|null $event = null,
        /** @var array<MessageHeaderDestination> destination Message destination application(s) */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\MessageHeader\MessageHeaderDestination',
        )]
        public array $destination = [],
        /** @var Reference|null sender Real world sender of the message */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $sender = null,
        /** @var Reference|null enterer The source of the data entry */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $enterer = null,
        /** @var Reference|null author The source of the decision */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $author = null,
        /** @var MessageHeaderSource|null source Message source application */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isRequired: true), NotBlank]
        public ?MessageHeaderSource $source = null,
        /** @var Reference|null responsible Final responsibility for event */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $responsible = null,
        /** @var CodeableConcept|null reason Cause of event */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $reason = null,
        /** @var MessageHeaderResponse|null response If this is a reply to prior message */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?MessageHeaderResponse $response = null,
        /** @var array<Reference> focus The actual content of the message */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        public array $focus = [],
        /** @var CanonicalPrimitive|null definition Link to the definition for this message */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $definition = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
