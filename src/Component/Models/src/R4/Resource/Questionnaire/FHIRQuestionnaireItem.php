<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A particular question, question grouping or display text that is part of the questionnaire.
 */
#[FHIRBackboneElement(parentResource: 'Questionnaire', elementPath: 'Questionnaire.item', fhirVersion: 'R4')]
class FHIRQuestionnaireItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null linkId Unique id for item in questionnaire */
        #[NotBlank]
        public FHIRString|string|null $linkId = null,
        /** @var FHIRUri|null definition ElementDefinition - details for the item */
        public ?FHIRUri $definition = null,
        /** @var array<FHIRCoding> code Corresponding concept for this item in a terminology */
        public array $code = [],
        /** @var FHIRString|string|null prefix E.g. "1(a)", "2.5.3" */
        public FHIRString|string|null $prefix = null,
        /** @var FHIRString|string|null text Primary text for the item */
        public FHIRString|string|null $text = null,
        /** @var FHIRQuestionnaireItemTypeType|null type group | display | boolean | decimal | integer | date | dateTime + */
        #[NotBlank]
        public ?FHIRQuestionnaireItemTypeType $type = null,
        /** @var array<FHIRQuestionnaireItemEnableWhen> enableWhen Only allow data when */
        public array $enableWhen = [],
        /** @var FHIREnableWhenBehaviorType|null enableBehavior all | any */
        public ?FHIREnableWhenBehaviorType $enableBehavior = null,
        /** @var FHIRBoolean|null required Whether the item must be included in data results */
        public ?FHIRBoolean $required = null,
        /** @var FHIRBoolean|null repeats Whether the item may repeat */
        public ?FHIRBoolean $repeats = null,
        /** @var FHIRBoolean|null readOnly Don't allow human editing */
        public ?FHIRBoolean $readOnly = null,
        /** @var FHIRInteger|null maxLength No more than this many characters */
        public ?FHIRInteger $maxLength = null,
        /** @var FHIRCanonical|null answerValueSet Valueset containing permitted answers */
        public ?FHIRCanonical $answerValueSet = null,
        /** @var array<FHIRQuestionnaireItemAnswerOption> answerOption Permitted answer */
        public array $answerOption = [],
        /** @var array<FHIRQuestionnaireItemInitial> initial Initial value(s) when item is first rendered */
        public array $initial = [],
        /** @var array<FHIRQuestionnaireItem> item Nested questionnaire items */
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
