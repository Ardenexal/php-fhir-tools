<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\SubscriptionTopic;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CriteriaNotExistsBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @description The FHIR query based rules that the server should use to determine when to trigger a notification for this subscription topic.
 */
#[FHIRBackboneElement(parentResource: 'SubscriptionTopic', elementPath: 'SubscriptionTopic.resourceTrigger.queryCriteria', fhirVersion: 'R5')]
class SubscriptionTopicResourceTriggerQueryCriteria extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null previous Rule applied to previous resource state */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $previous = null,
        /** @var CriteriaNotExistsBehaviorType|null resultForCreate test-passes | test-fails */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CriteriaNotExistsBehaviorType $resultForCreate = null,
        /** @var StringPrimitive|string|null current Rule applied to current resource state */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $current = null,
        /** @var CriteriaNotExistsBehaviorType|null resultForDelete test-passes | test-fails */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CriteriaNotExistsBehaviorType $resultForDelete = null,
        /** @var bool|null requireBoth Both must be true flag */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $requireBoth = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
