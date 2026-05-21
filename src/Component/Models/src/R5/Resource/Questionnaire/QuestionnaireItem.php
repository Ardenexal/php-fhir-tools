<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\EnableWhenBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireAnswerConstraintType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireItemDisabledDisplayType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A particular question, question grouping or display text that is part of the questionnaire.
 */
#[FHIRBackboneElement(parentResource: 'Questionnaire', elementPath: 'Questionnaire.item', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'que-1a',
    severity: 'error',
    expression: '(type=\'group\' and %resource.status=\'complete\') implies item.empty().not()',
    human: 'Group items must have nested items when Questionanire is complete',
)]
#[FHIRPathInvariant(
    key: 'que-1b',
    severity: 'warning',
    expression: 'type=\'group\' implies item.empty().not()',
    human: 'Groups should have items',
)]
#[FHIRPathInvariant(
    key: 'que-1c',
    severity: 'error',
    expression: 'type=\'display\' implies item.empty()',
    human: 'Display items cannot have child items',
)]
#[FHIRPathInvariant(
    key: 'que-3',
    severity: 'error',
    expression: 'type!=\'display\' or code.empty()',
    human: 'Display items cannot have a "code" asserted',
)]
#[FHIRPathInvariant(
    key: 'que-4',
    severity: 'error',
    expression: 'answerOption.empty() or answerValueSet.empty()',
    human: 'A question cannot have both answerOption and answerValueSet',
)]
#[FHIRPathInvariant(
    key: 'que-5',
    severity: 'error',
    expression: '(type=\'coding\' or type = \'decimal\' or type = \'integer\' or type = \'date\' or type = \'dateTime\' or type = \'time\' or type = \'string\' or type = \'quantity\') or (answerValueSet.empty() and answerOption.empty())',
    human: 'Only coding, decimal, integer, date, dateTime, time, string or quantity  items can have answerOption or answerValueSet',
)]
#[FHIRPathInvariant(
    key: 'que-6',
    severity: 'error',
    expression: 'type!=\'display\' or (required.empty() and repeats.empty())',
    human: 'Required and repeat aren\'t permitted for display items',
)]
#[FHIRPathInvariant(
    key: 'que-8',
    severity: 'error',
    expression: '(type!=\'group\' and type!=\'display\') or initial.empty()',
    human: 'Initial values can\'t be specified for groups or display items',
)]
#[FHIRPathInvariant(
    key: 'que-9',
    severity: 'error',
    expression: 'type!=\'display\' or readOnly.empty()',
    human: 'Read-only can\'t be specified for "display" items',
)]
#[FHIRPathInvariant(
    key: 'que-10',
    severity: 'error',
    expression: '(type in (\'boolean\' | \'decimal\' | \'integer\' | \'string\' | \'text\' | \'url\')) or answerConstraint=\'optionOrString\' or maxLength.empty()',
    human: 'Maximum length can only be declared for simple question types',
)]
#[FHIRPathInvariant(
    key: 'que-11',
    severity: 'error',
    expression: 'answerOption.empty() or initial.empty()',
    human: 'If one or more answerOption is present, initial cannot be present.  Use answerOption.initialSelected instead',
)]
#[FHIRPathInvariant(
    key: 'que-12',
    severity: 'error',
    expression: 'enableWhen.count() > 1 implies enableBehavior.exists()',
    human: 'If there are more than one enableWhen, enableBehavior must be specified',
)]
#[FHIRPathInvariant(
    key: 'que-13',
    severity: 'error',
    expression: 'repeats=true or initial.count() <= 1',
    human: 'Can only have multiple initial values for repeating items',
)]
#[FHIRPathInvariant(
    key: 'que-14',
    severity: 'warning',
    expression: 'answerConstraint.exists() implies answerOption.exists() or answerValueSet.exists()',
    human: 'Can only have answerConstraint if answerOption or answerValueSet are present.  (This is a warning because extensions may serve the same purpose)',
)]
class QuestionnaireItem extends BackboneElement
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
        /** @var StringPrimitive|string|null linkId Unique id for item in questionnaire */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $linkId = null,
        /** @var UriPrimitive|null definition ElementDefinition - details for the item */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $definition = null,
        /** @var array<Coding> code Corresponding concept for this item in a terminology */
        #[FhirProperty(
            fhirType: 'Coding',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
        )]
        public array $code = [],
        /** @var StringPrimitive|string|null prefix E.g. "1(a)", "2.5.3" */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $prefix = null,
        /** @var StringPrimitive|string|null text Primary text for the item */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $text = null,
        /** @var QuestionnaireItemTypeType|null type group | display | boolean | decimal | integer | date | dateTime + */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/item-type|5.0.0', strength: 'required')]
        public ?QuestionnaireItemTypeType $type = null,
        /** @var array<QuestionnaireItemEnableWhen> enableWhen Only allow data when */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItemEnableWhen',
        )]
        public array $enableWhen = [],
        /** @var EnableWhenBehaviorType|null enableBehavior all | any */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/questionnaire-enable-behavior|5.0.0', strength: 'required')]
        public ?EnableWhenBehaviorType $enableBehavior = null,
        /** @var QuestionnaireItemDisabledDisplayType|null disabledDisplay hidden | protected */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/questionnaire-disabled-display|5.0.0', strength: 'required')]
        public ?QuestionnaireItemDisabledDisplayType $disabledDisplay = null,
        /** @var bool|null required Whether the item must be included in data results */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $required = null,
        /** @var bool|null repeats Whether the item may repeat */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $repeats = null,
        /** @var bool|null readOnly Don't allow human editing */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $readOnly = null,
        /** @var int|null maxLength No more than these many characters */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $maxLength = null,
        /** @var QuestionnaireAnswerConstraintType|null answerConstraint optionsOnly | optionsOrType | optionsOrString */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/questionnaire-answer-constraint|5.0.0', strength: 'required')]
        public ?QuestionnaireAnswerConstraintType $answerConstraint = null,
        /** @var CanonicalPrimitive|null answerValueSet ValueSet containing permitted answers */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $answerValueSet = null,
        /** @var array<QuestionnaireItemAnswerOption> answerOption Permitted answer */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItemAnswerOption',
        )]
        public array $answerOption = [],
        /** @var array<QuestionnaireItemInitial> initial Initial value(s) when item is first rendered */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItemInitial',
        )]
        public array $initial = [],
        /** @var array<QuestionnaireItem> item Nested questionnaire items */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItem',
        )]
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
