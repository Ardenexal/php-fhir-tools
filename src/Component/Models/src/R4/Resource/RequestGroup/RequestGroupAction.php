<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RequestGroup;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionCardinalityBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionGroupingBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionPrecheckBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionRequiredBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionSelectionBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description The actions, if any, produced by the evaluation of the artifact.
 */
#[FHIRBackboneElement(parentResource: 'RequestGroup', elementPath: 'RequestGroup.action', fhirVersion: 'R4')]
class RequestGroupAction extends BackboneElement
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
        /** @var StringPrimitive|string|null prefix User-visible prefix for the action (e.g. 1. or A.) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $prefix = null,
        /** @var StringPrimitive|string|null title User-visible title */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $title = null,
        /** @var StringPrimitive|string|null description Short description of the action */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var StringPrimitive|string|null textEquivalent Static text equivalent of the action, used if the dynamic aspects cannot be interpreted by the receiving system */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $textEquivalent = null,
        /** @var RequestPriorityType|null priority routine | urgent | asap | stat */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?RequestPriorityType $priority = null,
        /** @var array<CodeableConcept> code Code representing the meaning of the action or sub-actions */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
        )]
        public array $code = [],
        /** @var array<RelatedArtifact> documentation Supporting documentation for the intended performer of the action */
        #[FhirProperty(
            fhirType: 'RelatedArtifact',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact',
        )]
        public array $documentation = [],
        /** @var array<RequestGroupActionCondition> condition Whether or not the action is applicable */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\RequestGroup\RequestGroupActionCondition',
        )]
        public array $condition = [],
        /** @var array<RequestGroupActionRelatedAction> relatedAction Relationship to another action */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\RequestGroup\RequestGroupActionRelatedAction',
        )]
        public array $relatedAction = [],
        /** @var DateTimePrimitive|Age|Period|Duration|Range|Timing|null timing When the action should take place */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'timingDateTime',
                ],
                [
                    'fhirType'     => 'Age',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Age',
                    'jsonKey'      => 'timingAge',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'timingPeriod',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration',
                    'jsonKey'      => 'timingDuration',
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Range',
                    'jsonKey'      => 'timingRange',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing',
                    'jsonKey'      => 'timingTiming',
                ],
            ],
        )]
        public DateTimePrimitive|Age|Period|Duration|Range|Timing|null $timing = null,
        /** @var array<Reference> participant Who should perform the action */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        public array $participant = [],
        /** @var CodeableConcept|null type create | update | remove | fire-event */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var ActionGroupingBehaviorType|null groupingBehavior visual-group | logical-group | sentence-group */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ActionGroupingBehaviorType $groupingBehavior = null,
        /** @var ActionSelectionBehaviorType|null selectionBehavior any | all | all-or-none | exactly-one | at-most-one | one-or-more */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ActionSelectionBehaviorType $selectionBehavior = null,
        /** @var ActionRequiredBehaviorType|null requiredBehavior must | could | must-unless-documented */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ActionRequiredBehaviorType $requiredBehavior = null,
        /** @var ActionPrecheckBehaviorType|null precheckBehavior yes | no */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ActionPrecheckBehaviorType $precheckBehavior = null,
        /** @var ActionCardinalityBehaviorType|null cardinalityBehavior single | multiple */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ActionCardinalityBehaviorType $cardinalityBehavior = null,
        /** @var Reference|null resource The target of the action */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $resource = null,
        /** @var array<RequestGroupAction> action Sub action */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\RequestGroup\RequestGroupAction',
        )]
        public array $action = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
