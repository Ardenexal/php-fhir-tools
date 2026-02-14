<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EnableWhenBehaviorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A particular question, question grouping or display text that is part of the questionnaire.
 */
#[FHIRBackboneElement(parentResource: 'Questionnaire', elementPath: 'Questionnaire.item', fhirVersion: 'R4')]
class QuestionnaireItem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null linkId Unique id for item in questionnaire */
        #[NotBlank]
        public StringPrimitive|string|null $linkId = null,
        /** @var UriPrimitive|null definition ElementDefinition - details for the item */
        public ?UriPrimitive $definition = null,
        /** @var array<Coding> code Corresponding concept for this item in a terminology */
        public array $code = [],
        /** @var StringPrimitive|string|null prefix E.g. "1(a)", "2.5.3" */
        public StringPrimitive|string|null $prefix = null,
        /** @var StringPrimitive|string|null text Primary text for the item */
        public StringPrimitive|string|null $text = null,
        /** @var QuestionnaireItemTypeType|null type group | display | boolean | decimal | integer | date | dateTime + */
        #[NotBlank]
        public ?QuestionnaireItemTypeType $type = null,
        /** @var array<QuestionnaireItemEnableWhen> enableWhen Only allow data when */
        public array $enableWhen = [],
        /** @var EnableWhenBehaviorType|null enableBehavior all | any */
        public ?EnableWhenBehaviorType $enableBehavior = null,
        /** @var bool|null required Whether the item must be included in data results */
        public ?bool $required = null,
        /** @var bool|null repeats Whether the item may repeat */
        public ?bool $repeats = null,
        /** @var bool|null readOnly Don't allow human editing */
        public ?bool $readOnly = null,
        /** @var int|null maxLength No more than this many characters */
        public ?int $maxLength = null,
        /** @var CanonicalPrimitive|null answerValueSet Valueset containing permitted answers */
        public ?CanonicalPrimitive $answerValueSet = null,
        /** @var array<QuestionnaireItemAnswerOption> answerOption Permitted answer */
        public array $answerOption = [],
        /** @var array<QuestionnaireItemInitial> initial Initial value(s) when item is first rendered */
        public array $initial = [],
        /** @var array<QuestionnaireItem> item Nested questionnaire items */
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
