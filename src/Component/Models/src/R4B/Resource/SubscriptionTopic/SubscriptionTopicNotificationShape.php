<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\SubscriptionTopic;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of properties to describe the shape (e.g., resources) included in notifications from this Subscription Topic.
 */
#[FHIRBackboneElement(parentResource: 'SubscriptionTopic', elementPath: 'SubscriptionTopic.notificationShape', fhirVersion: 'R4B')]
class SubscriptionTopicNotificationShape extends BackboneElement
{
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'modifierExtension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'modifierExtension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'resource' => [
            'fhirType'     => 'uri',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'include' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'revInclude' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var UriPrimitive|null resource URL of the Resource that is the focus (main) resource in a notification shape */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?UriPrimitive $resource = null,
        /** @var array<StringPrimitive|string> include Include directives, rooted in the resource for this shape */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $include = [],
        /** @var array<StringPrimitive|string> revInclude Reverse include directives, rooted in the resource for this shape */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $revInclude = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
